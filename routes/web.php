<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Models\Course;
use App\Models\User;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->can('viewAny', User::class)->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->can('create', User::class)->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->can('delete', 'user')->name('users.destroy');

    Route::get('/courses', [CourseController::class, 'index'])->can('viewAny', Course::class)->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->can('create', Course::class)->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->can('update', 'course')->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::put('/courses/{course}/instructors', [CourseController::class, 'updateInstructors'])->name('courses.instructors');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->can('delete', 'course')->name('courses.destroy');


    Route::get('/courses/{course}/student', [CourseController::class, 'students'])->can('manageStudents', 'course')->name('courses.students.index');
    Route::post('/courses/{course}/student', [CourseController::class, 'addStudent'])->name('courses.students.add');
    Route::delete('/courses/{course}/students/{user}', [CourseController::class, 'removeStudent'])->can('removeStudent', ['course', 'user'])->name('courses.students.remove');

    Route::get('/my-courses', [CourseController::class, 'mine'])->can('viewCourses', Course::class)->name('courses.mine');

    Route::get('/courses/{course}/exams', [ExamController::class, 'index'])
        ->can('manageStudents', 'course')->name('exams.index');
    Route::get('/courses/{course}/exams/create', [ExamController::class, 'create'])
        ->can('manageStudents', 'course')->name('exams.create');
    Route::post('/courses/{course}/exams', [ExamController::class, 'store'])
        ->name('exams.store');

    Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])
        ->can('update', 'exam')->name('exams.edit');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])
        ->name('exams.update');
    Route::patch('/exams/{exam}/publish', [ExamController::class, 'publish'])
        ->can('update', 'exam')->name('exams.publish');
    Route::patch('/exams/{exam}/unpublish', [ExamController::class, 'unpublish'])
        ->can('update', 'exam')->name('exams.unpublish');
    Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])
        ->can('delete', 'exam')->name('exams.destroy');
        
    Route::get('/exams/{exam}/questions', [QuestionController::class, 'index'])
        ->can('manageQuestions', 'exam')->name('questions.index');
    Route::post('/exams/{exam}/questions', [QuestionController::class, 'store'])
        ->name('questions.store');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])
        ->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])
        ->can('delete', 'question')->name('questions.destroy');
});
