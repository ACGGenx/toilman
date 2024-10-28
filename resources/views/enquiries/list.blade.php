<x-app-layout>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="card-title">Enquiries List</h4>
                                </div>
                                <div class="col-md-6 text-end">
                                    <form action="{{ route('enquiries.download') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                        @if(request('type'))
                                        @foreach((array)request('type') as $type)
                                        <input type="hidden" name="type[]" value="{{ $type }}">
                                        @endforeach
                                        @endif
                                        <button type="submit" class="btn btn-success">Download CSV</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('enquiries.list') }}" method="GET" class="mb-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <label for="start_date" class="col-form-label">Start Date:</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="col-auto">
                                        <label for="end_date" class="col-form-label">End Date:</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                    </div>
                                    <div class="col-auto">
                                        <label class="col-form-label">Type:</label>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="normal" name="type[]" value="normal" {{ in_array('normal', (array)request('type')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="normal">Normal</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="bulk" name="type[]" value="bulk" {{ in_array('bulk', (array)request('type')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="bulk">Bulk</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive mt-4">
                                <table id="basic-table" class="table table-striped mb-0" role="grid">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">Name</th>
                                            <th style="width: 15%;">Email</th>
                                            <th style="width: 45%;">Detail</th>
                                            <th style="width: 10%;">Product Name</th>
                                            <th style="width: 10%;">Type</th>
                                            <th style="width: 10%;">Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($enquiries as $enquiry)
                                        <tr>
                                            <td>{{ $enquiry->name }}</td>
                                            <td>{{ $enquiry->email }}</td>
                                            <td>{{ $enquiry->detail }}</td>
                                            <td>{{ $enquiry->product->name ?? 'N/A' }}</td>
                                            <td>{{ $enquiry->type}}</td>
                                            <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- DataTables initialization script -->
        <script>
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
        </script>
</x-app-layout>
