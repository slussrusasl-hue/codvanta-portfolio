<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe CRM - Administrator Login
|--------------------------------------------------------------------------
| Session-based demonstration login.
| No database is required.
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// If the administrator is already logged in, open the dashboard.
if (!empty($_SESSION['proserve_admin'])) {
    header('Location: index.php');
    exit;
}

// Demo administrator credentials.
$adminEmail = 'admin@proserve.demo';
$adminPassword = 'Admin123';
$adminName = 'ProServe Administrator';

if (empty($_SESSION['proserve_admin_login_csrf'])) {
    $_SESSION['proserve_admin_login_csrf'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';

// Display the message created by admin/logout.php.
if (!empty($_SESSION['proserve_admin_logout_message'])) {
    $success = $_SESSION['proserve_admin_logout_message'];
    unset($_SESSION['proserve_admin_logout_message']);
}

// Process login form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    $submittedEmail = trim((string) ($_POST['email'] ?? ''));
    $submittedPassword = (string) ($_POST['password'] ?? '');

    if (
        !is_string($submittedToken) ||
        !hash_equals($_SESSION['proserve_admin_login_csrf'], $submittedToken)
    ) {
        $error = 'Your session has expired. Please refresh the page and try again.';
    } elseif ($submittedEmail === '' || $submittedPassword === '') {
        $error = 'Please enter both your email address and password.';
    } elseif (
        !filter_var($submittedEmail, FILTER_VALIDATE_EMAIL)
    ) {
        $error = 'Please enter a valid email address.';
    } elseif (
        strtolower($submittedEmail) !== strtolower($adminEmail) ||
        !hash_equals($adminPassword, $submittedPassword)
    ) {
        $error = 'Invalid email address or password. Please try again.';
    } else {
        // Regenerate the session ID after successful authentication.
        session_regenerate_id(true);

        // Store administrator information in the session.
        $_SESSION['proserve_admin'] = [
            'id' => 1,
            'name' => $adminName,
            'email' => $adminEmail,
            'role' => 'Administrator',
            'logged_in_at' => date('Y-m-d H:i:s')
        ];

        $_SESSION['proserve_admin_id'] = 1;
        $_SESSION['proserve_admin_name'] = $adminName;
        $_SESSION['proserve_admin_email'] = $adminEmail;
        $_SESSION['proserve_admin_role'] = 'Administrator';

        // Generate a fresh token for future requests.
        $_SESSION['proserve_admin_login_csrf'] = bin2hex(random_bytes(32));

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administrator Login | ProServe CRM</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10233f;
            --muted: #64748b;
            --border: #e2e8f0;
            --background: #f4f7fc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            background: var(--background);
            color: var(--navy);
        }

        .login-wrapper {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .brand-panel {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 52px;
            color: white;
            background:
                radial-gradient(
                    circle at 85% 15%,
                    rgba(96, 165, 250, .32),
                    transparent 32%
                ),
                linear-gradient(145deg, #10233f 0%, #173d75 55%, #2563eb 100%);
        }

        .brand-panel::before,
        .brand-panel::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-panel::before {
            width: 430px;
            height: 430px;
            right: -160px;
            bottom: 10%;
        }

        .brand-panel::after {
            width: 300px;
            height: 300px;
            right: -95px;
            bottom: calc(10% + 65px);
        }

        .brand-content,
        .brand-footer,
        .brand-logo {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .2);
            font-size: 23px;
        }

        .brand-content {
            max-width: 520px;
            margin: 60px 0;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 13px;
            border-radius: 30px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .7px;
            text-transform: uppercase;
        }

        .brand-content h1 {
            margin: 25px 0 18px;
            font-size: clamp(34px, 3.5vw, 51px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1.8px;
        }

        .brand-content p {
            max-width: 440px;
            color: #dbeafe;
            font-size: 15px;
            line-height: 1.9;
        }

        .feature-list {
            display: grid;
            gap: 18px;
            margin-top: 36px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #eff6ff;
            font-size: 14px;
        }

        .feature-item i {
            display: grid;
            place-items: center;
            width: 35px;
            height: 35px;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(255, 255, 255, .13);
            font-size: 17px;
        }

        .brand-footer {
            color: #dbeafe;
            font-size: 12px;
            line-height: 1.8;
        }

        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 45px 28px;
            background: #fff;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
        }

        .mobile-brand {
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 35px;
            font-size: 22px;
            font-weight: 800;
            color: var(--navy);
        }

        .mobile-brand .brand-icon {
            color: var(--primary);
            background: #eff6ff;
            border-color: #dbeafe;
        }

        .login-heading {
            margin-bottom: 30px;
        }

        .login-heading .subtitle {
            margin-bottom: 10px;
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .login-heading h2 {
            margin-bottom: 12px;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .login-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .form-label {
            margin-bottom: 9px;
            color: #334155;
            font-size: 13px;
            font-weight: 700;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap > i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 17px;
            pointer-events: none;
        }

        .form-control {
            min-height: 51px;
            padding: 12px 15px 12px 44px;
            border: 1px solid var(--border);
            border-radius: 11px;
            font-size: 13px;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .09) !important;
        }

        .password-input {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 13px;
            transform: translateY(-50%);
            padding: 5px;
            border: 0;
            background: transparent;
            color: #64748b;
        }

        .btn-login {
            width: 100%;
            min-height: 52px;
            margin-top: 10px;
            border: 0;
            border-radius: 11px;
            background: var(--primary);
            color: white;
            font-size: 14px;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(37, 99, 235, .18);
        }

        .demo-box {
            margin-top: 25px;
            padding: 17px;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            background: #f8fbff;
        }

        .demo-box-heading {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 12px;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 800;
        }

        .demo-credential {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 9px;
            font-size: 12px;
        }

        .demo-credential span {
            color: var(--muted);
        }

        .demo-credential strong {
            overflow-wrap: anywhere;
            color: #334155;
            font-size: 12px;
        }

        .copy-btn {
            flex-shrink: 0;
            border: 0;
            background: transparent;
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
        }

        .security-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 24px;
            color: #64748b;
            font-size: 11px;
            line-height: 1.7;
        }

        .security-note i {
            color: #16a34a;
            font-size: 15px;
        }

        .bottom-link {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: var(--muted);
        }

        .bottom-link a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 900px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                display: none;
            }

            .mobile-brand {
                display: flex;
            }

            .form-panel {
                min-height: 100vh;
                padding: 40px 24px;
            }
        }

        @media (max-width: 480px) {
            .form-panel {
                padding: 28px 20px;
            }

            .login-heading h2 {
                font-size: 28px;
            }

            .demo-credential {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- Branding panel -->
    <aside class="brand-panel">

        <a href="../index.php" class="brand-logo">
            <span class="brand-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </span>
            <span>ProServe</span>
        </a>

        <div class="brand-content">

            <span class="eyebrow">
                <i class="bi bi-shield-check"></i>
                Administrator Portal
            </span>

            <h1>Manage your business with confidence.</h1>

            <p>
                Welcome to the ProServe CRM administration portal.
                Manage customers, leads, appointments, quotations,
                invoices and business reports from one central workspace.
            </p>

            <div class="feature-list">

                <div class="feature-item">
                    <i class="bi bi-people"></i>
                    <span>Customer and lead management</span>
                </div>

                <div class="feature-item">
                    <i class="bi bi-calendar-check"></i>
                    <span>Appointment and service management</span>
                </div>

                <div class="feature-item">
                    <i class="bi bi-receipt"></i>
                    <span>Quotations, invoices and reporting</span>
                </div>

            </div>
        </div>

        <div class="brand-footer">
            <strong>ProServe CRM</strong><br>
            Portfolio demonstration by Codvanta.<br>
            Turning Vision into Digital Reality.
        </div>

    </aside>


    <!-- Login form panel -->
    <main class="form-panel">

        <div class="login-card">

            <a href="../index.php" class="mobile-brand text-decoration-none">
                <span class="brand-icon">
                    <i class="bi bi-grid-1x2-fill"></i>
                </span>
                <span>ProServe</span>
            </a>

            <div class="login-heading">

                <div class="subtitle">Welcome back</div>

                <h2>Administrator login</h2>

                <p>
                    Enter your administrator credentials to access
                    the ProServe CRM dashboard.
                </p>

            </div>


            <?php if ($success !== ''): ?>
                <div class="alert alert-success d-flex gap-2 align-items-start small">
                    <i class="bi bi-check-circle-fill"></i>
                    <span><?= e($success) ?></span>
                </div>
            <?php endif; ?>


            <?php if ($error !== ''): ?>
                <div class="alert alert-danger d-flex gap-2 align-items-start small">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>


            <form method="POST" action="" autocomplete="on">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e($_SESSION['proserve_admin_login_csrf']) ?>"
                >

                <div class="mb-4">

                    <label for="email" class="form-label">
                        Email address
                    </label>

                    <div class="input-wrap">
                        <i class="bi bi-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter administrator email"
                            value="<?= e($_POST['email'] ?? '') ?>"
                            autocomplete="username"
                            maxlength="190"
                            required
                            autofocus
                        >
                    </div>

                </div>


                <div class="mb-3">

                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control password-input"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Show password"
                            title="Show or hide password"
                        >
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>
                    </div>

                </div>


                <button type="submit" class="btn btn-login">
                    Sign in to dashboard
                    <i class="bi bi-arrow-right ms-2"></i>
                </button>

            </form>


            <!-- Demonstration credentials -->
            <div class="demo-box">

                <div class="demo-box-heading">
                    <i class="bi bi-info-circle-fill"></i>
                    DEMO ADMINISTRATOR CREDENTIALS
                </div>

                <div class="demo-credential">
                    <span>Email</span>

                    <strong id="demoEmail">admin@proserve.demo</strong>

                    <button
                        type="button"
                        class="copy-btn"
                        data-copy="admin@proserve.demo"
                    >
                        Copy
                    </button>
                </div>

                <div class="demo-credential">
                    <span>Password</span>

                    <strong id="demoPassword">Admin123</strong>

                    <button
                        type="button"
                        class="copy-btn"
                        data-copy="Admin123"
                    >
                        Copy
                    </button>
                </div>

                <button
                    type="button"
                    class="btn btn-sm btn-outline-primary w-100 mt-3"
                    id="fillDemo"
                >
                    <i class="bi bi-lightning-charge me-1"></i>
                    Fill demo credentials
                </button>

            </div>


            <div class="security-note">
                <i class="bi bi-shield-lock-fill"></i>

                <span>
                    This is a session-based portfolio demonstration.
                    Credentials are hardcoded for demonstration purposes
                    and are not suitable for production use.
                </span>
            </div>


            <div class="bottom-link">
                <a href="../index.php">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to ProServe website
                </a>

                <div class="mt-3">
                    &copy; <?= date('Y') ?> ProServe CRM
                    &middot; Portfolio demonstration by Codvanta
                </div>
            </div>

        </div>

    </main>

</div>


<script>
    // Show or hide the password.
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const passwordIcon = document.getElementById('passwordIcon');

    togglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';

        passwordIcon.className = isPassword
            ? 'bi bi-eye-slash'
            : 'bi bi-eye';

        togglePassword.setAttribute(
            'aria-label',
            isPassword ? 'Hide password' : 'Show password'
        );
    });


    // Fill the form with demonstration credentials.
    document.getElementById('fillDemo').addEventListener('click', function () {
        document.getElementById('email').value = 'admin@proserve.demo';
        passwordInput.value = 'Admin123';
        passwordInput.focus();
    });


    // Copy individual demonstration credentials.
    document.querySelectorAll('[data-copy]').forEach(function (button) {
        button.addEventListener('click', async function () {
            const value = button.getAttribute('data-copy');

            try {
                await navigator.clipboard.writeText(value);

                const originalText = button.textContent;
                button.textContent = 'Copied!';

                setTimeout(function () {
                    button.textContent = originalText;
                }, 1200);
            } catch (error) {
                // Clipboard access may be blocked by the browser.
                const temporaryInput = document.createElement('input');
                temporaryInput.value = value;
                temporaryInput.style.position = 'fixed';
                temporaryInput.style.opacity = '0';

                document.body.appendChild(temporaryInput);
                temporaryInput.select();

                const copied = document.execCommand('copy');
                temporaryInput.remove();

                button.textContent = copied ? 'Copied!' : 'Copy failed';

                setTimeout(function () {
                    button.textContent = 'Copy';
                }, 1200);
            }
        });
    });
</script>

</body>
</html>