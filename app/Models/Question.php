<?php

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['text','type','points','position','correct_answer'];
    protected $casts =['type'=>QuestionType::class];
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
