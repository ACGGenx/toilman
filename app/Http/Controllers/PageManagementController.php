<?php

namespace App\Http\Controllers;

use App\Models\PageManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PageManagementController extends Controller
{
    public $isView;
    public $isEdit;
    public $isDelete;

    public function __construct()
    {
        // Ensure auth()->user() can be accessed
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            $this->isView = $user->hasRole('admin') || $user->isViewPermission(4);
            $this->isEdit = $user->hasRole('admin') || $user->isEditPermission(4);
            $this->isDelete = $user->hasRole('admin') || $user->isDeletePermission(4);

            return $next($request);
        });
    }
    // Display a listing of the resource
    public function index()
    {
        if ($this->isView) {
            $pages = PageManagement::all();
            return view('pages.index', [
                'pages' => $pages,
                'isEdit' => $this->isEdit,
                'isDelete' => $this->isDelete
            ]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view pages.');
    }

    // Show the form for creating a new resource
    public function create()
    {
        if ($this->isEdit) {
            $page = new PageManagement();  // Empty model for create
            return view('pages.create_edit', compact('page'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to add pages.');
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        if ($this->isEdit) {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'seo_tags' => 'nullable|string',
                'url' => 'required|string|max:255',
                'is_default' => 'boolean'
            ]);

            try {
                PageManagement::create($request->all());
                return redirect()->route('pages.index')
                    ->with('success', 'Page created successfully.');
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] == 1062) {
                    return redirect()->back()->withInput()
                        ->with('error', 'The URL must be unique. A page with the same URL already exists.');
                }
                return redirect()->back()->withInput()
                    ->with('error', 'An error occurred while saving the page. Please try again.');
            }
        }
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to edit pages.');
    }

    // Show the form for editing the specified resource
    public function edit(PageManagement $page)
    {
        if ($this->isEdit) {
            return view('pages.create_edit', compact('page'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit pages.');
    }

    // Update the specified resource in storage
    public function update(Request $request, PageManagement $page)
    {
        if ($this->isEdit) {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'seo_tags' => 'nullable|string',
                'url' => 'required|string|max:255',
                'is_default' => 'boolean'
            ]);

            try {
                $page->update($request->all());
                return redirect()->route('pages.index')
                    ->with('success', 'Page updated successfully.');
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] == 1062) {
                    return redirect()->back()->withInput()
                        ->with('error', 'The URL must be unique. A page with the same URL already exists.');
                }
                return redirect()->back()->withInput()
                    ->with('error', 'An error occurred while updating the page. Please try again.');
            }
        }
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to edit pages.');
    }

    // Remove the specified resource from storage
    public function destroy(PageManagement $page)
    {
        if ($this->isDelete) {
            $page->delete();

            return redirect()->route('pages.index')
                ->with('success', 'Page deleted successfully.');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to delete pages.');
    }

    public function changeStatus(Request $request)
    {
        if ($this->isEdit) {
            $request->validate([
                'page_id' => 'required|exists:page_management,id',
            ]);

            $page = PageManagement::findOrFail($request->input('page_id'));

            // Update the page status
            $page->status = !$page->status;
            $page->save();

            // Return a response (can be JSON if used in an AJAX request)
            return response()->json([
                'success' => true,
                'message' => 'Page status has been updated successfully!',
                'new_status' => $page->status
            ]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to delete pages.');
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

    public function setDefault(Request $request)
    {
        if ($this->isEdit) {
            $request->validate([
                'page_id' => 'required|exists:page_management,id',
            ]);

            $page = PageManagement::findOrFail($request->input('page_id'));
            $page->is_default = true;
            $page->save();

            return response()->json([
                'success' => true,
                'message' => 'Page has been set as default successfully!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to set default page.'
        ], 403);
    }
}
