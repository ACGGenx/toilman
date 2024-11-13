<x-guest-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="card-title">{{ $category->name }}</h4>

                                <h6 class="card-description">{!! $category->description !!} </h6>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('shop.index') }}" class="btn btn-info me-2">
                                    <i class="fas fa-shopping-cart"></i> View All Products
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($products->isEmpty())
                            <div class="alert alert-info">
                                No products found in this category.
                            </div>
                        @else
                            <div class="table-responsive">
                                <div class="row g-4">
                                    @foreach($products as $product)
                                        <div class="col-md-3 mb-4">
                                            <div class="card h-100 product-card">
                                                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                                    @if($product->images && $product->images->isNotEmpty())
                                                        <div class="product-image-container">
                                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                                                 class="card-img-top product-image"
                                                                 alt="{{ $product->name }}">
                                                        </div>
                                                    @else
                                                        <div class="product-image-container d-flex align-items-center justify-content-center bg-light">
                                                            <span class="text-muted">No Image</span>
                                                        </div>
                                                    @endif
                                                    <div class="card-body d-flex flex-column">
                                                        <h5 class="card-title product-title text-dark mb-2">{{ $product->name }}</h5>
                                                        <div class="product-price mt-auto">
                                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                                <span class="text-danger me-2">${{ number_format($product->sale_price, 2) }}</span>
                                                                <small class="text-decoration-line-through text-muted">
                                                                    ${{ number_format($product->price, 2) }}
                                                                </small>
                                                            @else
                                                                <span class="text-primary">${{ number_format($product->price, 2) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-end">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .product-card {
            transition: transform 0.2s;
            border: 1px solid #eee;
            height: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .product-image-container {
            height: 200px;
            overflow: hidden;
            position: relative;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-title {
            font-size: 1rem;
            line-height: 1.4;
            height: 2.8em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-price {
            font-weight: 500;
            margin-top: auto;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .col-md-3 {
                width: 33.333%;
            }
        }

        @media (max-width: 992px) {
            .col-md-3 {
                width: 50%;
            }
        }

        @media (max-width: 576px) {
            .col-md-3 {
                width: 100%;
            }
        }

        /* Pagination Styling */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
        }
    </style>
</x-guest-layout>