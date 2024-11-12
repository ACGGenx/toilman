<x-guest-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Filter by Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-sm btn-link" onclick="selectAllCategories()">
                                Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-link" onclick="clearAllCategories()">
                                Clear All
                            </button>
                        </div>

                        <form id="category-filter-form">
                            @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input type="checkbox" 
                                           class="form-check-input category-checkbox" 
                                           id="cat-{{ $category->id }}"
                                           value="{{ $category->id }}">
                                    <label class="form-check-label" for="cat-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                            
                            <button type="submit" class="btn btn-primary w-100 mt-3">Apply Filters</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-md-9">
                <div id="products-container">
                    <div class="products-header d-flex justify-content-between align-items-center mb-4">
                        <h4>Products</h4>
                        <span>{{ $products->total() }} products found</span>
                    </div>

                    <div id="products-grid">
                        @include('categories.partials.product-grid', ['products' => $products])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function selectAllCategories() {
            document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = true);
        }

        function clearAllCategories() {
            document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
        }

        document.getElementById('category-filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            filterProducts();
        });

        function filterProducts() {
            const selectedCategories = Array.from(
                document.querySelectorAll('.category-checkbox:checked')
            ).map(cb => cb.value);

            fetch('{{ route("shop.filter") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ categories: selectedCategories })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('products-grid').innerHTML = data.html;
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    @endpush
</x-guest-layout>