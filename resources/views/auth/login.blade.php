@extends('layouts.app')
@section('body-css','login-page')
@section('content')
<div class="container">
        <div class="login-box">
                <div class="login-logo">
    <!-- Brand Logo -->
            {{-- <a href="" class="brand-link">
            <img src="{{ asset('img/checkup.png') }}" alt="Medcheck Logo" class="brand-image img-rectangle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">Medcheck</span>
          </a> --}}
          <div class="col-1">
                <img src="{{ asset('img/checkup.png') }}" alt="Medcheck Logo" class="brand-image img-rectangle elevation-3"
                style="opacity: .8">              
          </div>
          <div class="col-11"><b>{{ config('app.name', 'Laravel') }}</b></div>

                  
                </div>
                <!-- /.login-logo -->
                <div class="card">
                  <div class="card-body login-card-body">
                    <p class="login-box-msg">{{ __('ログイン画面') }}</p>
              
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                      <div class="input-group mb-3">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                        name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="ユーザー名" autofocus>                          
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        {{-- <input type="email" class="form-control" placeholder="Email"> --}}
                        <div class="input-group-append input-group-text">
                            <span class="fa fa-user"></span>
                        </div>
                      </div>
                      <div class="input-group mb-3">

                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                        name="password" required autocomplete="current-password"  placeholder="パスワード">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror       

                        {{-- <input type="password" class="form-control" placeholder="Password"> --}}
                        <div class="input-group-append input-group-text">
                            <span class="fa fa-lock"></span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-8">
                          <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                {{ __('ログインしたままにする') }}
                            </label>
                            {{-- <input type="checkbox" id="remember">
                            <label for="remember">
                              Remember Me
                            </label> --}}
                          </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                          <button type="submit" class="btn btn-primary btn-block btn-flat">サインイン</button>
                        </div>
                        <!-- /.col -->
                      </div>
                    </form>

                    <div class="row mt-5">
                        <div class="col-12">
                    <p class="mb-1">
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                            {{ __('パスワードを忘れた方はこちら') }}
                        </a>
                        @endif                        
                      {{-- <a href="#">I forgot my password</a> --}}
                    </p>
                    <p class="mb-0">
                    @if (Route::has('register'))
                            <a class="text-center" href="{{ route('register') }}">{{ __('ユーザー新規登録') }}</a>
                    @endif                        
                      {{-- <a href="register.html" class="text-center">Register a new membership</a> --}}
                    </p>
                </div></div>

                  </div>
                  <!-- /.login-card-body -->
                </div>
              </div>


    {{-- <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('ユーザー名') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection
