@extends('admin.layouts.admin')

@section('title', __('messages.contact_messages'))
@section('page-title', __('messages.contact_messages'))

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ __('messages.contact_messages') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ __('messages.view_contact_messages') }}
            </p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h3 class="card-title">{{ __('messages.all_contact_messages') }}</h3>
                @if($messages->count() > 0)
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('messages.showing') }} {{ $messages->firstItem() ?? 0 }} {{ __('messages.to') }} {{ $messages->lastItem() ?? 0 }} {{ __('messages.of') }} {{ $messages->total() }}
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

            @if($messages->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.subject') }}</th>
                            <th class="w-24">{{ __('messages.status') }}</th>
                            <th class="w-40">{{ __('messages.submission_date') }}</th>
                            <th class="w-40">{{ __('messages.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr class=" dark:hover:bg-gray-800/50" style="background-color: var(--color-bg-secondary);">
                                <td>{{ $message->id }}</td>
                                <td>
                                    <div class="font-medium text-gray-900 dark:text-gray-200">
                                        {{ $message->name }}
                                    </div>
                                    @if($message->user)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('messages.registered_user') }}: {{ $message->user->full_name }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="mailto:{{ $message->email }}" class="text-primary text-sm">
                                        {{ $message->email }}
                                    </a>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900 dark:text-gray-200">
                                        {{ $message->subject ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $message->status === 'replied' ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $message->status === 'replied' ? __('messages.replied') : __('messages.new') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900 dark:text-gray-200">
                                        {{ $message->created_at->format('Y-m-d') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $message->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.contact-messages.show', $message) }}"
                                           class="btn btn-info btn-sm" title="{{ __('messages.view_details_reply') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.contact-messages.destroy', $message) }}"
                                              method="POST"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟');">
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
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h4 class="empty-state-title">لا توجد رسائل</h4>
                    <p class="empty-state-description">
                        لم يتم استلام أي رسائل من نموذج الاتصال حتى الآن.
                    </p>
                </div>
            @endif
        </div>

        @if($messages->hasPages())
            <div class="card-footer">
                {{ $messages->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection

