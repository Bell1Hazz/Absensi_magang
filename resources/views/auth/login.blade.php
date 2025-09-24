@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" 
     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin-left: 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-lg border-0" style="border-radius: 15px;">
                    <!-- Header -->
                    <div class="card-header text-center py-4 border-0" 
                         style="background: #f5365c; color: white; border-radius: 15px 15px 0 0;">
                        <h3 class="mb-0 font-weight-bold">Halaman Masuk</h3>
                        <p class="mb-0 mt-2 opacity-8">Sistem Absensi Magang</p>
                    </div>
                    
                    <!-- Body -->
                    <div class="card-body px-lg-5 py-lg-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <!-- Email Input -->
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </div>
                                    <input class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           placeholder="Email" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Password Input -->
                            <div class="form-group mb-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    </div>
                                    <input class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           placeholder="Kata Sandi" 
                                           type="password" 
                                           name="password" 
                                           required autocomplete="current-password">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Remember Me -->
                            <div class="custom-control custom-checkbox mb-3">
                                <input class="custom-control-input" id="customCheck1" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="customCheck1">
                                    <span class="text-muted">Ingat saya</span>
                                </label>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-dark btn-lg btn-block font-weight-bold">
                                    MASUK
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Footer -->
                    <div class="card-footer text-center py-3 bg-transparent border-0">
                        <small class="text-danger font-weight-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Lupa kata sandi? Segera lapor kepada Mentor
                        </small>
                    </div>
                </div>

                <!-- Demo Accounts Info -->
                {{-- <div class="card mt-3" style="background: rgba(255,255,255,0.9);">
                    <div class="card-body">
                        <h6 class="text-center mb-2"><i class="fas fa-info-circle"></i> Demo Accounts</h6>
                        <small class="d-block"><strong>Mentor:</strong> mentor@example.com / password</small>
                        <small class="d-block"><strong>Magang:</strong> abel@example.com / password</small>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>

<style>
    .main-content {
        margin-left: 0 !important;
    }
</style>
@endsection