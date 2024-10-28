<x-app-layout>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">{{ isset($product) ? 'Edit Product' : 'Add Product' }}</h4>
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

                    <form id="product-form-element" method="POST" action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($product))
                        @method('PUT')
                        @endif

                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label for="name">Product Name</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $product->name ?? old('name') }}" required>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="description">Description</label>
                                <div id="description-editor" style="height: 200px;">{!! $product->description ?? old('description') !!}</div>
                                <input type="hidden" name="description" id="description">
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-12 col-md-6">
                                <div class="form-group col-12">
                                    <label for="slug">Product URL (SEO-friendly)</label>
                                    <input type="text" class="form-control" name="slug" id="slug" value="{{ $product->slug ?? old('slug') }}" required>
                                    <span id="url-error-message" style="color: red; display: none;">This URL already exists!</span>
                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <div class="form-group">
                                    <label for="similar_products">Similar Products</label>
                                    <select name="similar_products[]" id="similar_products" class="form-control" multiple>
                                        @foreach($products as $similarProduct)
                                        <option value="{{ $similarProduct->id }}"
                                            @if(isset($product) && $product->similarProducts->contains($similarProduct->id)) selected @endif>
                                            {{ $similarProduct->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Multiple Image Upload Field -->
                        <div class="form-group">
                            <label for="images">Product Images</label>
                            <input type="file" class="form-control" name="images[]" id="images" multiple accept="image/*">
                            <div id="image-preview" class="mt-3"></div> <!-- Image preview container -->
                            <div class="existing-images">
                                @if(isset($product) && $product->images->isNotEmpty())
                                <h6>Existing Images</h6>
                                @foreach($product->images as $image)
                                <div class="image-wrapper {{$image->is_primary ? 'primary' : ''}}" style="position: relative; display: inline-block; margin: 10px;" id="img-{{$image->id}}">
                                    @if(!$image->is_primary)
                                    <input type="checkbox" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Set as Primary" data-bs-original-title="Set as Primary" class="form-check-input" style="position: absolute; top: -4px;height: 18px;width: 18px;line-height: 1px;padding: 0px;"
                                        onclick="seAsPrimary({{ $image->id }})" />
                                    @endif
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image" style="border-radius: 5%;width: 80px; height: 80px; object-fit: cover;">
                                    @if(!$image->is_primary)
                                    <button type="button" class="btn btn-danger delete-image-btn" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Remove" data-bs-original-title="Remove"
                                        style="position: absolute; top: 0; right: 0;height: 18px;width: 18px;line-height: 1px;padding: 0px;"
                                        onclick="deleteImage({{ $image->id }})">
                                        &times;
                                    </button>
                                    @endif
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label for="price">Price</label>
                                <input type="text" class="form-control" name="price" id="price" value="{{ $product->price ?? old('price') }}" required>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="sale_price">Sale Price</label>
                                <input type="text" class="form-control" name="sale_price" id="sale_price" value="{{ $product->sale_price ?? old('sale_price') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control" name="meta_title" id="meta_title" value="{{ $product->meta_title ?? old('meta_title') }}">
                        </div>
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control" name="meta_description" id="meta_description">{{ $product->meta_description ?? old('meta_description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="custom_box">Custom Box (HTML/Text)</label>
                            <textarea class="form-control" name="custom_box" id="custom_box">{{ $product->custom_box ?? old('custom_box') }}</textarea>
                        </div>
                        @if(isset($product))
                        <div class="form-group">
                            <label for="status">Status</label>
                            @if(isset($product) && $product->status == 1)
                            <span class="badge bg-primary">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                        @endif
                        <button type="button" class="btn btn-success" id="submit-form">{{ isset($product) ? 'Update' : 'Create' }} Product</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Quill JS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        let isValidSlug = true;
        // Initialize Quill editor
        var quill = new Quill('#description-editor', {
            theme: 'snow'
        });
        setTimeout(() => {
            $('#similar_products').select2({
                placeholder: 'Select Similar Products',
                allowClear: true,
                closeOnSelect: false
            });
        }, 2500);
        document.getElementById('submit-form').addEventListener('click', function(event) {

            document.querySelector('#description').value = quill.root.innerHTML;
            if (!isValidSlug) {
                event.preventDefault();
                Swal.fire({
                    title: 'Error',
                    text: "Please update slug, given input slug already exist!",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Submit the form programmatically
            document.getElementById('product-form-element').submit();
        });

        let selectedFiles = [];
        // Handle multiple image preview
        document.getElementById('images').addEventListener('change', function(event) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = ''; // Clear previous previews

            const files = Array.from(event.target.files);
            files.forEach((file) => {
                selectedFiles.push(file);

                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgWrapper = document.createElement('div');
                    imgWrapper.style.position = 'relative';
                    imgWrapper.style.display = 'inline-block';
                    imgWrapper.style.margin = '10px';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.width = 80;
                    img.height = 80;
                    img.style.borderRadius = '50%';
                    img.style.objectFit = 'cover';

                    // Create a delete button
                    const deleteBtn = document.createElement('button');

                    deleteBtn.setAttribute('data-bs-placement', 'top');
                    deleteBtn.setAttribute('aria-label', 'Remove from list');
                    deleteBtn.setAttribute('data-bs-original-title', 'Remove from list');
                    deleteBtn.setAttribute('data-bs-toggle', 'tooltip');
                    deleteBtn.innerHTML = '&minus;';
                    deleteBtn.className = 'btn btn-danger';
                    deleteBtn.style.position = 'absolute';
                    deleteBtn.style.top = '0';
                    deleteBtn.style.right = '0';
                    deleteBtn.style.height = '18px';
                    deleteBtn.style.width = '18px';
                    deleteBtn.style.lineHeight = '0.3px';
                    deleteBtn.style.padding = '0px';
                    deleteBtn.onclick = function() {
                        // Remove the correct file from the selectedFiles array
                        const fileIndex = selectedFiles.indexOf(file); // Find the correct file in the array
                        if (fileIndex > -1) {
                            selectedFiles.splice(fileIndex, 1); // Remove file from the array
                        }
                        imgWrapper.remove(); // Remove the preview element
                        updateFileInput(); // Update the file input with the remaining files
                    };

                    imgWrapper.appendChild(img);
                    imgWrapper.appendChild(deleteBtn);
                    preview.appendChild(imgWrapper);
                };

                reader.readAsDataURL(file);
            });

            updateFileInput();
        });

        function updateFileInput() {
            const fileInput = document.getElementById('images');
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files; // Update the input file list
        }

        document.getElementById('name').addEventListener('input', function() {
            let name = document.getElementById('name').value;
            let description = quill.root.innerText;
            let slug = generateSlug(name + ' ' + description);
            document.getElementById('slug').value = slug;
        });

        // // AJAX check if the URL (slug) is unique
        document.getElementById('slug').addEventListener('input', function() {
            let slug = document.getElementById('slug').value;
            checkSlugUnique(slug);
        });
        // Listen for input on the 'slug' and 'name' fields
        document.getElementById('name').addEventListener('input', function() {
            updateSlug();
        });

        document.getElementById('description-editor').addEventListener('input', function() {
            updateSlug();
        });

        // Function to generate slug based on 'name' and 'description'
        function updateSlug() {
            let name = document.getElementById('name').value;
            let description = quill.root.innerText; // Get the description from Quill editor
            let slug = generateSlug(name + ' ' + description);

            document.getElementById('slug').value = slug;

            checkSlugUnique(slug);
        }

        function generateSlug(text) {
            return text.trim().toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        }

        function checkSlugUnique(slug) {
            fetch(`/check-slug?slug=${slug}`)
                .then(response => response.json())
                .then(data => {
                    isValidSlug = !data.exists
                    if (data.exists) {
                        document.getElementById('url-error-message').style.display = 'inline';
                    } else {
                        document.getElementById('url-error-message').style.display = 'none';
                    }
                });
        }

        function deleteImage(imageId) {
            $.ajax({
                url: '{{ route("product.delete-image") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    image_id: imageId
                },
                success: function(response) {
                    if (response.success) {
                        $('#img-' + imageId).remove();
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Error while removing image",
                            icon: "error",
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function seAsPrimary(imageId) {
            $.ajax({
                url: '{{ route("product.set-primary-image") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    image_id: imageId
                },
                success: function(response) {
                    if (response.success) {
                        console.log('image set as primary')
                        location.reload();
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Error while making image as primary",
                            icon: "error",
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }
    </script>
</x-app-layout>
