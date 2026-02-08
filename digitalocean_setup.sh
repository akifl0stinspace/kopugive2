#!/bin/bash
# KopuGive DigitalOcean Setup Script
# Run this on your fresh LAMP droplet

echo "🚀 Setting up KopuGive on DigitalOcean..."

# Update system
echo "📦 Updating system..."
apt update && apt upgrade -y

# Go to web directory
cd /var/www/html

# Remove default files
echo "🗑️  Removing default files..."
rm -rf *

# Clone repository
echo "📥 Cloning KopuGive..."
git clone https://github.com/akifl0stinspace/kopugive2.git .

# Set permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# Create upload directories
mkdir -p uploads/receipts uploads/campaigns uploads/documents
chmod -R 755 uploads/

# Enable Apache modules
echo "⚙️  Configuring Apache..."
a2enmod rewrite
systemctl restart apache2

# Setup firewall
echo "🛡️  Configuring firewall..."
ufw allow 80/tcp
ufw allow 443/tcp
ufw allow 22/tcp
ufw --force enable

echo "✅ Setup complete!"
echo ""
echo "⚠️  NEXT STEPS:"
echo "1. Create MySQL database and user"
echo "2. Update config/database.php"
echo "3. Import database/schema.sql"
echo "4. Import database/seed.sql"
echo "5. Visit http://YOUR_IP to test"
echo ""
echo "🎉 Happy hosting!"

