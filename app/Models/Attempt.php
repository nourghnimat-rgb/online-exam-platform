<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    public function student()
    {
        return $this->belongsTo(User::class, "student_id");
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    public function attemptAnswers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }

}
