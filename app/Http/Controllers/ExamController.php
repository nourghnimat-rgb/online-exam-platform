<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Course;
use App\Models\Exam;
use App\Services\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{

    public function index(Course $course, ExamService $service)
    {
        return view('exams.index', ['course' => $course, 'exams' => $service->index($course)]);
    }

    public function create(Course $course){
        return view('exams.create',['course'=>$course]);
    }
    public function store(StoreExamRequest $request ,Course $course,ExamService $service){
        $service->create($course, $request->user(), $request->validated());

        return redirect()->route('exams.index', $course)->with('success', 'Exam created');
    }
    public function edit(Exam $exam){
        return view('exams.edit',['exam'=>$exam]);
    }
    public function update(UpdateExamRequest $request, Exam $exam, ExamService $service)
    {
        $service->update($exam, $request->validated());
        return redirect()->route('exams.index', $exam->course_id)->with('success', 'Exam updated');
    }

    public function publish(Exam $exam, ExamService $service){
        $service->publish($exam);
        return back()->with('success','Exam publishesd');

    }
    public function unpublish(Exam $exam, ExamService $service){
        $service->unpublish($exam);
        return back()->with('success','Exam unpublishesd');
        
    }
    public function destroy (Exam $exam, ExamService $service){
        $service->delete($exam);
        return redirect()->route('exams.index', $exam->course_id)->with('success', 'Exam deleted');
    }

}
