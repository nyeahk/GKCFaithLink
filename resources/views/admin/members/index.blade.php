@extends('layouts.gkc')

@section('title', 'Members')

@section('content')
<div class="members-container">
    <div class="members-header">
        <div class="header-content">
            <h1>Members</h1>
            <p class="subtitle">Manage your church members and their information</p>
        </div>
        <a href="{{ route('members.create') }}" class="add-member-btn">
            <i class="fas fa-plus"></i>
            Add New Member
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="members-card">
        <div class="table-responsive">
            <table class="members-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Membership Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>
                                <div class="member-info">
                                    @if($member->image_path)
                                        <img src="{{ asset('storage/' . $member->image_path) }}" alt="{{ $member->first_name }}" class="member-avatar">
                                    @else
                                        <div class="member-avatar placeholder">
                                            {{ strtoupper(substr($member->first_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span>{{ $member->first_name }} {{ $member->last_name }}</span>
                                </div>
                            </td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="membership-badge badge-{{ $member->membership_type }}">
                                    {{ ucfirst($member->membership_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $member->status }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="action-links">
                                <a href="{{ route('members.edit', $member) }}" class="action-btn edit-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this member?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-users"></i>
                                    <p>No members found</p>
                                    <a href="{{ route('members.create') }}" class="btn btn-primary">Add Your First Member</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-container">
        {{ $members->links() }}
    </div>
</div>

<style>
.members-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.members-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: #1a365d;
    margin: 0;
}

.subtitle {
    color: #4a5568;
    margin: 0.5rem 0 0;
}

.add-member-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background-color: #2b6cb0;
    color: white;
    border-radius: 0.5rem;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.2s;
}

.add-member-btn:hover {
    background-color: #2c5282;
}

.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background-color: #c6f6d5;
    color: #2f855a;
    border: 1px solid #48bb78;
}

.members-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.members-table {
    width: 100%;
    border-collapse: collapse;
}

.members-table th {
    background-color: #f7fafc;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #1a365d;
    border-bottom: 2px solid #e2e8f0;
}

.members-table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.member-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.member-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    object-fit: cover;
}

.member-avatar.placeholder {
    background-color: #bee3f8;
    color: #2b6cb0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.membership-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-regular {
    background-color: #bee3f8;
    color: #2b6cb0;
}

.badge-associate {
    background-color: #fefcbf;
    color: #975a16;
}

.badge-visitor {
    background-color: #fed7d7;
    color: #c53030;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-active {
    background-color: #c6f6d5;
    color: #2f855a;
}

.status-inactive {
    background-color: #fed7d7;
    color: #c53030;
}

.status-pending {
    background-color: #fefcbf;
    color: #975a16;
}

.action-links {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    padding: 0.5rem;
    border-radius: 0.375rem;
    color: #4a5568;
    transition: all 0.2s;
}

.edit-btn:hover {
    background-color: #bee3f8;
    color: #2b6cb0;
}

.delete-btn:hover {
    background-color: #fed7d7;
    color: #c53030;
}

.empty-state {
    text-align: center;
    padding: 3rem;
}

.empty-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.empty-content i {
    font-size: 3rem;
    color: #a0aec0;
}

.empty-content p {
    color: #4a5568;
    margin: 0;
}

.pagination-container {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 0.5rem;
}

.pagination li {
    list-style: none;
}

.pagination a {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    color: #4a5568;
    text-decoration: none;
    transition: all 0.2s;
}

.pagination a:hover {
    background-color: #f7fafc;
}

.pagination .active a {
    background-color: #2b6cb0;
    color: white;
}
</style>
@endsection 