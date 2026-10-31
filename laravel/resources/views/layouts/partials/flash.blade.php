{{-- Server flash messages as toasts. JS toasts (showToast) are appended to the same stack. --}}
<div class="toast-stack" id="toast-stack">
    @if (session('success'))
        <div class="toast app-toast fade" data-type="success" role="status" aria-live="polite" aria-atomic="true"
            data-autoshow data-bs-delay="5000">
            <div class="app-toast__inner">
                <i class="bi bi-check-circle app-toast__icon" aria-hidden="true"></i>
                <div class="app-toast__text">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="toast app-toast fade" data-type="error" role="alert" aria-live="assertive" aria-atomic="true"
            data-autoshow data-bs-delay="8000">
            <div class="app-toast__inner">
                <i class="bi bi-exclamation-circle app-toast__icon" aria-hidden="true"></i>
                <div class="app-toast__text">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div>
