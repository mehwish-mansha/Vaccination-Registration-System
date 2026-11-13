
<?php

require_once __DIR__ . '/../includes/layout.php';

require_role('hospital');

$stmt = $pdo->prepare("
    SELECT id, name
    FROM hospitals
    WHERE user_id = ?
    LIMIT 1
");

$stmt->execute([user()['id']]);
$hospital = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hospital) {
    redirect('hospital/dashboard.php');
}

$stmt = $pdo->prepare("
    SELECT
        a.id,
        a.preferred_date,
        a.status,
        a.result,
        c.name AS child_name,
        c.dob,
        u.name AS parent_name,
        v.name AS vaccine_name
    FROM appointments a
    JOIN children c ON a.child_id = c.id
    JOIN users u ON c.parent_id = u.id
    JOIN vaccines v ON a.vaccine_id = v.id
    WHERE a.hospital_id = ?
    AND a.status = 'approved'
    ORDER BY a.preferred_date ASC
");

$stmt->execute([$hospital['id']]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

page_header('Hospital Appointments');
?>

<section class="dashboard-section">
    <div class="dashboard-container">

        <div class="dashboard-welcome">
            <div>
                <span class="dashboard-label">HOSPITAL PORTAL</span>
                <h1>Approved Appointments</h1>
                <p><?= e($hospital['name']) ?></p>
            </div>
            <div class="welcome-icon">📅</div>
        </div>

        <div class="dashboard-panel">

            <?php if (empty($appointments)): ?>

                <div class="empty-state">
                    <h3>No Approved Appointments</h3>
                    <p>There are currently no approved appointments for your hospital.</p>
                </div>

            <?php else: ?>

                <div class="appointment-list">

                    <?php foreach ($appointments as $appointment): ?>

                        <div class="appointment-card">

                            <h3>
                                Appointment #<?= e($appointment['id']) ?>
                            </h3>

                            <p><strong>Child:</strong>
                                <?= e($appointment['child_name']) ?></p>

                            <p><strong>Parent:</strong>
                                <?= e($appointment['parent_name']) ?></p>

                            <p><strong>Date of Birth:</strong>
                                <?= e($appointment['dob']) ?></p>

                            <p><strong>Vaccine:</strong>
                                <?= e($appointment['vaccine_name']) ?></p>

                            <p><strong>Appointment Date:</strong>
                                <?= e($appointment['preferred_date']) ?></p>

                            <p><strong>Result:</strong>
                                <?= e(ucwords(str_replace('_', ' ', $appointment['result']))) ?></p>
                                <a href="<?= e(url('hospital/update_result.php?id=' . $appointment['id'])) ?>"
   class="dashboard-button">
    Record Vaccination Result
</a>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>

        <a href="<?= e(url('hospital/dashboard.php')) ?>"
           class="schedule-back">
            ← Back to Dashboard
        </a>

    </div>
</section>

<?php page_footer(); ?>
