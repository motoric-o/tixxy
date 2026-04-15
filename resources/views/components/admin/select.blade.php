@props(['label' => null, 'name', 'required' => false, 'disabled' => false])

<div {{ $attributes->only('class')->merge(['class' => 'group']) }}>
    @if($label)
        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-purple-500 transition-colors">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif
    <div class="relative">
        <select name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->except(['class', 'label', 'name', 'required', 'disabled']) }}
            class="appearance-none w-full h-[50px] bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block pl-4 pr-11 transition-all duration-300 @if($disabled) opacity-60 bg-gray-50 dark:bg-gray-800/50 cursor-not-allowed @else cursor-pointer @endif !bg-none">
            {{ $slot }}
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>
    @error($name)
        <p class="mt-2 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
    @enderror
</div>
