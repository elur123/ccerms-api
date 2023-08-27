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
use App\Http\Controllers\API\V1\DefenseScheduleController;
use App\Http\Controllers\API\V1\DefensePanelController;
use App\Http\Controllers\API\V1\DefenseTypeController;
use App\Http\Controllers\API\V1\MinuteTemplateController;
use App\Http\Controllers\API\V1\MinuteController;
use App\Http\Controllers\API\V1\ResearchArchiveController;
use App\Http\Controllers\API\V1\BoardController;


Route::prefix('auth')->name('auth.')->group(function () {

    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

Route::prefix('public')->name('public.')->group(function () {

    Route::get('courses', [CourseController::class, 'index'])->name('courses');
    Route::get('research-archives', [ResearchArchiveController::class, 'index'])->name('research-archives');
});

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('users/teachers', [UserController::class, 'teachers'])->name('users.teachers'); 
    Route::put('users/{user}/status', [UserController::class, 'status'])->name('users.status'); 
    Route::resource('users', UserController::class);

    Route::put('students/{student}/status', [StudentController::class, 'status'])->name('students.status'); 
    Route::resource('students', StudentController::class);

    Route::resource('groups', GroupController::class);

    Route::resource('courses', CourseController::class);

    Route::resource('sections', SectionController::class);

    Route::get('section-student', [SectionStudentController::class, 'availableStudents'])->name('section-student.available-students');
    Route::post('section-student/{section}', [SectionStudentController::class, 'store'])->name('section-student.store');
    Route::delete('section-student/{section}/{student}', [SectionStudentController::class, 'destroy'])->name('section-student.destroy');

    Route::get('section-group', [SectionGroupController::class, 'availableGroups'])->name('section-student.available-groups');
    Route::post('section-group/{section}', [SectionGroupController::class, 'store'])->name('section-group.store');
    Route::delete('section-group/{section}/{group}', [SectionGroupController::class, 'destroy'])->name('section-group.destroy');

    Route::put('defenses/{defense}/status', [DefenseScheduleController::class, 'status'])->name('defenses.status');
    Route::resource('defenses', DefenseScheduleController::class);
    
    Route::resource('defensetypes', DefenseTypeController::class);

    Route::resource('capstonetypes', CapstoneTypeController::class);

    Route::resource('milestones', MilestoneController::class);

    Route::resource('milestone-lists', MilestoneListController::class);

    Route::put('group-milestone/{groupmilestone}', [GroupMilestoneController::class, 'update'])->name('group-milestone.update');

    Route::post('group-member/{group}', [GroupMemberController::class, 'store'])->name('group-member.store');
    Route::delete('group-member/{group}/{member}', [GroupMemberController::class, 'destroy'])->name('group-member.destroy');

    Route::post('group-adviser/{group}', [GroupAdviserController::class, 'store'])->name('group-adviser.store');
    Route::delete('group-adviser/{group}/{adviser}', [GroupAdviserController::class, 'destroy'])->name('group-adviser.destroy');

    Route::post('group-panel/{group}', [GroupPanelController::class, 'store'])->name('group-panel.store');
    Route::delete('group-panel/{group}/{panel}', [GroupPanelController::class, 'destroy'])->name('group-panel.destroy');

    Route::post('minutetemplates/{template}/content', [MinuteTemplateController::class, 'addContent'])->name('minutetemplates.add.content');
    Route::delete('minutetemplates/{content}/content', [MinuteTemplateController::class, 'removeContent'])->name('minutetemplates.remove.content');
    Route::resource('minutetemplates', MinuteTemplateController::class);
    
    Route::resource('minutes', MinuteController::class);

    Route::post('research-archives/{research_archive}/member', [ResearchArchiveController::class, 'addMember'])->name('research-archives.member.store');
    Route::delete('research-archives/{member}/member', [ResearchArchiveController::class, 'removeMember'])->name('research-archives.member.destroy');
    Route::resource('research-archives', ResearchArchiveController::class);

    Route::get('boards/{group_id}/{step_id}', [BoardController::class, 'show'])->name('boards.show');
    Route::post('boards/{board}', [BoardController::class, 'storeSubmission'])->name('boards.submission.store');
    Route::post('boards/{submission}/comment', [BoardController::class, 'storeSubmissionComment'])->name('boards.submission.comment.store');
    Route::post('boards/{submission}/status', [BoardController::class, 'updateSubmissionStatus'])->name('boards.submission.status.update');
});