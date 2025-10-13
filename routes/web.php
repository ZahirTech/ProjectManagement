<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectManageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=> 'auth'], function(){
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('create', [ProjectManageController::class, 'create'])->name('projectmng.create');
Route::get('project_manage', [ProjectManageController::class, 'list'])->name('projectmng.list');
Route::get('project_manage/show', [ProjectManageController::class, 'show'])->name('projectmng.show');
});

Route::get('login', [AuthController::class, 'showLogin'])->middleware('guest')->name('showLogin');
Route::get('register', [AuthController::class, 'showRegister'])->name('showRegister');

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register')->middleware('auth');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
