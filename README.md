![WeServe](public/weservelogo.png)
# WeServe: Web-based Integrated System for Managing and Tracking Financial Application
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php)
![Python](https://img.shields.io/badge/Python-3.12%2B-3776AB?logo=python)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?logo=javascript&logoColor=yellow)
![CSS](https://img.shields.io/badge/CSS-1572B6?logo=css)
![Node.js](https://img.shields.io/badge/Node.js-22.x-339933?logo=nodedotjs)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://mysql.com)
[![Hostinger](https://img.shields.io/badge/Hostinger-Deployed-673DE6?logo=hostinger&logoColor=white)](https://hostinger.com)
[![CloudPanel](https://img.shields.io/badge/Control%20Panel-CloudPanel-1E3A8A.svg)](https://cloudpanel.io)
[![Ubuntu](https://img.shields.io/badge/Ubuntu-22.04-E95420?logo=ubuntu&logoColor=white)](https://ubuntu.com)
[![Cloudflare](https://img.shields.io/badge/Cloudflare-DNS-F38020?logo=cloudflare&logoColor=white)](https://cloudflare.com)

link: https://github.com/Silverwolf0707/WeServe.git

## Overview

The WeServe is a user-friendly web application designed to streamline the management of financial assistance programs of the City Social Welfare and Development Office (CSWDO). It provides a centralized platform for handling applications, tracking statuses, managing budgets, and generating reports, ensuring efficiency, transparency, accountability, and interdepartmental coordination among CSWD, Mayor's Office, Budget Office, Accounting, and Treasury.

## Problem Statement

The City Social Welfare and Development Office (CSWDO) currently faces several challenges in managing financial assistance programs:

- **Manual Processing:** Applications are handled through paper-based forms, leading to lost documents and delayed processing
- **Lack of Transparency:** Applicants have no way to track their application status
- **Interdepartmental Delays:** Coordination between CSWD, Mayor's Office, Budget, Accounting, and Treasury is slow and fragmented
- **Data Silos:** Each department maintains separate records, causing inconsistencies
- **Limited Reporting:** No centralized data for trend analysis or program evaluation
- **Budget Monitoring Issues:** Difficulty tracking allocated vs. actual disbursed funds

**Solution:** WeServe addresses these challenges by providing a unified, digital platform that connects all stakeholders in the financial assistance process.

The system aims to:
- Digitize and automate manual application processes
- Reduce processing time through streamlined workflows
- Enhance data accuracy and accessibility across departments
- Improve service delivery to constituents seeking financial assistance
- Provide data-driven insights for better program planning and resource allocation

## Features

- **User  Authentication:**
   - Secure login for different user roles (Admin, CSWD, Mayor, Budget, Accounting, Treasury).
   - Role-based access control to ensure users can only access features relevant to their role.
   - Permission-based access to allow configurable access levels for each role.

- **Patient Application Management:**
   - Online application submission with unique tracking numbers.
   - Review and transfer of online applications to formal patient records.
   - Status tracking through various stages (e.g. Submitted, Approved, Rejected, Budget Allocated, DV Submitted, Ready for Disbursement, Disbursed).

- **Budget and Disbursement Management:**
   - Budget allocation for approved applications.
   - Disbursement voucher code inputs.
   - Mark application as disbursed.

- **Data Analytics and Reporting:**
   - Statistical Data Summaries provide key metrics and trends, including calculated values for mean, median, mode, and standard deviation.
   - Data Visualization: Graphs that illustrate the distribution of assistance and deficiencies.
   - Advanced Trend Analysis: Yearly analysis of patient applications using STL Decomposition to break down and understand seasonal and long-term patterns.

- **Document Management:**
   - Upload and manage documents related to patient records.
   - Association of documents with specific patient records.

## Technologies Used

- **Backend:** Laravel (PHP Framework)
- **Frontend:** HTML, CSS, JavaScript
- **Database:** MySQL
- **Data Visualization:** Chart.js for analytics
- **Analytics:** Python

## Application Lifecycle and Workflow

### 1. Application Submission
- **Online Portal:** Applicant fills out form requirements
- **Offline Intake:** CSWD staff creates application on behalf of walk-in applicants
- **System Action:** Generates unique tracking number (Format: CSWD-YYYY-XXXXX) and mark as Internal: "Processing", External: "Application is on-process at CSWD Office"
- **Tracking:** Applicant can track their application through the website

### 2. Initial Assessment (CSWD)
- Verify completeness of documents
- Check eligibility criteria
- Add initial assessment notes or remarks
- **Actions:** CSWD submits the application to mayor for aproval
- **Status Update:** Internal: "Submitted", External: " Application is on-process at Mayor's Office"

### 3. Approval (Mayor)
- Review CSWD assessment
- View supporting documents
- Add approval/disapproval remarks
- **Actions:** Approve or Reject
- **Status Update:** Internal: "Approved" or "Rejected", External: "Application is on-process at Budget Office"

### 4. Budget Allocation (Budget Office)
- View approved applications
- Check available funds
- Allocate specific budget amount
- **Actions:** Input allocated amount
- **Status Update:** Internal: "Budget Allocated", External: "Application is on-process at Accounting Office"

### 5. Voucher Processing (Accounting)
- Generate Disbursement Voucher
- Input DV number and date
- Attach supporting documents
- **Actions:** Submit DV details
- **Status Update:** Internal: "DV Submitted", External: "Application is on-process at Treasury Office"

### 6. Check Preparation (Treasury)
- Verify DV and supporting documents
- Verify Budget documents
- **Actions:** Mark as disbursed
- **Status Update:** Internal: "Disbursed"

## Installation, Setup in Local Environment

**Prerequisites**
Before installation, ensure you have the following installed:

Requirement	      Version	         Purpose
PHP	      >= 8.4 or latest	      Laravel framework
MySQL	      >= 8.0.x or latest	   Database
XAMMP       >= 3.3.x                Control panel for mysql and apache
Composer	   Latest	               PHP dependency manager
Node.js	   Latest	               NPM package management
NPM	      Latest	               Frontend asset compilation
Git	      Latest	               Version control

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Silverwolf0707/WeServe.git
   cd WeServe
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Set up the environment file:**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations:**
   ```bash
   php artisan migrate
   ```

6. **Start the local development server:**
   ```bash
   php artisan serve
   npm run dev
   ```

7. **Access the application:**
   Open your browser and navigate to `http://localhost:8000` or `http://localhost:8000/login` for authority side.

## Installation and Setup in Cloud (Hostinger VPS with Cloud Panel)
**Prerequisites**
Before starting, ensure you have:
Git Repository ready
Hostinger VPS with Ubuntu 20.04/22.04
Cloud Panel installed and configured
Domain name pointed to your VPS IP
SSH access to your server


## Initial Server Setup

# SSH into your VPS
```bash
ssh root@your_server_ip
# Update system
apt update && apt upgrade -y

# Set server hostname
hostnamectl set-hostname weserve.yourdomain.com

# Create deployment user (non-root for security)
adduser weserve-system
usermod -aG sudo weserve-system

# Switch to new user
su - weserve-system

# Generate SSH key for GitHub (if not already done)
ssh-keygen -t ed25519 -C "weserve-system@vps" -f ~/.ssh/id_ed25519 -N ""

# Display public key to add to GitHub
cat ~/.ssh/id_ed25519.pub
```

## Configure GitHub SSH Access
# Add GitHub to known hosts
```bash
ssh-keyscan github.com >> ~/.ssh/known_hosts
```
# Test SSH connection
```bash
ssh -T git@github.com
# Should see: "Hi username! You've successfully authenticated..."
```
# GitHub Setup:
Go to GitHub Repository → Settings → Deploy Keys
Click "Add deploy key"
Key: Paste the public key from cat ~/.ssh/id_ed25519.pub
Check Allow write access (if needed for CI/CD)

## Install Required Software (Latest Versions)
```bash
# Switch to root for installation
sudo su -

# Install PHP
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.4-fpm php8.4-cli php8.4-mysql \
               php8.4-zip php8.4-gd php8.4-mbstring \
               php8.4-curl php8.4-xml php8.4-bcmath

# Install Composer (latest)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer --version=2.7.1
php -r "unlink('composer-setup.php');"

# Install Node.js 20.x (latest LTS)
apt install -y nodejs

# Install Nginx (latest)
apt install -y nginx

# Install Certbot for SSL
apt install -y certbot python3-certbot-nginx

# Verify versions
php -v          # Should show PHP 8.4.x
composer --version  # Should show 2.x
node -v         # Should show v20.x
nginx -v        # Should show latest
```

## Create Directory Structure
```bash
# Create application directory in htdocs (Cloud Panel default)
mkdir -p /home/htdocs/weserve.yourdomain.com
cd /home/htdocs/weserve.yourdomain.com

# Set ownership
chown -R weserve-system:weserve-system /home/htdocs/weserve.yourdomain.com

# Switch to application user
su - weserve-system
cd /home/htdocs/weserve.yourdomain.com
```

## Clone Repository
```bash
# Clone with SSH (using configured key)
git clone git@github.com:Silverwolf0707/WeServe.git .
# Note: The dot (.) clones into current directory

# Verify clone
ls -la
```
## Configure Database
Use CloudPanel for the creation of MySQL database then:
```bash
# Switch back to application user
su - weserve-system
cd /home/htdocs/weserve.yourdomain.com

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file to match the db name and pass
nano .env

# APP_NAME=WeServe
# APP_ENV=production
# APP_DEBUG=false
# APP_URL=https://weserve.yourdomain.com

# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=weserve_prod
# DB_USERNAME=weserve_user
# DB_PASSWORD=YourStrongPasswordHere123!

# # Add these for production
# SESSION_DOMAIN=.weserve.yourdomain.com
# SANCTUM_STATEFUL_DOMAINS=weserve.yourdomain.com
```

## Install Dependencies and Run Migrations
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install and build frontend
npm install
npm run build

# Create storage link
php artisan storage:link

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Set proper permissions
cd /home/htdocs
chown -R www-data:www-data weserve.yourdomain.com
chmod -R 755 weserve.yourdomain.com/storage
chmod -R 755 weserve.yourdomain.com/bootstrap/cache
```

## Configure Nginx
```bash
# Create Nginx configuration
sudo nano /etc/nginx/sites-available/weserve.yourdomain.com

# server {
#     listen 80;
#     server_name weserve.yourdomain.com;
#     root /home/htdocs/weserve.yourdomain.com/public;

#     add_header X-Frame-Options "SAMEORIGIN";
#     add_header X-Content-Type-Options "nosniff";
#     add_header X-XSS-Protection "1; mode=block";

#     index index.php index.html;

#     charset utf-8;

#     location / {
#         try_files $uri $uri/ /index.php?$query_string;
#     }

#     location = /favicon.ico { access_log off; log_not_found off; }
#     location = /robots.txt  { access_log off; log_not_found off; }

#     error_page 404 /index.php;

#     location ~ \.php$ {
#         fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
#         fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
#         include fastcgi_params;
#     }

#     location ~ /\.(?!well-known).* {
#         deny all;
#     }

#     client_max_body_size 20M;
# }

# Enable site
sudo ln -s /etc/nginx/sites-available/weserve.yourdomain.com /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
sudo systemctl reload php8.4-fpm
```

## Configure SSL Certificate
```bash
# Obtain SSL certificate
sudo certbot --nginx -d weserve.yourdomain.com --non-interactive --agree-tos --email admin@yourdomain.com
```
# Optional
## For CI/CD using Github Actions
## Create Deployment Script
```bash
#!/bin/bash
set -e

echo "Laravel Deployment Started - $(date)"
echo "========================================"

DEPLOY_DIR="/home/weserve-system/htdocs/www.weserve-system.online"
cd "$DEPLOY_DIR"

echo "Fixing git ownership issues..."
git config --global --add safe.directory "$DEPLOY_DIR" 2>/dev/null || true

echo "Pulling latest changes..."
git fetch origin
git reset --hard origin/main

echo "Running migrations..."
php artisan migrate 

echo "Optimizing application..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo " Deployment completed at $(date)"
echo "Your site should be live at: https://www.weserve-system.online"
```
```bash
# Make script executable
sudo chmod +x /usr/local/bin/deploy-laravel.sh

# Test script
sudo /usr/local/bin/deploy-laravel.sh
```
## Create Workflow files under .github/
## cd.yml
```bash
name: CD - Deploy to Production

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Deploy to VPS
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.VPS_HOST }}
          username: weserve-system
          key: ${{ secrets.VPS_SSH_KEY }}
          port: 22
          script: |
            /usr/local/bin/deploy-laravel.sh

```

## ci.yml
```bash
name: CI - Build & Test

on:
  push:
    branches-ignore:
      - main
  pull_request:
    branches:
      - main

jobs:
  laravel-ci:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: mbstring, bcmath, pdo, mysql, tokenizer, xml, curl
          coverage: none

      - name: Install Composer dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Prepare environment
        run: |
          cp .env.example .env
          php artisan key:generate
          touch database/database.sqlite
          php artisan migrate --env=testing --force

      - name: Run tests
        run: php artisan test
```

## License

--

## Acknowledgments

- Thanks to the Laravel community for their support and resources.
- Special thanks to the contributors and users who provide feedback and suggestions.

## Support
If you encounter issues during installation:

GitHub Issues: https://github.com/Silverwolf0707/WeServe/issues
Email: ---



