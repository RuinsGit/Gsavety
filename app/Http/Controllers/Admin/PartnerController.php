<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partners = Partner::orderBy('order')->get();
        return view('back.admin.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.partners.create');
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
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Upload Image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'partner_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/partners'), $imageName);
            $data['image'] = 'uploads/partners/' . $imageName;
        }

        Partner::create($data);

        return redirect()->route('back.pages.partners.index')->with('success', 'Məlumatlar uğurla əlavə edildi.');
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
        $partner = Partner::findOrFail($id);
        return view('back.admin.partners.edit', compact('partner'));
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
            'order' => 'nullable|integer',
        ]);

        $partner = Partner::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Upload Image
        if ($request->hasFile('image')) {
            // Delete old image
            if ($partner->image && File::exists(public_path($partner->image))) {
                File::delete(public_path($partner->image));
            }

            $image = $request->file('image');
            $imageName = 'partner_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/partners'), $imageName);
            $data['image'] = 'uploads/partners/' . $imageName;
        }

        $partner->update($data);

        return redirect()->route('back.pages.partners.index')->with('success', 'Partner uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $partner = Partner::findOrFail($id);

        // Delete image
        if ($partner->image && File::exists(public_path($partner->image))) {
            File::delete(public_path($partner->image));
        }

        $partner->delete();

        return redirect()->route('back.pages.partners.index')->with('success', 'Partner uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $partner = Partner::findOrFail($id);
        $partner->status = !$partner->status;
        $partner->save();

        return redirect()->route('back.pages.partners.index')->with('success', 'Partner statusu uğurla dəyişdirildi.');
    }
}
