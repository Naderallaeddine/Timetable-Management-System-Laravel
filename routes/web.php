<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherSubjectClassController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TimetableGenerationController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('teachers', TeacherController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('timetables', TimetableController::class);
    Route::resource('class_subjects', ClassSubjectController::class);
    Route::resource('teacher_subject_classes', TeacherSubjectClassController::class);
Route::post('/timetables/generateAll', [TimetableGenerationController::class, 'generateAll'])->name('timetables.generateAll');
Route::post('/timetables/teacher', [TimetableController::class, 'teacherSchedule'])->name('timetables.teacherSchedule');
Route::get('/export-pdf', [TimetableGenerationController::class, 'exportPDF'])->name('export.pdf');




});
