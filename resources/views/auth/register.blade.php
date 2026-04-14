<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        :root {
            --brand-orange: #ff7f50;
            --brand-orange-dark: #f16743;
            --ink: #1f2937;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Geist", "Inter", Arial, sans-serif;
            color: var(--ink);
            min-height: 100vh;
            background:
                radial-gradient(circle at 12% 18%, rgba(255, 127, 80, 0.42), transparent 44%),
                radial-gradient(circle at 84% 16%, rgba(214, 223, 255, 0.92), transparent 42%),
                radial-gradient(circle at 70% 82%, rgba(255, 191, 170, 0.55), transparent 38%),
                linear-gradient(155deg, #f6f7ff 0%, #f9f2ff 55%, #eef3ff 100%);
            overflow-x: hidden;
        }
        .bg-noise,
        .bg-grid {
            position: fixed;
            inset: 0;
            pointer-events: none;
        }
        .bg-grid {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.24) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.24) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(circle at center, black 35%, transparent 88%);
        }
        .bg-noise::before,
        .bg-noise::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            filter: blur(4px);
        }
        .bg-noise::before {
            width: 520px;
            height: 520px;
            right: -120px;
            top: -80px;
            background: radial-gradient(circle, rgba(255, 127, 80, 0.28), transparent 68%);
        }
        .bg-noise::after {
            width: 440px;
            height: 440px;
            left: -120px;
            bottom: -120px;
            background: radial-gradient(circle, rgba(180, 198, 255, 0.4), transparent 66%);
        }
        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
            position: relative;
            z-index: 1;
        }
        .auth-card {
            width: min(1080px, 100%);
            min-height: 640px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow:
                0 28px 60px rgba(32, 44, 73, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.55);
        }
        .auth-left {
            padding: 58px 64px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-form-wrap { width: 100%; max-width: 410px; }
        .logo-text { color: var(--brand-orange-dark); font-weight: 800; font-size: 30px; margin: 0 0 12px; letter-spacing: -0.02em; }
        .muted { color: #6b7280; font-size: 14px; margin: 0 0 10px; }
        .title { font-size: 46px; line-height: 1.05; font-weight: 800; color: #111827; margin: 0 0 24px; letter-spacing: -0.03em; }
        .error-list {
            margin-bottom: 16px;
            border-radius: 12px;
            background: rgba(254, 242, 242, 0.85);
            border: 1px solid #fecaca;
            padding: 10px 12px;
            color: #991b1b;
            font-size: 13px;
        }
        .error-list ul { margin: 0; padding-left: 16px; }
        .form-group { position: relative; margin-bottom: 12px; }
        .field-input {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.52);
            background: rgba(255, 255, 255, 0.8);
            border-radius: 13px;
            padding: 20px 14px 8px;
            font-size: 14px;
            outline: none;
            color: #111827;
            transition: all .25s ease;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }
        .field-label {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: #6b7280;
            pointer-events: none;
            transition: all .22s ease;
            background: transparent;
        }
        .field-input:focus {
            border-color: rgba(255, 127, 80, 0.92);
            box-shadow: 0 0 0 4px rgba(255, 127, 80, 0.2), 0 14px 24px rgba(15, 23, 42, 0.08);
            background: rgba(255, 255, 255, 0.95);
        }
        .field-input:focus + .field-label,
        .field-input:not(:placeholder-shown) + .field-label {
            top: 9px;
            transform: translateY(0);
            font-size: 11px;
            color: var(--brand-orange-dark);
            font-weight: 600;
        }
        .btn {
            width: 100%;
            border: 0;
            border-radius: 14px;
            padding: 13px 24px;
            margin-top: 2px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            color: #fff;
            background: linear-gradient(180deg, #ff996f 0%, #ff7f50 52%, #f16743 100%);
            box-shadow: 0 14px 28px rgba(241, 103, 67, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.35);
            transition: transform .2s ease, filter .2s ease, box-shadow .2s ease;
        }
        .btn:hover {
            transform: scale(1.02);
            filter: brightness(1.02);
            box-shadow: 0 16px 30px rgba(241, 103, 67, 0.38), inset 0 1px 0 rgba(255, 255, 255, 0.42);
        }
        .btn:active { transform: scale(0.98); }
        .meta-link { margin-top: 14px; font-size: 13px; color: #4b5563; }
        .meta-link a { color: var(--brand-orange-dark); font-weight: 700; text-decoration: none; }
        .auth-right {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at 18% 16%, rgba(255, 255, 255, 0.45), transparent 38%),
                linear-gradient(140deg, rgba(255, 127, 80, 0.16), rgba(232, 238, 255, 0.6));
        }
        .auth-right::before {
            content: "";
            position: absolute;
            inset: 24px;
            border-radius: 18px;
            border: 1px dashed rgba(255, 255, 255, 0.38);
            pointer-events: none;
        }
        .auth-right img {
            width: 86%;
            max-width: 470px;
            position: relative;
            z-index: 1;
            animation: floatY 4.2s ease-in-out infinite;
        }
        .toast {
            position: fixed;
            top: 22px;
            right: 22px;
            z-index: 15;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(134, 239, 172, 0.7);
            background: rgba(240, 253, 244, 0.96);
            color: #166534;
            font-size: 13px;
            box-shadow: 0 14px 24px rgba(15, 23, 42, 0.08);
            animation: toastIn .45s cubic-bezier(.16,1,.3,1);
        }
        .reveal {
            opacity: 0;
            transform: translateY(16px) scale(0.985);
            animation: revealIn .6s cubic-bezier(.16,1,.3,1) forwards;
        }
        .reveal-1 { animation-delay: .08s; }
        .reveal-2 { animation-delay: .18s; }
        .reveal-3 { animation-delay: .28s; }
        .reveal-4 { animation-delay: .38s; }
        .reveal-5 { animation-delay: .48s; }
        .reveal-6 { animation-delay: .58s; }
        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }
        @keyframes revealIn {
            0% { opacity: 0; transform: translateY(16px) scale(0.985); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes toastIn {
            0% { opacity: 0; transform: translateY(-10px) translateX(12px); }
            100% { opacity: 1; transform: translateY(0) translateX(0); }
        }
        @keyframes toastOut {
            0% { opacity: 1; transform: translateY(0) translateX(0); }
            100% { opacity: 0; transform: translateY(-10px) translateX(14px); }
        }
        @media (max-width: 980px) {
            .auth-card { grid-template-columns: 1fr; }
            .auth-left { padding: 38px 26px; }
            .auth-right { min-height: 260px; }
            .title { font-size: 38px; }
        }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="bg-noise"></div>

    @if (session('success'))
        <div class="toast">{{ session('success') }}</div>
    @endif

    <section class="auth-shell">
        <div class="auth-card">
            <div class="auth-left">
                <div class="auth-form-wrap">
                    <p class="logo-text reveal reveal-1">Vendora</p>
                    <p class="muted reveal reveal-2">Create your account</p>
                    <h1 class="title reveal reveal-3">Sign up</h1>

                    @if ($errors->any())
                        <div class="error-list reveal reveal-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}" class="reveal reveal-5">
                        @csrf
                        <div class="form-group">
                            <input id="name" class="field-input" type="text" name="name" value="{{ old('name') }}" placeholder=" " required>
                            <label class="field-label" for="name">Full name</label>
                        </div>
                        <div class="form-group">
                            <input id="email" class="field-input" type="email" name="email" value="{{ old('email') }}" placeholder=" " required>
                            <label class="field-label" for="email">Email</label>
                        </div>
                        <div class="form-group">
                            <input id="password" class="field-input" type="password" name="password" placeholder=" " required>
                            <label class="field-label" for="password">Password</label>
                        </div>
                        <div class="form-group">
                            <input id="password_confirmation" class="field-input" type="password" name="password_confirmation" placeholder=" " required>
                            <label class="field-label" for="password_confirmation">Confirm password</label>
                        </div>
                        <button class="btn" type="submit">Sign Up</button>
                    </form>

                    <p class="meta-link reveal reveal-6">
                        I already have an account?
                        <a href="{{ route('login') }}">Sign in</a>
                    </p>
                </div>
            </div>

            <div class="auth-right">
                <img src="{{ asset('images/bg.png') }}" alt="Ecommerce register illustration">
            </div>
        </div>
    </section>
    <script>
        (function () {
            var toast = document.querySelector('.toast');
            if (!toast) return;
            setTimeout(function () {
                toast.style.animation = 'toastOut .4s cubic-bezier(.4,0,.2,1) forwards';
            }, 3600);
            setTimeout(function () {
                toast.remove();
            }, 4100);
        })();
    </script>
</body>
</html>
