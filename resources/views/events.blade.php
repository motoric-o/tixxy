@extends('layouts.default')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 rounded-b-[3rem] shadow-2xl">
        <!-- Decorative blobs -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-purple-600 opacity-20 blur-3xl mix-blend-screen animate-blob"></div>
            <div class="absolute top-1/2 -right-24 w-96 h-96 rounded-full bg-indigo-600 opacity-20 blur-3xl mix-blend-screen animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-24 left-1/2 w-96 h-96 rounded-full bg-pink-600 opacity-20 blur-3xl mix-blend-screen animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-200 to-indigo-200 tracking-tight mb-6">
                    Discover Extraordinary Events
                </h1>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-indigo-100/80">
                    Find and book tickets to the best concerts, workshops, and conferences happening near you.
                </p>

                <!-- Search Bar -->
                <div class="mt-10 max-w-3xl mx-auto">
                    <form action="/events" method="GET" class="flex flex-col md:flex-row items-center gap-4 bg-white/10 dark:bg-gray-800/40 backdrop-blur-md p-2 rounded-2xl border border-white/20 shadow-xl">
                        <div class="relative w-full flex-grow flex items-center">
                            <svg class="absolute left-4 w-6 h-6 text-gray-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events, artists, or venues..." class="w-full bg-transparent border-none text-white placeholder-gray-300 focus:ring-0 pl-12 pr-4 py-3 rounded-xl">
                        </div>
                        <div class="hidden md:block w-px h-8 bg-white/20 mx-2"></div>
                        <select name="category" class="w-full md:w-auto bg-transparent border-none text-gray-200 focus:ring-0 py-3 px-4 rounded-xl [&>option]:text-gray-900">
                            <option value="">All Categories</option>
                            @php $cachedCategories = \App\Models\Category::all(); @endphp
                            @foreach($cachedCategories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-1">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Upcoming Events</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Don't miss out on these popular upcoming events.</p>
            </div>
            
            <!-- Filters -->
            <div class="flex gap-2 text-sm">
                <button class="px-4 py-2 bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 font-medium rounded-full border border-indigo-100 dark:border-indigo-800/50 transition-colors">Any Date</button>
                <button class="px-4 py-2 bg-white text-gray-600 dark:bg-gray-800 dark:text-gray-300 font-medium rounded-full border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">This Week</button>
                <button class="px-4 py-2 bg-white text-gray-600 dark:bg-gray-800 dark:text-gray-300 font-medium rounded-full border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">This Month</button>
            </div>
        </div>

        @php
            // Build the query gracefully from request inputs
            $query = \App\Models\Event::with('category');
            
            if (request('search')) {
                $query->where(function($q) {
                    // Using ilike for postgresql case-insensitive matching
                    $q->where('title', 'ilike', '%' . request('search') . '%')
                      ->orWhere('description', 'ilike', '%' . request('search') . '%')
                      ->orWhere('location', 'ilike', '%' . request('search') . '%');
                });
            }
            if (request('category')) {
                $query->where('category_id', request('category'));
            }
            
            // Execute paginated search and preserve URL query string parameters across pages
            $events = $query->orderBy('start_time', 'asc')->paginate(6)->withQueryString();
        @endphp

        <!-- AJAX Wrapper -->
        <div id="events-grid-wrapper">
            <!-- Events Grid -->
            <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 transition-opacity duration-300">
            @forelse($events as $event)
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 dark:hover:shadow-indigo-900/20 border border-gray-100 dark:border-gray-700/50 transition-all duration-300 transform hover:-translate-y-2 overflow-hidden flex flex-col h-full">
                <!-- Image Container -->
                <div class="relative h-56 overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    @if(isset($event->image_path))
                        <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <!-- Fallback Pattern -->
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-purple-500/20"></div>
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent"></div>
                    
                    <!-- Badges -->
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm dark:bg-gray-900/90 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm">
                            {{ $event->category->name ?? 'Event' }}
                        </span>
                    </div>
                    <div class="absolute top-4 right-4">
                        @if($event->status == 'ongoing' || $event->status == 'preparation')
                        <span class="flex items-center gap-1 px-3 py-1 bg-green-500/90 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Booking Open
                        </span>
                        @else
                        <span class="flex items-center gap-1 px-3 py-1 bg-gray-600/90 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            {{ ucfirst($event->status) }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Content Container -->
                <div class="p-6 flex-grow flex flex-col">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            {{ $event->title }}
                        </h3>
                    </div>
                    
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 line-clamp-2">{{ $event->description }}</p>

                    <!-- Details -->
                    <div class="mt-auto space-y-3">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mr-3 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span>{{ \Carbon\Carbon::parse($event->start_time)->format('D, M d • h:i A') }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mr-3 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Tickets Available</span>
                            <span class="text-lg font-extrabold text-gray-900 dark:text-white">{{ $event->quota }} left</span>
                        </div>
                        @if($event->status == 'ongoing' || $event->status == 'preparation')    
                        <a href="/checkout?event_id={{ $event->id }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded-xl hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transition-colors duration-300">
                            Get Tickets
                        </a>
                        @else
                        <button disabled class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-gray-400 bg-gray-100 dark:bg-gray-700/50 dark:text-gray-500 rounded-xl cursor-not-allowed">
                            Unavailable
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No events found locally.</p>
                </div>
            @endforelse

        </div>

        <!-- Functional Pagination -->
        @if($events->hasPages())
        <div class="mt-14 flex justify-center pagination-wrapper">
            {{ $events->links() }}
        </div>
        @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('events-grid-wrapper');
    
    // Listen for clicks within the events wrapper
    wrapper.addEventListener('click', function (e) {
        // Target specifically the pagination links
        const link = e.target.closest('.pagination-wrapper a[href]');
        if (!link) return;
        
        e.preventDefault();
        const url = link.href;
        
        // Add a gentle loading fade effect
        const container = document.getElementById('events-container');
        if (container) container.style.opacity = '0.5';
        
        // Fetch the new page asynchronously
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newWrapper = doc.getElementById('events-grid-wrapper');
                
                if (newWrapper) {
                    wrapper.innerHTML = newWrapper.innerHTML;
                    
                    // Update URL bar cleanly
                    window.history.pushState({path: url}, '', url);
                    
                    // Smoothly scroll back to the top of the events grid
                    const offsetTop = wrapper.getBoundingClientRect().top + window.scrollY - 100;
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            })
            .catch(error => {
                console.error('Error loading page:', error);
                // Fallback: standard navigation if ajax fails
                window.location.href = url;
            });
    });
    
    // Handle browser back/forward buttons seamlessly
    window.addEventListener('popstate', function() {
        window.location.reload();
    });
});
</script>

<style>
/* Custom animations for the blobs */
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
@endsection