@props([
    'type' => 'info',
    'dismissible' => true,
    'message' => ''
])

<div class="alert alert-{{ $type }} @if($dismissible) alert-dismissible fade show @endif" role="alert">
    @if($message)
        {{ $message }}
    @else
        {{ $slot }}
    @endif

    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
