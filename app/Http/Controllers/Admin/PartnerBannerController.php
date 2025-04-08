<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PartnerBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partnerBanners = PartnerBanner::orderBy('order')->get();
        return view('back.admin.partner-banners.index', compact('partnerBanners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.partner-banners.create');
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
            $imageName = 'partner_banner_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/partner-banners'), $imageName);
            $data['image'] = 'uploads/partner-banners/' . $imageName;
        }

        PartnerBanner::create($data);

        return redirect()->route('back.pages.partner-banners.index')->with('success', 'Məlumatlar uğurla əlavə edildi.');
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
        $partnerBanner = PartnerBanner::findOrFail($id);
        return view('back.admin.partner-banners.edit', compact('partnerBanner'));
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

        $partnerBanner = PartnerBanner::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Upload Image
        if ($request->hasFile('image')) {
            // Delete old image
            if ($partnerBanner->image && File::exists(public_path($partnerBanner->image))) {
                File::delete(public_path($partnerBanner->image));
            }

            $image = $request->file('image');
            $imageName = 'partner_banner_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/partner-banners'), $imageName);
            $data['image'] = 'uploads/partner-banners/' . $imageName;
        }

        $partnerBanner->update($data);

        return redirect()->route('back.pages.partner-banners.index')->with('success', 'Partner-Banner uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $partnerBanner = PartnerBanner::findOrFail($id);

        // Delete image
        if ($partnerBanner->image && File::exists(public_path($partnerBanner->image))) {
            File::delete(public_path($partnerBanner->image));
        }

        $partnerBanner->delete();

        return redirect()->route('back.pages.partner-banners.index')->with('success', 'Partner-Banner uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $partnerBanner = PartnerBanner::findOrFail($id);
        $partnerBanner->status = !$partnerBanner->status;
        $partnerBanner->save();

        return redirect()->route('back.pages.partner-banners.index')->with('success', 'Partner-Banner statusu uğurla dəyişdirildi.');
    }
}
