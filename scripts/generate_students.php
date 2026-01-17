<?php
// scripts/generate_students.php
// Generates and inserts 400 STUDENT records using columns:
// FULLNAME, ADDRESS1, ADDRESS2, POSTCODE, CITY, STATE, GENDER, RACE, RELIGION,
// CONTACTNO, EMAIL, matric_no, ic_no, programme, faculty, semester
//
// Usage:
//   php generate_students.php           # dry-run (prints samples)
//   php generate_students.php --commit  # insert into DB
declare(strict_types=1);

require_once __DIR__ . '/../public/db.php';

$cliArgs = $_SERVER['argv'] ?? [];
if (in_array('--help', $cliArgs, true) || in_array('-h', $cliArgs, true)) {
    echo "Usage:\n  php generate_students.php [--commit]\n";
    exit(0);
}
$commit = in_array('--commit', $cliArgs, true);

$total = 400;

$firstNames = ['Muhammad','Aiman','Nurul','Siti','Ahmad','Hafiz','Farah','Syafiq','Nadia','Izzat','Lina','Daniel','Amir','Haziq','Maya','Putri','Leong','Shawn','Rina','Ethan'];
$lastNames  = ['Ismail','Abdullah','Rahman','Hassan','Ali','Khalid','Ibrahim','Yusof','Zainal','Kamarul','Sulaiman','Lim','Tan','Lee','Wong','Kong','Zulkifli','Omar','Azman','Amran'];
$programmes = ['Information Technology','Computer Science','Software Engineering','Information Systems'];
$faculties  = ['Faculty of Computing','Faculty of Engineering','Faculty of Science'];
$genders    = ['M','F'];
$races      = ['Malay','Chinese','Indian','Other'];
$religions  = ['Islam','Christian','Buddhist','Hindu','Other'];
$cities     = ['Kuala Lumpur','George Town','Ipoh','Johor Bahru','Kuantan','Melaka','Alor Setar'];
$states     = ['Kuala Lumpur','Penang','Perak','Johor','Pahang','Melaka','Kedah'];
$streets    = ['Jalan Bukit','Lorong Mawar','Taman Jaya','Jalan Bunga','Jalan Meranti'];
$areas      = ['Taman Intan','Taman Ria','Taman Murni','Taman Desa','Taman Indah'];

function randName(array $fn, array $ln): string { return $fn[array_rand($fn)] . ' ' . $ln[array_rand($ln)]; }
function randAddress1(array $streets): string { return 'No. ' . rand(1,300) . ' ' . $streets[array_rand($streets)]; }
function randAddress2(array $areas): string { return $areas[array_rand($areas)]; }
function randPostcode(): string { return str_pad((string)rand(1000,999999), 6, '0', STR_PAD_LEFT); }
function randContact(): string { return '+60' . str_pad((string)rand(100000000,999999999), 9, '0', STR_PAD_LEFT); }

$records = [];
$year = '2023';
for ($i = 1; $i <= $total; $i++) {
    $matric = $year . str_pad((string)$i, 4, '0', STR_PAD_LEFT);
    $ic = '99' . str_pad((string)$i, 8, '0', STR_PAD_LEFT);

    $name = randName($firstNames, $lastNames);
    $records[] = [
        'FULLNAME'  => $name,
        'ADDRESS1'  => randAddress1($streets),
        'ADDRESS2'  => randAddress2($areas),
        'POSTCODE'  => randPostcode(),
        'CITY'      => $cities[array_rand($cities)],
        'STATE'     => $states[array_rand($states)],
        'GENDER'    => $genders[$i % 2],
        'RACE'      => $races[array_rand($races)],
        'RELIGION'  => $religions[array_rand($religions)],
        'CONTACTNO' => randContact(),
        'EMAIL'     => strtolower(str_replace(' ', '.', $name)) . $i . '@uni.edu',
        'matric_no' => $matric,
        'ic_no'     => $ic,
        'programme' => $programmes[array_rand($programmes)],
        'faculty'   => $faculties[array_rand($faculties)],
        'semester'  => ($i % 8) + 1
    ];
}

// Dry-run: print first 10 full records as JSON for inspection
if (!$commit) {
    echo "DRY-RUN: showing 10 sample records (no DB writes). Use --commit to insert.\n";
    for ($i = 0; $i < min(10, count($records)); $i++) {
        echo json_encode($records[$i], JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
    exit(0);
}

// Commit mode: insert to DB
if (!isset($conn) || !($conn instanceof mysqli)) {
    fwrite(STDERR, "No DB connection found in public/db.php (expecting \$conn as mysqli).\n");
    exit(1);
}

$conn->begin_transaction();
$sql = "INSERT INTO `STUDENT` (
    FULLNAME, ADDRESS1, ADDRESS2, POSTCODE, CITY, STATE, GENDER, RACE, RELIGION,
    CONTACTNO, EMAIL, matric_no, ic_no, programme, faculty, semester
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    fwrite(STDERR, "Prepare failed: " . $conn->error . PHP_EOL);
    $conn->rollback();
    exit(1);
}
$types = 'sssssssssssssssi'; // 15 strings + 1 integer
$inserted = 0;
foreach ($records as $idx => $r) {
    $v_FULLNAME = $r['FULLNAME'];
    $v_ADDRESS1 = $r['ADDRESS1'];
    $v_ADDRESS2 = $r['ADDRESS2'];
    $v_POSTCODE = $r['POSTCODE'];
    $v_CITY     = $r['CITY'];
    $v_STATE    = $r['STATE'];
    $v_GENDER   = $r['GENDER'];
    $v_RACE     = $r['RACE'];
    $v_RELIGION = $r['RELIGION'];
    $v_CONTACT  = $r['CONTACTNO'];
    $v_EMAIL    = $r['EMAIL'];
    $v_matric   = $r['matric_no'];
    $v_ic       = $r['ic_no'];
    $v_prog     = $r['programme'];
    $v_faculty  = $r['faculty'];
    $v_semester = $r['semester'];

    $ok = $stmt->bind_param(
        $types,
        $v_FULLNAME, $v_ADDRESS1, $v_ADDRESS2, $v_POSTCODE, $v_CITY, $v_STATE,
        $v_GENDER, $v_RACE, $v_RELIGION, $v_CONTACT, $v_EMAIL,
        $v_matric, $v_ic, $v_prog, $v_faculty, $v_semester
    );
    if ($ok === false) {
        fwrite(STDERR, "bind_param failed at index {$idx}: " . $stmt->error . PHP_EOL);
        $conn->rollback();
        exit(1);
    }
    if ($stmt->execute() === false) {
        fwrite(STDERR, "Execute failed for matric {$v_matric}: " . $stmt->error . PHP_EOL);
        $conn->rollback();
        exit(1);
    }
    $inserted++;
    if ($inserted % 50 === 0) {
        echo "Inserted {$inserted} records...\n";
    }
}
$conn->commit();
$stmt->close();
$conn->close();
echo "Done. Inserted {$inserted} records.\n";