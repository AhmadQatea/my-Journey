<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Admin::with(['role']);

        // Filtering
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        if ($request->filled('is_super_admin')) {
            $query->where('is_super_admin', $request->is_super_admin === '1');
        }

        $admins = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $totalAdmins = Admin::count();
        $superAdmins = Admin::where('is_super_admin', true)->count();
        $activeAdmins = Admin::where('is_active', true)->count();
        $inactiveAdmins = Admin::where('is_active', false)->count();

        // Roles distribution
        $rolesDistribution = Role::withCount('admins')->get()
            ->map(function ($role) {
                return [
                    'role' => $role->name,
                    'count' => $role->admins_count,
                ];
            });

        $roles = Role::all();

        return view('admin.admins.index', compact(
            'admins',
            'totalAdmins',
            'superAdmins',
            'activeAdmins',
            'inactiveAdmins',
            'rolesDistribution',
            'roles'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['is_super_admin'] = $request->has('is_super_admin');
        $data['is_active'] = $request->has('is_active') ? true : ($request->input('is_active', true));

        $admin = Admin::create($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم إنشاء المسؤول بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        $admin->load(['role']);

        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        // Ensure we have the correct admin
        $adminToEdit = Admin::findOrFail($admin->id);
        $roles = Role::all();

        return view('admin.admins.edit', [
            'admin' => $adminToEdit,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        // Ensure we're updating the correct admin by ID
        $adminId = $admin->id ?? $request->route('admin');

        // Find the specific admin to ensure we're updating the right one
        $adminToUpdate = Admin::findOrFail($adminId);

        $data = $request->validated();

        // Update password only if provided
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_super_admin'] = $request->has('is_super_admin');
        $data['is_active'] = $request->has('is_active');

        // Update only this specific admin
        $adminToUpdate->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم تحديث المسؤول بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        // Prevent deleting yourself
        if ($admin->id === auth('admin')->id()) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم حذف المسؤول بنجاح');
    }
}
