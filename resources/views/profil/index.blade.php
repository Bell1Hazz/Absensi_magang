@extends('layouts.app')

@section('content')
<div class="dashboard-content">
    
    <!-- Profile Header -->
    <div class="row">
        <div class="col-12">
            <div class="card-dashboard">
                <div class="card-body">
                    <div class="section-header" style="color: #5e72e4;">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        Profil Saya
                    </div>
                    
                    <div class="row">
                        <!-- Profile Photo Section -->
                        <div class="col-md-4 text-center">
                            <div class="profile-photo-large-container mb-4">
                                <img src="{{ $user->profile_photo_url }}" 
                                     alt="Foto Profil {{ $user->name }}"
                                     class="profile-photo-large"
                                     id="profile-photo-preview"
                                     onload="this.style.display='block'; document.getElementById('fallback-large').style.display='none';"
                                     onerror="this.style.display='none'; document.getElementById('fallback-large').style.display='flex';">
                                
                                <!-- Fallback untuk foto besar -->
                                <div id="fallback-large" class="profile-photo-large-fallback" style="display: none;">
                                    {{ $user->initials }}
                                </div>
                            </div>
                            
                            <!-- Photo Actions -->
                            <div class="profile-photo-actions">
                                <label for="photo-upload" class="btn btn-primary btn-sm mr-2">
                                    <i class="fas fa-camera mr-1"></i>
                                    {{ $user->hasProfilePhoto() ? 'Ganti Foto' : 'Upload Foto' }}
                                </label>
                                
                                @if($user->hasProfilePhoto())
                                <button type="button" class="btn btn-danger btn-sm" id="delete-photo-btn">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                                @endif
                            </div>
                            
                            <!-- Hidden File Input -->
                            <input type="file" id="photo-upload" class="d-none" accept="image/*">
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    Format: JPEG, PNG, JPG<br>
                                    Ukuran maksimal: 2MB
                                </small>
                            </div>
                        </div>
                        
                        <!-- User Info -->
                        <div class="col-md-8">
                            <h5 class="mb-3 text-primary">Informasi Akun</h5>
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold">Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                            </div>
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold">Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted font-weight-bold">Role</label>
                                        <input type="text" class="form-control" value="Magang" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted font-weight-bold">Shift</label>
                                        <input type="text" class="form-control" value="{{ ucfirst($user->shift) }}" disabled>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold">Lokasi</label>
                                <input type="text" class="form-control" value="{{ $user->lokasi }}" disabled>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-left mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3"></div>
                <h6>Memproses foto profil...</h6>
            </div>
        </div>
    </div>
</div>

<style>
.profile-photo-large-container {
    position: relative;
    display: inline-block;
}

.profile-photo-large {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.profile-photo-large-fallback {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: #5e72e4;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: 600;
    border: 4px solid #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.profile-photo-actions {
    margin-top: 1rem;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    
    console.log('🖼️ Current photo URL:', '{{ $user->profile_photo_url }}');
    console.log('📁 Has profile photo:', {{ $user->hasProfilePhoto() ? 'true' : 'false' }});
    
    // Handle photo upload
    $('#photo-upload').change(function() {
        const file = this.files[0];
        if (!file) return;
        
        console.log('📤 Uploading file:', file.name, 'Size:', file.size);
        
        // Validasi file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!allowedTypes.includes(file.type)) {
            alert('❌ File harus berformat JPEG, PNG, atau JPG');
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) { // 2MB
            alert('❌ Ukuran file maksimal 2MB');
            return;
        }
        
        // Preview foto sebelum upload
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#profile-photo-preview').attr('src', e.target.result).show();
            $('#fallback-large').hide();
            console.log('📸 Preview loaded');
        };
        reader.readAsDataURL(file);
        
        // Upload foto
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        $('#loadingModal').modal('show');
        
        $.ajax({
            url: '{{ route("profil.upload-photo") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#loadingModal').modal('hide');
                
                console.log('📤 Upload response:', response);
                
                if (response.success) {
                    alert('✅ ' + response.message);
                    console.log('🔄 Reloading page to show new photo...');
                    
                    // Force reload dengan timestamp untuk clear cache
                    setTimeout(() => {
                        window.location.href = window.location.href + '?t=' + Date.now();
                    }, 500);
                } else {
                    alert('❌ ' + response.message);
                    // Restore fallback
                    $('#profile-photo-preview').hide();
                    $('#fallback-large').show();
                }
            },
            error: function(xhr) {
                $('#loadingModal').modal('hide');
                console.error('❌ Upload Ajax Error:', xhr.responseText);
                alert('❌ Gagal upload foto. Silakan coba lagi.');
                $('#profile-photo-preview').hide();
                $('#fallback-large').show();
            }
        });
    });
    
    // Handle photo delete
    $('#delete-photo-btn').click(function() {
        if (!confirm('Yakin ingin menghapus foto profil?')) {
            return;
        }
        
        console.log('🗑️ Deleting photo...');
        
        $('#loadingModal').modal('show');
        
        $.ajax({
            url: '{{ route("profil.delete-photo") }}',
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#loadingModal').modal('hide');
                
                console.log('🗑️ Delete response:', response);
                
                if (response.success) {
                    alert('✅ ' + response.message);
                    console.log('🔄 Reloading page...');
                    
                    // Force reload
                    setTimeout(() => {
                        window.location.href = window.location.href + '?t=' + Date.now();
                    }, 500);
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function(xhr) {
                $('#loadingModal').modal('hide');
                console.error('❌ Delete Ajax Error:', xhr.responseText);
                alert('❌ Gagal hapus foto. Silakan coba lagi.');
            }
        });
    });
});
</script>
@endsection