<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class CategoryController extends Controller
{
    public $isView;
    public $isEdit;
    public $isDelete;

    public function __construct()
    {
        // Ensure auth()->user() can be accessed
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            $this->isView = $user->hasRole('admin') || $user->isViewPermission(2);
            $this->isEdit = $user->hasRole('admin') || $user->isEditPermission(2);
            $this->isDelete = $user->hasRole('admin') || $user->isDeletePermission(2);

            return $next($request);
        });
    }
    public function index()
    {
        if ($this->isView) {
            // $categories = Category::with('parentCategory')->get();

            $categories = $this->getCategoryHierarchy();
            // dd($categories);

            // Pass controller properties to the view
            return view('categories.index', [
                'categories' => $categories,
                'isEdit' => $this->isEdit,
                'isDelete' => $this->isDelete
            ]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view categories.');
    }

    public function store(Request $request)
    {
        if ($this->isEdit) {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_category_id' => 'nullable|exists:categories,id',
                'slug' => 'required|string|unique:categories,slug',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('category_images', 'public');
            }

            Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id,
                'slug' => $request->slug,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'image' => $imagePath,
            ]);

            return redirect()->route('categories.index')->with('success', 'Category added successfully');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to add categories.');
    }

    public function edit($id)
    {
        if ($this->isEdit) {
            $category = Category::findOrFail($id);
            // $categories = Category::all();
            $categories = $this->getCategoryHierarchy();
            dd($categories);
            return view('categories.edit', compact('category', 'categories'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit categories.');
    }

    public function update(Request $request, $id)
    {
        if ($this->isEdit) {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_category_id' => 'nullable|exists:categories,id',
                'slug' => 'required|string|unique:categories,slug,' . $id,
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $category = Category::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $imagePath = $request->file('image')->store('category_images', 'public');
            } else {
                $imagePath = $category->image;
            }

            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id,
                'slug' => $request->slug,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'image' => $imagePath,
            ]);

            return redirect()->route('categories.index')->with('success', 'Category updated successfully');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit categories.');
    }

    public function checkSlug(Request $request)
    {
        if ($this->isEdit) {
            $slug = $request->get('slug');
            $exists = Category::where('slug', $slug)->exists();

            return response()->json(['exists' => $exists]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit categories.');
    }

    public function destroy($id)
    {
        if ($this->isDelete) {
            Category::findOrFail($id)->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to delete categories.');
    }

    public function changeStatus(Request $request)
    {
        if ($this->isEdit) {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
            ]);

            $category = Category::findOrFail($request->input('category_id'));

            // Update the category status
            $category->status = !$category->status;
            $category->save();

            // Return a response (can be JSON if used in an AJAX request)
            return response()->json([
                'success' => true,
                'message' => 'category status has been updated successfully!',
                'new_status' => $category->status
            ]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit categories.');
    }

    protected function getCategoryHierarchy($parentId = null, $prefix = '')
    {
        $categories = Category::where('parent_category_id', $parentId)
            ->orderBy('name')
            ->get();

        $hierarchy = [];

        foreach ($categories as $category) {
            $category->name = $prefix . $category->name;
            $hierarchy[] = $category;
            $hierarchy = array_merge($hierarchy, $this->getCategoryHierarchy($category->id, $prefix));
        }

        return $hierarchy;
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('public/images'); // Save to storage/app/public/images
            $url = Storage::url($path); // Get URL for the stored image

            return response()->json(['success' => true, 'imageUrl' => $url]);
        }

        return response()->json(['success' => false]);
    }
    // Category-product 
    public function viewAllProducts()
    {
        if ($this->isView) {
            try {
                // Get all active categories for the sidebar
                $categories = Category::where('status', 1)->get();
    
                // Get products with their primary images using eager loading
                $products = Product::with(['images' => function($query) {
                    $query->where(function($q) {
                        $q->where('is_primary', true)
                          ->orWhereIn('id', function($subQuery) {
                              $subQuery->selectRaw('MIN(id)')
                                     ->from('product_images')
                                     ->groupBy('product_id');
                          });
                    });
                }])
                ->where('status', 1)
                ->paginate(9);

                // Ensure each product has an images collection, even if empty
                $products->each(function($product) {
                    if (!$product->relationLoaded('images')) {
                        $product->setRelation('images', collect());
                    }
                });
    
                return view('categories.all-products', compact('categories', 'products'));
    
            } catch (\Exception $e) {
                \Log::error('Error viewing all products: ' . $e->getMessage());
                return back()->with('error', 'Unable to display products.');
            }
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view products.');
    }

    public function viewCategoryProducts($slug)
    {
        if ($this->isView) {
            try {
                // Get the current category
                $category = Category::where('slug', $slug)
                    ->where('status', 1)
                    ->firstOrFail();
    
                // Get products for this category with their primary images
                $products = Product::with(['images' => function($query) {
                    $query->where(function($q) {
                        $q->where('is_primary', true)
                          ->orWhereIn('id', function($subQuery) {
                              $subQuery->selectRaw('MIN(id)')
                                     ->from('product_images')
                                     ->groupBy('product_id');
                          });
                    });
                }])
                ->whereHas('categories', function($query) use ($category) {
                    $query->where('categories.id', $category->id);
                })
                ->where('status', 1)
                ->paginate(12);

                // Ensure each product has an images collection, even if empty
                $products->each(function($product) {
                    if (!$product->relationLoaded('images')) {
                        $product->setRelation('images', collect());
                    }
                });
    
                // Get all active categories for sidebar
                $categories = Category::where('status', 1)
                    ->orderBy('name')
                    ->get();
    
                return view('categories.products', compact('category', 'categories', 'products'));
    
            } catch (\Exception $e) {
                \Log::error('Error in viewCategoryProducts: ' . $e->getMessage());
                return back()->with('error', 'Unable to display category products.');
            }
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view products.');
    }

    public function filterProducts(Request $request)
    {
        if ($this->isView) {
            try {
                $query = Product::with(['images' => function($query) {
                    $query->where(function($q) {
                        $q->where('is_primary', true)
                          ->orWhereIn('id', function($subQuery) {
                              $subQuery->selectRaw('MIN(id)')
                                     ->from('product_images')
                                     ->groupBy('product_id');
                          });
                    });
                }])
                ->where('status', 1);
    
                if ($request->filled('categories')) {
                    $query->whereHas('categories', function($query) use ($request) {
                        $query->whereIn('categories.id', $request->categories);
                    });
                }
    
                $products = $query->paginate(9);

                // Ensure each product has an images collection, even if empty
                $products->each(function($product) {
                    if (!$product->relationLoaded('images')) {
                        $product->setRelation('images', collect());
                    }
                });
    
                return response()->json([
                    'success' => true,
                    'html' => view('categories.partials.product-grid', compact('products'))->render(),
                    'pagination' => $products->links()->render()
                ]);
    
            } catch (\Exception $e) {
                \Log::error('Error filtering products: ' . $e->getMessage());
                return response()->json(['error' => 'Unable to filter products'], 500);
            }
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}


