<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\QuizList;
use App\Http\Livewire\TakeQuiz;
use App\Http\Livewire\Admin\QuizManager;
use App\Http\Livewire\Admin\QuestionManager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/quiz/{quizId}', TakeQuiz::class)->name('quiz.take');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', QuizManager::class)->name('admin.quizzes');
    Route::get('/quiz/{quizId}/questions', QuestionManager::class)->name('admin.questions');
});
