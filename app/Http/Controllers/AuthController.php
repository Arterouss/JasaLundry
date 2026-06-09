<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Menampilkan halaman Register/Signup
    public function showSignup()
    {
        return view('auth.register'); // Sesuaikan dengan nama file blade kamu nanti
    }

    // Proses Signup
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Secara default, user yang daftar lewat web adalah 'customer'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Di Laravel 12 otomatis di-hash karena cast 'password' => 'hashed' di model User
            'role' => 'customer', 
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard');
    }

    // Menampilkan halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Cek role untuk mengarahkan ke dashboard yang sesuai
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang kamu masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
 * Memproses data user yang dikembalikan oleh Google
 */
    public function redirectToGoogle()
        {
            return Socialite::driver('google')->redirect();
        }
public function handleGoogleCallback()
    {
        try {
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver('google');
            
            // Sekarang VS Code tahu kalau $driver ini punya fungsi stateless()
            $googleUser = $driver->stateless()->user();
            
            // Tambahkan query() agar Intelephense tahu ini adalah Eloquent Builder
            $user = User::query()->where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // Jika belum ada, buat user baru otomatis sebagai customer
                $user = User::query()->create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'role' => 'customer', 
                    'password' => Hash::make(Str::random(24)), 
                ]);
            }
            
            // Login-kan user ke sistem Laravel
            Auth::login($user);
            
            return redirect()->route('customer.dashboard')->with('success', 'Berhasil masuk menggunakan akun Google!');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login menggunakan Google.');
        }
    }
}