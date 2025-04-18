<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Kullanıcı kayıt API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }

        // Kullanıcı oluştur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => true,
        ]);

        // Token oluştur
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Kullanıcı başarıyla kaydedildi',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }

    /**
     * Kullanıcı giriş API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }

        // Kimlik doğrulama dene
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz giriş bilgileri',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        // Kullanıcı rolünü kontrol et
        if ($user->role !== 'user' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz kullanıcı türü',
            ], 403);
        }

        // Kullanıcı durumunu kontrol et
        if (!$user->status) {
            return response()->json([
                'success' => false,
                'message' => 'Hesabınız aktif değil. Lütfen yönetici ile iletişime geçin.',
            ], 403);
        }

        // Varolan tokenleri sil
        $user->tokens()->delete();
        
        // Yeni token oluştur
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Giriş başarılı',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    /**
     * Kullanıcı çıkış API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Kullanıcı tokenini sil
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Çıkış başarılı'
        ]);
    }

    /**
     * Giriş yapmış kullanıcı bilgilerini getirir
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role,
                ]
            ]
        ]);
    }
} 