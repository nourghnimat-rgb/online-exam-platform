<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
