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
Route::put('/todo/{id}', [ToDoController::class, 'update'])->name('todo.update');
Route::patch('/todo/{id}', [ToDoController::class, 'update'])->name('todo.update');
Route::delete('/todo/{id}', [ToDoController::class, 'destroy'])->name('todo.destroy');
Route::patch('/todo/{id}/update-id-completed', [ToDoController::class, 'updateIsCompleted'])->name('todo.updateIsCompleted');
