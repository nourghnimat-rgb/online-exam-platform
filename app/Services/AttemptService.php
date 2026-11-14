<?php

namespace App\Services;

use App\Enums\QuestionType;
use App\Jobs\GradeAttemptJob;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttemptService
{

    public function start(Exam $exam, User $student)
    {

        $started_at = now();
        $expires_at = $started_at->copy()->addMinute($exam->duration_minutes);
        if ($expires_at->greaterThan($exam->closes_at)) {
            $expires_at = $exam->closes_at->copy();
        }
        try {
            return Attempt::create([
                'exam_id'    => $exam->id,
                'student_id' => $student->id,
                'started_at' => $started_at,
                'expires_at' => $expires_at,
                'status'     => 'in_progress',
            ]);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'exam' => 'You have already started this exam.',
            ]);
        }
    }
    public function questionsFor(Attempt $attempt)
    {
        return $attempt->exam->questions()
            ->select(['id', 'exam_id', 'text', 'type', 'points', 'position'])
            ->with('options:id,question_id,text,position')
            ->orderBy('position')
            ->get();
    }
    public function saveAnswers(Attempt $attempt, array $answers)
    {
        $this->notExpiresd($attempt);

        $questions = $attempt->exam->questions()
            ->with('options:id,question_id')
            ->get()->keyBy('id');
        DB::transaction(function () use ($attempt, $answers, $questions) {
            $attempt->attemptAnswers()->delete();
            foreach ($answers as $questionId => $value) {
                $question = $questions->get($questionId);
                if (!$question) {
                    continue;
                }
                if ($question->type == QuestionType::ShortText) {
                    $text = trim((string)$value);
                    if ($text != '') {
                        $attempt->attemptAnswers()->create([
                            'question_id' => $question->id,
                            'text_answer' => $text,

                        ]);
                    }
                    continue;
                }
                if ($question->type === QuestionType::SingleChoice) {
                    $optionId = (int) $value;

                    if ($question->options->contains('id', $optionId)) {
                        $attempt->attemptAnswers()->create([
                            'question_id'        => $question->id,
                            'selected_option_id' => $optionId,
                        ]);
                    }
                    continue;
                }
                foreach ((array) $value as $item) {
                    $optionId = (int) $item;

                    if ($question->options->contains('id', $optionId)) {
                        $attempt->attemptAnswers()->create([
                            'question_id'        => $question->id,
                            'selected_option_id' => $optionId,
                        ]);
                    }
                }
            }
        });
    }
    public function notExpiresd(Attempt $attempt)
    {
        if (now()->greaterThanOrEqualTo($attempt->expires_at)) {
            throw ValidationException::withMessages([
                'attempt' => 'Time is up. Your answers can no longer be saved.',
            ]);
        }
    }
    public function submit(Attempt $attempt)
    {
        if (now()->greaterThanOrEqualTo($attempt->expires_at)) {
            throw ValidationException::withMessages([
                'attempt' => 'Time is up. You can no longer submit.',
            ]);
        }

        $attempt->update([
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);
        GradeAttemptJob::dispatch($attempt->id);
    }
}
