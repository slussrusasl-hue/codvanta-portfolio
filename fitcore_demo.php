
<?php
// FitCore - Interactive Demo Selection Page
// Codvanta Portfolio Project
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FitCore | Interactive Demo - Codvanta</title>

    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">

    <!-- Font Awesome (optional if available locally) -->
    <link rel="stylesheet" href="assets/css/all.min.css">

    <style>
        :root {
            --primary: #ff6b2c;
            --primary-hover: #e9561b;
            --background: #0b0d10;
            --surface: #15191f;
            --surface-light: #1d222a;
            --text: #f5f5f5;
            --muted: #a6adb7;
            --border: rgba(255, 255, 255, 0.09);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--background);
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        /* Navigation */
        .navbar {
            background: rgba(11, 13, 16, 0.96);
            border-bottom: 1px solid var(--border);
            padding: 16px 0;
        }

        .navbar-brand {
            font-size: 1.65rem;
            font-weight: 800;
            color: #fff !important;
            letter-spacing: 1px;
        }

        .brand-highlight {
            color: var(--primary);
        }

        .nav-link {
            color: #d0d4da !important;
            margin-left: 18px;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        /* Hero section */
        .hero {
            position: relative;
            min-height: 510px;
            display: flex;
            align-items: center;
            overflow: hidden;
            background:
                linear-gradient(
                    90deg,
                    rgba(11, 13, 16, 0.98) 0%,
                    rgba(11, 13, 16, 0.88) 48%,
                    rgba(11, 13, 16, 0.45) 100%
                ),
                url("assets/images/fitcore-hero.jpg") center/cover no-repeat;
        }

        .hero-content {
            padding-top: 80px;
            padding-bottom: 80px;
        }

        .eyebrow {
            display: inline-block;
            color: var(--primary);
            background: rgba(255, 107, 44, 0.12);
            border: 1px solid rgba(255, 107, 44, 0.3);
            padding: 7px 15px;
            border-radius: 30px;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-size: clamp(2.7rem, 6vw, 4.8rem);
            font-weight: 900;
            line-height: 1.12;
            margin-bottom: 22px;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero-description {
            max-width: 620px;
            font-size: 1.08rem;
            color: #c0c5cd;
            margin-bottom: 30px;
        }

        .btn-fitcore {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            border: 1px solid var(--primary);
            border-radius: 7px;
            padding: 13px 25px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-fitcore:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-outline-fitcore {
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 7px;
            padding: 13px 25px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-outline-fitcore:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Statistics strip */
        .stats-section {
            background: #111419;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 25px 0;
        }

        .stat-item {
            text-align: center;
            padding: 10px;
        }

        .stat-number {
            display: block;
            color: var(--primary);
            font-size: 1.8rem;
            font-weight: 800;
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.9rem;
        }

        /* Role section */
        .roles-section {
            padding: 85px 0 95px;
        }

        .section-heading {
            max-width: 720px;
            margin: 0 auto 45px;
            text-align: center;
        }

        .section-heading .section-label {
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .section-heading h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            margin: 12px 0 15px;
        }

        .section-heading p {
            color: var(--muted);
            margin: 0;
        }

        .role-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 30px 25px;
            transition: transform 0.3s ease, border-color 0.3s ease,
                        box-shadow 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-8px);
            border-color: rgba(255, 107, 44, 0.7);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);
        }

        .role-icon {
            width: 68px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: rgba(255, 107, 44, 0.12);
            color: var(--primary);
            font-size: 1.9rem;
            margin-bottom: 23px;
        }

        .role-number {
            color: var(--primary);
            font-size: 0.78rem;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .role-card h3 {
            font-size: 1.55rem;
            font-weight: 800;
            margin-bottom: 13px;
        }

        .role-description {
            color: var(--muted);
            min-height: 75px;
            margin-bottom: 22px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0 0 27px;
        }

        .feature-list li {
            color: #d4d8df;
            margin-bottom: 11px;
            font-size: 0.93rem;
        }

        .feature-list li::before {
            content: "\\2713";
            color: var(--primary);
            font-weight: 900;
            margin-right: 10px;
        }

        .role-button {
            width: 100%;
            margin-top: auto;
            text-align: center;
        }

        /* Demo notice */
        .demo-notice {
            background: linear-gradient(
                135deg,
                rgba(255, 107, 44, 0.12),
                rgba(255, 107, 44, 0.03)
            );
            border: 1px solid rgba(255, 107, 44, 0.23);
            border-radius: 12px;
            padding: 22px 25px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .demo-notice-icon {
            color: var(--primary);
            font-size: 1.5rem;
        }

        .demo-notice h4 {
            font-size: 1.05rem;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .demo-notice p {
            color: var(--muted);
            margin-bottom: 0;
            font-size: 0.92rem;
        }

        /* Bottom call to action */
        .cta-section {
            padding: 65px 0;
            background: var(--surface);
            border-top: 1px solid var(--border);
        }

        .cta-section h2 {
            font-size: clamp(1.8rem, 4vw, 2.7rem);
            font-weight: 800;
            margin-bottom: 15px;
        }

        .cta-section p {
            color: var(--muted);
            max-width: 650px;
            margin: 0 auto 25px;
        }

        /* Footer */
        .footer {
            background: #08090b;
            border-top: 1px solid var(--border);
            padding: 25px 0;
        }

        .footer p {
            color: var(--muted);
            margin: 0;
            font-size: 0.9rem;
        }

        .footer a {
            color: var(--primary);
        }

        /* Responsive design */
        @media (max-width: 991px) {
            .nav-link {
                margin-left: 0;
                padding: 10px 0;
            }

            .hero {
                min-height: 450px;
                background-position: 60% center;
            }

            .role-description {
                min-height: auto;
            }
        }

        @media (max-width: 767px) {
            .hero-content {
                padding-top: 65px;
                padding-bottom: 65px;
            }

            .hero {
                background:
                    linear-gradient(
                        rgba(11, 13, 16, 0.86),
                        rgba(11, 13, 16, 0.95)
                    ),
                    url("assets/images/fitcore-hero.jpg") center/cover no-repeat;
            }

            .roles-section {
                padding: 60px 0;
            }

            .role-card {
                padding: 25px 22px;
            }

            .demo-notice {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<!-- =========================
     NAVIGATION BAR
========================== -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">

        <a class="navbar-brand" href="fitcore_demo.php">
            FIT<span class="brand-highlight">CORE</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#fitcoreNavbar"
                aria-controls="fitcoreNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="fitcoreNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#roles">Explore Roles</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about-demo">About Demo</a>
                </li>

                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <a class="btn-fitcore" href="#roles">
                        Explore Demo <span aria-hidden="true">&rarr;</span>
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>


<!-- =========================
     HERO SECTION
========================== -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">

            <span class="eyebrow">
                Codvanta Project Showcase
            </span>

            <h1>
                YOUR FITNESS.<br>
                YOUR <span>JOURNEY.</span>
            </h1>

            <p class="hero-description">
                Welcome to FitCore, a modern gym membership and fitness
                booking platform. Explore how members, trainers, and
                administrators can connect through one powerful fitness
                management solution.
            </p>

            <div class="d-flex flex-wrap gap-3">
                <a href="#roles" class="btn-fitcore">
                    Explore the Demo <span aria-hidden="true">&rarr;</span>
                </a>

                <a href="#about-demo" class="btn-outline-fitcore">
                    About FitCore
                </a>
            </div>

        </div>
    </div>
</section>


<!-- =========================
     HIGHLIGHT STRIP
========================== -->
<section class="stats-section">
    <div class="container">
        <div class="row g-3">

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <span class="stat-number">03</span>
                    <span class="stat-label">User Roles</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <span class="stat-number">03</span>
                    <span class="stat-label">Membership Plans</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Demo Access</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <span class="stat-number">360&deg;</span>
                    <span class="stat-label">Fitness Management</span>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- =========================
     ROLE SELECTION SECTION
========================== -->
<section class="roles-section" id="roles">
    <div class="container">

        <div class="section-heading">
            <span class="section-label">Choose Your Experience</span>

            <h2>Explore FitCore by Role</h2>

            <p>
                Select one of the following roles to explore the features
                and interfaces designed for each type of user.
            </p>
        </div>

        <div class="row g-4">

            <!-- MEMBER CARD -->
            <div class="col-lg-4 col-md-6">
                <div class="role-card">

                    <div class="role-icon" aria-hidden="true">
                        &#128100;
                    </div>

                    <div class="role-number">Role 01</div>

                    <h3>Member</h3>

                    <p class="role-description">
                        Take control of your fitness journey by managing
                        your membership, discovering trainers, and booking
                        your favourite fitness sessions.
                    </p>

                    <ul class="feature-list">
                        <li>Register and select a membership</li>
                        <li>Explore Basic, Pro, and Premium plans</li>
                        <li>Browse trainer profiles</li>
                        <li>Book or cancel training sessions</li>
                        <li>View payment history</li>
                    </ul>

                    <a href="./projects/FitCore/customer/index.php"
                       class="btn-fitcore role-button">
                        Access Member Portal &rarr;
                    </a>

                </div>
            </div>


            <!-- TRAINER CARD -->
            <div class="col-lg-4 col-md-6">
                <div class="role-card">

                    <div class="role-icon" aria-hidden="true">
                        &#127947;
                    </div>

                    <div class="role-number">Role 02</div>

                    <h3>Trainer</h3>

                    <p class="role-description">
                        Organize your training schedule, manage your
                        availability, review appointments, and help
                        members achieve their fitness goals.
                    </p>

                    <ul class="feature-list">
                        <li>View upcoming appointments</li>
                        <li>Manage availability</li>
                        <li>View registered members</li>
                        <li>Schedule training sessions</li>
                        <li>Record session notes</li>
                    </ul>

                    <a href="./projects/FitCore/trainer/dashboard.php"
                       class="btn-fitcore role-button">
                        Access Trainer Portal &rarr;
                    </a>

                </div>
            </div>


            <!-- ADMINISTRATOR CARD -->
            <div class="col-lg-4 col-md-6 mx-md-auto">
                <div class="role-card">

                    <div class="role-icon" aria-hidden="true">
                        &#9881;
                    </div>

                    <div class="role-number">Role 03</div>

                    <h3>Administrator</h3>

                    <p class="role-description">
                        Oversee gym operations through a centralized
                        management interface for memberships, trainers,
                        classes, bookings, and payments.
                    </p>

                    <ul class="feature-list">
                        <li>View the gym dashboard</li>
                        <li>Manage members and trainers</li>
                        <li>Manage membership plans</li>
                        <li>Manage classes and bookings</li>
                        <li>View payments and reports</li>
                    </ul>

                    <a href="./projects/FitCore/admin/index.php"
                       class="btn-fitcore role-button">
                        Access Admin Portal &rarr;
                    </a>

                </div>
            </div>

        </div>

    </div>
</section>


<!-- =========================
     DEMO INFORMATION
========================== -->
<section class="container pb-5" id="about-demo">
    <div class="demo-notice">

        <div class="demo-notice-icon" aria-hidden="true">
            &#9432;
        </div>

        <div>
            <h4>About This Interactive Demonstration</h4>

            <p>
                This FitCore project is a portfolio demonstration developed
                by Codvanta to showcase the design and workflow of a gym
                management platform. Sample information may be used throughout
                the demonstration. Membership payments are simulated and
                do not process real transactions.
            </p>
        </div>

    </div>
</section>


<!-- =========================
     CALL TO ACTION
========================== -->
<section class="cta-section text-center">
    <div class="container">

        <h2>One Platform. Every Fitness Goal.</h2>

        <p>
            From membership management to personal training appointments,
            FitCore demonstrates how technology can simplify everyday
            gym operations.
        </p>

        <a href="#roles" class="btn-fitcore">
            Choose a Role &rarr;
        </a>

    </div>
</section>


<!-- =========================
     FOOTER
========================== -->
<footer class="footer">
    <div class="container">
        <div class="row align-items-center gy-3">

            <div class="col-md-6 text-center text-md-start">
                <a class="navbar-brand" href="fitcore_demo.php">
                    FIT<span class="brand-highlight">CORE</span>
                </a>

                <p class="mt-2">
                    Train Smarter. Get Stronger. Live Better.
                </p>
            </div>

            <div class="col-md-6 text-center text-md-end">
                <p>
                    &copy; <?php echo date("Y"); ?> FitCore.
                    Demonstration by
                    <a href="index.php">Codvanta</a>.
                </p>

                <p class="mt-1">
                    Turning Vision into Digital Reality.
                </p>
            </div>

        </div>
    </div>
</footer>


<!-- Local Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


</body>
</html>