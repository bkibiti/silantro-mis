<!DOCTYPE html>
<html lang="en">

<head>
    <title>Smart Pub | Login</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <meta name="author" content="CodedThemes" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{asset("apotek.ico")}}" type="image/x-icon">

    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{asset("assets/fonts/fontawesome/css/fontawesome-all.min.css")}}">

    <!-- animation css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/animation/css/animate.min.css")}}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{asset("assets/css/style.css")}}">


</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-content">

            <div class="card">
                <div class="card-body text-center">

                    <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <i class="feather icon-unlock auth-icon"></i>

                            </div>

                            <h3 class="mb-4">Login</h3>
                            <div class="input-group mb-3">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required placeholder="Username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="input-group mb-4">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group text-left">
                                <div class="checkbox checkbox-fill d-inline">
                                     <input class="form-check-input" type="checkbox" name="remember" id="remember"  {{ old('remember') ? 'checked' : '' }}>

                                    <label class="cr" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                                <button type="submit" class="btn btn-primary shadow-2 mb-4">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))

                                    <p class="mb-2 text-muted">Forgot password? <a href="{{ route('password.request') }}">Reset</a></p>

                                @endif

                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="{{asset("assets/js/vendor-all.min.js")}}"></script>
    <script src="{{asset("assets/plugins/bootstrap/js/bootstrap.min.js")}}"></script>
    <script src="{{asset("assets/js/pcoded.min.js")}}"></script>


</body>
</html>
