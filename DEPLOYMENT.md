# Deployment Guide - Laravel Quiz Application

This guide covers deploying the Laravel Quiz application to production environments.

## Prerequisites

- PHP 8.1 or higher
- Composer
- Web server (Apache/Nginx)
- Database (MySQL, PostgreSQL, or SQLite)
- SSL certificate (recommended)
- Git

## Deployment Methods

### Option 1: Traditional Server (VPS/Dedicated)

#### 1. Server Setup

**Install PHP and extensions:**
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**Install Web Server:**
```bash
# Nginx
sudo apt install nginx

# Apache
sudo apt install apache2 libapache2-mod-php8.1
```

#### 2. Clone Repository

```bash
cd /var/www
sudo git clone https://github.com/Temian1/laravel-quiz.git
cd laravel-quiz
```

#### 3. Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

#### 4. Configure Environment

```bash
cp .env.example .env
nano .env
```

Update `.env` with production values:
```env
APP_NAME="Laravel Quiz"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_quiz
DB_USERNAME=quiz_user
DB_PASSWORD=your_secure_password

# Generate a strong key
php artisan key:generate
```

#### 5. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/laravel-quiz
sudo chmod -R 755 /var/www/laravel-quiz
sudo chmod -R 775 /var/www/laravel-quiz/storage
sudo chmod -R 775 /var/www/laravel-quiz/bootstrap/cache
```

#### 6. Configure Database

```bash
# Create database
mysql -u root -p
CREATE DATABASE laravel_quiz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'quiz_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON laravel_quiz.* TO 'quiz_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force
php artisan db:seed --force
```

#### 7. Configure Nginx

Create `/etc/nginx/sites-available/laravel-quiz`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    
    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/laravel-quiz/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/laravel-quiz /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### 8. Configure Apache

Create `/etc/apache2/sites-available/laravel-quiz.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    
    # Redirect HTTP to HTTPS
    Redirect permanent / https://yourdomain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    
    DocumentRoot /var/www/laravel-quiz/public
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/yourdomain.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/yourdomain.com/privkey.pem
    
    <Directory /var/www/laravel-quiz/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Security Headers
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    
    ErrorLog ${APACHE_LOG_DIR}/laravel-quiz-error.log
    CustomLog ${APACHE_LOG_DIR}/laravel-quiz-access.log combined
</VirtualHost>
```

Enable site and modules:
```bash
sudo a2enmod rewrite ssl headers
sudo a2ensite laravel-quiz
sudo apache2ctl configtest
sudo systemctl restart apache2
```

#### 9. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx

# For Nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# For Apache
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

#### 10. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Option 2: Laravel Forge

1. Connect your server to Forge
2. Create new site: yourdomain.com
3. Set root directory to `/public`
4. Deploy repository from GitHub
5. Set environment variables in Forge UI
6. Run deployment script:

```bash
cd /home/forge/yourdomain.com
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Option 3: Docker

Create `docker-compose.yml`:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "80:80"
    volumes:
      - ./storage:/var/www/html/storage
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    depends_on:
      - db

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: laravel_quiz
      MYSQL_USER: quiz_user
      MYSQL_PASSWORD: password
      MYSQL_ROOT_PASSWORD: root_password
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

Create `Dockerfile`:

```dockerfile
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

Deploy:
```bash
docker-compose up -d
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force
```

## Post-Deployment

### 1. Create Admin User

```bash
php artisan tinker
User::create([
    'name' => 'Admin',
    'email' => 'admin@yourdomain.com',
    'password' => Hash::make('secure_password'),
    'role' => 'admin'
]);
```

### 2. Set Up Backups

**Database backup script:**
```bash
#!/bin/bash
BACKUP_DIR="/backups/laravel-quiz"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

# MySQL
mysqldump -u quiz_user -p laravel_quiz > $BACKUP_DIR/db_$DATE.sql

# Compress
gzip $BACKUP_DIR/db_$DATE.sql

# Keep only last 7 days
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +7 -delete
```

Add to crontab:
```bash
0 2 * * * /path/to/backup-script.sh
```

### 3. Monitoring

**Setup monitoring tools:**
- Application monitoring: New Relic, DataDog
- Uptime monitoring: UptimeRobot, Pingdom
- Error tracking: Sentry, Bugsnag
- Log management: Papertrail, Loggly

### 4. Performance Optimization

```bash
# Enable OPcache
sudo nano /etc/php/8.1/fpm/php.ini

# Add:
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
```

### 5. Security Hardening

- Disable directory listing
- Hide PHP version
- Implement rate limiting
- Use Content Security Policy
- Regular security updates
- Implement fail2ban

## Maintenance

### Updates
```bash
cd /var/www/laravel-quiz
git pull origin main
composer install --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.1-fpm
```

### Troubleshooting

**Clear all caches:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Check logs:**
```bash
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/error.log
```

## Support

For deployment issues, contact support or open an issue on GitHub.
