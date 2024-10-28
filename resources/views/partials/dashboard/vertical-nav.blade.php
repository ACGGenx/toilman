<ul class="navbar-nav iq-main-menu" id="sidebar">
    <li class="nav-item static-item">
        <a class="nav-link static-item disabled" href="#" tabindex="-1">
            <span class="default-icon">Home</span>
            <span class="mini-icon"></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('dashboard'))}}" aria-current="page" href="{{route('dashboard')}}">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.4" d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z" fill="currentColor"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z" fill="currentColor"></path>
                </svg>
            </i>
            <span class="item-name">Dashboard</span>
        </a>
    </li>
    @if(auth()->user()->hasRole('admin'))
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('categories.index'))}}" aria-current="page" href="{{route('categories.index')}}">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3H12L14 5H21C21.55 5 22 5.45 22 6V20C22 20.55 21.55 21 21 21H3C2.45 21 2 20.55 2 20V6C2 5.45 2.45 5 3 5Z" fill="currentColor" />
                    <path opacity="0.6" d="M3 9H21V11H3V9Z" fill="currentColor" />
                    <path opacity="0.6" d="M3 13H21V15H3V13Z" fill="currentColor" />
                    <path opacity="0.6" d="M3 17H21V19H3V17Z" fill="currentColor" />
                </svg>

            </i>
            <span class="item-name">Categories</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('products.index'))}}" aria-current="page" href="{{route('products.index')}}">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 4H17C18.1046 4 19 4.89543 19 6V18C19 19.1046 18.1046 20 17 20H7C5.89543 20 5 19.1046 5 18V6C5 4.89543 5.89543 4 7 4Z" fill="currentColor" />
                    <path d="M3 2H5L7 8H18L20 4H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <path d="M6 22C6.55228 22 7 22.4477 7 23C7 23.5523 6.55228 24 6 24C5.44772 24 5 23.5523 5 23C5 22.4477 5.44772 22 6 22Z" fill="currentColor" />
                    <path d="M18 22C18.5523 22 19 22.4477 19 23C19 23.5523 18.5523 24 18 24C17.4477 24 17 23.5523 17 23C17 22.4477 17.4477 22 18 22Z" fill="currentColor" />
                </svg>
            </i>
            <span class="item-name">Products</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('pages.index'))}}" aria-current="page" href="{{route('pages.index')}}">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4C3 3.44772 3.44772 3 4 3H20C20.5523 3 21 3.44772 21 4V8H3V4Z" fill="currentColor" />
                    <rect x="3" y="9" width="18" height="10" rx="2" ry="2" stroke="currentColor" stroke-width="2" />
                    <circle cx="6" cy="6" r="1" fill="currentColor" />
                    <circle cx="9" cy="6" r="1" fill="currentColor" />
                </svg>
            </i>
            <span class="item-name">pages</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('enquiries.list'))}}" aria-current="page" href="{{route('enquiries.list')}}">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4H20C21.1046 4 22 4.89543 22 6V18C22 19.1046 21.1046 20 20 20H4C2.89543 20 2 19.1046 2 18V6C2 4.89543 2.89543 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 10C12.5523 10 13 9.55228 13 9C13 8.44772 12.5523 8 12 8C11.4477 8 11 8.44772 11 9C11 9.55228 11.4477 10 12 10Z" fill="currentColor" />
                    <path d="M12 12V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </i>
            <span class="item-name">Enqueries</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('sliders.index'))}}" aria-current="page" href="{{route('sliders.index')}}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="8" height="16" fill="currentColor" rx="2" opacity="0.8" />
                    <rect x="7" y="4" width="8" height="16" fill="currentColor" rx="2" opacity="0.6" />
                    <rect x="11" y="4" width="8" height="16" fill="currentColor" rx="2" opacity="0.4" />
                </svg>
            <span class="item-name">Sliders</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('users.index'))}}" aria-current="page" href="{{route('users.index')}}">
            <i class="icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 14C18.2091 14 20 15.7909 20 18V19C20 19.5523 19.5523 20 19 20H5C4.44772 20 4 19.5523 4 19V18C4 15.7909 5.79086 14 8 14H16Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16 14C16 12.8954 15.1046 12 14 12C12.8954 12 12 12.8954 12 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M8 14C8 12.8954 8.89543 12 10 12C11.1046 12 12 12.8954 12 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <circle cx="8" cy="9" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <circle cx="16" cy="9" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

            </i>
            <span class="item-name">Users</span>
        </a>
    </li>
    @elseif(auth()->user()->hasRole('user'))
    @if(isset(auth()->user()->getPermissions()[2]) && (auth()->user()->getPermissions()[2]->permission_value & (2)))
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('categories.index'))}}" aria-current="page" href="{{route('categories.index')}}">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3H12L14 5H21C21.55 5 22 5.45 22 6V20C22 20.55 21.55 21 21 21H3C2.45 21 2 20.55 2 20V6C2 5.45 2.45 5 3 5Z" fill="currentColor" />
                    <path opacity="0.6" d="M3 9H21V11H3V9Z" fill="currentColor" />
                    <path opacity="0.6" d="M3 13H21V15H3V13Z" fill="currentColor" />
                    <path opacity="0.6" d="M3 17H21V19H3V17Z" fill="currentColor" />
                </svg>

            </i>
            <span class="item-name">Categories</span>
        </a>
    </li>
    @endif

    @if(isset(auth()->user()->getPermissions()[3]) && (auth()->user()->getPermissions()[3]->permission_value & (2)))
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('products.index'))}}" aria-current="page" href="{{route('products.index')}}">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 4H17C18.1046 4 19 4.89543 19 6V18C19 19.1046 18.1046 20 17 20H7C5.89543 20 5 19.1046 5 18V6C5 4.89543 5.89543 4 7 4Z" fill="currentColor" />
                    <path d="M3 2H5L7 8H18L20 4H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <path d="M6 22C6.55228 22 7 22.4477 7 23C7 23.5523 6.55228 24 6 24C5.44772 24 5 23.5523 5 23C5 22.4477 5.44772 22 6 22Z" fill="currentColor" />
                    <path d="M18 22C18.5523 22 19 22.4477 19 23C19 23.5523 18.5523 24 18 24C17.4477 24 17 23.5523 17 23C17 22.4477 17.4477 22 18 22Z" fill="currentColor" />
                </svg>
            </i>
            <span class="item-name">Products</span>
        </a>
    </li>
    @endif

    @if(isset(auth()->user()->getPermissions()[4]) && (auth()->user()->getPermissions()[4]->permission_value & (2)))
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('pages.index'))}}" aria-current="page" href="{{route('pages.index')}}">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4C3 3.44772 3.44772 3 4 3H20C20.5523 3 21 3.44772 21 4V8H3V4Z" fill="currentColor" />
                    <rect x="3" y="9" width="18" height="10" rx="2" ry="2" stroke="currentColor" stroke-width="2" />
                    <circle cx="6" cy="6" r="1" fill="currentColor" />
                    <circle cx="9" cy="6" r="1" fill="currentColor" />
                </svg>
            </i>
            <span class="item-name">pages</span>
        </a>
    </li>
    @endif
    @if(isset(auth()->user()->getPermissions()[1]) && (auth()->user()->getPermissions()[1]->permission_value & (2)))
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('enquiries.list'))}}" aria-current="page" href="{{route('enquiries.list')}}">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4H20C21.1046 4 22 4.89543 22 6V18C22 19.1046 21.1046 20 20 20H4C2.89543 20 2 19.1046 2 18V6C2 4.89543 2.89543 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 10C12.5523 10 13 9.55228 13 9C13 8.44772 12.5523 8 12 8C11.4477 8 11 8.44772 11 9C11 9.55228 11.4477 10 12 10Z" fill="currentColor" />
                    <path d="M12 12V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </i>
            <span class="item-name">Enqueries</span>
        </a>
    </li>
    @endif
    @if(isset(auth()->user()->getPermissions()[5]) && (auth()->user()->getPermissions()[1]->permission_value & (2)))
    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('sliders.index'))}}" aria-current="page" href="{{route('sliders.index')}}">
            <i class="icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="8" height="16" fill="currentColor" rx="2" opacity="0.8" />
                    <rect x="7" y="4" width="8" height="16" fill="currentColor" rx="2" opacity="0.6" />
                    <rect x="11" y="4" width="8" height="16" fill="currentColor" rx="2" opacity="0.4" />
                </svg>
            </i>
            <span class="item-name">Sliders</span>
        </a>
    </li>
    @endif
    @endif
</ul>
