<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public $isView;
    public $isEdit;
    public $isDelete;

    public function __construct()
    {
        // Ensure auth()->user() can be accessed
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            $this->isView = $user->hasRole('admin') || $user->isViewPermission(5);
            $this->isEdit = $user->hasRole('admin') || $user->isEditPermission(5);
            $this->isDelete = $user->hasRole('admin') || $user->isDeletePermission(5);

            return $next($request);
        });
    }
    public function index()
    {
        if ($this->isView) {
            $sliders = Slider::with(['images' => function ($query) {
                $query->where('is_active', 1)->orderBy('order', 'asc');  // Order images by 'order' field in ascending order
            }])->get();
            return view('sliders.index', [
                'sliders' => $sliders,
                'isEdit' => $this->isEdit,
                'isDelete' => $this->isDelete,
                'isView' => $this->isView,
            ]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view sliders.');
    }

    public function create()
    {
        if ($this->isEdit) {
            return view('sliders.create_edit');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit sliders.');
    }

    public function store(Request $request)
    {
        if ($this->isEdit) {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'images.*' => 'required|image',
            ]);

            $slider = Slider::create($request->only('name', 'description'));

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('slider_images', 'public');
                    // Save image with order starting from 1
                    SliderImage::create([
                        'slider_id' => $slider->id,
                        'image_path' => $path,
                        'order' => $index + 1,
                    ]);
                }
            }

            return redirect()->route('sliders.index')->with('success', 'Slider created successfully.');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit sliders.');
    }

    public function edit(Slider $slider)
    {
        if ($this->isEdit) {
            $slider->load(['images' => function ($query) {
                $query->orderBy('order', 'asc');  // Order images by the 'order' field in ascending order
            }]);
            return view('sliders.create_edit', compact('slider'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit sliders.');
    }

    public function update(Request $request, Slider $slider)
    {
        if ($this->isEdit) {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'images.*' => 'image',
            ]);

            $slider->update($request->only('name', 'description'));

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('slider_images', 'public');
                    // Save new images with sequential order after existing ones
                    SliderImage::create([
                        'slider_id' => $slider->id,
                        'image_path' => $path,
                        'order' => SliderImage::where('slider_id', $slider->id)->max('order') + 1,
                    ]);
                }
            }

            return redirect()->route('sliders.index')->with('success', 'Slider updated successfully.');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit sliders.');
    }

    public function destroy(Slider $slider)
    {
        if ($this->isDelete) {
            // Delete associated images
            foreach ($slider->images as $image) {
                Storage::delete('public/' . $image->image_path);  // Remove image from storage
                $image->delete();  // Delete image record
            }

            // Delete the slider
            $slider->delete();

            return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully.');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to delete sliders.');
    }

    // AJAX: Update image order
    public function updateImageOrder(Request $request, $sliderId)
    {
        if ($this->isEdit) {
            $order = $request->input('order');

            // Loop through the new order and update the positions
            foreach ($order as $item) {
                $image = SliderImage::find($item['id']);
                if ($image) {
                    $image->update(['order' => $item['position']]);
                }
            }

            return response()->json(['status' => 'success']);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit sliders.');
    }

    // AJAX: Remove image
    public function removeImage(Request $request, $sliderId)
    {
        if ($this->isDelete) {
            $imageId = $request->input('image_id');

            // Find the image
            $image = SliderImage::find($imageId);
            if ($image) {
                // Delete the image file from storage
                Storage::delete('public/' . $image->image_path);

                // Delete the image record from the database
                $image->delete();

                return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'error'], 404);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit sliders.');
    }

    public function uploadImages(Request $request, Slider $slider)
    {
        if ($this->isEdit) {
            $request->validate([
                'images.*' => 'image', // Validate each file as an image
            ]);

            $uploadedImages = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('slider_images', 'public');
                    $sliderImage = SliderImage::create([
                        'slider_id' => $slider->id,
                        'image_path' => $path,
                        'order' => SliderImage::where('slider_id', $slider->id)->max('order') + 1,
                    ]);
                    $uploadedImages[] = $sliderImage;
                }
            }

            return response()->json([
                'images' => $uploadedImages
            ]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit sliders.');
    }
    public function updateImageStatus(Request $request, $sliderId)
    {
        if ($this->isEdit) {
            $imageId = $request->input('image_id');
            $isActive = $request->input('is_active');

            $image = SliderImage::find($imageId);
            if ($image) {
                $image->update(['is_active' => $isActive ? 1 : 0]);
                return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'error'], 404);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit sliders.');
    }

    public function updateImageUrl(Request $request, $sliderId)
    {
        if ($this->isEdit) {
            $imageId = $request->input('image_id');
            $url = $request->input('url');

            $image = SliderImage::find($imageId);
            if ($image) {
                $image->update(['url' => $url]);
                return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'error'], 404);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit sliders.');
    }
}
