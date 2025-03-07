<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialfooterResource;
use App\Models\Socialfooter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SocialfooterApiController extends Controller
{
    public function index()
    {
        $socials = Socialfooter::orderBy('order')->get();
        return SocialfooterResource::collection($socials);
    }

    public function show($id)
    {
        $social = Socialfooter::find($id);
        if (!$social) {
            return response()->json(['message' => 'Social footer not found'], 404);
        }
        return new SocialfooterResource($social);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'link' => 'required|url'
        ]);

        $social = new Socialfooter();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/social'), $imageName);
            $social->image = 'uploads/social/' . $imageName;
        }

        $social->link = $request->link;
        $social->order = Socialfooter::max('order') + 1;
        $social->status = $request->has('status') ? 1 : 0;
        $social->save();

        return response()->json(new SocialfooterResource($social), 201);
    }

    public function update(Request $request, $id)
    {
        $social = Socialfooter::findOrFail($id);

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

        return response()->json(new SocialfooterResource($social), 200);
    }

    public function destroy($id)
    {
        $social = Socialfooter::findOrFail($id);
        
        if ($social->image && File::exists(public_path($social->image))) {
            File::delete(public_path($social->image));
        }
        
        $social->delete();

        return response()->json(['message' => 'Social media successfully deleted'], 204);
    }

    public function toggleStatus($id)
    {
        $social = Socialfooter::findOrFail($id);
        $social->status = !$social->status;
        $social->save();

        return response()->json(['message' => 'Status successfully updated'], 200);
    }
} 