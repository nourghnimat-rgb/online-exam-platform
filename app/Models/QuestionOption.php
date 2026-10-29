<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = ['text','position', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function attemptAnswers()
    {
        return $this->hasMany(AttemptAnswer::class, 'selected_option_id');
    }
}
