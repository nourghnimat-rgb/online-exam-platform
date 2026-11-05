<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;


class CourseService
{

    public function paginate($search)
    {

        $query = Course::with('instructors')->withCount('users')->orderBy('name');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        return $query->paginate(15);
    }

    public function create(array $data): Course
    {
    return Course::create($data);
    
    }
    public function update(Course $course, array $data)
    {
       return Course::update($data);
    }
    public function delete(Course $course)
    {
        $course->delete();
    }
    public function allInstructors(){

        return User::where('role', 'instructor')->orderBy('name')->get();
    }
    public function assignInstructors( Course $course , array $instructrIds){
        $oldIds= $course->instructors()->pluck('users.id')->toArray();

        $course->users()->detach($oldIds);
        $course->users()->attach($instructrIds);
    }
}
