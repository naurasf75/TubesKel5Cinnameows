<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Illuminate\Http\Request;
use App\Models\User;


class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        return redirect()->route('login');

    }

    public function showLogin()
    {
        if (Auth::check()){
            return back();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role==='admin'){
                return redirect()->route('dashboard');
            }
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

   public function logout(Request $request)
{
    Auth::logout(); // keluar dari sesi auth

    $request->session()->invalidate(); // hapus semua sesi
    $request->session()->regenerateToken(); // buat ulang CSRF token

    return redirect()->route('login'); // redirect ke halaman login
}

}
