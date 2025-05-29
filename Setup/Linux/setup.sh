#!/bin/bash

install_composer() {
    echo "Installing Composer..."
    
    # Download Composer installer
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    
    # Verify installer (optional but recommended)
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
    
    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
        echo "ERROR: Invalid Composer installer checksum"
        rm composer-setup.php
        exit 1
    fi
    
    # Install Composer globally
    php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer
    
    # Clean up
    rm composer-setup.php
    
    echo "✓ Composer installed successfully"
}

install_symfony_cli()
{
    echo "installing symfony cli";
    chmod +x linux-symfony-cli-setup && ./linux-symfony-cli-setup.sh
    #todo manage errors, should shut if not ok.
}

# Configure Redis authentication for production
setup_redis_auth() {
    if [ "$APP_ENV" = "prod" ]; then
        echo "Configuring Redis authentication..."
        
        # Generate secure password if not set
        if [ -z "$REDIS_PASSWORD" ]; then
            REDIS_PASSWORD=$(openssl rand -base64 32)
            echo "REDIS_PASSWORD=$REDIS_PASSWORD" >> .env.local
        fi
        
        # Update Redis configuration
        sudo cp /etc/redis/redis.conf /etc/redis/redis.conf.backup
        sudo sed -i "s/# requirepass foobared/requirepass $REDIS_PASSWORD/" /etc/redis/redis.conf
        
        # Restart Redis with new config
        sudo systemctl restart redis-server
        
        # Test authenticated connection
        redis-cli -a "$REDIS_PASSWORD" ping
    fi
}

ping_redis() {
    echo "Pinging Redis server..."
    if redis-cli ping; then
        echo "✓ Redis server is reachable"
    else
        echo "✗ Redis server is not reachable"
    fi
}

setup_redis() {
        
    echo Install PHP Redis extension
    $INSTALL_CMD php-redis

    sudo apt-get install lsb-release curl gpg
    curl -fsSL https://packages.redis.io/gpg | sudo gpg --dearmor -o /usr/share/keyrings/redis-archive-keyring.gpg
    sudo chmod 644 /usr/share/keyrings/redis-archive-keyring.gpg
    echo "deb [signed-by=/usr/share/keyrings/redis-archive-keyring.gpg] https://packages.redis.io/deb $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/redis.list
    sudo apt-get update
    sudo apt-get install redis        

    sudo systemctl enable redis-server
    sudo systemctl start redis-server 

    if [ "$APP_ENV" = "prod" ]; then
        echo "Setup redis for production environment..."
        # Check if Redis is already installed
        if ! command -v redis-server &> /dev/null; then
            setup_redis_auth   
        else
            echo "Redis is already installed"
        fi
    fi    
}

echo "Setting up Linux development environment..."

# setup-linux.sh (additional Redis security configuration)

# Detect package manager
if command -v apt-get &> /dev/null; then
    PACKAGE_MANAGER="apt-get"
    INSTALL_CMD="sudo apt-get update && sudo apt-get install -y"
elif command -v yum &> /dev/null; then
    PACKAGE_MANAGER="yum" 
    INSTALL_CMD="sudo yum install -y"
elif command -v dnf &> /dev/null; then
    PACKAGE_MANAGER="dnf"
    INSTALL_CMD="sudo dnf install -y"
else
    echo "Unsupported package manager"
    exit 1
fi

# Verify Composer installation
if composer --version &> /dev/null; then
    echo "✓ Composer verification successful"
else
    echo "✗ Composer installation failed"
    exit 1
fi

cp .env .env.local
echo "installing depedencies"
composer install

# Check if Composer is already installed
if command -v composer &> /dev/null; then
    echo "✓ Composer is already installed"
    composer --version
else
    echo "Composer not found, installing..."
    install_composer
fi

#todo skip dropping and creating if db created
echo "creating/overiding DB, apply all migrations";
php bin/console doc:dat:dr --force
php bin/console doc:dat:cr
php bin/console doctrine:migrations:migrate --no-interaction

#call tests commands here
php bin/console app:config:test

setup_redis

# Install Redis and PHP Redis extension
$INSTALL_CMD redis-server php-redis

# Start Redis service
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Check if Redis service is running
echo "Checking Redis service status..."
if sudo systemctl is-active --quiet redis-server; then
    echo "✓ Redis service is running"
else
    echo "✗ Redis service failed to start"
    echo "Checking service status:"
    sudo systemctl status redis-server
    exit 1
fi

# Test Redis connection
ping_redis 


