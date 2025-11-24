<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Livewire components
        Livewire::component('quiz-list', \App\Http\Livewire\QuizList::class);
        Livewire::component('take-quiz', \App\Http\Livewire\TakeQuiz::class);
        Livewire::component('admin.quiz-manager', \App\Http\Livewire\Admin\QuizManager::class);
        Livewire::component('admin.question-manager', \App\Http\Livewire\Admin\QuestionManager::class);
    }
}
