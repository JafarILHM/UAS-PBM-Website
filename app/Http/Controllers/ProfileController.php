<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan Halaman Profil
     */
    public function index()
    {
        return view('profile.index', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update Profil (Nama, Email, Password)
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id), // Cek unik kecuali punya sendiri
            ],
            'password' => 'nullable|min:6|confirmed', // Confirmed butuh input name="password_confirmation"
        ]);

        // Update Data Dasar
        $user->name = $request->name;
        $user->email = $request->email;

        // Update Password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Simpan perubahan

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
