# دليل شامل لمجلدات Laravel المهمة

## 📁 1. مجلد Requests (`app/Http/Requests/`)

### الوظيفة:
مجلد **Form Requests** يحتوي على فئات التحقق من صحة البيانات (Validation) المرسلة من النماذج.

### الفوائد:
1. **تنظيم كود التحقق:** فصل قواعد التحقق عن Controllers
2. **إعادة الاستخدام:** يمكن استخدام نفس Request في عدة Controllers
3. **رسائل خطأ مخصصة:** تخصيص رسائل الخطأ باللغة العربية
4. **التحقق من الصلاحيات:** يمكن التحقق من صلاحيات المستخدم قبل التحقق من البيانات

### المحتويات في المشروع:
```
app/Http/Requests/
├── ArticleRequest.php          # التحقق من بيانات المقالات
├── BookingRequest.php          # التحقق من بيانات الحجوزات
├── OfferRequest.php            # التحقق من بيانات العروض
├── StoreAdminRequest.php       # التحقق من بيانات إنشاء مسؤول
├── UpdateAdminRequest.php      # التحقق من بيانات تحديث مسؤول
├── StoreRoleRequest.php        # التحقق من بيانات إنشاء دور
├── UpdateRoleRequest.php       # التحقق من بيانات تحديث دور
├── TripRequest.php             # التحقق من بيانات الرحلات
└── VipTripRequest.php          # التحقق من بيانات رحلات VIP
```

### مثال من المشروع:

```php
// app/Http/Requests/ArticleRequest.php
class ArticleRequest extends FormRequest
{
    // التحقق من الصلاحيات
    public function authorize(): bool
    {
        return true; // أو يمكن التحقق من صلاحيات المستخدم
    }

    // قواعد التحقق
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:100',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    // رسائل الخطأ المخصصة
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المقال مطلوب.',
            'content.min' => 'محتوى المقال يجب أن يكون 100 حرف على الأقل.',
        ];
    }
}
```

### الاستخدام في Controller:

```php
// app/Http/Controllers/ArticleController.php
public function store(ArticleRequest $request)
{
    // البيانات هنا مضمونة أنها صحيحة ومتحقق منها
    $validated = $request->validated();
    // ...
}
```

### الفوائد العملية:
- ✅ **كود أنظف:** Controllers أصغر وأسهل للقراءة
- ✅ **أمان أفضل:** التحقق من البيانات قبل المعالجة
- ✅ **صيانة أسهل:** تعديل قواعد التحقق في مكان واحد
- ✅ **اختبار أسهل:** يمكن اختبار Request بشكل منفصل

---

## 📁 2. مجلد Policies (`app/Policies/`)

### الوظيفة:
مجلد **Authorization Policies** يحتوي على فئات تحدد من يمكنه تنفيذ إجراءات معينة على Models.

### الفوائد:
1. **التحكم في الصلاحيات:** تحديد من يمكنه إنشاء/تعديل/حذف الموارد
2. **منطق الصلاحيات المركزي:** كل منطق الصلاحيات في مكان واحد
3. **سهولة الاختبار:** يمكن اختبار Policies بشكل منفصل
4. **إعادة الاستخدام:** يمكن استخدام نفس Policy في عدة أماكن

### المحتويات في المشروع:
```
app/Policies/
└── (فارغ حالياً)
```

**ملاحظة:** هذا المشروع يستخدم **Gates** بدلاً من Policies (في `AppServiceProvider`)

### مثال على Policy (لو تم استخدامها):

```php
// app/Policies/ArticlePolicy.php
class ArticlePolicy
{
    // من يمكنه عرض المقالات
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_articles');
    }

    // من يمكنه إنشاء مقال
    public function create(User $user): bool
    {
        return $user->hasPermission('create_articles');
    }

    // من يمكنه تحديث مقال
    public function update(User $user, Article $article): bool
    {
        // صاحب المقال أو مسؤول
        return $user->id === $article->user_id || 
               $user->hasPermission('update_articles');
    }

    // من يمكنه حذف مقال
    public function delete(User $user, Article $article): bool
    {
        return $user->id === $article->user_id || 
               $user->hasPermission('delete_articles');
    }
}
```

### الاستخدام في Controller:

```php
// في Controller
public function update(ArticleRequest $request, Article $article)
{
    $this->authorize('update', $article);
    // ...
}

// أو في Blade
@can('update', $article)
    <a href="{{ route('articles.edit', $article) }}">تعديل</a>
@endcan
```

### الفرق بين Policies و Gates:

| Policies | Gates |
|----------|-------|
| مرتبطة بـ Model محدد | عامة وليست مرتبطة بـ Model |
| `$this->authorize('update', $article)` | `Gate::allows('manage_articles')` |
| أفضل للموارد (Resources) | أفضل للصلاحيات العامة |

### في هذا المشروع:
يتم استخدام **Gates** في `AppServiceProvider`:

```php
// app/Providers/AppServiceProvider.php
Gate::define('manage_articles', function ($admin = null) {
    $admin = $admin ?? Auth::guard('admin')->user();
    return $admin && ($admin->isSuperAdmin() || 
           $admin->hasPermission('manage_articles'));
});
```

---

## 📁 3. مجلد Providers (`app/Providers/`)

### الوظيفة:
مجلد **Service Providers** يحتوي على فئات تسجيل وتهيئة خدمات التطبيق.

### الفوائد:
1. **تسجيل الخدمات:** ربط Interfaces بـ Implementations
2. **تهيئة التطبيق:** إعداد Services, Gates, View Composers, etc.
3. **تنظيم الكود:** فصل منطق الإعداد عن منطق التطبيق
4. **تحميل عند الطلب:** Services تُحمّل فقط عند الحاجة

### المحتويات في المشروع:
```
app/Providers/
├── AppServiceProvider.php          # Provider الرئيسي للتطبيق
├── EventServiceProvider.php        # Provider للأحداث (Events)
└── FortifyServiceProvider.php     # Provider لـ Laravel Fortify
```

### 1. AppServiceProvider (`app/Providers/AppServiceProvider.php`)

**الوظيفة:**
- تسجيل Services عامة
- تعريف Gates للصلاحيات
- Route Model Binding
- View Composers

**مثال من المشروع:**

```php
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Route Model Binding
        Route::bind('admin', function ($value) {
            return Admin::findOrFail($value);
        });

        // تعريف Gates للصلاحيات
        Gate::define('manage_articles', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();
            return $admin && ($admin->isSuperAdmin() || 
                   $admin->hasPermission('manage_articles'));
        });

        // View Composer - تمرير بيانات لجميع views
        View::composer('admin.*', function ($view) {
            $currentAdmin = Auth::guard('admin')->user();
            $view->with('currentAdmin', $currentAdmin);
        });
    }

    public function register(): void
    {
        // تسجيل Services (مثل Binding Interfaces)
    }
}
```

### 2. EventServiceProvider (`app/Providers/EventServiceProvider.php`)

**الوظيفة:**
- ربط Events بـ Listeners
- تعريف Events و Listeners

**مثال:**

```php
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmail::class,
        ],
    ];
}
```

### 3. FortifyServiceProvider (`app/Providers/FortifyServiceProvider.php`)

**الوظيفة:**
- إعداد Laravel Fortify
- ربط Actions (CreateNewUser, UpdateUserPassword, etc.)
- تعريف Views للمصادقة
- Rate Limiting

**مثال من المشروع:**

```php
class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ربط Actions
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);

        // تعريف Views
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // Rate Limiting
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email);
        });
    }
}
```

### التسجيل في Laravel 12:

في Laravel 12، يتم تسجيل Providers في `bootstrap/providers.php`:

```php
// bootstrap/providers.php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
];
```

---

## 📁 4. مجلد Actions (`app/Actions/`)

### الوظيفة:
مجلد **Action Classes** يحتوي على فئات تنفذ إجراءات محددة (Single Responsibility Principle).

### الفوائد:
1. **فصل المنطق:** فصل منطق العمل عن Controllers
2. **إعادة الاستخدام:** يمكن استخدام نفس Action في عدة أماكن
3. **سهولة الاختبار:** اختبار Actions بشكل منفصل
4. **كود أنظف:** Controllers أصغر وأبسط

### المحتويات في المشروع:
```
app/Actions/
└── Fortify/
    ├── CreateNewUser.php                    # إنشاء مستخدم جديد
    ├── PasswordValidationRules.php          # قواعد التحقق من كلمة المرور
    ├── ResetUserPassword.php                # إعادة تعيين كلمة المرور
    ├── UpdateUserPassword.php               # تحديث كلمة المرور
    └── UpdateUserProfileInformation.php     # تحديث معلومات الملف الشخصي
```

### مثال من المشروع:

```php
// app/Actions/Fortify/CreateNewUser.php
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        // التحقق من البيانات
        Validator::make($input, [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        // الحصول على دور المستخدم
        $userRole = Role::where('name', 'user')->first();

        // إنشاء المستخدم
        return User::create([
            'full_name' => $input['full_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id' => $userRole?->id,
        ]);
    }
}
```

### مثال آخر:

```php
// app/Actions/Fortify/UpdateUserPassword.php
class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ])->validated();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
```

### الاستخدام:

```php
// في FortifyServiceProvider
Fortify::createUsersUsing(CreateNewUser::class);
Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);

// أو مباشرة في Controller
$action = new CreateNewUser();
$user = $action->create($request->validated());
```

### متى تستخدم Actions؟

✅ **استخدم Actions عندما:**
- لديك منطق معقد يحتاج فصل
- تريد إعادة استخدام المنطق في عدة أماكن
- تريد جعل Controllers أبسط
- تريد اختبار المنطق بشكل منفصل

❌ **لا تستخدم Actions عندما:**
- المنطق بسيط جداً (مثل `User::create()`)
- المنطق مرتبط مباشرة بـ Controller واحد فقط

---

## 📁 5. مجلد Console (`app/Console/`)

### الوظيفة:
مجلد **Artisan Commands** يحتوي على أوامر سطر الأوامر المخصصة.

### الفوائد:
1. **أتمتة المهام:** تنفيذ مهام متكررة تلقائياً
2. **صيانة قاعدة البيانات:** تنظيف البيانات القديمة
3. **معالجة البيانات:** معالجة بيانات كبيرة في الخلفية
4. **إعداد النظام:** مهام إعداد أولية

### المحتويات في المشروع:
```
app/Console/
└── Commands/
    └── CleanupTwoFactor.php    # تنظيف بيانات 2FA غير المفعلة
```

### مثال من المشروع:

```php
// app/Console/Commands/CleanupTwoFactor.php
class CleanupTwoFactor extends Command
{
    protected $signature = '2fa:cleanup';
    protected $description = 'تنظيف بيانات 2FA للمستخدمين غير المفعلين';

    public function handle(): int
    {
        $count = User::whereNull('two_factor_confirmed_at')
            ->whereNotNull('two_factor_secret')
            ->where('updated_at', '<', now()->subDays(30))
            ->update([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
            ]);

        $this->info("تم تنظيف بيانات $count مستخدم");

        return Command::SUCCESS;
    }
}
```

### الاستخدام:

```bash
# تنفيذ الأمر مباشرة
php artisan 2fa:cleanup

# أو جدولة في cron
# في app/Console/Kernel.php (Laravel 11+)
# أو في routes/console.php
```

### أنواع Commands:

#### 1. **Simple Command:**
```php
protected $signature = 'users:count';
protected $description = 'عرض عدد المستخدمين';

public function handle(): int
{
    $count = User::count();
    $this->info("عدد المستخدمين: $count");
    return Command::SUCCESS;
}
```

#### 2. **Command with Arguments:**
```php
protected $signature = 'user:delete {id}';
protected $description = 'حذف مستخدم';

public function handle(): int
{
    $id = $this->argument('id');
    User::findOrFail($id)->delete();
    $this->info("تم حذف المستخدم $id");
    return Command::SUCCESS;
}
```

#### 3. **Command with Options:**
```php
protected $signature = 'users:export {--format=csv}';
protected $description = 'تصدير المستخدمين';

public function handle(): int
{
    $format = $this->option('format');
    // ...
    return Command::SUCCESS;
}
```

#### 4. **Interactive Command:**
```php
public function handle(): int
{
    $name = $this->ask('ما اسمك؟');
    $email = $this->ask('ما بريدك الإلكتروني؟');
    $confirm = $this->confirm('هل تريد المتابعة؟');

    if ($confirm) {
        // ...
    }

    return Command::SUCCESS;
}
```

### جدولة Commands:

في Laravel 12، يمكن جدولة Commands في `routes/console.php`:

```php
// routes/console.php
use Illuminate\Support\Facades\Schedule;

Schedule::command('2fa:cleanup')->daily();
Schedule::command('users:export')->weekly();
```

### أمثلة على Commands مفيدة:

```php
// تنظيف البيانات القديمة
php artisan cleanup:old-data

// إرسال تقارير
php artisan reports:send

// معالجة الصور
php artisan images:optimize

// تصدير البيانات
php artisan export:users

// استيراد البيانات
php artisan import:products
```

---

## 📊 مقارنة سريعة

| المجلد | الوظيفة | متى تستخدمه |
|--------|---------|-------------|
| **Requests** | التحقق من صحة البيانات | عند استقبال بيانات من Forms |
| **Policies** | التحكم في الصلاحيات | عند الحاجة لصلاحيات مرتبطة بـ Models |
| **Providers** | تسجيل وتهيئة Services | عند إعداد Services, Gates, View Composers |
| **Actions** | تنفيذ إجراءات محددة | عند فصل منطق معقد عن Controllers |
| **Console** | أوامر Artisan | عند الحاجة لأتمتة مهام أو صيانة |

---

## 🔗 العلاقات بين المجلدات

```
Request → Controller → Action → Model → Database
         ↓
      Policy (Authorization)
         ↓
      Provider (Service Registration)
         ↓
      Console (Scheduled Tasks)
```

---

## 💡 نصائح وأفضل الممارسات

### Requests:
- ✅ استخدم Request لكل Form مهم
- ✅ أضف رسائل خطأ واضحة بالعربية
- ✅ استخدم `authorize()` للتحقق من الصلاحيات

### Policies:
- ✅ استخدم Policies للموارد (Resources)
- ✅ استخدم Gates للصلاحيات العامة
- ✅ اختبر Policies بشكل منفصل

### Providers:
- ✅ ضع منطق الإعداد في `boot()`
- ✅ ضع تسجيل Services في `register()`
- ✅ استخدم View Composers لتقليل التكرار

### Actions:
- ✅ استخدم Actions للمنطق المعقد
- ✅ اجعل Actions قابلة لإعادة الاستخدام
- ✅ اختبر Actions بشكل منفصل

### Console:
- ✅ استخدم Commands للمهام المتكررة
- ✅ جدول Commands للمهام الدورية
- ✅ أضف رسائل واضحة للمستخدم

---

## 📝 ملخص

1. **Requests:** التحقق من البيانات المرسلة من Forms
2. **Policies:** التحكم في الصلاحيات للموارد (Models)
3. **Providers:** تسجيل وتهيئة Services و Gates
4. **Actions:** تنفيذ إجراءات محددة (فصل المنطق)
5. **Console:** أوامر Artisan للأتمتة والصيانة

كل مجلد له دور محدد في بنية Laravel ويساعد في:
- ✅ تنظيم الكود
- ✅ إعادة الاستخدام
- ✅ سهولة الصيانة
- ✅ سهولة الاختبار

