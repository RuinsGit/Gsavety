<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogBanner;
use Illuminate\Support\Facades\File;


class BlogBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = BlogBanner::orderBy('id', 'desc')->get();
        return view('back.admin.blog-banner.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.blog-banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasyon
        $validated = $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Uploads klasörünü kontrol et, yoksa oluştur
            $uploadPath = public_path('uploads/blog-banner');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $imageName);
            $data['image'] = 'uploads/blog-banner/' . $imageName;
        }

        BlogBanner::create($data);

        return redirect()->route('back.pages.blog-banner.index')->with('success', 'Blog banner uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('back.pages.blog-banner.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = BlogBanner::findOrFail($id);
        return view('back.admin.blog-banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = BlogBanner::findOrFail($id);
        
        // Validasyon
        $validated = $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($banner->image && File::exists(public_path($banner->image))) {
                File::delete(public_path($banner->image));
            }
            
            // Uploads klasörünü kontrol et, yoksa oluştur
            $uploadPath = public_path('uploads/blog-banner');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $imageName);
            $data['image'] = 'uploads/blog-banner/' . $imageName;
        }

        $banner->update($data);

        return redirect()->route('back.pages.blog-banner.index')->with('success', 'Blog banner uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = BlogBanner::findOrFail($id);
        
        // Görseli sil
        if ($banner->image && File::exists(public_path($banner->image))) {
            File::delete(public_path($banner->image));
        }
        
        $banner->delete();

        return redirect()->route('back.pages.blog-banner.index')->with('success', 'Blog banner uğurla silindi.');
    }
    
    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $banner = BlogBanner::findOrFail($id);
        $banner->status = !$banner->status;
        $banner->save();

        return redirect()->route('back.pages.blog-banner.index')->with('success', 'Blog banner statusu uğurla dəyişdirildi.');
    }
}
