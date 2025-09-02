<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TimeLedger')</title>
    @vite(['resources/css/app.css', 'resources/css/style.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

   @unless (request()->routeIs('register.form') || request()->routeIs('register') || request()->routeIs('login.form') || request()->routeIs('login'))
    
    <div class="flex h-screen">
        {{-- Sidebar --}}
        <aside class="w-50 bg-gray-900 shadow-md flex-shrink-0 text-white">
            <div class="p-6 font-bold text-xl border-b-gray-500">TimeLedger</div>
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="block px-6 py-2 hover:bg-zinc-800 rounded">Dashboard</a>
                <a href="{{ route('projects.index') }}" class="block px-6 py-2 hover:bg-zinc-800 rounded">Projects</a>
                @auth
                 @if (auth()->user()?->role == 'admin')              
                <a href="{{ route('admin.user_list') }}" class="block px-6 py-2 hover:bg-zinc-800 rounded">Users</a>
                @endif
                @endauth
            </nav>
        </aside>


     

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            {{-- Header --}}
            <header class="flex justify-between items-center bg-white shadow-md p-4">
                

                <h1 class="text-lg font-semibold">@yield('header', 'Dashboard')</h1>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('profile') }}"><span class=" text-green-500 font-semibold">{{ auth()->user()->name ?? 'Guest' }}</span></a>
                    
                       <!-- resources/views/layouts/partials/notifications.blade.php -->
                        @include('layouts.partials.notifications')

                    @if (auth()->user())
                    
                         <form method="POST" action="{{ route('logout') }}">
                            @csrf
                           <input type="submit" value="Logout" class="text-rose-500 font-bold cursor-pointer">
                        </form>

                    @else
                         <a href="{{ route('login.form') }}">LogIn</a>
                    @endif
                   
                </div>
            </header>
    @endunless      

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto">
                @if (session('success'))
                    <div id="success-message" class="bg-green-500 text-white-500 p-2 rounded-lg mb-4 transition duration-300">
                          {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

<script>
    setTimeout(() => {
            const el = document.getElementById('success-message');
            if (el) el.style.display = 'none';
        }, 3000); // 3 seconds

</script>
