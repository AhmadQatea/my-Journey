<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد كلمة المرور - MyJpurney</title>
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
                    <i class="fas fa-shield-alt logo-icon"></i>
                    <h2>تأكيد كلمة المرور</h2>
                </div>
                <p>يرجى تأكيد كلمة المرور للمتابعة</p>
            </div>

            <form method="POST" action="{{ route('password.confirm.store') }}">
                @csrf

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        كلمة المرور
                    </label>
                    <div class="input-with-icon">
                        <input type="password" class="form-input @error('password') is-invalid @enderror"
                               id="password" name="password" required autofocus
                               placeholder="أدخل كلمة المرور">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-check-circle"></i>
                    تأكيد
                </button>
            </form>

            <div class="form-links">
                <a href="{{ route('dashboard') }}" class="form-link">
                    <i class="fas fa-arrow-right"></i>
                    رجوع إلى لوحة التحكم
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

