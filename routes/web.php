<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectManageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Project Item Routes
    Route::get('create', [ProjectManageController::class, 'create'])->name('projectmng.create');
    Route::post('create', [ProjectManageController::class, 'store'])->name('projectmng.store');
    Route::get('project_manage', [ProjectManageController::class, 'list'])->name('projectmng.list');
    Route::get('project_manage/{id}', [ProjectManageController::class, 'show'])->name('projectmng.show');
    Route::put('project_manage/{id}', [ProjectManageController::class, 'update'])->name('projectmng.update');
    Route::delete('project_manage/{id}', [ProjectManageController::class, 'destroy'])->name('projectmng.destroy');

    // Status Update (AJAX)
    Route::patch('project_manage/{id}/status', [ProjectManageController::class, 'updateStatus'])
        ->name('projectmng.updateStatus');

    // Pin/Unpin Item (AJAX)
    Route::patch('project_manage/{id}/pin', [ProjectManageController::class, 'togglePin'])
        ->name('projectmng.togglePin');

    // Attachment Routes
    Route::post('project_manage/{id}/attachments', [ProjectManageController::class, 'uploadAttachment'])
        ->name('projectmng.uploadAttachment');
    Route::delete('attachments/{id}', [ProjectManageController::class, 'deleteAttachment'])
        ->name('attachments.destroy');
});

Route::get('login', [AuthController::class, 'showLogin'])->middleware('guest')->name('showLogin');
Route::get('register', [AuthController::class, 'showRegister'])->name('showRegister');

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
