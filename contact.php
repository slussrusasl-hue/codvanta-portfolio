<?php
// contact.php
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact Us | Codvanta</title>

    <meta name="description"
          content="Contact Codvanta about your website, software application, business management system or e-commerce project.">

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="assets/bootstrap/bootstrap.min.css">

    <!-- Google Font -->
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">


    <style>

        /* =========================================
           GLOBAL
        ========================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {

            font-family:
                'Inter',
                'Trebuchet MS',
                sans-serif;

            background: #07111f;

            color: #ffffff;

            line-height: 1.7;
        }


        /* =========================================
           NAVBAR
        ========================================= */

        .navbar {

            padding: 18px 0;

            background:
                rgba(7, 17, 31, 0.92);

            backdrop-filter: blur(12px);

            border-bottom:
                1px solid
                rgba(255,255,255,0.06);
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

            padding:
                9px 20px !important;

            border-radius: 8px;

            background:
                linear-gradient(
                    135deg,
                    #3478f6,
                    #7256e8
                );

            color: #ffffff !important;
        }


        /* =========================================
           HERO
        ========================================= */

        .contact-hero {

            min-height: 65vh;

            display: flex;

            align-items: center;

            position: relative;

            overflow: hidden;

            background:

                radial-gradient(
                    circle at 15% 30%,
                    rgba(54,116,246,0.18),
                    transparent 30%
                ),

                radial-gradient(
                    circle at 85% 70%,
                    rgba(114,86,232,0.16),
                    transparent 30%
                ),

                #07111f;
        }


        .hero-content {

            padding:
                120px 0 80px;
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

            font-size:
                clamp(42px, 6vw, 72px);

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

            max-width: 730px;

            margin-top: 25px;

            color: #aebdce;

            font-size: 18px;
        }


        /* =========================================
           CONTACT SECTION
        ========================================= */

        .contact-section {

            padding:
                100px 0;
        }


        .contact-info {

            padding-right: 30px;
        }


        .section-title {

            font-size: 40px;

            font-weight: 800;

            letter-spacing: -1px;

            margin-bottom: 18px;
        }


        .section-description {

            color: #9eafc3;

            font-size: 16px;

            max-width: 580px;
        }


        /* =========================================
           CONTACT INFO CARDS
        ========================================= */

        .info-card {

            display: flex;

            align-items: flex-start;

            gap: 17px;

            margin-top: 30px;
        }


        .info-icon {

            min-width: 48px;

            height: 48px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 12px;

            background:
                rgba(67,137,246,0.12);

            color: #5da5ff;

            font-size: 20px;
        }


        .info-card h5 {

            font-size: 16px;

            font-weight: 700;

            margin-bottom: 3px;
        }


        .info-card p {

            color: #91a4ba;

            font-size: 14px;

            margin: 0;
        }


        /* =========================================
           WHATSAPP
        ========================================= */

        .whatsapp-box {

            margin-top: 40px;

            padding: 25px;

            border-radius: 16px;

            background:
                rgba(37,211,102,0.05);

            border:
                1px solid
                rgba(37,211,102,0.15);
        }


        .whatsapp-box h5 {

            font-weight: 700;

            margin-bottom: 7px;
        }


        .whatsapp-box p {

            color: #8fa4b9;

            font-size: 14px;

            margin-bottom: 18px;
        }


        .whatsapp-btn {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            padding:
                10px 18px;

            border-radius: 8px;

            background:
                #128c4a;

            color: #ffffff;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            transition: 0.3s;
        }


        .whatsapp-btn:hover {

            color: #ffffff;

            transform:
                translateY(-2px);

            background:
                #0d793e;
        }


        /* =========================================
           FORM CARD
        ========================================= */

        .form-card {

            padding:
                40px;

            border-radius: 22px;

            background:

                linear-gradient(
                    145deg,
                    #0d1c2f,
                    #091522
                );

            border:
                1px solid
                rgba(255,255,255,0.07);

            box-shadow:
                0 25px 70px
                rgba(0,0,0,0.18);
        }


        .form-title {

            font-size: 26px;

            font-weight: 800;

            margin-bottom: 8px;
        }


        .form-subtitle {

            color: #8fa2b8;

            font-size: 14px;

            margin-bottom: 30px;
        }


        .form-label {

            color: #c4cfdd;

            font-size: 13px;

            font-weight: 600;

            margin-bottom: 8px;
        }


        .form-control,
        .form-select {

            min-height: 48px;

            background:
                #081421;

            border:
                1px solid
                rgba(255,255,255,0.09);

            color: #ffffff;

            border-radius: 8px;

            font-size: 14px;

            padding:
                10px 14px;
        }


        .form-control:focus,
        .form-select:focus {

            background:
                #081421;

            color: #ffffff;

            border-color:
                #4d94ff;

            box-shadow:
                0 0 0 3px
                rgba(77,148,255,0.1);
        }


        .form-control::placeholder {

            color: #667a90;
        }


        .form-select option {

            background:
                #0b1827;

            color: #ffffff;
        }


        textarea.form-control {

            min-height: 150px;

            resize: vertical;
        }


        .required {

            color: #5da5ff;
        }


        .submit-btn {

            width: 100%;

            min-height: 50px;

            border: none;

            border-radius: 9px;

            background:
                linear-gradient(
                    135deg,
                    #3478f6,
                    #7256e8
                );

            color: #ffffff;

            font-size: 15px;

            font-weight: 700;

            transition: 0.3s;
        }


        .submit-btn:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 12px 30px
                rgba(52,120,246,0.25);
        }


        .form-note {

            text-align: center;

            color: #687d94;

            font-size: 12px;

            margin-top: 15px;

            margin-bottom: 0;
        }


        /* =========================================
           PROJECT TYPES
        ========================================= */

        .project-types-section {

            padding:
                90px 0;

            background:
                #091525;
        }


        .project-type-card {

            height: 100%;

            padding:
                28px;

            border-radius: 16px;

            background:
                #0c1a2b;

            border:
                1px solid
                rgba(255,255,255,0.06);

            transition: 0.3s;
        }


        .project-type-card:hover {

            transform:
                translateY(-6px);

            border-color:
                rgba(82,151,255,0.35);
        }


        .project-type-card h4 {

            font-size: 18px;

            font-weight: 700;

            margin-bottom: 10px;
        }


        .project-type-card p {

            color: #91a4ba;

            font-size: 14px;

            margin-bottom: 0;
        }


        /* =========================================
           CTA
        ========================================= */

        .cta-section {

            padding:
                100px 0;

            background:

                radial-gradient(
                    circle at center,
                    rgba(72,118,231,0.18),
                    transparent 45%
                ),

                #07111f;
        }


        .cta-box {

            padding:
                70px 30px;

            text-align: center;

            border-radius: 24px;

            border:
                1px solid
                rgba(91,157,255,0.15);

            background:

                linear-gradient(
                    135deg,
                    rgba(35,74,133,0.25),
                    rgba(71,54,126,0.2)
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

            padding:
                13px 28px;

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

            transform:
                translateY(-2px);
        }


        /* =========================================
           FOOTER
        ========================================= */

        footer {

            padding:
                35px 0;

            background:
                #050d17;

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


        /* =========================================
           RESPONSIVE
        ========================================= */

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


            .contact-section {

                padding:
                    75px 0;
            }


            .contact-info {

                padding-right: 0;

                margin-bottom: 50px;
            }


            .section-title {

                font-size: 34px;
            }


            .form-card {

                padding: 30px;
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


            .form-card {

                padding: 25px 20px;
            }


            .cta-box {

                padding:
                    50px 20px;
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


<!-- =========================================
     NAVBAR
========================================= -->

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">

    <div class="container">


        <a class="navbar-brand"
           href="index.php">

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
                        class="nav-link"
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




                <li class="nav-item ms-lg-3">

                    <a
                        class="nav-link contact-btn active"
                        href="contact.php">

                        Let's Talk

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- =========================================
     HERO
========================================= -->

<section class="contact-hero">


    <div class="container">


        <div class="hero-content">


            <span class="small-label">

                Start a Conversation

            </span>


            <h1 class="hero-title">

                Let's build something
                <span>valuable together.</span>

            </h1>


            <p class="hero-description">

                Have a business idea, a problem that needs solving, or an
                existing system that needs improvement? Tell us about it.
                We'll explore the requirements and discuss the right
                digital solution.

            </p>

        </div>

    </div>

</section>



<!-- =========================================
     CONTACT FORM SECTION
========================================= -->

<section class="contact-section">


    <div class="container">


        <div class="row g-5">


            <!-- =================================
                 LEFT SIDE
            ================================= -->

            <div class="col-lg-5">


                <div class="contact-info">


                    <span class="small-label">

                        Get In Touch

                    </span>


                    <h2 class="section-title">

                        Tell us about your project.

                    </h2>


                    <p class="section-description">

                        You don't need to have a complete technical
                        specification. Simply explain what you want to
                        achieve, and we can discuss how technology can
                        help your business.

                    </p>



                    <!-- INFO 01 -->

                    <div class="info-card">


                        <div class="info-icon">

                            ✉

                        </div>


                        <div>

                            <h5>
                                Email
                            </h5>

                            <p>
                                Send us your project requirements and
                                questions.
                            </p>

                        </div>

                    </div>



                    <!-- INFO 02 -->

                    <div class="info-card">


                        <div class="info-icon">

                            ◉

                        </div>


                        <div>

                            <h5>
                                Worldwide Projects
                            </h5>

                            <p>
                                We are open to working with businesses and
                                customers from different countries.
                            </p>

                        </div>

                    </div>



                    <!-- INFO 03 -->

                    <div class="info-card">


                        <div class="info-icon">

                            ↗

                        </div>


                        <div>

                            <h5>
                                Custom Solutions
                            </h5>

                            <p>
                                Every project can be discussed according to
                                your specific business requirements.
                            </p>

                        </div>

                    </div>



                    <!-- WHATSAPP -->

                    <div class="whatsapp-box">


                        <h5>

                            Prefer WhatsApp?

                        </h5>


                        <p>

                            If you prefer a quick conversation before
                            submitting the project details, you can contact
                            us directly through WhatsApp.

                        </p>


                        <!--
                            Replace the number below with
                            your real WhatsApp number.

                            Format:
                            https://wa.me/COUNTRYCODEPHONENUMBER
                        -->

                        <a
                            href="https://wa.me/947XXXXXXXX"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="whatsapp-btn">

                            WhatsApp Us

                            <span>→</span>

                        </a>

                    </div>


                </div>

            </div>



            <!-- =================================
                 RIGHT SIDE FORM
            ================================= -->

            <div class="col-lg-7">


                <div class="form-card">


                    <h3 class="form-title">

                        Project Inquiry

                    </h3>


                    <p class="form-subtitle">

                        Share a few details about your project. The more
                        information you provide, the better we can
                        understand your requirements.

                    </p>


                    <form
                        action=""
                        method="POST">


                        <!-- NAME -->

                        <div class="mb-3">


                            <label
                                for="name"
                                class="form-label">

                                Full Name
                                <span class="required">*</span>

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                placeholder="Enter your name"
                                required>

                        </div>



                        <!-- EMAIL -->

                        <div class="mb-3">


                            <label
                                for="email"
                                class="form-label">

                                Email Address
                                <span class="required">*</span>

                            </label>


                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="you@example.com"
                                required>

                        </div>



                        <!-- COUNTRY -->

                        <div class="mb-3">


                            <label
                                for="country"
                                class="form-label">

                                Country
                                <span class="required">*</span>

                            </label>


                            <select
                                class="form-select"
                                id="country"
                                name="country"
                                required>


                                <option value=""
                                        selected
                                        disabled>

                                    Select your country

                                </option>


                                <option value="Sri Lanka">
                                    Sri Lanka
                                </option>

                                <option value="United States">
                                    United States
                                </option>

                                <option value="United Kingdom">
                                    United Kingdom
                                </option>

                                <option value="Canada">
                                    Canada
                                </option>

                                <option value="Australia">
                                    Australia
                                </option>

                                <option value="New Zealand">
                                    New Zealand
                                </option>

                                <option value="India">
                                    India
                                </option>

                                <option value="United Arab Emirates">
                                    United Arab Emirates
                                </option>

                                <option value="Saudi Arabia">
                                    Saudi Arabia
                                </option>

                                <option value="Singapore">
                                    Singapore
                                </option>

                                <option value="Malaysia">
                                    Malaysia
                                </option>

                                <option value="Germany">
                                    Germany
                                </option>

                                <option value="France">
                                    France
                                </option>

                                <option value="Italy">
                                    Italy
                                </option>

                                <option value="Spain">
                                    Spain
                                </option>

                                <option value="Netherlands">
                                    Netherlands
                                </option>

                                <option value="Other">
                                    Other
                                </option>


                            </select>

                        </div>



                        <!-- PROJECT DOMAIN -->

                        <div class="mb-3">


                            <label
                                for="domain"
                                class="form-label">

                                Project Domain
                                <span class="required">*</span>

                            </label>


                            <select
                                class="form-select"
                                id="domain"
                                name="domain"
                                required>


                                <option value=""
                                        selected
                                        disabled>

                                    Select project domain

                                </option>


                                <option value="Business Management">
                                    Business Management
                                </option>

                                <option value="E-Commerce">
                                    E-Commerce
                                </option>

                                <option value="Healthcare">
                                    Healthcare
                                </option>

                                <option value="Restaurant">
                                    Restaurant
                                </option>

                                <option value="Hotel & Hospitality">
                                    Hotel & Hospitality
                                </option>

                                <option value="Education">
                                    Education
                                </option>

                                <option value="Finance">
                                    Finance
                                </option>

                                <option value="Retail">
                                    Retail
                                </option>

                                <option value="Logistics">
                                    Logistics
                                </option>

                                <option value="Service Business">
                                    Service Business
                                </option>

                                <option value="Other">
                                    Other
                                </option>


                            </select>

                        </div>



                        <!-- PROJECT DESCRIPTION -->

                        <div class="mb-4">


                            <label
                                for="description"
                                class="form-label">

                                Brief Description About Your Project
                                <span class="required">*</span>

                            </label>


                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                placeholder="Tell us about your business, what you want to build, the problem you want to solve, or the features you need..."
                                required></textarea>


                        </div>



                        <!-- SUBMIT -->

                        <button
                            type="submit"
                            class="submit-btn">

                            Send Project Inquiry
                            &nbsp; →

                        </button>


                        <p class="form-note">

                            Your project information will be used only to
                            understand and discuss your requirements.

                        </p>


                    </form>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =========================================
     PROJECT TYPES
========================================= -->

<section class="project-types-section">


    <div class="container">


        <div class="row mb-5">


            <div class="col-lg-8">


                <span class="small-label">

                    What Can We Build?

                </span>


                <h2 class="section-title">

                    From simple websites to complete
                    business systems.

                </h2>


                <p class="section-description">

                    Your project doesn't have to fit into a predefined
                    package. We can discuss the requirements and create
                    a solution around your business.

                </p>

            </div>

        </div>



        <div class="row g-4">


            <!-- CARD 01 -->

            <div class="col-md-6 col-lg-3">


                <div class="project-type-card">


                    <h4>
                        Business Systems
                    </h4>


                    <p>

                        Custom management systems for customers,
                        employees, inventory, sales, appointments and
                        business operations.

                    </p>


                </div>

            </div>



            <!-- CARD 02 -->

            <div class="col-md-6 col-lg-3">


                <div class="project-type-card">


                    <h4>
                        E-Commerce
                    </h4>


                    <p>

                        Online stores with products, search, shopping
                        carts, orders, payments and administration.

                    </p>


                </div>

            </div>



            <!-- CARD 03 -->

            <div class="col-md-6 col-lg-3">


                <div class="project-type-card">


                    <h4>
                        Web Applications
                    </h4>


                    <p>

                        Custom web applications designed around unique
                        workflows and business requirements.

                    </p>


                </div>

            </div>



            <!-- CARD 04 -->

            <div class="col-md-6 col-lg-3">


                <div class="project-type-card">


                    <h4>
                        Existing Systems
                    </h4>


                    <p>

                        Improve, maintain, modernize or add new
                        functionality to your existing website or
                        application.

                    </p>


                </div>

            </div>


        </div>

    </div>

</section>



<!-- =========================================
     CTA
========================================= -->

<section class="cta-section">


    <div class="container">


        <div class="cta-box">


            <span class="small-label">

                Your Idea. Our Expertise.

            </span>


            <h2>

                Ready to turn your idea into reality?

            </h2>


            <p>

                Start with a simple conversation. Tell us what you're
                trying to achieve, and let's explore the possibilities
                together.

            </p>


            <a
                href="#project-form"
                class="cta-button">

                Start Your Project

            </a>

        </div>

    </div>

</section>



<!-- =========================================
     FOOTER
========================================= -->

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