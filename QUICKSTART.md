# Quick Start Guide - Laravel Quiz Application

This is a quick reference guide to get you started with the Laravel Quiz application in 5 minutes.

## Prerequisites Check
- [ ] PHP 8.1+ installed
- [ ] Composer installed
- [ ] Git installed

## Installation (5 Steps)

### Step 1: Clone the Repository
```bash
git clone https://github.com/Temian1/laravel-quiz.git
cd laravel-quiz
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Setup Database
```bash
# For SQLite (easiest)
touch database/database.sqlite

# For MySQL, update .env:
# DB_CONNECTION=mysql
# DB_DATABASE=your_database
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

### Step 5: Run Migrations & Seed
```bash
php artisan migrate --seed
php artisan serve
```

## Access the Application

🌐 **URL:** http://localhost:8000

### Default Credentials

**Admin Account:**
- Email: `admin@example.com`
- Password: `password`

**User Account:**
- Email: `user@example.com`
- Password: `password`

## What's Included

✅ **2 Sample Quizzes** with questions
✅ **Admin Panel** at `/admin`
✅ **Quiz Taking Interface** with timer
✅ **Scoring System** with pass/fail
✅ **User Dashboard**

## Quick Actions

### As Admin (http://localhost:8000/admin)
1. Create a new quiz
2. Add questions with multiple answers
3. Set time limits and pass percentages
4. Activate/deactivate quizzes

### As User (http://localhost:8000)
1. Browse available quizzes
2. Start a quiz
3. Answer questions
4. View results and score

## Next Steps

📚 Read the full documentation:
- [README.md](README.md) - Complete setup guide
- [ARCHITECTURE.md](ARCHITECTURE.md) - System architecture
- [DEPLOYMENT.md](DEPLOYMENT.md) - Production deployment
- [CONTRIBUTING.md](CONTRIBUTING.md) - Contribution guidelines
- [API.md](API.md) - Future API endpoints

## Features Overview

### For Admins
- ✅ Create/Edit/Delete Quizzes
- ✅ Manage Questions & Answers
- ✅ Set Time Limits
- ✅ Configure Pass Percentages
- ✅ Set Points per Question
- ✅ Activate/Deactivate Quizzes

### For Users
- ✅ Browse Active Quizzes
- ✅ Take Timed Quizzes
- ✅ Navigate Between Questions
- ✅ Real-time Progress Tracking
- ✅ Instant Score Calculation
- ✅ Pass/Fail Indication

### Technical Features
- ✅ Livewire for Reactive UI
- ✅ Reusable Traits (Scorable, Timeable)
- ✅ Dynamic Blade Templates
- ✅ Role-Based Access Control
- ✅ Responsive Design (Tailwind CSS)
- ✅ SQLite/MySQL/PostgreSQL Support

## Troubleshooting

### Database Issues
```bash
# Reset database
php artisan migrate:fresh --seed
```

### Permission Issues
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

### Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Port Already in Use
```bash
# Use different port
php artisan serve --port=8080
```

## Verification Script

Run the installation verification script:
```bash
bash verify-installation.sh
```

This will check:
- ✅ PHP version compatibility
- ✅ Environment configuration
- ✅ Dependencies installation
- ✅ Database setup
- ✅ Directory permissions

## Common Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create new admin user
php artisan tinker
> User::create(['name'=>'Admin','email'=>'admin@test.com','password'=>Hash::make('password'),'role'=>'admin']);

# Run tests
php artisan test

# Clear caches
php artisan optimize:clear
```

## Directory Structure

```
laravel-quiz/
├── app/
│   ├── Http/Livewire/      # Livewire components
│   ├── Models/             # Eloquent models
│   └── Traits/             # Reusable traits
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── resources/views/
│   ├── layouts/            # Layout templates
│   └── livewire/           # Livewire views
├── routes/
│   └── web.php             # Web routes
└── tests/                  # Unit & Feature tests
```

## Getting Help

- 📖 Check [README.md](README.md) for detailed information
- 🏗️ Review [ARCHITECTURE.md](ARCHITECTURE.md) for technical details
- 🚀 See [DEPLOYMENT.md](DEPLOYMENT.md) for production setup
- 🐛 Found a bug? Open an issue on GitHub
- 💡 Have a suggestion? Create a feature request

## What to Do After Installation

1. **Explore Admin Panel**: Login as admin and create your own quiz
2. **Take a Quiz**: Login as user and test the quiz interface
3. **Customize**: Modify Blade templates to match your style
4. **Extend**: Add new features using the traits and components
5. **Deploy**: Follow DEPLOYMENT.md for production setup

---

**Ready to go?** Run `php artisan serve` and visit http://localhost:8000

**Need help?** Check the documentation files in the repository.

**Want to contribute?** Read [CONTRIBUTING.md](CONTRIBUTING.md)
