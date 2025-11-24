# Laravel Quiz Application

A comprehensive quiz application built with Laravel 10 and Livewire 3, featuring an admin panel, real-time timer, scoring system, and dynamic question management.

## Features

### Core Functionality
- ✅ **Complete Quiz System** with multiple-choice questions
- ✅ **Admin Panel** for managing quizzes and questions
- ✅ **Real-time Timer** with countdown functionality
- ✅ **Scoring System** with points calculation
- ✅ **User Authentication** with role-based access (Admin/User)
- ✅ **Livewire Components** for reactive UI without page reloads
- ✅ **Dynamic & Reusable** architecture with traits

### Admin Features
- Create, edit, and delete quizzes
- Manage questions with multiple answers
- Set time limits for quizzes
- Configure pass percentage
- Set point values for each question
- Activate/deactivate quizzes

### User Features
- Browse available quizzes
- Take quizzes with real-time timer
- Navigate between questions
- Visual progress tracking
- Instant score calculation
- Pass/fail indication based on configured percentage

### Technical Features
- **Reusable Traits**: `Scorable` and `Timeable` for shared functionality
- **Dynamic Blade Templates**: Easily customizable views
- **Responsive Design**: Tailwind CSS for mobile-friendly UI
- **Database Relations**: Well-structured Eloquent relationships
- **Form Validation**: Server-side validation with error handling

## Requirements

- PHP 8.1 or higher
- Composer
- SQLite (or MySQL/PostgreSQL)
- Node.js & NPM (optional, for asset compilation)

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/Temian1/laravel-quiz.git
cd laravel-quiz
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
Create SQLite database:
```bash
mkdir -p database
touch database/database.sqlite
```

Or configure MySQL/PostgreSQL in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_quiz
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate --seed
```

This will create:
- Admin user: `admin@example.com` / `password`
- Regular user: `user@example.com` / `password`
- Two sample quizzes with questions

### 6. Start the Application
```bash
php artisan serve
```

Visit: http://localhost:8000

## Usage

### Admin Panel

**Login as Admin:**
- Email: `admin@example.com`
- Password: `password`

**Note**: In a production environment, you should implement a proper authentication system with login/register pages. This demo assumes users are already authenticated. To add authentication UI, consider using Laravel Breeze or Jetstream.

**Managing Quizzes:**
1. Navigate to `/admin` 
2. Create a new quiz by filling in:
   - Title (required)
   - Description (optional)
   - Time Limit in minutes (optional)
   - Pass Percentage (required, 0-100)
   - Active status
3. Click "Create Quiz"

**Managing Questions:**
1. From the quiz list, click "Questions" for any quiz
2. Add a new question by entering:
   - Question text (required)
   - Points value (required)
   - At least 2 answers
   - Check the box(es) for correct answer(s)
3. Click "Add Question"

### Taking Quizzes

**Login as User:**
- Email: `user@example.com`
- Password: `password`

**Taking a Quiz:**
1. Browse available quizzes on the homepage
2. Click "Start Quiz"
3. Answer questions by selecting radio buttons
4. Use "Next" and "Previous" to navigate
5. Click numbered buttons to jump to specific questions
6. Click "Submit Quiz" on the last question
7. View your results with score, percentage, and time taken

## Architecture

### Database Schema

**Tables:**
- `users` - User accounts with roles
- `quizzes` - Quiz configurations
- `questions` - Quiz questions with points
- `answers` - Question answers (correct/incorrect)
- `quiz_attempts` - User quiz attempts with scores
- `user_answers` - Individual question answers

### Traits

**Scorable Trait** (`app/Traits/Scorable.php`):
- `calculateScore()` - Calculate total score from answers
- `hasPassed()` - Check if attempt passed based on percentage

**Timeable Trait** (`app/Traits/Timeable.php`):
- `formatTimeRemaining()` - Format seconds to MM:SS
- `isTimeExpired()` - Check if time limit exceeded
- `getRemainingTime()` - Get remaining seconds
- `calculateTimeTaken()` - Calculate elapsed time

### Livewire Components

**User Components:**
- `QuizList` - Display available quizzes
- `TakeQuiz` - Interactive quiz taking interface with timer

**Admin Components:**
- `QuizManager` - CRUD operations for quizzes
- `QuestionManager` - CRUD operations for questions

### Models

All models include proper relationships:
- `User` → `QuizAttempt` (one-to-many)
- `Quiz` → `Question` (one-to-many)
- `Quiz` → `QuizAttempt` (one-to-many)
- `Question` → `Answer` (one-to-many)
- `QuizAttempt` → `UserAnswer` (one-to-many)

## Customization

### Modify Blade Templates

All views are in `resources/views/`:
- `layouts/app.blade.php` - Main layout
- `livewire/quiz-list.blade.php` - Quiz listing
- `livewire/take-quiz.blade.php` - Quiz taking interface
- `livewire/admin/quiz-manager.blade.php` - Admin quiz management
- `livewire/admin/question-manager.blade.php` - Admin question management

### Configuration

Edit `config/quiz.php` for default settings:
```php
'default_time_limit' => 30, // minutes
'default_pass_percentage' => 70,
'allow_review_after_completion' => true,
'show_correct_answers_after_completion' => false,
'max_attempts_per_quiz' => null, // null for unlimited
```

### Styling

The application uses Tailwind CSS via CDN for quick setup. For production:

**Option 1: Install Tailwind locally (Recommended for Production)**
```bash
npm install
npm run build
```

**Option 2: Add SRI attributes for CDN**
The views include integrity checks for CDN resources. For production, consider:
- Using local installations of Tailwind CSS and Alpine.js
- Setting up proper asset versioning
- Implementing Content Security Policy (CSP)

To customize:
1. Install Tailwind locally: `npm install -D tailwindcss`
2. Create `tailwind.config.js`
3. Modify `resources/css/app.css`
4. Update `resources/views/layouts/app.blade.php` to use compiled assets

## API Endpoints

### Routes
- `GET /` - Homepage with quiz list
- `GET /quiz/{id}` - Take quiz
- `GET /admin` - Admin quiz management (requires admin)
- `GET /admin/quiz/{id}/questions` - Question management (requires admin)

## Security Features

- Role-based access control (Admin/User)
- Admin middleware protection
- CSRF protection on forms
- SQL injection prevention via Eloquent
- XSS protection via Blade templating
- Password hashing with bcrypt

## Testing

Run tests:
```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is open-sourced under the MIT license.

## Support

For issues and questions:
- Open an issue on GitHub
- Email: admin@example.com

## Acknowledgments

- Built with [Laravel](https://laravel.com)
- UI powered by [Livewire](https://livewire.laravel.com)
- Styled with [Tailwind CSS](https://tailwindcss.com)

---

**Made with ❤️ for the Laravel community**