<?php
session_start();

/*
|--------------------------------------------------------------------------
| ProServe - Request a Quote
|--------------------------------------------------------------------------
| Demonstration only:
| - No database connection
| - No emails are sent
| - Requests are stored in the current PHP session
|--------------------------------------------------------------------------
*/

$services = [
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

$budgets = [
    "Under $500",
    "$500 - $1,000",
    "$1,001 - $5,000",
    "$5,001 - $10,000",
    "$10,001 - $25,000",
    "$25,001 - $50,000",
    "Over $50,000",
    "Not sure yet"
];

$timelines = [
    "As soon as possible",
    "Within 2 weeks",
    "Within 1 month",
    "Within 2-3 months",
    "Within 6 months",
    "Flexible / Not decided"
];

$contactMethods = ["Email", "Phone"];

if (!isset($_SESSION["proserve_quotes"])) {
    $_SESSION["proserve_quotes"] = [];
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function quoteLength($value)
{
    return function_exists("mb_strlen")
        ? mb_strlen($value, "UTF-8")
        : strlen($value);
}

$initialService = trim((string)($_GET["service"] ?? ""));

if (!in_array($initialService, $services, true)) {
    $initialService = "";
}

$form = [
    "full_name" => "",
    "email" => "",
    "phone" => "",
    "company" => "",
    "service" => $initialService,
    "budget" => "",
    "timeline" => "",
    "contact_method" => "Email",
    "project_title" => "",
    "project_details" => ""
];

$errors = [];
$success = false;
$referenceNumber = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    foreach ($form as $key => $value) {
        if (isset($_POST[$key]) && is_string($_POST[$key])) {
            $form[$key] = trim($_POST[$key]);
        }
    }

    // Full name validation
    if (
        quoteLength($form["full_name"]) < 2 ||
        quoteLength($form["full_name"]) > 100
    ) {
        $errors["full_name"] = "Enter a name between 2 and 100 characters.";
    }

    // Email validation
    if (
        !filter_var($form["email"], FILTER_VALIDATE_EMAIL) ||
        quoteLength($form["email"]) > 150
    ) {
        $errors["email"] = "Enter a valid email address.";
    }

    // Phone validation
    if (
        $form["phone"] !== "" &&
        !preg_match('/^[0-9+\-\s().]{7,25}$/', $form["phone"])
    ) {
        $errors["phone"] = "Enter a valid phone number.";
    }

    // Company validation
    if (quoteLength($form["company"]) > 150) {
        $errors["company"] = "Company name cannot exceed 150 characters.";
    }

    // Service validation
    if (!in_array($form["service"], $services, true)) {
        $errors["service"] = "Please select a valid service.";
    }

    // Budget validation
    if (!in_array($form["budget"], $budgets, true)) {
        $errors["budget"] = "Please select an estimated budget.";
    }

    // Timeline validation
    if (!in_array($form["timeline"], $timelines, true)) {
        $errors["timeline"] = "Please select your preferred timeline.";
    }

    // Contact method validation
    if (!in_array($form["contact_method"], $contactMethods, true)) {
        $errors["contact_method"] = "Please select a contact method.";
    }

    if (
        $form["contact_method"] === "Phone" &&
        $form["phone"] === ""
    ) {
        $errors["phone"] = "A phone number is required for phone contact.";
    }

    // Project title validation
    if (
        quoteLength($form["project_title"]) < 3 ||
        quoteLength($form["project_title"]) > 150
    ) {
        $errors["project_title"] =
            "Enter a project title between 3 and 150 characters.";
    }

    // Project details validation
    if (
        quoteLength($form["project_details"]) < 20 ||
        quoteLength($form["project_details"]) > 3000
    ) {
        $errors["project_details"] =
            "Project details must contain between 20 and 3000 characters.";
    }

    // Save demonstration request
    if (empty($errors)) {

        $referenceNumber = "PSQ-" .
            date("ymd") . "-" .
            strtoupper(bin2hex(random_bytes(3)));

        $quoteRequest = [
            "reference" => $referenceNumber,
            "full_name" => $form["full_name"],
            "email" => $form["email"],
            "phone" => $form["phone"],
            "company" => $form["company"],
            "service" => $form["service"],
            "budget" => $form["budget"],
            "timeline" => $form["timeline"],
            "contact_method" => $form["contact_method"],
            "project_title" => $form["project_title"],
            "project_details" => $form["project_details"],
            "status" => "NEW QUOTE REQUEST",
            "submitted_at" => date("Y-m-d H:i:s")
        ];

        $_SESSION["proserve_quotes"][] = $quoteRequest;

        $success = true;

        // Reset the form after successful submission.
        $form = [
            "full_name" => "",
            "email" => "",
            "phone" => "",
            "company" => "",
            "service" => "",
            "budget" => "",
            "timeline" => "",
            "contact_method" => "Email",
            "project_title" => "",
            "project_details" => ""
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Request a Quote | ProServe</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --primary: #1769e0;
            --primary-dark: #1052b8;
            --navy: #10243e;
            --muted: #68778b;
            --light: #f4f8fd;
            --border: #e2eaf4;
            --success: #13865c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Inter", "Trebuchet MS", sans-serif;
            color: var(--navy);
            background: #fff;
            line-height: 1.65;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: rgba(255, 255, 255, .97);
            border-bottom: 1px solid var(--border);
        }

        .navbar-brand {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -.7px;
        }

        .brand-highlight {
            color: var(--primary);
        }

        .navbar .nav-link {
            color: #526176;
            font-weight: 600;
            padding: 10px 13px !important;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--primary);
        }

        .btn-primary-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--primary);
            color: #fff;
            border: 1px solid var(--primary);
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-outline-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 18px;
            border: 1px solid #d4dfed;
            border-radius: 10px;
            color: var(--navy);
            font-weight: 700;
            background: #fff;
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .hero {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 90% 10%, #dceaff 0, transparent 28%),
                linear-gradient(135deg, #f3f8ff, #ffffff 70%);
            padding: 76px 0 64px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 30px;
            background: #e7f0ff;
            color: var(--primary-dark);
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .hero h1 {
            max-width: 760px;
            margin: 18px 0;
            font-size: clamp(2.1rem, 4.2vw, 3.5rem);
            font-weight: 800;
            line-height: 1.13;
            letter-spacing: -1.8px;
        }

        .hero p {
            max-width: 690px;
            color: var(--muted);
            font-size: 1.08rem;
        }

        .breadcrumb-text {
            color: #7b8ba0;
            font-size: .9rem;
            margin-bottom: 18px;
        }

        .breadcrumb-text a {
            color: var(--primary);
            font-weight: 600;
        }

        .quote-section {
            padding: 65px 0 80px;
            background: #fff;
        }

        .form-card,
        .info-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 12px 38px rgba(24, 53, 91, .055);
        }

        .form-card {
            padding: clamp(22px, 4vw, 40px);
        }

        .info-card {
            padding: 28px;
            margin-bottom: 20px;
        }

        .section-heading {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .section-subtitle {
            color: var(--muted);
            font-size: .94rem;
        }

        .form-label {
            color: #243b57;
            font-size: .9rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 49px;
            border: 1px solid #dbe5f0;
            border-radius: 10px;
            padding: 11px 13px;
            color: var(--navy);
            font-size: .94rem;
            box-shadow: none !important;
        }

        textarea.form-control {
            min-height: 145px;
            resize: vertical;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #79a9f6;
            box-shadow: 0 0 0 3px rgba(23, 105, 224, .1) !important;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
        }

        .form-text {
            color: #8290a2;
            font-size: .82rem;
        }

        .invalid-feedback {
            display: block;
        }

        .form-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.03rem;
            font-weight: 800;
            margin: 30px 0 20px;
        }

        .form-section-title:first-of-type {
            margin-top: 0;
        }

        .section-number {
            width: 31px;
            height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 9px;
            color: var(--primary);
            background: #eaf2ff;
            font-size: .88rem;
            font-weight: 800;
        }

        .contact-option {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            height: 100%;
            cursor: pointer;
        }

        .contact-option input {
            margin-top: 5px;
            accent-color: var(--primary);
        }

        .contact-option strong {
            display: block;
            font-size: .9rem;
        }

        .contact-option small {
            display: block;
            color: var(--muted);
            margin-top: 2px;
        }

        .info-icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            background: #eaf2ff;
            color: var(--primary);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .info-card h3 {
            font-size: 1.1rem;
            font-weight: 800;
            margin: 0 0 10px;
        }

        .info-card p,
        .info-card li {
            color: var(--muted);
            font-size: .92rem;
        }

        .info-card ul {
            padding-left: 19px;
            margin-bottom: 0;
        }

        .info-card li {
            margin-bottom: 9px;
        }

        .process-item {
            display: flex;
            gap: 13px;
            margin-top: 20px;
        }

        .process-number {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eaf2ff;
            color: var(--primary);
            border-radius: 50%;
            font-weight: 800;
            font-size: .85rem;
        }

        .process-item strong {
            display: block;
            font-size: .93rem;
        }

        .process-item p {
            margin: 3px 0 0;
            font-size: .85rem;
        }

        .trust-note {
            padding: 15px;
            border-radius: 12px;
            background: #f3f8ff;
            color: #536781;
            font-size: .87rem;
        }

        .success-panel {
            padding: 34px;
            border: 1px solid #bfe8d4;
            background: #f1fbf6;
            border-radius: 18px;
        }

        .success-icon {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #d7f5e5;
            color: var(--success);
            border-radius: 50%;
            font-size: 1.8rem;
            font-weight: 800;
        }

        .reference-box {
            display: inline-block;
            background: #fff;
            border: 1px dashed #8ac9aa;
            border-radius: 10px;
            padding: 12px 16px;
            margin: 14px 0;
            font-weight: 800;
            letter-spacing: .7px;
            color: #146c43;
        }

        .faq-section {
            padding: 0 0 75px;
            background: #fff;
        }

        .faq-card {
            height: 100%;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 22px;
        }

        .faq-card h3 {
            font-size: 1rem;
            font-weight: 800;
        }

        .faq-card p {
            color: var(--muted);
            font-size: .9rem;
            margin: 0;
        }

        footer {
            background: #0d1e34;
            color: #fff;
            padding: 48px 0 20px;
        }

        footer .footer-text {
            color: #a9b7c9;
            font-size: .9rem;
        }

        footer a {
            color: #c3d0e1;
            font-size: .9rem;
        }

        footer a:hover {
            color: #fff;
        }

        .footer-title {
            font-weight: 800;
            margin-bottom: 14px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.12);
            margin-top: 35px;
            padding-top: 18px;
            color: #a9b7c9;
            font-size: .83rem;
        }

        @media (max-width: 767px) {
            .hero {
                padding: 55px 0 45px;
            }

            .quote-section {
                padding: 40px 0 55px;
            }

            .hero h1 {
                letter-spacing: -1px;
            }

            .info-card {
                padding: 23px;
            }
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container py-2">

        <a class="navbar-brand" href="index.php">
            Pro<span class="brand-highlight">Serve</span>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="services.php">Services</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="request_quote.php">
                        Request a Quote
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a class="btn-primary-custom" href="login.php">
                        Customer Login
                    </a>
                </li>
            </ul>

        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">

        <div class="breadcrumb-text">
            <a href="index.php">Home</a>
            <span class="mx-2">/</span>
            <a href="services.php">Services</a>
            <span class="mx-2">/</span>
            Request a Quote
        </div>

        <span class="eyebrow">Let's Discuss Your Project</span>

        <h1>
            Request a Quote for Your
            <span class="brand-highlight">Next Project.</span>
        </h1>

        <p>
            Tell us what you need, your estimated budget, and your preferred
            timeline. Explore how ProServe can help with your business or
            personal service requirements.
        </p>

    </div>
</section>

<!-- Quote Form -->
<section class="quote-section">
    <div class="container">

        <div class="row g-4 g-xl-5">

            <div class="col-lg-8">

                <?php if ($success): ?>

                    <div class="success-panel">

                        <div class="success-icon mb-3">✓</div>

                        <h2 class="fw-bold mb-2">
                            Your Quote Request Has Been Recorded!
                        </h2>

                        <p class="text-secondary mb-2">
                            Thank you for providing your project details.
                            Your demonstration request has been recorded for
                            this PHP session.
                        </p>

                        <div class="reference-box">
                            Reference: <?= e($referenceNumber) ?>
                        </div>

                        <p class="small text-secondary">
                            This is a portfolio demonstration. No quotation
                            email has been sent, and no permanent database
                            record has been created.
                        </p>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="request_quote.php" class="btn-primary-custom">
                                Submit Another Request
                            </a>

                            <a href="services.php" class="btn-outline-custom">
                                Explore Services
                            </a>

                            <button
                                type="button"
                                class="btn-outline-custom"
                                onclick="window.print()"
                            >
                                Print Confirmation
                            </button>
                        </div>

                    </div>

                <?php else: ?>

                    <div class="form-card">

                        <div class="mb-4">
                            <h2 class="section-heading">
                                Tell Us About Your Project
                            </h2>

                            <p class="section-subtitle mb-0">
                                Complete the form below. Fields marked with
                                an asterisk (*) are required.
                            </p>
                        </div>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Please check the following:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= e($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="request_quote.php" novalidate>

                            <!-- Customer Details -->
                            <div class="form-section-title">
                                <span class="section-number">1</span>
                                Your Contact Information
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label" for="full_name">
                                        Full Name *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                                        id="full_name"
                                        name="full_name"
                                        maxlength="100"
                                        autocomplete="name"
                                        placeholder="Enter your full name"
                                        value="<?= e($form['full_name']) ?>"
                                        required
                                    >

                                    <?php if (isset($errors['full_name'])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors['full_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="email">
                                        Email Address *
                                    </label>

                                    <input
                                        type="email"
                                        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                        id="email"
                                        name="email"
                                        maxlength="150"
                                        autocomplete="email"
                                        placeholder="you@example.com"
                                        value="<?= e($form['email']) ?>"
                                        required
                                    >

                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors['email']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="phone">
                                        Phone Number
                                        <span class="text-secondary fw-normal">
                                            (required for phone contact)
                                        </span>
                                    </label>

                                    <input
                                        type="tel"
                                        class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                        id="phone"
                                        name="phone"
                                        maxlength="25"
                                        autocomplete="tel"
                                        placeholder="+1 555 010 2020"
                                        value="<?= e($form['phone']) ?>"
                                    >

                                    <?php if (isset($errors['phone'])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors['phone']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="company">
                                        Company / Organization
                                        <span class="text-secondary fw-normal">
                                            (optional)
                                        </span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['company']) ? 'is-invalid' : '' ?>"
                                        id="company"
                                        name="company"
                                        maxlength="150"
                                        autocomplete="organization"
                                        placeholder="Your company name"
                                        value="<?= e($form['company']) ?>"
                                    >

                                    <?php if (isset($errors['company'])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors['company']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <!-- Project Details -->
                            <div class="form-section-title">
                                <span class="section-number">2</span>
                                Project Requirements
                            </div>

                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label" for="service">
                                        Service Required *
                                    </label>

                                    <select
                                        class="form-select <?= isset($errors['service']) ? 'is-invalid' : '' ?>"
                                        id="service"
                                        name="service"
                                        required
                                    >
                                        <option value="">Choose a service</option>

                                        <?php foreach ($services as $service): ?>
                                            <option
                                                value="<?= e($service) ?>"
                                                <?= $form['service'] === $service ? 'selected' : '' ?>
                                            >
                                                <?= e($service) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php if (isset($errors['service'])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors['service']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="project_title">
                                        Project Title *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['project_title']) ? 'is-invalid' : '' ?>"
                                        id="project_title"
                                        name="project_title"
                                        maxlength="150"
                                        placeholder="e.g. Business Website Development"
                                        value="<?= e($form['project_title']) ?>"
                                        required
                                    >

                                    <?php if (isset($errors['project_title'])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors['project_title']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="project_details">
                                        Describe Your Requirements *
                                    </label>

                                    <textarea
                                        class="form-control <?= isset($errors['project_details']) ? 'is-invalid' : '' ?>"
                                        id="project_details"
                                        name="project_details"
                                        maxlength="3000"
                                        rows="6"
                                        placeholder="Describe the work you need, important features, project goals, location (if relevant), and any other requirements..."
                                        required
                                    ><?= e($form['project_details']) ?></textarea>

                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="form-text">
                                            Please provide at least 20 characters.
                                        </small>

                                        <small class="form-text">
                                            <span id="characterCount"><?= quoteLength($form['project_details']) ?></span>/3000
                                        </small>
                                    </div>

                                    <?php if (isset($errors['project_details'])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors['project_details']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <!-- Budget and Timeline -->
                            <div class="form-section-title">
                                <span class="section-number">3</span>
                                Budget and Timeline
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label" for="budget">
                                        Estimated Budget *
                                    </label>

                                    <select
                                        class="form-select <?= isset($errors['budget']) ? 'is-invalid' : '' ?>"
                                        id="budget"
                                        name="budget"
                                        required
                                    >
                                        <option value="">Choose a budget</option>

                                        <?php foreach ($budgets as $budget): ?>
                                            <option
                                                value="<?= e($budget) ?>"
                                                <?= $form['budget'] === $budget ? 'selected' : '' ?>
                                            >
                                                <?= e($budget) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php if (isset($errors['budget'])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors['budget']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="timeline">
                                        Preferred Timeline *
                                    </label>

                                    <select
                                        class="form-select <?= isset($errors['timeline']) ? 'is-invalid' : '' ?>"
                                        id="timeline"
                                        name="timeline"
                                        required
                                    >
                                        <option value="">Choose a timeline</option>

                                        <?php foreach ($timelines as $timeline): ?>
                                            <option
                                                value="<?= e($timeline) ?>"
                                                <?= $form['timeline'] === $timeline ? 'selected' : '' ?>
                                            >
                                                <?= e($timeline) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php if (isset($errors['timeline'])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors['timeline']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <!-- Preferred Contact Method -->
                            <div class="form-section-title">
                                <span class="section-number">4</span>
                                Preferred Contact Method
                            </div>

                            <?php if (isset($errors['contact_method'])): ?>
                                <div class="text-danger small mb-2">
                                    <?= e($errors['contact_method']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="row g-3">

                                <div class="col-sm-6">
                                    <label class="contact-option">
                                        <input
                                            type="radio"
                                            name="contact_method"
                                            value="Email"
                                            <?= $form['contact_method'] === 'Email' ? 'checked' : '' ?>
                                        >

                                        <span>
                                            <strong>Email</strong>
                                            <small>Contact me by email.</small>
                                        </span>
                                    </label>
                                </div>

                                <div class="col-sm-6">
                                    <label class="contact-option">
                                        <input
                                            type="radio"
                                            name="contact_method"
                                            value="Phone"
                                            <?= $form['contact_method'] === 'Phone' ? 'checked' : '' ?>
                                        >

                                        <span>
                                            <strong>Phone</strong>
                                            <small>Contact me by telephone.</small>
                                        </span>
                                    </label>
                                </div>

                            </div>

                            <div class="trust-note mt-4">
                                <strong>Demo privacy notice:</strong>
                                Your submission is stored only in the current
                                PHP session for demonstration purposes. This
                                page does not send emails or save information
                                to a permanent database.
                            </div>

                            <div class="d-flex flex-wrap gap-3 mt-4">

                                <button
                                    type="submit"
                                    class="btn-primary-custom"
                                >
                                    Submit Quote Request
                                    <span aria-hidden="true">→</span>
                                </button>

                                <a href="services.php" class="btn-outline-custom">
                                    Back to Services
                                </a>

                            </div>

                        </form>

                    </div>

                <?php endif; ?>

            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">

                <div class="info-card">

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="info-icon">✓</div>

                        <h3 class="mb-0">Why Request a Quote?</h3>
                    </div>

                    <p>
                        Share your requirements to explore a suitable solution
                        for your project or business.
                    </p>

                    <ul>
                        <li>Describe your specific requirements.</li>
                        <li>Indicate your estimated budget.</li>
                        <li>Choose a preferred project timeline.</li>
                        <li>Tell us how you prefer to be contacted.</li>
                    </ul>

                </div>

                <div class="info-card">

                    <h3>What Happens Next?</h3>

                    <div class="process-item">
                        <div class="process-number">1</div>

                        <div>
                            <strong>Share Your Requirements</strong>
                            <p>
                                Provide project details, budget, and timeline.
                            </p>
                        </div>
                    </div>

                    <div class="process-item">
                        <div class="process-number">2</div>

                        <div>
                            <strong>Review the Request</strong>
                            <p>
                                A real service business could review the
                                requirements and clarify any questions.
                            </p>
                        </div>
                    </div>

                    <div class="process-item">
                        <div class="process-number">3</div>

                        <div>
                            <strong>Prepare a Quotation</strong>
                            <p>
                                The business could prepare pricing and a
                                proposed delivery plan.
                            </p>
                        </div>
                    </div>

                    <p class="small text-secondary mt-3 mb-0">
                        These steps illustrate a typical business workflow.
                        They are not automatically performed by this demo.
                    </p>

                </div>

                <div class="info-card">

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="info-icon">?</div>

                        <h3 class="mb-0">Need Help?</h3>
                    </div>

                    <p>
                        Not sure which service fits your needs? Send a general
                        enquiry and explain what you are trying to achieve.
                    </p>

                    <a href="contact.php" class="btn-outline-custom w-100">
                        Contact Our Team
                    </a>

                </div>

                <div class="info-card">
                    <h3>Explore ProServe</h3>

                    <p>
                        Discover the range of services available in this
                        business-service platform demonstration.
                    </p>

                    <div class="d-grid gap-2">
                        <a href="services.php" class="btn-outline-custom">
                            Browse Services
                        </a>

                        <a href="register.php" class="btn-outline-custom">
                            Create Demo Account
                        </a>

                        <a href="login.php" class="btn-outline-custom">
                            Customer Login
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- FAQ -->
<section class="faq-section">
    <div class="container">

        <div class="text-center mb-4">
            <span class="eyebrow">Frequently Asked Questions</span>

            <h2 class="fw-bold mt-3">
                Before You Submit
            </h2>

            <p class="text-secondary">
                A few helpful details about the quotation process.
            </p>
        </div>

        <div class="row g-3">

            <div class="col-md-4">
                <div class="faq-card">
                    <h3>Is the quotation free?</h3>
                    <p>
                        This demonstration illustrates a quote-request
                        workflow. Actual quotation terms depend on the service
                        provider.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="faq-card">
                    <h3>Do I need an exact budget?</h3>
                    <p>
                        No. You can select “Not sure yet” if you need guidance
                        estimating the project cost.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="faq-card">
                    <h3>Will I receive a confirmation email?</h3>
                    <p>
                        Not in this portfolio demo. Requests are stored in
                        the current PHP session, and no email is sent.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-5">
                <a href="index.php" class="navbar-brand text-white">
                    Pro<span class="brand-highlight">Serve</span>
                </a>

                <p class="footer-text mt-3">
                    A service management platform concept designed to
                    demonstrate how businesses can present services and
                    manage customer enquiries.
                </p>

                <p class="footer-text mb-0">
                    Portfolio demonstration by
                    <strong>Codvanta</strong>.
                </p>
            </div>

            <div class="col-6 col-lg-2">
                <div class="footer-title">Explore</div>

                <div class="d-flex flex-column gap-2">
                    <a href="index.php">Home</a>
                    <a href="services.php">Services</a>
                    <a href="request_quote.php">Request a Quote</a>
                    <a href="contact.php">Contact</a>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <div class="footer-title">Customer</div>

                <div class="d-flex flex-column gap-2">
                    <a href="register.php">Register</a>
                    <a href="login.php">Login</a>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="footer-title">Service Categories</div>

                <p class="footer-text">
                    Technology, maintenance, cleaning, consulting,
                    construction, and business services.
                </p>
            </div>

        </div>

        <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
            <span>
                &copy; <?= date("Y") ?> ProServe. Demonstration website.
            </span>

            <span>
                Turning Vision into Digital Reality.
            </span>
        </div>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Live character counter for project details.
    const detailsField = document.getElementById("project_details");
    const characterCount = document.getElementById("characterCount");

    if (detailsField && characterCount) {
        detailsField.addEventListener("input", function () {
            characterCount.textContent = this.value.length;
        });
    }

    // Require a phone number when Phone is selected.
    const phoneField = document.getElementById("phone");
    const contactRadios = document.querySelectorAll(
        'input[name="contact_method"]'
    );

    function updatePhoneRequirement() {
        const selected = document.querySelector(
            'input[name="contact_method"]:checked'
        );

        if (phoneField) {
            phoneField.required = selected && selected.value === "Phone";
        }
    }

    contactRadios.forEach(function (radio) {
        radio.addEventListener("change", updatePhoneRequirement);
    });

    updatePhoneRequirement();
</script>

</body>
</html>