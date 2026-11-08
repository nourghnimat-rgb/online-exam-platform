<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddStudentRequest;
use App\Http\Requests\AssignInstructorRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request, CourseService $service)
    {
        $courses = $service->paginate($request->search);
        return view('courses.index', ['courses' => $courses]);
    }
    public function create()
    {
        return view('courses.create');
    }
    public function store(StoreCourseRequest $request, CourseService $service)
    {
        $service->create($request->validated());
        return redirect()->route('courses.index')->with('success', 'Course ceated');
    }
    public function edit(Course $course, CourseService $service)
    {
        return view('courses.edit', [
            'course' => $course,
            'instructors' => $service->allInstructors(),
        ]);
    }
    public function update(StoreCourseRequest $request, CourseService $service, Course $course)
    {
        $service->update($course, $request->validated());
        return back()->with('success', 'course updated');
    }
    public function updateInstructors(AssignInstructorRequest $request, Course $course, CourseService $service)
    {
        $service->assignInstructors($course, $request->instructor_ids ?? []);

        return back()->with('success', 'Instructors updated.');
    }
    public function destroy(Course $course, CourseService $service)
    {
        $service->delete($course);
        return redirect()->route('courses.index')->with('success', 'course deleted.');
    }
    public function students(Course $course, CourseService $service)
    {
        return view('courses.students', ['course' => $course, 'students' => $service->students($course),]);
    }
    public function addStudent(AddStudentRequest $request, Course $course, CourseService $service)
    {
        $added = $service->addStudent($course, $request->validated('email'));
        if ($added) {
            return back()->with('success', 'student added successfully');
        } else {
            return back()->with('error', 'student is already enrolled in this course ');
        }
    }
    public function removeStudent(Course $course, CourseService $service, User $user)
    {
        $service->removeStudent($course, $user);
        return back()->with('success', 'student removed successfully');
    }
    public function mine(Request $request){

        $courses = $request->user()->courses()->withCount('exams')->orderBy('name')->get();
        return view('courses.mine',['courses'=>$courses]);
    }
}
