<aside class="sidebar sidebar-default navs-rounded-all">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="{{route('dashboard')}}" class="navbar-brand">
            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 4H17C18.1046 4 19 4.89543 19 6V18C19 19.1046 18.1046 20 17 20H7C5.89543 20 5 19.1046 5 18V6C5 4.89543 5.89543 4 7 4Z" fill="currentColor" />
                <path d="M3 2H5L7 8H18L20 4H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <path d="M6 22C6.55228 22 7 22.4477 7 23C7 23.5523 6.55228 24 6 24C5.44772 24 5 23.5523 5 23C5 22.4477 5.44772 22 6 22Z" fill="currentColor" />
                <path d="M18 22C18.5523 22 19 22.4477 19 23C19 23.5523 18.5523 24 18 24C17.4477 24 17 23.5523 17 23C17 22.4477 17.4477 22 18 22Z" fill="currentColor" />
            </svg>

            <h4 class="logo-title">{{env('APP_NAME')}}</h4>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>

    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list" id="sidebar">
            @include('partials.dashboard.vertical-nav')
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside>
