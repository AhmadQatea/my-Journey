@extends('website.pages.layouts.app')

@section('title', 'عن الموقع - MyJourney')

@section('content')
    <!-- ========== ABOUT HERO ========== -->
    <section class="hero-section" style="background: var(--gradient-purple);">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">عن MyJourney</h1>
                <p class="hero-subtitle">
                    منصة سياحية رائدة تهدف إلى إعادة إحياء السياحة في سوريا
                </p>
            </div>
        </div>
    </section>

    <!-- ========== ABOUT CONTENT ========== -->
    <section class="section">
        <div class="container">
            <div class="grid grid-2 align-items-center gap-4">
                <div class="fade-in">
                    <h2 class="section-title">{{ __('messages.about_us') }}</h2>
                    @php
                        $locale = app()->getLocale();
                        if ($locale === 'en') {
                            $aboutText = $siteSettings->about_story_en ?? null;
                            // إذا لم يكن هناك نص إنجليزي، استخدم النص الافتراضي
                            if (!$aboutText) {
                                $aboutText = 'MyJourney is an electronic tourism platform established to facilitate the booking and exploration of tourist trips in Syria. We believe that Syria possesses a huge tourism wealth that deserves to be discovered by the world. We strive to make our platform the first window for local and international tourists to discover the beauty of Syria by providing an easy and safe booking experience, with accurate and detailed displays of the best tourist places in each governorate.';
                            }
                        } else {
                            $aboutText = $siteSettings->about_story ?? null;
                            // إذا لم يكن هناك نص عربي، استخدم النص الافتراضي
                            if (!$aboutText) {
                                $aboutText = 'MyJourney هي منصة سياحية إلكترونية تأسست بهدف تسهيل عملية حجز واستكشاف الرحلات السياحية في سوريا. نحن نؤمن بأن سوريا تمتلك ثروة سياحية هائلة تستحق أن تكتشف من قبل العالم. نسعى ليكون موقعنا النافذة الأولى للسياح المحليين والدوليين لاكتشاف جمال سوريا من خلال تقديم تجربة حجز سهلة وآمنة، مع عرض دقيق ومفصل لأفضل الأماكن السياحية في كل محافظة.';
                            }
                        }
                    @endphp
                    <div style="line-height: 1.8; color: var(--gray-700); font-size: 1.05rem;">
                        {!! nl2br(e($aboutText)) !!}
                    </div>
                </div>
                <div class="fade-in">
                    <img src="{{ asset('assets/images/damascus.jpg') }}"
                         alt="عن MyJourney"
                         style="width: 100%; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl);">
                </div>
            </div>
        </div>
    </section>

    <!-- ========== OUR MISSION ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">رسالتنا وأهدافنا</h2>
                <p class="section-subtitle">نسعى لتحقيق رؤية سياحية متكاملة في سوريا</p>
            </div>

            <div class="grid grid-3">
                <div class="card fade-in">
                    <div class="card-body text-center">
                        <div class="icon-box" style="width: 80px; height: 80px; background: rgba(67, 97, 238, 0.1); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary); font-size: 2rem;">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3>اكتشاف الأماكن</h3>
                        <p>عرض تفاصيل دقيقة للأماكن السياحية في جميع المحافظات السورية</p>
                    </div>
                </div>

                <div class="card fade-in">
                    <div class="card-body text-center">
                        <div class="icon-box" style="width: 80px; height: 80px; background: rgba(157, 78, 221, 0.1); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary); font-size: 2rem;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>حجز آمن</h3>
                        <p>توفير نظام حجز آمن وموثوق مع توثيق الهوية للمسافرين</p>
                    </div>
                </div>

                <div class="card fade-in">
                    <div class="card-body text-center">
                        <div class="icon-box" style="width: 80px; height: 80px; background: rgba(76, 201, 240, 0.1); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary); font-size: 2rem;">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3>مجتمع تفاعلي</h3>
                        <p>بناء مجتمع من المسافرين لمشاركة التجارب والتقييمات</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== OUR TEAM ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">فريق العمل</h2>
                <p class="section-subtitle">فريق متخصص يعمل لخدمتكم على مدار الساعة</p>
            </div>

            <!-- Team Slider -->
            <div class="swiper team-slider">
                <div class="swiper-wrapper">
                    @forelse($admins as $admin)
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img src="{{ asset('assets/images/person.png') }}"
                                         alt="المدير {{ $admin->name }}"
                                         style="width: 120px; height: 120px; border-radius: var(--radius-full); object-fit: cover; margin: 0 auto 1.5rem; border: 4px solid var(--gray-200);">
                                    <h3>{{ $admin->name }}</h3>
                                    <p class="text-muted" style="color: var(--gray-500); margin-bottom: 1rem;">
                                        {{ $admin->role?->name ?? 'مسؤول الموقع' }}
                                    </p>
                                    <p>أحد المسؤولين عن إدارة وتشغيل منصة MyJourney.</p>
                                    <div class="social-links" style="display: flex; gap: 0.5rem; justify-content: center; margin-top: 1rem;">
                                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img src="{{ asset('assets/images/person.png') }}"
                                         alt="عضو الفريق"
                                         style="width: 120px; height: 120px; border-radius: var(--radius-full); object-fit: cover; margin: 0 auto 1.5rem; border: 4px solid var(--gray-200);">
                                    <h3>فريق الإدارة</h3>
                                    <p class="text-muted" style="color: var(--gray-500); margin-bottom: 1rem;">
                                        مسؤول الموقع
                                    </p>
                                    <p>يتم حالياً إعداد بيانات فريق إدارة المنصة.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- ========== TIMELINE ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">رحلة تطورنا</h2>
                <p class="section-subtitle">خطواتنا نحو التميز في مجال السياحة الإلكترونية</p>
            </div>

            <div class="timeline">
                @php
                    $milestones = [
                        ['year' => '2022', 'title' => 'تأسيس الفكرة', 'description' => 'بداية التخطيط للمشروع وتحديد الأهداف'],
                        ['year' => '2023', 'title' => 'التطوير التقني', 'description' => 'بناء النظام التقني وتصميم الواجهات'],
                        ['year' => '2024', 'title' => 'الإطلاق الرسمي', 'description' => 'إطلاق المنصة رسمياً للجمهور'],
                        ['year' => '2025', 'title' => 'التوسع', 'description' => 'إضافة المزيد من المحافظات والخدمات']
                    ];
                @endphp

                @foreach($milestones as $milestone)
                    <div class="timeline-item fade-in" style="position: relative; padding-right: 2rem; margin-bottom: 2rem; border-right: 2px solid var(--primary);">
                        <div class="timeline-year" style="position: absolute; right: -15px; top: 0; background: var(--primary); color: white; width: 30px; height: 30px; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            {{ $milestone['year'] }}
                        </div>
                        <div style="padding: 1rem; background: var(--gray-100); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); margin-right: 1rem;">
                            <h3 style="color: var(--primary); margin-bottom: 0.5rem;">{{ $milestone['title'] }}</h3>
                            <p>{{ $milestone['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========== PARTNERS ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">شركاؤنا</h2>
                <p class="section-subtitle">نعمل مع أفضل الشركات السياحية في سوريا</p>
            </div>

            <div class="grid grid-4">
                @for($i = 1; $i <= 8; $i++)
                    <div class="partner-logo fade-in" style="background: var(--gray-100); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: center;">
                        <span style="font-weight: bold; color: var(--gray-700);">شريك {{ $i }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </section>
@endsection
