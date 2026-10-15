<?php
// about.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us | Codvanta</title>

    <meta name="description"
          content="Learn about Codvanta, a professional web and software development team building modern digital solutions for businesses across different industries.">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

        /* =========================
           NAVBAR
        ========================= */

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

        /* =========================
           HERO
        ========================= */

        .about-hero {
            min-height: 75vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 15% 30%, rgba(54, 116, 246, 0.18), transparent 30%),
                radial-gradient(circle at 85% 70%, rgba(114, 86, 232, 0.16), transparent 30%),
                #07111f;
        }

        .hero-content {
            padding: 120px 0 90px;
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
            max-width: 900px;
        }

        .hero-title span {
            background: linear-gradient(90deg, #55a3ff, #8d6cff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-description {
            max-width: 720px;
            margin-top: 25px;
            color: #aebdce;
            font-size: 18px;
        }

        /* =========================
           COMMON
        ========================= */

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
            max-width: 700px;
            font-size: 17px;
        }

        /* =========================
           WHO WE ARE
        ========================= */

        .about-text {
            color: #b2c0d0;
            font-size: 16px;
        }

        .about-text strong {
            color: #ffffff;
        }

        .highlight-box {
            height: 100%;
            padding: 35px;
            border-radius: 18px;
            background: linear-gradient(
                145deg,
                rgba(40, 82, 145, 0.25),
                rgba(35, 38, 79, 0.2)
            );
            border: 1px solid rgba(111, 156, 224, 0.15);
        }

        .highlight-number {
            font-size: 48px;
            font-weight: 800;
            color: #5ba5ff;
        }

        .highlight-title {
            font-size: 20px;
            font-weight: 700;
            margin-top: 5px;
        }

        .highlight-text {
            color: #96a9bd;
            margin-top: 8px;
        }

        /* =========================
           PROCESS
        ========================= */

        .process-card {
            height: 100%;
            padding: 30px;
            background: #0c1a2b;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            transition: 0.3s;
        }

        .process-card:hover {
            transform: translateY(-7px);
            border-color: rgba(78, 148, 255, 0.4);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .process-number {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3478f6, #7256e8);
            font-weight: 800;
            margin-bottom: 22px;
        }

        .process-card h4 {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .process-card p {
            color: #96a9bd;
            font-size: 14px;
            margin-bottom: 0;
        }

        /* =========================
           INDUSTRIES
        ========================= */

        .industry-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 13px;
            margin-top: 35px;
        }

        .industry {
            padding: 12px 19px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.025);
            border-radius: 50px;
            color: #c3cfdd;
            font-size: 14px;
            transition: 0.3s;
        }

        .industry:hover {
            border-color: #4c94ff;
            color: #ffffff;
            background: rgba(76,148,255,0.08);
        }

        /* =========================
           TECHNOLOGIES
        ========================= */

        .technology-section {
            background: #06101c;
        }

        .tech-card {
            padding: 25px;
            background: #0c1929;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px;
            height: 100%;
        }

        .tech-card h5 {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .tech-card p {
            color: #8fa2b8;
            font-size: 14px;
            margin-bottom: 0;
        }

        /* =========================
           WHY CODVANTA
        ========================= */

        .why-card {
            padding: 30px;
            height: 100%;
            border-radius: 16px;
            background: linear-gradient(
                145deg,
                #0c1b2d,
                #0a1523
            );
            border: 1px solid rgba(255,255,255,0.06);
        }

        .why-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(65, 135, 245, 0.12);
            color: #5ca5ff;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .why-card h4 {
            font-size: 19px;
            font-weight: 700;
        }

        .why-card p {
            color: #94a7bc;
            font-size: 14px;
            margin-bottom: 0;
        }

        /* =========================
           CTA
        ========================= */

        .cta-section {
            padding: 100px 0;
            background:
                radial-gradient(circle at center, rgba(72, 118, 231, 0.18), transparent 45%),
                #07111f;
        }

        .cta-box {
            padding: 70px 30px;
            text-align: center;
            border-radius: 24px;
            border: 1px solid rgba(91, 157, 255, 0.15);
            background: linear-gradient(
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
            margin: 0 auto 30px;
        }

        .cta-button {
            display: inline-block;
            padding: 13px 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, #3478f6, #7256e8);
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .cta-button:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(52,120,246,0.25);
        }

        /* =========================
           FOOTER
        ========================= */

        footer {
            padding: 35px 0;
            background: #050d17;
            border-top: 1px solid rgba(255,255,255,0.05);
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

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 991px) {

            .navbar-nav {
                padding-top: 15px;
            }

            .navbar-nav .nav-link {
                margin-left: 0;
                padding: 9px 0;
            }

            .hero-content {
                padding: 100px 0 70px;
            }

            .section {
                padding: 75px 0;
            }

            .section-title {
                font-size: 34px;
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

<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">

    <div class="container">

        <a class="navbar-brand" href="index.php">
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

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="about.php">
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="services.php">
                        Services
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="projects.php">
                        Projects
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#technologies">
                        Technologies
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a class="nav-link contact-btn" href="contact.php">
                        Let's Talk
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- =========================
     HERO
========================= -->

<section class="about-hero">

    <div class="container">

        <div class="hero-content">

            <span class="small-label">
                About Codvanta
            </span>

            <h1 class="hero-title">
                We turn business ideas into
                <span>digital solutions.</span>
            </h1>

            <p class="hero-description">
                Codvanta is a professional web and software development team
                focused on creating modern, reliable and scalable digital
                solutions that solve real business problems.
            </p>

        </div>

    </div>

</section>


<!-- =========================
     WHO WE ARE
========================= -->

<section class="section">

    <div class="container">

        <div class="row g-5 align-items-center">

            <div class="col-lg-7">

                <span class="small-label">
                    Who We Are
                </span>

                <h2 class="section-title">
                    Technology with a purpose.
                </h2>

                <p class="about-text">
                    At <strong>Codvanta</strong>, we are professional,
                    well-educated and experienced web and software developers
                    who believe that technology should solve problems rather
                    than simply look impressive.
                </p>

                <p class="about-text">
                    Every business has its own processes, challenges and
                    requirements. Instead of forcing businesses to adapt to
                    generic software, we focus on understanding the customer's
                    requirements and developing solutions around their actual
                    workflow.
                </p>

                <p class="about-text">
                    From a simple business website to a complex business
                    management system, we can design and develop solutions
                    according to the required functionality, budget and future
                    growth of the business.
                </p>

            </div>

            <div class="col-lg-5">

                <div class="highlight-box">

                    <div class="highlight-number">
                        01
                    </div>

                    <div class="highlight-title">
                        Business First
                    </div>

                    <p class="highlight-text">
                        We start by understanding the business problem before
                        deciding how technology should be used to solve it.
                    </p>

                    <hr class="border-secondary my-4">

                    <div class="highlight-number">
                        02
                    </div>

                    <div class="highlight-title">
                        Solution Focused
                    </div>

                    <p class="highlight-text">
                        Our goal is to create practical software that provides
                        measurable value to the customer.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     OUR CAPABILITY
========================= -->

<section class="section section-dark">

    <div class="container">

        <div class="row mb-5">

            <div class="col-lg-8">

                <span class="small-label">
                    Our Capability
                </span>

                <h2 class="section-title">
                    From idea to working software.
                </h2>

                <p class="section-description">
                    We follow a structured development approach to transform
                    an idea or business problem into a practical digital
                    solution.
                </p>

            </div>

        </div>


        <div class="row g-4">

            <div class="col-md-6 col-lg-4">

                <div class="process-card">

                    <div class="process-number">01</div>

                    <h4>Understand</h4>

                    <p>
                        Understand the customer's business, requirements,
                        users, workflows and challenges.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-4">

                <div class="process-card">

                    <div class="process-number">02</div>

                    <h4>Plan & Design</h4>

                    <p>
                        Plan the system architecture, database, user
                        experience and important application workflows.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-4">

                <div class="process-card">

                    <div class="process-number">03</div>

                    <h4>Develop</h4>

                    <p>
                        Build secure and functional web applications using
                        appropriate modern technologies.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-4">

                <div class="process-card">

                    <div class="process-number">04</div>

                    <h4>Test</h4>

                    <p>
                        Test functionality, usability, security and
                        compatibility before delivering the solution.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-4">

                <div class="process-card">

                    <div class="process-number">05</div>

                    <h4>Deploy</h4>

                    <p>
                        Deploy the completed application to an appropriate
                        hosting environment and make it accessible to users.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-4">

                <div class="process-card">

                    <div class="process-number">06</div>

                    <h4>Improve</h4>

                    <p>
                        Continue improving the system as the customer's
                        business grows and new requirements appear.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     INDUSTRIES
========================= -->

<section class="section">

    <div class="container">

        <div class="row">

            <div class="col-lg-8">

                <span class="small-label">
                    Industry Flexibility
                </span>

                <h2 class="section-title">
                    Any domain. Real problems.
                </h2>

                <p class="section-description">
                    Software requirements are different from business to
                    business. Our development approach allows us to create
                    solutions for a wide range of industries.
                </p>

            </div>

        </div>


        <div class="industry-wrapper">

            <span class="industry">Healthcare</span>

            <span class="industry">Hotels & Resorts</span>

            <span class="industry">Restaurants</span>

            <span class="industry">E-Commerce</span>

            <span class="industry">Education</span>

            <span class="industry">Finance</span>

            <span class="industry">Retail</span>

            <span class="industry">Service Businesses</span>

            <span class="industry">Logistics</span>

            <span class="industry">Professional Services</span>

            <span class="industry">And More</span>

        </div>

    </div>

</section>


<!-- =========================
     TECHNOLOGY
========================= -->

<section class="section technology-section" id="technologies">

    <div class="container">

        <div class="row mb-5">

            <div class="col-lg-8">

                <span class="small-label">
                    Technology
                </span>

                <h2 class="section-title">
                    Built with modern technologies.
                </h2>

                <p class="section-description">
                    We select technologies according to the project's
                    requirements, complexity, scalability and long-term
                    maintenance needs.
                </p>

            </div>

        </div>


        <div class="row g-4">

            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>PHP</h5>

                    <p>
                        Reliable backend development for dynamic web
                        applications.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>Laravel</h5>

                    <p>
                        Modern PHP framework for scalable business
                        applications and APIs.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>Java</h5>

                    <p>
                        Powerful technology for robust backend and enterprise
                        applications.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>Spring Boot</h5>

                    <p>
                        Modern Java framework for production-ready backend
                        systems and APIs.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>React.js</h5>

                    <p>
                        Modern component-based interfaces and interactive
                        frontend applications.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>Node.js</h5>

                    <p>
                        Fast and scalable JavaScript runtime for backend
                        applications and APIs.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>MongoDB</h5>

                    <p>
                        Flexible NoSQL database solutions for modern
                        applications.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>MySQL</h5>

                    <p>
                        Reliable relational database solutions for
                        data-driven applications.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>JavaScript</h5>

                    <p>
                        Interactive and dynamic functionality for modern
                        websites and applications.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>Bootstrap</h5>

                    <p>
                        Responsive and professional user interface
                        development.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>REST APIs</h5>

                    <p>
                        Structured communication between applications and
                        services.
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="tech-card">

                    <h5>Git & GitHub</h5>

                    <p>
                        Version control and collaborative software
                        development workflows.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     WHY CODVANTA
========================= -->

<section class="section">

    <div class="container">

        <div class="row mb-5">

            <div class="col-lg-8">

                <span class="small-label">
                    Why Codvanta
                </span>

                <h2 class="section-title">
                    More than just writing code.
                </h2>

                <p class="section-description">
                    We focus on delivering software that is useful,
                    maintainable and aligned with the customer's business
                    objectives.
                </p>

            </div>

        </div>


        <div class="row g-4">

            <div class="col-md-6 col-lg-3">

                <div class="why-card">

                    <div class="why-icon">
                        ✓
                    </div>

                    <h4>Business Focused</h4>

                    <p>
                        We understand the business problem first and then
                        design the right technology solution.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="why-card">

                    <div class="why-icon">
                        ⚡
                    </div>

                    <h4>Modern Technology</h4>

                    <p>
                        We use modern development tools and technologies
                        appropriate for today's digital environment.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="why-card">

                    <div class="why-icon">
                        ◈
                    </div>

                    <h4>Quality Mindset</h4>

                    <p>
                        Clean structure, usability, security and reliable
                        functionality are important parts of our development
                        process.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="why-card">

                    <div class="why-icon">
                        ↗
                    </div>

                    <h4>Built to Grow</h4>

                    <p>
                        We aim to build systems that can evolve when the
                        customer's business and requirements grow.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     CTA
========================= -->

<section class="cta-section">

    <div class="container">

        <div class="cta-box">

            <span class="small-label">
                Let's Build Something
            </span>

            <h2>
                Have a business problem?
            </h2>

            <p>
                Tell us what you are trying to achieve. We can help transform
                your idea, process or business challenge into a practical
                digital solution.
            </p>

            <a href="contact.php" class="cta-button">
                Start a Conversation
            </a>

        </div>

    </div>

</section>


<!-- =========================
     FOOTER
========================= -->

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

                    <a href="index.php">Home</a>

                    <a href="about.php">About</a>

                    <a href="services.php">Services</a>

                    <a href="projects.php">Projects</a>

                    <a href="contact.php">Contact</a>

                </div>

            </div>

        </div>

    </div>

</footer>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


</body>
</html>