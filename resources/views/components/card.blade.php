@props([
    'title' => '',
    'footer' => ''
])

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    @if($title)
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h5 class="text-lg font-semibold text-gray-900 mb-0">{{ $title }}</h5>
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $footer }}
        </div>
    @endif
</div>
