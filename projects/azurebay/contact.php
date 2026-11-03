
<?php
session_start();

date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Contact Page
|--------------------------------------------------------------------------
| Demonstration features:
| - Server-side form validation
| - Inquiry category selection
| - Demo submission confirmation
| - Session-based storage of submitted inquiries
|
| This demo does not send real emails or use a database.
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['azurebay_inquiries'])) {
    $_SESSION['azurebay_inquiries'] = [];
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$errors = [];

$form = [
    'name'         => '',
    'email'        => '',
    'phone'        => '',
    'inquiry_type' => '',
    'subject'      => '',
    'message'      => ''
];

$success = isset($_GET['submitted']) && $_GET['submitted'] === '1';

$inquiryTypes = [
    'Room Reservation',
    'Existing Booking',
    'Dining & Restaurants',
    'Spa & Wellness',
    'Events & Celebrations',
    'General Inquiry',
    'Other'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form as $key => $value) {
        $form[$key] = trim($_POST[$key] ?? '');
    }

    // Name validation
    if ($form['name'] === '') {
        $errors['name'] = 'Please enter your full name.';
    } elseif (strlen($form['name']) < 2 || strlen($form['name']) > 100) {
        $errors['name'] = 'Your name must be between 2 and 100 characters.';
    }

    // Email validation
    if ($form['email'] === '') {
        $errors['email'] = 'Please enter your email address.';
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    // Optional phone validation
    if ($form['phone'] !== '' &&
        !preg_match('/^[0-9+\s()\-]{7,25}$/', $form['phone'])) {
        $errors['phone'] = 'Please enter a valid phone number.';
    }

    // Inquiry type validation
    if (!in_array($form['inquiry_type'], $inquiryTypes, true)) {
        $errors['inquiry_type'] = 'Please select an inquiry type.';
    }

    // Subject validation
    if ($form['subject'] === '') {
        $errors['subject'] = 'Please enter a subject.';
    } elseif (strlen($form['subject']) > 150) {
        $errors['subject'] = 'The subject cannot exceed 150 characters.';
    }

    // Message validation
    if ($form['message'] === '') {
        $errors['message'] = 'Please enter your message.';
    } elseif (strlen($form['message']) < 10) {
        $errors['message'] = 'Your message must contain at least 10 characters.';
    } elseif (strlen($form['message']) > 3000) {
        $errors['message'] = 'Your message cannot exceed 3000 characters.';
    }

    if (empty($errors)) {
        $inquiry = [
            'id'           => 'ABR-' . strtoupper(bin2hex(random_bytes(3))),
            'name'         => $form['name'],
            'email'        => $form['email'],
            'phone'        => $form['phone'],
            'inquiry_type' => $form['inquiry_type'],
            'subject'      => $form['subject'],
            'message'      => $form['message'],
            'submitted_at' => date('Y-m-d H:i:s')
        ];

        // Keep the latest demo inquiries in the current session.
        array_unshift($_SESSION['azurebay_inquiries'], $inquiry);
        $_SESSION['azurebay_inquiries'] =
            array_slice($_SESSION['azurebay_inquiries'], 0, 10);

        // Post/Redirect/Get prevents accidental resubmission on refresh.
        header('Location: contact.php?submitted=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact Us | Azure Bay Resort</title>

    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --ocean: #073b4c;
            --ocean-dark: #052c39;
            --ocean-light: #0b6573;
            --gold: #c9a45c;
            --gold-light: #ead7a8;
            --cream: #fbf8f1;
            --text: #24353b;
            --muted: #718087;
            --border: #e8e4da;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fff;
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: rgba(255, 255, 255, .98);
            border-bottom: 1px solid #eee9df;
            padding: 15px 0;
        }

        .navbar-brand {
            color: var(--ocean) !important;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 37px;
            height: 37px;
            margin-right: 8px;
            border-radius: 50%;
            background: var(--gold);
            color: white;
            font-size: 19px;
        }

        .navbar .nav-link {
            color: #44545a;
            font-size: 14px;
            font-weight: 600;
            margin: 0 5px;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--gold);
        }

        .btn-gold {
            display: inline-block;
            padding: 12px 23px;
            border: 1px solid var(--gold);
            border-radius: 4px;
            background: var(--gold);
            color: #fff;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-gold:hover {
            background: #b38c43;
            border-color: #b38c43;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-outline-ocean {
            display: inline-block;
            padding: 11px 21px;
            border: 1px solid var(--ocean);
            border-radius: 4px;
            color: var(--ocean);
            font-weight: 700;
        }

        .btn-outline-ocean:hover {
            background: var(--ocean);
            color: #fff;
        }

        .page-hero {
            position: relative;
            min-height: 350px;
            display: flex;
            align-items: center;
            background:
                linear-gradient(90deg,
                    rgba(4, 34, 45, .86),
                    rgba(4, 34, 45, .48)),
                url('assets/images/resort-hero.jpg') center 48% / cover no-repeat,
                var(--ocean);
            color: #fff;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding-top: 65px;
            padding-bottom: 65px;
        }

        .eyebrow {
            color: var(--gold);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .page-hero h1 {
            margin: 15px 0;
            font-size: clamp(36px, 5vw, 57px);
            font-weight: 800;
        }

        .page-hero p {
            max-width: 620px;
            color: #edf4f5;
            font-size: 16px;
            line-height: 1.8;
        }

        .breadcrumb-line {
            color: #d7e2e4;
            font-size: 13px;
        }

        .breadcrumb-line a {
            color: var(--gold-light);
        }

        .section-padding {
            padding: 75px 0;
        }

        .section-label {
            color: var(--gold);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .section-title {
            color: var(--ocean);
            font-size: clamp(27px, 3vw, 37px);
            font-weight: 800;
            line-height: 1.3;
        }

        .section-description {
            color: var(--muted);
            line-height: 1.8;
        }

        .contact-card {
            height: 100%;
            padding: 26px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            transition: .2s ease;
        }

        .contact-card:hover {
            border-color: var(--gold-light);
            box-shadow: 0 12px 30px rgba(7, 59, 76, .07);
            transform: translateY(-3px);
        }

        .contact-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 49px;
            height: 49px;
            margin-bottom: 18px;
            border-radius: 50%;
            background: #f5edda;
            color: var(--ocean);
            font-size: 22px;
        }

        .contact-card h5 {
            margin-bottom: 10px;
            color: var(--ocean);
            font-weight: 800;
        }

        .contact-card p,
        .contact-card a {
            margin-bottom: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .contact-card a:hover {
            color: var(--gold);
        }

        .form-panel {
            padding: 36px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 15px 45px rgba(7, 59, 76, .06);
        }

        .form-label {
            color: var(--ocean);
            font-size: 13px;
            font-weight: 700;
        }

        .form-control,
        .form-select {
            min-height: 48px;
            border: 1px solid #dedfdc;
            border-radius: 4px;
            font-size: 14px;
            box-shadow: none !important;
        }

        textarea.form-control {
            min-height: 160px;
            resize: vertical;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
        }

        .form-control::placeholder {
            color: #a4adb0;
        }

        .form-note {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .info-panel {
            padding: 34px;
            border-radius: 8px;
            background: var(--ocean);
            color: #fff;
        }

        .info-panel h3 {
            margin-bottom: 13px;
            font-weight: 800;
        }

        .info-panel > p {
            color: #d6e3e5;
            font-size: 14px;
            line-height: 1.8;
        }

        .info-item {
            display: flex;
            gap: 14px;
            padding: 18px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .14);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item-icon {
            flex: 0 0 39px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 39px;
            height: 39px;
            border-radius: 50%;
            background: rgba(201, 164, 92, .17);
            color: var(--gold-light);
            font-size: 18px;
        }

        .info-item h6 {
            margin-bottom: 6px;
            color: var(--gold-light);
            font-weight: 800;
        }

        .info-item p,
        .info-item a {
            margin-bottom: 0;
            color: #e0eaeb;
            font-size: 13px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .info-item a:hover {
            color: var(--gold-light);
        }

        .hours-box {
            margin-top: 22px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 5px;
            background: rgba(255, 255, 255, .05);
        }

        .hours-box h6 {
            margin-bottom: 12px;
            color: var(--gold-light);
            font-weight: 800;
        }

        .hours-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 7px 0;
            color: #e0eaeb;
            font-size: 12px;
        }

        .hours-row span:last-child {
            text-align: right;
        }

        .map-placeholder {
            position: relative;
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            overflow: hidden;
            border-radius: 8px;
            background:
                linear-gradient(135deg, rgba(7, 59, 76, .88),
                    rgba(11, 101, 115, .80)),
                url('assets/images/resort-pool.jpg') center / cover no-repeat;
            color: #fff;
            text-align: center;
        }

        .map-content {
            position: relative;
            z-index: 1;
            max-width: 460px;
        }

        .map-pin {
            margin-bottom: 12px;
            color: var(--gold-light);
            font-size: 36px;
        }

        .map-placeholder h4 {
            font-weight: 800;
        }

        .map-placeholder p {
            color: #e0ebed;
            font-size: 14px;
            line-height: 1.8;
        }

        .faq-section {
            background: var(--cream);
        }

        .accordion-item {
            margin-bottom: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 5px !important;
            background: #fff;
        }

        .accordion-button {
            padding: 20px;
            color: var(--ocean);
            font-size: 14px;
            font-weight: 800;
            box-shadow: none !important;
        }

        .accordion-button:not(.collapsed) {
            background: #f5edda;
            color: var(--ocean);
        }

        .accordion-body {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .demo-note {
            padding: 14px 16px;
            border-left: 3px solid var(--gold);
            background: var(--cream);
            color: #6d7779;
            font-size: 12px;
            line-height: 1.7;
        }

        .site-footer {
            padding: 55px 0 20px;
            background: var(--ocean-dark);
            color: #d2dfe1;
        }

        .footer-brand {
            color: #fff;
            font-size: 22px;
            font-weight: 800;
        }

        .footer-description {
            max-width: 350px;
            margin-top: 15px;
            color: #b8c9cc;
            font-size: 13px;
            line-height: 1.9;
        }

        .site-footer h6 {
            margin-bottom: 17px;
            color: var(--gold-light);
            font-size: 13px;
            font-weight: 800;
        }

        .site-footer ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .site-footer li {
            margin-bottom: 11px;
        }

        .site-footer a {
            color: #c4d2d4;
            font-size: 13px;
        }

        .site-footer a:hover {
            color: var(--gold-light);
        }

        .footer-bottom {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, .13);
            color: #9db2b6;
            font-size: 12px;
        }

        @media (max-width: 767px) {
            .page-hero {
                min-height: 300px;
            }

            .section-padding {
                padding: 52px 0;
            }

            .form-panel,
            .info-panel {
                padding: 24px;
            }

            .contact-card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            <span class="brand-mark">A</span>AZURE BAY
        </a>

        <button class="navbar-toggler" type="button"
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
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="rooms.php">Rooms & Suites</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="reviews.php">Guest Reviews</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="my_bookings.php">My Bookings</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="contact.php">Contact</a>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a class="btn-gold" href="rooms.php">Book Your Stay</a>
                </li>
            </ul>
        </div>

    </div>
</nav>


<!-- Page Hero -->
<section class="page-hero">
    <div class="container hero-content">

        <div class="breadcrumb-line mb-3">
            <a href="index.php">Home</a>
            <span class="mx-2">/</span>
            Contact Us
        </div>

        <div class="eyebrow">We're Here for You</div>

        <h1>Let's Make Your Stay Special</h1>

        <p>
            Have a question about your stay, our suites, or resort experiences?
            Our hospitality team is ready to help you plan an unforgettable
            beachfront escape.
        </p>

    </div>
</section>


<!-- Contact Information Cards -->
<section class="section-padding pb-0">
    <div class="container">

        <div class="text-center mb-5">
            <div class="section-label mb-2">Get in Touch</div>

            <h2 class="section-title">We'd Love to Hear From You</h2>

            <p class="section-description mx-auto" style="max-width: 650px;">
                Whether you're planning a holiday, arranging a celebration,
                or checking an existing reservation, we're happy to assist.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <div class="contact-card">
                    <div class="contact-icon">☎</div>

                    <h5>Call Our Team</h5>

                    <p>
                        Speak with our guest services team for booking
                        questions and assistance.
                    </p>

                    <a href="tel:+94112345678">
                        +94 11 234 5678
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="contact-card">
                    <div class="contact-icon">✉</div>

                    <h5>Email Us</h5>

                    <p>
                        Send us your questions and we'll be happy to help
                        with your resort arrangements.
                    </p>

                    <a href="mailto:info@azurebay.example">
                        info@azurebay.example
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="contact-card">
                    <div class="contact-icon">⌖</div>

                    <h5>Visit the Resort</h5>

                    <p>
                        Discover our beachfront setting and enjoy a peaceful
                        escape beside the Indian Ocean.
                    </p>

                    <a href="#location">View Location Information</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="contact-card">
                    <div class="contact-icon">◷</div>

                    <h5>Guest Assistance</h5>

                    <p>
                        Our front desk is available around the clock for
                        in-house guest assistance.
                    </p>

                    <a href="#contact-form">Send an Inquiry</a>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- Contact Form and Resort Details -->
<section class="section-padding" id="contact-form">
    <div class="container">

        <div class="row g-4 g-xl-5">

            <!-- Contact Form -->
            <div class="col-lg-7">

                <div class="form-panel">

                    <div class="section-label mb-2">Send a Message</div>

                    <h2 class="section-title mb-3">
                        How Can We Help You?
                    </h2>

                    <p class="section-description mb-4">
                        Complete the form below and share a few details
                        about your inquiry.
                    </p>

                    <?php if ($success): ?>
                        <div class="alert alert-success" role="status">
                            <strong>Thank you for contacting Azure Bay Resort!</strong>
                            Your demo inquiry has been submitted successfully.
                            Please note that this demonstration does not send
                            a real email.
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <strong>Please review the following:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= e($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="contact.php" method="POST" novalidate>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    Full Name *
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                                    placeholder="Enter your full name"
                                    value="<?= e($form['name']) ?>"
                                    maxlength="100"
                                    autocomplete="name"
                                    required
                                >

                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback">
                                        <?= e($errors['name']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email Address *
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                    placeholder="you@example.com"
                                    value="<?= e($form['email']) ?>"
                                    maxlength="254"
                                    autocomplete="email"
                                    required
                                >

                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?= e($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    Phone Number
                                </label>

                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                    placeholder="+94 77 123 4567"
                                    value="<?= e($form['phone']) ?>"
                                    maxlength="25"
                                    autocomplete="tel"
                                >

                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback">
                                        <?= e($errors['phone']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="inquiry_type" class="form-label">
                                    Inquiry Type *
                                </label>

                                <select
                                    id="inquiry_type"
                                    name="inquiry_type"
                                    class="form-select <?= isset($errors['inquiry_type']) ? 'is-invalid' : '' ?>"
                                    required
                                >
                                    <option value="">Choose an inquiry type</option>

                                    <?php foreach ($inquiryTypes as $type): ?>
                                        <option
                                            value="<?= e($type) ?>"
                                            <?= $form['inquiry_type'] === $type ? 'selected' : '' ?>
                                        >
                                            <?= e($type) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <?php if (isset($errors['inquiry_type'])): ?>
                                    <div class="invalid-feedback">
                                        <?= e($errors['inquiry_type']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12">
                                <label for="subject" class="form-label">
                                    Subject *
                                </label>

                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    class="form-control <?= isset($errors['subject']) ? 'is-invalid' : '' ?>"
                                    placeholder="What would you like to know?"
                                    value="<?= e($form['subject']) ?>"
                                    maxlength="150"
                                    required
                                >

                                <?php if (isset($errors['subject'])): ?>
                                    <div class="invalid-feedback">
                                        <?= e($errors['subject']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label">
                                    Your Message *
                                </label>

                                <textarea
                                    id="message"
                                    name="message"
                                    class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>"
                                    placeholder="Tell us how we can assist you..."
                                    maxlength="3000"
                                    required
                                ><?= e($form['message']) ?></textarea>

                                <?php if (isset($errors['message'])): ?>
                                    <div class="invalid-feedback">
                                        <?= e($errors['message']) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="form-note mt-2">
                                    Please provide at least 10 characters.
                                    Maximum length: 3000 characters.
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn-gold">
                                    Send Your Inquiry &rarr;
                                </button>
                            </div>

                            <div class="col-12">
                                <div class="demo-note">
                                    <strong>Demo notice:</strong>
                                    This form demonstrates the inquiry workflow.
                                    Submissions are temporarily stored in your
                                    current PHP session. No real email is sent,
                                    and the information is not stored permanently.
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>


            <!-- Resort Contact Details -->
            <div class="col-lg-5">

                <div class="info-panel h-100">

                    <div class="eyebrow mb-3">Azure Bay Hospitality</div>

                    <h3>We're Always Happy to Help</h3>

                    <p>
                        From planning your arrival to arranging a special
                        experience, our team is here to make every detail
                        of your stay feel effortless.
                    </p>

                    <div class="info-item">
                        <div class="info-item-icon">⌖</div>

                        <div>
                            <h6>Resort Location</h6>

                            <p>
                                Beachfront Resort<br>
                                Southern Coast, Sri Lanka
                            </p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-icon">☎</div>

                        <div>
                            <h6>Telephone</h6>

                            <p>
                                <a href="tel:+94112345678">
                                    +94 11 234 5678
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-icon">✉</div>

                        <div>
                            <h6>Email</h6>

                            <p>
                                <a href="mailto:info@azurebay.example">
                                    info@azurebay.example
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-icon">◷</div>

                        <div>
                            <h6>Front Desk</h6>

                            <p>
                                Available 24 hours a day for in-house
                                guest assistance.
                            </p>
                        </div>
                    </div>

                    <div class="hours-box">
                        <h6>Guest Service Hours</h6>

                        <div class="hours-row">
                            <span>Front Desk</span>
                            <span>24 hours</span>
                        </div>

                        <div class="hours-row">
                            <span>Reservation Inquiries</span>
                            <span>8:00 AM – 8:00 PM</span>
                        </div>

                        <div class="hours-row">
                            <span>Email Inquiries</span>
                            <span>Anytime</span>
                        </div>

                        <p class="form-note mt-3 mb-0" style="color:#d1dfe1;">
                            These are illustrative service details for
                            this website demonstration.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- Location Section -->
<section class="section-padding pt-0" id="location">
    <div class="container">

        <div class="row align-items-center g-4">

            <div class="col-lg-5">
                <div class="section-label mb-2">Find Your Escape</div>

                <h2 class="section-title mb-3">
                    Your Coastal Getaway Awaits
                </h2>

                <p class="section-description">
                    Imagine waking up to ocean breezes, spending your day
                    beside the water, and ending the evening with the calm
                    of the coastline.
                </p>

                <p class="section-description">
                    Contact our team for directions, arrival information,
                    and assistance planning your visit.
                </p>

                <a href="rooms.php" class="btn-outline-ocean mt-2">
                    Explore Our Rooms
                </a>
            </div>

            <div class="col-lg-7">
                <div class="map-placeholder">
                    <div class="map-content">

                        <div class="map-pin">⌖</div>

                        <h4>Azure Bay Resort</h4>

                        <p>
                            Southern Coast, Sri Lanka<br>
                            A fictional resort location created for this
                            demonstration website.
                        </p>

                        <a href="contact.php#contact-form" class="btn-gold">
                            Contact Our Team
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- Frequently Asked Questions -->
<section class="section-padding faq-section">
    <div class="container">

        <div class="text-center mb-5">
            <div class="section-label mb-2">Helpful Information</div>

            <h2 class="section-title">Frequently Asked Questions</h2>

            <p class="section-description mx-auto" style="max-width: 600px;">
                Find answers to some common questions before contacting us.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="accordion" id="contactFaq">

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faqOne"
                                    aria-expanded="true"
                                    aria-controls="faqOne">
                                How can I make a room reservation?
                            </button>
                        </h3>

                        <div id="faqOne"
                             class="accordion-collapse collapse show"
                             data-bs-parent="#contactFaq">

                            <div class="accordion-body">
                                Visit our Rooms & Suites page, select a room,
                                enter your travel dates and guest details,
                                and continue through the demonstration booking
                                workflow.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faqTwo"
                                    aria-expanded="false"
                                    aria-controls="faqTwo">
                                How can I check my booking?
                            </button>
                        </h3>

                        <div id="faqTwo"
                             class="accordion-collapse collapse"
                             data-bs-parent="#contactFaq">

                            <div class="accordion-body">
                                Visit the My Bookings page to explore the
                                booking demonstration. Actual booking lookup
                                depends on the sample workflow implemented
                                in the other pages.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faqThree"
                                    aria-expanded="false"
                                    aria-controls="faqThree">
                                Can I ask about a special event or celebration?
                            </button>
                        </h3>

                        <div id="faqThree"
                             class="accordion-collapse collapse"
                             data-bs-parent="#contactFaq">

                            <div class="accordion-body">
                                Yes. Select “Events & Celebrations” in the
                                inquiry form and describe your requirements.
                                This demo records the inquiry in your current
                                session only.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faqFour"
                                    aria-expanded="false"
                                    aria-controls="faqFour">
                                Does the contact form send a real email?
                            </button>
                        </h3>

                        <div id="faqFour"
                             class="accordion-collapse collapse"
                             data-bs-parent="#contactFaq">

                            <div class="accordion-body">
                                No. This is a portfolio demonstration. The
                                form validates the submitted information and
                                shows a confirmation message, but it does not
                                send email or permanently save inquiries.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>


<!-- Footer -->
<footer class="site-footer">
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-5">
                <a href="index.php" class="footer-brand">
                    <span class="brand-mark">A</span> AZURE BAY
                </a>

                <p class="footer-description">
                    Discover refined beachfront living, thoughtful hospitality,
                    and unforgettable coastal experiences at Azure Bay Resort.
                </p>
            </div>

            <div class="col-6 col-lg-2">
                <h6>EXPLORE</h6>

                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="rooms.php">Rooms & Suites</a></li>
                    <li><a href="reviews.php">Guest Reviews</a></li>
                    <li><a href="my_bookings.php">My Bookings</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6>GUEST SERVICES</h6>

                <ul>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="rooms.php">Reservations</a></li>
                    <li><a href="reviews.php">Share Feedback</a></li>
                    <li><a href="azurebay_demo.php">Demo Portal</a></li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h6>CONTACT INFORMATION</h6>

                <ul>
                    <li>
                        <a href="tel:+94112345678">
                            +94 11 234 5678
                        </a>
                    </li>

                    <li>
                        <a href="mailto:info@azurebay.example">
                            info@azurebay.example
                        </a>
                    </li>

                    <li>
                        <span style="font-size:13px;">
                            Southern Coast, Sri Lanka
                        </span>
                    </li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom d-flex flex-column flex-md-row
                    justify-content-between gap-2">
            <span>
                &copy; <?= date('Y') ?> Azure Bay Resort. All rights reserved.
            </span>

            <span>
                A fictional resort website developed as a portfolio demo.
            </span>
        </div>

    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


</body>
</html>