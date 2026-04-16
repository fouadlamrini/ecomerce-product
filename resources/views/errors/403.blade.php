<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Access Denied</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900" style="font-family: Inter, Geist, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">
    <main class="flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white p-6 text-center shadow-2xl sm:p-8">
                <h1 class="mb-2 text-5xl font-extrabold text-slate-900">403</h1>
                <h2 class="mb-3 text-xl font-semibold text-slate-700">Access denied</h2>
                <p class="mb-8 leading-relaxed text-slate-600">
                    You do not have permission to access this page.
                </p>

                <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <a
                        href="{{ url('/') }}"
                        class="w-full rounded-xl bg-[#FF7F50] px-8 py-3 text-center font-bold text-white shadow-lg shadow-orange-500/20 transition-all hover:bg-[#E66D43] active:scale-95 sm:w-auto"
                    >
                        Go Home
                    </a>
                    <a
                        href="javascript:history.back()"
                        class="w-full rounded-xl border border-slate-300 px-8 py-3 text-center font-semibold text-slate-700 transition-all hover:bg-white sm:w-auto"
                    >
                        Go Back
                    </a>
                </div>
            </div>
    </main>
</body>
</html>
