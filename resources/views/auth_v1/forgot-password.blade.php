@section('title', 'Forget Password')
@extends('layouts.login_app')

@section('content')



<h4 class="card-title mb-1">Forgot Password? 🔒</h4>
<p class="card-text mb-2">Enter your email and we'll send you instructions to reset your password</p>

<form class="auth-forgot-password-form mt-2" action="{{ route('accountResetPassword') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror form-control-merge" id="email" name="email" placeholder="john@example.com" aria-describedby="email" tabindex="1" autofocus />
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <div class="g-recaptcha brochure__form__captcha" data-sitekey="6LcYyE4iAAAAANhYvwVQtyK0WL1ZMODEppS4ZKy9"></div>
    <br>
    {{-- <button class="btn btn-primary btn-block" tabindex="2">Send reset link</button> --}}
    <button class="btn btn-primary btn-block" type="submit">Reset password</button>
</form>

<p class="text-center mt-2">
    <a href="{{ route('sp-login') }}"> <i data-feather="chevron-left"></i> Back to login </a>
</p>

@endsection