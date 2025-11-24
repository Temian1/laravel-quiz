# Laravel Quiz Application - Architecture Overview

## System Architecture

### 1. Database Structure

```
users
├── id
├── name
├── email
├── password
└── role (admin/user)

quizzes
├── id
├── title
├── description
├── time_limit (minutes)
├── pass_percentage
└── is_active

questions
├── id
├── quiz_id (FK → quizzes)
├── question_text
├── points
└── order

answers
├── id
├── question_id (FK → questions)
├── answer_text
└── is_correct

quiz_attempts
├── id
├── user_id (FK → users)
├── quiz_id (FK → quizzes)
├── score
├── total_points
├── percentage
├── started_at
├── completed_at
├── time_taken
└── passed

user_answers
├── id
├── quiz_attempt_id (FK → quiz_attempts)
├── question_id (FK → questions)
├── answer_id (FK → answers)
└── is_correct
```

### 2. Component Structure

#### Livewire Components

**User-Facing Components:**
- `QuizList` - Displays available quizzes with search
- `TakeQuiz` - Interactive quiz interface with:
  - Real-time timer countdown
  - Question navigation
  - Answer selection
  - Progress tracking
  - Score calculation

**Admin Components:**
- `QuizManager` - CRUD operations for quizzes
- `QuestionManager` - CRUD operations for questions and answers

### 3. Reusable Traits

#### Scorable Trait
```php
- calculateScore(array $userAnswers, $questions): array
- hasPassed($percentage, $passPercentage): bool
```

#### Timeable Trait
```php
- formatTimeRemaining($seconds): string
- isTimeExpired($startedAt, $timeLimit): bool
- getRemainingTime($startedAt, $timeLimit): int
- calculateTimeTaken($startedAt, $completedAt): int
```

### 4. Data Flow

#### Taking a Quiz

```
1. User selects quiz → TakeQuiz component loads
2. QuizAttempt created with started_at timestamp
3. Timer starts countdown (if time_limit set)
4. User selects answers → UserAnswer records created
5. User submits or timer expires
6. Scorable trait calculates final score
7. QuizAttempt updated with results
8. Results displayed to user
```

#### Admin Quiz Management

```
1. Admin creates quiz → Quiz record created
2. Admin adds questions → Question records created
3. Admin adds answers → Answer records created
4. Mark correct answer(s) → is_correct flag set
5. Quiz activated → is_active set to true
6. Quiz appears on user's homepage
```

### 5. Security Features

- **Authentication**: Laravel Sanctum
- **Authorization**: 
  - AdminMiddleware for admin routes
  - User->isAdmin() method
- **CSRF Protection**: Livewire forms
- **SQL Injection**: Eloquent ORM
- **XSS Prevention**: Blade templating

### 6. Key Features

#### Timer System
- JavaScript countdown using Alpine.js
- Auto-submit on expiration
- Persistent across page refresh via server-side tracking

#### Scoring System
- Point-based questions
- Percentage calculation
- Pass/fail determination
- Detailed results display

#### Dynamic UI
- Real-time updates without page reload
- Question navigation dots
- Progress bar
- Visual feedback on selections

### 7. Customization Points

#### Blade Templates
All views are fully customizable:
- `resources/views/layouts/app.blade.php`
- `resources/views/livewire/*.blade.php`

#### Configuration
`config/quiz.php` allows setting:
- Default time limits
- Pass percentages
- Answer display options
- Attempt limits

#### Styling
Using Tailwind CSS via CDN:
- Easy to modify colors and layouts
- Responsive by default
- Can switch to local Tailwind

### 8. Extension Points

To add new features:

**Question Types:**
1. Create new answer type in migration
2. Update Question model
3. Add UI in question-manager.blade.php
4. Update TakeQuiz component logic

**Quiz Settings:**
1. Add column to quizzes migration
2. Update Quiz model fillable
3. Add field to quiz-manager form
4. Use in TakeQuiz component

**Reporting:**
1. Create new QuizAttempt relationships
2. Add admin dashboard component
3. Create statistics views
4. Use existing Scorable trait methods

## File Organization

```
app/
├── Http/
│   ├── Livewire/
│   │   ├── Admin/
│   │   │   ├── QuizManager.php
│   │   │   └── QuestionManager.php
│   │   ├── QuizList.php
│   │   └── TakeQuiz.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── User.php
│   ├── Quiz.php
│   ├── Question.php
│   ├── Answer.php
│   ├── QuizAttempt.php
│   └── UserAnswer.php
└── Traits/
    ├── Scorable.php
    └── Timeable.php

resources/views/
├── layouts/
│   └── app.blade.php
└── livewire/
    ├── admin/
    │   ├── quiz-manager.blade.php
    │   └── question-manager.blade.php
    ├── quiz-list.blade.php
    └── take-quiz.blade.php
```

## Testing Strategy

### Unit Tests
- Test Scorable trait methods
- Test Timeable trait methods
- Test model relationships

### Feature Tests
- Test quiz list display
- Test quiz taking flow
- Test admin CRUD operations
- Test authorization

### Integration Tests
- Test complete quiz flow
- Test timer expiration
- Test score calculation
- Test admin workflow

## Performance Considerations

1. **Eager Loading**: Questions with answers loaded together
2. **Caching**: Quiz list can be cached
3. **Database Indexing**: Foreign keys indexed automatically
4. **Livewire Optimization**: Components use wire:model.live sparingly

## Deployment

1. Set up environment variables
2. Run migrations: `php artisan migrate`
3. Seed database: `php artisan db:seed`
4. Set proper permissions on storage/
5. Configure web server (Apache/Nginx)
6. Enable PHP opcache
7. Queue jobs if adding email notifications

## Future Enhancements

Potential features to add:
- Multiple choice (select multiple answers)
- True/False questions
- Fill-in-the-blank questions
- Image-based questions
- Quiz categories/tags
- Leaderboards
- Email notifications
- PDF certificates
- Question randomization
- Answer randomization
- Quiz retake limits
- Time-based availability
- Detailed analytics
