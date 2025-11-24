# Contributing to Laravel Quiz

Thank you for considering contributing to the Laravel Quiz application! This document outlines the process and guidelines for contributing.

## Getting Started

1. Fork the repository
2. Clone your fork: `git clone https://github.com/YOUR_USERNAME/laravel-quiz.git`
3. Create a feature branch: `git checkout -b feature/your-feature-name`
4. Make your changes
5. Test your changes
6. Commit with clear messages
7. Push to your fork
8. Create a Pull Request

## Development Setup

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create database
touch database/database.sqlite

# Run migrations and seeders
php artisan migrate --seed

# Start development server
php artisan serve
```

## Code Style

This project follows the PSR-12 coding standard. Please ensure your code adheres to these standards.

### Running Code Style Checks

```bash
# Using Laravel Pint
./vendor/bin/pint
```

## Testing

All new features should include tests. We use PHPUnit for testing.

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter QuizTest

# Run with coverage
php artisan test --coverage
```

### Test Structure

- **Unit Tests**: Place in `tests/Unit/`
- **Feature Tests**: Place in `tests/Feature/`
- **Integration Tests**: Also in `tests/Feature/`

### Writing Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_example_feature(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get('/');

        // Assert
        $response->assertStatus(200);
    }
}
```

## Pull Request Guidelines

### Before Submitting

- [ ] Code follows PSR-12 standards
- [ ] All tests pass
- [ ] New features include tests
- [ ] Documentation is updated
- [ ] Commit messages are clear
- [ ] No debugging code (console.log, dd(), dump())
- [ ] No commented-out code

### PR Description Template

```markdown
## Description
Brief description of what this PR does

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
Describe how you tested these changes

## Screenshots (if applicable)
Add screenshots for UI changes

## Checklist
- [ ] Tests added/updated
- [ ] Documentation updated
- [ ] Code style follows guidelines
```

## Feature Requests

We welcome feature requests! Please:

1. Check existing issues first
2. Create a new issue with the `enhancement` label
3. Clearly describe the feature
4. Explain the use case
5. Provide examples if possible

## Bug Reports

When reporting bugs, please include:

1. Laravel version
2. PHP version
3. Steps to reproduce
4. Expected behavior
5. Actual behavior
6. Error messages/logs
7. Screenshots if applicable

## Code Review Process

1. At least one maintainer review required
2. All tests must pass
3. Code style checks must pass
4. No merge conflicts
5. Documentation must be updated

## Commit Messages

Use clear, descriptive commit messages:

### Good Examples
```
Add timer pause functionality to quiz interface
Fix incorrect score calculation for partial answers
Update README with deployment instructions
```

### Bad Examples
```
Update code
Fix bug
Changes
```

### Commit Message Format

```
<type>: <subject>

<body>

<footer>
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation
- `style`: Code style changes
- `refactor`: Code refactoring
- `test`: Test changes
- `chore`: Build/tooling changes

## Areas Where We Need Help

- [ ] Multiple choice questions (select multiple)
- [ ] Question randomization
- [ ] Quiz categories
- [ ] Export quiz results to PDF
- [ ] Email notifications
- [ ] Leaderboard functionality
- [ ] Mobile app API
- [ ] Dark mode
- [ ] Internationalization (i18n)
- [ ] Accessibility improvements

## Documentation

When adding features, please update:

1. `README.md` - If it affects setup or usage
2. `ARCHITECTURE.md` - If it changes the architecture
3. Inline code comments - For complex logic
4. PHPDoc blocks - For all public methods

## Questions?

If you have questions:

1. Check existing documentation
2. Search closed issues
3. Ask in a new issue with `question` label
4. Contact maintainers

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Recognition

Contributors will be:
- Listed in the repository contributors
- Mentioned in release notes for significant contributions
- Given credit in the README for major features

Thank you for contributing! 🎉
