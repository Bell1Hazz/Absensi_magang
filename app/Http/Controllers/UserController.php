<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Jika request AJAX untuk DataTables
        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'email', 'role', 'shift', 'lokasi', 'profile_photo', 'created_at']);
            
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function($user) {
                    $editUrl = route('users.edit', $user->id);
                    return '
                        <a href="'.$editUrl.'" class="btn btn-sm btn-warning mr-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser('.$user->id.')">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->addColumn('profile_photo_status', function($user) {
                    if ($user->profile_photo) {
                        return '<span class="badge badge-success">Ada</span>';
                    }
                    return '<span class="badge badge-secondary">Tidak Ada</span>';
                })
                ->editColumn('role', function($user) {
                    $badgeClass = $user->role == 'mentor' ? 'primary' : 'success';
                    return '<span class="badge badge-'.$badgeClass.'">'.ucfirst($user->role).'</span>';
                })
                ->editColumn('shift', function($user) {
                    return ucfirst($user->shift);
                })
                ->editColumn('created_at', function($user) {
                    return $user->created_at->format('d M Y');
                })
                ->rawColumns(['action', 'role', 'profile_photo_status'])
                ->make(true);
        }
        
        return view('users.index');
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:magang,mentor',
            'shift' => 'required|string',
            'lokasi' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'shift' => $request->shift,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:magang,mentor',
            'shift' => 'required|string',
            'lokasi' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'shift' => $request->shift,
            'lokasi' => $request->lokasi,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = $request->password;
        }

        $user->update($updateData);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true, 
                'message' => 'User berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal hapus user: ' . $e->getMessage()
            ]);
        }
    }

    // Generate Fake Data
    public function generateFakeData(Request $request)
    {
        try {
            $faker = \Faker\Factory::create('id_ID');
            
            // Generate 50 user magang
            for ($i = 1; $i <= 50; $i++) {
                $name = $faker->name;
                $email = strtolower(str_replace(' ', '.', $name)) . $i . '@magang.com';
                
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'magang',
                    'shift' => $faker->randomElement(['pagi', 'sore']),
                    'lokasi' => $faker->randomElement(['KANTOR', 'REMOTE', 'CABANG SOLO']),
                    'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                ]);
            }
            
            // Generate 5 mentor
            for ($i = 1; $i <= 5; $i++) {
                User::create([
                    'name' => $faker->name,
                    'email' => 'mentor.fake.' . $i . '@company.com',
                    'password' => Hash::make('password'),
                    'role' => 'mentor',
                    'shift' => 'pagi',
                    'lokasi' => 'KANTOR',
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil generate 50 user magang dan 5 mentor!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate data: ' . $e->getMessage()
            ]);
        }
    }

    // Export Users
    public function export(Request $request)
    {
        $role = $request->get('role');
        $format = $request->get('format', 'xlsx');
        
        $filename = 'users_' . ($role ?: 'all') . '_' . date('Y-m-d_H-i-s') . '.' . $format;
        
        return Excel::download(new UsersExport($role), $filename);
    }
}