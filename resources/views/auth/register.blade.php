@extends('layouts.auth')

@section('title', 'Đăng ký | EduLib')

@section('content')
<section class="login-content">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center height-self-center">
            <div class="col-md-5 col-sm-12 col-12 align-self-center">
                <div class="sign-user_card">
                    <img src="{{ asset('assets/images/HeaderIcon.svg') }}" class="img-fluid rounded-normal light-logo logo" alt="logo">
                    <h3 class="mb-3">Đăng ký</h3>
                    <p>Tạo tài khoản của bạn.</p>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="floating-label form-group">
                                    <input class="floating-input form-control @error('first_name') is-invalid @enderror" 
                                           type="text" 
                                           name="first_name" 
                                           value="{{ old('first_name') }}" 
                                           placeholder=" " 
                                           required 
                                           autofocus>
                                    <label>Tên</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="floating-label form-group">
                                    <input class="floating-input form-control @error('last_name') is-invalid @enderror" 
                                           type="text" 
                                           name="last_name" 
                                           value="{{ old('last_name') }}" 
                                           placeholder=" " 
                                           required>
                                    <label>Họ</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="floating-label form-group">
                                    <input class="floating-input form-control @error('email') is-invalid @enderror" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder=" " 
                                           required>
                                    <label>Email</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="floating-label form-group">
                                    <input class="floating-input form-control @error('password') is-invalid @enderror" 
                                           type="password" 
                                           name="password" 
                                           placeholder=" " 
                                           required>
                                    <label>Mật khẩu</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="floating-label form-group">
                                    <input class="floating-input form-control" 
                                           type="password" 
                                           name="password_confirmation" 
                                           placeholder=" " 
                                           required>
                                    <label>Nhập lại mật khẩu</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="custom-control custom-checkbox mb-3 text-left">
                                    <input type="checkbox" class="custom-control-input @error('terms') is-invalid @enderror" id="customCheck1" name="terms" required>
                                    <label class="custom-control-label" for="customCheck1">Tôi đồng ý với các điều khoản sử dụng</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Đăng ký</button>
                        <p class="mt-3">
                            Đã có tài khoản <a href="{{ route('login') }}" class="text-primary">Đăng nhập</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
