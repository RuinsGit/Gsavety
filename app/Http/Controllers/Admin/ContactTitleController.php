<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class ContactTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private const MAX_TITLES = 1;
    
    public function index()
    {   
        Artisan::call('migrate');
        $contactTitles = ContactTitle::orderBy('order')->get();
        $canCreate = $contactTitles->count() < self::MAX_TITLES;
        return view('back.admin.contact-titles.index', compact('contactTitles', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $count = ContactTitle::count();
        
        if ($count >= self::MAX_TITLES) {
            return redirect()->route('back.pages.contact-titles.index')
                ->with('error', 'Maksimum ' . self::MAX_TITLES . ' ədəd əlaqə başlığı əlavə edilə bilər.');
        }
        
        return view('back.admin.contact-titles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'special_title_az' => 'nullable|string|max:255',
            'special_title_en' => 'nullable|string|max:255',
            'special_title_ru' => 'nullable|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        ContactTitle::create($data);

        return redirect()->route('back.pages.contact-titles.index')->with('success', 'Məlumatlar uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contactTitle = ContactTitle::findOrFail($id);
        return view('back.admin.contact-titles.show', compact('contactTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contactTitle = ContactTitle::findOrFail($id);
        return view('back.admin.contact-titles.edit', compact('contactTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'special_title_az' => 'nullable|string|max:255',
            'special_title_en' => 'nullable|string|max:255',
            'special_title_ru' => 'nullable|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $contactTitle = ContactTitle::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        $contactTitle->update($data);

        return redirect()->route('back.pages.contact-titles.index')->with('success', 'Məlumatlar uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contactTitle = ContactTitle::findOrFail($id);
        $contactTitle->delete();

        return redirect()->route('back.pages.contact-titles.index')->with('success', 'Məlumatlar uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $contactTitle = ContactTitle::findOrFail($id);
        $contactTitle->status = !$contactTitle->status;
        $contactTitle->save();

        return redirect()->route('back.pages.contact-titles.index')->with('success', 'Status uğurla dəyişdirildi.');
    }
} 