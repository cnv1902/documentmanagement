<div class="iq-sidebar sidebar-default">
    <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="header-logo">
            <img src="{{ asset('assets/images/HeaderIcon.svg') }}" class="img-fluid rounded-normal light-logo" alt="logo">
        </a>
        <div class="iq-menu-bt-sidebar">
            <i class="las la-bars wrapper-menu"></i>
        </div>
    </div>
    <div class="data-scrollbar" data-scroll="1">
        <div class="new-create select-dropdown input-prepend input-append">
            <div class="btn-group">
                <div data-toggle="dropdown">
                    <div class="search-query selet-caption"><i class="las la-plus pr-2"></i>Tạo mới</div>
                    <span class="search-replace"></span>
                    <span class="caret"><!--icon--></span>
                </div>
                <ul class="dropdown-menu">
                    <li>
                        <a class="item" href="{{ route('catalogs.index') }}">
                            <i class="ri-folder-add-line pr-3"></i>Danh mục mới
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="">
                        <i class="las la-home iq-arrow-left"></i><span>Bảng điều khiển</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('catalogs.*') ? 'active' : '' }}">
                    <a href="{{ route('catalogs.index') }}" class="">
                        <i class="las la-folder-open"></i><span>Danh mục tài liệu</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('publishers.*') ? 'active' : '' }}">
                    <a href="{{ route('publishers.index') }}" class="">
                        <i class="las la-brush"></i><span>Nhà xuất bản</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('authors.*') ? 'active' : '' }}">
                    <a href="{{ route('authors.index') }}" class="">
                        <i class="las la-id-card"></i><span>Tác giả</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('files.index') ? 'active' : '' }}">
                    <a href="{{ route('files.index') }}" class="">
                        <i class="lar la-file-alt iq-arrow-left"></i><span>Tệp</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('recent') ? 'active' : '' }}">
                    <a href="{{ route('recent') }}" class="">
                        <i class="las la-stopwatch iq-arrow-left"></i><span>Gần đây</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('favourites') ? 'active' : '' }}">
                    <a href="{{ route('favourites') }}" class="">
                        <i class="lar la-star"></i><span>Favourite</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('trash') ? 'active' : '' }}">
                    <a href="{{ route('trash') }}" class="">
                        <i class="las la-trash-alt iq-arrow-left"></i><span>Rác</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="sidebar-bottom">
            <h4 class="mb-3"><i class="las la-cloud mr-2"></i>Lưu trữ</h4>
            <p>{{ formatBytes(auth()->user()->storage_used ?? 0) }} / {{ formatBytes(auth()->user()->storage_limit ?? 21474836480) }} Đã sử dụng</p>
            <div class="iq-progress-bar mb-3">
                <span class="bg-primary iq-progress progress-1" data-percent="{{ auth()->user()->storagePercent() ?? 0 }}">
                </span>
            </div>
            <p>{{ auth()->user()->storagePercent() ?? 0 }}% Full - {{ formatBytes((auth()->user()->storage_limit ?? 21474836480) - (auth()->user()->storage_used ?? 0)) }} Free</p>
            <a href="{{ route('storage.upgrade') }}" class="btn btn-outline-primary view-more mt-4">Mua ổ đĩa</a>
        </div>
        <div class="p-3"></div>
    </div>
</div>
