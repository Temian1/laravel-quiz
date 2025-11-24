<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_quiz_list(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_quiz_displays_correct_information(): void
    {
        $quiz = Quiz::factory()->create([
            'title' => 'Test Quiz',
            'description' => 'Test Description',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('Test Quiz');
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }
}
