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
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Image"
                                        class="img-fluid" style="width: 100px; height: 100px;border-radius: 5%;">
                                    <div class="image-controls mt-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input toggle-active"
                                                id="active_{{ $image->id }}"
                                                data-id="{{ $image->id }}"
                                                {{ $image->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active_{{ $image->id }}">Active</label>
                                        </div>
                                        <div class="mt-1">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control form-control-sm image-url" style="color:blue"
                                                    placeholder="Enter URL"
                                                    value="{{ $image->url }}"
                                                    data-id="{{ $image->id }}">
                                                <button class="btn btn-outline-secondary save-url-btn d-flex align-items-center save-icon" type="button" data-id="{{ $image->id }}">
                                                    <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </button>
                                                <span class="loader-icon d-none">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger delete-image-btn remove-image"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        aria-label="Remove" data-bs-original-title="Remove"
                                        style="position: absolute; top: 10px; left: 10px;height: 18px;width: 18px;line-height: 1px;padding: 0px;"
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

    // Handle active toggle
    $('.toggle-active').on('change', function() {
        let imageId = $(this).data('id');
        let isActive = $(this).prop('checked');

        $.ajax({
            url: '{{ route("sliders.updateImageStatus", $slider->id) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                image_id: imageId,
                is_active: isActive ? 1 : 0
            },
            success: function(response) {
                console.log("Image status updated!");
                // Swal.fire({
                //     title: "Success",
                //     text: "Image status updated!",
                //     icon: "success",
                //     confirmButtonText: 'OK'
                // });
            },
            error: function(error) {
                Swal.fire({
                    title: "Error",
                    text: "Failed to update image status!",
                    icon: "error",
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Handle URL update (with debounce)
    let urlUpdateTimeout;
    $('.save-url-btn').on('click', function() {
        let imageId = $(this).data('id');
        let url = $(this).closest('.input-group').find('.image-url').val();
        let $button = $(this);
        let $saveIcon = $button.find('.save-icon');
        let $loaderIcon = $button.find('.loader-icon');

        // Toggle icons and disable button
        $button.prop('disabled', true);
        $saveIcon.addClass('d-none');
        $loaderIcon.removeClass('d-none');

        $.ajax({
            url: '{{ route("sliders.updateImageUrl", $slider->id) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                image_id: imageId,
                url: url
            },
            success: function(response) {
                // Reset button state
                $button.prop('disabled', false);
                $saveIcon.removeClass('d-none');
                $loaderIcon.addClass('d-none');

                Swal.fire({
                    title: "Success",
                    text: "URL updated!",
                    icon: "success",
                    confirmButtonText: 'OK'
                });
            },
            error: function(error) {
                // Reset button state
                $button.prop('disabled', false);
                $saveIcon.removeClass('d-none');
                $loaderIcon.addClass('d-none');

                Swal.fire({
                    title: "Error",
                    text: "Failed to update URL!",
                    icon: "error",
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>
@endif
