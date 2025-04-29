<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Artisan::call('migrate');
        $questions = Question::orderBy('id', 'desc')->get();
        return view('back.admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255'
        ]);

        Question::create([
            'title_az' => $request->title_az,
            'title_en' => $request->title_en,
            'title_ru' => $request->title_ru,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('back.pages.questions.index')
            ->with('success', 'Sual uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $question = Question::findOrFail($id);
        return view('back.admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = Question::findOrFail($id);
        return view('back.admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255'
        ]);

        $question = Question::findOrFail($id);
        
        $question->update([
            'title_az' => $request->title_az,
            'title_en' => $request->title_en,
            'title_ru' => $request->title_ru,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('back.pages.questions.index')
            ->with('success', 'Sual uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('back.pages.questions.index')
            ->with('success', 'Sual uğurla silindi.');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus($id)
    {
        $question = Question::findOrFail($id);
        $question->status = !$question->status;
        $question->save();

        return redirect()->route('back.pages.questions.index')
            ->with('success', 'Status uğurla dəyişdirildi.');
    }
}
