# Student Information System (STUFO)

A comprehensive web-based Student Information System built with PHP and MySQL, featuring secure authentication, role-based access control, and complete CRUD operations for student management.

## Features

- **Secure Authentication**: Login system with password hashing and session management
- **Role-Based Access Control**: Admin and Student roles with different permissions
- **Student Management**: Complete CRUD operations for student records
- **Pagination**: Browse through student records with 20 items per page
- **CSRF Protection**: All forms protected against Cross-Site Request Forgery
- **SQL Injection Prevention**: All queries use prepared statements
- **XSS Prevention**: All output properly escaped
- **Responsive Design**: Mobile-friendly interface

## Technology Stack

- **Backend**: PHP 7.4+ with MySQLi
- **Database**: MySQL 5.7+ / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Security**: Password hashing, CSRF tokens, Prepared statements

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB
- Apache/Nginx web server

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/sudo-nut/stufo-system.git
   cd stufo-system
   ```

2. **Create the database**
   ```bash
   mysql -u root -p < sql/init_db.sql
   ```

3. **Configure database connection**
   Edit `public/db.php` if needed (default uses localhost with root user):
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
   Point your web server's document root to the `public/` directory.

7. **Access the application**
   Open your browser and navigate to your configured URL (e.g., `http://localhost/stufo-system/public/`)

## Default Credentials

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`

### Student Account
- **Username**: `student1`
- **Password**: `student123`

## User Roles

### Administrator
- View all student records
- Add new students
- Edit student information
- Delete students

### Student
- View student records (read-only)
- Browse through student list

## File Structure

```
stufo-system/
├── sql/
│   └── init_db.sql              # Database schema
├── scripts/
│   ├── seed_users.php           # Create default users
│   └── generate_students.php    # Generate sample data
├── public/
│   ├── db.php                   # Database connection
│   ├── header.php               # Header template
│   ├── footer.php               # Footer template
│   ├── index.php                # Home page
│   ├── about.php                # About page
│   ├── login.php                # Login page
│   ├── logout.php               # Logout handler
│   ├── students.php             # Student list
│   ├── student_view.php         # View student
│   ├── student_add.php          # Add student
│   ├── student_edit.php         # Edit student
│   ├── student_delete.php       # Delete student
│   └── assets/
│       ├── css/styles.css       # Stylesheet
│       └── js/scripts.js        # JavaScript
├── report/
│   └── report_template.md       # Project report
└── README.md                    # This file
```

## Database Schema

### USER Table
Stores user authentication and role information.
- `user_id`, `username`, `password` (hashed), `full_name`, `role`, `created_at`

### STUDENT Table
Stores student information.
- `student_id`, `matric_no`, `name`, `ic_no`, `gender`, `programme`, `faculty`, `semester`, `email`, `phone_no`, `address`, `created_at`

## Security Features

1. **Password Security**: Bcrypt hashing for all passwords
2. **CSRF Protection**: Tokens generated and validated for all forms
3. **SQL Injection Prevention**: Prepared statements for all queries
4. **XSS Prevention**: HTML escaping for all output
5. **Session Security**: Session regeneration on login
6. **Access Control**: Role-based permissions enforced

## Usage

### Viewing Students
1. Login with your credentials
2. Click on "Students" in the navigation menu
3. Browse through the paginated list
4. Click "View" to see detailed information

### Adding Students (Admin Only)
1. Login as admin
2. Click "Add Student" or navigate to the add page
3. Fill in all required fields (marked with *)
4. Submit the form

### Editing Students (Admin Only)
1. Navigate to the student list
2. Click "Edit" on the student you want to modify
3. Update the information
4. Submit the form

### Deleting Students (Admin Only)
1. Navigate to the student list or detail page
2. Click "Delete"
3. Confirm the deletion

## Contributing

Contributions are welcome! Please follow these guidelines:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available for educational purposes.

## Support

For issues or questions, please open an issue on GitHub or contact the development team.

## Acknowledgments

- Built as part of the STUFO System project
- Uses modern PHP security best practices
- Responsive design inspired by modern web applications