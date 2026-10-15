
<?php
// NovaMart Demonstration Entry Page
// Codvanta Portfolio Project

$pageTitle = "NovaMart Demo | Codvanta";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $pageTitle; ?></title>

    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">

    <!-- Font Awesome (optional, if already available locally) -->
    <link rel="stylesheet" href="assets/css/all.min.css">

    <style>
        :root {
            --primary: #4263eb;
            --primary-dark: #304bd6;
            --navy: #101a35;
            --navy-light: #182746;
            --text-dark: #172033;
            --text-muted: #64748b;
            --page-bg: #f4f7fc;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Trebuchet MS", Arial, sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
        }

        /* Navigation */
        .navbar {
            background: var(--navy);
            padding: 16px 0;
        }

        .brand {
            color: var(--white);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand:hover {
            color: #dce6ff;
        }

        .brand span {
            color: #7da0ff;
        }

        .brand-caption {
            color: #aebbd4;
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .back-link {
            color: #e0e8fa;
            font-size: 14px;
            transition: 0.25s;
        }

        .back-link:hover {
            color: #8eacff;
        }

        /* Hero section */
        .hero {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(
                    circle at 85% 10%,
                    rgba(66, 99, 235, 0.35),
                    transparent 30%
                ),
                linear-gradient(135deg, #101a35, #182d53 65%, #243f78);
            color: var(--white);
            padding: 76px 20px 100px;
            text-align: center;
        }

        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::before {
            width: 380px;
            height: 380px;
            top: -200px;
            left: -100px;
        }

        .hero::after {
            width: 500px;
            height: 500px;
            bottom: -380px;
            right: -100px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: auto;
        }

        .project-label {
            display: inline-block;
            color: #d9e4ff;
            background: rgba(110, 145, 255, 0.15);
            border: 1px solid rgba(151, 176, 255, 0.3);
            border-radius: 30px;
            padding: 8px 17px;
            margin-bottom: 24px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .hero h1 {
            font-size: clamp(36px, 5vw, 58px);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -1.5px;
            margin-bottom: 22px;
        }

        .hero h1 span {
            color: #8eacff;
        }

        .hero p {
            color: #d1dbef;
            font-size: 17px;
            line-height: 1.8;
            max-width: 650px;
            margin: 0 auto;
        }

        /* Selection area */
        .selection-section {
            position: relative;
            z-index: 2;
            margin-top: -48px;
            padding: 0 20px 70px;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 35px;
        }

        .section-heading h2 {
            font-size: 29px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .section-heading p {
            color: var(--text-muted);
            margin: 0;
            line-height: 1.7;
        }

        .access-card {
            height: 100%;
            background: var(--white);
            border: 1px solid #e6ebf4;
            border-radius: 22px;
            padding: 36px 32px;
            box-shadow: 0 12px 35px rgba(22, 39, 76, 0.07);
            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease,
                border-color 0.3s ease;
        }

        .access-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 22px 48px rgba(22, 39, 76, 0.13);
            border-color: #b9caff;
        }

        .access-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            border-radius: 20px;
            margin-bottom: 25px;
            font-size: 34px;
        }

        .customer-icon {
            background: #eaf0ff;
            color: #4263eb;
        }

        .admin-icon {
            background: #e5f8ef;
            color: #15945d;
        }

        .access-card h3 {
            font-size: 25px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .access-card > p {
            color: var(--text-muted);
            font-size: 15px;
            line-height: 1.8;
            min-height: 81px;
            margin-bottom: 22px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0 0 28px;
        }

        .feature-list li {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-bottom: 13px;
            color: #3d4a61;
            font-size: 14px;
        }

        .feature-check {
            color: #4263eb;
            font-weight: 900;
        }

        .admin-features .feature-check {
            color: #15945d;
        }

        .access-button {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 800;
            transition: 0.25s;
        }

        .customer-button {
            color: white;
            background: var(--primary);
            border: 1px solid var(--primary);
        }

        .customer-button:hover {
            color: white;
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .admin-button {
            color: #13784d;
            background: #e7f8ef;
            border: 1px solid #c7eed8;
        }

        .admin-button:hover {
            color: white;
            background: #168653;
            border-color: #168653;
            transform: translateY(-2px);
        }

        .demo-note {
            max-width: 820px;
            margin: 38px auto 0;
            padding: 19px 23px;
            border: 1px solid #dce6fb;
            border-radius: 14px;
            background: #edf3ff;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .demo-note-icon {
            color: var(--primary);
            font-size: 21px;
            margin-top: 1px;
        }

        .demo-note p {
            color: #425474;
            font-size: 13px;
            line-height: 1.7;
            margin: 0;
        }

        /* Footer */
        footer {
            background: var(--navy);
            color: #b7c3da;
            padding: 25px 20px;
            text-align: center;
            font-size: 13px;
        }

        footer a {
            color: #8eacff;
        }

        footer a:hover {
            color: white;
        }

        /* Responsive design */
        @media (max-width: 767px) {
            .hero {
                padding: 60px 18px 85px;
            }

            .hero p {
                font-size: 15px;
            }

            .selection-section {
                margin-top: -35px;
                padding-bottom: 45px;
            }

            .section-heading h2 {
                font-size: 25px;
            }

            .access-card {
                padding: 28px 23px;
            }

            .access-card > p {
                min-height: auto;
            }

            .brand {
                font-size: 22px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container d-flex justify-content-between
                    align-items-center flex-wrap gap-3">

            <a href="../index.php" class="brand">
                Cod<span>vanta</span>
                <div class="brand-caption">
                    Turning Vision into Digital Reality
                </div>
            </a>

            <a href="javascript:history.back()" class="back-link">
                &larr; Back to Portfolio
            </a>

        </div>
    </nav>


    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">

            <div class="project-label">
                <span aria-hidden="true">&#10022;</span>
                Portfolio Project Demonstration
            </div>

            <h1>
                Welcome to <span>NovaMart</span>
            </h1>

            <p>
                Discover a modern e-commerce experience with a powerful
                inventory management system. Choose your preferred access
                and explore how NovaMart connects online shopping with
                efficient business operations.
            </p>

        </div>
    </section>


    <!-- Access Selection -->
    <section class="selection-section">

        <div class="container">

            <div class="section-heading">
                <h2>How Would You Like to Explore?</h2>
                <p>
                    Choose an experience to discover the features of NovaMart.
                </p>
            </div>


            <div class="row g-4 justify-content-center">

                <!-- Customer Access Card -->
                <div class="col-lg-5 col-md-6">

                    <div class="access-card">

                        <div class="access-icon customer-icon"
                             aria-hidden="true">
                            &#128722;
                        </div>

                        <h3>Customer Experience</h3>

                        <p>
                            Explore the online store as a customer.
                            Discover products, compare prices, add items
                            to your shopping cart, and experience the
                            checkout process.
                        </p>

                        <ul class="feature-list">
                            <li>
                                <span class="feature-check">&#10003;</span>
                                Browse products and categories
                            </li>
                            <li>
                                <span class="feature-check">&#10003;</span>
                                Search and filter products
                            </li>
                            <li>
                                <span class="feature-check">&#10003;</span>
                                View product details and discounts
                            </li>
                            <li>
                                <span class="feature-check">&#10003;</span>
                                Shopping cart and checkout
                            </li>
                            <li>
                                <span class="feature-check">&#10003;</span>
                                Explore order tracking
                            </li>
                        </ul>

                        <!-- Update this URL if your storefront uses
                             a different filename or directory. -->
                        <a href="projects/novamart/index.php"
                           class="access-button customer-button">
                            Explore as Customer
                            <span aria-hidden="true">&rarr;</span>
                        </a>

                    </div>

                </div>


                <!-- Administrator Access Card -->
                <div class="col-lg-5 col-md-6">

                    <div class="access-card">

                        <div class="access-icon admin-icon"
                             aria-hidden="true">
                            &#9881;
                        </div>

                        <h3>Administrator Experience</h3>

                        <p>
                            Explore the management side of NovaMart.
                            See how administrators manage products,
                            monitor inventory, process customer orders,
                            and review store performance.
                        </p>

                        <ul class="feature-list admin-features">
                            <li>
                                <span class="feature-check">&#10003;</span>
                                Sales and business dashboard
                            </li>
                            <li>
                                <span class="feature-check">&#10003;</span>
                                Product and category management
                            </li>
                            <li>
                                <span class="feature-check">&#10003;</span>
                                Inventory and stock alerts
                            </li>
                            <li>
                                <span class="feature-check">&#10003;</span>
                                Order processing and status updates
                            </li>
                            <li>
                                <span class="feature-check">&#10003;</span>
                                Sales and inventory reports
                            </li>
                        </ul>

                        <!-- Update this URL if your admin dashboard
                             uses a different filename or directory. -->
                        <a href="novamart/admin/index.php"
                           class="access-button admin-button">
                            Explore as Administrator
                            <span aria-hidden="true">&rarr;</span>
                        </a>

                    </div>

                </div>

            </div>


            <!-- Demo Notice -->
            <div class="demo-note">

                <div class="demo-note-icon" aria-hidden="true">
                    &#8505;
                </div>

                <p>
                    <strong>About this demonstration:</strong>
                    NovaMart is a portfolio demonstration developed by
                    Codvanta to showcase e-commerce and inventory
                    management capabilities. Some operations, including
                    payment processing, may be simulated. Please use
                    demonstration data only and do not enter real payment
                    information or sensitive personal details.
                </p>

            </div>

        </div>

    </section>


    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="mb-2">
                &copy; <?php echo date("Y"); ?>
                <a href="../index.php">Codvanta</a>.
                Turning Vision into Digital Reality.
            </p>

            <p class="mb-0">
                NovaMart E-Commerce &amp; Inventory Management Platform
            </p>
        </div>
    </footer>

</body>
</html>