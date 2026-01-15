```markdown
# Student Information System (PHP + MySQL)

This repository contains a simple Student Information System implemented with standard PHP and MySQL.

Default configuration:
- Database: DBASSIGNMENT
- DB host: localhost
- DB user: root
- DB password: (empty)
- Default accounts:
  - Admin: username `admin01`, password `admin123`
  - Student: username `student01`, password `student123`

Setup steps:

1. Create the database and tables:
   - Import `sql/init_db.sql` into MySQL:
     - mysql -u root -p < sql/init_db.sql
   - Or use your preferred DB tool.

2. Seed users:
   - Run `php scripts/seed_users.php`
   - This will insert or update admin01 and student01 with hashed passwords.

3. Generate and insert 400 students:
   - Dry-run: `php scripts/generate_students.php`
   - Insert into DB: `php scripts/generate_students.php --commit`

4. Web server:
   - Place the `public/` directory into your web server root, or run PHP built-in server:
     - cd public
     - php -S localhost:8000
   - Then browse to http://localhost:8000/

Notes:
- All database interactions use mysqli prepared statements.
- Admin pages are protected with role checks and CSRF tokens.
- Student list supports search by name, matric_no, and ic_no and has pagination (20 per page).
- Change default DB credentials in `public/db.php` if needed.
```