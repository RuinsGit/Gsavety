<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeHeroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $heroes = HomeHero::ordered()->get();
        return view('back.admin.home-heroes.index', compact('heroes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.home-heroes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_az' => 'required|string',
            'title_en' => 'nullable|string',
            'title_ru' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order' => 'nullable|integer',
        ]);

        $hero = new HomeHero();
        $hero->title_az = $request->title_az;
        $hero->title_en = $request->title_en;
        $hero->title_ru = $request->title_ru;
        $hero->status = $request->has('status') ? 1 : 0;
        $hero->order = $request->order ?? 0;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/heroes'), $imageName);
            $hero->image = 'uploads/heroes/' . $imageName;
        }
        
        $hero->save();
        
        return redirect()->route('back.pages.home-heroes.index')->with('success', 'Hero başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Bu metod genellikle kullanılmayacak
        return redirect()->route('back.pages.home-heroes.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hero = HomeHero::findOrFail($id);
        return view('back.admin.home-heroes.edit', compact('hero'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title_az' => 'required|string',
            'title_en' => 'nullable|string',
            'title_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order' => 'nullable|integer',
        ]);

        $hero = HomeHero::findOrFail($id);
        $hero->title_az = $request->title_az;
        $hero->title_en = $request->title_en;
        $hero->title_ru = $request->title_ru;
        $hero->status = $request->has('status') ? 1 : 0;
        $hero->order = $request->order ?? 0;
        
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($hero->image && File::exists(public_path($hero->image))) {
                File::delete(public_path($hero->image));
            }
            
            // Yeni resmi yükle
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/heroes'), $imageName);
            $hero->image = 'uploads/heroes/' . $imageName;
        }
        
        $hero->save();
        
        return redirect()->route('back.pages.home-heroes.index')->with('success', 'Hero başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hero = HomeHero::findOrFail($id);
        
        // Resmi sil
        if ($hero->image && File::exists(public_path($hero->image))) {
            File::delete(public_path($hero->image));
        }
        
        $hero->delete();
        
        return redirect()->route('back.pages.home-heroes.index')->with('success', 'Hero başarıyla silindi.');
    }
    
    /**
     * Toggle the status of the specified hero.
     */
    public function toggleStatus($id)
    {
        $hero = HomeHero::findOrFail($id);
        $hero->status = !$hero->status;
        $hero->save();
        
        return redirect()->route('back.pages.home-heroes.index')->with('success', 'Hero durumu başarıyla değiştirildi.');
    }
}
