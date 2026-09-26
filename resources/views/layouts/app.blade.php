<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BMEX')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between shadow-lg">
            <div>
                <div class="p-5 text-xl font-bold border-b border-slate-800 flex items-center gap-2">
                    <span class="bg-emerald-500 text-white p-2 rounded-lg text-xs font-black">BMEX</span>
                    <span>Bali Money Exchange</span>
                </div>

                <nav class="mt-4 px-3 space-y-1">
                    <a href="{{ route('transactions.create') }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('transactions.create') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Input Transaksi
                    </a>
                    <a href="{{ route('customers.index') }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('customers.*') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Data Nasabah
                    </a>
                    <a href="{{ route('mutations.index') }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('mutations.*') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Mutasi Transaksi
                    </a>
                    <a href="{{ route('currency.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('currency.index') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Data Mata Uang
                    </a>

                    {{-- MENU KHUSUS ADMIN --}}
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('users.*') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Kelola Kasir
                    </a>
                    @endif
                </nav>
            </div>

            <div class="p-4 border-t border-slate-800 text-xs text-gray-500 text-center">
                &copy; {{ date('Y') }} BMEX System
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center shadow-sm">
                <h1 class="text-lg font-semibold text-gray-700">@yield('page_heading', 'Dashboard')</h1>
                
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-emerald-600 uppercase font-semibold">{{ Auth::user()->role }}</div>
                    </div>

                    <a href="{{ route('password.change') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-3 py-2 rounded-lg font-medium transition flex items-center gap-1 border border-gray-300">
                        🔑 Password
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs px-3 py-2 rounded-lg font-medium transition border border-rose-200">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-6">
                @yield('content')
            </main>

        </div>

    </div>

</body>
</html>