<header class="w-full bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] shadow-[0_4px_20px_rgba(142,45,226,0.6)] border-b border-[#a855f7]/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center group cursor-pointer">
                <a href="/" class="text-white font-bold text-2xl tracking-wider flex items-center gap-2 drop-shadow-[0_0_8px_rgba(255,255,255,0.4)] group-hover:drop-shadow-[0_0_12px_rgba(255,255,255,0.8)] transition-all duration-300">
                    <svg class="w-8 h-8 text-[#e9d5ff] drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    Tixxy
                </a>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex space-x-6 items-center px-4">
                @php
                $navItems = [
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Events', 'url' => '/events'],
                ['label' => 'About', 'url' => '/about'],
                ['label' => 'Contact', 'url' => '/contact'],
                ];
                @endphp

                @foreach($navItems as $item)
                @php
                $path = trim($item['url'], '/');
                $isActive = request()->is($path == '' ? '/' : $path);
                $baseClasses = "relative py-2 text-sm font-medium transition-colors duration-300 after:content-[''] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:bg-[#d8b4fe] after:shadow-[0_0_8px_rgba(216,180,254,0.8)]";
                $activeClasses = $isActive ? "text-white after:w-full" : "text-[#e9d5ff] hover:text-white after:w-0 hover:after:w-full after:transition-all after:duration-300";
                @endphp
                <a href="{{ url($item['url']) }}" class="{{ $baseClasses }} {{ $activeClasses }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>

            <!-- Right Side (Search & Avatar) -->
            <div class="flex items-center space-x-5">
                <!-- Search Icon -->
                <button class="text-[#e9d5ff] hover:text-white hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.8)] hover:scale-110 transition-all duration-300 p-1 rounded-full hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Notification Bell -->
                <button class="relative text-[#e9d5ff] hover:text-white hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.8)] hover:scale-110 transition-all duration-300 p-1 rounded-full hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-[#00f2fe] ring-2 ring-[#7e22ce] shadow-[0_0_10px_rgba(0,242,254,0.9)] animate-pulse"></span>
                </button>

                <!-- VDivider -->
                <div class="hidden md:block h-8 w-px bg-gradient-to-b from-transparent via-[#d8b4fe]/30 to-transparent"></div>

                <!-- User Avatar Dropdown -->
                <!-- User Avatar Dropdown Component -->
                <x-avatar />
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden ml-4">
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-lg text-[#e9d5ff] hover:text-white hover:bg-white/10 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none transition-all border border-transparent hover:border-white/20">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>