@extends('layouts.member')

@section('title', $announcement->title)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('member.announcements') }}">Announcements</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $announcement->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="card-title mb-3">{{ $announcement->title }}</h2>
                    
                    <div class="d-flex align-items-center text-muted mb-4">
                        <div class="me-3">
                            <i class="bi bi-calendar me-1"></i> 
                            {{ $announcement->posted_at->format('F d, Y') }}
                        </div>
                        <div>
                            <i class="bi bi-clock me-1"></i> 
                            {{ $announcement->posted_at->format('h:i A') }}
                        </div>
                    </div>
                    
                    @if($announcement->image_path)
                        <div class="announcement-image mb-4">
                            <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                                alt="{{ $announcement->title }}" 
                                class="img-fluid rounded">
                        </div>
                    @endif
                    
                    <div class="announcement-content">
                        <p>{!! nl2br(e($announcement->content)) !!}</p>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('member.announcements') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back to Announcements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .announcement-content {
        font-size: 1.1rem;
        line-height: 1.7;
    }
    
    .announcement-image img {
        max-height: 400px;
        width: 100%;
        object-fit: cover;
    }
</style>
@endpush