
<?php

require_once __DIR__ . '/includes/layout.php';

$u = user();

$start = $u
    ? url(home_for($u['role']))
    : url('register.php?role=parent');

$vaccineCount = (int) val('SELECT COUNT(*) FROM vaccines');

page_header('Home');

?>

<!-- ==========================
     HERO SECTION
=========================== -->

<section class="hero" id="top">

    <div class="hero-content">

        <div class="hero-badge">
            <span>✚</span>
            Protect Today, Secure Tomorrow
        </div>

        <h1>
            Smart Vaccination
            <span>Stronger Generation</span>
        </h1>

        <p class="hero-description">
            E-VaxCare connects parents, hospitals and administrators
            to make childhood vaccination easier. Track vaccines,
            book appointments and keep every child's immunization
            record organized in one place.
        </p>

        <div class="hero-buttons">

            <a href="<?= e($start) ?>" class="btn-primary">

                <?= $u ? 'Open Dashboard' : 'Get Started' ?>

                <span>→</span>

            </a>

            <a href="<?= e(url('how.php')) ?>"
               class="btn-secondary">

                Learn More

            </a>

        </div>

        <div class="hero-stats">

            <div>
                <h3>3</h3>
                <p>Connected Roles</p>
            </div>

            <div>
                <h3><?= $vaccineCount ?></h3>
                <p>Vaccines Tracked</p>
            </div>

            <div>
                <h3>24/7</h3>
                <p>Record Access</p>
            </div>

        </div>

    </div>


    <!-- HERO IMAGE -->

    <div class="hero-visual">

        <div class="hero-circle circle-one"></div>
        <div class="hero-circle circle-two"></div>

        <div class="medical-shield">
            <span>+</span>
        </div>

        <div class="hero-image-container">

            <img
                src="<?= e(url('assets/images/baby.jpg')) ?>"
                alt="Smiling baby"
                class="hero-baby"
            >

        </div>

        <div class="floating-card">

            <span class="floating-icon">✓</span>

            <div>
                <strong>Vaccination Care</strong>
                <small>Every dose matters!</small>
            </div>

        </div>

    </div>

</section>


<!-- ==========================
     ROLES SECTION
=========================== -->

<section class="roles-section" id="roles">

    <div class="section-heading">

        <span class="section-label">OUR SERVICES</span>

        <h2>One Platform, Three Connected Roles</h2>

        <p>
            A simple and connected vaccination management
            experience for everyone.
        </p>

    </div>


    <div class="roles-grid">


        <!-- PARENT -->

        <div class="role-card parent-card">

            <div class="role-icon">👨‍👩‍👧</div>

            <h3>Parent</h3>

            <p>
                Manage your children's vaccination journey
                with ease.
            </p>

            <ul>
                <li>Add and manage children</li>
                <li>Track upcoming vaccines</li>
                <li>Book hospital appointments</li>
                <li>View vaccination history</li>
            </ul>

            <a href="<?= e(url('register.php?role=parent')) ?>">
                Register as Parent →
            </a>

        </div>


        <!-- ADMIN -->

        <div class="role-card admin-card">

            <div class="role-icon">🛡️</div>

            <h3>Administrator</h3>

            <p>
                Manage the complete vaccination
                management system.
            </p>

            <ul>
                <li>Manage users and hospitals</li>
                <li>Approve or reject appointments</li>
                <li>Manage vaccine schedules</li>
                <li>Generate vaccination reports</li>
            </ul>

            <a href="<?= e(url('login.php')) ?>">
                Admin Login →
            </a>

        </div>


        <!-- HOSPITAL -->

        <div class="role-card hospital-card">

            <div class="role-icon">🏥</div>

            <h3>Hospital</h3>

            <p>
                Manage appointments and update
                vaccination records.
            </p>

            <ul>
                <li>View approved appointments</li>
                <li>Manage vaccination visits</li>
                <li>Update vaccination status</li>
                <li>Maintain dose records</li>
            </ul>

            <a href="<?= e(url('register.php?role=hospital')) ?>">
                Register Hospital →
            </a>

        </div>

    </div>

</section>


<?php page_footer(); ?>
