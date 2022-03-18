@section('title', 'Register')
@extends('layouts.login_app')

@section('content')



<h4 class="card-title mb-1">Adventure starts here 🚀</h4>
<p class="card-text mb-2">Make your app management easy and fun!</p>

<form class="auth-register-form mt-2" action="{{ route('accountRegister') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="user_name" class="form-label">User name</label>
        <input value="{{old('user_name')}}" type="text" class="form-control @error('user_name') is-invalid @enderror form-control-merge" id="user_name" name="user_name" placeholder="johndoe" aria-describedby="user_name" tabindex="1" />
        {{-- <input value="{{old('user_name')}}" type="text" class="form-control @error('user_name') is-invalid @enderror form-control-merge" id="user_name" name="user_name" placeholder="johndoe" aria-describedby="user_name" tabindex="1" autofocus /> --}}
        @error('user_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input value="{{old('email')}}" type="text" class="form-control @error('email') is-invalid @enderror form-control-merge" id="email" name="email" placeholder="john@example.com" aria-describedby="email" tabindex="2" />
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password" class="form-label">Password</label>

        <div class="input-group input-group-merge form-password-toggle">
            <input type="password" class="form-control  @error('password') is-invalid @enderror form-control-merge" id="password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" tabindex="3" />
            <div class="input-group-append">
                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="confirm_password" class="form-label">Password</label>

        <div class="input-group input-group-merge form-password-toggle">
            <input type="password" class="form-control  @error('confirm_password') is-invalid @enderror form-control-merge" id="confirm_password" name="confirm_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="confirm_password" tabindex="6" />
            <div class="input-group-append">
                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
        </div>
        @error('confirm_password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" id="register-privacy-policy" tabindex="4" />
            <label class="custom-control-label" for="register-privacy-policy">
                I agree to <a href="javascript:void(0);">privacy policy & terms</a>
            </label>
        </div>
    </div>
    <button class="btn btn-primary btn-block" tabindex="5">Sign up</button>
</form>

<p class="text-center mt-2">
    <span>Already have an account?</span>
    <a href="{{ route('login') }}">
        <span>Sign in instead</span>
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