<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CapstoneTypeController;
use App\Http\Controllers\API\V1\MilestoneController;
use App\Http\Controllers\API\V1\MilestoneListController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\CourseController;
use App\Http\Controllers\API\V1\SectionController;
use App\Http\Controllers\API\V1\SectionStudentController;
use App\Http\Controllers\API\V1\SectionGroupController;
use App\Http\Controllers\API\V1\GroupController;
use App\Http\Controllers\API\V1\GroupMilestoneController;
use App\Http\Controllers\API\V1\GroupMemberController;
use App\Http\Controllers\API\V1\GroupAdviserController;
use App\Http\Controllers\API\V1\GroupPanelController;
use App\Http\Controllers\API\V1\StudentController;


Route::prefix('auth')->name('auth.')->group(function () {

    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

Route::prefix('public')->name('public.')->group(function () {

    Route::get('courses', [CourseController::class, 'index'])->name('courses');
});

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('users/teachers', [UserController::class, 'teachers'])->name('users.teachers'); 
    Route::put('users/{user}/status', [UserController::class, 'status'])->name('users.status'); 
    Route::resource('users', UserController::class);

    Route::resource('courses', CourseController::class);

    Route::resource('sections', SectionController::class);

    Route::post('section-student/{section}', [SectionStudentController::class, 'store'])->name('section-student.store');
    Route::delete('section-student/{section}/{student}', [SectionStudentController::class, 'destroy'])->name('section-student.destroy');

    Route::post('section-group/{section}', [SectionGroupController::class, 'store'])->name('section-group.store');
    Route::delete('section-group/{section}/{group}', [SectionGroupController::class, 'destroy'])->name('section-group.destroy');

    Route::resource('capstonetypes', CapstoneTypeController::class);

    Route::resource('milestones', MilestoneController::class);

    Route::resource('milestone-lists', MilestoneListController::class);

    Route::resource('groups', GroupController::class);

    Route::put('group-milestone/{groupmilestone}', [GroupMilestoneController::class, 'update'])->name('group-milestone.update');

    Route::post('group-member/{group}', [GroupMemberController::class, 'store'])->name('group-member.store');
    Route::delete('group-member/{group}/{member}', [GroupMemberController::class, 'destroy'])->name('group-member.destroy');

    Route::post('group-adviser/{group}', [GroupAdviserController::class, 'store'])->name('group-adviser.store');
    Route::delete('group-adviser/{group}/{adviser}', [GroupAdviserController::class, 'destroy'])->name('group-adviser.destroy');

    Route::post('group-panel/{group}', [GroupPanelController::class, 'store'])->name('group-panel.store');
    Route::delete('group-panel/{group}/{panel}', [GroupPanelController::class, 'destroy'])->name('group-panel.destroy');

    Route::put('students/{student}/status', [StudentController::class, 'status'])->name('students.status'); 
    Route::resource('students', StudentController::class);
});