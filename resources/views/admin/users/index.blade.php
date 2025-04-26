@extends('layouts.gkc')

@section('title', 'Users')

@section('content')
    <div class="users-container">
        <div class="users-header">
            <h1>Users</h1>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    @if($user->image_path)
                                        <img src="{{ asset('storage/' . $user->image_path) }}" alt="{{ $user->name }}" class="user-avatar">
                                    @else
                                        <div class="user-avatar-placeholder">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $user->isCurrentlyActive() ? 'active' : 'inactive' }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $user->isCurrentlyActive() ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if($user->last_active_at)
                                    {{ $user->last_active_at->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .users-container {
        padding: 2rem;
    }

    .users-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .users-header h1 {
        color: #1a365d;
        margin: 0;
        font-size: 1.875rem;
    }

    .users-table-container {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th,
    .users-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .users-table th {
        background-color: #f7fafc;
        font-weight: 600;
        color: #4a5568;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-avatar-placeholder {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background-color: #4299e1;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .badge-primary {
        background-color: #ebf8ff;
        color: #2b6cb0;
    }

    .badge-secondary {
        background-color: #f7fafc;
        color: #4a5568;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-active {
        background-color: #f0fff4;
        color: #2f855a;
    }

    .status-inactive {
        background-color: #fff5f5;
        color: #c53030;
    }

    .status-badge i {
        font-size: 0.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border-radius: 0.25rem;
        transition: all 0.2s;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .btn-primary {
        background-color: #3182ce;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2c5282;
    }

    .btn-edit {
        background-color: #e2e8f0;
        color: #4a5568;
    }

    .btn-edit:hover {
        background-color: #cbd5e0;
    }

    .btn-delete {
        background-color: #fed7d7;
        color: #c53030;
    }

    .btn-delete:hover {
        background-color: #feb2b2;
    }

    .alert {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background-color: #f0fff4;
        color: #2f855a;
        border: 1px solid #c6f6d5;
    }

    .alert i {
        font-size: 1.25rem;
    }
</style>
@endpush 