<x-app-layout>
    <div class="container-fluid py-4">
        <!-- Top Banner -->
        <!-- <div class="bg-dark text-white text-center py-2 small">
            @if($product->sale_price && $product->sale_price < $product->price)
                Save {{ number_format((($product->price-$product->sale_price)/$product->price*100), 0) }}% on this product! Limited time offer.
            @else
                Sign-up and GET 20% OFF for your first order | Sign up now
            @endif
        </div> -->

 

        <!-- Product Section -->
        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6">
                
                    <!-- Main Image -->
                    <div class="main-image-wrapper position-relative">
                        @if($product->images && $product->images->count() > 0)
                            @php
                                $mainImage = $product->images->firstWhere('is_primary', true) 
                                            ?? $product->images->first();
                            @endphp
                            <img src="{{ asset('storage/' . $mainImage->image_path) }}" 
                                class="main-product-image" 
                                id="mainImage" 
                                alt="{{ $product->name }}">
                            
                            <!-- Navigation Arrows -->
                            @if($product->images->count() > 1)
                                <button class="nav-btn prev" onclick="changeImage(-1)">❮</button>
                                <button class="nav-btn next" onclick="changeImage(1)">❯</button>
                            @endif
                        @else
                            <div class="no-image-placeholder">No Image Available</div>
                        @endif
                    </div>

        <!-- Thumbnail Images -->
        @if($product->images && $product->images->count() > 1)
                        <div class="thumbnail-grid mt-4">
                            @foreach($product->images as $index => $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     class="thumbnail-img {{ $image->is_primary ? 'active' : '' }}"
                                     onclick="setMainImage({{ $index }})"
                                     alt="Product view {{ $loop->iteration }}">
                            @endforeach
                        </div>
                    @endif
                </div>
            

            <!-- Product Details -->
            <div class="col-md-6">
                <div class="product-info ps-md-4">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    @if($product->category)
                        <div class="category-badge mt-2">
                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
                        </div>
                    @endif

                    <div class="product-price my-3">
                        @if($product->hasDiscount())
                            <div class="d-flex align-items-center gap-2">
                                <span class="current-price">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="original-price text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</span>
                                <span class="discount-badge">{{ $product->discount_percentage }}% OFF</span>
                            </div>
                        @else
                            <span class="current-price">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    <div class="product-description mt-4">
                        {!! $product->description !!}
                    </div>

                    @if($product->custom_box)
                        <div class="custom-content mt-4">
                            {!! $product->custom_box !!}
                        </div>
                    @endif
                </div>
            </div>
        

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
            <div class="related-products-section mt-5">
                <h3 class="section-title mb-4">Related Products</h3>
                <div class="row">
                    @foreach($relatedProducts as $related)
                        <div class="col-md-4">
                            <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none">
                                <div class="related-product-card">
                                    @if($related->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $related->images->first()->image_path) }}" 
                                             class="related-product-image" 
                                             alt="{{ $related->name }}">
                                    @else
                                        <div class="no-image-placeholder">No Image Available</div>
                                    @endif
                                    
                                    <div class="related-product-info p-3">
                                        <h5 class="mb-2">{{ $related->name }}</h5>
                                        <div class="price">
                                            @if($related->sale_price && $related->sale_price < $related->price)
                                                <span class="text-danger">${{ number_format($related->sale_price, 2) }}</span>
                                                <small class="text-muted text-decoration-line-through">${{ number_format($related->price, 2) }}</small>
                                            @else
                                                <span>${{ number_format($related->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <style>
    .main-image-container {
        width: 100%;
        height: 400px; /* Reduced from 600px */
        background: #f8f9fa;
        position: relative;
        overflow: hidden;
    }

    .main-product-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .thumbnail-item {
        aspect-ratio: 1;
        overflow: hidden;
    }

    .thumbnail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
    }

    .thumbnail-img:hover {
        opacity: 0.8;
    }

    .thumbnail-img.active {
        border-color: #000;
    }

    .nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.8);
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .nav-btn:hover {
        background: rgba(255, 255, 255, 0.95);
    }

    .prev { left: 10px; }
    .next { right: 10px; }

    .product-title {
        font-size: 1.8rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .current-price {
        font-size: 1.5rem;
        font-weight: 500;
    }

    .original-price {
        font-size: 1rem;
    }

    .discount-badge {
        background: #dc3545;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .product-description {
        color: #666;
        line-height: 1.6;
    }

    .no-image-placeholder {
        width: 100%;
        height: 400px; /* Reduced from 600px */
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    /* Related Products Styling */
    .related-product-card {
        border: 1px solid #eee;
        transition: transform 0.3s;
        height: 100%;
    }

    .related-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .related-product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    </style>

<script>
    let currentImageIndex = 0;
    const images = @json($product->images->pluck('image_path'));

    function setMainImage(index) {
        currentImageIndex = index;
        const mainImage = document.getElementById('mainImage');
        mainImage.src = `{{ asset('storage') }}/${images[index]}`;
        
        // Update thumbnails
        document.querySelectorAll('.thumbnail-img').forEach((thumb, idx) => {
            thumb.classList.toggle('active', idx === index);
        });
    }

    function changeImage(direction) {
        currentImageIndex = (currentImageIndex + direction + images.length) % images.length;
        setMainImage(currentImageIndex);
    }
</script>
</x-app-layout>