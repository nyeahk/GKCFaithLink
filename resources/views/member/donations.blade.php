@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8 rounded-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                    <p class="mt-1 text-sm text-green-600">
                        Your donation has been submitted successfully! Our team will review your submission and update the status once approved. You can check the status of your donation in the table below.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 md:mb-0">Donate to GKC FaithLink</h1>
        <a href="{{ route('member.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-[#205781] text-white rounded-lg hover:bg-[#4F959D] transition-colors shadow-md">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Donation Info Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Support Our Ministry</h2>
                <p class="text-gray-600 mb-6">Your generous donations help us continue our mission and serve our community. Thank you for your support!</p>
                
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Scan GCash QR Code</h3>
                    <div class="flex justify-center mb-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <img src="{{ asset('images/gcash-qr.png') }}" alt="GCash QR Code" id="qrImage" class="w-48 h-48 object-contain">
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 text-center">Scan this QR code using your GCash app to make a donation.</p>
                </div>
            </div>
        </div>

        <!-- Donation Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Donation Details</h2>
                <p class="text-gray-600 mb-6">Please fill in your donation details and upload your receipt after completing the transaction.</p>
                
                <form action="{{ route('member.donations.store') }}" method="POST" enctype="multipart/form-data" id="donationForm" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="donor_name" class="block text-sm font-medium text-gray-700">Donor Name</label>
                        <input type="text" id="donor_name" name="donor_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4F959D] focus:border-[#4F959D] transition-colors" required>
                    </div>

                    <div class="space-y-2">
                        <label for="donation_type" class="block text-sm font-medium text-gray-700">Donation Type</label>
                        <select id="donation_type" name="donation_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4F959D] focus:border-[#4F959D] transition-colors" required>
                            <option value="">Select Donation Type</option>
                            <option value="tithe">Tithe</option>
                            <option value="offering">Offering</option>
                            <option value="mission">Mission</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (PHP)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">₱</span>
                            </div>
                            <input type="number" id="amount" name="amount" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4F959D] focus:border-[#4F959D] transition-colors" min="1" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="reference_number" class="block text-sm font-medium text-gray-700">GCash Reference Number</label>
                        <input type="text" id="reference_number" name="reference_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4F959D] focus:border-[#4F959D] transition-colors" required>
                        <p class="text-xs text-gray-500">Enter the reference number from your GCash transaction.</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="receipt" class="block text-sm font-medium text-gray-700">Upload Receipt</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#4F959D] transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="receipt" class="relative cursor-pointer bg-white rounded-md font-medium text-[#205781] hover:text-[#4F959D] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#4F959D]">
                                        <span>Upload a file</span>
                                        <input id="receipt" name="receipt" type="file" class="sr-only" accept="image/*" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                        <div id="filePreview" class="mt-2">
                            <div id="previewContainer" class="hidden">
                                <img id="previewImage" src="" alt="Receipt Preview" class="max-h-48 rounded-lg shadow-sm">
                                <button type="button" onclick="removeImage()" class="mt-2 text-red-600 hover:text-red-800 text-sm">
                                    Remove Image
                                </button>
                            </div>
                            <p id="noFileText" class="text-sm text-gray-500">No file chosen</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="message" class="block text-sm font-medium text-gray-700">Message (Optional)</label>
                        <textarea id="message" name="message" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4F959D] focus:border-[#4F959D] transition-colors" rows="3"></textarea>
                    </div>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    After submitting your donation, our team will review your submission. You will be notified once your donation is approved. Thank you for your support!
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#205781] text-white py-3 px-4 rounded-lg hover:bg-[#4F959D] focus:outline-none focus:ring-2 focus:ring-[#4F959D] focus:ring-offset-2 transition-colors shadow-md">
                            Submit Donation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Donation History -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Recent Donations</h2>
            
            @if($donations->isEmpty())
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-md">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <p class="text-blue-700">No donations have been recorded yet.</p>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($donations as $donation)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($donation->created_at)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $donation->donor_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $donation->donation_type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ₱{{ number_format($donation->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $donation->reference_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $donation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($donation->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($donation->receipt_path)
                                            <button type="button" class="text-[#205781] hover:text-[#4F959D] focus:outline-none" 
                                                    onclick="openReceiptModal('{{ $donation->id }}', '{{ asset('storage/' . $donation->receipt_path) }}')">
                                                View Receipt
                                            </button>
                                        @else
                                            <span class="text-gray-400">No receipt</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800" id="modalTitle">Receipt for Donation</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeReceiptModal()">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="flex justify-center">
                <img id="modalReceiptImage" src="" alt="Donation Receipt" class="max-h-[70vh] object-contain">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('receipt');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const noFileText = document.getElementById('noFileText');
        const dropZone = document.querySelector('.border-dashed');
        
        // Handle file input change
        fileInput.addEventListener('change', handleFileSelect);
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop zone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight(e) {
            dropZone.classList.add('border-[#4F959D]', 'bg-gray-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-[#4F959D]', 'bg-gray-50');
        }
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect({ target: fileInput });
            }
        }
        
        function handleFileSelect(e) {
            const file = e.target.files[0];
            
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    noFileText.classList.add('hidden');
                }
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
                noFileText.classList.remove('hidden');
                alert('Please select an image file (PNG, JPG, GIF)');
            }
        }
    });
    
    function removeImage() {
        const fileInput = document.getElementById('receipt');
        const previewContainer = document.getElementById('previewContainer');
        const noFileText = document.getElementById('noFileText');
        
        fileInput.value = '';
        previewContainer.classList.add('hidden');
        noFileText.classList.remove('hidden');
    }
    
    // Receipt modal functions
    function openReceiptModal(donationId, imageUrl) {
        const modal = document.getElementById('receiptModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalImage = document.getElementById('modalReceiptImage');
        
        // Set modal content
        modalTitle.textContent = `Receipt for Donation #${donationId}`;
        modalImage.src = imageUrl;
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeReceiptModal() {
        const modal = document.getElementById('receiptModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    // Close modal when clicking outside
    document.getElementById('receiptModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReceiptModal();
        }
    });
</script>
@endsection

