@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-black text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>إعداد المصادقة الثنائية
                    </h4>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
                        <div>
                        <i class="fas fa-info-circle me-2"></i>
                        للمزيد من الأمان، يرجى إعداد المصادقة الثنائية باستخدام تطبيق Google Authenticator
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#installAppModal">
                            <i class="fas fa-download me-1"></i>تثبيت التطبيق
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('status') && session('status') != 'two-factor-authentication-enabled' && session('status') != 'تم تفعيل المصادقة الثنائية بنجاح! احفظ أكواد الاسترجاع في مكان آمن.')
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(!isset($passwordVerified) || !$passwordVerified)
                        {{-- الخطوة 1: التحقق من كلمة المرور --}}
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <span class="badge bg-primary fs-6 px-3 py-2">الخطوة 1 من 3</span>
                            </div>
                            <h5 class="text-dark mb-3">التحقق من كلمة المرور</h5>
                            <p class="text-muted mb-3">أدخل كلمة المرور لتأكيد هويتك قبل تفعيل المصادقة الثنائية:</p>
                            
                            <form method="POST" action="{{ route('two-factor.verify-password') }}" class="mb-4" id="passwordForm">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">كلمة المرور</label>
                                            <input type="password"
                                                   name="password"
                                                   id="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   required
                                                   autocomplete="current-password"
                                                   placeholder="أدخل كلمة المرور">
                                            @error('password')
                                                <div class="invalid-feedback d-block mt-2">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg w-100" id="passwordSubmitBtn">
                                            <i class="fas fa-shield-alt me-2"></i>تفعيل المصادقة الثنائية
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        {{-- الخطوة 2: عرض QR Code --}}
                        @if($qrCodeSvg)
                            <div class="text-center mb-4">
                                <div class="mb-3">
                                    <span class="badge bg-success fs-6 px-3 py-2">الخطوة 2 من 3</span>
                                </div>
                                <h5 class="text-dark mb-3">مسح QR Code</h5>
                                <p class="text-muted mb-3">افتح تطبيق Google Authenticator وامسح رمز QR التالي:</p>
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="border rounded p-3 bg-light">
                                        {!! $qrCodeSvg !!}
                                    </div>
                                </div>
                                <p class="text-muted">أو أدخل المفتاح السري يدوياً:</p>
                                <div class="input-group mb-3 mx-auto" style="max-width: 400px;" dir="ltr">
                                    <input type="text" class="form-control text-center font-monospace"
                                           value="{{ $user->two_factor_secret }}" readonly id="secretKey">
                                    <button class="btn btn-outline-secondary copy-btn"
                                            data-clipboard-text="{{ $user->two_factor_secret }}">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    بعد إضافة الحساب في التطبيق، انتقل إلى الخطوة التالية
                                </div>
                            </div>
                        @endif

                        {{-- الخطوة 3: التحقق من الكود --}}
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">الخطوة 3 من 3</span>
                            </div>
                            <h5 class="text-dark mb-3">التحقق من الكود</h5>
                            <p class="text-muted mb-3">أدخل الرمز المكون من 6 أرقام الذي يظهر في تطبيق Google Authenticator:</p>
                            
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-clock me-2"></i>
                                <strong>ملاحظة هامة حول تزامن الوقت:</strong>
                                <ul class="mb-0 mt-2 text-start">
                                    <li>تأكد من تفعيل <strong>الوقت التلقائي</strong> في إعدادات هاتفك</li>
                                    <li>Android: Settings > Date & Time > Automatic date & time</li>
                                    <li>iOS: Settings > General > Date & Time > Set Automatically</li>
                                    <li>الكود يتغير كل 30 ثانية - استخدم الكود الحالي من التطبيق</li>
                                </ul>
                            </div>

                            <form method="POST" action="{{ route('two-factor.enable-custom') }}" id="enable2FAForm">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text"
                                                   name="code"
                                                   id="codeInput"
                                                   class="form-control text-center font-monospace fs-4 @error('code') is-invalid @enderror"
                                                   maxlength="6"
                                                   pattern="[0-9]{6}"
                                                   required
                                                   placeholder="123456"
                                                   inputmode="numeric"
                                                   autocomplete="one-time-code"
                                                   dir="ltr"
                                                   value="{{ old('code') }}">
                                            @error('code')
                                                <div class="invalid-feedback d-block mt-2">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                            @if($errors->has('code'))
                                                <div class="text-danger mt-2">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('code') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                                <i class="fas fa-check-circle me-2"></i>تأكيد المصادقة الثنائية
                                            </button>

                                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-2"></i>لاحقاً
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

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
    if (document.querySelector('.copy-btn')) {
        new ClipboardJS('.copy-btn');
    }

    // Password form handler
    const passwordForm = document.getElementById('passwordForm');
    const passwordSubmitBtn = document.getElementById('passwordSubmitBtn');
    
    if (passwordForm && passwordSubmitBtn) {
        passwordForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value.trim();
            
            if (!password) {
                e.preventDefault();
                alert('الرجاء إدخال كلمة المرور');
                return false;
            }

            // Disable submit button to prevent double submission
            passwordSubmitBtn.disabled = true;
            passwordSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحقق...';
            
            return true;
        });
    }

    // Get code input
    const codeInput = document.getElementById('codeInput');
    const form = document.getElementById('enable2FAForm');
    const submitBtn = document.getElementById('submitBtn');

    // Auto-focus code input
    if (codeInput) {
        setTimeout(() => codeInput.focus(), 100);

        // Only allow numbers
        codeInput.addEventListener('input', function(e) {
            // Remove any non-numeric characters
            e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 6);
        });

        // Prevent non-numeric input
        codeInput.addEventListener('keypress', function(e) {
            // Only allow numbers (0-9)
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
    }

    // Form submission handler - فقط لمنع الإرسال المزدوج
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            const code = codeInput.value.trim();
            
            console.log('Form submission started', {
                code: code,
                codeLength: code.length,
                formAction: form.action
            });
            
            // Basic validation
            if (!code || code.length !== 6) {
                e.preventDefault();
                alert('الرجاء إدخال رمز تحقق مكون من 6 أرقام');
                codeInput.focus();
                return false;
            }

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحقق...';
            
            // Show loading message
            const errorDiv = form.querySelector('.text-danger');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
            
            // Log form data before submission
            const formData = new FormData(form);
            console.log('Submitting form with code:', code);
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);
            
            // Allow form to submit normally
            return true;
        });
    }
    
    // Show error messages if they exist and focus on input
    @if($errors->has('code'))
        setTimeout(function() {
            const codeInput = document.getElementById('codeInput');
            if (codeInput) {
                codeInput.focus();
                codeInput.select();
                // Scroll to input
                codeInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 300);
    @endif
</script>
@endpush

<!-- Install App Modal -->
<div class="modal fade" id="installAppModal" tabindex="-1" aria-labelledby="installAppModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="installAppModalLabel">
                    <i class="fas fa-download me-2"></i>تثبيت تطبيق Google Authenticator
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-4">قم بتنزيل تطبيق Google Authenticator على هاتفك:</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                       target="_blank" class="btn btn-outline-dark btn-lg">
                        <i class="fab fa-google-play me-2"></i> Google Play
                    </a>
                    <a href="https://apps.apple.com/us/app/google-authenticator/id388497605"
                       target="_blank" class="btn btn-outline-dark btn-lg">
                        <i class="fab fa-app-store me-2"></i> App Store
                    </a>
                </div>
                <div class="mt-4">
                    <p class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        بعد تثبيت التطبيق، ارجع إلى هذه الصفحة وامسح رمز QR أعلاه
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection
