@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 bg-white/50 backdrop-blur-sm focus:border-brand-500 focus:ring-brand-500 rounded-xl shadow-sm transition-all duration-200 ease-in-out py-2.5']) }}>