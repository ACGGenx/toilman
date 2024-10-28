<x-app-layout>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">{{ isset($slider) ? 'Edit Slider' : 'Add Slider' }}</h4>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Form for creating/updating the slider -->
                    <form id="slider-form" action="{{ isset($slider) ? route('sliders.update', $slider->id) : route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($slider))
                        @method('PUT')
                        @endif
                        <div class="form-group">
                            <label for="name">Slider Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $slider->name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ $slider->description ?? '' }}</textarea>
                        </div>

                        <!-- Upload new images -->
                        <div class="form-group">
                            <label for="images">Upload Images</label>
                            <input type="file" name="images[]" id="images" multiple class="form-control">
                        </div>

                        <!-- Only display the sortable images section for the edit page -->
                        @if(isset($slider) && $slider->images && $slider->images->count() > 0)
                        <div class="form-group mt-3">
                            <label>Existing Images (Drag to reorder)</label>
                            <ul id="sortable-images" class="list-unstyled d-flex flex-wrap">
                                @foreach ($slider->images as $image)
                                <li class="ui-state-default p-2 img-wrapper" data-id="{{ $image->id }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Image" class="img-fluid" style="width: 100px; height: 100px;border-radius: 5%;">
                                    <button type="button" class="btn btn-danger delete-image-btn remove-image" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Remove" data-bs-original-title="Remove"
                                        style="position: absolute; top: 0; right: 0;height: 18px;width: 18px;line-height: 1px;padding: 0px;"
                                        data-id="{{ $image->id }}">
                                        &times;
                                    </button>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-success">{{ isset($slider) ? 'Update' : 'Create' }} Slider</button>
                        <a href="{{ route('sliders.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Add SortableJS and AJAX script for uploading new images and reordering -->
@if(isset($slider))
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Handle image reordering with SortableJS
        var sortable = new Sortable(document.getElementById('sortable-images'), {
            animation: 150,
            onEnd: function(evt) {
                let order = [];
                $('#sortable-images li').each(function(index, element) {
                    order.push({
                        id: $(element).data('id'),
                        position: index + 1 // Save new position
                    });
                });

                // Send new order via AJAX
                $.ajax({
                    url: '{{ route("sliders.updateImageOrder", $slider->id) }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Success",
                            text: "Image order updated!",
                            icon: "success",
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "Error",
                            text: "Failed to update image order!",
                            icon: "error",
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });

        // Handle image removal
        $('.remove-image').on('click', function() {
            let imageId = $(this).data('id');
            let parentElement = $(this).closest('li');

            // Send AJAX request to delete the image
            $.ajax({
                url: '{{ route("sliders.removeImage", $slider->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    image_id: imageId
                },
                success: function(response) {
                    parentElement.remove();
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error",
                        text: "Failed to remove image!",
                        icon: "error",
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle uploading new images
        $('#upload-images-btn').on('click', function(e) {
            e.preventDefault();

            // Create a FormData object with the selected files
            var formData = new FormData();
            var files = $('#images')[0].files;
            for (var i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }
            formData.append('_token', '{{ csrf_token() }}');

            // Send AJAX request to upload the images
            $.ajax({
                url: '{{ route("sliders.uploadImages", $slider->id) }}',
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    // Dynamically update the image list with newly uploaded images
                    response.images.forEach(function(image) {
                        $('#sortable-images').append(
                            '<li class="ui-state-default p-2 img-wrapper" data-id="' + image.id + '">' +
                            '<img src="/storage/' + image.image_path + '" alt="Image" class="img-fluid" style="width: 100px; height: 100px;border-radius: 5%;">' +
                            '<button type="button" class="btn btn-danger delete-image-btn remove-image" data-id="' + image.id + '" style="position: absolute; top: 0; right: 0;height: 18px;width: 18px;">&times;</button>' +
                            '</li>'
                        );
                    });
                    Swal.fire({
                        title: "Success",
                        text: "Images uploaded successfully!",
                        icon: "success",
                        confirmButtonText: 'OK'
                    });
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error",
                        text: "Failed to upload images!",
                        icon: "error",
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endif
