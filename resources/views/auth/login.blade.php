<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="{{ asset('img/bmex.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-xl">
        <div class="text-center mb-6">
            <span class="bg-emerald-500 text-white px-3 py-1 rounded-lg text-xs font-black tracking-wider">POS</span>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Aneh BMEX</h1>
            <p class="text-xs text-gray-500">Silakan masuk untuk melanjutkan</p>
        </div>

        <!-- Alert Error Validation -->
        @if ($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-lg text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 rounded-lg text-sm transition duration-200">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Money Changer System
        </div>
    </div>

</body>
</html>