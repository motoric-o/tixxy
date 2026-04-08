<div class="flex flex-col py-4 px-[14px] sticky top-5 h-[calc(100vh-40px)] bg-gray-200 dark:bg-gray-800 rounded-xl transition-all duration-300 overflow-y-auto overflow-x-hidden shrink-0" :class="sidebarOpen ? 'w-1/6' : 'w-[88px]'">
    <div class="flex flex-row items-center justify-start gap-4 mb-5 whitespace-nowrap overflow-hidden">
        <button @click="sidebarOpen = !sidebarOpen" class="ml-[6px] p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 shrink-0">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 x-show="sidebarOpen" x-transition class="dark:text-white text-2xl font-bold">Admin</h1>
    </div>
    <div class="flex flex-col px-[8px] py-1 bg-gray-300 dark:bg-[#2b3544] rounded-xl transition-all duration-300">
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden shadow-lg" href="/manage/scanner" title="Ticket Scanner">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 11v1m5-10v.5m0 9v.5M7 7v.5m0 9v.5M5 12h1m12 0h1M7 7l.354.354M7 17l.354-.354m10-10l-.354.354M17 17l-.354-.354"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap font-bold">Ticket Scanner</span>
        </a>
        {{-- 1. Overview --}}
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden" href="/manage/dashboard" title="Dashboard">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Dashboard</span>
        </a>

        {{-- 2. Core Business --}}
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden" href="/manage/events" title="Events">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Events</span>
        </a>

        {{-- 3. Operations --}}
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden" href="/manage/orders" title="Orders">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Orders</span>
        </a>
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden" href="/manage/tickets" title="Tickets">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Tickets</span>
        </a>

        {{-- 4. Management --}}
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden" href="/manage/users" title="Users">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Users</span>
        </a>

        {{-- 5. Configuration --}}
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden" href="/manage/ticket-types" title="Ticket Types">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Ticket Types</span>
        </a>
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden" href="/manage/categories" title="Categories">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Categories</span>
        </a>
    </div>
</div>