@extends('layouts.admin.default')

@section('content')
<!-- Page Header -->
<div class="flex items-center gap-4 mb-6">
    <a href="{{ $backUrl }}" class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] border border-transparent hover:border-white/20" title="Back">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
</div>

<!-- Form Card -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-300">
    <form action="{{ $action }}" method="POST">
        @csrf
        @if(isset($method))
            @method($method)
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($fields as $field)
                <div class="{{ ($field['type'] ?? 'text') === 'textarea' ? 'md:col-span-2' : '' }}">
                    <label for="{{ $field['name'] }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
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

        <!-- Buttons -->
        <div class="flex items-center gap-4 mt-8">
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
@endsection
