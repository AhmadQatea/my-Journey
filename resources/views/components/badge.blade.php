@props([
    'type' => 'primary',
    'pill' => false
])

@php
    $typeClasses = [
        'primary' => 'bg-blue-100 text-blue-800',
        'secondary' => 'bg-gray-100 text-gray-800',
        'success' => 'bg-green-100 text-green-800',
        'danger' => 'bg-red-100 text-red-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'info' => 'bg-cyan-100 text-cyan-800',
    ][$type] ?? 'bg-blue-100 text-blue-800';
@endphp

<span {{ $attributes->class([
    'inline-flex items-center px-2.5 py-0.5 text-xs font-medium',
    $typeClasses,
    'rounded-full' => $pill,
    'rounded' => !$pill
]) }}>
    {{ $slot }}
</span>
