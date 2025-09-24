@extends('layouts.app')

@section('page-title', 'Ajukan Izin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow border-0">
            <div class="card-header bg-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus mr-2 text-primary"></i>Ajukan Izin
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('izin.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-control-label">Jenis Izin <span class="text-danger">*</span></label>
                        <select class="form-control @error('jenis_izin') is-invalid @enderror" name="jenis_izin" required>
                            <option value="">Pilih Jenis Izin</option>
                            <option value="sakit" {{ old('jenis_izin') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="izin" {{ old('jenis_izin') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="cuti" {{ old('jenis_izin') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        </select>
                        @error('jenis_izin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-control-label">Alasan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alasan') is-invalid @enderror" 
                                  name="alasan" rows="4" placeholder="Jelaskan alasan izin Anda..." required>{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="text-right">
                        <a href="{{ route('izin.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i>Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection