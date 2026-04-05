@extends('layouts.admin.default')

@section('content')
{{-- Page Header --}}
<div class="mb-6 flex flex-row justify-between items-center">
    <div class="flex flex-row items-center gap-5">
        <a href="{{ $backUrl }}" class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] border border-transparent hover:border-white/20" title="Back">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="flex flex-col">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $title }}</h2>
            @if(isset($subtitle))
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 {{ !empty($detailFields ?? []) ? 'lg:grid-cols-3' : '' }} gap-6">

    {{-- Details Panel (Left / Top) --}}
    @if(!empty($detailFields ?? []))
    <div class="lg:col-span-1 space-y-6">
        {{-- Summary Card --}}
        @php $standardFields = array_filter($detailFields, fn($f) => !isset($f['type'])); @endphp
        @if(count($standardFields) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Details</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($standardFields as $detail)
                    <div class="flex justify-between items-center group">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $detail['label'] }}</span>
                        @if(isset($detail['url']))
                            <a href="{{ $detail['url'] }}" class="text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 hover:underline transition-colors duration-300">
                                {{ $detail['value'] }}
                            </a>
                        @else
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $detail['value'] }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Table-type detail cards (e.g. Order Items) --}}
        @foreach($detailFields as $detail)
            @if(($detail['type'] ?? null) === 'table' && !empty($detail['value']))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $detail['label'] }}</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    @foreach(array_keys($detail['value'][0]) as $header)
                                    <th class="py-3 px-4 font-medium">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="text-gray-900 dark:text-white">
                                @foreach($detail['value'] as $row)
                                <tr class="border-b border-gray-200 dark:border-gray-700 last:border-0">
                                    @foreach($row as $cell)
                                    <td class="py-3 px-4">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Badge-type detail cards (e.g. Tickets) --}}
            @if(($detail['type'] ?? null) === 'badges' && !empty($detail['value']))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $detail['label'] }}</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach($detail['value'] as $badge)
                        @if(isset($badge['url']))
                        <a href="{{ $badge['url'] }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium transition-all duration-300 hover:scale-105 active:scale-95
                            {{ ($badge['color'] ?? 'gray') === 'green'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50'
                                : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ ($badge['color'] ?? 'gray') === 'green' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                            <span class="font-mono text-[11px]">{{ $badge['label'] }}</span>
                            <span class="opacity-70">{{ $badge['badge'] }}</span>
                        </a>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                            {{ ($badge['color'] ?? 'gray') === 'green'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ ($badge['color'] ?? 'gray') === 'green' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                            <span class="font-mono text-[11px]">{{ $badge['label'] }}</span>
                            <span class="opacity-70">{{ $badge['badge'] }}</span>
                        </span>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    {{-- Form Card (Right / Full) --}}
    <div class="{{ !empty($detailFields ?? []) ? 'lg:col-span-2' : '' }}">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ isset($item) && $item ? 'Edit Information' : 'Create New' }}
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ $action }}" method="POST">
                    @csrf
                    @if(isset($method))
                        @method($method)
                    @endif

                    <div class="space-y-5">
                        @foreach($fields as $field)
                            <div>
                                <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ $field['label'] }}
                                </label>

                                @if(($field['type'] ?? 'text') === 'textarea')
                                    <textarea
                                        name="{{ $field['name'] }}"
                                        id="{{ $field['name'] }}"
                                        rows="4"
                                        placeholder="{{ $field['placeholder'] ?? '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300"
                                        {{ ($field['required'] ?? false) ? 'required' : '' }}
                                    >{{ old($field['name'], data_get($item ?? null, $field['name'])) }}</textarea>

                                @elseif(($field['type'] ?? 'text') === 'select')
                                    <select
                                        name="{{ $field['name'] }}"
                                        id="{{ $field['name'] }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300"
                                        {{ ($field['required'] ?? false) ? 'required' : '' }}
                                    >
                                        <option value="">Select {{ $field['label'] }}</option>
                                        @foreach($field['options'] ?? [] as $value => $optionLabel)
                                            <option value="{{ $value }}" {{ old($field['name'], data_get($item ?? null, $field['name'])) == $value ? 'selected' : '' }}>
                                                {{ $optionLabel }}
                                            </option>
                                        @endforeach
                                    </select>

                                @else
                                    <input
                                        type="{{ $field['type'] ?? 'text' }}"
                                        name="{{ $field['name'] }}"
                                        id="{{ $field['name'] }}"
                                        placeholder="{{ $field['placeholder'] ?? '' }}"
                                        value="{{ old($field['name'], data_get($item ?? null, $field['name'])) }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300"
                                        {{ ($field['required'] ?? false) ? 'required' : '' }}
                                    >
                                @endif

                                @error($field['name'])
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] transition-all duration-300 shadow-md hover:shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save
                        </button>
                        <a href="{{ $backUrl }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-300">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
