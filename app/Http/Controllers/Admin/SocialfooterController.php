<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Socialfooter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;




class SocialfooterController extends Controller
{
    public function index()
    {
        Artisan::call('migrate');
        $socialfooters = Socialfooter::orderBy('order')->get();
        return view('back.admin.socialfooter.index', compact('socialfooters'));
    }

    public function create()
    {
        return view('back.admin.socialfooter.create');
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

        $socialfooter = new Socialfooter();
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/socialfooter'), $imageName);
            $socialfooter->image = 'uploads/socialfooter/' . $imageName;
        }

        $socialfooter->link = $request->link;
        $socialfooter->order = Socialfooter::max('order') + 1;
        $socialfooter->status = 1;
        $socialfooter->save();

        return redirect()->route('back.pages.socialfooter.index')->with('success', 'Sosial media uğurla əlavə edildi');
    }

    public function edit($id)
    {
        $socialfooter = Socialfooter::findOrFail($id);
        return view('back.admin.socialfooter.edit', compact('socialfooter'));
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

        $socialfooter = Socialfooter::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($socialfooter->image && File::exists(public_path($socialfooter->image))) {
                File::delete(public_path($socialfooter->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/socialfooter'), $imageName);
            $socialfooter->image = 'uploads/socialfooter/' . $imageName;
        }

        $socialfooter->link = $request->link;
        $socialfooter->status = 1;
        $socialfooter->save();

        return redirect()->route('back.pages.socialfooter.index')->with('success', 'Sosial media uğurla yeniləndi');
    }

    public function destroy($id)
    {
        $socialfooter = Socialfooter::findOrFail($id);
        
        if ($socialfooter->image && File::exists(public_path($socialfooter->image))) {
            File::delete(public_path($socialfooter->image));
        }
        
        $socialfooter->delete();

        return redirect()->route('back.pages.socialfooter.index')->with('success', 'Sosial media uğurla silindi');
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->order as $key => $order) {
            Socialfooter::where('id', $order['id'])->update(['order' => $order['position']]);
        }

        return redirect()->route('back.admin.socialfooter.index')
        ->with('success', 'Sosial media uğurla silindi.');
    }

    public function toggleStatus($id)
    {
        $socialfooter = Socialfooter::findOrFail($id);
        $socialfooter->status = !$socialfooter->status;
        $socialfooter->save();

        return redirect()->back()->with('success', 'Status uğurla dəyişdirildi');
    }
}
