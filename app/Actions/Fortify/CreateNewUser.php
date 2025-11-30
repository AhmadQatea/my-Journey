<?php

namespace App\Actions\Fortify;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => $this->passwordRules(),
            'identity_front_image' => ['nullable', 'image', 'max:2048'],
            'identity_back_image' => ['nullable', 'image', 'max:2048'],
        ])->validate();

        // الحصول على دور المستخدم العادي (يجب أن يكون موجوداً في قاعدة البيانات)
        $userRole = Role::where('name', 'user')->first();

        $userData = [
            'full_name' => $input['full_name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'password' => Hash::make($input['password']),
            'account_type' => 'visitor',
            'role_id' => $userRole ? $userRole->id : null,
        ];

        // رفع صور البطاقة الشخصية إذا تم إرسالها
        if (isset($input['identity_front_image']) && $input['identity_front_image']->isValid()) {
            $userData['identity_front_image'] = $input['identity_front_image']->store('identities', 'public');
        }

        if (isset($input['identity_back_image']) && $input['identity_back_image']->isValid()) {
            $userData['identity_back_image'] = $input['identity_back_image']->store('identities', 'public');
        }

        return User::create($userData);
    }
}
