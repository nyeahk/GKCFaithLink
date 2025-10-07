@extends('layouts.member')

@section('title', 'Announcements')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-megaphone me-2"></i> Announcements
                    </h5>
                </div>
                <div class="card-body">
                    @if($announcements->count() > 0)
                        <div class="row">
                            @foreach($announcements as $announcement)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 announcement-card">
                                        @if($announcement->image_path)
                                            <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                                                class="card-img-top" 
                                                alt="{{ $announcement->title }}"
                                                style="height: 180px; object-fit: cover;">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $announcement->title }}</h5>
                                            <p class="card-text text-muted mb-2">
                                                <small>
                                                    <i class="bi bi-calendar me-1"></i> 
                                                    {{ $announcement->posted_at->format('M d, Y') }}
                                                </small>
                                            </p>
                                            <p class="card-text">{{ Str::limit($announcement->content, 150) }}</p>
                                        </div>
                                        <div class="card-footer bg-white border-top-0">
                                            <a href="{{ route('member.announcements.show', $announcement->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye me-1"></i> Read More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination: match staff events style -->
                        @if(isset($announcements) && method_exists($announcements, 'hasPages') && $announcements->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Showing {{ $announcements->firstItem() }} to {{ $announcements->lastItem() }} of {{ $announcements->total() }} announcements
                            </div>
                            <div>
                                {{ $announcements->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-megaphone text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">No Announcements</h5>
                            <p class="text-muted">There are no announcements at this time.</p>
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
    .announcement-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .announcement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush
