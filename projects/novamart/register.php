<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart Customer Registration Page
|--------------------------------------------------------------------------
| Demonstration only.
| No customer data is stored in a database.
*/

$registerMessage = "";
$messageType = "";

$fullName = "";
$email = "";
$phone = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");

    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    $termsAccepted = isset($_POST["terms"]);

    if (
        empty($fullName) ||
        empty($email) ||
        empty($phone) ||
        empty($password) ||
        empty($confirmPassword)
    ) {

        $registerMessage = "Please complete all required fields.";
        $messageType = "danger";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $registerMessage = "Please enter a valid email address.";
        $messageType = "danger";

    } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {

        $registerMessage = "Please enter a valid phone number.";
        $messageType = "danger";

    } elseif (strlen($password) < 8) {

        $registerMessage = "Your password must contain at least 8 characters.";
        $messageType = "danger";

    } elseif ($password !== $confirmPassword) {

        $registerMessage = "The passwords do not match. Please try again.";
        $messageType = "danger";

    } elseif (!$termsAccepted) {

        $registerMessage = "Please accept the Terms and Conditions to continue.";
        $messageType = "danger";

    } else {

        $registerMessage = "Registration completed successfully! This is a demonstration. Customer accounts are not saved.";
        $messageType = "success";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Registration | NovaMart</title>

    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        :root {
            --primary-color: #0d2b52;
            --secondary-color: #164e8a;
            --accent-color: #f0a500;
            --light-bg: #f5f8fc;
            --text-color: #26374a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Trebuchet MS", Arial, sans-serif;
            background: var(--light-bg);
            color: var(--text-color);
        }

        /* NAVBAR */

        .navbar {
            background: #ffffff;
            padding: 16px 0;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .navbar-brand {
            font-size: 27px;
            font-weight: bold;
            color: var(--primary-color);
            letter-spacing: -0.5px;
        }

        .navbar-brand span {
            color: var(--accent-color);
        }

        .navbar-brand:hover {
            color: var(--primary-color);
        }

        .nav-link {
            color: #394b60;
            font-weight: 600;
            margin-left: 15px;
        }

        .nav-link:hover {
            color: var(--secondary-color);
        }

        .nav-home {
            background: var(--primary-color);
            color: #ffffff !important;
            padding: 9px 18px !important;
            border-radius: 7px;
        }

        /* REGISTRATION SECTION */

        .register-section {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            padding: 50px 15px;
        }

        .register-wrapper {
            max-width: 1050px;
            width: 100%;
            margin: auto;
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 45px rgba(13, 43, 82, 0.12);
        }

        /* LEFT INFORMATION PANEL */

        .register-info {
            background: linear-gradient(
                145deg,
                #0d2b52,
                #164e8a
            );
            color: #ffffff;
            padding: 55px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .register-info::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            border: 45px solid rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .register-info::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
            bottom: -100px;
            left: -70px;
        }

        .info-content {
            position: relative;
            z-index: 1;
        }

        .info-icon {
            width: 75px;
            height: 75px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            margin-bottom: 30px;
        }

        .register-info h2 {
            font-size: 32px;
            font-weight: bold;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .register-info p {
            color: #d8e5f5;
            line-height: 1.8;
            font-size: 15px;
        }

        .benefit-list {
            list-style: none;
            padding: 0;
            margin: 28px 0 0;
        }

        .benefit-list li {
            margin-bottom: 17px;
            color: #e9f2fc;
            font-size: 14px;
        }

        .benefit-list i {
            color: #f0a500;
            margin-right: 10px;
            font-size: 17px;
        }

        /* REGISTRATION FORM */

        .register-form-panel {
            padding: 45px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading {
            margin-bottom: 25px;
        }

        .form-heading h1 {
            font-size: 29px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .form-heading p {
            color: #738195;
            font-size: 14px;
            margin-bottom: 0;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #34475d;
            margin-bottom: 8px;
        }

        .form-control {
            height: 47px;
            border: 1px solid #dce3ec;
            border-radius: 8px;
            font-size: 14px;
            padding: 12px 15px;
            background: #fbfcfe;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(22, 78, 138, 0.1);
            background: #ffffff;
        }

        .input-group .form-control {
            border-right: 0;
        }

        .password-toggle {
            background: #fbfcfe;
            border: 1px solid #dce3ec;
            border-left: 0;
            color: #6c7a8b;
            border-radius: 0 8px 8px 0;
            padding: 0 15px;
        }

        .password-toggle:hover {
            color: var(--secondary-color);
        }

        .form-check-label {
            font-size: 13px;
            color: #68788d;
            line-height: 1.6;
        }

        .form-check-label a {
            color: var(--secondary-color);
            font-weight: 600;
            text-decoration: none;
        }

        .form-check-label a:hover {
            text-decoration: underline;
        }

        .btn-register {
            width: 100%;
            height: 49px;
            border: none;
            border-radius: 8px;
            background: var(--primary-color);
            color: #ffffff;
            font-weight: bold;
            font-size: 15px;
            transition: 0.2s ease;
        }

        .btn-register:hover {
            background: var(--secondary-color);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(13, 43, 82, 0.15);
        }

        .login-text {
            text-align: center;
            margin-top: 23px;
            color: #758398;
            font-size: 14px;
        }

        .login-text a {
            color: var(--secondary-color);
            font-weight: bold;
            text-decoration: none;
        }

        .login-text a:hover {
            text-decoration: underline;
        }

        .secure-note {
            text-align: center;
            color: #8b98a9;
            font-size: 12px;
            margin-top: 22px;
        }

        .secure-note i {
            color: #2e9d69;
            margin-right: 5px;
        }

        /* RESPONSIVE */

        @media (max-width: 767px) {

            .register-wrapper {
                grid-template-columns: 1fr;
            }

            .register-info {
                padding: 35px 30px;
            }

            .register-info h2 {
                font-size: 26px;
            }

            .benefit-list {
                display: none;
            }

            .info-icon {
                width: 60px;
                height: 60px;
                font-size: 28px;
                margin-bottom: 20px;
            }

            .register-form-panel {
                padding: 35px 25px;
            }

            .register-section {
                padding: 30px 15px;
            }

            .navbar-brand {
                font-size: 23px;
            }

        }

    </style>

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            <img src="../images/novamart/novamart_logo.png" width="40px" height="40px">
            Nova<span>Mart</span>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
            aria-controls="navbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="shop.php">
                        Shop
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="bi bi-cart3"></i>
                        Cart
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-home" href="login.php">
                        <i class="bi bi-person-circle me-1"></i>
                        Login
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- REGISTRATION SECTION -->

<section class="register-section">

    <div class="register-wrapper">

        <!-- LEFT INFORMATION PANEL -->

        <div class="register-info">

            <div class="info-content">

                <div class="info-icon">
                    <i class="bi bi-person-plus"></i>
                </div>

                <h2>
                    Create Your
                    <br>
                    NovaMart Account
                </h2>

                <p>
                    Join NovaMart and discover a convenient way
                    to shop for your favourite products.
                    Create your account to get started.
                </p>

                <ul class="benefit-list">

                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        Discover products across multiple categories
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        Manage your shopping orders
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        Enjoy a convenient shopping experience
                    </li>

                </ul>

            </div>

        </div>


        <!-- REGISTRATION FORM PANEL -->

        <div class="register-form-panel">

            <div class="form-heading">

                <h1>Create Account</h1>

                <p>
                    Fill in your details to register.
                </p>

            </div>


            <!-- REGISTRATION MESSAGE -->

            <?php if (!empty($registerMessage)): ?>

                <div class="alert alert-<?php echo htmlspecialchars($messageType); ?> alert-dismissible fade show"
                     role="alert">

                    <?php echo htmlspecialchars($registerMessage); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            <?php endif; ?>


            <!-- REGISTRATION FORM -->

            <form method="POST" action="register.php">

                <!-- FULL NAME -->

                <div class="mb-3">

                    <label for="full_name" class="form-label">
                        Full Name
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="full_name"
                        name="full_name"
                        placeholder="Enter your full name"
                        value="<?php echo htmlspecialchars($fullName); ?>"
                        autocomplete="name"
                        maxlength="100"
                        required
                    >

                </div>


                <!-- EMAIL -->

                <div class="mb-3">

                    <label for="email" class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="Enter your email address"
                        value="<?php echo htmlspecialchars($email); ?>"
                        autocomplete="email"
                        maxlength="150"
                        required
                    >

                </div>


                <!-- PHONE NUMBER -->

                <div class="mb-3">

                    <label for="phone" class="form-label">
                        Phone Number
                    </label>

                    <input
                        type="tel"
                        class="form-control"
                        id="phone"
                        name="phone"
                        placeholder="Enter your phone number"
                        value="<?php echo htmlspecialchars($phone); ?>"
                        autocomplete="tel"
                        maxlength="20"
                        required
                    >

                </div>


                <!-- PASSWORD -->

                <div class="mb-3">

                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="input-group">

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Create a password"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <button
                            class="password-toggle"
                            type="button"
                            id="togglePassword"
                            aria-label="Show password"
                        >
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>

                    </div>

                    <small class="text-muted">
                        Use at least 8 characters.
                    </small>

                </div>


                <!-- CONFIRM PASSWORD -->

                <div class="mb-3">

                    <label for="confirm_password" class="form-label">
                        Confirm Password
                    </label>

                    <div class="input-group">

                        <input
                            type="password"
                            class="form-control"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="Re-enter your password"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <button
                            class="password-toggle"
                            type="button"
                            id="toggleConfirmPassword"
                            aria-label="Show confirm password"
                        >
                            <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                        </button>

                    </div>

                </div>


                <!-- TERMS AND CONDITIONS -->

                <div class="form-check mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="terms"
                        name="terms"
                        required
                    >

                    <label class="form-check-label" for="terms">

                        I agree to the
                        <a href="#" onclick="return false;">
                            Terms and Conditions
                        </a>
                        and
                        <a href="#" onclick="return false;">
                            Privacy Policy
                        </a>.

                    </label>

                </div>


                <!-- REGISTER BUTTON -->

                <button type="submit" class="btn-register">

                    <i class="bi bi-person-plus me-2"></i>

                    Create Account

                </button>

            </form>


            <!-- LOGIN LINK -->

            <div class="login-text">

                Already have an account?

                <a href="login.php">
                    Sign In
                </a>

            </div>


            <!-- SECURITY NOTE -->

            <div class="secure-note">

                <i class="bi bi-shield-lock-fill"></i>

                Your shopping experience starts here.

            </div>

        </div>

    </div>

</section>


<!-- JAVASCRIPT -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>

    // Show / hide password

    function setupPasswordToggle(inputId, buttonId, iconId) {

        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(buttonId);
        const passwordIcon = document.getElementById(iconId);

        toggleButton.addEventListener("click", function () {

            const isPassword = passwordInput.type === "password";

            passwordInput.type = isPassword ? "text" : "password";

            passwordIcon.className = isPassword
                ? "bi bi-eye-slash"
                : "bi bi-eye";

            toggleButton.setAttribute(
                "aria-label",
                isPassword ? "Hide password" : "Show password"
            );

        });

    }

    setupPasswordToggle(
        "password",
        "togglePassword",
        "passwordIcon"
    );

    setupPasswordToggle(
        "confirm_password",
        "toggleConfirmPassword",
        "confirmPasswordIcon"
    );


    // Client-side password confirmation validation

    const registrationForm = document.querySelector("form");

    registrationForm.addEventListener("submit", function (event) {

        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        if (password !== confirmPassword) {

            event.preventDefault();

            alert("The passwords do not match. Please try again.");

        }

    });

</script>

</body>

</html>