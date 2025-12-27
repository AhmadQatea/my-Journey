<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - MyJourney Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-site.png') }}">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dashboard-admin.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="transition-colors duration-500">
    <!-- Enhanced Animated Background -->
    <div class="animated-bg">
        <!-- Floating Orbs -->
        <div class="floating-orbs">
            <div class="orb orb-1 animate-float"></div>
            <div class="orb orb-2 animate-float"></div>
            <div class="orb orb-3 animate-float"></div>
        </div>

        <!-- Animated Grid -->
        <div class="animated-grid"></div>

        <!-- Particle Rain -->
        <div class="particle-rain">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar fixed inset-y-0 right-0 z-50 w-64 shadow-2xl flex flex-col" id="sidebar">
        <div class="sidebar-header p-6 flex-shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="logo-icon w-10 h-10 bg-gradient-to-br from-blue-400 to-green-400 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-route text-black text-lg animate-pulse"></i>
                    </div>
                    <div class="logo-text">
                        <h3 class="logo text-xl font-bold">MyJourney</h3>
                        <p class="text-xs text-blue-100 mt-1">Admin Panel</p>
                    </div>
                </div>
                <button class="toggle-sidebar p-2 rounded-xl hover:bg-white/10 transition-all duration-300 ripple" id="toggleSidebar">
                    <i class="fas fa-chevron-right text-blue-200 transition-transform duration-300"></i>
                </button>
            </div>
        </div>

        <div class="sidebar-menu flex-1 overflow-y-auto p-4 space-y-2 pb-24">
            @foreach($sidebarMenuItems ?? [] as $item)
                <x-sidebar-item
                    :title="$item['title']"
                    :route="$item['route']"
                    :icon="$item['icon']"
                    :routePattern="$item['routePattern'] ?? null"
                    :badge="$item['badge'] ?? null"
                />
            @endforeach
        </div>

        <!-- Sidebar Footer - Fixed at bottom -->
        <div class="sidebar-footer flex-shrink-0 w-full p-4 border-t border-white/10 bg-gradient-to-t from-blue-900/50 to-transparent">
            <div class="flex items-center justify-between">
                <div class="theme-toggle-container flex items-center space-x-2 space-x-reverse">
                    <i class="fas fa-sun text-yellow-400 text-xs animate-pulse"></i>
                    <label class="theme-toggle cursor-pointer">
                        <input type="checkbox" id="themeToggle" class="sr-only">
                        <span class="theme-slider"></span>
                    </label>
                    <i class="fas fa-moon text-indigo-300 text-xs animate-pulse"></i>
                </div>
                <div class="flex items-center space-x-2 space-x-reverse text-xs text-blue-200 transition-all duration-300">
                    <i class="fas fa-shield-alt text-green-300 animate-pulse"></i>
                    <span class="animate-shimmer">الإصدار 1.0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content mr-64 transition-all duration-300" id="mainContent">
        <!-- Top Navbar -->
        <nav class="top-navbar px-8 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <button class="toggle-sidebar p-3 rounded-xl hover:bg-blue-500/10 dark:hover:bg-green-500/10 transition-all duration-300 lg:hidden ripple" id="mobileToggleSidebar">
                        <i class="fas fa-bars text-blue-600 dark:text-green-400 text-xl"></i>
                    </button>
                    <h4 class="text-2xl font-bold animated-gradient-text">
                        @yield('page-title', 'مرحباً بعودتك! 👋')
                    </h4>
                </div>

                <div class="flex items-center space-x-4 space-x-reverse">
                    <!-- Theme Toggle for Mobile -->
                    <div class="lg:hidden">
                        <button class="theme-toggle-mobile p-3 rounded-xl hover:bg-blue-500/10 dark:hover:bg-green-500/10 transition-all duration-300 text-blue-600 dark:text-green-400 ripple">
                            <i class="fas fa-moon text-lg" id="mobileThemeIcon"></i>
                        </button>
                    </div>

                    <!-- Notifications -->
                    @include('admin.notifications.dropdown')

                    <!-- User Info -->
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="user-avatar w-10 h-10 rounded-xl flex items-center justify-center text-black font-bold shadow-lg transition-all duration-300">
                            {{ substr(auth()->guard('admin')->user()->name, 0, 1) }}
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-500">{{ auth()->guard('admin')->user()->name }}</span>
                            <p class="text-xs text-blue-600 dark:text-green-400">مدير النظام</p>
                        </div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn-primary flex items-center space-x-2 space-x-reverse shadow-lg">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>تسجيل الخروج</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-area p-8 min-h-screen">
            <!-- Flash Messages -->
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

            @if(session('error'))
                <div class="custom-alert error mb-6 animate-slideInUp">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl animate-pulse"></i>
                            <span class="text-red-700 dark:text-red-300 font-bold">{{ session('error') }}</span>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors duration-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button class="scroll-top ripple" id="scrollTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Enhanced JavaScript -->
    <script>
        // Enhanced Theme Manager with Smooth Transitions
        class ThemeManager {
            constructor() {
                this.themeToggle = document.getElementById('themeToggle');
                this.mobileThemeToggle = document.querySelector('.theme-toggle-mobile');
                this.mobileThemeIcon = document.getElementById('mobileThemeIcon');
                this.body = document.body;
                this.init();
            }

            init() {
                this.loadTheme();
                this.bindEvents();
                this.applySystemTheme();
                this.addTransitionClass();
            }

            addTransitionClass() {
                // إضافة class للتحكم في انتقالات الألوان
                document.documentElement.classList.add('color-transition');
            }

            loadTheme() {
                const savedTheme = localStorage.getItem('admin-theme') || 'light';
                this.setTheme(savedTheme);
                this.updateToggleState(savedTheme);
            }

            setTheme(theme) {
                // إضافة تأثير انتقال سلس
                this.body.style.opacity = '0.8';
                document.documentElement.classList.toggle('dark', theme === 'dark');
                localStorage.setItem('admin-theme', theme);

                setTimeout(() => {
                    this.body.style.opacity = '1';
                    this.triggerThemeChangeEvent(theme);
                }, 300);
            }

            updateToggleState(theme) {
                if (this.themeToggle) {
                    this.themeToggle.checked = theme === 'dark';
                }
                if (this.mobileThemeIcon) {
                    this.mobileThemeIcon.className = theme === 'dark'
                        ? 'fas fa-sun text-lg animate-pulse'
                        : 'fas fa-moon text-lg animate-pulse';
                }
            }

            toggleTheme() {
                const currentTheme = localStorage.getItem('admin-theme') || 'light';
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';

                // إضافة تأثير صوتي خفيف (اختياري)
                this.playThemeSound();

                this.setTheme(newTheme);
                this.updateToggleState(newTheme);

                // إظهار رسالة توضيحية
                this.showThemeNotification(newTheme);
            }

            playThemeSound() {
                // إنشاء صوت خفيف عند التبديل
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 800;
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                oscillator.start();
                oscillator.stop(audioContext.currentTime + 0.5);
            }

            showThemeNotification(theme) {
                const notification = document.createElement('div');
                notification.className = `fixed top-6 left-1/2 transform -translate-x-1/2 z-[10000] px-6 py-3 rounded-xl shadow-xl backdrop-blur-lg border ${
                    theme === 'dark'
                    ? 'bg-gray-900/90 text-black border-gray-700'
                    : 'bg-white/90 text-gray-900 border-gray-200'
                }`;

                notification.innerHTML = `
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-${theme === 'dark' ? 'moon' : 'sun'} text-xl ${
                            theme === 'dark' ? 'text-indigo-300' : 'text-yellow-500'
                        } animate-pulse"></i>
                        <span class="font-bold">تم التبديل إلى الوضع ${theme === 'dark' ? 'الليلي 🌙' : 'النهاري ☀️'}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                // إزالة الإشعار بعد 3 ثواني
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translate(-50%, -20px)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            triggerThemeChangeEvent(theme) {
                // إرسال حدث لتحديث المكونات الأخرى
                const event = new CustomEvent('themeChange', { detail: { theme } });
                window.dispatchEvent(event);
            }

            applySystemTheme() {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

                prefersDark.addEventListener('change', e => {
                    const theme = localStorage.getItem('admin-theme');
                    if (theme === 'system' || !theme) {
                        this.setTheme(e.matches ? 'dark' : 'light');
                        this.updateToggleState(e.matches ? 'dark' : 'light');
                    }
                });
            }

            bindEvents() {
                if (this.themeToggle) {
                    this.themeToggle.addEventListener('change', () => {
                        this.toggleTheme();
                    });
                }

                if (this.mobileThemeToggle) {
                    this.mobileThemeToggle.addEventListener('click', () => {
                        this.toggleTheme();
                    });
                }
            }
        }

        // Enhanced Sidebar Manager with Smooth Animations
        class SidebarManager {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.mainContent = document.getElementById('mainContent');
                this.toggleButtons = document.querySelectorAll('.toggle-sidebar, #mobileToggleSidebar');
                this.init();
            }

            init() {
                this.bindEvents();
                this.loadSidebarState();
                this.handleResize();
                this.addHoverEffects();
            }

            bindEvents() {
                this.toggleButtons.forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.toggleSidebar();
                        this.playToggleSound();
                    });
                });

                document.addEventListener('click', (event) => {
                    if (window.innerWidth < 1024 && !this.sidebar.contains(event.target) && !event.target.closest('#mobileToggleSidebar')) {
                        this.collapseSidebar();
                    }
                });

                window.addEventListener('resize', () => this.handleResize());
            }

            playToggleSound() {
                // صوت خفيف عند فتح/إغلاق الشريط الجانبي
                const audio = new Audio('data:audio/wav;base64,UklGRigAAABXQVZFZm10IBIAAAABAAEAQB8AAEAfAAABAAgAZGF0YQ');
                audio.volume = 0.1;
                audio.play().catch(() => {});
            }

            toggleSidebar() {
                this.sidebar.classList.toggle('collapsed');
                this.updateMainContentMargin();
                this.updateToggleIcon();
                this.saveSidebarState();
                this.animateSidebarToggle();
            }

            animateSidebarToggle() {
                const isCollapsed = this.sidebar.classList.contains('collapsed');
                const menuItems = this.sidebar.querySelectorAll('.menu-item');

                menuItems.forEach((item, index) => {
                    item.style.transitionDelay = isCollapsed
                        ? `${index * 50}ms`
                        : `${(menuItems.length - index - 1) * 50}ms`;

                    if (!isCollapsed) {
                        item.classList.add('animate-fadeIn');
                        setTimeout(() => item.classList.remove('animate-fadeIn'), 600);
                    }
                });
            }

            collapseSidebar() {
                this.sidebar.classList.add('collapsed');
                this.updateMainContentMargin();
                this.updateToggleIcon();
                this.saveSidebarState();
            }

            expandSidebar() {
                this.sidebar.classList.remove('collapsed');
                this.updateMainContentMargin();
                this.updateToggleIcon();
                this.saveSidebarState();
            }

            updateMainContentMargin() {
                const isCollapsed = this.sidebar.classList.contains('collapsed');
                this.mainContent.classList.toggle('mr-64', !isCollapsed);
                this.mainContent.classList.toggle('mr-20', isCollapsed);
            }

            updateToggleIcon() {
                const icons = document.querySelectorAll('.toggle-sidebar i');
                icons.forEach(icon => {
                    if (this.sidebar.classList.contains('collapsed')) {
                        icon.className = 'fas fa-chevron-left text-blue-200 transition-transform duration-300 animate-pulse';
                    } else {
                        icon.className = 'fas fa-chevron-right text-blue-200 transition-transform duration-300 animate-pulse';
                    }
                });
            }

            addHoverEffects() {
                const menuItems = this.sidebar.querySelectorAll('.menu-item');

                menuItems.forEach(item => {
                    item.addEventListener('mouseenter', () => {
                        if (this.sidebar.classList.contains('collapsed')) {
                            const tooltip = item.getAttribute('data-tooltip');
                            this.showTooltip(item, tooltip);
                        }
                    });

                    item.addEventListener('mouseleave', () => {
                        this.hideTooltip();
                    });
                });
            }

            showTooltip(element, text) {
                this.hideTooltip();

                const tooltip = document.createElement('div');
                tooltip.className = 'menu-tooltip';
                tooltip.textContent = text;

                const rect = element.getBoundingClientRect();
                tooltip.style.right = (window.innerWidth - rect.left + 10) + 'px';
                tooltip.style.top = (rect.top + (rect.height / 2) - 16) + 'px';

                tooltip.id = 'current-tooltip';
                document.body.appendChild(tooltip);
            }

            hideTooltip() {
                const existingTooltip = document.getElementById('current-tooltip');
                if (existingTooltip) {
                    existingTooltip.remove();
                }
            }

            saveSidebarState() {
                const isCollapsed = this.sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebar-collapsed', isCollapsed);
            }

            loadSidebarState() {
                const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                if (isCollapsed) {
                    this.collapseSidebar();
                } else {
                    this.expandSidebar();
                }
            }

            handleResize() {
                if (window.innerWidth < 1024) {
                    this.collapseSidebar();
                } else {
                    this.loadSidebarState();
                }
            }
        }

        // Scroll to Top Manager
        class ScrollTopManager {
            constructor() {
                this.scrollTopBtn = document.getElementById('scrollTop');
                this.init();
            }

            init() {
                this.bindEvents();
                this.checkScrollPosition();
            }

            bindEvents() {
                window.addEventListener('scroll', () => this.checkScrollPosition());

                this.scrollTopBtn.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            checkScrollPosition() {
                if (window.pageYOffset > 300) {
                    this.scrollTopBtn.classList.add('show');
                } else {
                    this.scrollTopBtn.classList.remove('show');
                }
            }
        }

        // Page Loader
        class PageLoader {
            constructor() {
                this.init();
            }

            init() {
                this.hideLoader();
            }

            hideLoader() {
                window.addEventListener('load', () => {
                    document.body.classList.add('loaded');

                    setTimeout(() => {
                        const loader = document.getElementById('pageLoader');
                        if (loader) loader.remove();
                    }, 500);
                });
            }
        }

        // Ripple Effect Manager
        class RippleManager {
            constructor() {
                this.buttons = document.querySelectorAll('.ripple');
                this.init();
            }

            init() {
                this.bindEvents();
            }

            bindEvents() {
                this.buttons.forEach(button => {
                    button.addEventListener('click', this.createRipple.bind(this));
                });
            }

            createRipple(event) {
                const button = event.currentTarget;
                const circle = document.createElement('span');
                const diameter = Math.max(button.clientWidth, button.clientHeight);
                const radius = diameter / 2;

                circle.style.width = circle.style.height = `${diameter}px`;
                circle.style.left = `${event.clientX - button.getBoundingClientRect().left - radius}px`;
                circle.style.top = `${event.clientY - button.getBoundingClientRect().top - radius}px`;
                circle.classList.add('ripple-circle');

                const ripple = button.getElementsByClassName('ripple-circle')[0];
                if (ripple) ripple.remove();

                button.appendChild(circle);
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new ThemeManager();
            new SidebarManager();
            new ScrollTopManager();
            new PageLoader();
            new RippleManager();

            // Add loading animation to page
            document.body.classList.add('loading');
            setTimeout(() => {
                document.body.classList.remove('loading');
                document.body.classList.add('loaded');
            }, 500);
        });

        // Add CSS for ripple effect
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            .ripple-circle {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.7);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }

            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }

            .color-transition {
                transition:
                    background-color 0.3s ease,
                    color 0.3s ease,
                    border-color 0.3s ease,
                    box-shadow 0.3s ease;
            }

            body.loading {
                opacity: 0;
            }

            body.loaded {
                opacity: 1;
                transition: opacity 0.5s ease;
            }
        `;
        document.head.appendChild(rippleStyle);
    </script>

    @stack('scripts')
</body>
</html>
