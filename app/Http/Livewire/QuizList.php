<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use Livewire\Component;

class QuizList extends Component
{
    public $search = '';

    public function render()
    {
        $quizzes = Quiz::active()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->withCount('questions')
            ->latest()
            ->get();

        return view('livewire.quiz-list', [
            'quizzes' => $quizzes
        ]);
    }
}
