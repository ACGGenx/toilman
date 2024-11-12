<div class="row g-4">
    @forelse($products as $product)
        <div class="col-md-4 mb-4">
            <div class="product-card">
                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                    <div class="product-image">
                        @if($product->images && $product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid">
                        @else
                            <div class="no-image">No Image Available</div>
                        @endif
                    </div>
                    <div class="product-info p-3">
                        <h5 class="product-title">{{ $product->name }}</h5>
                        <div class="product-price">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="sale-price">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="original-price text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="regular-price">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">No products found</div>
        </div>
    @endforelse
</div>

@if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@endif