@extends('admin.layouts.admin')

@section('title', __('messages.user_feedback'))
@section('page-title', __('messages.user_feedback_ratings'))

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ __('messages.user_feedback_ratings') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ __('messages.view_user_feedback') }}
            </p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h3 class="card-title">{{ __('messages.all_feedback') }}</h3>
                @if($feedback->count() > 0)
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('messages.showing') }} {{ $feedback->firstItem() ?? 0 }} {{ __('messages.to') }} {{ $feedback->lastItem() ?? 0 }} {{ __('messages.of') }} {{ $feedback->total() }}
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            @if(session('status'))
                <div class="p-4">
                    <div class="custom-alert success mb-4">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span class="text-green-700 dark:text-green-300 font-bold">
                                {{ session('status') }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            @if($feedback->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>{{ __('messages.name') }}</th>
                            <th class="w-24">{{ __('messages.rating') }}</th>
                            <th>{{ __('messages.what_they_liked') }}</th>
                            <th class="w-40">{{ __('messages.submission_date') }}</th>
                            <th class="w-40">{{ __('messages.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($feedback as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td>{{ $item->id }}</td>
                                <td>
                                    <div class="font-medium text-gray-900 dark:text-gray-200">
                                        {{ $item->name }}
                                    </div>
                                    @if($item->user)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('messages.registered_user') }}: {{ $item->user->full_name }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        {{ $item->rating }}/5
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900 dark:text-gray-200">
                                        @if($item->likes)
                                            {{ implode('، ', $item->likes) }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900 dark:text-gray-200">
                                        {{ $item->created_at->format('Y-m-d') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.feedback.show', $item) }}"
                                           class="btn btn-info btn-sm" title="{{ __('messages.view_details_reply') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.feedback.destroy', $item) }}" method="POST"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <h4 class="empty-state-title">لا توجد ملاحظات</h4>
                    <p class="empty-state-description">
                        لم يقم أي مستخدم بإرسال ملاحظات حتى الآن.
                    </p>
                </div>
            @endif
        </div>

        @if($feedback->hasPages())
            <div class="card-footer">
                {{ $feedback->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection

