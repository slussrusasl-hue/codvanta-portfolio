
<?php
$pageTitle = "ProServe | Professional Business Services";

/*
|--------------------------------------------------------------------------
| ProServe Customer Landing Page
|--------------------------------------------------------------------------
| This page is a frontend demonstration.
| Customer accounts, appointments and quotations will be connected
| to the backend in the following development stages.
*/

$services = [
    [
        "icon" => "💻",
        "title" => "IT & Web Development",
        "description" => "Websites, business applications, IT support and digital solutions.",
        "category" => "Technology"
    ],
    [
        "icon" => "📈",
        "title" => "Digital Marketing",
        "description" => "Digital campaigns, SEO, social media and online growth strategies.",
        "category" => "Marketing"
    ],
    [
        "icon" => "🧹",
        "title" => "Cleaning Services",
        "description" => "Professional cleaning solutions for offices and commercial properties.",
        "category" => "Property"
    ],
    [
        "icon" => "🔧",
        "title" => "Repair & Maintenance",
        "description" => "Reliable repair, maintenance and technical assistance for your business.",
        "category" => "Maintenance"
    ],
    [
        "icon" => "⚡",
        "title" => "Electrical Services",
        "description" => "Electrical installation, inspection and maintenance services.",
        "category" => "Technical"
    ],
    [
        "icon" => "📊",
        "title" => "Business Consulting",
        "description" => "Practical advice to improve business operations and performance.",
        "category" => "Consulting"
    ]
];

$steps = [
    [
        "number" => "01",
        "title" => "Explore Services",
        "description" => "Find the service that matches your business requirements."
    ],
    [
        "number" => "02",
        "title" => "Submit a Request",
        "description" => "Describe your project and request a quotation or consultation."
    ],
    [
        "number" => "03",
        "title" => "Confirm the Details",
        "description" => "Review your appointment, service proposal and quotation."
    ],
    [
        "number" => "04",
        "title" => "Get the Service",
        "description" => "Track your service requests and access your invoices."
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($pageTitle); ?>
    </title>

    <meta name="description"
          content="Discover professional business services with ProServe. Explore IT, cleaning, maintenance, marketing and consulting services.">

    <meta name="theme-color" content="#123b72">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #102b4e;
            --navy-dark: #0b1d35;
            --text: #24364b;
            --muted: #687b91;
            --background: #f5f8fc;
            --white: #ffffff;
            --border: #e3eaf3;
            --success: #15803d;
            --radius: 18px;
            --shadow: 0 12px 35px rgba(16, 43, 78, 0.07);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            font-family: "Trebuchet MS", Arial, sans-serif;
            color: var(--text);
            background: var(--white);
            line-height: 1.7;
        }

        body,
        button,
        input {
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        button:focus-visible,
        a:focus-visible {
            outline: 3px solid #93c5fd;
            outline-offset: 3px;
        }

        img {
            max-width: 100%;
        }

        .container {
            width: min(1160px, calc(100% - 48px));
            margin: 0 auto;
        }

        .section {
            padding: 85px 0;
        }

        .section-light {
            background: var(--background);
        }

        .section-header {
            max-width: 680px;
            margin: 0 auto 44px;
            text-align: center;
        }

        .section-label {
            display: inline-block;
            margin-bottom: 10px;
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .section-header h2 {
            color: var(--navy);
            font-size: clamp(29px, 4vw, 41px);
            line-height: 1.25;
            letter-spacing: -1px;
        }

        .section-header p {
            margin-top: 15px;
            color: var(--muted);
            font-size: 15px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 48px;
            padding: 12px 20px;
            border: 1px solid transparent;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: background 0.2s,
                        color 0.2s,
                        transform 0.2s,
                        border-color 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            color: var(--white);
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline {
            color: var(--navy);
            background: var(--white);
            border-color: #cbd7e6;
        }

        .btn-outline:hover {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-white {
            color: var(--primary-dark);
            background: var(--white);
        }

        .btn-white:hover {
            background: #eff6ff;
        }

        /* Navigation */

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.97);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
        }

        .nav-inner {
            min-height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            flex-shrink: 0;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border-radius: 13px;
            color: white;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            font-size: 25px;
            font-weight: 900;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.2);
        }

        .brand-name {
            color: var(--navy);
            font-size: 23px;
            font-weight: 900;
            letter-spacing: -0.7px;
            line-height: 1.2;
        }

        .brand-caption {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.6px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-links > a:not(.btn) {
            color: #506177;
            font-size: 13px;
            font-weight: 700;
            transition: color 0.2s;
        }

        .nav-links > a:not(.btn):hover {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-actions .btn {
            min-height: 42px;
            padding: 10px 15px;
        }

        .menu-toggle {
            display: none;
            width: 43px;
            height: 43px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: white;
            color: var(--navy);
            font-size: 23px;
            cursor: pointer;
        }

        /* Hero */

        .hero {
            position: relative;
            overflow: hidden;
            padding: 84px 0 88px;
            background:
                radial-gradient(
                    circle at 82% 25%,
                    rgba(96, 165, 250, 0.20),
                    transparent 28%
                ),
                linear-gradient(125deg, #f0f6ff, #ffffff 55%, #edf4ff);
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            align-items: center;
            gap: 65px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 13px;
            border: 1px solid #c9dcfb;
            border-radius: 30px;
            background: #ffffff;
            color: var(--primary-dark);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .hero-badge span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
        }

        .hero h1 {
            margin: 24px 0 20px;
            color: var(--navy);
            font-size: clamp(40px, 5vw, 59px);
            line-height: 1.12;
            letter-spacing: -2px;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero-description {
            max-width: 550px;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.9;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 29px;
        }

        .hero-trust {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin-top: 30px;
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #4e6076;
            font-size: 12px;
            font-weight: 700;
        }

        .trust-check {
            display: grid;
            place-items: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #dcfce7;
            color: #15803d;
            font-size: 11px;
            font-weight: 900;
        }

        /* Dashboard preview */

        .dashboard-preview {
            position: relative;
            padding: 23px;
            border: 1px solid #dbe6f5;
            border-radius: 23px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 25px 70px rgba(29, 78, 216, 0.12);
        }

        .preview-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }

        .preview-top h3 {
            color: var(--navy);
            font-size: 16px;
        }

        .demo-badge {
            padding: 5px 9px;
            border-radius: 20px;
            color: #1d4ed8;
            background: #dbeafe;
            font-size: 10px;
            font-weight: 900;
        }

        .preview-welcome {
            margin: 20px 0;
        }

        .preview-welcome h4 {
            color: var(--navy);
            font-size: 20px;
        }

        .preview-welcome p {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .preview-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .preview-stat {
            padding: 16px;
            border: 1px solid #e5edf7;
            border-radius: 14px;
            background: #f8fbff;
        }

        .preview-stat .symbol {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            margin-bottom: 12px;
            border-radius: 11px;
            background: #dbeafe;
            font-size: 19px;
        }

        .preview-stat strong {
            display: block;
            color: var(--navy);
            font-size: 15px;
        }

        .preview-stat p {
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .preview-appointment {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-top: 15px;
            padding: 14px;
            border-radius: 13px;
            background: #eff6ff;
        }

        .appointment-symbol {
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            border-radius: 12px;
            background: white;
            font-size: 21px;
        }

        .preview-appointment strong {
            display: block;
            color: var(--navy);
            font-size: 12px;
        }

        .preview-appointment p {
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
        }

        .preview-status {
            margin-left: auto;
            padding: 5px 8px;
            border-radius: 20px;
            background: #fef3c7;
            color: #92400e;
            font-size: 9px;
            font-weight: 900;
        }

        /* Service cards */

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .service-card {
            display: flex;
            flex-direction: column;
            padding: 27px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: white;
            box-shadow: 0 8px 25px rgba(16, 43, 78, 0.025);
            transition: transform 0.25s,
                        box-shadow 0.25s,
                        border-color 0.25s;
        }

        .service-card:hover {
            transform: translateY(-5px);
            border-color: #b6d0fa;
            box-shadow: var(--shadow);
        }

        .service-icon {
            display: grid;
            place-items: center;
            width: 57px;
            height: 57px;
            margin-bottom: 20px;
            border-radius: 15px;
            background: #eff6ff;
            font-size: 28px;
        }

        .service-category {
            margin-bottom: 7px;
            color: var(--primary);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .service-card h3 {
            margin-bottom: 10px;
            color: var(--navy);
            font-size: 19px;
            line-height: 1.4;
        }

        .service-card p {
            flex: 1;
            margin-bottom: 21px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .service-link {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--primary);
            font-size: 12px;
            font-weight: 900;
        }

        .service-link:hover {
            color: var(--primary-dark);
        }

        .services-more {
            margin-top: 35px;
            text-align: center;
        }

        /* Benefits */

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .benefit-card {
            padding: 25px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: white;
        }

        .benefit-icon {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            margin-bottom: 16px;
            border-radius: 13px;
            background: #dbeafe;
            font-size: 23px;
        }

        .benefit-card h3 {
            margin-bottom: 9px;
            color: var(--navy);
            font-size: 17px;
        }

        .benefit-card p {
            color: var(--muted);
            font-size: 13px;
        }

        /* Workflow */

        .workflow-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .workflow-card {
            position: relative;
            padding: 25px 20px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: white;
        }

        .workflow-number {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            margin-bottom: 18px;
            border-radius: 12px;
            background: #dbeafe;
            color: var(--primary-dark);
            font-size: 14px;
            font-weight: 900;
        }

        .workflow-card h3 {
            margin-bottom: 9px;
            color: var(--navy);
            font-size: 16px;
        }

        .workflow-card p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        /* CTA */

        .cta-section {
            padding: 75px 0;
            background:
                radial-gradient(
                    circle at 90% 20%,
                    rgba(147, 197, 253, 0.18),
                    transparent 30%
                ),
                linear-gradient(120deg, #123b72, #1d4ed8);
            color: white;
        }

        .cta-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 35px;
        }

        .cta-content h2 {
            max-width: 640px;
            font-size: clamp(28px, 4vw, 39px);
            line-height: 1.3;
            letter-spacing: -0.8px;
        }

        .cta-content p {
            max-width: 650px;
            margin-top: 13px;
            color: #dbeafe;
            font-size: 14px;
        }

        .cta-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 11px;
            flex-shrink: 0;
        }

        .cta-actions .btn-outline {
            color: white;
            border-color: rgba(255, 255, 255, 0.45);
            background: transparent;
        }

        .cta-actions .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Footer */

        .footer {
            padding: 55px 0 0;
            background: var(--navy-dark);
            color: #cbd5e1;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 45px;
            padding-bottom: 40px;
        }

        .footer-brand .brand-name {
            color: white;
        }

        .footer-brand .brand-caption {
            color: #a9bdd7;
        }

        .footer-description {
            max-width: 360px;
            margin-top: 17px;
            color: #a9b8ca;
            font-size: 13px;
        }

        .footer-column h3 {
            margin-bottom: 17px;
            color: white;
            font-size: 14px;
        }

        .footer-column a {
            display: block;
            width: fit-content;
            margin: 10px 0;
            color: #a9b8ca;
            font-size: 12px;
            transition: color 0.2s;
        }

        .footer-column a:hover {
            color: white;
        }

        .footer-bottom {
            padding: 19px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }

        .footer-bottom-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .footer-bottom p,
        .footer-bottom a {
            color: #a9b8ca;
            font-size: 11px;
        }

        .footer-bottom strong {
            color: white;
        }

        /* Responsive navigation and layouts */

        @media (max-width: 1000px) {
            .nav-links {
                gap: 15px;
            }

            .nav-links > a:not(.btn) {
                font-size: 12px;
            }

            .hero-grid {
                gap: 35px;
            }

            .services-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .workflow-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .cta-content {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(100% - 36px, 600px);
            }

            .section {
                padding: 65px 0;
            }

            .nav-inner {
                min-height: 72px;
            }

            .menu-toggle {
                display: grid;
                place-items: center;
            }

            .nav-links {
                position: absolute;
                top: calc(100% + 1px);
                left: 0;
                right: 0;
                display: none;
                align-items: stretch;
                flex-direction: column;
                gap: 0;
                padding: 12px 22px 20px;
                background: white;
                border-bottom: 1px solid var(--border);
                box-shadow: 0 15px 25px rgba(16, 43, 78, 0.08);
            }

            .nav-links.open {
                display: flex;
            }

            .nav-links > a:not(.btn) {
                display: block;
                padding: 12px 0;
                font-size: 14px;
            }

            .nav-actions {
                padding-top: 12px;
                border-top: 1px solid var(--border);
            }

            .nav-actions .btn {
                flex: 1;
            }

            .hero {
                padding: 60px 0 65px;
            }

            .hero-grid {
                grid-template-columns: 1fr;
                gap: 38px;
            }

            .hero h1 {
                font-size: clamp(39px, 9vw, 53px);
                letter-spacing: -1.5px;
            }

            .hero-description {
                font-size: 14px;
            }

            .dashboard-preview {
                padding: 18px;
            }

            .benefits-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .footer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 30px;
            }

            .footer-brand {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 480px) {
            .brand-name {
                font-size: 20px;
            }

            .brand-mark {
                width: 39px;
                height: 39px;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .hero-trust {
                gap: 11px;
                flex-direction: column;
            }

            .services-grid,
            .benefits-grid,
            .workflow-grid {
                grid-template-columns: 1fr;
            }

            .service-card {
                padding: 23px;
            }

            .preview-stats {
                gap: 9px;
            }

            .preview-stat {
                padding: 12px;
            }

            .preview-status {
                display: none;
            }

            .cta-actions {
                width: 100%;
                flex-direction: column;
            }

            .cta-actions .btn {
                width: 100%;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .footer-brand {
                grid-column: auto;
            }

            .footer-bottom-inner {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>

<!-- =========================================================
     NAVIGATION BAR
========================================================= -->

<header class="navbar">

    <div class="container nav-inner">

        <a href="index.php" class="brand" aria-label="ProServe Home">

            <span class="brand-mark">&#8644;</span>

            <span>
                <span class="brand-name">ProServe</span>
                <span class="brand-caption">
                    CRM & SERVICE MANAGEMENT
                </span>
            </span>

        </a>


        <button
            type="button"
            class="menu-toggle"
            id="menuToggle"
            aria-label="Open navigation menu"
            aria-expanded="false"
            aria-controls="mainNavigation"
        >
            &#9776;
        </button>


        <nav class="nav-links" id="mainNavigation">

            <a href="index.php">Home</a>

            <a href="services.php">Services</a>

            <a href="./my_appointments.php">My Appointments</a>

            <a href="./dashboard.php">Dashboard</a>

            <a href="./profile.php">My Profile</a>

            <div class="nav-actions">

                <a href="login.php" class="btn btn-outline">
                    Sign In
                </a>

                <a href="register.php" class="btn btn-primary">
                    Get Started
                    <span>&rarr;</span>
                </a>

            </div>

        </nav>

    </div>

</header>


<main>

    <!-- =====================================================
         HERO SECTION
    ====================================================== -->

    <section class="hero">

        <div class="container hero-grid">

            <div class="hero-content">

                <div class="hero-badge">
                    <span></span>
                    YOUR BUSINESS SERVICE PARTNER
                </div>

                <h1>
                    Professional Services.
                    <span>Simplified.</span>
                </h1>

                <p class="hero-description">
                    From IT solutions and digital marketing to
                    maintenance and business consulting, ProServe
                    makes it easier to find services, request
                    quotations, schedule consultations, and manage
                    your service requests in one place.
                </p>

                <div class="hero-actions">

                    <a href="services.php" class="btn btn-primary">
                        Explore Our Services
                        <span>&rarr;</span>
                    </a>

                    <a href="book_appointment.php"
                       class="btn btn-outline">
                        &#128197; Book a Consultation
                    </a>

                </div>

                <div class="hero-trust">

                    <div class="trust-item">
                        <span class="trust-check">&#10003;</span>
                        Convenient Service Requests
                    </div>

                    <div class="trust-item">
                        <span class="trust-check">&#10003;</span>
                        Organized Appointments
                    </div>

                    <div class="trust-item">
                        <span class="trust-check">&#10003;</span>
                        Clear Quotations & Invoices
                    </div>

                </div>

            </div>


            <!-- Customer Dashboard Preview -->

            <div class="dashboard-preview">

                <div class="preview-top">

                    <h3>Your Service Overview</h3>

                    <span class="demo-badge">
                        DEMO PREVIEW
                    </span>

                </div>


                <div class="preview-welcome">

                    <h4>Everything in one place.</h4>

                    <p>
                        A convenient view of your service journey.
                    </p>

                </div>


                <div class="preview-stats">

                    <div class="preview-stat">

                        <div class="symbol">&#128196;</div>

                        <strong>Service Requests</strong>

                        <p>Submit and track your requirements</p>

                    </div>


                    <div class="preview-stat">

                        <div class="symbol">&#128197;</div>

                        <strong>Appointments</strong>

                        <p>Manage consultation requests</p>

                    </div>


                    <div class="preview-stat">

                        <div class="symbol">&#128203;</div>

                        <strong>Quotations</strong>

                        <p>Review proposed service prices</p>

                    </div>


                    <div class="preview-stat">

                        <div class="symbol">&#128179;</div>

                        <strong>Invoices</strong>

                        <p>Access your billing documents</p>

                    </div>

                </div>


                <div class="preview-appointment">

                    <div class="appointment-symbol">
                        &#128197;
                    </div>

                    <div>
                        <strong>Website Development Consultation</strong>

                        <p>
                            Request a convenient date and time
                        </p>
                    </div>

                    <span class="preview-status">
                        REQUEST
                    </span>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         SERVICES SECTION
    ====================================================== -->

    <section class="section" id="services">

        <div class="container">

            <div class="section-header">

                <span class="section-label">
                    What We Offer
                </span>

                <h2>Services Designed Around Your Needs</h2>

                <p>
                    Discover professional services for your business,
                    property, technology, and everyday operational
                    requirements.
                </p>

            </div>


            <div class="services-grid">

                <?php foreach ($services as $service): ?>

                    <article class="service-card">

                        <div class="service-icon">
                            <?php
                            echo htmlspecialchars($service["icon"]);
                            ?>
                        </div>

                        <div class="service-category">
                            <?php
                            echo htmlspecialchars($service["category"]);
                            ?>
                        </div>

                        <h3>
                            <?php
                            echo htmlspecialchars($service["title"]);
                            ?>
                        </h3>

                        <p>
                            <?php
                            echo htmlspecialchars($service["description"]);
                            ?>
                        </p>

                        <a href="services.php" class="service-link">
                            Explore Service
                            <span>&rarr;</span>
                        </a>

                    </article>

                <?php endforeach; ?>

            </div>


            <div class="services-more">

                <a href="services.php" class="btn btn-outline">
                    View All Services
                    <span>&rarr;</span>
                </a>

            </div>

        </div>

    </section>


    <!-- =====================================================
         WHY PROSERVE
    ====================================================== -->

    <section class="section section-light" id="why-proserve">

        <div class="container">

            <div class="section-header">

                <span class="section-label">
                    The ProServe Advantage
                </span>

                <h2>A Better Way to Manage Your Service Needs</h2>

                <p>
                    A structured service experience helps customers
                    communicate their requirements and follow progress
                    without losing track of important details.
                </p>

            </div>


            <div class="benefits-grid">

                <article class="benefit-card">

                    <div class="benefit-icon">&#128269;</div>

                    <h3>Find the Right Service</h3>

                    <p>
                        Explore different service categories and
                        identify the solution that matches your
                        requirements.
                    </p>

                </article>


                <article class="benefit-card">

                    <div class="benefit-icon">&#128221;</div>

                    <h3>Clear Quotations</h3>

                    <p>
                        Submit your project requirements and review
                        proposed prices before confirming a service.
                    </p>

                </article>


                <article class="benefit-card">

                    <div class="benefit-icon">&#128197;</div>

                    <h3>Convenient Scheduling</h3>

                    <p>
                        Request consultations by selecting your
                        preferred service date and time.
                    </p>

                </article>


                <article class="benefit-card">

                    <div class="benefit-icon">&#128230;</div>

                    <h3>Organized Requests</h3>

                    <p>
                        Keep your service enquiries and appointment
                        details organized in your customer portal.
                    </p>

                </article>


                <article class="benefit-card">

                    <div class="benefit-icon">&#128196;</div>

                    <h3>Accessible Invoices</h3>

                    <p>
                        View billing information and access invoice
                        documents associated with your services.
                    </p>

                </article>


                <article class="benefit-card">

                    <div class="benefit-icon">&#128172;</div>

                    <h3>Better Communication</h3>

                    <p>
                        Provide project details and keep relevant
                        service information connected to your requests.
                    </p>

                </article>

            </div>

        </div>

    </section>


    <!-- =====================================================
         HOW IT WORKS
    ====================================================== -->

    <section class="section" id="how-it-works">

        <div class="container">

            <div class="section-header">

                <span class="section-label">
                    Simple Process
                </span>

                <h2>How ProServe Works</h2>

                <p>
                    Follow a straightforward process from discovering
                    a service to reviewing your quotation and invoice.
                </p>

            </div>


            <div class="workflow-grid">

                <?php foreach ($steps as $step): ?>

                    <article class="workflow-card">

                        <div class="workflow-number">
                            <?php
                            echo htmlspecialchars($step["number"]);
                            ?>
                        </div>

                        <h3>
                            <?php
                            echo htmlspecialchars($step["title"]);
                            ?>
                        </h3>

                        <p>
                            <?php
                            echo htmlspecialchars($step["description"]);
                            ?>
                        </p>

                    </article>

                <?php endforeach; ?>

            </div>

        </div>

    </section>


    <!-- =====================================================
         CALL TO ACTION
    ====================================================== -->

    <section class="cta-section">

        <div class="container cta-content">

            <div>

                <h2>
                    Have a Project in Mind?
                    Let's Get Started.
                </h2>

                <p>
                    Tell us what you need, request a quotation, or
                    arrange a consultation to discuss your project.
                    Start by exploring the ProServe customer portal.
                </p>

            </div>


            <div class="cta-actions">

                <a href="request_quote.php"
                   class="btn btn-white">
                    Request a Quotation
                    <span>&rarr;</span>
                </a>

                <a href="book_appointment.php"
                   class="btn btn-outline">
                    Book Consultation
                </a>

            </div>

        </div>

    </section>

</main>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    <div class="container">

        <div class="footer-grid">

            <div class="footer-brand">

                <a href="index.php" class="brand">

                    <span class="brand-mark">&#8644;</span>

                    <span>
                        <span class="brand-name">ProServe</span>

                        <span class="brand-caption">
                            CRM & SERVICE MANAGEMENT
                        </span>
                    </span>

                </a>

                <p class="footer-description">
                    A unified service management experience for
                    discovering services, requesting quotations,
                    scheduling consultations, and organizing
                    customer service information.
                </p>

            </div>


            <div class="footer-column">

                <h3>Explore</h3>

                <a href="index.php">Home</a>

                <a href="services.php">Our Services</a>

                <a href="#how-it-works">How It Works</a>

                <a href="#why-proserve">Why ProServe</a>

            </div>


            <div class="footer-column">

                <h3>Customer Portal</h3>

                <a href="login.php">Sign In</a>

                <a href="register.php">Create an Account</a>

                <a href="book_appointment.php">Book an Appointment</a>

                <a href="request_quote.php">Request a Quotation</a>

                <a href="contact.php">Contact Us</a>

            </div>

        </div>


        <div class="footer-bottom">

            <div class="footer-bottom-inner">

                <p>
                    &copy; <?php echo date("Y"); ?>
                    ProServe. Portfolio demonstration by
                    <strong>Codvanta</strong>.
                </p>

                <p>
                    <a href="../index.php">
                        &larr; Back to Codvanta Portfolio
                    </a>
                </p>

            </div>

        </div>

    </div>

</footer>


<!-- =========================================================
     MOBILE NAVIGATION SCRIPT
========================================================= -->

<script>
    const menuToggle = document.getElementById("menuToggle");
    const mainNavigation = document.getElementById("mainNavigation");

    menuToggle.addEventListener("click", function () {
        const isOpen = mainNavigation.classList.toggle("open");

        menuToggle.setAttribute("aria-expanded", String(isOpen));

        menuToggle.setAttribute(
            "aria-label",
            isOpen ? "Close navigation menu" : "Open navigation menu"
        );

        menuToggle.innerHTML = isOpen ? "&times;" : "&#9776;";
    });

    // Close the mobile navigation after selecting a link.
    mainNavigation.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            mainNavigation.classList.remove("open");
            menuToggle.setAttribute("aria-expanded", "false");
            menuToggle.setAttribute("aria-label", "Open navigation menu");
            menuToggle.innerHTML = "&#9776;";
        });
    });
</script>

</body>
</html>
