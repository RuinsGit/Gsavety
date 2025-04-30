<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoScript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SeoScriptController extends Controller
{
    public function index()
    {
        Artisan::call('migrate');
        $scripts = SeoScript::all();
        return view('back.admin.seo_script.index', compact('scripts'));
    }

    public function create()
    {
        return view('back.admin.seo_script.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'script_content' => 'required',
            'status' => 'required|in:0,1'
        ]);

        SeoScript::create($request->all());
        return redirect()->route('back.pages.seo_script.index')->with('success', 'Script başarıyla eklendi.');
    }

    public function edit(SeoScript $seoScript)
    {
        return view('back.admin.seo_script.edit', compact('seoScript'));
    }

    public function update(Request $request, SeoScript $seoScript)
    {
        $request->validate([
            'script_content' => 'required',
            'status' => 'required|in:0,1'
        ]);

        $seoScript->update($request->all());
        return redirect()->route('back.pages.seo_script.index')->with('success', 'Script başarıyla güncellendi.');
    }

    public function destroy(SeoScript $seoScript)
    {
        $seoScript->delete();
        return redirect()->route('back.pages.seo_script.index')->with('success', 'Script başarıyla silindi.');
    }

    public function toggleStatus(string $id)
    {
        $script = SeoScript::findOrFail($id);
        $script->status = !$script->status;
        $script->save();

        if(request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Durum başarıyla değiştirildi.',
                'status' => $script->status
            ]);
        }

        return redirect()->route('back.pages.seo_script.index')->with('success', 'Durum başarıyla değiştirildi.');
    }
}
