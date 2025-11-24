#!/bin/bash

# Laravel Quiz Application - Installation Verification Script
# This script verifies that the application is properly set up

echo "=========================================="
echo "Laravel Quiz - Installation Verification"
echo "=========================================="
echo ""

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check PHP version
echo "Checking PHP version..."
PHP_VERSION=$(php -r "echo PHP_VERSION;")
PHP_REQUIRED="8.1"
if [ "$(printf '%s\n' "$PHP_REQUIRED" "$PHP_VERSION" | sort -V | head -n1)" = "$PHP_REQUIRED" ]; then
    echo -e "${GREEN}✓ PHP version $PHP_VERSION is compatible${NC}"
else
    echo -e "${RED}✗ PHP version $PHP_VERSION is not compatible. Required: $PHP_REQUIRED or higher${NC}"
    exit 1
fi

# Check if .env exists
echo ""
echo "Checking environment configuration..."
if [ -f ".env" ]; then
    echo -e "${GREEN}✓ .env file exists${NC}"
else
    echo -e "${YELLOW}⚠ .env file not found. Copying from .env.example...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✓ .env file created${NC}"
fi

# Check if APP_KEY is set
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ]; then
    echo -e "${YELLOW}⚠ APP_KEY not set. Generating key...${NC}"
    php artisan key:generate
    echo -e "${GREEN}✓ APP_KEY generated${NC}"
else
    echo -e "${GREEN}✓ APP_KEY is set${NC}"
fi

# Check if vendor directory exists
echo ""
echo "Checking dependencies..."
if [ -d "vendor" ]; then
    echo -e "${GREEN}✓ Composer dependencies installed${NC}"
else
    echo -e "${YELLOW}⚠ Composer dependencies not found. Run 'composer install'${NC}"
fi

# Check database file
echo ""
echo "Checking database..."
DB_CONNECTION=$(grep "^DB_CONNECTION=" .env | cut -d '=' -f2)
if [ "$DB_CONNECTION" = "sqlite" ]; then
    DB_PATH="database/database.sqlite"
    if [ -f "$DB_PATH" ]; then
        echo -e "${GREEN}✓ SQLite database file exists${NC}"
    else
        echo -e "${YELLOW}⚠ SQLite database file not found. Creating...${NC}"
        touch "$DB_PATH"
        echo -e "${GREEN}✓ SQLite database file created${NC}"
    fi
fi

# Check migrations
echo ""
echo "Checking migrations..."
if php artisan migrate:status >/dev/null 2>&1; then
    echo -e "${GREEN}✓ Database migrations are up to date${NC}"
else
    echo -e "${YELLOW}⚠ Database migrations not run. Run 'php artisan migrate --seed'${NC}"
fi

# Check required directories
echo ""
echo "Checking directory structure..."
REQUIRED_DIRS=(
    "storage/app"
    "storage/framework/cache"
    "storage/framework/sessions"
    "storage/framework/views"
    "storage/logs"
    "bootstrap/cache"
)

for dir in "${REQUIRED_DIRS[@]}"; do
    if [ -d "$dir" ]; then
        echo -e "${GREEN}✓ $dir exists${NC}"
    else
        echo -e "${YELLOW}⚠ Creating $dir...${NC}"
        mkdir -p "$dir"
        echo -e "${GREEN}✓ $dir created${NC}"
    fi
done

# Check write permissions
echo ""
echo "Checking write permissions..."
WRITABLE_DIRS=("storage" "bootstrap/cache")
for dir in "${WRITABLE_DIRS[@]}"; do
    if [ -w "$dir" ]; then
        echo -e "${GREEN}✓ $dir is writable${NC}"
    else
        echo -e "${RED}✗ $dir is not writable. Run 'chmod -R 775 $dir'${NC}"
    fi
done

echo ""
echo "=========================================="
echo "Verification Complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Run 'php artisan migrate --seed' to set up the database"
echo "2. Run 'php artisan serve' to start the development server"
echo "3. Visit http://localhost:8000 in your browser"
echo "4. Login as admin with: admin@example.com / password"
echo ""
echo "For production deployment, see README.md"
echo ""
