<?php
// projects.php
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Projects | Codvanta</title>

    <meta name="description"
          content="Explore Codvanta's portfolio of custom web applications, business management systems, e-commerce platforms and other digital solutions.">


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

        .projects-hero {

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

            max-width: 750px;

            margin-top: 25px;

            color: #aebdce;

            font-size: 18px;
        }


        /* =========================================
           COMMON SECTIONS
        ========================================= */

        .section {

            padding:
                100px 0;
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


        /* =========================================
           CATEGORY FILTER
        ========================================= */

        .category-filter {

            display: flex;

            flex-wrap: wrap;

            gap: 10px;

            margin-top: 35px;

            margin-bottom: 45px;
        }


        .filter-btn {

            border:
                1px solid
                rgba(255,255,255,0.09);

            background:
                rgba(255,255,255,0.03);

            color: #9eafc3;

            padding:
                9px 18px;

            border-radius: 30px;

            font-size: 14px;

            cursor: pointer;

            transition: 0.3s;
        }


        .filter-btn:hover,
        .filter-btn.active {

            color: #ffffff;

            border-color:
                rgba(79,156,255,0.55);

            background:
                rgba(67,137,246,0.12);
        }


        /* =========================================
           PROJECT CARD
        ========================================= */

        .project-item {

            transition:
                opacity 0.3s,
                transform 0.3s;
        }


        .project-item.hidden {

            display: none;
        }


        .project-card {

            height: 100%;

            overflow: hidden;

            border-radius: 18px;

            background:
                linear-gradient(
                    145deg,
                    #0d1c2f,
                    #091522
                );

            border:
                1px solid
                rgba(255,255,255,0.07);

            transition:
                0.35s;

        }


        .project-card:hover {

            transform:
                translateY(-8px);

            border-color:
                rgba(82,151,255,0.4);

            box-shadow:
                0 20px 50px
                rgba(0,0,0,0.28);
        }


        /* =========================================
           PROJECT IMAGE
        ========================================= */

        .project-image {

            height: 250px;

            position: relative;

            overflow: hidden;

            background: #101f33;
        }


        .project-image img {

            width: 100%;

            height: 100%;

            object-fit: cover;

            transition:
                transform 0.5s;
        }


        .project-card:hover
        .project-image img {

            transform:
                scale(1.06);
        }


        .project-overlay {

            position: absolute;

            inset: 0;

            display: flex;

            align-items: flex-end;

            padding: 20px;

            background:
                linear-gradient(
                    transparent 45%,
                    rgba(0,0,0,0.65)
                );
        }


        .category-badge {

            display: inline-block;

            padding:
                6px 12px;

            border-radius: 20px;

            background:
                rgba(7,17,31,0.8);

            border:
                1px solid
                rgba(255,255,255,0.12);

            color: #ffffff;

            font-size: 12px;

            font-weight: 600;
        }


        /* =========================================
           PROJECT CONTENT
        ========================================= */

        .project-content {

            padding: 28px;
        }


        .project-content h3 {

            font-size: 22px;

            font-weight: 700;

            margin-bottom: 12px;
        }


        .project-content p {

            color: #96a9bd;

            font-size: 14px;

            margin-bottom: 22px;

            min-height: 72px;
        }


        /* =========================================
           TECHNOLOGIES
        ========================================= */

        .technology-list {

            display: flex;

            flex-wrap: wrap;

            gap: 7px;

            margin-bottom: 25px;
        }


        .technology-tag {

            padding:
                5px 10px;

            border-radius: 6px;

            background:
                rgba(255,255,255,0.045);

            border:
                1px solid
                rgba(255,255,255,0.07);

            color: #aebdce;

            font-size: 11px;
        }


        /* =========================================
           EXPLORE BUTTON
        ========================================= */

        .explore-btn {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            text-decoration: none;

            color: #ffffff;

            font-size: 14px;

            font-weight: 600;

            transition: 0.3s;
        }


        .explore-btn span {

            color: #5da5ff;

            font-size: 18px;

            transition: 0.3s;
        }


        .explore-btn:hover {

            color: #ffffff;
        }


        .explore-btn:hover span {

            transform:
                translateX(5px);
        }


        /* =========================================
           FEATURED PROJECT
        ========================================= */

        .featured-project {

            border-radius: 22px;

            overflow: hidden;

            background:
                linear-gradient(
                    135deg,
                    rgba(42,91,165,0.28),
                    rgba(71,54,126,0.22)
                );

            border:
                1px solid
                rgba(91,157,255,0.17);
        }


        .featured-image {

            height: 100%;

            min-height: 390px;

            overflow: hidden;
        }


        .featured-image img {

            width: 100%;

            height: 100%;

            object-fit: cover;
        }


        .featured-content {

            padding: 45px;
        }


        .featured-content h2 {

            font-size: 34px;

            font-weight: 800;

            margin-bottom: 15px;
        }


        .featured-content p {

            color: #a5b5c8;

            font-size: 15px;
        }


        .featured-label {

            display: inline-block;

            padding:
                6px 12px;

            border-radius: 20px;

            background:
                rgba(91,157,255,0.12);

            color: #62a4ff;

            font-size: 12px;

            font-weight: 700;

            margin-bottom: 18px;
        }


        /* =========================================
           PROJECT STATS
        ========================================= */

        .project-stats {

            display: flex;

            flex-wrap: wrap;

            gap: 30px;

            margin:
                25px 0;
        }


        .stat strong {

            display: block;

            font-size: 18px;

            color: #ffffff;
        }


        .stat span {

            color: #7f94aa;

            font-size: 12px;
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

            box-shadow:
                0 10px 30px
                rgba(52,120,246,0.25);
        }


        /* =========================================
           FOOTER
        ========================================= */

        footer {

            padding:
                35px 0;

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


            .section {

                padding:
                    75px 0;
            }


            .section-title {

                font-size: 34px;
            }


            .featured-content {

                padding: 35px;
            }


            .featured-content h2 {

                font-size: 29px;
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


            .project-image {

                height: 220px;
            }


            .project-content {

                padding: 25px;
            }


            .featured-content {

                padding: 28px;
            }


            .featured-image {

                min-height: 250px;
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
                        class="nav-link active"
                        href="projects.php">

                        Projects

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



<!-- =========================================
     HERO
========================================= -->

<section class="projects-hero">

    <div class="container">

        <div class="hero-content">


            <span class="small-label">

                Our Work

            </span>


            <h1 class="hero-title">

                Real solutions for
                <span>real businesses.</span>

            </h1>


            <p class="hero-description">

                Explore selected projects developed by Codvanta.
                Each project demonstrates how modern technology can be
                used to solve practical business problems and create
                useful digital experiences.

            </p>

        </div>

    </div>

</section>



<!-- =========================================
     PROJECTS
========================================= -->

<section class="section">


    <div class="container">


        <div class="row">


            <div class="col-lg-8">

                <span class="small-label">

                    Portfolio

                </span>


                <h2 class="section-title">

                    Selected projects.

                </h2>


                <p class="section-description">

                    Our portfolio covers different industries and
                    application types, from business management systems
                    to customer-facing web platforms.

                </p>

            </div>

        </div>



        <!-- CATEGORY FILTER -->

        <div class="category-filter">


            <button
                class="filter-btn active"
                data-filter="all">

                All Projects

            </button>


            <button
                class="filter-btn"
                data-filter="healthcare">

                Healthcare

            </button>


            <button
                class="filter-btn"
                data-filter="restaurant">

                Restaurant

            </button>


            <button
                class="filter-btn"
                data-filter="ecommerce">

                E-Commerce

            </button>


            <button
                class="filter-btn"
                data-filter="hotel">

                Hotel

            </button>


            <button
                class="filter-btn"
                data-filter="business">

                Business

            </button>

        </div>



        <div class="row g-4">


            <!-- =================================
                 PROJECT 01
            ================================= -->

            <div
                class="col-md-6 col-lg-4 project-item"
                data-category="healthcare">


                <div class="project-card">


                    <div class="project-image">

                        <img
                            src="assets/images/projects/ecims.jpg"
                            alt="ECIMS healthcare management system">


                        <div class="project-overlay">

                            <span class="category-badge">

                                Healthcare

                            </span>

                        </div>

                    </div>


                    <div class="project-content">


                        <h3>

                            ECIMS

                        </h3>


                        <p>

                            A comprehensive e-channeling and inventory
                            management platform designed to manage
                            appointments, doctors, prescriptions,
                            billing and healthcare operations.

                        </p>


                        <div class="technology-list">

                            <span class="technology-tag">
                                PHP
                            </span>

                            <span class="technology-tag">
                                MySQL
                            </span>

                            <span class="technology-tag">
                                JavaScript
                            </span>

                            <span class="technology-tag">
                                Bootstrap
                            </span>

                        </div>


                        <a
                            href="projects/ecims/index.php"
                            class="explore-btn">

                            Explore Project

                            <span>→</span>

                        </a>

                    </div>

                </div>

            </div>



            <!-- =================================
                 PROJECT 02
            ================================= -->

            <div
                class="col-md-6 col-lg-4 project-item"
                data-category="restaurant">


                <div class="project-card">


                    <div class="project-image">

                        <img
                            src="assets/img/restaurant.png"
                            alt="Restaurant management web application">


                        <div class="project-overlay">

                            <span class="category-badge">

                                Restaurant

                            </span>

                        </div>

                    </div>


                    <div class="project-content">


                        <h3>

                            Restaurant Management Platform

                        </h3>


                        <p>

                            A modern restaurant web application
                            demonstrating online menu browsing, food
                            search, ordering workflows and administrative
                            management.

                        </p>


                        <div class="technology-list">

                            <span class="technology-tag">
                                PHP
                            </span>

                            <span class="technology-tag">
                                MySQL
                            </span>

                            <span class="technology-tag">
                                JavaScript
                            </span>

                            <span class="technology-tag">
                                Bootstrap
                            </span>

                        </div>


                        <a
                            href="./urbanbites_demo.php"
                            class="explore-btn">

                            Explore Project

                            <span>→</span>

                        </a>

                    </div>

                </div>

            </div>



            <!-- =================================
                 PROJECT 03
            ================================= -->

            <div
                class="col-md-6 col-lg-4 project-item"
                data-category="ecommerce">


                <div class="project-card">


                    <div class="project-image">

                        <img
                            src="assets/images/projects/ecommerce.jpg"
                            alt="E-commerce web application">


                        <div class="project-overlay">

                            <span class="category-badge">

                                E-Commerce

                            </span>

                        </div>

                    </div>


                    <div class="project-content">


                        <h3>

                            E-Commerce Platform

                        </h3>


                        <p>

                            A modern online shopping platform with
                            product discovery, search, shopping cart,
                            customer accounts and order management.

                        </p>


                        <div class="technology-list">

                            <span class="technology-tag">
                                Laravel
                            </span>

                            <span class="technology-tag">
                                MySQL
                            </span>

                            <span class="technology-tag">
                                React.js
                            </span>

                            <span class="technology-tag">
                                REST API
                            </span>

                        </div>


                        <a
                            href="./novamart_demo.php"
                            class="explore-btn">

                            Explore Project

                            <span>→</span>

                        </a>

                    </div>

                </div>

            </div>



            <!-- =================================
                 PROJECT 04
            ================================= -->

            <div
                class="col-md-6 col-lg-4 project-item"
                data-category="hotel">


                <div class="project-card">


                    <div class="project-image">

                        <img
                            src="assets/images/projects/hotel.jpg"
                            alt="Hotel booking management system">


                        <div class="project-overlay">

                            <span class="category-badge">

                                Hospitality

                            </span>

                        </div>

                    </div>


                    <div class="project-content">


                        <h3>

                            Hotel Booking System

                        </h3>


                        <p>

                            A hotel website and booking management
                            platform designed to help customers discover
                            rooms and manage reservations online.

                        </p>


                        <div class="technology-list">

                            <span class="technology-tag">
                                PHP
                            </span>

                            <span class="technology-tag">
                                MySQL
                            </span>

                            <span class="technology-tag">
                                JavaScript
                            </span>

                            <span class="technology-tag">
                                Bootstrap
                            </span>

                        </div>


                        <a
                            href="projects/hotel/index.php"
                            class="explore-btn">

                            Explore Project

                            <span>→</span>

                        </a>

                    </div>

                </div>

            </div>



            <!-- =================================
                 PROJECT 05
            ================================= -->

            <div
                class="col-md-6 col-lg-4 project-item"
                data-category="business">


                <div class="project-card">


                    <div class="project-image">

                        <img
                            src="assets/images/projects/business.jpg"
                            alt="Business management system">


                        <div class="project-overlay">

                            <span class="category-badge">

                                Business

                            </span>

                        </div>

                    </div>


                    <div class="project-content">


                        <h3>

                            Business Management System

                        </h3>


                        <p>

                            A centralized business management solution
                            for managing customers, employees, inventory,
                            sales and operational reports.

                        </p>


                        <div class="technology-list">

                            <span class="technology-tag">
                                Laravel
                            </span>

                            <span class="technology-tag">
                                MySQL
                            </span>

                            <span class="technology-tag">
                                JavaScript
                            </span>

                            <span class="technology-tag">
                                REST API
                            </span>

                        </div>


                        <a
                            href="projects/business/index.php"
                            class="explore-btn">

                            Explore Project

                            <span>→</span>

                        </a>

                    </div>

                </div>

            </div>



            <!-- =================================
                 PROJECT 06
            ================================= -->

            <div
                class="col-md-6 col-lg-4 project-item"
                data-category="business">


                <div class="project-card">


                    <div class="project-image">

                        <img
                            src="assets/images/projects/service.jpg"
                            alt="Service business management platform">


                        <div class="project-overlay">

                            <span class="category-badge">

                                Services

                            </span>

                        </div>

                    </div>


                    <div class="project-content">


                        <h3>

                            Service Business Platform

                        </h3>


                        <p>

                            A customer-focused platform for service
                            businesses to manage enquiries, bookings,
                            customers and internal operations.

                        </p>


                        <div class="technology-list">

                            <span class="technology-tag">
                                Node.js
                            </span>

                            <span class="technology-tag">
                                MongoDB
                            </span>

                            <span class="technology-tag">
                                React.js
                            </span>

                            <span class="technology-tag">
                                REST API
                            </span>

                        </div>


                        <a
                            href="./fitcore_demo.php"
                            class="explore-btn">

                            Explore Project

                            <span>→</span>

                        </a>

                    </div>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- =========================================
     FEATURED PROJECT
========================================= -->

<section class="section section-dark">


    <div class="container">


        <div class="featured-project">


            <div class="row g-0 align-items-stretch">


                <div class="col-lg-6">


                    <div class="featured-image">

                        <img
                            src="assets/images/projects/featured.jpg"
                            alt="Featured Codvanta project">

                    </div>

                </div>


                <div class="col-lg-6">


                    <div class="featured-content">


                        <span class="featured-label">

                            FEATURED PROJECT

                        </span>


                        <h2>

                            Built to solve complex
                            business requirements.

                        </h2>


                        <p>

                            Our featured projects demonstrate how multiple
                            business processes can be brought together
                            inside one centralized digital platform.

                        </p>


                        <div class="project-stats">


                            <div class="stat">

                                <strong>
                                    Custom
                                </strong>

                                <span>
                                    Development
                                </span>

                            </div>


                            <div class="stat">

                                <strong>
                                    Multi-Role
                                </strong>

                                <span>
                                    Access
                                </span>

                            </div>


                            <div class="stat">

                                <strong>
                                    Scalable
                                </strong>

                                <span>
                                    Architecture
                                </span>

                            </div>

                        </div>


                        <a
                            href="projects/ecims/index.php"
                            class="explore-btn">

                            View Featured Project

                            <span>→</span>

                        </a>

                    </div>

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

                Your Project Could Be Next

            </span>


            <h2>

                Have an idea worth building?

            </h2>


            <p>

                Whether you need a business website, custom web
                application, management system or e-commerce platform,
                let's discuss your requirements and find the right
                solution.

            </p>


            <a
                href="contact.php"
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



<!-- =========================================
     JAVASCRIPT
========================================= -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>



<script>

    document.addEventListener(
        "DOMContentLoaded",
        function () {


            const filterButtons =
                document.querySelectorAll(
                    ".filter-btn"
                );


            const projects =
                document.querySelectorAll(
                    ".project-item"
                );


            filterButtons.forEach(
                function (button) {


                    button.addEventListener(
                        "click",
                        function () {


                            /*
                             * Remove active state
                             * from all buttons.
                             */

                            filterButtons.forEach(
                                function (btn) {

                                    btn.classList.remove(
                                        "active"
                                    );

                                }
                            );


                            /*
                             * Activate
                             * clicked button.
                             */

                            this.classList.add(
                                "active"
                            );


                            /*
                             * Get selected category.
                             */

                            const filter =
                                this.getAttribute(
                                    "data-filter"
                                );


                            /*
                             * Filter projects.
                             */

                            projects.forEach(
                                function (project) {


                                    const category =
                                        project.getAttribute(
                                            "data-category"
                                        );


                                    if (
                                        filter === "all" ||
                                        category === filter
                                    ) {

                                        project.classList.remove(
                                            "hidden"
                                        );

                                    }

                                    else {

                                        project.classList.add(
                                            "hidden"
                                        );

                                    }

                                }
                            );

                        }
                    );

                }
            );

        }
    );

</script>


</body>

</html>