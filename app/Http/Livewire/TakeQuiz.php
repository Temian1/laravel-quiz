<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Livewire\Component;

class TakeQuiz extends Component
{
    public $quiz;
    public $attempt;
    public $questions;
    public $currentQuestionIndex = 0;
    public $userAnswers = [];
    public $timeRemaining;
    public $quizCompleted = false;
    public $showResults = false;

    protected $listeners = ['timeExpired'];

    public function mount($quizId)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            abort(403, 'You must be logged in to take a quiz.');
        }

        $this->quiz = Quiz::with('questions.answers')->findOrFail($quizId);
        $this->questions = $this->quiz->questions;

        // Check for existing incomplete attempt
        $this->attempt = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $this->quiz->id)
            ->whereNull('completed_at')
            ->first();

        if (!$this->attempt) {
            // Create new attempt
            $this->attempt = QuizAttempt::create([
                'user_id' => auth()->id(),
                'quiz_id' => $this->quiz->id,
                'started_at' => now(),
            ]);
        }

        // Load existing answers
        $existingAnswers = UserAnswer::where('quiz_attempt_id', $this->attempt->id)
            ->get()
            ->keyBy('question_id');

        foreach ($existingAnswers as $questionId => $userAnswer) {
            $this->userAnswers[$questionId] = $userAnswer->answer_id;
        }

        $this->calculateTimeRemaining();
    }

    public function calculateTimeRemaining()
    {
        if ($this->quiz->time_limit && $this->attempt->started_at) {
            $this->timeRemaining = $this->attempt->getRemainingTime(
                $this->attempt->started_at,
                $this->quiz->time_limit
            );
        }
    }

    public function selectAnswer($questionId, $answerId)
    {
        $this->userAnswers[$questionId] = $answerId;

        // Save answer
        UserAnswer::updateOrCreate(
            [
                'quiz_attempt_id' => $this->attempt->id,
                'question_id' => $questionId,
            ],
            [
                'answer_id' => $answerId,
                'is_correct' => $this->isAnswerCorrect($questionId, $answerId),
            ]
        );
    }

    protected function isAnswerCorrect($questionId, $answerId)
    {
        $question = $this->questions->firstWhere('id', $questionId);
        $correctAnswer = $question->answers->firstWhere('is_correct', true);
        
        return $correctAnswer && $correctAnswer->id == $answerId;
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function goToQuestion($index)
    {
        $this->currentQuestionIndex = $index;
    }

    public function submitQuiz()
    {
        $this->completeQuiz();
    }

    public function timeExpired()
    {
        $this->completeQuiz();
    }

    protected function completeQuiz()
    {
        if ($this->quizCompleted) {
            return;
        }

        $this->quizCompleted = true;

        // Calculate score
        $result = $this->attempt->calculateScore($this->userAnswers, $this->questions);

        // Update attempt
        $this->attempt->update([
            'score' => $result['score'],
            'total_points' => $result['total_points'],
            'percentage' => $result['percentage'],
            'completed_at' => now(),
            'time_taken' => $this->attempt->calculateTimeTaken($this->attempt->started_at),
            'passed' => $this->attempt->hasPassed($result['percentage'], $this->quiz->pass_percentage),
        ]);

        $this->showResults = true;
    }

    public function render()
    {
        $currentQuestion = $this->questions[$this->currentQuestionIndex] ?? null;

        return view('livewire.take-quiz', [
            'currentQuestion' => $currentQuestion,
            'totalQuestions' => count($this->questions),
            'progress' => count($this->questions) > 0 
                ? (($this->currentQuestionIndex + 1) / count($this->questions)) * 100 
                : 0,
        ]);
    }
}
