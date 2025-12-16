{{-- resources/views/emails/trips/rejected.blade.php --}}
<x-mail::message>
# رفض رحلتك

عذراً، تم رفض رحلتك **"{{ $trip->title }}"** من قبل فريق الإدارة.

@if($reason)
**سبب الرفض:**
{{ $reason }}
@else
لقد تم رفض رحلتك بناءً على سياسات الموقع.
@endif

<x-mail::panel>
يمكنك تعديل الرحلة وإعادة إرسالها للمراجعة من خلال لوحة التحكم الخاصة بك.
</x-mail::panel>

<x-mail::button :url="route('vip.trips.edit', $trip)">
تعديل الرحلة
</x-mail::button>

شكراً لاستخدامك منصة رحلاتي،
فريق الدعم الفني
</x-mail::message>
