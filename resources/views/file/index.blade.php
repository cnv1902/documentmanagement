@extends('layouts.app')

@section('title', 'Quản lý File')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Danh sách File</h4>
                    </div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createFileModal">
                        <i class="las la-plus"></i> Upload File
                    </button>
                </div>
                <div class="card-body">
                    @php
                        // Fallback nếu controller chưa truyền đủ dữ liệu
                        $catalogs = $catalogs ?? \App\Models\Catalog::orderBy('name')->get();
                        $authors = $authors ?? \App\Models\Author::orderBy('name')->get();
                        $publishers = $publishers ?? \App\Models\Publisher::orderBy('name')->get();
                    @endphp
                    <form method="get" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm kiếm file...">
                            </div>
                            <div class="col-md-2">
                                <select name="catalog_id" class="form-control">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($catalogs as $catalog)
                                        <option value="{{ $catalog->id }}" {{ request('catalog_id') == $catalog->id ? 'selected' : '' }}>
                                            {{ $catalog->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="publisher_id" class="form-control">
                                    <option value="">Tất cả NXB</option>
                                    @foreach($publishers as $publisher)
                                        <option value="{{ $publisher->id }}" {{ request('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                            {{ $publisher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="approved" class="form-control">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>Đã phê duyệt</option>
                                    <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>Chưa phê duyệt</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                                <a href="{{ route('files.index') }}" class="btn btn-secondary">Xóa lọc</a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Tên File</th>
                                    <th>Tác giả</th>
                                    <th>Nhà xuất bản</th>
                                    <th>Danh mục</th>
                                    <th>Kích thước</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($files as $file)
                                <tr>
                                    <td>{{ $file->name }}</td>
                                    <td>
                                        @foreach($file->authors as $author)
                                            <span class="badge badge-info">{{ $author->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $file->publisher?->name ?? '-' }}</td>
                                    <td>{{ $file->catalog?->name ?? '-' }}</td>
                                    <td>{{ number_format($file->size / 1024, 2) }} KB</td>
                                    <td>
                                        @if($file->approved)
                                            <span class="badge badge-success">Đã phê duyệt</span>
                                        @else
                                            <span class="badge badge-warning">Chưa phê duyệt</span>
                                        @endif
                                        @if($file->is_favourite)
                                            <span class="badge badge-danger">Yêu thích</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning mr-1" data-toggle="modal" data-target="#editFileModal-{{ $file->id }}">Sửa</button>
                                        <a href="{{ Storage::url($file->path) }}" target="_blank" class="btn btn-sm btn-info">Xem</a>
                                        <form action="{{ route('files.destroy', $file) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xoá?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có file nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $files->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createFileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload File Mới</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- add owner fallback --}}
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Chọn File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control-file" name="file" required>
                        <small class="form-text text-muted">Kích thước tối đa: 50MB</small>
                    </div>
                    <div class="form-group">
                        <label>Tên File <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Danh mục</label>
                        <select class="form-control" name="catalog_id">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($catalogs as $catalog)
                                <option value="{{ $catalog->id }}">{{ $catalog->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tác giả <span class="text-danger">*</span></label>
                        <select class="form-control" name="author_ids[]" multiple required size="5">
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Giữ Ctrl (hoặc Cmd) để chọn nhiều</small>
                    </div>
                    <div class="form-group">
                        <label>Nhà xuất bản</label>
                        <select class="form-control" name="publisher_id">
                            <option value="">-- Chọn nhà xuất bản --</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" name="approved" value="1" id="approved">
                        <label class="form-check-label" for="approved">Phê duyệt ngay</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($files as $file)
<!-- Edit Modal -->
<div class="modal fade" id="editFileModal-{{ $file->id }}" tabindex="-1" role="dialog" aria-labelledby="editFileLabel-{{ $file->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFileLabel-{{ $file->id }}">Chỉnh sửa File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('files.update', $file) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">File gốc</label>
                        <div class="alert alert-info">
                            {{ $file->original_name ?? $file->filename }} ({{ number_format($file->size / 1024, 2) }} KB)
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="file-name-{{ $file->id }}" class="font-weight-bold">Tên File <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="file-name-{{ $file->id }}" name="name"
                            value="{{ old('name', $file->name) }}" required placeholder="Nhập tên file">
                    </div>
                    <div class="form-group mb-3">
                        <label for="catalog-{{ $file->id }}" class="font-weight-bold">Danh mục</label>
                        <select class="form-control form-control-lg" id="catalog-{{ $file->id }}" name="catalog_id">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($catalogs as $catalog)
                                <option value="{{ $catalog->id }}" {{ old('catalog_id', $file->catalog_id) == $catalog->id ? 'selected' : '' }}>
                                    {{ $catalog->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="authors-{{ $file->id }}" class="font-weight-bold">Tác giả <span class="text-danger">*</span></label>
                        <select class="form-control form-control-lg" id="authors-{{ $file->id }}" name="author_ids[]" multiple required size="6">
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}"
                                    {{ in_array($author->id, old('author_ids', $file->authors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều tác giả</small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="publisher-{{ $file->id }}" class="font-weight-bold">Nhà xuất bản</label>
                        <select class="form-control form-control-lg" id="publisher-{{ $file->id }}" name="publisher_id">
                            <option value="">-- Chọn nhà xuất bản --</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}" {{ old('publisher_id', $file->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                    {{ $publisher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="favourite-{{ $file->id }}" name="is_favourite" value="1"
                            {{ old('is_favourite', $file->is_favourite) ? 'checked' : '' }}>
                        <label class="form-check-label font-weight-bold" for="favourite-{{ $file->id }}">Yêu thích</label>
                    </div>
                    <div class="form-group form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="approved-{{ $file->id }}" name="approved" value="1"
                            {{ old('approved', $file->approved) ? 'checked' : '' }}>
                        <label class="form-check-label font-weight-bold" for="approved-{{ $file->id }}">Đã phê duyệt</label>
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
@endforeach
@endsection

