<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart Customer Login Page
|--------------------------------------------------------------------------
| Demonstration only.
| No real database authentication is performed.
*/

$loginMessage = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($email) || empty($password)) {

        $loginMessage = "Please enter your email address and password.";
        $messageType = "danger";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $loginMessage = "Please enter a valid email address.";
        $messageType = "danger";

    } else {

        $loginMessage = "This is a demonstration login page. Customer authentication is not connected yet.";
        $messageType = "info";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Login | NovaMart</title>

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

        /* LOGIN SECTION */

        .login-section {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            padding: 55px 15px;
        }

        .login-wrapper {
            max-width: 1000px;
            width: 100%;
            margin: auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 45px rgba(13, 43, 82, 0.12);
        }

        /* LEFT PANEL */

        .login-info {
            background: linear-gradient(
                145deg,
                #0d2b52,
                #164e8a
            );
            color: #ffffff;
            padding: 55px 42px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-info::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            border: 45px solid rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .login-info::after {
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

        .login-info h2 {
            font-size: 32px;
            font-weight: bold;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .login-info p {
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

        /* RIGHT PANEL */

        .login-form-panel {
            padding: 55px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading {
            margin-bottom: 30px;
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
            margin-bottom: 9px;
        }

        .form-control {
            height: 49px;
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
        }

        .forgot-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--secondary-color);
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
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

        .btn-login:hover {
            background: var(--secondary-color);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(13, 43, 82, 0.15);
        }

        .register-text {
            text-align: center;
            margin-top: 25px;
            color: #758398;
            font-size: 14px;
        }

        .register-text a {
            color: var(--secondary-color);
            font-weight: bold;
            text-decoration: none;
        }

        .register-text a:hover {
            text-decoration: underline;
        }

        .secure-note {
            text-align: center;
            color: #8b98a9;
            font-size: 12px;
            margin-top: 25px;
        }

        .secure-note i {
            color: #2e9d69;
            margin-right: 5px;
        }

        /* RESPONSIVE */

        @media (max-width: 767px) {

            .login-wrapper {
                grid-template-columns: 1fr;
            }

            .login-info {
                padding: 35px 30px;
            }

            .login-info h2 {
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

            .login-form-panel {
                padding: 40px 28px;
            }

            .login-section {
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


<!-- LOGIN SECTION -->

<section class="login-section">

    <div class="login-wrapper">

        <!-- LEFT INFORMATION PANEL -->

        <div class="login-info">

            <div class="info-content">

                <div class="info-icon">
                    <i class="bi bi-bag-check"></i>
                </div>

                <h2>
                    Welcome Back to
                    <br>
                    NovaMart!
                </h2>

                <p>
                    Sign in to your account and enjoy a convenient
                    shopping experience. Discover great products,
                    manage your orders, and shop with confidence.
                </p>

                <ul class="benefit-list">

                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        Explore a wide range of products
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        Track and manage your orders
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        Enjoy a convenient shopping experience
                    </li>

                </ul>

            </div>

        </div>


        <!-- LOGIN FORM PANEL -->

        <div class="login-form-panel">

            <div class="form-heading">

                <h1>Customer Login</h1>

                <p>
                    Enter your account details to continue.
                </p>

            </div>


            <!-- LOGIN MESSAGE -->

            <?php if (!empty($loginMessage)): ?>

                <div class="alert alert-<?php echo htmlspecialchars($messageType); ?> alert-dismissible fade show"
                     role="alert">

                    <?php echo htmlspecialchars($loginMessage); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            <?php endif; ?>


            <!-- LOGIN FORM -->

            <form method="POST" action="login.php">

                <!-- EMAIL -->

                <div class="mb-4">

                    <label for="email" class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="Enter your email address"
                        value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"
                        autocomplete="email"
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
                            placeholder="Enter your password"
                            autocomplete="current-password"
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

                </div>


                <!-- REMEMBER ME / FORGOT PASSWORD -->

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="remember"
                            name="remember"
                        >

                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>

                    </div>

                    <a href="forgot_password.php" class="forgot-link">
                        Forgot password?
                    </a>

                </div>


                <!-- LOGIN BUTTON -->

                <button type="submit" class="btn-login">

                    <i class="bi bi-box-arrow-in-right me-2"></i>

                    Sign In

                </button>

            </form>


            <!-- REGISTER LINK -->

            <div class="register-text">

                Don't have an account?

                <a href="register.php">
                    Create an account
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

    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const passwordIcon = document.getElementById("passwordIcon");

    togglePassword.addEventListener("click", function () {

        const isPassword = passwordInput.type === "password";

        passwordInput.type = isPassword ? "text" : "password";

        passwordIcon.className = isPassword
            ? "bi bi-eye-slash"
            : "bi bi-eye";

        togglePassword.setAttribute(
            "aria-label",
            isPassword ? "Hide password" : "Show password"
        );

    });

</script>

</body>

</html>