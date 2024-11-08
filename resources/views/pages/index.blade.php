<x-app-layout>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <!-- Product Grid -->
            <div id="product-list">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="card-title">Pages</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                @if($isEdit)
                                <a href="{{ route('pages.create') }}" class="btn btn-primary mb-3">Add New Page</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        @if($pages->isEmpty())
                        <p>No page available</p>
                        @else
                        <div class="table-responsive mt-4 ">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Title</th>
                                        <th style="width: 50%;">URL</th>
                                        <th style="width: 10%;">Is home page</th>
                                        <!-- <th style="width: 40%;">Description</th> -->
                                        @if($isEdit || $isDelete)
                                        <th style="width: 10%;">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pages as $page)
                                    <tr>
                                        <td>{{ $page->title }}</td>
                                        <td>{{ $page->url }}</td>
                                        <!-- <td class="desc-text"> -->
                                        <!-- {!! \Illuminate\Support\Str::limit($page->description, 100, '...') !!}</td> -->

                                        <td>
                                            <input type="checkbox"
                                                {{ $page->is_default ? 'checked' : '' }}
                                                name="is_default"
                                                class="form-check-input default-page-checkbox"
                                                onclick="toggleDefaultPage(this, {{ $page->id }})"
                                                {{ $isEdit ? '' : 'disabled' }}>
                                        </td>
                                        @if($isEdit || $isDelete)
                                        <td>
                                            @if($isEdit)
                                            <input {{$page->status ? 'checked' : ''}} class="toggle product-status" type="checkbox" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Status" data-bs-original-title="Status" data-id="{{ $page->id }}" onclick="toggleStatus(this,{{ $page->id }})">
                                            <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" href="#" aria-label="Edit" data-bs-original-title="Edit">
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
                                            <a class="btn btn-sm btn-danger delete-category-btn" data-id="{{ $page->id }}"
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
                        @endif
                    </div>
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
                    Are you sure you want to delete this page?
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-category-btn').forEach(button => {
            button.addEventListener('click', function() {
                const PageId = this.getAttribute('data-id');
                const deleteForm = document.getElementById('delete-form');
                deleteForm.action = `/pages/${PageId}`;
            });
        });
    });

    function toggleStatus(element, pageId) {
        $.ajax({
            url: '{{ route("page.change-status") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                page_id: pageId
            },
            success: function(response) {
                msg = "Page status changed";
                title = "Success";
                if (!response.success) {
                    $(element).prop('checked', !$(element).is(':checked'));
                    title = "Error";
                    msg = "Error while changing page status";
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

    function toggleDefaultPage(element, pageId) {
        // Prevent multiple simultaneous requests
        if ($(element).data('processing')) {
            return;
        }

        $(element).data('processing', true);

        // Uncheck all other checkboxes first (visually)
        if (element.checked) {
            $('input[name="is_default"]').not(element).prop('checked', false);
        }

        $.ajax({
            url: '/pages/set-default',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                page_id: pageId,
                is_default: element.checked
            },
            success: function(response) {
                if (response.success) {
                    // Swal.fire({
                    //     title: 'Success',
                    //     text: response.message,
                    //     icon: 'success',
                    //     confirmButtonText: 'OK'
                    // });
                } else {
                    // Revert the checkbox if the operation failed
                    element.checked = !element.checked;
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                // Revert the checkbox on error
                element.checked = !element.checked;
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to update default page status',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                $(element).data('processing', false);
            }
        });
    }
</script>
