<?php
// Codvanta - Home Page
// Replace the logo placeholder with your actual logo when available.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Codvanta builds modern websites and custom software solutions that turn business ideas into digital reality.">
    <title>Codvanta | Turning Vision into Digital Reality</title>

    <!-- Use your local Bootstrap files -->
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg fixed-top codvanta-navbar" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <!-- Replace this with your real logo -->
                <img src="./projects/images/Codvanta_logo.png" width="23px" height="35px">
                <span class="brand-name">Codvanta</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContent" aria-controls="navbarContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./projects.php">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#technologies">Technologies</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-nav" href="./contact.php">Let's Talk</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ================= HERO ================= -->
    <main>
        <section class="hero-section" id="home">
            <div class="hero-grid"></div>
            <div class="hero-glow hero-glow-one"></div>
            <div class="hero-glow hero-glow-two"></div>

            <div class="container position-relative">
                <div class="row align-items-center min-vh-100 pt-5">
                    <div class="col-lg-7">
                        <div class="hero-badge">
                            <span class="status-dot"></span>
                            Web &amp; Software Development
                        </div>

                        <h1 class="hero-title">
                            Turning <span>Vision</span><br>
                            into Digital Reality.
                        </h1>

                        <p class="hero-text">
                            We design and develop modern websites and custom software
                            solutions that help businesses work smarter, serve customers
                            better, and grow digitally.
                        </p>

                        <div class="hero-actions">
                            <a href="#projects" class="btn btn-primary-codvanta">
                                Explore Our Work
                                <span class="arrow">→</span>
                            </a>
                            <a href="#contact" class="btn btn-outline-codvanta">
                                Start a Project
                            </a>
                        </div>

                        <div class="hero-trust">
                            <div class="trust-item">
                                <strong>Custom</strong>
                                <span>Business Solutions</span>
                            </div>
                            <div class="trust-divider"></div>
                            <div class="trust-item">
                                <strong>Modern</strong>
                                <span>Web Experiences</span>
                            </div>
                            <div class="trust-divider"></div>
                            <div class="trust-item">
                                <strong>Scalable</strong>
                                <span>Software Systems</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 d-none d-lg-block">
                        <div class="hero-visual">
                            <div class="visual-orbit orbit-one"></div>
                            <div class="visual-orbit orbit-two"></div>

                            <div class="code-card">
                                <div class="code-card-top">
                                    <div class="window-dots">
                                        <span></span><span></span><span></span>
                                    </div>
                                    <span class="code-label">CODVANTA / SOLUTIONS</span>
                                </div>

                                <div class="code-content">
                                    <div class="code-line"><span class="code-num">01</span><span class="purple">const</span> vision = <span class="blue">businessIdea</span>;</div>
                                    <div class="code-line"><span class="code-num">02</span><span class="purple">const</span> solution = <span class="green">build</span>(vision);</div>
                                    <div class="code-line"><span class="code-num">03</span><span class="purple">return</span> solution.<span class="blue">grow</span>();</div>
                                </div>

                                <div class="solution-status">
                                    <span class="status-check">✓</span>
                                    <div>
                                        <small>PROJECT STATUS</small>
                                        <strong>Building Digital Solutions</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="floating-card floating-card-one">
                                <div class="mini-icon">⌁</div>
                                <div>
                                    <small>Custom Software</small>
                                    <strong>Business Systems</strong>
                                </div>
                            </div>

                            <div class="floating-card floating-card-two">
                                <div class="mini-icon">↗</div>
                                <div>
                                    <small>Digital Growth</small>
                                    <strong>Built to Scale</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="scroll-indicator">
                <span>Scroll to explore</span>
                <span class="scroll-line"></span>
            </div>
        </section>

        <!-- ================= INTRO ================= -->
        <section class="intro-section section-padding" id="about">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <span class="section-tag">WHO WE ARE</span>
                        <h2 class="section-title">
                            Technology built around
                            <span>your business.</span>
                        </h2>
                    </div>

                    <div class="col-lg-6">
                        <p class="section-lead">
                            Codvanta is a web and software development brand focused on
                            creating practical digital solutions for real-world businesses.
                        </p>
                        <p class="section-copy">
                            From customer-facing websites to complete management systems,
                            we combine clean interfaces, reliable backend systems and
                            thoughtful business logic to create software that is useful,
                            maintainable and ready to grow.
                        </p>
                        <a href="#projects" class="text-link">
                            Discover our projects <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= SERVICES ================= -->
        <section class="services-section section-padding" id="services">
            <div class="container">
                <div class="section-heading-row">
                    <div>
                        <span class="section-tag">WHAT WE BUILD</span>
                        <h2 class="section-title mb-0">Solutions for modern businesses.</h2>
                    </div>
                    <p class="section-heading-copy">
                        From a focused business website to a complete custom application,
                        we build around the problem that needs to be solved.
                    </p>
                </div>

                <div class="row g-4 mt-4">
                    <div class="col-md-6 col-xl-3">
                        <article class="service-card">
                            <div class="service-number">01</div>
                            <div class="service-icon">⌘</div>
                            <h3>Web Development</h3>
                            <p>Responsive, professional websites designed around your brand and customers.</p>
                            <a href="#contact">Explore service →</a>
                        </article>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <article class="service-card featured-service">
                            <div class="service-number">02</div>
                            <div class="service-icon">▦</div>
                            <h3>Custom Software</h3>
                            <p>Business systems that automate workflows and bring your operations together.</p>
                            <a href="#contact">Explore service →</a>
                        </article>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <article class="service-card">
                            <div class="service-number">03</div>
                            <div class="service-icon">◇</div>
                            <h3>E-Commerce</h3>
                            <p>Online stores with product management, ordering, customer and sales workflows.</p>
                            <a href="#contact">Explore service →</a>
                        </article>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <article class="service-card">
                            <div class="service-number">04</div>
                            <div class="service-icon">◫</div>
                            <h3>Business Systems</h3>
                            <p>Dashboards, booking systems, CRM platforms, inventory and reporting solutions.</p>
                            <a href="#contact">Explore service →</a>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= PROJECTS ================= -->
        <section class="projects-section section-padding" id="projects">
            <div class="container">
                <div class="section-heading-row">
                    <div>
                        <span class="section-tag">SELECTED WORK</span>
                        <h2 class="section-title mb-0">Built for real-world use.</h2>
                    </div>
                    <a href="projects.php" class="text-link desktop-project-link">
                        View all projects <span>→</span>
                    </a>
                </div>

                <div class="row g-4 mt-4">
                    <div class="col-lg-7">
                        <a href="projects/ecims/showcase.php" class="project-card project-large">
                            <div class="project-overlay"></div>
                            <div class="project-placeholder ecims-bg">
                                <img src="./projects/images/main_img/background_2.avif" width="750px" height="480px">
                                <span class="project-symbol">+</span>
                                <span class="project-ui-label">HEALTHCARE PLATFORM</span>
                            </div>
                            <div class="project-content">
                                <div>
                                    <span class="project-category">Healthcare Management</span>
                                    <h3>ECIMS</h3>
                                    <p>E-Channeling &amp; Inventory Management System with Video Consultation.</p>
                                </div>
                                <span class="project-arrow">↗</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-5">
                        <a href="projects/restaurant/showcase.php" class="project-card">
                            <div class="project-placeholder restaurant-bg">
                                <img src="./assets/img/close-up-delicious-burger-meal.jpg" width="530px" height="360px">
                                <span class="project-symbol">◈</span>
                                <span class="project-ui-label">RESTAURANT PLATFORM</span>
                            </div>
                            <div class="project-content">
                                <div>
                                    <span class="project-category">Food &amp; Hospitality</span>
                                    <h3>UrbanBite</h3>
                                    <p>Online ordering &amp; restaurant management.</p>
                                </div>
                                <span class="project-arrow">↗</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-5">
                        <a href="projects/hotel/showcase.php" class="project-card">
                            <div class="project-placeholder hotel-bg">
                                <span class="project-symbol">◇</span>
                                <span class="project-ui-label">HOSPITALITY PLATFORM</span>
                            </div>
                            <div class="project-content">
                                <div>
                                    <span class="project-category">Hotel &amp; Resort</span>
                                    <h3>Azure Bay Resort</h3>
                                    <p>Room discovery and reservation platform.</p>
                                </div>
                                <span class="project-arrow">↗</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-7">
                        <a href="projects/ecommerce/showcase.php" class="project-card">
                            <div class="project-placeholder ecommerce-bg">
                                <span class="project-symbol">□</span>
                                <span class="project-ui-label">E-COMMERCE PLATFORM</span>
                            </div>
                            <div class="project-content">
                                <div>
                                    <span class="project-category">Retail &amp; Commerce</span>
                                    <h3>NovaMart</h3>
                                    <p>Modern shopping and inventory platform.</p>
                                </div>
                                <span class="project-arrow">↗</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="mobile-project-link">
                    <a href="projects.php" class="btn btn-outline-codvanta">View All Projects</a>
                </div>
            </div>
        </section>

        <!-- ================= TECHNOLOGIES ================= -->
        <section class="tech-section section-padding" id="technologies">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-5">
                        <span class="section-tag">TECHNOLOGY</span>
                        <h2 class="section-title">
                            The right tools for
                            <span>the right solution.</span>
                        </h2>
                        <p class="section-copy">
                            Our technology choices are driven by project requirements,
                            reliability and long-term maintainability.
                        </p>
                    </div>

                    <div class="col-lg-7">
                        <div class="tech-grid">
                            <div class="tech-item"><strong>PHP</strong><span>Backend</span></div>
                            <div class="tech-item"><strong>MySQL</strong><span>Database</span></div>
                            <div class="tech-item"><strong>JavaScript</strong><span>Frontend</span></div>
                            <div class="tech-item"><strong>Bootstrap</strong><span>UI Framework</span></div>
                            <div class="tech-item"><strong>HTML5</strong><span>Structure</span></div>
                            <div class="tech-item"><strong>CSS3</strong><span>Design</span></div>
                            <div class="tech-item"><strong>Git</strong><span>Version Control</span></div>
                            <div class="tech-item"><strong>REST</strong><span>APIs</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= CTA ================= -->
        <section class="cta-section" id="contact">
            <div class="container">
                <div class="cta-box">
                    <div>
                        <span class="section-tag">START SOMETHING NEW</span>
                        <h2>Have an idea? Let's turn it into reality.</h2>
                        <p>
                            Tell us what you want to build. We can transform your
                            business requirements into a practical digital solution.
                        </p>
                    </div>
                    <a href="contact.php" class="btn btn-primary-codvanta">
                        Start a Project <span class="arrow">→</span>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <a href="index.php" class="footer-brand">Codvanta</a>
                    <p>
                        Turning Vision into Digital Reality.
                    </p>
                </div>

                <div class="col-6 col-lg-2">
                    <h5>Explore</h5>
                    <a href="#about">About</a>
                    <a href="#services">Services</a>
                    <a href="#projects">Projects</a>
                </div>

                <div class="col-6 col-lg-2">
                    <h5>Connect</h5>
                    <a href="#contact">Contact</a>
                    <a href="#" target="_blank">LinkedIn</a>
                    <a href="#" target="_blank">GitHub</a>
                </div>

                <div class="col-lg-3">
                    <h5>Let's build</h5>
                    <p class="footer-small">
                        Have a business idea or software requirement?
                    </p>
                    <a href="contact.php" class="footer-email">Start a conversation →</a>
                </div>
            </div>

            <div class="footer-bottom">
                <span>© <?php echo date('Y'); ?> Codvanta. All rights reserved.</span>
                <span>Web &amp; Software Development</span>
            </div>
        </div>
    </footer>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
