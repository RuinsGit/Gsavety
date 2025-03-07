<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogoController extends Controller
{
    public function index()
    {
        $logos = Logo::all();
        $logoCount = $logos->count();
        return view('back.admin.logos.index', compact('logos', 'logoCount'));
    }

    public function create()
    {
        $logoCount = Logo::count();
        return view('back.admin.logos.create', compact('logoCount'));
    }

    public function store(Request $request)
    {
        $logoCount = Logo::count();

        if ($logoCount >= 1) {
            return redirect()->route('back.pages.logos.index')->with('error', 'Zaten bir logo mevcut. Yeni bir logo ekleyemezsiniz.');
        }

        $request->validate([
            'logo_1_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_2_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_alt1_az' => 'required|string',
            'logo_alt1_en' => 'required|string',
            'logo_alt1_ru' => 'required|string',
            'logo_alt2_az' => 'required|string',
            'logo_alt2_en' => 'required|string',
            'logo_alt2_ru' => 'required|string',
            'logo_title1_az' => 'required|string',
            'logo_title1_en' => 'required|string',
            'logo_title1_ru' => 'required|string',
            'logo_title2_az' => 'required|string',
            'logo_title2_en' => 'required|string',
            'logo_title2_ru' => 'required|string',
        ], [
            'logo_1_image.required' => 'Logo 1 şəkli mütləq yüklənməlidir',
            'logo_2_image.required' => 'Logo 2 şəkli mütləq yüklənməlidir',
        ]);

        $logo = new Logo();

       
        if ($request->hasFile('logo_1_image')) {
            $image = $request->file('logo_1_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/logos'), $imageName);
            $logo->logo_1_image = 'uploads/logos/' . $imageName;
        }

        if ($request->hasFile('logo_2_image')) {
            $image = $request->file('logo_2_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/logos'), $imageName);
            $logo->logo_2_image = 'uploads/logos/' . $imageName;
        }

        $logo->logo_alt1_az = $request->logo_alt1_az;
        $logo->logo_alt1_en = $request->logo_alt1_en;
        $logo->logo_alt1_ru = $request->logo_alt1_ru;
        $logo->logo_alt2_az = $request->logo_alt2_az;
        $logo->logo_alt2_en = $request->logo_alt2_en;
        $logo->logo_alt2_ru = $request->logo_alt2_ru;
        $logo->logo_title1_az = $request->logo_title1_az;
        $logo->logo_title1_en = $request->logo_title1_en;
        $logo->logo_title1_ru = $request->logo_title1_ru;
        $logo->logo_title2_az = $request->logo_title2_az;
        $logo->logo_title2_en = $request->logo_title2_en;
        $logo->logo_title2_ru = $request->logo_title2_ru;

        $logo->save();

        return redirect()->route('back.pages.logos.index')->with('success', 'Logo başarıyla eklendi.');
    }

    public function edit($id)
    {
        $logo = Logo::findOrFail($id);
        return view('back.admin.logos.edit', compact('logo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'logo_1_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_2_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_alt1_az' => 'required|string',
            'logo_alt1_en' => 'required|string',
            'logo_alt1_ru' => 'required|string',
            'logo_alt2_az' => 'required|string',
            'logo_alt2_en' => 'required|string',
            'logo_alt2_ru' => 'required|string',
            'logo_title1_az' => 'required|string',
            'logo_title1_en' => 'required|string',
            'logo_title1_ru' => 'required|string',
            'logo_title2_az' => 'required|string',
            'logo_title2_en' => 'required|string',
            'logo_title2_ru' => 'required|string',
        ]);

        $logo = Logo::findOrFail($id);

        if ($request->hasFile('logo_1_image')) {
            if ($logo->logo_1_image && File::exists(public_path($logo->logo_1_image))) {
                File::delete(public_path($logo->logo_1_image));
            }

            $image = $request->file('logo_1_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/logos'), $imageName);
            $logo->logo_1_image = 'uploads/logos/' . $imageName;
        }

        if ($request->hasFile('logo_2_image')) {
            if ($logo->logo_2_image && File::exists(public_path($logo->logo_2_image))) {
                File::delete(public_path($logo->logo_2_image));
            }

            $image = $request->file('logo_2_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/logos'), $imageName);
            $logo->logo_2_image = 'uploads/logos/' . $imageName;
        }

        $logo->logo_alt1_az = $request->logo_alt1_az;
        $logo->logo_alt1_en = $request->logo_alt1_en;
        $logo->logo_alt1_ru = $request->logo_alt1_ru;
        $logo->logo_alt2_az = $request->logo_alt2_az;
        $logo->logo_alt2_en = $request->logo_alt2_en;
        $logo->logo_alt2_ru = $request->logo_alt2_ru;
        $logo->logo_title1_az = $request->logo_title1_az;
        $logo->logo_title1_en = $request->logo_title1_en;
        $logo->logo_title1_ru = $request->logo_title1_ru;
        $logo->logo_title2_az = $request->logo_title2_az;
        $logo->logo_title2_en = $request->logo_title2_en;
        $logo->logo_title2_ru = $request->logo_title2_ru;

        $logo->save();

        return redirect()->route('back.pages.logos.index')->with('success', 'Logo uğurla yeniləndi.');
    }

    public function destroy($id)
    {
        $logo = Logo::findOrFail($id);
        
        if ($logo->logo_1_image && File::exists(public_path($logo->logo_1_image))) {
            File::delete(public_path($logo->logo_1_image));
        }
        
        if ($logo->logo_2_image && File::exists(public_path($logo->logo_2_image))) {
            File::delete(public_path($logo->logo_2_image));
        }

        $logo->delete();

        return redirect()->route('back.pages.logos.index')->with('success', 'Logo başarıyla silindi.');
    }

    public function toggleStatus($id)
    {
        $logo = Logo::findOrFail($id);
        if ($logo->status) {
            $logo->status = null; 
        } else {
            $logo->status = 1; 
        }
        $logo->save();

        return redirect()->route('back.pages.logos.index')->with('success', 'Status uğurla dəyişdirildi');
    }
}