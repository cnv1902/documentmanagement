@extends('layouts.app')

@section('title', 'Bảng điều khiển | EduLib')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-transparent card-block card-stretch card-height mb-3">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Bảng điều khiển</h4>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card card-block card-stretch card-height iq-welcome" style="background: url({{ asset('assets/images/layouts/mydrive/background.png') }}) no-repeat scroll right center; background-color: #ffffff; background-size: contain;">
                <div class="card-body property2-content">
                    <h3 class="mb-3">Xin chào {{ auth()->user()->name }}!</h3>
                    <p class="mb-3">Bảng điều khiển Website lưu trữ của bạn đã sẵn sàng</p>
                    <a href="{{ route('files.create') }}" class="btn btn-primary">Tải lên 1 tệp mới</a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="iq-cart-text text-capitalize">
                            <p class="mb-0 font-size-14">Tổng số tệp</p>
                        </div>
                        <div class="icon iq-icon-box-top rounded-circle bg-primary">
                            <i class="las la-file-alt"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-3">
                        <h4 class="mb-0">{{ $stats['total_files'] ?? 0 }}</h4>
                        <p class="mb-0 text-primary">
                            <span><i class="las la-arrow-up mr-0"></i></span>{{ $stats['new_files_today'] ?? 0 }} Hôm nay
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="card card-block card-stretch card-transparent">
                <div class="card-header d-flex justify-content-between pb-0">
                    <div class="header-title">
                        <h4 class="card-title">Danh mục</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <a href="{{ route('catalogs.index') }}" class="text-primary">Xem tất cả</a>
                    </div>
                </div>
            </div>
        </div>

        @foreach($catalogs ?? [] as $catalog)
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="folder">
                            <div class="icon-small bg-primary mb-4 rounded">
                                <i class="ri-folder-open-line"></i>
                            </div>
                            <h6 class="mb-1">{{ $catalog->name }}</h6>
                            <p class="mb-2 text-muted small">{{ Str::limit($catalog->description, 60) }}</p>
                        </div>
                        <div class="card-header-toolbar">
                            <a href="{{ route('catalogs.index') }}" class="btn btn-sm btn-outline-primary">Quản lý</a>
                        </div>
                    </div>
                    <p class="mb-2">{{ $catalog->files_count ?? 0 }} Files</p>
                    <p class="mb-0"><small>{{ $catalog->updated_at->diffForHumans() }}</small></p>
                </div>
            </div>
        </div>
        @endforeach
        
        <div class="col-lg-12">
            <div class="card card-block card-stretch card-transparent">
                <div class="card-header d-flex justify-content-between pb-0">
                    <div class="header-title">
                        <h4 class="card-title">Các tập tin gần đây</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <a href="{{ route('files.index') }}" class="text-primary">Xem tất cả</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 col-xl-8">
            <div class="card card-block card-stretch card-height files-table">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Kích thước</th>
                                    <th scope="col">Đã sửa đổi</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentFiles ?? [] as $file)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-small bg-primary rounded mr-3">
                                                <i class="{{ getFileIcon($file->mime_type) }}"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $file->name }}</h6>
                                                <p class="mb-0 font-size-12">{{ $file->mime_type }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ formatBytes($file->size) }}</td>
                                    <td>{{ $file->updated_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('files.download', $file->id) }}" class="btn btn-sm btn-primary mr-2">
                                                <i class="las la-download"></i>
                                            </a>
                                            <a href="{{ route('files.show', $file->id) }}" class="btn btn-sm btn-info mr-2">
                                                <i class="las la-eye"></i>
                                            </a>
                                            <button onclick="deleteFile({{ $file->id }})" class="btn btn-sm btn-danger">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không tìm thấy tập tin</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Trạng thái lưu trữ</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="iq-progress-bar mb-3">
                        <span class="bg-primary iq-progress progress-1" data-percent="{{ auth()->user()->storagePercent() ?? 0 }}">
                        </span>
                    </div>
                    <p>{{ formatBytes(auth()->user()->storage_used ?? 0) }} of {{ formatBytes(auth()->user()->storage_limit ?? 21474836480) }} đã sử dụng</p>
                    <div class="storage-detail mt-3">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="las la-file-alt text-primary"></i> Tài liệu: {{ formatBytes($stats['documents_size'] ?? 0) }}
                            </li>
                            <li class="mb-2">
                                <i class="las la-image text-success"></i> Ảnh: {{ formatBytes($stats['images_size'] ?? 0) }}
                            </li>
                            <li class="mb-2">
                                <i class="las la-video text-warning"></i> Video: {{ formatBytes($stats['videos_size'] ?? 0) }}
                            </li>
                            <li class="mb-2">
                                <i class="las la-file-archive text-info"></i> Khác: {{ formatBytes($stats['others_size'] ?? 0) }}
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('storage.upgrade') }}" class="btn btn-primary btn-block mt-3">Nâng cấp kho lưu trữ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCatalog(id) {
    if (confirm('Bạn có chắc muốn xoá danh mục này?')) {
        fetch(`/api/catalogs/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(async (response) => {
            const data = await response.json().catch(() => ({}));
            if (response.ok) {
                location.reload();
            } else {
                alert(data.message || 'Không thể xoá danh mục');
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra');
            console.error(error);
        });
    }
}

function deleteFile(id) {
    if (confirm('Are you sure you want to delete this file?')) {
        fetch(`/api/files/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete file');
            }
        })
        .catch(error => {
            alert('An error occurred');
            console.error(error);
        });
    }
}
</script>
@endpush
