@extends('layouts.app')

@section('page-title', 'Riwayat Absensi')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">
            <i class="fas fa-history mr-2 text-primary"></i>Riwayat Absen
        </h1>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Absensi Semua Magang</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th>NAMA</th>
                        <th>TANGGAL</th>
                        <th>SHIFT</th>
                        <th>MASUK</th>
                        <th>PULANG</th>
                        <th>LOKASI</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $item)
                    <tr>
                        <td class="font-weight-bold">{{ $item->user->name }}</td>
                        <td>{{ $item->tanggal->format('d M Y') }}</td>
                        <td>{{ ucfirst($item->user->shift) }}</td>
                        <td>
                            @if($item->jam_masuk)
                                <span class="text-success font-weight-bold">{{ $item->jam_masuk }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->jam_pulang)
                                <span class="text-success font-weight-bold">{{ $item->jam_pulang }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-success">{{ $item->lokasi_masuk ?? $item->user->lokasi }}</span>
                            @if($item->lokasi_pulang && $item->lokasi_pulang != $item->lokasi_masuk)
                                <br><span class="badge badge-info mt-1">{{ $item->lokasi_pulang }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item->jam_pulang)
                                <span class="badge badge-success">SUDAH PULANG</span>
                            @elseif($item->jam_masuk)
                                <span class="badge badge-danger">Absen Pulang</span>
                            @else
                                <span class="badge badge-secondary">Belum Absen</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada data absensi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $absensi->links() }}
        </div>
    </div>
</div>
@endsection