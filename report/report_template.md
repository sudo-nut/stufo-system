# Student Information System - Assignment Report

## 1. Introduction

### 1.1 Project Overview
This project implements a comprehensive Student Information System using PHP and MySQL. The system provides functionality for managing student records with features including user authentication, role-based access control, search capabilities, and full CRUD operations for administrators.

### 1.2 Objectives
- Develop a secure web-based student information management system
- Implement role-based access control (Admin and Student roles)
- Provide efficient search and pagination features
- Ensure data security through prepared statements and CSRF protection
- Create a responsive and user-friendly interface

## 2. System Design

### 2.1 Database Design

#### 2.1.1 Database Name
- **Database:** DBASSIGNMENT
- **Character Set:** utf8mb4
- **Collation:** utf8mb4_unicode_ci

#### 2.1.2 Table Structures

**USER Table:**
- `user_id` (INT, Primary Key, Auto Increment)
- `username` (VARCHAR(50), Unique, Not Null)
- `password` (VARCHAR(255), Not Null)
- `full_name` (VARCHAR(100), Not Null)
- `role` (ENUM('admin', 'student'), Default: 'student')
- `created_at` (TIMESTAMP, Default: CURRENT_TIMESTAMP)
- `updated_at` (TIMESTAMP, Default: CURRENT_TIMESTAMP ON UPDATE)

**STUDENT Table:**
- `student_id` (INT, Primary Key, Auto Increment)
- `matric_no` (VARCHAR(20), Unique, Not Null)
- `ic_no` (VARCHAR(20), Unique, Not Null)
- `full_name` (VARCHAR(100), Not Null)
- `email` (VARCHAR(100), Not Null)
- `phone` (VARCHAR(20))
- `address` (TEXT)
- `date_of_birth` (DATE)
- `gender` (ENUM('Male', 'Female'), Not Null)
- `program` (VARCHAR(100))
- `year_of_study` (INT)
- `created_at` (TIMESTAMP, Default: CURRENT_TIMESTAMP)
- `updated_at` (TIMESTAMP, Default: CURRENT_TIMESTAMP ON UPDATE)

#### 2.1.3 Entity-Relationship Diagram
*[Insert ER Diagram here]*

### 2.2 System Architecture

The system follows a standard three-tier architecture:

1. **Presentation Layer:** HTML, CSS, JavaScript
2. **Application Layer:** PHP (server-side logic)
3. **Data Layer:** MySQL database

### 2.3 File Structure

```
stufo-system/
├── sql/
│   └── init_db.sql                 # Database initialization
├── scripts/
│   ├── seed_users.php              # User seeding script
│   └── generate_students.php       # Student data generator
├── public/
│   ├── db.php                      # Database connection
│   ├── header.php                  # Common header
│   ├── footer.php                  # Common footer
│   ├── index.php                   # Home page
│   ├── about.php                   # About page
│   ├── login.php                   # Login page
│   ├── logout.php                  # Logout handler
│   ├── students.php                # Student listing
│   ├── student_view.php            # View student details
│   ├── student_add.php             # Add student (admin)
│   ├── student_edit.php            # Edit student (admin)
│   ├── student_delete.php          # Delete student (admin)
│   └── assets/
│       ├── css/
│       │   └── styles.css          # Stylesheets
│       └── js/
│           └── scripts.js          # JavaScript
├── report/
│   └── report_template.md          # This report
└── README.md                       # Setup instructions
```

## 3. Implementation

### 3.1 Authentication System

#### 3.1.1 Login Process
*[Insert screenshot of login page]*

The login system uses the following security measures:
- Password hashing using `password_hash()` with `PASSWORD_DEFAULT`
- Password verification using `password_verify()`
- Session-based authentication
- Prepared statements to prevent SQL injection

**Code Snippet - Login Authentication:**
```php
// [Insert relevant code snippet from login.php]
```

#### 3.1.2 Session Management
*[Describe session handling implementation]*

### 3.2 User Roles and Access Control

#### 3.2.1 Administrator Role
Administrators have full access to:
- View all students
- Add new students
- Edit existing students
- Delete students

*[Insert screenshot of admin view]*

#### 3.2.2 Student Role
Students have limited access to:
- View student list
- Search students
- View student details

*[Insert screenshot of student view]*

### 3.3 Student Management Features

#### 3.3.1 Student Listing
*[Insert screenshot of students listing page]*

Features:
- Paginated display (20 records per page)
- Server-side search by name, matric number, or IC number
- Responsive table layout

**Code Snippet - Search Implementation:**
```php
// [Insert relevant code snippet from students.php]
```

#### 3.3.2 Add Student
*[Insert screenshot of add student form]*

Validation includes:
- Required field checks
- Email format validation
- Unique matric number and IC number validation
- CSRF token validation

**Code Snippet - Add Student:**
```php
// [Insert relevant code snippet from student_add.php]
```

#### 3.3.3 Edit Student
*[Insert screenshot of edit student form]*

**Code Snippet - Edit Student:**
```php
// [Insert relevant code snippet from student_edit.php]
```

#### 3.3.4 Delete Student
*[Insert screenshot of delete confirmation]*

**Code Snippet - Delete Student:**
```php
// [Insert relevant code snippet from student_delete.php]
```

#### 3.3.5 View Student Details
*[Insert screenshot of student details page]*

### 3.4 Security Implementation

#### 3.4.1 SQL Injection Prevention
All database queries use prepared statements with bound parameters:
```php
// Example
$stmt = $conn->prepare("SELECT * FROM STUDENT WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
```

#### 3.4.2 CSRF Protection
- CSRF tokens generated using `random_bytes()`
- Tokens stored in session
- Validated on all POST requests
- Hash comparison using `hash_equals()`

**Code Snippet - CSRF Token Generation:**
```php
// [Insert relevant code snippet from header.php]
```

#### 3.4.3 XSS Prevention
All output is sanitized using `htmlspecialchars()`:
```php
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
```

#### 3.4.4 Password Security
- Passwords hashed using `password_hash()` with `PASSWORD_DEFAULT`
- Never stored in plain text
- Verified using `password_verify()`

### 3.5 Search and Pagination

#### 3.5.1 Search Implementation
*[Insert screenshot of search functionality]*

The search feature supports:
- Partial matching
- Case-insensitive search
- Multiple fields (name, matric_no, ic_no)

#### 3.5.2 Pagination
*[Insert screenshot of pagination]*

- Default 20 records per page
- Efficient database queries using LIMIT and OFFSET
- Page navigation with ellipsis for large datasets

## 4. Testing

### 4.1 Test Cases

#### 4.1.1 Authentication Tests
| Test Case | Description | Expected Result | Actual Result | Status |
|-----------|-------------|-----------------|---------------|--------|
| TC-001 | Login with valid admin credentials | Successful login, redirect to students page | | |
| TC-002 | Login with valid student credentials | Successful login, redirect to students page | | |
| TC-003 | Login with invalid credentials | Error message displayed | | |
| TC-004 | Access admin page without login | Redirect to login page | | |
| TC-005 | Logout functionality | Session destroyed, redirect to login | | |

#### 4.1.2 Student Management Tests
| Test Case | Description | Expected Result | Actual Result | Status |
|-----------|-------------|-----------------|---------------|--------|
| TC-006 | Add student with valid data | Student created successfully | | |
| TC-007 | Add student with duplicate matric no | Error message displayed | | |
| TC-008 | Edit student information | Student updated successfully | | |
| TC-009 | Delete student record | Student deleted successfully | | |
| TC-010 | View student details | All information displayed correctly | | |

#### 4.1.3 Search and Pagination Tests
| Test Case | Description | Expected Result | Actual Result | Status |
|-----------|-------------|-----------------|---------------|--------|
| TC-011 | Search by student name | Matching records displayed | | |
| TC-012 | Search by matric number | Matching record displayed | | |
| TC-013 | Navigate to next page | Next 20 records displayed | | |
| TC-014 | Navigate to specific page | Correct page displayed | | |

### 4.2 Test Screenshots
*[Insert relevant test screenshots]*

## 5. User Guide

### 5.1 Installation

1. **Import Database:**
   ```bash
   mysql -u root < sql/init_db.sql
   ```

2. **Seed Users:**
   ```bash
   php scripts/seed_users.php
   ```

3. **Generate Student Data:**
   ```bash
   php scripts/generate_students.php --commit
   ```

4. **Start Server:**
   ```bash
   php -S localhost:8000 -t public
   ```

5. **Access Application:**
   Open browser and navigate to `http://localhost:8000`

### 5.2 Default Credentials

**Administrator:**
- Username: `admin01`
- Password: `admin123`

**Student:**
- Username: `student01`
- Password: `student123`

### 5.3 Using the System

#### 5.3.1 For Administrators
*[Provide step-by-step guide with screenshots]*

1. Login with admin credentials
2. Navigate to Students page
3. Use search to find specific students
4. Click "Add Student" to create new records
5. Use Edit/Delete buttons for management

#### 5.3.2 For Students
*[Provide step-by-step guide with screenshots]*

1. Login with student credentials
2. Browse student list
3. Use search functionality
4. View student details

## 6. Conclusion

### 6.1 Summary
This Student Information System successfully implements a secure, efficient, and user-friendly solution for managing student records. The system incorporates industry-standard security practices including prepared statements, CSRF protection, password hashing, and role-based access control.

### 6.2 Key Features Implemented
- ✅ User authentication with role-based access control
- ✅ Complete CRUD operations for student records
- ✅ Advanced search functionality
- ✅ Pagination for efficient data browsing
- ✅ CSRF protection on all forms
- ✅ SQL injection prevention using prepared statements
- ✅ Password hashing and secure authentication
- ✅ Responsive design for mobile and desktop
- ✅ Client-side validation and enhancements

### 6.3 Challenges and Solutions
*[Describe any challenges faced and how they were resolved]*

### 6.4 Future Enhancements
Potential improvements for future versions:
- Export student data to PDF/Excel
- Advanced filtering options
- Student photo upload
- Email notifications
- Bulk import from CSV
- Audit log for all changes
- Password reset functionality
- Multi-language support

## 7. References

1. PHP Documentation - https://www.php.net/docs.php
2. MySQL Documentation - https://dev.mysql.com/doc/
3. OWASP Security Guidelines - https://owasp.org/
4. MDN Web Docs - https://developer.mozilla.org/

## Appendix

### Appendix A: Complete Code Listings
*[Include complete code for key files if required]*

### Appendix B: Database Queries
*[Include important SQL queries used in the system]*

### Appendix C: Screenshots
*[Compile all screenshots referenced in the report]*
