<?php
// Azure Bay Resort - Demonstration Selection Page

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Azure Bay Resort | Choose Your Experience</title>

    <!-- Local Bootstrap 5 CSS -->
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">

    <!-- Google Fonts are not required; the page works offline -->
    <style>
        :root {
            --ocean: #123f50;
            --ocean-dark: #0b2936;
            --sea: #1c7580;
            --sand: #f3e8d5;
            --gold: #c7a66a;
            --text: #263943;
            --muted: #71818a;
            --surface: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Trebuchet MS", Arial, sans-serif;
            color: var(--text);
            background: #f6f8f7;
        }

        a {
            text-decoration: none;
        }

        /* Navigation */
        .navbar {
            background: rgba(11, 41, 54, 0.97);
            padding: 17px 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            font-size: 21px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .brand:hover {
            color: var(--sand);
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--gold);
            border-radius: 50%;
            color: var(--gold);
            font-size: 23px;
        }

        .nav-label {
            color: #d6e2e4;
            font-size: 13px;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        /* Hero section */
        .hero {
            position: relative;
            min-height: 450px;
            display: flex;
            align-items: center;
            overflow: hidden;
            color: white;
            background:
                linear-gradient(
                    90deg,
                    rgba(7, 34, 45, 0.90) 0%,
                    rgba(7, 34, 45, 0.67) 53%,
                    rgba(7, 34, 45, 0.22) 100%
                ),
                url("assets/images/resort-hero.jpg")
                center 48% / cover no-repeat,
                #164e60;
        }

        .hero-content {
            padding-top: 72px;
            padding-bottom: 76px;
            max-width: 780px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #e4c991;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            display: inline-block;
            width: 35px;
            height: 1px;
            background: var(--gold);
        }

        .hero h1 {
            margin: 0 0 22px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(38px, 5.2vw, 65px);
            font-weight: 400;
            line-height: 1.15;
        }

        .hero h1 span {
            color: #e4c991;
            font-style: italic;
        }

        .hero p {
            max-width: 590px;
            color: #e3ebed;
            font-size: 16px;
            line-height: 1.9;
        }

        /* Intro */
        .section-heading {
            margin-bottom: 10px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(27px, 3vw, 36px);
        }

        .section-description {
            max-width: 680px;
            margin: 0 auto;
            color: var(--muted);
            line-height: 1.8;
        }

        .demo-section {
            padding: 72px 0 82px;
        }

        /* Role cards */
        .role-card {
            height: 100%;
            overflow: hidden;
            background: var(--surface);
            border: 1px solid #e4e9e7;
            border-radius: 12px;
            box-shadow: 0 8px 28px rgba(21, 57, 64, 0.055);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .role-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 38px rgba(21, 57, 64, 0.12);
        }

        .role-image {
            position: relative;
            height: 235px;
            overflow: hidden;
            background: linear-gradient(135deg, #164e60, #6ba9a5);
        }

        .role-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s ease;
        }

        .role-card:hover .role-image img {
            transform: scale(1.05);
        }

        .role-number {
            position: absolute;
            top: 16px;
            left: 16px;
            padding: 7px 12px;
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 4px;
            background: rgba(11, 41, 54, 0.78);
            color: white;
            font-size: 11px;
            letter-spacing: 1.5px;
        }

        .role-content {
            padding: 28px;
        }

        .role-tag {
            display: inline-block;
            margin-bottom: 13px;
            padding: 6px 10px;
            border-radius: 4px;
            background: #e5f1ef;
            color: #226c6c;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .admin-tag {
            background: #f5ecd9;
            color: #88662c;
        }

        .role-content h3 {
            margin-bottom: 12px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 27px;
        }

        .role-content p {
            min-height: 75px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .feature-list {
            min-height: 145px;
            margin: 21px 0 25px;
            padding: 0;
            list-style: none;
        }

        .feature-list li {
            display: flex;
            gap: 10px;
            margin-bottom: 13px;
            color: #40535c;
            font-size: 13px;
            line-height: 1.5;
        }

        .feature-check {
            color: var(--sea);
            font-weight: bold;
        }

        .admin-check {
            color: #b28b45;
        }

        .btn-demo {
            display: flex;
            width: 100%;
            min-height: 49px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 1px solid var(--ocean);
            border-radius: 5px;
            background: var(--ocean);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.4px;
            transition: background 0.2s ease;
        }

        .btn-demo:hover {
            background: var(--sea);
            border-color: var(--sea);
            color: white;
        }

        .btn-admin {
            border-color: var(--gold);
            background: var(--gold);
            color: #172f38;
        }

        .btn-admin:hover {
            border-color: #b38d4d;
            background: #b38d4d;
            color: #fff;
        }

        /* Features strip */
        .features-strip {
            padding: 30px 0;
            background: #eaf1ef;
            border-top: 1px solid #dce6e2;
            border-bottom: 1px solid #dce6e2;
        }

        .strip-item {
            padding: 8px 15px;
            text-align: center;
        }

        .strip-icon {
            margin-bottom: 9px;
            color: var(--sea);
            font-size: 25px;
        }

        .strip-item h6 {
            margin: 0 0 5px;
            color: var(--ocean);
            font-size: 14px;
            font-weight: 700;
        }

        .strip-item p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
        }

        /* Demo notice */
        .demo-notice {
            margin-top: 32px;
            padding: 16px 19px;
            border-left: 3px solid var(--gold);
            border-radius: 4px;
            background: #fffaf0;
            color: #685b43;
            font-size: 13px;
            line-height: 1.8;
        }

        /* Footer */
        footer {
            padding: 29px 0;
            background: var(--ocean-dark);
            color: #cfdbde;
        }

        .footer-brand {
            color: #fff;
            font-size: 17px;
            font-weight: 700;
        }

        .footer-note {
            margin-top: 7px;
            color: #aebfc4;
            font-size: 12px;
        }

        .footer-credit {
            color: #e0c38b;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        @media (max-width: 767px) {
            .hero {
                min-height: 400px;
                background-position: center;
            }

            .hero-content {
                padding-top: 60px;
                padding-bottom: 60px;
            }

            .demo-section {
                padding: 52px 0;
            }

            .role-image {
                height: 220px;
            }

            .role-content p,
            .feature-list {
                min-height: auto;
            }

            .nav-label {
                display: none;
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
                align-items-center">

        <a href="azurebay_demo.php" class="brand">
            <span class="brand-icon" aria-hidden="true">A</span>
            <span>
                AZURE BAY
                <small class="d-block"
                       style="font-size:10px; font-weight:400;
                              letter-spacing:3px;">
                    RESORT
                </small>
            </span>
        </a>

        <div class="nav-label">
            Luxury Beachfront Resort
        </div>

    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <div class="hero-content">

            <div class="eyebrow">
                Discover Azure Bay
            </div>

            <h1>
                Your Escape.<br>
                Your <span>Azure</span> Paradise.
            </h1>

            <p>
                Experience a luxury beachfront resort booking
                platform designed to bring exceptional stays,
                effortless reservations, and intelligent resort
                management together in one place.
            </p>

        </div>
    </div>
</section>

<!-- Demonstration selection -->
<section class="demo-section">
    <div class="container">

        <div class="text-center mb-5">
            <div class="eyebrow justify-content-center"
                 style="color:#9b7b43;">
                Interactive Demonstration
            </div>

            <h2 class="section-heading">
                Choose Your Experience
            </h2>

            <p class="section-description">
                Explore Azure Bay Resort from the perspective
                of a guest making a reservation or an
                administrator managing resort operations.
                Select a portal below to begin.
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            <!-- Customer portal -->
            <div class="col-lg-6 col-md-6">
                <div class="role-card">

                    <div class="role-image">
                        <img
                            src="assets/images/resort-customer.jpg"
                            alt="Luxury beachfront resort with an
                                 inviting swimming pool"
                            loading="lazy"
                            onerror="this.style.display='none';"
                        >

                        <div class="role-number">
                            EXPERIENCE 01
                        </div>
                    </div>

                    <div class="role-content">

                        <span class="role-tag">
                            Guest Experience
                        </span>

                        <h3>Customer Portal</h3>

                        <p>
                            Discover beautiful accommodation,
                            explore room photos, check your
                            preferred dates, and experience
                            a convenient resort booking journey.
                        </p>

                        <ul class="feature-list">
                            <li>
                                <span class="feature-check">✓</span>
                                Browse rooms, photos, and prices
                            </li>
                            <li>
                                <span class="feature-check">✓</span>
                                Select dates and number of guests
                            </li>
                            <li>
                                <span class="feature-check">✓</span>
                                Make reservations and demo payments
                            </li>
                            <li>
                                <span class="feature-check">✓</span>
                                Manage bookings and leave reviews
                            </li>
                        </ul>

                        <a href="./projects/azurebay/index.php" class="btn-demo">
                            Explore Customer Portal
                            <span aria-hidden="true">→</span>
                        </a>

                    </div>
                </div>
            </div>

            <!-- Administrator portal -->
            <div class="col-lg-6 col-md-6">
                <div class="role-card">

                    <div class="role-image">
                        <img
                            src="assets/images/resort-admin.jpg"
                            alt="Elegant resort reception and
                                 guest services area"
                            loading="lazy"
                            onerror="this.style.display='none';"
                        >

                        <div class="role-number">
                            EXPERIENCE 02
                        </div>
                    </div>

                    <div class="role-content">

                        <span class="role-tag admin-tag">
                            Resort Operations
                        </span>

                        <h3>Administrator Portal</h3>

                        <p>
                            Explore a centralized management
                            dashboard for room inventory,
                            guest reservations, payments,
                            pricing, and resort performance.
                        </p>

                        <ul class="feature-list">
                            <li>
                                <span class="feature-check admin-check">
                                    ✓
                                </span>
                                Add rooms and manage room prices
                            </li>
                            <li>
                                <span class="feature-check admin-check">
                                    ✓
                                </span>
                                Manage reservations and guests
                            </li>
                            <li>
                                <span class="feature-check admin-check">
                                    ✓
                                </span>
                                Manage payments, discounts,
                                and availability
                            </li>
                            <li>
                                <span class="feature-check admin-check">
                                    ✓
                                </span>
                                View revenue and occupancy reports
                            </li>
                        </ul>

                        <a href="./projects/azurebay/admin/index.php" class="btn-demo btn-admin">
                            Enter Admin Dashboard
                            <span aria-hidden="true">→</span>
                        </a>

                    </div>
                </div>
            </div>

        </div>

        <!-- Demo notice -->
        <div class="demo-notice">
            <strong>Demonstration Notice:</strong>
            This platform is designed to showcase the
            features and user experience of a resort booking
            system. Booking records, payments, and management
            actions may use demonstration data. Do not enter
            real payment details or sensitive personal
            information.
        </div>

    </div>
</section>

<!-- Platform highlights -->
<section class="features-strip">
    <div class="container">
        <div class="row g-4">

            <div class="col-6 col-lg-3">
                <div class="strip-item">
                    <div class="strip-icon" aria-hidden="true">
                        ◇
                    </div>
                    <h6>Luxury Accommodation</h6>
                    <p>Explore resort rooms and amenities</p>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="strip-item">
                    <div class="strip-icon" aria-hidden="true">
                        ◷
                    </div>
                    <h6>Easy Reservations</h6>
                    <p>Choose dates and manage bookings</p>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="strip-item">
                    <div class="strip-icon" aria-hidden="true">
                        ♧
                    </div>
                    <h6>Guest Management</h6>
                    <p>Organized resort operations</p>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="strip-item">
                    <div class="strip-icon" aria-hidden="true">
                        ↗
                    </div>
                    <h6>Business Insights</h6>
                    <p>Revenue and occupancy statistics</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center
                    gap-3">

            <div>
                <div class="footer-brand">
                    AZURE BAY RESORT
                </div>

                <div class="footer-note">
                    Luxury stays. Seamless experiences.
                </div>
            </div>

            <div class="footer-credit">
                A DEMONSTRATION PROJECT BY CODVANTA
            </div>

        </div>
    </div>
</footer>

<!-- Local Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


</body>
</html>