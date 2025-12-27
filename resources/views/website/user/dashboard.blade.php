<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.user_dashboard') }} - MyJourney</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;600;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-user.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-site.png') }}">
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
                        <span class="logo-subtitle">{{ __('messages.travel_platform') }}</span>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="main-nav" id="mainNav">
                    <ul class="nav-menu">
                        <li class="nav-menu-item" data-tooltip="{{ __('messages.home') }}">
                            <a href="{{ route('dashboard') }}" class="nav-menu-link active">
                                <i class="fas fa-home"></i>
                                <span class="nav-menu-text">{{ __('messages.home') }}</span>
                            </a>
                        </li>
                        <li class="nav-menu-item" data-tooltip="{{ __('messages.profile') }}">
                            <a href="{{ route('profile.show') }}" class="nav-menu-link">
                                <i class="fas fa-user-circle"></i>
                                <span class="nav-menu-text">{{ __('messages.profile') }}</span>
                            </a>
                        </li>
                        <li class="nav-menu-item" data-tooltip="{{ __('messages.bookings') }}">
                            <a href="{{ route('my-bookings') }}" class="nav-menu-link">
                                <i class="fas fa-calendar-check"></i>
                                <span class="nav-menu-text">{{ __('messages.bookings') }}</span>
                                @if($activeBookingsCount > 0)
                                    <span class="nav-menu-badge">{{ $activeBookingsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-menu-item" data-tooltip="{{ __('messages.articles') }}">
                            <a href="{{ route('my-articles') }}" class="nav-menu-link">
                                <i class="fas fa-newspaper"></i>
                                <span class="nav-menu-text">{{ __('messages.articles') }}</span>
                                @if($publishedArticlesCount > 0)
                                    <span class="nav-menu-badge">{{ $publishedArticlesCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Actions with Dropdown -->
                <div class="user-actions">
                    <!-- Language Switcher -->
                    <x-language-switcher />

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
                                    <span class="user-status active">{{ __('messages.active_now') }}</span>
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
                                        <i class="fas fa-circle"></i> {{ __('messages.active_now') }}
                                    </div>
                                </div>
                                <div class="dropdown-user-details">
                                    <h4>{{ Auth::user()->full_name }}</h4>
                                    <p>{{ Auth::user()->email }}</p>
                                    <div class="user-badges">
                                        @if(Auth::user()->google_id)
                                            <span class="google-badge">
                                                <i class="fab fa-google"></i> {{ __('messages.linked_to_google') }}
                                            </span>
                                        @endif
                                        @if(Auth::user()->two_factor_confirmed_at)
                                            <span class="twofa-badge">
                                                <i class="fas fa-shield-alt"></i> 2FA {{ __('messages.enabled') }}
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
                                    <span>{{ __('messages.profile') }}</span>
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <span>{{ __('messages.account_settings') }}</span>
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <!-- Security Settings -->
                                <div class="dropdown-subheader">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>{{ __('messages.security') }}</span>
                                </div>

                                <a href="{{ route('two-factor.setup') }}" class="dropdown-item">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <span>{{ __('messages.two_factor_auth') }}</span>
                                    @if(Auth::user()->two_factor_confirmed_at)
                                        <span class="dropdown-badge active">{{ __('messages.enabled') }}</span>
                                    @else
                                        <span class="dropdown-badge inactive">{{ __('messages.disabled') }}</span>
                                    @endif
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <a href="{{ route('password.change') }}" class="dropdown-item">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <span>{{ __('messages.change_password') }}</span>
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <a href="{{ route('identity-verification.create') }}" class="dropdown-item" onclick="event.stopPropagation();">
                                    <div class="dropdown-icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <span>{{ __('messages.identity_verification') }}</span>
                                    @if(Auth::user()->identity_verified)
                                        <span class="dropdown-badge active">{{ __('messages.verified') }}</span>
                                    @else
                                        <span class="dropdown-badge inactive">{{ __('messages.not_verified') }}</span>
                                    @endif
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <!-- Google Login -->
                                <div class="dropdown-subheader">
                                    <i class="fab fa-google"></i>
                                    <span>{{ __('messages.google_signin') }}</span>
                                </div>

                                @if(Auth::user()->google_id)
                                    <form method="POST" action="{{ route('google.unlink') }}" class="dropdown-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <div class="dropdown-icon">
                                                <i class="fas fa-unlink"></i>
                                            </div>
                                            <span>{{ __('messages.unlink_google') }}</span>
                                            <i class="fas fa-chevron-left arrow"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login.google') }}" class="dropdown-item">
                                        <div class="dropdown-icon">
                                            <i class="fab fa-google"></i>
                                        </div>
                                        <span>{{ __('messages.link_google_account') }}</span>
                                        <i class="fas fa-chevron-left arrow"></i>
                                    </a>
                                @endif

                                <!-- Backup Codes -->
                                @if(Auth::user()->two_factor_confirmed_at)
                                    <div class="dropdown-subheader">
                                        <i class="fas fa-file-alt"></i>
                                        <span>{{ __('messages.recovery_codes') }}</span>
                                    </div>

                                    <a href="{{ route('two-factor.recovery-codes.show') }}" class="dropdown-item">
                                        <div class="dropdown-icon">
                                            <i class="fas fa-key"></i>
                                        </div>
                                        <span>{{ __('messages.view_recovery_codes') }}</span>
                                        <i class="fas fa-chevron-left arrow"></i>
                                    </a>

                                    <form method="POST" action="{{ route('two-factor.generate-recovery-codes') }}" class="dropdown-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <div class="dropdown-icon">
                                                <i class="fas fa-redo"></i>
                                            </div>
                                            <span>{{ __('messages.generate_new_codes') }}</span>
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
                                    <span>{{ __('messages.visit_site') }}</span>
                                    <i class="fas fa-chevron-left arrow"></i>
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout">
                                        <div class="dropdown-icon">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <span>{{ __('messages.logout') }}</span>
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

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">{{ __('messages.user_dashboard') }}</h2>
                    <p class="page-subtitle">{{ __('messages.welcome_back_user') }}</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('home') }}" class="btn btn-new-trip" title="{{ __('messages.back_to_site') }}">
                        <i class="fas fa-arrow-right"></i>
                        <span>{{ __('messages.back_to_site') }}</span>
                    </a>
                    <a href="{{ route('bookings.create') }}" class="btn btn-new-trip">
                        <i class="fas fa-calendar-check"></i>
                        <span>{{ __('messages.book_trip') }}</span>
                    </a>
                </div>
            </div>

            <!-- ========== STATS CARDS ========== -->
            <div class="stats-section">
                <div class="stats-grid">
                    <!-- Card 1 -->
                    <div class="stat-card card-bookings">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.active_bookings') }}</h3>
                            <div class="card-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="stat-number">{{ $activeBookingsCount }}</div>
                            <p class="stat-desc">{{ __('messages.active_booking') }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('my-bookings') }}" class="card-link">{{ __('messages.view_details') }} <i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="stat-card card-articles">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.published_articles') }}</h3>
                            <div class="card-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="stat-number">{{ $publishedArticlesCount }}</div>
                            <p class="stat-desc">{{ __('messages.active_article') }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('my-articles') }}" class="card-link">{{ __('messages.write_article') }} <i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="stat-card card-status">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.account_status') }}</h3>
                            <div class="card-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="stat-badge">{{ $accountStatus }}</div>
                            <p class="stat-desc">{{ $accountStatusDescription }}</p>
                        </div>
                        <div class="card-footer">
                            @if(!Auth::user()->identity_verified)
                                <a href="{{ route('identity-verification.create') }}" class="card-link">{{ __('messages.verify_identity') }} <i class="fas fa-arrow-left"></i></a>
                            @else
                                <a href="{{ route('profile.show') }}" class="card-link">{{ __('messages.view_profile') }} <i class="fas fa-arrow-left"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== ACTIVITY SECTION ========== -->
            <div class="activity-section">
                <div class="section-header">
                    <h3 class="section-title">{{ __('messages.recent_activities') }}</h3>
                    <div class="section-actions">
                        <button class="btn btn-filter">
                            <i class="fas fa-filter"></i>
                            {{ __('messages.filter') }}
                        </button>
                    </div>
                </div>

                <div class="activity-grid">
                    <!-- Recent Bookings -->
                    <div class="activity-card">
                        <div class="activity-header">
                            <div class="activity-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4 class="activity-title">{{ __('messages.recent_bookings') }}</h4>
                        </div>
                        <div class="activity-body">
                            @if($recentBookings->count() > 0)
                                <div class="activity-list">
                                    @foreach($recentBookings as $booking)
                                        <div class="activity-item">
                                            <div class="activity-item-icon">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                            <div class="activity-item-content">
                                                <h5>{{ $booking->trip->title ?? __('messages.deleted_trip') }}</h5>
                                                <p>
                                                    <span class="status-badge status-{{ $booking->status === 'مؤكدة' ? 'confirmed' : ($booking->status === 'معلقة' ? 'pending' : 'rejected') }}">
                                                        {{ $booking->status }}
                                                    </span>
                                                    - {{ $booking->booking_date->format('Y-m-d') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="activity-footer">
                                    <a href="{{ route('my-bookings') }}" class="btn btn-view-all">
                                        {{ __('messages.view_all') }} <i class="fas fa-arrow-left"></i>
                                    </a>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-calendar-times"></i>
                                    </div>
                                    <h5>{{ __('messages.no_recent_bookings') }}</h5>
                                    <p>{{ __('messages.start_first_trip') }}</p>
                                    <a href="{{ route('bookings.create') }}" class="btn btn-explore">
                                        <i class="fas fa-search"></i>
                                        {{ __('messages.browse_trips') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Articles -->
                    <div class="activity-card">
                        <div class="activity-header">
                            <div class="activity-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <h4 class="activity-title">{{ __('messages.recent_articles') }}</h4>
                        </div>
                        <div class="activity-body">
                            @if($recentArticles->count() > 0)
                                <div class="activity-list">
                                    @foreach($recentArticles as $article)
                                        <div class="activity-item">
                                            <div class="activity-item-icon">
                                                <i class="fas fa-newspaper"></i>
                                            </div>
                                            <div class="activity-item-content">
                                                <h5>{{ $article->title }}</h5>
                                                <p>
                                                    <span class="status-badge status-{{ $article->status === 'منشورة' ? 'published' : ($article->status === 'معلقة' ? 'pending' : 'rejected') }}">
                                                        {{ $article->status }}
                                                    </span>
                                                    - {{ $article->created_at->format('Y-m-d') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="activity-footer">
                                    <a href="{{ route('my-articles') }}" class="btn btn-view-all">
                                        {{ __('messages.view_all') }} <i class="fas fa-arrow-left"></i>
                                    </a>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <h5>{{ __('messages.no_recent_articles') }}</h5>
                                    <p>{{ __('messages.share_experiences') }}</p>
                                    <a href="{{ route('articles.create') }}" class="btn btn-write">
                                        <i class="fas fa-pen"></i>
                                        {{ __('messages.write_new_article') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== FOOTER ========== -->
            <footer class="main-footer">
                <div class="footer-content">
                    <div class="footer-logo">
                        <i class="fas fa-compass"></i>
                        <span>MyJourney</span>
                    </div>
                    <p class="footer-text">{{ __('messages.travel_platform_copyright') }}</p>
                    <div class="footer-links">
                        <a href="#" class="footer-link">{{ __('messages.terms_conditions_link') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.privacy_policy_link') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.support_help') }}</a>
                    </div>
                </div>
            </footer>
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
            notificationBtn.nextElementSibling.classList.remove('show');
        });

        // Notification Dropdown
        notificationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const notificationDropdown = notificationBtn.nextElementSibling;
            notificationDropdown.classList.toggle('show');

            // Close user dropdown if open
            userDropdownContent.classList.remove('show');
        });

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

            if (!notificationBtn.contains(e.target) && !notificationBtn.nextElementSibling.contains(e.target)) {
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
                notificationBtn.nextElementSibling.classList.remove('show');

                if (mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                    body.classList.remove('nav-active');
                    const icon = menuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });

        // Mark notification as read
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                if (this.classList.contains('unread')) {
                    this.classList.remove('unread');
                    const badge = document.querySelector('.notification-badge');
                    let count = parseInt(badge.textContent);
                    if (count > 0) {
                        count--;
                        badge.textContent = count;
                        if (count === 0) {
                            badge.style.display = 'none';
                        }
                    }
                }
            });
        });

        // Mark all notifications as read
        document.querySelector('.mark-all-read')?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });

            const badge = document.querySelector('.notification-badge');
            badge.textContent = '0';
            badge.style.display = 'none';
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
                notificationBtn.nextElementSibling.classList.remove('show');
            }
        }

        window.addEventListener('resize', handleResize);

        // Animate cards on scroll
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

        // Observe all cards
        document.querySelectorAll('.stat-card, .activity-card').forEach(card => {
            observer.observe(card);
        });

        // Smooth loading animation
        document.addEventListener('DOMContentLoaded', () => {
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.4s ease';
                document.body.style.opacity = '1';
            }, 100);
        });

        // Toggle compact menu - يمكن التبديل بالنقر المزدوج على أي عنصر في القائمة
        navMenu.addEventListener('dblclick', (e) => {
            if (e.target.closest('.nav-menu-link')) {
                navMenu.classList.toggle('compact');
                localStorage.setItem(compactMenuKey, navMenu.classList.contains('compact'));
            }
        });

    </script>
</body>
</html>

