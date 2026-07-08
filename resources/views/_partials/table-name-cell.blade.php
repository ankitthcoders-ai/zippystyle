@props([
    'name',
    'image' => null,
    'fallbackIcon' => 'bx-package',
    'fallbackIconClass' => 'text-primary',
])

@if($image)
    <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}" class="rounded me-3" width="32" height="32" style="object-fit: cover;" />
@else
    <i class="icon-base bx {{ $fallbackIcon }} icon-md {{ $fallbackIconClass }} me-4"></i>
@endif
<span>{{ $name }}</span>
