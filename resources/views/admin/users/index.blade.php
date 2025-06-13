@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Users</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by name, email or username" 
                               name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Role Filter -->
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Administrator</option>
                        <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Treasurer</option>
                        <option value="3" {{ request('role') == '3' ? 'selected' : '' }}>Member</option>
                        <option value="4" {{ request('role') == '4' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>
                
                <!-- Filter Button -->
                <div class="col-md-2">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search', 'role', 'status']))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
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
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($user->image_path)
                                            <img src="{{ asset('storage/' . $user->image_path) }}" alt="{{ $user->username }}" 
                                                class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-person" style="font-size: 1.2rem;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none">
                                            {{ $user->username }}
                                        </a>
                                        @if($user->name)
                                            <div class="small text-muted">{{ $user->name }}</div>
                                        @endif
                                    </div>
                                </div>
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
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">No Users Found</h5>
                                    <p class="text-muted">No users match your search criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Enhanced Pagination -->
            @if(isset($users) && method_exists($users, 'hasPages') && $users->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                <div>
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

