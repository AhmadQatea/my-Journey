<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user->update($request->only('full_name', 'phone'));

        return redirect()->route('profile.edit')->with('success', 'تم تحديث الملف الشخصي بنجاح');
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

            return back()->with('success', 'تم ترقية حسابك إلى VIP بنجاح!');
        }

        return back()->with('error', 'لا يمكن ترقية حسابك حالياً');
    }
}
