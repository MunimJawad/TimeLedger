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
                <a href="" class="block px-6 py-2 hover:bg-zinc-800 rounded">Dashboard</a>
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
                    <span class="text-gray-600">{{ auth()->user()->name ?? 'Guest' }}</span>

                    @if (auth()->user())
                    
                         <form method="POST" action="{{ route('logout') }}">
                            @csrf
                           <button type="submit">Logout</button>
                        </form>

                    @else
                         <a href="{{ route('login.form') }}">LogIn</a>
                    @endif
                   
                </div>
            </header>
    @endunless      

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
