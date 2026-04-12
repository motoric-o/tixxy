@extends('layouts.admin.default')

@section('content')
<div class="mb-6 flex flex-row items-center gap-5">
    <a href="/manage/events/{{ $event->id }}/edit"
        class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] border border-transparent hover:border-white/20"
        title="Back to Event">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
    </a>
    <div class="flex flex-col">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Orders for Event: {{ $event->title }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Manage all orders related to this event.
        </p>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
        
        <!-- Status Filter -->
        <div class="lg:col-span-1">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
            <select name="status" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>

        <!-- Ticket Type Filter -->
        <div class="lg:col-span-1">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Ticket Type</label>
            <select name="ticket_type_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Types</option>
                @foreach ($event->eventTicketTypes as $eventTicketType)
                    <option value="{{ $eventTicketType->ticketType->id }}" {{ request('ticket_type_id') == $eventTicketType->ticketType->id ? 'selected' : '' }}>
                        {{ $eventTicketType->ticketType->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Payment Proof Filter -->
        <div class="lg:col-span-1">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Payment Proof</label>
            <select name="has_payment_proof" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Any</option>
                <option value="1" {{ request('has_payment_proof') == '1' ? 'selected' : '' }}>Uploaded</option>
                <option value="0" {{ request('has_payment_proof') == '0' ? 'selected' : '' }}>Not Uploaded</option>
            </select>
        </div>

        <!-- Date Range From -->
        <div class="lg:col-span-1">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <!-- Date Range To -->
        <div class="lg:col-span-1">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <!-- Sort By -->
        <div class="lg:col-span-1 flex items-end gap-2">
            <div class="flex-grow">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Sort By</label>
                <select name="sort" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Amount (High to Low)</option>
                    <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Amount (Low to High)</option>
                    <option value="customer_asc" {{ request('sort') == 'customer_asc' ? 'selected' : '' }}>Customer (A-Z)</option>
                    <option value="customer_desc" {{ request('sort') == 'customer_desc' ? 'selected' : '' }}>Customer (Z-A)</option>
                </select>
            </div>
        </div>
        
        <div class="lg:col-span-6 flex justify-end gap-2 mt-2">
            <a href="/manage/events/{{ $event->id }}/orders" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition duration-200 shadow-sm">
                Clear Filters
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-[#d8b4fe]">
                <tr>
                    <th class="px-6 py-4">Order ID</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Items</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-200">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 cursor-pointer" onclick="window.location='/manage/orders/{{ $order->id }}/approve'">
                        <td class="px-6 py-4 font-medium sm:whitespace-nowrap">
                            #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->user->name }}
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <ul class="list-disc list-inside text-xs">
                                @php
                                    $groupedDetails = $order->orderDetails->groupBy('eventTicketType.ticketType.name');
                                @endphp
                                @foreach($groupedDetails as $typeName => $details)
                                    <li>{{ $details->count() }}x {{ $typeName ?? 'Unknown' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4 font-semibold">
                            Rp {{ number_format($order->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($order->status == 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Completed
                                </span>
                            @elseif ($order->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Canceled
                                </span>
                            @endif
                            @if($order->payment_proof)
                                <svg class="inline w-4 h-4 text-blue-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Payment Proof Uploaded"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p class="text-lg font-medium">No orders found</p>
                            <p class="text-sm mt-1">Try adjusting your filters or wait for new orders.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endif

@endsection
