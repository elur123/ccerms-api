<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CapstoneTypeController;
use App\Http\Controllers\API\V1\MilestoneController;
use App\Http\Controllers\API\V1\MilestoneListController;
use App\Http\Controllers\API\V1\CourseController;


Route::prefix('auth')->name('auth.')->group(function () {

    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
}); 

Route::middleware('auth:sanctum')->group(function () {

    Route::resource('courses', CourseController::class);

    Route::resource('capstonetypes', CapstoneTypeController::class);

    Route::resource('milestones', MilestoneController::class);

    Route::resource('milestone-lists', MilestoneListController::class);
});