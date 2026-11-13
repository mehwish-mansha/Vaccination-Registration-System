
<?php

require_once __DIR__ . '/../includes/layout.php';

require_role('hospital');

$hospitalUserId = user()['id'];

// Find hospital associated with logged-in user
$stmt = $pdo->prepare("
    SELECT id, name
    FROM hospitals
    WHERE user_id = ?
    LIMIT 1
");

$stmt->execute([$hospitalUserId]);

$hospital = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hospital) {
    page_header('Hospital Dashboard');
    echo '<div class="dashboard-container">
        <h2>Hospital Profile Not Found</h2>
        <p>Please contact the administrator.</p>
    </div>';
    page_footer();
    exit;
}

$hospitalId = $hospital['id'];

// Count appointments
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM appointments
    WHERE hospital_id = ?
");

$stmt->execute([$hospitalId]);
$totalAppointments = $stmt->fetchColumn();

// Count approved appointments
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM appointments
    WHERE hospital_id = ?
    AND status = 'approved'
");

$stmt->execute([$hospitalId]);
$approvedAppointments = $stmt->fetchColumn();

// Count pending appointments
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM appointments
    WHERE hospital_id = ?
    AND status = 'pending'
");

$stmt->execute([$hospitalId]);
$pendingAppointments = $stmt->fetchColumn();

page_header('Hospital Dashboard');

?>

<section class="dashboard-section">

    <div class="dashboard-container">

        <div class="dashboard-welcome">

            <div>
                <span class="dashboard-label">HOSPITAL PORTAL</span>

                <h1>
                    Welcome, <?= e($hospital['name']) ?>
                </h1>

                <p>Manage your vaccination appointments.</p>
            </div>

            <div class="welcome-icon">🏥</div>

        </div>

        <div class="dashboard-stats">

            <div class="stat-card">
                <h3>Total Appointments</h3>
                <h2><?= e($totalAppointments) ?></h2>
            </div>

            <div class="stat-card">
                <h3>Approved Appointments</h3>
                <h2><?= e($approvedAppointments) ?></h2>
            </div>

            <div class="stat-card">
                <h3>Pending Appointments</h3>
                <h2><?= e($pendingAppointments) ?></h2>
            </div>

        </div>

       
<div class="dashboard-panel">

    <h2>Hospital Management</h2>

    <div class="dashboard-links">

        <a href="<?= e(url('hospital/appointments.php')) ?>">
            View Appointments
        </a>

        <a href="<?= e(url('hospital/dashboard.php')) ?>">
            Hospital Dashboard
        </a>

    </div>

</div>

    </div>

</section>

<?php page_footer(); ?>
