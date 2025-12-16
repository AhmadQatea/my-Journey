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
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        سنرسل لك رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني لتأكيد هويتك قبل تغيير كلمة المرور.
                    </p>

                    <form method="POST" action="{{ route('password.change.send') }}" id="sendCodeForm">
                        @csrf

                        <div class="mb-4">
                            <div class="alert alert-info">
                                <i class="fas fa-envelope me-2"></i>
                                <strong>البريد الإلكتروني:</strong> {{ Auth::user()->email }}
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                <span>إرسال رمز التحقق</span>
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

@push('scripts')
<script>
    const form = document.getElementById('sendCodeForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الإرسال...';

            // Allow form to submit normally
            return true;
        });
    }
</script>
@endpush
@endsection

