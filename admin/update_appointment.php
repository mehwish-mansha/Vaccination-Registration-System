
<?php
require_once __DIR__ . '/../includes/layout.php';

require_role('admin');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('error', 'Invalid appointment ID.');
    redirect('admin/appointments.php');
}

$stmt = $pdo->prepare("
    SELECT
        a.*,
        c.name AS child_name,
        v.name AS vaccine_name,
        h.name AS hospital_name
    FROM appointments a
    JOIN children c ON a.child_id = c.id
    JOIN vaccines v ON a.vaccine_id = v.id
    JOIN hospitals h ON a.hospital_id = h.id
    WHERE a.id = ?
");

$stmt->execute([$id]);
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    set_flash('error', 'Appointment not found.');
    redirect('admin/appointments.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
    ) {
        $errors[] = 'Invalid security token. Refresh the page.';
    } else {

        $status = $_POST['status'] ?? '';
        $remark = trim($_POST['admin_remark'] ?? '');

        if (!in_array($status, ['approved', 'rejected'], true)) {
            $errors[] = 'Please select a valid status.';
        }

        if (empty($errors)) {
            $update = $pdo->prepare("
                UPDATE appointments
                SET status = ?,
                    admin_remark = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");

            $update->execute([$status, $remark, $id]);

            set_flash('success', 'Appointment updated successfully.');
            redirect('admin/appointments.php');
        }
    }
}

page_header();
?>

<div class="dashboard-container">

    <h1>Update Appointment</h1>

    <div class="appointment-card">

        <h3>Appointment #<?= e($appointment['id']) ?></h3>

        <p><strong>Child:</strong>
            <?= e($appointment['child_name']) ?></p>

        <p><strong>Vaccine:</strong>
            <?= e($appointment['vaccine_name']) ?></p>

        <p><strong>Hospital:</strong>
            <?= e($appointment['hospital_name']) ?></p>

        <p><strong>Preferred Date:</strong>
            <?= e($appointment['preferred_date']) ?></p>

        <p><strong>Current Status:</strong>
            <?= e(ucfirst($appointment['status'])) ?></p>

    </div>

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

        <label for="status">Appointment Decision</label>

        <select name="status" id="status" required>
            <option value="">Select Decision</option>
            <option value="approved">Approve</option>
            <option value="rejected">Reject</option>
        </select>

        <label for="admin_remark">Admin Remark</label>

        <textarea
            name="admin_remark"
            id="admin_remark"
            rows="4"
            placeholder="Write a remark..."
        ><?= e($appointment['admin_remark'] ?? '') ?></textarea>

        <button type="submit">Update Appointment</button>

    </form>

    <p>
        <a href="<?= e(url('admin/appointments.php')) ?>">
            Back to Appointments
        </a>
    </p>

</div>

<?php page_footer(); ?>
