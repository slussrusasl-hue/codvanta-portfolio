
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore - Payment History
|--------------------------------------------------------------------------
| Portfolio demonstration only.
| Payment records are stored in the PHP session, not a database.
*/

$member = $_SESSION['fitcore_demo_member'] ?? null;
$payment = $_SESSION['fitcore_demo_payment'] ?? null;

$hasMember = is_array($member);
$hasPayment = is_array($payment);

$memberName = $hasMember
    ? ($member['full_name'] ?? 'FitCore Member')
    : 'Guest Member';

$memberEmail = $hasMember
    ? ($member['email'] ?? '')
    : '';

$membershipName = $hasPayment
    ? ($payment['membership'] ?? 'N/A')
    : ($hasMember ? ($member['membership_name'] ?? 'N/A') : 'N/A');

$membershipPrice = $hasPayment
    ? (float) ($payment['amount'] ?? 0)
    : (float) ($member['membership_price'] ?? 0);

$paymentStatus = $hasPayment
    ? ($payment['status'] ?? 'Pending')
    : 'No Payment';

$transactionId = $hasPayment
    ? ($payment['transaction_id'] ?? 'N/A')
    : 'N/A';

$paymentDate = $hasPayment
    ? ($payment['payment_date'] ?? '')
    : '';

$paymentMethod = $hasPayment
    ? ($payment['payment_method'] ?? 'Demo Payment')
    : 'N/A';

$formattedPaymentDate = 'N/A';

if ($paymentDate !== '') {
    $timestamp = strtotime($paymentDate);

    if ($timestamp !== false) {
        $formattedPaymentDate = date('d M Y, h:i A', $timestamp);
    }
}

/*
|--------------------------------------------------------------------------
| Prepare a history table
|--------------------------------------------------------------------------
| This demo currently has one possible payment record per session.
| Additional records can be added when the demo is expanded.
*/

$paymentHistory = [];

if ($hasPayment) {
    $paymentHistory[] = [
        'transaction_id' => $transactionId,
        'membership' => $membershipName,
        'amount' => $membershipPrice,
        'status' => $paymentStatus,
        'payment_method' => $paymentMethod,
        'payment_date' => $formattedPaymentDate
    ];
}

$totalPaid = 0;

foreach ($paymentHistory as $record) {
    if (strtolower($record['status']) === 'paid') {
        $totalPaid += (float) $record['amount'];
    }
}

function fitcoreEscape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment History | FitCore</title>

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
            --fc-yellow: #f5c451;
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

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--fc-orange);
        }

        .page-header {
            padding: 50px 0 35px;
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
            font-size: clamp(30px, 5vw, 43px);
            font-weight: 900;
            margin: 12px 0;
        }

        .page-header p {
            color: var(--fc-muted);
            max-width: 650px;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .page-content {
            padding: 15px 0 75px;
        }

        .member-banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 15px;
            padding: 22px 25px;
            margin-bottom: 25px;
        }

        .member-information {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .member-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: rgba(255, 107, 44, 0.15);
            border: 1px solid rgba(255, 107, 44, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--fc-orange);
            font-size: 23px;
            font-weight: 900;
            flex-shrink: 0;
        }

        .member-information h2 {
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 5px;
            overflow-wrap: anywhere;
        }

        .member-information p {
            color: var(--fc-muted);
            font-size: 13px;
            margin: 0;
            overflow-wrap: anywhere;
        }

        .member-label {
            display: inline-block;
            color: var(--fc-orange);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-card {
            height: 100%;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
            padding: 23px;
            transition: 0.2s;
        }

        .summary-card:hover {
            border-color: rgba(255, 107, 44, 0.5);
            transform: translateY(-2px);
        }

        .summary-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 22px;
        }

        .summary-title {
            color: var(--fc-muted);
            font-size: 13px;
            font-weight: 700;
        }

        .summary-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            color: var(--fc-orange);
            background: rgba(255, 107, 44, 0.12);
            font-size: 21px;
            font-weight: 900;
        }

        .summary-value {
            font-size: clamp(24px, 3vw, 31px);
            font-weight: 900;
            overflow-wrap: anywhere;
        }

        .summary-caption {
            color: var(--fc-muted);
            font-size: 12px;
            margin-top: 7px;
        }

        .section-card {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 15px;
            overflow: hidden;
        }

        .section-header {
            padding: 24px 25px;
            border-bottom: 1px solid var(--fc-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .section-header h2 {
            font-size: 20px;
            font-weight: 900;
            margin: 0 0 6px;
        }

        .section-header p {
            color: var(--fc-muted);
            font-size: 13px;
            margin: 0;
        }

        .record-count {
            display: inline-block;
            background: var(--fc-card-light);
            border: 1px solid var(--fc-border);
            border-radius: 30px;
            padding: 7px 12px;
            color: #ddd;
            font-size: 12px;
            font-weight: 700;
        }

        .table-responsive {
            padding: 0 15px 10px;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: #e9e9e9;
            --bs-table-border-color: var(--fc-border);
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            color: var(--fc-muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 800;
            padding: 18px 12px;
            white-space: nowrap;
            border-bottom-width: 1px;
        }

        .table tbody td {
            padding: 18px 12px;
            font-size: 13px;
        }

        .table tbody tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .transaction-reference {
            color: var(--fc-orange);
            font-weight: 800;
            white-space: nowrap;
        }

        .plan-name {
            font-weight: 800;
        }

        .plan-description {
            color: var(--fc-muted);
            font-size: 11px;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 900;
            white-space: nowrap;
        }

        .status-paid {
            background: rgba(54, 201, 143, 0.12);
            color: var(--fc-green);
            border: 1px solid rgba(54, 201, 143, 0.3);
        }

        .status-pending {
            background: rgba(245, 196, 81, 0.12);
            color: var(--fc-yellow);
            border: 1px solid rgba(245, 196, 81, 0.3);
        }

        .status-other {
            background: rgba(255, 255, 255, 0.07);
            color: #ddd;
            border: 1px solid var(--fc-border);
        }

        .empty-state {
            padding: 55px 25px;
            text-align: center;
        }

        .empty-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--fc-orange);
            background: rgba(255, 107, 44, 0.12);
            font-size: 30px;
            font-weight: 900;
        }

        .empty-state h3 {
            font-size: 21px;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--fc-muted);
            max-width: 470px;
            margin: 0 auto 22px;
            line-height: 1.7;
            font-size: 14px;
        }

        .btn-fitcore {
            display: inline-block;
            background: var(--fc-orange);
            color: #111;
            border: 0;
            padding: 12px 19px;
            border-radius: 8px;
            font-size: 13px;
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
            font-size: 13px;
            font-weight: 700;
            transition: 0.2s;
        }

        .btn-outline-fitcore:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .demo-notice {
            border: 1px solid rgba(245, 196, 81, 0.3);
            background: rgba(245, 196, 81, 0.06);
            color: #e8d59c;
            border-radius: 11px;
            padding: 16px 18px;
            font-size: 12px;
            line-height: 1.7;
            margin-top: 22px;
        }

        .bottom-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 25px;
        }

        .bottom-actions p {
            color: var(--fc-muted);
            font-size: 12px;
            margin: 0;
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
            .page-header {
                padding-top: 38px;
            }

            .member-banner {
                padding: 20px;
            }

            .section-header {
                padding: 20px;
            }

            .table-responsive {
                padding: 0 10px 10px;
            }

            .bottom-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .bottom-actions .btn-fitcore,
            .bottom-actions .btn-outline-fitcore {
                text-align: center;
            }
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
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
                    <a class="nav-link" href="my_bookings.php">My Bookings</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="payment_history.php">
                        Payment History
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Heading -->
<header class="page-header">
    <div class="container">
        <div class="eyebrow">Member Account</div>

        <h1>Payment History</h1>

        <p>
            Review your FitCore membership payment, transaction reference,
            payment status, and payment date in one place.
        </p>
    </div>
</header>

<main class="page-content">
    <div class="container">

        <!-- Member Information -->
        <section class="member-banner">
            <div class="member-information">
                <div class="member-avatar" aria-hidden="true">
                    <?= fitcoreEscape(
                        strtoupper(substr(trim($memberName), 0, 1))
                    ) ?>
                </div>

                <div>
                    <span class="member-label">Member Account</span>

                    <h2><?= fitcoreEscape($memberName) ?></h2>

                    <p>
                        <?= $memberEmail !== ''
                            ? fitcoreEscape($memberEmail)
                            : 'No registered member details found in this session.' ?>
                    </p>
                </div>
            </div>

            <a href="index.php" class="btn-outline-fitcore">
                Back to FitCore Home
            </a>
        </section>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">

            <div class="col-md-6 col-xl-4">
                <div class="summary-card">
                    <div class="summary-top">
                        <span class="summary-title">Total Paid</span>

                        <div class="summary-icon" aria-hidden="true">$</div>
                    </div>

                    <div class="summary-value">
                        $<?= number_format($totalPaid, 2) ?>
                    </div>

                    <div class="summary-caption">
                        Confirmed demo payments in this session
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="summary-card">
                    <div class="summary-top">
                        <span class="summary-title">Payment Records</span>

                        <div class="summary-icon" aria-hidden="true">
                            &#9776;
                        </div>
                    </div>

                    <div class="summary-value">
                        <?= count($paymentHistory) ?>
                    </div>

                    <div class="summary-caption">
                        Simulated transactions available
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="summary-card">
                    <div class="summary-top">
                        <span class="summary-title">Membership Plan</span>

                        <div class="summary-icon" aria-hidden="true">
                            &#9733;
                        </div>
                    </div>

                    <div class="summary-value">
                        <?= fitcoreEscape($membershipName) ?>
                    </div>

                    <div class="summary-caption">
                        <?php if ($hasPayment): ?>
                            Monthly plan payment recorded for this session
                        <?php elseif ($hasMember): ?>
                            Selected plan; no payment recorded yet
                        <?php else: ?>
                            Register to select a membership plan
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Payment History Table -->
        <section class="section-card">
            <div class="section-header">
                <div>
                    <h2>Transaction Records</h2>

                    <p>
                        Details of membership payments recorded in this demo.
                    </p>
                </div>

                <span class="record-count">
                    <?= count($paymentHistory) ?>
                    <?= count($paymentHistory) === 1 ? 'Record' : 'Records' ?>
                </span>
            </div>

            <?php if (!empty($paymentHistory)): ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Transaction</th>
                                <th>Membership</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($paymentHistory as $record): ?>

                                <?php
                                $statusClass = 'status-other';

                                if (strtolower($record['status']) === 'paid') {
                                    $statusClass = 'status-paid';
                                } elseif (
                                    strtolower($record['status']) === 'pending'
                                ) {
                                    $statusClass = 'status-pending';
                                }
                                ?>

                                <tr>
                                    <td>
                                        <span class="transaction-reference">
                                            <?= fitcoreEscape(
                                                $record['transaction_id']
                                            ) ?>
                                        </span>

                                        <div class="plan-description">
                                            Demo transaction
                                        </div>
                                    </td>

                                    <td>
                                        <div class="plan-name">
                                            <?= fitcoreEscape(
                                                $record['membership']
                                            ) ?>
                                        </div>

                                        <div class="plan-description">
                                            Monthly membership
                                        </div>
                                    </td>

                                    <td>
                                        <strong>
                                            $<?= number_format(
                                                (float) $record['amount'],
                                                2
                                            ) ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= fitcoreEscape(
                                            $record['payment_method']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= fitcoreEscape(
                                            $record['payment_date']
                                        ) ?>
                                    </td>

                                    <td>
                                        <span class="status-badge <?= $statusClass ?>">
                                            <?= fitcoreEscape(
                                                $record['status']
                                            ) ?>
                                            (Demo)
                                        </span>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <div class="empty-state">
                    <div class="empty-icon" aria-hidden="true">
                        &#9776;
                    </div>

                    <h3>No Payment Records Yet</h3>

                    <p>
                        You have no completed membership payment in this
                        session. Register for a FitCore membership and
                        complete the simulated payment to see a transaction
                        appear here.
                    </p>

                    <a href="register.php" class="btn-fitcore">
                        Choose a Membership
                    </a>
                </div>

            <?php endif; ?>
        </section>

        <!-- Demonstration Notice -->
        <div class="demo-notice">
            <strong>Portfolio demonstration:</strong>
            These records are held in the current PHP session and are not
            stored permanently. They may disappear when the session expires
            or is cleared. Payment amounts, transaction references, and
            statuses are for demonstration only; no real money is charged.
        </div>

        <!-- Page Actions -->
        <div class="bottom-actions">
            <p>
                Need to explore another FitCore feature? Continue browsing
                the member demonstration.
            </p>

            <div class="d-flex flex-wrap gap-2">
                <a href="index.php" class="btn-outline-fitcore">
                    Member Home
                </a>

                <a href="register.php" class="btn-fitcore">
                    Membership Plans
                </a>

                <a href="../fitcore_demo.php" class="btn-outline-fitcore">
                    Role Selection
                </a>
            </div>
        </div>

    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-2">
            &copy; <?= date('Y') ?> FitCore Fitness Platform.
            Demonstration website by
            <a href="../index.php">Codvanta</a>.
        </p>

        <p class="mb-0">
            All payment records on this page are simulated.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


</body>
</html>