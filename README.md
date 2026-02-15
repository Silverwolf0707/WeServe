# WeServe: Web-based Integrated System for Managing and Tracking Financial Application
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
```
# Update system
```bash
apt update && apt upgrade -y
```
# Set server hostname
```bash
hostnamectl set-hostname weserve.yourdomain.com
```
# Create deployment user (non-root for security)
```bash
adduser weserve-system
usermod -aG sudo weserve-system
```
# Switch to new user
```bash
su - weserve-system
```
# Generate SSH key for GitHub (if not already done)
```bash
ssh-keygen -t ed25519 -C "weserve-system@vps" -f ~/.ssh/id_ed25519 -N ""
```
# Display public key to add to GitHub
```bash
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

# Install PHP 8.3 (latest stable)
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.3-fpm php8.3-cli php8.3-mysql \
               php8.3-zip php8.3-gd php8.3-mbstring \
               php8.3-curl php8.3-xml php8.3-bcmath \
               php8.3-redis php8.3-intl php8.3-sqlite3

# Install Composer (latest)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer --version=2.7.1
php -r "unlink('composer-setup.php');"

# Install Node.js 20.x (latest LTS)
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Install Nginx (latest)
apt install -y nginx

# Install MySQL 8.0
apt install -y mysql-server

# Install Redis
apt install -y redis-server

# Install Supervisor
apt install -y supervisor

# Install Certbot for SSL
apt install -y certbot python3-certbot-nginx

# Verify versions
php -v          # Should show PHP 8.3.x
composer --version  # Should show 2.7.1
node -v         # Should show v20.x
nginx -v        # Should show latest
mysql --version # Should show 8.0.x
redis-server -v # Should show 7.x
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



