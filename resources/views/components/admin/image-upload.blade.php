@props(['label' => null, 'name', 'value' => null, 'disabled' => false])

<div {{ $attributes->only('class')->merge(['class' => 'group']) }} 
    x-data="{ 
        preview: '{{ $value ? asset('storage/' . $value) : '' }}',
        handleFile(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => this.preview = e.target.result;
                reader.readAsDataURL(file);
            }
        }
    }">
    @if($label)
        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-purple-500 transition-colors">
            {{ $label }}
        </label>
    @endif
    <div class="relative group/file">
        <div :class="preview ? 'border-purple-200 dark:border-purple-900/50 bg-white dark:bg-gray-900' : 'border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50'"
            class="p-4 rounded-2xl border-2 transition-all duration-300 {{ !$disabled ? 'hover:border-purple-400/50' : '' }}">
            
            <template x-if="preview">
                <div class="relative rounded-xl overflow-hidden aspect-video bg-gray-100 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <img :src="preview" class="w-full h-full object-cover">
                    @if(!$disabled)
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/file:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl text-xs font-bold text-white border border-white/20">Change Image</span>
                        </div>
                    @endif
                </div>
            </template>

            <template x-if="!preview">
                <div class="py-8 flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center px-4">
                        {{ !$disabled ? 'Drag and drop banner or click to browse' : 'No image provided' }}
                    </p>
                </div>
            </template>

            @if(!$disabled)
                <input type="file" name="{{ $name }}" @change="handleFile"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
            @endif
        </div>
        @error($name)
            <p class="mt-2 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
        @enderror
    </div>
</div>
