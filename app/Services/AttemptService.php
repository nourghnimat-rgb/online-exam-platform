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
        $questions = $attempt->exam->questions()
            ->select(['id', 'exam_id', 'text', 'type', 'points', 'position'])
            ->with('options:id,question_id,text,position')
            ->orderBy('position')
            ->get();
        if ($attempt->exam->randomize_questions) {
            return $questions->shuffle($attempt->id);
        }

        return $questions;
    }
    public function saveAnswers(Attempt $attempt, array $answers)
    {
        $this->notExpired($attempt);

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
        return [
            'saved_at'          => now()->format('H:i:s'),
            'remaining_seconds' => $attempt->remainingSeconds(),
        ];
    }
    public function notExpired(Attempt $attempt)
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
    public function closeExpired()
    {
        $attempts = Attempt::where('status', 'in_progress')
            ->where('expires_at', '<=', now())
            ->get();

        $closed = 0;

        foreach ($attempts as $attempt) {
            $claimed = Attempt::where('id', $attempt->id)
                ->where('status', 'in_progress')
                ->update([
                    'status'       => 'expired',
                    'submitted_at' => $attempt->expires_at,
                ]);

            if ($claimed) {
                GradeAttemptJob::dispatch($attempt->id);
                $closed++;
            }
        }

        return $closed;
    }
    public function resultFor(Attempt $attempt)
    {
        $attempt->student->unreadNotifications->where('data.attempt_id', $attempt->id)->markAsRead();

        return $attempt->load('exam.questions.options', 'attemptAnswers');
    }
    public function forExam(Exam $exam)
    {
        return $exam->attempts()->with('student')->latest()->paginate(15);
    }
    public function resultsFor(User $student)
    {
        return $student->attempts()
            ->with('exam.course')
            ->where('status', '!=', 'in_progress')
            ->latest('submitted_at')
            ->paginate(15);
    }
}
