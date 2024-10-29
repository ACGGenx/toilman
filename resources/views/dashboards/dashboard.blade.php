<x-app-layout :assets="$assets ?? []">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="row row-cols-1">
                <div class="d-slider1 overflow-hidden">
                    <ul class="row col-md-12 col-12  list-inline m-0 p-0 mb-2">
                        <!-- Categories Card -->
                        @if(auth()->user()->hasRole('admin') || (isset(auth()->user()->getPermissions()[2]) && (auth()->user()->getPermissions()[2]->permission_value & (2))))
                        <li class="-12 m-3 col-12 col-sm-6 col-md-3 col-lg-2 card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{route('categories.index')}}">
                                <div class="card-body dashboard-card">
                                    <div class="progress-widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 16 16">
                                            <circle cx="8" cy="8" r="8" fill="#3a57e824" />
                                            <path d="M12 5.5h-2.2c-.3 0-.5-.1-.6-.3l-.4-.7c-.1-.2-.3-.3-.6-.3H5.5c-.8 0-1.3.6-1.3 1.3v5.3c0 .7.6 1.3 1.3 1.3h6.7c.7 0 1.3-.6 1.3-1.3V6.8c0-.7-.6-1.3-1.3-1.3z" fill="#3a57e8" />
                                        </svg>
                                        <div class="progress-detail">
                                            <p class="mb-2">Total Categories</p>
                                            <h4 class="counter">{{ $totalCategories ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <!-- Sub-Categories Card -->
                        <li class="-12 m-3 col-12 col-sm-6 col-md-3 col-lg-2 card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{route('categories.index')}}">
                                <div class="card-body dashboard-card">
                                    <div class="progress-widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 16 16">
                                            <circle cx="8" cy="8" r="8" fill="#3a57e824" />
                                            <path d="M10.7 5.3H9c-.3 0-.6-.3-.6-.6v-.4c0-.4-.3-.6-.6-.6H5.3c-.7 0-1.3.6-1.3 1.3v5.3c0 .7.6 1.3 1.3 1.3h5.3c.7 0 1.3-.6 1.3-1.3V6.7c0-.7-.6-1.4-1.2-1.4z" fill="#3a57e8" />
                                            <path d="M12 7.3h-1.7c-.3 0-.6-.3-.6-.6v-.7c0-.4-.3-.6-.6-.6H6.7" fill="none" stroke="#3a57e8" stroke-width="1" />
                                        </svg>
                                        <div class="progress-detail">
                                            <p class="mb-2">Total Sub-Categories</p>
                                            <h4 class="counter">{{ $totalSubCategories ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endif
                        <!-- Products Card -->
                        @if(auth()->user()->hasRole('admin') || (isset(auth()->user()->getPermissions()[3]) && (auth()->user()->getPermissions()[3]->permission_value & (2))))
                        <li class="-12 m-3 col-12 col-sm-6 col-md-3 col-lg-2 card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{route('products.index')}}">
                                <div class="card-body dashboard-card">
                                    <div class="progress-widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 16 16">
                                            <circle cx="8" cy="8" r="8" fill="#3a57e824" />
                                            <path d="M10.7 6l-2.7-1.7L5.3 6v4l2.7 1.7L10.7 10V6z" fill="none" stroke="#3a57e8" stroke-width="1" />
                                            <path d="M8 4.3v4M5.3 6l2.7 1.7L10.7 6" fill="none" stroke="#3a57e8" stroke-width="1" />
                                        </svg>
                                        <div class="progress-detail">
                                            <p class="mb-2">Total Products</p>
                                            <h4 class="counter">{{ $totalProducts ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endif
                        <!-- Enquiries Card -->
                        @if(auth()->user()->hasRole('admin') || (isset(auth()->user()->getPermissions()[1]) && (auth()->user()->getPermissions()[1]->permission_value & (2))))
                        <li class="-12 m-3 col-12 col-sm-6 col-md-3 col-lg-2 card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{route('enquiries.list', ['type[]' => 'normal'])}}">
                                <div class="card-body dashboard-card">
                                    <div class="progress-widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 16 16">
                                            <circle cx="8" cy="8" r="8" fill="#3a57e824" />
                                            <path d="M11.3 6c0-.7-.6-1.3-1.3-1.3H6c-.7 0-1.3.6-1.3 1.3v3.3c0 .7.6 1.3 1.3 1.3h4l2 1.7V6z" fill="#3a57e8" />
                                            <path d="M6 7.3h4M6 8.7h3.3" fill="none" stroke="#fff" stroke-width="1" stroke-linecap="round" />
                                        </svg>
                                        <div class="progress-detail">
                                            <p class="mb-2">Total Enquiries</p>
                                            <h4 class="counter">{{ $totalEnquiries ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <!-- Bulk Enquiries Card -->
                        <li class="-12 m-3 col-12 col-sm-6 col-md-3 col-lg-2 card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{route('enquiries.list', ['type[]' => 'bulk'])}}">
                                <div class="card-body dashboard-card">
                                    <div class="progress-widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 16 16">
                                            <circle cx="8" cy="8" r="8" fill="#3a57e824" />
                                            <path d="M10.7 5.3H5.3c-.7 0-1.3.6-1.3 1.3v2.7c0 .7.6 1.3 1.3 1.3h5.3c.7 0 1.3-.6 1.3-1.3V6.7c0-.7-.6-1.4-1.2-1.4z" fill="#3a57e8" />
                                            <path d="M9.3 7.3h-2.7M9.3 8.7h-2.7" fill="none" stroke="#fff" stroke-width="1" stroke-linecap="round" />
                                            <path d="M12 6.7v4c0 .7-.6 1.3-1.3 1.3H5.3" fill="none" stroke="#3a57e8" stroke-width="1" />
                                        </svg>
                                        <div class="progress-detail">
                                            <p class="mb-2">Total Bulk Enquiries</p>
                                            <h4 class="counter">{{ $totalBulkEnquiries ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endif
                        <!-- Sliders Card -->
                        @if(auth()->user()->hasRole('admin') || (isset(auth()->user()->getPermissions()[5]) && (auth()->user()->getPermissions()[1]->permission_value & (2))))
                        <li class="-12 m-3 col-12 col-sm-6 col-md-3 col-lg-2 card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{route('sliders.index')}}">
                                <div class="card-body dashboard-card">
                                    <div class="progress-widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 16 16">
                                            <circle cx="8" cy="8" r="8" fill="#3a57e824" />
                                            <rect x="2" y="4" width="6" height="8" fill="#3a57e8" rx="1" opacity="0.8" />
                                            <rect x="5" y="4" width="6" height="8" fill="#3a57e8" rx="1" opacity="0.6" />
                                            <rect x="8" y="4" width="6" height="8" fill="#3a57e8" rx="1" opacity="0.4" />
                                        </svg>
                                        <div class="progress-detail">
                                            <p class="mb-2">Total Sliders</p>
                                            <h4 class="counter">{{ $totalSliders ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endif
                        <!-- Pages Card -->
                        @if(auth()->user()->hasRole('admin') || (isset(auth()->user()->getPermissions()[4]) && (auth()->user()->getPermissions()[4]->permission_value & (2))))
                        <li class="-12 m-3 col-12 col-sm-6 col-md-3 col-lg-2 card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{route('pages.index')}}">
                                <div class="card-body dashboard-card">
                                    <div class="progress-widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 16 16">
                                            <circle cx="8" cy="8" r="8" fill="#3a57e824" />
                                            <!-- Top bar -->
                                            <path d="M3 3.5C3 3.22386 3.22386 3 3.5 3H12.5C12.7761 3 13 3.22386 13 3.5V5H3V3.5Z" fill="#3a57e8" />
                                            <!-- Window dots -->
                                            <circle cx="4.5" cy="4" r="0.5" fill="#ffffff" />
                                            <circle cx="5.8" cy="4" r="0.5" fill="#ffffff" />
                                            <!-- Main content area -->
                                            <rect x="3" y="5.5" width="10" height="7.5" rx="1" ry="1" stroke="#3a57e8" stroke-width="1.2" fill="none" />
                                        </svg>
                                        <div class="progress-detail">
                                            <p class="mb-2">Total Pages</p>
                                            <h4 class="counter">{{ $totalPages ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endif
                        <!-- Users Card -->

                        @if(auth()->user()->hasRole('admin'))
                        <li class="-12 m-3 col-12 col-sm-6 col-md-3 col-lg-2 card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{route('users.index', ['status' => 'active'])}}">
                                <div class="card-body dashboard-card">
                                    <div class="progress-widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 16 16">
                                            <circle cx="8" cy="8" r="8" fill="#3a57e824" />
                                            <!-- Main user -->
                                            <circle cx="8" cy="6" r="2" fill="#3a57e8" />
                                            <path d="M5 12.5c0-1.7 1.3-3 3-3s3 1.3 3 3" fill="#3a57e8" />
                                            <!-- Left user -->
                                            <circle cx="4.5" cy="7" r="1.5" fill="#3a57e8" opacity="0.8" />
                                            <path d="M2 13c0-1.4 1-2.5 2.5-2.5s2.5 1.1 2.5 2.5" fill="#3a57e8" opacity="0.8" />
                                            <!-- Right user -->
                                            <circle cx="11.5" cy="7" r="1.5" fill="#3a57e8" opacity="0.8" />
                                            <path d="M9 13c0-1.4 1-2.5 2.5-2.5s2.5 1.1 2.5 2.5" fill="#3a57e8" opacity="0.8" />
                                        </svg>
                                        <div class="progress-detail">
                                            <p class="mb-2">Total Active Users</p>
                                            <h4 class="counter">{{ $totalUsers ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="-12 m-3 col-12 col-sm-6 col-md-3 col-lg-2 card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{route('users.index', ['status' => 'inactive'])}}">
                                <div class="card-body dashboard-card">
                                    <div class="progress-widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 16 16">
                                            <circle cx="8" cy="8" r="8" fill="#3a57e824" />
                                            <!-- Main user -->
                                            <circle cx="8" cy="6" r="2" fill="#3a57e8" opacity="0.5" />
                                            <path d="M5 12.5c0-1.7 1.3-3 3-3s3 1.3 3 3" fill="#3a57e8" opacity="0.5" />
                                            <!-- Left user -->
                                            <circle cx="4.5" cy="7" r="1.5" fill="#3a57e8" opacity="0.4" />
                                            <path d="M2 13c0-1.4 1-2.5 2.5-2.5s2.5 1.1 2.5 2.5" fill="#3a57e8" opacity="0.4" />
                                            <!-- Right user -->
                                            <circle cx="11.5" cy="7" r="1.5" fill="#3a57e8" opacity="0.4" />
                                            <path d="M9 13c0-1.4 1-2.5 2.5-2.5s2.5 1.1 2.5 2.5" fill="#3a57e8" opacity="0.4" />
                                            <!-- Inactive indicator (diagonal line) -->
                                            <line x1="3" y1="3" x2="13" y2="13" stroke="#3a57e8" stroke-width="1.5" />
                                        </svg>
                                        <div class="progress-detail">
                                            <p class="mb-2">Total In-active Users</p>
                                            <h4 class="counter">{{ $totalInactiveUsers ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
