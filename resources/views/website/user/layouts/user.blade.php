<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyJourney')</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;600;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-user.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-site.png') }}">

    @stack('styles')
</head>
<body>
    <!-- ========== HEADER ========== -->
    <header class="dashboard-header">
        <nav class="navbar">
            <div class="nav-container">
                <!-- Menu Toggle -->
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Brand Logo -->
                <div class="brand-logo">
                    <div class="logo-icon">
                        <i class="fas fa-compass"></i>
                    </div>
                    <div class="logo-text">
                        <h1 class="logo-title">MyJourney</h1>
                        <span class="logo-subtitle">منصة السفر المميزة</span>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="main-nav" id="mainNav">
                    <ul class="nav-menu">
                        <li class="nav-menu-item" data-tooltip="الرئيسية">
                            <a href="{{ route('dashboard') }}" class="nav-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                <span class="nav-menu-text">الرئيسية</span>
                            </a>
                        </li>
                        <li class="nav-menu-item" data-tooltip="الملف الشخصي">
                            <a href="{{ route('profile.show') }}" class="nav-menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <i class="fas fa-user-circle"></i>
                                <span class="nav-menu-text">الملف الشخصي</span>
                            </a>
                        </li>
                        <li class="nav-menu-item" data-tooltip="الحجوزات">
                            <a href="{{ route('my-bookings') }}" class="nav-menu-link {{ request()->routeIs('my-bookings') || request()->routeIs('bookings.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                                <span class="nav-menu-text">الحجوزات</span>
                                @php
                                    $activeBookingsCount = \App\Models\Booking::where('user_id', Auth::id())->where('status', 'مؤكدة')->count();
                                @endphp
                                @if($activeBookingsCount > 0)
                                    <span class="nav-menu-badge">{{ $activeBookingsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-menu-item" data-tooltip="المقالات">
                            <a href="{{ route('my-articles') }}" class="nav-menu-link {{ request()->routeIs('my-articles') || request()->routeIs('articles.*') ? 'active' : '' }}">
                                <i class="fas fa-newspaper"></i>
                                <span class="nav-menu-text">المقالات</span>
                                @php
                                    $publishedArticlesCount = \App\Models\Article::where('user_id', Auth::id())->where('status', 'منشورة')->count();
                                @endphp
                                @if($publishedArticlesCount > 0)
                                    <span class="nav-menu-badge">{{ $publishedArticlesCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Actions with Dropdown -->
                <div class="user-actions">
                    <!-- Notification Bell -->
                    @include('notifications.dropdown')

                    <!-- User Dropdown -->
                    <div class="user-dropdown">
                        <button class="user-dropdown-btn" id="userDropdownBtn">
                            <div class="user-info">
                                <div class="user-avatar">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->full_name }}">
                                    @else
                                        <i class="fas fa-user-circle"></i>
                                    @endif
                                </div>
                                <div class="user-details">
                                    <span class="user-name">{{ Auth::user()->full_name }}</span>
                                    <span class="user-status active">نشط الآن</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </div>
                        </button>

                        <div class="user-dropdown-content" id="userDropdownContent">
                            <!-- معلومات المستخدم -->
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-avatar-container">
                                    <div class="dropdown-user-avatar">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->full_name }}">
                                        @else
                                            <i class="fas fa-user-circle"></i>
                                        @endif
                                    </div>
                                    <div class="dropdown-user-status">
                                        <i class="fas fa-circle"></i> نشط الآن
                                    </div>
                                </div>
                                <div class="dropdown-user-details">
                                    <h4>{{ Auth::user()->full_name }}</h4>
                                    <p>{{ Auth::user()->email }}</p>
                                    <div class="user-badges">
                                        @if(Auth::user()->google_id)
                                            <span class="google-badge">
                                                <i class="fab fa-google"></i> مرتبط بجوجل
                                            </span>
                                        @endif
                                        @if(Auth::user()->two_factor_confirmed_at)
                                            <span class="twofa-badge">
                                                <i class="fas fa-shield-alt"></i> 2FA مفعل
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- ظل في الأعلى -->
                            <div class="dropdown-scroll-shadow top"></div>

                            <!-- المحتوى القابل للتمرير -->
                            <div class="dropdown-content-scrollable">
                                <div class="dropdown-divider"></div>

                                <!-- عناصر القائمة -->
                                <a href="{{ route('profile.show') }}" class="dropdown-item">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span>الملف الشخصي</span>
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <span>إعدادات الحساب</span>
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <!-- Security Settings -->
                                <div class="dropdown-subheader">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>الأمان</span>
                                </div>

                                <a href="{{ route('two-factor.setup') }}" class="dropdown-item">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <span>المصادقة الثنائية (2FA)</span>
                                    @if(Auth::user()->two_factor_confirmed_at)
                                        <span class="dropdown-badge active">مفعل</span>
                                    @else
                                        <span class="dropdown-badge inactive">غير مفعل</span>
                                    @endif
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <a href="{{ route('password.change') }}" class="dropdown-item">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <span>تغيير كلمة المرور</span>
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <a href="{{ route('identity-verification.create') }}" class="dropdown-item" onclick="event.stopPropagation();">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <span>توثيق الهوية</span>
                                    @if(Auth::user()->identity_verified)
                                        <span class="dropdown-badge active">موثق</span>
                                    @else
                                        <span class="dropdown-badge inactive">غير موثق</span>
                                    @endif
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <!-- Google Login -->
                                <div class="dropdown-subheader">
                                    <i class="fab fa-google"></i>
                                    <span>التسجيل عبر جوجل</span>
                                </div>

                                @if(Auth::user()->google_id)
                                    <form method="POST" action="{{ route('google.unlink') }}" class="dropdown-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <div class="dropdown-icon">
                                                <i class="fas fa-unlink"></i>
                                            </div>
                                            <span>فك ارتباط جوجل</span>
                                            <i class="fas fa-chevron-left arrow"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login.google') }}" class="dropdown-item">
                                        <div class="dropdown-icon">
                                            <i class="fab fa-google"></i>
                                        </div>
                                        <span>ربط حساب جوجل</span>
                                        <i class="fas fa-chevron-left arrow"></i>
                                    </a>
                                @endif

                                <!-- Backup Codes -->
                                @if(Auth::user()->two_factor_confirmed_at)
                                    <div class="dropdown-subheader">
                                        <i class="fas fa-file-alt"></i>
                                        <span>أكواد الاسترجاع</span>
                                    </div>

                                    <a href="{{ route('two-factor.recovery-codes.show') }}" class="dropdown-item">
                                        <div class="dropdown-icon">
                                            <i class="fas fa-key"></i>
                                        </div>
                                        <span>عرض أكواد الاسترجاع</span>
                                        <i class="fas fa-chevron-left arrow"></i>
                                    </a>

                                    <form method="POST" action="{{ route('two-factor.generate-recovery-codes') }}" class="dropdown-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <div class="dropdown-icon">
                                                <i class="fas fa-redo"></i>
                                            </div>
                                            <span>إنشاء أكواد جديدة</span>
                                            <i class="fas fa-chevron-left arrow"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- ظل في الأسفل -->
                            <div class="dropdown-scroll-shadow bottom"></div>

                            <!-- الفوتر (العناصر الثابتة) -->
                            <div class="dropdown-footer">
                                <div class="dropdown-divider"></div>

                                <!-- عناصر الفوتر -->
                                <a href="{{ route('home') }}" target="_blank" class="dropdown-item">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-external-link-alt"></i>
                                    </div>
                                    <span>زيارة الموقع</span>
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout">
                                        <div class="dropdown-icon">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <span>تسجيل الخروج</span>
                                        <i class="fas fa-chevron-left arrow"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- ========== MAIN LAYOUT ========== -->
    <div class="dashboard-layout">
        <!-- ========== MAIN CONTENT ========== -->
        <main class="main-content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 1rem; border-radius: 0.5rem;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 1rem; border-radius: 0.5rem;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert" style="margin: 1rem; border-radius: 0.5rem;">
                    <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- ========== SCRIPTS ========== -->
    <script>
        // Compact Menu Toggle
        const navMenu = document.querySelector('.nav-menu');
        const compactMenuKey = 'nav-menu-compact';

        // تحميل حالة القائمة من localStorage (افتراضياً مدمجة)
        const isCompact = localStorage.getItem(compactMenuKey) !== 'false'; // افتراضياً true
        if (isCompact) {
            navMenu.classList.add('compact');
        }

        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const mainNav = document.getElementById('mainNav');
        const body = document.body;

        menuToggle.addEventListener('click', () => {
            mainNav.classList.toggle('active');
            body.classList.toggle('nav-active');

            // Update toggle icon
            const icon = menuToggle.querySelector('i');
            if (mainNav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // User Dropdown
        const userDropdownBtn = document.getElementById('userDropdownBtn');
        const userDropdownContent = document.getElementById('userDropdownContent');
        const notificationBtn = document.getElementById('notificationBtn');

        userDropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdownContent.classList.toggle('show');

            // Close notification dropdown if open
            if (notificationBtn && notificationBtn.nextElementSibling) {
                notificationBtn.nextElementSibling.classList.remove('show');
            }
        });

        // Notification Dropdown
        if (notificationBtn) {
            notificationBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const notificationDropdown = notificationBtn.nextElementSibling;
                notificationDropdown.classList.toggle('show');

                // Close user dropdown if open
                userDropdownContent.classList.remove('show');
            });
        }

        // Close dropdowns when clicking outside (but allow links to work)
        document.addEventListener('click', (e) => {
            // Allow dropdown-item links to work before closing
            if (e.target.closest('.dropdown-item')) {
                // Let the link navigate normally
                return;
            }

            if (!userDropdownBtn.contains(e.target) && !userDropdownContent.contains(e.target)) {
                userDropdownContent.classList.remove('show');
            }

            if (notificationBtn && !notificationBtn.contains(e.target) && !notificationBtn.nextElementSibling.contains(e.target)) {
                notificationBtn.nextElementSibling.classList.remove('show');
            }

            // Close mobile menu
            if (window.innerWidth <= 1024) {
                if (!mainNav.contains(e.target) &&
                    !menuToggle.contains(e.target) &&
                    mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                    body.classList.remove('nav-active');
                    const icon = menuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });

        // Close dropdowns on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                userDropdownContent.classList.remove('show');
                if (notificationBtn && notificationBtn.nextElementSibling) {
                    notificationBtn.nextElementSibling.classList.remove('show');
                }

                if (mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                    body.classList.remove('nav-active');
                    const icon = menuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });

        // Handle window resize
        function handleResize() {
            if (window.innerWidth > 1024) {
                mainNav.classList.remove('active');
                body.classList.remove('nav-active');
                const icon = menuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }

            // Close dropdowns on mobile when resizing to desktop
            if (window.innerWidth > 768) {
                userDropdownContent.classList.remove('show');
                if (notificationBtn && notificationBtn.nextElementSibling) {
                    notificationBtn.nextElementSibling.classList.remove('show');
                }
            }
        }

        window.addEventListener('resize', handleResize);

        // Toggle compact menu - يمكن التبديل بالنقر المزدوج على أي عنصر في القائمة
        navMenu.addEventListener('dblclick', (e) => {
            if (e.target.closest('.nav-menu-link')) {
                navMenu.classList.toggle('compact');
                localStorage.setItem(compactMenuKey, navMenu.classList.contains('compact'));
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

