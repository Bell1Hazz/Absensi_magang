@extends('layouts.app')

@section('page-title', 'Izin Saya')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-calendar-alt mr-2 text-primary"></i>Izin Saya
            </h1>
            <a href="{{ route('izin.create') }}" class="btn btn-primary shadow">
                <i class="fas fa-plus mr-2"></i>Ajukan Izin
            </a>
        </div>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Pengajuan Izin</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Keterangan Mentor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($izinList as $izin)
                    <tr>
                        <td>{{ $loop->iteration + ($izinList->currentPage()-1) * $izinList->perPage() }}</td>
                        <td>
                            {{ $izin->tanggal_mulai->format('d M Y') }}
                            @if($izin->tanggal_mulai != $izin->tanggal_selesai)
                                <br><small class="text-muted">s/d {{ $izin->tanggal_selesai->format('d M Y') }}</small>
                            @endif
                        </td>
                        <td>{!! $izin->jenis_izin_badge !!}</td>
                        <td>
                            <span title="{{ $izin->alasan }}">{{ Str::limit($izin->alasan, 50) }}</span>
                        </td>
                        <td>{!! $izin->status_badge !!}</td>
                        <td>{{ $izin->keterangan_mentor ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
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
@endsection