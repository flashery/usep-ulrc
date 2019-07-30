@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="login card">
                <!-- <div class="card-header">{{ __('Login') }}</div> -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="logo-login-cont">
                                <div class="login-login-img"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <h3>Account Login</h3>

                                <div class="form-group row">

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} action-text-input " placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} action-text-input " placeholder="Password" name="password" required autocomplete="current-password">

                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <div style="width:100%;">
                                        <button type="submit" class="action-btn btn btn-primary">
                                            {{ __('Sign in') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="form-check" style="text-align:right;">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        @if (Route::has('password.request'))
                                        <a class="btn btn-link" style="padding: 0 5px;" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="other-btn-cont">
                                    <a href="/register">Sign up</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> @endsection