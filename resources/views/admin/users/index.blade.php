@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="container py-2">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Users Management</h1>
            <p class="text-muted mb-0">Manage and filter user accounts</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-funnel me-1"></i> Advanced Filters
            </button>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Enhanced Search and Filters -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-light border-0">
            <div class="d-flex align-items-center">
                <i class="bi bi-search me-2 text-primary"></i>
                <h6 class="mb-0 fw-semibold">Search & Filter Users</h6>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.users.index') }}" method="GET" id="userSearchForm">
                <div class="row g-4">
                    <!-- Search Input -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control" 
                                   id="searchInput" 
                                   name="search" 
                                   placeholder="Search users..." 
                                   value="{{ request('search') }}"
                                   style="border-radius: 10px;">
                            <label for="searchInput">
                                <i class="bi bi-search me-1"></i>Search by name, email
                            </label>
                        </div>
                    </div>
                    
                    <!-- Role Filter -->
                    <div class="col-lg-3 col-md-6">
                        <div class="form-floating">
                            <select name="role" id="roleFilter" class="form-select" onchange="document.getElementById('userSearchForm').submit();" style="border-radius: 10px;">
                                <option value="">All Roles</option>
                                <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>
                                    <i class="bi bi-shield-check me-1"></i>Administrator
                                </option>
                                <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>
                                    <i class="bi bi-cash-coin me-1"></i>Treasurer
                                </option>
                                <option value="3" {{ request('role') == '3' ? 'selected' : '' }}>
                                    <i class="bi bi-person me-1"></i>Member
                                </option>
                                <option value="4" {{ request('role') == '4' ? 'selected' : '' }}>
                                    <i class="bi bi-briefcase me-1"></i>Staff
                                </option>
                            </select>
                            <label for="roleFilter">
                                <i class=""></i>Filter by Role
                            </label>
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="col-lg-3 col-md-6">
                        <div class="form-floating">
                            <select name="status" id="statusFilter" class="form-select" onchange="document.getElementById('userSearchForm').submit();" style="border-radius: 10px;">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                    <i class=""></i>Active
                                </option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                    <i class=""></i>Disabled
                                </option>
                            </select>
                            <label for="statusFilter">
                                <i class=""></i>Filter by Status
                            </label>
                        </div>
                    </div>
                    
                    <!-- Approval Filter -->
                    <div class="col-lg-2 col-md-6">
                        <div class="form-floating">
                            <select name="approval" id="approvalFilter" class="form-select" onchange="document.getElementById('userSearchForm').submit();" style="border-radius: 10px;">
                                <option value="">All Approvals</option>
                                <option value="pending" {{ request('approval') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('approval') == 'approved' ? 'selected' : '' }}>Approved</option>
                            </select>
                            <label for="approvalFilter">
                                <i class="bi bi-check2-square me-1"></i>Approval
                            </label>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="col-lg-2 col-md-6">
                        <div class="d-flex flex-column gap-2 h-100">
                            <button type="submit" class="btn btn-primary filter-button" style="border-radius: 10px; height: 58px;">
                                <i class="bi bi-funnel-fill me-1"></i>Apply Filters
                            </button>
                            @if(request()->anyFilled(['search', 'role', 'status']))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Clear All
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Active Filters Display -->
                @if(request()->anyFilled(['search', 'role', 'status','approval']))
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="text-muted small me-2">Active filters:</span>
                        @if(request('search'))
                            <span class="badge bg-primary">
                                <i class="bi bi-search me-1"></i>Search: "{{ request('search') }}"
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                        @if(request('role'))
                            @php
                                $roleNames = [1 => 'Administrator', 2 => 'Treasurer', 3 => 'Member', 4 => 'Staff'];
                                $roleName = $roleNames[request('role')] ?? 'Unknown';
                            @endphp
                            <span class="badge bg-info">
                                <i class="bi bi-person-gear me-1"></i>Role: {{ $roleName }}
                                <a href="{{ request()->fullUrlWithQuery(['role' => null]) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                        @if(request('status'))
                            <span class="badge bg-{{ request('status') == 'active' ? 'success' : 'warning' }}">
                                <i class="bi bi-toggle-{{ request('status') == 'active' ? 'on' : 'off' }} me-1"></i>Status: {{ ucfirst(request('status')) }}
                                <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                        @if(request('approval'))
                            <span class="badge bg-{{ request('approval') == 'approved' ? 'success' : 'warning' }}">
                                <i class="bi bi-check2-square me-1"></i>Approval: {{ ucfirst(request('approval')) }}
                                <a href="{{ request()->fullUrlWithQuery(['approval' => null]) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-people me-2 text-primary"></i>Users List
                </h6>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary">{{ $users->total() }} Total Users</span>
                @php $pendingCount = \App\Models\User::where('is_approved', false)->count(); @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingCount }} Pending Approval</span>
                @endif
                @if(request()->anyFilled(['search', 'role', 'status']))
                    <span class="badge bg-info">{{ $users->count() }} Filtered</span>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">
                                <i class="bi bi-person me-1"></i>Name
                            </th>
                            <th class="border-0">
                                <i class="bi bi-envelope me-1"></i>Email
                            </th>
                            <th class="border-0">
                                <i class="bi bi-person-gear me-1"></i>Role
                            </th>
                            <th class="border-0">
                                <i class="bi bi-toggle-on me-1"></i>Status
                            </th>
                            <th class="border-0">
                                <i class="bi bi-gear me-1"></i>Actions
                            </th>
                            <th class="border-0">
                                <i class="bi bi-arrow-repeat me-1"></i>Quick Role
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="border-bottom">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($user->image_path)
                                            <img src="{{ asset('storage/' . $user->image_path) }}" 
                                                 alt="{{ $user->full_name }}" 
                                                 class="rounded-circle border border-2 border-light shadow-sm" 
                                                 width="45" height="45" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-circle border border-2 border-light shadow-sm d-flex align-items-center justify-content-center" 
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-person" style="font-size: 1.3rem; color: #6c757d;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none fw-semibold">
                                            {{ $user->full_name }}
                                        </a>
                                        @if($user->name && $user->username)
                                            <div class="small text-muted">
                                                <i class="bi bi-at me-1"></i>{{ $user->username }}
                                            </div>
                                        @elseif(!$user->name && $user->username)
                                            <div class="small text-muted">
                                                <i class="bi bi-at me-1"></i>{{ $user->username }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td>
                                @switch($user->role)
                                    @case(1)
                                        <span class="badge bg-primary px-3 py-2">
                                            <i class="bi bi-shield-check me-1"></i>Admin
                                        </span>
                                        @break
                                    @case(2)
                                        <span class="badge bg-info px-3 py-2">
                                            <i class="bi bi-cash-coin me-1"></i>Treasurer
                                        </span>
                                        @break
                                    @case(3)
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="bi bi-person me-1"></i>Member
                                        </span>
                                        @break
                                    @case(4)
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="bi bi-briefcase me-1"></i>Staff
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark px-3 py-2">Unknown</span>
                                @endswitch
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>Disabled
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if(!$user->is_approved)
                                        <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" style="border-radius: 8px;" title="Approve User">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                                style="border-radius: 8px;" 
                                                title="{{ $user->is_active ? 'Disable User' : 'Enable User' }}">
                                            <i class="bi {{ $user->is_active ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary" 
                                       style="border-radius: 8px;" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.assignRole', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" onchange="this.form.submit()" class="form-select form-select-sm" 
                                            style="width: auto; border-radius: 8px; min-width: 120px;">
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
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-people text-muted mb-3" style="font-size: 4rem;"></i>
                                    <h5 class="text-muted">No Users Found</h5>
                                    <p class="text-muted">No users match your search criteria.</p>
                                    @if(request()->anyFilled(['search', 'role', 'status']))
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Clear Filters
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Enhanced Pagination -->
    @if(isset($users) && method_exists($users, 'hasPages') && $users->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
        </div>
        <div>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>

<style>
.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    opacity: 0.65;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
}

.card {
    border-radius: 12px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    border-radius: 6px;
    font-weight: 500;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.form-select:focus,
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.filter-button {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    border: none;
}

.filter-button:hover {
    background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
    transform: translateY(-1px);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    if (roleFilter) {
        roleFilter.addEventListener('change', function() {
            document.getElementById('userSearchForm').submit();
        });
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            document.getElementById('userSearchForm').submit();
        });
    }
    
    // Add loading state to filter button
    const filterButton = document.querySelector('.filter-button');
    if (filterButton) {
        filterButton.addEventListener('click', function() {
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Filtering...';
        });
    }
});
</script>
@endpush
@endsection