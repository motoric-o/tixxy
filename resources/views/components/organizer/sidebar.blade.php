<div class="flex flex-col py-4 px-[14px] sticky top-5 h-[calc(100vh-40px)] bg-gray-200 dark:bg-gray-800 rounded-xl transition-all duration-300 overflow-y-auto overflow-x-hidden shrink-0"
    :class="sidebarOpen ? 'w-1/6' : 'w-[88px]'">
    <div class="flex flex-row items-center justify-start gap-4 mb-5 whitespace-nowrap overflow-hidden">
        <button @click="sidebarOpen = !sidebarOpen"
            class="ml-[6px] p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 shrink-0">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)]" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 x-show="sidebarOpen" x-transition class="dark:text-white text-2xl font-bold">Organizer</h1>
    </div>
    <div class="flex flex-col px-[8px] py-1 bg-gray-300 dark:bg-[#2b3544] rounded-xl transition-all duration-300">
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden shadow-lg"
            href="/manage/scanner" title="Ticket Scanner">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v1m0 11v1m5-10v.5m0 9v.5M7 7v.5m0 9v.5M5 12h1m12 0h1M7 7l.354.354M7 17l.354-.354m10-10l-.354.354M17 17l-.354-.354">
                </path>
            </svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap font-bold">Ticket Scanner</span>
        </a>
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden"
            href="/manage/dashboard" title="Dashboard">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Dashboard</span>
        </a>
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden"
            href="/manage/finances" title="Financial">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Financial</span>
        </a>
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden"
            href="/manage/events" title="Events">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Events</span>
        </a>
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden"
            href="/manage/orders" title="Orders">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Orders</span>
        </a>
        <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden"
            href="/manage/tickets" title="Tickets">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                </path>
            </svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Tickets</span>
        </a>
        {{-- <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden" href="/manage/scan" title="Scan">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M 17 18 V 15 M 13 16 V 17 M 12 14 H 13 M 11 18 H 10 M 12 16 H 14 M 11 12 H 14 M 10 20 H 11 M 15 20 H 13 M 5 14 H 3 M 10 14 H 8 M 5 9 H 6 M 5 13 H 6 M 3 7 V 7 A 1 1 0 0 1 4 6 H 6 A 1 1 0 0 1 7 7 V 9 A 1 1 0 0 1 6 10 H 4 A 1 1 0 0 1 3 9 Z M 3 17 V 19 A 1 1 0 0 0 4 20 H 6 A 1 1 0 0 0 7 19 V 17 A 1 1 0 0 0 6 16 H 4 A 1 1 0 0 0 3 17 Z M 13 7 V 7 A 1 1 0 0 1 14 6 H 16 A 1 1 0 0 1 17 7 V 9 A 1 1 0 0 1 16 10 H 14 A 1 1 0 0 1 13 9 Z"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Scan</span>
        </a> --}}
        {{-- <a class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] focus:outline-none border border-transparent hover:border-white/20 my-2 flex items-center gap-3 overflow-hidden" href="/admin/settings" title="Settings">
            <svg class="w-7 h-7 drop-shadow-[0_0_5px_rgba(233,213,255,0.8)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Settings</span>
        </a> --}}
    </div>
</div>
