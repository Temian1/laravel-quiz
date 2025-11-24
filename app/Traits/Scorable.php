<?php

namespace App\Traits;

trait Scorable
{
    /**
     * Calculate the score for a quiz attempt
     */
    public function calculateScore(array $userAnswers, $questions): array
    {
        $score = 0;
        $totalPoints = 0;
        $correctAnswers = 0;
        $totalQuestions = count($questions);

        foreach ($questions as $question) {
            $totalPoints += $question->points;
            
            $userAnswerId = $userAnswers[$question->id] ?? null;
            
            if ($userAnswerId) {
                $correctAnswer = $question->answers()->where('is_correct', true)->first();
                if ($correctAnswer && $correctAnswer->id == $userAnswerId) {
                    $score += $question->points;
                    $correctAnswers++;
                }
            }
        }

        $percentage = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;

        return [
            'score' => $score,
            'total_points' => $totalPoints,
            'percentage' => round($percentage, 2),
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions
        ];
    }

    /**
     * Check if the attempt passed based on pass percentage
     */
    public function hasPassed($percentage, $passPercentage): bool
    {
        return $percentage >= $passPercentage;
    }
}
