<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductColorController extends Controller
{
    
    public function index()
    {
        $colors = ProductColor::with('product')->get();
        return view('back.admin.product_colors.index', compact('colors'));
    }

    
    public function create()
    {
        $products = Product::where('status', 1)->get();
        return view('back.admin.product_colors.create', compact('products'));
    }

   
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
        
       
        if ($request->hasFile('color_image')) {
            $image = $request->file('color_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/product_colors'), $imageName);
            $color->color_image = 'uploads/product_colors/' . $imageName;
        }
        
        $color->save();
        
        return redirect()->route('back.pages.product_colors.index')->with('success', 'Məlumatlar uğurla əlavə edildi.');
    }

    
    public function show(string $id)
    {
        $color = ProductColor::with('product')->findOrFail($id);
        return view('back.admin.product_colors.show', compact('color'));
    }

    
    public function edit(string $id)
    {
        $color = ProductColor::findOrFail($id);
        $products = Product::where('status', 1)->get();
        return view('back.admin.product_colors.edit', compact('color', 'products'));
    }

   
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
        
       
        if ($request->hasFile('color_image')) {
            
            if ($color->color_image && File::exists(public_path($color->color_image))) {
                File::delete(public_path($color->color_image));
            }
            
            $image = $request->file('color_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/product_colors'), $imageName);
            $color->color_image = 'uploads/product_colors/' . $imageName;
        }
        
        $color->save();
        
        return redirect()->route('back.pages.product_colors.index')->with('success', 'Məlumatlar uğurla yeniləndi.');
    }

   
    public function destroy(string $id)
    {
        $color = ProductColor::findOrFail($id);
        
       
        if ($color->color_image && File::exists(public_path($color->color_image))) {
            File::delete(public_path($color->color_image));
        }
        
       
        $color->delete();
        
        return redirect()->route('back.pages.product_colors.index')->with('success', 'Məlumatlar uğurla silindi.');
    }
    
   
    public function toggleStatus($id)
    {
        $color = ProductColor::findOrFail($id);
        $color->status = !$color->status;
        $color->save();
        
        return redirect()->route('back.pages.product_colors.index')->with('success', 'Status uğurla dəyişdirildi.');
    }
}
