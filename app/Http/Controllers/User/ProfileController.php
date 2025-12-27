<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // جلب عدد الحجوزات والمقالات
        $bookingsCount = $user->bookings()->count();
        $articlesCount = $user->articles()->count();

        return view('website.user.profile.show', compact('user', 'bookingsCount', 'articlesCount'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('website.user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $oldName = $user->full_name;
        $data = ['full_name' => $request->full_name];

        // معالجة رفع الصورة
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة إن وجدت
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }

            // رفع الصورة الجديدة
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        // إرسال إشعار عند التغييرات
        $changes = [];
        if ($oldName !== $request->full_name) {
            $changes['full_name'] = $request->full_name;
        }
        if ($request->hasFile('avatar')) {
            $changes['avatar'] = 'تم تحديث الصورة';
        }

        if (! empty($changes)) {
            NotificationService::notifyProfileUpdated($user, $changes);
        }

        return redirect()->route('profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function verifyIdentity(Request $request)
    {
        $request->validate([
            'identity_front' => ['required', 'image', 'max:2048'],
            'identity_back' => ['required', 'image', 'max:2048'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // رفع الصور
        $frontPath = $request->file('identity_front')->store('identities', 'public');
        $backPath = $request->file('identity_back')->store('identities', 'public');

        $user->update([
            'identity_front_image' => $frontPath,
            'identity_back_image' => $backPath,
            'identity_verified' => false, // تنتظر التحقق من المسؤول
        ]);

        return redirect()->route('profile.edit')->with('success', 'تم رفع صور الهوية بنجاح، تنتظر التحقق من المسؤول');
    }

    public function upgradeAccount()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->total_bookings >= 5 && ! $user->isVip()) {
            $user->upgradeToVip();
            NotificationService::notifyAccountUpgradedToVip($user);

            return back()->with('success', 'تم ترقية حسابك إلى VIP بنجاح!');
        }

        return back()->with('error', 'لا يمكن ترقية حسابك حالياً');
    }
}
