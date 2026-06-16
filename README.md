# Expense Tracker App

A simple web app to track expenses, budgets, and categories using PHP (MVC), JS, and Tailwind CSS.

## Features
- User registration and login
- Add, edit, delete expenses
- Track budgets by month
- Categorize expenses
- View remaining budget
- View total amount of expenses

## Tech Stack
- PHP (MVC architecture)
- JavaScript
- Tailwind CSS
- MySQL (database)

## Setup Instructions

1. **Clone this repository to your local machine using Git**

2. **Import the database:**  
    - Open phpMyAdmin  
    - Import `database/database.sql`  
    *(The SQL file already creates the database for you)*

3. **Create a .env file in the project root to store your configuration settings**
    
4. **Add database configuration in .env:**
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=expense_app
   APP_ENV=local
   ```
   
5. **Email Setup (Required for User Registration)**

   This app uses email to send verification links for new users. To make it work with Gmail:

      1. Enable **2-Step Verification** on your Google account.
      2. Go to **App Passwords** in Google Security settings.
      3. Generate a **16-character app password** for Mail (custom name: Expense Tracker).
      4. Add the credentials to `.env`:
         
         ```env
         MAIL_USERNAME=youremail@gmail.com
         MAIL_PASSWORD=yourapppasswordhere
         MAIL_FROM_NAME=Expense Tracker App
         MAIL_HOST=smtp.gmail.com
         MAIL_PORT=587
         MAIL_ENCRYPTION=tls
         ```
         
6. Run the project in your local server (XAMPP, WAMP, etc.)

Notes:
    - CSRF protection is not implemented yet (will be added in future updates)
