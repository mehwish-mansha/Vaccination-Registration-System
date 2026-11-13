
<?php
require_once __DIR__ . '/../includes/layout.php';

require_role('admin');

$totalUsers = $pdo->query("
    SELECT COUNT(*) FROM users
")->fetchColumn();

$totalChildren = $pdo->query("
    SELECT COUNT(*) FROM children
")->fetchColumn();

$totalHospitals = $pdo->query("
    SELECT COUNT(*) FROM hospitals
")->fetchColumn();

$totalAppointments = $pdo->query("
    SELECT COUNT(*) FROM appointments
")->fetchColumn();

$pendingAppointments = $pdo->query("
    SELECT COUNT(*) FROM appointments
    WHERE status = 'pending'
")->fetchColumn();

page_header();
?>

<div class="dashboard-container">

    <h1>Admin Dashboard</h1>
    <p>Welcome to the Vaccination Registration System administration panel.</p>

    <div class="dashboard-stats">

        <div class="stat-card">
            <h3>Total Users</h3>
            <h2><?= e($totalUsers) ?></h2>
        </div>

        <div class="stat-card">
            <h3>Total Children</h3>
            <h2><?= e($totalChildren) ?></h2>
        </div>

        <div class="stat-card">
            <h3>Total Hospitals</h3>
            <h2><?= e($totalHospitals) ?></h2>
        </div>

        <div class="stat-card">
            <h3>Total Appointments</h3>
            <h2><?= e($totalAppointments) ?></h2>
        </div>

        <div class="stat-card">
            <h3>Pending Appointments</h3>
            <h2><?= e($pendingAppointments) ?></h2>
        </div>

    </div>

    <h2>Admin Management</h2>

    <div class="dashboard-links">
        <a href="<?= e(url('admin/appointments.php')) ?>">
            Manage Appointments
        </a>
    </div>

</div>

<?php page_footer(); ?>
