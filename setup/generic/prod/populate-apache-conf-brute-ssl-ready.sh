domain=$1

# Validate domain was passed
if [ -z "$domain" ]; then
    echo "ERROR: No domain provided!"
    exit 1
fi

sudo tee /etc/apache2/sites-available/$domain.conf > /dev/null << EOF
<VirtualHost *:80>
    ServerName $domain
    ServerAlias www.$domain

    # Uncomment the following line to force Apache to pass the Authorization
    # header to PHP: required for "basic_auth" under PHP-FPM and FastCGI
    SetEnvIfNoCase ^Authorization$ "(.+)" HTTP_AUTHORIZATION=$1

    <FilesMatch \.php$>
        # when using PHP-FPM as a unix socket
        SetHandler proxy:unix:/var/run/php/php8.4-fpm.sock|fcgi://dummy
        # when PHP-FPM is configured to use TCP
        # SetHandler proxy:fcgi://127.0.0.1:9000
    </FilesMatch>
    
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public/.well-known>
        Require all granted
        # Disable fallback resource for Let's Encrypt challenges
        FallbackResource disabled
    </Directory>

    <Directory /var/www/html/public>
        AllowOverride None
        Require all granted
        FallbackResource /index.php
    </Directory>

    # uncomment the following lines if you install assets as symlinks
    # or run into problems when compiling LESS/Sass/CoffeeScript assets
    <Directory /var/www/html>
        Options FollowSymlinks
    </Directory>

    # optionally disable the fallback resource for the asset directories
    # which will allow Apache to return a 404 error when files are
    # not found instead of passing the request to Symfony
    <Directory /var/www/html/public/bundles>
    #     DirectoryIndex disabled
    #     FallbackResource disabled
    </Directory>

    ErrorLog /var/log/apache2/${domain}_error.log
    CustomLog /var/log/apache2/${domain}_access.log combined
</VirtualHost>
EOF

echo 'file populated !'