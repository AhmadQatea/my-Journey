<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MyJourney</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #00b09b;
            --danger-color: #f5576c;
            --warning-color: #f093fb;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .navbar {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* تصميم بطاقة التحقق */
        .verification-card {
            max-width: 500px;
            margin: 40px auto;
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px 30px;
            border-bottom: 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .verification-icon {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .verification-icon i {
            font-size: 40px;
            color: white;
        }

        /* تصميم حقل الإدخال */
        .code-input-container {
            position: relative;
            margin: 30px 0;
        }

        .code-input {
            width: 100%;
            height: 80px;
            font-size: 40px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 15px;
            background: linear-gradient(145deg, #ffffff, #f5f7fa);
            border: 3px solid #e2e8f0;
            border-radius: 20px;
            padding: 10px 20px;
            color: #2d3748;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow:
                inset 0 4px 8px rgba(0, 0, 0, 0.05),
                0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .code-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow:
                inset 0 4px 8px rgba(0, 0, 0, 0.05),
                0 12px 30px rgba(102, 126, 234, 0.25),
                0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-3px);
        }

        .code-input.valid {
            border-color: var(--success-color);
            background: linear-gradient(145deg, #f0fff4, #e6fffa);
        }

        .code-input::placeholder {
            letter-spacing: normal;
            font-size: 18px;
            color: #a0aec0;
        }

        /* تصميم مؤشر التحقق التلقائي */
        .auto-verify-indicator {
            height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.5s ease;
            text-align: center;
        }

        .auto-verify-indicator.active {
            height: 120px;
            opacity: 1;
            margin: 20px 0;
        }

        .verifying-text {
            color: var(--success-color);
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .verifying-text i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .progress-container {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
            margin: 20px 0;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--success-color), #96c93d);
            width: 0%;
            transition: width 0.5s ease;
            position: relative;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* تصميم الأزرار */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-resend {
            flex: 1;
            background: linear-gradient(135deg, var(--warning-color), var(--danger-color));
            color: white;
            border: none;
            border-radius: 15px;
            padding: 16px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(245, 87, 108, 0.3);
        }

        .btn-resend:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(245, 87, 108, 0.4);
        }

        .btn-resend:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-cancel {
            flex: 1;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 16px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* تصميم رسائل المعلومات */
        .info-box {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            color: #4a5568;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-item i {
            color: var(--primary-color);
            margin-top: 3px;
        }

        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .verification-card {
                margin: 20px auto;
                border-radius: 20px;
            }

            .code-input {
                height: 70px;
                font-size: 32px;
                letter-spacing: 12px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        @media (max-width: 576px) {
            .code-input {
                height: 65px;
                font-size: 28px;
                letter-spacing: 10px;
            }

            .verification-icon {
                width: 80px;
                height: 80px;
            }

            .verification-icon i {
                font-size: 32px;
            }
        }

        /* أنيميشن النجاح */
        .success-animation {
            animation: successPop 0.5s ease;
        }

        @keyframes successPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-road me-2"></i>
                MyJourney
            </a>

            <div class="navbar-nav">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                @else
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        تسجيل الدخول
                    </a>
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i>
                        إنشاء حساب
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')

        <!-- محتوى صفحة التحقق -->
        <div class="verification-card">
            <!-- رأس البطاقة -->
            <div class="card-header">
                <div class="verification-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2 class="mb-3 fw-bold">التحقق من الهوية</h2>
                <p class="mb-0 fs-5 opacity-90">أدخل رمز التحقق المرسل إلى بريدك الإلكتروني</p>
            </div>

            <!-- محتوى البطاقة -->
            <div class="card-body p-4 p-md-5">
                <!-- معلومات التحقق -->
                <div class="info-box">
                    <div class="info-item">
                        <i class="fas fa-envelope-open-text"></i>
                        <span>تم إرسال رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني المسجل</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span>الرمز صالح لمدة 10 دقائق فقط</span>
                    </div>
                </div>

                <!-- نموذج التحقق -->
                <form method="POST" action="{{ $formAction ?? route('password.code.verify') }}" id="verificationForm">
                    @csrf

                    <!-- حقل إدخال الرمز -->
                    <div class="code-input-container">
                        <input
                            type="text"
                            id="codeInput"
                            name="code"
                            maxlength="6"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            autocomplete="one-time-code"
                            class="form-control code-input"
                            placeholder="أدخل رمز التحقق"
                            oninput="handleCodeInput(this)"
                            onkeydown="handleKeyDown(event)"
                            onpaste="handlePaste(event)"
                            required
                            autofocus
                        >
                    </div>

                    <!-- مؤشر التحقق التلقائي -->
                    <div class="auto-verify-indicator" id="autoVerifyIndicator">
                        <div class="progress-container">
                            <div class="progress-bar" id="progressBar"></div>
                        </div>
                        <div class="verifying-text">
                            <i class="fas fa-sync-alt"></i>
                            جاري التحقق تلقائياً...
                        </div>
                    </div>

                    <!-- رسائل الخطأ -->
                    @error('code')
                        <div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @enderror
                </form>

                <!-- أزرار التحكم -->
                <div class="action-buttons">
                    <button type="button" class="btn-resend" id="resendBtn" onclick="resendCode()">
                        <i class="fas fa-redo me-2"></i>
                        إعادة إرسال الرمز
                    </button>

                    <a href="{{ $cancelRoute ?? route('password.request') }}" class="btn-cancel">
                        <i class="fas fa-times me-2"></i>
                        إلغاء والعودة
                    </a>
                </div>

                <!-- رسالة تأكيد -->
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted mb-0">
                        <small>
                            <i class="fas fa-shield-alt me-2 text-success"></i>
                            هذا الرمز سري ولا ينبغي مشاركته مع أي شخص
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.getElementById('codeInput');
            const verificationForm = document.getElementById('verificationForm');
            const autoVerifyIndicator = document.getElementById('autoVerifyIndicator');
            const progressBar = document.getElementById('progressBar');
            const resendBtn = document.getElementById('resendBtn');

            let isAutoVerifying = false;
            let resendCooldown = false;

            // التركيز على حقل الإدخال عند التحميل
            setTimeout(() => codeInput?.focus(), 300);

            // دالة معالجة إدخال الرمز
            window.handleCodeInput = function(input) {
                // السماح فقط بالأرقام
                input.value = input.value.replace(/\D/g, '').slice(0, 6);

                // تحديث التصميم البصري
                updateInputState(input);

                // التحقق من اكتمال الرمز
                checkAndAutoSubmit();
            };

            // دالة معالجة مفاتيح الإدخال
            window.handleKeyDown = function(event) {
                // منع إدخال أي حروف غير رقمية
                if (!/[0-9]|Backspace|Delete|ArrowLeft|ArrowRight|Tab/.test(event.key) &&
                    !event.ctrlKey && !event.metaKey) {
                    event.preventDefault();
                }

                // التحقق من اكتمال الرمز
                setTimeout(() => checkAndAutoSubmit(), 10);
            };

            // دالة معالجة اللصق
            window.handlePaste = function(event) {
                event.preventDefault();
                const pastedData = event.clipboardData.getData('text').replace(/\D/g, '');

                if (pastedData.length > 0) {
                    codeInput.value = pastedData.slice(0, 6);
                    updateInputState(codeInput);

                    // إضافة أنيميشن النجاح
                    codeInput.classList.add('success-animation');
                    setTimeout(() => {
                        codeInput.classList.remove('success-animation');
                    }, 500);

                    checkAndAutoSubmit();
                }
            };

            // دالة تحديث الحالة البصرية
            function updateInputState(input) {
                if (input.value.length === 6) {
                    input.classList.add('valid');
                } else {
                    input.classList.remove('valid');
                }
            }

            // دالة التحقق التلقائي
            function checkAndAutoSubmit() {
                const code = codeInput.value;

                // التحقق من اكتمال الرمز
                if (code.length === 6 && !isAutoVerifying) {
                    isAutoVerifying = true;

                    // عرض مؤشر التحقق
                    if (autoVerifyIndicator) {
                        autoVerifyIndicator.classList.add('active');
                    }

                    // تشغيل مؤشر التقدم
                    if (progressBar) {
                        progressBar.style.width = '100%';
                    }

                    // إضافة تأثير النجاح
                    codeInput.classList.add('success-animation');

                    // إرسال النموذج بعد تأخير قصير
                    setTimeout(() => {
                        if (verificationForm) {
                            verificationForm.submit();
                        }
                    }, 1000);
                } else if (code.length < 6) {
                    // إخفاء مؤشر التحقق إذا لم يكتمل الرمز
                    if (autoVerifyIndicator) {
                        autoVerifyIndicator.classList.remove('active');
                    }
                    if (progressBar) {
                        progressBar.style.width = '0%';
                    }
                    isAutoVerifying = false;
                }
            }

            // دالة إعادة إرسال الرمز
            window.resendCode = function() {
                if (resendCooldown) return;

                // تفعيل فترة الانتظار
                resendCooldown = true;
                resendBtn.disabled = true;
                const originalText = resendBtn.innerHTML;

                // عرض حالة التحميل
                resendBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    جاري الإرسال...
                `;

                // محاكاة إعادة الإرسال
                setTimeout(() => {
                    // إعادة تعيين حقل الإدخال
                    codeInput.value = '';
                    updateInputState(codeInput);
                    codeInput.focus();

                    // إعادة تعيين زر الإرسال بعد 30 ثانية
                    setTimeout(() => {
                        resendBtn.disabled = false;
                        resendBtn.innerHTML = originalText;
                        resendCooldown = false;
                    }, 30000);

                    // عرض رسالة نجاح
                    showNotification('تم إعادة إرسال الرمز بنجاح', 'success');
                }, 1500);
            };

            // دالة عرض الإشعارات
            function showNotification(message, type = 'info') {
                // إنشاء العنصر
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                notification.style.cssText = `
                    top: 80px;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 9999;
                    min-width: 300px;
                    max-width: 90%;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                    border-radius: 15px;
                    border: none;
                `;

                // إضافة المحتوى
                notification.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-3 fs-4"></i>
                        <div class="flex-grow-1">${message}</div>
                        <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
                    </div>
                `;

                // إضافة للصفحة
                document.body.appendChild(notification);

                // إزالة تلقائية بعد 5 ثواني
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }

            // إضافة تأثير عند التحميل
            setTimeout(() => {
                codeInput.style.opacity = '0';
                codeInput.style.transform = 'translateY(20px)';
                codeInput.style.transition = 'all 0.5s ease';

                setTimeout(() => {
                    codeInput.style.opacity = '1';
                    codeInput.style.transform = 'translateY(0)';
                }, 300);
            }, 500);
        });
    </script>
</body>
</html>
