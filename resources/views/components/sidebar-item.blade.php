@props([
    'title',
    'route',
    'icon',
    'routePattern' => null,
    'badge' => null,
    'badgeClass' => 'badge badge-warning text-xs',
])

@php
    $isActive = $routePattern ? request()->routeIs($routePattern) : (is_string($route) ? request()->routeIs($route) : false);
    // إذا كان route عبارة عن URL كامل (مثل dashboard مع role)، نستخدمه مباشرة
    // وإلا نستخدم route() لإنشاء URL من اسم الـ route
    $href = (is_string($route) && (str_starts_with($route, 'http://') || str_starts_with($route, 'https://') || str_starts_with($route, '/')))
        ? $route
        : (is_string($route) ? route($route) : $route);
@endphp

<a href="{{ $href }}"
   class="menu-item flex items-center space-x-3 space-x-reverse p-3 text-blue-100 hover:text-black transition-all duration-300 {{ $isActive ? 'active' : '' }}"
   data-tooltip="{{ $title }}">
    <i class="{{ $icon }} w-4 text-base"></i>
    <span class="menu-text font-medium transition-all duration-300 text-sm">{{ $title }}</span>
    @if($badge)
        <span class="{{ $badgeClass }}">{{ $badge }}</span>
    @endif
</a>

