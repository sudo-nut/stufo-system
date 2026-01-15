# Student Information System

A comprehensive web-based Student Information System built with PHP and MySQL, featuring user authentication, role-based access control, and full CRUD operations for managing student records.

## Features

- **User Authentication:** Secure login system with session management
- **Role-Based Access Control:** Separate permissions for admin and student users
- **Student Management:** Complete CRUD operations (Create, Read, Update, Delete)
- **Search Functionality:** Server-side search by name, matric number, or IC number
- **Pagination:** Efficient navigation through large datasets (20 records per page)
- **Security:** CSRF protection, prepared statements, password hashing
- **Responsive Design:** Mobile-friendly interface

## Technology Stack

- **Backend:** PHP (standard PHP, no frameworks)
- **Database:** MySQL with mysqli extension
- **Frontend:** HTML5, CSS3, JavaScript
- **Security:** Prepared statements, CSRF tokens, password hashing

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or PHP built-in server

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/sudo-nut/stufo-system.git
cd stufo-system
```

### 2. Initialize the Database

Import the database schema into MySQL:

```bash
mysql -u root < sql/init_db.sql
```

Or using MySQL client:

```bash
mysql -u root
```

```sql
SOURCE sql/init_db.sql;
```

### 3. Seed User Accounts

Run the user seeding script to create default admin and student accounts:

```bash
php scripts/seed_users.php
```

This will create:
- **Admin:** `admin01` / `admin123`
- **Student:** `student01` / `student123`

### 4. Generate Student Data (Optional)

To populate the database with 400 sample student records:

```bash
php scripts/generate_students.php --commit
```

To preview the data without inserting (dry-run mode):

```bash
php scripts/generate_students.php
```

### 5. Start the Application

Using PHP built-in server:

```bash
php -S localhost:8000 -t public
```

Or configure your web server to serve files from the `public` directory.

### 6. Access the Application

Open your web browser and navigate to:

```
http://localhost:8000
```

## Default Credentials

### Administrator Account
- **Username:** `admin01`
- **Password:** `admin123`
- **Capabilities:** Full access to add, edit, and delete student records

### Student Account
- **Username:** `student01`
- **Password:** `student123`
- **Capabilities:** View-only access to student information

## Project Structure

```
stufo-system/
├── sql/
│   └── init_db.sql                 # Database initialization script
├── scripts/
│   ├── seed_users.php              # User seeding script
│   └── generate_students.php       # Student data generator
├── public/
│   ├── db.php                      # Database connection
│   ├── header.php                  # Common header with auth helpers
│   ├── footer.php                  # Common footer
│   ├── index.php                   # Home page
│   ├── about.php                   # About page
│   ├── login.php                   # Login page
│   ├── logout.php                  # Logout handler
│   ├── students.php                # Student listing (search & pagination)
│   ├── student_view.php            # View student details
│   ├── student_add.php             # Add student (admin only)
│   ├── student_edit.php            # Edit student (admin only)
│   ├── student_delete.php          # Delete student (admin only)
│   └── assets/
│       ├── css/
│       │   └── styles.css          # Application styles
│       └── js/
│           └── scripts.js          # Client-side JavaScript
├── report/
│   └── report_template.md          # Assignment report template
└── README.md                       # This file
```

## Database Configuration

Default database configuration is set for local development in `public/db.php`:

```php
DB_HOST: localhost
DB_USER: root
DB_PASS: (empty)
DB_NAME: DBASSIGNMENT
DB_CHARSET: utf8mb4
```

To modify these settings, edit the constants in `public/db.php`.

## Security Features

### 1. SQL Injection Prevention
- All database queries use prepared statements with bound parameters
- No direct string interpolation in SQL queries

### 2. CSRF Protection
- CSRF tokens generated using `random_bytes()`
- Tokens validated on all POST requests
- Secure hash comparison using `hash_equals()`

### 3. Password Security
- Passwords hashed using `password_hash()` with `PASSWORD_DEFAULT`
- Never stored in plain text
- Verified using `password_verify()`

### 4. XSS Prevention
- All output sanitized using `htmlspecialchars()`
- Proper encoding for HTML contexts

### 5. Session Security
- Secure session management
- Session destruction on logout
- Session-based authentication

## Usage Guide

### For Administrators

1. **Login** with admin credentials (`admin01` / `admin123`)
2. **Browse Students:** Navigate to the Students page
3. **Search:** Use the search box to find students by name, matric no, or IC no
4. **Add Student:** Click "Add Student" button and fill in the form
5. **Edit Student:** Click "Edit" button on any student row
6. **Delete Student:** Click "Delete" button (with confirmation)
7. **View Details:** Click "View" button to see complete student information

### For Students

1. **Login** with student credentials (`student01` / `student123`)
2. **Browse Students:** View the list of all students
3. **Search:** Search for specific students
4. **View Details:** Click "View" to see detailed student information

## Development

### Database Schema

**USER Table:**
- Stores admin and student user accounts
- Password hashing for security
- Role-based access control

**STUDENT Table:**
- Comprehensive student information
- Unique constraints on matric_no and ic_no
- Indexed fields for efficient searching

### Key Functions

**Authentication Helpers (header.php):**
- `isLoggedIn()` - Check if user is authenticated
- `isAdmin()` - Check if user has admin role
- `requireLogin()` - Enforce authentication
- `requireAdmin()` - Enforce admin access
- `generateCSRFToken()` - Generate CSRF token
- `validateCSRFToken()` - Validate CSRF token
- `h()` - HTML escape function

## Troubleshooting

### Database Connection Issues

If you encounter database connection errors:

1. Ensure MySQL is running
2. Verify database credentials in `public/db.php`
3. Check that the database has been initialized: `mysql -u root < sql/init_db.sql`

### Permission Errors

If you see permission errors:

1. Ensure proper file permissions: `chmod -R 755 public/`
2. Check that PHP has write access to session directory

### Port Already in Use

If port 8000 is already in use, try a different port:

```bash
php -S localhost:8080 -t public
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is created for educational purposes.

## Support

For issues or questions, please open an issue on the GitHub repository.

## Acknowledgments

- Built as part of a university assignment
- Implements industry-standard security practices
- Follows PHP and MySQL best practices