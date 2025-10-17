@extends('layouts.app')

@section('content')
<div class="dashboard-content">
    <div class="row">
        <div class="col-12">
            <div class="card-dashboard">
                <div class="card-body">
                    <!-- Header dengan Export -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="section-header" style="color: #5e72e4; margin-bottom: 0;">
                            <div class="section-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            Riwayat Absensi
                        </div>
                        <div>
                            <button class="btn btn-warning btn-sm mr-2" onclick="generateFakeAbsensi()">
                                <i class="fas fa-database mr-1"></i>Generate Data Absensi
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-download mr-1"></i>Export
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('absensi.export', ['format' => 'xlsx']) }}">
                                        <i class="fas fa-file-excel mr-2"></i>Export XLSX
                                    </a>
                                    <a class="dropdown-item" href="{{ route('absensi.export', ['format' => 'csv']) }}">
                                        <i class="fas fa-file-csv mr-2"></i>Export CSV
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">Filter Export</h6>
                                    <a class="dropdown-item" href="#" onclick="showExportModal()">
                                        <i class="fas fa-filter mr-2"></i>Export dengan Filter
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="absensi-table" width="100%">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Shift</th>
                                    <th>Masuk</th>
                                    <th>Pulang</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Data Absensi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="export-form">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" value="{{ date('Y-m-01') }}">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" class="form-control" name="end_date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>User (Opsional)</label>
                        <select class="form-control" name="user_id">
                            <option value="">Semua User</option>
                            @foreach(\App\Models\User::where('role', 'magang')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Format</label>
                        <select class="form-control" name="format">
                            <option value="xlsx">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="exportData()">
                    <i class="fas fa-download mr-1"></i>Export
                </button>
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
    $('#absensi-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("absensi.riwayat") }}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama', name: 'user.name'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'shift', name: 'user.shift'},
            {data: 'jam_masuk', name: 'jam_masuk'},
            {data: 'jam_pulang', name: 'jam_pulang'},
            {data: 'lokasi_masuk', name: 'lokasi_masuk'},
            {data: 'status', name: 'status'}
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        pageLength: 25,
        responsive: true,
        order: [[2, 'desc']] // Sort by tanggal desc
    });
});

// Generate Fake Absensi Data
function generateFakeAbsensi() {
    if (!confirm('Generate data absensi fake untuk 30 hari terakhir?\n\nIni akan menambah banyak data ke database.')) {
        return;
    }
    
    $.ajax({
        url: '{{ route("absensi.generate-fake") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert('✅ ' + response.message);
                $('#absensi-table').DataTable().ajax.reload();
            } else {
                alert('❌ ' + response.message);
            }
        },
        error: function(xhr) {
            alert('❌ Gagal generate data absensi.');
        }
    });
}

// Show Export Modal
function showExportModal() {
    $('#exportModal').modal('show');
}

// Export Data
function exportData() {
    const formData = new FormData(document.getElementById('export-form'));
    const params = new URLSearchParams(formData);
    
    window.location.href = '{{ route("absensi.export") }}?' + params.toString();
    $('#exportModal').modal('hide');
}
</script>
@endsection