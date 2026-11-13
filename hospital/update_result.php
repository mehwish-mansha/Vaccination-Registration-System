
<?php

require_once __DIR__ . '/../includes/layout.php';

require_role('hospital');

$hospitalUserId = user()['id'];

// Find the logged-in hospital
$stmt = $pdo->prepare("
    SELECT id, name
    FROM hospitals
    WHERE user_id = ?
    LIMIT 1
");

$stmt->execute([$hospitalUserId]);
$hospital = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hospital) {
    redirect('hospital/dashboard.php');
}

$appointmentId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$appointmentId) {
    redirect('hospital/appointments.php');
}

// Fetch appointment belonging to this hospital
$stmt = $pdo->prepare("
    SELECT
        a.*,
        c.name AS child_name,
        v.name AS vaccine_name
    FROM appointments a
    JOIN children c ON a.child_id = c.id
    JOIN vaccines v ON a.vaccine_id = v.id
    WHERE a.id = ?
    AND a.hospital_id = ?
    AND a.status = 'approved'
");

$stmt->execute([$appointmentId, $hospital['id']]);
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    set_flash('error', 'Approved appointment not found.');
    redirect('hospital/appointments.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['csrf_token'] ?? '', $token)
    ) {
        $errors[] = 'Invalid security token. Refresh the page.';
    }

    $result = $_POST['result'] ?? '';

    if (!in_array($result, ['vaccinated', 'not_vaccinated'], true)) {
        $errors[] = 'Please select a valid vaccination result.';
    }

    if (empty($errors)) {

        try {

            $pdo->beginTransaction();

            // Update appointment result
            $update = $pdo->prepare("
                UPDATE appointments
                SET result = ?,
                    updated_at = NOW()
                WHERE id = ?
                AND hospital_id = ?
                AND status = 'approved'
            ");

            $update->execute([
                $result,
                $appointmentId,
                $hospital['id']
            ]);

            // Save a vaccination record only when vaccination was given
            if ($result === 'vaccinated') {

                $check = $pdo->prepare("
                    SELECT id
                    FROM vaccination_records
                    WHERE appointment_id = ?
                    LIMIT 1
                ");

                $check->execute([$appointmentId]);
                $existingRecord = $check->fetch(PDO::FETCH_ASSOC);

                if ($existingRecord) {

                    $recordUpdate = $pdo->prepare("
                        UPDATE vaccination_records
                        SET given_on = CURDATE(),
                            status = 'vaccinated'
                        WHERE appointment_id = ?
                    ");

                    $recordUpdate->execute([$appointmentId]);

                } else {

                    $insert = $pdo->prepare("
                        INSERT INTO vaccination_records
                        (
                            child_id,
                            vaccine_id,
                            hospital_id,
                            appointment_id,
                            given_on,
                            status
                        )
                        VALUES (?, ?, ?, ?, CURDATE(), 'vaccinated')
                    ");

                    $insert->execute([
                        $appointment['child_id'],
                        $appointment['vaccine_id'],
                        $hospital['id'],
                        $appointmentId
                    ]);
                }
            }

            $pdo->commit();

            set_flash('success', 'Vaccination result saved successfully.');
            redirect('hospital/appointments.php');

        } catch (PDOException $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $errors[] = 'Unable to save the result. Please try again.';
        }
    }
}

page_header('Update Vaccination Result');

?>

<section class="dashboard-section">

    <div class="dashboard-container">

        <div class="dashboard-welcome">
            <div>
                <span class="dashboard-label">HOSPITAL PORTAL</span>
                <h1>Update Vaccination Result</h1>
                <p>Record the outcome of this appointment.</p>
            </div>
            <div class="welcome-icon">💉</div>
        </div>

        <div class="dashboard-panel">

            <h2>Appointment Details</h2>

            <p>
                <strong>Child:</strong>
                <?= e($appointment['child_name']) ?>
            </p>

            <p>
                <strong>Vaccine:</strong>
                <?= e($appointment['vaccine_name']) ?>
            </p>

            <p>
                <strong>Current Result:</strong>
                <?= e(ucwords(str_replace('_', ' ', $appointment['result']))) ?>
            </p>

            <?php if (!empty($errors)): ?>
                <div class="form-errors">
                    <?php foreach ($errors as $error): ?>
                        <p><?= e($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="child-form">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e($_SESSION['csrf_token'] ?? '') ?>"
                >

                <label for="result">Vaccination Result</label>

                <select name="result" id="result" required>
                    <option value="">Select Result</option>
                    <option value="vaccinated">Vaccinated</option>
                    <option value="not_vaccinated">Not Vaccinated</option>
                </select>

                <button type="submit">
                    Save Vaccination Result
                </button>

            </form>

        </div>

        <a href="<?= e(url('hospital/appointments.php')) ?>"
           class="schedule-back">
            ← Back to Appointments
        </a>

    </div>

</section>

<?php page_footer(); ?>
