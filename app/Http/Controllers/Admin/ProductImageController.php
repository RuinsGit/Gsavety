<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = ProductImage::with(['product', 'color'])->get();
        return view('back.admin.product_images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', 1)->get();
        $colors = ProductColor::where('status', 1)->get();
        return view('back.admin.product_images.create', compact('products', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_color_id' => 'nullable|exists:product_colors,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'alt_text_az' => 'nullable|string',
            'alt_text_en' => 'nullable|string',
            'alt_text_ru' => 'nullable|string',
        ]);

        $image = new ProductImage();
        $image->product_id = $request->product_id;
        $image->product_color_id = $request->product_color_id;
        $image->alt_text_az = $request->alt_text_az;
        $image->alt_text_en = $request->alt_text_en;
        $image->alt_text_ru = $request->alt_text_ru;
        $image->is_main = $request->has('is_main') ? 1 : 0;
        $image->status = $request->has('status') ? 1 : 0;
        $image->sort_order = $request->sort_order ?? 0;
        
        // Resim yükleme
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('uploads/product_images'), $imageName);
            $image->image_path = 'uploads/product_images/' . $imageName;
        }
        
        $image->save();
        
        // Eğer bu resim ana resim olarak işaretlendiyse, diğer resimlerin ana resim işaretini kaldır
        if ($image->is_main) {
            ProductImage::where('product_id', $request->product_id)
                ->where('id', '!=', $image->id)
                ->update(['is_main' => 0]);
        }
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Ürün resmi başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $image = ProductImage::with(['product', 'color'])->findOrFail($id);
        return view('back.admin.product_images.show', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $image = ProductImage::findOrFail($id);
        $products = Product::where('status', 1)->get();
        $colors = ProductColor::where('status', 1)->get();
        return view('back.admin.product_images.edit', compact('image', 'products', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_color_id' => 'nullable|exists:product_colors,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'alt_text_az' => 'nullable|string',
            'alt_text_en' => 'nullable|string',
            'alt_text_ru' => 'nullable|string',
        ]);

        $image = ProductImage::findOrFail($id);
        $image->product_id = $request->product_id;
        $image->product_color_id = $request->product_color_id;
        $image->alt_text_az = $request->alt_text_az;
        $image->alt_text_en = $request->alt_text_en;
        $image->alt_text_ru = $request->alt_text_ru;
        $image->is_main = $request->has('is_main') ? 1 : 0;
        $image->status = $request->has('status') ? 1 : 0;
        $image->sort_order = $request->sort_order ?? 0;
        
        // Resim yükleme
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($image->image_path && File::exists(public_path($image->image_path))) {
                File::delete(public_path($image->image_path));
            }
            
            $imageFile = $request->file('image');
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('uploads/product_images'), $imageName);
            $image->image_path = 'uploads/product_images/' . $imageName;
        }
        
        $image->save();
        
        // Eğer bu resim ana resim olarak işaretlendiyse, diğer resimlerin ana resim işaretini kaldır
        if ($image->is_main) {
            ProductImage::where('product_id', $request->product_id)
                ->where('id', '!=', $image->id)
                ->update(['is_main' => 0]);
        }
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Ürün resmi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = ProductImage::findOrFail($id);
        
        // Resmi sil
        if ($image->image_path && File::exists(public_path($image->image_path))) {
            File::delete(public_path($image->image_path));
        }
        
        $image->delete();
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Ürün resmi başarıyla silindi.');
    }
    
    /**
     * Toggle image status.
     */
    public function toggleStatus($id)
    {
        $image = ProductImage::findOrFail($id);
        $image->status = !$image->status;
        $image->save();
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Resim durumu başarıyla değiştirildi.');
    }
    
    /**
     * Set as main image.
     */
    public function setAsMain($id)
    {
        $image = ProductImage::findOrFail($id);
        
        // Önce tüm resimlerin ana resim işaretini kaldır
        ProductImage::where('product_id', $image->product_id)
            ->update(['is_main' => 0]);
            
        // Bu resmi ana resim olarak işaretle
        $image->is_main = 1;
        $image->save();
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Ana resim başarıyla değiştirildi.');
    }
    
    /**
     * Get colors by product.
     */
    public function getColorsByProduct($productId)
    {
        $colors = ProductColor::where('product_id', $productId)
            ->where('status', 1)
            ->get();
            
        return response()->json($colors);
    }
}
