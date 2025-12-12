@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>المصادقة الثنائية
                    </h3>
                    <p class="mb-0 mt-2">يرجى إدخال رمز التحقق من تطبيق Google Authenticator</p>
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('two-factor.verify') }}">
                        @csrf

                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-mobile-alt fa-4x text-primary"></i>
                            </div>
                            <p class="text-muted">افتح تطبيق Google Authenticator على هاتفك وأدخل الرمز المكون من 6 أرقام</p>
                        </div>

                        <div class="mb-4">
                            <label for="code" class="form-label text-dark">رمز التحقق</label>
                            <div class="input-group input-group-lg">
                                <input type="text"
                                       name="code"
                                       id="code"
                                       class="form-control text-center font-monospace fs-3"
                                       maxlength="6"
                                       pattern="[0-9]{6}"
                                       required
                                       placeholder="123456"
                                       autofocus
                                       dir="ltr">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-key text-primary"></i>
                                </span>
                            </div>
                            @error('code')
                                <div class="text-danger mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>تأكيد وتسجيل الدخول
                            </button>

                            <a href="{{ route('two-factor.recovery-codes') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-key me-2"></i>استخدام كود استرجاع
                            </a>
                        </div>

                        <div class="mt-4 text-center">
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                إذا فقدت هاتفك، يمكنك استخدام أكواد الاسترجاع التي حصلت عليها عند إعداد المصادقة الثنائية.
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto submit when 6 digits entered
    document.getElementById('code').addEventListener('input', function(e) {
        if (e.target.value.length === 6) {
            e.target.form.submit();
        }
    });
</script>
@endpush
@endsection
