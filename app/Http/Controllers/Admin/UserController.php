<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends AdminController
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'account_type' => 'required|in:visitor,active,vip',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function verifyIdentity(User $user)
    {
        $user->update(['identity_verified' => true]);

        return back()->with('success', 'تم توثيق هوية المستخدم بنجاح');
    }

    public function upgradeToVip(User $user)
    {
        $user->update(['account_type' => 'vip']);

        return back()->with('success', 'تم ترقية المستخدم إلى VIP بنجاح');
    }

    public function destroy(User $user)
    {
        // منع حذف المستخدم إذا كان لديه حجوزات أو مقالات
        if ($user->bookings()->exists() || $user->articles()->exists()) {
            return back()->with('error', 'لا يمكن حذف المستخدم لأنه لديه حجوزات أو مقالات');
        }

        $user->delete();

        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }
}
