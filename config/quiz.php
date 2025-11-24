<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Quiz Configuration
    |--------------------------------------------------------------------------
    */

    'default_time_limit' => 30, // minutes
    'default_pass_percentage' => 70,
    'allow_review_after_completion' => true,
    'show_correct_answers_after_completion' => false,
    'max_attempts_per_quiz' => null, // null for unlimited
    'randomize_questions' => false,
    'randomize_answers' => false,
];
