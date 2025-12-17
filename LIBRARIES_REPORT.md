# تقرير شامل عن المكتبات المستخدمة في المشروع

## 📚 المكتبات الأساسية (Production Dependencies)


### 2. **Laravel Fortify** (`laravel/fortify: ^1.32`)
**الموقع:** `vendor/laravel/fortify/`

**الوظيفة:**
- نظام مصادقة شامل (Authentication) بدون واجهات مسبقة الصنع
- يوفر: تسجيل الدخول، التسجيل، استعادة كلمة المرور، التحقق من البريد، المصادقة الثنائية

**آلية العمل:**
1. **الإعداد:** `config/fortify.php` - تحديد الميزات والسلوكيات
2. **Service Provider:** `app/Providers/FortifyServiceProvider.php` - ربط Actions و Views
3. **Actions:** `app/Actions/Fortify/` - منطق العمل:
   - `CreateNewUser.php` - إنشاء مستخدم جديد
   - `UpdateUserProfileInformation.php` - تحديث معلومات الملف الشخصي
   - `UpdateUserPassword.php` - تحديث كلمة المرور
   - `ResetUserPassword.php` - إعادة تعيين كلمة المرور
4. **Routes:** يتم تسجيلها تلقائياً بواسطة Fortify
5. **Views:** `resources/views/auth/` - واجهات المستخدم

**الاستخدام في المشروع:**
```php
// config/fortify.php
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication(),
]

// app/Providers/FortifyServiceProvider.php
Fortify::createUsersUsing(CreateNewUser::class);
Fortify::loginView(function () {
    return view('auth.login');
});
```

**Routes التلقائية:**
- `/login` - تسجيل الدخول
- `/register` - التسجيل
- `/forgot-password` - طلب إعادة تعيين كلمة المرور
- `/reset-password` - إعادة تعيين كلمة المرور
- `/two-factor-challenge` - تحدي المصادقة الثنائية

---

### 3. **Google2FA Laravel** (`pragmarx/google2fa-laravel: ^2.3`)
**الموقع:** `vendor/pragmarx/google2fa-laravel/`

**الوظيفة:**
- تنفيذ المصادقة الثنائية (Two-Factor Authentication) باستخدام Google Authenticator
- توليد رموز QR Code لإعداد المصادقة الثنائية
- التحقق من رموز TOTP (Time-based One-Time Password)

**آلية العمل:**
1. **توليد Secret Key:** عند تفعيل 2FA، يتم توليد مفتاح سري فريد لكل مستخدم
2. **إنشاء QR Code:** يتم إنشاء QR Code يحتوي على Secret Key ليتم مسحه عبر تطبيق Google Authenticator
3. **التحقق:** عند تسجيل الدخول، يتم التحقق من الرمز المكون من 6 أرقام

**الاستخدام في المشروع:**
```php
// app/Models/User.php
public function twoFactorQrCodeSvg(): string
{
    return app('pragmarx.google2fa')->getQRCodeInline(
        config('app.name'),
        $this->email,
        $this->two_factor_secret
    );
}

// app/Http/Controllers/TwoFactorAuthController.php
$secret = Google2FA::generateSecretKey();
$qrCodeSvg = $user->twoFactorQrCodeSvg();
$valid = Google2FA::verifyKey($secret, $code);
```

**الملفات المرتبطة:**
- `app/Http/Controllers/TwoFactorAuthController.php` - التحكم في 2FA
- `app/Http/Middleware/TwoFactorMiddleware.php` - Middleware للتحقق من 2FA
- `resources/views/auth/two-factor-setup.blade.php` - صفحة إعداد 2FA
- `resources/views/auth/two-factor-challenge.blade.php` - صفحة تحدي 2FA

---

### 4. **Bacon QR Code** (`bacon/bacon-qr-code: ^3.0`)
**الموقع:** `vendor/bacon/bacon-qr-code/`

**الوظيفة:**
- مكتبة لتوليد رموز QR Code
- تستخدم كـ dependency لـ Google2FA Laravel

**آلية العمل:**
- يتم استخدامها داخلياً من قبل `pragmarx/google2fa-laravel`
- توليد QR Code بصيغة SVG أو PNG

**الاستخدام في المشروع:**
- غير مستخدم مباشرة، بل يتم استخدامه عبر Google2FA Laravel
- `app/Models/User.php` → `twoFactorQrCodeSvg()` يستخدمه داخلياً

---

### 5. **Laravel Socialite** (`laravel/socialite: ^5.23`)
**الموقع:** `vendor/laravel/socialite/`

**الوظيفة:**
- تسجيل الدخول عبر OAuth (Google, Facebook, Twitter, etc.)
- في هذا المشروع: تسجيل الدخول عبر Google فقط

**آلية العمل:**
1. **الإعداد:** `config/services.php` - إعدادات Google OAuth
2. **Redirect:** توجيه المستخدم إلى صفحة مصادقة Google
3. **Callback:** معالجة الاستجابة من Google وإنشاء/تسجيل دخول المستخدم

**الاستخدام في المشروع:**
```php
// config/services.php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT', env('APP_URL').'/auth/google/callback'),
]

// app/Http/Controllers/SocialiteController.php
public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}

public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->user();
    // إنشاء أو تحديث المستخدم
    Auth::login($user, true);
}
```

**Routes:**
- `GET /auth/google` - توجيه إلى Google
- `GET /auth/google/callback` - معالجة الاستجابة



## 🛠️ المكتبات للتطوير والاختبار (Development Dependencies)




---





## 📦 المكتبات الأمامية (Frontend Dependencies)

### 17. **Vite** (`vite: ^7.0.7`)
**الموقع:** `node_modules/vite/`

**الوظيفة:**
- Build tool سريع للـ frontend
- بديل حديث لـ Webpack

**الاستخدام:**
```bash
npm run dev    # Development mode
npm run build  # Production build
```

**الملفات:**
- `vite.config.js` - إعدادات Vite
- `resources/js/app.js` - نقطة الدخول للـ JavaScript
- `resources/css/app.css` - نقطة الدخول للـ CSS

---


### 20. **Axios** (`axios: ^1.11.0`)
**الموقع:** `node_modules/axios/`

**الوظيفة:**
- HTTP client للـ JavaScript
- يستخدم لإرسال AJAX requests

---


## 🔄 آلية سير العمل في المشروع

### 1. **تسجيل الدخول العادي:**
```
المستخدم → /login → Fortify → CreateNewUser Action → Database → Session → Dashboard
```

### 2. **تسجيل الدخول عبر Google:**
```
المستخدم → /auth/google → Socialite → Google OAuth → Callback → 
SocialiteController → إنشاء/تحديث User → Auth::login() → Dashboard
```

### 3. **المصادقة الثنائية (2FA):**
```
تسجيل الدخول → Fortify → TwoFactorMiddleware → 
إذا 2FA مفعل → /two-factor-challenge → 
Google2FA::verifyKey() → Dashboard
```

### 4. **إعداد 2FA:**
```
المستخدم → /two-factor/setup → verifyPassword() → 
Google2FA::generateSecretKey() → twoFactorQrCodeSvg() → 
Bacon QR Code → عرض QR Code → المستخدم يمسح → 
enable() → Google2FA::verifyKey() → تفعيل 2FA
```

---

## 📍 مواقع الملفات المهمة

### Authentication:
- `app/Providers/FortifyServiceProvider.php` - إعداد Fortify
- `app/Actions/Fortify/` - منطق المصادقة
- `app/Http/Controllers/TwoFactorAuthController.php` - 2FA Controller
- `app/Http/Controllers/SocialiteController.php` - Google OAuth Controller
- `config/fortify.php` - إعدادات Fortify
- `config/services.php` - إعدادات Google OAuth

### Views:
- `resources/views/auth/login.blade.php` - صفحة تسجيل الدخول
- `resources/views/auth/register.blade.php` - صفحة التسجيل
- `resources/views/auth/two-factor-setup.blade.php` - إعداد 2FA
- `resources/views/auth/two-factor-challenge.blade.php` - تحدي 2FA

### Models:
- `app/Models/User.php` - نموذج المستخدم (يحتوي على TwoFactorAuthenticatable trait)
- `app/Models/Admin.php` - نموذج المسؤول

### Routes:
- `routes/auth.php` - Routes المصادقة
- `routes/web.php` - Routes العامة
- `routes/admin.php` - Routes لوحة التحكم

---

## 🔐 متغيرات البيئة المطلوبة (.env)

```env
# Google OAuth
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT=http://localhost/auth/google/callback

# App
APP_URL=http://localhost
APP_NAME="My Journey"
```
