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
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }

  
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => true,
        ]);


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Kullanıcı başarıyla kaydedildi',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
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


        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz giriş bilgileri',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

    
        if ($user->role !== 'user' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz kullanıcı türü',
            ], 403);
        }

  
        if (!$user->status) {
            return response()->json([
                'success' => false,
                'message' => 'Hesabınız aktif değil. Lütfen yönetici ile iletişime geçin.',
            ], 403);
        }

   
        $user->tokens()->delete();
        

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Giriş başarılı',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
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
            'message' => 'Kullanıcı bilgileri',
            'data' => [
                'user' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'last_name' => $request->user()->last_name,
                    'email' => $request->user()->email,
                    'phone' => $request->user()->phone,
                    'role' => $request->user()->role,
                ]
            ]
        ]);
    }

    /**
     * Kullanıcı profilini günceller
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'phone' => 'string|max:20',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'string|min:6',
            'new_password' => 'string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }


        if ($request->has('current_password') && $request->has('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mevcut şifre yanlış',
                ], 422);
            }
            
            $user->password = Hash::make($request->new_password);
        }

   
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        
        if ($request->has('last_name')) {
            $user->last_name = $request->last_name;
        }
        
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil başarıyla güncellendi',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                ]
            ]
        ]);
    }
    
    /**
     * Kullanıcının siparişlerini getirir
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserOrders(Request $request)
    {
        $user = $request->user();
        
        $orders = \App\Models\Order::with(['items.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        
        return response()->json([
            'success' => true,
            'message' => 'Kullanıcı siparişleri',
            'data' => $orders
        ]);
    }
} 