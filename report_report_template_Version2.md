```markdown
# Student Information System — Report

## 1. Introduction
- Describe the original static website (if any).
- Strengths and weaknesses.
- Brief overview of this system: backend (PHP + MySQL), frontend (HTML/CSS), optional JS.

## 2. System Design
### 2.1 Site Map
- Home (index.php)
- About (about.php)
- Login (login.php)
- Students list (students.php)
- Student view (student_view.php)
- Admin management: add/edit/delete (student_add.php, student_edit.php, student_delete.php)

### 2.2 User Interface Design
- Simple, clean layout with header/footer.
- Role-aware navigation (admin vs student).

## 3. Implementation

### 3.1 Login
- Description: Authenticates users in `USER` table using password_verify().
- Screenshot: (place screenshot here)
- Source code: ```php
<?php
// login.php
// ... paste relevant code
?>
```

### 3.2 Add Student (Admin)
- Description: Admin creates a new student; CSRF token validated.
- Screenshot: (place screenshot here)
- Source code: student_add.php

### 3.3 Update Student
- Description and code (student_edit.php)
- Screenshot placeholder

### 3.4 Delete Student
- Description and code (student_delete.php)
- Screenshot placeholder

### 3.5 View Student
- Description and code (student_view.php)
- Screenshot placeholder

## 4. Conclusion
- Summary of implemented features.
- Learning outcomes.
- Future improvements: stronger validation, password resets, file uploads, export/import.

## Appendix
- SQL DDL: include contents of sql/init_db.sql
- Seed scripts: include scripts/seed_users.php and scripts/generate_students.php
```