<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = ['exam_id', 'student_id', 'started_at', 'expires_at', 'submitted_at', 'status', 'score', 'percentage', 'passed', 'graded_at'];
    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'passed' => 'boolean',
    ];
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
    public function remainingSeconds(): int
    {
        return max(0, (int) now()->diffInSeconds($this->expires_at, false));
    }
}
