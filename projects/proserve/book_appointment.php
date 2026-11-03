
<?php
session_start();

/*
|--------------------------------------------------------------------------
| ProServe - Book an Appointment
|--------------------------------------------------------------------------
| Portfolio demonstration:
| - No database connection
| - No emails or SMS messages are sent
| - Appointments are stored in the current PHP session
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Asia/Colombo');

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

$appointmentTypes = [
    "Online Consultation",
    "Phone Consultation",
    "In-Person Meeting",
    "On-Site Service Visit"
];

$timeSlots = [
    "09:00" => "09:00 AM - 09:30 AM",
    "09:30" => "09:30 AM - 10:00 AM",
    "10:00" => "10:00 AM - 10:30 AM",
    "10:30" => "10:30 AM - 11:00 AM",
    "11:00" => "11:00 AM - 11:30 AM",
    "11:30" => "11:30 AM - 12:00 PM",
    "13:00" => "01:00 PM - 01:30 PM",
    "13:30" => "01:30 PM - 02:00 PM",
    "14:00" => "02:00 PM - 02:30 PM",
    "14:30" => "02:30 PM - 03:00 PM",
    "15:00" => "03:00 PM - 03:30 PM",
    "15:30" => "03:30 PM - 04:00 PM"
];

if (!isset($_SESSION["proserve_appointments"])) {
    $_SESSION["proserve_appointments"] = [];
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function textLength($value)
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
    "appointment_type" => "Online Consultation",
    "appointment_date" => "",
    "appointment_time" => "",
    "location" => "",
    "notes" => ""
];

$errors = [];
$success = false;
$bookingReference = "";
$confirmedAppointment = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    foreach ($form as $key => $value) {
        if (isset($_POST[$key]) && is_string($_POST[$key])) {
            $form[$key] = trim($_POST[$key]);
        }
    }

    // Full name
    if (
        textLength($form["full_name"]) < 2 ||
        textLength($form["full_name"]) > 100
    ) {
        $errors["full_name"] =
            "Enter a name between 2 and 100 characters.";
    }

    // Email
    if (
        !filter_var($form["email"], FILTER_VALIDATE_EMAIL) ||
        textLength($form["email"]) > 150
    ) {
        $errors["email"] = "Enter a valid email address.";
    }

    // Phone
    if (
        !preg_match('/^[0-9+\-\s().]{7,25}$/', $form["phone"])
    ) {
        $errors["phone"] = "Enter a valid phone number.";
    }

    // Company
    if (textLength($form["company"]) > 150) {
        $errors["company"] =
            "Company name cannot exceed 150 characters.";
    }

    // Service
    if (!in_array($form["service"], $services, true)) {
        $errors["service"] = "Select a valid service.";
    }

    // Appointment type
    if (!in_array($form["appointment_type"], $appointmentTypes, true)) {
        $errors["appointment_type"] =
            "Select a valid appointment type.";
    }

    // Date validation: appointments must be today or in the future.
    $dateObject = DateTime::createFromFormat(
        '!Y-m-d',
        $form["appointment_date"]
    );

    $dateValid = $dateObject &&
        $dateObject->format("Y-m-d") === $form["appointment_date"];

    $today = new DateTime("today");

    if (!$dateValid || $dateObject < $today) {
        $errors["appointment_date"] =
            "Choose today or a future date.";
    }

    // Time
    if (!array_key_exists($form["appointment_time"], $timeSlots)) {
        $errors["appointment_time"] =
            "Select an available time slot.";
    }

    // In-person and on-site visits need a location.
    $needsLocation = in_array(
        $form["appointment_type"],
        ["In-Person Meeting", "On-Site Service Visit"],
        true
    );

    if ($needsLocation) {
        if (
            textLength($form["location"]) < 5 ||
            textLength($form["location"]) > 250
        ) {
            $errors["location"] =
                "Enter a location between 5 and 250 characters.";
        }
    } elseif (textLength($form["location"]) > 250) {
        $errors["location"] =
            "Location cannot exceed 250 characters.";
    }

    // Notes
    if (textLength($form["notes"]) > 2000) {
        $errors["notes"] =
            "Additional notes cannot exceed 2000 characters.";
    }

    // Prevent duplicate time slots within this session.
    // This is a demonstration only, not a shared real booking calendar.
    if ($dateValid && array_key_exists($form["appointment_time"], $timeSlots)) {

        foreach ($_SESSION["proserve_appointments"] as $existing) {
            if (
                ($existing["appointment_date"] ?? "") === $form["appointment_date"] &&
                ($existing["appointment_time"] ?? "") === $form["appointment_time"] &&
                ($existing["status"] ?? "") !== "CANCELLED"
            ) {
                $errors["appointment_time"] =
                    "This time slot has already been booked in your current demo session. Please choose another time.";
                break;
            }
        }
    }

    if (empty($errors)) {

        $bookingReference = "PSA-" .
            date("ymd") . "-" .
            strtoupper(bin2hex(random_bytes(3)));

        $confirmedAppointment = [
            "reference" => $bookingReference,
            "full_name" => $form["full_name"],
            "email" => $form["email"],
            "phone" => $form["phone"],
            "company" => $form["company"],
            "service" => $form["service"],
            "appointment_type" => $form["appointment_type"],
            "appointment_date" => $form["appointment_date"],
            "appointment_time" => $form["appointment_time"],
            "time_label" => $timeSlots[$form["appointment_time"]],
            "location" => $form["location"],
            "notes" => $form["notes"],
            "status" => "PENDING CONFIRMATION",
            "created_at" => date("Y-m-d H:i:s")
        ];

        $_SESSION["proserve_appointments"][] = $confirmedAppointment;

        $success = true;

        $form = [
            "full_name" => "",
            "email" => "",
            "phone" => "",
            "company" => "",
            "service" => "",
            "appointment_type" => "Online Consultation",
            "appointment_date" => "",
            "appointment_time" => "",
            "location" => "",
            "notes" => ""
        ];
    }
}

$minimumDate = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book an Appointment | ProServe</title>

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
            --green: #13865c;
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
            background:
                radial-gradient(circle at 90% 10%, #dceaff 0, transparent 28%),
                linear-gradient(135deg, #f3f8ff, #ffffff 70%);
            padding: 72px 0 62px;
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
            max-width: 700px;
            color: var(--muted);
            font-size: 1.08rem;
        }

        .booking-section {
            padding: 65px 0 80px;
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
            padding: 27px;
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
            min-height: 125px;
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
            margin-top: 19px;
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

        .notice-box {
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
            color: var(--green);
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

        .booking-summary {
            background: #fff;
            border: 1px solid #d9eee2;
            border-radius: 12px;
            padding: 18px;
            margin: 18px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 8px 0;
            border-bottom: 1px solid #edf3ef;
            font-size: .9rem;
        }

        .summary-row:last-child {
            border-bottom: 0;
        }

        .summary-label {
            color: var(--muted);
        }

        .summary-value {
            font-weight: 700;
            text-align: right;
            overflow-wrap: anywhere;
        }

        .faq-section {
            padding: 0 0 75px;
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
                padding: 52px 0 43px;
            }

            .booking-section {
                padding: 40px 0 55px;
            }

            .hero h1 {
                letter-spacing: -1px;
            }

            .info-card {
                padding: 23px;
            }

            .summary-row {
                flex-direction: column;
                gap: 2px;
            }

            .summary-value {
                text-align: left;
            }
        }

        @media print {
            .navbar,
            .hero,
            .booking-section,
            .faq-section,
            footer,
            .no-print {
                display: none !important;
            }

            .success-panel {
                border: 1px solid #999;
                background: #fff;
            }

            body {
                background: #fff;
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
                    <a class="nav-link" href="request_quote.php">
                        Request a Quote
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="book_appointment.php">
                        Book Appointment
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
            Book an Appointment
        </div>

        <span class="eyebrow">Schedule a Consultation</span>

        <h1>
            Let's Find a Time That
            <span class="brand-highlight">Works for You.</span>
        </h1>

        <p>
            Choose the service you need, select a preferred date and time,
            and tell us how you would like to meet. This page demonstrates
            a typical service appointment booking workflow.
        </p>

    </div>
</section>

<!-- Booking Form -->
<section class="booking-section">
    <div class="container">

        <div class="row g-4 g-xl-5">

            <div class="col-lg-8">

                <?php if ($success): ?>

                    <div class="success-panel">

                        <div class="success-icon mb-3">✓</div>

                        <h2 class="fw-bold mb-2">
                            Appointment Request Recorded!
                        </h2>

                        <p class="text-secondary">
                            Thank you, <?= e($confirmedAppointment["full_name"]) ?>.
                            Your appointment request has been recorded in this
                            demonstration session.
                        </p>

                        <div class="reference-box">
                            Booking Reference:
                            <?= e($bookingReference) ?>
                        </div>

                        <div class="booking-summary">

                            <h3 class="h6 fw-bold mb-3">
                                Appointment Summary
                            </h3>

                            <div class="summary-row">
                                <span class="summary-label">Service</span>
                                <span class="summary-value">
                                    <?= e($confirmedAppointment["service"]) ?>
                                </span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Appointment Type</span>
                                <span class="summary-value">
                                    <?= e($confirmedAppointment["appointment_type"]) ?>
                                </span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Date</span>
                                <span class="summary-value">
                                    <?= e(date(
                                        "D, d M Y",
                                        strtotime($confirmedAppointment["appointment_date"])
                                    )) ?>
                                </span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Time</span>
                                <span class="summary-value">
                                    <?= e($confirmedAppointment["time_label"]) ?>
                                </span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Contact Email</span>
                                <span class="summary-value">
                                    <?= e($confirmedAppointment["email"]) ?>
                                </span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Status</span>
                                <span class="summary-value text-warning-emphasis">
                                    <?= e($confirmedAppointment["status"]) ?>
                                </span>
                            </div>

                            <?php if ($confirmedAppointment["location"] !== ""): ?>
                                <div class="summary-row">
                                    <span class="summary-label">Location</span>
                                    <span class="summary-value">
                                        <?= e($confirmedAppointment["location"]) ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                        </div>

                        <p class="small text-secondary">
                            <strong>Demonstration notice:</strong>
                            No real appointment calendar was checked, no
                            email or SMS was sent, and no permanent database
                            record was created. The booking is marked
                            pending confirmation.
                        </p>

                        <div class="d-flex flex-wrap gap-2 mt-3 no-print">
                            <a href="book_appointment.php" class="btn-primary-custom">
                                Book Another Appointment
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
                                Appointment Booking Form
                            </h2>

                            <p class="section-subtitle mb-0">
                                Enter your contact details and choose your
                                preferred appointment schedule.
                            </p>
                        </div>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Please correct the following:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= e($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="book_appointment.php" novalidate>

                            <!-- Contact Details -->
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

                                    <?php if (isset($errors["full_name"])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors["full_name"]) ?>
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

                                    <?php if (isset($errors["email"])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors["email"]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="phone">
                                        Phone Number *
                                    </label>

                                    <input
                                        type="tel"
                                        class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                        id="phone"
                                        name="phone"
                                        maxlength="25"
                                        autocomplete="tel"
                                        placeholder="+94 77 123 4567"
                                        value="<?= e($form['phone']) ?>"
                                        required
                                    >

                                    <?php if (isset($errors["phone"])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors["phone"]) ?>
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
                                        placeholder="Company name"
                                        value="<?= e($form['company']) ?>"
                                    >

                                    <?php if (isset($errors["company"])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors["company"]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <!-- Service Details -->
                            <div class="form-section-title">
                                <span class="section-number">2</span>
                                Service and Appointment Type
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
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
                                                <?= $form["service"] === $service ? "selected" : "" ?>
                                            >
                                                <?= e($service) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php if (isset($errors["service"])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors["service"]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="appointment_type">
                                        Appointment Type *
                                    </label>

                                    <select
                                        class="form-select <?= isset($errors['appointment_type']) ? 'is-invalid' : '' ?>"
                                        id="appointment_type"
                                        name="appointment_type"
                                        required
                                    >
                                        <?php foreach ($appointmentTypes as $type): ?>
                                            <option
                                                value="<?= e($type) ?>"
                                                <?= $form["appointment_type"] === $type ? "selected" : "" ?>
                                            >
                                                <?= e($type) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php if (isset($errors["appointment_type"])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors["appointment_type"]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12" id="locationWrapper">
                                    <label class="form-label" for="location">
                                        Meeting / Service Location
                                        <span class="text-secondary fw-normal">
                                            (required for in-person visits)
                                        </span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['location']) ? 'is-invalid' : '' ?>"
                                        id="location"
                                        name="location"
                                        maxlength="250"
                                        placeholder="Enter the address or preferred meeting location"
                                        value="<?= e($form['location']) ?>"
                                    >

                                    <?php if (isset($errors["location"])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors["location"]) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-text mt-2">
                                        For online or phone consultations,
                                        you can leave this field empty.
                                    </div>
                                </div>

                            </div>

                            <!-- Schedule -->
                            <div class="form-section-title">
                                <span class="section-number">3</span>
                                Preferred Schedule
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label" for="appointment_date">
                                        Appointment Date *
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control <?= isset($errors['appointment_date']) ? 'is-invalid' : '' ?>"
                                        id="appointment_date"
                                        name="appointment_date"
                                        min="<?= e($minimumDate) ?>"
                                        value="<?= e($form['appointment_date']) ?>"
                                        required
                                    >

                                    <?php if (isset($errors["appointment_date"])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors["appointment_date"]) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-text mt-2">
                                        Select today or a future date.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="appointment_time">
                                        Preferred Time *
                                    </label>

                                    <select
                                        class="form-select <?= isset($errors['appointment_time']) ? 'is-invalid' : '' ?>"
                                        id="appointment_time"
                                        name="appointment_time"
                                        required
                                    >
                                        <option value="">Choose a time slot</option>

                                        <?php foreach ($timeSlots as $value => $label): ?>
                                            <option
                                                value="<?= e($value) ?>"
                                                <?= $form["appointment_time"] === $value ? "selected" : "" ?>
                                            >
                                                <?= e($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php if (isset($errors["appointment_time"])): ?>
                                        <div class="invalid-feedback">
                                            <?= e($errors["appointment_time"]) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-text mt-2">
                                        Demo slots are 30 minutes long.
                                        Availability is not checked against
                                        a real calendar.
                                    </div>
                                </div>

                            </div>

                            <!-- Additional Notes -->
                            <div class="form-section-title">
                                <span class="section-number">4</span>
                                Additional Information
                            </div>

                            <div>
                                <label class="form-label" for="notes">
                                    Notes or Special Requirements
                                    <span class="text-secondary fw-normal">
                                        (optional)
                                    </span>
                                </label>

                                <textarea
                                    class="form-control <?= isset($errors['notes']) ? 'is-invalid' : '' ?>"
                                    id="notes"
                                    name="notes"
                                    maxlength="2000"
                                    rows="5"
                                    placeholder="Share any questions, preferred meeting arrangements, or additional information..."
                                ><?= e($form["notes"]) ?></textarea>

                                <div class="d-flex justify-content-between mt-2">
                                    <small class="form-text">
                                        Maximum 2000 characters.
                                    </small>

                                    <small class="form-text">
                                        <span id="notesCount"><?= e(textLength($form["notes"])) ?></span>/2000
                                    </small>
                                </div>

                                <?php if (isset($errors["notes"])): ?>
                                    <div class="invalid-feedback">
                                        <?= e($errors["notes"]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="notice-box mt-4">
                                <strong>Demonstration notice:</strong>
                                This booking form simulates an appointment
                                request. It does not contact a real service
                                provider, reserve a shared calendar slot, or
                                send a confirmation message.
                            </div>

                            <div class="d-flex flex-wrap gap-3 mt-4">
                                <button type="submit" class="btn-primary-custom">
                                    Submit Appointment Request
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

            <!-- Sidebar -->
            <div class="col-lg-4">

                <div class="info-card">

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="info-icon">✓</div>
                        <h3 class="mb-0">Before You Book</h3>
                    </div>

                    <ul>
                        <li>Choose the service that best matches your needs.</li>
                        <li>Provide an active email address and phone number.</li>
                        <li>Select a suitable date and preferred time.</li>
                        <li>Include a location for on-site visits.</li>
                        <li>Add any important requirements in the notes field.</li>
                    </ul>

                </div>

                <div class="info-card">

                    <h3>How Booking Works</h3>

                    <div class="process-item">
                        <div class="process-number">1</div>
                        <div>
                            <strong>Choose Your Service</strong>
                            <p>Tell us which service you want to discuss.</p>
                        </div>
                    </div>

                    <div class="process-item">
                        <div class="process-number">2</div>
                        <div>
                            <strong>Select a Schedule</strong>
                            <p>Choose a date, time, and meeting type.</p>
                        </div>
                    </div>

                    <div class="process-item">
                        <div class="process-number">3</div>
                        <div>
                            <strong>Submit Your Request</strong>
                            <p>Receive a demonstration booking reference.</p>
                        </div>
                    </div>

                    <p class="small text-secondary mt-3 mb-0">
                        In a production system, a staff member would review
                        and confirm the appointment.
                    </p>

                </div>

                <div class="info-card">

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="info-icon">?</div>
                        <h3 class="mb-0">Need Assistance?</h3>
                    </div>

                    <p>
                        If you are unsure which service to book, contact the
                        team or submit a quotation request first.
                    </p>

                    <div class="d-grid gap-2">
                        <a href="contact.php" class="btn-outline-custom">
                            Contact Us
                        </a>

                        <a href="request_quote.php" class="btn-primary-custom">
                            Request a Quote
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
            <span class="eyebrow">Booking Information</span>

            <h2 class="fw-bold mt-3">
                Frequently Asked Questions
            </h2>

            <p class="text-secondary">
                A few details to help you complete your booking request.
            </p>
        </div>

        <div class="row g-3">

            <div class="col-md-4">
                <div class="faq-card">
                    <h3>Is my appointment confirmed immediately?</h3>
                    <p>
                        No. The demo records requests as pending confirmation
                        to illustrate a service company's appointment workflow.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="faq-card">
                    <h3>Can I book an on-site visit?</h3>
                    <p>
                        Yes. Choose “On-Site Service Visit” and enter the
                        address where the service is required.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="faq-card">
                    <h3>Will I receive an email?</h3>
                    <p>
                        Not in this version. The form does not send email
                        or SMS messages and does not use a database.
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
                    demonstrate customer bookings, service enquiries,
                    and business workflows.
                </p>

                <p class="footer-text mb-0">
                    Portfolio demonstration by <strong>Codvanta</strong>.
                </p>
            </div>

            <div class="col-6 col-lg-2">
                <div class="footer-title">Explore</div>

                <div class="d-flex flex-column gap-2">
                    <a href="index.php">Home</a>
                    <a href="services.php">Services</a>
                    <a href="request_quote.php">Request a Quote</a>
                    <a href="book_appointment.php">Book Appointment</a>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <div class="footer-title">Customer</div>

                <div class="d-flex flex-column gap-2">
                    <a href="register.php">Register</a>
                    <a href="login.php">Login</a>
                    <a href="contact.php">Contact</a>
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

            <span>Turning Vision into Digital Reality.</span>
        </div>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Show the location field as required only for in-person visits.
    const appointmentType = document.getElementById("appointment_type");
    const locationField = document.getElementById("location");

    function updateLocationRequirement() {
        if (!appointmentType || !locationField) {
            return;
        }

        const needsLocation = [
            "In-Person Meeting",
            "On-Site Service Visit"
        ].includes(appointmentType.value);

        locationField.required = needsLocation;
        locationField.placeholder = needsLocation
            ? "Enter the address or preferred meeting location"
            : "Optional for online or phone consultations";
    }

    if (appointmentType) {
        appointmentType.addEventListener("change", updateLocationRequirement);
        updateLocationRequirement();
    }

    // Notes character counter.
    const notesField = document.getElementById("notes");
    const notesCount = document.getElementById("notesCount");

    if (notesField && notesCount) {
        notesField.addEventListener("input", function () {
            notesCount.textContent = this.value.length;
        });
    }

    // Avoid selecting a past date if the date input changes.
    const dateField = document.getElementById("appointment_date");

    if (dateField) {
        const today = new Date();
        const localToday = [
            today.getFullYear(),
            String(today.getMonth() + 1).padStart(2, "0"),
            String(today.getDate()).padStart(2, "0")
        ].join("-");

        dateField.min = localToday;
    }
</script>

</body>
</html>
