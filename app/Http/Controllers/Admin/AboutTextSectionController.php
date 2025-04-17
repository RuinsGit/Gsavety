<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutTextSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;

class AboutTextSectionController extends Controller
{
    /**
     * Display and edit form for about text sections.
     */
    public function index()
    {
        Artisan::call('migrate');
        // İlk kaydı al veya boş bir kayıt oluştur
        $aboutTextSection = AboutTextSection::first();
        
        if (!$aboutTextSection) {
            $aboutTextSection = AboutTextSection::create([
                'status' => 1,
            ]);
        }
        
        return view('back.admin.about-text-sections.index', compact('aboutTextSection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title1_az' => 'nullable|string|max:255',
            'title1_en' => 'nullable|string|max:255',
            'title1_ru' => 'nullable|string|max:255',
            'description1_az' => 'nullable|string',
            'description1_en' => 'nullable|string',
            'description1_ru' => 'nullable|string',
            'title2_az' => 'nullable|string|max:255',
            'title2_en' => 'nullable|string|max:255',
            'title2_ru' => 'nullable|string|max:255',
            'description2_az' => 'nullable|string',
            'description2_en' => 'nullable|string',
            'description2_ru' => 'nullable|string',
            'title3_az' => 'nullable|string|max:255',
            'title3_en' => 'nullable|string|max:255',
            'title3_ru' => 'nullable|string|max:255',
            'description3_az' => 'nullable|string',
            'description3_en' => 'nullable|string',
            'description3_ru' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $aboutTextSection = AboutTextSection::first();
        
        if (!$aboutTextSection) {
            $aboutTextSection = new AboutTextSection();
        }
        
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        $aboutTextSection->fill($data);
        $aboutTextSection->save();
        
        return redirect()->route('back.pages.about-text-sections.index')
            ->with('success', 'Məlumatlar uğurla yeniləndi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus()
    {
        $aboutTextSection = AboutTextSection::first();
        
        if (!$aboutTextSection) {
            return redirect()->route('back.pages.about-text-sections.index')
                ->with('error', 'Məlumat tapılmadı.');
        }
        
        $aboutTextSection->status = !$aboutTextSection->status;
        $aboutTextSection->save();
        
        return redirect()->route('back.pages.about-text-sections.index')
            ->with('success', 'Status uğurla dəyişdirildi.');
    }
} 