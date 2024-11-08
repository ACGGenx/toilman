<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public $isView;
    public $isEdit;
    public $isDelete;

    public function __construct()
    {
        // Ensure auth()->user() can be accessed
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            $this->isView = $user->hasRole('admin') || $user->isViewPermission(3);
            $this->isEdit = $user->hasRole('admin') || $user->isEditPermission(3);
            $this->isDelete = $user->hasRole('admin') || $user->isDeletePermission(3);

            return $next($request);
        });
    }
    public function index()
    {
        if ($this->isView) {
            // Eager load the images and category relationships
            $products = Product::whereNotNull('category_id')->get();
            foreach ($products as $product) {
                // Fetch the images related to the product
                $product->images = ProductImage::where('product_id', $product->id)->get();
            }

            $categories = Category::all(); // Fetch categories for filtering or displaying if required
            return view('products.index', ['products' => $products,
            'categories' => $categories,
            'isEdit' => $this->isEdit,
            'isDelete' => $this->isDelete]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view products.');
    }
    public function create()
    {
        if ($this->isEdit) {
            $categories = Category::all();  // Fetch categories for the dropdown
            $products = Product::all();     // For selecting similar products
            $product = [];
            return view('products.create_edit', compact('categories', 'products'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to add products.');
    }
    public function store(Request $request)
    {
        if ($this->isEdit) {
            // dd($request->description);
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'description' => 'required',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif',
                'category_id' => 'required|exists:categories,id',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'custom_box' => 'nullable',
            ]);

            // Generate slug
            $slug = Str::slug($request->name);

            // Ensure unique slug
            $existingSlugCount = Product::where('slug', $slug)->count();
            if ($existingSlugCount > 0) {
                $slug .= '-' . ($existingSlugCount + 1);
            }

            // Create the product
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'custom_box' => $request->custom_box,
                'status' => $request->status ?? true,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
            ]);

            // Handle image upload
            if ($request->hasFile('images')) {
                $isFirstImage = true;
                foreach ($request->file('images') as $image) {
                    // Store image
                    $imagePath = $image->store('products', 'public');

                    // Save image path to the product_images table
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => $isFirstImage ? true : false,
                    ]);
                    $isFirstImage = false;
                }
            }

            // Sync similar products
            if ($request->similar_products) {
                $product->similarProducts()->sync($request->similar_products);
            }

            return redirect()->route('products.index')->with('success', 'Product added successfully');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }


    public function edit(Product $product)
    {
        if ($this->isEdit) {
            $categories = Category::all();
            $products = Product::where('id', '!=', $product->id)->get();
            $product->images = ProductImage::where('product_id', $product->id)
                ->orderBy('is_primary', 'desc')
                ->get();

            return view('products.create_edit', compact('product', 'categories', 'products'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }

    public function update(Request $request, Product $product)
    {
        if ($this->isEdit) {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'description' => 'required',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif',
                'category_id' => 'required|exists:categories,id', // Validate category
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'custom_box' => 'nullable',
            ]);

            $slug = Str::slug($request->name);
            // Update product details
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'custom_box' => $request->custom_box,
                'status' => $request->status ?? true,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
            ]);

            // Handle image upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('products', 'public');

                    $isPrimary = $request->input('primary_image') == $image->getClientOriginalName();

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => $isPrimary,
                    ]);
                }
            }

            if ($request->filled('primary_image_id')) {
                ProductImage::where('product_id', $product->id)
                    ->update(['is_primary' => false]);

                ProductImage::where('id', $request->primary_image_id)
                    ->update(['is_primary' => true]);
            }

            // Sync similar products
            if ($request->similar_products) {
                $product->similarProducts()->sync($request->similar_products);
            }

            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }

    public function destroy(Product $product)
    {
        if ($this->isDelete) {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to delete products.');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['status' => !$product->status]);
        return redirect()->route('products.index');
    }

    public function checkSlug(Request $request)
    {
        if ($this->isEdit) {
            $slug = $request->get('slug');
            $exists = Product::where('slug', $slug)->exists();

            return response()->json(['exists' => $exists]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }

    public function deleteImage(Request $request)
    {
        if ($this->isDelete) {
            $image = ProductImage::findOrFail($request->image_id);
            // Delete the image file from storage
            if (\Storage::exists('public/' . $image->image_path)) {
                \Storage::delete('public/' . $image->image_path);
            }
            // Delete the image record from the database
            $image->delete();

            return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to delete products.');
    }
    public function setAsPrimary(Request $request)
    {
        if ($this->isEdit) {
            // Validate the incoming request to ensure 'image_id' is present
            $request->validate([
                'image_id' => 'required|exists:product_images,id',
            ]);

            // Find the image that needs to be set as primary
            $image = ProductImage::findOrFail($request->input('image_id'));

            // Get the product ID from the image (since the image belongs to a product)
            $productId = $image->product_id;

            // Mark all other images of this product as non-primary
            ProductImage::where('product_id', $productId)->update(['is_primary' => false]);

            // Set the selected image as the primary image
            $image->is_primary = true;
            $image->save();

            return response()->json(['success' => true, 'message' => 'Primary image has been updated.']);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }
    public function changeStatus(Request $request)
    {
        if ($this->isEdit) {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $product = Product::findOrFail($request->input('product_id'));

            // Update the product status
            $product->status = !$product->status;
            $product->save();

            // Return a response (can be JSON if used in an AJAX request)
            return response()->json([
                'success' => true,
                'message' => 'Product status has been updated successfully!',
                'new_status' => $product->status
            ]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }

    public function show($slug)
    {
        if ($this->isView) {
            try {
                // First get the product
                $product = Product::with(['category', 'similarProducts'])
                    ->where('slug', $slug)
                    ->where('status', 1)
                    ->firstOrFail();
    
                // Fetch images separately and order by is_primary
                $product->images = ProductImage::where('product_id', $product->id)
                    ->orderBy('is_primary', 'desc')
                    ->get();
    
                // Get related products
                $relatedProducts = Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->where('status', 1)
                    ->take(3)
                    ->get();
    
                // Fetch images for related products
                foreach ($relatedProducts as $relatedProduct) {
                    $relatedProduct->images = ProductImage::where('product_id', $relatedProduct->id)
                        ->orderBy('is_primary', 'desc')
                        ->get();
                }
    
                // Debug information
                \Log::info('Product Images:', [
                    'product_id' => $product->id,
                    'image_count' => $product->images->count(),
                    'images' => $product->images->pluck('image_path')->toArray()
                ]);
    
                return view('product.details', compact('product', 'relatedProducts'));
    
            } catch (\Exception $e) {
                \Log::error('Error in show method: ' . $e->getMessage());
                return back()->with('error', 'Unable to display product details.');
            }
        }
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to view products.');
        // if ($this->isView) {
        //     $product = Product::where('slug', $slug)
        //         ->where('status', 1)  // Assuming 1 means active
        //         ->with('images')  // Assuming you have an images relationship
        //         ->firstOrFail();

        //     return view('product.view', ['product'=> $product,
        //     'isEdit' => $this->isEdit,
        //     'isDelete' => $this->isDelete
        // ]);
        // }
        // return redirect()->route('dashboard')->with('error', 'You do not have permission to view products.');
    }
}
