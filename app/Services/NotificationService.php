<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Role;
use App\Models\User;
use App\Models\UserNotification;

class NotificationService
{
    /**
     * إنشاء إشعار جديد للمسؤولين
     */
    public static function create(array $data, ?Admin $admin = null): AdminNotification
    {
        $notificationData = [
            'type' => $data['type'] ?? 'info',
            'title' => $data['title'],
            'message' => $data['message'],
            'icon' => $data['icon'] ?? 'fas fa-bell',
            'color' => $data['color'] ?? 'info',
            'link' => $data['link'] ?? null,
            'data' => $data['data'] ?? null,
        ];

        if ($admin) {
            // إشعار لمسؤول محدد
            $notificationData['admin_id'] = $admin->id;

            return AdminNotification::create($notificationData);
        }

        // إشعار لجميع المسؤولين
        $notifications = [];
        $admins = Admin::where('is_active', true)->get();

        foreach ($admins as $adminUser) {
            $notificationData['admin_id'] = $adminUser->id;
            $notifications[] = AdminNotification::create($notificationData);
        }

        return $notifications[0] ?? AdminNotification::create($notificationData);
    }

    /**
     * إشعار للمسؤول الكبير فقط
     */
    public static function notifyBigBoss(array $data): void
    {
        $bigBossRole = Role::where('name', 'big_boss')->first();

        if (! $bigBossRole) {
            return;
        }

        $bigBossAdmins = Admin::where('role_id', $bigBossRole->id)
            ->where('is_active', true)
            ->get();

        foreach ($bigBossAdmins as $admin) {
            self::create($data, $admin);
        }
    }

    /**
     * إشعار لمسؤول المستخدمين والمسؤول الكبير
     */
    public static function notifyUsersAdmins(array $data): void
    {
        $usersAdminRole = Role::where('name', 'users_admin')->first();
        $bigBossRole = Role::where('name', 'big_boss')->first();

        $admins = collect();

        if ($usersAdminRole) {
            $usersAdmins = Admin::where('role_id', $usersAdminRole->id)
                ->where('is_active', true)
                ->get();
            $admins = $admins->merge($usersAdmins);
        }

        if ($bigBossRole) {
            $bigBossAdmins = Admin::where('role_id', $bigBossRole->id)
                ->where('is_active', true)
                ->get();
            $admins = $admins->merge($bigBossAdmins);
        }

        // إزالة التكرارات
        $admins = $admins->unique('id');

        // إذا لم يتم العثور على مسؤولين، أرسل لجميع المسؤولين النشطين
        if ($admins->isEmpty()) {
            $admins = Admin::where('is_active', true)->get();
        }

        foreach ($admins as $admin) {
            self::create($data, $admin);
        }
    }

    /**
     * إشعار لمسؤول الحجوزات والمسؤول الكبير
     */
    public static function notifyBookingAdmins(array $data): void
    {
        $bookingAdminRole = Role::where('name', 'booking_admin')->first();
        $bigBossRole = Role::where('name', 'big_boss')->first();

        $admins = collect();

        if ($bookingAdminRole) {
            $admins = $admins->merge(
                Admin::where('role_id', $bookingAdminRole->id)
                    ->where('is_active', true)
                    ->get()
            );
        }

        if ($bigBossRole) {
            $admins = $admins->merge(
                Admin::where('role_id', $bigBossRole->id)
                    ->where('is_active', true)
                    ->get()
            );
        }

        // إزالة التكرارات
        $admins = $admins->unique('id');

        foreach ($admins as $admin) {
            self::create($data, $admin);
        }
    }

    /**
     * إشعار لمسؤول الموقع والمسؤول الكبير
     */
    public static function notifySiteAdmins(array $data): void
    {
        $siteAdminRole = Role::where('name', 'site_admin')->first();
        $bigBossRole = Role::where('name', 'big_boss')->first();

        $admins = collect();

        if ($siteAdminRole) {
            $admins = $admins->merge(
                Admin::where('role_id', $siteAdminRole->id)
                    ->where('is_active', true)
                    ->get()
            );
        }

        if ($bigBossRole) {
            $admins = $admins->merge(
                Admin::where('role_id', $bigBossRole->id)
                    ->where('is_active', true)
                    ->get()
            );
        }

        // إزالة التكرارات
        $admins = $admins->unique('id');

        foreach ($admins as $admin) {
            self::create($data, $admin);
        }
    }

    /**
     * إشعار عند إجراء مسؤول (للمسؤول الكبير فقط)
     */
    public static function notifyAdminAction(Admin $actor, string $action, string $resource, $resourceId = null, ?string $link = null): void
    {
        // لا نرسل إشعار إذا كان المسؤول هو big_boss نفسه
        if ($actor->hasRole('big_boss') || $actor->is_super_admin) {
            return;
        }

        $actionLabels = [
            'create' => 'إنشاء',
            'update' => 'تعديل',
            'delete' => 'حذف',
            'approve' => 'الموافقة على',
            'reject' => 'رفض',
            'activate' => 'تفعيل',
            'deactivate' => 'إلغاء تفعيل',
        ];

        $resourceLabels = [
            'trip' => 'رحلة',
            'booking' => 'حجز',
            'article' => 'مقال',
            'user' => 'مستخدم',
            'admin' => 'مسؤول',
            'offer' => 'عرض',
            'governorate' => 'محافظة',
            'tourist_spot' => 'مكان سياحي',
            'category' => 'فئة',
            'identity_verification' => 'طلب توثيق هوية',
        ];

        $actionLabel = $actionLabels[$action] ?? $action;
        $resourceLabel = $resourceLabels[$resource] ?? $resource;

        self::notifyBigBoss([
            'type' => 'admin_action',
            'title' => "إجراء من مسؤول: {$actionLabel} {$resourceLabel}",
            'message' => "المسؤول {$actor->name} ({$actor->email}) قام بـ {$actionLabel} {$resourceLabel}",
            'icon' => 'fas fa-user-shield',
            'color' => 'info',
            'link' => $link,
            'data' => [
                'actor_id' => $actor->id,
                'action' => $action,
                'resource' => $resource,
                'resource_id' => $resourceId,
            ],
        ]);
    }

    /**
     * إشعار عن طلب توثيق هوية جديد
     */
    public static function notifyIdentityVerificationRequest($identityVerification): void
    {
        $user = $identityVerification->user;

        try {
            self::notifyUsersAdmins([
                'type' => 'identity_verification',
                'title' => 'طلب توثيق هوية جديد',
                'message' => "المستخدم {$user->full_name} ({$user->email}) قام برفع طلب توثيق هوية",
                'icon' => 'fas fa-id-card',
                'color' => 'warning',
                'link' => route('admin.identity-verifications.show', $identityVerification),
                'data' => [
                    'user_id' => $user->id,
                    'verification_id' => $identityVerification->id,
                ],
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the flow
            \Log::error('Error sending identity verification notification: '.$e->getMessage());
        }
    }

    /**
     * إشعار عن تفعيل المصادقة الثنائية
     */
    public static function notifyTwoFactorEnabled(User $user): void
    {
        self::notifyUsersAdmins([
            'type' => 'two_factor',
            'title' => 'تفعيل المصادقة الثنائية',
            'message' => "المستخدم {$user->full_name} ({$user->email}) قام بتفعيل المصادقة الثنائية (2FA)",
            'icon' => 'fas fa-shield-alt',
            'color' => 'success',
            'link' => route('admin.users.show', $user),
            'data' => [
                'user_id' => $user->id,
            ],
        ]);
    }

    /**
     * إشعار عن حجز جديد
     */
    public static function notifyNewBooking($booking): void
    {
        $user = $booking->user;
        $trip = $booking->trip;

        self::notifyBookingAdmins([
            'type' => 'booking',
            'title' => 'حجز جديد',
            'message' => "المستخدم {$user->full_name} قام بحجز رحلة: {$trip->title}",
            'icon' => 'fas fa-calendar-check',
            'color' => 'success',
            'link' => route('admin.bookings.show', $booking),
            'data' => [
                'booking_id' => $booking->id,
                'user_id' => $user->id,
            ],
        ]);
    }

    /**
     * إشعار عن مقال جديد
     */
    public static function notifyNewArticle($article): void
    {
        $user = $article->user;

        self::notifySiteAdmins([
            'type' => 'article',
            'title' => 'مقال جديد',
            'message' => "المستخدم {$user->full_name} قام بنشر مقال: {$article->title}",
            'icon' => 'fas fa-newspaper',
            'color' => 'info',
            'link' => route('admin.articles.show', $article),
            'data' => [
                'article_id' => $article->id,
                'user_id' => $user->id,
            ],
        ]);
    }

    /**
     * إشعار عن تسجيل مستخدم جديد
     */
    public static function notifyNewUser(User $user): void
    {
        self::notifyUsersAdmins([
            'type' => 'user_registration',
            'title' => 'مستخدم جديد',
            'message' => "تم تسجيل مستخدم جديد: {$user->full_name} ({$user->email})",
            'icon' => 'fas fa-user-plus',
            'color' => 'success',
            'link' => route('admin.users.show', $user),
            'data' => [
                'user_id' => $user->id,
            ],
        ]);
    }

    // ========== User Notifications ==========

    /**
     * إنشاء إشعار جديد للمستخدمين
     */
    public static function createForUser(array $data, ?User $user = null): UserNotification
    {
        $notificationData = [
            'type' => $data['type'] ?? 'info',
            'title' => $data['title'],
            'message' => $data['message'],
            'icon' => $data['icon'] ?? 'fas fa-bell',
            'color' => $data['color'] ?? 'info',
            'link' => $data['link'] ?? null,
            'data' => $data['data'] ?? null,
        ];

        if ($user) {
            $notificationData['user_id'] = $user->id;

            return UserNotification::create($notificationData);
        }

        return UserNotification::create($notificationData);
    }

    /**
     * إشعار عند توثيق الهوية
     */
    public static function notifyIdentityVerified(User $user): void
    {
        self::createForUser([
            'type' => 'identity_verified',
            'title' => 'تم توثيق هويتك',
            'message' => 'تم التحقق من هويتك بنجاح. يمكنك الآن الاستفادة من جميع خدمات الموقع.',
            'icon' => 'fas fa-check-circle',
            'color' => 'success',
            'link' => route('dashboard'),
            'data' => [
                'user_id' => $user->id,
            ],
        ], $user);
    }

    /**
     * إشعار عند رفض طلب توثيق الهوية
     */
    public static function notifyIdentityRejected(User $user, string $reason): void
    {
        self::createForUser([
            'type' => 'identity_rejected',
            'title' => 'تم رفض طلب توثيق الهوية',
            'message' => "تم رفض طلب توثيق هويتك. السبب: {$reason}",
            'icon' => 'fas fa-times-circle',
            'color' => 'danger',
            'link' => route('identity-verification.create'),
            'data' => [
                'user_id' => $user->id,
                'rejection_reason' => $reason,
            ],
        ], $user);
    }

    /**
     * إشعار عند قبول مقال
     */
    public static function notifyArticleApproved($article): void
    {
        $user = $article->user;

        self::createForUser([
            'type' => 'article_approved',
            'title' => 'تم قبول مقالك',
            'message' => "تم قبول مقالك: {$article->title}",
            'icon' => 'fas fa-check',
            'color' => 'success',
            'link' => route('my-articles'),
            'data' => [
                'article_id' => $article->id,
                'user_id' => $user->id,
            ],
        ], $user);
    }

    /**
     * إشعار عند رفض مقال
     */
    public static function notifyArticleRejected($article, ?string $reason = null): void
    {
        $user = $article->user;

        self::createForUser([
            'type' => 'article_rejected',
            'title' => 'تم رفض مقالك',
            'message' => $reason
                ? "تم رفض مقالك: {$article->title}. السبب: {$reason}"
                : "تم رفض مقالك: {$article->title}",
            'icon' => 'fas fa-times',
            'color' => 'danger',
            'link' => route('my-articles'),
            'data' => [
                'article_id' => $article->id,
                'user_id' => $user->id,
                'rejection_reason' => $reason,
            ],
        ], $user);
    }

    /**
     * إشعار عند تأكيد حجز
     */
    public static function notifyBookingConfirmed($booking): void
    {
        $user = $booking->user;
        $trip = $booking->trip;

        // تحديد نوع الإشعار حسب من أنشأ الحجز
        $isCreatedByAdmin = $booking->created_by_admin ?? false;
        $title = $isCreatedByAdmin ? 'تم إنشاء حجز لك' : 'تم تأكيد حجزك';
        $message = $isCreatedByAdmin
            ? "تم إنشاء حجز لك لرحلة: {$trip->title}"
            : "تم تأكيد حجزك لرحلة: {$trip->title}";

        self::createForUser([
            'type' => 'booking_confirmed',
            'title' => $title,
            'message' => $message,
            'icon' => 'fas fa-calendar-check',
            'color' => 'success',
            'link' => route('my-bookings'),
            'data' => [
                'booking_id' => $booking->id,
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'created_by_admin' => $isCreatedByAdmin,
            ],
        ], $user);
    }

    /**
     * إشعار عند رفض حجز
     */
    public static function notifyBookingRejected($booking, ?string $reason = null): void
    {
        $user = $booking->user;
        $trip = $booking->trip;

        self::createForUser([
            'type' => 'booking_rejected',
            'title' => 'تم رفض حجزك',
            'message' => $reason
                ? "تم رفض حجزك لرحلة: {$trip->title}. السبب: {$reason}"
                : "تم رفض حجزك لرحلة: {$trip->title}",
            'icon' => 'fas fa-calendar-times',
            'color' => 'danger',
            'link' => route('my-bookings'),
            'data' => [
                'booking_id' => $booking->id,
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'rejection_reason' => $reason,
            ],
        ], $user);
    }

    /**
     * إشعار عند تحديث حجز
     */
    public static function notifyBookingUpdated($booking, array $changes = []): void
    {
        $user = $booking->user;
        $trip = $booking->trip;

        $changeMessages = [];
        if (isset($changes['guest_count'])) {
            $changeMessages[] = "عدد الضيوف: {$changes['guest_count']}";
        }
        if (isset($changes['booking_date'])) {
            $changeMessages[] = "تاريخ الحجز: {$changes['booking_date']}";
        }
        if (isset($changes['total_price'])) {
            $changeMessages[] = "السعر الإجمالي: {$changes['total_price']}";
        }

        $message = "تم تحديث حجزك لرحلة: {$trip->title}";
        if (! empty($changeMessages)) {
            $message .= '. التغييرات: '.implode(', ', $changeMessages);
        }

        self::createForUser([
            'type' => 'booking_updated',
            'title' => 'تم تحديث حجزك',
            'message' => $message,
            'icon' => 'fas fa-calendar-edit',
            'color' => 'info',
            'link' => route('my-bookings'),
            'data' => [
                'booking_id' => $booking->id,
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'changes' => $changes,
            ],
        ], $user);
    }

    /**
     * إشعار عند تفعيل الحساب
     */
    public static function notifyAccountActivated(User $user): void
    {
        self::createForUser([
            'type' => 'account_activated',
            'title' => 'تم تفعيل حسابك',
            'message' => 'تم تفعيل حسابك بنجاح. يمكنك الآن الاستفادة من جميع خدمات الموقع.',
            'icon' => 'fas fa-check-circle',
            'color' => 'success',
            'link' => route('dashboard'),
            'data' => [
                'user_id' => $user->id,
            ],
        ], $user);
    }

    /**
     * إشعار عند إلغاء تفعيل الحساب
     */
    public static function notifyAccountDeactivated(User $user): void
    {
        self::createForUser([
            'type' => 'account_deactivated',
            'title' => 'تم إلغاء تفعيل حسابك',
            'message' => 'تم إلغاء تفعيل حسابك. يرجى التواصل مع الدعم لمعرفة السبب.',
            'icon' => 'fas fa-pause-circle',
            'color' => 'warning',
            'link' => route('dashboard'),
            'data' => [
                'user_id' => $user->id,
            ],
        ], $user);
    }

    /**
     * إشعار عند ترقية الحساب إلى VIP
     */
    public static function notifyAccountUpgradedToVip(User $user): void
    {
        self::createForUser([
            'type' => 'account_upgraded_vip',
            'title' => 'تم ترقية حسابك إلى VIP',
            'message' => 'تهانينا! تم ترقية حسابك إلى VIP. يمكنك الآن الاستفادة من المميزات الحصرية.',
            'icon' => 'fas fa-crown',
            'color' => 'success',
            'link' => route('dashboard'),
            'data' => [
                'user_id' => $user->id,
            ],
        ], $user);
    }

    /**
     * إشعار عند التحقق من البريد الإلكتروني
     */
    public static function notifyEmailVerified(User $user): void
    {
        self::createForUser([
            'type' => 'email_verified',
            'title' => 'تم التحقق من بريدك الإلكتروني',
            'message' => 'تم التحقق من بريدك الإلكتروني بنجاح.',
            'icon' => 'fas fa-envelope-check',
            'color' => 'success',
            'link' => route('dashboard'),
            'data' => [
                'user_id' => $user->id,
            ],
        ], $user);
    }

    /**
     * إشعار عند تغيير كلمة المرور
     */
    public static function notifyPasswordChanged(User $user): void
    {
        self::createForUser([
            'type' => 'password_changed',
            'title' => 'تم تغيير كلمة المرور',
            'message' => 'تم تغيير كلمة المرور بنجاح. إذا لم تقم بهذا التغيير، يرجى التواصل معنا فوراً.',
            'icon' => 'fas fa-key',
            'color' => 'warning',
            'link' => route('dashboard'),
            'data' => [
                'user_id' => $user->id,
            ],
        ], $user);
    }

    /**
     * إشعار عند تحديث الملف الشخصي
     */
    public static function notifyProfileUpdated(User $user, array $changes = []): void
    {
        $changeMessages = [];
        if (isset($changes['full_name'])) {
            $changeMessages[] = "الاسم: {$changes['full_name']}";
        }
        if (isset($changes['email'])) {
            $changeMessages[] = "البريد الإلكتروني: {$changes['email']}";
        }
        if (isset($changes['phone'])) {
            $changeMessages[] = "الهاتف: {$changes['phone']}";
        }

        $message = 'تم تحديث ملفك الشخصي بنجاح.';
        if (! empty($changeMessages)) {
            $message .= ' التغييرات: '.implode(', ', $changeMessages);
        }

        self::createForUser([
            'type' => 'profile_updated',
            'title' => 'تم تحديث ملفك الشخصي',
            'message' => $message,
            'icon' => 'fas fa-user-edit',
            'color' => 'info',
            'link' => route('profile.show'),
            'data' => [
                'user_id' => $user->id,
                'changes' => $changes,
            ],
        ], $user);
    }

    /**
     * إشعار عند إرسال رسالة من المسؤول
     */
    public static function notifyAdminMessage(User $user, string $subject, string $message): void
    {
        self::createForUser([
            'type' => 'admin_message',
            'title' => $subject,
            'message' => $message,
            'icon' => 'fas fa-envelope',
            'color' => 'info',
            'link' => route('dashboard'),
            'data' => [
                'user_id' => $user->id,
            ],
        ], $user);
    }

    /**
     * إشعار عند تحديث مقال
     */
    public static function notifyArticleUpdated($article, array $changes = []): void
    {
        $user = $article->user;

        $changeMessages = [];
        if (isset($changes['title'])) {
            $changeMessages[] = "العنوان: {$changes['title']}";
        }
        if (isset($changes['status'])) {
            $changeMessages[] = "الحالة: {$changes['status']}";
        }

        $message = "تم تحديث مقالك: {$article->title}";
        if (! empty($changeMessages)) {
            $message .= '. التغييرات: '.implode(', ', $changeMessages);
        }

        self::createForUser([
            'type' => 'article_updated',
            'title' => 'تم تحديث مقالك',
            'message' => $message,
            'icon' => 'fas fa-edit',
            'color' => 'info',
            'link' => route('my-articles'),
            'data' => [
                'article_id' => $article->id,
                'user_id' => $user->id,
                'changes' => $changes,
            ],
        ], $user);
    }
}
