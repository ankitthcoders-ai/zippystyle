@php
$containerFooter = !empty($containerNav) ? $containerNav : 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
    <div class="{{ $containerFooter }}">
        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
            <div class="text-body mb-2 mb-md-0">
                © {{ date('Y') }}, made with ❤️ by
                <a href="{{ config('variables.creatorUrl') ?: '#' }}" target="_blank" class="footer-link fw-medium">{{ config('variables.creatorName') ?: 'Thcoders' }}</a>
            </div>
            <div class="text-body">
                {{ config('variables.templateName') ?: 'TH Style' }} · All rights reserved.
            </div>
        </div>
    </div>
</footer>
<!--/ Footer-->