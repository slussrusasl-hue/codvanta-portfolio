
<?php
$pageTitle = "ProServe — CRM & Service Management";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <meta name="description"
          content="Explore ProServe, a customer relationship management and service management platform developed by Codvanta.">

    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10213d;
            --muted: #64748b;
            --background: #f5f8ff;
            --border: #e2e8f0;
            --white: #ffffff;
            --success: #16a34a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Trebuchet MS", Arial, sans-serif;
            background: var(--background);
            color: var(--navy);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
        }

        .nav-container {
            max-width: 1200px;
            min-height: 76px;
            margin: auto;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--navy);
            font-size: 23px;
            font-weight: 800;
            letter-spacing: -0.7px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            border-radius: 13px;
            color: white;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            font-size: 23px;
            box-shadow: 0 7px 18px rgba(37, 99, 235, 0.2);
        }

        .brand small {
            display: block;
            color: var(--muted);
            font-size: 10px;
            letter-spacing: 2px;
            font-weight: 700;
            line-height: 1.2;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 26px;
        }

        .nav-links a {
            color: #475569;
            font-size: 14px;
            font-weight: 700;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links .back-link {
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--navy);
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 86px 24px 76px;
            background:
                radial-gradient(
                    circle at 85% 15%,
                    rgba(96, 165, 250, 0.18),
                    transparent 30%
                ),
                linear-gradient(135deg, #eff6ff, #ffffff 58%, #eaf2ff);
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 320px;
            height: 320px;
            border: 1px solid rgba(37, 99, 235, 0.10);
            border-radius: 50%;
            right: -130px;
            bottom: -170px;
        }

        .hero-container {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            align-items: center;
            gap: 60px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 13px;
            border-radius: 30px;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .eyebrow-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
        }

        .hero h1 {
            margin: 22px 0 18px;
            max-width: 680px;
            font-size: clamp(40px, 5vw, 62px);
            line-height: 1.1;
            letter-spacing: -2px;
            font-weight: 800;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero-description {
            max-width: 580px;
            margin-bottom: 26px;
            color: var(--muted);
            font-size: 17px;
            line-height: 1.9;
        }

        .hero-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .hero-tags span {
            padding: 8px 12px;
            border: 1px solid var(--border);
            background: white;
            border-radius: 8px;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
        }

        .hero-visual {
            padding: 25px;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid #dbeafe;
            border-radius: 24px;
            box-shadow: 0 25px 65px rgba(30, 64, 175, 0.10);
        }

        .visual-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 22px;
        }

        .visual-heading h3 {
            font-size: 17px;
        }

        .live-label {
            color: #15803d;
            background: #dcfce7;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .stat-card {
            padding: 18px;
            border: 1px solid #e5edf9;
            border-radius: 15px;
            background: #f8fbff;
        }

        .stat-icon {
            display: grid;
            place-items: center;
            width: 37px;
            height: 37px;
            margin-bottom: 13px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 11px;
            font-size: 18px;
        }

        .stat-card strong {
            display: block;
            font-size: 25px;
            line-height: 1.2;
        }

        .stat-card p {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .visual-note {
            margin-top: 15px;
            padding: 13px;
            background: #eff6ff;
            border-radius: 12px;
            color: #1e40af;
            font-size: 12px;
        }

        .section {
            padding: 76px 24px;
        }

        .section-container {
            max-width: 1100px;
            margin: auto;
        }

        .section-heading {
            max-width: 700px;
            margin: 0 auto 42px;
            text-align: center;
        }

        .section-heading .subtitle {
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .section-heading h2 {
            margin: 12px 0;
            font-size: clamp(29px, 4vw, 40px);
            letter-spacing: -1px;
            line-height: 1.2;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 15px;
        }

        .role-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 25px;
        }

        .role-card {
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 32px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.035);
            transition: transform 0.25s, box-shadow 0.25s,
                        border-color 0.25s;
        }

        .role-card:hover {
            transform: translateY(-5px);
            border-color: #93c5fd;
            box-shadow: 0 20px 45px rgba(37, 99, 235, 0.10);
        }

        .role-icon {
            display: grid;
            place-items: center;
            width: 62px;
            height: 62px;
            margin-bottom: 22px;
            border-radius: 17px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 29px;
        }

        .role-card.admin .role-icon {
            background: #ede9fe;
            color: #6d28d9;
        }

        .role-card h3 {
            margin-bottom: 10px;
            font-size: 25px;
        }

        .role-card > p {
            margin-bottom: 23px;
            color: var(--muted);
            font-size: 14px;
        }

        .feature-list {
            list-style: none;
            display: grid;
            gap: 12px;
            margin-bottom: 28px;
        }

        .feature-list li {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            color: #334155;
            font-size: 13px;
        }

        .check {
            display: grid;
            place-items: center;
            flex: 0 0 20px;
            width: 20px;
            height: 20px;
            background: #dcfce7;
            color: #15803d;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 900;
        }

        .role-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            margin-top: auto;
            padding: 15px 18px;
            background: var(--primary);
            color: white;
            border: 1px solid var(--primary);
            border-radius: 11px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 800;
            transition: background 0.2s;
        }

        .role-button:hover {
            background: var(--primary-dark);
        }

        .role-card.admin .role-button {
            background: #6d28d9;
            border-color: #6d28d9;
        }

        .role-card.admin .role-button:hover {
            background: #5b21b6;
        }

        .workflow-section {
            background: #ffffff;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .workflow-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 13px;
        }

        .workflow-step {
            position: relative;
            padding: 23px 12px;
            text-align: center;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: #f8fbff;
        }

        .workflow-number {
            display: grid;
            place-items: center;
            width: 35px;
            height: 35px;
            margin: 0 auto 13px;
            border-radius: 11px;
            background: #dbeafe;
            color: var(--primary-dark);
            font-weight: 900;
        }

        .workflow-step h4 {
            margin-bottom: 7px;
            font-size: 14px;
        }

        .workflow-step p {
            color: var(--muted);
            font-size: 11px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .feature-card {
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 17px;
            background: white;
        }

        .feature-card .feature-symbol {
            font-size: 26px;
        }

        .feature-card h3 {
            margin: 13px 0 8px;
            font-size: 17px;
        }

        .feature-card p {
            color: var(--muted);
            font-size: 13px;
        }

        .cta {
            padding: 65px 24px;
            background: linear-gradient(120deg, #1d4ed8, #2563eb);
            color: white;
            text-align: center;
        }

        .cta h2 {
            margin-bottom: 12px;
            font-size: clamp(27px, 4vw, 38px);
        }

        .cta p {
            max-width: 620px;
            margin: 0 auto 25px;
            color: #dbeafe;
            font-size: 14px;
        }

        .cta a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 23px;
            background: white;
            color: #1d4ed8;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
        }

        .footer {
            padding: 27px 24px;
            background: #0f1f38;
            color: #cbd5e1;
        }

        .footer-container {
            max-width: 1200px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .footer strong {
            color: white;
        }

        .footer p,
        .footer a {
            font-size: 12px;
        }

        .footer a {
            color: #bfdbfe;
        }

        @media (max-width: 900px) {
            .hero-container {
                grid-template-columns: 1fr;
                gap: 38px;
            }

            .hero {
                padding-top: 60px;
            }

            .hero-visual {
                max-width: 650px;
            }

            .workflow-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .features-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .nav-container {
                padding: 12px 17px;
                min-height: 67px;
            }

            .brand {
                font-size: 19px;
            }

            .brand-icon {
                width: 38px;
                height: 38px;
            }

            .nav-links {
                gap: 10px;
            }

            .nav-links a:not(.back-link) {
                display: none;
            }

            .nav-links .back-link {
                padding: 8px 10px;
                font-size: 11px;
            }

            .hero {
                padding: 50px 18px;
            }

            .hero h1 {
                font-size: 39px;
                letter-spacing: -1.5px;
            }

            .hero-description {
                font-size: 15px;
            }

            .hero-visual {
                padding: 17px;
                border-radius: 18px;
            }

            .stat-card {
                padding: 13px;
            }

            .stat-card strong {
                font-size: 22px;
            }

            .section {
                padding: 55px 18px;
            }

            .role-grid,
            .features-grid {
                grid-template-columns: 1fr;
            }

            .role-card {
                padding: 24px;
            }

            .workflow-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .footer-container {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <header class="navbar">
        <div class="nav-container">

            <a href="proserve_demo.php" class="brand">
                <span class="brand-icon">&#8644;</span>
                <span>
                    ProServe
                    <small>CRM & SERVICE MANAGEMENT</small>
                </span>
            </a>

            <nav class="nav-links">
                <a href="#features">Features</a>
                <a href="#workflow">Workflow</a>
                <a href="#demo">Explore Demo</a>
                <a href="index.php" class="back-link">
                    &larr; Codvanta
                </a>
            </nav>

        </div>
    </header>


    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">

            <div class="hero-content">

                <div class="eyebrow">
                    <span class="eyebrow-dot"></span>
                    BUSINESS MANAGEMENT SOLUTION
                </div>

                <h1>
                    Manage Every
                    <span>Customer.</span>
                    Deliver Every
                    <span>Service.</span>
                </h1>

                <p class="hero-description">
                    ProServe is a centralized CRM and service management
                    platform designed to help service-based businesses
                    manage customer relationships, track sales leads,
                    schedule appointments, prepare quotations, and
                    generate invoices with ease.
                </p>

                <div class="hero-tags">
                    <span>Customer Management</span>
                    <span>Sales Pipeline</span>
                    <span>Appointments</span>
                    <span>PDF Invoices</span>
                </div>

            </div>


            <!-- Illustrative Dashboard Preview -->
            <div class="hero-visual">

                <div class="visual-heading">
                    <h3>Business Overview</h3>
                    <span class="live-label">DEMO PREVIEW</span>
                </div>

                <div class="stat-grid">

                    <div class="stat-card">
                        <div class="stat-icon">&#128101;</div>
                        <strong>Customers</strong>
                        <p>Customer relationship records</p>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">&#128200;</div>
                        <strong>Leads</strong>
                        <p>Sales pipeline management</p>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">&#128197;</div>
                        <strong>Bookings</strong>
                        <p>Appointment scheduling</p>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">&#128196;</div>
                        <strong>Invoices</strong>
                        <p>Billing and payment tracking</p>
                    </div>

                </div>

                <div class="visual-note">
                    <strong>One connected workflow</strong><br>
                    From the first customer enquiry to service delivery
                    and final invoicing.
                </div>

            </div>

        </div>
    </section>


    <!-- Role Selection -->
    <section class="section" id="demo">

        <div class="section-container">

            <div class="section-heading">
                <span class="subtitle">Interactive Demonstration</span>
                <h2>Choose Your Experience</h2>
                <p>
                    Explore ProServe from the perspective of a customer
                    or a business administrator. Select a portal below
                    to discover its features and workflows.
                </p>
            </div>


            <div class="role-grid">

                <!-- Customer Portal -->
                <article class="role-card">

                    <div class="role-icon">&#128100;</div>

                    <h3>Customer Portal</h3>

                    <p>
                        Explore how customers discover services, submit
                        project requirements, request appointments, and
                        manage their quotations and invoices.
                    </p>

                    <ul class="feature-list">
                        <li>
                            <span class="check">&#10003;</span>
                            Browse available business services
                        </li>

                        <li>
                            <span class="check">&#10003;</span>
                            Register and access a customer dashboard
                        </li>

                        <li>
                            <span class="check">&#10003;</span>
                            Request quotations and consultations
                        </li>

                        <li>
                            <span class="check">&#10003;</span>
                            View appointment and request statuses
                        </li>

                        <li>
                            <span class="check">&#10003;</span>
                            Access quotations and PDF invoices
                        </li>
                    </ul>

                    <a href="./projects/proserve/index.php" class="role-button">
                        Explore Customer Portal
                        <span>&rarr;</span>
                    </a>

                </article>


                <!-- Administrator Portal -->
                <article class="role-card admin">

                    <div class="role-icon">&#9881;</div>

                    <h3>Administrator Portal</h3>

                    <p>
                        Explore the business management interface for
                        organizing customers, converting leads, managing
                        appointments, and monitoring business performance.
                    </p>

                    <ul class="feature-list">
                        <li>
                            <span class="check">&#10003;</span>
                            View dashboard statistics and activity
                        </li>

                        <li>
                            <span class="check">&#10003;</span>
                            Search and manage customer records
                        </li>

                        <li>
                            <span class="check">&#10003;</span>
                            Track leads through the sales pipeline
                        </li>

                        <li>
                            <span class="check">&#10003;</span>
                            Accept and manage service appointments
                        </li>

                        <li>
                            <span class="check">&#10003;</span>
                            Create quotations and generate invoices
                        </li>
                    </ul>

                    <a href="./projects/proserve/admin/index.php" class="role-button">
                        Explore Admin Dashboard
                        <span>&rarr;</span>
                    </a>

                </article>

            </div>

        </div>

    </section>


    <!-- Lead Workflow -->
    <section class="section workflow-section" id="workflow">

        <div class="section-container">

            <div class="section-heading">
                <span class="subtitle">Sales Pipeline</span>
                <h2>From New Lead to Successful Deal</h2>
                <p>
                    ProServe helps administrators follow every potential
                    customer through a structured sales process.
                </p>
            </div>

            <div class="workflow-grid">

                <div class="workflow-step">
                    <div class="workflow-number">01</div>
                    <h4>New Lead</h4>
                    <p>Record a potential customer's enquiry.</p>
                </div>

                <div class="workflow-step">
                    <div class="workflow-number">02</div>
                    <h4>Contacted</h4>
                    <p>Follow up and understand requirements.</p>
                </div>

                <div class="workflow-step">
                    <div class="workflow-number">03</div>
                    <h4>Quotation</h4>
                    <p>Prepare a proposed service price.</p>
                </div>

                <div class="workflow-step">
                    <div class="workflow-number">04</div>
                    <h4>Negotiation</h4>
                    <p>Discuss price and service terms.</p>
                </div>

                <div class="workflow-step">
                    <div class="workflow-number">05</div>
                    <h4>Won</h4>
                    <p>Confirm the successful business deal.</p>
                </div>

            </div>

        </div>

    </section>


    <!-- Features -->
    <section class="section" id="features">

        <div class="section-container">

            <div class="section-heading">
                <span class="subtitle">Core Capabilities</span>
                <h2>Everything in One Platform</h2>
                <p>
                    A demonstration of the essential processes involved
                    in managing a modern service-based business.
                </p>
            </div>


            <div class="features-grid">

                <article class="feature-card">
                    <div class="feature-symbol">&#128101;</div>
                    <h3>Customer Records</h3>
                    <p>
                        Maintain contact details, customer requirements,
                        enquiries, and service history in one place.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-symbol">&#128200;</div>
                    <h3>Lead Management</h3>
                    <p>
                        Track sales opportunities through new lead,
                        contacted, quotation, negotiation, and won.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-symbol">&#128197;</div>
                    <h3>Appointments</h3>
                    <p>
                        Collect preferred service dates and times and
                        allow administrators to manage requests.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-symbol">&#128221;</div>
                    <h3>Quotations</h3>
                    <p>
                        Prepare service estimates, communicate proposed
                        prices, and track customer decisions.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-symbol">&#128196;</div>
                    <h3>Invoice Generation</h3>
                    <p>
                        Produce professional invoices and demonstrate
                        printable or downloadable PDF documents.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-symbol">&#128202;</div>
                    <h3>Business Reports</h3>
                    <p>
                        Review lead conversion, appointment activity,
                        invoiced amounts, and outstanding balances.
                    </p>
                </article>

            </div>

        </div>

    </section>


    <!-- Call to Action -->
    <section class="cta">

        <h2>Ready to Explore ProServe?</h2>

        <p>
            Discover how customer relationship management, appointment
            scheduling, quotations, and invoicing can work together
            in a single business application.
        </p>

        <a href="#demo">Choose a Demonstration Portal &rarr;</a>

    </section>


    <!-- Footer -->
    <footer class="footer">

        <div class="footer-container">

            <p>
                &copy; <?php echo date("Y"); ?>
                <strong>Codvanta</strong>.
                Turning Vision into Digital Reality.
            </p>

            <p>
                ProServe CRM & Service Management
                <span> | </span>
                <a href="index.php">Back to Codvanta</a>
            </p>

        </div>

    </footer>

</body>
</html>
