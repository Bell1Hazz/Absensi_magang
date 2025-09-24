@extends('layouts.app')

@section('page-title', 'Kelola Izin')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">
            <i class="fas fa-clipboard-list mr-2 text-primary"></i>Kelola Izin
        </h1>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pengajuan Izin Magang</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($izinList as $izin)
                    <tr>
                        <td>{{ $loop->iteration + ($izinList->currentPage()-1) * $izinList->perPage() }}</td>
                        <td class="font-weight-bold">{{ $izin->user->name }}</td>
                        <td>
                            {{ $izin->tanggal_mulai->format('d M Y') }}
                            @if($izin->tanggal_mulai != $izin->tanggal_selesai)
                                <br><small class="text-muted">s/d {{ $izin->tanggal_selesai->format('d M Y') }}</small>
                            @endif
                        </td>
                        <td>{!! $izin->jenis_izin_badge !!}</td>
                        <td>
                            <span title="{{ $izin->alasan }}">{{ Str::limit($izin->alasan, 30) }}</span>
                        </td>
                        <td>{!! $izin->status_badge !!}</td>
                        <td>
                            @if($izin->status == 'pending')
                                <button class="btn btn-sm btn-success" onclick="setujuiIzin({{ $izin->id }})">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-danger ml-1" onclick="tolakIzin({{ $izin->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                <small class="text-muted">
                                    {{ $izin->tanggal_disetujui ? $izin->tanggal_disetujui->format('d M Y H:i') : '-' }}
                                </small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada pengajuan izin</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $izinList->links() }}
        </div>
    </div>
</div>

<!-- Modal Setujui -->
<div class="modal fade" id="setujuiModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check mr-2"></i>Setujui Izin
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="setujuiForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" name="keterangan_mentor" rows="3" 
                                  placeholder="Tambahkan keterangan jika perlu..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i>Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="tolakModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times mr-2"></i>Tolak Izin
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="tolakForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="keterangan_mentor" rows="3" 
                                  placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i>Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function setujuiIzin(id) {
    $('#setujuiForm').attr('action', '/izin/' + id + '/setujui');
    $('#setujuiModal').modal('show');
}

function tolakIzin(id) {
    $('#tolakForm').attr('action', '/izin/' + id + '/tolak');
    $('#tolakModal').modal('show');
}
</script>
@endsection