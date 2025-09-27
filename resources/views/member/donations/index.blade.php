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
                                                <span class="badge"
                                                    @if($donation->status == 'declined') style="background-color:#e74a3b;color:white;"
                                                    @elseif($donation->status == 'verified') style="background-color:#28a745;color:white;"
                                                    @elseif($donation->status == 'pending') style="background-color:#ffc107;color:#212529;"
                                                    @else style="background-color:#4F959D;color:white;"
                                                    @endif>
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

@push('styles')
<style>
    .table .btn-info,
    .table .btn-info:hover,
    .table .btn-info:focus,
    .table .btn-info:active,
    .table .btn-info.active,
    .table .btn-info:not(:disabled):not(.disabled):active,
    .table .btn-info:not(:disabled):not(.disabled).active {
        background-color: #4F959D !important;
        border-color: #4F959D !important;
        color: white !important;
    }

    .table .btn-info:hover,
    .table .btn-info:focus,
    .table .btn-info:active,
    .table .btn-info.active {
        background-color: #367588 !important;
        border-color: #367588 !important;
        color: white !important;
        box-shadow: none !important;
    }
</style>
@endpush