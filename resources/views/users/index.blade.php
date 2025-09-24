@extends('layouts.app')

@section('page-title', 'Kelola User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-users mr-2 text-primary"></i>Kelola User
            </h1>
            <a href="{{ route('users.create') }}" class="btn btn-primary shadow">
                <i class="fas fa-plus mr-2"></i>Tambah User
            </a>
        </div>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data User Magang</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Shift</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage()-1) * $users->perPage() }}</td>
                        <td class="font-weight-bold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-{{ $user->role == 'mentor' ? 'primary' : 'success' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ ucfirst($user->shift) }}</td>
                        <td>{{ $user->lokasi }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada data user</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection