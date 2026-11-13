
<?php

require_once __DIR__ . '/../includes/layout.php';

require_role('parent');


// ==================================
// GET PARENT'S CHILDREN
// ==================================

$stmt = $pdo->prepare("
    SELECT *
    FROM children
    WHERE parent_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([user()['id']]);

$children = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ==================================
// DASHBOARD STATISTICS
// ==================================

$totalChildren = count($children);

$totalVaccines = (int) val(
    "SELECT COUNT(*) FROM vaccines WHERE available = 1"
);

$totalAppointments = (int) val("
    SELECT COUNT(*)
    FROM appointments a
    INNER JOIN children c ON a.child_id = c.id
    WHERE c.parent_id = ?
", [user()['id']]);


// ==================================
// CSRF TOKEN
// ==================================

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


page_header('Parent Dashboard');

?>


<section class="dashboard-section">

    <div class="dashboard-container">


        <!-- WELCOME -->

        <div class="dashboard-welcome">

            <div>

                <span class="dashboard-label">
                    PARENT PORTAL
                </span>

                <h1>
                    Welcome, <?= e(user()['name']) ?>!
                </h1>

                <p>
                    Manage your children's vaccination journey
                    from one place.
                </p>

            </div>

            <div class="welcome-icon">
                👨‍👩‍👧
            </div>

        </div>


        <!-- STATISTICS -->
         <div style="margin-bottom:20px">

    <a href="<?= e(url('parent/vaccination_schedule.php')) ?>"
       class="dashboard-button">

        💉 View Vaccination Schedule →

    </a>

</div>
<div style="margin-bottom:20px">

    <a href="<?= e(url('parent/book_appointment.php')) ?>"
       class="dashboard-button">

        📅 Book Hospital Appointment →

    </a>

</div>

<div style="margin-bottom:20px">

    <a href="<?= e(url('parent/my_appointments.php')) ?>"
       class="dashboard-button">

        📋 My Appointments →

    </a>

</div>

        <div class="dashboard-stats">

            <div class="stat-card">

                <span>👶</span>

                <h2><?= $totalChildren ?></h2>

                <p>Registered Children</p>

            </div>


            <div class="stat-card">

                <span>💉</span>

                <h2><?= $totalVaccines ?></h2>

                <p>Available Vaccines</p>

            </div>


            <div class="stat-card">

                <span>📅</span>

                <h2><?= $totalAppointments ?></h2>

                <p>Total Appointments</p>

            </div>

        </div>


        <!-- ADD CHILD FORM -->

        <div class="dashboard-panel">

            <div class="panel-heading">

                <div>

                    <h2>Register Your Child</h2>

                    <p>
                        Enter your child's information below.
                    </p>

                </div>

                <span class="panel-icon">+</span>

            </div>


            <form
                action="<?= e(url('parent/add_child.php')) ?>"
                method="POST"
                class="child-form"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e($_SESSION['csrf_token']) ?>"
                >


                <div class="form-group">

                    <label>Child's Full Name</label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter child's name"
                        maxlength="100"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Date of Birth</label>

                    <input
                        type="date"
                        name="dob"
                        max="<?= date('Y-m-d') ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Gender</label>

                    <select name="gender" required>

                        <option value="">Select Gender</option>

                        <option value="Boy">Boy</option>

                        <option value="Girl">Girl</option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Blood Group (Optional)</label>

                    <select name="blood_group">

                        <option value="">Select Blood Group</option>

                        <option value="A+">A+</option>
                        <option value="A-">A-</option>

                        <option value="B+">B+</option>
                        <option value="B-">B-</option>

                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>

                        <option value="O+">O+</option>
                        <option value="O-">O-</option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Guardian Name</label>

                    <input
                        type="text"
                        name="guardian_name"
                        placeholder="Enter guardian's name"
                        maxlength="100"
                        required
                    >

                </div>


                <button type="submit" class="dashboard-button">

                    Register Child →

                </button>

            </form>

        </div>


        <!-- CHILDREN LIST -->

        <div class="children-section">

            <div class="panel-heading">

                <div>

                    <h2>My Children</h2>

                    <p>
                        Your registered children's information.
                    </p>

                </div>

                <span class="children-count">
                    <?= $totalChildren ?> Children
                </span>

            </div>


            <?php if (empty($children)): ?>

                <div class="empty-state">

                    <div>👶</div>

                    <h3>No Children Registered Yet</h3>

                    <p>
                        Register your first child using the form above.
                    </p>

                </div>

            <?php else: ?>

                <div class="children-grid">

                    <?php foreach ($children as $child): ?>

                        <div class="child-card">

                            <div class="child-card-top">

                                <div class="child-avatar">
                                    <?= $child['gender'] === 'Boy' ? '👦' : '👧' ?>
                                </div>

                                <div>

                                    <h3><?= e($child['name']) ?></h3>

                                    <span>
                                        <?= e($child['gender']) ?>
                                    </span>

                                </div>

                            </div>


                            <div class="child-details">

                                <p>
                                    <strong>Date of Birth:</strong>
                                    <?= e($child['dob']) ?>
                                </p>

                                <p>
                                    <strong>Blood Group:</strong>
                                    <?= e($child['blood_group'] ?: 'Not provided') ?>
                                </p>

                                <p>
                                    <strong>Guardian:</strong>
                                    <?= e($child['guardian_name']) ?>
                                </p>

                            </div>


                            <div class="child-card-footer">

                                <span>
                                    Registered Child
                                </span>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>


    </div>

</section>


<?php page_footer(); ?>
