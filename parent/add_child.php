
<?php

require_once __DIR__ . '/../includes/functions.php';

require_role('parent');


// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('parent/dashboard.php');
}


// Check CSRF token
if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    set_flash('Invalid form submission. Please try again.', 'error');
    redirect('parent/dashboard.php');
}


// Get form values
$name = trim($_POST['name'] ?? '');
$dob = trim($_POST['dob'] ?? '');
$gender = $_POST['gender'] ?? '';
$bloodGroup = $_POST['blood_group'] ?? '';
$guardianName = trim($_POST['guardian_name'] ?? '');


// Validate name
if ($name === '' || strlen($name) > 100) {
    set_flash('Please enter a valid child name.', 'error');
    redirect('parent/dashboard.php');
}


// Validate date of birth
$date = DateTime::createFromFormat('!Y-m-d', $dob);

if (
    !$date ||
    $date->format('Y-m-d') !== $dob ||
    $dob > date('Y-m-d')
) {
    set_flash('Please enter a valid date of birth.', 'error');
    redirect('parent/dashboard.php');
}


// Validate gender
if (!in_array($gender, ['Boy', 'Girl'], true)) {
    set_flash('Please select a valid gender.', 'error');
    redirect('parent/dashboard.php');
}


// Validate blood group
$allowedGroups = [
    '', 'A+', 'A-', 'B+', 'B-',
    'AB+', 'AB-', 'O+', 'O-'
];

if (!in_array($bloodGroup, $allowedGroups, true)) {
    set_flash('Please select a valid blood group.', 'error');
    redirect('parent/dashboard.php');
}


// Validate guardian name
if ($guardianName === '' || strlen($guardianName) > 100) {
    set_flash('Please enter the guardian name.', 'error');
    redirect('parent/dashboard.php');
}


// Insert child
try {

    $stmt = $pdo->prepare("
        INSERT INTO children
        (
            parent_id,
            name,
            dob,
            gender,
            blood_group,
            guardian_name
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        user()['id'],
        $name,
        $dob,
        $gender,
        $bloodGroup ?: null,
        $guardianName
    ]);

    set_flash('Child registered successfully!', 'success');

} catch (PDOException $e) {

    set_flash('Unable to register child. Please try again.', 'error');

}

redirect('parent/dashboard.php');

?>
