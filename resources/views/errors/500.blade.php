@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">Server Error</div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h4 class="text-center mb-4">Oops! Something went wrong.</h4>
                    
                    <p class="text-center">{{ $message ?? 'We encountered an error while processing your request.' }}</p>
                    
                    <div class="text-center mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary">Go to Homepage</a>
                        
                        <button onclick="window.location.reload();" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-arrow-clockwise me-1"></i> Try Again
                        </button>
                    </div>
                    
                    @if(config('app.debug') && isset($exception))
                    <div class="mt-5">
                        <div class="alert alert-secondary">
                            <h5>Debug Information:</h5>
                            <p><strong>Message:</strong> {{ $exception->getMessage() }}</p>
                            <p><strong>File:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection