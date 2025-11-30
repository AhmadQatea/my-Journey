<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - MyJpurney</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>
<body class="auth-body">
    <!-- خلفية مموجة -->
    <div class="wave-background">
        <div class="wave-line"></div>
        <div class="wave-line"></div>
        <div class="wave-line"></div>
        <div class="wave-line"></div>
    </div>

    <div class="auth-container">
        <div class="login-card fade-in">
            <div class="card-header">
                <div class="logo-container">
                    <i class="fas fa-globe-americas logo-icon"></i>
                    <h2>MyJpurney</h2>
                </div>
                <p>اكتشف العالم معنا! سجل الدخول لرحلتك القادمة</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        البريد الإلكتروني
                    </label>
                    <div class="input-with-icon">
                        <input type="email" class="form-input @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="بريدك الإلكتروني">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        كلمة المرور
                    </label>
                    <div class="input-with-icon">
                        <input type="password" class="form-input @error('password') is-invalid @enderror"
                               id="password" name="password" required placeholder="كلمة المرور">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="text-align: left;">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">تذكرني</label>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    تسجيل الدخول
                </button>
            </form>

            <div class="form-links">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="form-link">
                        <i class="fas fa-key"></i>
                        نسيت كلمة المرور؟
                    </a>
                @endif
                <a href="{{ route('register') }}" class="form-link">
                    <i class="fas fa-user-plus"></i>
                    إنشاء حساب جديد
                </a>
            </div>

            <div class="card-footer">
                <p>ابدأ رحلتك مع MyJpurney © 2024</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
