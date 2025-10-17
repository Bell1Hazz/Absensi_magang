@extends('layouts.app')

@section('content')
<div class="dashboard-content">
    <div class="row">
        <div class="col-12">
            <div class="card-dashboard">
                <div class="card-body">
                    <!-- Header dengan Export dan Generate -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="section-header" style="color: #5e72e4; margin-bottom: 0;">
                            <div class="section-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            Kelola User
                        </div>
                        <div>
                            <button class="btn btn-info btn-sm mr-2" onclick="generateFakeData()">
                                <i class="fas fa-database mr-1"></i>Generate Data Faker
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-download mr-1"></i>Export
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('users.export', ['format' => 'xlsx']) }}">
                                        <i class="fas fa-file-excel mr-2"></i>Export XLSX
                                    </a>
                                    <a class="dropdown-item" href="{{ route('users.export', ['format' => 'csv']) }}">
                                        <i class="fas fa-file-csv mr-2"></i>Export CSV
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('users.export', ['role' => 'magang', 'format' => 'xlsx']) }}">
                                        <i class="fas fa-users mr-2"></i>Export Magang Only
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm ml-2">
                                <i class="fas fa-plus mr-1"></i>Tambah User
                            </a>
                        </div>
                    </div>
                    
                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="users-table" width="100%">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Shift</th>
                                    <th>Lokasi</th>
                                    <th>Foto Profil</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // DataTable initialization
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("users.index") }}',
            error: function(xhr, error, code) {
                console.error('DataTables Ajax Error:', xhr.responseText);
                alert('Error loading data: ' + xhr.responseText);
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'role', name: 'role'},
            {data: 'shift', name: 'shift'},
            {data: 'lokasi', name: 'lokasi'},
            {data: 'profile_photo_status', name: 'profile_photo_status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        pageLength: 25,
        responsive: true,
        order: [[1, 'asc']] // Sort by nama
    });
});

// Generate Fake Data
function generateFakeData() {
    if (!confirm('Generate 50 user magang dan 5 mentor fake data?\n\nIni akan menambah data ke database.')) {
        return;
    }
    
    // Show loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Generating...';
    btn.disabled = true;
    
    $.ajax({
        url: '{{ route("users.generate-fake") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            if (response.success) {
                alert('✅ ' + response.message);
                $('#users-table').DataTable().ajax.reload();
            } else {
                alert('❌ ' + response.message);
            }
        },
        error: function(xhr) {
            btn.innerHTML = originalText;
            btn.disabled = false;
            console.error('Generate Error:', xhr.responseText);
            alert('❌ Gagal generate data: ' + xhr.responseText);
        }
    });
}

// Delete User
function deleteUser(id) {
    if (!confirm('Yakin ingin menghapus user ini?')) {
        return;
    }
    
    $.ajax({
        url: '/users/' + id,
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert('✅ ' + response.message);
                $('#users-table').DataTable().ajax.reload();
            } else {
                alert('❌ Gagal hapus user.');
            }
        },
        error: function(xhr) {
            console.error('Delete Error:', xhr.responseText);
            alert('❌ Gagal hapus user: ' + xhr.responseText);
        }
    });
}
</script>
@endsection