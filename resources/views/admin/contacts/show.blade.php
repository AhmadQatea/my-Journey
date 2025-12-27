@extends('admin.layouts.admin')

@section('title', 'رسالة من: ' . $message->name)
@section('page-title', 'تفاصيل رسالة المستخدم')

@section('content')
    <div class="container mx-auto px-4 py-2">
        <div class="flex items-center justify-between mb-3">
            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>رجوع إلى الرسائل</span>
            </a>
        </div>

        @if(session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Message Details -->
            <div class="lg:col-span-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">تفاصيل الرسالة</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <p><strong>الاسم:</strong> {{ $message->name }}</p>
                        <p><strong>البريد الإلكتروني:</strong>
                            <a href="mailto:{{ $message->email }}" class="text-primary">
                                {{ $message->email }}
                            </a>
                        </p>
                        @if($message->user)
                            <p><strong>المستخدم:</strong>
                                <a href="{{ route('admin.users.show', $message->user) }}" class="text-primary">
                                    {{ $message->user->full_name }}
                                </a>
                            </p>
                        @endif
                        <p><strong>الموضوع:</strong> {{ $message->subject ?? '-' }}</p>
                        <p><strong>التصنيف:</strong> {{ $message->topic ?? '-' }}</p>
                        <p><strong>الحالة:</strong>
                            <span class="badge badge-{{ $message->status === 'replied' ? 'success' : 'secondary' }}">
                                {{ $message->status === 'replied' ? 'تم الرد' : 'جديد' }}
                            </span>
                        </p>
                        <p><strong>التاريخ:</strong> {{ $message->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">نص الرسالة</h3>
                    </div>
                    <div class="card-body">
                        <p class="whitespace-pre-line">{{ $message->message }}</p>
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
                        <form action="{{ route('admin.contact-messages.reply', $message) }}" method="POST">
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
                                <span>إرسال الرد إلى {{ $message->email }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
