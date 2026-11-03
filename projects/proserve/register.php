
<?php
session_start();

$pageTitle = "Create Account | ProServe";

$errors = [];
$success = false;

$fullName = "";
$email = "";
$phone = "";
$company = "";
$accountType = "individual";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $company = trim($_POST["company"] ?? "");
    $accountType = $_POST["account_type"] ?? "individual";
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";
    $termsAccepted = isset($_POST["terms"]);

    // Server-side validation
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

    if (!in_array($accountType, ["individual", "business"], true)) {
        $errors[] = "Please select a valid account type.";
    }

    if ($accountType === "business" && $company === "") {
        $errors[] = "Please enter your business or company name.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Your password must contain at least 8 characters.";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Your password must contain at least one uppercase letter.";
    }

    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Your password must contain at least one lowercase letter.";
    }

    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Your password must contain at least one number.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "The password confirmation does not match.";
    }

    if (!$termsAccepted) {
        $errors[] = "Please accept the terms and conditions.";
    }

    if (empty($errors)) {
        /*
         * DEMONSTRATION MODE
         * This page does not create a persistent database account.
         * The registration is simulated for the portfolio demonstration.
         */

        $_SESSION["demo_customer"] = [
            "name" => $fullName,
            "email" => $email,
            "phone" => $phone,
            "company" => $accountType === "business" ? $company : "",
            "account_type" => $accountType,
            "registered_at" => date("Y-m-d H:i:s")
        ];

        // Do not store the plain-text password in the session.
        $success = true;
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($pageTitle) ?></title>

    <meta name="description"
          content="Create your ProServe customer demo account to explore service bookings, quotation requests, and customer service management.">

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
            --danger: #dc2626;
            --success: #15803d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: "Inter", "Trebuchet MS", sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        input,
        select,
        button {
            font: inherit;
        }

        .navbar {
            background: white;
            border-bottom: 1px solid var(--border);
        }

        .nav-container {
            width: min(1160px, 92%);
            min-height: 76px;
            margin: auto;
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
            letter-spacing: -0.8px;
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

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .nav-right a:not(.nav-button) {
            color: #53627a;
        }

        .nav-right a:hover {
            color: var(--primary);
        }

        .nav-button {
            background: var(--primary);
            color: white;
            padding: 10px 16px;
            border-radius: 9px;
        }

        .page-layout {
            width: min(1100px, 92%);
            margin: 45px auto;
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            align-items: stretch;
            gap: 28px;
        }

        /* Left information panel */

        .info-panel {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            padding: 43px 35px;
            color: white;
            background:
                radial-gradient(circle at 85% 15%, rgba(96,165,250,.23), transparent 30%),
                linear-gradient(145deg, #102344, #1d4ed8);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 650px;
        }

        .info-panel::after {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 50%;
            right: -110px;
            bottom: -90px;
            box-shadow:
                0 0 0 35px rgba(255,255,255,.025),
                0 0 0 70px rgba(255,255,255,.025);
        }

        .info-content,
        .info-bottom {
            position: relative;
            z-index: 1;
        }

        .eyebrow {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 30px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.17);
            color: #bfdbfe;
            font-size: 10px;
            letter-spacing: 1.1px;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 22px;
        }

        .info-panel h1 {
            font-size: clamp(30px, 3.5vw, 41px);
            line-height: 1.2;
            letter-spacing: -1.5px;
            margin-bottom: 19px;
        }

        .info-panel h1 span {
            color: #93c5fd;
        }

        .info-panel .intro {
            color: #d6e5fb;
            font-size: 13px;
            line-height: 1.9;
            margin-bottom: 30px;
        }

        .benefit-list {
            list-style: none;
            display: grid;
            gap: 20px;
        }

        .benefit-list li {
            display: flex;
            gap: 13px;
            align-items: flex-start;
        }

        .benefit-icon {
            flex-shrink: 0;
            display: grid;
            place-items: center;
            width: 39px;
            height: 39px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 11px;
            font-size: 17px;
        }

        .benefit-list strong {
            display: block;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .benefit-list p {
            color: #c4d7f2;
            font-size: 11px;
            line-height: 1.7;
        }

        .info-bottom {
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,.16);
            padding-top: 20px;
            color: #c4d7f2;
            font-size: 11px;
        }

        /* Registration form */

        .form-panel {
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 38px;
            box-shadow: 0 10px 35px rgba(27,52,95,.045);
        }

        .form-heading {
            margin-bottom: 25px;
        }

        .form-heading h2 {
            color: var(--navy);
            font-size: 27px;
            letter-spacing: -0.8px;
            margin-bottom: 6px;
        }

        .form-heading p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .demo-notice {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            color: #1e40af;
            font-size: 11px;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        .demo-notice strong {
            display: block;
            margin-bottom: 2px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 12px;
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
            padding: 15px 0 5px;
        }

        .success-icon {
            width: 76px;
            height: 76px;
            margin: 0 auto 20px;
            display: grid;
            place-items: center;
            background: #dcfce7;
            color: #15803d;
            border-radius: 50%;
            font-size: 34px;
        }

        .success-panel h2 {
            color: var(--navy);
            font-size: 26px;
            margin-bottom: 10px;
        }

        .success-panel p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.9;
            margin-bottom: 23px;
        }

        .account-summary {
            text-align: left;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 17px;
            margin-bottom: 23px;
            background: #f8faff;
        }

        .account-summary h3 {
            color: var(--navy);
            font-size: 13px;
            margin-bottom: 12px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 8px 0;
            border-bottom: 1px solid #e8edf5;
            font-size: 11px;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row span:first-child {
            color: var(--muted);
        }

        .summary-row span:last-child {
            color: var(--navy);
            font-weight: 700;
            text-align: right;
            overflow-wrap: anywhere;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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
            color: #dc2626;
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

        .password-wrap {
            position: relative;
        }

        .password-wrap .form-control {
            padding-right: 65px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--primary);
            cursor: pointer;
            font-size: 10px;
            font-weight: 800;
        }

        .field-hint {
            color: #8995a8;
            font-size: 10px;
            margin-top: 6px;
        }

        .password-rules {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 7px;
            margin-top: 10px;
        }

        .password-rule {
            font-size: 10px;
            color: #8995a8;
        }

        .password-rule.valid {
            color: var(--success);
        }

        .password-rule::before {
            content: "○";
            margin-right: 6px;
        }

        .password-rule.valid::before {
            content: "✓";
            font-weight: 800;
        }

        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 7px 0 21px;
            color: #64748b;
            font-size: 11px;
            line-height: 1.8;
        }

        .terms-row input {
            margin-top: 5px;
            accent-color: var(--primary);
            flex-shrink: 0;
        }

        .terms-row a {
            color: var(--primary);
            font-weight: 700;
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

        .login-prompt {
            text-align: center;
            margin-top: 20px;
            color: var(--muted);
            font-size: 12px;
        }

        .login-prompt a {
            color: var(--primary);
            font-weight: 800;
        }

        .security-note {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            color: #8793a6;
            font-size: 10px;
            margin-top: 22px;
        }

        footer {
            text-align: center;
            padding: 20px 15px 25px;
            color: #8290a5;
            font-size: 11px;
        }

        footer a {
            color: var(--primary);
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .page-layout {
                grid-template-columns: 1fr;
                max-width: 650px;
                margin: 30px auto;
            }

            .info-panel {
                min-height: auto;
                padding: 35px;
            }

            .info-bottom {
                margin-top: 30px;
            }

            .form-panel {
                padding: 32px;
            }
        }

        @media (max-width: 560px) {
            .nav-container {
                min-height: 68px;
            }

            .brand {
                font-size: 20px;
            }

            .brand-icon {
                width: 39px;
                height: 39px;
            }

            .nav-right {
                gap: 10px;
                font-size: 11px;
            }

            .nav-right .nav-home {
                display: none;
            }

            .nav-button {
                padding: 9px 11px;
            }

            .page-layout {
                width: 94%;
                gap: 18px;
                margin: 20px auto;
            }

            .info-panel {
                padding: 28px 23px;
                border-radius: 15px;
            }

            .info-panel h1 {
                font-size: 31px;
            }

            .form-panel {
                padding: 25px 20px;
                border-radius: 15px;
            }

            .form-heading h2 {
                font-size: 24px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .password-rules {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="nav-container">
        <a href="index.php" class="brand" aria-label="ProServe homepage">
            <span class="brand-icon">P</span>
            <span>
                ProServe
                <small>SERVICE MANAGEMENT</small>
            </span>
        </a>

        <nav class="nav-right">
            <a href="index.php" class="nav-home">Home</a>
            <a href="services.php">Services</a>
            <a href="login.php" class="nav-button">Sign In</a>
        </nav>
    </div>
</header>

<main class="page-layout">

    <section class="info-panel">
        <div class="info-content">
            <span class="eyebrow">Welcome to ProServe</span>

            <h1>
                One account.<br>
                Better service.<br>
                <span>Less hassle.</span>
            </h1>

            <p class="intro">
                Create your customer account and explore a simpler way to
                request services, schedule appointments, and keep track of
                your service enquiries.
            </p>

            <ul class="benefit-list">
                <li>
                    <span class="benefit-icon">📋</span>
                    <div>
                        <strong>Manage service requests</strong>
                        <p>Keep your service enquiries and quotation requests organized.</p>
                    </div>
                </li>

                <li>
                    <span class="benefit-icon">📅</span>
                    <div>
                        <strong>Schedule appointments</strong>
                        <p>Submit appointment requests for your preferred services.</p>
                    </div>
                </li>

                <li>
                    <span class="benefit-icon">🧾</span>
                    <div>
                        <strong>Keep track of invoices</strong>
                        <p>Explore how customers can review invoices and service details.</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="info-bottom">
            <strong>ProServe Demo Platform</strong><br>
            A service management system demonstration by Codvanta.
        </div>
    </section>

    <section class="form-panel">
        <?php if ($success): ?>

            <div class="success-panel">
                <div class="success-icon">✓</div>

                <h2>Registration successful!</h2>

                <p>
                    Welcome to ProServe, <?= e($fullName) ?>.
                    Your demo registration has been completed for this session.
                </p>

                <div class="alert alert-success">
                    <strong>Demo account created for this session.</strong><br>
                    This is a demonstration only. Your account is not stored
                    permanently in a database, and you should not use a real password.
                </div>

                <div class="account-summary">
                    <h3>Account summary</h3>

                    <div class="summary-row">
                        <span>Customer name</span>
                        <span><?= e($fullName) ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Email address</span>
                        <span><?= e($email) ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Contact number</span>
                        <span><?= e($phone !== "" ? $phone : "Not provided") ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Account type</span>
                        <span>
                            <?= $accountType === "business" ? "Business" : "Individual" ?>
                        </span>
                    </div>

                    <?php if ($accountType === "business"): ?>
                        <div class="summary-row">
                            <span>Company</span>
                            <span><?= e($company) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="services.php" class="submit-btn"
                   style="display:block;">
                    Explore Our Services →
                </a>

                <p style="margin-top:18px; margin-bottom:0;">
                    <a href="index.php" style="color:#2563eb;font-weight:700;">
                        Return to homepage
                    </a>
                </p>
            </div>

        <?php else: ?>

            <div class="form-heading">
                <h2>Create your account</h2>
                <p>Enter your details below to explore the ProServe customer experience.</p>
            </div>

            <div class="demo-notice">
                <span>ℹ️</span>
                <div>
                    <strong>Portfolio demonstration</strong>
                    This form simulates registration. Do not enter a real password
                    or sensitive personal information.
                </div>
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

            <form method="POST" action="register.php" id="registerForm">
                <div class="form-group">
                    <label for="full_name">
                        Full name <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        class="form-control"
                        placeholder="Enter your full name"
                        value="<?= e($fullName) ?>"
                        minlength="2"
                        maxlength="100"
                        autocomplete="name"
                        required
                    >
                </div>

                <div class="form-row">
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

                    <div class="form-group">
                        <label for="phone">Contact number</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="form-control"
                            placeholder="+94 7X XXX XXXX"
                            value="<?= e($phone) ?>"
                            maxlength="25"
                            autocomplete="tel"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="account_type">
                        Account type <span class="required">*</span>
                    </label>
                    <select
                        id="account_type"
                        name="account_type"
                        class="form-control"
                        required
                    >
                        <option value="individual"
                            <?= $accountType === "individual" ? "selected" : "" ?>>
                            Individual customer
                        </option>
                        <option value="business"
                            <?= $accountType === "business" ? "selected" : "" ?>>
                            Business customer
                        </option>
                    </select>
                </div>

                <div class="form-group" id="companyGroup"
                     style="<?= $accountType === "business" ? "" : "display:none;" ?>">
                    <label for="company">
                        Business / company name <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="company"
                        name="company"
                        class="form-control"
                        placeholder="Enter your company name"
                        value="<?= e($company) ?>"
                        maxlength="150"
                        autocomplete="organization"
                        <?= $accountType === "business" ? "required" : "" ?>
                    >
                </div>

                <div class="form-group">
                    <label for="password">
                        Create password <span class="required">*</span>
                    </label>
                    <div class="password-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Create a demo password"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                        <button type="button" class="toggle-password"
                                data-target="password">SHOW</button>
                    </div>

                    <div class="password-rules">
                        <span class="password-rule" id="ruleLength">At least 8 characters</span>
                        <span class="password-rule" id="ruleUpper">One uppercase letter</span>
                        <span class="password-rule" id="ruleLower">One lowercase letter</span>
                        <span class="password-rule" id="ruleNumber">One number</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">
                        Confirm password <span class="required">*</span>
                    </label>
                    <div class="password-wrap">
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            class="form-control"
                            placeholder="Enter your password again"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                        <button type="button" class="toggle-password"
                                data-target="confirm_password">SHOW</button>
                    </div>
                    <p class="field-hint" id="passwordMatchMessage">
                        Enter the same password in both fields.
                    </p>
                </div>

                <label class="terms-row">
                    <input type="checkbox" name="terms" id="terms" required>
                    <span>
                        I agree to the demonstration
                        <a href="#" id="termsLink">terms and conditions</a>
                        and understand that this page does not create a permanent
                        account or provide a real service.
                    </span>
                </label>

                <button type="submit" class="submit-btn">
                    Create Demo Account →
                </button>
            </form>

            <div class="login-prompt">
                Already have a demo account?
                <a href="login.php">Sign in here</a>
            </div>

            <div class="security-note">
                <span>🔒</span>
                Demo only — never use a real password here.
            </div>

        <?php endif; ?>
    </section>

</main>

<footer>
    <p>
        &copy; <?= date("Y") ?> ProServe.
        Portfolio demonstration by
        <a href="../index.php">Codvanta</a>.
    </p>
</footer>

<script>
    // Show/hide password fields
    document.querySelectorAll(".toggle-password").forEach(function (button) {
        button.addEventListener("click", function () {
            const field = document.getElementById(this.dataset.target);

            if (!field) return;

            const showPassword = field.type === "password";
            field.type = showPassword ? "text" : "password";
            this.textContent = showPassword ? "HIDE" : "SHOW";
        });
    });

    // Show company name only for business accounts
    const accountType = document.getElementById("account_type");
    const companyGroup = document.getElementById("companyGroup");
    const companyInput = document.getElementById("company");

    function updateCompanyField() {
        if (!accountType || !companyGroup || !companyInput) return;

        const isBusiness = accountType.value === "business";

        companyGroup.style.display = isBusiness ? "block" : "none";
        companyInput.required = isBusiness;

        if (!isBusiness) {
            companyInput.value = "";
        }
    }

    if (accountType) {
        accountType.addEventListener("change", updateCompanyField);
        updateCompanyField();
    }

    // Live password requirement indicators
    const passwordInput = document.getElementById("password");

    const passwordRules = [
        {
            id: "ruleLength",
            test: value => value.length >= 8
        },
        {
            id: "ruleUpper",
            test: value => /[A-Z]/.test(value)
        },
        {
            id: "ruleLower",
            test: value => /[a-z]/.test(value)
        },
        {
            id: "ruleNumber",
            test: value => /[0-9]/.test(value)
        }
    ];

    function updatePasswordRules() {
        if (!passwordInput) return;

        const value = passwordInput.value;

        passwordRules.forEach(function (rule) {
            const element = document.getElementById(rule.id);

            if (element) {
                element.classList.toggle("valid", rule.test(value));
            }
        });

        updatePasswordMatch();
    }

    if (passwordInput) {
        passwordInput.addEventListener("input", updatePasswordRules);
    }

    // Confirm both passwords match
    const confirmPasswordInput = document.getElementById("confirm_password");
    const passwordMatchMessage = document.getElementById("passwordMatchMessage");

    function updatePasswordMatch() {
        if (!passwordInput || !confirmPasswordInput || !passwordMatchMessage) {
            return;
        }

        if (confirmPasswordInput.value === "") {
            passwordMatchMessage.textContent =
                "Enter the same password in both fields.";
            passwordMatchMessage.style.color = "#8995a8";
            return;
        }

        if (passwordInput.value === confirmPasswordInput.value) {
            passwordMatchMessage.textContent = "✓ Passwords match.";
            passwordMatchMessage.style.color = "#15803d";
        } else {
            passwordMatchMessage.textContent = "Passwords do not match.";
            passwordMatchMessage.style.color = "#dc2626";
        }
    }

    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener("input", updatePasswordMatch);
    }

    // Prevent submission when passwords do not match or requirements are unmet.
    const registerForm = document.getElementById("registerForm");

    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            updatePasswordRules();
            updatePasswordMatch();

            const value = passwordInput.value;

            const passwordValid = passwordRules.every(rule => rule.test(value));
            const passwordsMatch =
                value === confirmPasswordInput.value;

            if (!passwordValid || !passwordsMatch) {
                event.preventDefault();

                if (!passwordValid) {
                    passwordInput.focus();
                } else {
                    confirmPasswordInput.focus();
                }
            }
        });
    }

    // Demo terms notice
    const termsLink = document.getElementById("termsLink");

    if (termsLink) {
        termsLink.addEventListener("click", function (event) {
            event.preventDefault();

            alert(
                "ProServe Demonstration Terms\n\n" +
                "1. This website is a portfolio demonstration.\n" +
                "2. Registration details are kept only in the current PHP session.\n" +
                "3. No real service request or payment is created by registering.\n" +
                "4. Do not enter real passwords or sensitive personal information."
            );
        });
    }
</script>

</body>
</html>
