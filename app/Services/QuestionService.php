<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Validation\ValidationException;

class QuestionService
{

    public function index(Exam $exam)
    {
        return $exam->questions()->with('options')->orderby('position')->get();
    }

    public function create(Exam $exam, array $data)
    {
        $this->checkAttempts($exam);

        $question = new Question();
        $question->exam_id = $exam->id;
        $question->text = $data['text'];
        $question->type = $data['type'];
        $question->points = $data['points'];
        $question->position = $exam->questions()->count() + 1;
        $question->correct_answer = $data['correct_answer'] ?? null;
        $question->save();

        $this->saveOptions($question,$data['options']??[]);
        return $question;
    }
    public function checkAttempts(Exam $exam)
    {
        if ($exam->attempts()->exists()) {
            throw ValidationException::withMessages(['exam' => 'This exam already has attempts, so its questions cannot be changed.']);
        }
    }
    public function saveOptions(Question $question, array $options)
    {
        $question->options()->delete();

        $position = 1;

        foreach ($options as $option) {
            $newOption = new QuestionOption();
            $newOption->question_id = $question->id;
            $newOption->text = $option['text'];
            $newOption->position = $position;
            $newOption->is_correct = isset($option['is_correct']);
            $newOption->save();

            $position++;
        }
    }
    public function update(Question $question, array $data)
    {
        $this->checkAttempts($question->exam);
        $question->text = $data['text'];
        $question->type = $data['type'];
        $question->points = $data['points'];
        $question->correct_answer = $data['correct_answer'] ?? null;
        $question->save();

        $this->saveOptions($question,$data['options']??[]);
        return $question;
    }
    public function delete(Question $question){
         $this->checkAttempts($question->exam);
         $question->delete();

    }
}
