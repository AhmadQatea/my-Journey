@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-key me-2"></i>طلب كود التحقق لتغيير كلمة المرور
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
                        سنرسل لك رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني لتأكيد هويتك قبل تغيير كلمة المرور.
                    </p>

                    <form method="POST" action="{{ route('password.change.send') }}">
                        @csrf

                        <div class="mb-4">
                            <div class="alert alert-info">
                                <i class="fas fa-envelope me-2"></i>
                                <strong>البريد الإلكتروني:</strong> {{ Auth::user()->email }}
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                إرسال رمز التحقق
                            </button>

                            <a href="{{ route('password.change.form') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                رجوع
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light text-center py-3">
                    <p class="mb-0 text-muted">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            ستصلك رسالة بريد إلكتروني تحتوي على رمز تحقق صالح لمدة 10 دقائق
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

