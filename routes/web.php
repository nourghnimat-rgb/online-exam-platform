<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use App\Models\Course;
use App\Models\User;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
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
});
