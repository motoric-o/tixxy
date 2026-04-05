@extends('layouts.organizer')

@section('content')
<div class="flex items-center justify-between mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
    
    <div class="flex items-center gap-4 ml-auto">
        <!-- Search & Filters -->
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3">
            @if(isset($filters))
                @foreach($filters as $filterKey => $filterParams)
                    <div class="relative">
                        <select name="{{ $filterKey }}" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-200 text-sm rounded-lg focus:ring-[#8e2de2] focus:border-[#8e2de2] block pl-3 pr-10 py-2 transition-colors duration-300 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <option value="">All {{ $filterParams['label'] ?? ucfirst($filterKey) }}</option>
                            @foreach($filterParams['options'] as $value => $label)
                                <option value="{{ $value }}" {{ request($filterKey) !== null && request($filterKey) !== '' && request($filterKey) == (string)$value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="relative w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-200 text-sm rounded-lg focus:ring-[#8e2de2] focus:border-[#8e2de2] block w-full pl-10 p-2 transition-colors duration-300">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            
            @if(request()->hasAny(array_merge(['search'], array_keys($filters ?? []))))
                <a href="{{ url()->current() }}" class="p-2 text-gray-500 hover:text-red-500 transition-colors duration-300" title="Clear Filters">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            @endif
        </form>

        <a href="{{ $createUrl }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] transition-all duration-300 shadow-md hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] whitespace-nowrap ml-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create {{ Str::singular($title) }}
        </a>
    </div>
</div>

<!-- Table -->
<div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
    <table class="w-full text-sm text-left">
        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-[#d8b4fe]">
            <tr>
                <th class="px-6 py-4">#</th>
                @foreach($columns as $col)
                    <th class="px-6 py-4">{{ $col['label'] }}</th>
                @endforeach
                <th class="px-6 py-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($rows as $index => $row)
                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 cursor-pointer" onclick="window.location='{{ $editUrl }}/{{ data_get($row, 'id') }}/edit'">
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                    @foreach($columns as $col)
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-200">
                            {{ data_get($row, $col['key']) }}
                        </td>
                    @endforeach
                    <td class="px-6 py-4" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Edit -->
                            <a href="{{ $editUrl }}/{{ data_get($row, 'id') }}/edit" class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] hover:shadow-[0_0_10px_rgba(168,85,247,0.4)] transition-all duration-300 border border-transparent hover:border-white/20" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <!-- Delete -->
                            <form action="{{ $editUrl }}/{{ data_get($row, 'id') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-300 hover:text-white rounded-lg bg-red-600/80 hover:bg-red-600 hover:shadow-[0_0_10px_rgba(239,68,68,0.4)] transition-all duration-300 border border-transparent hover:border-white/20" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="bg-white dark:bg-gray-800">
                    <td colspan="{{ count($columns) + 2 }}" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p class="text-lg font-medium">No {{ strtolower($title) }} found</p>
                        <p class="text-sm mt-1">Get started by creating a new {{ strtolower(Str::singular($title)) }}.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($rows, 'hasPages') && $rows->hasPages())
    <div class="mt-6">
        {{ $rows->appends(request()->query())->links() }}
    </div>
@endif
@endsection
