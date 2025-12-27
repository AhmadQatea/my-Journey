<?php

namespace App\Http\Controllers;

use App\Models\IdentityVerification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdentityVerificationController extends Controller
{
    /**
     * عرض صفحة رفع صورة الهوية
     */
    public function create()
    {
        // إعادة تحميل المستخدم من قاعدة البيانات لضمان الحصول على أحدث البيانات
        $user = Auth::user();

        // إعادة تحميل من قاعدة البيانات
        $user = $user->fresh();

        // التحقق من أن الهوية موثقة بالفعل
        if ($user->identity_verified) {
            return redirect()->route('dashboard')
                ->with('info', 'تم توثيق هويتك بالفعل');
        }

        // التحقق من وجود طلب معلق
        $pendingRequest = IdentityVerification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        // التحقق من أن البريد الإلكتروني تم التحقق منه
        $emailNotVerified = is_null($user->email_verified_at);

        // تحديث المستخدم في الجلسة
        Auth::setUser($user);

        return view('identity-verification.create', compact('pendingRequest', 'emailNotVerified'));
    }

    /**
     * حفظ صورة الهوية
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // التحقق من أن البريد الإلكتروني تم التحقق منه
        if (! $user->email_verified_at) {
            return back()->with('error', 'يجب التحقق من البريد الإلكتروني أولاً');
        }

        // التحقق من وجود طلب معلق
        $pendingRequest = IdentityVerification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return back()->with('error', 'لديك طلب معلق بالفعل، يرجى انتظار المراجعة');
        }

        $request->validate([
            'identity_image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ], [
            'identity_image.required' => 'يجب رفع صورة الهوية الشخصية',
            'identity_image.image' => 'يجب أن يكون الملف صورة',
            'identity_image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg أو jpg أو png',
            'identity_image.max' => 'يجب ألا تتجاوز الصورة 2 ميجابايت',
        ]);

        // رفع الصورة
        $imagePath = $request->file('identity_image')->store('identity-verifications', 'public');

        // إنشاء طلب توثيق
        $identityVerification = IdentityVerification::create([
            'user_id' => $user->id,
            'identity_image' => $imagePath,
            'status' => 'pending',
        ]);

        // إرسال إشعار للمسؤولين
        NotificationService::notifyIdentityVerificationRequest($identityVerification);

        return redirect()->route('identity-verification.create')
            ->with('success', 'تم رفع صورة الهوية بنجاح، تنتظر المراجعة من المسؤول');
    }
}
