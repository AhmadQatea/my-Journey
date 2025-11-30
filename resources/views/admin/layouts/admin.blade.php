<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - MyJourney Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Enhanced Animated Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background:
                linear-gradient(45deg, rgba(59, 130, 246, 0.03) 0%, transparent 50%),
                linear-gradient(135deg, rgba(34, 197, 94, 0.03) 0%, transparent 50%),
                linear-gradient(225deg, rgba(255, 255, 255, 0.02) 0%, transparent 50%);
            overflow: hidden;
        }

        .wave-lines {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.4;
        }

        .wave-line {
            position: absolute;
            width: 200%;
            height: 100px;
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(59, 130, 246, 0.1) 25%,
                rgba(34, 197, 94, 0.1) 50%,
                rgba(59, 130, 246, 0.1) 75%,
                transparent 100%);
            animation: waveMove 20s linear infinite;
            mask: linear-gradient(90deg, transparent, white 20%, white 80%, transparent);
        }

        .wave-line:nth-child(1) {
            top: 10%;
            animation-delay: 0s;
            height: 80px;
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(34, 197, 94, 0.08) 25%,
                rgba(59, 130, 246, 0.08) 50%,
                rgba(34, 197, 94, 0.08) 75%,
                transparent 100%);
        }

        .wave-line:nth-child(2) {
            top: 40%;
            animation-delay: -5s;
            animation-duration: 25s;
            height: 120px;
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(59, 130, 246, 0.06) 25%,
                rgba(255, 255, 255, 0.04) 50%,
                rgba(59, 130, 246, 0.06) 75%,
                transparent 100%);
        }

        .wave-line:nth-child(3) {
            top: 70%;
            animation-delay: -10s;
            animation-duration: 30s;
            height: 100px;
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(34, 197, 94, 0.07) 25%,
                rgba(59, 130, 246, 0.07) 50%,
                rgba(34, 197, 94, 0.07) 75%,
                transparent 100%);
        }

        @keyframes waveMove {
            0% {
                transform: translateX(-50%);
            }
            100% {
                transform: translateX(0%);
            }
        }

        /* Floating Particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .particle {
            position: absolute;
            background: linear-gradient(45deg, #3B82F6, #22C55E);
            border-radius: 50%;
            animation: floatParticle 15s ease-in-out infinite;
            opacity: 0.1;
        }

        .particle:nth-child(1) {
            width: 60px;
            height: 60px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
            background: linear-gradient(45deg, #3B82F6, #22C55E);
        }

        .particle:nth-child(2) {
            width: 40px;
            height: 40px;
            top: 60%;
            left: 80%;
            animation-delay: 2s;
            background: linear-gradient(45deg, #22C55E, #3B82F6);
        }

        .particle:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 80%;
            left: 20%;
            animation-delay: 4s;
            background: linear-gradient(45deg, #3B82F6, #ffffff);
        }

        .particle:nth-child(4) {
            width: 50px;
            height: 50px;
            top: 30%;
            left: 70%;
            animation-delay: 6s;
            background: linear-gradient(45deg, #22C55E, #ffffff);
        }

        @keyframes floatParticle {
            0%, 100% {
                transform: translateY(0px) translateX(0px) rotate(0deg);
            }
            25% {
                transform: translateY(-40px) translateX(20px) rotate(90deg);
            }
            50% {
                transform: translateY(20px) translateX(-30px) rotate(180deg);
            }
            75% {
                transform: translateY(-20px) translateX(15px) rotate(270deg);
            }
        }

        /* Sidebar Styles - Blue & Green Theme */
        .sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(180deg, #1e3a8a 0%, #065f46 100%);
            position: relative;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                linear-gradient(45deg, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, transparent 50%);
            z-index: 0;
        }

        .sidebar.collapsed {
            width: 80px !important;
        }

        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .sidebar-footer span:not(.theme-toggle) {
            opacity: 0;
            visibility: hidden;
            width: 0;
            height: 0;
            margin: 0;
            padding: 0;
        }

        .sidebar.collapsed .sidebar-header,
        .sidebar.collapsed .sidebar-menu,
        .sidebar.collapsed .sidebar-footer {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .sidebar.collapsed .menu-item {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar.collapsed .menu-item i {
            margin: 0;
        }

        .sidebar-header {
            position: relative;
            z-index: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            background: linear-gradient(135deg, #bfdbfe 0%, #bbf7d0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-menu {
            position: relative;
            z-index: 1;
        }

        .menu-item {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border-radius: 12px;
            margin-bottom: 4px;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #60a5fa, #4ade80);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .menu-item:hover::before,
        .menu-item.active::before {
            transform: scaleY(1);
        }

        .menu-item.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(34, 197, 94, 0.15));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .sidebar-footer {
            position: relative;
            z-index: 1;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Main Content */
        .main-content {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(248, 250, 252, 0.9);
            backdrop-filter: blur(20px);
        }

        .dark .main-content {
            background: rgba(15, 23, 42, 0.9);
        }

        .top-navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
        }

        .dark .top-navbar {
            background: rgba(30, 41, 59, 0.8);
            border-bottom: 1px solid rgba(34, 197, 94, 0.1);
        }

        /* Theme Toggle - Blue & Green */
        .theme-toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .theme-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .theme-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #9ca3af, #6b7280);
            transition: .4s;
            border-radius: 34px;
            overflow: hidden;
        }

        .theme-slider::before {
            content: '';
            position: absolute;
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background: white;
            transition: .4s;
            border-radius: 50%;
            z-index: 2;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        input:checked + .theme-slider {
            background: linear-gradient(135deg, #3B82F6, #22C55E);
        }

        input:checked + .theme-slider::before {
            transform: translateX(30px);
        }

        .theme-slider .sun-icon,
        .theme-slider .moon-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: white;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .theme-slider .sun-icon {
            left: 8px;
        }

        .theme-slider .moon-icon {
            right: 8px;
        }

        /* Content Cards */
        .content-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .dark .content-card {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(34, 197, 94, 0.1);
        }

        .content-card:hover {
            transform: translateY(-2px);
            box-shadow:
                0 8px 32px rgba(59, 130, 246, 0.1),
                0 4px 16px rgba(34, 197, 94, 0.1);
        }

        /* Buttons - Blue & Green Theme */
        .btn-primary {
            background: linear-gradient(135deg, #3B82F6, #22C55E);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow:
                0 8px 25px rgba(59, 130, 246, 0.3),
                0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #1e40af;
            padding: 10px 20px;
            border-radius: 12px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }

        .dark .btn-secondary {
            background: rgba(30, 41, 59, 0.9);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Table Actions */
        .table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .action-btn.view {
            background: rgba(34, 197, 94, 0.15);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .action-btn.edit {
            background: rgba(59, 130, 246, 0.15);
            color: #1d4ed8;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.15);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* View More Button */
        .view-more-btn {
            background: linear-gradient(135deg, #3B82F6, #22C55E);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
        }

        .view-more-btn:hover {
            transform: translateY(-2px);
            box-shadow:
                0 8px 25px rgba(59, 130, 246, 0.3),
                0 4px 12px rgba(34, 197, 94, 0.3);
        }

        /* Stats Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .dark .stat-card {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(34, 197, 94, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow:
                0 12px 40px rgba(59, 130, 246, 0.1),
                0 6px 20px rgba(34, 197, 94, 0.1);
        }

        /* Dark Mode Overrides */
        .dark .sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #065f46 100%);
        }

        .dark .menu-item {
            color: #e5e7eb;
        }

        .dark .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .dark .menu-item.active {
            color: #ffffff;
        }

        .dark .sidebar-footer {
            color: #9ca3af;
        }

        /* Text Colors */
        .text-muted {
            color: #6b7280;
        }

        .dark .text-muted {
            color: #9ca3af;
        }

        /* Loading Animation */
        .loading-spinner {
            border: 2px solid #f3f4f6;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* User Avatar */
        .user-avatar {
            background: linear-gradient(135deg, #3B82F6, #22C55E);
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.1);
            box-shadow:
                0 4px 12px rgba(59, 130, 246, 0.4),
                0 2px 6px rgba(34, 197, 94, 0.4);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gradient-to-br from-blue-50 to-green-50 dark:from-gray-900 dark:to-gray-800 transition-colors duration-500">
    <!-- Enhanced Animated Background -->
    <div class="animated-bg">
        <div class="wave-lines">
            <div class="wave-line"></div>
            <div class="wave-line"></div>
            <div class="wave-line"></div>
        </div>
        <div class="particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar fixed inset-y-0 right-0 z-50 w-64 shadow-2xl" id="sidebar">
        <div class="sidebar-header p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="logo-icon w-10 h-10 bg-gradient-to-br from-blue-400 to-green-400 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-route text-white text-lg"></i>
                    </div>
                    <div class="logo-text">
                        <h3 class="logo text-xl font-bold">MyJourney</h3>
                        <p class="text-xs text-blue-100 mt-1">Admin Panel</p>
                    </div>
                </div>
                <button class="toggle-sidebar p-2 rounded-xl hover:bg-white/10 transition-all duration-300" id="toggleSidebar">
                    <i class="fas fa-chevron-right text-blue-200 transition-transform duration-300"></i>
                </button>
            </div>
        </div>

        <div class="sidebar-menu p-4 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="menu-item flex items-center space-x-3 space-x-reverse p-4 text-blue-100 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5 text-lg"></i>
                <span class="menu-text font-medium transition-all duration-300">لوحة التحكم</span>
            </a>

            <!-- Users -->
            @can('view_users')
            <a href="{{ route('admin.users.index') }}" class="menu-item flex items-center space-x-3 space-x-reverse p-4 text-blue-100 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users w-5 text-lg"></i>
                <span class="menu-text font-medium transition-all duration-300">المستخدمين</span>
            </a>
            @endcan

            <!-- Trips -->
            @can('manage_trips')
            <a href="{{ route('admin.trips.index') }}" class="menu-item flex items-center space-x-3 space-x-reverse p-4 text-blue-100 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt w-5 text-lg"></i>
                <span class="menu-text font-medium transition-all duration-300">الرحلات</span>
            </a>
            @endcan

            <!-- Bookings -->
            @can('manage_bookings')
            <a href="{{ route('admin.bookings.index') }}" class="menu-item flex items-center space-x-3 space-x-reverse p-4 text-blue-100 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check w-5 text-lg"></i>
                <span class="menu-text font-medium transition-all duration-300">الحجوزات</span>
            </a>
            @endcan

            <!-- Articles -->
            @can('manage_articles')
            <a href="{{ route('admin.articles.index') }}" class="menu-item flex items-center space-x-3 space-x-reverse p-4 text-blue-100 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper w-5 text-lg"></i>
                <span class="menu-text font-medium transition-all duration-300">المقالات</span>
            </a>
            @endcan

            <!-- Deals -->
            @can('manage_deals')
            <a href="{{ route('admin.deals.index') }}" class="menu-item flex items-center space-x-3 space-x-reverse p-4 text-blue-100 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.deals.*') ? 'active' : '' }}">
                <i class="fas fa-tag w-5 text-lg"></i>
                <span class="menu-text font-medium transition-all duration-300">العروض</span>
            </a>
            @endcan
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer absolute bottom-0 w-full p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 space-x-reverse text-sm text-blue-200 transition-all duration-300">
                    <i class="fas fa-shield-alt text-green-300"></i>
                    <span>الإصدار 1.0</span>
                </div>

                <!-- Theme Toggle -->
                <label class="theme-toggle">
                    <input type="checkbox" id="themeToggle">
                    <span class="theme-slider">
                        <i class="fas fa-sun sun-icon"></i>
                        <i class="fas fa-moon moon-icon"></i>
                    </span>
                </label>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content mr-64 transition-all duration-300" id="mainContent">
        <!-- Top Navbar -->
        <nav class="top-navbar px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <button class="toggle-sidebar p-3 rounded-xl hover:bg-blue-500/10 dark:hover:bg-green-500/10 transition-all duration-300 lg:hidden" id="mobileToggleSidebar">
                        <i class="fas fa-bars text-blue-600 dark:text-green-400"></i>
                    </button>
                    <h4 class="text-xl font-bold text-gray-800 dark:text-white bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                        @yield('page-title', 'مرحباً بعودتك! 👋')
                    </h4>
                </div>

                <div class="flex items-center space-x-4 space-x-reverse">
                    <!-- Theme Toggle for Mobile -->
                    <div class="lg:hidden">
                        <button class="theme-toggle-mobile p-3 rounded-xl hover:bg-blue-500/10 dark:hover:bg-green-500/10 transition-all duration-300 text-blue-600 dark:text-green-400">
                            <i class="fas fa-moon text-lg" id="mobileThemeIcon"></i>
                        </button>
                    </div>

                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-3 rounded-xl hover:bg-blue-500/10 dark:hover:bg-green-500/10 transition-all duration-300">
                            <i class="fas fa-bell text-blue-600 dark:text-green-400 text-lg"></i>
                            <span class="absolute -top-1 -left-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center shadow-lg">3</span>
                        </button>
                    </div>

                    <!-- User Info -->
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="user-avatar w-10 h-10 rounded-xl flex items-center justify-center text-white font-semibold shadow-lg transition-all duration-300">
                            {{ substr(auth()->guard('admin')->user()->name, 0, 1) }}
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ auth()->guard('admin')->user()->name }}</span>
                            <p class="text-xs text-blue-600 dark:text-green-400">مدير النظام</p>
                        </div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-green-500 hover:from-blue-600 hover:to-green-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center space-x-2 space-x-reverse shadow-lg hover:shadow-xl transform hover:scale-105">
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
                <div class="content-card mb-6 p-4 border-l-4 border-green-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                            <span class="text-green-700 dark:text-green-300 font-medium">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="text-green-500 hover:text-green-700 dark:hover:text-green-400 transition-colors duration-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="content-card mb-6 p-4 border-l-4 border-red-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                            <span class="text-red-700 dark:text-red-300 font-medium">{{ session('error') }}</span>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors duration-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')

            <!-- Example Section with View More Button -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">@yield('section-title', 'آخر الإحصائيات')</h3>
                    <button class="view-more-btn">
                        <i class="fas fa-chart-line"></i>
                        <span>عرض المزيد</span>
                    </button>
                </div>

                <!-- Example Table with Actions -->
                <div class="content-card p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-blue-200 dark:border-green-200">
                                    <th class="text-right pb-4 font-semibold text-blue-800 dark:text-green-300">الاسم</th>
                                    <th class="text-right pb-4 font-semibold text-blue-800 dark:text-green-300">البريد الإلكتروني</th>
                                    <th class="text-right pb-4 font-semibold text-blue-800 dark:text-green-300">الحالة</th>
                                    <th class="text-right pb-4 font-semibold text-blue-800 dark:text-green-300">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-blue-100 dark:border-green-100">
                                    <td class="py-4 text-blue-600 dark:text-green-400">أحمد محمد</td>
                                    <td class="py-4 text-blue-600 dark:text-green-400">ahmed@example.com</td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            نشط
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <div class="table-actions">
                                            <button class="action-btn view">
                                                <i class="fas fa-eye"></i>
                                                عرض
                                            </button>
                                            <button class="action-btn edit">
                                                <i class="fas fa-edit"></i>
                                                تعديل
                                            </button>
                                            <button class="action-btn delete">
                                                <i class="fas fa-trash"></i>
                                                حذف
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced Theme Manager
        class ThemeManager {
            constructor() {
                this.themeToggle = document.getElementById('themeToggle');
                this.mobileThemeToggle = document.querySelector('.theme-toggle-mobile');
                this.mobileThemeIcon = document.getElementById('mobileThemeIcon');
                this.init();
            }

            init() {
                this.loadTheme();
                this.bindEvents();
                this.applySystemTheme();
            }

            loadTheme() {
                const savedTheme = localStorage.getItem('admin-theme') || 'light';
                this.setTheme(savedTheme);
                this.updateToggleState(savedTheme);
            }

            setTheme(theme) {
                document.documentElement.classList.toggle('dark', theme === 'dark');
                localStorage.setItem('admin-theme', theme);
            }

            updateToggleState(theme) {
                if (this.themeToggle) {
                    this.themeToggle.checked = theme === 'dark';
                }
                if (this.mobileThemeIcon) {
                    this.mobileThemeIcon.className = theme === 'dark' ? 'fas fa-sun text-lg' : 'fas fa-moon text-lg';
                }
            }

            toggleTheme() {
                const currentTheme = localStorage.getItem('admin-theme') || 'light';
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
                this.updateToggleState(newTheme);
            }

            applySystemTheme() {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                    const theme = localStorage.getItem('admin-theme');
                    if (theme === 'system') {
                        this.setTheme('system');
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

        // Enhanced Sidebar Manager
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
            }

            bindEvents() {
                this.toggleButtons.forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.toggleSidebar();
                    });
                });

                document.addEventListener('click', (event) => {
                    if (window.innerWidth < 1024 && !this.sidebar.contains(event.target) && !event.target.closest('#mobileToggleSidebar')) {
                        this.collapseSidebar();
                    }
                });

                window.addEventListener('resize', () => this.handleResize());
            }

            toggleSidebar() {
                this.sidebar.classList.toggle('collapsed');
                this.updateMainContentMargin();
                this.updateToggleIcon();
                this.saveSidebarState();
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
                        icon.className = 'fas fa-chevron-left text-blue-200 transition-transform duration-300';
                    } else {
                        icon.className = 'fas fa-chevron-right text-blue-200 transition-transform duration-300';
                    }
                });
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

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new ThemeManager();
            new SidebarManager();
        });
    </script>

    @stack('scripts')
</body>
</html>
