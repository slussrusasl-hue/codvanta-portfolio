
<?php
// FitCore - Member Registration
// Codvanta Portfolio Demonstration

$plans = [
    'basic' => [
        'name' => 'BASIC',
        'price' => 20,
        'description' => 'A great starting point for your fitness journey.'
    ],
    'pro' => [
        'name' => 'PRO',
        'price' => 40,
        'description' => 'For members who want to take their training further.'
    ],
    'premium' => [
        'name' => 'PREMIUM',
        'price' => 70,
        'description' => 'A premium fitness experience with personalized support.'
    ]
];

// Select a plan passed from the homepage, if valid.
$selectedPlan = strtolower($_GET['plan'] ?? 'pro');

if (!isset($plans[$selectedPlan])) {
    $selectedPlan = 'pro';
}

$errors = [];
$fullName = '';
$email = '';
$phone = '';
$gender = '';
$dateOfBirth = '';
$selectedPlan = strtolower($_POST['membership'] ?? $selectedPlan);

if (!isset($plans[$selectedPlan])) {
    $selectedPlan = 'pro';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $dateOfBirth = $_POST['date_of_birth'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $termsAccepted = isset($_POST['terms']);

    // Basic server-side validation.
    if ($fullName === '' || strlen($fullName) < 3) {
        $errors[] = 'Please enter your full name (at least 3 characters).';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($phone === '' || !preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
        $errors[] = 'Please enter a valid contact number.';
    }

    if (!in_array($gender, ['Male', 'Female', 'Other', 'Prefer not to say'], true)) {
        $errors[] = 'Please select a gender option.';
    }

    if ($dateOfBirth === '') {
        $errors[] = 'Please select your date of birth.';
    } else {
        $birthDate = DateTime::createFromFormat('Y-m-d', $dateOfBirth);
        $dateErrors = DateTime::getLastErrors();

        if (
            !$birthDate ||
            ($dateErrors !== false &&
                ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) ||
            $birthDate->format('Y-m-d') !== $dateOfBirth ||
            $birthDate >= new DateTime('today')
        ) {
            $errors[] = 'Please enter a valid date of birth in the past.';
        }
    }

    if (strlen($password) < 8) {
        $errors[] = 'Your password must contain at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'The password and confirmation password do not match.';
    }

    if (!$termsAccepted) {
        $errors[] = 'Please accept the demonstration terms to continue.';
    }

    if (!isset($plans[$selectedPlan])) {
        $errors[] = 'Please select a valid membership plan.';
    }

    /*
     * Demonstration only:
     * No member is saved to a database.
     * No password is stored.
     * No real payment is processed.
     *
     * When validation succeeds, pass the selected plan and
     * sample registration details to the demo payment page.
     */
    if (empty($errors)) {
        session_start();

        $_SESSION['fitcore_demo_member'] = [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'gender' => $gender,
            'date_of_birth' => $dateOfBirth,
            'membership' => $selectedPlan,
            'membership_name' => $plans[$selectedPlan]['name'],
            'membership_price' => $plans[$selectedPlan]['price']
        ];

        header('Location: membership_payment.php');
        exit;
    }
}

$plan = $plans[$selectedPlan];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Join FitCore | Member Registration</title>

    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --primary: #ff6b2c;
            --primary-hover: #e9561b;
            --background: #0b0d10;
            --surface: #15191f;
            --surface-light: #1d222a;
            --text: #f5f5f5;
            --muted: #a6adb7;
            --border: rgba(255, 255, 255, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: rgba(11, 13, 16, 0.97);
            border-bottom: 1px solid var(--border);
            padding: 15px 0;
        }

        .navbar-brand {
            color: #fff !important;
            font-size: 1.6rem;
            font-weight: 900;
            letter-spacing: 1px;
        }

        .brand-highlight {
            color: var(--primary);
        }

        .nav-link {
            color: #c6cbd3 !important;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .btn-fitcore {
            display: inline-block;
            background: var(--primary);
            border: 1px solid var(--primary);
            border-radius: 7px;
            color: #fff;
            padding: 12px 22px;
            font-weight: 800;
            transition: 0.3s;
        }

        .btn-fitcore:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-outline-fitcore {
            display: inline-block;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 7px;
            color: #fff;
            padding: 11px 20px;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-outline-fitcore:hover {
            color: var(--primary);
            border-color: var(--primary);
        }

        .page-banner {
            padding: 55px 0 45px;
            background:
                linear-gradient(
                    120deg,
                    rgba(255, 107, 44, 0.10),
                    transparent 65%
                ),
                #101318;
            border-bottom: 1px solid var(--border);
        }

        .eyebrow {
            display: inline-block;
            color: var(--primary);
            background: rgba(255, 107, 44, 0.10);
            border: 1px solid rgba(255, 107, 44, 0.25);
            border-radius: 30px;
            padding: 6px 14px;
            font-size: 0.78rem;
            font-weight: 900;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
        }

        .page-banner h1 {
            font-size: clamp(2.2rem, 5vw, 3.6rem);
            font-weight: 900;
            margin-bottom: 12px;
        }

        .page-banner h1 span {
            color: var(--primary);
        }

        .page-banner p {
            color: var(--muted);
            max-width: 700px;
            margin-bottom: 0;
        }

        .registration-section {
            padding: 55px 0 80px;
        }

        .registration-card,
        .membership-summary {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 15px;
        }

        .registration-card {
            padding: 32px;
        }

        .membership-summary {
            padding: 25px;
            position: sticky;
            top: 100px;
        }

        .card-heading {
            font-size: 1.35rem;
            font-weight: 900;
            margin-bottom: 6px;
        }

        .muted-text {
            color: var(--muted);
            font-size: 0.91rem;
        }

        .form-label {
            color: #e5e7eb;
            font-weight: 700;
            font-size: 0.92rem;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background-color: #101318;
            color: #f5f5f5;
            border: 1px solid #343b46;
            border-radius: 7px;
            padding: 12px 13px;
        }

        .form-control::placeholder {
            color: #777f8c;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #101318;
            color: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 44, 0.15);
        }

        .form-select option {
            background: #15191f;
            color: #fff;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(1);
        }

        .section-divider {
            border-color: var(--border);
            margin: 27px 0;
        }

        .plan-option {
            display: block;
            height: 100%;
            cursor: pointer;
        }

        .plan-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .plan-option-card {
            height: 100%;
            padding: 17px;
            background: #101318;
            border: 1px solid #343b46;
            border-radius: 10px;
            transition: 0.2s;
        }

        .plan-option input:checked + .plan-option-card {
            border: 2px solid var(--primary);
            background: rgba(255, 107, 44, 0.07);
        }

        .plan-option input:focus-visible + .plan-option-card {
            outline: 2px solid #fff;
            outline-offset: 3px;
        }

        .plan-option-card:hover {
            border-color: var(--primary);
        }

        .plan-option-name {
            font-size: 0.85rem;
            font-weight: 900;
            letter-spacing: 1px;
            color: var(--primary);
        }

        .plan-option-price {
            font-size: 1.8rem;
            font-weight: 900;
            margin: 5px 0;
        }

        .plan-option-price small {
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 400;
        }

        .plan-option-description {
            color: var(--muted);
            font-size: 0.82rem;
            margin-bottom: 0;
        }

        .form-check-input {
            background-color: #101318;
            border-color: #525b68;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 44, 0.15);
        }

        .form-check-label {
            color: #c8cdd5;
            font-size: 0.88rem;
        }

        .membership-summary h3 {
            font-size: 1.2rem;
            font-weight: 900;
            margin-bottom: 20px;
        }

        .summary-label {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .summary-price {
            color: var(--primary);
            font-size: 2.7rem;
            font-weight: 900;
            line-height: 1.25;
        }

        .summary-price small {
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 400;
        }

        .summary-plan-name {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 30px;
            color: var(--primary);
            background: rgba(255, 107, 44, 0.12);
            font-size: 0.78rem;
            font-weight: 900;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .summary-benefit {
            color: #d5d9df;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .summary-benefit span {
            color: var(--primary);
            margin-right: 9px;
        }

        .payment-notice {
            padding: 15px;
            background: rgba(255, 255, 255, 0.035);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--muted);
            font-size: 0.83rem;
            margin-top: 20px;
        }

        .security-note {
            text-align: center;
            color: var(--muted);
            font-size: 0.8rem;
            margin-top: 15px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.10);
            border: 1px solid rgba(220, 53, 69, 0.4);
            color: #ffb4bb;
        }

        .footer {
            background: #08090b;
            border-top: 1px solid var(--border);
            padding: 28px 0;
        }

        .footer p {
            color: var(--muted);
            font-size: 0.86rem;
            margin: 4px 0;
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
        }

        @media (max-width: 991px) {
            .membership-summary {
                position: static;
            }
        }

        @media (max-width: 767px) {
            .page-banner {
                padding: 40px 0;
            }

            .registration-section {
                padding: 35px 0 55px;
            }

            .registration-card {
                padding: 22px 18px;
            }

            .membership-summary {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
            FIT<span class="brand-highlight">CORE</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#registerNavbar"
                aria-controls="registerNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="registerNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php#membership">
                        Membership
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="trainers.php">Trainers</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="classes.php">Classes</a>
                </li>

                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a class="btn-outline-fitcore" href="../fitcore_demo.php">
                        Back to Demo
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>


<!-- PAGE HEADER -->
<section class="page-banner">
    <div class="container">

        <span class="eyebrow">BECOME A MEMBER</span>

        <h1>
            Start Your <span>Fitness Journey.</span>
        </h1>

        <p>
            Create your FitCore member profile, choose your preferred
            membership plan, and continue to the demonstration payment
            process.
        </p>

    </div>
</section>


<!-- REGISTRATION CONTENT -->
<section class="registration-section">

    <div class="container">

        <?php if (!empty($errors)): ?>

            <div class="alert alert-danger mb-4" role="alert">

                <h5 class="fw-bold">Please check the following details:</h5>

                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>

            </div>

        <?php endif; ?>


        <div class="row g-4 align-items-start">

            <!-- REGISTRATION FORM -->
            <div class="col-lg-8">

                <div class="registration-card">

                    <h2 class="card-heading">Member Registration</h2>

                    <p class="muted-text mb-4">
                        Fill in your information to create your demonstration
                        membership profile.
                    </p>

                    <form method="POST" action="register.php" id="registrationForm">

                        <!-- PERSONAL DETAILS -->
                        <h3 class="h6 fw-bold mb-3">
                            01 &nbsp; Personal Information
                        </h3>

                        <div class="row g-3">

                            <div class="col-12">
                                <label for="full_name" class="form-label">
                                    Full Name *
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="full_name"
                                    name="full_name"
                                    placeholder="Enter your full name"
                                    value="<?php echo htmlspecialchars($fullName); ?>"
                                    minlength="3"
                                    maxlength="100"
                                    autocomplete="name"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email Address *
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="you@example.com"
                                    value="<?php echo htmlspecialchars($email); ?>"
                                    maxlength="150"
                                    autocomplete="email"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    Contact Number *
                                </label>

                                <input
                                    type="tel"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    placeholder="+94 77 123 4567"
                                    value="<?php echo htmlspecialchars($phone); ?>"
                                    maxlength="20"
                                    autocomplete="tel"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="gender" class="form-label">
                                    Gender *
                                </label>

                                <select class="form-select" id="gender"
                                        name="gender" required>

                                    <option value="">Select gender</option>

                                    <?php
                                    $genders = [
                                        'Male',
                                        'Female',
                                        'Other',
                                        'Prefer not to say'
                                    ];
                                    ?>

                                    <?php foreach ($genders as $option): ?>
                                        <option
                                            value="<?php echo htmlspecialchars($option); ?>"
                                            <?php echo $gender === $option ? 'selected' : ''; ?>
                                        >
                                            <?php echo htmlspecialchars($option); ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">
                                    Date of Birth *
                                </label>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="date_of_birth"
                                    name="date_of_birth"
                                    value="<?php echo htmlspecialchars($dateOfBirth); ?>"
                                    max="<?php echo date('Y-m-d', strtotime('-1 day')); ?>"
                                    required
                                >
                            </div>

                        </div>


                        <hr class="section-divider">


                        <!-- MEMBERSHIP SELECTION -->
                        <h3 class="h6 fw-bold mb-2">
                            02 &nbsp; Choose Your Membership
                        </h3>

                        <p class="muted-text mb-3">
                            Select the monthly plan that best suits your goals.
                        </p>

                        <div class="row g-3">

                            <?php foreach ($plans as $key => $membership): ?>

                                <div class="col-md-4">

                                    <label class="plan-option">

                                        <input
                                            type="radio"
                                            name="membership"
                                            value="<?php echo htmlspecialchars($key); ?>"
                                            data-name="<?php echo htmlspecialchars($membership['name']); ?>"
                                            data-price="<?php echo (int) $membership['price']; ?>"
                                            <?php echo $selectedPlan === $key ? 'checked' : ''; ?>
                                            required
                                        >

                                        <div class="plan-option-card">

                                            <div class="plan-option-name">
                                                <?php echo htmlspecialchars($membership['name']); ?>
                                            </div>

                                            <div class="plan-option-price">
                                                $<?php echo number_format($membership['price'], 0); ?>

                                                <small>/mo</small>
                                            </div>

                                            <p class="plan-option-description">
                                                <?php echo htmlspecialchars($membership['description']); ?>
                                            </p>

                                        </div>

                                    </label>

                                </div>

                            <?php endforeach; ?>

                        </div>


                        <hr class="section-divider">


                        <!-- ACCOUNT DETAILS -->
                        <h3 class="h6 fw-bold mb-3">
                            03 &nbsp; Create Your Account
                        </h3>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label for="password" class="form-label">
                                    Password *
                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    placeholder="Minimum 8 characters"
                                    minlength="8"
                                    autocomplete="new-password"
                                    required
                                >

                                <small class="muted-text">
                                    Use at least 8 characters.
                                </small>

                            </div>

                            <div class="col-md-6">

                                <label for="confirm_password" class="form-label">
                                    Confirm Password *
                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="confirm_password"
                                    name="confirm_password"
                                    placeholder="Re-enter your password"
                                    minlength="8"
                                    autocomplete="new-password"
                                    required
                                >

                                <small id="passwordMessage"
                                       class="muted-text"
                                       aria-live="polite">
                                    Enter the same password again.
                                </small>

                            </div>

                        </div>


                        <div class="form-check mt-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                value="1"
                                id="terms"
                                name="terms"
                                <?php echo isset($_POST['terms']) ? 'checked' : ''; ?>
                                required
                            >

                            <label class="form-check-label" for="terms">
                                I understand this is a portfolio demonstration
                                and no real payment will be processed.
                            </label>

                        </div>


                        <div class="d-flex flex-wrap gap-3 mt-4">

                            <button type="submit" class="btn-fitcore">
                                Continue to Payment &rarr;
                            </button>

                            <a href="index.php" class="btn-outline-fitcore">
                                Cancel
                            </a>

                        </div>

                        <p class="security-note text-start">
                            * Required fields. This demonstration does not
                            save registration data permanently.
                        </p>

                    </form>

                </div>
            </div>


            <!-- MEMBERSHIP SUMMARY -->
            <div class="col-lg-4">

                <aside class="membership-summary">

                    <h3>Your Membership Summary</h3>

                    <span class="summary-plan-name" id="summaryPlan">
                        <?php echo htmlspecialchars($plan['name']); ?>
                    </span>

                    <div class="summary-price">
                        $<span id="summaryPrice">
                            <?php echo number_format($plan['price'], 0); ?>
                        </span>

                        <small>/ month</small>
                    </div>

                    <p class="muted-text mt-2" id="summaryDescription">
                        <?php echo htmlspecialchars($plan['description']); ?>
                    </p>

                    <hr class="section-divider">

                    <p class="fw-bold mb-3">Your next steps</p>

                    <div class="summary-benefit">
                        <span>&#10003;</span>
                        Complete your member details
                    </div>

                    <div class="summary-benefit">
                        <span>&#10003;</span>
                        Select your preferred plan
                    </div>

                    <div class="summary-benefit">
                        <span>&#10003;</span>
                        Review the simulated payment
                    </div>

                    <div class="summary-benefit">
                        <span>&#10003;</span>
                        Explore the member portal
                    </div>

                    <div class="payment-notice">
                        <strong style="color: #fff;">Demo payment only</strong>
                        <br><br>

                        The selected price represents one month of membership.
                        This page does not collect card information or process
                        actual payments.
                    </div>

                    <a href="index.php#membership"
                       class="btn-outline-fitcore w-100 text-center mt-4">
                        Compare Membership Plans
                    </a>

                </aside>

            </div>

        </div>

    </div>

</section>


<!-- FOOTER -->
<footer class="footer">

    <div class="container">

        <div class="row gy-3 align-items-center">

            <div class="col-md-6 text-center text-md-start">

                <a href="index.php" class="navbar-brand">
                    FIT<span class="brand-highlight">CORE</span>
                </a>

                <p>Train Smarter. Get Stronger. Live Better.</p>

            </div>

            <div class="col-md-6 text-center text-md-end">

                <p>
                    &copy; <?php echo date('Y'); ?> FitCore.
                    Demonstration by
                    <a href="../index.php">Codvanta</a>.
                </p>

                <p>Turning Vision into Digital Reality.</p>

            </div>

        </div>

    </div>

</footer>


<!-- Local Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Update the membership summary when a plan is selected.
    const planInputs = document.querySelectorAll(
        'input[name="membership"]'
    );

    const summaryPlan = document.getElementById('summaryPlan');
    const summaryPrice = document.getElementById('summaryPrice');
    const summaryDescription = document.getElementById('summaryDescription');

    const planDescriptions = {
        basic: 'A great starting point for your fitness journey.',
        pro: 'For members who want to take their training further.',
        premium: 'A premium fitness experience with personalized support.'
    };

    planInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            if (!this.checked) {
                return;
            }

            summaryPlan.textContent = this.dataset.name;
            summaryPrice.textContent = this.dataset.price;
            summaryDescription.textContent =
                planDescriptions[this.value] || '';
        });
    });

    // Check that both password fields match before submission.
    const registrationForm = document.getElementById('registrationForm');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const passwordMessage = document.getElementById('passwordMessage');

    function validatePasswordMatch() {
        if (confirmPassword.value === '') {
            confirmPassword.setCustomValidity('');
            passwordMessage.textContent = 'Enter the same password again.';
            passwordMessage.style.color = '';
            return;
        }

        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match.');
            passwordMessage.textContent = 'Passwords do not match.';
            passwordMessage.style.color = '#ff8b96';
        } else {
            confirmPassword.setCustomValidity('');
            passwordMessage.textContent = 'Passwords match.';
            passwordMessage.style.color = '#91e6a3';
        }
    }

    password.addEventListener('input', validatePasswordMatch);
    confirmPassword.addEventListener('input', validatePasswordMatch);

    registrationForm.addEventListener('submit', function (event) {
        validatePasswordMatch();

        if (!registrationForm.checkValidity()) {
            event.preventDefault();
            registrationForm.reportValidity();
        }
    });
</script>

</body>
</html>