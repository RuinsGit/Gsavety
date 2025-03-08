<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = ProductSize::with('product')->get();
        return view('back.admin.product_sizes.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', 1)->get();
        return view('back.admin.product_sizes.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_name_az' => 'required|string',
            'size_name_en' => 'required|string',
            'size_name_ru' => 'required|string',
            'size_value' => 'required|string',
        ]);

        $size = new ProductSize();
        $size->product_id = $request->product_id;
        $size->size_name_az = $request->size_name_az;
        $size->size_name_en = $request->size_name_en;
        $size->size_name_ru = $request->size_name_ru;
        $size->size_value = $request->size_value;
        $size->status = $request->has('status') ? 1 : 0;
        $size->sort_order = $request->sort_order ?? 0;
        
        $size->save();
        
        return redirect()->route('back.pages.product_sizes.index')->with('success', 'Ürün boyutu başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $size = ProductSize::with('product')->findOrFail($id);
        return view('back.admin.product_sizes.show', compact('size'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $size = ProductSize::findOrFail($id);
        $products = Product::where('status', 1)->get();
        return view('back.admin.product_sizes.edit', compact('size', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_name_az' => 'required|string',
            'size_name_en' => 'required|string',
            'size_name_ru' => 'required|string',
            'size_value' => 'required|string',
        ]);

        $size = ProductSize::findOrFail($id);
        $size->product_id = $request->product_id;
        $size->size_name_az = $request->size_name_az;
        $size->size_name_en = $request->size_name_en;
        $size->size_name_ru = $request->size_name_ru;
        $size->size_value = $request->size_value;
        $size->status = $request->has('status') ? 1 : 0;
        $size->sort_order = $request->sort_order ?? 0;
        
        $size->save();
        
        return redirect()->route('back.pages.product_sizes.index')->with('success', 'Ürün boyutu başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $size = ProductSize::findOrFail($id);
        
        // Boyutu sil (ilişkili kayıtlar cascade ile silinecek)
        $size->delete();
        
        return redirect()->route('back.pages.product_sizes.index')->with('success', 'Ürün boyutu başarıyla silindi.');
    }
    
    /**
     * Toggle size status.
     */
    public function toggleStatus($id)
    {
        $size = ProductSize::findOrFail($id);
        $size->status = !$size->status;
        $size->save();
        
        return redirect()->route('back.pages.product_sizes.index')->with('success', 'Boyut durumu başarıyla değiştirildi.');
    }
}
