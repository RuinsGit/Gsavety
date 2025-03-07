<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialshareResource;
use App\Models\Socialshare;
use Illuminate\Http\Request;

class SocialshareApiController extends Controller
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
        return SocialshareResource::collection($socialshares);
    }

    public function show($id)
    {
        $socialshare = Socialshare::find($id);
        if (!$socialshare) {
            return response()->json(['message' => 'Social share not found'], 404);
        }
        return new SocialshareResource($socialshare);
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

        $socialshare = Socialshare::create($data);

        return response()->json(new SocialshareResource($socialshare), 201);
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
            // Eski resmi sil
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

        $socialshare->update($data);

        return response()->json(new SocialshareResource($socialshare), 200);
    }

    public function destroy($id)
    {
        $socialshare = Socialshare::findOrFail($id);
        
        // Resmi sil
        if ($socialshare->image) {
            $imagePath = public_path($socialshare->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $socialshare->delete();

        return response()->json(['message' => 'Social share successfully deleted'], 204);
    }
} 