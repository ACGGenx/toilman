<x-app-layout>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Error</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $message }}</h5>
                        @if(config('app.debug'))
                            <div class="alert alert-danger mt-3">
                                {{ $error }}
                            </div>
                        @endif
                        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Return Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>