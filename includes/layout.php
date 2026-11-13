
<?php

require_once __DIR__ . '/functions.php';


// ==================================
// PAGE HEADER
// ==================================

function page_header($title = 'E-VaxCare') {

    $currentUser = user();

    $pageTitle = $title
        ? $title . ' | E-VaxCare'
        : 'E-VaxCare - Smart Vaccination System';

    $flash = get_flash();

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">

        <title><?= e($pageTitle) ?></title>

        <link rel="stylesheet"
              href="<?= e(url('assets/css/style.css')) ?>">

        <link rel="preconnect"
              href="https://fonts.googleapis.com">

        <link rel="preconnect"
              href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
              rel="stylesheet">

    </head>

    <body>

    <!-- NAVIGATION -->

    <nav class="navbar">

        <div class="nav-container">

            <a href="<?= e(url('index.php')) ?>"
               class="logo">

                <span class="logo-icon">✚</span>

                <span>E-Vax<span>Care</span></span>

            </a>


            <button class="menu-toggle"
                    id="menuToggle"
                    type="button"
                    aria-label="Toggle navigation">

                ☰

            </button>


            <div class="nav-links" id="navLinks">

                <a href="<?= e(url('index.php')) ?>">
                    Home
                </a>

                <a href="<?= e(url('how.php')) ?>">
                    How It Works
                </a>

                <?php if (!$currentUser): ?>

                    <a href="<?= e(url('index.php#roles')) ?>">
                        Our Services
                    </a>

                    <a href="<?= e(url('login.php')) ?>"
                       class="nav-login">

                        Login

                    </a>

                    <a href="<?= e(url('register.php?role=parent')) ?>"
                       class="nav-register">

                        Get Started

                    </a>

                <?php else: ?>

                    <a href="<?= e(url(home_for($currentUser['role']))) ?>">
                        Dashboard
                    </a>

                    <span class="nav-user">
                        <?= e($currentUser['name']) ?>
                    </span>

                    <a href="<?= e(url('logout.php')) ?>"
                       class="nav-login">

                        Logout

                    </a>

                <?php endif; ?>

            </div>

        </div>

    </nav>


    <?php if ($flash): ?>

        <div class="flash-message <?= e($flash['type']) ?>">

            <?= e($flash['message']) ?>

        </div>

    <?php endif; ?>


    <main>

    <?php

}


// ==================================
// PAGE FOOTER
// ==================================

function page_footer() {

    ?>

    </main>

    <footer class="footer">

        <div class="footer-container">

            <div class="footer-brand">

                <a href="<?= e(url('index.php')) ?>"
                   class="logo">

                    <span class="logo-icon">✚</span>

                    <span>E-Vax<span>Care</span></span>

                </a>

                <p>
                    Protecting every child through smarter
                    vaccination management.
                </p>

            </div>


            <div class="footer-links">

                <h4>Quick Links</h4>

                <a href="<?= e(url('index.php')) ?>">Home</a>

                <a href="<?= e(url('how.php')) ?>">How It Works</a>

                <a href="<?= e(url('login.php')) ?>">Login</a>

                <a href="<?= e(url('register.php?role=parent')) ?>">
                    Register
                </a>

            </div>


            <div class="footer-contact">

                <h4>Our Mission</h4>

                <p>
                    Making childhood vaccination accessible,
                    organized and easier to manage.
                </p>

            </div>

        </div>


        <div class="footer-bottom">

            © <?= date('Y') ?> E-VaxCare.
            All rights reserved.

        </div>

    </footer>


    <script>

        const menuToggle = document.getElementById('menuToggle');

        const navLinks = document.getElementById('navLinks');

        if (menuToggle && navLinks) {

            menuToggle.addEventListener('click', function() {

                navLinks.classList.toggle('active');

            });

        }

    </script>

    </body>

    </html>

    <?php

}

?>
