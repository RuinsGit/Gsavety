<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Kategorilerin listesini göster
     */
    public function index()
    {
        $categories = Category::all();
        return view('back.admin.categories.index', compact('categories'));
    }

    /**
     * Yeni kategori oluşturma formu
     */
    public function create()
    {
        return view('back.admin.categories.create');
    }

    /**
     * Yeni kategoriyi kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_az' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = new Category();
        $category->name_az = $request->name_az;
        $category->name_en = $request->name_en;
        $category->name_ru = $request->name_ru;
        $category->description_az = $request->description_az;
        $category->description_en = $request->description_en;
        $category->description_ru = $request->description_ru;
        $category->status = $request->has('status') ? 1 : 0;
        $category->order = $request->order ?? 0;
        
        // Slug oluştur
        $category->slug_az = Str::slug($request->name_az);
        if (!empty($request->name_en)) {
            $category->slug_en = Str::slug($request->name_en);
        }
        if (!empty($request->name_ru)) {
            $category->slug_ru = Str::slug($request->name_ru);
        }
        
        // Resim yükleme
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/categories'), $imageName);
            $category->image = 'uploads/categories/' . $imageName;
        }
        
        $category->save();
        
        return redirect()->route('back.pages.categories.index')->with('success', 'Kategori başarıyla oluşturuldu');
    }

    /**
     * Kategori detaylarını göster
     */
    public function show(string $id)
    {
        $category = Category::with('products')->findOrFail($id);
        return view('back.admin.categories.show', compact('category'));
    }

    /**
     * Kategori düzenleme formu
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('back.admin.categories.edit', compact('category'));
    }

    /**
     * Kategori bilgilerini güncelle
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name_az' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $category = Category::findOrFail($id);
        
        $category->name_az = $request->name_az;
        $category->name_en = $request->name_en;
        $category->name_ru = $request->name_ru;
        $category->description_az = $request->description_az;
        $category->description_en = $request->description_en;
        $category->description_ru = $request->description_ru;
        $category->status = $request->has('status') ? 1 : 0;
        $category->order = $request->order ?? 0;
        
        // Slug oluştur
        $category->slug_az = Str::slug($request->name_az);
        if (!empty($request->name_en)) {
            $category->slug_en = Str::slug($request->name_en);
        }
        if (!empty($request->name_ru)) {
            $category->slug_ru = Str::slug($request->name_ru);
        }
        
        // Resim yükleme
        if ($request->hasFile('image')) {
            // Eski resim varsa sil
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/categories'), $imageName);
            $category->image = 'uploads/categories/' . $imageName;
        }
        
        $category->save();
        
        return redirect()->route('back.pages.categories.index')->with('success', 'Kategori başarıyla güncellendi');
    }

    /**
     * Kategoriyi sil
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        
        // Kategori resmi varsa sil
        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }
        
        $category->delete();
        
        return redirect()->route('back.pages.categories.index')->with('success', 'Kategori başarıyla silindi');
    }
    
    /**
     * Kategori durumunu değiştir
     */
    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->status = !$category->status;
        $category->save();
        
        return redirect()->route('back.pages.categories.index')->with('success', 'Kategori durumu başarıyla değiştirildi');
    }
}
