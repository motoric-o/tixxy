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

        // Global Chart Themes Helper
        window.ChartThemes = {
            isDark: () => document.documentElement.classList.contains('dark'),
            getGridColor: () => window.ChartThemes.isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)',
            getLabelColor: () => window.ChartThemes.isDark() ? '#9ca3af' : '#6b7280',
            getTooltipStyle: () => ({
                backgroundColor: window.ChartThemes.isDark() ? '#1f2937' : '#ffffff',
                titleColor: window.ChartThemes.isDark() ? '#e5e7eb' : '#111827',
                bodyColor: window.ChartThemes.isDark() ? '#9ca3af' : '#6b7280',
                borderColor: window.ChartThemes.isDark() ? '#374151' : '#e5e7eb',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 12,
                displayColors: false,
            }),
            getGradient: (ctx, color, height = 220) => {
                const colors = {
                    emerald: { start: 'rgba(16, 185, 129, 0.9)', end: 'rgba(16, 185, 129, 0.1)' },
                    amber:   { start: 'rgba(245, 158, 11, 0.9)',  end: 'rgba(245, 158, 11, 0.1)' },
                    blue:    { start: 'rgba(59, 130, 246, 0.9)',  end: 'rgba(59, 130, 246, 0.1)' },
                    indigo:  { start: 'rgba(99, 102, 241, 0.9)',  end: 'rgba(99, 102, 241, 0.1)' },
                    purple:  { start: 'rgba(168, 85, 247, 0.9)', end: 'rgba(168, 85, 247, 0.1)' },
                    rose:    { start: 'rgba(251, 113, 133, 0.9)', end: 'rgba(251, 113, 133, 0.1)' }
                };
                const c = colors[color] || colors.emerald;
                const grad = ctx.createLinearGradient(0, 0, 0, height);
                grad.addColorStop(0, c.start);
                grad.addColorStop(1, c.end);
                return grad;
            }
        };
    </script>
</head>

<body class="flex min-h-screen flex-col dark:bg-gray-900 transition-colors duration-300">
    <x-header />

    <main x-data="{ sidebarOpen: false }" class="flex flex-row items-start gap-5 min-w-screen m-5">
        @if(auth()->user()->role == "organizer")
            <x-organizer.sidebar />
        @elseif(auth()->user()->role == "admin")
            <x-admin.sidebar />
        @endif
        <div id="content" class="flex-1 flex flex-col min-h-screen bg-gray-200 dark:bg-gray-800 rounded-xl transition-all duration-300 p-5">
            <!-- Temporary filler to demonstrate scrolling -->
            @yield('content')
        </div>
    </main>

    <x-footer />
</body>

</html>