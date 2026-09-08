<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Demo Quick Switcher for competition/demo presentation
     */
    public function demoLogin(string $role)
    {
        $email = match ($role) {
            'desa' => 'desa@gresaktif.id',
            'kecamatan' => 'kecamatan@gresaktif.id',
            'kabupaten' => 'kabupaten@gresaktif.id',
            'admin' => 'admin@gresaktif.id',
            default => 'masyarakat@gresaktif.id',
        };

        $user = User::where('email', $email)->first();
        if ($user) {
            Auth::login($user);
            return $this->redirectBasedOnRole($user);
        }

        return \Illuminate\Support\Facades\Redirect::route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return \Illuminate\Support\Facades\Redirect::route('home');
    }

    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isVillageAdmin()) {
            return \Illuminate\Support\Facades\Redirect::route('dashboard.village');
        }
        if ($user->isDistrictAdmin()) {
            return \Illuminate\Support\Facades\Redirect::route('dashboard.district');
        }
        if ($user->isRegencyAdmin() || $user->isSuperAdmin()) {
            return \Illuminate\Support\Facades\Redirect::route('dashboard.regency');
        }
        return \Illuminate\Support\Facades\Redirect::route('explore');
    }
}
