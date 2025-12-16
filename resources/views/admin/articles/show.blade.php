{{-- resources/views/admin/articles/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'تفاصيل المقال: ' . $article->title)
@section('page-title', 'تفاصيل المقال: ' . $article->title)

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ $article->title }}</h1>
            <div class="flex items-center gap-3 mt-2 flex-wrap">
                @php
                    $statusColors = [
                        'معلقة' => 'badge-warning',
                        'منشورة' => 'badge-success',
                        'مرفوضة' => 'badge-danger',
                    ];
                @endphp
                <span class="badge {{ $statusColors[$article->status] ?? 'badge-secondary' }}">
                    {{ $article->status }}
                </span>
                @if($article->rating)
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $article->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.articles.edit', $article) }}"
               class="btn btn-warning inline-flex items-center gap-2">
                <i class="fas fa-edit"></i>
                <span>تعديل</span>
            </a>
            <a href="{{ route('admin.articles.index') }}"
               class="btn btn-outline inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>رجوع للقائمة</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Article Images -->
            @if($article->images && count($article->images) > 0)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">صور المقال</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($article->images as $index => $image)
                        <div class="relative group">
                            <img src="{{ Storage::url($image) }}"
                                 alt="صورة المقال {{ $index + 1 }}"
                                 class="w-full h-48 object-cover rounded-lg cursor-pointer"
                                 onclick="openImageModal('{{ Storage::url($image) }}')">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                <button type="button"
                                        onclick="openImageModal('{{ Storage::url($image) }}')"
                                        class="btn btn-primary btn-sm">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Article Excerpt -->
            @if($article->excerpt)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">الملخص</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $article->excerpt }}</p>
                </div>
            </div>
            @endif

            <!-- Article Content -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">محتوى المقال</h3>
                    <div class="prose max-w-none text-gray-700 dark:text-gray-300">
                        {!! $article->content !!}
                    </div>
                </div>
            </div>

            <!-- Trip Information -->
            @if($article->trip)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات الرحلة المرتبطة</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">عنوان الرحلة</p>
                            <a href="{{ route('admin.trips.show', $article->trip) }}" class="font-medium text-primary hover:underline">
                                {{ $article->trip->title }}
                            </a>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">المحافظة</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $article->trip->governorate->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Rejection Reason -->
            @if($article->status === 'مرفوضة' && $article->rejection_reason)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">سبب الرفض</h3>
                    <p class="text-red-600 dark:text-red-400 whitespace-pre-line">{{ $article->rejection_reason }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Article Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">معلومات المقال</h3>
                </div>
                <div class="card-body space-y-4">
                    @if($article->user)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">المؤلف:</span>
                        <span class="font-medium text-gray-900 dark:text-gray">
                            {{ $article->user->full_name }}
                        </span>
                    </div>
                    @elseif($article->created_by_admin && $article->adminCreator)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">المنشئ:</span>
                        <span class="font-medium text-gray-900 dark:text-gray">
                            {{ $article->adminCreator->name }} (مسؤول)
                        </span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">المشاهدات:</span>
                        <span class="font-medium text-gray-900 dark:text-gray">{{ number_format($article->views_count ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">تاريخ الإنشاء:</span>
                        <span class="font-medium text-gray-900 dark:text-gray">{{ $article->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">آخر تحديث:</span>
                        <span class="font-medium text-gray-900 dark:text-gray">{{ $article->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if($article->created_by_admin && $article->adminCreator)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">تم الإنشاء من قبل:</span>
                        <span class="font-medium text-gray-900 dark:text-gray">{{ $article->adminCreator->name }}</span>
                    </div>
                    @endif
                    @if($article->status === 'منشورة' && $article->adminConfirmer)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">تم التأكيد من قبل:</span>
                        <span class="font-medium text-gray-900 dark:text-gray">{{ $article->adminConfirmer->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Change -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تغيير الحالة</h3>
                </div>
                <div class="card-body space-y-3">
                    @if($article->status === 'معلقة')
                    <form action="{{ route('admin.articles.approve', $article) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-full">
                            <i class="fas fa-check ml-1"></i>
                            الموافقة على المقال
                        </button>
                    </form>

                    <form action="{{ route('admin.articles.reject', $article) }}" method="POST" id="rejectForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label">سبب الرفض</label>
                            <textarea name="rejection_reason"
                                      class="form-control"
                                      rows="3"
                                      placeholder="أدخل سبب رفض المقال..."
                                      required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-full">
                            <i class="fas fa-times ml-1"></i>
                            رفض المقال
                        </button>
                    </form>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle ml-1"></i>
                        <p class="text-sm">المقال {{ $article->status }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">الإجراءات</h3>
                </div>
                <div class="card-body space-y-3">
                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning w-full">
                        <i class="fas fa-edit ml-1"></i>
                        تعديل المقال
                    </a>
                    <button type="button" class="btn btn-danger w-full" onclick="confirmDelete({{ $article->id }})">
                        <i class="fas fa-trash ml-1"></i>
                        حذف المقال
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal hidden">
    <div class="modal-content max-w-4xl">
        <div class="modal-header">
            <h3 class="modal-title">صورة المقال</h3>
            <button type="button" class="modal-close" data-modal-hide="imageModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body p-0">
            <img id="modalImage" src="" alt="صورة المقال" class="w-full h-auto">
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal hidden">
    <div class="modal-content max-w-sm">
        <div class="modal-header">
            <h3 class="modal-title">تأكيد الحذف</h3>
            <button type="button" class="modal-close" data-modal-hide="deleteModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>هل أنت متأكد أنك تريد حذف هذا المقال؟ لا يمكن التراجع عن هذا الإجراء.</p>
        </div>
        <div class="modal-footer flex justify-end gap-3">
            <button type="button" class="btn btn-outline" data-modal-hide="deleteModal">إلغاء</button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">حذف</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
    }

    document.querySelectorAll('[data-modal-hide="imageModal"]').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
        });
    });

    function confirmDelete(articleId) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('admin/articles') }}/${articleId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    document.querySelectorAll('[data-modal-hide="deleteModal"]').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        });
    });
</script>
@endpush
