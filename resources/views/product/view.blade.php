<x-guest-layout :assets="$assets ?? []">
    {{-- Meta tags for SEO --}}
    @section('meta')
    <meta name="title" content="{{ $product->meta_title }}">
    <meta name="description" content="{{ $product->meta_description }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $product->meta_title }}">
    <meta property="og:description" content="{{ $product->meta_description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->path) : asset('images/default.jpg') }}">
    @endsection

    <div class="row m-0 align-items-center bg-white vh-100">
        <div class="col-md-12">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="profile-content tab-content">
                                <div id="profile-feed" class="tab-pane fade active show">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center justify-content-between pb-4">
                                            <div class="header-title">
                                                <div class="d-flex flex-wrap">
                                                    <div class="media-support-info mt-2">
                                                        <h3 class="mb-0">{{ $product->name }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="user-post">
                                                <a href="javascript:void(0);">
                                                    @if ($product->primaryImage)
                                                    <img src="{{ asset('storage/' . $product->primaryImage->path) }}" alt="{{ $product->name }}" class="img-fluid">
                                                    @else
                                                    <img src="{{ asset('images/default.jpg') }}" alt="{{ $product->name }}" class="img-fluid">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="comment-area p-3">
                                                <div class="d-flex flex-wrap justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="d-flex align-items-center message-icon me-3">
                                                            <!-- SVG Inquiry Icon -->
                                                            <svg width="40px" height="40px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                                <title>Inquiry</title>
                                                                <g fill="none" stroke="none">
                                                                    <g fill="#000000">
                                                                        <path d="M384,0L384,298.666667L277.333333,298.666667L277.333333,384L147.2,298.666667L0,298.666667L0,0L384,0ZM341.333333,42.6666667L42.6666667,42.6666667L42.6666667,256L159.941531,256L234.666667,305.002667L234.666667,256L341.333333,256L341.333333,42.6666667Z" />
                                                                    </g>
                                                                </g>
                                                            </svg>

                                                            <span class="ms-1">{{ $product->status == 1 ? 'Available' : 'Unavailable' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <p>{{ $product->description }}</p>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="header-title">
                                        <h4 class="card-title">Similar Products</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-inline m-0 p-0">
                                        @foreach ($product->similarProducts as $similarProduct)
                                        <li class="d-flex mb-4 align-items-center">
                                            <div class="img-fluid bg-soft-warning rounded-pill">
                                                @if ($similarProduct->primaryImage)
                                                <img src="{{ asset('storage/' . $similarProduct->primaryImage->path) }}" alt="{{ $similarProduct->name }}" class="rounded-pill avatar-40">
                                                @else
                                                <img src="{{ asset('images/default.jpg') }}" alt="{{ $similarProduct->name }}" class="rounded-pill avatar-40">
                                                @endif
                                            </div>
                                            <div class="ms-3 flex-grow-1">
                                                <h6>{{ $similarProduct->name }}</h6>
                                                <p class="mb-0">{{ Str::limit($similarProduct->description, 50) }}</p>
                                            </div>
                                            <a href="{{ $similarProduct->slug}}" class="btn btn-outline-primary btn-icon btn-sm p-2">
                                                View
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
