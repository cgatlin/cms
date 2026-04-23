@props ([
    'title' => 'Imperial Valley Family Services',
    'active' => null,
])


<!DOCTYPE html>
<html lang="en" data-theme="silk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title }}</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
       
        <!-- Fonts -->
        
        <!-- Styles / Scripts -->
        {{-- <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" /> --}}
     
        
    </head>

    <body class="">
        <x-layout.navbar />
        
        <main class="main-content text-center">
            <div class="bg-primary text-primary-content p-4 border-b">
                @auth
                    <ul class="menu menu-horizontal px-1">
                        <li><a class="link text-lg {{ $active === 'dashboard' ? 'bg-primary-content text-primary' : '' }}" href="/dashboard">Dashboard</a></li>
                        <li><a class="link text-lg {{ $active === 'clients' ? 'bg-primary-content text-primary' : '' }}" href="/clients">Clients</a></li>
                        <li><a class="link text-lg {{ $active === 'cases' ? 'bg-primary-content text-primary' : '' }}" href="/cases">Cases</a></li>
                        <li><a class="link text-lg {{ $active === 'reports' ? 'bg-primary-content text-primary' : '' }}" href="/reports">Reports</a></li>
                        <li><a class="link text-lg {{ $active === 'users' ? 'bg-primary-content text-primary' : '' }}" href="/users">Users</a></li> 
                    </ul>
                @endauth
            </div>
            {{ $slot }}
        </main>    
        <footer class="footer flex items-end justify-end p-4">
        </footer>
    </body>
</html>