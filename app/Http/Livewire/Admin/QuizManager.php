<?php

namespace App\Http\Livewire\Admin;

use App\Models\Quiz;
use Livewire\Component;

class QuizManager extends Component
{
    public $quizzes;
    public $quiz;
    public $title;
    public $description;
    public $time_limit;
    public $pass_percentage = 70;
    public $is_active = true;
    public $isEditing = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'time_limit' => 'nullable|integer|min:1',
        'pass_percentage' => 'required|integer|min:0|max:100',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadQuizzes();
    }

    public function loadQuizzes()
    {
        $this->quizzes = Quiz::withCount('questions')->latest()->get();
    }

    public function createQuiz()
    {
        $this->validate();

        Quiz::create([
            'title' => $this->title,
            'description' => $this->description,
            'time_limit' => $this->time_limit,
            'pass_percentage' => $this->pass_percentage,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        $this->loadQuizzes();
        
        session()->flash('message', 'Quiz created successfully!');
    }

    public function editQuiz($quizId)
    {
        $this->quiz = Quiz::findOrFail($quizId);
        $this->title = $this->quiz->title;
        $this->description = $this->quiz->description;
        $this->time_limit = $this->quiz->time_limit;
        $this->pass_percentage = $this->quiz->pass_percentage;
        $this->is_active = $this->quiz->is_active;
        $this->isEditing = true;
    }

    public function updateQuiz()
    {
        $this->validate();

        $this->quiz->update([
            'title' => $this->title,
            'description' => $this->description,
            'time_limit' => $this->time_limit,
            'pass_percentage' => $this->pass_percentage,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        $this->loadQuizzes();
        
        session()->flash('message', 'Quiz updated successfully!');
    }

    public function deleteQuiz($quizId)
    {
        Quiz::findOrFail($quizId)->delete();
        $this->loadQuizzes();
        
        session()->flash('message', 'Quiz deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'time_limit', 'quiz', 'isEditing']);
        $this->pass_percentage = 70;
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.admin.quiz-manager');
    }
}
