<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen overflow-x-hidden bg-[radial-gradient(circle_at_12%_18%,rgba(255,127,80,0.42),transparent_44%),radial-gradient(circle_at_84%_16%,rgba(214,223,255,0.92),transparent_42%),radial-gradient(circle_at_70%_82%,rgba(255,191,170,0.55),transparent_38%),linear-gradient(155deg,#f6f7ff_0%,#f9f2ff_55%,#eef3ff_100%)] font-['Geist','Inter',Arial,sans-serif] text-slate-800">
    <div class="pointer-events-none fixed inset-0 bg-[linear-gradient(rgba(255,255,255,0.24)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.24)_1px,transparent_1px)] bg-size-[48px_48px] mask-[radial-gradient(circle_at_center,black_35%,transparent_88%)]"></div>
    <div class="pointer-events-none fixed inset-0 before:absolute before:-right-[120px] before:-top-20 before:h-[520px] before:w-[520px] before:rounded-full before:bg-[radial-gradient(circle,rgba(255,127,80,0.28),transparent_68%)] after:absolute after:-bottom-[120px] after:-left-[120px] after:h-[440px] after:w-[440px] after:rounded-full after:bg-[radial-gradient(circle,rgba(180,198,255,0.4),transparent_66%)]"></div>

    @if (session('success'))
        <div id="loginToast" class="fixed right-5 top-5 z-20 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-3 text-[13px] text-emerald-800 shadow-lg transition">{{ session('success') }}</div>
    @endif

    <section class="relative z-10 flex min-h-screen items-center justify-center p-7">
        <div class="grid min-h-[640px] w-full max-w-[1080px] overflow-hidden rounded-[28px] border border-white/30 bg-white/70 shadow-2xl backdrop-blur-xl lg:grid-cols-2">
            <div class="flex items-center justify-center p-8 lg:p-16">
                <div class="w-full max-w-[410px]">
                    <p class="mb-3 text-3xl font-extrabold tracking-tight text-[#f16743]">Nexus</p>
                    <p class="mb-2.5 text-sm text-slate-500">Welcome back</p>
                    <h1 class="mb-7 text-5xl font-extrabold tracking-tight text-slate-900">Sign in</h1>

                    @if ($errors->any())
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-[13px] text-red-800">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf
                        <div class="mb-3.5">
                            <label class="mb-1.5 block text-xs font-bold text-slate-500" for="email">Email</label>
                            <input id="email" class="w-full rounded-xl border border-slate-200 bg-white/90 px-3.5 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-[#ff7f50] focus:ring-4 focus:ring-[#ff7f50]/20" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="mb-1.5 block text-xs font-bold text-slate-500" for="password">Password</label>
                            <input id="password" class="w-full rounded-xl border border-slate-200 bg-white/90 px-3.5 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-[#ff7f50] focus:ring-4 focus:ring-[#ff7f50]/20" type="password" name="password" placeholder="••••••••" required>
                        </div>
                        <button class="w-full rounded-2xl bg-linear-to-b from-[#ff996f] via-[#ff7f50] to-[#f16743] px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-[#f16743]/30 transition hover:scale-[1.01]" type="submit">Sign In</button>
                    </form>

                    <p class="mt-4 text-[13px] text-slate-600">I don't have an account? <a class="font-bold text-[#f16743]" href="{{ route('register') }}">Sign up</a></p>
                </div>
            </div>

            <div class="relative hidden items-center justify-center bg-[radial-gradient(circle_at_18%_16%,rgba(255,255,255,0.45),transparent_38%),linear-gradient(140deg,rgba(255,127,80,0.16),rgba(232,238,255,0.6))] lg:flex">
                <div class="pointer-events-none absolute inset-6 rounded-[18px] border border-dashed border-white/40"></div>
                <img class="relative z-10 w-[86%] max-w-[470px]" src="{{ asset('images/bg.png') }}" alt="Ecommerce login illustration">
            </div>
        </div>
    </section>
    <script>
        (function () {
            var toast = document.getElementById('loginToast');
            if (!toast) return;
            setTimeout(function () {
                toast.classList.add('opacity-0', '-translate-y-2');
            }, 3600);
            setTimeout(function () {
                toast.remove();
            }, 4100);
        })();
    </script>
</body>
</html>
