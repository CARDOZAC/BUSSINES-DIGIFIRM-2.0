#!/usr/bin/env bash
# exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install and build frontend assets
npm install
npm run build

# Optimize Laravel
php artisan optimize

# Run database migrations
php artisan migrate --force
