        <!-- Backdrop -->
        <div
          x-show="isSidebarOpen"
          @click="isSidebarOpen = false"
          class="fixed inset-0 z-10 bg-primary-darker lg:hidden"
          style="opacity: 0.5"
          aria-hidden="true"
        ></div>

        <!-- Sidebar content -->
        <aside
          x-show="isSidebarOpen"
          x-transition:enter="transition-all transform duration-300 ease-in-out"
          x-transition:enter-start="-translate-x-full opacity-0"
          x-transition:enter-end="translate-x-0 opacity-100"
          x-transition:leave="transition-all transform duration-300 ease-in-out"
          x-transition:leave-start="translate-x-0 opacity-100"
          x-transition:leave-end="-translate-x-full opacity-0"
          x-ref="sidebar"
          @keydown.escape="window.innerWidth <= 1024 ? isSidebarOpen = false : ''"
          tabindex="-1"
          class="fixed inset-y-0 z-10 flex flex-shrink-0 overflow-hidden bg-white border-r lg:static dark:border-primary-darker dark:bg-darker focus:outline-none"
        >
          <!-- Mini column -->
          <div class="flex flex-col flex-shrink-0 h-full px-2 py-4 border-r dark:border-primary-darker">
            <!-- Brand -->
            <div class="flex-shrink-0">
              <a
                href="{{ route('dashboard') }}"
                class="inline-block text-xl font-bold tracking-wider uppercase text-primary-dark dark:text-light"
              >
                K-WD
              </a>
            </div>
            <div class="flex flex-col items-center justify-center flex-1 space-y-4">
              <!-- Notification button -->
              <button
                @click="openNotificationsPanel"
                class="p-2 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker"
              >
                <span class="sr-only">Open Notification panel</span>
                <svg
                  class="w-7 h-7"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                  />
                </svg>
              </button>

              <!-- Search button -->
              <button
                @click="openSearchPanel"
                class="p-2 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker"
              >
                <span class="sr-only">Open search panel</span>
                <svg
                  class="w-7 h-7"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </button>

              <!-- Settings button -->
              <button
                @click="openSettingsPanel"
                class="p-2 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker"
              >
                <span class="sr-only">Open settings panel</span>
                <svg
                  class="w-7 h-7"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
              </button>
            </div>
            <!-- Mini column footer -->
            <div class="relative flex items-center justify-center flex-shrink-0">
              <!-- User avatar button -->
              <div class="" x-data="{ open: false }">
                <button
                  @click="open = !open; $nextTick(() => { if(open){ $refs.userMenu.focus() } })"
                  type="button"
                  aria-haspopup="true"
                  :aria-expanded="open ? 'true' : 'false'"
                  class="block transition-opacity duration-200 rounded-full dark:opacity-75 dark:hover:opacity-100 focus:outline-none focus:ring dark:focus:opacity-100"
                >
                  <span class="sr-only">User menu</span>
                  <img class="w-10 h-10 rounded-full" src="{{ asset('dash_assets/') }}/images/avatar.jpg" alt="Ahmed Kamel" />
                </button>

                <!-- User dropdown menu -->
                <div
                  x-show="open"
                  x-ref="userMenu"
                  x-transition:enter="transition-all transform ease-out"
                  x-transition:enter-start="-translate-y-1/2 opacity-0"
                  x-transition:enter-end="translate-y-0 opacity-100"
                  x-transition:leave="transition-all transform ease-in"
                  x-transition:leave-start="translate-y-0 opacity-100"
                  x-transition:leave-end="-translate-y-1/2 opacity-0"
                  @click.away="open = false"
                  @keydown.escape="open = false"
                  class="absolute w-56 py-1 mb-4 bg-white rounded-md shadow-lg min-w-max left-5 bottom-full ring-1 ring-black ring-opacity-5 dark:bg-dark focus:outline-none"
                  tabindex="-1"
                  role="menu"
                  aria-orientation="vertical"
                  aria-label="User menu"
                >
                  <a
                    href="#"
                    role="menuitem"
                    class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                  >
                    Your Profile
                  </a>
                  <a
                    href="#"
                    role="menuitem"
                    class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                  >
                    Settings
                  </a>
                  <a
                    href="#"
                    role="menuitem"
                    class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                  >
                    Logout
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!-- Sidebar links -->
          <nav aria-label="Main" class="flex-1 w-64 px-2 py-4 space-y-2 overflow-y-hidden hover:overflow-y-auto">
            <!-- Dashboards links -->
            <div x-data="{ isActive: false, open: false}">
              <!-- active & hover classes 'bg-primary-100 dark:bg-primary' -->
              <a
                href="#"
                @click="$event.preventDefault(); open = !open"
                class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                :class="{'bg-primary-100 dark:bg-primary': isActive || open}"
                role="button"
                aria-haspopup="true"
                :aria-expanded="(open || isActive) ? 'true' : 'false'"
              >
                <span aria-hidden="true">
                  <svg
                    class="w-5 h-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    />
                  </svg>
                </span>
                <span class="ml-2 text-sm"> Dashboards </span>
                <span class="ml-auto" aria-hidden="true">
                  <!-- active class 'rotate-180' -->
                  <svg
                    class="w-4 h-4 transition-transform transform"
                    :class="{ 'rotate-180': open }"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </span>
              </a>
              <div role="menu" x-show="open" class="mt-2 space-y-2 px-7" aria-label="Dashboards">
                <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->
                <a
                  href="{{ route('dashboard') }}"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                >
                  Default
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Project Mangement (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  E-Commerce (soon)
                </a>
              </div>
            </div>

            <!-- Components links -->
            <div x-data="{ isActive: false, open: false }">
              <!-- active classes 'bg-primary-100 dark:bg-primary' -->
              <a
                href="#"
                @click="$event.preventDefault(); open = !open"
                class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }"
                role="button"
                aria-haspopup="true"
                :aria-expanded="(open || isActive) ? 'true' : 'false'"
              >
                <span aria-hidden="true">
                  <svg
                    class="w-5 h-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                    />
                  </svg>
                </span>
                <span class="ml-2 text-sm"> Components </span>
                <span aria-hidden="true" class="ml-auto">
                  <!-- active class 'rotate-180' -->
                  <svg
                    class="w-4 h-4 transition-transform transform"
                    :class="{ 'rotate-180': open }"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </span>
              </a>
              <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="Components">
                <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                >
                  Alerts (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                >
                  Buttons (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Cards (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Dropdowns (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Forms (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Lists (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Modals (soon)
                </a>
              </div>
            </div>

            <!-- Pages links -->
            <div x-data="{ isActive: false, open: false }">
              <!-- active classes 'bg-primary-100 dark:bg-primary' -->
              <a
                href="#"
                @click="$event.preventDefault(); open = !open"
                class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }"
                role="button"
                aria-haspopup="true"
                :aria-expanded="(open || isActive) ? 'true' : 'false'"
              >
                <span aria-hidden="true">
                  <svg
                    class="w-5 h-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                    />
                  </svg>
                </span>
                <span class="ml-2 text-sm"> Pages </span>
                <span aria-hidden="true" class="ml-auto">
                  <!-- active class 'rotate-180' -->
                  <svg
                    class="w-4 h-4 transition-transform transform"
                    :class="{ 'rotate-180': open }"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </span>
              </a>
              <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="Pages">
                <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->
                <a
                  href="../pages/blank.html"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                >
                  Blank
                </a>
                <a
                  href="../pages/404.html"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                >
                  404
                </a>
                <a
                  href="../pages/500.html"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                >
                  500
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                >
                  Profile (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Pricing (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Kanban (soon)
                </a>
                <a
                  href="#"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Feed (soon)
                </a>
              </div>
            </div>

            <!-- Authentication links -->
            <div x-data="{ isActive: false, open: false}">
              <!-- active & hover classes 'bg-primary-100 dark:bg-primary' -->
              <a
                href="#"
                @click="$event.preventDefault(); open = !open"
                class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                :class="{'bg-primary-100 dark:bg-primary': isActive || open}"
                role="button"
                aria-haspopup="true"
                :aria-expanded="(open || isActive) ? 'true' : 'false'"
              >
                <span aria-hidden="true">
                  <svg
                    class="w-5 h-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                  </svg>
                </span>
                <span class="ml-2 text-sm"> Authentication </span>
                <span aria-hidden="true" class="ml-auto">
                  <!-- active class 'rotate-180' -->
                  <svg
                    class="w-4 h-4 transition-transform transform"
                    :class="{ 'rotate-180': open }"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </span>
              </a>
              <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" aria-label="Authentication">
                <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->
                <a
                  href="{{ route('register') }}"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Register
                </a>
                <a
                  href="{{ route('login') }}"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Login
                </a>
                <a
                  href="../auth/forgot-password.html"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Forgot Password
                </a>
                <a
                  href="../auth/reset-password.html"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:hover:text-light hover:text-gray-700"
                >
                  Reset Password
                </a>
              </div>
            </div>

            <!-- Layouts links -->
            <div x-data="{ isActive: true, open: true}">
              <!-- active & hover classes 'bg-primary-100 dark:bg-primary' -->
              <a
                href="#"
                @click="$event.preventDefault(); open = !open"
                class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                :class="{'bg-primary-100 dark:bg-primary': isActive || open}"
                role="button"
                aria-haspopup="true"
                :aria-expanded="(open || isActive) ? 'true' : 'false'"
              >
                <span aria-hidden="true">
                  <svg
                    class="w-5 h-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                    />
                  </svg>
                </span>
                <span class="ml-2 text-sm"> Layouts </span>
                <span aria-hidden="true" class="ml-auto">
                  <!-- active class 'rotate-180' -->
                  <svg
                    class="w-4 h-4 transition-transform transform"
                    :class="{ 'rotate-180': open }"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </span>
              </a>
              <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" aria-label="Layouts">
                <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->
                <a
                  href="two-columns-sidebar.html"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                >
                  Two Columns Sidebar
                </a>
                <a
                  href="two-columns-sidebar.html"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-700 transition-colors duration-200 rounded-md dark:text-light dark:hover:text-light hover:text-gray-700"
                >
                  Mini + One Columns Sidebar
                </a>
                <a
                  href="mini-column-sidebar.html"
                  role="menuitem"
                  class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700"
                >
                  Mini Column Sidebar
                </a>
              </div>
            </div>
          </nav>
        </aside>
