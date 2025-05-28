#!/bin/bash
# setup-macos.sh

echo "Setting up macOS development environment..."

# Install Redis
brew install redis php

# Start Redis service
brew services start redis

# Wait a moment for Redis to start
sleep 2

# Check if Redis service is running via brew services
echo "Checking Redis service status..."
if brew services list | grep redis | grep -q "started"; then
    echo "✓ Redis service is running"
else
    echo "✗ Redis service failed to start"
    echo "Checking brew services:"
    brew services list | grep redis
    exit 1
fi

# Test Redis connection
echo "Testing Redis connection..."
if redis-cli ping | grep -q "PONG"; then
    echo "✓ Redis connection successful"
else
    echo "✗ Redis connection failed"
    exit 1
fi

# Continue with rest of setup...

composer install

#todo skip dropping and creating if db created will do for now
php bin/console doc:dat:dr --force
php bin/console doc:dat:cr
php bin/console doctrine:migrations:migrate --no-interaction

php bin/console app:config:test