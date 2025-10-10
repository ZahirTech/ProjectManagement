<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectManageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('create', [ProjectManageController::class, 'create'])->name('projectmng.create');
Route::get('project_manage', [ProjectManageController::class, 'list'])->name('projectmng.list');
Route::get('project_manage/show', [ProjectManageController::class, 'show'])->name('projectmng.show');
