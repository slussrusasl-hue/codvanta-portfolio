
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore - Membership Payment
|--------------------------------------------------------------------------
| Demonstration only.
| No real payment is processed and no card details are collected.
*/

if (
    !isset($_SESSION['fitcore_demo_member']) ||
    !is_array($_SESSION['fitcore_demo_member'])
) {
    header("Location: register.php");
    exit;
}

$member = $_SESSION['fitcore_demo_member'];

$plans = [
    'basic' => [
        'name' => 'BASIC',
        'price' => 20,
        'description' => 'A great starting point for your fitness journey.'
    ],
    'pro' => [
        'name' => 'PRO',
        'price' => 40,
        'description' => 'More flexibility for your fitness goals.'
    ],
    'premium' => [
        'name' => 'PREMIUM',
        'price' => 70,
        'description' => 'A premium experience for dedicated members.'
    ]
];

$membershipKey = strtolower($member['membership'] ?? 'pro');

if (!isset($plans[$membershipKey])) {
    $membershipKey = 'pro';
}

$plan = $plans[$membershipKey];
$memberName = $member['full_name'] ?? 'FitCore Member';
$memberEmail = $member['email'] ?? '';

$error = '';
$paymentComplete = false;

/*
|--------------------------------------------------------------------------
| Handle simulated payment confirmation
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['fitcore_demo_payment'])) {
        // Do not create another demo payment if already completed.
        $paymentComplete = true;
    } elseif (isset($_POST['confirm_payment'])) {
        if (
            !isset($_POST['demo_payment_agreement']) ||
            $_POST['demo_payment_agreement'] !== 'yes'
        ) {
            $error = 'Please confirm that you understand this is a demo payment.';
        } else {
            // Simulated transaction reference for demonstration purposes.
            $transactionId = 'FC-DEMO-' . strtoupper(
                substr(sha1(uniqid((string) mt_rand(), true)), 0, 8)
            );

            $_SESSION['fitcore_demo_payment'] = [
                'status' => 'Paid',
                'transaction_id' => $transactionId,
                'amount' => $plan['price'],
                'membership' => $plan['name'],
                'membership_key' => $membershipKey,
                'member_name' => $memberName,
                'member_email' => $memberEmail,
                'payment_date' => date('Y-m-d H:i:s'),
                'payment_method' => 'Demo Payment',
                'note' => 'Simulated payment only. No real money was charged.'
            ];

            $paymentComplete = true;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Show the completed payment if this session already has one
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['fitcore_demo_payment'])) {
    $paymentComplete = true;
}

$payment = $_SESSION['fitcore_demo_payment'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Membership Payment | FitCore</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --fc-orange: #ff6b2c;
            --fc-orange-light: #ff8a52;
            --fc-bg: #101010;
            --fc-card: #191919;
            --fc-card-light: #202020;
            --fc-border: #303030;
            --fc-muted: #a6a6a6;
            --fc-white: #ffffff;
            --fc-green: #36c98f;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--fc-bg);
            color: var(--fc-white);
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: rgba(16, 16, 16, 0.97);
            border-bottom: 1px solid var(--fc-border);
        }

        .navbar-brand {
            color: var(--fc-white) !important;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .brand-highlight {
            color: var(--fc-orange);
        }

        .navbar .nav-link {
            color: #d2d2d2;
            transition: 0.2s;
        }

        .navbar .nav-link:hover {
            color: var(--fc-orange);
        }

        .page-header {
            padding: 55px 0 35px;
            text-align: center;
            background:
                radial-gradient(
                    circle at top,
                    rgba(255, 107, 44, 0.15),
                    transparent 60%
                );
        }

        .eyebrow {
            color: var(--fc-orange);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 12px;
            font-weight: 800;
        }

        .page-header h1 {
            font-size: clamp(30px, 5vw, 45px);
            font-weight: 800;
            margin: 12px 0;
        }

        .page-header p {
            color: var(--fc-muted);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.7;
        }

        .payment-wrapper {
            padding: 15px 0 75px;
        }

        .payment-card {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 18px;
            padding: 30px;
            height: 100%;
        }

        .section-heading {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .section-description {
            color: var(--fc-muted);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .plan-summary {
            background: linear-gradient(
                135deg,
                rgba(255, 107, 44, 0.15),
                rgba(255, 107, 44, 0.04)
            );
            border: 1px solid rgba(255, 107, 44, 0.4);
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 25px;
        }

        .plan-badge {
            display: inline-block;
            background: var(--fc-orange);
            color: #111;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1px;
        }

        .plan-name {
            font-size: 27px;
            font-weight: 900;
            margin: 15px 0 5px;
        }

        .plan-description {
            color: #c0c0c0;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .plan-price {
            color: var(--fc-orange);
            font-size: 38px;
            font-weight: 900;
        }

        .plan-price small {
            color: var(--fc-muted);
            font-size: 14px;
            font-weight: 400;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 13px 0;
            border-bottom: 1px solid var(--fc-border);
            font-size: 14px;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: var(--fc-muted);
        }

        .summary-value {
            text-align: right;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .total-row {
            margin-top: 12px;
            padding-top: 18px;
            border-top: 1px solid var(--fc-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .total-row span:first-child {
            color: #d1d1d1;
            font-weight: 700;
        }

        .total-amount {
            color: var(--fc-orange);
            font-size: 28px;
            font-weight: 900;
        }

        .payment-option {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 18px;
            border: 1px solid rgba(255, 107, 44, 0.6);
            background: rgba(255, 107, 44, 0.06);
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .payment-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
            height: 45px;
            border-radius: 10px;
            background: rgba(255, 107, 44, 0.15);
            color: var(--fc-orange);
            font-size: 22px;
            font-weight: 900;
        }

        .payment-option h3 {
            font-size: 16px;
            font-weight: 800;
            margin: 2px 0 6px;
        }

        .payment-option p {
            color: var(--fc-muted);
            font-size: 13px;
            margin: 0;
            line-height: 1.6;
        }

        .demo-notice {
            border: 1px solid rgba(255, 193, 7, 0.35);
            background: rgba(255, 193, 7, 0.07);
            color: #f5d989;
            border-radius: 10px;
            padding: 15px;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        .form-check {
            margin: 20px 0;
        }

        .form-check-input {
            background-color: #252525;
            border-color: #666;
        }

        .form-check-input:checked {
            background-color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .form-check-label {
            color: #d4d4d4;
            font-size: 13px;
            line-height: 1.6;
        }

        .btn-fitcore {
            display: block;
            width: 100%;
            background: var(--fc-orange);
            color: #111;
            border: 0;
            padding: 14px 20px;
            border-radius: 9px;
            font-size: 15px;
            font-weight: 900;
            transition: 0.2s;
        }

        .btn-fitcore:hover {
            background: var(--fc-orange-light);
            color: #111;
            transform: translateY(-1px);
        }

        .btn-outline-fitcore {
            display: inline-block;
            border: 1px solid var(--fc-border);
            color: #eee;
            padding: 11px 17px;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }

        .btn-outline-fitcore:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .security-note {
            color: var(--fc-muted);
            font-size: 12px;
            text-align: center;
            margin-top: 15px;
            line-height: 1.7;
        }

        .success-icon {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(54, 201, 143, 0.13);
            border: 1px solid rgba(54, 201, 143, 0.4);
            color: var(--fc-green);
            font-size: 42px;
            margin: 0 auto 22px;
        }

        .success-card {
            max-width: 720px;
            margin: 0 auto;
            text-align: center;
        }

        .success-card h2 {
            font-size: 30px;
            font-weight: 900;
            margin-bottom: 12px;
        }

        .success-card > p {
            color: var(--fc-muted);
            line-height: 1.7;
        }

        .success-details {
            text-align: left;
            margin: 25px 0;
            padding: 10px 22px;
            background: var(--fc-card-light);
            border: 1px solid var(--fc-border);
            border-radius: 12px;
        }

        .status-paid {
            color: var(--fc-green);
            font-weight: 900;
        }

        .footer {
            border-top: 1px solid var(--fc-border);
            padding: 25px 0;
            color: var(--fc-muted);
            font-size: 13px;
            text-align: center;
        }

        .footer a {
            color: var(--fc-orange);
        }

        @media (max-width: 767px) {
            .payment-card {
                padding: 22px;
            }

            .page-header {
                padding-top: 40px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            FIT<span class="brand-highlight">CORE</span>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#fitcoreNavbar"
            aria-controls="fitcoreNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="fitcoreNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="trainers.php">Trainers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="classes.php">Classes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Membership</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header class="page-header">
    <div class="container">
        <div class="eyebrow">Your Fitness Journey Starts Here</div>

        <?php if ($paymentComplete && $payment): ?>
            <h1>Payment Confirmation</h1>
            <p>
                Your FitCore demonstration membership payment has been
                completed successfully.
            </p>
        <?php else: ?>
            <h1>Complete Your Membership</h1>
            <p>
                Review your selected plan and complete the demonstration
                payment to continue to your FitCore membership.
            </p>
        <?php endif; ?>
    </div>
</header>

<main class="payment-wrapper">
    <div class="container">

        <?php if ($paymentComplete && $payment): ?>

            <!-- Payment success view -->

            <section class="payment-card success-card">
                <div class="success-icon" aria-hidden="true">&#10003;</div>

                <div class="eyebrow">Demo Payment Successful</div>

                <h2>Welcome to FitCore, <?= htmlspecialchars($memberName) ?>!</h2>

                <p>
                    Your simulated payment is complete. This confirmation
                    demonstrates how a successful membership payment could
                    appear on a real fitness platform.
                </p>

                <div class="success-details">
                    <div class="summary-row">
                        <span class="summary-label">Member</span>
                        <span class="summary-value">
                            <?= htmlspecialchars($payment['member_name']) ?>
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Membership Plan</span>
                        <span class="summary-value">
                            <?= htmlspecialchars($payment['membership']) ?>
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Amount</span>
                        <span class="summary-value">
                            $<?= number_format((float) $payment['amount'], 2) ?>
                            / month
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Payment Status</span>
                        <span class="summary-value status-paid">
                            <?= htmlspecialchars($payment['status']) ?>
                            (Demo)
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Payment Method</span>
                        <span class="summary-value">
                            <?= htmlspecialchars($payment['payment_method']) ?>
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Transaction Reference</span>
                        <span class="summary-value">
                            <?= htmlspecialchars($payment['transaction_id']) ?>
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Date</span>
                        <span class="summary-value">
                            <?= htmlspecialchars(
                                date(
                                    'd M Y, h:i A',
                                    strtotime($payment['payment_date'])
                                )
                            ) ?>
                        </span>
                    </div>
                </div>

                <div class="demo-notice text-start">
                    <strong>Demonstration notice:</strong>
                    No real money was charged. The transaction reference
                    and payment status are generated for this portfolio
                    demonstration only.
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="index.php" class="btn-fitcore text-center">
                        Explore FitCore
                    </a>

                    <a href="../fitcore_demo.php" class="btn-outline-fitcore">
                        Back to Role Selection
                    </a>
                </div>
            </section>

        <?php else: ?>

            <!-- Payment form and order summary -->

            <div class="row g-4">

                <div class="col-lg-7">
                    <section class="payment-card">
                        <h2 class="section-heading">Payment Method</h2>

                        <p class="section-description">
                            Choose the demonstration payment option below.
                            This portfolio page does not connect to a payment
                            gateway.
                        </p>

                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <div class="payment-option">
                            <div class="payment-icon" aria-hidden="true">
                                $
                            </div>

                            <div>
                                <h3>Demo Membership Payment</h3>
                                <p>
                                    Simulates a successful membership payment
                                    so visitors can explore the customer
                                    workflow without using a real bank card.
                                </p>
                            </div>
                        </div>

                        <div class="demo-notice">
                            <strong>Demo mode:</strong>
                            Do not enter bank card numbers, passwords, or
                            other financial information. Clicking the
                            confirmation button creates a simulated
                            transaction only.
                        </div>

                        <form method="POST" action="membership_payment.php">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="demo_payment_agreement"
                                    value="yes"
                                    id="demoPaymentAgreement"
                                    required
                                >

                                <label
                                    class="form-check-label"
                                    for="demoPaymentAgreement"
                                >
                                    I understand that this is a portfolio
                                    demonstration and that no real payment
                                    will be processed.
                                </label>
                            </div>

                            <button
                                type="submit"
                                name="confirm_payment"
                                value="1"
                                class="btn-fitcore"
                            >
                                Confirm Demo Payment —
                                $<?= number_format((float) $plan['price'], 2) ?>
                            </button>

                            <p class="security-note">
                                <span aria-hidden="true">&#128274;</span>
                                Demo transaction only. No card details are
                                requested or stored.
                            </p>
                        </form>

                        <div class="text-center mt-4">
                            <a href="register.php" class="btn-outline-fitcore">
                                &larr; Back to Registration
                            </a>
                        </div>
                    </section>
                </div>

                <div class="col-lg-5">
                    <section class="payment-card">
                        <h2 class="section-heading">Order Summary</h2>

                        <p class="section-description">
                            Check your membership details before confirming.
                        </p>

                        <div class="plan-summary">
                            <span class="plan-badge">SELECTED PLAN</span>

                            <div class="plan-name">
                                <?= htmlspecialchars($plan['name']) ?>
                            </div>

                            <p class="plan-description">
                                <?= htmlspecialchars($plan['description']) ?>
                            </p>

                            <div class="plan-price">
                                $<?= number_format((float) $plan['price'], 2) ?>
                                <small>/ month</small>
                            </div>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Member Name</span>
                            <span class="summary-value">
                                <?= htmlspecialchars($memberName) ?>
                            </span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Email Address</span>
                            <span class="summary-value">
                                <?= htmlspecialchars($memberEmail) ?>
                            </span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Membership</span>
                            <span class="summary-value">
                                <?= htmlspecialchars($plan['name']) ?>
                            </span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Billing Period</span>
                            <span class="summary-value">Monthly</span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Payment Type</span>
                            <span class="summary-value">Demo Only</span>
                        </div>

                        <div class="total-row">
                            <span>Monthly Total</span>
                            <span class="total-amount">
                                $<?= number_format((float) $plan['price'], 2) ?>
                            </span>
                        </div>

                        <p class="security-note">
                            Prices are displayed in USD for demonstration
                            purposes. No actual subscription is created.
                        </p>
                    </section>
                </div>

            </div>

        <?php endif; ?>

    </div>
</main>

<footer class="footer">
    <div class="container">
        <p class="mb-2">
            &copy; <?= date('Y') ?> FitCore Fitness Platform.
            Demonstration website by
            <a href="../index.php">Codvanta</a>.
        </p>

        <p class="mb-0">
            Membership payments shown on this page are simulated.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


</body>
</html>