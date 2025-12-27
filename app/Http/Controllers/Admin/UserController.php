<?php

namespace App\Http\Controllers\Admin;

use App\Mail\ContactUserMail;
use App\Models\Role;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends AdminController
{
    public function index(Request $request)
    {
        $query = User::with(['role'])->withCount(['bookings', 'articles']);

        // Filtering
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('account_type') && $request->account_type !== 'all') {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('email_verified')) {
            if ($request->email_verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $perPage = $request->get('per_page', 5);
        $users = $query->latest()->paginate($perPage)->withQueryString();

        // Stats
        $stats = [
            'total' => User::count(),
            'visitor' => User::where('account_type', 'visitor')->count(),
            'active' => User::where('account_type', 'active')->count(),
            'vip' => User::where('account_type', 'vip')->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
        ];

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'stats', 'roles'));
    }

    public function show(User $user)
    {
        $user->load(['role'])
            ->loadCount(['bookings', 'articles']);

        // Recent bookings
        $recentBookings = $user->bookings()
            ->with(['trip.governorate'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent articles
        $recentArticles = $user->articles()
            ->latest()
            ->limit(6)
            ->get();

        // Statistics
        $completedBookings = $user->bookings()
            ->where('status', 'مؤكدة')
            ->count();

        $totalSpent = $user->bookings()
            ->where('status', 'مؤكدة')
            ->sum('total_price');

        return view('admin.users.show', compact(
            'user',
            'recentBookings',
            'recentArticles',
            'completedBookings',
            'totalSpent'
        ));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'account_type' => 'required|in:visitor,active,vip',
        ]);

        $oldAccountType = $user->account_type;
        $oldName = $user->full_name;
        $oldEmail = $user->email;
        $oldPhone = $user->phone;

        $user->update($request->only(['full_name', 'email', 'phone', 'account_type']));

        // إرسال إشعارات عند التغييرات
        $changes = [];
        if ($oldName !== $request->full_name) {
            $changes['full_name'] = $request->full_name;
        }
        if ($oldEmail !== $request->email) {
            $changes['email'] = $request->email;
        }
        if ($oldPhone !== $request->phone) {
            $changes['phone'] = $request->phone;
        }

        if (! empty($changes)) {
            NotificationService::notifyProfileUpdated($user, $changes);
        }

        // إشعار عند تغيير نوع الحساب
        if ($oldAccountType !== $request->account_type) {
            if ($request->account_type === 'vip' && $oldAccountType !== 'vip') {
                NotificationService::notifyAccountUpgradedToVip($user);
            } elseif ($request->account_type === 'active' && $oldAccountType === 'visitor') {
                NotificationService::notifyAccountActivated($user);
            } elseif ($request->account_type === 'visitor' && $oldAccountType !== 'visitor') {
                NotificationService::notifyAccountDeactivated($user);
            }
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function activate(User $user)
    {
        // تفعيل المستخدم عن طريق تغيير نوع الحساب إلى active
        if ($user->account_type === 'visitor') {
            $user->update(['account_type' => 'active']);
            NotificationService::notifyAccountActivated($user);
        }

        return back()->with('success', 'تم تفعيل المستخدم بنجاح');
    }

    public function deactivate(User $user)
    {
        // إلغاء تفعيل المستخدم عن طريق تغيير نوع الحساب إلى visitor
        $user->update(['account_type' => 'visitor']);
        NotificationService::notifyAccountDeactivated($user);

        return back()->with('success', 'تم إلغاء تفعيل المستخدم بنجاح');
    }

    public function verify(User $user)
    {
        $user->update(['email_verified_at' => now()]);
        NotificationService::notifyEmailVerified($user);

        return back()->with('success', 'تم التحقق من البريد الإلكتروني بنجاح');
    }

    public function verifyIdentity(User $user)
    {
        $user->update(['identity_verified' => true]);
        NotificationService::notifyIdentityVerified($user);

        return back()->with('success', 'تم توثيق هوية المستخدم بنجاح');
    }

    public function upgradeToVip(User $user)
    {
        $user->update(['account_type' => 'vip']);
        NotificationService::notifyAccountUpgradedToVip($user);

        return back()->with('success', 'تم ترقية المستخدم إلى VIP بنجاح');
    }

    /**
     * عرض نموذج التواصل مع المستخدم
     */
    public function showContactForm(User $user)
    {
        return view('admin.users.contact', compact('user'));
    }

    /**
     * إرسال رسالة إلى المستخدم
     */
    public function sendContactMessage(Request $request, User $user)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        $admin = Auth::guard('admin')->user();

        try {
            Mail::to($user->email)->send(
                new ContactUserMail(
                    $user,
                    $request->subject,
                    $request->message,
                    $admin->name
                )
            );

            // إرسال إشعار للمستخدم
            NotificationService::notifyAdminMessage($user, $request->subject, $request->message);

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'تم إرسال الرسالة إلى المستخدم بنجاح');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إرسال الرسالة: '.$e->getMessage());
        }
    }
}
