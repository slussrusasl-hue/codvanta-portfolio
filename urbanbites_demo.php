
<?php
$pageTitle = "UrbanBites Demo | Codvanta";

// Project destination URLs
$customerUrl = "projects/urbanbites/index.php";
$adminUrl = "projects/urbanbites/admin/index.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $pageTitle; ?></title>

    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">

    <!-- Font Awesome (optional local asset) -->
    <!-- Add your local Font Awesome CSS here if available -->

    <style>
        :root {
            --primary: #635bff;
            --primary-dark: #4941db;
            --dark: #101828;
            --text: #475467;
            --muted: #667085;
            --light-bg: #f8f9fc;
            --border: #e4e7ec;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Trebuchet MS", Arial, sans-serif;
            color: var(--dark);
            background: var(--white);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        /* Navigation */

        .navbar {
            padding: 18px 0;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid #f0f1f5;
        }

        .navbar-brand {
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--dark);
        }

        .brand-highlight {
            color: var(--primary);
        }

        .navbar-brand:hover {
            color: var(--dark);
        }

        .nav-link {
            color: #344054;
            font-weight: 600;
            margin-left: 22px;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 25px;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary);
        }

        /* Hero section */

        .demo-hero {
            position: relative;
            overflow: hidden;
            padding: 75px 0 85px;
            background:
                radial-gradient(
                    circle at 10% 10%,
                    rgba(99, 91, 255, 0.10),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 90% 80%,
                    rgba(32, 201, 151, 0.08),
                    transparent 28%
                ),
                #fbfbff;
        }

        .hero-content {
            max-width: 850px;
            margin: 0 auto;
            text-align: center;
        }

        .project-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border: 1px solid #e1dfff;
            border-radius: 50px;
            color: var(--primary-dark);
            background: #f0efff;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            background: #12b76a;
            border-radius: 50%;
        }

        .hero-content h1 {
            font-size: clamp(36px, 5vw, 58px);
            line-height: 1.15;
            letter-spacing: -1.8px;
            font-weight: 800;
            margin-bottom: 22px;
        }

        .hero-content h1 span {
            color: var(--primary);
        }

        .hero-content p {
            max-width: 690px;
            margin: 0 auto;
            font-size: 17px;
            color: var(--muted);
            line-height: 1.9;
        }

        /* Project choice section */

        .choice-section {
            padding: 65px 0 85px;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 42px;
        }

        .section-heading h2 {
            font-size: clamp(27px, 4vw, 36px);
            font-weight: 800;
            letter-spacing: -0.7px;
            margin-bottom: 12px;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 15px;
        }

        .choice-card {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 35px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: 0 10px 35px rgba(16, 24, 40, 0.045);
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .choice-card:hover {
            transform: translateY(-7px);
            border-color: #c8c4ff;
            box-shadow: 0 22px 45px rgba(16, 24, 40, 0.09);
        }

        .choice-icon {
            width: 68px;
            height: 68px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 18px;
            font-size: 31px;
            margin-bottom: 25px;
        }

        .admin-icon {
            background: #eeedff;
        }

        .customer-icon {
            background: #e7f8ef;
        }

        .choice-label {
            display: inline-block;
            width: fit-content;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary-dark);
            background: #f0efff;
            padding: 6px 10px;
            border-radius: 7px;
            margin-bottom: 14px;
        }

        .customer-card .choice-label {
            color: #087443;
            background: #e7f8ef;
        }

        .choice-card h3 {
            font-size: 25px;
            font-weight: 800;
            margin-bottom: 13px;
        }

        .choice-description {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 24px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0 0 30px;
        }

        .feature-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #344054;
            font-size: 14px;
            margin-bottom: 13px;
        }

        .feature-check {
            color: #12a76a;
            font-weight: 800;
            font-size: 17px;
            line-height: 1.3;
        }

        .choice-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px 20px;
            border-radius: 11px;
            font-size: 15px;
            font-weight: 700;
            transition: all 0.2s ease;
            margin-top: auto;
        }

        .admin-button {
            background: var(--primary);
            color: white;
            border: 1px solid var(--primary);
        }

        .admin-button:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: white;
        }

        .customer-button {
            color: #087443;
            background: #e7f8ef;
            border: 1px solid #c5f0d8;
        }

        .customer-button:hover {
            color: white;
            background: #087443;
            border-color: #087443;
        }

        .button-arrow {
            font-size: 19px;
            transition: transform 0.2s ease;
        }

        .choice-button:hover .button-arrow {
            transform: translateX(4px);
        }

        /* Demo notice */

        .notice {
            max-width: 850px;
            margin: 0 auto;
            padding: 19px 22px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            border: 1px solid #f2dfac;
            background: #fffbeb;
            border-radius: 14px;
        }

        .notice-icon {
            font-size: 22px;
        }

        .notice h4 {
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 5px;
            color: #854d0e;
        }

        .notice p {
            color: #795b26;
            font-size: 13px;
            margin: 0;
            line-height: 1.7;
        }

        /* Footer */

        .footer {
            padding: 27px 0;
            border-top: 1px solid #eaecf0;
            background: #fcfcfd;
        }

        .footer-brand {
            font-size: 21px;
            font-weight: 800;
            color: var(--dark);
        }

        .footer p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
        }

        .footer-link {
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
        }

        .footer-link:hover {
            color: var(--primary);
        }

        /* Responsive */

        @media (max-width: 767px) {
            .demo-hero {
                padding: 55px 0 60px;
            }

            .hero-content p {
                font-size: 15px;
            }

            .choice-section {
                padding: 45px 0 60px;
            }

            .choice-card {
                padding: 27px;
            }

            .footer .container {
                text-align: center;
                gap: 12px;
            }
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            Cod<span class="brand-highlight">vanta</span>.
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="services.php">Services</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="projects.php">Projects</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="demo-hero">
    <div class="container">

        <div class="hero-content">

            <a href="projects.php" class="back-link">
                <span aria-hidden="true">&larr;</span>
                Back to Projects
            </a>

            <div>
                <span class="project-badge">
                    <span class="badge-dot"></span>
                    Interactive Project Demo
                </span>
            </div>

            <h1>
                Explore <span>UrbanBites</span>
            </h1>

            <p>
                Discover our restaurant ordering and management system.
                Choose the customer experience to explore the restaurant
                website, or explore the administration dashboard to see how
                restaurant operations can be managed from one place.
            </p>

        </div>

    </div>
</section>

<!-- User choices -->
<section class="choice-section">
    <div class="container">

        <div class="section-heading">
            <h2>How Would You Like to Explore?</h2>
            <p>Select an experience to explore the UrbanBites demonstration.</p>
        </div>

        <div class="row g-4 justify-content-center">

            <!-- Admin card -->
            <div class="col-lg-5 col-md-6">
                <div class="choice-card">

                    <div class="choice-icon admin-icon">
                        &#9881;
                    </div>

                    <span class="choice-label">Management Experience</span>

                    <h3>Explore as Admin</h3>

                    <p class="choice-description">
                        Discover the management side of UrbanBites.
                        Explore the dashboard and see how restaurant
                        operations can be organized and monitored.
                    </p>

                    <ul class="feature-list">
                        <li>
                            <span class="feature-check">&#10003;</span>
                            <span>View the administration dashboard and order statuses</span>
                        </li>

                        <li>
                            <span class="feature-check">&#10003;</span>
                            <span>Explore menu and food price management</span>
                        </li>

                        <li>
                            <span class="feature-check">&#10003;</span>
                            <span>Review customer information and inventory alerts</span>
                        </li>

                        <li>
                            <span class="feature-check">&#10003;</span>
                            <span>Discover other sample restaurant management pages</span>
                        </li>
                    </ul>

                    <a href="<?php echo htmlspecialchars($adminUrl); ?>"
                       class="choice-button admin-button">
                        Explore Admin Dashboard
                        <span class="button-arrow" aria-hidden="true">&rarr;</span>
                    </a>

                </div>
            </div>

            <!-- Customer card -->
            <div class="col-lg-5 col-md-6">
                <div class="choice-card customer-card">

                    <div class="choice-icon customer-icon">
                        &#127828;
                    </div>

                    <span class="choice-label">Customer Experience</span>

                    <h3>Explore as Customer</h3>

                    <p class="choice-description">
                        Experience the restaurant website from a customer's
                        perspective. Browse the menu and try the sample
                        ordering process.
                    </p>

                    <ul class="feature-list">
                        <li>
                            <span class="feature-check">&#10003;</span>
                            <span>Browse food items and search the menu</span>
                        </li>

                        <li>
                            <span class="feature-check">&#10003;</span>
                            <span>Add items to the shopping cart and update quantities</span>
                        </li>

                        <li>
                            <span class="feature-check">&#10003;</span>
                            <span>Explore checkout and delivery options</span>
                        </li>

                        <li>
                            <span class="feature-check">&#10003;</span>
                            <span>Experience the sample order confirmation process</span>
                        </li>
                    </ul>

                    <a href="<?php echo htmlspecialchars($customerUrl); ?>"
                       class="choice-button customer-button">
                        Visit Restaurant Website
                        <span class="button-arrow" aria-hidden="true">&rarr;</span>
                    </a>

                </div>
            </div>

        </div>

        <!-- Demo notice -->
        <div class="notice mt-5">
            <div class="notice-icon" aria-hidden="true">&#9432;</div>

            <div>
                <h4>About This Demonstration</h4>

                <p>
                    UrbanBites is a portfolio demonstration created by
                    Codvanta. Sample data and certain interactions are
                    provided to demonstrate the user interface and
                    application workflow. Changes made in the demo may
                    not be permanently saved. No real orders or payments
                    are processed.
                </p>
            </div>
        </div>

    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container d-flex flex-column flex-md-row
                justify-content-between align-items-center">

        <a href="index.php" class="footer-brand">
            Cod<span class="brand-highlight">vanta</span>.
        </a>

        <p class="my-2 my-md-0">
            Turning Vision into Digital Reality.
        </p>

        <a href="projects.php" class="footer-link">
            Explore More Projects
        </a>

    </div>
</footer>

<!-- Local Bootstrap JavaScript -->
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>
