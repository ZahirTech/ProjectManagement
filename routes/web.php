<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectListController;
use App\Http\Controllers\ProjectManageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Project Routes
    Route::get('projects', [ProjectListController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectListController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectListController::class, 'store'])->name('projects.store');
    Route::get('projects/{id}/edit', [ProjectListController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{id}', [ProjectListController::class, 'update'])->name('projects.update');
    Route::patch('projects/{id}/status', [ProjectListController::class, 'toggleStatus'])->name('projects.toggleStatus');

    // Project Item Routes
    Route::get('create', [ProjectManageController::class, 'create'])->name('projectmng.create');
    Route::post('create', [ProjectManageController::class, 'store'])->name('projectmng.store');
    Route::get('project_manage', [ProjectManageController::class, 'list'])->name('projectmng.list');
    Route::get('project_manage/tab/{status}', [ProjectManageController::class, 'getTabItems'])->name('projectmng.getTabItems');
    Route::get('project_manage/{id}', [ProjectManageController::class, 'show'])->name('projectmng.show');
    Route::get('project_manage/{id}/edit', [ProjectManageController::class, 'edit'])->name('projectmng.edit');
    Route::put('project_manage/{id}', [ProjectManageController::class, 'update'])->name('projectmng.update');
    Route::delete('project_manage/{id}', [ProjectManageController::class, 'destroy'])->name('projectmng.destroy');
    // Notes Routes
    Route::get('notes', [NotesController::class, 'index'])->name('notes.index');
    Route::get('notes/create', [NotesController::class, 'create'])->name('notes.create');
    Route::post('notes', [NotesController::class, 'store'])->name('notes.store');
    Route::get('notes/{id}', [NotesController::class, 'show'])->name('notes.show');
    Route::get('notes/{id}/edit', [NotesController::class, 'edit'])->name('notes.edit');
    Route::put('notes/{id}', [NotesController::class, 'update'])->name('notes.update');
    Route::delete('notes/{id}', [NotesController::class, 'destroy'])->name('notes.destroy');
    Route::patch('notes/{id}/pin', [NotesController::class, 'togglePin'])->name('notes.togglePin');

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
