<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - MyJourney</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;600;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-user.css') }}">
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
                        <li class="nav-menu-item">
                            <a href="{{ route('dashboard') }}" class="nav-menu-link active">
                                <i class="fas fa-home"></i>
                                <span>الرئيسية</span>
                            </a>
                        </li>
                        <li class="nav-menu-item">
                            <a href="{{ route('profile.show') }}" class="nav-menu-link">
                                <i class="fas fa-user-circle"></i>
                                <span>الملف الشخصي</span>
                            </a>
                        </li>
                        <li class="nav-menu-item">
                            <a href="#" class="nav-menu-link">
                                <i class="fas fa-calendar-check"></i>
                                <span>الحجوزات</span>
                                <span class="nav-menu-badge">5</span>
                            </a>
                        </li>
                        <li class="nav-menu-item">
                            <a href="#" class="nav-menu-link">
                                <i class="fas fa-map-marked-alt"></i>
                                <span>رحلاتي</span>
                                <span class="nav-menu-badge">3</span>
                            </a>
                        </li>
                        <li class="nav-menu-item">
                            <a href="#" class="nav-menu-link">
                                <i class="fas fa-newspaper"></i>
                                <span>المقالات</span>
                            </a>
                        </li>
                        <li class="nav-menu-item">
                            <a href="#" class="nav-menu-link">
                                <i class="fas fa-heart"></i>
                                <span>المفضلة</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Actions with Dropdown -->
                <div class="user-actions">
                    <!-- Notification Bell -->
                    <div class="notification-dropdown">
                        <button class="notification-btn" id="notificationBtn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                        <div class="notification-dropdown-content">
                            <div class="notification-header">
                                <h4>الإشعارات</h4>
                                <button class="mark-all-read">تحديد الكل كمقروء</button>
                            </div>
                            <div class="notification-list">
                                <a href="#" class="notification-item unread">
                                    <div class="notification-icon">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text">تم تأكيد حجزك لرحلة دبي</p>
                                        <span class="notification-time">قبل 5 دقائق</span>
                                    </div>
                                </a>
                                <a href="#" class="notification-item unread">
                                    <div class="notification-icon">
                                        <i class="fas fa-star text-warning"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text">لقد ربحت 50 نقطة ولاء جديدة</p>
                                        <span class="notification-time">قبل ساعة</span>
                                    </div>
                                </a>
                                <a href="#" class="notification-item">
                                    <div class="notification-icon">
                                        <i class="fas fa-newspaper text-info"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text">مقالك الجديد حصل على 10 إعجابات</p>
                                        <span class="notification-time">أمس</span>
                                    </div>
                                </a>
                            </div>
                            <div class="notification-footer">
                                <a href="#" class="view-all">عرض كل الإشعارات</a>
                            </div>
                        </div>
                    </div>

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
                            <!-- User Info -->
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-avatar">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->full_name }}">
                                    @else
                                        <i class="fas fa-user-circle"></i>
                                    @endif
                                </div>
                                <div class="dropdown-user-details">
                                    <h4>{{ Auth::user()->full_name }}</h4>
                                    <p>{{ Auth::user()->email }}</p>
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

                            <div class="dropdown-divider"></div>

                            <!-- Menu Items -->
                            <a href="{{ route('profile.show') }}" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>الملف الشخصي</span>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>إعدادات الحساب</span>
                            </a>

                            <!-- Security Settings -->
                            <div class="dropdown-subheader">
                                <i class="fas fa-shield-alt"></i>
                                <span>الأمان</span>
                            </div>

                            <a href="{{ route('two-factor.setup') }}" class="dropdown-item">
                                <i class="fas fa-key"></i>
                                <span>المصادقة الثنائية (2FA)</span>
                                @if(Auth::user()->two_factor_confirmed_at)
                                    <span class="dropdown-badge active">مفعل</span>
                                @else
                                    <span class="dropdown-badge inactive">غير مفعل</span>
                                @endif
                            </a>

                            <a href="{{ route('password.change') }}" class="dropdown-item">
                                <i class="fas fa-lock"></i>
                                <span>تغيير كلمة المرور</span>
                            </a>

                            <!-- Google Login -->
                            <div class="dropdown-subheader">
                                <i class="fab fa-google"></i>
                                <span>التسجيل عبر جوجل</span>
                            </div>

                            @if(Auth::user()->google_id)
                                <form method="POST" action="{{ route('google.unlink') }}" class="dropdown-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item danger">
                                        <i class="fas fa-unlink"></i>
                                        <span>فك ارتباط جوجل</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login.google') }}" class="dropdown-item">
                                    <i class="fab fa-google"></i>
                                    <span>ربط حساب جوجل</span>
                                </a>
                            @endif

                            <!-- Backup Codes -->
                            @if(Auth::user()->two_factor_confirmed_at)
                                <div class="dropdown-subheader">
                                    <i class="fas fa-file-alt"></i>
                                    <span>أكواد الاسترجاع</span>
                                </div>

                                <a href="{{ route('two-factor.recovery-codes') }}" class="dropdown-item">
                                    <i class="fas fa-key"></i>
                                    <span>عرض أكواد الاسترجاع</span>
                                </a>

                                <form method="POST" action="{{ route('two-factor.generate-recovery-codes') }}" class="dropdown-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-redo"></i>
                                        <span>إنشاء أكواد جديدة</span>
                                    </button>
                                </form>
                            @endif

                            <div class="dropdown-divider"></div>
                           <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>تسجيل الخروج</span>
                                </button>
                            </form>
                            <!-- Site Actions -->
                            <a href="{{ route('home') }}" target="_blank" class="dropdown-item">
                                <i class="fas fa-external-link-alt"></i>
                                <span>زيارة الموقع</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                                @csrf
                                <button type="submit" class="dropdown-item danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>تسجيل الخروج</span>
                                </button>
                            </form>
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
            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">لوحة التحكم</h2>
                    <p class="page-subtitle">مرحباً بعودتك! إليك ملخص نشاطاتك</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('home') }}" class="btn btn-new-trip" title="العودة للموقع ->">
                        <i class="fas fa-arrow-right"></i>
                        <span>العودة للموقع</span>
                    </a>
                    <button class="btn btn-new-trip">
                        <i class="fas fa-plus"></i>
                        <span>رحلة جديدة</span>
                    </button>
                </div>
            </div>

            <!-- ========== LOYALTY CARD ========== -->
            <div class="loyalty-card-main">
                <div class="loyalty-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="loyalty-content">
                    <h4 class="loyalty-title">نقاط الولاء</h4>
                    <div class="loyalty-points">1,250</div>
                    <p class="loyalty-desc">مستوى المستخدم المميز</p>
                </div>
            </div>

            <!-- ========== STATS CARDS ========== -->
            <div class="stats-section">
                <div class="stats-grid">
                    <!-- Card 1 -->
                    <div class="stat-card card-bookings">
                        <div class="card-header">
                            <h3 class="card-title">الحجوزات النشطة</h3>
                            <div class="card-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="stat-number">5</div>
                            <p class="stat-desc">حجز فعال</p>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="card-link">عرض التفاصيل <i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="stat-card card-articles">
                        <div class="card-header">
                            <h3 class="card-title">المقالات المنشورة</h3>
                            <div class="card-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="stat-number">3</div>
                            <p class="stat-desc">مقال نشط</p>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="card-link">كتابة مقال <i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="stat-card card-points">
                        <div class="card-header">
                            <h3 class="card-title">نقاط الولاء</h3>
                            <div class="card-icon">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="stat-number">1,250</div>
                            <p class="stat-desc">نقطة مكتسبة</p>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="card-link">كيفية الربح <i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="stat-card card-status">
                        <div class="card-header">
                            <h3 class="card-title">حالة الحساب</h3>
                            <div class="card-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="stat-badge">مميز</div>
                            <p class="stat-desc">مستخدم نشط</p>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="card-link">ترقية <i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== ACTIVITY SECTION ========== -->
            <div class="activity-section">
                <div class="section-header">
                    <h3 class="section-title">النشاطات الحديثة</h3>
                    <div class="section-actions">
                        <button class="btn btn-filter">
                            <i class="fas fa-filter"></i>
                            تصفية
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
                            <h4 class="activity-title">آخر الحجوزات</h4>
                        </div>
                        <div class="activity-body">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <h5>لا توجد حجوزات حديثة</h5>
                                <p>ابدأ رحلتك الأولى وقم بحجز رحلة جديدة</p>
                                <button class="btn btn-explore">
                                    <i class="fas fa-search"></i>
                                    استعرض الرحلات
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Articles -->
                    <div class="activity-card">
                        <div class="activity-header">
                            <div class="activity-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <h4 class="activity-title">آخر المقالات</h4>
                        </div>
                        <div class="activity-body">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <h5>لا توجد مقالات حديثة</h5>
                                <p>شارك تجاربك واكتب عن رحلاتك</p>
                                <button class="btn btn-write">
                                    <i class="fas fa-pen"></i>
                                    كتابة مقال جديد
                                </button>
                            </div>
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
                    <p class="footer-text">منصة السفر والرحلات المميزة © 2024</p>
                    <div class="footer-links">
                        <a href="#" class="footer-link">الشروط والأحكام</a>
                        <a href="#" class="footer-link">سياسة الخصوصية</a>
                        <a href="#" class="footer-link">الدعم والمساعدة</a>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <!-- ========== SCRIPTS ========== -->
    <script>
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

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
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
    </script>
</body>
</html>
