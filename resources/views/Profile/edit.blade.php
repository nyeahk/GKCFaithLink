@php
    $layout = 'layouts.admin';
    if(auth()->user()->role == 1) {
        $layout = 'layouts.admin';
    } elseif(auth()->user()->role == 2) {
        $layout = 'layouts.treasurer';
    }elseif(auth()->user()->role == 3) {
        $layout = 'layouts.member';
    } elseif(auth()->user()->role == 4) {
        $layout = 'layouts.staff';
    }
@endphp

@extends($layout)

@section('title', 'Edit Profile')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(auth()->user()->role == 3)
                        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @elseif(auth()->user()->role == 1)
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @elseif(auth()->user()->role == 2)
                        <form action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @endif
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="profile-image-container mb-3">
                                    <div class="position-relative" style="width: 150px; height: 150px; margin: 0 auto;">
    @if(auth()->user()->image_path)
        <img id="preview-image" 
             src="{{ asset('storage/' . auth()->user()->image_path) }}" 
             alt="{{ auth()->user()->name }}" 
             class="img-fluid rounded-circle" 
             style="width: 150px; height: 150px; object-fit: cover;"
             onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}'; console.error('Failed to load image: {{ asset('storage/' . auth()->user()->image_path) }}');">
        <!-- Debug info (remove in production) -->
        <small class="d-none">Image path: {{ auth()->user()->image_path }}</small>
    @else
        <div id="preview-placeholder" class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
            <i class="bi bi-person-fill" style="font-size: 4rem; color: #6c757d;"></i>
        </div>
        <img id="preview-image" src="" alt="" class="img-fluid rounded-circle d-none" style="width: 150px; height: 150px; object-fit: cover;">
    @endif
</div>
                                    <div class="mt-3">
                                        <label for="image" class="btn btn-outline-primary btn-sm" style="transition: all 0.3s ease;">
                                            <i class="bi bi-upload me-1"></i> Change Photo
                                        </label>
                                        <input type="file" id="image" name="image" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" value="{{ auth()->user()->username }}" disabled>
                <small class="text-muted">Username cannot be changed.</small>
            </div>
            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number', auth()->user()->contact_number) }}">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address', auth()->user()->address) }}">
            </div>
            <div class="d-flex justify-content-end mt-4">
                @if(auth()->user()->role == 3)
                    <a href="{{ route('member.profile.index') }}" class="btn btn-secondary me-2 rounded-pill d-flex align-items-center justify-content-center" style="transition: all 0.3s ease;">
                        Cancel
                    </a>
                @elseif(auth()->user()->role == 1)
                    <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary me-2 rounded-pill d-flex align-items-center justify-content-center" style="transition: all 0.3s ease;">
                        Cancel
                    </a>
                @elseif(auth()->user()->role == 2)
                    <a href="{{ route('treasurer.profile.index') }}" class="btn btn-secondary me-2 rounded-pill d-flex align-items-center justify-content-center" style="transition: all 0.3s ease;">
                        Cancel
                    </a>
                @elseif(auth()->user()->role == 4)
                    <a href="{{ route('staff.profile.index') }}" class="btn btn-secondary me-2 rounded-pill d-flex align-items-center justify-content-center" style="transition: all 0.3s ease;">
                        Cancel
                    </a>
                @endif
                <button type="submit" class="btn btn-primary" style="transition: all 0.3s ease;">Save Changes</button>
            </div>
        </div>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-image').classList.remove('d-none');
                
                if (document.getElementById('preview-placeholder')) {
                    document.getElementById('preview-placeholder').classList.add('d-none');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

@push('styles')
<style>
    /* Button hover styles */
    .profile-image-container .btn-outline-primary {
        color: #4F959D !important;
        border-color: #4F959D !important;
        background-color: transparent !important;
    }

    .profile-image-container .btn-outline-primary:hover {
        background-color: #4F959D !important;
        color: #ffffff !important;
        border-color: #4F959D !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .card-body .btn-secondary {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #ffffff !important;
    }

    .card-body .btn-secondary:hover {
        background-color: #5a6268 !important;
        border-color: #5a6268 !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .card-body .btn-primary {
        background-color: #4F959D !important;
        border-color: #4F959D !important;
        color: #ffffff !important;
    }

    .card-body .btn-primary:hover {
        background-color: #3d7a80 !important;
        border-color: #3d7a80 !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    /* Add active state styles */
    .profile-image-container .btn-outline-primary:active,
    .card-body .btn-secondary:active,
    .card-body .btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Override any default Bootstrap styles */
    .btn-outline-primary:hover,
    .btn-outline-primary:focus,
    .btn-outline-primary:active {
        background-color: #4F959D !important;
        color: #ffffff !important;
        border-color: #4F959D !important;
    }

    .btn-secondary:hover,
    .btn-secondary:focus,
    .btn-secondary:active {
        background-color: #5a6268 !important;
        color: #ffffff !important;
        border-color: #5a6268 !important;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: #3d7a80 !important;
        color: #ffffff !important;
        border-color: #3d7a80 !important;
    }
</style>
@endpush
@endsection
