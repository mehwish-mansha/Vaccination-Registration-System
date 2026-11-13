
<?php
require_once __DIR__ . '/../includes/layout.php';

require_role('admin');

$stmt = $pdo->query("
    SELECT
        a.id,
        a.preferred_date,
        a.status,
        a.result,
        c.name AS child_name,
        u.name AS parent_name,
        v.name AS vaccine_name,
        h.name AS hospital_name
    FROM appointments a
    JOIN children c ON a.child_id = c.id
    JOIN users u ON c.parent_id = u.id
    JOIN vaccines v ON a.vaccine_id = v.id
    JOIN hospitals h ON a.hospital_id = h.id
    ORDER BY a.created_at DESC
");

$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

page_header();
?>

<div class="dashboard-container">

    <h1>Manage Appointments</h1>
    <p>View all vaccination appointment requests.</p>

    <?php if (empty($appointments)): ?>
        <p>No appointments found.</p>
    <?php else: ?>

        <div class="appointment-list">

            <?php foreach ($appointments as $appointment): ?>

                <div class="appointment-card">

                    <h3>Appointment #<?= e($appointment['id']) ?></h3>

                    <p><strong>Parent:</strong>
                        <?= e($appointment['parent_name']) ?></p>

                    <p><strong>Child:</strong>
                        <?= e($appointment['child_name']) ?></p>

                    <p><strong>Vaccine:</strong>
                        <?= e($appointment['vaccine_name']) ?></p>

                    <p><strong>Hospital:</strong>
                        <?= e($appointment['hospital_name']) ?></p>

                    <p><strong>Date:</strong>
                        <?= e($appointment['preferred_date']) ?></p>

                    <p><strong>Status:</strong>
                        <?= e(ucfirst($appointment['status'])) ?></p>

                    <p><strong>Result:</strong>
                        <?= e(ucfirst($appointment['result'])) ?></p>

                    <a href="<?= e(url('admin/update_appointment.php?id=' . $appointment['id'])) ?>">
                        Manage This Appointment
                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php page_footer(); ?>
