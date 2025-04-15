<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login sayfasını gösterir
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * Kullanıcı giriş işlemini gerçekleştirir
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        
        // Kullanıcı aktif mi kontrolü
        $user = User::where('email', $request->email)->first();
        if ($user && !$user->status) {
            return redirect()->back()
                ->withErrors(['email' => 'Bu hesap aktif değil. Lütfen yönetici ile iletişime geçin.'])
                ->withInput($request->except('password'));
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Admin kullanıcısı için admin paneline yönlendir
            if ($user && $user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('home'));
        }

        return redirect()->back()
            ->withErrors(['email' => 'Bu bilgilerle eşleşen bir hesap bulunamadı.'])
            ->withInput($request->except('password'));
    }

    /**
     * Kayıt ol sayfasını gösterir
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Yeni kullanıcı kaydını gerçekleştirir
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Varsayılan olarak normal kullanıcı
            'status' => true, // Varsayılan olarak aktif
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Kayıt işlemi başarıyla tamamlandı!');
    }

    /**
     * Kullanıcı çıkış işlemini gerçekleştirir
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
} 