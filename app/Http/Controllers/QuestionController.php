<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Exam;
use App\Models\Question;
use App\Services\QuestionService;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
     public function index(Exam $exam, QuestionService $service)
    {
        return view('questions.index', ['exam' => $exam, 'questions' => $service->index($exam)]);
    }
    public function store(StoreQuestionRequest $request,Exam $exam, QuestionService $service)
    {
        $service->create($exam,$request->validated());
        return redirect()->route('questions.index',$exam)->with('success','question added');
    }
    public function update(UpdateQuestionRequest $request,Question $question, QuestionService $service)
    {
        $service->update($question,$request->validated());
        return redirect()->route('questions.index',$question->exam_id)->with('success','question updated');
    }
    public function destroy(Question $question, QuestionService $service)
{
    $service->delete($question);

    return back()->with('success', 'Question deleted');
}
}
