<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --gradient-start: #1e3a8a;
            --gradient-end: #3b82f6;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .login-card {
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        .login-brand {
            background: rgba(255,255,255,0.1);
            padding: 1rem 2rem;
            text-align: center;
        }
        .login-brand .login-logo {
            max-height: 100px;
            max-width: 260px;
            width: 100%;
            object-fit: contain;
            display: block;
            margin: 0 auto 0.5rem;
        }
        .login-brand h1 {
            color: #fff;
            font-weight: 700;
            margin: 0;
        }
        .login-brand p {
            color: rgba(255,255,255,0.8);
            margin: 0.25rem 0 0;
        }
        .login-body {
            background: #fff;
            padding: 1rem 2.5rem 2.5rem;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1e40af 100%);
            transform: translateY(-1px);
        }
        .input-group-text {
            background: #f8fafc;
            border-right: 0;
        }
        .input-group .form-control {
            border-left: 0;
        }
        .input-group .form-control:focus + .input-group-text,
        .input-group .form-control:focus {
            border-color: var(--primary);
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card border-0">
                    <div class="login-brand">
                        @php $logoSetting = \App\Models\Setting::getValue(null, 'general', 'logo', null); @endphp
                        @if($logoSetting)
                        <img src="{{ asset('storage/' . $logoSetting) }}" alt="{{ config('app.name') }}" class="login-logo">
                        @else
                            @php $fallback = config('app.logo', 'logo.png'); @endphp
                            @if($fallback && file_exists(public_path($fallback)))
                            <img src="{{ asset($fallback) }}" alt="{{ config('app.name') }}" class="login-logo">
                            @endif
                        @endif
                        <h1><i class="fas fa-cube me-2"></i>{{ config('app.name') }}</h1>
                        <p>SaaS Multi-Tenant Platform</p>
                    </div>
                    <div class="login-body">
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $err)
                                    <div>{{ $err }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@jbtechnepal.com" required autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100 py-2">
                                <i class="fas fa-sign-in-alt me-2"></i> Log In
                            </button>
                        </form>

                        @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="text-decoration-none small text-muted">Forgot password?</a>
                        </div>
                        @endif
                    </div>
                </div>
                <p class="text-center text-white-50 mt-3 small">&copy; {{ date('Y') }} JB Tech Nepal</p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
