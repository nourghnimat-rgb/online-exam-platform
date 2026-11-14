<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ExamService
{
    public function index(Course $course)
    {

        return $course->exams()->withCount(['attempts', 'questions'])->latest()->paginate(15);
    }
    public function create(Course $course, User $user, array $data)
    {
        $exam = new Exam($data);
        $exam->course_id = $course->id;
        $exam->created_by = $user->id;
        $exam->save();
        return $exam;
    }
    public function update(Exam $exam, array $data)
    {
        $data['show_correct_answers'] ??= false;
        $data['randomize_questions']  ??= false;
        $exam->update($data);
        return $exam;
    }
    public function publish(Exam $exam)
    {
        if (!$exam->questions()->exists()) {
            throw ValidationException::withMessages(['status' => 'Add atleast one questions to publish this exam']);
        }
        $exam->update(['status' => 'published']);
        return $exam;
    }
    public function unpublish(Exam $exam)
    {
        if ($exam->attempts()->exists()) {
            throw ValidationException::withMessages(['status' => 'This exam already has attempts and cannot be unpublished']);
        }
        $exam->update(['status' => 'draft']);
        return $exam;
    }
    public function delete(Exam $exam)
    {
        if ($exam->attempts()->exists()) {
            throw ValidationException::withMessages(['status' => 'This exam already has attempts and cannot be deleted']);
        }

        $exam->delete();
    }
    public function availableFor(User $student)
    {

        $courseIds = $student->courses()->pluck('courses.id');


        return Exam::query()
            ->whereIn('course_id', $courseIds)
            ->where('status', 'published')
            ->where('opens_at', '<=', now())
            ->where('closes_at', '>=', now())
            ->with('course')
            ->with(['attempts' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->orderBy('closes_at')->paginate(15);
    }
    
}
