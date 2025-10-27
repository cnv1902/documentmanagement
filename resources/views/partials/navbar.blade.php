<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                <i class="ri-menu-line wrapper-menu"></i>
                <a href="{{ route('dashboard') }}" class="header-logo">
                    <img src="{{ asset('assets/images/HeaderIcon.svg') }}" class="img-fluid rounded-normal light-logo" alt="logo">                </a>
            </div>
            <div class="iq-search-bar device-search">
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-prepend input-append">
                        <div class="btn-group">
                            <label class="dropdown-toggle searchbox" data-toggle="dropdown">
                                <input class="dropdown-toggle search-query text search-input" type="text" name="q" placeholder="Search..." value="{{ request('q') }}">
                                <span class="search-replace"></span>
                                <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-list align-items-center">
                        <li class="nav-item nav-icon search-content">
                            <a href="#" class="search-toggle rounded" id="dropdownSearch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-search-line"></i>
                            </a>
                            <div class="iq-search-bar iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownSearch">
                                <form action="{{ route('search') }}" method="GET" class="searchbox p-2">
                                    <div class="form-group mb-0 position-relative">
                                        <input type="text" class="text search-input font-size-12" name="q" placeholder="Type here to search..." value="{{ request('q') }}">
                                        <a href="#" class="search-link"><i class="las la-search"></i></a>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <li class="nav-item nav-icon dropdown">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-notification-line"></i>
                                <span class="bg-primary"></span>
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0">
                                        <div class="p-3">
                                            <h6 class="mb-0">Thông báo</h6>
                                        </div>
                                        <div class="cust-scroll">
                                            @forelse(auth()->user()->unreadNotifications ?? [] as $notification)
                                            <a href="{{ $notification->data['url'] ?? '#' }}" class="iq-sub-card">
                                                <div class="media align-items-center">
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                                        <small class="float-right font-size-12">{{ $notification->created_at->diffForHumans() }}</small>
                                                        <p class="mb-0">{{ $notification->data['message'] ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                            @empty
                                            <div class="p-3 text-center">
                                                <p class="mb-0">Không có thông báo</p>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item nav-icon dropdown caption-content">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ auth()->user()->avatar ?? asset('assets/images/user/1.jpg') }}" class="img-fluid rounded" alt="user">
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 text-center">
                                        <div class="media-body profile-detail text-center">
                                            <img src="{{ auth()->user()->avatar ?? asset('assets/images/user/1.jpg') }}" alt="profile-bg" class="rounded-circle img-fluid mb-4">
                                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                            <p class="mb-0">{{ auth()->user()->email }}</p>
                                        </div>
                                        <div class="p-3">
                                            <a href="{{ route('profile') }}" class="iq-sub-card iq-bg-primary-hover">
                                                <div class="media align-items-center">
                                                    <div class="rounded card-icon bg-soft-primary mr-3">
                                                        <i class="las la-user-cog"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="mb-0" style="float:left">Chỉnh sửa thông tin</h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="{{ route('settings') }}" class="iq-sub-card iq-bg-primary-hover">
                                                <div class="media align-items-center">
                                                    <div class="rounded card-icon bg-soft-primary mr-3">
                                                        <i class="las la-cog"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="mb-0" style="float:left">Cài đặt tài khoản</h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="iq-sub-card iq-bg-primary-hover w-100 text-left border-0 bg-transparent">
                                                    <div class="media align-items-center">
                                                        <div class="rounded card-icon bg-soft-primary mr-3">
                                                            <i class="las la-sign-out-alt"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="mb-0">Đăng xuất</h6>
                                                        </div>
                                                    </div>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
