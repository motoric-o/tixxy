@extends('layouts.admin.default')

@section('content')

    <form action="/manage/events/{{ $item->id }}" method="POST" id="eventForm">
        @csrf
        @method('PUT')

        <div class="mb-6 flex flex-row justify-between items-center">
            <div class="flex flex-row items-center gap-5">
                <a href="{{ $backUrl }}"
                    class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] border border-transparent hover:border-white/20"
                    title="Back">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div class="flex flex-col">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Event: {{ $item->title }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Overview as of {{ now()->format('l, d F Y') }}
                    </p>
                </div>
            </div>
            <div class="flex flex-col items-end gap-1">
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Assigned
                    Organizer</label>
                <select name="user_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300 min-w-[180px]">
                    <option value="">Unassigned</option>
                    @foreach ($organizers as $orgId => $orgName)
                        <option value="{{ $orgId }}" @if (old('user_id', $item->user_id) == $orgId) selected @endif>
                            {{ $orgName }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                {{-- Event Details --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div
                        class="flex flex-row items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Event Details</h3>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] shadow-md hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Save Changes
                        </button>
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
                                        <option value="{{ $id }}"
                                            @if (old('category_id', $item->category_id) == $id) selected @endif>{{ $name }}</option>
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
                                        <option value="{{ $value }}"
                                            @if (old('status', $item->status) == $value) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quota</label>
                                <input type="number" name="quota" id="eventQuota"
                                    value="{{ old('quota', $item->quota) }}"
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
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white rounded-lg bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] shadow-sm transition-all duration-300">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Tickets
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
                                                    <option value="{{ $tt->id }}"
                                                        @if (($ticket['ticket_type_id'] ?? '') == $tt->id) selected @endif>
                                                        {{ $tt->name }}</option>
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
                                            <input type="number" step="0.01"
                                                name="ticket_types[{{ $index }}][price]"
                                                value="{{ $ticket['price'] ?? 0 }}"
                                                @if ($isSold) readonly @endif
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
                                <span class="font-semibold text-gray-900 dark:text-white">123</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Revenue</span>
                                <span class="font-semibold text-gray-900 dark:text-white">$6,027.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Active Orders</span>
                                <span class="font-semibold text-gray-900 dark:text-white">15</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Orders --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h3>
                        <a href="{{ route('manage.orders.event', $item->id) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                            View All &rarr;
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead
                                    class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <th class="py-3 px-4 font-medium">Order ID</th>
                                        <th class="py-3 px-4 font-medium">Customer</th>
                                        <th class="py-3 px-4 font-medium">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-900 dark:text-white">
                                    @foreach ($item->orders->take(5) as $order)
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="py-4 px-4">{{ $order->id }}</td>
                                            <td class="py-4 px-4">{{ $order->user->name }}</td>
                                            <td class="py-4 px-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
        document.addEventListener('DOMContentLoaded', function() {
            let ticketIndex =
                {{ collect(old('ticket_types', $eventTicketTypesData))->count() ? max(array_keys(old('ticket_types', is_array($eventTicketTypesData) ? $eventTicketTypesData : $eventTicketTypesData->toArray()))) + 1 : 0 }};
            const container = document.getElementById('ticketContainer');
            const template = document.getElementById('ticketTemplate').innerHTML;

            document.getElementById('addTicketBtn').addEventListener('click', function() {
                const emptyMsg = document.getElementById('emptyTicketMsg');
                if (emptyMsg) emptyMsg.style.display = 'none';

                const newRowHtml = template.replace(/__INDEX__/g, ticketIndex++);
                container.insertAdjacentHTML('beforeend', newRowHtml);
            });

            container.addEventListener('click', function(e) {
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

@endsection
