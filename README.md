# Student Information System (STUFO System)

A comprehensive web-based Student Information System built with PHP and MySQL, featuring secure CRUD operations, search functionality, pagination, and report generation.

## Features

### Core Functionality
- **Student Management**: Complete CRUD (Create, Read, Update, Delete) operations
- **Search & Filter**: Search students by name, matric number, or IC number
- **Pagination**: Browse through student records with paginated views
- **Student Details**: View detailed information for individual students
- **Report Generation**: Generate HTML and CSV reports of all students

### Security Features
- **Prepared Statements**: All database queries use mysqli prepared statements to prevent SQL injection
- **CSRF Protection**: All admin forms include CSRF token validation
- **Session Security**: Secure session management with httponly cookies
- **Input Sanitization**: All output is properly escaped using htmlspecialchars
- **Password Hashing**: Admin passwords are securely hashed using PHP's password_hash()

### User Interface
- **Responsive Design**: Mobile-friendly interface that works on all devices
- **Public View**: Anyone can view student list and details
- **Admin Dashboard**: Protected admin area for managing students
- **Print-Friendly Reports**: Reports optimized for printing

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/sudo-nut/stufo-system.git
   cd stufo-system
   ```

2. **Create the database**
   - Open phpMyAdmin or MySQL command line
   - Import the database schema:
   ```bash
   mysql -u root -p < database.sql
   ```
   Or manually run the SQL commands from `database.sql`

3. **Configure database connection**
   - The system uses default credentials (no password):
     - Host: `localhost`
     - Username: `root`
     - Password: (empty)
     - Database: `student_system`
   - Edit `config.php` if you need different credentials

4. **Set up web server**
   - Point your web server document root to the project directory
   - Ensure PHP is enabled
   - Make sure the web server has read permissions on all files

5. **Access the system**
   - Open your browser and navigate to: `http://localhost/stufo-system/`
   - For admin access: `http://localhost/stufo-system/login.php`

## Default Credentials

- **Admin Username**: `admin`
- **Admin Password**: `admin123`

⚠️ **Important**: Change the default admin password in production!

## File Structure

```
stufo-system/
├── config.php           # Database configuration and security functions
├── students.php         # Student database operations (all use prepared statements)
├── index.php           # Public student list with search and pagination
├── view_student.php    # Individual student details page
├── login.php           # Admin login page
├── admin.php           # Admin dashboard with CRUD operations
├── logout.php          # Logout functionality
├── report.php          # Report generation (HTML/CSV)
├── style.css           # Stylesheet for the entire system
├── database.sql        # Database schema and sample data
└── README.md           # This file
```

## Usage Guide

### For Public Users

1. **View Student List**
   - Navigate to `index.php`
   - Browse through paginated student records
   - Use the search box to find students by name, matric number, or IC number

2. **View Student Details**
   - Click "View Details" on any student in the list
   - See complete information including contact details and program information

3. **Generate Reports**
   - Click "Generate Report" to view all students
   - Use "Print Report" to print the report
   - Use "Download CSV" to export data to Excel/spreadsheet

### For Administrators

1. **Login**
   - Navigate to `login.php`
   - Enter admin credentials (default: admin/admin123)

2. **Manage Students**
   - Add new students using the "Add New Student" button
   - Edit existing students by clicking "Edit" in the student list
   - Delete students by clicking "Delete" (confirmation required)
   - All forms are protected with CSRF tokens

3. **Search and Filter**
   - Use the search box to find specific students
   - Results are paginated for easy browsing

## Database Schema

### Students Table
- `id`: Primary key (auto-increment)
- `matric_no`: Unique matriculation number
- `ic_no`: Unique IC/identification number
- `name`: Full name of the student
- `email`: Email address
- `phone`: Phone number
- `address`: Full address
- `program`: Academic program
- `year_of_study`: Current year (1-6)
- `created_at`: Record creation timestamp
- `updated_at`: Last update timestamp

### Admin Users Table
- `id`: Primary key (auto-increment)
- `username`: Unique admin username
- `password`: Hashed password (bcrypt)
- `created_at`: Account creation timestamp

## Security Notes

### Implemented Security Measures

1. **SQL Injection Prevention**
   - All database queries use prepared statements with parameter binding
   - No user input is directly concatenated into SQL queries

2. **Cross-Site Scripting (XSS) Prevention**
   - All output is escaped using `htmlspecialchars()`
   - HTML special characters are converted to entities

3. **Cross-Site Request Forgery (CSRF) Prevention**
   - All admin forms include CSRF tokens
   - Tokens are validated before processing any state-changing operations

4. **Session Security**
   - Sessions use httponly cookies
   - Sessions use only cookies (not URL parameters)
   - Session data is properly managed and destroyed on logout

5. **Password Security**
   - Passwords are hashed using `password_hash()` with bcrypt
   - Passwords are verified using `password_verify()`

### Recommendations for Production

1. **Change Default Credentials**
   - Update admin password immediately after installation

2. **Use HTTPS**
   - Enable SSL/TLS on your web server
   - Set `session.cookie_secure` to 1 in `config.php`

3. **Database Security**
   - Use a strong database password
   - Create a dedicated database user with limited privileges
   - Don't use root user in production

4. **File Permissions**
   - Ensure proper file permissions (readable but not writable by web server)
   - Keep configuration files secure

5. **Regular Updates**
   - Keep PHP and MySQL updated
   - Monitor for security vulnerabilities

## Sample Data

The system comes with 10 sample student records for testing:
- Matric numbers: M2023001 to M2023010
- Various programs: Computer Science, Information Technology, Software Engineering
- Different year levels: 1 to 4

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check database credentials in `config.php`
- Ensure database `student_system` exists

### Cannot Login
- Verify admin user exists in database
- Default credentials: admin/admin123
- Check if sessions are enabled in PHP

### Search Not Working
- Ensure database indexes are created
- Check if student records exist in database

### Reports Not Generating
- Verify PHP has write permissions for CSV files
- Check browser pop-up settings for print dialog

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is created for educational purposes.

## Support

For issues or questions, please open an issue on GitHub.

## Author

Created for the sudo-nut/stufo-system repository

## Version History

- **v1.0.0** (2026-01-15)
  - Initial release
  - Complete CRUD functionality
  - Search and pagination
  - Report generation
  - Security features (prepared statements, CSRF tokens)
  - Responsive design