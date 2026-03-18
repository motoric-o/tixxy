<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Tixxy') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark Mode Init -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="flex min-h-screen flex-col bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <x-header />

    <main x-data="{ sidebarOpen: false }" class="flex flex-row items-start gap-5 min-w-screen m-5">
        <x-admin.sidebar />
        <div id="content" class="flex-1 flex flex-col min-h-screen dark:bg-gray-800 rounded-xl transition-all duration-300 p-5">
            <!-- Temporary filler to demonstrate scrolling -->
            @yield('content')
        </div>
    </main>

    <x-footer />
</body>

</html>