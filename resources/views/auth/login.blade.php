@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row no-gutter">
        <!-- The image half -->
        <div class="col-md-6 d-none d-md-flex bg-image">
            <img src="https://ahmadproject.org/assets/images/pexels-alena-darmel-8164742-1.jpg" class="media-object" >
            <img src="https://ahmadproject.org/assets/images/logo-white.svg" style="position: absolute;margin-top: 500px;margin-bottom: 131px;margin-right: 185px;margin-left: 175px;" />
        </div>

        <!-- The content half -->
        <div class="col-md-6 bg-light">
            <div class="login d-flex align-items-center py-5">

                <!-- Demo content-->
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 col-xl-7 mx-auto">
                            <h3 class="display-4">{{ __('Login') }}</h3>
                            <p class="text-muted mb-4">AHMaD Project Dashboard Manager</p>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="email">{{ __('E-Mail Address') }}</label>
                                    <input id="email"  name="email" type="email" placeholder="Email address" required="" autofocus="" class="form-control rounded-pill border-0 shadow-sm px-4 @error('email') is-invalid @enderror">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input id="password" type="password" class="form-control rounded-pill border-0 shadow-sm px-4 text-primary @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                {{-- <button type="submit" class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm">Sign in</button> --}}
                                <button type="submit" class="btn btn-warning btn-block text-uppercase mb-2 rounded-pill shadow-sm">
                                    {{ __('Login') }}
                                </button>
                                {{-- <div class="text-center d-flex justify-content-between mt-4"><p>Snippet by <a href="https://bootstrapious.com/snippets" class="font-italic text-muted"> 
                                        <u>Boostrapious</u></a></p></div> --}}
                            </form>
                        </div>
                    </div>
                </div><!-- End -->

            </div>
        </div><!-- End -->

    </div>
</div>

@endsection
