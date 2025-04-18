<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeQuestionController extends Controller
{
    public function index()
    {
        Artisan::call('migrate');
        $questions = HomeQuestion::orderBy('order')->get();
        return view('back.pages.home-questions.index', compact('questions'));
    }

    public function create()
    {
        return view('back.pages.home-questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'title_ru' => 'required|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'required|string',
            'description_ru' => 'required|string',
        ]);

        try {
            HomeQuestion::create($request->all());

            return redirect()->route('back.pages.home-questions.index')
                ->with('success', 'Sual uğurla əlavə edildi.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Xəta baş verdi: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $question = HomeQuestion::findOrFail($id);
        return view('back.pages.home-questions.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $question = HomeQuestion::findOrFail($id);

        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'title_ru' => 'required|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'required|string',
            'description_ru' => 'required|string',
        ]);

        try {
            $question->update($request->all());

            return redirect()->route('back.pages.home-questions.index')
                ->with('success', 'Sual məlumatları uğurla yeniləndi.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Xəta baş verdi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $question = HomeQuestion::findOrFail($id);
        $question->delete();

        return redirect()->route('back.pages.home-questions.index')
            ->with('success', 'Sual uğurla silindi.');
    }

    public function toggleStatus($id)
    {
        $question = HomeQuestion::findOrFail($id);
        $question->status = !$question->status;
        $question->save();

        return redirect()->route('back.pages.home-questions.index')
            ->with('success', 'Sualın statusu uğurla dəyişdirildi.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:home_questions,id'
        ]);

        foreach ($request->orders as $index => $id) {
            HomeQuestion::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }
} 