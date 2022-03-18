@section('title', 'Login')
@extends('layouts.login_app')

@section('content')

<h4 class="card-title mb-1">Welcome to {{ config('app.name') }}! 👋</h4>
<p class="card-text mb-2">Please sign-in to your account and start the working</p>


<form class="auth-login-form mt-2" action="{{ route('accountLogin') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input required type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="john@example.com" aria-describedby="email" tabindex="1" autofocus />
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <div class="d-flex justify-content-between">
            <label for="password">Password</label>
            <a href="{{ route('forgotPassword') }}">
                <small>Forgot Password?</small>
            </a>
        </div>
        <div class="input-group input-group-merge form-password-toggle">
            <input required type="password" class="form-control @error('password') is-invalid @enderror form-control-merge" id="password" name="password" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="input-group-append">
                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" id="remember-me" tabindex="3" />
            <label class="custom-control-label" for="remember-me"> Remember Me </label>
        </div>
    </div>
    <button class="btn btn-primary btn-block" tabindex="4">Sign in</button>
    <br><br>
</form>

<p class="text-center mt-2">
    <span>New on our platform?</span>
    <a href="{{ route('register') }}">
        <span>Create an account</span>
    </a>
</p>

{{-- <div class="divider my-2">
    <div class="divider-text">or</div>
</div>

<div class="auth-footer-btn d-flex justify-content-center">
    <a href="javascript:void(0)" class="btn btn-facebook">
        <i data-feather="facebook"></i>
    </a>
    <a href="javascript:void(0)" class="btn btn-twitter white">
        <i data-feather="twitter"></i>
    </a>
    <a href="javascript:void(0)" class="btn btn-google">
        <i data-feather="mail"></i>
    </a>
    <a href="javascript:void(0)" class="btn btn-github">
        <i data-feather="github"></i>
    </a>
</div> --}}
@endsection