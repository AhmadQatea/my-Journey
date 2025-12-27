@props([
    'title',
    'pageTitle' => null,
    'action',
    'model',
    'backRoute',
    'backText' => 'رجوع للقائمة',
    'submitText' => 'تحديث',
    'submitIcon' => 'fas fa-save',
    'enctype' => false,
    'layout' => 'default', // 'default', 'grid', 'card'
    'gridCols' => 'lg:grid-cols-3',
    'mainColSpan' => 'lg:col-span-2',
])

@extends('admin.layouts.admin')

@section('title', $title)
@section('page-title', $pageTitle ?? $title)

@section('content')
@if($layout === 'card')
    <x-card :title="$title">
        <form action="{{ $action }}" method="POST" @if($enctype) enctype="multipart/form-data" @endif>
            @csrf
            @method('PUT')

            {{ $slot }}

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ $backRoute }}" class="btn btn-secondary">{{ $backText }}</a>
                <button type="submit" class="btn btn-primary">
                    <i class="{{ $submitIcon }}"></i> {{ $submitText }}
                </button>
            </div>
        </form>
    </x-card>
@else
    <div class="container mx-auto px-4 py-4">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ $pageTitle ?? $title }}</h1>
                @if(isset($model) && $model)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        @if(isset($model->title))
                            {{ Str::limit($model->title, 50) }}
                        @elseif(isset($model->name))
                            {{ Str::limit($model->name, 50) }}
                        @endif
                    </p>
                @endif
            </div>
            <a href="{{ $backRoute }}" class="btn btn-outline inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>{{ $backText }}</span>
            </a>
        </div>

        <form id="site-settings-form" action="{{ $action }}" method="POST" @if($enctype) enctype="multipart/form-data" @endif>
            @csrf
            @method('PUT')

            @if($layout === 'grid')
                <div class="grid grid-cols-1 {{ $gridCols }} gap-6">
                    <div class="{{ $mainColSpan }} space-y-6">
                        {{ $slot }}
                    </div>
                    @isset($sidebar)
                        <div class="space-y-6">
                            {{ $sidebar }}
                        </div>
                    @endisset
                </div>
            @else
                {{ $slot }}
            @endif

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ $backRoute }}" class="btn btn-secondary">{{ $backText }}</a>
                <button type="submit" class="btn btn-primary">
                    <i class="{{ $submitIcon }}"></i> {{ $submitText }}
                </button>
            </div>
        </form>
    </div>
@endif
@endsection

