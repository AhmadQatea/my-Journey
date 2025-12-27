<x-mail::message>
# {{ $subject }}

مرحباً **{{ $user->full_name }}**،

نأمل أن تكون بخير. نود التواصل معك بخصوص:

<x-mail::panel>
{!! nl2br(e($message)) !!}
</x-mail::panel>

إذا كان لديك أي استفسارات أو تحتاج إلى مساعدة إضافية، لا تتردد في التواصل معنا.

<x-mail::button :url="route('home')">
زيارة الموقع
</x-mail::button>

مع أطيب التحيات،<br>
**{{ $adminName }}**<br>
فريق {{ config('app.name') }}
</x-mail::message>
