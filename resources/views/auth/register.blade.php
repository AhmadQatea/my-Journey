<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - MyJpurney</title>
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
        <div class="register-card fade-in">
            <div class="card-header">
                <div class="logo-container">
                    <i class="fas fa-globe-americas logo-icon"></i>
                    <h2>MyJpurney</h2>
                </div>
                <p>انضم إلى مجتمع المسافرين وابدأ رحلتك</p>
            </div>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="full_name" class="form-label">
                        <i class="fas fa-user"></i>
                        الاسم الكامل
                    </label>
                    <div class="input-with-icon">
                        <input type="text" class="form-input @error('full_name') is-invalid @enderror"
                               id="full_name" name="full_name" value="{{ old('full_name') }}" required autofocus
                               placeholder="اسمك الكامل">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        البريد الإلكتروني
                    </label>
                    <div class="input-with-icon">
                        <input type="email" class="form-input @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required
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
                               id="password" name="password" required placeholder="كلمة مرور قوية">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock"></i>
                        تأكيد كلمة المرور
                    </label>
                    <div class="input-with-icon">
                        <input type="password" class="form-input"
                               id="password_confirmation" name="password_confirmation" required
                               placeholder="تأكيد كلمة المرور">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">
                        <i class="fas fa-phone"></i>
                        رقم الهاتف (اختياري)
                    </label>
                    <div class="input-with-icon">
                        <input type="text" class="form-input @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone') }}"
                               placeholder="رقم هاتفك">
                        <i class="fas fa-phone input-icon"></i>
                    </div>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="submit-btn register-btn">
                    <i class="fas fa-user-plus"></i>
                    إنشاء حساب
                </button>
            </form>

            <div class="form-links">
                <a href="{{ route('login') }}" class="form-link">
                    <i class="fas fa-sign-in-alt"></i>
                    لديك حساب؟ سجل الدخول
                </a>
            </div>

            <div class="card-footer">
                <p>ابدأ رحلتك مع MyJpurney © 2024</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // عرض اسم الملف عند اختياره
        document.querySelectorAll('.file-input-hidden').forEach(input => {
            input.addEventListener('change', function(e) {
                const label = this.previousElementSibling;
                const fileName = this.files[0]?.name || 'لم يتم اختيار ملف';
                label.innerHTML = `<i class="fas fa-camera"></i> ${fileName}`;
            });
        });
    </script>
</body>
</html>
