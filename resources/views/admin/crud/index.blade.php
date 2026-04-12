@extends('layouts.admin.default')

@section('content')
    <div class="flex flex-col items-center justify-between mb-6 gap-4">
        <h1 class="text-2xl text-start w-full font-bold text-gray-900 dark:text-white">{{ $title }}</h1>

        <div class="flex items-center w-full gap-4 ml-auto">
            <div
                class="w-full mb-6 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-end gap-4">
                    @if (isset($filters))
                        @foreach ($filters as $filterKey => $filterParams)
                            @if (isset($filterParams['type']) && $filterParams['type'] === 'date')
                                <div class="flex-grow min-w-[150px]">
                                    <label
                                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ $filterParams['label'] ?? ucfirst($filterKey) }}</label>
                                    <input type="date" name="{{ $filterKey }}" value="{{ request($filterKey) }}"
                                        class="h-[36px] w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-colors duration-300">
                                </div>
                            @else
                                <div class="flex-grow min-w-[150px] relative">
                                    <label
                                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ $filterParams['label'] ?? ucfirst($filterKey) }}</label>
                                    <select name="{{ $filterKey }}"
                                        class="h-[36px] appearance-none w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block pl-3 pr-10 py-2 transition-colors duration-300 cursor-pointer hover:bg-gray-50/80 dark:hover:bg-gray-700/80">
                                        <option value="">All {{ $filterParams['label'] ?? ucfirst($filterKey) }}
                                        </option>
                                        @foreach ($filterParams['options'] as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ request($filterKey) !== null && request($filterKey) !== '' && request($filterKey) == (string) $value ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 top-5 flex items-center pr-3 text-gray-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    <div class="flex-grow min-w-[200px] relative">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                            class="h-[36px] bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full pl-10 py-2 transition-colors duration-300">
                        <div
                            class="absolute inset-y-0 left-0 top-5 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Hidden inputs to preserve sort state across filter submissions -->
                    @if (request()->has('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @if (request()->has('direction'))
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                    @endif

                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="h-[36px] px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm whitespace-nowrap">
                            Apply Filters
                        </button>
                        @if (request()->hasAny(array_merge(['search'], array_keys($filters ?? []))))
                            <a href="{{ url()->current() }}"
                                class="h-[36px] flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition duration-200 shadow-sm whitespace-nowrap"
                                title="Clear Filters">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>


        </div>
        @if ($createUrl)
            <div class="flex items-end justify-end w-full">
                <a href="{{ $createUrl }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] transition-all duration-300 shadow-md hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] whitespace-nowrap ml-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create {{ Str::singular($title) }}
                </a>
            </div>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-[#d8b4fe]">
                <tr>
                    @foreach ($columns as $col)
                        <th class="px-6 py-4">
                            @if (isset($col['sortable']) && $col['sortable'] === false)
                                {{ $col['label'] }}
                            @else
                                @php
                                    $isSorted = request('sort') === $col['key'];
                                    $direction = $isSorted && request('direction') === 'asc' ? 'desc' : 'asc';
                                    // Merge existing query params but replace sort and direction
                                    $sortUrl = request()->fullUrlWithQuery([
                                        'sort' => $col['key'],
                                        'direction' => $direction,
                                    ]);
                                @endphp
                                <a href="{{ $sortUrl }}"
                                    class="flex items-center gap-1 hover:text-purple-600 dark:hover:text-purple-400 group">
                                    <span class="{{ $isSorted ? 'text-purple-600 dark:text-purple-400 font-bold' : '' }}">
                                        {{ $col['label'] }}
                                    </span>
                                    @if ($isSorted)
                                        @if (request('direction') === 'asc')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                        </svg>
                                    @endif
                                </a>
                            @endif
                        </th>
                    @endforeach
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($rows as $row)
                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 cursor-pointer"
                        onclick="window.location='{{ $editUrl }}/{{ data_get($row, 'id') }}/edit'">
                        @foreach ($columns as $col)
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-200">
                                {{ data_get($row, $col['key']) }}
                            </td>
                        @endforeach
                        <td class="px-6 py-4" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Edit -->
                                <a href="{{ $editUrl }}/{{ data_get($row, 'id') }}/edit"
                                    class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] hover:shadow-[0_0_10px_rgba(168,85,247,0.4)] transition-all duration-300 border border-transparent hover:border-white/20"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                @if ($canDelete ?? true)
                                    <!-- Delete -->
                                    <form action="{{ $editUrl }}/{{ data_get($row, 'id') }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-300 hover:text-white rounded-lg bg-red-600/80 hover:bg-red-600 hover:shadow-[0_0_10px_rgba(239,68,68,0.4)] transition-all duration-300 border border-transparent hover:border-white/20"
                                            title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="{{ count($columns) + 2 }}"
                            class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                            <p class="text-lg font-medium">No {{ strtolower($title) }} found</p>
                            <p class="text-sm mt-1">Get started by creating a new {{ strtolower(Str::singular($title)) }}.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (method_exists($rows, 'hasPages') && $rows->hasPages())
        <div class="mt-6">
            {{ $rows->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
