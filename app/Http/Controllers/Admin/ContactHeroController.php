<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class ContactHeroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private const MAX_HEROES = 1;
    
    public function index()
    {
        Artisan::call('migrate');
        $contactHeroes = ContactHero::orderBy('order')->get();
        $canCreate = $contactHeroes->count() < self::MAX_HEROES;
        return view('back.admin.contact-heroes.index', compact('contactHeroes', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $count = ContactHero::count();
        
        if ($count >= self::MAX_HEROES) {
            return redirect()->route('back.pages.contact-heroes.index')
                ->with('error', 'Maksimum ' . self::MAX_HEROES . ' ədəd əlaqə hero əlavə edilə bilər.');
        }
        
        return view('back.admin.contact-heroes.create');
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
            $image->move(public_path('uploads/contact-heroes'), $imageName);
            $data['image'] = 'uploads/contact-heroes/' . $imageName;
        }

        ContactHero::create($data);

        return redirect()->route('back.pages.contact-heroes.index')->with('success', 'Məlumatlar uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contactHero = ContactHero::findOrFail($id);
        return view('back.admin.contact-heroes.show', compact('contactHero'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contactHero = ContactHero::findOrFail($id);
        return view('back.admin.contact-heroes.edit', compact('contactHero'));
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

        $contactHero = ContactHero::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Resim yükleme işlemi
        if ($request->hasFile('image')) {
            // Eski resmi silme
            if ($contactHero->image && File::exists(public_path($contactHero->image))) {
                File::delete(public_path($contactHero->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/contact-heroes'), $imageName);
            $data['image'] = 'uploads/contact-heroes/' . $imageName;
        }

        $contactHero->update($data);

        return redirect()->route('back.pages.contact-heroes.index')->with('success', 'Məlumatlar uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contactHero = ContactHero::findOrFail($id);
        
        // Resmi silme
        if ($contactHero->image && File::exists(public_path($contactHero->image))) {
            File::delete(public_path($contactHero->image));
        }
        
        $contactHero->delete();

        return redirect()->route('back.pages.contact-heroes.index')->with('success', 'Məlumatlar uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $contactHero = ContactHero::findOrFail($id);
        $contactHero->status = !$contactHero->status;
        $contactHero->save();

        return redirect()->route('back.pages.contact-heroes.index')->with('success', 'Status uğurla dəyişdirildi.');
    }
} 