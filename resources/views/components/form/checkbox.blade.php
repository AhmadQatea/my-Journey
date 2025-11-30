@props([
    'name',
    'label' => '',
    'checked' => false,
    'value' => '1',
    'id' => ''
])

@php
    $id = $id ?: $name;
    $oldChecked = old($name, $checked);
@endphp

<div class="mb-3 form-check form-switch">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ $value }}"
        class="form-check-input @error($name) is-invalid @enderror"
        {{ $oldChecked ? 'checked' : '' }}
        role="switch"
    >

    @if($label)
        <label class="form-check-label" for="{{ $id }}">
            {{ $label }}
        </label>
    @endif

    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
