@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="login card">
                <!-- <div class="card-header">{{ __('Register') }}</div> -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="logo-login-cont">
                                <div class="login-login-img"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <h3>Account Signup</h3>
                                <div class="form-group row">

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }} action-text-input" placeholder="Name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} action-text-input" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email">

                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} action-text-input" placeholder="Password" name="password" required autocomplete="new-password">

                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>


                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control action-text-input" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div style="width:100%;">
                                        <button type="submit" class="action-btn btn btn-primary">
                                            {{ __('Sign up') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="other-btn-cont">
                                    <a href="/login">Sign in</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection