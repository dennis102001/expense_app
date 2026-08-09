# Expense Tracker App

A simple web application for tracking expenses, budgets, and categories, built using PHP with a custom MVC architecture, JavaScript, and Tailwind CSS.

## Features

- User registration and login
- Email verification for new accounts
- Add, edit, delete expenses
- Track budgets by month
- Categorize expenses
- View remaining budget
- View total expenses
- Password reset via email

## Tech Stack

- PHP (MVC architecture)
- JavaScript
- Tailwind CSS
- MySQL (database)
- Brevo API (Transactional Email)

## Local Setup

### 1. Clone the repository

Clone this repository to your local machine using Git.

### 2. Import the database

Open **phpMyAdmin** and import:

```text
database/database.sql
``` 
   
The SQL file already creates the required database and tables.

### 3. Create the .env file 

Create a `.env` file in the project root.

Example:

```env
APP_ENV=local
APP_URL=http://localhost/Personal_Expense_Tracker/public

DB_HOST=localhost
DB_PORT=3306
DB_NAME=expense_app
DB_USER=root
DB_PASS=

MAIL_USERNAME=your-verified-email@example.com
MAIL_FROM_NAME=Expense Tracker App
BREVO_API_KEY=your_brevo_api_key
```
   
### 4. Configure email
The application uses the **Brevo API** to send transactional emails such as account verification and password reset emails.

Create a Brevo account, generate an API key, and add it to your `.env` file:

Make sure the sender email used by the application is verified in Brevo.
         
### 5. Run the application

The application uses Apache for local development. It can be run using XAMPP, WAMP, or another local Apache/PHP environment.

With the current XAMPP setup, access the application at:

```text
http://localhost/Personal_Expense_Tracker/public
```

The `/public` directory is the application's entry point.

## Environment Configuration

The application uses environment variables so the same codebase can work in both local and production environments.

### Local

```env
APP_ENV=local
APP_URL=http://localhost/Personal_Expense_Tracker/public

DB_HOST=localhost
DB_PORT=3306
DB_NAME=expense_app
DB_USER=root
DB_PASS=

MAIL_USERNAME=your-verified-email@example.com
MAIL_FROM_NAME=Expense Tracker App
BREVO_API_KEY=your_brevo_api_key
```

### Production

```env
APP_ENV=production
APP_URL=https://your-production-domain.com

DB_HOST=your-database-host
DB_PORT=your-production-database-port
DB_NAME=your-database-name
DB_USER=your-database-user
DB_PASS=your-database-password

MAIL_USERNAME=your-verified-email@example.com
MAIL_FROM_NAME=Expense Tracker App
BREVO_API_KEY=your_brevo_api_key
```

> Do not commit your `.env` file or API keys to the repository.

## Deployment

The application is deployed to Render using Docker.

The included `Dockerfile` is used only for the production deployment. Local development does not require Docker and uses XAMPP/Apache instead.

## Notes
- The application uses the `public` directory as its entry point.
- `APP_URL` is used for generating application links, redirects, asset URLs, and email verification links.
- Email sending uses the Brevo HTTP API instead of SMTP.
- CSRF protection is not implemented yet and is planned for a future update.