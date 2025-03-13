<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutCartSection;
use Illuminate\Support\Facades\File;

class AboutCartSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aboutCartSection = AboutCartSection::first();
        $canCreate = is_null($aboutCartSection);
        return view('back.admin.about-cart-sections.index', compact('aboutCartSection', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Zaten bir kayıt varsa, düzenleme sayfasına yönlendir
        $aboutCartSection = AboutCartSection::first();
        if ($aboutCartSection) {
            return redirect()->route('back.pages.about-cart-sections.edit', $aboutCartSection->id)
                ->with('error', 'Bu bölmə üçün məlumat artıq mövcuddur. Mövcud məlumatı redaktə edə bilərsiniz.');
        }
        
        return view('back.admin.about-cart-sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Zaten bir kayıt varsa, onu düzenle
        $aboutCartSection = AboutCartSection::first();
        if ($aboutCartSection) {
            return redirect()->route('back.pages.about-cart-sections.edit', $aboutCartSection->id)
                ->with('error', 'Bu bölmə üçün məlumat artıq mövcuddur. Mövcud məlumatı redaktə edə bilərsiniz.');
        }
        
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'name_az' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Görsel yükleme
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about-cart-sections'), $imageName);
            $data['image'] = 'uploads/about-cart-sections/' . $imageName;
        }

        AboutCartSection::create($data);

        return redirect()->route('back.pages.about-cart-sections.index')->with('success', 'Məlumat uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('back.pages.about-cart-sections.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $aboutCartSection = AboutCartSection::findOrFail($id);
        return view('back.admin.about-cart-sections.edit', compact('aboutCartSection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $aboutCartSection = AboutCartSection::findOrFail($id);
        
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'name_az' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($aboutCartSection->image && File::exists(public_path($aboutCartSection->image))) {
                File::delete(public_path($aboutCartSection->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about-cart-sections'), $imageName);
            $data['image'] = 'uploads/about-cart-sections/' . $imageName;
        }

        $aboutCartSection->update($data);

        return redirect()->route('back.pages.about-cart-sections.index')->with('success', 'Məlumat uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aboutCartSection = AboutCartSection::findOrFail($id);
        
        // Görselleri sil
        if ($aboutCartSection->image && File::exists(public_path($aboutCartSection->image))) {
            File::delete(public_path($aboutCartSection->image));
        }
        
        $aboutCartSection->delete();

        return redirect()->route('back.pages.about-cart-sections.index')->with('success', 'Məlumat uğurla silindi.');
    }
    
    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $aboutCartSection = AboutCartSection::findOrFail($id);
        $aboutCartSection->status = !$aboutCartSection->status;
        $aboutCartSection->save();

        // AJAX yanıtı için JSON dönüşü yap
        if(request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status uğurla dəyişdirildi.',
                'status' => $aboutCartSection->status
            ]);
        }

        return redirect()->route('back.pages.about-cart-sections.index')->with('success', 'Status uğurla dəyişdirildi.');
    }
}
