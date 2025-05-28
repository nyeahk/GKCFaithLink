@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Users</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Assign Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}">
                            {{ $user->username }}
                        </a>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @switch($user->role)
                            @case(1)
                                <span class="badge bg-primary">Admin</span>
                                @break
                            @case(2)
                                <span class="badge bg-info">Treasurer</span>
                                @break
                            @case(3)
                                <span class="badge bg-success">Member</span>
                                @break
                            @case(4)
                                <span class="badge bg-secondary">Staff</span>
                                @break
                            @default
                                <span class="badge bg-light text-dark">Unknown</span>
                        @endswitch
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Disabled</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                                {{ $user->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm ms-2">
                            View
                        </a>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.assignRole', $user->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <select name="role" onchange="this.form.submit()" class="form-select form-select-sm" style="width: auto;">
                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
                <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Treasurer</option>
                <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>Member</option>
                <option value="4" {{ $user->role == 4 ? 'selected' : '' }}>Staff</option>
            </select>
        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection