<?php
// scripts/generate_students.php
// Generates 400 realistic-looking unique STUDENT records.
// Usage:
//   php scripts/generate_students.php        -> dry-run (prints first 10 generated rows)
//   php scripts/generate_students.php --commit  -> inserts into DB

require_once __DIR__ . '/../public/db.php';

$commit = in_array('--commit', $argv, true);

$firstNames = ['Muhammad','Aiman','Nurul','Siti','Ahmad','Hafiz','Farah','Syafiq','Nadia','Izzat','Lina','Daniel','Amir','Haziq','Maya','Putri','Leong','Shawn','Rina','Ethan'];
$lastNames  = ['Ismail','Abdullah','Rahman','Hassan','Ali','Khalid','Ibrahim','Yusof','Zainal','Kamarul','Sulaiman','Lim','Tan','Lee','Wong','Kong','Zulkifli','Omar','Azman','Amran'];
$programmes = ['Information Technology','Computer Science','Software Engineering','Information Systems'];
$faculties  = ['Faculty of Computing','Faculty of Engineering','Faculty of Science'];
$genders    = ['Male','Female'];

function randName($firstNames, $lastNames) {
    return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
}

$records = [];
$usedMatric = [];
$usedIc = [];

$yearPrefix = '2023';

for ($n = 1; $n <= 400; $n++) {
    // ensure unique matric_no: 2023 + 4-digit index
    $matric_no = $yearPrefix . str_pad($n, 4, '0', STR_PAD_LEFT);

    // ensure unique ic_no: 9901 + 6-digit unique (simple synthetic)
    $ic_no = '99' . str_pad($n, 8, '0', STR_PAD_LEFT);

    $name = randName($firstNames, $lastNames);
    $gender = $genders[$n % 2];
    $programme = $programmes[array_rand($programmes)];
    $faculty = $faculties[array_rand($faculties)];
    $semester = ($n % 8) + 1;
    $email = strtolower(str_replace(' ', '.', $name)) . $n . '@uni.edu';
    $phone_no = '01' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
    $address = 'Malaysia';

    $records[] = [
        'matric_no' => $matric_no,
        'name' => $name,
        'ic_no' => $ic_no,
        'gender' => $gender,
        'programme' => $programme,
        'faculty' => $faculty,
        'semester' => $semester,
        'email' => $email,
        'phone_no' => $phone_no,
        'address' => $address
    ];
}

// Dry-run: show first 10
echo ($commit ? "COMMIT MODE: inserting 400 records...\n" : "DRY-RUN MODE: showing 10 sample records. To insert use --commit\n");
for ($i = 0; $i < 10; $i++) {
    $r = $records[$i];
    echo sprintf("%s | %s | %s | %s | %s | %d | %s | %s\n",
        $r['matric_no'], $r['name'], $r['ic_no'], $r['programme'], $r['faculty'],
        $r['semester'], $r['email'], $r['phone_no']
    );
}

if (!$commit) {
    exit(0);
}

// Insert into DB in a transaction with prepared statement
$conn->begin_transaction();
$insertSql = "INSERT INTO `STUDENT` (matric_no, name, ic_no, gender, programme, faculty, semester, email, phone_no, address)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertSql);
if (!$stmt) {
    echo "Prepare failed: " . $conn->error . "\n";
    exit(1);
}
$batch = 0;
foreach ($records as $r) {
    $stmt->bind_param(
        'ssssssisss',
        $r['matric_no'],
        $r['name'],
        $r['ic_no'],
        $r['gender'],
        $r['programme'],
        $r['faculty'],
        $r['semester'],
        $r['email'],
        $r['phone_no'],
        $r['address']
    );
    $ok = $stmt->execute();
    if (!$ok) {
        echo "Insert failed for {$r['matric_no']}: " . $stmt->error . "\n";
        $conn->rollback();
        exit(1);
    }
    $batch++;
    if ($batch % 50 === 0) {
        echo "Inserted $batch records...\n";
    }
}
$conn->commit();
$stmt->close();
$conn->close();
echo "Done: 400 records inserted.\n";