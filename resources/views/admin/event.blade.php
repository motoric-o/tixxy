@extends('layouts.admin.default')

@section('content')


        <div x-data="{ 
                activeTab: window.location.hash ? window.location.hash.substring(1) : 'setup',
                quota: {{ (int)($item->quota ?? 0) }},
                allocatedQuota: 0,
                performanceData: {{ json_encode($performanceData) }},
                init() {
                    this.recalculateAllocation();
                    setInterval(() => {
                        this.fetchPerformance();
                    }, 15000);
                },
                fetchPerformance() {
                    fetch(`/manage/events/{{ $item->id }}/performance/data`)
                        .then(response => response.json())
                        .then(data => {
                            this.performanceData = data;
                            // Update charts globally if they exist
                            if (window.velocityChart) {
                                window.velocityChart.data.labels = data.chartLabels;
                                window.velocityChart.data.datasets[0].data = data.chartVelocity;
                                window.velocityChart.update();
                            }
                            if (window.revenueChart) {
                                window.revenueChart.data.labels = data.chartLabels;
                                window.revenueChart.data.datasets[0].data = data.chartRevenueVelocity;
                                window.revenueChart.update();
                            }
                            if (window.attendanceChart) {
                                const noshow = data.totalTicketsSold - data.totalTicketsScanned;
                                window.attendanceChart.data.datasets[0].data = [data.totalTicketsScanned, noshow || 0.001];
                                window.attendanceChart.update();
                            }
                        })
                        .catch(error => console.error('Error fetching performance data:', error));
                },
                recalculateAllocation() {
                    const inputs = Array.from(document.querySelectorAll('#ticketContainer input[name*=\'[capacity]\']'));
                    this.allocatedQuota = inputs.reduce((acc, input) => acc + (parseInt(input.value) || 0), 0);
                },
                addTicket() {
                    const emptyMsg = document.getElementById('emptyTicketMsg');
                    if (emptyMsg) emptyMsg.style.display = 'none';
                    
                    const container = document.getElementById('ticketContainer');
                    const template = document.getElementById('ticketTemplate').innerHTML;
                    
                    // We need a unique index for Laravel submission. 
                    // Using timestamp to avoid collisions since we don't have a simple counter.
                    const index = 'new_' + Date.now();
                    const newRowHtml = template.replace(/__INDEX__/g, index);
                    
                    container.insertAdjacentHTML('beforeend', newRowHtml);
                    this.recalculateAllocation();
                },
                removeTicket(el) {
                    const row = el.closest('.ticket-row');
                    row.remove();
                    
                    this.recalculateAllocation();
                    
                    const container = document.getElementById('ticketContainer');
                    if (container.querySelectorAll('.ticket-row').length === 0) {
                        const emptyMsg = document.getElementById('emptyTicketMsg');
                        if (emptyMsg) emptyMsg.style.display = 'block';
                    }
                },
                formatRp(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                },
                formatNum(value) {
                    return new Intl.NumberFormat('id-ID').format(value);
                }
            }" @hashchange.window="activeTab = window.location.hash.substring(1) || 'setup'">

        <div
            class="mb-8 flex flex-row justify-between items-center sticky top-4 z-20 bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl p-5 rounded-2xl border border-white/20 dark:border-gray-800/50 shadow-xl shadow-purple-500/5 ring-1 ring-black/5 dark:ring-white/5">
            <div class="flex flex-row items-center gap-6">
                <x-admin.button variant="action-purple" :href="$backUrl" title="Back" class="group">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </x-admin.button>
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
                        <select name="user_id" form="eventForm"
                            class="appearance-none bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 block w-full pl-4 pr-10 py-2 dark:bg-gray-800/50 dark:border-gray-700 dark:text-white transition-all duration-300 min-w-[200px] {{ Auth::user()->role === 'admin' ? 'cursor-pointer hover:border-purple-400/50' : 'cursor-default' }}"
                            {{ Auth::user()->role === 'organizer' ? 'disabled' : '' }}>
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
                <nav
                    class="flex space-x-1.5 bg-gray-100/80 dark:bg-gray-800/80 p-1.5 rounded-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm shadow-inner">
                    <a href="#setup" @click.prevent="activeTab = 'setup'; window.location.hash = 'setup'"
                        :class="activeTab === 'setup' ?
                            'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-bold tracking-wide' :
                            'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'"
                        class="px-5 py-2.5 text-sm rounded-lg transition-all duration-300">Setup</a>
                    <a href="#analytics" @click.prevent="activeTab = 'analytics'; window.location.hash = 'analytics'"
                        :class="activeTab === 'analytics' ?
                            'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-bold tracking-wide' :
                            'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'"
                        class="px-5 py-2.5 text-sm rounded-lg transition-all duration-300">Analytics</a>
                    <a href="#orders" @click.prevent="activeTab = 'orders'; window.location.hash = 'orders'"
                        :class="activeTab === 'orders' ?
                            'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-bold tracking-wide' :
                            'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'"
                        class="px-5 py-2.5 text-sm rounded-lg transition-all duration-300">Orders <span
                            class="ml-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300">{{ $orders->total() }}</span></a>
                </nav>


                @if(Auth::user()->role === 'admin')
                <x-admin.button type="submit" form="eventForm" x-show="activeTab === 'setup'" class="h-12 w-54">
                    <svg class="w-4.5 h-4.5 transition-transform duration-500 group-hover:rotate-12" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    <span>Save Changes</span>
                </x-admin.button>
                @endif
            </div>
        </div>

        <form action="/manage/events/{{ $item->id }}" method="POST" id="eventForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')



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
            <div x-show="activeTab === 'setup'" x-transition.opacity.duration.300ms
                class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-8 animate-in fade-in slide-in-from-left-4 duration-500">
                    {{-- Event Details --}}
                    <x-admin.card header="Event Details">
                        <div class="space-y-6">
                            <x-admin.input label="Event Name" name="title" :value="$item->title" required :disabled="Auth::user()->role === 'organizer'" />
                            
                            <x-admin.select label="Category" name="category_id" required :disabled="Auth::user()->role === 'organizer'">
                                <option value="">Select Category</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" @if (old('category_id', $item->category_id) == $id) selected @endif>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </x-admin.select>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-admin.input type="datetime-local" label="Start Time" name="start_time" 
                                    :value="$item->start_time ? $item->start_time->format('Y-m-d\TH:i') : ''" 
                                    required :disabled="Auth::user()->role === 'organizer'" />
                                
                                <x-admin.input type="datetime-local" label="End Time" name="end_time" 
                                    :value="$item->end_time ? $item->end_time->format('Y-m-d\TH:i') : ''" 
                                    required :disabled="Auth::user()->role === 'organizer'" />
                            </div>

                            <x-admin.input label="Location" name="location" :value="$item->location" required :disabled="Auth::user()->role === 'organizer'" />

                            <x-admin.image-upload label="Event Banner" name="banner_path" :value="$item->banner_path" :disabled="Auth::user()->role !== 'admin'" />

                            <x-admin.textarea label="Description" name="description" :value="$item->description" rows="4" :disabled="Auth::user()->role === 'organizer'" />
                        </div>
                    </x-admin.card>

                    {{-- Event Settings --}}
                    <x-admin.card header="Event Settings">
                        <div class="space-y-6">
                            <x-admin.select label="Status" name="status" :disabled="Auth::user()->role === 'organizer'">
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @if (old('status', $item->status) == $value) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-admin.select>

                            <x-admin.input type="number" label="Total Event Capacity" name="quota" id="eventQuota" 
                                x-model.number="quota" :disabled="Auth::user()->role === 'organizer'" />
                        </div>
                    </x-admin.card>

                    {{-- Ticket Types --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-8 py-6 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-[0.1em]">Ticket Types</h3>
                                
                                {{-- Allocation Badge --}}
                                <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700" 
                                     :class="allocatedQuota > quota ? 'rose-50 dark:rose-900/20 border-rose-200 dark:border-rose-800' : ''">
                                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Allocation:</span>
                                    <span class="text-xs font-black" :class="allocatedQuota > quota ? 'text-rose-600 dark:text-rose-400' : 'text-purple-600 dark:text-purple-400'">
                                        <span x-text="allocatedQuota"></span> / <span x-text="quota"></span>
                                    </span>
                                    <template x-if="allocatedQuota > quota">
                                        <svg class="w-3 h-3 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                    </template>
                                </div>

                                {{-- Sold Badge --}}
                                <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-500/30">
                                    <span class="text-[10px] font-bold text-indigo-400 dark:text-indigo-500 uppercase tracking-widest">Sold:</span>
                                    <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">
                                        <span x-text="formatNum(performanceData.totalTicketsSold)"></span> / <span x-text="quota"></span>
                                    </span>
                                </div>
                            </div>
                            @if(Auth::user()->role === 'admin')
                                <button type="button" @click="addTicket()"
                                    class="inline-flex items-center gap-2 text-xs font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors uppercase tracking-widest">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Add Ticket Type
                                </button>
                            @endif
                        </div>
                        <div class="p-8">
                            <div class="space-y-6" id="ticketContainer">
                                @php $tickets = old('ticket_types', is_array($eventTicketTypesData) ? $eventTicketTypesData : $eventTicketTypesData->toArray()); @endphp
                                @forelse($tickets as $index => $ticket)
                                    @php
                                        $isSold = isset($ticket['sold_count']) && $ticket['sold_count'] > 0;
                                        $soldCount = $ticket['sold_count'] ?? 0;
                                    @endphp
                                    <div class="ticket-row border border-gray-100 dark:border-gray-700/50 rounded-2xl p-6 bg-gray-50/30 dark:bg-gray-900/20 relative group overflow-hidden">
                                        <input type="hidden" name="ticket_types[{{ $index }}][id]"
                                            value="{{ $ticket['id'] ?? '' }}">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end relative z-10">
                                            <div class="col-span-2 md:col-span-1">
                                                <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Type</label>
                                                <div class="relative">
                                                    <select name="ticket_types[{{ $index }}][ticket_type_id]"
                                                        class="appearance-none w-full h-[46px] bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block pl-4 pr-10 transition-all duration-300 cursor-pointer !bg-none"
                                                        required {{ Auth::user()->role === 'organizer' ? 'disabled' : '' }}>
                                                        <option value="">Select</option>
                                                        @foreach ($ticketTypes as $tt)
                                                            <option value="{{ $tt->id }}"
                                                                @if (($ticket['ticket_type_id'] ?? '') == $tt->id) selected @endif>
                                                                {{ $tt->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-span-2 md:col-span-1">
                                                <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5 ml-1">
                                                    Price (Rp)
                                                    @if ($isSold)
                                                        <span class="text-[9px] text-amber-500 ml-1 font-black underline decoration-amber-500/30 decoration-2">LOCKED</span>
                                                    @endif
                                                </label>
                                                <input type="number" step="0.01"
                                                    name="ticket_types[{{ $index }}][price]"
                                                    value="{{ $ticket['price'] ?? 0 }}"
                                                    @if ($isSold || Auth::user()->role === 'organizer') readonly @endif
                                                    class="w-full h-[46px] bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block px-4 transition-all duration-300 @if ($isSold || Auth::user()->role === 'organizer') opacity-60 bg-gray-50 dark:bg-gray-800/50 cursor-not-allowed @endif"
                                                    required>
                                            </div>
                                            <div class="col-span-2 md:col-span-1">
                                                <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5 ml-1">
                                                    Capacity
                                                    @if ($isSold)
                                                        <span class="text-[9px] text-purple-500 ml-1 font-black underline decoration-purple-500/30 decoration-2">MIN: {{ $soldCount }}</span>
                                                    @endif
                                                </label>
                                                <input type="number" name="ticket_types[{{ $index }}][capacity]"
                                                    @input="recalculateAllocation()"
                                                    value="{{ $ticket['capacity'] ?? 0 }}" min="{{ $soldCount }}"
                                                    class="w-full h-[46px] bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block px-4 transition-all duration-300"
                                                    required {{ Auth::user()->role === 'organizer' ? 'disabled' : '' }}>
                                            </div>
                                            <div class="col-span-2 md:col-span-1 flex justify-end pb-1 h-[46px] items-center">
                                                @if(Auth::user()->role === 'admin')
                                                    @if (!$isSold)
                                                        <button type="button" @click="removeTicket($el)"
                                                            class="btn-remove-ticket group/btn w-full h-full flex items-center justify-center gap-2 text-[10px] font-bold text-rose-500 border border-rose-100 dark:border-rose-900/30 bg-rose-50/50 dark:bg-rose-900/10 hover:bg-rose-500 hover:text-white rounded-xl transition-all duration-300 uppercase tracking-widest">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            Remove
                                                        </button>
                                                    @else
                                                        <div class="flex flex-col items-end gap-0.5 pr-2">
                                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Confirmed Sales</span>
                                                            <span class="text-xs font-black text-gray-900 dark:text-white tracking-widest">{{ $soldCount }}</span>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="flex flex-col items-end gap-0.5 pr-2">
                                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Confirmed Sales</span>
                                                        <span class="text-xs font-black text-gray-900 dark:text-white tracking-widest">{{ $soldCount }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div id="emptyTicketMsg"
                                        class="flex flex-col items-center justify-center py-12 text-gray-400 bg-gray-50/30 dark:bg-gray-900/20 border border-dashed border-gray-200 dark:border-gray-700 rounded-3xl">
                                        <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        <p class="text-xs font-bold uppercase tracking-[0.2em]">No ticket types configured</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

                <div class="space-y-8 animate-in fade-in slide-in-from-right-4 duration-500 delay-75">
                    {{-- Quick Stats --}}
                    <x-admin.card header="Quick Stats">
                        <div class="space-y-6">
                            <div class="flex justify-between items-center group">
                                <span class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total Tickets Sold</span>
                                <span class="font-black text-gray-900 dark:text-white text-lg tracking-tight" x-text="formatNum(performanceData.totalTicketsSold)"></span>
                            </div>
                            <div class="flex justify-between items-center group">
                                <span class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Revenue</span>
                                <span class="font-black text-purple-600 dark:text-purple-400 text-lg tracking-tight" x-text="formatRp(performanceData.totalRevenue)"></span>
                            </div>
                            <div class="flex justify-between items-center group border-t border-gray-50 dark:border-gray-700/50 pt-4 mt-4">
                                <span class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Active Orders</span>
                                <span class="font-black text-gray-900 dark:text-white text-lg tracking-tight" x-text="formatNum(performanceData.totalOrdersPending)"></span>
                            </div>
                        </div>
                    </x-admin.card>
                </div>
            </div>
        </form>


        {{-- Analytics Tab --}}
        <div x-show="activeTab === 'analytics'" x-transition.opacity.duration.300ms class="mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Performance Analytics</h3>
                    <p class="text-sm text-gray-500 mt-1">Detailed breakdown of financial and sales metrics</p>
                </div>
                <div class="flex gap-3">
                    <a href="/manage/events/{{ $item->id }}/export/csv" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-purple-600 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors shadow-sm active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export CSV
                    </a>
                    <a href="/manage/events/{{ $item->id }}/export/pdf" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-purple-600 border border-transparent rounded-xl hover:bg-purple-700 dark:hover:bg-purple-500 transition-colors shadow-lg shadow-purple-500/20 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export PDF
                    </a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <x-admin.stat-card 
                    title="Gross Revenue" 
                    value="" 
                    color="emerald"
                    iconPath="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    <x-slot name="valueSlot">
                        <span x-text="formatRp(performanceData.totalRevenue)"></span>
                    </x-slot>
                    From <span class="text-emerald-400 font-semibold" x-text="formatNum(performanceData.totalOrdersCompleted)"></span> orders 
                    &bull; <span class="text-amber-400 font-semibold"><span x-text="formatRp(performanceData.pendingOrdersValue)"></span> pending</span>
                </x-admin.stat-card>

                <x-admin.stat-card 
                    title="Sell-Through Rate" 
                    value="" 
                    color="blue"
                    iconPath="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                    <x-slot name="valueSlot">
                        <span x-text="performanceData.sellThroughRate + '%'"></span>
                    </x-slot>
                    <div class="mb-1 mt-1">
                        <div class="h-1.5 w-full bg-blue-900/30 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all duration-700"
                                :style="`width: ${performanceData.sellThroughRate}%`"></div>
                        </div>
                    </div>
                    <span class="text-blue-400 font-semibold" x-text="formatNum(performanceData.totalTicketsSold)"></span> / <span x-text="formatNum(performanceData.totalCapacity)"></span> sold
                </x-admin.stat-card>

                <x-admin.stat-card 
                    title="Avg. Order Value" 
                    value="" 
                    color="purple"
                    iconPath="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    <x-slot name="valueSlot">
                        <span x-text="formatRp(performanceData.avgOrderValue)"></span>
                    </x-slot>
                    Per completed order &bull; <span class="text-purple-400 font-semibold" x-text="formatNum(performanceData.totalOrdersCompleted)"></span> orders
                </x-admin.stat-card>

                <x-admin.stat-card 
                    title="Conversion Rate" 
                    value="" 
                    color="amber"
                    iconPath="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
                    <x-slot name="valueSlot">
                        <span x-text="performanceData.conversionRate + '%'"></span>
                    </x-slot>
                    <span class="text-emerald-400 font-semibold" x-text="formatNum(performanceData.totalOrdersCompleted)"></span> completed
                    &bull; <span class="text-amber-400 font-semibold" x-text="formatNum(performanceData.totalOrdersPending)"></span> pending
                    &bull; <span class="text-red-400 font-semibold" x-text="formatNum(performanceData.totalOrdersCanceled)"></span> canceled
                </x-admin.stat-card>
            </div>
{{-- end row 1 --}}

            {{-- ═══════════════════════════════════════════════════════════════
    ROW 2 – Sales Velocity + Revenue Trend
    ═══════════════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <x-admin.chart-card 
                    title="Sales Velocity" 
                    subtitle="Tickets sold over time" 
                    totalValue="{{ number_format($performanceData['totalTicketsSold'] ?? 0) }}" 
                    totalLabel="tickets" 
                    canvasId="velocityChart" 
                    color="indigo" />

                <x-admin.chart-card 
                    title="Revenue Trend" 
                    subtitle="Revenue generated over time" 
                    totalValue="Rp {{ number_format($performanceData['totalRevenue'] ?? 0, 0, ',', '.') }}" 
                    canvasId="revenueChart" 
                    color="emerald" />
            </div>

            {{-- ═══════════════════════════════════════════════════════════════
    ROW 2.5 – Attendance Mix
    ═══════════════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                {{-- Attendance Summary --}}
                <x-admin.card header="Attendance Summary">
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative w-36 h-36">
                            <canvas id="attendanceChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-2xl font-extrabold text-gray-800 dark:text-white" x-text="performanceData.attendanceRate + '%'"></span>
                                <span class="text-[10px] text-gray-400 uppercase tracking-wider">Check-in</span>
                            </div>
                        </div>
                        <div class="w-full space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 inline-block"></span>
                                    <span class="text-gray-500 dark:text-gray-400">Checked In</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-white" x-text="formatNum(performanceData.totalTicketsScanned)"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-gray-600 inline-block"></span>
                                    <span class="text-gray-500 dark:text-gray-400">No-show</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-white" x-text="formatNum(performanceData.totalTicketsSold - performanceData.totalTicketsScanned)"></span>
                            </div>
                        </div>
                    </div>
                </x-admin.card>

                {{-- Ticket Tier Performance --}}
                <div class="lg:col-span-2">
                    <x-admin.card>
                        <x-slot name="header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Ticket Tier Performance</h3>
                                    <p class="text-xs text-gray-400 mt-0.5">Breakdown by ticket type</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 6h4m-4 12h4M3 6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6z" />
                                </svg>
                            </div>
                        </x-slot>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                                        <th class="pb-3 px-2">Tier</th>
                                        <th class="pb-3 px-2 text-right">Price</th>
                                        <th class="pb-3 px-2 text-right">Capacity</th>
                                        <th class="pb-3 px-2 text-right">Sold</th>
                                        <th class="pb-3 px-2 text-right">Revenue</th>
                                        <th class="pb-3 px-2">Fill Rate</th>
                                    </tr>
                                </thead>
                                <tbody id="tier-table-body" class="divide-y divide-gray-50 dark:divide-gray-700/50">
                                    <template x-for="tier in performanceData.tierBreakdown" :key="tier.name">
                                        <tr class="transition-colors duration-150">
                                            <td class="py-3.5 px-2">
                                                <span class="inline-flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                                                    <span class="font-medium text-gray-800 dark:text-white" x-text="tier.name"></span>
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-2 text-right text-gray-600 dark:text-gray-300" x-text="formatRp(tier.price)">
                                            </td>
                                            <td class="py-3.5 px-2 text-right text-gray-600 dark:text-gray-300" x-text="formatNum(tier.capacity)">
                                            </td>
                                            <td class="py-3.5 px-2 text-right font-semibold text-gray-800 dark:text-white" x-text="formatNum(tier.sold)">
                                            </td>
                                            <td class="py-3.5 px-2 text-right text-emerald-500 font-semibold" x-text="formatRp(tier.revenue)">
                                            </td>
                                            <td class="py-3.5 px-2 min-w-[120px]">
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                        <div class="h-full rounded-full transition-all duration-700"
                                                            :class="tier.fill >= 90 ? 'bg-emerald-400' : (tier.fill >= 60 ? 'bg-blue-400' : 'bg-amber-400')"
                                                            :style="`width: ${tier.fill}%`"></div>
                                                    </div>
                                                    <span class="text-xs font-medium"
                                                          :class="tier.fill >= 90 ? 'text-emerald-400' : (tier.fill >= 60 ? 'text-blue-400' : 'text-amber-400')"
                                                          x-text="tier.fill + '%'">
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="!performanceData.tierBreakdown || performanceData.tierBreakdown.length === 0">
                                        <td colspan="6" class="py-12 text-center text-sm text-gray-400 italic">No ticket tiers configured for this event.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </x-admin.card>
                </div>
            </div>{{-- end row 2.5 --}}

        </div>{{-- end analytics tab --}}

        {{-- ═══════════════════════════════════════════════════════════════
    Chart.js + AJAX Logic
    ═══════════════════════════════════════════════════════════════ --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>


        {{-- Orders Tab --}}
        <div x-cloak x-show="activeTab === 'orders'" x-transition.opacity.duration.300ms class="mt-6">
            <x-admin.card>
                <x-slot name="header">
                    <div class="flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Orders</h3>
                        <p class="text-sm text-gray-500 mt-1">Complete history of transactions for this event.</p>
                    </div>
                </x-slot>

                {{-- Filters Section --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                    <form action="{{ url()->current() }}#orders" method="GET"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                        {{-- Status --}}
                        <x-admin.select label="Status" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </x-admin.select>

                        {{-- Ticket Type --}}
                        <x-admin.select label="Ticket Type" name="ticket_type_id">
                            <option value="">All Types</option>
                            @foreach ($item->eventTicketTypes as $tt)
                                <option value="{{ $tt->id }}"
                                    {{ request('ticket_type_id') == $tt->id ? 'selected' : '' }}>
                                    {{ $tt->ticketType->name }}
                                </option>
                            @endforeach
                        </x-admin.select>

                        {{-- Payment Proof --}}
                        <x-admin.select label="Payment Proof" name="has_payment_proof">
                            <option value="">All</option>
                            <option value="yes" {{ request('has_payment_proof') == 'yes' ? 'selected' : '' }}>Has Proof</option>
                            <option value="no" {{ request('has_payment_proof') == 'no' ? 'selected' : '' }}>No Proof</option>
                        </x-admin.select>

                        {{-- Sort --}}
                        <x-admin.select label="Sort By" name="sort">
                            <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Highest Amount</option>
                            <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Lowest Amount</option>
                        </x-admin.select>

                        {{-- Apply Button --}}
                        <div class="lg:col-span-2 flex items-end gap-2">
                            <x-admin.button type="submit" class="flex-1 h-[42px]">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter
                            </x-admin.button>
                            @if (request()->anyFilled(['status', 'ticket_type_id', 'has_payment_proof', 'sort']))
                                <x-admin.button variant="secondary" :href="url()->current() . '#orders'" class="h-[42px] px-4">
                                    Reset
                                </x-admin.button>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Table Section --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead
                            class="text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="py-3.5 px-6">Order ID</th>
                                <th class="py-3.5 px-6">Customer</th>
                                <th class="py-3.5 px-6">Tickets</th>
                                <th class="py-3.5 px-6">Date</th>
                                <th class="py-3.5 px-6 text-right">Amount</th>
                                <th class="py-3.5 px-6 text-center">Status</th>
                                <th class="py-3.5 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50 text-gray-900 dark:text-white">
                            @forelse ($orders as $order)
                                <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150 cursor-pointer"
                                    @click="window.location.href='/manage/orders/{{ $order->id }}/approve'">
                                    <td class="py-4 px-6">
                                        <span class="font-bold text-indigo-600 dark:text-indigo-400">#{{ $order->id }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <span class="font-semibold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $order->user->name }}</span>
                                            <span class="text-[10px] text-gray-400">{{ $order->user->email }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($order->orderDetails->groupBy('event_ticket_type_id') as $typeId => $details)
                                                @php $first = $details->first(); @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-100 dark:bg-gray-700/50 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                                    {{ $details->count() }}x {{ $first->eventTicketType->ticketType->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500 dark:text-gray-400 text-xs text-nowrap">
                                        {{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td class="py-4 px-6 text-right font-bold text-gray-900 dark:text-white">Rp
                                        {{ number_format($order->amount, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <x-admin.badge :type="$order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger')">
                                            {{ $order->status }}
                                        </x-admin.badge>
                                    </td>
                                    <td class="py-4 px-6 text-center" @click.stop>
                                        <a href="/manage/orders/{{ $order->id }}/approve"
                                            class="inline-flex items-center justify-center p-2 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30 rounded-lg transition-all"
                                            title="View Approval Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-gray-400 italic">
                                        No orders matching the criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($orders->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                        {{ $orders->fragment('orders')->links() }}
                    </div>
                @endif
            </x-admin.card>
        </div>



        {{-- Template for new ticket rows --}}
        <template id="ticketTemplate">
            <div class="ticket-row border border-gray-100 dark:border-gray-700/50 rounded-2xl p-6 bg-gray-50/30 dark:bg-gray-900/20 relative group overflow-hidden animate-in zoom-in-95 duration-300">
                <input type="hidden" name="ticket_types[__INDEX__][id]" value="">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end relative z-10">
                    <x-admin.select label="Type" name="ticket_types[__INDEX__][ticket_type_id]" required containerClass="col-span-2 md:col-span-1">
                        <option value="">Select</option>
                        @foreach ($ticketTypes as $tt)
                            <option value="{{ $tt->id }}">{{ $tt->name }}</option>
                        @endforeach
                    </x-admin.select>
                    
                    <x-admin.input type="number" step="0.01" label="Price (Rp)" name="ticket_types[__INDEX__][price]" value="0" required containerClass="col-span-2 md:col-span-1" />
                    
                    <x-admin.input type="number" label="Capacity" name="ticket_types[__INDEX__][capacity]" value="0" min="0" @input="recalculateAllocation()" required containerClass="col-span-2 md:col-span-1" />
                    
                    <div class="col-span-2 md:col-span-1 flex justify-end pb-1 h-[46px] items-center">
                        <x-admin.button variant="danger" @click="removeTicket($el)" class="w-full h-full text-[10px]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Remove
                        </x-admin.button>
                    </div>
                </div>
            </div>
        </template>

        <script>
            // Note: Charts and other logic moved to non-Alpine script block below
        </script>
        <script>
            (function() {
                const T = window.ChartThemes;

                // Format helpers
                const fmtRp = v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v);
                const fmtNum = v => new Intl.NumberFormat('id-ID').format(v);

                function buildVelocityChart(labels, data) {
                    const ctx = document.getElementById('velocityChart');
                    if (!ctx) return;
                    const cctx = ctx.getContext('2d');

                    window.velocityChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Tickets Sold',
                                data: data,
                                fill: true,
                                backgroundColor: T.getGradient(cctx, 'indigo'),
                                borderColor: 'rgba(99,102,241,0.9)',
                                borderWidth: 2,
                                pointBackgroundColor: 'rgba(99,102,241,1)',
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                tension: 0.4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    ...T.getTooltipStyle(),
                                    callbacks: {
                                        label: c => fmtNum(c.raw) + ' tickets'
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { color: T.getGridColor(), display: false },
                                    ticks: { color: T.getLabelColor(), font: { size: 10 } }
                                },
                                y: {
                                    grid: { color: T.getGridColor() },
                                    ticks: { color: T.getLabelColor(), font: { size: 10 } },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function buildRevenueChart(labels, data) {
                    const ctx = document.getElementById('revenueChart');
                    if (!ctx) return;
                    const cctx = ctx.getContext('2d');

                    window.revenueChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Revenue',
                                data: data,
                                fill: true,
                                backgroundColor: T.getGradient(cctx, 'emerald'),
                                borderColor: 'rgba(16, 185, 129, 0.9)',
                                borderWidth: 2,
                                pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                tension: 0.4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    ...T.getTooltipStyle(),
                                    callbacks: {
                                        label: c => fmtRp(c.raw)
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { color: T.getGridColor(), display: false },
                                    ticks: { color: T.getLabelColor(), font: { size: 10 } }
                                },
                                y: {
                                    grid: { color: T.getGridColor() },
                                    ticks: {
                                        color: T.getLabelColor(),
                                        font: { size: 10 },
                                        callback: v => window.innerWidth > 768 ? fmtRp(v) : v
                                    },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function buildAttendanceChart(scanned, noshow) {
                    const ctx = document.getElementById('attendanceChart');
                    if (!ctx) return;
                    window.attendanceChart = new Chart(ctx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Checked In', 'No-show'],
                            datasets: [{
                                data: [scanned, noshow || 0.001],
                                backgroundColor: [
                                    'rgba(99,102,241,0.85)', 
                                    T.isDark() ? 'rgba(75,85,99,0.4)' : 'rgba(229,231,235,0.8)'
                                ],
                                borderColor: T.isDark() ? '#1f2937' : '#ffffff',
                                borderWidth: 3,
                                hoverOffset: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '72%',
                            plugins: {
                                legend: { display: false },
                                tooltip: T.getTooltipStyle()
                            }
                        }
                    });
                }

                // We inject the actual labels array directly from Blade
                const chartLabels = {!! json_encode($performanceData['chartLabels'] ?? []) !!};
                const chartVelocity = {!! json_encode($performanceData['chartVelocity'] ?? []) !!};
                const chartRevenueVelocity = {!! json_encode($performanceData['chartRevenueVelocity'] ?? []) !!};
                const scannedCount = {{ $performanceData['totalTicketsScanned'] ?? 0 }};
                const noshowCount =
                    {{ ($performanceData['totalTicketsSold'] ?? 0) - ($performanceData['totalTicketsScanned'] ?? 0) }};

                // Needs to be rebuilt when switching tabs since charts use width/height and might 0 out when display: none
                let chartRendered = false;

                function initCharts() {
                    if (chartRendered) return;
                    buildVelocityChart(chartLabels, chartVelocity);
                    buildRevenueChart(chartLabels, chartRevenueVelocity);
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
