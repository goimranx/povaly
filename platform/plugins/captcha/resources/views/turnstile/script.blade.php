@if (!$isRendered || request()->ajax())
    <script
        src="{{ $url }}"
        async
        defer
    ></script>

    <script>
        'use strict';

        window.turnstileInputs = window.turnstileInputs || [];

        var refreshTurnstile = function() {
            window.turnstileInputs.forEach(function(item) {
                if (window.turnstile && document.getElementById(item)) {
                    turnstile.reset('#' + item);
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.turnstileInputs.forEach(function(item) {
                if (!document.getElementById(item)) {
                    console.warn('Turnstile placeholder element not found for ID:', item);
                }
            });
        });
    </script>
@endif

<script>
    window.turnstileInputs = window.turnstileInputs || [];
    window.turnstileInputs.push('{{ $name }}');
</script>
