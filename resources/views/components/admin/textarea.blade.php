@props(['label' => null, 'name', 'value' => '', 'required' => false, 'disabled' => false, 'rows' => 4, 'placeholder' => ''])

<div {{ $attributes->only('class')->merge(['class' => 'group']) }}>
    @if($label)
        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-purple-500 transition-colors">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif
    <textarea name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except(['class', 'label', 'name', 'value', 'required', 'disabled', 'rows', 'placeholder']) }}
        class="w-full bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 block p-4 transition-all duration-300 @if($disabled) opacity-60 bg-gray-50 dark:bg-gray-800/50 cursor-not-allowed @endif">{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="mt-2 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
    @enderror
</div>
