@extends('layouts.app')

@section('title', 'توثيق الهوية')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>توثيق الهوية الشخصية
                    </h4>
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

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($emailNotVerified)
                        {{-- البريد الإلكتروني غير موثق --}}
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>تحذير:</strong> يجب التحقق من بريدك الإلكتروني أولاً قبل رفع صورة الهوية.
                            <div class="mt-3">
                                <form method="POST" action="{{ route('email.send') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-envelope me-2"></i>إرسال كود التحقق إلى بريدي الإلكتروني
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right me-2"></i>العودة إلى لوحة التحكم
                            </a>
                        </div>
                    @elseif($pendingRequest ?? null)
                        {{-- يوجد طلب معلق --}}
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-clock me-2"></i>
                            <strong>طلب معلق:</strong> لديك طلب توثيق هوية قيد المراجعة. يرجى انتظار مراجعة المسؤول.
                            <div class="mt-2">
                                <small class="text-muted">
                                    تاريخ الطلب: {{ $pendingRequest->created_at->format('Y-m-d H:i') }}
                                </small>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right me-2"></i>العودة إلى لوحة التحكم
                            </a>
                        </div>
                    @else
                        {{-- البريد موثق ولا يوجد طلب معلق - عرض النموذج --}}
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>ملاحظة هامة:</strong> بعد رفع صورة الهوية، سيتم مراجعتها من قبل المسؤولين والموافقة عليها أو رفضها.
                        </div>

                        <form method="POST" action="{{ route('identity-verification.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="identity_image" class="form-label">
                                    صورة الهوية الشخصية <span class="text-danger">*</span>
                                </label>
                                <input type="file"
                                       class="form-control @error('identity_image') is-invalid @enderror"
                                       id="identity_image"
                                       name="identity_image"
                                       accept="image/jpeg,image/jpg,image/png"
                                       required>
                                <div class="form-text">
                                    الصيغ المدعومة: JPEG, JPG, PNG. الحد الأقصى للحجم: 2 ميجابايت
                                </div>
                                @error('identity_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-upload me-2"></i>رفع صورة الهوية
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right me-2"></i>العودة إلى لوحة التحكم
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

