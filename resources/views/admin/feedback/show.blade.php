@extends('admin.layouts.admin')

@section('title', 'تقييم من: ' . $feedback->name)
@section('page-title', 'تفاصيل ملاحظة المستخدم')

@section('content')
    <div class="container mx-auto px-4 py-2">
        <div class="flex items-center justify-between mb-3">
            <a href="{{ route('admin.feedback.index') }}" class="btn btn-outline inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>رجوع إلى الملاحظات</span>
            </a>
        </div>

        @if(session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Feedback Details -->
            <div class="lg:col-span-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">تفاصيل الملاحظة</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <p><strong>الاسم:</strong> {{ $feedback->name }}</p>
                        @if($feedback->user)
                            <p><strong>المستخدم:</strong>
                                <a href="{{ route('admin.users.show', $feedback->user) }}" class="text-primary">
                                    {{ $feedback->user->full_name }}
                                </a>
                            </p>
                        @endif
                        <p><strong>التقييم:</strong> {{ $feedback->rating }}/5</p>
                        <p><strong>ما أعجبه:</strong>
                            @if($feedback->likes)
                                {{ implode('، ', $feedback->likes) }}
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>التاريخ:</strong> {{ $feedback->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">نص الملاحظة</h3>
                    </div>
                    <div class="card-body">
                        <p class="whitespace-pre-line">{{ $feedback->comments }}</p>
                    </div>
                </div>
            </div>

            <!-- Reply Form -->
            <div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">إرسال رد عبر البريد</h3>
                    </div>
                    <div class="card-body">
                        @if($feedback->user && $feedback->user->email)
                            <form action="{{ route('admin.feedback.reply', $feedback) }}" method="POST">
                                @csrf
                                <div class="form-group mb-4">
                                    <label class="form-label" for="reply">محتوى الرد</label>
                                    <textarea name="reply" id="reply" rows="8"
                                              class="form-control @error('reply') is-invalid @enderror"
                                              placeholder="اكتب ردك هنا...">{{ old('reply') }}</textarea>
                                    @error('reply')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-full inline-flex items-center justify-center gap-2">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>إرسال الرد إلى {{ $feedback->user->email }}</span>
                                </button>
                            </form>
                        @else
                            <p class="text-gray-500 text-sm">
                                لا يمكن إرسال رد عبر البريد لهذا التقييم لعدم ارتباطه بحساب مستخدم أو لعدم توفر بريد إلكتروني.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
