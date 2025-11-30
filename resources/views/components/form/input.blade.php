@props([
    'name',
    'label' => '',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'placeholder' => '',
    'id' => '',
    'attributes' => []
])

@php
    $id = $id ?: $name;
    $oldValue = old($name, $value);
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
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ $oldValue }}"
        class="form-control @error($name) is-invalid @enderror"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes }}
    >

    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
