<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['title','description','duration_minutes','opens_at','closes_at','pass_mark','status','show_correct_answers','randomize_questions'];

    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'show_correct_answers' => 'boolean',
        'randomize_questions' => 'boolean',
    ];
    
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }
}
