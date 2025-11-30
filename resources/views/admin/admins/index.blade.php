@extends('admin.layouts.admin')

@section('title', 'Admins Management')
@section('page-title', 'Admins Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Administrators</h3>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Admin
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role Type</th>
                        <th>Roles</th>
                        <th>Super Admin</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-medium">{{ $admin->name }}</h4>
                                    <p class="text-sm text-gray-500">ID: {{ $admin->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <span class="badge badge-info">{{ $admin->role_type }}</span>
                        </td>
                        <td>
                            @foreach($admin->roles as $role)
                                <span class="badge badge-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($admin->is_super_admin)
                                <span class="badge badge-success">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $admin->is_active ? 'success' : 'danger' }}">
                                {{ $admin->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            {{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i') : 'Never' }}
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($admin->id !== auth()->guard('admin')->id())
                                <button class="btn btn-danger btn-sm delete-item" 
                                        data-id="{{ $admin->id }}" 
                                        data-type="admin"
                                        data-url="{{ route('admin.admins.destroy', ':id') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $admins->links() }}
        </div>
    </div>
</div>

<!-- Admin Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #3b82f6;">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-number">{{ $totalAdmins }}</div>
        <div class="stat-label">Total Admins</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #10b981;">
            <i class="fas fa-crown"></i>
        </div>
        <div class="stat-number">{{ $superAdmins }}</div>
        <div class="stat-label">Super Admins</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-number">{{ $activeAdmins }}</div>
        <div class="stat-label">Active Admins</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fce7f3; color: #ec4899;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-number">{{ $recentLogins }}</div>
        <div class="stat-label">Recent Logins (7d)</div>
    </div>
</div>

<!-- Roles Distribution Chart -->
<div class="card mt-6">
    <div class="card-header">
        <h3 class="card-title">Roles Distribution</h3>
    </div>
    <div class="card-body">
        <canvas id="rolesChart" height="100"></canvas>
    </div>
</div>

@push('scripts')
<script>
    // Set chart data for global access
    window.rolesChartLabels = @json($rolesDistribution->pluck('role'));
    window.rolesChartData = @json($rolesDistribution->pluck('count'));
</script>
@endpush
@endsection