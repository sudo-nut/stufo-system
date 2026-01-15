<?php
/**
 * generate_students.php
 * Generates sample student records for testing
 */

require_once __DIR__ . '/../public/db.php';

// Sample data arrays
$first_names = ['Muhammad', 'Ahmad', 'Ali', 'Siti', 'Fatimah', 'Nurul', 'Amirah', 'Aisyah', 'Hassan', 'Hussein'];
$last_names = ['Abdullah', 'Rahman', 'Ibrahim', 'Hassan', 'Ahmad', 'Ismail', 'Yusof', 'Mohamed', 'Ali', 'Omar'];
$programmes = ['Computer Science', 'Information Technology', 'Software Engineering', 'Data Science', 'Cybersecurity'];
$faculties = ['Faculty of Computing', 'Faculty of Engineering', 'Faculty of Science'];
$genders = ['Male', 'Female'];

// Generate 50 student records
for ($i = 1; $i <= 50; $i++) {
    $matric_no = 'A' . str_pad($i, 6, '0', STR_PAD_LEFT);
    $first_name = $first_names[array_rand($first_names)];
    $last_name = $last_names[array_rand($last_names)];
    $name = $first_name . ' ' . $last_name;
    $ic_no = rand(900000, 999999) . '-' . rand(10, 14) . '-' . rand(1000, 9999);
    $gender = $genders[array_rand($genders)];
    $programme = $programmes[array_rand($programmes)];
    $faculty = $faculties[array_rand($faculties)];
    $semester = rand(1, 8);
    $email = strtolower(str_replace(' ', '.', $name)) . '@student.edu.my';
    $phone_no = '01' . rand(0, 9) . '-' . rand(1000000, 9999999);
    $address = rand(1, 999) . ' Jalan ' . $last_names[array_rand($last_names)] . ', ' . rand(10000, 99999) . ' Kuala Lumpur';
    
    $stmt = $conn->prepare("INSERT INTO STUDENT (matric_no, name, ic_no, gender, programme, faculty, semester, email, phone_no, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE matric_no=matric_no");
    $stmt->bind_param("ssssssisss", $matric_no, $name, $ic_no, $gender, $programme, $faculty, $semester, $email, $phone_no, $address);
    
    if ($stmt->execute()) {
        echo "Student $i created: $matric_no - $name\n";
    } else {
        echo "Error creating student $i: " . $stmt->error . "\n";
    }
    
    $stmt->close();
}

$conn->close();
echo "Student generation completed.\n";
?>
