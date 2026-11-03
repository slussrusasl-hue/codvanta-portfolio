
<?php
session_start();

$pageTitle = "Contact Us | ProServe";

$errors = [];
$submitted = false;
$referenceNumber = "";

$fullName = "";
$email = "";
$phone = "";
$company = "";
$service = "";
$subject = "";
$message = "";
$preferredContact = "email";

$serviceOptions = [
    "Website Development",
    "Software Development",
    "IT Support & Maintenance",
    "Digital Marketing",
    "Graphic & Brand Design",
    "Commercial Cleaning",
    "Repair & Maintenance",
    "Electrical Services",
    "Plumbing Services",
    "Business Consulting",
    "Construction & Renovation",
    "Security & Systems Consultation",
    "Other / General Enquiry"
];

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $company = trim($_POST["company"] ?? "");
    $service = trim($_POST["service"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");
    $preferredContact = $_POST["preferred_contact"] ?? "email";

    // Validate the contact form.
    if ($fullName === "") {
        $errors[] = "Please enter your full name.";
    } elseif (mb_strlen($fullName) < 2 || mb_strlen($fullName) > 100) {
        $errors[] = "Your name must contain between 2 and 100 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($phone !== "" && !preg_match('/^[0-9+\-\s()]{7,25}$/', $phone)) {
        $errors[] = "Please enter a valid contact number.";
    }

    if ($company !== "" && mb_strlen($company) > 150) {
        $errors[] = "The company name must not exceed 150 characters.";
    }

    if (!in_array($service, $serviceOptions, true)) {
        $errors[] = "Please select a valid service category.";
    }

    if ($subject === "") {
        $errors[] = "Please enter an enquiry subject.";
    } elseif (mb_strlen($subject) > 150) {
        $errors[] = "The subject must not exceed 150 characters.";
    }

    if ($message === "") {
        $errors[] = "Please enter your enquiry message.";
    } elseif (mb_strlen($message) < 10 || mb_strlen($message) > 3000) {
        $errors[] = "Your message must contain between 10 and 3000 characters.";
    }

    if (!in_array($preferredContact, ["email", "phone"], true)) {
        $errors[] = "Please select a valid preferred contact method.";
    }

    if ($preferredContact === "phone" && $phone === "") {
        $errors[] = "Please provide a phone number if you prefer a phone response.";
    }

    if (empty($errors)) {
        /*
         * DEMO MODE
         * This enquiry is stored in the current PHP session only.
         * It is not saved to a database or sent by email.
         */

        if (!isset($_SESSION["proserve_enquiries"])) {
            $_SESSION["proserve_enquiries"] = [];
        }

        $referenceNumber = "PS-" . date("ymd") . "-" .
            strtoupper(bin2hex(random_bytes(3)));

        $enquiry = [
            "reference" => $referenceNumber,
            "full_name" => $fullName,
            "email" => $email,
            "phone" => $phone,
            "company" => $company,
            "service" => $service,
            "subject" => $subject,
            "message" => $message,
            "preferred_contact" => $preferredContact,
            "status" => "NEW LEAD",
            "created_at" => date("Y-m-d H:i:s")
        ];

        $_SESSION["proserve_enquiries"][] = $enquiry;

        $submitted = true;
    }
}

// Prefill the service field when arriving from services.php.
if ($_SERVER["REQUEST_METHOD"] !== "POST" && isset($_GET["service"])) {
    $requestedService = trim($_GET["service"]);

    if (in_array($requestedService, $serviceOptions, true)) {
        $service = $requestedService;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($pageTitle) ?></title>

    <meta name="description"
          content="Contact ProServe for service enquiries, quotations, appointments, and business support.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #102344;
            --text: #26354d;
            --muted: #718096;
            --background: #f4f7fc;
            --white: #ffffff;
            --border: #e1e8f2;
            --success: #15803d;
            --danger: #dc2626;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Inter", "Trebuchet MS", sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        input,
        select,
        textarea,
        button {
            font: inherit;
        }

        .container {
            width: min(1160px, 92%);
            margin: 0 auto;
        }

        /* Navigation */

        .navbar {
            background: rgba(255, 255, 255, .97);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
        }

        .nav-inner {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 22px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--navy);
            font-size: 23px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            color: white;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            font-size: 21px;
        }

        .brand small {
            display: block;
            color: var(--muted);
            font-size: 9px;
            letter-spacing: 1.5px;
            margin-top: -3px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-links a {
            color: #53627a;
            font-size: 13px;
            font-weight: 600;
            transition: color .2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 17px;
            border-radius: 9px;
            border: 1px solid transparent;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: white;
            border-color: #d9e2f1;
            color: var(--navy);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Hero */

        .hero {
            padding: 68px 0 65px;
            color: white;
            background:
                radial-gradient(circle at 85% 20%, rgba(96,165,250,.2), transparent 28%),
                linear-gradient(125deg, #102344, #183d77 65%, #2563eb);
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            max-width: 730px;
            position: relative;
            z-index: 1;
        }

        .eyebrow {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 30px;
            color: #bfdbfe;
            background: rgba(255,255,255,.09);
            border: 1px solid rgba(255,255,255,.15);
            font-size: 10px;
            letter-spacing: 1.2px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-size: clamp(34px, 5vw, 51px);
            line-height: 1.15;
            letter-spacing: -1.8px;
            margin-bottom: 18px;
        }

        .hero h1 span {
            color: #93c5fd;
        }

        .hero p {
            color: #d7e5fb;
            max-width: 620px;
            font-size: 14px;
            line-height: 1.9;
        }

        /* Main layout */

        .contact-section {
            padding: 65px 0 80px;
        }

        .section-heading {
            text-align: center;
            max-width: 690px;
            margin: 0 auto 38px;
        }

        .section-label {
            color: var(--primary);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .section-heading h2 {
            color: var(--navy);
            font-size: clamp(26px, 4vw, 35px);
            line-height: 1.3;
            letter-spacing: -.8px;
            margin-bottom: 12px;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.9;
        }

        .contact-layout {
            display: grid;
            grid-template-columns: .82fr 1.18fr;
            gap: 25px;
            align-items: start;
        }

        /* Contact information */

        .contact-info {
            display: grid;
            gap: 17px;
        }

        .info-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 23px;
            box-shadow: 0 5px 20px rgba(27,52,95,.025);
        }

        .info-card h3 {
            color: var(--navy);
            font-size: 16px;
            margin-bottom: 9px;
        }

        .info-card > p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            margin-top: 18px;
        }

        .contact-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border-radius: 11px;
            background: #eff6ff;
            color: var(--primary);
            font-size: 19px;
        }

        .contact-item h4 {
            color: var(--navy);
            font-size: 12px;
            margin-bottom: 4px;
        }

        .contact-item p,
        .contact-item a {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .contact-item a:hover {
            color: var(--primary);
        }

        .hours-box {
            margin-top: 18px;
            padding-top: 17px;
            border-top: 1px solid var(--border);
        }

        .hours-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 10px;
            font-size: 11px;
        }

        .hours-row span:first-child {
            color: var(--muted);
        }

        .hours-row span:last-child {
            color: var(--navy);
            font-weight: 700;
            text-align: right;
        }

        .help-card {
            color: white;
            border-radius: 14px;
            padding: 24px;
            background: linear-gradient(135deg, #173967, #2563eb);
        }

        .help-card h3 {
            font-size: 17px;
            margin-bottom: 9px;
        }

        .help-card p {
            color: #d7e5fb;
            font-size: 12px;
            line-height: 1.8;
            margin-bottom: 18px;
        }

        .help-card .btn {
            background: white;
            color: var(--primary-dark);
        }

        .help-card .btn:hover {
            background: #eff6ff;
        }

        /* Contact form */

        .contact-form-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 8px 28px rgba(27,52,95,.035);
        }

        .form-heading {
            margin-bottom: 24px;
        }

        .form-heading h3 {
            color: var(--navy);
            font-size: 22px;
            letter-spacing: -.5px;
            margin-bottom: 7px;
        }

        .form-heading p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .demo-notice {
            padding: 12px 14px;
            border: 1px solid #dbeafe;
            border-radius: 9px;
            background: #eff6ff;
            color: #1e40af;
            font-size: 11px;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 12px;
            line-height: 1.7;
        }

        .alert-error {
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .alert-success {
            color: #166534;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .alert ul {
            padding-left: 18px;
            margin-top: 6px;
        }

        .alert li + li {
            margin-top: 4px;
        }

        .success-panel {
            text-align: center;
            padding: 12px 0;
        }

        .success-icon {
            width: 76px;
            height: 76px;
            margin: 0 auto 18px;
            display: grid;
            place-items: center;
            background: #dcfce7;
            color: #15803d;
            border-radius: 50%;
            font-size: 34px;
        }

        .success-panel h3 {
            color: var(--navy);
            font-size: 25px;
            margin-bottom: 10px;
        }

        .success-panel > p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.9;
            margin-bottom: 20px;
        }

        .reference-box {
            background: #f8faff;
            border: 1px dashed #b8cdf8;
            border-radius: 11px;
            padding: 17px;
            margin-bottom: 20px;
        }

        .reference-box span {
            display: block;
            color: var(--muted);
            font-size: 10px;
            margin-bottom: 5px;
        }

        .reference-box strong {
            color: var(--primary-dark);
            font-size: 22px;
            letter-spacing: 1px;
            overflow-wrap: anywhere;
        }

        .reference-box p {
            color: var(--muted);
            font-size: 10px;
            margin-top: 8px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-group label {
            display: block;
            color: #34445e;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            min-height: 44px;
            padding: 11px 12px;
            background: white;
            border: 1px solid #dce4f0;
            border-radius: 9px;
            color: var(--text);
            outline: none;
            font-size: 12px;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .form-control::placeholder {
            color: #9aa7ba;
        }

        textarea.form-control {
            min-height: 145px;
            resize: vertical;
        }

        .form-hint {
            color: #8995a8;
            font-size: 10px;
            margin-top: 6px;
        }

        .contact-preference {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 7px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 11px;
            color: #53627a;
        }

        .radio-option input {
            accent-color: var(--primary);
        }

        .submit-btn {
            width: 100%;
            border: none;
            background: var(--primary);
            color: white;
            padding: 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .privacy-note {
            text-align: center;
            color: #8995a8;
            font-size: 10px;
            line-height: 1.8;
            margin-top: 14px;
        }

        /* FAQ */

        .faq-section {
            padding: 70px 0;
            background: white;
            border-top: 1px solid var(--border);
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 32px;
        }

        .faq-card {
            padding: 23px;
            border: 1px solid var(--border);
            border-radius: 13px;
        }

        .faq-card h3 {
            color: var(--navy);
            font-size: 14px;
            margin-bottom: 10px;
        }

        .faq-card p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        /* Footer */

        footer {
            background: #0c1930;
            color: white;
            padding: 45px 0 22px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 35px;
            padding-bottom: 30px;
        }

        .footer-brand {
            color: white;
            margin-bottom: 13px;
        }

        .footer-description {
            max-width: 330px;
            color: #a9b7cd;
            font-size: 12px;
            line-height: 1.9;
        }

        .footer-column h4 {
            font-size: 13px;
            margin-bottom: 15px;
        }

        .footer-column a {
            display: block;
            color: #a9b7cd;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .footer-column a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.12);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            color: #93a4bd;
            font-size: 11px;
        }

        .footer-bottom strong {
            color: #bfdbfe;
        }

        @media (max-width: 950px) {
            .nav-links {
                gap: 14px;
            }

            .contact-layout {
                grid-template-columns: 1fr;
            }

            .contact-info {
                grid-template-columns: 1fr 1fr;
                align-items: start;
            }

            .help-card {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 700px) {
            .nav-links {
                display: none;
            }

            .hero {
                padding: 52px 0;
            }

            .contact-section {
                padding: 45px 0 55px;
            }

            .contact-info {
                grid-template-columns: 1fr;
            }

            .help-card {
                grid-column: auto;
            }

            .contact-form-card {
                padding: 24px 19px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .faq-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 25px;
            }

            .footer-grid > :first-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 450px) {
            .brand {
                font-size: 20px;
            }

            .brand-icon {
                width: 39px;
                height: 39px;
            }

            .nav-actions .btn {
                padding: 9px 10px;
                font-size: 11px;
            }

            .hero h1 {
                font-size: 34px;
            }

            .section-heading h2 {
                font-size: 27px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid > :first-child {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="container nav-inner">
        <a href="index.php" class="brand" aria-label="ProServe homepage">
            <span class="brand-icon">P</span>
            <span>
                ProServe
                <small>SERVICE MANAGEMENT</small>
            </span>
        </a>

        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="services.php">Services</a>
            <a href="contact.php" class="active">Contact</a>
        </nav>

        <div class="nav-actions">
            <a href="login.php" class="btn btn-outline">Sign In</a>
            <a href="register.php" class="btn btn-primary">Get Started</a>
        </div>
    </div>
</header>

<section class="hero">
    <div class="container hero-content">
        <span class="eyebrow">We're here to help</span>

        <h1>
            Let's talk about<br>
            your <span>next project.</span>
        </h1>

        <p>
            Have a question, need a quotation, or want to discuss a service?
            Send us a message and explore how ProServe can organize your
            service enquiries and customer communication.
        </p>
    </div>
</section>

<main>
    <section class="contact-section">
        <div class="container">

            <div class="section-heading">
                <div class="section-label">Get in touch</div>
                <h2>We'd love to hear from you</h2>
                <p>
                    Tell us what you need. Use the contact form to submit a
                    service enquiry and see how a CRM platform can capture
                    potential customer leads.
                </p>
            </div>

            <div class="contact-layout">

                <!-- Contact information -->
                <aside class="contact-info">

                    <div class="info-card">
                        <h3>Contact information</h3>
                        <p>
                            Explore the contact details section of a typical
                            service business website.
                        </p>

                        <div class="contact-item">
                            <span class="contact-icon">✉️</span>
                            <div>
                                <h4>Email us</h4>
                                <p>hello@proserve.demo</p>
                                <p>support@proserve.demo</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <span class="contact-icon">📞</span>
                            <div>
                                <h4>Call us</h4>
                                <p>+1 (555) 010-2020</p>
                                <p>Demo contact number</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <span class="contact-icon">📍</span>
                            <div>
                                <h4>Our office</h4>
                                <p>ProServe Business Centre</p>
                                <p>Sample address for demonstration</p>
                            </div>
                        </div>

                        <div class="hours-box">
                            <h4 style="color:#102344;font-size:12px;">
                                Business hours
                            </h4>

                            <div class="hours-row">
                                <span>Monday – Friday</span>
                                <span>9:00 AM – 5:00 PM</span>
                            </div>

                            <div class="hours-row">
                                <span>Saturday</span>
                                <span>9:00 AM – 1:00 PM</span>
                            </div>

                            <div class="hours-row">
                                <span>Sunday</span>
                                <span>Closed</span>
                            </div>
                        </div>
                    </div>

                    <div class="help-card">
                        <h3>Looking for a specific service?</h3>
                        <p>
                            Browse the service catalogue to learn about
                            available service categories before submitting
                            your enquiry.
                        </p>

                        <a href="services.php" class="btn">
                            Browse Services →
                        </a>
                    </div>

                </aside>

                <!-- Contact form -->
                <section class="contact-form-card">

                    <?php if ($submitted): ?>

                        <div class="success-panel">
                            <div class="success-icon">✓</div>

                            <h3>Enquiry submitted!</h3>

                            <p>
                                Thank you, <?= e($fullName) ?>.
                                Your enquiry has been recorded in this
                                demonstration session.
                            </p>

                            <div class="reference-box">
                                <span>Your enquiry reference number</span>
                                <strong><?= e($referenceNumber) ?></strong>
                                <p>
                                    Keep this reference for demonstration
                                    purposes. No email notification has been sent.
                                </p>
                            </div>

                            <div class="alert alert-success">
                                <strong>CRM lead status: NEW LEAD</strong><br>
                                This submission illustrates how a new enquiry
                                can enter a CRM pipeline for follow-up.
                                The enquiry is stored in the current PHP
                                session only.
                            </div>

                            <a href="services.php" class="btn btn-primary"
                               style="width:100%;padding:14px;">
                                Explore Our Services →
                            </a>

                            <p style="margin-top:17px;margin-bottom:0;">
                                <a href="contact.php"
                                   style="color:#2563eb;font-weight:700;font-size:12px;">
                                    Submit another enquiry
                                </a>
                            </p>
                        </div>

                    <?php else: ?>

                        <div class="form-heading">
                            <h3>Send us an enquiry</h3>
                            <p>
                                Complete the form below. Fields marked with
                                an asterisk are required.
                            </p>
                        </div>

                        <div class="demo-notice">
                            <strong>Portfolio demonstration notice</strong>
                            This form demonstrates the enquiry workflow.
                            Submissions are stored in your current PHP session
                            only; they are not emailed or saved to a database.
                        </div>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-error" role="alert">
                                <strong>Please correct the following:</strong>
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= e($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="contact.php" id="contactForm">

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="full_name">
                                        Full name <span class="required">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="full_name"
                                        name="full_name"
                                        class="form-control"
                                        placeholder="Your full name"
                                        value="<?= e($fullName) ?>"
                                        minlength="2"
                                        maxlength="100"
                                        autocomplete="name"
                                        required
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="email">
                                        Email address <span class="required">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="you@example.com"
                                        value="<?= e($email) ?>"
                                        maxlength="254"
                                        autocomplete="email"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone number</label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        class="form-control"
                                        placeholder="+1 555 010 2020"
                                        value="<?= e($phone) ?>"
                                        maxlength="25"
                                        autocomplete="tel"
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="company">Company name</label>
                                    <input
                                        type="text"
                                        id="company"
                                        name="company"
                                        class="form-control"
                                        placeholder="Your business (optional)"
                                        value="<?= e($company) ?>"
                                        maxlength="150"
                                        autocomplete="organization"
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="service">
                                    Service category <span class="required">*</span>
                                </label>
                                <select
                                    id="service"
                                    name="service"
                                    class="form-control"
                                    required
                                >
                                    <option value="">Choose a service</option>

                                    <?php foreach ($serviceOptions as $option): ?>
                                        <option
                                            value="<?= e($option) ?>"
                                            <?= $service === $option ? "selected" : "" ?>
                                        >
                                            <?= e($option) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subject">
                                    Enquiry subject <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    class="form-control"
                                    placeholder="Briefly describe your enquiry"
                                    value="<?= e($subject) ?>"
                                    maxlength="150"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="message">
                                    Your message <span class="required">*</span>
                                </label>
                                <textarea
                                    id="message"
                                    name="message"
                                    class="form-control"
                                    placeholder="Tell us about your requirements, preferred schedule, or questions..."
                                    minlength="10"
                                    maxlength="3000"
                                    required
                                ><?= e($message) ?></textarea>

                                <div class="form-hint">
                                    <span id="messageCount"><?= mb_strlen($message) ?></span>
                                    / 3000 characters
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Preferred contact method</label>

                                <div class="contact-preference">
                                    <label class="radio-option">
                                        <input
                                            type="radio"
                                            name="preferred_contact"
                                            value="email"
                                            <?= $preferredContact === "email" ? "checked" : "" ?>
                                        >
                                        Email
                                    </label>

                                    <label class="radio-option">
                                        <input
                                            type="radio"
                                            name="preferred_contact"
                                            value="phone"
                                            <?= $preferredContact === "phone" ? "checked" : "" ?>
                                        >
                                        Phone
                                    </label>
                                </div>

                                <p class="form-hint">
                                    A phone number is required if you choose phone contact.
                                </p>
                            </div>

                            <button type="submit" class="submit-btn">
                                Submit Enquiry →
                            </button>

                            <p class="privacy-note">
                                Demonstration only. Do not include passwords,
                                payment details, or other sensitive information.
                            </p>

                        </form>

                    <?php endif; ?>

                </section>

            </div>
        </div>
    </section>

    <section class="faq-section">
        <div class="container">

            <div class="section-heading">
                <div class="section-label">Quick answers</div>
                <h2>Frequently asked questions</h2>
                <p>
                    Learn how the proposed ProServe service enquiry workflow
                    is intended to work.
                </p>
            </div>

            <div class="faq-grid">
                <article class="faq-card">
                    <h3>How do I request a quotation?</h3>
                    <p>
                        Select the service you need and describe your
                        requirements. A service business could review the
                        enquiry and prepare a quotation.
                    </p>
                </article>

                <article class="faq-card">
                    <h3>Can I book an appointment?</h3>
                    <p>
                        Yes, the planned demonstration includes appointment
                        booking. Visit the booking page when it is available
                        in the demo.
                    </p>
                </article>

                <article class="faq-card">
                    <h3>What happens after I submit?</h3>
                    <p>
                        This demo displays a reference number and NEW LEAD
                        status to illustrate how a CRM system could track
                        a new customer enquiry.
                    </p>
                </article>
            </div>

        </div>
    </section>
</main>

<footer>
    <div class="container">
        <div class="footer-grid">

            <div>
                <a href="index.php" class="brand footer-brand">
                    <span class="brand-icon">P</span>
                    <span>
                        ProServe
                        <small>SERVICE MANAGEMENT</small>
                    </span>
                </a>

                <p class="footer-description">
                    A demonstration CRM and service management platform
                    designed to showcase customer enquiries, service
                    requests, appointments, and business workflows.
                </p>
            </div>

            <div class="footer-column">
                <h4>Quick Links</h4>
                <a href="index.php">Home</a>
                <a href="services.php">Our Services</a>
                <a href="contact.php">Contact Us</a>
            </div>

            <div class="footer-column">
                <h4>Customer</h4>
                <a href="register.php">Create Account</a>
                <a href="login.php">Customer Login</a>
                <a href="services.php">Browse Services</a>
            </div>

        </div>

        <div class="footer-bottom">
            <span>
                &copy; <?= date("Y") ?> ProServe. All rights reserved.
            </span>

            <span>
                Portfolio demonstration by
                <a href="../index.php"><strong>Codvanta</strong></a>
            </span>
        </div>
    </div>
</footer>

<script>
    // Live character counter for the message field.
    const messageField = document.getElementById("message");
    const messageCount = document.getElementById("messageCount");

    function updateMessageCount() {
        if (messageField && messageCount) {
            messageCount.textContent = messageField.value.length;
        }
    }

    if (messageField) {
        messageField.addEventListener("input", updateMessageCount);
        updateMessageCount();
    }

    // Require a phone number when phone contact is selected.
    const phoneField = document.getElementById("phone");
    const contactForm = document.getElementById("contactForm");
    const preferredContactInputs =
        document.querySelectorAll('input[name="preferred_contact"]');

    function updatePhoneRequirement() {
        if (!phoneField) return;

        const selected = document.querySelector(
            'input[name="preferred_contact"]:checked'
        );

        const phoneRequired = selected && selected.value === "phone";

        phoneField.required = Boolean(phoneRequired);
        phoneField.placeholder = phoneRequired
            ? "Phone number required"
            : "+1 555 010 2020";
    }

    preferredContactInputs.forEach(function (input) {
        input.addEventListener("change", updatePhoneRequirement);
    });

    updatePhoneRequirement();

    // Basic client-side form validation.
    if (contactForm) {
        contactForm.addEventListener("submit", function (event) {
            if (messageField && messageField.value.trim().length < 10) {
                event.preventDefault();
                alert("Please enter a message containing at least 10 characters.");
                messageField.focus();
                return;
            }

            if (phoneField && phoneField.required &&
                phoneField.value.trim() === "") {
                event.preventDefault();
                alert("Please enter your phone number for phone contact.");
                phoneField.focus();
            }
        });
    }
</script>

</body>
</html>