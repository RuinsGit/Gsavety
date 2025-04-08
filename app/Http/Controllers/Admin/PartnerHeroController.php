<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PartnerHeroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partnerHeroes = PartnerHero::orderBy('order')->get();
        return view('back.admin.partner-heroes.index', compact('partnerHeroes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(PartnerHero::count() >= 1){
            return redirect()->route('back.pages.partner-heroes.index')->with('error', 'Partner Hero sayı 1-dən çox ola bilməz.');
        }
        return view('back.admin.partner-heroes.create');
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
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Upload Image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'partner_hero_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/partner-heroes'), $imageName);
            $data['image'] = 'uploads/partner-heroes/' . $imageName;
        }

        PartnerHero::create($data);

        return redirect()->route('back.pages.partner-heroes.index')->with('success', 'Məlumatlar uğurla əlavə edildi.');
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
        $partnerHero = PartnerHero::findOrFail($id);
        return view('back.admin.partner-heroes.edit', compact('partnerHero'));
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

        $partnerHero = PartnerHero::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Upload Image
        if ($request->hasFile('image')) {
            // Delete old image
            if ($partnerHero->image && File::exists(public_path($partnerHero->image))) {
                File::delete(public_path($partnerHero->image));
            }

            $image = $request->file('image');
            $imageName = 'partner_hero_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/partner-heroes'), $imageName);
            $data['image'] = 'uploads/partner-heroes/' . $imageName;
        }

        $partnerHero->update($data);

        return redirect()->route('back.pages.partner-heroes.index')->with('success', 'Partner Hero uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $partnerHero = PartnerHero::findOrFail($id);

        // Delete image
        if ($partnerHero->image && File::exists(public_path($partnerHero->image))) {
            File::delete(public_path($partnerHero->image));
        }

        $partnerHero->delete();

        return redirect()->route('back.pages.partner-heroes.index')->with('success', 'Partner Hero uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $partnerHero = PartnerHero::findOrFail($id);
        $partnerHero->status = !$partnerHero->status;
        $partnerHero->save();

        return redirect()->route('back.pages.partner-heroes.index')->with('success', 'Partner Hero statusu uğurla dəyişdirildi.');
    }
}
