<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['text','type','points','position','correct_answer'];
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
    public function attemptAnswers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }
}
