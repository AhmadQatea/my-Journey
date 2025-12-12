<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetCodeNotification extends Notification
{
    use Queueable;

    public $code;
    public $purpose;

    public function __construct($code, $purpose = 'استعادة كلمة المرور')
    {
        $this->code = $code;
        $this->purpose = $purpose;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $expireMinutes = $this->purpose == 'استعادة كلمة المرور' ? 15 : 10;

        return (new MailMessage)
            ->subject('رمز التحقق - ' . $this->purpose . ' - ' . config('app.name'))
            ->greeting('مرحباً ' . $notifiable->name . '!')
            ->line('لقد طلبت ' . $this->purpose . ' لحسابك.')
            ->line('**رمز التحقق الخاص بك هو:**')
            ->line('## ' . $this->code . ' ##')
            ->line('هذا الرمز صالح لمدة ' . $expireMinutes . ' دقائق فقط.')
            ->line('**نصائح أمنية:**')
            ->line('- لا تشارك هذا الرمز مع أي شخص')
            ->line('- إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة')
            ->line('- تأكد من أن كلمة مرورك قوية وفريدة')
            ->action('الذهاب إلى الموقع', url('/'))
            ->line('شكراً لاستخدامك ' . config('app.name'))
            ->salutation('مع التحية، فريق الدعم');
    }

    public function toArray($notifiable)
    {
        return [
            'code' => $this->code,
            'purpose' => $this->purpose,
        ];
    }
}
