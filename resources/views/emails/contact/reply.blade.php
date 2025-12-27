<x-mail::message>
# مرحباً {{ $contactMessage->name }}

شكراً لتواصلك مع **MyJourney**.  
لقد استلمنا رسالتك بالموضوع:

> {{ $contactMessage->subject ?: 'بدون موضوع' }}

وإليك رد فريق الدعم:

> {{ $replyBody }}

يمكنك دائماً الرد على هذا البريد إذا احتجت لأي مساعدة إضافية.

مع أطيب التحيات،<br>
{{ $admin->name }}  
فريق MyJourney
</x-mail::message>
