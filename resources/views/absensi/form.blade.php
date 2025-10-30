@extends('layouts.app')

@section('page-title', 'Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        
        @if($absensiKemarinBelumPulang ?? false)
        <!-- Alert untuk absensi kemarin yang belum pulang -->
        <div class="card shadow border-0 mb-4" style="border-radius: 15px; border-left: 4px solid #fb6340;">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <h6><i class="fas fa-exclamation-triangle mr-2"></i>Absensi Kemarin Belum Lengkap</h6>
                    <p class="mb-3">
                        Anda belum absen pulang untuk tanggal <strong>{{ $absensiKemarinBelumPulang->tanggal->format('d M Y') }}</strong><br>
                        Masuk: {{ $absensiKemarinBelumPulang->jam_masuk }} ({{ $absensiKemarinBelumPulang->lokasi_masuk }})
                    </p>
                    <button type="button" class="btn btn-warning btn-sm" id="btn-pulang-kemarin" 
                            data-tanggal="{{ $absensiKemarinBelumPulang->tanggal->format('Y-m-d') }}">
                        <i class="fas fa-clock mr-1"></i>Absen Pulang Kemarin Sekarang
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="card shadow border-0" style="border-radius: 15px;">
            <!-- Card header -->
            <div class="card-header bg-white border-0 text-center py-4">
                <h1 class="text-dark mb-0" style="font-weight: 300; font-size: 2.5rem;">Absensi</h1>
            </div>
            
            <!-- Card body -->
            <div class="card-body px-lg-5 py-4">
                
                <!-- User Info -->
                <div class="form-group mb-4">
                    <h5 class="text-dark mb-0" style="font-weight: 300;">
                        <strong>Nama:</strong> {{ auth()->user()->name }}
                    </h5>
                </div>
                
                <!-- Shift & Location Row -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label text-muted" style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">
                                SHIFT:
                            </label>
                            <select class="form-control form-control-lg" id="shift-select">
                                <option value="pagi" {{ auth()->user()->shift == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                <option value="sore" {{ auth()->user()->shift == 'sore' ? 'selected' : '' }}>Sore</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label text-muted" style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">
                                LOKASI:
                            </label>
                            <input type="text" class="form-control form-control-lg" id="current-location" 
                                   value="Mendeteksi lokasi..." disabled
                                   style="background-color: #f8f9fe; border-color: #dee2e6; color: #495057;">
                        </div>
                    </div>
                </div>

                <!-- GPS Status Bar -->
                <div class="form-group mb-4">
                    <div class="text-center p-3 rounded" id="location-status" 
                         style="background-color: #8fa4b3; color: white; font-weight: 500; border-radius: 8px;">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Mendeteksi lokasi Anda...
                    </div>
                </div>
                
                <!-- Status Message based on attendance -->
                @if(!$absensiHariIni)
                    <!-- Belum absensi sama sekali -->
                    <div class="text-center mb-4">
                        <div class="alert" style="background-color: #8fa4b3; color: white; border: none; font-weight: 500;">
                            Anda belum absen hari ini
                        </div>
                    </div>
                @elseif(!$absensiHariIni->jam_pulang)
                    <!-- Sudah masuk, belum pulang -->
                    <div class="text-center mb-4">
                        <div class="alert alert-success" style="border: none;">
                            <strong>Anda sudah absen masuk pada {{ $absensiHariIni->jam_masuk }}</strong>
                            <br><small>Shift: {{ ucfirst($absensiHariIni->user->shift) }} | Lokasi: {{ $absensiHariIni->lokasi_masuk }}</small>
                        </div>
                    </div>
                @else
                    <!-- Sudah lengkap masuk dan pulang -->
                    <div class="text-center mb-4">
                        <div class="alert alert-info" style="border: none;">
                            <strong>Absensi hari ini sudah lengkap</strong><br>
                            <small>
                                Shift: {{ ucfirst($absensiHariIni->user->shift) }}<br>
                                Masuk: {{ $absensiHariIni->jam_masuk }} ({{ $absensiHariIni->lokasi_masuk }})<br>
                                Pulang: {{ $absensiHariIni->jam_pulang }} ({{ $absensiHariIni->lokasi_pulang }})
                            </small>
                        </div>
                    </div>
                @endif

                <!-- Action Button -->
                <div class="text-center mb-4">
                    @if(!$absensiHariIni)
                        <!-- Button MASUK -->
                        <button type="button" class="btn btn-lg btn-block py-3" 
                                id="btn-masuk" disabled
                                style="background-color: #5e72e4; border-color: #5e72e4; color: white; font-weight: 600; font-size: 1.125rem; border-radius: 8px; text-transform: uppercase;">
                            MASUK
                        </button>
                    @elseif(!$absensiHariIni->jam_pulang)
                        <!-- Button PULANG -->
                        <button type="button" class="btn btn-lg btn-block py-3" 
                                id="btn-pulang" disabled
                                style="background-color: #f5365c; border-color: #f5365c; color: white; font-weight: 600; font-size: 1.125rem; border-radius: 8px; text-transform: uppercase;">
                            PULANG
                        </button>
                    @else
                        <!-- Button SELESAI (disabled) -->
                        <button class="btn btn-lg btn-block py-3" disabled
                                style="background-color: #6c757d; border-color: #6c757d; color: white; font-weight: 600; font-size: 1.125rem; border-radius: 8px; text-transform: uppercase;">
                            ABSENSI SELESAI
                        </button>
                    @endif
                </div>

                <!-- Debug Button (bisa dihapus setelah GPS oke) -->
                <div class="text-center mb-4">
                    <button type="button" class="btn btn-info btn-sm" onclick="debugGPSLocation()">
                        🧪 Debug GPS
                    </button>
                </div>

                <!-- Footer Message -->
                <div class="text-center">
                    <small style="color: #f5365c; font-weight: 500;">
                        Lupa kata sandi? Segera lapor kepada Mentor
                    </small>
                </div>
            </div>
        </div>

        <!-- Current Time Display -->
        <div class="card shadow mt-4 border-0">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Waktu Sekarang</h6>
                <h2 class="text-primary mb-0" id="current-time">{{ date('H:i:s') }}</h2>
                <small class="text-muted">{{ date('d F Y') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Loading...</span>
                </div>
                <h6 class="text-dark mb-2">Sedang memproses absensi...</h6>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let userLatitude = null;
    let userLongitude = null;
    
    // KOORDINAT KANTOR YANG BENAR
    const OFFICE_LATITUDE = -7.845969257804157;
    const OFFICE_LONGITUDE = 110.36214269617226;
    const MAX_DISTANCE = 1000; // 1km radius
    
    console.log('🏢 Office Coordinates:', OFFICE_LATITUDE, OFFICE_LONGITUDE);
    
    // Update time display
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID');
        $('#current-time').text(timeString);
    }
    setInterval(updateTime, 1000);

    // Debug GPS Function
    window.debugGPSLocation = function() {
        console.log('🧪 === GPS DEBUG START ===');
        console.log('📍 Current User Location:', userLatitude, userLongitude);
        console.log('🏢 Office Location:', OFFICE_LATITUDE, OFFICE_LONGITUDE);
        
        if (userLatitude && userLongitude) {
            const distance = calculateDistance(userLatitude, userLongitude, OFFICE_LATITUDE, OFFICE_LONGITUDE);
            const isInOffice = distance <= MAX_DISTANCE;
            
            alert('🧪 GPS Debug Results:\n\n' +
                  '📍 Koordinat Anda:\n' + userLatitude.toFixed(8) + ', ' + userLongitude.toFixed(8) + '\n\n' +
                  '🏢 Koordinat Kantor:\n' + OFFICE_LATITUDE + ', ' + OFFICE_LONGITUDE + '\n\n' +
                  '📏 Jarak: ' + Math.round(distance) + ' meter\n' +
                  '🎯 Status: ' + (isInOffice ? 'KANTOR ✅' : 'DILUAR KANTOR ❌') + '\n' +
                  '📐 Batas: ' + MAX_DISTANCE + 'm');
        } else {
            alert('⚠️ GPS belum terdeteksi!');
        }
    };

    // Get user location - GPS OTOMATIS TANPA PILIHAN MANUAL
    function getUserLocation() {
        console.log('🎯 Starting GPS detection...');
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    userLatitude = position.coords.latitude;
                    userLongitude = position.coords.longitude;
                    
                    console.log('📍 User GPS Location:', {
                        latitude: userLatitude,
                        longitude: userLongitude,
                        accuracy: position.coords.accuracy + 'm'
                    });
                    
                    console.log('🏢 Office Location:', {
                        latitude: OFFICE_LATITUDE,
                        longitude: OFFICE_LONGITUDE
                    });
                    
                    // Hitung jarak dengan koordinat yang benar
                    const distance = calculateDistance(userLatitude, userLongitude, OFFICE_LATITUDE, OFFICE_LONGITUDE);
                    
                    console.log('📏 Calculated Distance:', Math.round(distance) + 'm');
                    console.log('📐 Max Distance:', MAX_DISTANCE + 'm');
                    
                    let locationText, statusStyle;
                    if (distance <= MAX_DISTANCE) {
                        locationText = 'KANTOR';
                        statusStyle = 'background-color: #2dce89; color: white; font-weight: 500; border-radius: 8px; padding: 1rem;';
                    } else {
                        locationText = 'DILUAR KANTOR';
                        statusStyle = 'background-color: #FF204E; color: white; font-weight: 500; border-radius: 8px; padding: 1rem;';
                    }
                    
                    console.log('✅ Final Result:', locationText, '(' + Math.round(distance) + 'm)');
                    
                    // Update UI
                    $('#current-location').val(locationText);
                    $('#location-status')
                        .attr('style', statusStyle)
                        .html('<i class="fas fa-map-marker-alt mr-2"></i>' + locationText + ' <small>(' + Math.round(distance) + 'm dari kantor)</small>');
                    
                    // Enable buttons
                    $('#btn-masuk, #btn-pulang, #btn-pulang-kemarin').prop('disabled', false);
                },
                function(error) {
                    console.error('❌ GPS Error:', error);
                    
                    $('#location-status')
                        .attr('style', 'background-color: #f5365c; color: white; font-weight: 500; border-radius: 8px; padding: 1rem;')
                        .html('<i class="fas fa-exclamation-triangle mr-2"></i>GPS Error: Tidak dapat mendeteksi lokasi');
                    
                    // Tetap disable button jika GPS error (sesuai aturan)
                    $('#btn-masuk, #btn-pulang, #btn-pulang-kemarin').prop('disabled', true);
                    
                    alert('❌ GPS Error: ' + error.message + '\nAbsensi hanya bisa dilakukan jika GPS terdeteksi (sesuai aturan).');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 30000,
                    maximumAge: 300000
                }
            );
        } else {
            alert('❌ Browser tidak mendukung GPS. Absensi tidak dapat dilakukan.');
        }
    }

    // Distance calculation yang akurat
    function calculateDistance(lat1, lon1, lat2, lon2) {
        console.log('🧮 Calculating distance:');
        console.log('  From: ' + lat1 + ', ' + lon1);
        console.log('  To: ' + lat2 + ', ' + lon2);
        
        const R = 6371000; // Earth radius in meters
        
        // Convert to radians
        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                  Math.cos(φ1) * Math.cos(φ2) *
                  Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        const distance = R * c;
        
        console.log('📏 Result:', Math.round(distance) + 'm');
        return distance;
    }

    // Handle shift change
    $('#shift-select').change(function() {
        const selectedShift = $(this).val();
        
        $.ajax({
            url: '{{ route("absensi.update-shift") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                shift: selectedShift
            },
            success: function(response) {
                if (response.success) {
                    $('#shift-select').addClass('border-success');
                    setTimeout(() => {
                        $('#shift-select').removeClass('border-success');
                    }, 2000);
                }
            },
            error: function(xhr) {
                alert('Gagal mengubah shift.');
                location.reload();
            }
        });
    });

    // Handle absen masuk (hari ini)
    $('#btn-masuk').click(function() {
        console.log('🎯 Starting attendance check-in (TODAY)...');
        
        if (!userLatitude || !userLongitude) {
            alert('⚠️ GPS belum terdeteksi. Tidak dapat melakukan absensi.');
            return;
        }

        $('#loadingModal').modal('show');
        
        $.ajax({
            url: '{{ route("absensi.masuk") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                latitude: userLatitude,
                longitude: userLongitude
            },
            success: function(response) {
                $('#loadingModal').modal('hide');
                
                if (response.success) {
                    alert('✅ ' + response.message + '\n📍 Lokasi: ' + response.location + '\n🕐 Waktu: ' + response.time);
                    location.reload();
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function(xhr) {
                $('#loadingModal').modal('hide');
                alert('❌ Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });

    // Handle absen pulang (hari ini)
    $('#btn-pulang').click(function() {
        console.log('🎯 Starting attendance check-out (TODAY)...');
        
        if (!userLatitude || !userLongitude) {
            alert('⚠️ GPS belum terdeteksi. Tidak dapat melakukan absensi.');
            return;
        }

        $('#loadingModal').modal('show');
        
        $.ajax({
            url: '{{ route("absensi.pulang") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                latitude: userLatitude,
                longitude: userLongitude
            },
            success: function(response) {
                $('#loadingModal').modal('hide');
                if (response.success) {
                    alert('✅ ' + response.message + '\n📍 Lokasi: ' + response.location + '\n🕐 Waktu: ' + response.time);
                    location.reload();
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function(xhr) {
                $('#loadingModal').modal('hide');
                alert('❌ Terjadi kesalahan.');
            }
        });
    });

    // Handle absen pulang KEMARIN
    $('#btn-pulang-kemarin').click(function() {
        console.log('🎯 Starting attendance check-out (YESTERDAY)...');
        
        if (!userLatitude || !userLongitude) {
            alert('⚠️ GPS belum terdeteksi. Tidak dapat melakukan absensi.');
            return;
        }

        const tanggalKemarin = $(this).data('tanggal');
        
        if (!confirm('Konfirmasi absen pulang untuk tanggal ' + tanggalKemarin + '?')) {
            return;
        }

        $('#loadingModal').modal('show');
        
        $.ajax({
            url: '{{ route("absensi.pulang") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                latitude: userLatitude,
                longitude: userLongitude,
                tanggal_pulang: tanggalKemarin // Key: kirim tanggal kemarin
            },
            success: function(response) {
                $('#loadingModal').modal('hide');
                if (response.success) {
                    alert('✅ Absen pulang berhasil!\n📅 Tanggal: ' + (response.date || tanggalKemarin) + '\n📍 Lokasi: ' + response.location + '\n🕐 Waktu: ' + response.time);
                    location.reload();
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function(xhr) {
                $('#loadingModal').modal('hide');
                alert('❌ Terjadi kesalahan.');
            }
        });
    });

    // Initialize GPS
    console.log('🚀 Initializing GPS with coordinates: ' + OFFICE_LATITUDE + ', ' + OFFICE_LONGITUDE);
    getUserLocation();
});
</script>
@endsection