<?php
// service.php
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Services | Codvanta</title>

    <meta name="description"
          content="Codvanta provides custom web application development, business management systems, e-commerce development, database development, API integration and website maintenance.">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            background: #07111f;
            color: #ffffff;
            line-height: 1.7;
        }


        /* =================================
           NAVBAR
        ================================= */

        .navbar {
            padding: 18px 0;
            background: rgba(7, 17, 31, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .navbar-brand {
            font-size: 27px;
            font-weight: 800;
            color: #ffffff !important;
            letter-spacing: -1px;
        }

        .brand-dot {
            color: #4f9cff;
        }

        .navbar-nav .nav-link {
            color: #b9c5d6 !important;
            font-weight: 500;
            margin-left: 22px;
            transition: 0.3s;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #ffffff !important;
        }

        .contact-btn {
            padding: 9px 20px !important;
            border-radius: 8px;
            background: linear-gradient(135deg, #3478f6, #7256e8);
            color: #ffffff !important;
        }


        /* =================================
           HERO
        ================================= */

        .services-hero {

            min-height: 70vh;

            display: flex;
            align-items: center;

            position: relative;

            overflow: hidden;

            background:

                radial-gradient(
                    circle at 15% 30%,
                    rgba(54, 116, 246, 0.18),
                    transparent 30%
                ),
                url("./projects/images/service.png")
                center 55% / cover no-repeat,

                radial-gradient(
                    circle at 85% 70%,
                    rgba(114, 86, 232, 0.16),
                    transparent 30%
                ),

                #07111f;
        }

        .hero-content {
            padding: 120px 0 80px;
        }

        .small-label {

            display: inline-block;

            color: #62a4ff;

            font-size: 14px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 2px;

            margin-bottom: 18px;
        }

        .hero-title {

            font-size: clamp(42px, 6vw, 72px);

            line-height: 1.08;

            font-weight: 800;

            letter-spacing: -2.5px;

            max-width: 950px;
        }

        .hero-title span {

            background:
                linear-gradient(
                    90deg,
                    #55a3ff,
                    #8d6cff
                );

            -webkit-background-clip: text;

            -webkit-text-fill-color: transparent;
        }

        .hero-description {

            max-width: 760px;

            margin-top: 25px;

            color: #aebdce;

            font-size: 18px;
        }


        /* =================================
           COMMON
        ================================= */

        .section {
            padding: 100px 0;
        }

        .section-dark {
            background: #091525;
        }

        .section-title {

            font-size: 40px;

            font-weight: 800;

            letter-spacing: -1px;

            margin-bottom: 18px;
        }

        .section-description {

            color: #9eafc3;

            max-width: 720px;

            font-size: 17px;
        }


        /* =================================
           MAIN SERVICE CARDS
        ================================= */

        .service-card {

            height: 100%;

            padding: 38px;

            background:
                linear-gradient(
                    145deg,
                    #0d1c2f,
                    #091522
                );

            border:

                1px solid
                rgba(255,255,255,0.07);

            border-radius: 18px;

            transition: 0.35s;

            position: relative;

            overflow: hidden;
        }

        .service-card::before {

            content: "";

            position: absolute;

            width: 120px;

            height: 120px;

            top: -60px;

            right: -60px;

            background:
                rgba(67, 137, 245, 0.12);

            border-radius: 50%;
        }

        .service-card:hover {

            transform: translateY(-8px);

            border-color:
                rgba(82, 151, 255, 0.4);

            box-shadow:
                0 20px 50px
                rgba(0,0,0,0.25);
        }

        .service-number {

            font-size: 13px;

            color: #5da5ff;

            font-weight: 700;

            letter-spacing: 1px;

            margin-bottom: 18px;
        }

        .service-icon {

            width: 58px;

            height: 58px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 14px;

            background:
                rgba(66, 137, 246, 0.12);

            color: #5da5ff;

            font-size: 25px;

            margin-bottom: 25px;
        }

        .service-card h3 {

            font-size: 23px;

            font-weight: 700;

            margin-bottom: 15px;
        }

        .service-card > p {

            color: #96a9bd;

            font-size: 15px;

            margin-bottom: 25px;
        }


        /* =================================
           SERVICE FEATURES
        ================================= */

        .service-features {

            list-style: none;

            padding: 0;

            margin: 0;
        }

        .service-features li {

            color: #b9c6d5;

            font-size: 14px;

            margin-bottom: 9px;

            padding-left: 23px;

            position: relative;
        }

        .service-features li::before {

            content: "✓";

            position: absolute;

            left: 0;

            color: #58a1ff;

            font-weight: 700;
        }


        /* =================================
           FEATURED SERVICE
        ================================= */

        .featured-service {

            padding: 45px;

            border-radius: 22px;

            background:

                linear-gradient(
                    135deg,
                    rgba(42, 91, 165, 0.28),
                    rgba(71, 54, 126, 0.22)
                );

            border:
                1px solid
                rgba(91, 157, 255, 0.18);
        }

        .featured-service h2 {

            font-size: 32px;

            font-weight: 800;

            margin-bottom: 15px;
        }

        .featured-service p {

            color: #a4b5c8;

            max-width: 720px;
        }

        .feature-pill {

            display: inline-block;

            padding: 8px 14px;

            border-radius: 30px;

            background:
                rgba(255,255,255,0.05);

            border:
                1px solid
                rgba(255,255,255,0.08);

            color: #c4d0df;

            font-size: 13px;

            margin: 5px 4px 0 0;
        }


        /* =================================
           BUSINESS PROBLEMS
        ================================= */

        .problem-card {

            height: 100%;

            padding: 30px;

            border-radius: 16px;

            background: #0c1929;

            border:
                1px solid
                rgba(255,255,255,0.06);
        }

        .problem-card h4 {

            font-size: 19px;

            font-weight: 700;

            margin-bottom: 10px;
        }

        .problem-card p {

            color: #91a4ba;

            font-size: 14px;

            margin-bottom: 0;
        }


        /* =================================
           TECHNOLOGY STRIP
        ================================= */

        .technology-strip {

            display: flex;

            flex-wrap: wrap;

            gap: 10px;

            margin-top: 30px;
        }

        .technology {

            padding: 10px 17px;

            border-radius: 8px;

            background:
                rgba(255,255,255,0.035);

            border:
                1px solid
                rgba(255,255,255,0.08);

            color: #aebdce;

            font-size: 13px;
        }


        /* =================================
           CTA
        ================================= */

        .cta-section {

            padding: 100px 0;

            background:

                radial-gradient(
                    circle at center,
                    rgba(72, 118, 231, 0.18),
                    transparent 45%
                ),

                #07111f;
        }

        .cta-box {

            padding: 70px 30px;

            text-align: center;

            border-radius: 24px;

            border:
                1px solid
                rgba(91, 157, 255, 0.15);

            background:

                linear-gradient(
                    135deg,
                    rgba(35, 74, 133, 0.25),
                    rgba(71, 54, 126, 0.2)
                );
        }

        .cta-box h2 {

            font-size: 42px;

            font-weight: 800;

            margin-bottom: 15px;
        }

        .cta-box p {

            color: #a5b5c8;

            max-width: 650px;

            margin:
                0 auto 30px;
        }

        .cta-button {

            display: inline-block;

            padding: 13px 28px;

            border-radius: 8px;

            background:
                linear-gradient(
                    135deg,
                    #3478f6,
                    #7256e8
                );

            color: #ffffff;

            text-decoration: none;

            font-weight: 600;

            transition: 0.3s;
        }

        .cta-button:hover {

            color: #ffffff;

            transform: translateY(-2px);

            box-shadow:
                0 10px 30px
                rgba(52,120,246,0.25);
        }


        /* =================================
           FOOTER
        ================================= */

        footer {

            padding: 35px 0;

            background: #050d17;

            border-top:
                1px solid
                rgba(255,255,255,0.05);
        }

        .footer-brand {

            font-size: 22px;

            font-weight: 800;
        }

        .footer-text {

            color: #7f92a8;

            font-size: 14px;

            margin-bottom: 0;
        }

        .footer-links a {

            color: #8fa1b5;

            text-decoration: none;

            margin-left: 20px;

            font-size: 14px;
        }

        .footer-links a:hover {

            color: #ffffff;
        }


        /* =================================
           RESPONSIVE
        ================================= */

        @media (max-width: 991px) {

            .navbar-nav {

                padding-top: 15px;
            }

            .navbar-nav .nav-link {

                margin-left: 0;

                padding: 9px 0;
            }

            .hero-content {

                padding:
                    100px 0 70px;
            }

            .section {

                padding: 75px 0;
            }

            .section-title {

                font-size: 34px;
            }

            .featured-service {

                padding: 35px;
            }

            .cta-box h2 {

                font-size: 34px;
            }
        }


        @media (max-width: 576px) {

            .hero-title {

                font-size: 42px;
            }

            .hero-description {

                font-size: 16px;
            }

            .section-title {

                font-size: 30px;
            }

            .service-card {

                padding: 28px;
            }

            .featured-service {

                padding: 28px;
            }

            .cta-box {

                padding: 50px 20px;
            }

            .cta-box h2 {

                font-size: 29px;
            }

            .footer-links {

                margin-top: 15px;
            }

            .footer-links a {

                margin-left: 0;

                margin-right: 15px;
            }
        }

    </style>

</head>


<body>


<!-- =================================
     NAVBAR
================================= -->

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            <img src="./projects/images/Codvanta_logo.png" width="23px" height="35px">
            Codvanta<span class="brand-dot">.</span>

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


        <div
            class="collapse navbar-collapse"
            id="mainNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php">

                        Home

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="about.php">

                        About

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="services.php">

                        Services

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="projects.php">

                        Projects

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="#technologies">

                        Technologies

                    </a>

                </li>


                <li class="nav-item ms-lg-3">

                    <a
                        class="nav-link contact-btn"
                        href="contact.php">

                        Let's Talk

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- =================================
     HERO
================================= -->

<section class="services-hero">

    <div class="container">

        <div class="hero-content">

            <span class="small-label">

                What We Do

            </span>


            <h1 class="hero-title">

                Digital solutions built
                <span>around your business.</span>

            </h1>


            <p class="hero-description">

                From custom business applications to e-commerce platforms
                and API integrations, Codvanta develops practical software
                solutions designed around real business requirements.

            </p>

        </div>

    </div>

</section>



<!-- =================================
     SERVICES INTRO
================================= -->

<section class="section">

    <div class="container">

        <div class="row mb-5">

            <div class="col-lg-8">

                <span class="small-label">

                    Our Services

                </span>


                <h2 class="section-title">

                    What can we build for you?

                </h2>


                <p class="section-description">

                    Every business is different. We provide flexible
                    development services that can be used individually or
                    combined to create a complete digital solution.

                </p>

            </div>

        </div>



        <div class="row g-4">


            <!-- SERVICE 01 -->

            <div class="col-md-6 col-lg-4">

                <div class="service-card">

                    <div class="service-number">

                        SERVICE 01

                    </div>


                    <div class="service-icon">

                        &lt;/&gt;

                    </div>


                    <h3>

                        Custom Web Application Development

                    </h3>


                    <p>

                        We develop custom web applications based on the
                        specific workflows, requirements and objectives of
                        your business.

                    </p>


                    <ul class="service-features">

                        <li>
                            Custom business applications
                        </li>

                        <li>
                            User authentication & role management
                        </li>

                        <li>
                            Admin dashboards
                        </li>

                        <li>
                            Search and filtering systems
                        </li>

                        <li>
                            Online forms and workflows
                        </li>

                        <li>
                            Reports and analytics
                        </li>

                        <li>
                            Responsive interfaces
                        </li>

                    </ul>

                </div>

            </div>



            <!-- SERVICE 02 -->

            <div class="col-md-6 col-lg-4">

                <div class="service-card">

                    <div class="service-number">

                        SERVICE 02

                    </div>


                    <div class="service-icon">

                        ◫

                    </div>


                    <h3>

                        Business Management Systems

                    </h3>


                    <p>

                        Replace manual business processes with centralized
                        software that helps organizations manage their
                        daily operations efficiently.

                    </p>


                    <ul class="service-features">

                        <li>
                            Customer management
                        </li>

                        <li>
                            Employee management
                        </li>

                        <li>
                            Inventory management
                        </li>

                        <li>
                            Appointment management
                        </li>

                        <li>
                            Billing and payments
                        </li>

                        <li>
                            Reports and dashboards
                        </li>

                        <li>
                            Role-based access control
                        </li>

                    </ul>

                </div>

            </div>



            <!-- SERVICE 03 -->

            <div class="col-md-6 col-lg-4">

                <div class="service-card">

                    <div class="service-number">

                        SERVICE 03

                    </div>


                    <div class="service-icon">

                        🛒

                    </div>


                    <h3>

                        E-Commerce Development

                    </h3>


                    <p>

                        Build professional online stores that allow
                        businesses to present products, accept orders and
                        manage their online sales.

                    </p>


                    <ul class="service-features">

                        <li>
                            Product catalogues
                        </li>

                        <li>
                            Product search and filtering
                        </li>

                        <li>
                            Shopping cart
                        </li>

                        <li>
                            Customer accounts
                        </li>

                        <li>
                            Order management
                        </li>

                        <li>
                            Payment integration
                        </li>

                        <li>
                            E-commerce administration
                        </li>

                    </ul>

                </div>

            </div>



            <!-- SERVICE 04 -->

            <div class="col-md-6 col-lg-4">

                <div class="service-card">

                    <div class="service-number">

                        SERVICE 04

                    </div>


                    <div class="service-icon">

                        🗄

                    </div>


                    <h3>

                        Database Development

                    </h3>


                    <p>

                        Design structured and reliable databases that allow
                        business applications to store, manage and retrieve
                        information efficiently.

                    </p>


                    <ul class="service-features">

                        <li>
                            Database architecture
                        </li>

                        <li>
                            Table and relationship design
                        </li>

                        <li>
                            Data validation
                        </li>

                        <li>
                            Query optimization
                        </li>

                        <li>
                            Secure data access
                        </li>

                        <li>
                            Database integration
                        </li>

                        <li>
                            Data migration
                        </li>

                    </ul>

                </div>

            </div>



            <!-- SERVICE 05 -->

            <div class="col-md-6 col-lg-4">

                <div class="service-card">

                    <div class="service-number">

                        SERVICE 05

                    </div>


                    <div class="service-icon">

                        ⇄

                    </div>


                    <h3>

                        API Integration

                    </h3>


                    <p>

                        Connect your website or application with external
                        platforms and services through secure and reliable
                        APIs.

                    </p>


                    <ul class="service-features">

                        <li>
                            REST API integration
                        </li>

                        <li>
                            Third-party services
                        </li>

                        <li>
                            Payment gateways
                        </li>

                        <li>
                            External data integration
                        </li>

                        <li>
                            Authentication APIs
                        </li>

                        <li>
                            Application-to-application communication
                        </li>

                        <li>
                            Custom API development
                        </li>

                    </ul>

                </div>

            </div>



            <!-- SERVICE 06 -->

            <div class="col-md-6 col-lg-4">

                <div class="service-card">

                    <div class="service-number">

                        SERVICE 06

                    </div>


                    <div class="service-icon">

                        ↻

                    </div>


                    <h3>

                        Website Maintenance

                    </h3>


                    <p>

                        Keep your website secure, functional and up to date
                        with ongoing maintenance and improvement services.

                    </p>


                    <ul class="service-features">

                        <li>
                            Bug fixing
                        </li>

                        <li>
                            Content updates
                        </li>

                        <li>
                            Feature improvements
                        </li>

                        <li>
                            Performance improvements
                        </li>

                        <li>
                            Security updates
                        </li>

                        <li>
                            Database maintenance
                        </li>

                        <li>
                            Technical support
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =================================
     CUSTOM DEVELOPMENT FEATURE
================================= -->

<section class="section section-dark">

    <div class="container">

        <div class="featured-service">

            <span class="small-label">

                Custom Solutions

            </span>


            <h2>

                Your business doesn't need to fit into
                generic software.

            </h2>


            <p>

                If existing software does not match your business process,
                we can build a custom solution around your requirements.
                Whether you need a booking system, management platform,
                customer portal, internal dashboard or another specialized
                application, we can turn the requirement into a working
                digital product.

            </p>


            <div class="mt-4">

                <span class="feature-pill">
                    Custom workflows
                </span>

                <span class="feature-pill">
                    Admin dashboards
                </span>

                <span class="feature-pill">
                    Authentication
                </span>

                <span class="feature-pill">
                    Reports
                </span>

                <span class="feature-pill">
                    Notifications
                </span>

                <span class="feature-pill">
                    Payments
                </span>

                <span class="feature-pill">
                    Search
                </span>

                <span class="feature-pill">
                    Analytics
                </span>

                <span class="feature-pill">
                    API integrations
                </span>

            </div>

        </div>

    </div>

</section>



<!-- =================================
     BUSINESS PROBLEMS
================================= -->

<section class="section">

    <div class="container">

        <div class="row mb-5">

            <div class="col-lg-8">

                <span class="small-label">

                    What We Solve

                </span>


                <h2 class="section-title">

                    Turning manual processes into digital workflows.

                </h2>


                <p class="section-description">

                    Many businesses lose time because important processes
                    are handled manually. We can transform those processes
                    into centralized and manageable digital systems.

                </p>

            </div>

        </div>


        <div class="row g-4">


            <div class="col-md-6 col-lg-3">

                <div class="problem-card">

                    <h4>
                        Manual Records
                    </h4>

                    <p>
                        Replace spreadsheets and paper-based records with
                        structured digital information.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="problem-card">

                    <h4>
                        Repetitive Tasks
                    </h4>

                    <p>
                        Reduce repetitive work by creating automated digital
                        workflows.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="problem-card">

                    <h4>
                        Scattered Information
                    </h4>

                    <p>
                        Bring important business information into one
                        centralized application.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="problem-card">

                    <h4>
                        Limited Visibility
                    </h4>

                    <p>
                        Provide dashboards and reports that help businesses
                        understand their operations.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =================================
     TECHNOLOGIES
================================= -->

<section class="section technology-section" id="technologies">

    <div class="container">

        <div class="row">

            <div class="col-lg-8">

                <span class="small-label">

                    Development Technologies

                </span>


                <h2 class="section-title">

                    The right technology for the right solution.

                </h2>


                <p class="section-description">

                    Depending on the requirements of the project, we can
                    work with modern technologies and frameworks to build
                    reliable and scalable applications.

                </p>


                <div class="technology-strip">

                    <span class="technology">
                        PHP
                    </span>

                    <span class="technology">
                        Laravel
                    </span>

                    <span class="technology">
                        Java
                    </span>

                    <span class="technology">
                        Spring Boot
                    </span>

                    <span class="technology">
                        JavaScript
                    </span>

                    <span class="technology">
                        React.js
                    </span>

                    <span class="technology">
                        Node.js
                    </span>

                    <span class="technology">
                        MySQL
                    </span>

                    <span class="technology">
                        MongoDB
                    </span>

                    <span class="technology">
                        REST APIs
                    </span>

                    <span class="technology">
                        Bootstrap
                    </span>

                    <span class="technology">
                        Git & GitHub
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =================================
     CTA
================================= -->

<section class="cta-section">

    <div class="container">

        <div class="cta-box">

            <span class="small-label">

                Start Your Project

            </span>


            <h2>

                Have a business idea?

            </h2>


            <p>

                Tell us what you need. Whether you have a complete
                specification or only a business problem, we can discuss
                the right digital solution for you.

            </p>


            <a
                href="contact.php"
                class="cta-button">

                Discuss Your Project

            </a>

        </div>

    </div>

</section>



<!-- =================================
     FOOTER
================================= -->

<footer>

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6">

                <div class="footer-brand">

                    Codvanta<span class="brand-dot">.</span>

                </div>

                <p class="footer-text">

                    Turning Vision into Digital Reality.

                </p>

            </div>


            <div class="col-md-6 text-md-end">

                <div class="footer-links">

                    <a href="index.php">
                        Home
                    </a>

                    <a href="about.php">
                        About
                    </a>

                    <a href="service.php">
                        Services
                    </a>

                    <a href="projects.php">
                        Projects
                    </a>

                    <a href="contact.php">
                        Contact
                    </a>

                </div>

            </div>

        </div>

    </div>

</footer>



<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


</body>

</html>