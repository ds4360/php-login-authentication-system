# 🔐 PHP Login Authentication System

A PHP-based user authentication system designed to provide secure login, registration, password management, OTP verification, profile management, and session-based authentication.

## 🚀 Features

* 👤 User Registration
* 🔑 Secure Login & Logout
* 🔄 Password Change
* 📧 Forgot Password
* 🔐 Password Reset
* 🔢 OTP Verification
* 👨‍💻 User Profile
* ✏️ Edit Profile
* 🛡️ Session Authentication
* 🔒 CSRF Token Protection
* 📩 Email/OTP Support

## 🛠️ Technologies Used

* **PHP**
* **MySQL**
* **HTML5**
* **CSS3**
* **Composer**
* **XAMPP**

## 📂 Project Structure

```text
php-login-authentication-system/
│
├── dashboard/
├── img/
│
├── index.php
├── login.php
├── logout.php
├── dashboard.php
├── profile.php
├── edit_profile.php
├── change_password.php
│
├── forgot_password.php
├── reset_password.php
│
├── otp.php
├── send_otp.php
├── verify_otp.php
│
├── csrf_token.php
├── session_check.php
│
├── composer.json
├── composer.lock
└── .gitignore
```

## ⚙️ Installation

### 1. Install XAMPP

Download and install XAMPP with Apache and MySQL.

### 2. Clone the Repository

```bash
git clone https://github.com/ds4360/php-login-authentication-system.git
```

### 3. Move the Project

Place the project inside:

```text
C:\xampp\htdocs\
```

### 4. Start XAMPP

Start:

* Apache
* MySQL

### 5. Configure Database

Create the required MySQL database and configure the database connection according to your local environment.

### 6. Run the Project

Open your browser and visit:

```text
http://localhost/index.php
```

## 🔐 Security

This project includes several authentication and security mechanisms:

* Session-based authentication
* CSRF token protection
* Password authentication
* OTP verification
* Password reset functionality
* Protected user sessions

> **Important:** Never upload database passwords, SMTP passwords, API keys, or other sensitive credentials to GitHub.

## 📧 SMTP Configuration

SMTP configuration should be kept locally and should not be committed to the repository.

The `smtp_config.php` file is excluded through `.gitignore`.

## 📌 Future Improvements

Possible future improvements include:

* Responsive UI improvements
* Stronger password validation
* Login rate limiting
* Account lockout after repeated failed attempts
* Improved email verification
* Two-factor authentication
* Better error handling

## 👨‍💻 Author

**Deepanshu Sagar**

GitHub:
https://github.com/ds4360

## ⭐ Support

If you find this project useful, consider giving the repository a ⭐ on GitHub.
