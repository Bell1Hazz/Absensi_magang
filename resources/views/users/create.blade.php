@extends('layouts.app')

@section('page-title', 'Tambah User')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow border-0">
            <div class="card-header bg-white">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus mr-2 text-primary"></i>Tambah User Baru
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-control-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-control-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Role <span class="text-danger">*</span></label>
                                <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="magang" {{ old('role') == 'magang' ? 'selected' : '' }}>Magang</option>
                                    <option value="mentor" {{ old('role') == 'mentor' ? 'selected' : '' }}>Mentor</option>
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
                                    <option value="">Pilih Shift</option>
                                    <option value="pagi" {{ old('shift') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                    <option value="sore" {{ old('shift') == 'sore' ? 'selected' : '' }}>Sore</option>
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
                                       name="lokasi" value="{{ old('lokasi', 'KANTOR') }}" required>
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
                            <i class="fas fa-save mr-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection