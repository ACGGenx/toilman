<x-guest-layout>
    <section class="login-content">
        <div class="row m-0 align-items-center vh-100">
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                            <div class="card-body">
                                <a href="{{route('dashboard')}}" class="navbar-brand d-flex align-items-center mb-3">
                                    <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg">
                                        <circle
                                            cx="16"
                                            cy="16"
                                            r="15"
                                            fill="none"
                                            stroke="#3498db"
                                            stroke-width="2" />
                                        <rect x="8" y="18" width="4" height="8" fill="#3498db" />
                                        <rect x="14" y="14" width="4" height="12" fill="#3498db" />
                                        <rect x="20" y="10" width="4" height="16" fill="#3498db" />
                                    </svg>
                                    <h4 class="logo-title ms-3">{{env('APP_NAME')}}</h4>
                                </a>
                                <h2 class="mb-2">Reset Password</h2>
                                <p>Enter your email address and we'll send you an email with instructions to reset your password.</p>
                                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                <form>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="floating-label form-group">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" aria-describedby="email" placeholder=" ">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block"> {{ __('Reset') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
