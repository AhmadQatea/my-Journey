# أوامر Git لرفع التحديثات على GitHub

## ملاحظة: قم بتنفيذ هذه الأوامر بالترتيب

---

## 1. إضافة جميع الملفات الجديدة والمعدلة

```bash
# إضافة جميع الملفات
git add .
```

---

## 2. Commit 1: تطبيق Multi-Guard System

```bash
git commit -m "feat: تطبيق نظام Multi-Guard للمستخدمين والإداريين

- إضافة guard منفصل للإداريين (admin) في config/auth.php
- إنشاء Admin model مع helper methods
- إنشاء AdminController base class مع helper methods
- تحديث جميع Admin controllers لاستخدام AdminController
- إضافة AdminFactory للاختبارات
- إنشاء tests للتحقق من عمل Multi-Guard بشكل صحيح"
```

---

## 3. Commit 2: إضافة Authentication Controllers

```bash
git commit -m "feat: إضافة Controllers للمصادقة والإدارة

- إنشاء AuthController للمستخدمين العاديين
- إنشاء Admin/AuthController للإداريين
- فصل نظام تسجيل الدخول للمستخدمين والإداريين
- إضافة middleware للحماية والتحقق من الصلاحيات"
```

---

## 4. Commit 3: إضافة Routes والمسارات

```bash
git commit -m "feat: إضافة Routes للمستخدمين والإداريين

- إضافة routes/admin.php للمسارات الإدارية
- تحديث routes/web.php للمسارات العامة
- إضافة routes/auth.php للمصادقة الاجتماعية
- حماية المسارات باستخدام guards منفصلة"
```

---

## 5. Commit 4: إضافة Views وواجهات المستخدم

```bash
git commit -m "feat: إضافة Views للمصادقة والإدارة

- إنشاء views/auth/login.blade.php للمستخدمين
- إنشاء views/auth/register.blade.php للمستخدمين
- إنشاء views/admin/auth/login.blade.php للإداريين
- إضافة layouts وcomponents
- تصميم واجهات مستخدم احترافية"
```

---

## 6. Commit 5: تحديث تصميم Auth بألوان عصرية

```bash
git commit -m "style: تحديث تصميم صفحات المصادقة بألوان عصرية

- إعادة كتابة auth.css بألوان عصرية للموقع السياحي
- إضافة خطوط مموجة رفيعة احترافية في الخلفية
- استخدام ألوان: ocean-blue, tropical-teal, sunset-orange
- تحسين التصميم المتجاوب
- تبسيط نموذج التسجيل (إزالة الحقول الاختيارية المعقدة)"
```

---

## 7. Commit 6: إضافة Models والعلاقات

```bash
git commit -m "feat: إضافة Models والعلاقات

- إنشاء Admin model مع علاقات Roles
- تحديث User model مع علاقات جديدة
- إضافة Role model مع نظام الصلاحيات
- إضافة helper methods في Models"
```

---

## 8. Commit 7: إضافة Migrations

```bash
git commit -m "feat: إضافة Migrations لقاعدة البيانات

- إنشاء جدول admins
- إضافة role_id إلى جدول users
- إنشاء جدول sessions
- إعداد قاعدة البيانات لنظام Multi-Guard"
```

---

## 9. Commit 8: إضافة Seeders

```bash
git commit -m "feat: إضافة Seeders للبيانات الأولية

- إنشاء AdminSeeder لإنشاء حسابات إدارية
- إضافة بيانات تجريبية للإداريين"
```

---

## 10. Commit 9: إضافة Tests

```bash
git commit -m "test: إضافة Tests لنظام Multi-Guard

- إنشاء MultiGuardTest للتحقق من عمل النظام
- اختبار تسجيل دخول المستخدمين والإداريين
- اختبار استقلالية الـ guards
- اختبار حماية المسارات"
```

---

## 11. Commit 10: تحديث Configuration

```bash
git commit -m "config: تحديث إعدادات Laravel

- تحديث config/auth.php لدعم Multi-Guard
- تحديث FortifyServiceProvider
- تحديث bootstrap/app.php
- إضافة middleware aliases"
```

---

## 12. رفع التحديثات إلى GitHub

```bash
# رفع جميع التحديثات إلى GitHub
git push origin main
```

---

## أوامر بديلة (إذا أردت commit واحد فقط)

إذا كنت تفضل commit واحد شامل:

```bash
# إضافة جميع الملفات
git add .

# Commit شامل
git commit -m "feat: تطبيق نظام Multi-Guard كامل للموقع السياحي

التحديثات الرئيسية:
- تطبيق Multi-Guard للمستخدمين والإداريين
- إضافة Admin model و AdminController
- إنشاء views منفصلة للمصادقة
- تحديث تصميم auth.css بألوان عصرية وخطوط مموجة
- إضافة tests و migrations و seeders
- تحسين UX/UI للموقع السياحي"

# رفع التحديثات
git push origin main
```

---

## ملاحظات مهمة:

1. **قبل الرفع**: تأكد من أن جميع الملفات تعمل بشكل صحيح
2. **اختبار**: قم بتشغيل `php artisan test` للتأكد من أن كل شيء يعمل
3. **النسخ الاحتياطي**: تأكد من وجود نسخة احتياطية قبل الرفع
4. **الفرع**: إذا كنت تعمل على فرع آخر، استبدل `main` باسم الفرع الخاص بك

