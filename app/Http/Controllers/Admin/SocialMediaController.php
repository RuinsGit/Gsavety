<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SocialMediaController extends Controller
{
    public function index()
    {
        
        $socials = SocialMedia::orderBy('order')->get();
        return view('back.admin.social.index', compact('socials'));
    }

    public function create()
    {
        return view('back.admin.social.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'link' => 'required|url'
        ], [
            'image.required' => 'Şəkil mütləq yüklənməlidir',
            'image.image' => 'Fayl şəkil formatında olmalıdır',
            'link.required' => 'Link mütləq daxil edilməlidir',
            'link.url' => 'Düzgün link daxil edin'
        ]);

        $social = new SocialMedia();
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/social'), $imageName);
            $social->image = 'uploads/social/' . $imageName;
        }

        $social->link = $request->link;
        $social->order = SocialMedia::max('order') + 1;
        $social->status = 1;
        $social->save();

        return redirect()->route('back.pages.social.index')->with('success', 'Sosial media uğurla əlavə edildi');
    }

    public function edit($id)
    {
        $social = SocialMedia::findOrFail($id);
        return view('back.admin.social.edit', compact('social'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'link' => 'required|url'
        ], [
            'image.image' => 'Fayl şəkil formatında olmalıdır',
            'link.required' => 'Link mütləq daxil edilməlidir',
            'link.url' => 'Düzgün link daxil edin'
        ]);

        $social = SocialMedia::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($social->image && File::exists(public_path($social->image))) {
                File::delete(public_path($social->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/social'), $imageName);
            $social->image = 'uploads/social/' . $imageName;
        }

        $social->link = $request->link;
        $social->status = 1;
        $social->save();

        return redirect()->route('back.pages.social.index')->with('success', 'Sosial media uğurla yeniləndi');
    }

    public function destroy($id)
    {
        $social = SocialMedia::findOrFail($id);
        
        if ($social->image && File::exists(public_path($social->image))) {
            File::delete(public_path($social->image));
        }
        
        $social->delete();

        return redirect()->route('back.pages.social.index')->with('success', 'Sosial media uğurla silindi');
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->order as $key => $order) {
            SocialMedia::where('id', $order['id'])->update(['order' => $order['position']]);
        }

        return redirect()->route('back.admin.social.index')
        ->with('success', 'Sosial media uğurla silindi.');
    }

    public function toggleStatus($id)
    {
        $social = SocialMedia::findOrFail($id);
        $social->status = !$social->status;
        $social->save();

        return redirect()->back()->with('success', 'Status uğurla dəyişdirildi');
    }
}
