<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            font-family: Inter, Arial, sans-serif;
            background: #f4f4f4;
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-card {
            width: min(1080px, 100%);
            min-height: 620px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .auth-left {
            padding: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-form-wrap {
            width: 100%;
            max-width: 390px;
        }

        .logo-text {
            color: #f16743;
            font-weight: 700;
            font-size: 30px;
            margin-bottom: 12px;
        }

        .muted {
            color: #9b9b9b;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 46px;
            line-height: 1;
            font-weight: 800;
            color: #111;
            margin-bottom: 28px;
        }

        .input {
            width: 100%;
            border: 1px solid #f0e9e8;
            background: #fff7f6;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .btn {
            border: 0;
            background: #f16743;
            color: #fff;
            border-radius: 999px;
            padding: 12px 28px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn:hover {
            background: #e85a35;
        }

        .auth-right {
            background: #faece4;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-right img {
            width: 86%;
            max-width: 460px;
        }

        .error-list {
            margin-bottom: 16px;
            border-radius: 10px;
            background: #fff1f1;
            border: 1px solid #ffc8c8;
            padding: 10px 12px;
            color: #9a1a1a;
            font-size: 13px;
        }

        .success-box {
            margin-bottom: 16px;
            border-radius: 10px;
            background: #effff3;
            border: 1px solid #b6efc1;
            padding: 10px 12px;
            color: #176029;
            font-size: 13px;
        }

        @media (max-width: 900px) {
            .auth-card {
                grid-template-columns: 1fr;
            }

            .auth-right {
                min-height: 260px;
            }
        }
    </style>
</head>
<body>
    <section class="auth-shell">
        <div class="auth-card">
            <div class="auth-left">
                <div class="auth-form-wrap">
                    <p class="logo-text">Logo Here</p>
                    <p class="muted">Welcome back !!!</p>
                    <h1 class="title">Sign up</h1>

                    @if (session('success'))
                        <div class="success-box">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="error-list">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                        <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="Full name" required>
                        <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                        <input class="input" type="password" name="password" placeholder="Password" required>
                        <input class="input" type="password" name="password_confirmation" placeholder="Confirm password" required>
                        <button class="btn" type="submit">SIGN UP</button>
                    </form>

                    <p style="margin-top:14px;font-size:13px;color:#666;">
                        I already have an account ?
                        <a href="{{ route('login') }}" style="color:#f16743;font-weight:700;text-decoration:none;">Sign in</a>
                    </p>
                </div>
            </div>

            <div class="auth-right">
                <img src="{{ asset('images/bg.png') }}" alt="Ecommerce register illustration">
            </div>
        </div>
    </section>
</body>
</html>
