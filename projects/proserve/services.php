
<?php
$pageTitle = "Our Services | ProServe";

/*
|--------------------------------------------------------------------------
| ProServe Service Catalogue
|--------------------------------------------------------------------------
| Frontend demonstration data.
| This array can later be replaced with records from a MySQL services table.
*/

$services = [
    [
        "id" => 1,
        "icon" => "💻",
        "title" => "Website Development",
        "category" => "Technology",
        "description" => "Professional, responsive websites designed to strengthen your online presence and support your business goals.",
        "features" => [
            "Business and corporate websites",
            "E-commerce website development",
            "Website redesign and maintenance"
        ],
        "duration" => "Project-based",
        "popular" => true
    ],
    [
        "id" => 2,
        "icon" => "⚙️",
        "title" => "Software Development",
        "category" => "Technology",
        "description" => "Custom software and business applications that simplify daily operations and improve productivity.",
        "features" => [
            "Business management systems",
            "Database-driven applications",
            "Workflow automation solutions"
        ],
        "duration" => "Project-based",
        "popular" => false
    ],
    [
        "id" => 3,
        "icon" => "🛠️",
        "title" => "IT Support & Maintenance",
        "category" => "Technology",
        "description" => "Practical IT assistance to help businesses maintain reliable systems and resolve technical issues.",
        "features" => [
            "Troubleshooting and technical support",
            "System maintenance",
            "IT consultation"
        ],
        "duration" => "Hourly or scheduled",
        "popular" => false
    ],
    [
        "id" => 4,
        "icon" => "📈",
        "title" => "Digital Marketing",
        "category" => "Marketing",
        "description" => "Digital marketing services that help businesses reach their target audiences and build their online presence.",
        "features" => [
            "Search engine optimization",
            "Social media marketing",
            "Digital campaign planning"
        ],
        "duration" => "Monthly or project-based",
        "popular" => true
    ],
    [
        "id" => 5,
        "icon" => "🎨",
        "title" => "Graphic & Brand Design",
        "category" => "Marketing",
        "description" => "Creative design services to help businesses communicate their identity consistently and professionally.",
        "features" => [
            "Logo and brand identity",
            "Marketing materials",
            "Social media graphics"
        ],
        "duration" => "Project-based",
        "popular" => false
    ],
    [
        "id" => 6,
        "icon" => "🧹",
        "title" => "Commercial Cleaning",
        "category" => "Property",
        "description" => "Organized cleaning services for offices, workplaces and commercial properties.",
        "features" => [
            "Office cleaning",
            "Scheduled maintenance cleaning",
            "Commercial space cleaning"
        ],
        "duration" => "One-time or recurring",
        "popular" => false
    ],
    [
        "id" => 7,
        "icon" => "🔧",
        "title" => "Repair & Maintenance",
        "category" => "Maintenance",
        "description" => "Repair and maintenance assistance designed to keep business equipment and facilities operating effectively.",
        "features" => [
            "General repair requests",
            "Preventive maintenance",
            "Equipment inspection"
        ],
        "duration" => "Appointment-based",
        "popular" => false
    ],
    [
        "id" => 8,
        "icon" => "⚡",
        "title" => "Electrical Services",
        "category" => "Technical",
        "description" => "Electrical installation, inspection and maintenance services provided according to project requirements.",
        "features" => [
            "Electrical installation",
            "Fault inspection",
            "Maintenance requests"
        ],
        "duration" => "Appointment-based",
        "popular" => false
    ],
    [
        "id" => 9,
        "icon" => "🚰",
        "title" => "Plumbing Services",
        "category" => "Maintenance",
        "description" => "Plumbing support for installation, maintenance and common water-system problems.",
        "features" => [
            "Leak and pipe repairs",
            "Fixture installation",
            "Plumbing maintenance"
        ],
        "duration" => "Appointment-based",
        "popular" => false
    ],
    [
        "id" => 10,
        "icon" => "📊",
        "title" => "Business Consulting",
        "category" => "Consulting",
        "description" => "Professional guidance to help businesses identify operational challenges and plan practical improvements.",
        "features" => [
            "Business process reviews",
            "Operational improvement",
            "Project planning consultations"
        ],
        "duration" => "Hourly or project-based",
        "popular" => true
    ],
    [
        "id" => 11,
        "icon" => "🏗️",
        "title" => "Construction & Renovation",
        "category" => "Property",
        "description" => "Project consultation for construction, renovation and property improvement requirements.",
        "features" => [
            "Project consultation",
            "Renovation planning",
            "Maintenance coordination"
        ],
        "duration" => "Project-based",
        "popular" => false
    ],
    [
        "id" => 12,
        "icon" => "🔒",
        "title" => "Security & Systems Consultation",
        "category" => "Consulting",
        "description" => "Consultation to help businesses assess operational security requirements and plan suitable system improvements.",
        "features" => [
            "Requirements assessment",
            "Systems planning",
            "Technical consultation"
        ],
        "duration" => "Consultation-based",
        "popular" => false
    ]
];

$categories = [
    "All Services",
    "Technology",
    "Marketing",
    "Property",
    "Maintenance",
    "Technical",
    "Consulting"
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
          content="Explore ProServe professional IT, website development, cleaning, electrical, plumbing, marketing, maintenance and consulting services.">

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
            --radius: 17px;
            --shadow: 0 12px 35px rgba(16, 43, 78, 0.07);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }

        body {
            font-family: "Trebuchet MS", Arial, sans-serif;
            background: var(--white);
            color: var(--text);
            line-height: 1.7;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input,
        select {
            font: inherit;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible {
            outline: 3px solid #93c5fd;
            outline-offset: 3px;
        }

        .container {
            width: min(1160px, calc(100% - 48px));
            margin: 0 auto;
        }

        .section {
            padding: 65px 0 85px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            min-height: 45px;
            padding: 11px 17px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            color: white;
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            color: var(--navy);
            background: white;
            border-color: #cbd7e6;
        }

        .btn-outline:hover {
            color: var(--primary);
            border-color: var(--primary);
        }

        /* Navigation */

        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.97);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
        }

        .nav-inner {
            min-height: 76px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            font-size: 24px;
            font-weight: 900;
        }

        .brand-name {
            display: block;
            color: var(--navy);
            font-size: 22px;
            font-weight: 900;
            line-height: 1.2;
        }

        .brand-caption {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.4px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 23px;
        }

        .nav-links > a:not(.btn) {
            color: #506177;
            font-size: 12px;
            font-weight: 700;
        }

        .nav-links > a:not(.btn):hover,
        .nav-links > a.active {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            gap: 9px;
        }

        .nav-actions .btn {
            min-height: 40px;
            padding: 9px 13px;
        }

        .menu-toggle {
            display: none;
            width: 42px;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: white;
            color: var(--navy);
            font-size: 22px;
            cursor: pointer;
        }

        /* Page heading */

        .page-hero {
            position: relative;
            overflow: hidden;
            padding: 70px 0 75px;
            background:
                radial-gradient(
                    circle at 90% 15%,
                    rgba(96, 165, 250, 0.2),
                    transparent 28%
                ),
                linear-gradient(125deg, #eff6ff, #ffffff 60%, #edf4ff);
        }

        .hero-content {
            max-width: 790px;
            margin: 0 auto;
            text-align: center;
        }

        .eyebrow {
            display: inline-block;
            padding: 7px 12px;
            border: 1px solid #c9dcfb;
            border-radius: 25px;
            background: white;
            color: var(--primary-dark);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .hero-content h1 {
            margin-top: 19px;
            color: var(--navy);
            font-size: clamp(36px, 5vw, 53px);
            line-height: 1.2;
            letter-spacing: -1.7px;
        }

        .hero-content h1 span {
            color: var(--primary);
        }

        .hero-content > p {
            max-width: 690px;
            margin: 17px auto 25px;
            color: var(--muted);
            font-size: 15px;
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 11px;
        }

        /* Search */

        .search-section {
            position: relative;
            z-index: 2;
            margin-top: -27px;
        }

        .search-panel {
            display: grid;
            grid-template-columns: 1.6fr 1fr auto;
            align-items: end;
            gap: 15px;
            padding: 22px;
            border: 1px solid var(--border);
            border-radius: 17px;
            background: white;
            box-shadow: var(--shadow);
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: #71839a;
            font-size: 16px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            min-height: 45px;
            padding: 10px 13px;
            border: 1px solid #d8e2ee;
            border-radius: 9px;
            background: white;
            color: var(--text);
            font-size: 12px;
        }

        .search-input {
            padding-left: 39px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-button {
            min-width: 120px;
        }

        /* Catalogue heading and filters */

        .catalogue-top {
            display: flex;
            justify-content: space-between;
            align-items: end;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 26px;
        }

        .catalogue-heading h2 {
            color: var(--navy);
            font-size: 28px;
            line-height: 1.35;
        }

        .catalogue-heading p {
            margin-top: 6px;
            color: var(--muted);
            font-size: 13px;
        }

        .result-count {
            padding: 7px 11px;
            border-radius: 8px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 800;
        }

        .filter-list {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-bottom: 29px;
        }

        .filter-btn {
            padding: 9px 15px;
            border: 1px solid var(--border);
            border-radius: 25px;
            background: white;
            color: #51647b;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s;
        }

        .filter-btn:hover {
            border-color: #93c5fd;
            color: var(--primary);
        }

        .filter-btn.active {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
        }

        /* Service grid */

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .service-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            padding: 25px;
            border: 1px solid var(--border);
            border-radius: 17px;
            background: white;
            box-shadow: 0 6px 20px rgba(16, 43, 78, 0.025);
            transition: transform 0.25s,
                        box-shadow 0.25s,
                        border-color 0.25s;
        }

        .service-card:hover {
            transform: translateY(-5px);
            border-color: #bdd4fa;
            box-shadow: var(--shadow);
        }

        .service-card[hidden] {
            display: none !important;
        }

        .card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .service-icon {
            display: grid;
            place-items: center;
            width: 55px;
            height: 55px;
            border-radius: 14px;
            background: #eff6ff;
            font-size: 27px;
        }

        .popular-badge {
            padding: 5px 8px;
            border-radius: 20px;
            background: #fff7ed;
            color: #c2410c;
            font-size: 9px;
            font-weight: 900;
        }

        .category-label {
            margin-top: 19px;
            color: var(--primary);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .service-card h3 {
            margin: 7px 0 10px;
            color: var(--navy);
            font-size: 18px;
            line-height: 1.45;
        }

        .service-description {
            flex: 1;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.85;
        }

        .service-features {
            display: grid;
            gap: 8px;
            margin: 19px 0;
            padding-top: 16px;
            border-top: 1px solid #edf1f7;
            list-style: none;
        }

        .service-features li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            color: #4b5e73;
            font-size: 11px;
        }

        .check {
            color: #16a34a;
            font-weight: 900;
        }

        .service-duration {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            color: var(--muted);
            font-size: 10px;
        }

        .card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
            margin-top: auto;
        }

        .card-actions .btn {
            padding: 10px 8px;
            font-size: 10px;
        }

        .no-results {
            display: none;
            padding: 45px 20px;
            border: 1px dashed #cbd7e6;
            border-radius: 15px;
            background: #fafcff;
            text-align: center;
        }

        .no-results.visible {
            display: block;
        }

        .no-results h3 {
            color: var(--navy);
            font-size: 20px;
        }

        .no-results p {
            margin: 8px 0 18px;
            color: var(--muted);
            font-size: 13px;
        }

        /* How it works */

        .how-section {
            background: var(--background);
        }

        .section-heading {
            max-width: 700px;
            margin: 0 auto 40px;
            text-align: center;
        }

        .section-label {
            color: var(--primary);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .section-heading h2 {
            margin-top: 10px;
            color: var(--navy);
            font-size: clamp(27px, 4vw, 38px);
            line-height: 1.3;
        }

        .section-heading p {
            margin-top: 12px;
            color: var(--muted);
            font-size: 13px;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .step-card {
            padding: 24px 20px;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
        }

        .step-number {
            display: grid;
            place-items: center;
            width: 39px;
            height: 39px;
            margin-bottom: 16px;
            border-radius: 11px;
            background: #dbeafe;
            color: var(--primary-dark);
            font-size: 13px;
            font-weight: 900;
        }

        .step-card h3 {
            margin-bottom: 8px;
            color: var(--navy);
            font-size: 15px;
        }

        .step-card p {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.85;
        }

        /* CTA */

        .cta-section {
            padding: 65px 0;
            color: white;
            background: linear-gradient(120deg, #123b72, #2563eb);
        }

        .cta-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
        }

        .cta-inner h2 {
            max-width: 650px;
            font-size: clamp(26px, 4vw, 36px);
            line-height: 1.3;
        }

        .cta-inner p {
            max-width: 620px;
            margin-top: 12px;
            color: #dbeafe;
            font-size: 13px;
        }

        .cta-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            flex-shrink: 0;
        }

        .cta-actions .btn-outline {
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
            background: transparent;
        }

        .cta-actions .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Footer */

        .footer {
            padding: 35px 0;
            background: var(--navy-dark);
            color: #cbd5e1;
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 17px;
        }

        .footer p,
        .footer a {
            font-size: 11px;
        }

        .footer strong {
            color: white;
        }

        .footer a {
            color: #bfdbfe;
        }

        .footer a:hover {
            color: white;
        }

        @media (max-width: 1000px) {
            .nav-links {
                gap: 14px;
            }

            .services-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .steps-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .cta-inner {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(100% - 36px, 620px);
            }

            .nav-inner {
                min-height: 70px;
            }

            .menu-toggle {
                display: grid;
                place-items: center;
            }

            .nav-links {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                display: none;
                align-items: stretch;
                flex-direction: column;
                gap: 0;
                padding: 12px 22px 20px;
                border-bottom: 1px solid var(--border);
                background: white;
                box-shadow: 0 15px 25px rgba(16, 43, 78, 0.08);
            }

            .nav-links.open {
                display: flex;
            }

            .nav-links > a:not(.btn) {
                display: block;
                padding: 11px 0;
                font-size: 13px;
            }

            .nav-actions {
                padding-top: 12px;
                border-top: 1px solid var(--border);
            }

            .nav-actions .btn {
                flex: 1;
            }

            .page-hero {
                padding: 55px 0 65px;
            }

            .search-panel {
                grid-template-columns: 1fr;
                gap: 13px;
                padding: 18px;
            }

            .search-button {
                width: 100%;
            }

            .catalogue-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .cta-actions {
                width: 100%;
                flex-direction: column;
            }

            .cta-actions .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .services-grid,
            .steps-grid {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 55px 0 65px;
            }

            .service-card {
                padding: 22px;
            }

            .filter-list {
                gap: 7px;
            }

            .filter-btn {
                padding: 8px 11px;
                font-size: 10px;
            }

            .footer-inner {
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
            }
        }
    </style>
</head>

<body>

<!-- =========================================================
     NAVIGATION
========================================================= -->

<header class="navbar">

    <div class="container nav-inner">

        <a href="index.php" class="brand">

            <span class="brand-icon">&#8644;</span>

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

            <a href="services.php" class="active" aria-current="page">
                Services
            </a>

            <a href="index.php#how-it-works">How It Works</a>

            <a href="contact.php">Contact</a>

            <div class="nav-actions">

                <a href="login.php" class="btn btn-outline">
                    Sign In
                </a>

                <a href="register.php" class="btn btn-primary">
                    Get Started &rarr;
                </a>

            </div>

        </nav>

    </div>

</header>


<main>

    <!-- =====================================================
         PAGE HERO
    ====================================================== -->

    <section class="page-hero">

        <div class="container">

            <div class="hero-content">

                <span class="eyebrow">
                    OUR SERVICE CATALOGUE
                </span>

                <h1>
                    Find the Right Service
                    <span>for Your Business.</span>
                </h1>

                <p>
                    Explore our service categories, discover solutions
                    for your requirements, and take the next step by
                    requesting a quotation or arranging a consultation.
                </p>

                <div class="hero-actions">

                    <a href="#catalogue" class="btn btn-primary">
                        Browse Services &darr;
                    </a>

                    <a href="book_appointment.php"
                       class="btn btn-outline">
                        &#128197; Book Consultation
                    </a>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         SEARCH AND CATEGORY SELECTOR
    ====================================================== -->

    <section class="search-section" aria-label="Search services">

        <div class="container">

            <form class="search-panel" id="serviceSearchForm">

                <div class="form-group">

                    <label for="serviceSearch">
                        Search Services
                    </label>

                    <div class="input-wrap">

                        <span class="input-icon">&#128269;</span>

                        <input
                            type="search"
                            id="serviceSearch"
                            class="form-control search-input"
                            placeholder="e.g. website development, cleaning..."
                            autocomplete="off"
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="categorySelect">
                        Service Category
                    </label>

                    <select
                        id="categorySelect"
                        class="form-control"
                    >

                        <?php foreach ($categories as $category): ?>

                            <option value="<?php
                                echo htmlspecialchars(
                                    $category === "All Services"
                                        ? "all"
                                        : $category
                                );
                            ?>">
                                <?php echo htmlspecialchars($category); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <button type="submit"
                        class="btn btn-primary search-button">
                    Search Services
                </button>

            </form>

        </div>

    </section>


    <!-- =====================================================
         SERVICE CATALOGUE
    ====================================================== -->

    <section class="section" id="catalogue">

        <div class="container">

            <div class="catalogue-top">

                <div class="catalogue-heading">

                    <h2>Explore Our Services</h2>

                    <p>
                        Select a service to learn more or submit your
                        requirements.
                    </p>

                </div>

                <div class="result-count"
                     id="resultCount"
                     aria-live="polite">
                    <?php echo count($services); ?> services available
                </div>

            </div>


            <!-- Category filter buttons -->

            <div class="filter-list"
                 aria-label="Filter services by category">

                <?php foreach ($categories as $index => $category): ?>

                    <button
                        type="button"
                        class="filter-btn <?php echo $index === 0 ? 'active' : ''; ?>"
                        data-category="<?php
                            echo htmlspecialchars(
                                $category === "All Services"
                                    ? "all"
                                    : $category
                            );
                        ?>"
                        aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                    >
                        <?php echo htmlspecialchars($category); ?>
                    </button>

                <?php endforeach; ?>

            </div>


            <!-- Service cards -->

            <div class="services-grid" id="servicesGrid">

                <?php foreach ($services as $service): ?>

                    <article
                        class="service-card"
                        data-category="<?php
                            echo htmlspecialchars($service["category"]);
                        ?>"
                        data-search="<?php
                            echo htmlspecialchars(
                                strtolower(
                                    $service["title"] . " " .
                                    $service["category"] . " " .
                                    $service["description"] . " " .
                                    implode(" ", $service["features"])
                                ),
                                ENT_QUOTES,
                                "UTF-8"
                            );
                        ?>"
                    >

                        <div class="card-top">

                            <div class="service-icon">
                                <?php
                                echo htmlspecialchars($service["icon"]);
                                ?>
                            </div>

                            <?php if ($service["popular"]): ?>

                                <span class="popular-badge">
                                    POPULAR
                                </span>

                            <?php endif; ?>

                        </div>


                        <div class="category-label">
                            <?php
                            echo htmlspecialchars($service["category"]);
                            ?>
                        </div>


                        <h3>
                            <?php
                            echo htmlspecialchars($service["title"]);
                            ?>
                        </h3>


                        <p class="service-description">
                            <?php
                            echo htmlspecialchars($service["description"]);
                            ?>
                        </p>


                        <ul class="service-features">

                            <?php foreach ($service["features"] as $feature): ?>

                                <li>
                                    <span class="check">&#10003;</span>

                                    <span>
                                        <?php
                                        echo htmlspecialchars($feature);
                                        ?>
                                    </span>
                                </li>

                            <?php endforeach; ?>

                        </ul>


                        <div class="service-duration">

                            <span>&#9201;</span>

                            <span>
                                <?php
                                echo htmlspecialchars($service["duration"]);
                                ?>
                            </span>

                        </div>


                        <div class="card-actions">

                            <a
                                href="request_quote.php?service=<?php
                                    echo urlencode($service["title"]);
                                ?>"
                                class="btn btn-primary"
                            >
                                Request Quote
                            </a>

                            <a
                                href="book_appointment.php?service=<?php
                                    echo urlencode($service["title"]);
                                ?>"
                                class="btn btn-outline"
                            >
                                Book Service
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>


            <!-- Empty search state -->

            <div class="no-results" id="noResults" aria-live="polite">

                <h3>No matching services found</h3>

                <p>
                    Try a different search term or choose another category.
                </p>

                <button type="button"
                        class="btn btn-primary"
                        id="resetFilters">
                    Show All Services
                </button>

            </div>

        </div>

    </section>


    <!-- =====================================================
         HOW IT WORKS
    ====================================================== -->

    <section class="section how-section" id="how-it-works">

        <div class="container">

            <div class="section-heading">

                <span class="section-label">
                    Easy Service Requests
                </span>

                <h2>Getting Started Is Simple</h2>

                <p>
                    Follow these four steps to move from exploring
                    services to arranging your next project.
                </p>

            </div>


            <div class="steps-grid">

                <article class="step-card">

                    <div class="step-number">01</div>

                    <h3>Choose a Service</h3>

                    <p>
                        Browse the catalogue and select a service
                        that matches your needs.
                    </p>

                </article>


                <article class="step-card">

                    <div class="step-number">02</div>

                    <h3>Submit Requirements</h3>

                    <p>
                        Describe your project, or select a preferred
                        date and time for a consultation.
                    </p>

                </article>


                <article class="step-card">

                    <div class="step-number">03</div>

                    <h3>Review the Proposal</h3>

                    <p>
                        Review the proposed service details and
                        quotation when available.
                    </p>

                </article>


                <article class="step-card">

                    <div class="step-number">04</div>

                    <h3>Track Your Service</h3>

                    <p>
                        Use the customer portal to review appointment
                        status, quotations and invoices.
                    </p>

                </article>

            </div>

        </div>

    </section>


    <!-- =====================================================
         CALL TO ACTION
    ====================================================== -->

    <section class="cta-section">

        <div class="container cta-inner">

            <div>

                <h2>
                    Not Sure Which Service You Need?
                </h2>

                <p>
                    Tell us about your requirements and arrange a
                    consultation to discuss the right approach for
                    your project.
                </p>

            </div>


            <div class="cta-actions">

                <a href="book_appointment.php"
                   class="btn"
                   style="background:white;color:#1d4ed8;">
                    Book a Consultation &rarr;
                </a>

                <a href="contact.php"
                   class="btn btn-outline">
                    Contact Us
                </a>

            </div>

        </div>

    </section>

</main>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    <div class="container footer-inner">

        <p>
            &copy; <?php echo date("Y"); ?>
            <strong>ProServe</strong>.
            Portfolio demonstration by
            <strong>Codvanta</strong>.
        </p>

        <p>
            <a href="index.php">&larr; ProServe Home</a>
            &nbsp; | &nbsp;
            <a href="../index.php">Codvanta Portfolio</a>
        </p>

    </div>

</footer>


<!-- =========================================================
     JAVASCRIPT: SEARCH AND FILTERS
========================================================= -->

<script>
    const searchForm = document.getElementById("serviceSearchForm");
    const searchInput = document.getElementById("serviceSearch");
    const categorySelect = document.getElementById("categorySelect");
    const filterButtons = document.querySelectorAll(".filter-btn");
    const serviceCards = document.querySelectorAll(".service-card");
    const resultCount = document.getElementById("resultCount");
    const noResults = document.getElementById("noResults");
    const resetFilters = document.getElementById("resetFilters");

    const menuToggle = document.getElementById("menuToggle");
    const mainNavigation = document.getElementById("mainNavigation");

    let activeCategory = "all";

    function filterServices() {
        const searchTerm = searchInput.value
            .trim()
            .toLowerCase();

        const selectedCategory = categorySelect.value;

        activeCategory = selectedCategory;

        let visibleCount = 0;

        serviceCards.forEach(function (card) {
            const cardCategory = card.dataset.category;
            const searchableText = card.dataset.search;

            const matchesCategory =
                selectedCategory === "all" ||
                cardCategory === selectedCategory;

            const matchesSearch =
                searchableText.includes(searchTerm);

            const isVisible =
                matchesCategory && matchesSearch;

            card.hidden = !isVisible;

            if (isVisible) {
                visibleCount++;
            }
        });

        resultCount.textContent =
            visibleCount +
            (visibleCount === 1
                ? " service found"
                : " services found");

        noResults.classList.toggle(
            "visible",
            visibleCount === 0
        );

        filterButtons.forEach(function (button) {
            const isActive =
                button.dataset.category === selectedCategory;

            button.classList.toggle("active", isActive);

            button.setAttribute(
                "aria-pressed",
                String(isActive)
            );
        });
    }

    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();
        filterServices();
    });

    searchInput.addEventListener("input", filterServices);

    categorySelect.addEventListener("change", filterServices);

    filterButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            categorySelect.value = button.dataset.category;
            filterServices();
        });
    });

    resetFilters.addEventListener("click", function () {
        searchInput.value = "";
        categorySelect.value = "all";
        filterServices();
    });

    menuToggle.addEventListener("click", function () {
        const isOpen = mainNavigation.classList.toggle("open");

        menuToggle.setAttribute(
            "aria-expanded",
            String(isOpen)
        );

        menuToggle.setAttribute(
            "aria-label",
            isOpen ? "Close navigation menu" : "Open navigation menu"
        );

        menuToggle.innerHTML = isOpen ? "&times;" : "&#9776;";
    });

    mainNavigation.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            mainNavigation.classList.remove("open");
            menuToggle.setAttribute("aria-expanded", "false");
            menuToggle.setAttribute("aria-label", "Open navigation menu");
            menuToggle.innerHTML = "&#9776;";
        });
    });

    // Apply the initial state.
    filterServices();
</script>

</body>
</html>
