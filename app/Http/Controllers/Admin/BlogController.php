<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::orderBy('id', 'desc')->get();
        return view('back.admin.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.blog.create');
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
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'short_description_az' => 'required|string',
            'short_description_en' => 'nullable|string',
            'short_description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // Slug oluşturma
        $data['slug_az'] = Str::slug($request->title_az);
        $data['slug_en'] = $request->title_en ? Str::slug($request->title_en) : null;
        $data['slug_ru'] = $request->title_ru ? Str::slug($request->title_ru) : null;

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Uploads klasörünü kontrol et, yoksa oluştur
            $uploadPath = public_path('uploads/blog');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $imageName);
            $data['image'] = 'uploads/blog/' . $imageName;
        }
        
        // Yayınlanma tarihi
        if (empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Blog::create($data);

        return redirect()->route('back.pages.blog.index')->with('success', 'Blog yazısı uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('back.pages.blog.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        return view('back.admin.blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $blog = Blog::findOrFail($id);
        
        // Validasyon
        $validated = $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'short_description_az' => 'required|string',
            'short_description_en' => 'nullable|string',
            'short_description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // Slug oluşturma
        $data['slug_az'] = Str::slug($request->title_az);
        $data['slug_en'] = $request->title_en ? Str::slug($request->title_en) : null;
        $data['slug_ru'] = $request->title_ru ? Str::slug($request->title_ru) : null;

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($blog->image && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }
            
            // Uploads klasörünü kontrol et, yoksa oluştur
            $uploadPath = public_path('uploads/blog');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $imageName);
            $data['image'] = 'uploads/blog/' . $imageName;
        }
        
        // Yayınlanma tarihi
        if (empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return redirect()->route('back.pages.blog.index')->with('success', 'Blog yazısı uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        
        // Görseli sil
        if ($blog->image && File::exists(public_path($blog->image))) {
            File::delete(public_path($blog->image));
        }
        
        $blog->delete();

        return redirect()->route('back.pages.blog.index')->with('success', 'Blog yazısı uğurla silindi.');
    }
    
    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->status = !$blog->status;
        $blog->save();

        return redirect()->route('back.pages.blog.index')->with('success', 'Blog yazısının statusu uğurla dəyişdirildi.');
    }
} 