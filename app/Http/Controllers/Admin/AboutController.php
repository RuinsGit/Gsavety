<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;
use Illuminate\Support\Facades\File;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $about = About::first();
        $canCreate = is_null($about);
        return view('back.admin.about.index', compact('about', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Zaten bir kayıt varsa, düzenleme sayfasına yönlendir
        $about = About::first();
        if ($about) {
            return redirect()->route('back.pages.about.edit', $about->id)
                ->with('error', 'Haqqımızda məlumatı artıq mövcuddur. Mövcud məlumatı redaktə edə bilərsiniz.');
        }
        
        return view('back.admin.about.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Zaten bir kayıt varsa, onu düzenle
        $about = About::first();
        if ($about) {
            return redirect()->route('back.pages.about.edit', $about->id)
                ->with('error', 'Haqqımızda məlumatı artıq mövcuddur. Mövcud məlumatı redaktə edə bilərsiniz.');
        }
        
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Görsel yükleme - Resim   
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about'), $imageName);
            $data['image'] = 'uploads/about/' . $imageName;
        }
        
        // Görsel yükleme - İkon
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconName = time() . '_icon.' . $icon->getClientOriginalExtension();
            $icon->move(public_path('uploads/about'), $iconName);
            $data['icon'] = 'uploads/about/' . $iconName;
        }

        About::create($data);

        return redirect()->route('back.pages.about.index')->with('success', 'Haqqımızda məlumatı uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('back.pages.about.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $about = About::findOrFail($id);
        return view('back.admin.about.edit', compact('about'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $about = About::findOrFail($id);
        
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Görsel yükleme - Resim
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($about->image && File::exists(public_path($about->image))) {
                File::delete(public_path($about->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about'), $imageName);
            $data['image'] = 'uploads/about/' . $imageName;
        }
        
        // Görsel yükleme - İkon
        if ($request->hasFile('icon')) {
            // Eski ikonu sil
            if ($about->icon && File::exists(public_path($about->icon))) {
                File::delete(public_path($about->icon));
            }
            
            $icon = $request->file('icon');
            $iconName = time() . '_icon.' . $icon->getClientOriginalExtension();
            $icon->move(public_path('uploads/about'), $iconName);
            $data['icon'] = 'uploads/about/' . $iconName;
        }

        $about->update($data);

        return redirect()->route('back.pages.about.index')->with('success', 'Haqqımızda məlumatı uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $about = About::findOrFail($id);
        
        // Görselleri sil
        if ($about->image && File::exists(public_path($about->image))) {
            File::delete(public_path($about->image));
        }
        
        if ($about->icon && File::exists(public_path($about->icon))) {
            File::delete(public_path($about->icon));
        }
        
        $about->delete();

        return redirect()->route('back.pages.about.index')->with('success', 'Haqqımızda məlumatı uğurla silindi.');
    }
    
    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $about = About::findOrFail($id);
        $about->status = !$about->status;
        $about->save();

        return redirect()->route('back.pages.about.index')->with('success', 'Haqqımızda məlumatının statusu uğurla dəyişdirildi.');
    }
}
