<x-app-layout>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>{{ $page->exists ? 'Edit Page' : 'Create Page' }}</h4>

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

                    <form id="page-form-element" action="{{ $page->exists ? route('pages.update', $page->id) : route('pages.store') }}" method="POST">
                        @csrf
                        @if ($page->exists)
                        @method('PUT')
                        @endif
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $page->title) }}" required>
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="url">URL</label>
                                <input type="text" class="form-control" id="url" name="url" value="{{ old('url', $page->url) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="seo_tags">SEO Tags</label>
                            <textarea type="text" class="form-control" id="seo_tags" name="seo_tags">{{ old('seo_tags', $page->seo_tags) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <div id="description-editor" style="height: 200px;">{!! $page->description ?? old('description') !!}</div>
                            <input type="hidden" name="description" id="description">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_default" name="is_default" value="1" {{ old('is_default', $page->is_default) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_default">Set as home page</label>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success" id="submit-form">{{ $page->exists ? 'Update' : 'Create' }} page</button>
                        <a href="{{ route('pages.index') }}" class="btn btn-secondary">Cancel</a>
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
        var quill;
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

        document.getElementById('submit-form').addEventListener('click', function(event) {

            document.querySelector('#description').value = quill.root.innerHTML;
            if (!isValidSlug) {
                event.preventDefault();
                Swal.fire({
                    title: 'Error',
                    text: "Please update URL, given input URL already exist!",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Submit the form programmatically
            document.getElementById('page-form-element').submit();
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
            fetch('/pages/upload-image', {
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
</x-app-layout>
