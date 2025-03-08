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
 
    public function index()
    {
        $images = ProductImage::with(['product', 'color'])->get();
        return view('back.admin.product_images.index', compact('images'));
    }

    
    public function create()
    {
        $products = Product::where('status', 1)->get();
        $colors = ProductColor::where('status', 1)->get();
        return view('back.admin.product_images.create', compact('products', 'colors'));
    }

 
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
        
       
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('uploads/product_images'), $imageName);
            $image->image_path = 'uploads/product_images/' . $imageName;
        }
        
        $image->save();
        
        
        if ($image->is_main) {
            ProductImage::where('product_id', $request->product_id)
                ->where('id', '!=', $image->id)
                ->update(['is_main' => 0]);
        }
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Ürün resmi başarıyla eklendi.');
    }

    
    public function show(string $id)
    {
        $image = ProductImage::with(['product', 'color'])->findOrFail($id);
        return view('back.admin.product_images.show', compact('image'));
    }

    
    public function edit(string $id)
    {
        $image = ProductImage::findOrFail($id);
        $products = Product::where('status', 1)->get();
        $colors = ProductColor::where('status', 1)->get();
        return view('back.admin.product_images.edit', compact('image', 'products', 'colors'));
    }

    
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
        
       
        if ($request->hasFile('image')) {
           
            if ($image->image_path && File::exists(public_path($image->image_path))) {
                File::delete(public_path($image->image_path));
            }
            
            $imageFile = $request->file('image');
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('uploads/product_images'), $imageName);
            $image->image_path = 'uploads/product_images/' . $imageName;
        }
        
        $image->save();
        
       
        if ($image->is_main) {
            ProductImage::where('product_id', $request->product_id)
                ->where('id', '!=', $image->id)
                ->update(['is_main' => 0]);
        }
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Ürün resmi başarıyla güncellendi.');
    }

   
    public function destroy(string $id)
    {
        $image = ProductImage::findOrFail($id);
        
      
        if ($image->image_path && File::exists(public_path($image->image_path))) {
            File::delete(public_path($image->image_path));
        }
        
        $image->delete();
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Ürün resmi başarıyla silindi.');
    }
    
  
    public function toggleStatus($id)
    {
        $image = ProductImage::findOrFail($id);
        $image->status = !$image->status;
        $image->save();
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Resim durumu başarıyla değiştirildi.');
    }
    
   
    public function setAsMain($id)
    {
        $image = ProductImage::findOrFail($id);
        
     
        ProductImage::where('product_id', $image->product_id)
            ->update(['is_main' => 0]);
            
       
        $image->is_main = 1;
        $image->save();
        
        return redirect()->route('back.pages.product_images.index')->with('success', 'Ana resim başarıyla değiştirildi.');
    }
    
  
    public function getColorsByProduct($productId)
    {
        $colors = ProductColor::where('product_id', $productId)
            ->where('status', 1)
            ->get();
            
        return response()->json($colors);
    }
}
