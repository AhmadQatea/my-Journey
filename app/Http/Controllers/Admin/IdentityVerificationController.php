<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdentityVerification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdentityVerificationController extends Controller
{
    /**
     * عرض قائمة طلبات توثيق الهوية
     */
    public function index(Request $request)
    {
        $query = IdentityVerification::with(['user', 'reviewer']);

        // التصفية حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // البحث
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $verifications = $query->latest()->paginate(15)->withQueryString();

        // إحصائيات
        $stats = [
            'pending' => IdentityVerification::where('status', 'pending')->count(),
            'approved' => IdentityVerification::where('status', 'approved')->count(),
            'rejected' => IdentityVerification::where('status', 'rejected')->count(),
            'total' => IdentityVerification::count(),
        ];

        return view('admin.identity-verifications.index', compact('verifications', 'stats'));
    }

    /**
     * عرض تفاصيل طلب توثيق الهوية
     */
    public function show(IdentityVerification $identityVerification)
    {
        $identityVerification->load(['user', 'reviewer']);

        return view('admin.identity-verifications.show', compact('identityVerification'));
    }

    /**
     * الموافقة على طلب توثيق الهوية
     */
    public function approve(IdentityVerification $identityVerification)
    {
        $admin = Auth::guard('admin')->user();

        if ($identityVerification->status !== 'pending') {
            return back()->with('error', 'لا يمكن الموافقة على طلب تمت معالجته بالفعل');
        }

        // تحديث حالة الطلب
        $identityVerification->update([
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $user = $identityVerification->user;

        // تحديث حالة المستخدم
        $user->update([
            'identity_verified' => true,
            'identity_front_image' => $identityVerification->identity_image,
        ]);

        // إرسال إشعار للمسؤول الكبير
        NotificationService::notifyAdminAction(
            $admin,
            'approve',
            'identity_verification',
            $identityVerification->id,
            route('admin.identity-verifications.show', $identityVerification)
        );

        // إرسال إشعار للمستخدم
        NotificationService::notifyIdentityVerified($user);

        return back()->with('success', 'تم الموافقة على طلب توثيق الهوية بنجاح');
    }

    /**
     * رفض طلب توثيق الهوية
     */
    public function reject(Request $request, IdentityVerification $identityVerification)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'rejection_reason.required' => 'يجب إدخال سبب الرفض',
            'rejection_reason.min' => 'يجب أن يكون سبب الرفض على الأقل 10 أحرف',
            'rejection_reason.max' => 'يجب ألا يتجاوز سبب الرفض 500 حرف',
        ]);

        $admin = Auth::guard('admin')->user();

        if ($identityVerification->status !== 'pending') {
            return back()->with('error', 'لا يمكن رفض طلب تمت معالجته بالفعل');
        }

        // تحديث حالة الطلب
        $identityVerification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $user = $identityVerification->user;

        // التأكد من أن المستخدم غير موثق
        $user->update([
            'identity_verified' => false,
        ]);

        // إرسال إشعار للمسؤول الكبير
        NotificationService::notifyAdminAction(
            $admin,
            'reject',
            'identity_verification',
            $identityVerification->id,
            route('admin.identity-verifications.show', $identityVerification)
        );

        // إرسال إشعار للمستخدم
        NotificationService::notifyIdentityRejected($user, $request->rejection_reason);

        return back()->with('success', 'تم رفض طلب توثيق الهوية');
    }
}
