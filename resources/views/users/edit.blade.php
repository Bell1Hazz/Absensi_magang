@extends('layouts.app')

@section('page-title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow border-0">
            <div class="card-header bg-white">
                <h4 class="mb-0">
                    <i class="fas fa-user-edit mr-2 text-primary"></i>Edit User: {{ $user->name }}
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-control-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-control-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Role <span class="text-danger">*</span></label>
                                <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                                    <option value="magang" {{ old('role', $user->role) == 'magang' ? 'selected' : '' }}>Magang</option>
                                    <option value="mentor" {{ old('role', $user->role) == 'mentor' ? 'selected' : '' }}>Mentor</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Shift <span class="text-danger">*</span></label>
                                <select class="form-control @error('shift') is-invalid @enderror" name="shift" required>
                                    <option value="pagi" {{ old('shift', $user->shift) == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                    <option value="sore" {{ old('shift', $user->shift) == 'sore' ? 'selected' : '' }}>Sore</option>
                                </select>
                                @error('shift')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Lokasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                       name="lokasi" value="{{ old('lokasi', $user->lokasi) }}" required>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection