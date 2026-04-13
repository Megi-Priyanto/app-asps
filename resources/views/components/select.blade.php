@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => '-- Pilih --',
    'valueField' => 'id',
    'labelField' => null,
])

<div class="mb-3">
    @if ($label)
        <label>{{ $label }}</label>
    @endif

    <select name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'form-select ' . ($errors->has($name) ? 'is-invalid' : ''),
        ]) }}>
        
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $option)
            <option value="{{ $option->{$valueField} }}"
                {{ old($name, $value) == $option->{$valueField} ? 'selected' : '' }}>
                {{ $labelField ? $option->{$labelField} : $option }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
