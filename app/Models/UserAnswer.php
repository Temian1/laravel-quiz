<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'answer_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Quiz attempt relationship
     */
    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    /**
     * Question relationship
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Answer relationship
     */
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
