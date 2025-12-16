@extends('admin.layouts.admin')

@section('title', 'Roles Management')
@section('page-title', 'Roles Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Roles</h3>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Role
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Permissions Count</th>
                        <th>Admins Count</th>
                        <th>Users Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>
                            <span class="badge badge-info">{{ $role->name }}</span>
                        </td>
                        <td>{{ $role->description ?? 'No description' }}</td>
                        <td>
                            <span class="badge badge-primary">{{ count($role->permissions ?? []) }}</span>
                        </td>
                        <td>{{ $role->admins_count }}</td>
                        <td>{{ $role->users_count }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($role->admins_count == 0 && $role->users_count == 0)
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
