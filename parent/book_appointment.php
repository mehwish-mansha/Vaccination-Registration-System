
<?php

require_once __DIR__ . '/../includes/layout.php';

require_role('parent');

$errors = [];


// ==================================
// GET PARENT'S CHILDREN
// ==================================

$childStmt = $pdo->prepare("
    SELECT id, name, dob
    FROM children
    WHERE parent_id = ?
    ORDER BY name
");

$childStmt->execute([user()['id']]);

$children = $childStmt->fetchAll(PDO::FETCH_ASSOC);


// ==================================
// GET AVAILABLE VACCINES
// ==================================

$vaccineStmt = $pdo->query("
    SELECT id, name, age_weeks
    FROM vaccines
    WHERE available = 1
    ORDER BY age_weeks, id
");

$vaccines = $vaccineStmt->fetchAll(PDO::FETCH_ASSOC);


// ==================================
// GET ACTIVE HOSPITALS
// ==================================

$hospitalStmt = $pdo->query("
    SELECT id, name, address, city
    FROM hospitals
    WHERE active = 1
    ORDER BY name
");

$hospitals = $hospitalStmt->fetchAll(PDO::FETCH_ASSOC);


// ==================================
// GENERATE CSRF TOKEN
// ==================================

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// ==================================
// HANDLE BOOKING
// ==================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $childId = filter_var(
        $_POST['child_id'] ?? '',
        FILTER_VALIDATE_INT
    );

    $vaccineId = filter_var(
        $_POST['vaccine_id'] ?? '',
        FILTER_VALIDATE_INT
    );

    $hospitalId = filter_var(
        $_POST['hospital_id'] ?? '',
        FILTER_VALIDATE_INT
    );

    $preferredDate = trim($_POST['preferred_date'] ?? '');

    // CSRF validation
   if (!verify_csrf_token()) {
    $errors[] = 'Invalid security token. Please refresh the page.';
}
    // Validate date
    $date = DateTime::createFromFormat('!Y-m-d', $preferredDate);

    if (
        !$date ||
        $date->format('Y-m-d') !== $preferredDate ||
        $preferredDate < date('Y-m-d')
    ) {
        $errors[] = "Please select a valid future appointment date.";
    }

    // Validate child belongs to logged-in parent
    $validChild = false;

    if ($childId) {

        $checkChild = $pdo->prepare("
            SELECT id
            FROM children
            WHERE id = ? AND parent_id = ?
        ");

        $checkChild->execute([
            $childId,
            user()['id']
        ]);

        $validChild = (bool)$checkChild->fetchColumn();
    }

    if (!$validChild) {
        $errors[] = "Please select one of your registered children.";
    }

    // Validate vaccine
    $checkVaccine = $pdo->prepare("
        SELECT id
        FROM vaccines
        WHERE id = ? AND available = 1
    ");

    $checkVaccine->execute([$vaccineId ?: 0]);

    if (!$checkVaccine->fetchColumn()) {
        $errors[] = "Please select a valid available vaccine.";
    }

    // Validate hospital
    $checkHospital = $pdo->prepare("
        SELECT id
        FROM hospitals
        WHERE id = ? AND active = 1
    ");

    $checkHospital->execute([$hospitalId ?: 0]);

    if (!$checkHospital->fetchColumn()) {
        $errors[] = "Please select an available hospital.";
    }


    // ==================================
    // SAVE APPOINTMENT
    // ==================================

    if (empty($errors)) {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO appointments
                (
                    child_id,
                    vaccine_id,
                    hospital_id,
                    preferred_date,
                    status,
                    result
                )
                VALUES (?, ?, ?, ?, 'pending', 'pending')
            ");

            $stmt->execute([
                $childId,
                $vaccineId,
                $hospitalId,
                $preferredDate
            ]);

            set_flash(
                'Appointment request submitted successfully!',
                'success'
            );

            redirect('parent/my_appointments.php');

        } catch (PDOException $e) {

            $errors[] = "Unable to book appointment. Please try again.";

        }

    }

}


page_header('Book Appointment');

?>


<section class="dashboard-section">

    <div class="dashboard-container">

        <div class="dashboard-welcome">

            <div>

                <span class="dashboard-label">
                    PARENT PORTAL
                </span>

                <h1>Book an Appointment</h1>

                <p>
                    Select your child, vaccine, hospital and preferred date.
                </p>

            </div>

            <div class="welcome-icon">📅</div>

        </div>


        <div class="dashboard-panel">

            <div class="panel-heading">

                <div>

                    <h2>Appointment Details</h2>

                    <p>
                        Your request will be sent to the administrator for approval.
                    </p>

                </div>

                <span class="panel-icon">+</span>

            </div>


            <?php if (!empty($errors)): ?>

                <div class="form-errors">

                    <?php foreach ($errors as $error): ?>

                        <p><?= e($error) ?></p>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <?php if (empty($children)): ?>

                <div class="empty-state">

                    <div>👶</div>

                    <h3>No Children Registered</h3>

                    <p>Register a child before booking an appointment.</p>

                    <a href="<?= e(url('parent/dashboard.php')) ?>"
                       class="dashboard-button">

                        Register Child

                    </a>

                </div>

            <?php elseif (empty($hospitals)): ?>

                <div class="empty-state">

                    <h3>No Active Hospitals Available</h3>

                    <p>Please contact the administrator.</p>

                </div>

            <?php elseif (empty($vaccines)): ?>

                <div class="empty-state">

                    <h3>No Available Vaccines</h3>

                    <p>Please contact the administrator.</p>

                </div>

            <?php else: ?>


                <form method="POST"
                      action="<?= e(url('parent/book_appointment.php')) ?>"
                      class="child-form">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e($_SESSION['csrf_token']) ?>"
                    >


                    <div class="form-group">

                        <label>Select Child</label>

                        <select name="child_id" required>

                            <option value="">Choose your child</option>

                            <?php foreach ($children as $child): ?>

                                <option
                                    value="<?= (int)$child['id'] ?>"
                                    <?= (string)($_POST['child_id'] ?? '') === (string)$child['id'] ? 'selected' : '' ?>
                                >
                                    <?= e($child['name']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Select Vaccine</label>

                        <select name="vaccine_id" required>

                            <option value="">Choose vaccine</option>

                            <?php foreach ($vaccines as $vaccine): ?>

                                <option
                                    value="<?= (int)$vaccine['id'] ?>"
                                    <?= (string)($_POST['vaccine_id'] ?? '') === (string)$vaccine['id'] ? 'selected' : '' ?>
                                >
                                    <?= e($vaccine['name']) ?>
                                    (<?= (int)$vaccine['age_weeks'] ?> weeks)
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Select Hospital</label>

                        <select name="hospital_id" required>

                            <option value="">Choose hospital</option>

                            <?php foreach ($hospitals as $hospital): ?>

                                <option
                                    value="<?= (int)$hospital['id'] ?>"
                                    <?= (string)($_POST['hospital_id'] ?? '') === (string)$hospital['id'] ? 'selected' : '' ?>
                                >
                                    <?= e($hospital['name']) ?>
                                    - <?= e($hospital['city']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Preferred Appointment Date</label>

                        <input
                            type="date"
                            name="preferred_date"
                            min="<?= date('Y-m-d') ?>"
                            value="<?= e($_POST['preferred_date'] ?? '') ?>"
                            required
                        >

                    </div>


                    <button type="submit" class="dashboard-button">

                        Submit Appointment Request →

                    </button>

                </form>


            <?php endif; ?>

        </div>


        <a href="<?= e(url('parent/dashboard.php')) ?>"
           class="schedule-back">

            ← Back to Dashboard

        </a>

    </div>

</section>


<?php page_footer(); ?>
