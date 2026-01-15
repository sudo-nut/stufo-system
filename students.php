<?php
/**
 * Student Database Operations
 * Uses mysqli prepared statements for security
 */

require_once 'config.php';

/**
 * Get total number of students with optional search
 */
function getStudentCount($search = '') {
    $conn = getDBConnection();
    
    if (!empty($search)) {
        $sql = "SELECT COUNT(*) as count FROM students 
                WHERE name LIKE ? OR matric_no LIKE ? OR ic_no LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchParam = "%{$search}%";
        $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    } else {
        $sql = "SELECT COUNT(*) as count FROM students";
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $row['count'];
}

/**
 * Get students with pagination and search
 */
function getStudents($limit = 10, $offset = 0, $search = '') {
    $conn = getDBConnection();
    
    if (!empty($search)) {
        $sql = "SELECT * FROM students 
                WHERE name LIKE ? OR matric_no LIKE ? OR ic_no LIKE ?
                ORDER BY name ASC 
                LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $searchParam = "%{$search}%";
        $stmt->bind_param("sssii", $searchParam, $searchParam, $searchParam, $limit, $offset);
    } else {
        $sql = "SELECT * FROM students ORDER BY name ASC LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
    
    $stmt->close();
    $conn->close();
    
    return $students;
}

/**
 * Get a single student by ID
 */
function getStudentById($id) {
    $conn = getDBConnection();
    
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $student;
}

/**
 * Add a new student
 */
function addStudent($matric_no, $ic_no, $name, $email, $phone, $address, $program, $year_of_study) {
    $conn = getDBConnection();
    
    $sql = "INSERT INTO students (matric_no, ic_no, name, email, phone, address, program, year_of_study) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $matric_no, $ic_no, $name, $email, $phone, $address, $program, $year_of_study);
    
    $success = $stmt->execute();
    $insert_id = $conn->insert_id;
    
    $stmt->close();
    $conn->close();
    
    return $success ? $insert_id : false;
}

/**
 * Update an existing student
 */
function updateStudent($id, $matric_no, $ic_no, $name, $email, $phone, $address, $program, $year_of_study) {
    $conn = getDBConnection();
    
    $sql = "UPDATE students 
            SET matric_no = ?, ic_no = ?, name = ?, email = ?, phone = ?, address = ?, program = ?, year_of_study = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssii", $matric_no, $ic_no, $name, $email, $phone, $address, $program, $year_of_study, $id);
    
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $success;
}

/**
 * Delete a student
 */
function deleteStudent($id) {
    $conn = getDBConnection();
    
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $success;
}

/**
 * Get all students for report generation (no pagination)
 */
function getAllStudents() {
    $conn = getDBConnection();
    
    $sql = "SELECT * FROM students ORDER BY name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
    
    $stmt->close();
    $conn->close();
    
    return $students;
}

/**
 * Verify admin login
 */
function verifyAdminLogin($username, $password) {
    $conn = getDBConnection();
    
    $sql = "SELECT id, password FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    if ($admin && password_verify($password, $admin['password'])) {
        return true;
    }
    
    return false;
}
?>
