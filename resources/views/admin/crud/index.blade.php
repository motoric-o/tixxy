@extends('layouts.admin.default')

@section('content')
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">
                {{ $subtitle ?? 'Manage your ' . strtolower($title) . ' records efficiently.' }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if (isset($compareUrl))
                <a href="{{ $compareUrl }}"
                    class="inline-flex items-center gap-2.5 px-6 py-3 text-sm font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-500/30 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/40 hover:border-indigo-200 dark:hover:border-indigo-500/50 transition-all duration-300 shadow-sm hover:shadow-indigo-500/10 active:scale-95 whitespace-nowrap group/compare">
                    <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 transition-transform duration-300 group-hover/compare:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span>Compare {{ $title }}</span>
                </a>
            @endif

            @if ($createUrl && Auth::user()->role === 'admin')
                <a href="{{ $createUrl }}"
                    class="inline-flex items-center gap-2.5 px-6 py-3 text-sm font-bold text-white rounded-xl bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 bg-[length:200%_auto] hover:bg-[position:right_center] shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 transition-all duration-500 hover:-translate-y-0.5 active:scale-95">
                    <svg class="w-5 h-5 transition-transform duration-500 group-hover:rotate-12" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Create New {{ Str::singular($title) }}</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Premium Filter & Search Bar --}}
    <div
        class="sticky top-4 z-20 bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl p-5 rounded-2xl border border-white/20 dark:border-gray-800/50 shadow-xl shadow-purple-500/5 ring-1 ring-black/5 dark:ring-white/5 mb-8">
        <form action="{{ url()->current() }}" method="GET"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-5">

            {{-- Search Field --}}
            <div class="sm:col-span-2 space-y-1.5">
                <label class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">
                    Quick Search
                </label>
                <div class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by details..."
                        class="w-full h-[44px] bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block pl-11 transition-all duration-300">
                    <div
                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-500 transition-colors">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Dynamic Filters --}}
            @if (isset($filters))
                @foreach ($filters as $filterKey => $filterParams)
                    @php
                        $isDate = isset($filterParams['type']) && $filterParams['type'] === 'date';
                    @endphp
                    <div class="space-y-1.5">
                        <label
                            class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">
                            {{ $filterParams['label'] ?? ucfirst($filterKey) }}
                        </label>
                        <div class="relative">
                            @if ($isDate)
                                <input type="date" name="{{ $filterKey }}" value="{{ request($filterKey) }}"
                                    class="w-full h-[44px] bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block px-4 transition-all duration-300">
                            @else
                                <select name="{{ $filterKey }}"
                                    class="appearance-none w-full h-[44px] bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block pl-4 pr-10 transition-all duration-300 cursor-pointer">
                                    <option value="">All {{ $filterParams['label'] ?? ucfirst($filterKey) }}</option>
                                    @foreach ($filterParams['options'] as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ request($filterKey) !== null && request($filterKey) !== '' && request($filterKey) == (string) $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Sort State Preservation --}}
            @if (request()->has('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            @if (request()->has('direction'))
                <input type="hidden" name="direction" value="{{ request('direction') }}">
            @endif

            {{-- Filter Actions --}}
            <div class="flex items-end gap-2 sm:col-span-2 xl:col-span-1">
                <button type="submit"
                    class="flex-1 h-[44px] bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-purple-500/20 flex items-center justify-center gap-2 group">
                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Apply
                </button>
                @if (request()->anyFilled(array_merge(['search'], array_keys($filters ?? []))))
                    <a href="{{ url()->current() }}"
                        class="h-[44px] px-4 bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-xl flex items-center justify-center transition-all border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Premium Table Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        @foreach ($columns as $col)
                            <th class="px-6 py-4">
                                @if (isset($col['sortable']) && $col['sortable'] === false)
                                    {{ $col['label'] }}
                                @else
                                    @php
                                        $isSorted = request('sort') === $col['key'];
                                        $direction = $isSorted && request('direction') === 'asc' ? 'desc' : 'asc';
                                        $sortUrl = request()->fullUrlWithQuery([
                                            'sort' => $col['key'],
                                            'direction' => $direction,
                                        ]);
                                    @endphp
                                    <a href="{{ $sortUrl }}"
                                        class="flex items-center gap-2 hover:text-purple-600 dark:hover:text-purple-400 transition-colors group">
                                        <span class="{{ $isSorted ? 'text-purple-600 dark:text-purple-400 font-bold' : '' }}">
                                            {{ $col['label'] }}
                                        </span>
                                        <div class="flex flex-col gap-0.5">
                                            @if ($isSorted)
                                                @if (request('direction') === 'asc')
                                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/></svg>
                                                @else
                                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/></svg>
                                                @endif
                                            @else
                                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 text-gray-400 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </a>
                                @endif
                            </th>
                        @endforeach
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($rows as $row)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors duration-150 cursor-pointer"
                            onclick="window.location='{{ $editUrl }}/{{ data_get($row, 'id') }}/edit'">
                            @foreach ($columns as $col)
                                <td class="px-6 py-4 text-gray-900 dark:text-gray-200">
                                    {{ data_get($row, $col['key']) }}
                                </td>
                            @endforeach
                            <td class="px-6 py-4" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ $editUrl }}/{{ data_get($row, 'id') }}/edit"
                                        class="p-2 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30 rounded-lg transition-all"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    @if (($canDelete ?? true) && Auth::user()->role === 'admin')
                                        <form action="{{ $editUrl }}/{{ data_get($row, 'id') }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-500 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-lg transition-all"
                                                title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="py-16 text-center text-gray-400 dark:text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 opacity-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-xl font-bold tracking-tight">No {{ strtolower($title) }} records found</p>
                                    <p class="text-sm mt-1">Try adjusting your filters or search terms.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (method_exists($rows, 'hasPages') && $rows->hasPages())
        <div class="mt-8">
            {{ $rows->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
