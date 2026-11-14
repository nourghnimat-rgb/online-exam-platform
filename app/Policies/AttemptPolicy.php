<?php

namespace App\Policies;

use App\Models\Attempt;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttemptPolicy
{
  
    public function answer(User $user, Attempt $attempt): bool
    {

        return $attempt->student_id === $user->id
            && $attempt->status === 'in_progress';
    }
}
