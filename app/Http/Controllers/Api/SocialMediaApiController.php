<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialMediaResource;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SocialMediaApiController extends Controller
{
    public function index()
    {
        $socials = SocialMedia::orderBy('order')->get();
        return SocialMediaResource::collection($socials);
    }

    public function show($id)
    {
        $social = SocialMedia::find($id);
        if (!$social) {
            return response()->json(['message' => 'Social media not found'], 404);
        }
        return new SocialMediaResource($social);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'link' => 'required|url'
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
        $social->status = $request->has('status') ? 1 : 0;
        $social->save();

        return response()->json(new SocialMediaResource($social), 201);
    }

    public function update(Request $request, $id)
    {
        $social = SocialMedia::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'link' => 'required|url'
        ]);

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
        $social->status = $request->has('status') ? 1 : 0;
        $social->save();

        return response()->json(new SocialMediaResource($social), 200);
    }

    public function destroy($id)
    {
        $social = SocialMedia::findOrFail($id);
        
        if ($social->image && File::exists(public_path($social->image))) {
            File::delete(public_path($social->image));
        }
        
        $social->delete();

        return response()->json(['message' => 'Social media successfully deleted'], 204);
    }

    public function toggleStatus($id)
    {
        $social = SocialMedia::findOrFail($id);
        $social->status = !$social->status;
        $social->save();

        return response()->json(['message' => 'Status successfully updated'], 200);
    }
} 