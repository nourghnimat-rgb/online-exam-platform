<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'instructor';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Exam $exam): bool
    {
        return $this->canManage($exam, $user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'instructor';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Exam $exam): bool
    {
        return $this->canManage($exam, $user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Exam $exam): bool
    {
        return $this->canManage($exam, $user);
    }


    public function canManage(Exam $exam, User $user)
    {

        return $user->role === 'admin'
            || ($user->role === 'instructor'
                && $exam->course->users()->where('users.id', $user->id)->exists());
    }
    public function manageQuestions(User $user, Exam $exam)
    {
        return $this->canManage($exam, $user);
    }
    public function viewAvailable(User $user): bool
    {
        return $user->role === 'student';
    }
    public function take(User $user, Exam $exam)
    {
        return $user->role === 'student'
            && $exam->status === 'published'
            && $exam->opens_at->isPast()
            && $exam->closes_at->isFuture()
            && $exam->course->users()->where('users.id', $user->id)->exists();
    }
    public function viewAttempts(User $user, Exam $exam)
    {
        return $this->canManage($exam, $user);
    }
}
