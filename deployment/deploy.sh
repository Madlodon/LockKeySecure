#!/bin/bash
# Script de déploiement pour LockKeySecure sur EC2 Ubuntu 24.04
# Usage: bash deploy.sh

set -e

DOMAIN="laniproject.dev"
APP_DIR="/var/www/lockkeysecure"
REPO_URL="https://github.com/Madlodon/LockKeySecure.git"

echo "==> Mise à jour du système"
sudo apt update && sudo apt upgrade -y

echo "==> Installation des dépendances"
sudo apt install -y nginx php8.3 php8.3-fpm php8.3-cli php8.3-mbstring \
    php8.3-xml php8.3-sqlite3 php8.3-curl php8.3-zip php8.3-bcmath \
    php8.3-tokenizer git unzip curl certbot python3-certbot-nginx

echo "==> Installation de Composer"
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

echo "==> Installation de Node.js"
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install -y nodejs

echo "==> Clonage du projet"
sudo mkdir -p $APP_DIR
sudo git clone $REPO_URL $APP_DIR
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

echo "==> Configuration Laravel"
cd $APP_DIR
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data npm install
sudo -u www-data npm run build

sudo cp .env.example .env
# IMPORTANT: édite manuellement le .env avant de continuer
# sudo nano .env

sudo -u www-data php artisan key:generate
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

echo "==> Configuration Nginx"
sudo cp deployment/nginx.conf /etc/nginx/sites-available/lockkeysecure
sudo sed -i "s/tondomaine.com/$DOMAIN/g" /etc/nginx/sites-available/lockkeysecure
sudo ln -sf /etc/nginx/sites-available/lockkeysecure /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx

echo "==> Installation du certificat SSL (Certbot)"
sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos -m "larouchenic@gmail.com"

echo "==> Configuration du renouvellement automatique SSL"
(crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet") | crontab -

echo ""
echo "Déploiement terminé! Ton site est accessible sur https://$DOMAIN"