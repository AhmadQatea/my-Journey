@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>إعداد المصادقة الثنائية
                    </h4>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        للمزيد من الأمان، يرجى إعداد المصادقة الثنائية باستخدام تطبيق Google Authenticator
                    </div>

                    <div class="text-center mb-4">
                        <h5 class="text-dark mb-3">الخطوة 1: تثبيت التطبيق</h5>
                        <p class="text-muted">قم بتنزيل تطبيق Google Authenticator على هاتفك:</p>
                        <div class="d-flex justify-content-center gap-3 mb-4">
                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                               target="_blank" class="btn btn-outline-dark">
                                <i class="fab fa-google-play"></i> Google Play
                            </a>
                            <a href="https://apps.apple.com/us/app/google-authenticator/id388497605"
                               target="_blank" class="btn btn-outline-dark">
                                <i class="fab fa-app-store"></i> App Store
                            </a>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <h5 class="text-dark mb-3">الخطوة 2: مسح QR Code</h5>
                        <div class="d-flex justify-content-center">
                            <div class="border rounded p-3 bg-light">
                                {!! $qrCodeSvg !!}
                            </div>
                        </div>
                        <p class="text-muted mt-3">أو أدخل المفتاح السري يدوياً:</p>
                        <div class="input-group mb-3" dir="ltr">
                            <input type="text" class="form-control text-center font-monospace"
                                   value="{{ $user->two_factor_secret }}" readonly>
                            <button class="btn btn-outline-secondary copy-btn"
                                    data-clipboard-text="{{ $user->two_factor_secret }}">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <h5 class="text-dark mb-3">الخطوة 3: التحقق</h5>
                        <p class="text-muted">أدخل الرمز المكون من 6 أرقام الذي يظهر في التطبيق:</p>

                        <form method="POST" action="{{ route('two-factor.enable') }}">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="text"
                                               name="code"
                                               class="form-control text-center font-monospace fs-4"
                                               maxlength="6"
                                               pattern="[0-9]{6}"
                                               required
                                               placeholder="123456"
                                               dir="ltr">
                                        @error('code')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-check-circle me-2"></i>تفعيل المصادقة الثنائية
                                        </button>

                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>لاحقاً
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="alert alert-warning mt-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>ملاحظة هامة:</strong> احفظ أكواد الاسترجاع في مكان آمن. ستستخدمها في حالة فقدان هاتفك.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
<script>
    // Copy secret key
    new ClipboardJS('.copy-btn');

    // Auto-focus code input
    document.querySelector('input[name="code"]').focus();

    // Auto-move between inputs (if using separate inputs)
    const inputs = document.querySelectorAll('input[type="text"]');
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
</script>
@endpush
@endsection
