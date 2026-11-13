
<?php

require_once __DIR__ . '/includes/layout.php';

$u = user();

$start = $u
    ? url(home_for($u['role']))
    : url('register.php?role=parent');

page_header('How It Works');

?>

<!-- ==========================
     HOW PAGE HERO
=========================== -->

<section class="how-hero">

    <div class="how-hero-content">

        <span class="section-label">
            SIMPLE • CONNECTED • ORGANIZED
        </span>

        <h1>
            How <span>E-VaxCare</span> Works
        </h1>

        <p>
            A simple digital platform that connects parents,
            hospitals and administrators to organize childhood
            vaccination registration and management.
        </p>

        <div class="how-hero-buttons">

            <a href="<?= e($start) ?>" class="btn-primary">
                Get Started →
            </a>

            <a href="#parent-process" class="btn-secondary">
                Explore the Process
            </a>

        </div>

    </div>

</section>


<!-- ==========================
     PLATFORM OVERVIEW
=========================== -->

<section class="how-overview">

    <div class="section-heading">

        <span class="section-label">THE PLATFORM</span>

        <h2>Three Roles, One Connected System</h2>

        <p>
            Each user has dedicated features designed
            for their responsibilities.
        </p>

    </div>

    <div class="how-roles-grid">

        <div class="how-role-card">
            <div class="how-role-icon">👨‍👩‍👧</div>
            <h3>Parents</h3>
            <p>
                Manage children's profiles, view vaccination
                schedules and submit appointment requests.
            </p>
        </div>

        <div class="how-role-card">
            <div class="how-role-icon">🏥</div>
            <h3>Hospitals</h3>
            <p>
                View approved appointments and update
                vaccination results after visits.
            </p>
        </div>

        <div class="how-role-card">
            <div class="how-role-icon">🛡️</div>
            <h3>Administrators</h3>
            <p>
                Oversee system users, hospitals and
                appointment approval activities.
            </p>
        </div>

    </div>

</section>


<!-- ==========================
     PARENT PROCESS
=========================== -->

<section class="how-process-section" id="parent-process">

    <div class="section-heading">

        <span class="section-label">PARENT JOURNEY</span>

        <h2>How Parents Use E-VaxCare</h2>

        <p>
            Follow these simple steps to manage
            your child's vaccination information.
        </p>

    </div>

    <div class="how-steps">

        <div class="how-step">
            <span class="how-step-number">01</span>
            <div class="how-step-icon">👤</div>
            <h3>Create an Account</h3>
            <p>
                Register as a parent and log in
                to your personal dashboard.
            </p>
        </div>

        <div class="how-step">
            <span class="how-step-number">02</span>
            <div class="how-step-icon">👶</div>
            <h3>Add Child Information</h3>
            <p>
                Create a child profile with the
                required personal information.
            </p>
        </div>

        <div class="how-step">
            <span class="how-step-number">03</span>
            <div class="how-step-icon">💉</div>
            <h3>View Vaccine Schedule</h3>
            <p>
                Explore available vaccines and
                check the child's recorded progress.
            </p>
        </div>

        <div class="how-step">
            <span class="how-step-number">04</span>
            <div class="how-step-icon">📅</div>
            <h3>Request Appointment</h3>
            <p>
                Select a vaccine, participating hospital
                and preferred appointment date.
            </p>
        </div>

        <div class="how-step">
            <span class="how-step-number">05</span>
            <div class="how-step-icon">✓</div>
            <h3>Track Results</h3>
            <p>
                Check appointment status and view
                vaccination results recorded by the hospital.
            </p>
        </div>

    </div>

</section>


<!-- ==========================
     HOSPITAL WORKFLOW
=========================== -->

<section class="how-workflow-section">

    <div class="how-workflow-container">

        <div class="how-workflow-visual">
            <div class="workflow-icon">🏥</div>
            <span>HOSPITAL PORTAL</span>
            <h3>Manage Vaccination Visits</h3>
        </div>

        <div class="how-workflow-content">

            <span class="section-label">FOR HOSPITALS</span>

            <h2>Organized Appointment Management</h2>

            <p>
                Hospitals use their dedicated portal to
                manage appointments assigned to them.
            </p>

            <ul>
                <li>Log in to the hospital account.</li>
                <li>View approved appointments.</li>
                <li>Check child and vaccine information.</li>
                <li>Record the vaccination result after the visit.</li>
                <li>Maintain vaccination records in the system.</li>
            </ul>

        </div>

    </div>

</section>


<!-- ==========================
     ADMIN WORKFLOW
=========================== -->

<section class="how-workflow-section admin-workflow">

    <div class="how-workflow-container">

        <div class="how-workflow-content">

            <span class="section-label">FOR ADMINISTRATORS</span>

            <h2>Centralized System Administration</h2>

            <p>
                Administrators oversee the platform and
                coordinate appointment management.
            </p>

            <ul>
                <li>Access the administrator dashboard.</li>
                <li>Monitor users, children and hospitals.</li>
                <li>Review submitted appointments.</li>
                <li>Approve or reject appointment requests.</li>
                <li>Monitor overall appointment activity.</li>
            </ul>

        </div>

        <div class="how-workflow-visual admin-visual">
            <div class="workflow-icon">🛡️</div>
            <span>ADMIN PORTAL</span>
            <h3>System Overview</h3>
        </div>

    </div>

</section>


<!-- ==========================
     FAQ SECTION
=========================== -->

<section class="how-faq-section" id="faqs">

    <div class="section-heading">

        <span class="section-label">COMMON QUESTIONS</span>

        <h2>Frequently Asked Questions</h2>

        <p>
            Find answers to common questions
            about using E-VaxCare.
        </p>

    </div>

    <div class="faq-container">

        <details class="faq-item">
            <summary>What is E-VaxCare?</summary>
            <p>
                E-VaxCare is a web-based vaccination registration
                and management system connecting parents,
                hospitals and administrators.
            </p>
        </details>

        <details class="faq-item">
            <summary>Who can use E-VaxCare?</summary>
            <p>
                Parents, registered hospitals and authorized
                administrators can access their respective portals.
            </p>
        </details>

        <details class="faq-item">
            <summary>Can parents manage multiple children?</summary>
            <p>
                Yes. Parents can add and manage multiple
                child profiles through their dashboard.
            </p>
        </details>

        <details class="faq-item">
            <summary>How can parents book an appointment?</summary>
            <p>
                Parents can select a child, available vaccine,
                participating hospital and preferred date
                through the appointment booking page.
            </p>
        </details>

        <details class="faq-item">
            <summary>Who approves appointment requests?</summary>
            <p>
                The administrator reviews submitted appointment
                requests and can approve or reject them.
            </p>
        </details>

        <details class="faq-item">
            <summary>Who records vaccination results?</summary>
            <p>
                The assigned hospital records the vaccination
                result after the vaccination visit.
            </p>
        </details>

    </div>

</section>


<!-- ==========================
     FINAL CALL TO ACTION
=========================== -->

<section class="how-cta-section">

    <div class="how-cta-content">

        <span>START YOUR JOURNEY</span>

        <h2>Stay Organized With E-VaxCare</h2>

        <p>
            Create your account and explore a connected
            vaccination management experience.
        </p>

        <a href="<?= e($start) ?>" class="btn-primary">
            <?= $u ? 'Open Your Dashboard' : 'Create Your Account' ?> →
        </a>

    </div>

</section>


<?php page_footer(); ?>
