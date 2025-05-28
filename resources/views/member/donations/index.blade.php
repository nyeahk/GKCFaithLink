@extends('layouts.member')

@section('title', 'My Donations')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Donations</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i>My Donations</h5>
                    <a href="{{ route('member.donations.create') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Make a Donation
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($donations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Purpose</th>
                                        <th>Payment Method</th>
                                        <th>Reference #</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donations as $donation)
                                        <tr>
                                            <td>{{ $donation->created_at->format('M d, Y') }}</td>
                                            <td>₱{{ number_format($donation->amount, 2) }}</td>
                                            <td>{{ ucfirst($donation->purpose) }}</td>
                                            <td>{{ $donation->payment_method }}</td>
                                            <td>{{ $donation->reference_number ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $donation->status == 'approved' ? 'success' : ($donation->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($donation->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('member.donations.show', $donation->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $donations->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <img src="{{ asset('images/no-data.svg') }}" alt="No Donations" class="img-fluid mb-3" style="max-height: 150px;">
                            <h5>No Donations Yet</h5>
                            <p class="text-muted">You haven't made any donations yet.</p>
                            <a href="{{ route('member.donations.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle me-1"></i> Make Your First Donation
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection