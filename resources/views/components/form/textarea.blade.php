@props([
    'name',
    'label' => '',
    'value' => '',
    'required' => false,
    'placeholder' => '',
    'id' => '',
    'rows' => 3
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

    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        rows="{{ $rows }}"
        class="form-control @error($name) is-invalid @enderror"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
    >{{ $oldValue }}</textarea>

    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
