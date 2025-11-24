<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'points',
        'order',
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Quiz relationship
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Answers relationship
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * User answers relationship
     */
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    /**
     * Get correct answer
     */
    public function getCorrectAnswerAttribute()
    {
        return $this->answers()->where('is_correct', true)->first();
    }
}
