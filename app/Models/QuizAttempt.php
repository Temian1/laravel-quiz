<?php

namespace App\Models;

use App\Traits\Scorable;
use App\Traits\Timeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory, Scorable, Timeable;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'total_points',
        'percentage',
        'started_at',
        'completed_at',
        'time_taken',
        'passed',
    ];

    protected $casts = [
        'score' => 'integer',
        'total_points' => 'integer',
        'percentage' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_taken' => 'integer',
        'passed' => 'boolean',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quiz relationship
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * User answers relationship
     */
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    /**
     * Check if attempt is in progress
     */
    public function isInProgress(): bool
    {
        return $this->started_at && !$this->completed_at;
    }

    /**
     * Check if attempt is completed
     */
    public function isCompleted(): bool
    {
        return (bool) $this->completed_at;
    }
}
