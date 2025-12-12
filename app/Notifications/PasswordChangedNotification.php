<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تم تغيير كلمة مرور حسابك - ' . config('app.name'))
            ->greeting('مرحباً ' . $notifiable->name . '!')
            ->line('تم تغيير كلمة مرور حسابك بنجاح.')
            ->line('**الوقت:** ' . now()->format('Y/m/d H:i'))
            ->line('**عنوان IP:** ' . request()->ip())
            ->line('**المتصفح:** ' . request()->header('User-Agent'))
            ->action('عرض الحساب', url('/dashboard'))
            ->line('إذا لم تقم بهذا التغيير، يرجى التواصل مع الدعم فوراً.')
            ->salutation('مع التحية، فريق ' . config('app.name'));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'تم تغيير كلمة المرور',
            'message' => 'تم تغيير كلمة مرور حسابك بنجاح.',
            'time' => now(),
            'ip' => request()->ip(),
        ];
    }
}
