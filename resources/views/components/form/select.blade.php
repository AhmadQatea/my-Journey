@props([
    'name',
    'label' => '',
    'options' => [],
    'selected' => '',
    'required' => false,
    'id' => '',
    'placeholder' => 'اختر من القائمة'
])

@php
    $id = $id ?: $name;
    $oldSelected = old($name, $selected);
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

    <select
        name="{{ $name }}"
        id="{{ $id }}"
        class="form-select @error($name) is-invalid @enderror"
        @if($required) required @endif
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $key => $value)
            <option value="{{ $key }}" {{ $oldSelected == $key ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
