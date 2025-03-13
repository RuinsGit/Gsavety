<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeCartSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeCartSectionController extends Controller
{
    private const MAX_CARDS = 1;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $homeCartSections = HomeCartSection::orderBy('order')->get();
        $canCreate = $homeCartSections->count() < self::MAX_CARDS;
        return view('back.admin.home-cart-sections.index', compact('homeCartSections', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $count = HomeCartSection::count();
        
        if ($count >= self::MAX_CARDS) {
            return redirect()->route('back.pages.home-cart-sections.index')
                ->with('error', 'Maksimum ' . self::MAX_CARDS . ' ədəd kart əlavə edilə bilər.');
        }
        
        return view('back.admin.home-cart-sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $count = HomeCartSection::count();
        
        if ($count >= self::MAX_CARDS) {
            return redirect()->route('back.pages.home-cart-sections.index')
                ->with('error', 'Maksimum ' . self::MAX_CARDS . ' ədəd kart əlavə edilə bilər.');
        }
        
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Resim yükleme işlemi
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/home-cart-sections'), $imageName);
            $data['image'] = 'uploads/home-cart-sections/' . $imageName;
        }

        HomeCartSection::create($data);

        return redirect()->route('back.pages.home-cart-sections.index')->with('success', 'Kart uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $homeCartSection = HomeCartSection::findOrFail($id);
        return view('back.admin.home-cart-sections.edit', compact('homeCartSection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order' => 'nullable|integer',
        ]);

        $homeCartSection = HomeCartSection::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Resim yükleme işlemi
        if ($request->hasFile('image')) {
            // Eski resmi silme
            if ($homeCartSection->image && File::exists(public_path($homeCartSection->image))) {
                File::delete(public_path($homeCartSection->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/home-cart-sections'), $imageName);
            $data['image'] = 'uploads/home-cart-sections/' . $imageName;
        }

        $homeCartSection->update($data);

        return redirect()->route('back.pages.home-cart-sections.index')->with('success', 'Kart uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $homeCartSection = HomeCartSection::findOrFail($id);
        
        // Resmi silme
        if ($homeCartSection->image && File::exists(public_path($homeCartSection->image))) {
            File::delete(public_path($homeCartSection->image));
        }
        
        $homeCartSection->delete();

        return redirect()->route('back.pages.home-cart-sections.index')->with('success', 'Kart uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $homeCartSection = HomeCartSection::findOrFail($id);
        $homeCartSection->status = !$homeCartSection->status;
        $homeCartSection->save();

        // AJAX yanıtı için JSON dönüşü yap
        if(request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kart statusu uğurla dəyişdirildi.',
                'status' => $homeCartSection->status
            ]);
        }

        return redirect()->route('back.pages.home-cart-sections.index')->with('success', 'Kart statusu uğurla dəyişdirildi.');
    }
}
