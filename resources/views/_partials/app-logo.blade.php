@php
$variant = $variant ?? 'sidebar';
@endphp

@if ($variant === 'auth')
<span class="app-brand-logo demo">
    <img src="{{ asset('assets/img/branding/zippy-style-logo.png') }}"
         alt="{{ config('variables.templateName') }}"
         class="zippy-style-logo zippy-style-logo--auth"
         height="72"
         style="height: 72px; max-width: 220px; width: auto; object-fit: contain; display: block;">
</span>
@else
<span class="app-brand-logo demo">
    <img src="{{ asset('assets/img/branding/zippy-style-logo-icon.png') }}"
         alt="{{ config('variables.templateName') }}"
         class="zippy-style-logo zippy-style-logo--icon"
         height="64"
         style="height: 64px; max-width: 72px; width: auto; object-fit: contain; display: block;">
</span>
@endif
