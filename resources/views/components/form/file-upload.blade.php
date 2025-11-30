@props([
    'name',
    'label' => '',
    'required' => false,
    'accept' => '',
    'multiple' => false,
    'id' => ''
])

@php
    $id = $id ?: $name;
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $id }}"
        class="form-control @error($name) is-invalid @enderror"
        @if($accept) accept="{{ $accept }}" @endif
        @if($multiple) multiple @endif
        @if($required) required @endif
    >

    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
