<x-app-layout>
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <!-- Category Grid -->
            <div id="category-list">
                @if(!isset($categories))
                <p>No items found</p>
                @else
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="card-title">Categories List</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                @if($isEdit)
                                <button class="btn btn-primary" id="add-category-btn">Add Category</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive mt-4">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Name</th>
                                        <th style="width: 10%;">Parent Category</th>
                                        <!-- <th style="width: 10%;">Slug</th> -->
                                        <!-- <th style="width: 50%;">Description</th> -->
                                        <th style="width: 10%;">Image</th>

                                        @if($isEdit || $isDelete)
                                        <th style="width: 20%;" class="text-end">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->parentCategory ? $category->parentCategory->name : 'Root' }}</td>
                                        <!-- <td>{{ $category->slug }}</td> -->
                                        <!-- <td class="desc-text">{!! \Illuminate\Support\Str::limit($category->description, 50, '...') !!}</td> -->
                                        <td>
                                            @if($category->image)
                                            <div class="image-stack">
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image">
                                            </div>
                                            @endif
                                        </td>
                                        @if($isEdit || $isDelete)
                                        <td class="text-end">
                                            <!-- Edit Button -->
                                            <!-- <button class="btn btn-sm btn-warning edit-category-btn"
                                                    data-id="{{ $category->id }}"
                                                    data-name="{{ $category->name }}"
                                                    data-description="{{ $category->description }}"
                                                    data-parent="{{ $category->parent_category_id }}">
                                                Edit
                                            </button> -->
                                            &nbsp;
                                            <!-- Delete Button -->
                                            @if($isEdit)
                                            <input {{$category->status ? 'checked' : ''}} class="toggle product-status  " type="checkbox" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Status" data-bs-original-title="Status" data-id="{{ $category->id }}" onclick="toggleStatus(this, {{ $category->id }})">
                                            <a href="#" class="btn btn-sm btn-icon btn-warning edit-category-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" href="#" aria-label="Edit" data-bs-original-title="Edit"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                data-description="{{ $category->description }}"
                                                data-slug="{{ $category->slug }}"
                                                data-meta_title="{{ $category->meta_title }}"
                                                data-meta_description="{{ $category->meta_description }}"
                                                data-parent="{{ $category->parent_category_id }}"
                                                data-image="{{$category->image}}">
                                                <span class="btn-inner">
                                                    <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                            @endif
                                            @if($isDelete)
                                            <a class="btn btn-sm btn-danger delete-category-btn" data-id="{{ $category->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-bs-toggle="tooltip" data-bs-placement="top" href="#" aria-label="Delete" data-bs-original-title="Delete" style="padding: 0.125rem 0.25rem;">
                                                <span class="btn-inner" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Add/Edit Category Form (Initially Hidden) -->
            <div id="category-form" style="display:none;">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title" id="header-txt">Add Category</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="category-form-element" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" id="form-method" value="POST">
                            <div class="row col-12 col-md-12">
                                <div class="form-group col-12 col-md-6">
                                    <label for="name">Category Name</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="parent_category_id">Parent Category</label>
                                    <select name="parent_category_id" id="parent_category_id" class="form-control select2">
                                        <option value="" data-level="0">Root</option>
                                        @foreach($categories as $parentCategory)
                                        <option value="{{ $parentCategory->id }}" data-level="{{ $parentCategory->parent_category_id ? 1 : 0 }}">
                                            {{ $parentCategory->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="image">Category Images</label>
                                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                    <div class="existing-images hide" id="existing_image_box">
                                        <h6>Existing Image</h6>
                                        <div class="image-wrapper " style="position: relative; display: inline-block; margin: 10px;" id="cat-image">
                                            <img src="" id="cat_image" alt="Category Image" style="border-radius: 5%;width: 80px; height: 80px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="slug">Slug</label>
                                    <input type="text" class="form-control" name="slug" id="slug">
                                    <span id="url-error-message" style="color: red; display: none;">This Slug already exists!</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <div id="description-editor" style="min-height: 200px;"></div>
                                <input type="hidden" name="description" id="description">
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control" name="meta_title" id="meta_title">
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label for="meta_description">Meta Description</label>
                                <textarea class="form-control" name="meta_description" id="meta_description"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success" id="save-btn">Save</button>
                            <button type="button" class="btn btn-secondary" id="cancel-btn">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this category?
                    </div>
                    <div class="modal-footer">
                        <form id="delete-form" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
<script src="{{ asset('js/image-resize.min.js') }}"></script>
<script>
    let isValidSlug = true;
    var quill;
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            templateResult: formatState,
            templateSelection: formatState
        });

        function formatState(option) {
            if (!option.id) return option.text;
            let $option = $(option.element);
            let level = $option.data('level');
            let indent = '&nbsp;'.repeat(level * 2); // Adjust multiplier for more/less indentation
            return $('<span>' + indent + option.text + '</span>');
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        quill = new Quill('#description-editor', {
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

        // Clear the editor and reset the form when adding a new category
        document.getElementById('add-category-btn').addEventListener('click', function() {
            document.getElementById('category-form').style.display = 'block';
            document.getElementById('category-list').style.display = 'none';
            document.getElementById('add-category-btn').style.display = 'none';

            // Reset form for adding a new category
            document.getElementById('form-method').value = 'POST';
            document.getElementById('slug').value = '';
            document.getElementById('meta_title').value = '';
            document.getElementById('meta_description').value = '';
            document.getElementById('image').value = '';
            var element = document.getElementById('existing_image_box');
            element.classList.add('hide');

            quill.root.innerHTML = ''; // Clear the Quill editor
            document.getElementById('parent_category_id').value = '';
            document.getElementById('save-btn').innerText = 'Save';
            document.getElementById('header-txt').innerText = 'Add Category';
        });

        // Toggle back to the category list when the cancel button is clicked
        document.getElementById('cancel-btn').addEventListener('click', function() {
            document.getElementById('category-form').style.display = 'none';
            document.getElementById('category-list').style.display = 'block';
            document.getElementById('add-category-btn').style.display = 'inline';
        });

        // Handle editing a category
        document.querySelectorAll('.edit-category-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const description = this.dataset.description;
                const parent = this.dataset.parent;
                const slug = this.dataset.slug;
                const meta_title = this.dataset.meta_title;
                const meta_description = this.dataset.meta_description;
                const img = "{{ asset('storage') }}/" + this.dataset.image;

                // Show the form
                document.getElementById('category-form').style.display = 'block';
                document.getElementById('category-list').style.display = 'none';
                document.getElementById('add-category-btn').style.display = 'none';

                // Set form action for editing
                document.getElementById('category-form-element').action = '/categories/' + id;
                document.getElementById('form-method').value = 'PUT';

                // Populate the form fields
                document.getElementById('name').value = name;
                quill.root.innerHTML = description; // Load the description into the Quill editor
                document.getElementById('parent_category_id').value = parent;

                document.getElementById('slug').value = slug;
                document.getElementById('meta_title').value = meta_title;
                document.getElementById('meta_description').value = meta_description;
                var element = document.getElementById('existing_image_box');
                element.classList.add('hide');
                if (this.dataset.image.length) {
                    document.getElementById("cat_image").src = img;
                    element.classList.remove('hide');
                }

                document.getElementById('save-btn').innerText = 'Update';
                document.getElementById('header-txt').innerText = 'Edit Category';
            });
        });

        // Save Quill editor content on form submission
        document.getElementById('category-form-element').onsubmit = function() {
            document.getElementById('description').value = quill.root.innerHTML;
        };

        // Handle Delete button logic
        document.querySelectorAll('.delete-category-btn').forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-id');
                const deleteForm = document.getElementById('delete-form');
                deleteForm.action = `/categories/${categoryId}`;
            });
        });
    });

    function toggleStatus(element, categoryId) {
        $.ajax({
            url: '{{ route("categories.change-status") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                category_id: categoryId
            },
            success: function(response) {
                msg = "Category status changed";
                title = "Success";
                if (!response.success) {
                    $(element).prop('checked', !$(element).is(':checked'));
                    title = "Error";
                    msg = "Error while changing Category status";
                }
                Swal.fire({
                    title: title,
                    text: msg,
                    icon: title.toLowerCase(),
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    setTimeout(() => {
        $('#basic-table').DataTable({
            "pageLength": 10, // Default page size
            "lengthMenu": [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ], // Page size options
            "ordering": true, // Enable sorting
            "searching": true, // Enable search
            "pagingType": "full_numbers" // Show pagination with numbers
        });
        $('#basic-table').addClass('b-1-g');
    }, 1000);

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
        let slug = generateSlug(name);

        document.getElementById('slug').value = slug;

        console.log(slug);
        checkSlugUnique(slug);
        // let description = quill.root.innerText;
    }

    function generateSlug(text) {
        return text.trim().toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
    }

    function checkSlugUnique(slug) {
        fetch(`/check-slug-cat?slug=${slug}`)
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

    document.getElementById('save-btn').addEventListener('click', function(event) {
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
        fetch('/categories/upload-image', {
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
</script>
