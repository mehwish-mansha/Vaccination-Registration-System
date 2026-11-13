
<?php

require_once __DIR__ . '/includes/layout.php';


// ==================================
// DETERMINE REGISTRATION ROLE
// ==================================

$role = $_GET['role'] ?? 'parent';

if (!in_array($role, ['parent', 'hospital'], true)) {
    $role = 'parent';
}

$errors = [];


// ==================================
// HANDLE REGISTRATION FORM
// ==================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $role = $_POST['role'] ?? 'parent';

    if (!in_array($role, ['parent', 'hospital'], true)) {
        $role = 'parent';
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $hospitalName = trim($_POST['hospital_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? 'Karachi');

    // Check CSRF token
    if (
        !isset($_POST['csrf_token']) ||
        !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $errors[] = "Invalid form submission. Please try again.";
    }

    // Validate name
    if ($name === '') {
        $errors[] = "Name is required.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Validate phone
    if ($phone !== '' && !preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
        $errors[] = "Please enter a valid phone number.";
    }

    // Validate password
    if (strlen($password) < 8) {
        $errors[] = "Password must contain at least 8 characters.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Validate hospital information
    if ($role === 'hospital') {

        if ($hospitalName === '') {
            $errors[] = "Hospital name is required.";
        }

        if ($address === '') {
            $errors[] = "Hospital address is required.";
        }

        if ($city === '') {
            $errors[] = "City is required.";
        }
    }


    // ==================================
    // INSERT INTO DATABASE
    // ==================================

    if (empty($errors)) {

        try {

            $pdo->beginTransaction();

            // Check duplicate email
            $check = $pdo->prepare(
                "SELECT id FROM users WHERE email = ?"
            );

            $check->execute([$email]);

            if ($check->fetch()) {

                throw new Exception(
                    "This email is already registered."
                );

            }

            // Hash password
            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Insert user
            $stmt = $pdo->prepare("
                INSERT INTO users
                (name, email, phone, password_hash, role)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $name,
                $email,
                $phone ?: null,
                $passwordHash,
                $role
            ]);

            $userId = $pdo->lastInsertId();


            // Insert hospital details if hospital registration
            if ($role === 'hospital') {

                $hospitalStmt = $pdo->prepare("
                    INSERT INTO hospitals
                    (user_id, name, address, city, phone, active)
                    VALUES (?, ?, ?, ?, ?, 1)
                ");

                $hospitalStmt->execute([
                    $userId,
                    $hospitalName,
                    $address,
                    $city,
                    $phone ?: null
                ]);

            }


            // Commit transaction
            $pdo->commit();

            set_flash(
                "Registration successful! Please log in.",
                "success"
            );

            redirect('login.php');


        } catch (Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if ($e instanceof PDOException && $e->getCode() == 23000) {

                $errors[] = "This email or hospital name is already registered.";

            } else {

                $errors[] = $e->getMessage();

            }

        }

    }

}


// ==================================
// GENERATE CSRF TOKEN
// ==================================

if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

}

page_header('Register');

?>


<!-- ==================================
     REGISTRATION PAGE
================================== -->

<section class="auth-section">

    <div class="auth-card">

        <div class="auth-heading">

            <div class="auth-icon">✚</div>

            <h1>Create Your Account</h1>

            <p>
                Join E-VaxCare and make vaccination management easier.
            </p>

        </div>


        <!-- ROLE SELECTION -->

        <div class="register-tabs">

            <a
                href="<?= e(url('register.php?role=parent')) ?>"
                class="<?= $role === 'parent' ? 'active' : '' ?>"
            >
                Parent
            </a>

            <a
                href="<?= e(url('register.php?role=hospital')) ?>"
                class="<?= $role === 'hospital' ? 'active' : '' ?>"
            >
                Hospital
            </a>

        </div>


        <!-- ERROR MESSAGES -->

        <?php if (!empty($errors)): ?>

            <div class="form-errors">

                <?php foreach ($errors as $error): ?>

                    <p><?= e($error) ?></p>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <!-- REGISTRATION FORM -->

        <form method="POST"
              action="<?= e(url('register.php?role=' . $role)) ?>">

            <input
                type="hidden"
                name="role"
                value="<?= e($role) ?>"
            >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= e($_SESSION['csrf_token']) ?>"
            >


            <div class="form-group">

                <label>
                    <?= $role === 'parent'
                        ? 'Parent / Guardian Name'
                        : 'Contact Person Name' ?>
                </label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter your full name"
                    value="<?= e($_POST['name'] ?? '') ?>"
                    maxlength="100"
                    required
                >

            </div>


            <div class="form-group">

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    placeholder="example@email.com"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    maxlength="150"
                    required
                >

            </div>


            <div class="form-group">

                <label>Phone Number</label>

                <input
                    type="tel"
                    name="phone"
                    placeholder="+92 300 1234567"
                    value="<?= e($_POST['phone'] ?? '') ?>"
                    maxlength="20"
                >

            </div>


            <?php if ($role === 'hospital'): ?>

                <div class="form-group">

                    <label>Hospital Name</label>

                    <input
                        type="text"
                        name="hospital_name"
                        placeholder="Enter hospital name"
                        value="<?= e($_POST['hospital_name'] ?? '') ?>"
                        maxlength="120"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Hospital Address</label>

                    <input
                        type="text"
                        name="address"
                        placeholder="Enter hospital address"
                        value="<?= e($_POST['address'] ?? '') ?>"
                        maxlength="200"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>City</label>

                    <input
                        type="text"
                        name="city"
                        value="<?= e($_POST['city'] ?? 'Karachi') ?>"
                        maxlength="80"
                        required
                    >

                </div>

            <?php endif; ?>


            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Minimum 8 characters"
                    minlength="8"
                    required
                >

            </div>


            <div class="form-group">

                <label>Confirm Password</label>

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Re-enter your password"
                    minlength="8"
                    required
                >

            </div>


            <button type="submit" class="auth-submit">

                Create Account →

            </button>


        </form>


        <div class="auth-bottom">

            Already have an account?

            <a href="<?= e(url('login.php')) ?>">
                Login here
            </a>

        </div>

    </div>

</section>


<?php page_footer(); ?>
