@props(['active' => ''])

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <i class="fas fa-route me-2"></i>
            MyJourney
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'home' ? 'active' : '' }}" href="/">
                        الرئيسية
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'about' ? 'active' : '' }}" href="/about">
                        عن المنصة
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'offers' ? 'active' : '' }}" href="/offers">
                        العروض
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'booking' ? 'active' : '' }}" href="/booking">
                        الحجز
                    </a>
                </li>
            </ul>

            <div class="navbar-nav">
                @auth
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->full_name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/profile">الملف الشخصي</a></li>
                            <li><a class="dropdown-item" href="/my-bookings">حجوزاتي</a></li>
                            <li><a class="dropdown-item" href="/my-articles">مقالاتي</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">تسجيل الخروج</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="nav-link" href="/login">تسجيل الدخول</a>
                    <a class="nav-link" href="/register">إنشاء حساب</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
