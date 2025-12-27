@extends('admin.layouts.admin')

@section('title', __('messages.manage_roles'))
@section('page-title', __('messages.manage_roles'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.all_roles') }}</h3>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.create_role') }}
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.id') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.description') }}</th>
                        <th>{{ __('messages.permissions_count') }}</th>
                        <th>{{ __('messages.admins_count') }}</th>
                        <th>{{ __('messages.users_count') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>
                            <span class="badge badge-info">{{ $role->name }}</span>
                        </td>
                        <td>{{ $role->description ?? __('messages.no_description') }}</td>
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
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.are_you_sure_delete_role') }}')">
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
