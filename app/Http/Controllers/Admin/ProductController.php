<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductProperty;
use App\Models\ProductSize;
use App\Models\ProductStock;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
   
    public function index()
    {
        $products = Product::all();
        return view('back.admin.products.index', compact('products'));
    }

    
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('back.admin.products.create', compact('categories'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'reference' => 'nullable|string',
            'sku' => 'required|string|unique:products',
            'name_az' => 'required|string',
            'name_en' => 'required|string',
            'name_ru' => 'required|string',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $product = new Product();
        $product->reference = $request->reference;
        $product->sku = $request->sku;
        $product->name_az = $request->name_az;
        $product->name_en = $request->name_en;
        $product->name_ru = $request->name_ru;
        $product->description_az = $request->description_az;
        $product->description_en = $request->description_en;
        $product->description_ru = $request->description_ru;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->is_featured = $request->has('is_featured') ? 1 : 0;
        $product->status = $request->has('status') ? 1 : 0;
        
        
        $product->meta_title_az = $request->meta_title_az ?? $request->name_az;
        $product->meta_title_en = $request->meta_title_en ?? $request->name_en;
        $product->meta_title_ru = $request->meta_title_ru ?? $request->name_ru;
        $product->meta_description_az = $request->meta_description_az ?? $request->description_az;
        $product->meta_description_en = $request->meta_description_en ?? $request->description_en;
        $product->meta_description_ru = $request->meta_description_ru ?? $request->description_ru;
        $product->slug_az = Str::slug($request->name_az);
        $product->slug_en = Str::slug($request->name_en);
        $product->slug_ru = Str::slug($request->name_ru);
        
       
        if ($request->hasFile('main_image')) {
            $image = $request->file('main_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $product->main_image = 'uploads/products/' . $imageName;
        }
        
        $product->save();
        
        // Kategorileri ürüne atama
        if ($request->has('categories') && is_array($request->categories)) {
            $product->categories()->attach($request->categories);
        }
        
        // Ürün özelliklerini kaydetme
        if ($request->has('property_name_az') && is_array($request->property_name_az)) {
            foreach ($request->property_name_az as $key => $name) {
                if (!empty($name)) {
                    $property = new ProductProperty();
                    $property->product_id = $product->id;
                    $property->property_type = 'other'; 
                    $property->property_name_az = $name;
                    $property->property_name_en = $request->property_name_en[$key] ?? '';
                    $property->property_name_ru = $request->property_name_ru[$key] ?? '';
                    $property->property_value_az = $request->property_value_az[$key] ?? '';
                    $property->property_value_en = $request->property_value_en[$key] ?? '';
                    $property->property_value_ru = $request->property_value_ru[$key] ?? '';
                    $property->sort_order = $key;
                    $property->save();
                }
            }
        }
        
        return redirect()->route('back.pages.products.index')->with('success', 'Məlumatlar uğurla əlavə edildi.');
    }

    
    public function show(string $id)
    {
        $product = Product::with([
            'properties',
            'colors',
            'sizes',
            'images',
            'stocks',
            'categories'
        ])->findOrFail($id);
        
        return view('back.admin.products.show', compact('product'));
    }

    
    public function edit(string $id)
    {
        $product = Product::with(['properties', 'categories'])->findOrFail($id);
        $categories = Category::where('status', 1)->get();
        $selectedCategories = $product->categories->pluck('id')->toArray();
        
        return view('back.admin.products.edit', compact('product', 'categories', 'selectedCategories'));
    }

   
    public function update(Request $request, string $id)
    {
        $request->validate([
            'reference' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'name_az' => 'required|string',
            'name_en' => 'required|string',
            'name_ru' => 'required|string',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        $product->reference = $request->reference;
        $product->sku = $request->sku;
        $product->name_az = $request->name_az;
        $product->name_en = $request->name_en;
        $product->name_ru = $request->name_ru;
        $product->description_az = $request->description_az;
        $product->description_en = $request->description_en;
        $product->description_ru = $request->description_ru;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->is_featured = $request->has('is_featured') ? 1 : 0;
        $product->status = $request->has('status') ? 1 : 0;
        
       
        $product->meta_title_az = $request->meta_title_az ?? $request->name_az;
        $product->meta_title_en = $request->meta_title_en ?? $request->name_en;
        $product->meta_title_ru = $request->meta_title_ru ?? $request->name_ru;
        $product->meta_description_az = $request->meta_description_az ?? $request->description_az;
        $product->meta_description_en = $request->meta_description_en ?? $request->description_en;
        $product->meta_description_ru = $request->meta_description_ru ?? $request->description_ru;
        $product->slug_az = Str::slug($request->name_az);
        $product->slug_en = Str::slug($request->name_en);
        $product->slug_ru = Str::slug($request->name_ru);
        
        
        if ($request->hasFile('main_image')) {
           
            if ($product->main_image && File::exists(public_path($product->main_image))) {
                File::delete(public_path($product->main_image));
            }
            
            $image = $request->file('main_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $product->main_image = 'uploads/products/' . $imageName;
        }
        
        $product->save();
        
        // Kategorileri güncelle (önce tüm ilişkileri kaldırıp, yeni kategorileri eklemek)
        $product->categories()->sync($request->categories ?? []);
        
        
        if ($request->has('property_name_az') && is_array($request->property_name_az)) {
          
            ProductProperty::where('product_id', $product->id)->delete();
            
           
            foreach ($request->property_name_az as $key => $name) {
                if (!empty($name)) {
                    $property = new ProductProperty();
                    $property->product_id = $product->id;
                    $property->property_type = 'other'; 
                    $property->property_name_az = $name;
                    $property->property_name_en = $request->property_name_en[$key] ?? '';
                    $property->property_name_ru = $request->property_name_ru[$key] ?? '';
                    $property->property_value_az = $request->property_value_az[$key] ?? '';
                    $property->property_value_en = $request->property_value_en[$key] ?? '';
                    $property->property_value_ru = $request->property_value_ru[$key] ?? '';
                    $property->sort_order = $key;
                    $property->save();
                }
            }
        }
        
        return redirect()->route('back.pages.products.index')->with('success', 'Məlumatlar uğurla yeniləndi.');
    }

   
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
       
        if ($product->main_image && File::exists(public_path($product->main_image))) {
            File::delete(public_path($product->main_image));
        }
        
        
        foreach ($product->images as $image) {
            if (File::exists(public_path($image->image_path))) {
                File::delete(public_path($image->image_path));
            }
        }
        
        
        $product->delete();
        
        return redirect()->route('back.pages.products.index')->with('success', 'Məlumatlar uğurla silindi.');
    }
    
   
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = !$product->status;
        $product->save();
        
        return redirect()->route('back.pages.products.index')->with('success', 'Status uğurla dəyişdirildi.');
    }
    
   
    public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();
        
        return redirect()->route('back.pages.products.index')->with('success', 'Ürün öne çıkarma durumu uğurla dəyişdirildi.');
    }
}
