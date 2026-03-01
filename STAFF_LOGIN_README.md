# Bobony Family Staff Login System

A secure staff login system with PHP and MySQL database for your Bobony Family server.

## Features

✅ Secure password hashing (bcrypt)
✅ Session management with timeout
✅ Role-based access (Admin, Moderator, Staff)
✅ Staff dashboard with statistics
✅ Recent login tracking
✅ Responsive design matching your site
✅ XSS and SQL injection protection

## Files Created

- **login.php** - Login page for staff members
- **dashboard.php** - Staff dashboard (requires login)
- **logout.php** - Logout functionality
- **config.php** - Database configuration and session management
- **database_setup.sql** - SQL script to create database tables
- **STAFF_LOGIN_README.md** - This file

## Setup Instructions

### Step 1: Install XAMPP or Local PHP/MySQL Server

Download and install XAMPP from https://www.apachefriends.org/

### Step 2: Create the Database

1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
2. Click on "SQL" tab
3. Copy and paste the contents of `database_setup.sql`
4. Click "Go" to execute

**OR** manually create the database using the SQL commands in `database_setup.sql`

### Step 3: Update Database Configuration

Edit `config.php` and update these lines if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');  // Your MySQL password
define('DB_NAME', 'bobony_db');
```

### Step 4: Test the Login

1. Start XAMPP (Apache and MySQL)
2. Navigate to: `http://localhost/bobony/login.php`
3. Login with credentials:
   - **Username:** admin
   - **Password:** admin123

## Default Test Accounts

```
Account 1 (Admin):
  Username: admin
  Password: admin123

Account 2 (Moderator):
  Username: moderator1
  Password: mod123

Account 3 (Staff):
  Username: staff1
  Password: staff123
```

## How to Create New Staff Accounts

### Method 1: Direct Database Insert (Recommended)

1. Open phpMyAdmin
2. Go to the `staff` table
3. Click "Insert"
4. Fill in the fields:
   - **username:** newusername
   - **password:** (paste the hashed password from Method 2 output)
   - **role:** admin, moderator, or staff
   - **status:** active or inactive
5. Click "Go"

### Method 2: Generate Hashed Password

Create a temporary file `hash_password.php` with this code:

```php
<?php
$password = 'your_password_here';
echo password_hash($password, PASSWORD_BCRYPT);
?>
```

Visit the file in your browser, copy the output, and use it in Method 1.

### Method 3: PHP Script

Create `add_staff.php`:

```php
<?php
require 'config.php';

$username = 'newstaff';
$password = 'newpassword123';
$role = 'staff';  // admin, moderator, or staff

$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$sql = "INSERT INTO staff (username, password, role, status) VALUES ('$username', '$hashed_password', '$role', 'active')";

if ($conn->query($sql)) {
    echo "Staff member created successfully!";
} else {
    echo "Error: " . $conn->error;
}
?>
```

## Security Features

- **Password Hashing:** Uses bcrypt (PASSWORD_BCRYPT) - industry standard
- **SQL Injection Prevention:** Uses `real_escape_string()` for user inputs
- **Session Management:** 1-hour timeout for idle sessions
- **Status Check:** Only active staff can login
- **XSS Protection:** Uses `htmlspecialchars()` for output

## File Structure

```
bobony/
├── login.php              ← Login page (public)
├── dashboard.php          ← Staff dashboard (protected)
├── logout.php             ← Logout handler
├── config.php             ← Database configuration
├── database_setup.sql     ← Database setup script
└── home.html              ← Your main page
```

## Customization

### Change Logo or Styling

Edit `login.php` and `dashboard.php` to match your preferences. The styling is in the `<style>` sections.

### Change Session Timeout

In `config.php`, modify:

```php
define('SESSION_TIMEOUT', 3600); // 3600 = 1 hour
```

### Change Password Requirements

Edit the validation in `login.php` as needed.

## Troubleshooting

### "Connection failed" Error
- Check if MySQL is running
- Verify database credentials in `config.php`
- Make sure the database exists

### "Username not found" on Correct Credentials
- Verify the username/password in the `staff` table
- Check that the account status is "active"
- Make sure the hashed password is correct

### Stuck on Login Page After Submitting
- Check your web server/PHP error logs
- Verify all files are uploaded to correct directory
- Clear browser cookies

### Dashboard Not Loading
- Make sure MySQL is running
- Check the database connection
- Verify session is set properly

## Next Steps

1. **Add Admin Panel:** Create `admin.php` to manage staff accounts
2. **Email Verification:** Add email confirmation for new accounts
3. **Password Reset:** Implement forgot password functionality
4. **2FA:** Add two-factor authentication
5. **Activity Logging:** Use the `activity_log` table to track actions

## Support

For issues or questions, check the error logs in your server and verify all setup steps above were completed correctly.

---

**Created for:** Bobony Family Studios
**Date:** 2026
