<?php
// scripts/generate_students.php
// CLI generator to create and insert 400 STUDENT records for the DBASSIGNMENT.STUDENT table.
// Usage:
//   php scripts/generate_students.php           -> dry-run (prints 10 samples, does NOT insert)
//   php scripts/generate_students.php --commit  -> inserts 400 records into the database
//   php scripts/generate_students.php --help    -> show usage
//
// Notes:
// - This script expects public/db.php to exist and expose $conn (mysqli).
// - It inserts values for both the new uppercase fields (FULLNAME, ADDRESS1, ...) and the
//   previous lowercase fields (matric_no, name, ic_no, programme, faculty, semester, phone_no, address).
// - STUDENTID is AUTO_INCREMENT and created_at is left to database default.

declare(strict_types=1);

require_once __DIR__ . '/../public/db.php';

// CLI args safe handling
$cliArgs = $_SERVER['argv'] ?? [];
if (in_array('--help', $cliArgs, true) || in_array('-h', $cliArgs, true)) {
    echo "Usage:\n";
    echo "  php generate_students.php           # dry-run (no DB writes)\n";
    echo "  php generate_students.php --commit  # insert into DB\n";
    echo "  php generate_students.php --help    # show this help\n";
    exit(0);
}
$commit = in_array('--commit', $cliArgs, true);

$totalToGenerate = 400;

// Pools for synthetic data
$firstNames = ['Muhammad','Aiman','Nurul','Siti','Ahmad','Hafiz','Farah','Syafiq','Nadia','Izzat','Lina','Daniel','Amir','Haziq','Maya','Putri','Leong','Shawn','Rina','Ethan'];
$lastNames  = ['Ismail','Abdullah','Rahman','Hassan','Ali','Khalid','Ibrahim','Yusof','Zainal','Kamarul','Sulaiman','Lim','Tan','Lee','Wong','Kong','Zulkifli','Omar','Azman','Amran'];
$programmes = ['Information Technology','Computer Science','Software Engineering','Information Systems'];
$faculties  = ['Faculty of Computing','Faculty of Engineering','Faculty of Science'];
$genders    = ['M','F'];
$races      = ['Malay','Chinese','Indian','Other'];
$religions  = ['Islam','Christian','Buddhist','Hindu','Other'];
$cities     = ['Kuala Lumpur','George Town','Ipoh','Johor Bahru','Kuantan','Melaka','Alor Setar'];
$states     = ['Kuala Lumpur','Penang','Perak','Johor','Pahang','Melaka','Kedah'];
$streetNames = ['Jalan Bukit','Lorong Mawar','Taman Jaya','Jalan Bunga','Jalan Meranti'];
$areaNames = ['Taman Intan','Taman Ria','Taman Murni','Taman Desa','Taman Indah'];

function randName(array $first, array $last): string {
    return $first[array_rand($first)] . ' ' . $last[array_rand($last)];
}
function randAddress1(array $streets): string {
    $no = rand(1, 300);
    return "No. $no, " . $streets[array_rand($streets)];
}
function randAddress2(array $areas): string {
    return $areas[array_rand($areas)];
}
function randPostcode(): string {
    // ensure 6 characters as requested (pad with leading zeros if necessary)
    return str_pad((string)rand(1000, 999999), 6, '0', STR_PAD_LEFT);
}
function randContactNo(): string {
    // produce Malaysian-like numbers including +60 (length up to 12)
    return '+60' . str_pad((string)rand(100000000, 999999999), 9, '0', STR_PAD_LEFT); // +60XXXXXXXXX (12)
}

// Build records
$records = [];
$yearPrefix = '2023';
for ($i = 1; $i <= $totalToGenerate; $i++) {
    $matric_no = $yearPrefix . str_pad((string)$i, 4, '0', STR_PAD_LEFT); // e.g. 20230001
    $ic_no = '99' . str_pad((string)$i, 8, '0', STR_PAD_LEFT); // synthetic ic

    $name = randName($firstNames, $lastNames);
    $fullname = $name;
    $address1 = randAddress1($streetNames);
    $address2 = randAddress2($areaNames);
    $postcode = randPostcode();
    $city = $cities[array_rand($cities)];
    $state = $states[array_rand($states)];
    $gender = $genders[$i % 2];
    $race = $races[array_rand($races)];
    $religion = $religions[array_rand($religions)];
    $contactno = randContactNo();
    $email = strtolower(str_replace(' ', '.', $name)) . $i . '@uni.edu';

    $programme = $programmes[array_rand($programmes)];
    $faculty = $faculties[array_rand($faculties)];
    $semester = ($i % 8) + 1;
    $phone_no = $contactno;
    $address = $address1 . ', ' . $address2 . ', ' . $city;

    $records[] = [
        'FULLNAME'  => $fullname,
        'ADDRESS1'  => $address1,
        'ADDRESS2'  => $address2,
        'POSTCODE'  => $postcode,
        'CITY'      => $city,
        'STATE'     => $state,
        'GENDER'    => $gender,
        'RACE'      => $race,
        'RELIGION'  => $religion,
        'CONTACTNO' => $contactno,
        'EMAIL'     => $email,
        'matric_no' => $matric_no,
        'name'      => $name,
        'ic_no'     => $ic_no,
        'programme' => $programme,
        'faculty'   => $faculty,
        'semester'  => $semester,
        'phone_no'  => $phone_no,
        'address'   => $address
    ];
}

// Dry-run: display a sample of the first 10 records
echo $commit ? "COMMIT mode: will insert {$totalToGenerate} records.\n" : "DRY-RUN mode: showing 10 sample records (no DB writes). Use --commit to insert.\n";
for ($i = 0; $i < min(10, count($records)); $i++) {
    $r = $records[$i];
    echo sprintf("%s | %s | %s | %s | %s | %d | %s\n",
        $r['matric_no'],
        $r['name'],
        $r['ic_no'],
        $r['programme'],
        $r['faculty'],
        $r['semester'],
        $r['EMAIL']
    );
}
if (!$commit) {
    exit(0);
}

// Insert into DB
echo "Connecting to DB and inserting records...\n";

if (!isset($conn) || !($conn instanceof mysqli)) {
    fwrite(STDERR, "Database connection (\$conn) not found. Ensure public/db.php exists and exports \$conn (mysqli).\n");
    exit(1);
}

$conn->begin_transaction();

$insertSql = "INSERT INTO `STUDENT` (
    FULLNAME, ADDRESS1, ADDRESS2, POSTCODE, CITY, STATE, GENDER, RACE, RELIGION,
    CONTACTNO, EMAIL, matric_no, name, ic_no, programme, faculty, semester, phone_no, address
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($insertSql);
if ($stmt === false) {
    fwrite(STDERR, "Prepare failed: " . $conn->error . PHP_EOL);
    $conn->rollback();
    exit(1);
}

// types: 16 strings then 1 int then 2 strings -> 'ssssssssssssssssiss'
$types = 'ssssssssssssssssiss';

$inserted = 0;
foreach ($records as $idx => $r) {
    // assign to variables (bind_param requires variables passed by reference)
    $v_fullname  = $r['FULLNAME'];
    $v_address1  = $r['ADDRESS1'];
    $v_address2  = $r['ADDRESS2'];
    $v_postcode  = $r['POSTCODE'];
    $v_city      = $r['CITY'];
    $v_state     = $r['STATE'];
    $v_gender    = $r['GENDER'];
    $v_race      = $r['RACE'];
    $v_religion  = $r['RELIGION'];
    $v_contactno = $r['CONTACTNO'];
    $v_email     = $r['EMAIL'];
    $v_matric_no = $r['matric_no'];
    $v_name      = $r['name'];
    $v_ic_no     = $r['ic_no'];
    $v_programme = $r['programme'];
    $v_faculty   = $r['faculty'];
    $v_semester  = $r['semester']; // integer
    $v_phone_no  = $r['phone_no'];
    $v_address   = $r['address'];

    $bindOk = $stmt->bind_param(
        $types,
        $v_fullname,
        $v_address1,
        $v_address2,
        $v_postcode,
        $v_city,
        $v_state,
        $v_gender,
        $v_race,
        $v_religion,
        $v_contactno,
        $v_email,
        $v_matric_no,
        $v_name,
        $v_ic_no,
        $v_programme,
        $v_faculty,
        $v_semester,
        $v_phone_no,
        $v_address
    );

    if ($bindOk === false) {
        fwrite(STDERR, "bind_param failed on record {$idx}: " . $stmt->error . PHP_EOL);
        $conn->rollback();
        exit(1);
    }

    if ($stmt->execute() === false) {
        fwrite(STDERR, "Execute failed on matric_no {$v_matric_no}: " . $stmt->error . PHP_EOL);
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

echo "Done. Inserted {$inserted} records into STUDENT.\n";
exit(0);