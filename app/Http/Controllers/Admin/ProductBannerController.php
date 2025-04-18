<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductBanner;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;


class ProductBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Artisan::call('migrate');
        $banners = ProductBanner::orderBy('id', 'desc')->get();
        return view('back.admin.product-banner.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.product-banner.create');
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
            $uploadPath = public_path('uploads/product-banner');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $imageName);
            $data['image'] = 'uploads/product-banner/' . $imageName;
        }

        ProductBanner::create($data);

        return redirect()->route('back.pages.product-banner.index')->with('success', 'Product banner uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('back.pages.product-banner.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = ProductBanner::findOrFail($id);
        return view('back.admin.product-banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = ProductBanner::findOrFail($id);
        
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
            $uploadPath = public_path('uploads/product-banner');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $imageName);
            $data['image'] = 'uploads/product-banner/' . $imageName;
        }

        $banner->update($data);

        return redirect()->route('back.pages.product-banner.index')->with('success', 'Product banner uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = ProductBanner::findOrFail($id);
        
        // Görseli sil
        if ($banner->image && File::exists(public_path($banner->image))) {
            File::delete(public_path($banner->image));
        }
        
        $banner->delete();

        return redirect()->route('back.pages.product-banner.index')->with('success', 'Product banner uğurla silindi.');
    }
    
    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $banner = ProductBanner::findOrFail($id);
        $banner->status = !$banner->status;
        $banner->save();

        return redirect()->route('back.pages.product-banner.index')->with('success', 'Product banner statusu uğurla dəyişdirildi.');
    }
}
