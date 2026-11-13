
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

            <a href="<?= e(url('how.php')) ?>" class="btn-secondary">
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


<!-- ==========================
     ABOUT E-VAXCARE SECTION
=========================== -->

<section class="about-section" id="about">

    <div class="about-container">

        <div class="about-content">

            <span class="section-label">ABOUT E-VAXCARE</span>

            <h2>
                Making Childhood Vaccination
                <span>Simple and Organized</span>
            </h2>

            <p>
                E-VaxCare is a web-based vaccination registration
                and management system designed to connect parents,
                hospitals and administrators through one platform.
            </p>

            <p>
                It helps parents manage their children's profiles,
                view vaccination schedules, book appointments and
                access vaccination records. Hospitals can manage
                approved appointments and update vaccination results,
                while administrators oversee the system.
            </p>

            <div class="about-features">

                <div>
                    <span>✓</span>
                    Organized Vaccination Records
                </div>

                <div>
                    <span>✓</span>
                    Appointment Management
                </div>

                <div>
                    <span>✓</span>
                    Connected User Portals
                </div>

            </div>

            <a href="<?= e(url('how.php')) ?>" class="btn-primary">
                Discover How It Works →
            </a>

        </div>

        <div class="about-visual">

            <div class="about-icon">🏥</div>

            <h3>Care Through Connection</h3>

            <p>
                Parents, hospitals and administrators
                working together on one platform.
            </p>

            <div class="about-highlight">
                <strong>One Platform</strong>
                <span>Three Connected Roles</span>
            </div>

        </div>

    </div>

</section>


<!-- ==========================
     WHY CHOOSE E-VAXCARE
=========================== -->

<section class="benefits-section" id="benefits">

    <div class="section-heading">

        <span class="section-label">WHY CHOOSE US</span>

        <h2>Everything You Need in One Place</h2>

        <p>
            A convenient way to organize and manage
            childhood vaccination activities.
        </p>

    </div>

    <div class="benefits-grid">

        <div class="benefit-card">
            <div class="benefit-icon">📋</div>
            <h3>Organized Records</h3>
            <p>
                Keep children's vaccination information
                and records organized in one place.
            </p>
        </div>

        <div class="benefit-card">
            <div class="benefit-icon">📅</div>
            <h3>Easy Appointments</h3>
            <p>
                Submit vaccination appointment requests
                through the parent portal.
            </p>
        </div>

        <div class="benefit-card">
            <div class="benefit-icon">🔔</div>
            <h3>Schedule Tracking</h3>
            <p>
                View completed, due and upcoming vaccines
                through the vaccination schedule.
            </p>
        </div>

        <div class="benefit-card">
            <div class="benefit-icon">🏥</div>
            <h3>Hospital Connection</h3>
            <p>
                Connect appointment management with
                participating hospitals.
            </p>
        </div>

        <div class="benefit-card">
            <div class="benefit-icon">🔐</div>
            <h3>Role-Based Access</h3>
            <p>
                Separate portals for parents,
                hospitals and administrators.
            </p>
        </div>

        <div class="benefit-card">
            <div class="benefit-icon">📊</div>
            <h3>Centralized Management</h3>
            <p>
                Support system administration and
                vaccination activity monitoring.
            </p>
        </div>

    </div>

</section>


<!-- ==========================
     HOW IT WORKS SECTION
=========================== -->

<section class="process-section" id="how-it-works">

    <div class="section-heading">

        <span class="section-label">SIMPLE PROCESS</span>

        <h2>How E-VaxCare Works</h2>

        <p>
            Four simple steps to manage your child's
            vaccination journey.
        </p>

    </div>

    <div class="process-grid">

        <div class="process-card">
            <div class="process-number">01</div>
            <div class="process-icon">👤</div>
            <h3>Create an Account</h3>
            <p>
                Register as a parent and access
                your personal dashboard.
            </p>
        </div>

        <div class="process-card">
            <div class="process-number">02</div>
            <div class="process-icon">👶</div>
            <h3>Add Your Child</h3>
            <p>
                Create your child's profile to
                organize vaccination information.
            </p>
        </div>

        <div class="process-card">
            <div class="process-number">03</div>
            <div class="process-icon">📅</div>
            <h3>Book an Appointment</h3>
            <p>
                Select an available vaccine, hospital
                and preferred appointment date.
            </p>
        </div>

        <div class="process-card">
            <div class="process-number">04</div>
            <div class="process-icon">✓</div>
            <h3>Track Vaccination</h3>
            <p>
                View appointment updates and
                recorded vaccination results.
            </p>
        </div>

    </div>

</section>


<!-- ==========================
     VACCINATION SCHEDULE
=========================== -->

<section class="schedule-info-section" id="vaccination-schedule">

    <div class="section-heading">

        <span class="section-label">VACCINATION INFORMATION</span>

        <h2>Stay Informed About Vaccination</h2>

        <p>
            Explore the vaccines available in our system
            and keep track of your child's vaccination schedule.
        </p>

    </div>

    <div class="schedule-info-container">

        <div class="schedule-info-content">

            <h3>A More Organized Vaccination Journey</h3>

            <p>
                E-VaxCare provides a vaccination schedule
                that helps parents view vaccine names,
                recommended ages and recorded vaccination
                progress.
            </p>

            <ul>
                <li>View available vaccines</li>
                <li>Check vaccine age information</li>
                <li>Track completed vaccinations</li>
                <li>Identify due and upcoming doses</li>
            </ul>

            <a href="<?= e($start) ?>" class="btn-primary">
                View Vaccination Schedule →
            </a>

        </div>

        <div class="schedule-info-card">

            <div class="schedule-card-header">
                <span>💉</span>
                <div>
                    <h3>Vaccination Overview</h3>
                    <p>Available in E-VaxCare</p>
                </div>
            </div>

            <div class="schedule-count">
                <strong><?= $vaccineCount ?></strong>
                <span>Vaccines in the system</span>
            </div>

            <div class="schedule-note">
                <span>✓</span>
                <p>
                    Keep vaccination records organized
                    and accessible through your dashboard.
                </p>
            </div>

        </div>

    </div>

</section>



<!-- ==========================
     FINAL CALL TO ACTION
=========================== -->

<section class="final-cta-section">

    <div class="final-cta-content">

        <span class="section-tag">Get Started Today</span>

        <h2>Take the Next Step in Managing Your Child's Vaccinations</h2>

        <p>
            Keep vaccination appointments and records organized
            with E-VaxCare.
        </p>

        <div class="final-cta-buttons">

            <a href="<?php echo e($start); ?>" class="btn btn-primary">
                Go to Dashboard
            </a>

            <a href="<?php echo e(url('how.php')); ?>" class="btn btn-outline">
                Learn How It Works
            </a>

        </div>

    </div>

</section>

<?php page_footer(); ?>
