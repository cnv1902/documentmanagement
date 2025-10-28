@extends('layouts.app')

@section('title', 'Quản lý tác giả')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div id="author-status"></div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Danh sách tác giả</h4>
                    </div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createAuthorModal">
                        <i class="las la-plus"></i> Thêm tác giả
                    </button>
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-sm-6 col-md-6">
                            <form method="get" class="mr-3 position-relative">
                                <div class="form-group mb-0">
                                    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm kiếm tác giả...">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mt-4">
                            <thead>
                                <tr>
                                    <th>Tên tác giả</th>
                                    <th style="width: 50%;">Tiểu sử</th>
                                    <th>Liên hệ</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($authors as $author)
                                <tr>
                                    <td>{{ $author->name }}</td>
                                    <td>{{ $author->bio }}</td>
                                    <td>{{ $author->contact }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editAuthorModal-{{ $author->id }}">Sửa</button>
                                        <form action="{{ route('authors.destroy', $author) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xoá?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $authors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createAuthorModal" tabindex="-1" role="dialog" aria-labelledby="createAuthorLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAuthorLabel">Thêm tác giả mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('authors.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="author-name">Tên tác giả</label>
                        <input type="text" class="form-control" id="author-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="author-bio">Tiểu sử</label>
                        <textarea class="form-control" id="author-bio" name="bio" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="author-contact">Liên hệ</label>
                        <input type="text" class="form-control" id="author-contact" name="contact">
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

{{-- Render edit modals OUTSIDE the table --}}
@foreach($authors as $author)
<div class="modal fade" id="editAuthorModal-{{ $author->id }}" tabindex="-1" role="dialog" aria-labelledby="editAuthorLabel-{{ $author->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAuthorLabel-{{ $author->id }}">Chỉnh sửa tác giả</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('authors.update', $author) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="author-name-{{ $author->id }}">Tên tác giả</label>
                        <input type="text" class="form-control" id="author-name-{{ $author->id }}" name="name" value="{{ old('name', $author->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="author-bio-{{ $author->id }}">Tiểu sử</label>
                        <textarea class="form-control" id="author-bio-{{ $author->id }}" name="bio" rows="3">{{ old('bio', $author->bio) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="author-contact-{{ $author->id }}">Liên hệ</label>
                        <input type="text" class="form-control" id="author-contact-{{ $author->id }}" name="contact" value="{{ old('contact', $author->contact) }}">
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
