# API Documentation (Future Implementation)

This document outlines potential API endpoints that could be added to the Laravel Quiz application for mobile apps or third-party integrations.

## Authentication

All API requests require authentication using Laravel Sanctum tokens.

### Get Token
```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}

Response:
{
  "token": "1|xxxx...",
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com",
    "role": "user"
  }
}
```

## Quizzes

### List Quizzes
```http
GET /api/quizzes
Authorization: Bearer {token}

Response:
{
  "data": [
    {
      "id": 1,
      "title": "Laravel Basics",
      "description": "Test your Laravel knowledge",
      "time_limit": 10,
      "pass_percentage": 70,
      "total_questions": 5,
      "total_points": 10
    }
  ]
}
```

### Get Quiz Details
```http
GET /api/quizzes/{id}
Authorization: Bearer {token}

Response:
{
  "id": 1,
  "title": "Laravel Basics",
  "description": "Test your Laravel knowledge",
  "time_limit": 10,
  "pass_percentage": 70,
  "questions": [
    {
      "id": 1,
      "question_text": "What is Laravel?",
      "points": 2,
      "answers": [
        {
          "id": 1,
          "answer_text": "A PHP Framework"
        },
        {
          "id": 2,
          "answer_text": "A JavaScript Library"
        }
      ]
    }
  ]
}
```

## Quiz Attempts

### Start Quiz
```http
POST /api/quizzes/{id}/start
Authorization: Bearer {token}

Response:
{
  "attempt_id": 1,
  "started_at": "2024-01-01T12:00:00Z",
  "expires_at": "2024-01-01T12:10:00Z"
}
```

### Submit Answer
```http
POST /api/attempts/{attempt_id}/answers
Authorization: Bearer {token}
Content-Type: application/json

{
  "question_id": 1,
  "answer_id": 1
}

Response:
{
  "success": true,
  "message": "Answer recorded"
}
```

### Submit Quiz
```http
POST /api/attempts/{attempt_id}/submit
Authorization: Bearer {token}

Response:
{
  "score": 8,
  "total_points": 10,
  "percentage": 80.0,
  "passed": true,
  "time_taken": 480,
  "correct_answers": 4,
  "total_questions": 5
}
```

### Get Attempt Results
```http
GET /api/attempts/{attempt_id}
Authorization: Bearer {token}

Response:
{
  "id": 1,
  "quiz": {
    "id": 1,
    "title": "Laravel Basics"
  },
  "score": 8,
  "total_points": 10,
  "percentage": 80.0,
  "passed": true,
  "started_at": "2024-01-01T12:00:00Z",
  "completed_at": "2024-01-01T12:08:00Z",
  "time_taken": 480
}
```

## User Stats

### Get User Quiz History
```http
GET /api/user/attempts
Authorization: Bearer {token}

Response:
{
  "data": [
    {
      "id": 1,
      "quiz_title": "Laravel Basics",
      "score": 8,
      "total_points": 10,
      "percentage": 80.0,
      "passed": true,
      "completed_at": "2024-01-01T12:08:00Z"
    }
  ],
  "stats": {
    "total_attempts": 10,
    "passed_attempts": 7,
    "average_score": 75.5
  }
}
```

## Admin Endpoints

### Create Quiz
```http
POST /api/admin/quizzes
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "New Quiz",
  "description": "Quiz description",
  "time_limit": 15,
  "pass_percentage": 70,
  "is_active": true
}

Response:
{
  "id": 2,
  "title": "New Quiz",
  "created_at": "2024-01-01T12:00:00Z"
}
```

### Create Question
```http
POST /api/admin/quizzes/{quiz_id}/questions
Authorization: Bearer {token}
Content-Type: application/json

{
  "question_text": "What is PHP?",
  "points": 2,
  "answers": [
    {
      "answer_text": "A programming language",
      "is_correct": true
    },
    {
      "answer_text": "A database",
      "is_correct": false
    }
  ]
}

Response:
{
  "id": 10,
  "question_text": "What is PHP?",
  "created_at": "2024-01-01T12:00:00Z"
}
```

### Get Quiz Statistics
```http
GET /api/admin/quizzes/{id}/stats
Authorization: Bearer {token}

Response:
{
  "quiz_id": 1,
  "total_attempts": 100,
  "average_score": 75.5,
  "pass_rate": 70.0,
  "average_time": 420,
  "popular_questions": [
    {
      "question_id": 1,
      "question_text": "What is Laravel?",
      "correct_rate": 85.0
    }
  ]
}
```

## Error Responses

### 400 Bad Request
```json
{
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized"
}
```

### 404 Not Found
```json
{
  "message": "Resource not found"
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

## Rate Limiting

- 60 requests per minute for authenticated users
- 10 requests per minute for unauthenticated users

## Implementation Notes

To implement these endpoints:

1. Create API controllers in `app/Http/Controllers/Api/`
2. Add routes in `routes/api.php`
3. Use API Resources for response formatting
4. Implement proper validation
5. Add rate limiting middleware
6. Document with OpenAPI/Swagger
7. Add API tests

## Example Implementation

```php
// app/Http/Controllers/Api/QuizController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::active()->with('questions')->get();
        return QuizResource::collection($quizzes);
    }

    public function show(Quiz $quiz)
    {
        return new QuizResource($quiz->load('questions.answers'));
    }
}
```

## Security Considerations

- Use HTTPS only
- Implement proper CORS policies
- Validate all input data
- Rate limit requests
- Log all API access
- Use token expiration
- Implement token refresh mechanism
