<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SessionsController extends Controller
{
    public function create()
    {
        // Jika sudah login, langsung redirect ke halaman giling
        if (Auth::check()) {
            return redirect()->route('giling.index');
        }

        return view('session.login-session');
    }

    public function store()
    {
        // Cek jika sudah login, langsung arahkan ke halaman giling
        if (Auth::check()) {
            return redirect()->route('giling.index');
        }

        // Validasi input form login
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login jika tidak ada session yang aktif
        if (Auth::attempt($attributes)) {
            session()->regenerate();
            // Setelah login berhasil, arahkan ke halaman giling
            return redirect()->route('giling.index');
        } else {
            // Jika login gagal, kembali ke halaman login dengan pesan error
            return back()->withErrors(['email' => 'Email or password invalid.']);
        }
    }

    public function destroy()
    {
        Auth::logout();

        return redirect('/login')->with(['success' => 'You\'ve been logged out.']);
    }
}
