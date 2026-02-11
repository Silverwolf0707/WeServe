# WeServe: Web-based Integrated System for Managing and Tracking Financial Application
link: https://github.com/Silverwolf0707/Prototype.git

## Overview

The WeServe is a user-friendly web application designed to streamline the management of financial assistance programs. It provides a centralized platform for handling applications, tracking statuses, managing budgets, and generating reports, ensuring efficiency, transparency, and accountability.

## Features

- **User  Authentication:**
  - Secure login for different user roles (Admin, CSWD Officer, Mayor, Budget Officer, Accounting Officer, Treasury Officer).
  - Role-based access control to ensure users can only access relevant features.
  - Permission-based access for different roles.

- **Patient Application Management:**
  - Online application submission with unique tracking numbers.
  - Review and transfer of online applications to formal patient records.
  - Status tracking through various stages (Submitted, Approved, Rejected, Budget Allocated, DV Submitted, Disbursed).

- **Budget and Disbursement Management:**
  - Budget allocation for approved applications.
  - Disbursement voucher code inputs
  - Mark application as diburse

- **Data Analytics and Reporting:**
  - Dashboards displaying key metrics and trends.
  - Distribution of assistance and deficiency data graphs.
  - Yearly trend analysis of patient applications.

- **Document Management:**
  - Upload and manage documents related to patient records.
  - Association of documents with specific patient records.

## Technologies Used

- **Backend:** Laravel (PHP Framework)
- **Frontend:** HTML, CSS, JavaScript
- **Database:** MySQL
- **Data Visualization:** Chart.js for analytics
- **Analytics:** Python Scripts

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Silverwolf0707/Prototype.git
   cd Prototype
   ```

2. **Install dependencies:**
   ```bash
   composer install
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
   Open your browser and navigate to `http://localhost:8000`.

## License

--

## Acknowledgments

- Thanks to the Laravel community for their support and resources.
- Special thanks to the contributors and users who provide feedback and suggestions.



