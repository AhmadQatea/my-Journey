@extends('admin.layouts.admin')

@section('title', __('messages.site_settings'))
@section('page-title', __('messages.site_settings'))

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ __('messages.site_settings') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.view_edit_site_info') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.site.edit') }}" class="btn btn-primary inline-flex items-center gap-2">
                <i class="fas fa-edit"></i>
                <span>{{ __('messages.edit_settings') }}</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="custom-alert success mb-6 animate-slideInUp">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <i class="fas fa-check-circle text-green-500 text-xl animate-pulse"></i>
                    <span class="text-green-700 dark:text-green-300 font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" class="text-green-500 hover:text-green-700 dark:hover:text-green-400 transition-colors duration-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Settings Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- وصف مختصر عن الموقع -->
        <div class="card">
            <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                    <i class="fas fa-book"></i>
                    {{ __('messages.short_description') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">{{ __('messages.description_arabic') }}</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 min-h-[100px]">
                            {!! nl2br(e($settings->about_story ?: __('messages.no_description_added'))) !!}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.description_english') }}</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 min-h-[100px]">
                            {!! nl2br(e($settings->about_story_en ?: __('messages.no_description_added'))) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات التواصل -->
        <div class="card">
            <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                    <i class="fas fa-address-card"></i>
                    {{ __('messages.contact_info') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">{{ __('messages.email') }}</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            {{ $settings->contact_email ?: __('messages.not_specified') }}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.phone') }}</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            {{ $settings->contact_phone ?: __('messages.not_specified') }}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.address') }}</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            {{ $settings->contact_address ?: __('messages.not_specified') }}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.working_hours') }}</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            @if($settings->working_hours)
                                @if(is_array($settings->working_hours))
                                    @foreach($settings->working_hours as $day => $hours)
                                        <div>{{ $day }}: {{ $hours }}</div>
                                    @endforeach
                                @else
                                    {{ $settings->working_hours }}
                                @endif
                            @else
                                {{ __('messages.not_specified') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- روابط التواصل الاجتماعي -->
        <div class="card">
            <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                    <i class="fas fa-share-alt"></i>
                    {{ __('messages.social_media_links') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @foreach([
                        'facebook' => ['icon' => 'fab fa-facebook-f', 'label' => __('messages.facebook')],
                        'twitter' => ['icon' => 'fab fa-twitter', 'label' => __('messages.twitter')],
                        'instagram' => ['icon' => 'fab fa-instagram', 'label' => __('messages.instagram')],
                        'youtube' => ['icon' => 'fab fa-youtube', 'label' => __('messages.youtube')],
                        'linkedin' => ['icon' => 'fab fa-linkedin-in', 'label' => __('messages.linkedin')],
                        'whatsapp' => ['icon' => 'fab fa-whatsapp', 'label' => __('messages.whatsapp')],
                    ] as $key => $info)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i class="{{ $info['icon'] }} text-xl"></i>
                                <span>{{ $info['label'] }}</span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $settings->{'social_' . $key} ?: __('messages.not_specified') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- السياسات -->
        <div class="card">
            <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                    <i class="fas fa-file-contract"></i>
                    {{ __('messages.policies_terms') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">{{ __('messages.terms_conditions') }}</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 max-h-[150px] overflow-y-auto">
                            {!! $settings->terms_and_conditions ?: '<span class="text-gray-500">' . __('messages.no_terms_added') . '</span>' !!}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.privacy_policy') }}</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 max-h-[150px] overflow-y-auto">
                            {!! $settings->privacy_policy ?: '<span class="text-gray-500">' . __('messages.no_privacy_added') . '</span>' !!}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.cookie_policy') }}</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 max-h-[150px] overflow-y-auto">
                            {!! $settings->cookie_policy ?: '<span class="text-gray-500">' . __('messages.no_cookie_policy_added') . '</span>' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

