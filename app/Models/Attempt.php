<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = ['exam_id', 'student_id', 'started_at', 'expires_at', 'submitted_at', 'status', 'score', 'graded_at'];

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
