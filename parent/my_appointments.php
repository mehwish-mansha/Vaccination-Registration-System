
<?php

require_once __DIR__ . '/../includes/layout.php';

require_role('parent');


// Get parent's appointments
$stmt = $pdo->prepare("
    SELECT
        a.id,
        a.preferred_date,
        a.status,
        a.admin_remark,
        a.result,
        c.name AS child_name,
        v.name AS vaccine_name,
        h.name AS hospital_name,
        h.address AS hospital_address,
        h.city AS hospital_city

    FROM appointments a

    INNER JOIN children c
        ON a.child_id = c.id

    INNER JOIN vaccines v
        ON a.vaccine_id = v.id

    INNER JOIN hospitals h
        ON a.hospital_id = h.id

    WHERE c.parent_id = ?

    ORDER BY a.created_at DESC
");

$stmt->execute([user()['id']]);

$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

page_header('My Appointments');

?>


<section class="dashboard-section">

    <div class="dashboard-container">

        <div class="dashboard-welcome">

            <div>

                <span class="dashboard-label">
                    PARENT PORTAL
                </span>

                <h1>My Appointments</h1>

                <p>
                    Track your hospital booking requests.
                </p>

            </div>

            <div class="welcome-icon">📅</div>

        </div>


        <div class="dashboard-panel">

            <div class="panel-heading">

                <div>

                    <h2>Appointment History</h2>

                    <p>
                        All your submitted appointment requests.
                    </p>

                </div>

            </div>


            <?php if (empty($appointments)): ?>

                <div class="empty-state">

                    <div>📅</div>

                    <h3>No Appointments Yet</h3>

                    <p>
                        You haven't booked any appointments.
                    </p>

                </div>

            <?php else: ?>

                <div class="appointment-list">

                    <?php foreach ($appointments as $appointment): ?>

                        <?php

                        $statusClass = [
                            'pending' => 'appointment-pending',
                            'approved' => 'appointment-approved',
                            'rejected' => 'appointment-rejected',
                            'cancelled' => 'appointment-cancelled'
                        ][$appointment['status']] ?? 'appointment-pending';

                        ?>

                        <div class="appointment-card">

                            <div class="appointment-header">

                                <h3>
                                    <?= e($appointment['vaccine_name']) ?>
                                </h3>

                                <span class="appointment-status <?= $statusClass ?>">
                                    <?= e(ucfirst($appointment['status'])) ?>
                                </span>

                            </div>


                            <div class="appointment-details">

                                <p>
                                    <strong>Child:</strong>
                                    <?= e($appointment['child_name']) ?>
                                </p>

                                <p>
                                    <strong>Hospital:</strong>
                                    <?= e($appointment['hospital_name']) ?>
                                </p>

                                <p>
                                    <strong>Address:</strong>
                                    <?= e($appointment['hospital_address']) ?>,
                                    <?= e($appointment['hospital_city']) ?>
                                </p>

                                <p>
                                    <strong>Preferred Date:</strong>
                                    <?= e($appointment['preferred_date']) ?>
                                </p>

                                <p>
                                    <strong>Vaccination Result:</strong>
                                    <?= e(ucwords(str_replace('_', ' ', $appointment['result']))) ?>
                                </p>

                                <?php if (!empty($appointment['admin_remark'])): ?>

                                    <p>
                                        <strong>Admin Remark:</strong>
                                        <?= e($appointment['admin_remark']) ?>
                                    </p>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>


        <a href="<?= e(url('parent/book_appointment.php')) ?>"
           class="dashboard-button">

            + Book New Appointment

        </a>

        <br>

        <a href="<?= e(url('parent/dashboard.php')) ?>"
           class="schedule-back">

            ← Back to Dashboard

        </a>

    </div>

</section>


<?php page_footer(); ?>
