ا ت<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل دخول الإداريين - MyJourney</title>
    <link href="{{ asset('assets/css/admin-login.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="admin-login-body">
    <!-- Pre-loader -->
    <div class="admin-preloader">
        <div class="admin-preloader-inner">
            <div class="admin-preloader-spinner"></div>
            <p>جاري التحميل...</p>
        </div>
    </div>

    <div class="admin-login-container">
        <div class="admin-login-card">
            <!-- Header Section -->
            <div class="admin-login-header">
                <div class="admin-brand">
                    <div class="admin-logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="admin-brand-text">
                        <h1>MyJourney</h1>
                        <span>نظام إدارة الرحلات</span>
                    </div>
                </div>
                <div class="admin-access-title">
                    <h2>تسجيل دخول الإداريين</h2>
                    <p>الوصول الآمن إلى لوحة التحكم</p>
                </div>
            </div>

            <!-- Alert Messages -->
            <div class="admin-alerts">
                @if(session('error'))
                <div class="admin-alert admin-alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="alert-content">
                        <strong>فشل المصادقة</strong>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
                @endif

                @if(session('csrf_error'))
                <div class="admin-alert admin-alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="alert-content">
                        <strong>انتهت صلاحية الجلسة</strong>
                        <span>{{ session('csrf_error') }}</span>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="admin-alert admin-alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="alert-content">
                        <strong>خطأ في التحقق</strong>
                        @foreach($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Login Form -->
            <form class="admin-login-form" method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="admin-form-group">
                    <label for="email" class="admin-form-label">
                        <i class="fas fa-user-tie"></i>
                        <span>البريد الإلكتروني</span>
                    </label>
                    <div class="admin-input-wrapper">
                        <input
                            type="email"
                            class="admin-form-input @error('email') admin-input-error @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="admin@myjourney.com"
                            required
                            autocomplete="username"
                        >
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <div class="admin-input-feedback">
                            <i class="fas fa-info-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="admin-form-group">
                    <label for="password" class="admin-form-label">
                        <i class="fas fa-key"></i>
                        <span>كلمة المرور</span>
                    </label>
                    <div class="admin-input-wrapper">
                        <input
                            type="password"
                            class="admin-form-input @error('password') admin-input-error @enderror"
                            id="password"
                            name="password"
                            placeholder="أدخل كلمة المرور"
                            required
                            autocomplete="current-password"
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="admin-input-feedback">
                            <i class="fas fa-info-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="admin-form-options">
                    <label class="admin-checkbox">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="checkmark"></span>
                        <span class="checkbox-label">
                            <i class="fas fa-check"></i>
                            تذكر هذا الجهاز
                        </span>
                    </label>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="admin-login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>دخول لوحة التحكم</span>
                        <div class="btn-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="admin-login-footer">
                <div class="security-info">
                    <i class="fas fa-lock"></i>
                    <span>محمي بتشفير AES-256</span>
                </div>
                <div class="copyright">
                    &copy; 2024 MyJourney Travel System. للإداريين فقط.
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.admin-preloader');
            setTimeout(() => {
                preloader.classList.add('loaded');
            }, 500);
        });

        // Password visibility toggle
        document.addEventListener('DOMContentLoaded', function() {
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');

            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                const icon = this.querySelector('i');
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';

                this.classList.toggle('active');
            });

            // Form submission loading state and CSRF token refresh
            const loginForm = document.querySelector('.admin-login-form');
            const loginBtn = document.querySelector('.admin-login-btn');
            const csrfTokenInput = loginForm.querySelector('input[name="_token"]');
            const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');

            // Function to refresh CSRF token
            function refreshCsrfToken() {
                return fetch('{{ route("admin.csrf-token") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                    cache: 'no-cache'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.token) {
                        // Update the token in the form
                        if (csrfTokenInput) {
                            csrfTokenInput.value = data.token;
                        }
                        // Update the meta tag
                        if (csrfMetaTag) {
                            csrfMetaTag.setAttribute('content', data.token);
                        }
                        return true;
                    }
                    return false;
                })
                .catch(error => {
                    console.error('Error refreshing CSRF token:', error);
                    return false;
                });
            }

            // Refresh CSRF token before form submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                loginBtn.classList.add('loading');

                // Refresh token before submitting
                refreshCsrfToken().then(success => {
                    if (success) {
                        // Submit the form after token refresh
                        loginForm.submit();
                    } else {
                        // If refresh fails, try submitting anyway
                        loginBtn.classList.remove('loading');
                        alert('حدث خطأ في تحديث الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
                    }
                });
            });

            // Input animations
            const inputs = document.querySelectorAll('.admin-form-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
        });
    </script>
</body>
</html>

