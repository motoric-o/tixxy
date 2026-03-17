@props(['name' => 'Agent Smith', 'role' => 'Admin'])

<div class="relative group">
    <button class="flex items-center space-x-3 focus:outline-none p-1 rounded-full hover:bg-white/5 transition-all duration-300 pr-3">
        <div class="relative">
            <img class="h-9 w-9 rounded-full object-cover border-2 border-[#d8b4fe] shadow-[0_0_15px_rgba(168,85,247,0.5)] group-hover:border-white group-hover:shadow-[0_0_20px_rgba(216,180,254,0.8)] transition-all duration-300" src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=2e1065&color=d8b4fe" alt="{{ $name }} avatar">
            <div class="absolute inset-0 rounded-full ring-1 ring-inset ring-white/20"></div>
        </div>
        <div class="hidden md:flex flex-col items-start">
            <span class="text-sm font-semibold text-white drop-shadow-[0_0_5px_rgba(255,255,255,0.3)]">{{ $name }}</span>
            <span class="text-xs text-[#d8b4fe]">{{ $role }}</span>
        </div>
        <svg class="hidden md:block w-4 h-4 text-[#d8b4fe] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    <!-- Dropdown Menu -->
    <div class="absolute right-0 mt-3 w-56 bg-[#1e0a3c]/95 backdrop-blur-md rounded-xl shadow-[0_15px_35px_rgba(0,0,0,0.5)] py-2 border border-[#a855f7]/30 ring-1 ring-black/5 focus:outline-none z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-300 transform origin-top-right translate-y-2 group-hover:translate-y-0">
        <div class="px-4 py-2 border-b border-[#a855f7]/20 mb-1">
            <p class="text-sm text-[#e9d5ff]">Signed in as</p>
            <p class="text-sm font-bold text-white truncate">{{ strtolower(str_replace(' ', '.', $name)) }}@tixxy.com</p>
        </div>
        <a href="#" class="px-4 py-2.5 text-sm text-[#e9d5ff] hover:bg-[#a855f7]/20 hover:text-white transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Your Profile
        </a>
        <a href="#" class="px-4 py-2.5 text-sm text-[#e9d5ff] hover:bg-[#a855f7]/20 hover:text-white transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Settings
        </a>
        <button onclick="window.toggleTheme()" class="w-full text-left px-4 py-2.5 text-sm text-[#e9d5ff] hover:bg-[#a855f7]/20 hover:text-white transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            Toggle Theme
        </button>
        <div class="border-t border-[#a855f7]/20 my-1"></div>
        <a href="#" class="px-4 py-2.5 text-sm text-[#ff4d4d] hover:bg-[#ff4d4d]/10 transition-colors flex items-center gap-2 group-hover:text-[#ff7676]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Sign out
        </a>
    </div>
</div>
