@extends('layouts.app')

@section('title', 'التحقق من البريد الإلكتروني')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-envelope-circle-check fa-3x"></i>
                    </div>
                    <h4 class="mb-2">التحقق من البريد الإلكتروني</h4>
                    <p class="mb-0 opacity-75">أدخل رمز التحقق المرسل إلى بريدك الإلكتروني</p>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle me-2 mt-1"></i>
                            <div>
                                <strong>معلومات:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>تم إرسال رمز تحقق مكون من 6 أرقام إلى: <strong>{{ Auth::user()->email }}</strong></li>
                                    <li>الرمز صالح لمدة 15 دقيقة فقط</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('email.verify.post') }}" id="verificationForm">
                        @csrf

                        <div class="mb-4">
                            <label for="code" class="form-label">رمز التحقق</label>
                            <input type="text" 
                                   class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   maxlength="6"
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   placeholder="000000"
                                   style="font-size: 2rem; letter-spacing: 0.5rem; font-weight: bold;"
                                   required 
                                   autofocus
                                   oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6)">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check me-2"></i>التحقق من الرمز
                            </button>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <form method="POST" action="{{ route('email.resend') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-2"></i>إعادة إرسال الرمز
                                </button>
                            </form>
                            <a href="{{ route('dashboard') }}" class="btn btn-link">
                                <i class="fas fa-arrow-right me-2"></i>العودة للوحة التحكم
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const form = document.getElementById('verificationForm');

    // التحقق التلقائي عند إدخال 6 أرقام
    codeInput.addEventListener('input', function() {
        if (this.value.length === 6) {
            setTimeout(() => {
                form.submit();
            }, 500);
        }
    });
});
</script>
@endsection

