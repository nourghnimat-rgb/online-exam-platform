<?php

namespace App\Services;

use App\Models\Attempt;

class GradingService
{
    public function grade(Attempt $attempt)
    {
        
        if ($attempt->graded_at) {
            return;
        }

        $score = 0;
        $total = 0;

        foreach ($attempt->exam->questions as $question) {
            $total += $question->points;

            $given = $attempt->attemptAnswers->where('question_id', $question->id);

            if ($question->type->value === 'short_text') {
                $text = $given->first()->text_answer ?? '';
                $correct = mb_strtolower(trim($text)) === mb_strtolower(trim($question->correct_answer));
            } else {
               
                $right = $question->options->where('is_correct', true)->pluck('id')->sort()->values()->all();
                $chosen = $given->pluck('selected_option_id')->sort()->values()->all();
                $correct = $right == $chosen;
            }

            if ($correct) {
                $score += $question->points;
            }
        }

        $percentage = $total > 0 ? round($score / $total * 100, 2) : 0;

        $attempt->update([
            'score'      => $score,
            'percentage' => $percentage,
            'passed'     => $percentage >= $attempt->exam->pass_mark,
            'graded_at'  => now(),
        ]);
    }
}