<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Kullanıcıların listesini gösterir
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        return view('back.admin.users.index', compact('users'));
    }

    /**
     * Yeni kullanıcı oluşturma formu
     */
    public function create()
    {
        return view('back.admin.users.create');
    }

    /**
     * Yeni kullanıcı oluşturma işlemi
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->status = $request->has('status') ? 1 : 0;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    /**
     * Kullanıcı düzenleme formu
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('back.admin.users.edit', compact('user'));
    }

    /**
     * Kullanıcı bilgilerini güncelleme
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->role = $request->role;
        $user->status = $request->has('status') ? 1 : 0;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    /**
     * Kullanıcı silme işlemi
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Admin kullanıcıyı silmeyi engelle
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Son admin kullanıcısı silinemez!');
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla silindi.');
    }

    /**
     * Kullanıcı durumunu değiştirme (aktif/pasif)
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Son admin kullanıcıyı pasif yapmayı engelle
        if ($user->role === 'admin' && $user->status && User::where('role', 'admin')->where('status', 1)->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Son aktif admin kullanıcısı pasif yapılamaz!');
        }
        
        $user->status = !$user->status;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı durumu başarıyla değiştirildi.');
    }
} 