<?php
/**
 * Student Data Generation Script
 * 
 * This CLI script generates 400 unique student records with realistic data.
 * By default, runs in dry-run mode showing sample data.
 * Use --commit flag to insert data into the database.
 * 
 * Usage: 
 *   php scripts/generate_students.php          (dry-run mode)
 *   php scripts/generate_students.php --commit (insert into database)
 */

// Ensure this script is run from CLI only
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Include database configuration
require_once __DIR__ . '/../public/db.php';

// Check for --commit flag
$commit_mode = in_array('--commit', $argv);

echo "=== Student Data Generation Script ===\n";
echo "Database: " . DB_NAME . "\n";
echo "Mode: " . ($commit_mode ? "COMMIT (inserting data)" : "DRY-RUN (preview only)") . "\n\n";

// Sample data for realistic generation
$first_names = [
    'Ahmad', 'Ali', 'Amirul', 'Aziz', 'Daniel', 'David', 'Farid', 'Hafiz', 'Haris', 'Haziq',
    'Irfan', 'Ismail', 'Khairul', 'Lukman', 'Muhammad', 'Nabil', 'Nasir', 'Omar', 'Razak', 'Syafiq',
    'Aisyah', 'Alya', 'Amira', 'Anis', 'Elina', 'Farah', 'Hannah', 'Iman', 'Lisa', 'Nadia',
    'Nur', 'Nurul', 'Sarah', 'Siti', 'Sofea', 'Sophia', 'Syahira', 'Yasmin', 'Zara', 'Zulaikha'
];

$last_names = [
    'Abdullah', 'Ahmad', 'Ali', 'Arif', 'Aziz', 'Bakar', 'Farouk', 'Halim', 'Hamid', 'Hassan',
    'Ibrahim', 'Ismail', 'Jamal', 'Khan', 'Lee', 'Malik', 'Noor', 'Omar', 'Rahman', 'Rashid',
    'Said', 'Salim', 'Singh', 'Tan', 'Yusof', 'Zain', 'Wong', 'Lim', 'Chong', 'Ng'
];

$programs = [
    'Bachelor of Computer Science',
    'Bachelor of Information Technology',
    'Bachelor of Software Engineering',
    'Bachelor of Business Administration',
    'Bachelor of Accounting',
    'Bachelor of Engineering',
    'Bachelor of Science',
    'Bachelor of Arts'
];

$genders = ['Male', 'Female'];

// Function to generate unique matric number
function generateMatricNo($index) {
    $year = 2022 + ($index % 4); // Spread across 4 years
    $sequence = str_pad($index + 1, 5, '0', STR_PAD_LEFT);
    return "M{$year}{$sequence}";
}

// Function to generate unique IC number (Malaysian format: YYMMDD-PB-###G)
function generateIcNo($index, $dob) {
    $dob_parts = explode('-', $dob);
    $yy = substr($dob_parts[0], 2, 2);
    $mm = $dob_parts[1];
    $dd = $dob_parts[2];
    $pb = str_pad(($index % 14) + 1, 2, '0', STR_PAD_LEFT); // Place of birth code
    $seq = str_pad($index % 9999, 4, '0', STR_PAD_LEFT);
    return "{$yy}{$mm}{$dd}-{$pb}-{$seq}";
}

// Function to generate email from name
function generateEmail($first_name, $last_name, $matric_no) {
    $first = strtolower(str_replace(' ', '', $first_name));
    $last = strtolower(str_replace(' ', '', $last_name));
    return "{$first}.{$last}@student.university.edu";
}

// Function to generate phone number (Malaysian format)
function generatePhone($index) {
    $prefix = ['012', '013', '014', '016', '017', '018', '019'];
    $chosen_prefix = $prefix[$index % count($prefix)];
    $number = str_pad($index % 10000000, 7, '0', STR_PAD_LEFT);
    return "{$chosen_prefix}-{$number}";
}

// Function to generate address
function generateAddress($index) {
    $streets = ['Jalan Merdeka', 'Jalan Raja', 'Jalan Sultan', 'Jalan Ampang', 'Jalan Tun Razak'];
    $cities = ['Kuala Lumpur', 'Petaling Jaya', 'Shah Alam', 'Subang Jaya', 'Selangor'];
    
    $house_no = ($index % 999) + 1;
    $street = $streets[$index % count($streets)];
    $postcode = 50000 + ($index % 1000);
    $city = $cities[$index % count($cities)];
    
    return "{$house_no}, {$street}, {$postcode} {$city}, Malaysia";
}

// Function to generate date of birth
function generateDOB($index) {
    $year = 1998 + ($index % 8); // Ages 18-25
    $month = str_pad(($index % 12) + 1, 2, '0', STR_PAD_LEFT);
    $day = str_pad(($index % 28) + 1, 2, '0', STR_PAD_LEFT);
    return "{$year}-{$month}-{$day}";
}

// Generate 400 students
$students = [];
echo "Generating 400 student records...\n\n";

for ($i = 0; $i < 400; $i++) {
    $first_name = $first_names[array_rand($first_names)];
    $last_name = $last_names[array_rand($last_names)];
    $full_name = "{$first_name} bin {$last_name}";
    
    // Adjust name format for female students
    $gender = $genders[$i % 2];
    if ($gender === 'Female') {
        $full_name = "{$first_name} binti {$last_name}";
    }
    
    $dob = generateDOB($i);
    $matric_no = generateMatricNo($i);
    $ic_no = generateIcNo($i, $dob);
    $email = generateEmail($first_name, $last_name, $matric_no);
    $phone = generatePhone($i);
    $address = generateAddress($i);
    $program = $programs[$i % count($programs)];
    $year_of_study = ($i % 4) + 1;
    
    $students[] = [
        'matric_no' => $matric_no,
        'ic_no' => $ic_no,
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'date_of_birth' => $dob,
        'gender' => $gender,
        'program' => $program,
        'year_of_study' => $year_of_study
    ];
}

// Display sample in dry-run mode
if (!$commit_mode) {
    echo "Sample of generated students (showing first 5):\n";
    echo str_repeat('-', 100) . "\n";
    
    for ($i = 0; $i < min(5, count($students)); $i++) {
        $s = $students[$i];
        echo "Student " . ($i + 1) . ":\n";
        echo "  Matric No: {$s['matric_no']}\n";
        echo "  IC No: {$s['ic_no']}\n";
        echo "  Name: {$s['full_name']}\n";
        echo "  Email: {$s['email']}\n";
        echo "  Phone: {$s['phone']}\n";
        echo "  Gender: {$s['gender']}\n";
        echo "  Program: {$s['program']}\n";
        echo "  Year: {$s['year_of_study']}\n";
        echo "\n";
    }
    
    echo str_repeat('-', 100) . "\n";
    echo "Total students generated: " . count($students) . "\n\n";
    echo "To insert these records into the database, run:\n";
    echo "  php scripts/generate_students.php --commit\n";
} else {
    // Insert students into database
    echo "Inserting students into database...\n";
    
    $insert_stmt = $conn->prepare(
        "INSERT INTO STUDENT (matric_no, ic_no, full_name, email, phone, address, date_of_birth, gender, program, year_of_study) " .
        "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($students as $index => $student) {
        $insert_stmt->bind_param(
            "sssssssssi",
            $student['matric_no'],
            $student['ic_no'],
            $student['full_name'],
            $student['email'],
            $student['phone'],
            $student['address'],
            $student['date_of_birth'],
            $student['gender'],
            $student['program'],
            $student['year_of_study']
        );
        
        if ($insert_stmt->execute()) {
            $success_count++;
            if (($index + 1) % 50 == 0) {
                echo "  Progress: " . ($index + 1) . " / 400 students inserted...\n";
            }
        } else {
            $error_count++;
            echo "  Error inserting student {$student['matric_no']}: " . $insert_stmt->error . "\n";
        }
    }
    
    $insert_stmt->close();
    
    echo "\n=== Insert Complete ===\n";
    echo "Successfully inserted: {$success_count} students\n";
    echo "Errors: {$error_count}\n";
}

$conn->close();
