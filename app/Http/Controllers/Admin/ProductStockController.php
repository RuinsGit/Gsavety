<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
   
    public function index()
    {
        $stocks = ProductStock::with(['product', 'color', 'size'])->get();
        return view('back.admin.product_stocks.index', compact('stocks'));
    }

    
    public function create()
    {
        $products = Product::where('status', 1)->get();
        $colors = ProductColor::where('status', 1)->get();
        $sizes = ProductSize::where('status', 1)->get();
        return view('back.admin.product_stocks.create', compact('products', 'colors', 'sizes'));
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_color_id' => 'nullable|exists:product_colors,id',
            'product_size_id' => 'nullable|exists:product_sizes,id',
            'quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
        ]);

      
        $existingStock = ProductStock::where('product_id', $request->product_id)
            ->where('product_color_id', $request->product_color_id)
            ->where('product_size_id', $request->product_size_id)
            ->first();
            
        if ($existingStock) {
            return redirect()->back()->with('error', 'Bu ürün, renk ve boyut kombinasyonu için zaten stok kaydı mevcut.');
        }

        $stock = new ProductStock();
        $stock->product_id = $request->product_id;
        $stock->product_color_id = $request->product_color_id;
        $stock->product_size_id = $request->product_size_id;
        $stock->quantity = $request->quantity;
        $stock->sku = $request->sku;
        $stock->price = $request->price;
        $stock->discount_price = $request->discount_price;
        $stock->status = $request->has('status') ? 1 : 0;
        
        $stock->save();
        
        return redirect()->route('back.pages.product_stocks.index')->with('success', 'Ürün stoğu başarıyla eklendi.');
    }

  
    public function show(string $id)
    {
        $stock = ProductStock::with(['product', 'color', 'size'])->findOrFail($id);
        return view('back.admin.product_stocks.show', compact('stock'));
    }

   
    public function edit(string $id)
    {
        $stock = ProductStock::findOrFail($id);
        $products = Product::where('status', 1)->get();
        $colors = ProductColor::where('status', 1)->get();
        $sizes = ProductSize::where('status', 1)->get();
        return view('back.admin.product_stocks.edit', compact('stock', 'products', 'colors', 'sizes'));
    }

   
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_color_id' => 'nullable|exists:product_colors,id',
            'product_size_id' => 'nullable|exists:product_sizes,id',
            'quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
        ]);

        $stock = ProductStock::findOrFail($id);
        
        
        $existingStock = ProductStock::where('product_id', $request->product_id)
            ->where('product_color_id', $request->product_color_id)
            ->where('product_size_id', $request->product_size_id)
            ->where('id', '!=', $id)
            ->first();
            
        if ($existingStock) {
            return redirect()->back()->with('error', 'Bu ürün, renk ve boyut kombinasyonu için zaten stok kaydı mevcut.');
        }

        $stock->product_id = $request->product_id;
        $stock->product_color_id = $request->product_color_id;
        $stock->product_size_id = $request->product_size_id;
        $stock->quantity = $request->quantity;
        $stock->sku = $request->sku;
        $stock->price = $request->price;
        $stock->discount_price = $request->discount_price;
        $stock->status = $request->has('status') ? 1 : 0;
        
        $stock->save();
        
        return redirect()->route('back.pages.product_stocks.index')->with('success', 'Ürün stoğu başarıyla güncellendi.');
    }

  
    public function destroy(string $id)
    {
        $stock = ProductStock::findOrFail($id);
        $stock->delete();
        
        return redirect()->route('back.pages.product_stocks.index')->with('success', 'Ürün stoğu başarıyla silindi.');
    }
    
   
    public function toggleStatus($id)
    {
        $stock = ProductStock::findOrFail($id);
        $stock->status = !$stock->status;
        $stock->save();
        
        return redirect()->route('back.pages.product_stocks.index')->with('success', 'Stok durumu başarıyla değiştirildi.');
    }
    
    
    public function getColorsByProduct($productId)
    {
        $colors = ProductColor::where('product_id', $productId)
            ->where('status', 1)
            ->get();
            
        return response()->json($colors);
    }
    
   
    public function getSizesByProduct($productId)
    {
        $sizes = ProductSize::where('product_id', $productId)
            ->where('status', 1)
            ->get();
            
        return response()->json($sizes);
    }
}
