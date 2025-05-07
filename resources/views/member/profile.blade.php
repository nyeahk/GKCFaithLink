@extends('layouts.member')

@section('title', 'My Profile')

@section('content')
    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center py-12">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-8">
            <div class="flex flex-col items-center mb-8 relative">
                <!-- Profile Image with overlay -->
                <div class="relative w-32 h-32 mb-4 group">
                    <img id="profileImage" 
                         src="{{ auth()->user()->image_path ? asset('storage/' . auth()->user()->image_path) : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" 
                         alt="Profile" 
                         class="w-32 h-32 rounded-full border-4 border-blue-100 object-cover transition-transform duration-300 group-hover:scale-105">
                    
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <label for="profilePicInput" class="cursor-pointer text-white hover:text-blue-200 transition-colors">
                            <i class="fas fa-camera text-2xl"></i>
                            <span class="sr-only">Change profile picture</span>
                        </label>
                    </div>
                    
                    <input id="profilePicInput" 
                           name="profile_picture" 
                           type="file" 
                           accept="image/*" 
                           class="hidden" 
                           form="profileUpdateForm">
                </div>
                <div class="text-gray-500">{{ auth()->user()->username }}</div>
            </div>

            <form id="profileUpdateForm" action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1" for="name">Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="w-full border rounded px-3 py-2" 
                               value="{{ old('name', auth()->user()->name) }}" 
                               onkeydown="return !/[0-9]/.test(event.key)"
                               onpaste="return false"
                               ondrop="return false"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Only letters and spaces are allowed</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1" for="email">Email</label>
                        <input type="email" id="email" name="email" class="w-full border rounded px-3 py-2 bg-gray-100" value="{{ auth()->user()->email }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1" for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" class="w-full border rounded px-3 py-2" value="{{ old('contact_number', auth()->user()->contact_number) }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1" for="position">Position</label>
                        <input type="text" id="position" name="position" class="w-full border rounded px-3 py-2 bg-gray-100" value="{{ auth()->user()->position }}" readonly>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-600 mb-1" for="address">Address</label>
                        <input type="text" id="address" name="address" class="w-full border rounded px-3 py-2" value="{{ old('address', auth()->user()->address) }}">
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Change password</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1" for="current_password">Current password</label>
                            <input type="password" id="current_password" name="current_password" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1" for="new_password">New password</label>
                            <input type="password" id="new_password" name="new_password" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1" for="new_password_confirmation">Confirm password</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="w-full border rounded px-3 py-2">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="reset" class="bg-gray-100 text-gray-800 px-6 py-2 rounded font-semibold hover:bg-gray-200 transition">Reset</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700 transition">Save changes</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Profile picture upload handling
        document.getElementById('profilePicInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
                
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    // Update both profile page and sidebar images
                    document.getElementById('profileImage').src = evt.target.result;
                    const sidebarImage = document.querySelector('.sidebar img[alt="Profile Picture"]');
                    if (sidebarImage) {
                        sidebarImage.src = evt.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Name input validation
        const nameInput = document.getElementById('name');
        
        // Prevent any non-letter characters
        nameInput.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            if (!/[a-zA-Z\s]/.test(char)) {
                e.preventDefault();
            }
        });

        // Remove any numbers or special characters if they somehow get in
        nameInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });

        // Prevent paste
        nameInput.addEventListener('paste', function(e) {
            e.preventDefault();
        });

        // Prevent drag and drop
        nameInput.addEventListener('drop', function(e) {
            e.preventDefault();
        });
    </script>
@endsection 