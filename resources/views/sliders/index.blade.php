<x-app-layout>
    <style>
        .slider-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            overflow: hidden;
        }

        .slide-item {
            padding: 0 0.5rem;
        }

        .img-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 100%;
            /* Creates a square aspect ratio */
        }

        .img-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        /* Slick slider customization */
        .slick-prev,
        .slick-next {
            width: 30px;
            height: 30px;
            z-index: 1;
            border-radius: 50%;
            transition: background 0.3s ease;
        }


        .slick-prev {
            left: 5px;
        }

        .slick-next {
            right: 5px;
        }

        .slick-track {
            display: flex;
            align-items: center;
        }

        /* Loading state */
        .slick-loading .slick-track,
        .slick-loading .slick-slide {
            visibility: hidden;
        }

        .slider-container {
            max-width: 700px;
            padding: 0 15px
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .slide-item {
                padding: 0 0.25rem;
            }

            .slick-prev,
            .slick-next {
                width: 25px;
                height: 25px;
            }

            .slider-container {
                max-width: 300px;
                padding: 0 15px
            }
        }

        @media (max-width:480px) {
            .slider-container {
                max-width: 185px;
                padding: 0 15px
            }
        }
    </style>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div id="product-list">

                @if($sliders->isEmpty())
                <p>No slider found</p>
                @else
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="card-title">Sliders</h4>
                            </div>
                            @if($isEdit)
                            <div class="col-md-6 text-end">
                                <a href="{{ route('sliders.create') }}" class="btn btn-primary">Add Slider</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mt-4">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Name</th>
                                        <th style="width: 25%;">Description</th>
                                        <th style="width: 50%;text-align:center;">Images</th>
                                        @if($isEdit || $isDelete)
                                        <th style="width: 15%;" class="text-end">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sliders as $slider)
                                    <tr>
                                        <td>{{ $slider->name }}</td>
                                        <td class="desc-text">
                                            {{\Illuminate\Support\Str::limit($slider->description, 30, '...')}}
                                        </td>
                                        <td>
                                            @if($slider->images && $slider->images->count() > 0)
                                            <div class="slider-container">
                                                <div class="image-slider-{{$slider->id}}" style="padding: 0 24px;">
                                                    @foreach ($slider->images as $image)
                                                    <div class="slide-item">
                                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                                            alt="Image"
                                                            class="img-fluid"
                                                            style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @else
                                            <p>No Images</p>
                                            @endif
                                        </td>

                                        @if($isEdit || $isDelete)
                                        <td class="text-end">

                                            @if($isEdit)
                                            <a href="{{ route('sliders.edit', $slider->id) }}" class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" href="#" aria-label="Edit" data-bs-original-title="Edit">
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
                                            <a class="btn btn-sm btn-danger delete-category-btn" data-id="{{ $slider->id }}"
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
    <!-- Delete Modal remains the same -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this slider?
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
    $(document).ready(function() {
        @foreach($sliders as $slider)
        $('.image-slider-{{$slider->id}}').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 5,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        @endforeach
    });
</script>
