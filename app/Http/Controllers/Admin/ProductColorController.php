<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = ProductColor::with('product')->get();
        return view('back.admin.product_colors.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', 1)->get();
        return view('back.admin.product_colors.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_name_az' => 'required|string',
            'color_name_en' => 'required|string',
            'color_name_ru' => 'required|string',
            'color_code' => 'nullable|string',
            'color_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $color = new ProductColor();
        $color->product_id = $request->product_id;
        $color->color_name_az = $request->color_name_az;
        $color->color_name_en = $request->color_name_en;
        $color->color_name_ru = $request->color_name_ru;
        $color->color_code = $request->color_code;
        $color->status = $request->has('status') ? 1 : 0;
        $color->sort_order = $request->sort_order ?? 0;
        
        // Renk görseli yükleme
        if ($request->hasFile('color_image')) {
            $image = $request->file('color_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/product_colors'), $imageName);
            $color->color_image = 'uploads/product_colors/' . $imageName;
        }
        
        $color->save();
        
        return redirect()->route('back.pages.product_colors.index')->with('success', 'Ürün rengi başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $color = ProductColor::with('product')->findOrFail($id);
        return view('back.admin.product_colors.show', compact('color'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $color = ProductColor::findOrFail($id);
        $products = Product::where('status', 1)->get();
        return view('back.admin.product_colors.edit', compact('color', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_name_az' => 'required|string',
            'color_name_en' => 'required|string',
            'color_name_ru' => 'required|string',
            'color_code' => 'nullable|string',
            'color_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $color = ProductColor::findOrFail($id);
        $color->product_id = $request->product_id;
        $color->color_name_az = $request->color_name_az;
        $color->color_name_en = $request->color_name_en;
        $color->color_name_ru = $request->color_name_ru;
        $color->color_code = $request->color_code;
        $color->status = $request->has('status') ? 1 : 0;
        $color->sort_order = $request->sort_order ?? 0;
        
        // Renk görseli yükleme
        if ($request->hasFile('color_image')) {
            // Eski görseli sil
            if ($color->color_image && File::exists(public_path($color->color_image))) {
                File::delete(public_path($color->color_image));
            }
            
            $image = $request->file('color_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/product_colors'), $imageName);
            $color->color_image = 'uploads/product_colors/' . $imageName;
        }
        
        $color->save();
        
        return redirect()->route('back.pages.product_colors.index')->with('success', 'Ürün rengi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $color = ProductColor::findOrFail($id);
        
        // Renk görselini sil
        if ($color->color_image && File::exists(public_path($color->color_image))) {
            File::delete(public_path($color->color_image));
        }
        
        // Rengi sil (ilişkili kayıtlar cascade ile silinecek)
        $color->delete();
        
        return redirect()->route('back.pages.product_colors.index')->with('success', 'Ürün rengi başarıyla silindi.');
    }
    
    /**
     * Toggle color status.
     */
    public function toggleStatus($id)
    {
        $color = ProductColor::findOrFail($id);
        $color->status = !$color->status;
        $color->save();
        
        return redirect()->route('back.pages.product_colors.index')->with('success', 'Renk durumu başarıyla değiştirildi.');
    }
}
