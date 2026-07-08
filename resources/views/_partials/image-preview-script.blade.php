<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.image-preview-input').forEach(function (input) {
        input.addEventListener('change', function () {
            const preview = document.getElementById(input.dataset.preview);

            if (!preview) {
                return;
            }

            if (input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.classList.remove('d-none');
            } else {
                preview.src = '#';
                preview.classList.add('d-none');
            }
        });
    });
});
</script>
