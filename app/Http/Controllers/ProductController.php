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
            $products = Product::with(['categories', 'images' => function ($query) {
                $query->orderBy('is_primary', 'desc');
            }])
                ->latest()
                ->get();
            foreach ($products as $product) {
                // Fetch the images related to the product
                $product->images = ProductImage::where('product_id', $product->id)->get();
            }
            $categories = Category::all();

            return view('products.index', [
                'products' => $products,
                'categories' => $categories,
                'isEdit' => $this->isEdit,
                'isDelete' => $this->isDelete
            ]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view products.');
    }

    public function create()
    {
        if ($this->isEdit) {
            $categories = Category::all();
            $products = Product::all();
            return view('products.create_edit', compact('categories', 'products'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to add products.');
    }

    public function store(Request $request)
    {
        if ($this->isEdit) {
            $validatedData = $request->validate($this->getValidationRules($request));

            // Generate slug
            $slug = Str::slug($request->name);

            // Ensure unique slug
            $existingSlugCount = Product::where('slug', $slug)->count();
            if ($existingSlugCount > 0) {
                $slug .= '-' . ($existingSlugCount + 1);
            }

            // Create the product
            $product = Product::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'slug' => $validatedData['slug'],
                'meta_title' => $validatedData['meta_title'],
                'meta_description' => $validatedData['meta_description'],
                'custom_box' => $validatedData['custom_box'],
                'status' => $request->status ?? true,
                'price' => $validatedData['price'],
                'sale_price' => $validatedData['sale_price'],
            ]);

            // Sync categories
            $product->categories()->sync($request->category_id);

            // Handle image upload
            if ($request->hasFile('images')) {
                $isFirstImage = true;
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => $isFirstImage,
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
            $product->load('categories'); // Make sure categories are loaded

            $product->images = ProductImage::where('product_id', $product->id)
                ->orderBy('is_primary', 'desc')
                ->get();

            // Get array of selected category IDs
            $selectedCategories = $product->categories->pluck('id')->toArray();

            return view('products.create_edit', compact('product', 'categories', 'products', 'selectedCategories'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }

    public function update(Request $request, Product $product)
    {
        if ($this->isEdit) {
            try{
            DB::beginTransaction();
            $validatedData = $request->validate($this->getValidationRules($request, $product->id));
            $slug = Str::slug($request->name);

            // Update product details
            $product->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'slug' => Str::slug($request->name),
                'meta_title' => $validatedData['meta_title'],
                'meta_description' => $validatedData['meta_description'],
                'custom_box' => $validatedData['custom_box'],
                'status' => $request->status ?? true,
                'price' => $validatedData['price'],
                'sale_price' => $validatedData['sale_price'],
            ]);


            // Sync categories
            $product->categories()->sync($request->category_id);

            // Handle image upload
            if ($request->hasFile('images')) {
                $isFirstImage = !$product->images()->exists();
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => $isFirstImage,
                    ]);
                    $isFirstImage = false;
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
            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product update error: ' . $e->getMessage());
            return back()->with('error', 'Error updating product: ' . $e->getMessage())
                        ->withInput();
        }
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }

    public function destroy(Product $product)
    {
        if ($this->isDelete) {
            $product->categories()->detach(); // Remove category relationships
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to delete products.');
    }

    public function toggleStatus(Product $product)
    {
        if ($this->isEdit) {
            $product->update(['status' => !$product->status]);
            return redirect()->route('products.index');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }

    public function checkSlug(Request $request)
    {
        if ($this->isEdit) {
            $slug = $request->get('slug');
            $productId = $request->get('product_id', 0);

            $exists = Product::where('slug', $slug)
                ->when($productId, function ($query) use ($productId) {
                    return $query->where('id', '!=', $productId);
                })
                ->exists();

            return response()->json(['exists' => $exists]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit products.');
    }

    public function deleteImage(Request $request)
    {
        if ($this->isDelete) {
            $image = ProductImage::findOrFail($request->image_id);
            if (Storage::exists('public/' . $image->image_path)) {
                Storage::delete('public/' . $image->image_path);
            }
            $image->delete();

            return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to delete products.');
    }

    public function setAsPrimary(Request $request)
    {
        if ($this->isEdit) {
            $request->validate([
                'image_id' => 'required|exists:product_images,id',
            ]);

            $image = ProductImage::findOrFail($request->input('image_id'));
            ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);

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
            $product->status = !$product->status;
            $product->save();

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
        try {
            // Get product with its categories and images
            $product = Product::with([
                'categories',
                'images' => function ($query) {
                    $query->orderBy('is_primary', 'desc');
                },
                'similarProducts' => function ($query) {
                    $query->with(['images' => function ($q) {
                        $q->orderBy('is_primary', 'desc');
                    }, 'categories'])
                        ->where('status', 1);
                }
            ])
                ->where('slug', $slug)
                ->where('status', 1)
                ->firstOrFail();
            // Initialize empty collections if relationships are null
            if (!$product->categories) {
                $product->setRelation('categories', collect([]));
            }
            if (!$product->images) {
                $product->images = ProductImage::where('product_id', $product->id)->get();
            }
            if (!$product->similarProducts) {
                $product->setRelation('similarProducts', collect([]));
            } else {
                foreach ($product->similarProducts as $sproduct) {
                    $sproduct->images = ProductImage::where('product_id', $sproduct->id)->orderBy('is_primary', 'desc')->first();
                }
            }
            // dd($product->similarProducts);

            // Get related products
            $relatedProducts = collect([]);
            $relatedProducts = $product->similarProducts;
            // dd($relatedProducts);
            // if ($product->categories->isNotEmpty()) {
            //     $relatedProducts = Product::with(['images', 'categories'])
            //         ->where('products.id', '!=', $product->id)
            //         ->where('status', 1)
            //         ->whereHas('categories', function ($query) use ($product) {
            //             $query->whereIn('categories.id', $product->categories->pluck('id')); // Specify 'categories.id'
            //         })
            //         ->take(3)
            //         ->get();
            // }

            return view('product.details', compact('product', 'relatedProducts'));
        } catch (\Exception $e) {
            \Log::error('Error in show method: ' . $e->getMessage());
            return back()->with('error', 'Unable to display product details.');
        }
    }

    public function uploadProductImage(Request $request)
    {
        if ($this->isEdit) {
            try {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'product_id' => 'required|exists:products,id'
                ]);
    
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $imagePath = $file->store('products', 'public');
                    
                    $imgDetail = ProductImage::create([
                        'product_id' => $request->product_id,
                        'image_path' => $imagePath,
                        'is_primary' => false,
                    ]);
    
                    return response()->json([
                        'success' => true,
                        'imageUrl' => Storage::url($imagePath),
                        'imageId' => $imgDetail->id
                    ]);
                }
    
                return response()->json([
                    'success' => false, 
                    'message' => 'Image upload failed'
                ], 400);
    
            } catch (\Exception $e) {
                \Log::error('Upload product image error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error uploading image: ' . $e->getMessage()
                ], 500);
            }
        }
    
        return response()->json([
            'success' => false, 
            'message' => 'Permission denied'
        ], 403);
    }

    public function uploadImage(Request $request)
    {
        if ($this->isEdit) {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('public/images');
                $url = Storage::url($path);

                return response()->json(['success' => true, 'imageUrl' => $url]);
            }
            return response()->json(['success' => false]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to upload images.');
    }

    private function getValidationRules(Request $request, $productId = null)
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($productId) {
                    $query = Product::where('slug', $value);
                    if ($productId) {
                        $query->where('id', '!=', $productId);
                    }
                    if ($query->exists()) {
                        $fail('This URL (slug) is already in use.');
                    }
                },
            ],
            'price' => 'required|numeric|min:0',
            'sale_price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value !== null && $value <= 0) {
                        $fail('Sale price must be greater than 0.');
                    }
                    if ($value !== null && $request->price && $value >= $request->price) {
                        $fail('Sale price must be less than the regular price.');
                    }
                },
            ],
            'description' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'custom_box' => 'nullable',
        ];
    }
}
