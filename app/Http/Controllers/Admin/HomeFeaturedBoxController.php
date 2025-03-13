<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeFeaturedBox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeFeaturedBoxController extends Controller
{
    private const MAX_BOXES = 4;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $homeFeaturedBoxes = HomeFeaturedBox::orderBy('order')->get();
        $canCreate = $homeFeaturedBoxes->count() < self::MAX_BOXES;
        return view('back.admin.home-featured-boxes.index', compact('homeFeaturedBoxes', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $count = HomeFeaturedBox::count();
        
        if ($count >= self::MAX_BOXES) {
            return redirect()->route('back.pages.home-featured-boxes.index')
                ->with('error', 'Maksimum ' . self::MAX_BOXES . ' ədəd featured box əlavə edilə bilər.');
        }
        
        return view('back.admin.home-featured-boxes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $count = HomeFeaturedBox::count();
        
        if ($count >= self::MAX_BOXES) {
            return redirect()->route('back.pages.home-featured-boxes.index')
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
            $image->move(public_path('uploads/home-featured-boxes'), $imageName);
            $data['image'] = 'uploads/home-featured-boxes/' . $imageName;
        }

        HomeFeaturedBox::create($data);

        return redirect()->route('back.pages.home-featured-boxes.index')->with('success', 'Featured Box uğurla əlavə edildi.');
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
        $homeFeaturedBox = HomeFeaturedBox::findOrFail($id);
        return view('back.admin.home-featured-boxes.edit', compact('homeFeaturedBox'));
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

        $homeFeaturedBox = HomeFeaturedBox::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Resim yükleme işlemi
        if ($request->hasFile('image')) {
            // Eski resmi silme
            if ($homeFeaturedBox->image && File::exists(public_path($homeFeaturedBox->image))) {
                File::delete(public_path($homeFeaturedBox->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/home-featured-boxes'), $imageName);
            $data['image'] = 'uploads/home-featured-boxes/' . $imageName;
        }

        $homeFeaturedBox->update($data);

        return redirect()->route('back.pages.home-featured-boxes.index')->with('success', 'Featured Box uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $homeFeaturedBox = HomeFeaturedBox::findOrFail($id);
        
        // Resmi silme
        if ($homeFeaturedBox->image && File::exists(public_path($homeFeaturedBox->image))) {
            File::delete(public_path($homeFeaturedBox->image));
        }
        
        $homeFeaturedBox->delete();

        return redirect()->route('back.pages.home-featured-boxes.index')->with('success', 'Featured Box uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $homeFeaturedBox = HomeFeaturedBox::findOrFail($id);
        $homeFeaturedBox->status = !$homeFeaturedBox->status;
        $homeFeaturedBox->save();

        return redirect()->route('back.pages.home-featured-boxes.index')->with('success', 'Featured Box statusu uğurla dəyişdirildi.');
    }
}
