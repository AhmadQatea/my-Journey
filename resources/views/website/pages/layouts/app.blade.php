<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyJourney - منصة السفر السورية')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-site.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;600;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/website.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Dark/Light Mode Toggle -->
    <div class="theme-toggle">
        <button id="themeToggle" class="theme-btn">
            <i class="fas fa-moon"></i>
            <i class="fas fa-sun"></i>
        </button>
    </div>

    <!-- ========== HEADER ========== -->
    <header class="main-header">
        <div class="container">
            <nav class="navbar">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-compass"></i>
                    </div>
                    <div class="logo-text">
                        <h1>MyJourney</h1>
                        <span>منصة السفر السورية</span>
                    </div>
                </a>

                <!-- Main Navigation -->
                <ul class="nav-menu" id="navMenu">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>{{ __('messages.home') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                            <i class="fas fa-info-circle"></i>
                            <span>{{ __('messages.about') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('trips') }}" class="nav-link {{ request()->routeIs('trips') ? 'active' : '' }}">
                            <i class="fas fa-map-marked-alt"></i>
                            <span>{{ __('messages.trips') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('provinces') }}" class="nav-link {{ request()->routeIs('provinces') ? 'active' : '' }}">
                            <i class="fas fa-map"></i>
                            <span>{{ __('messages.provinces') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('articles') }}" class="nav-link {{ request()->routeIs('articles') ? 'active' : '' }}">
                            <i class="fas fa-newspaper"></i>
                            <span>{{ __('messages.articles') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                            <i class="fas fa-envelope"></i>
                            <span>{{ __('messages.contact') }}</span>
                        </a>
                    </li>
                </ul>

                <!-- Language Switcher & Auth Buttons -->
                <div class="header-actions" style="display: flex; align-items: center; gap: 1rem;">
                    <x-language-switcher />
                    
                    <div class="auth-buttons">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt"></i>
                                {{ __('messages.dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline">
                                <i class="fas fa-sign-in-alt"></i>
                                {{ __('messages.login') }}
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i>
                                {{ __('messages.register') }}
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- ========== MAIN CONTENT ========== -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- ========== FOOTER ========== -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Logo & Description -->
                <div class="footer-col">
                    <div class="footer-logo">
                        <i class="fas fa-compass"></i>
                        <span>MyJourney</span>
                    </div>
                    <p class="footer-desc">
                        منصة سياحية متكاملة تهدف إلى عرض جمال سوريا وتسهيل حجز الرحلات السياحية
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h3 class="footer-title">روابط سريعة</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="fas fa-chevron-left"></i> الرئيسية</a></li>
                        <li><a href="{{ route('about') }}"><i class="fas fa-chevron-left"></i> عن الموقع</a></li>
                        <li><a href="{{ route('trips') }}"><i class="fas fa-chevron-left"></i> الرحلات والعروض</a></li>
                        <li><a href="{{ route('provinces') }}"><i class="fas fa-chevron-left"></i> المحافظات</a></li>
                        <li><a href="{{ route('articles') }}"><i class="fas fa-chevron-left"></i> المقالات والتقييمات</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-left"></i> اتصل بنا</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-col">
                    <h3 class="footer-title">معلومات التواصل</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>دمشق، سوريا</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>+963 11 123 4567</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@myjourney.sy</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>الأحد - الخميس: 9 صباحاً - 5 مساءً</span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="footer-col">
                    <h3 class="footer-title">النشرة البريدية</h3>
                    <p class="newsletter-desc">اشترك لتصلك آخر العروض والرحلات</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="بريدك الإلكتروني" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            اشتراك
                        </button>
                    </form>
                </div>
            </div>

            <!-- Copyright -->
            <div class="footer-bottom">
                <p>&copy; 2024 MyJourney. جميع الحقوق محفوظة.</p>
                <div class="footer-bottom-links">
                    <a href="{{ route('legal.terms') }}">الشروط والأحكام</a>
                    <a href="{{ route('legal.privacy') }}">سياسة الخصوصية</a>
                    <a href="{{ route('legal.cookies') }}">سياسة ملفات تعريف الارتباط</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ========== SCRIPTS ========== -->
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Main Script -->
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

        // Check for saved theme or prefer color scheme
        const currentTheme = localStorage.getItem('theme') ||
                           (prefersDarkScheme.matches ? 'dark' : 'light');

        // Apply theme
        document.body.classList.toggle('dark-theme', currentTheme === 'dark');

        // Toggle theme
        themeToggle.addEventListener('click', function() {
            const isDark = document.body.classList.toggle('dark-theme');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
        });

        // Update theme icon
        function updateThemeIcon(isDark) {
            const moonIcon = themeToggle.querySelector('.fa-moon');
            const sunIcon = themeToggle.querySelector('.fa-sun');

            if (isDark) {
                moonIcon.style.display = 'none';
                sunIcon.style.display = 'block';
            } else {
                moonIcon.style.display = 'block';
                sunIcon.style.display = 'none';
            }
        }

        // Initialize theme icon
        updateThemeIcon(currentTheme === 'dark');

        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!navMenu.contains(event.target) && !menuToggle.contains(event.target)) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
            }
        });

        // Close mobile menu on resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
            }
        });

        // Initialize Swipers
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any swiper with class 'swiper'
            document.querySelectorAll('.swiper').forEach(swiperEl => {
                if (!swiperEl.swiper) {
                    new Swiper(swiperEl, {
                        direction: 'horizontal',
                        loop: true,
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                        breakpoints: {
                            320: {
                                slidesPerView: 1,
                                spaceBetween: 10
                            },
                            768: {
                                slidesPerView: 2,
                                spaceBetween: 20
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 30
                            }
                        }
                    });
                }
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add fade-in animation to elements
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);

        // Observe all elements with animation class
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
    </script>

    <!-- Legal Content Modal Script (Optional - for modal display) -->
    <script>
        // دالة لعرض المحتوى القانوني في modal
        function showLegalContent(type) {
            const typeMap = {
                'terms': 'الشروط والأحكام',
                'privacy': 'سياسة الخصوصية',
                'cookies': 'سياسة ملفات تعريف الارتباط'
            };

            // جلب المحتوى من API
            fetch(`/api/legal/${type}`)
                .then(response => response.json())
                .then(data => {
                    // إنشاء modal
                    const modal = document.createElement('div');
                    modal.className = 'legal-modal';
                    modal.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.7);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                        padding: 20px;
                    `;

                    modal.innerHTML = `
                        <div style="
                            background: white;
                            border-radius: 12px;
                            max-width: 800px;
                            width: 100%;
                            max-height: 90vh;
                            overflow-y: auto;
                            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                        ">
                            <div style="
                                padding: 24px;
                                border-bottom: 1px solid #e5e7eb;
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                            ">
                                <h2 style="margin: 0; font-size: 24px; font-weight: bold;">${data.title}</h2>
                                <button onclick="this.closest('.legal-modal').remove()" style="
                                    background: none;
                                    border: none;
                                    font-size: 24px;
                                    cursor: pointer;
                                    color: #6b7280;
                                    padding: 0;
                                    width: 32px;
                                    height: 32px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                ">&times;</button>
                            </div>
                            <div style="padding: 24px; line-height: 1.8; color: #374151;">
                                ${data.content ? data.content.replace(/\n/g, '<br>') : '<p style="text-align: center; color: #9ca3af;">لم يتم إضافة المحتوى بعد.</p>'}
                            </div>
                        </div>
                    `;

                    document.body.appendChild(modal);

                    // إغلاق عند الضغط خارج المحتوى
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            modal.remove();
                        }
                    });

                    // إغلاق عند الضغط على ESC
                    const escHandler = function(e) {
                        if (e.key === 'Escape') {
                            modal.remove();
                            document.removeEventListener('keydown', escHandler);
                        }
                    };
                    document.addEventListener('keydown', escHandler);
                })
                .catch(error => {
                    console.error('Error loading legal content:', error);
                    alert('حدث خطأ أثناء تحميل المحتوى. يرجى المحاولة مرة أخرى.');
                });
        }

        // ربط الأزرار في footer (اختياري - يمكن استخدامه بدلاً من الروابط العادية)
        document.addEventListener('DOMContentLoaded', function() {
            // يمكنك إضافة data-modal="true" للروابط التي تريد فتحها في modal
            const footerLinks = document.querySelectorAll('.footer-bottom-links a[data-modal="true"]');
            footerLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    const type = href.includes('terms') ? 'terms' :
                                href.includes('privacy') ? 'privacy' :
                                href.includes('cookies') ? 'cookies' : null;
                    if (type) {
                        showLegalContent(type);
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
