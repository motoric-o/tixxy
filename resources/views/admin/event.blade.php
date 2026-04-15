@extends('layouts.admin.default')

@section('content')

<div x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'setup' }" @hashchange.window="activeTab = window.location.hash.substring(1) || 'setup'">

    <form action="/manage/events/{{ $item->id }}" method="POST" id="eventForm">
        @csrf
        @method('PUT')

        <div
            class="mb-8 flex flex-row justify-between items-center sticky top-4 z-20 bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl p-5 rounded-2xl border border-white/20 dark:border-gray-800/50 shadow-xl shadow-purple-500/5 ring-1 ring-black/5 dark:ring-white/5">
            <div class="flex flex-row items-center gap-6">
                <a href="{{ $backUrl }}"
                    class="group p-2.5 text-purple-100 hover:text-white rounded-xl bg-gradient-to-br from-purple-600 to-indigo-700 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/30 hover:-translate-y-0.5 active:scale-95 border border-white/10"
                    title="Back">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div class="flex flex-col">
                    <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Manage Event: <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-500 dark:from-purple-400 dark:to-indigo-300">{{ $item->title }}</span>
                    </h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Overview as of {{ now()->format('l, d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-row items-center gap-6">
                <div class="flex flex-col items-end gap-1.5">
                    <label
                        class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Assigned
                        Organizer</label>
                    <div class="relative group">
                        <select name="user_id"
                            class="appearance-none bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 block w-full pl-4 pr-10 py-2 dark:bg-gray-800/50 dark:border-gray-700 dark:text-white transition-all duration-300 cursor-pointer hover:border-purple-400/50 min-w-[200px]">
                            <option value="">Unassigned</option>
                            @foreach ($organizers as $orgId => $orgName)
                                <option value="{{ $orgId }}" @if (old('user_id', $item->user_id) == $orgId) selected @endif>
                                    {{ $orgName }}
                                </option>
                            @endforeach
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 group-hover:text-purple-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>
                    @error('user_id')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="h-10 w-px bg-gray-200 dark:bg-gray-700 mx-2"></div>

                
                {{-- Tab Navigation --}}
                <nav class="flex space-x-1.5 bg-gray-100/80 dark:bg-gray-800/80 p-1.5 rounded-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm shadow-inner">
                    <a href="#setup" @click.prevent="activeTab = 'setup'; window.location.hash = 'setup'" :class="activeTab === 'setup' ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-bold tracking-wide' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'" class="px-5 py-2.5 text-sm rounded-lg transition-all duration-300">Setup</a>
                    <a href="#analytics" @click.prevent="activeTab = 'analytics'; window.location.hash = 'analytics'" :class="activeTab === 'analytics' ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-bold tracking-wide' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'" class="px-5 py-2.5 text-sm rounded-lg transition-all duration-300">Analytics</a>
                    <a href="#orders" @click.prevent="activeTab = 'orders'; window.location.hash = 'orders'" :class="activeTab === 'orders' ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-bold tracking-wide' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'" class="px-5 py-2.5 text-sm rounded-lg transition-all duration-300">Orders <span class="ml-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300">{{ $orders->total() }}</span></a>
                </nav>


                <button type="submit" x-show="activeTab === 'setup'"
                    class="h-12 w-54 group inline-flex items-center gap-2.5 px-7 py-3 text-sm font-bold text-white rounded-xl bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 bg-[length:200%_auto] hover:bg-[position:right_center] shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 transition-all duration-500 hover:-translate-y-0.5 active:scale-95">
                    <svg class="w-4.5 h-4.5 transition-transform duration-500 group-hover:rotate-12" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Save Changes</span>
                </button>
            </div>
        </div>


        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm">
                <strong class="font-bold">Please correct the following errors:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Setup Tab --}}
        <div x-show="activeTab === 'setup'" x-transition.opacity.duration.300ms class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                {{-- Event Details --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div
                        class="flex flex-row items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Event Details</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Event
                                    Name</label>
                                <input type="text" name="title" value="{{ old('title', $item->title) }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                    required>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                <select name="category_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                    required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}" @if (old('category_id', $item->category_id) == $id) selected
                                        @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start
                                        Time</label>
                                    <input type="datetime-local" name="start_time"
                                        value="{{ old('start_time', $item->start_time ? $item->start_time->format('Y-m-d\TH:i') : '') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End
                                        Time</label>
                                    <input type="datetime-local" name="end_time"
                                        value="{{ old('end_time', $item->end_time ? $item->end_time->format('Y-m-d\TH:i') : '') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                        required>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                                <input type="text" name="location" value="{{ old('location', $item->location) }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                    required>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                <textarea name="description" rows="4"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">{{ old('description', $item->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Event Settings --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Event Settings</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select name="status"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-600 focus:border-purple-600 dark:focus:ring-purple-500 dark:focus:border-purple-500">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @if (old('status', $item->status) == $value) selected @endif>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quota</label>
                                <input type="number" name="quota" id="eventQuota" value="{{ old('quota', $item->quota) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-600 focus:border-purple-600 dark:focus:ring-purple-500 dark:focus:border-purple-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ticket Types --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div
                        class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ticket Types</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" id="addTicketBtn"
                                class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                + Add Ticket Type
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4" id="ticketContainer">
                            @php $tickets = old('ticket_types', is_array($eventTicketTypesData) ? $eventTicketTypesData : $eventTicketTypesData->toArray()); @endphp

                            @forelse($tickets as $index => $ticket)
                                @php
                                    $isSold = isset($ticket['sold_count']) && $ticket['sold_count'] > 0;
                                    $soldCount = $ticket['sold_count'] ?? 0;
                                @endphp
                                <div class="ticket-row border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <input type="hidden" name="ticket_types[{{ $index }}][id]"
                                        value="{{ $ticket['id'] ?? '' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                        <div class="col-span-2 md:col-span-1">
                                            <label
                                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Type</label>
                                            <select name="ticket_types[{{ $index }}][ticket_type_id]"
                                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                required>
                                                <option value="">Select</option>
                                                @foreach ($ticketTypes as $tt)
                                                    <option value="{{ $tt->id }}" @if (($ticket['ticket_type_id'] ?? '') == $tt->id)
                                                    selected @endif>
                                                        {{ $tt->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-2 md:col-span-1">
                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                Price (Rp)
                                                @if ($isSold)
                                                    <span class="text-[10px] text-amber-600 ml-1">(Locked)</span>
                                                @endif
                                            </label>
                                            <input type="number" step="0.01" name="ticket_types[{{ $index }}][price]"
                                                value="{{ $ticket['price'] ?? 0 }}" @if ($isSold) readonly @endif
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white @if ($isSold) opacity-60 bg-gray-200 dark:bg-gray-800 cursor-not-allowed @endif"
                                                required>
                                        </div>
                                        <div class="col-span-2 md:col-span-1">
                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                Capacity
                                                @if ($isSold)
                                                    <span class="text-[10px] text-blue-600 ml-1">(Min:
                                                        {{ $soldCount }})</span>
                                                @endif
                                            </label>
                                            <input type="number" name="ticket_types[{{ $index }}][capacity]"
                                                value="{{ $ticket['capacity'] ?? 0 }}" min="{{ $soldCount }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                required>
                                        </div>
                                        <div class="col-span-2 md:col-span-1 flex justify-end pb-1 h-full items-end">
                                            @if (!$isSold)
                                                <button type="button"
                                                    class="btn-remove-ticket w-full text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 border border-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 px-3 py-2 rounded transition-colors duration-200">Remove</button>
                                            @else
                                                <span class="text-xs text-gray-400 py-2">Sales: {{ $soldCount }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div id="emptyTicketMsg"
                                    class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                                    No ticket types configured for this event.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            <div class="space-y-6">
                {{-- Quick Stats --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Stats</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Total Tickets Sold</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($performanceData['totalTicketsSold'] ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Revenue</span>
                                <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($performanceData['totalRevenue'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Active Orders</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($performanceData['totalOrdersPending'] ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
            </div>
    </form>

    {{-- Analytics Tab --}}
    <div x-show="activeTab === 'analytics'" x-transition.opacity.duration.300ms class="mt-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

        {{-- Total Gross Revenue --}}
        <div class="stat-card relative overflow-hidden rounded-2xl
                        bg-gradient-to-br from-emerald-500/20 to-emerald-700/10
                        border border-emerald-500/20 dark:border-emerald-500/10
                        p-5 shadow-sm dark:bg-gray-700/40
                        group hover:shadow-[0_0_24px_rgba(52,211,153,0.2)] transition-all duration-300">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-400">Gross Revenue</p>
                    {{-- @BACKEND_VAR: $performanceData['totalRevenue'] — sum of completed orders for this event --}}
                    <p id="stat-revenue" class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($performanceData['totalRevenue'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="p-2.5 rounded-xl bg-emerald-500/20 text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {{-- Heroicons: currency-dollar --}}
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            {{-- @BACKEND_VAR: $performanceData['totalOrdersCompleted'] & $performanceData['pendingOrdersValue'] --}}
            <p id="stat-revenue-sub" class="text-xs text-gray-500 dark:text-gray-400">
                From <span class="text-emerald-400 font-semibold">{{ number_format($performanceData['totalOrdersCompleted'] ?? 0) }}</span>
                orders
                &bull; <span class="text-amber-400">Rp {{ number_format($performanceData['pendingOrdersValue'] ?? 0, 0, ',', '.') }}
                    pending</span>
            </p>
            <div
                class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-emerald-500/10 group-hover:bg-emerald-500/20 transition-all duration-500">
            </div>
        </div>

        {{-- Sell-Through Rate --}}
        <div class="stat-card relative overflow-hidden rounded-2xl
                        bg-gradient-to-br from-blue-500/20 to-blue-700/10
                        border border-blue-500/20 dark:border-blue-500/10
                        p-5 shadow-sm dark:bg-gray-700/40
                        group hover:shadow-[0_0_24px_rgba(59,130,246,0.2)] transition-all duration-300">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-400">Sell-Through Rate</p>
                    {{-- @BACKEND_VAR: $performanceData['sellThroughRate'] — round((sold/capacity)*100, 1) --}}
                    <p id="stat-sellthrough" class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $performanceData['sellThroughRate'] ?? 0 }}%
                    </p>
                </div>
                <div class="p-2.5 rounded-xl bg-blue-500/20 text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {{-- Heroicons: ticket --}}
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
            </div>
            {{-- @BACKEND_VAR: $performanceData['totalTicketsSold'], $performanceData['totalCapacity'] --}}
            <div class="mb-1">
                <div class="h-1.5 w-full bg-blue-900/30 rounded-full overflow-hidden">
                    <div id="stat-sellthrough-bar"
                        class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all duration-700"
                        style="width: {{ $performanceData['sellThroughRate'] ?? 0 }}%"></div>
                </div>
            </div>
            <p id="stat-sellthrough-sub" class="text-xs text-gray-500 dark:text-gray-400">
                <span class="text-blue-400 font-semibold">{{ number_format($performanceData['totalTicketsSold'] ?? 0) }}</span>
                / {{ number_format($performanceData['totalCapacity'] ?? 0) }} tickets sold
            </p>
            <div
                class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-blue-500/10 group-hover:bg-blue-500/20 transition-all duration-500">
            </div>
        </div>

        {{-- Average Order Value --}}
        <div class="stat-card relative overflow-hidden rounded-2xl
                        bg-gradient-to-br from-purple-500/20 to-purple-700/10
                        border border-purple-500/20 dark:border-purple-500/10
                        p-5 shadow-sm dark:bg-gray-700/40
                        group hover:shadow-[0_0_24px_rgba(168,85,247,0.2)] transition-all duration-300">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-purple-400">Avg. Order Value</p>
                    {{-- @BACKEND_VAR: $performanceData['avgOrderValue'] — avg of completed order amounts for this event --}}
                    <p id="stat-aov" class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($performanceData['avgOrderValue'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="p-2.5 rounded-xl bg-purple-500/20 text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {{-- Heroicons: chart-bar --}}
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            {{-- @BACKEND_VAR: $performanceData['totalOrdersCompleted'] --}}
            <p id="stat-aov-sub" class="text-xs text-gray-500 dark:text-gray-400">
                Per completed order &bull;
                <span class="text-purple-400 font-semibold">{{ number_format($performanceData['totalOrdersCompleted'] ?? 0) }}</span> orders
            </p>
            <div
                class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-purple-500/10 group-hover:bg-purple-500/20 transition-all duration-500">
            </div>
        </div>

        {{-- Conversion Rate --}}
        <div class="stat-card relative overflow-hidden rounded-2xl
                        bg-gradient-to-br from-amber-500/20 to-amber-700/10
                        border border-amber-500/20 dark:border-amber-500/10
                        p-5 shadow-sm dark:bg-gray-700/40
                        group hover:shadow-[0_0_24px_rgba(245,158,11,0.2)] transition-all duration-300">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-400">Conversion Rate</p>
                    {{-- @BACKEND_VAR: $performanceData['conversionRate'] — round((completed / (completed+canceled+pending)) * 100, 1) --}}
                    <p id="stat-conversion" class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $performanceData['conversionRate'] ?? 0 }}%
                    </p>
                </div>
                <div class="p-2.5 rounded-xl bg-amber-500/20 text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {{-- Heroicons: trending-up --}}
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            {{-- @BACKEND_VAR: $performanceData['totalOrdersCompleted'], $performanceData['totalOrdersPending'], $performanceData['totalOrdersCanceled'] --}}
            <p id="stat-conversion-sub" class="text-xs text-gray-500 dark:text-gray-400">
                <span class="text-emerald-400 font-semibold">{{ $performanceData['totalOrdersCompleted'] ?? 0 }}</span> completed
                &bull; <span class="text-amber-400">{{ $performanceData['totalOrdersPending'] ?? 0 }}</span> pending
                &bull; <span class="text-red-400">{{ $performanceData['totalOrdersCanceled'] ?? 0 }}</span> canceled
            </p>
            <div
                class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-amber-500/10 group-hover:bg-amber-500/20 transition-all duration-500">
            </div>
        </div>

    </div>{{-- end row 1 --}}

    {{-- ═══════════════════════════════════════════════════════════════
    ROW 2 – Sales Velocity + Attendance Mix
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        {{-- Sales Velocity Line Chart (2/3) --}}
        <div
            class="lg:col-span-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/60 p-5 shadow-sm backdrop-blur-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Sales Velocity</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Tickets sold over selected timeframe</p>
                </div>
                <span id="velocity-total-badge"
                    class="text-xs px-2.5 py-1 rounded-full bg-indigo-500/10 text-indigo-400 font-medium border border-indigo-500/20">
                    {{-- @BACKEND_VAR: $performanceData['totalTicketsSold'] --}}
                    {{ number_format($performanceData['totalTicketsSold'] ?? 0) }} total tickets
                </span>
            </div>
            <div class="relative h-56">
                <canvas id="velocityChart"></canvas>
            </div>
        </div>

        {{-- Attendance Doughnut (1/3) --}}
        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/60 p-5 shadow-sm backdrop-blur-sm">
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Attendance Summary</h3>
                <p class="text-xs text-gray-400 mt-0.5">Checked-in vs. No-show</p>
            </div>
            <div class="flex flex-col items-center gap-4">
                <div class="relative w-36 h-36">
                    <canvas id="attendanceChart"></canvas>
                    {{-- Center label --}}
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        {{-- @BACKEND_VAR: $performanceData['attendanceRate'] --}}
                        <span id="attendance-rate-label"
                            class="text-2xl font-extrabold text-gray-800 dark:text-white">{{ $performanceData['attendanceRate'] ?? 0 }}%</span>
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Check-in</span>
                    </div>
                </div>
                <div class="w-full space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 inline-block"></span>
                            <span class="text-gray-500 dark:text-gray-400">Checked In</span>
                        </div>
                        {{-- @BACKEND_VAR: $performanceData['totalTicketsScanned'] --}}
                        <span id="stat-scanned"
                            class="font-semibold text-gray-800 dark:text-white">{{ number_format($performanceData['totalTicketsScanned'] ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-gray-600 inline-block"></span>
                            <span class="text-gray-500 dark:text-gray-400">No-show</span>
                        </div>
                        {{-- @BACKEND_VAR: $performanceData['totalTicketsSold'] - $performanceData['totalTicketsScanned'] --}}
                        <span id="stat-noshow"
                            class="font-semibold text-gray-800 dark:text-white">{{ number_format(($performanceData['totalTicketsSold'] ?? 0) - ($performanceData['totalTicketsScanned'] ?? 0)) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- end row 2 --}}

    {{-- ═══════════════════════════════════════════════════════════════
    ROW 3 – Ticket Tier Breakdown
    ═══════════════════════════════════════════════════════════════ --}}
    <div
        class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/60 p-5 shadow-sm backdrop-blur-sm mb-6">

        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Ticket Tier Performance</h3>
                <p class="text-xs text-gray-400 mt-0.5">Breakdown by ticket type</p>
            </div>
            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {{-- Heroicons: table --}}
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M3 14h18M10 6h4m-4 12h4M3 6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6z" />
            </svg>
        </div>

        <div class="overflow-x-auto -mx-1">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                        <th class="pb-3 px-2">Tier</th>
                        <th class="pb-3 px-2 text-right">Price</th>
                        <th class="pb-3 px-2 text-right">Capacity</th>
                        <th class="pb-3 px-2 text-right">Sold</th>
                        <th class="pb-3 px-2 text-right">Revenue</th>
                        <th class="pb-3 px-2">Fill Rate</th>
                    </tr>
                </thead>
                <tbody id="tier-table-body" class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse ($performanceData['tierBreakdown'] as $tier)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">
                            <td class="py-3.5 px-2">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $tier['name'] }}</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-2 text-right text-gray-600 dark:text-gray-300">
                                Rp {{ number_format($tier['price'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-2 text-right text-gray-600 dark:text-gray-300">
                                {{ number_format($tier['capacity']) }}
                            </td>
                            <td class="py-3.5 px-2 text-right font-semibold text-gray-800 dark:text-white">
                                {{ number_format($tier['sold']) }}
                            </td>
                            <td class="py-3.5 px-2 text-right text-emerald-500 font-semibold">
                                Rp {{ number_format($tier['revenue'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-2 min-w-[120px]">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-700
                                                            {{ $tier['fill'] >= 90 ? 'bg-emerald-400' : ($tier['fill'] >= 60 ? 'bg-blue-400' : 'bg-amber-400') }}"
                                            style="width: {{ $tier['fill'] }}%"></div>
                                    </div>
                                    <span
                                        class="text-xs font-medium {{ $tier['fill'] >= 90 ? 'text-emerald-400' : ($tier['fill'] >= 60 ? 'text-blue-400' : 'text-amber-400') }}">
                                        {{ $tier['fill'] }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-sm text-gray-400">No ticket tiers configured for this
                                event.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>{{-- end row 3 --}}

    </div>{{-- end analytics tab --}}

    {{-- ═══════════════════════════════════════════════════════════════
    Chart.js + AJAX Logic
    ═══════════════════════════════════════════════════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>


    {{-- Orders Tab --}}
    <div x-show="activeTab === 'orders'" x-transition.opacity.duration.300ms class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Orders</h3>
            <p class="text-sm text-gray-500 mt-1">Complete history of transactions for this event.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="py-3.5 px-6">Order ID</th>
                        <th class="py-3.5 px-6">Customer</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6 text-right">Amount</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50 text-gray-900 dark:text-white">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">
                            <td class="py-4 px-6 font-medium text-indigo-600 dark:text-indigo-400">#{{ $order->id }}</td>
                            <td class="py-4 px-6">{{ $order->user->name }}</td>
                            <td class="py-4 px-6 text-gray-500 dark:text-gray-400">{{ $order->created_at->format('M d, Y H:i') }}</td>
                            <td class="py-4 px-6 text-right font-medium">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($order->status == 'completed') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                                    @elseif($order->status == 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                    @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="/manage/orders/{{ $order->id }}/edit" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">No orders found for this event yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $orders->links() }}
            </div>
        @endif
    </div>


    {{-- Template for new ticket rows --}}
    <template id="ticketTemplate">
        <div class="ticket-row border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
            <input type="hidden" name="ticket_types[__INDEX__][id]" value="">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Type</label>
                    <select name="ticket_types[__INDEX__][ticket_type_id]"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                        <option value="">Select</option>
                        @foreach ($ticketTypes as $tt)
                            <option value="{{ $tt->id }}">{{ $tt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Price (Rp)</label>
                    <input type="number" step="0.01" name="ticket_types[__INDEX__][price]" value="0"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Capacity</label>
                    <input type="number" name="ticket_types[__INDEX__][capacity]" value="0" min="0"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                </div>
                <div class="col-span-2 md:col-span-1 flex justify-end pb-1 h-full items-end">
                    <button type="button"
                        class="btn-remove-ticket w-full text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 border border-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 px-3 py-2 rounded transition-colors duration-200">Remove</button>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let ticketIndex =
                {{ collect(old('ticket_types', $eventTicketTypesData))->count() ? max(array_keys(old('ticket_types', is_array($eventTicketTypesData) ? $eventTicketTypesData : $eventTicketTypesData->toArray()))) + 1 : 0 }};
            const container = document.getElementById('ticketContainer');
            const template = document.getElementById('ticketTemplate').innerHTML;

            document.getElementById('addTicketBtn').addEventListener('click', function () {
                const emptyMsg = document.getElementById('emptyTicketMsg');
                if (emptyMsg) emptyMsg.style.display = 'none';

                const newRowHtml = template.replace(/__INDEX__/g, ticketIndex++);
                container.insertAdjacentHTML('beforeend', newRowHtml);
            });

            container.addEventListener('click', function (e) {
                if (e.target.closest('.btn-remove-ticket')) {
                    const row = e.target.closest('.ticket-row');
                    row.remove();
                    if (container.querySelectorAll('.ticket-row').length === 0) {
                        const emptyMsg = document.getElementById('emptyTicketMsg');
                        if (emptyMsg) emptyMsg.style.display = 'block';
                    }
                }
            });
        });
    </script>
    <script>
        (function () {
            // Theme helpers
            const isDark = () => document.documentElement.classList.contains('dark');
            const gridColor = () => isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
            const labelColor = () => isDark() ? '#9ca3af' : '#6b7280';
            const tooltipOpts = () => ({
                backgroundColor: isDark() ? '#1f2937' : '#ffffff',
                titleColor: isDark() ? '#e5e7eb' : '#111827',
                bodyColor: isDark() ? '#9ca3af' : '#6b7280',
                borderColor: isDark() ? '#374151' : '#e5e7eb',
                borderWidth: 1,
            });

            // Format helpers
            const fmtRp = v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v);
            const fmtNum = v => new Intl.NumberFormat('id-ID').format(v);

            let velocityChart, attendanceChart;

            function buildVelocityChart(labels, data) {
                const ctx = document.getElementById('velocityChart');
                if(!ctx) return;
                const grad = ctx.getContext('2d').createLinearGradient(0, 0, 0, 220);
                grad.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
                grad.addColorStop(1, 'rgba(99, 102, 241, 0)');

                velocityChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Tickets Sold',
                            data: data,
                            fill: true,
                            backgroundColor: grad,
                            borderColor: 'rgba(99,102,241,0.9)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(99,102,241,1)',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { ...tooltipOpts(), callbacks: { label: c => fmtNum(c.raw) + ' tickets' } }
                        },
                        scales: {
                            x: { grid: { color: gridColor() }, ticks: { color: labelColor(), font: { size: 11 } } },
                            y: { grid: { color: gridColor() }, ticks: { color: labelColor(), font: { size: 11 } }, beginAtZero: true }
                        }
                    }
                });
            }

            function buildAttendanceChart(scanned, noshow) {
                const ctx = document.getElementById('attendanceChart');
                if (!ctx) return;
                attendanceChart = new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Checked In', 'No-show'],
                        datasets: [{
                            data: [scanned, noshow || 0.001],
                            backgroundColor: ['rgba(99,102,241,0.85)', isDark() ? 'rgba(75,85,99,0.4)' : 'rgba(229,231,235,0.8)'],
                            borderColor: isDark() ? '#1f2937' : '#ffffff',
                            borderWidth: 3,
                            hoverOffset: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: { legend: { display: false }, tooltip: tooltipOpts() }
                    }
                });
            }

            // We inject the actual labels array directly from Blade
            const chartLabels = {!! json_encode($performanceData['chartLabels'] ?? []) !!};
            const chartVelocity = {!! json_encode($performanceData['chartVelocity'] ?? []) !!};
            const scannedCount = {{ $performanceData['totalTicketsScanned'] ?? 0 }};
            const noshowCount = {{ ($performanceData['totalTicketsSold'] ?? 0) - ($performanceData['totalTicketsScanned'] ?? 0) }};

            // Needs to be rebuilt when switching tabs since charts use width/height and might 0 out when display: none
            let chartRendered = false;
            
            function initCharts() {
                if (chartRendered) return;
                buildVelocityChart(chartLabels, chartVelocity);
                buildAttendanceChart(scannedCount, noshowCount);
                chartRendered = true;
            }

            // Use an Intersection Observer to delay render until element is actually visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        initCharts();
                    }
                });
            });

            const analyticsTab = document.querySelector('[x-show="activeTab === \'analytics\'"]');
            if (analyticsTab) {
                observer.observe(analyticsTab);
            }
        })();
    </script>

</div>

@endsection