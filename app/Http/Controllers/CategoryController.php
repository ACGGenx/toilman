<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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
            $categories = Category::with('parentCategory')->get();

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
            $categories = Category::all();
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
}
