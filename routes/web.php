<?php

declare(strict_types=1);

use App\Http\Controllers\CaseRecordsController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'));
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::delete('/logout', [SessionController::class, 'destroy']);

Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/create', [ClientController::class, 'create']);
Route::post('/clients', [ClientController::class, 'store']);
Route::get('/clients/{client}', [ClientController::class, 'show']);
Route::get('/clients/{client}/edit', [ClientController::class, 'edit']);
Route::patch('/clients/{client}', [ClientController::class, 'update']);
Route::delete('/clients/{client}', [ClientController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/create', [UserController::class, 'create']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::get('/users/{user}/edit', [UserController::class, 'edit']);
Route::patch('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

Route::get('/cases', [CaseRecordsController::class, 'index']);
