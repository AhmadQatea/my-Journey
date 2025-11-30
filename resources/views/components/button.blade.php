@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'icon' => '',
    'iconPosition' => 'left',
    'href' => null
])

@php
    $variantClasses = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
        'info' => 'bg-cyan-600 hover:bg-cyan-700 text-white',
    ][$variant] ?? 'bg-blue-600 hover:bg-blue-700 text-white';

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg'
    ][$size] ?? 'px-4 py-2 text-base';

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    @if(!$href) type="{{ $type }}" @endif
    class="inline-flex items-center justify-center rounded-lg font-medium transition-colors {{ $variantClasses }} {{ $sizeClasses }} @if($disabled) opacity-50 cursor-not-allowed @endif"
    @if($disabled && !$href) disabled @endif
    {{ $attributes }}
>
    @if($icon && $iconPosition === 'left')
        <i class="fas fa-{{ $icon }} ml-2"></i>
    @endif

    {{ $slot }}

    @if($icon && $iconPosition === 'right')
        <i class="fas fa-{{ $icon }} mr-2"></i>
    @endif
</{{ $tag }}>
