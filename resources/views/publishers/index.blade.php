@extends('layouts.app')

@section('title', 'Quản lý nhà xuất bản')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div id="publisher-status"></div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Danh sách nhà xuất bản</h4>
                    </div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createPublisherModal">
                        <i class="las la-plus"></i> Thêm nhà xuất bản
                    </button>
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-sm-6 col-md-6">
                            <form method="get" class="mr-3 position-relative">
                                <div class="form-group mb-0">
                                    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm kiếm nhà xuất bản...">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mt-4">
                            <thead>
                                <tr>
                                    <th>Tên nhà xuất bản</th>
                                    <th>Địa chỉ</th>
                                    <th>Liên hệ</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($publishers as $publisher)
                                <tr>
                                    <td>{{ $publisher->name }}</td>
                                    <td>{{ $publisher->address }}</td>
                                    <td>{{ $publisher->contact }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editPublisherModal-{{ $publisher->id }}">Sửa</button>
                                        <form action="{{ route('publishers.destroy', $publisher) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xoá?');">
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
                    {{ $publishers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createPublisherModal" tabindex="-1" role="dialog" aria-labelledby="createPublisherLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPublisherLabel">Thêm nhà xuất bản mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('publishers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="publisher-name">Tên nhà xuất bản</label>
                        <input type="text" class="form-control" id="publisher-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="publisher-address">Địa chỉ</label>
                        <input type="text" class="form-control" id="publisher-address" name="address">
                    </div>
                    <div class="form-group">
                        <label for="publisher-contact">Liên hệ</label>
                        <input type="text" class="form-control" id="publisher-contact" name="contact">
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
@foreach($publishers as $publisher)
<div class="modal fade" id="editPublisherModal-{{ $publisher->id }}" tabindex="-1" role="dialog" aria-labelledby="editPublisherLabel-{{ $publisher->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPublisherLabel-{{ $publisher->id }}">Chỉnh sửa nhà xuất bản</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('publishers.update', $publisher) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="publisher-name-{{ $publisher->id }}">Tên nhà xuất bản</label>
                        <input type="text" class="form-control" id="publisher-name-{{ $publisher->id }}" name="name" value="{{ old('name', $publisher->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="publisher-address-{{ $publisher->id }}">Địa chỉ</label>
                        <textarea class="form-control" id="publisher-address-{{ $publisher->id }}" name="address" rows="3">{{ old('address', $publisher->address) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="publisher-contact-{{ $publisher->id }}">Liên hệ</label>
                        <input type="text" class="form-control" id="publisher-contact-{{ $publisher->id }}" name="contact" value="{{ old('contact', $publisher->contact) }}">
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
