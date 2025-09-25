<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isMagang()) {
            return redirect()->route('dashboard')->with('error', 'Fitur profil hanya untuk magang');
        }
        
        return view('profil.index', compact('user'));
    }

    public function uploadPhoto(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isMagang()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Hapus foto lama jika ada
            $this->deleteOldPhoto($user);
            
            // Method yang lebih reliable: Simpan ke public/uploads
            $file = $request->file('photo');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Pastikan folder ada
            $uploadPath = public_path('uploads/profile_photos');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
                \Log::info('Created upload directory: ' . $uploadPath);
            }
            
            // Simpan file
            $file->move($uploadPath, $filename);
            
            // Verify file saved
            $fullPath = $uploadPath . '/' . $filename;
            if (!file_exists($fullPath)) {
                throw new \Exception('File gagal disimpan ke: ' . $fullPath);
            }
            
            // Update user dengan path yang benar
            $relativePath = 'uploads/profile_photos/' . $filename;
            $user->update(['profile_photo' => $relativePath]);
            
            $photoUrl = asset($relativePath);
            
            \Log::info('Photo uploaded successfully:', [
                'user_id' => $user->id,
                'filename' => $filename,
                'full_path' => $fullPath,
                'relative_path' => $relativePath,
                'url' => $photoUrl,
                'file_exists' => file_exists($fullPath),
                'file_size' => filesize($fullPath)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupload!',
                'photo_url' => $photoUrl
            ]);

        } catch (\Exception $e) {
            \Log::error('Photo upload failed:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'file_original_name' => $request->file('photo')->getClientOriginalName()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload foto: ' . $e->getMessage()
            ]);
        }
    }

    public function deletePhoto()
    {
        $user = Auth::user();
        
        if (!$user->isMagang()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        try {
            // Hapus foto lama
            $this->deleteOldPhoto($user);
            
            // Update user record
            $user->update(['profile_photo' => null]);
            
            \Log::info('Photo deleted successfully:', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus!',
                'photo_url' => $user->getDefaultAvatarUrl()
            ]);

        } catch (\Exception $e) {
            \Log::error('Photo delete failed:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus foto: ' . $e->getMessage()
            ]);
        }
    }

    // Helper method untuk hapus foto lama
    private function deleteOldPhoto($user)
    {
        if (!$user->profile_photo) {
            return;
        }
        
        // Try multiple deletion methods
        $deleted = false;
        
        // Method 1: Storage disk
        if (Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
            $deleted = true;
            \Log::info('Old photo deleted (storage): ' . $user->profile_photo);
        }
        
        // Method 2: Public folder direct
        if (file_exists(public_path($user->profile_photo))) {
            unlink(public_path($user->profile_photo));
            $deleted = true;
            \Log::info('Old photo deleted (public): ' . $user->profile_photo);
        }
        
        // Method 3: Public uploads folder
        $uploadPath = public_path('uploads/profile_photos/' . basename($user->profile_photo));
        if (file_exists($uploadPath)) {
            unlink($uploadPath);
            $deleted = true;
            \Log::info('Old photo deleted (uploads): ' . $uploadPath);
        }
        
        if (!$deleted) {
            \Log::warning('Old photo not found for deletion: ' . $user->profile_photo);
        }
    }
}