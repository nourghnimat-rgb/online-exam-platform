<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttemptAnswer extends Model
{
    protected $fillable = ['attempt_id', 'question_id', 'selected_option_id', 'text_answer', 'awarded_points'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }
    public function questionOption()
    {
        return $this->belongsTo(QuestionOption::class, 'selected_option_id');
    }
}
