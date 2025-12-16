@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-black text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-lock me-2"></i>تغيير كلمة المرور
                    </h4>
                </div>

                <div class="card-body p-4">
                    <!-- قسم 2FA إذا كان مفعلاً -->
                    @if(Auth::user()->two_factor_confirmed_at)
                    <div class="alert alert-warning mb-4" id="2faSection">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1">المصادقة الثنائية مفعلة</h5>
                                <p class="mb-0">يجب إدخال رمز التحقق من تطبيق Google Authenticator</p>
                            </div>
                        </div>

                        <div class="mt-3" id="2faForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="two_factor_code" class="form-label">رمز التحقق (6 أرقام)</label>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="two_factor_code"
                                               id="two_factor_code"
                                               class="form-control text-center font-monospace fs-4"
                                               maxlength="6"
                                               pattern="[0-9]{6}"
                                               placeholder="123456"
                                               dir="ltr">
                                        <button type="button" class="btn btn-outline-secondary"
                                                onclick="verify2FA()" id="verifyBtn">
                                            <i class="fas fa-check"></i> التحقق
                                        </button>
                                    </div>
                                    <div id="2faError" class="text-danger d-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- نموذج تغيير كلمة المرور -->
                    <form method="POST" action="{{ route('password.change') }}" id="passwordForm"
                          class="{{ Auth::user()->two_factor_confirmed_at ? 'd-none' : '' }}">
                        @csrf

                        @if(session('password_change_verified'))
                            <div class="alert alert-success mb-4">
                                <i class="fas fa-check-circle me-2"></i>
                                تم التحقق من هويتك بنجاح. يمكنك الآن تغيير كلمة المرور.
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-key me-2"></i>
                                    كلمة المرور الحالية
                                </label>
                                <div class="input-group">
                                    <input type="password"
                                           name="current_password"
                                           id="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>
                                كلمة المرور الجديدة
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required>
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="mt-2">
                                <div class="password-strength">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" id="passwordStrength"
                                             role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted" id="strengthText">قوة كلمة المرور</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-2"></i>
                                تأكيد كلمة المرور الجديدة
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control"
                                       required>
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>تغيير كلمة المرور
                            </button>

                            <a href="{{ route('password.change.request') }}" class="btn btn-outline-warning">
                                <i class="fas fa-question-circle me-2"></i>
                                هل نسيت كلمة المرور الحالية؟
                            </a>

                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right me-2"></i>رجوع
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// التحقق من 2FA
function verify2FA() {
    const code = document.getElementById('two_factor_code').value;
    const verifyBtn = document.getElementById('verifyBtn');
    const errorDiv = document.getElementById('2faError');

    if (code.length !== 6) {
        errorDiv.textContent = 'يجب إدخال 6 أرقام';
        errorDiv.classList.remove('d-none');
        return;
    }

    verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحقق...';
    verifyBtn.disabled = true;

    fetch('{{ route("two-factor.verify-for-password-change") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ two_factor_code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // إخفاء قسم 2FA وإظهار نموذج كلمة المرور
            document.getElementById('2faSection').classList.add('d-none');
            document.getElementById('passwordForm').classList.remove('d-none');

            // إضافة رمز التحقق إلى النموذج
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'two_factor_code';
            hiddenInput.value = code;
            document.getElementById('passwordForm').appendChild(hiddenInput);
        } else {
            errorDiv.textContent = data.message;
            errorDiv.classList.remove('d-none');
            verifyBtn.innerHTML = '<i class="fas fa-check"></i> التحقق';
            verifyBtn.disabled = false;
        }
    })
    .catch(error => {
        errorDiv.textContent = 'حدث خطأ في الاتصال';
        errorDiv.classList.remove('d-none');
        verifyBtn.innerHTML = '<i class="fas fa-check"></i> التحقق';
        verifyBtn.disabled = false;
    });
}

// إظهار/إخفاء كلمة المرور
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input');
        const icon = this.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});

// قياس قوة كلمة المرور
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');

    let score = 0;

    // طول كلمة المرور
    if (password.length >= 8) score += 25;
    if (password.length >= 12) score += 15;

    // أحرف متنوعة
    if (/[a-z]/.test(password)) score += 10;
    if (/[A-Z]/.test(password)) score += 10;
    if (/[0-9]/.test(password)) score += 10;
    if (/[^A-Za-z0-9]/.test(password)) score += 10;

    // عدم استخدام كلمات شائعة
    const commonPasswords = ['password', '123456', 'qwerty'];
    if (!commonPasswords.includes(password.toLowerCase())) score += 20;

    // تحديث شريط القوة
    strengthBar.style.width = score + '%';

    // تحديث النص واللون
    if (score < 50) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'ضعيفة';
    } else if (score < 75) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'جيدة';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'قوية جداً';
    }
});

// إدخال رمز 2FA تلقائياً
document.getElementById('two_factor_code')?.addEventListener('input', function() {
    if (this.value.length === 6) {
        verify2FA();
    }
});
</script>
@endpush
@endsection
