@extends('layouts.default')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <!-- Dynamic Background elements -->
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-pulse"></div>
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-pulse animation-delay-2000"></div>
            <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-pink-500/20 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-pulse animation-delay-4000"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-gray-50/50 to-gray-50 dark:from-gray-900/50 dark:to-gray-900"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 backdrop-blur-md shadow-sm mb-8 text-sm font-medium text-gray-900 dark:text-white">
                <span class="flex h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                <span class="flex h-2 w-2 rounded-full bg-indigo-500 absolute"></span>
                <span class="ml-2">Over 1,000+ live events happening today</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight mb-8">
                Your Ticket to <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">Unforgettable</span> Experiences
            </h1>
            
            <p class="max-w-2xl mx-auto text-xl text-gray-600 dark:text-gray-300 mb-10">
                Discover, book, and experience the best concerts, workshops, and exclusive events near you. Join millions of event-goers worldwide.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/events" class="w-full sm:w-auto px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    Explore Events
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
                <a href="#" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl border border-gray-100 dark:border-gray-700 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    How it works
                </a>
            </div>
            
            <!-- Removed Trust badges -->
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-20 bg-white dark:bg-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Browse by Category</h2>
                    <p class="text-gray-500 dark:text-gray-400">Discover events perfectly tailored to your interests.</p>
                </div>
                <a href="/events" class="hidden md:flex items-center gap-1 text-indigo-600 dark:text-indigo-400 font-semibold hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                    See All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Category 1 -->
                <a href="#" class="group relative rounded-3xl overflow-hidden aspect-square flex items-end p-6 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <img src="https://images.unsplash.com/photo-1459749411175-04bf5292ceea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Concerts" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-300"></div>
                    <div class="relative z-10 w-full">
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center mb-3 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-1">Music & Concerts</h3>
                        <p class="text-sm text-gray-300 font-medium">{{ $musicCount }} Events</p>
                    </div>
                </a>

                <!-- Category 2 -->
                <a href="#" class="group relative rounded-3xl overflow-hidden aspect-square flex items-end p-6 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Tech" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-900 via-blue-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-300"></div>
                    <div class="relative z-10 w-full">
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center mb-3 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-1">Tech Seminars</h3>
                        <p class="text-sm text-blue-200 font-medium">{{ $techCount }} Events</p>
                    </div>
                </a>

                <!-- Category 3 -->
                <a href="#" class="group relative rounded-3xl overflow-hidden aspect-square flex items-end p-6 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Art" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-pink-900 via-pink-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-300"></div>
                    <div class="relative z-10 w-full">
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center mb-3 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-1">Art & Culture</h3>
                        <p class="text-sm text-pink-200 font-medium">{{ $artCount }} Events</p>
                    </div>
                </a>

                <!-- Category 4 -->
                <a href="#" class="group relative rounded-3xl overflow-hidden aspect-square flex items-end p-6 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Sports" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-green-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-300"></div>
                    <div class="relative z-10 w-full">
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center mb-3 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-1">Sports & Wellness</h3>
                        <p class="text-sm text-green-200 font-medium">{{ $sportsCount }} Events</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Tixxy -->
    <section class="py-20 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Why Book With Us?</h2>
                <p class="text-gray-500 dark:text-gray-400 text-lg">We make discovering and securing tickets for your favorite events seamless and worry-free from start to finish.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="w-16 h-16 mx-auto bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center mb-6 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">100% Secure Checkout</h3>
                    <p class="text-gray-500 dark:text-gray-400">Your payments are fully protected through our industry-leading secure payment gateways.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="w-16 h-16 mx-auto bg-purple-50 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mb-6 text-purple-600 dark:text-purple-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Mobile Ticketing</h3>
                    <p class="text-gray-500 dark:text-gray-400">Skip the lines with our digital mobile tickets. Accessible directly from your smartphone.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="w-16 h-16 mx-auto bg-pink-50 dark:bg-pink-900/30 rounded-2xl flex items-center justify-center mb-6 text-pink-600 dark:text-pink-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Priority Support</h3>
                    <p class="text-gray-500 dark:text-gray-400">Our dedicated customer support team is available 24/7 to help you with your booking.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Create Event CTA -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-gray-900 to-indigo-900 dark:from-indigo-900 dark:to-purple-900 rounded-[3rem] p-10 md:p-16 text-center shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1470229722913-7c090be34b3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] mix-blend-overlay opacity-20 bg-cover bg-center"></div>
                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Are you an event organizer?</h2>
                    <p class="text-lg text-indigo-100 mb-10">Host your event on Tixxy to reach millions of users. Manage ticket sales, capacities, and attendees in one place effortlessly.</p>
                    <a href="/events/create" class="inline-flex px-8 py-4 bg-white text-indigo-600 hover:bg-indigo-50 font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        Create Event Now
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: .7; transform: scale(1.05); }
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
@endsection