<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TranslationManage;
use Illuminate\Http\Request;


class TranslationManageController extends Controller
{
    public function index()
    {
     
        $translationManages = TranslationManage::all();
        return view('back.admin.translation-manage.index', compact('translationManages'));
    }

    public function create()
    {
        return view('back.admin.translation-manage.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:translation_manages,key',
            'value_az' => 'required',
            'value_en' => 'required',
            'value_ru' => 'required',
            'group' => 'required',
            'status' => 'required',
        ]);

        TranslationManage::create($request->all());

        return redirect()->route('back.pages.translation-manage.index')->with('success', 'Tərcümə məlumatları uğurla əlavə edildi.');
    }

    public function edit(TranslationManage $translationManage)
    {
        return view('back.admin.translation-manage.edit', compact('translationManage'));
    }

    public function update(Request $request, TranslationManage $translationManage)
    {
        $request->validate([
            'key' => 'required|unique:translation_manages,key,' . $translationManage->id,
            'value_az' => 'required',
            'value_en' => 'required',
            'value_ru' => 'required',
            'group' => 'required',
            'status' => 'required',
        ]);

        $translationManage->update($request->all());

        return redirect()->route('back.pages.translation-manage.index')->with('success', 'Tərcümə məlumatları uğurla yeniləndi.');
    }

    public function destroy(TranslationManage $translationManage)
    {
        $translationManage->delete();

        return redirect()->route('back.pages.translation-manage.index')->with('success', 'Translation deleted successfully.');
    }
}