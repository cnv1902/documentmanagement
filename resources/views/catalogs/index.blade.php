@extends('layouts.app')

@section('title', 'Danh mục tài liệu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Danh mục tài liệu</h4>
                    </div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createCatalogModal">
                        <i class="las la-plus"></i> Thêm danh mục
                    </button>
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-sm-6 col-md-6">
                            <form method="get" class="mr-3 position-relative">
                                <div class="form-group mb-0">
                                    <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Tìm kiếm danh mục...">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mt-4">
                            <thead>
                                <tr>
                                    <th>Tên danh mục</th>
                                    <th>Mô tả</th>
                                    <th>Trạng thái</th>
                                    <th>Tệp đính kèm</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($catalogs as $catalog)
                                <tr>
                                    <td>{{ $catalog->name }}</td>
                                    <td class="text-muted">{{ Str::limit($catalog->description, 80) }}</td>
                                    <td>
                                        <span class="badge {{ $catalog->is_active ? 'iq-bg-primary' : 'iq-bg-danger' }}">
                                            {{ $catalog->is_active ? 'Đang hoạt động' : 'Ngưng hoạt động' }}
                                        </span>
                                    </td>
                                    <td>{{ $catalog->files()->count() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-sm btn-outline-primary mr-2" data-toggle="modal" data-target="#editCatalogModal-{{ $catalog->id }}">
                                                <i class="ri-pencil-line"></i> Sửa
                                            </button>
                                            <form method="post" action="{{ route('catalogs.toggle', $catalog) }}" class="mr-2">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-warning" type="submit">
                                                    <i class="ri-refresh-line"></i> {{ $catalog->is_active ? 'Ngưng' : 'Kích hoạt' }}
                                                </button>
                                            </form>
                                            <form method="post" action="{{ route('catalogs.destroy', $catalog) }}" onsubmit="return confirm('Bạn có chắc muốn xoá danh mục này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                                    <i class="ri-delete-bin-line"></i> Xoá
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editCatalogModal-{{ $catalog->id }}" tabindex="-1" role="dialog" aria-labelledby="editCatalogLabel-{{ $catalog->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCatalogLabel-{{ $catalog->id }}">Chỉnh sửa danh mục</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="post" action="{{ route('catalogs.update', $catalog) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="name-{{ $catalog->id }}">Tên danh mục</label>
                                                        <input type="text" class="form-control" id="name-{{ $catalog->id }}" name="name" value="{{ old('name', $catalog->name) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description-{{ $catalog->id }}">Mô tả</label>
                                                        <textarea class="form-control" id="description-{{ $catalog->id }}" name="description" rows="3">{{ old('description', $catalog->description) }}</textarea>
                                                    </div>
                                                    <div class="form-group form-check">
                                                        <input type="checkbox" class="form-check-input" id="is_active-{{ $catalog->id }}" name="is_active" value="1" {{ $catalog->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_active-{{ $catalog->id }}">Đang hoạt động</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Chưa có danh mục nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $catalogs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCatalogModal" tabindex="-1" role="dialog" aria-labelledby="createCatalogLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCatalogLabel">Thêm danh mục mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('catalogs.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Tên danh mục</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Đang hoạt động</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Tạo</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
