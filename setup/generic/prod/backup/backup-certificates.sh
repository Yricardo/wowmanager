#!/bin/bash
# Backup Let's Encrypt certificates and configuration

BACKUP_DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="$HOME/ssl-backup-$BACKUP_DATE"

echo "🔒 Creating SSL backup directory: $BACKUP_DIR"
mkdir -p "$BACKUP_DIR"

# Backup Let's Encrypt
echo "📜 Backing up Let's Encrypt certificates..."
sudo tar -czf "$BACKUP_DIR/letsencrypt-$BACKUP_DATE.tar.gz" -C / etc/letsencrypt/

# Backup Apache SSL config
echo "🌐 Backing up Apache SSL configuration..."
sudo cp -r /etc/apache2/sites-available/ "$BACKUP_DIR/apache-sites/"
sudo cp -r /etc/apache2/sites-enabled/ "$BACKUP_DIR/apache-enabled/"

# Create restore instructions
cat > "$BACKUP_DIR/RESTORE-INSTRUCTIONS.md" << 'EOF'
# SSL Certificate Restore Instructions

## 1. Install certbot first:
```bash
sudo apt update 
sudo apt install snapd
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot
```

## 2. Restore Let's Encrypt files:
```bash
sudo tar -xzf letsencrypt-*.tar.gz -C /
```

## 3. Restore Apache configuration:
```bash
sudo cp apache-sites/* /etc/apache2/sites-available/
sudo a2ensite *ssl*
sudo systemctl reload apache2
```

## 4. Test certificates:
```bash
sudo certbot certificates
curl -I https://gate.powtato.art
```
EOF

# Set permissions
sudo chown -R $USER:$USER "$BACKUP_DIR"
sudo chmod -R 755 "$BACKUP_DIR"

echo "✅ Backup completed: $BACKUP_DIR"
echo "📋 Backup contains:"
ls -la "$BACKUP_DIR"