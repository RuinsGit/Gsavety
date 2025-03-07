<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Socialshare;
use Illuminate\Http\Request;

class SocialshareController extends Controller
{
    private $destinationPath;

    public function __construct()
    {   
        $this->destinationPath = public_path('uploads');
        
        // Uploads klasörü yoksa oluştur
        if (!file_exists($this->destinationPath)) {
            mkdir($this->destinationPath, 0775, true);
        }
    }

    public function index()
    {
        $socialshares = Socialshare::orderBy('order')->get();
        return view('back.pages.socialshare.index', compact('socialshares'));
    }

    public function create()
    {
        return view('back.pages.socialshare.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg',
            'link' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($this->destinationPath, $fileName);
            $data['image'] = 'uploads/' . $fileName;
        }

        if (!isset($data['order'])) {
            $data['order'] = Socialshare::max('order') + 1;
        }

        // Varsayılan status değeri 1 olarak ayarlandı
        $data['status'] = 1;

        Socialshare::create($data);

        return redirect()->route('back.pages.socialshare.index')
            ->with('success', 'Sosial paylaşım uğurla əlavə edildi.');
    }

    public function edit($id)
    {
        $socialshare = Socialshare::findOrFail($id);
        return view('back.pages.socialshare.edit', compact('socialshare'));
    }

    public function update(Request $request, $id)
    {
        $socialshare = Socialshare::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg',
            'link' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($socialshare->image) {
                $oldImagePath = public_path($socialshare->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($this->destinationPath, $fileName);
            $data['image'] = 'uploads/' . $fileName;
        }

        // Varsayılan status değeri 1 olarak ayarlandı
        $data['status'] = 1;

        $socialshare->update($data);

        return redirect()->route('back.pages.socialshare.index')
            ->with('success', 'Sosial paylaşım uğurla yeniləndi.');
    }

    public function destroy($id)
    {
        $socialshare = Socialshare::findOrFail($id);
        
        // Delete image
        if ($socialshare->image) {
            $imagePath = public_path($socialshare->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $socialshare->delete();

        return redirect()->route('back.pages.socialshare.index')
            ->with('success', 'Sosial media uğurla silindi.');
    }

    public function toggleStatus($id)
    {
        $socialshare = Socialshare::findOrFail($id);
        $socialshare->status = !$socialshare->status;
        $socialshare->save();

        return redirect()->back()->with('success', 'Status uğurla dəyişdirildi');
    }
} 