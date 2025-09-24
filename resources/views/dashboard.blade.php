@extends('layouts.app')

@section('content')

@if(auth()->user()->isMentor())
    <!-- Dashboard Mentor -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card-dashboard">
                <div class="card-body text-center">
                    <div class="info-icon shift">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="h4 font-weight-bold text-dark">{{ $totalMagang ?? 0 }}</div>
                    <div class="text-muted">Total Magang</div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card-dashboard">
                <div class="card-body text-center">
                    <div class="info-icon location">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="h4 font-weight-bold text-dark">{{ $hadirHariIni ?? 0 }}</div>
                    <div class="text-muted">Hadir Hari Ini</div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card-dashboard">
                <div class="card-body text-center">
                    <div class="info-icon time">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="h4 font-weight-bold text-dark">{{ $absenHariIni ?? 0 }}</div>
                    <div class="text-muted">Tidak Hadir</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu untuk Mentor -->
    <div class="action-grid">
        <a href="{{ route('absensi.riwayat') }}" class="btn-action btn-absensi">
            <i class="fas fa-history"></i>Riwayat Absensi
        </a>
        <a href="{{ route('izin.daftar') }}" class="btn-action btn-izin">
            <i class="fas fa-clipboard-list"></i>Kelola Izin
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ route('users.index') }}" class="btn-action btn-absensi w-100">
                <i class="fas fa-users"></i>Kelola User
            </a>
        </div>
    </div>

@else
    <!-- Dashboard Magang - PERSIS SEPERTI SCREENSHOT -->
    
    <!-- Status Absensi Hari Ini -->
    <div class="card-dashboard">
        <div class="card-body">
            <div class="section-header status">
                <div class="section-icon">
                    <i class="fas fa-info"></i>
                </div>
                Status Absensi Hari Ini
            </div>
            
            @if($absensiHariIni ?? false)
                <div class="alert-status">
                    <h6><i class="fas fa-check-circle mr-2"></i>Anda Sudah Melakukan Absensi</h6>
                    <div class="status-content">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Jam Masuk:</strong> {{ $absensiHariIni->jam_masuk ?? '-' }}<br>
                                <small class="text-muted">📍 {{ $absensiHariIni->lokasi_masuk ?? '-' }}</small>
                            </div>
                            <div class="col-md-6">
                                <strong>Jam Pulang:</strong> 
                                @if($absensiHariIni->jam_pulang ?? false)
                                    {{ $absensiHariIni->jam_pulang }}<br>
                                    <small class="text-muted">📍 {{ $absensiHariIni->lokasi_pulang ?? '-' }}</small>
                                @else
                                    <span class="text-warning">Belum Pulang</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert-status">
                    <h6><i class="fas fa-exclamation-triangle mr-2"></i>Belum Melakukan Absensi</h6>
                    <div class="status-content">
                        Silakan lakukan absensi masuk terlebih dahulu.
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons - PERSIS SEPERTI SCREENSHOT -->
    <div class="action-grid">
        <a href="{{ route('absensi.form') }}" class="btn-action btn-absensi">
            <i class="fas fa-clock"></i>
            Absensi
        </a>
        <a href="{{ route('izin.index') }}" class="btn-action btn-izin">
            <i class="fas fa-calendar-alt"></i>
            Izin Saya
        </a>
    </div>

    <!-- Informasi Akun - PERSIS SEPERTI SCREENSHOT -->
    <div class="card-dashboard">
        <div class="card-body">
            <div class="section-header info">
                <div class="section-icon">
                    <i class="fas fa-info"></i>
                </div>
                Informasi Akun
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon shift">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div class="info-label">Shift</div>
                    <span class="info-value badge-shift">{{ ucfirst(auth()->user()->shift) }}</span>
                </div>
                <div class="info-item">
                    <div class="info-icon location">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-label">Lokasi</div>
                    <span class="info-value badge-location">{{ auth()->user()->lokasi }}</span>
                </div>
                <div class="info-item">
                    <div class="info-icon time">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-label">Waktu Sekarang</div>
                    <span class="info-value badge-time" id="current-time">{{ date('H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@section('scripts')
<script>
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}

// Update time every second
setInterval(updateTime, 1000);
</script>
@endsection