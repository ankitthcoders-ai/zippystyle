@props([
    'name' => 'image',
    'label' => 'Image',
    'current' => null,
    'wrapperClass' => 'mb-4',
])

<div class="{{ $wrapperClass }}">
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>

    @if($current)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $current) }}" width="100" height="100" class="rounded object-fit-cover" alt="{{ $label }}">
        </div>
    @endif

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-control image-preview-input @error($name) is-invalid @enderror"
        accept="image/*"
        data-preview="{{ $name }}-preview"
    >

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="mt-2">
        <img
            id="{{ $name }}-preview"
            src="#"
            alt="{{ $label }} preview"
            class="rounded d-none"
            style="max-width: 150px; max-height: 150px; object-fit: cover;"
        >
    </div>
</div>
