<x-app-layout>
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <!-- Product Grid -->
            <div id="product-list">
                @if($products->isEmpty())
                <p>No products found</p>
                @else
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="card-title">Products</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                @if($isEdit)
                                <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive mt-4 ">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Name</th>
                                        <th style="width: 10%;">Category</th>
                                        <th style="width: 40%;">Description</th>
                                        <th style="width: 10%;">Price</th>
                                        <th style="width: 15%;">Images</th>

                                        @if($isEdit || $isDelete)
                                        <th style="width: 15%;" class="text-end">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ isset($product->category) ? $product->category->name : '' }}</td>
                                        <td class="desc-text">
                                            {!! \Illuminate\Support\Str::limit($product->description, 100, '...') !!}
                                        </td>
                                        <td>
                                            @if(isset($product->sale_price) && $product->sale_price < $product->price)
                                                <span>{{ number_format($product->sale_price, 2) }}<sup>{{number_format((($product->price-$product->sale_price)/$product->price*100),0)}}% off</sup></span>
                                                <span style="text-decoration: line-through; color: red;">{{ number_format($product->price, 2) }}</span>
                                                @else
                                                <span>{{ number_format($product->price, 2) }}</span>
                                                @endif
                                        </td>
                                        <td>
                                            @if($product->images && $product->images->count() > 0)
                                            <div class="col-12">
                                                <div class="image-stack">
                                                    @foreach($product->images->take(2) as $image)
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image">
                                                    @endforeach
                                                    @if(($product->images->count() - 2)>0)
                                                    <div class="more-images">+{{ $product->images->count() - 2 }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            @else
                                            <p>No Images</p>
                                            @endif
                                        </td>
                                        <!-- <td>{{ $product->status ? 'Active' : 'Inactive' }} -->
                                        </td>
                                        @if($isEdit || $isDelete)
                                        <td class="text-end">

                                            @if($isEdit)
                                            <input {{$product->status ? 'checked' : ''}} class="toggle product-status" type="checkbox" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Status" data-bs-original-title="Status" data-id="{{ $product->id }}" onclick="toggleStatus(this,{{ $product->id }})">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" href="#" aria-label="Edit" data-bs-original-title="Edit">
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
                                            <a class="btn btn-sm btn-danger delete-category-btn" data-id="{{ $product->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-bs-toggle="tooltip" data-bs-placement="top" href="#" aria-label="Delete" data-bs-original-title="Delete" style="padding: 0.125rem 0.25rem;">
                                                <span class="btn-inner">
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
                    Are you sure you want to delete this product?
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
                const ProductId = this.getAttribute('data-id');
                const deleteForm = document.getElementById('delete-form');
                deleteForm.action = `/products/${ProductId}`;
            });
        });
    });

    function toggleStatus(element, productId) {
        $.ajax({
            url: '{{ route("product.change-status") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function(response) {
                msg = "Product status changed";
                title = "Success";
                if (!response.success) {
                    $(element).prop('checked', !$(element).is(':checked'));
                    title = "Error";
                    msg = "Error while changing product status";
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
</script>
