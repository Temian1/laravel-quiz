<?php

namespace App\Http\Livewire\Admin;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Livewire\Component;

class QuestionManager extends Component
{
    public $quiz;
    public $questions;
    public $question;
    public $question_text;
    public $points = 1;
    public $answers = [];
    public $isEditing = false;

    protected $rules = [
        'question_text' => 'required|string',
        'points' => 'required|integer|min:1',
        'answers' => 'required|array|min:2',
        'answers.*.text' => 'required|string',
        'answers.*.is_correct' => 'boolean',
    ];

    public function mount($quizId)
    {
        $this->quiz = Quiz::findOrFail($quizId);
        $this->loadQuestions();
        $this->initializeAnswers();
    }

    public function loadQuestions()
    {
        $this->questions = Question::where('quiz_id', $this->quiz->id)
            ->with('answers')
            ->orderBy('order')
            ->get();
    }

    public function initializeAnswers()
    {
        $this->answers = [
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ];
    }

    public function addAnswer()
    {
        $this->answers[] = ['text' => '', 'is_correct' => false];
    }

    public function removeAnswer($index)
    {
        if (count($this->answers) > 2) {
            unset($this->answers[$index]);
            $this->answers = array_values($this->answers);
        }
    }

    public function createQuestion()
    {
        $this->validate();

        // Ensure at least one correct answer
        $hasCorrect = collect($this->answers)->contains('is_correct', true);
        if (!$hasCorrect) {
            session()->flash('error', 'At least one answer must be marked as correct!');
            return;
        }

        $order = Question::where('quiz_id', $this->quiz->id)->max('order') + 1;

        $question = Question::create([
            'quiz_id' => $this->quiz->id,
            'question_text' => $this->question_text,
            'points' => $this->points,
            'order' => $order,
        ]);

        foreach ($this->answers as $answer) {
            if (!empty($answer['text'])) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answer['text'],
                    'is_correct' => $answer['is_correct'] ?? false,
                ]);
            }
        }

        $this->resetForm();
        $this->loadQuestions();
        
        session()->flash('message', 'Question created successfully!');
    }

    public function editQuestion($questionId)
    {
        $this->question = Question::with('answers')->findOrFail($questionId);
        $this->question_text = $this->question->question_text;
        $this->points = $this->question->points;
        
        $this->answers = $this->question->answers->map(function ($answer) {
            return [
                'id' => $answer->id,
                'text' => $answer->answer_text,
                'is_correct' => $answer->is_correct,
            ];
        })->toArray();

        $this->isEditing = true;
    }

    public function updateQuestion()
    {
        $this->validate();

        $hasCorrect = collect($this->answers)->contains('is_correct', true);
        if (!$hasCorrect) {
            session()->flash('error', 'At least one answer must be marked as correct!');
            return;
        }

        $this->question->update([
            'question_text' => $this->question_text,
            'points' => $this->points,
        ]);

        // Update answers more efficiently
        $existingAnswerIds = $this->question->answers->pluck('id')->toArray();
        $submittedAnswerIds = array_filter(array_column($this->answers, 'id'));
        
        // Delete answers that were removed
        $toDelete = array_diff($existingAnswerIds, $submittedAnswerIds);
        if (!empty($toDelete)) {
            Answer::whereIn('id', $toDelete)->delete();
        }

        // Update or create answers
        foreach ($this->answers as $answer) {
            if (!empty($answer['text'])) {
                if (isset($answer['id'])) {
                    // Update existing answer
                    Answer::where('id', $answer['id'])->update([
                        'answer_text' => $answer['text'],
                        'is_correct' => $answer['is_correct'] ?? false,
                    ]);
                } else {
                    // Create new answer
                    Answer::create([
                        'question_id' => $this->question->id,
                        'answer_text' => $answer['text'],
                        'is_correct' => $answer['is_correct'] ?? false,
                    ]);
                }
            }
        }

        $this->resetForm();
        $this->loadQuestions();
        
        session()->flash('message', 'Question updated successfully!');
    }

    public function deleteQuestion($questionId)
    {
        Question::findOrFail($questionId)->delete();
        $this->loadQuestions();
        
        session()->flash('message', 'Question deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset(['question_text', 'points', 'question', 'isEditing']);
        $this->points = 1;
        $this->initializeAnswers();
    }

    public function render()
    {
        return view('livewire.admin.question-manager');
    }
}
