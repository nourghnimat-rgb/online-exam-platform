<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveAnswerRequest;
use App\Models\Attempt;
use App\Models\Exam;
use App\Services\AttemptService;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function store(Request $request, Exam $exam, AttemptService $service)
    {
        $attempt =$service->start($exam, $request->user());

        return redirect()->route('attempts.show', $attempt);
    }
    public function show(Attempt $attempt,AttemptService $service){
         return view('attempts.show', [
        'attempt'   => $attempt,
        'questions' => $service->questionsFor($attempt),
    ]);

    }
    public function saveAnswers(SaveAnswerRequest $request, Attempt $attempt, AttemptService $service)
{
    $service->saveAnswers($attempt, $request->validated('answers') ?? []);

    return response()->json(['saved_at' => now()->format('H:i:s')]);
}
public function submit(Attempt $attempt, AttemptService $service)
{
    $service->submit($attempt);

    return redirect()->route('exams.available')->with('success', 'Exam submitted.');
}
}
