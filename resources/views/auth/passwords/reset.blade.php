@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-black text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-key me-2"></i>تعيين كلمة المرور الجديدة
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        أدخل كلمة مرور جديدة قوية لحسابك.
                    </p>

                    <form method="POST" action="{{ route('password.reset') }}">
                        @csrf

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
                                       required
                                       autofocus
                                       placeholder="كلمة المرور الجديدة (8 أحرف على الأقل)">
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
                                       required
                                       placeholder="أعد إدخال كلمة المرور">
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>
                                تعيين كلمة المرور الجديدة
                            </button>

                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة لتسجيل الدخول
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light text-center py-3">
                    <p class="mb-0 text-muted">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            تأكد من استخدام كلمة مرور قوية وفريدة
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection

