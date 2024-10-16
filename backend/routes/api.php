<?php

use App\Http\Controllers\ToDoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/todo', [ToDoController::class, 'index'])->name('todo.index');
Route::post('/todo/create', [ToDoController::class, 'store'])->name('todo.store');
Route::get('/todo/{id}', [ToDoController::class, 'show'])->name('todo.show');
