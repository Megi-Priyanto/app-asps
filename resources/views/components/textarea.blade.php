@props(['name', 'placeholder' => '', 'value' => null, 'label' => null, 'rows' => 3])

<div class="mb-3">
    @if ($label)
        <label>{{ $label }}</label>
    @endif

    <textarea name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : ''),
        ]) }}>{{ old($name, $value) }}</textarea>

    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
