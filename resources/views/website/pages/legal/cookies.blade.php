@extends('website.pages.layouts.app')

@section('title', 'سياسة ملفات تعريف الارتباط - MyJourney')

@section('content')
    <!-- ========== LEGAL HERO ========== -->
    <section class="hero-section" style="background: var(--gradient-purple);">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">سياسة ملفات تعريف الارتباط</h1>
                <p class="hero-subtitle">
                    سياسة استخدام ملفات تعريف الارتباط (Cookies) في MyJourney
                </p>
            </div>
        </div>
    </section>

    <!-- ========== LEGAL CONTENT ========== -->
    <section class="section">
        <div class="container">
            <div class="max-w-4xl mx-auto">
                <div class="fade-in">
                    @if($content)
                        <div class="legal-content" style="line-height: 1.8; color: var(--gray-700); font-size: 1.05rem;">
                            {!! nl2br(e($content)) !!}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-cookie text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600 text-lg">لم يتم إضافة محتوى سياسة ملفات تعريف الارتباط بعد.</p>
                            <p class="text-gray-500 text-sm mt-2">يرجى مراجعة المسؤول لإضافة المحتوى.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

