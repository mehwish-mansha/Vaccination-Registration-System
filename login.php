
<?php

require_once __DIR__ . '/includes/layout.php';

$errors = [];


// ==================================
// REDIRECT ALREADY LOGGED-IN USERS
// ==================================

if (is_logged_in()) {
    redirect(home_for(user()['role']));
}


// ==================================
// GENERATE CSRF TOKEN
// ==================================

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// ==================================
// HANDLE LOGIN
// ==================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate CSRF
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $errors[] = "Invalid form submission. Please try again.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    }


    if (empty($errors)) {

        try {

            // Find user
            $stmt = $pdo->prepare("
                SELECT id, name, email, password_hash, role
                FROM users
                WHERE email = ?
                LIMIT 1
            ");

            $stmt->execute([$email]);

            $account = $stmt->fetch(PDO::FETCH_ASSOC);


            // Verify password
            if (
                !$account ||
                !password_verify($password, $account['password_hash'])
            ) {
                 session_regenerate_id(true);

                $errors[] = "Incorrect email or password.";

            } else {

                // Regenerate session ID
                session_regenerate_id(true);

                // Store user information
                $_SESSION['user'] = [
                    'id' => (int)$account['id'],
                    'name' => $account['name'],
                    'email' => $account['email'],
                    'role' => $account['role']
                ];

                // Regenerate CSRF token
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                // Redirect according to role
                redirect(home_for($account['role']));

            }

        } catch (PDOException $e) {

            $errors[] = "Something went wrong. Please try again.";

        }

    }

}

page_header('Login');

?>


<!-- ==================================
     LOGIN PAGE
================================== -->

<section class="auth-section">

    <div class="auth-card">

        <div class="auth-heading">

            <div class="auth-icon">✚</div>

            <h1>Welcome Back!</h1>

            <p>
                Login to your E-VaxCare account.
            </p>

        </div>


        <!-- ERROR MESSAGES -->

        <?php if (!empty($errors)): ?>

            <div class="form-errors">

                <?php foreach ($errors as $error): ?>

                    <p><?= e($error) ?></p>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <!-- LOGIN FORM -->

        <form method="POST"
              action="<?= e(url('login.php')) ?>">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= e($_SESSION['csrf_token']) ?>"
            >


            <div class="form-group">

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button type="submit" class="auth-submit">

                Login to Account →

            </button>

        </form>


        <div class="auth-bottom">

            Don't have an account?

            <a href="<?= e(url('register.php?role=parent')) ?>">
                Register here
            </a>

        </div>

    </div>

</section>


<?php page_footer(); ?>
