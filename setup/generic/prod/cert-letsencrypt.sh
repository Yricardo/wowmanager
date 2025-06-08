#!/bin/bash
# Enhanced cert-letsencrypt.sh with backup capability

# Check if restoring from backup
if [ "$1" = "restore" ] && [ -f "$2" ]; then
    echo "🔄 Restoring certificates from backup: $2"
    sudo tar -xzf "$2" -C /
    sudo systemctl reload apache2
    sudo certbot certificates
    echo "✅ Certificate restoration completed!"
    exit 0
fi

# Regular certificate installation
echo "🔒 Installing Let's Encrypt certificates..."

sudo apt update 
sudo apt install snapd
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot

# Create backup before getting new cert (if certs exist)
if [ -d "/etc/letsencrypt/live" ]; then
    echo "📦 Creating backup of existing certificates..."
    BACKUP_FILE="letsencrypt-backup-$(date +%Y%m%d_%H%M%S).tar.gz"
    sudo tar -czf "/root/$BACKUP_FILE" -C / etc/letsencrypt/
    echo "✅ Backup created: /root/$BACKUP_FILE"
fi

sudo certbot --apache

echo "🎉 Let's Encrypt setup completed!"
echo "📋 Your certificates:"
sudo certbot certificates