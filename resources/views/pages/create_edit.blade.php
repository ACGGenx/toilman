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

    <script>
        let isValidSlug = true;
        // Initialize Quill editor
        var quill = new Quill('#description-editor', {
            theme: 'snow'
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
    </script>
</x-app-layout>
