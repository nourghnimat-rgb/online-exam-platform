<?php

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = ['text', 'position', 'is_correct'];
    protected $casts = ['is_correct' => 'boolean'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function attemptAnswers()
    {
        return $this->hasMany(AttemptAnswer::class, 'selected_option_id');
    }
}
