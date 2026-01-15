# Student Information System - Project Report

## Project Overview
This report documents the Student Information System developed using PHP and MySQL.

## Executive Summary
The Student Information System (SIS) is a web-based application designed to manage student records efficiently. It provides secure access control, CRUD operations for student data, and a user-friendly interface.

## System Architecture

### Technology Stack
- **Backend**: PHP 7.4+ with MySQLi
- **Database**: MySQL 5.7+ / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Security**: Password hashing, CSRF protection, Prepared statements

### Database Design

#### USER Table
- `user_id`: INT (Primary Key, Auto Increment)
- `username`: VARCHAR(50) (Unique, Not Null)
- `password`: VARCHAR(255) (Not Null, Hashed)
- `full_name`: VARCHAR(100) (Not Null)
- `role`: ENUM('admin', 'student') (Not Null)
- `created_at`: TIMESTAMP (Default: CURRENT_TIMESTAMP)

#### STUDENT Table
- `student_id`: INT (Primary Key, Auto Increment)
- `matric_no`: VARCHAR(20) (Unique, Not Null)
- `name`: VARCHAR(100) (Not Null)
- `ic_no`: VARCHAR(20) (Unique, Not Null)
- `gender`: VARCHAR(10) (Not Null)
- `programme`: VARCHAR(100) (Not Null)
- `faculty`: VARCHAR(100) (Not Null)
- `semester`: INT (Not Null)
- `email`: VARCHAR(100)
- `phone_no`: VARCHAR(20)
- `address`: TEXT
- `created_at`: TIMESTAMP (Default: CURRENT_TIMESTAMP)

## Features Implemented

### 1. Authentication System
- Secure login with password verification
- Session management with regeneration
- Role-based access control (Admin/Student)
- Secure logout with session destruction

### 2. Student Management (CRUD Operations)

#### Create (Admin Only)
- Add new student records
- Form validation (client and server-side)
- CSRF token protection
- Duplicate checking for matric_no and ic_no

#### Read
- List all students with pagination (20 records per page)
- View detailed student information
- Search and filter capabilities (extensible)

#### Update (Admin Only)
- Edit existing student records
- Pre-populated form with current data
- CSRF token protection
- Validation for data integrity

#### Delete (Admin Only)
- Delete student records with confirmation
- CSRF token protection
- Soft confirmation before deletion

### 3. Security Features

#### SQL Injection Prevention
- All database queries use prepared statements with parameter binding
- User input is properly sanitized

#### Cross-Site Scripting (XSS) Prevention
- All output is HTML-escaped using custom `h()` function
- Proper encoding of special characters

#### Cross-Site Request Forgery (CSRF) Protection
- CSRF tokens generated for each session
- Token verification on all state-changing operations
- Tokens validated using timing-safe comparison

#### Password Security
- Passwords hashed using `password_hash()` with bcrypt
- Password verification using `password_verify()`
- No plain-text password storage

#### Access Control
- Role-based permissions (Admin vs Student)
- Login required for all protected pages
- Admin-only operations enforced server-side

### 4. User Interface

#### Responsive Design
- Mobile-friendly layout
- Responsive tables and forms
- Flexible navigation menu

#### User Experience
- Intuitive navigation
- Clear feedback messages (success/error alerts)
- Pagination for large datasets
- Confirmation dialogs for destructive actions

## File Structure

```
stufo-system/
├── sql/
│   └── init_db.sql                 # Database initialization script
├── scripts/
│   ├── seed_users.php              # User seeding script
│   └── generate_students.php       # Student data generator
├── public/
│   ├── db.php                      # Database connection & utilities
│   ├── header.php                  # Common header template
│   ├── footer.php                  # Common footer template
│   ├── index.php                   # Home page
│   ├── about.php                   # About page
│   ├── login.php                   # Login page
│   ├── logout.php                  # Logout handler
│   ├── students.php                # Student list with pagination
│   ├── student_view.php            # View student details
│   ├── student_add.php             # Add new student (admin)
│   ├── student_edit.php            # Edit student (admin)
│   ├── student_delete.php          # Delete student (admin)
│   └── assets/
│       ├── css/
│       │   └── styles.css          # Main stylesheet
│       └── js/
│           └── scripts.js          # Client-side JavaScript
├── report/
│   └── report_template.md          # This report template
└── README.md                       # Project documentation
```

## Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB
- Apache/Nginx web server
- phpMyAdmin (optional, for database management)

### Installation Steps

1. **Clone/Download the repository**
   ```bash
   git clone <repository-url>
   cd stufo-system
   ```

2. **Create the database**
   ```bash
   mysql -u root -p < sql/init_db.sql
   ```

3. **Configure database connection**
   Edit `public/db.php` and update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'DBASSIGNMENT');
   ```

4. **Seed initial users**
   ```bash
   php scripts/seed_users.php
   ```

5. **Generate sample student data (optional)**
   ```bash
   php scripts/generate_students.php
   ```

6. **Configure web server**
   Point document root to the `public/` directory

7. **Access the application**
   Open browser and navigate to your configured URL

### Default Credentials
- **Admin**: Username: `admin`, Password: `admin123`
- **Student**: Username: `student1`, Password: `student123`

## Usage Guide

### For Students
1. Login with provided credentials
2. View student records (read-only access)
3. Browse through paginated student list
4. View detailed student information

### For Administrators
1. Login with admin credentials
2. Full access to all features:
   - View student records
   - Add new students
   - Edit existing student information
   - Delete student records
3. All actions protected by CSRF tokens

## Testing

### Manual Testing Checklist
- [ ] User authentication (login/logout)
- [ ] Role-based access control
- [ ] Student list pagination
- [ ] Add new student (admin)
- [ ] Edit student (admin)
- [ ] Delete student (admin)
- [ ] Form validation
- [ ] CSRF protection
- [ ] SQL injection prevention
- [ ] XSS prevention
- [ ] Responsive design on mobile devices

### Security Testing
- [ ] Attempt SQL injection
- [ ] Attempt XSS attacks
- [ ] Test CSRF protection
- [ ] Verify password hashing
- [ ] Test session security
- [ ] Verify role-based access

## Known Limitations
1. No password recovery mechanism
2. No email verification
3. No advanced search/filtering
4. No export functionality (PDF/Excel)
5. No student profile pictures
6. No audit logging

## Future Enhancements
1. **Advanced Features**
   - Student search and filtering
   - Export to PDF/Excel
   - Bulk import from CSV
   - Student profile pictures
   - Email notifications

2. **Security Enhancements**
   - Two-factor authentication
   - Password complexity requirements
   - Account lockout after failed attempts
   - Audit logging

3. **User Experience**
   - Advanced pagination with search
   - Sorting by multiple columns
   - Dark mode support
   - Printable reports

4. **Additional Modules**
   - Course management
   - Grade management
   - Attendance tracking
   - Fee management

## Conclusion
The Student Information System successfully implements a secure, functional web application for managing student records. It demonstrates best practices in web development including security measures, user authentication, and responsive design.

## References
- PHP Manual: https://www.php.net/manual/
- MySQL Documentation: https://dev.mysql.com/doc/
- OWASP Security Guidelines: https://owasp.org/

---

**Project**: Student Information System  
**Version**: 1.0  
**Date**: 2026  
**Author**: Development Team
