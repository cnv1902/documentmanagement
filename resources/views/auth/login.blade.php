@extends('layouts.auth')

@section('title', 'Đăng nhập | EduLib')

@section('content')
<section class="login-content">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center height-self-center">
            <div class="col-md-5 col-sm-12 col-12 align-self-center">
                <div class="sign-user_card">
                    <img src="{{ asset('assets/images/HeaderIcon.svg') }}" class="img-fluid rounded-normal light-logo logo" alt="logo">
                    <h3 class="mb-3">Đăng nhập</h3>
                    <p>Đăng nhập để giữ kết nối.</p>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="floating-label form-group">
                                    <input class="floating-input form-control @error('email') is-invalid @enderror" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder=" " 
                                           required 
                                           autofocus>
                                    <label>Email</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
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
                                <div class="custom-control custom-checkbox mb-3 text-left">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customCheck1">Ghi nhớ đăng nhập</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('password.request') }}" class="text-primary float-right">Quên mật khẩu?</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Đăng nhập</button>
                        <p class="mt-3">
                            Tạo tài khoản mới <a href="{{ route('register') }}" class="text-primary">Đăng ký</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
