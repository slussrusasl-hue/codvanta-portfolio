
<?php
session_start();

$pageTitle = "Customer Login | ProServe";

$errors = [];
$email = "";
$success = false;

// Demo login credentials for the portfolio demonstration.
// Do not use these credentials for a production system.
$demoEmail = "customer@proserve.demo";
$demoPassword = "ProServe123";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $loginType = $_POST["login_type"] ?? "demo";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($password === "") {
        $errors[] = "Please enter your password.";
    }

    if (!in_array($loginType, ["demo", "registered"], true)) {
        $errors[] = "Invalid login option.";
    }

    if (empty($errors)) {
        if ($loginType === "demo") {
            // Demo customer account
            if (
                strtolower($email) === strtolower($demoEmail) &&
                $password === $demoPassword
            ) {
                session_regenerate_id(true);

                $_SESSION["proserve_customer"] = [
                    "name" => "Demo Customer",
                    "email" => $demoEmail,
                    "account_type" => "individual",
                    "is_demo" => true
                ];

                $success = true;
            } else {
                $errors[] = "The demo email or password is incorrect. Use the demo credentials shown below.";
            }
        } else {
            /*
             * Registration data from register.php is stored in
             * $_SESSION["demo_customer"] for the current session.
             *
             * The registration page intentionally does not save
             * passwords, so registered-account login cannot verify
             * a password in this database-free demonstration.
             */
            $registeredCustomer = $_SESSION["demo_customer"] ?? null;

            if (
                is_array($registeredCustomer) &&
                isset($registeredCustomer["email"]) &&
                strtolower($email) === strtolower($registeredCustomer["email"])
            ) {
                $errors[] = "This demonstration registration does not save passwords. Please use the demo account credentials below.";
            } else {
                $errors[] = "No matching demo registration was found in this session. Use the demo account credentials below.";
            }
        }
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
          content="Sign in to the ProServe customer demonstration to explore service management features.">

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
            gap: 22px;
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
            width: min(1050px, 92%);
            margin: 48px auto;
            display: grid;
            grid-template-columns: 1fr 0.95fr;
            gap: 28px;
            align-items: stretch;
        }

        /* Welcome panel */

        .welcome-panel {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            padding: 45px 38px;
            color: white;
            background:
                radial-gradient(circle at 85% 15%, rgba(96,165,250,.23), transparent 30%),
                linear-gradient(145deg, #102344, #1d4ed8);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 610px;
        }

        .welcome-panel::after {
            content: "";
            position: absolute;
            width: 270px;
            height: 270px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 50%;
            right: -115px;
            bottom: -100px;
            box-shadow:
                0 0 0 35px rgba(255,255,255,.025),
                0 0 0 70px rgba(255,255,255,.025);
        }

        .welcome-content,
        .welcome-bottom {
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
            margin-bottom: 24px;
        }

        .welcome-panel h1 {
            font-size: clamp(32px, 4vw, 43px);
            line-height: 1.2;
            letter-spacing: -1.5px;
            margin-bottom: 20px;
        }

        .welcome-panel h1 span {
            color: #93c5fd;
        }

        .welcome-panel .intro {
            color: #d6e5fb;
            font-size: 13px;
            line-height: 1.9;
            margin-bottom: 33px;
        }

        .feature-list {
            list-style: none;
            display: grid;
            gap: 20px;
        }

        .feature-list li {
            display: flex;
            gap: 13px;
            align-items: flex-start;
        }

        .feature-icon {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 11px;
            font-size: 17px;
        }

        .feature-list strong {
            display: block;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .feature-list p {
            color: #c4d7f2;
            font-size: 11px;
            line-height: 1.7;
        }

        .welcome-bottom {
            margin-top: 35px;
            border-top: 1px solid rgba(255,255,255,.16);
            padding-top: 20px;
            color: #c4d7f2;
            font-size: 11px;
        }

        /* Login form */

        .form-panel {
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 39px;
            box-shadow: 0 10px 35px rgba(27,52,95,.045);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading {
            margin-bottom: 24px;
        }

        .form-heading h2 {
            color: var(--navy);
            font-size: 28px;
            letter-spacing: -0.8px;
            margin-bottom: 7px;
        }

        .form-heading p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .demo-notice {
            padding: 15px;
            border-radius: 11px;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            color: #1e40af;
            margin-bottom: 22px;
            font-size: 11px;
            line-height: 1.7;
        }

        .demo-notice strong {
            display: block;
            margin-bottom: 5px;
        }

        .credentials {
            background: white;
            border: 1px dashed #b8cdf8;
            border-radius: 9px;
            padding: 10px 12px;
            margin-top: 11px;
            color: #334155;
            overflow-wrap: anywhere;
        }

        .credential-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin: 5px 0;
        }

        .credential-row span {
            font-size: 10px;
            color: #64748b;
        }

        .credential-row code {
            font-size: 11px;
            font-weight: 700;
            color: var(--navy);
        }

        .fill-demo-btn {
            margin-top: 10px;
            border: 1px solid #bfdbfe;
            border-radius: 7px;
            padding: 8px 10px;
            color: var(--primary-dark);
            background: #f8fbff;
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .fill-demo-btn:hover {
            background: #dbeafe;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 10px;
            margin-bottom: 19px;
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

        .form-group {
            margin-bottom: 19px;
        }

        .form-group label {
            display: block;
            color: #34445e;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            min-height: 46px;
            padding: 12px 13px;
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
            right: 11px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--primary);
            cursor: pointer;
            font-size: 10px;
            font-weight: 800;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin: -2px 0 22px;
            font-size: 11px;
        }

        .remember-option {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #64748b;
        }

        .remember-option input {
            accent-color: var(--primary);
        }

        .forgot-link {
            color: var(--primary);
            font-weight: 700;
        }

        .submit-btn {
            display: block;
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

        .register-prompt {
            text-align: center;
            margin-top: 22px;
            color: var(--muted);
            font-size: 12px;
        }

        .register-prompt a {
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
            margin-top: 24px;
        }

        .success-panel {
            text-align: center;
            padding: 15px 0;
        }

        .success-icon {
            width: 74px;
            height: 74px;
            margin: 0 auto 20px;
            display: grid;
            place-items: center;
            background: #dcfce7;
            color: #15803d;
            border-radius: 50%;
            font-size: 33px;
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
            margin-bottom: 22px;
        }

        .success-actions {
            display: grid;
            gap: 11px;
        }

        .secondary-link {
            display: block;
            text-align: center;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--navy);
            font-size: 12px;
            font-weight: 700;
        }

        footer {
            text-align: center;
            padding: 22px 15px 28px;
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
                max-width: 620px;
                margin: 30px auto;
            }

            .welcome-panel {
                min-height: auto;
                padding: 35px;
            }

            .welcome-bottom {
                margin-top: 30px;
            }

            .form-panel {
                padding: 34px;
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
                gap: 11px;
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
                margin: 20px auto;
                gap: 17px;
            }

            .welcome-panel {
                padding: 28px 23px;
                border-radius: 15px;
            }

            .welcome-panel h1 {
                font-size: 32px;
            }

            .form-panel {
                padding: 26px 20px;
                border-radius: 15px;
            }

            .form-heading h2 {
                font-size: 25px;
            }

            .form-options {
                align-items: flex-start;
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
            <a href="register.php" class="nav-button">Create Account</a>
        </nav>
    </div>
</header>

<main class="page-layout">

    <section class="welcome-panel">
        <div class="welcome-content">
            <span class="eyebrow">Customer Portal</span>

            <h1>
                Welcome back.<br>
                Let's get things<br>
                <span>done together.</span>
            </h1>

            <p class="intro">
                Sign in to explore the ProServe customer experience.
                Discover services, submit enquiries, request quotations,
                and manage your service-related activities.
            </p>

            <ul class="feature-list">
                <li>
                    <span class="feature-icon">📋</span>
                    <div>
                        <strong>Track your enquiries</strong>
                        <p>Explore how service requests can be organized and followed up.</p>
                    </div>
                </li>

                <li>
                    <span class="feature-icon">📅</span>
                    <div>
                        <strong>Appointment management</strong>
                        <p>View the proposed workflow for booking and scheduling services.</p>
                    </div>
                </li>

                <li>
                    <span class="feature-icon">🧾</span>
                    <div>
                        <strong>Service and invoice overview</strong>
                        <p>See how a customer portal can bring important information together.</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="welcome-bottom">
            <strong>ProServe Demo Platform</strong><br>
            A service management system demonstration by Codvanta.
        </div>
    </section>

    <section class="form-panel">

        <?php if ($success): ?>

            <div class="success-panel">
                <div class="success-icon">✓</div>

                <h2>Login successful!</h2>

                <p>
                    Welcome back,
                    <?= e($_SESSION["proserve_customer"]["name"] ?? "Demo Customer") ?>.
                    You have signed in to the ProServe customer demonstration.
                </p>

                <div class="alert alert-success">
                    Your demo session is active. Explore the available customer-facing pages below.
                </div>

                <div class="success-actions">
                    <a href="services.php" class="submit-btn">
                        Explore Our Services →
                    </a>

                    <a href="index.php" class="secondary-link">
                        Return to Homepage
                    </a>
                </div>
            </div>

        <?php else: ?>

            <div class="form-heading">
                <h2>Sign in to ProServe</h2>
                <p>Enter the demo credentials or explore the registration workflow.</p>
            </div>

            <div class="demo-notice">
                <strong>🧪 Ready-to-use demo account</strong>
                Use the sample credentials below to demonstrate the login workflow.
                No database is required.

                <div class="credentials">
                    <div class="credential-row">
                        <span>Email</span>
                        <code>customer@proserve.demo</code>
                    </div>

                    <div class="credential-row">
                        <span>Password</span>
                        <code>ProServe123</code>
                    </div>

                    <button type="button" class="fill-demo-btn" id="fillDemo">
                        USE DEMO CREDENTIALS
                    </button>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error" role="alert">
                    <strong>Unable to sign in:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" id="loginForm">
                <input type="hidden" name="login_type" value="demo">

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter your email address"
                        value="<?= e($email) ?>"
                        maxlength="254"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="password-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button type="button" class="toggle-password"
                                id="togglePassword">
                            SHOW
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-option">
                        <input type="checkbox" id="remember">
                        Remember me
                    </label>

                    <a href="#" class="forgot-link" id="forgotPassword">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" class="submit-btn">
                    Sign In to Customer Portal →
                </button>
            </form>

            <div class="register-prompt">
                Don't have a demo account?
                <a href="register.php">Create an account</a>
            </div>

            <div class="security-note">
                <span>🔒</span>
                Demonstration only — do not use real credentials.
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
    // Toggle password visibility.
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    if (togglePassword && passwordField) {
        togglePassword.addEventListener("click", function () {
            const shouldShow = passwordField.type === "password";

            passwordField.type = shouldShow ? "text" : "password";
            togglePassword.textContent = shouldShow ? "HIDE" : "SHOW";
        });
    }

    // Fill the sample demo credentials.
    const fillDemoButton = document.getElementById("fillDemo");
    const emailField = document.getElementById("email");

    if (fillDemoButton && emailField && passwordField) {
        fillDemoButton.addEventListener("click", function () {
            emailField.value = "customer@proserve.demo";
            passwordField.value = "ProServe123";
            passwordField.type = "password";

            if (togglePassword) {
                togglePassword.textContent = "SHOW";
            }

            emailField.focus();
        });
    }

    // Demo-only forgot password message.
    const forgotPasswordLink = document.getElementById("forgotPassword");

    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener("click", function (event) {
            event.preventDefault();

            alert(
                "Password Recovery Demonstration\n\n" +
                "Password recovery is not connected to an email service or database.\n\n" +
                "Please use the sample demo credentials displayed on this page."
            );
        });
    }
</script>

</body>
</html>
