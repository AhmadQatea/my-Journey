@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-black text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-key me-2"></i>استعادة كلمة المرور
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        أدخل بريدك الإلكتروني وسنرسل لك رمز تحقق مكون من 6 أرقام.
                    </p>

                    <form method="POST" action="{{ route('password.code.send') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>
                                البريد الإلكتروني
                            </label>
                            <div class="input-group">
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       placeholder="example@email.com">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-at text-primary"></i>
                                </span>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                إرسال رمز التحقق
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
                            ستصلك رسالة بريد إلكتروني تحتوي على رمز تحقق صالح لمدة 15 دقيقة
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
