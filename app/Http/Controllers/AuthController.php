<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function index()
    {
        return view('pages-sign-in');
    }

    /**
     * Memproses data login yang dikirimkan form.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Ambil hanya email dan password
        $credentials = $request->only('email', 'password');

        // 3. Coba login (Attempt)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Login sukses, arahkan ke dashboard
            return redirect()->intended('/dashboard');
        }

        // 4. Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
