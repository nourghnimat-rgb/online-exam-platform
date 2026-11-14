<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    public function index(Request $request,ExamService $service){
        return view('exams.available',['exams' => $service->availableFor($request->user())]);
    }
}
