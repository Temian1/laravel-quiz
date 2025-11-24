<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create sample quiz
        $quiz = Quiz::create([
            'title' => 'Laravel Basics Quiz',
            'description' => 'Test your knowledge of Laravel framework basics',
            'time_limit' => 10,
            'pass_percentage' => 70,
            'is_active' => true,
        ]);

        // Create questions
        $question1 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'What is Laravel?',
            'points' => 1,
            'order' => 1,
        ]);

        Answer::create([
            'question_id' => $question1->id,
            'answer_text' => 'A PHP framework',
            'is_correct' => true,
        ]);

        Answer::create([
            'question_id' => $question1->id,
            'answer_text' => 'A JavaScript library',
            'is_correct' => false,
        ]);

        Answer::create([
            'question_id' => $question1->id,
            'answer_text' => 'A database system',
            'is_correct' => false,
        ]);

        Answer::create([
            'question_id' => $question1->id,
            'answer_text' => 'A CSS framework',
            'is_correct' => false,
        ]);

        $question2 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Which of the following is a Laravel feature?',
            'points' => 2,
            'order' => 2,
        ]);

        Answer::create([
            'question_id' => $question2->id,
            'answer_text' => 'Eloquent ORM',
            'is_correct' => true,
        ]);

        Answer::create([
            'question_id' => $question2->id,
            'answer_text' => 'React Components',
            'is_correct' => false,
        ]);

        Answer::create([
            'question_id' => $question2->id,
            'answer_text' => 'Angular Directives',
            'is_correct' => false,
        ]);

        $question3 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'What command is used to run migrations in Laravel?',
            'points' => 1,
            'order' => 3,
        ]);

        Answer::create([
            'question_id' => $question3->id,
            'answer_text' => 'php artisan migrate',
            'is_correct' => true,
        ]);

        Answer::create([
            'question_id' => $question3->id,
            'answer_text' => 'php artisan db:migrate',
            'is_correct' => false,
        ]);

        Answer::create([
            'question_id' => $question3->id,
            'answer_text' => 'php migrate',
            'is_correct' => false,
        ]);

        // Create another quiz
        $quiz2 = Quiz::create([
            'title' => 'PHP Fundamentals',
            'description' => 'Test your PHP programming knowledge',
            'time_limit' => 15,
            'pass_percentage' => 60,
            'is_active' => true,
        ]);

        $question4 = Question::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'What does PHP stand for?',
            'points' => 1,
            'order' => 1,
        ]);

        Answer::create([
            'question_id' => $question4->id,
            'answer_text' => 'PHP: Hypertext Preprocessor',
            'is_correct' => true,
        ]);

        Answer::create([
            'question_id' => $question4->id,
            'answer_text' => 'Personal Home Page',
            'is_correct' => false,
        ]);

        Answer::create([
            'question_id' => $question4->id,
            'answer_text' => 'Private Home Page',
            'is_correct' => false,
        ]);

        $question5 = Question::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'Which operator is used for concatenation in PHP?',
            'points' => 2,
            'order' => 2,
        ]);

        Answer::create([
            'question_id' => $question5->id,
            'answer_text' => '. (dot)',
            'is_correct' => true,
        ]);

        Answer::create([
            'question_id' => $question5->id,
            'answer_text' => '+ (plus)',
            'is_correct' => false,
        ]);

        Answer::create([
            'question_id' => $question5->id,
            'answer_text' => '& (ampersand)',
            'is_correct' => false,
        ]);
    }
}
