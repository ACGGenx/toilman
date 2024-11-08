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
                                <input type="text" class="form-control" name="name" id="name" data-id="{{isset($product) ? $product->id : 0}}" value="{{ $product->name ?? old('name') }}" required>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="category_id">Category</label>
                                <select name="category_id[]" id="category_id" class="form-control" required multiple>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @if(isset($product) && $product->categories->contains($category->id)) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="display: block;">
                            <div class="form-group col-12">
                                <label for="description">Description</label>
                                <div id="description-editor" style="min-height: 200px;">{!! $product->description ?? old('description') !!}</div>
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
                            <div class="form-group input-group">
                                <input type="file" class="form-control" name="images[]" id="images" multiple accept="image/*">
                                @if(isset($product))
                                <span class="input-group-text" id="upload-prd-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 15v4H5v-4H3v4c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-4h-2zm-7-12l-5.5 5.5 1.41 1.41L11 7.83V18h2V7.83l3.09 3.09 1.41-1.41L12 3z" />
                                    </svg>
                                </span>
                                @endif
                            </div>
                            <div id="image-preview" class="mt-3"></div> <!-- Image preview container -->
                            <div class="existing-images">
                                @if(isset($product) && $product->images->isNotEmpty())
                                <h6>Existing Images</h6>
                                @foreach($product->images as $image)
                                <div class="image-wrapper {{$image->is_primary ? 'primary' : ''}}" style="position: relative; display: inline-block; margin: 10px;" id="img-{{$image->id}}">
                                    @if(!$image->is_primary)
                                    <input type="checkbox" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Set as Primary" data-bs-original-title="Set as Primary" class="form-check-input" style="position: absolute; top: -4px;height: 18px;width: 18px;line-height: 1px;padding: 0px;"
                                        onclick="setAsPrimary({{ $image->id }})" />
                                    @endif
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image" style="border-radius: 5%;width: 80px; height: 80px; object-fit: cover;">
                                    @if(!$image->is_primary)
                                    <button type="button" class="btn btn-danger delete-image-btn"
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

    <script src="{{ asset('js/image-resize.min.js') }}"></script>
    <script>
        let isValidSlug = true;
        // Initialize Quill editor
        var quill = new Quill('#description-editor', {
            theme: 'snow',
            modules: {
                imageResize: {
                    displaySize: true
                },
                toolbar: {
                    container: [
                        [{
                            'header': [1, 2, 3, 4, 5, 6, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'align': []
                        }],
                        ['link', 'image'],

                        ['clean']
                    ],
                    handlers: {
                        image: imageHandler
                    }
                }
            }
        });
        setTimeout(() => {
            $('#similar_products').select2({
                placeholder: 'Select Similar Products',
                allowClear: true,
                closeOnSelect: false
            });
        }, 2500);
        setTimeout(() => {
            $('#category_id').select2({
                placeholder: 'Select Categories',
                allowClear: true,
                closeOnSelect: false
            });
        }, 1000);
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
            let ds = document.getElementById('name').dataset;
            let description = quill.root.innerText; // Get the description from Quill editor
            let slug = generateSlug(name); //(name + ' ' + description);
            let product_id = ds.id;
            document.getElementById('slug').value = slug;
            checkSlugUnique(slug, product_id);
        }

        function generateSlug(text) {
            return text.trim().toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        }

        function checkSlugUnique(slug, productId) {
            fetch(`/check-slug?slug=${slug}&product_id=${productId}`)
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

        function setAsPrimary(imageId) {
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

        function imageHandler() {
            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = () => {
                let file = input.files[0];
                if (/^image\//.test(file.type)) {
                    saveImageToServer(file);
                } else {
                    alert('You can only upload images.');
                }
            };
        }

        function saveImageToServer(file) {
            let formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            // Perform an AJAX request to upload the image
            fetch('/product/upload-image', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        let range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', result.imageUrl);
                    } else {
                        console.error('Failed to upload image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.getElementById('upload-prd-image').addEventListener('click', function() {
            const input = document.getElementById('images');
            if (input.files.length === 0) {
                alert('Please select at least one image to upload.');
                return;
            }

            Array.from(input.files).forEach(file => {
                if (!file.type.startsWith('image/')) {
                    alert('Only image files are allowed.');
                    return;
                }
                uploadProductImage(file);
            });

            input.value = '';
            const preview = document.getElementById('image-preview');
            // Safely remove all child elements
            while (preview.firstChild) {
                preview.removeChild(preview.firstChild);
            }
        });

        function uploadProductImage(file) {
            let ds = document.getElementById('name').dataset;
            let product_id = ds.id;
            const formData = new FormData();
            formData.append('image', file);
            formData.append('product_id', product_id);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("product.uploadProductImage") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayImagePreview(data.imageUrl, data.imageId);
                    } else {
                        alert('Image upload failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error uploading image:', error);
                });
        }

        function displayImagePreview(imageUrl, imageId) {
            const existingImagesContainer = document.querySelector('.existing-images');

            const imgWrapper = document.createElement('div');
            imgWrapper.className = 'image-wrapper';
            imgWrapper.style.position = 'relative';
            imgWrapper.style.margin = '10px';
            imgWrapper.style.display = 'inline-block';
            imgWrapper.id = `img-${imageId}`; // Set a unique ID for each image

            const img = document.createElement('img');
            img.src = imageUrl;
            img.style.borderRadius = '5%';
            img.style.width = '80px';
            img.style.height = '80px';
            img.style.objectFit = 'cover';
            img.alt = 'Product Image';

            // Add a checkbox to set as primary (non-primary by default for new images)
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'form-check-input';
            checkbox.style.position = 'absolute';
            checkbox.style.top = '-4px';
            checkbox.style.left = '5px';
            checkbox.style.height = '18px';
            checkbox.style.width = '18px';
            checkbox.title = 'Set as Primary';
            checkbox.onclick = function() {
                setAsPrimary(`${imageId}`);
            };

            // Add delete button for the new image
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'btn btn-danger delete-image-btn';
            deleteBtn.style.position = 'absolute';
            deleteBtn.style.top = '0';
            deleteBtn.style.right = '0';
            deleteBtn.style.height = '18px';
            deleteBtn.style.width = '18px';
            deleteBtn.style.lineHeight = '1';
            deleteBtn.style.padding = '0px';
            deleteBtn.innerHTML = '&times;';
            deleteBtn.onclick = function() {
                deleteImage(imageId);
            };

            // Append image, checkbox, and delete button to wrapper
            imgWrapper.appendChild(checkbox);
            imgWrapper.appendChild(img);
            imgWrapper.appendChild(deleteBtn);

            // Append the new image wrapper to the existing images container
            existingImagesContainer.appendChild(imgWrapper);
        }
    </script>
</x-app-layout>
