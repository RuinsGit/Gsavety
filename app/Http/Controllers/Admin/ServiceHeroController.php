<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ServiceHeroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private const MAX_HEROES = 1;
    public function index()
    {
        $serviceHeroes = ServiceHero::orderBy('order')->get();
        $canCreate = $serviceHeroes->count() < self::MAX_HEROES;
        return view('back.admin.service-heroes.index', compact('serviceHeroes', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $count = ServiceHero::count();
        
        if ($count >= self::MAX_HEROES) {
            return redirect()->route('back.pages.service-heroes.index')
                ->with('error', 'Maksimum ' . self::MAX_HEROES . ' ədəd servis hero əlavə edilə bilər.');
        }
        return view('back.admin.service-heroes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_alt_az' => 'nullable|string|max:255',
            'image_alt_en' => 'nullable|string|max:255',
            'image_alt_ru' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Resim yükleme işlemi
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/service-heroes'), $imageName);
            $data['image'] = 'uploads/service-heroes/' . $imageName;
        }

        ServiceHero::create($data);

        return redirect()->route('back.pages.service-heroes.index')->with('success', 'Məlumatlar uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $serviceHero = ServiceHero::findOrFail($id);
        return view('back.admin.service-heroes.show', compact('serviceHero'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $serviceHero = ServiceHero::findOrFail($id);
        return view('back.admin.service-heroes.edit', compact('serviceHero'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_alt_az' => 'nullable|string|max:255',
            'image_alt_en' => 'nullable|string|max:255',
            'image_alt_ru' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $serviceHero = ServiceHero::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Resim yükleme işlemi
        if ($request->hasFile('image')) {
            // Eski resmi silme
            if ($serviceHero->image && File::exists(public_path($serviceHero->image))) {
                File::delete(public_path($serviceHero->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/service-heroes'), $imageName);
            $data['image'] = 'uploads/service-heroes/' . $imageName;
        }

        $serviceHero->update($data);

        return redirect()->route('back.pages.service-heroes.index')->with('success', 'Məlumatlar uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $serviceHero = ServiceHero::findOrFail($id);
        
        // Resmi silme
        if ($serviceHero->image && File::exists(public_path($serviceHero->image))) {
            File::delete(public_path($serviceHero->image));
        }
        
        $serviceHero->delete();

        return redirect()->route('back.pages.service-heroes.index')->with('success', 'Məlumatlar uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $serviceHero = ServiceHero::findOrFail($id);
        $serviceHero->status = !$serviceHero->status;
        $serviceHero->save();

        return redirect()->route('back.pages.service-heroes.index')->with('success', 'Status uğurla dəyişdirildi.');
    }
} 