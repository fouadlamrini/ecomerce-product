<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen font-sans text-slate-900" style="font-family: Inter, Geist, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">
    <main class="relative min-h-screen overflow-hidden">
        <img
            src="{{ asset('images/404.jpg') }}"
            alt="404 page not found"
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div class="absolute inset-0 bg-slate-900/35"></div>

        <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-xl rounded-3xl bg-white/90 backdrop-blur-sm border border-white/60 p-6 sm:p-8 text-center shadow-2xl">
                <h1 class="text-5xl font-extrabold text-slate-900 mb-2">404</h1>
                <h2 class="text-xl font-semibold text-slate-700 mb-3">Oops, page not found</h2>
                <p class="text-slate-600 leading-relaxed mb-8">
                    The page you are looking for does not exist, may have been moved, or is temporarily unavailable.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a
                        href="{{ url('/') }}"
                        class="w-full sm:w-auto bg-[#FF7F50] hover:bg-[#E66D43] text-white font-bold py-3 px-8 rounded-xl transition-all active:scale-95 shadow-lg shadow-orange-500/20 text-center"
                    >
                        Go Home
                    </a>
                    <a
                        href="javascript:history.back()"
                        class="w-full sm:w-auto border border-slate-300 text-slate-700 hover:bg-white font-semibold py-3 px-8 rounded-xl transition-all text-center"
                    >
                        Go Back
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
