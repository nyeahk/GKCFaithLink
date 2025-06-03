<form action="{{ route('treasurer.donations.verify', $donation->id) }}" method="POST">
    @csrf
    
    <div class="mb-3">
        <label for="status" class="form-label">Verification Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="">Select Status</option>
            <option value="verified">Verify (Approve)</option>
            <option value="declined">Decline</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="verification_notes" class="form-label">Verification Notes</label>
        <textarea class="form-control @error('verification_notes') is-invalid @enderror" 
                  id="verification_notes" name="verification_notes" rows="4" 
                  placeholder="Enter verification notes or reason for declining...">{{ old('verification_notes') }}</textarea>
        @error('verification_notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">
            These notes will be visible to the donor. Please provide clear information about the verification process or reasons for declining.
        </div>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="{{ route('treasurer.donations.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check-circle me-1"></i> Submit Verification
        </button>
    </div>
</form>
