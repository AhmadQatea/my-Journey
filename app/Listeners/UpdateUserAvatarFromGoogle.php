<?php

namespace App\Listeners;

use App\Events\SocialLogin;

class UpdateUserAvatarFromGoogle
{
    public function handle(SocialLogin $event): void
    {
        if ($event->driver === 'google' && $event->avatar) {
            $event->user->update([
                'avatar' => $event->avatar,
            ]);
        }
    }
}
