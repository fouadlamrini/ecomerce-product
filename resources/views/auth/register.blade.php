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
</head>
<body class="min-h-screen overflow-x-hidden bg-[radial-gradient(circle_at_12%_18%,rgba(255,127,80,0.42),transparent_44%),radial-gradient(circle_at_84%_16%,rgba(214,223,255,0.92),transparent_42%),radial-gradient(circle_at_70%_82%,rgba(255,191,170,0.55),transparent_38%),linear-gradient(155deg,#f8fafc_0%,#f3f4f6_55%,#eef2ff_100%)] font-['Geist','Inter',Arial,sans-serif] text-slate-800">
    <div class="pointer-events-none fixed inset-0 bg-[linear-gradient(rgba(255,255,255,0.24)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.24)_1px,transparent_1px)] bg-size-[48px_48px] mask-[radial-gradient(circle_at_center,black_35%,transparent_88%)]"></div>
    <div class="pointer-events-none fixed inset-0 before:absolute before:-right-[120px] before:-top-20 before:h-[520px] before:w-[520px] before:rounded-full before:bg-[radial-gradient(circle,rgba(255,127,80,0.28),transparent_68%)] after:absolute after:-bottom-[120px] after:-left-[120px] after:h-[440px] after:w-[440px] after:rounded-full after:bg-[radial-gradient(circle,rgba(180,198,255,0.4),transparent_66%)]"></div>

    @if (session('success'))
        <div id="registerToast" class="fixed inset-x-4 top-4 z-30 mx-auto w-full max-w-xl rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-center text-sm text-emerald-700 shadow-xl shadow-emerald-100/40 transition">
            {{ session('success') }}
        </div>
    @endif

    <section class="relative z-10 flex min-h-screen items-center justify-center p-7">
        <div class="grid min-h-[640px] w-full max-w-[1080px] overflow-hidden rounded-3xl border border-white/30 bg-white/75 shadow-xl shadow-slate-200/50 backdrop-blur-xl lg:grid-cols-2">
            <div class="flex items-center justify-center p-8 lg:p-16">
                <div class="w-full max-w-[410px]">
                    <p class="mb-3 text-3xl font-extrabold tracking-tight text-[#f16743]">Nexus</p>
                    <p class="mb-2.5 text-sm text-slate-500">Create your account</p>
                    <h1 class="mb-6 text-[46px] font-extrabold tracking-tight text-slate-900">Sign up</h1>

                    @if ($errors->any())
                        <div class="mb-4 rounded-2xl border border-red-100 bg-red-50 px-3.5 py-3 text-[13px] text-red-800 shadow-xl shadow-red-100/40">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="mb-1.5 block text-xs font-bold text-slate-500" for="name">Full name</label>
                            <input id="name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition-all focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10" type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" required>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1.5 block text-xs font-bold text-slate-500" for="email">Email</label>
                            <input id="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition-all focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1.5 block text-xs font-bold text-slate-500" for="password">Password</label>
                            <input id="password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition-all focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10" type="password" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="mb-4">
                            <label class="mb-1.5 block text-xs font-bold text-slate-500" for="password_confirmation">Confirm password</label>
                            <input id="password_confirmation" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition-all focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10" type="password" name="password_confirmation" placeholder="••••••••" required>
                        </div>
                        <button class="w-full rounded-xl bg-[#FF7F50] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 transition-all hover:bg-[#E66D43] active:scale-95" type="submit">Sign Up</button>
                    </form>

                    <p class="mt-3.5 text-[13px] text-slate-600">
                        I already have an account?
                        <a class="font-semibold text-[#FF7F50]" href="{{ route('login') }}">Sign in</a>
                    </p>
                </div>
            </div>

            <div class="relative hidden items-center justify-center bg-[radial-gradient(circle_at_18%_16%,rgba(255,255,255,0.45),transparent_38%),linear-gradient(140deg,rgba(255,127,80,0.16),rgba(232,238,255,0.6))] lg:flex">
                <div class="pointer-events-none absolute inset-6 rounded-[18px] border border-dashed border-white/40"></div>
                <img class="relative z-10 w-[86%] max-w-[470px]" src="{{ asset('images/bg.png') }}" alt="Ecommerce register illustration">
            </div>
        </div>
    </section>
    <script>
        (function () {
            var toast = document.getElementById('registerToast');
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
