@props(['name', 'type' => 'text', 'placeholder' => '', 'value' => null, 'label' => null])

<div class="mb-3">
    @if ($label)
        <label>{{ $label }}</label>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : ''),
        ]) }}>

    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>