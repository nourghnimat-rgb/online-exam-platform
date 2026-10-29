<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttemptAnswer extends Model
{
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }
    public function questionOption(){
        return $this->belongsTo(QuestionOption::class,'selected_option_id');
    }
}
