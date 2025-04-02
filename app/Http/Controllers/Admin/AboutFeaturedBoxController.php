<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutFeaturedBox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AboutFeaturedBoxController extends Controller
{
    private const MAX_BOXES = 4;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aboutFeaturedBoxes = AboutFeaturedBox::orderBy('order')->get();
        $canCreate = $aboutFeaturedBoxes->count() < self::MAX_BOXES;
        return view('back.admin.about-featured-boxes.index', compact('aboutFeaturedBoxes', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $count = AboutFeaturedBox::count();
        
        if ($count >= self::MAX_BOXES) {
            return redirect()->route('back.pages.about-featured-boxes.index')
                ->with('error', 'Maksimum ' . self::MAX_BOXES . ' ədəd featured box əlavə edilə bilər.');
        }
        
        return view('back.admin.about-featured-boxes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $count = AboutFeaturedBox::count();
        
        if ($count >= self::MAX_BOXES) {
            return redirect()->route('back.pages.about-featured-boxes.index')
                ->with('error', 'Maksimum ' . self::MAX_BOXES . ' ədəd featured box əlavə edilə bilər.');
        }
        
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Resim yükleme işlemi
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about-featured-boxes'), $imageName);
            $data['image'] = 'uploads/about-featured-boxes/' . $imageName;
        }

        AboutFeaturedBox::create($data);

        return redirect()->route('back.pages.about-featured-boxes.index')->with('success', 'Məlumatlar uğurla əlavə edildi.');
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
        $aboutFeaturedBox = AboutFeaturedBox::findOrFail($id);
        return view('back.admin.about-featured-boxes.edit', compact('aboutFeaturedBox'));
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

        $aboutFeaturedBox = AboutFeaturedBox::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Resim yükleme işlemi
        if ($request->hasFile('image')) {
            // Eski resmi silme
            if ($aboutFeaturedBox->image && File::exists(public_path($aboutFeaturedBox->image))) {
                File::delete(public_path($aboutFeaturedBox->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about-featured-boxes'), $imageName);
            $data['image'] = 'uploads/about-featured-boxes/' . $imageName;
        }

        $aboutFeaturedBox->update($data);

        return redirect()->route('back.pages.about-featured-boxes.index')->with('success', 'Məlumatlar uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aboutFeaturedBox = AboutFeaturedBox::findOrFail($id);
        
        // Resmi silme
        if ($aboutFeaturedBox->image && File::exists(public_path($aboutFeaturedBox->image))) {
            File::delete(public_path($aboutFeaturedBox->image));
        }
        
        $aboutFeaturedBox->delete();

        return redirect()->route('back.pages.about-featured-boxes.index')->with('success', 'Məlumatlar uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $aboutFeaturedBox = AboutFeaturedBox::findOrFail($id);
        $aboutFeaturedBox->status = !$aboutFeaturedBox->status;
        $aboutFeaturedBox->save();

        return redirect()->route('back.pages.about-featured-boxes.index')->with('success', 'Status uğurla dəyişdirildi.');
    }
}
