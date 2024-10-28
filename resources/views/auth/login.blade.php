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
                                <h2 class="mb-2 text-center">Sign In</h2>
                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <!-- Validation Errors -->
                                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                <form method="POST" action="{{ route('login') }}" data-toggle="validator">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email</label>
                                                <input id="email" type="email" name="email" value="{{env('IS_DEMO') ? '' : old('email')}}" class="form-control" placeholder="email" required autofocus>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="password" class="form-label">Password</label>
                                                <input class="form-control" type="password" placeholder="********" name="password" value="{{ env('IS_DEMO') ? '' : '' }}" required autocomplete="current-password">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                <!-- <input type="checkbox" class="custom-control-input" id="customCheck1"> -->
                                                <label class="form-check-label" for="customCheck1">Remember Me</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <a href="{{route('auth.recoverpw')}}" class="float-end">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary">{{ __('Sign In') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
