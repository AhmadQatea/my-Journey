@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-black text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-key me-2"></i>أكواد الاسترجاع للمصادقة الثنائية
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تحذير هام:</strong> احفظ هذه الأكواد في مكان آمن. يمكنك استخدامها لتسجيل الدخول في حالة فقدان هاتفك أو عدم إمكانية الوصول إلى تطبيق Google Authenticator.
                    </div>

                    @if(session('recoveryCodes') && count(session('recoveryCodes')) > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            هذه هي أكواد الاسترجاع الجديدة. احفظها الآن لأنها لن تظهر مرة أخرى.
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5 class="text-dark mb-3">أكواد الاسترجاع:</h5>
                        <div class="row g-2">
                            @php
                                // Get recovery codes from session first, then from controller
                                $codes = session('recoveryCodes') ?? (is_array($recoveryCodes) ? $recoveryCodes : []);
                            @endphp
                            @if(!empty($codes) && count($codes) > 0)
                                @foreach($codes as $index => $code)
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body p-3">
                                                <code class="font-monospace fs-5">{{ $code }}</code>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    لا توجد أكواد استرجاع متاحة. يرجى إنشاء أكواد جديدة.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('two-factor.generate-recovery-codes') }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('هل أنت متأكد؟ سيتم استبدال جميع أكواد الاسترجاع الحالية بأكواد جديدة.')">
                                <i class="fas fa-redo me-2"></i>إنشاء أكواد استرجاع جديدة
                            </button>
                        </form>

                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right me-2"></i>العودة إلى لوحة التحكم
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
