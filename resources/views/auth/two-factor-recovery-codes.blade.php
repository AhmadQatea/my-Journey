<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fab fa-google text-success me-2"></i>
            الربط مع جوجل
        </h5>
    </div>
    <div class="card-body">
        @if(Auth::user()->google_id)
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                حسابك مرتبط بحساب جوجل
                <div class="mt-2">
                    <form method="POST" action="{{ route('google.unlink') }}"
                          onsubmit="return confirm('هل أنت متأكد من فك ارتباط حساب جوجل؟')">
                        @csrf
                        <div class="input-group">
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="كلمة المرور للتأكيد"
                                   required>
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-unlink me-2"></i>فك الارتباط
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                حسابك غير مرتبط بحساب جوجل
            </div>

            <div class="d-grid gap-3">
                <a href="{{ route('login.google') }}" class="btn btn-outline-success">
                    <i class="fab fa-google me-2"></i>ربط بحساب جوجل
                </a>

                <small class="text-muted">
                    الربط مع جوجل يتيح لك تسجيل الدخول بسرعة دون الحاجة لتذكر كلمة المرور
                </small>
            </div>
        @endif
    </div>
</div>
