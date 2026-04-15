@extends('layouts.admin.default')

@section('content')
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($method))
            @method($method)
        @endif

        {{-- Sticky Header Bar --}}
        <div
            class="sticky top-4 z-40 bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl p-4 rounded-2xl border border-white/20 dark:border-gray-800/50 shadow-xl shadow-purple-500/5 ring-1 ring-black/5 dark:ring-white/5 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ $backUrl }}"
                    class="p-2.5 text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 bg-gray-100 dark:bg-gray-800 rounded-xl transition-all hover:scale-105 active:scale-95 border border-transparent hover:border-purple-500/20"
                    title="Back">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ $title }}
                    </h2>
                    @if (isset($subtitle))
                        <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-0.5">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ $backUrl }}"
                    class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white rounded-xl bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 bg-[length:200%_auto] hover:bg-[position:right_center] shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 transition-all duration-500 hover:-translate-y-0.5 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Save Changes</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 {{ !empty($detailFields ?? []) ? 'lg:grid-cols-3' : 'max-w-4xl mx-auto' }} gap-8">

            {{-- Details Panel (Optional) --}}
            @if (!empty($detailFields ?? []))
                <div class="lg:col-span-1 space-y-8 animate-in fade-in slide-in-from-left-4 duration-500">
                    
                    {{-- Standard Details Card --}}
                    @php $standardFields = array_filter($detailFields, fn($f) => !isset($f['type'])); @endphp
                    @if (count($standardFields) > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50">
                                <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Summary Information</h3>
                            </div>
                            <div class="p-6 space-y-5">
                                @foreach ($standardFields as $detail)
                                    <div class="flex flex-col gap-1 group">
                                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $detail['label'] }}</span>
                                        @if (isset($detail['url']))
                                            <a href="{{ $detail['url'] }}"
                                                class="text-sm font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors">
                                                {{ $detail['value'] }}
                                            </a>
                                        @else
                                            <span class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                {{ $detail['value'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Table/Badges Detail Cards --}}
                    @foreach ($detailFields as $detail)
                        @if (($detail['type'] ?? null) === 'table' && !empty($detail['value']))
                            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-5 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50">
                                    <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">{{ $detail['label'] }}</h3>
                                </div>
                                <div class="p-0">
                                    <div class="overflow-x-auto text-[13px]">
                                        <table class="w-full">
                                            <thead class="bg-gray-50/30 dark:bg-gray-900/30 text-gray-400 dark:text-gray-500 font-bold uppercase text-[10px]">
                                                <tr>
                                                    @foreach (array_keys($detail['value'][0]) as $header)
                                                        <th class="py-3 px-6 text-left">{{ $header }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                                                @foreach ($detail['value'] as $row)
                                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-colors">
                                                        @foreach ($row as $cell)
                                                            <td class="py-3.5 px-6 font-semibold text-gray-700 dark:text-gray-300">{{ $cell }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (($detail['type'] ?? null) === 'badges' && !empty($detail['value']))
                            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-5 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50">
                                    <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">{{ $detail['label'] }}</h3>
                                </div>
                                <div class="p-6">
                                    <div class="flex flex-wrap gap-2.5">
                                        @foreach ($detail['value'] as $badge)
                                            <a @if(isset($badge['url'])) href="{{ $badge['url'] }}" @endif
                                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all
                                                {{ ($badge['color'] ?? 'gray') === 'green'
                                                    ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50'
                                                    : 'bg-gray-50 text-gray-600 dark:bg-gray-700/30 dark:text-gray-400 border border-gray-100 dark:border-gray-600/50' }}
                                                {{ isset($badge['url']) ? 'hover:scale-105 active:scale-95' : '' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ ($badge['color'] ?? 'gray') === 'green' ? 'bg-emerald-500' : 'bg-gray-400' }} shadow-[0_0_8px] {{ ($badge['color'] ?? 'gray') === 'green' ? 'shadow-emerald-500/50' : 'shadow-gray-400/50' }}"></span>
                                                <span class="font-mono tracking-tight">{{ $badge['label'] }}</span>
                                                <span class="opacity-50 text-[10px]">{{ $badge['badge'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Form Section --}}
            <div class="{{ !empty($detailFields ?? []) ? 'lg:col-span-2' : 'w-full' }} animate-in fade-in slide-in-from-right-4 duration-500 delay-75">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-[0.1em]">
                            {{ isset($item) && $item ? 'Manage Record Details' : 'Initialize New Record' }}
                        </h3>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                            @foreach ($fields as $field)
                                <div class="{{ ($field['type'] ?? 'text') === 'textarea' || ($field['wide'] ?? false) ? 'md:col-span-2' : '' }} group">
                                    <label for="{{ $field['name'] }}"
                                        class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-purple-500 transition-colors">
                                        {{ $field['label'] }}
                                        @if ($field['required'] ?? false)
                                            <span class="text-red-500 font-black">*</span>
                                        @endif
                                    </label>

                                    @if (($field['type'] ?? 'text') === 'textarea')
                                        <textarea name="{{ $field['name'] }}" id="{{ $field['name'] }}" rows="5"
                                            placeholder="{{ $field['placeholder'] ?? 'Enter ' . strtolower($field['label']) . '...' }}"
                                            class="w-full bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block p-4 transition-all duration-300"
                                            {{ $field['required'] ?? false ? 'required' : '' }}>{{ old($field['name'], data_get($item ?? null, $field['name'])) }}</textarea>
                                    @elseif(($field['type'] ?? 'text') === 'select')
                                        <div class="relative">
                                            <select name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                class="appearance-none w-full bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block pl-4 pr-11 h-[50px] transition-all duration-300 cursor-pointer !bg-none"
                                                {{ $field['required'] ?? false ? 'required' : '' }}>
                                                <option value="">Choose {{ $field['label'] }}</option>
                                                @foreach ($field['options'] ?? [] as $value => $optionLabel)
                                                    <option value="{{ $value }}"
                                                        {{ old($field['name'], data_get($item ?? null, $field['name'])) == $value ? 'selected' : '' }}>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    @elseif(($field['type'] ?? 'text') === 'file')
                                        <div class="space-y-4">
                                            <div class="relative group h-40 flex items-center justify-center border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-3xl hover:border-purple-500/50 hover:bg-purple-500/5 transition-all cursor-pointer overflow-hidden" 
                                                 onclick="document.getElementById('{{ $field['name'] }}').click()">
                                                <input type="file" name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="hidden" 
                                                       @change="previewImage($event, 'preview-{{ $field['name'] }}')">
                                                
                                                @php $existingImage = data_get($item ?? null, $field['name']); @endphp
                                                <img id="preview-{{ $field['name'] }}" 
                                                     src="{{ $existingImage ? asset('storage/' . $existingImage) : '#' }}" 
                                                     class="{{ $existingImage ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-20 transition-opacity">
                                                
                                                <div class="text-center space-y-2 relative z-10 transition-transform group-hover:scale-105">
                                                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-2xl mx-auto w-fit">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                    </div>
                                                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                                        {{ $existingImage ? 'Change Media' : 'Click to Upload' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] }}"
                                            id="{{ $field['name'] }}" placeholder="{{ $field['placeholder'] ?? 'Type ' . strtolower($field['label']) . '...' }}"
                                            value="{{ old($field['name'], data_get($item ?? null, $field['name'])) }}"
                                            class="w-full h-[50px] bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block px-4 transition-all duration-300"
                                            {{ $field['required'] ?? false ? 'required' : '' }}>
                                    @endif

                                    @error($field['name'])
                                        <div class="flex items-center gap-1.5 mt-2 ml-1 text-red-500">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                            <p class="text-[10px] font-bold uppercase tracking-tight">{{ $message }}</p>
                                        </div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function previewImage(event, previewId) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
