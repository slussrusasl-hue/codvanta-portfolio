
<?php
session_start();

date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| FitCore - My Bookings
|--------------------------------------------------------------------------
| Demonstration features:
| - Display the logged-in demo member's bookings.
| - View booking details and status.
| - Cancel an upcoming booking.
| - Store booking data in PHP sessions, not a database.
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function formatDate($date)
{
    $timestamp = strtotime($date);

    return $timestamp ? date('D, d M Y', $timestamp) : 'Date unavailable';
}

function formatTime($time)
{
    $timestamp = strtotime($time);

    return $timestamp ? date('h:i A', $timestamp) : 'Time unavailable';
}

function isUpcomingBooking($booking)
{
    if (empty($booking['session_date']) || empty($booking['start_time'])) {
        return false;
    }

    $sessionDateTime = DateTimeImmutable::createFromFormat(
        '!Y-m-d H:i',
        $booking['session_date'] . ' ' . $booking['start_time']
    );

    if (!$sessionDateTime) {
        return false;
    }

    return $sessionDateTime > new DateTimeImmutable();
}

/*
|--------------------------------------------------------------------------
| Member session validation
|--------------------------------------------------------------------------
*/

$member = $_SESSION['fitcore_demo_member'] ?? null;

if (!is_array($member) || empty($member['email'])) {
    header('Location: register.php?message=register_first');
    exit;
}

$memberEmail = strtolower(trim($member['email']));
$memberName = $member['full_name'] ?? 'FitCore Member';

/*
|--------------------------------------------------------------------------
| CSRF protection for cancellation forms
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['fitcore_booking_csrf'])) {
    $_SESSION['fitcore_booking_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['fitcore_booking_csrf'];

/*
|--------------------------------------------------------------------------
| Booking storage
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['fitcore_demo_bookings']) ||
    !is_array($_SESSION['fitcore_demo_bookings'])) {
    $_SESSION['fitcore_demo_bookings'] = [];
}

$allBookings = &$_SESSION['fitcore_demo_bookings'];

$successMessage = '';
$errorMessage = '';

/*
|--------------------------------------------------------------------------
| Cancel booking
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $submittedToken = $_POST['csrf_token'] ?? '';
    $action = $_POST['action'] ?? '';
    $bookingReference = trim($_POST['booking_reference'] ?? '');

    if (
        !is_string($submittedToken) ||
        !hash_equals($csrfToken, $submittedToken)
    ) {
        $errorMessage = 'Your request could not be verified. Please refresh the page and try again.';

    } elseif ($action !== 'cancel_booking' || $bookingReference === '') {
        $errorMessage = 'Invalid booking request.';

    } else {

        $bookingFound = false;

        foreach ($allBookings as $index => $booking) {

            $bookingOwner = strtolower(
                trim($booking['member_email'] ?? '')
            );

            if (
                ($booking['reference'] ?? '') === $bookingReference &&
                $bookingOwner === $memberEmail
            ) {
                $bookingFound = true;

                if (($booking['status'] ?? '') === 'Cancelled') {
                    $errorMessage = 'This booking has already been cancelled.';

                } elseif (!isUpcomingBooking($booking)) {
                    $errorMessage = 'Only upcoming sessions can be cancelled.';

                } else {
                    $allBookings[$index]['status'] = 'Cancelled';
                    $allBookings[$index]['cancelled_at'] = date('Y-m-d H:i:s');

                    header('Location: my_bookings.php?cancelled=1');
                    exit;
                }

                break;
            }
        }

        if (!$bookingFound) {
            $errorMessage = 'The selected booking could not be found.';
        }
    }
}

/*
|--------------------------------------------------------------------------
| Flash message after successful cancellation
|--------------------------------------------------------------------------
*/

if (isset($_GET['cancelled']) && $_GET['cancelled'] === '1') {
    $successMessage = 'Your booking has been cancelled successfully.';
}

/*
|--------------------------------------------------------------------------
| Get only this member's bookings
|--------------------------------------------------------------------------
*/

$memberBookings = [];

foreach ($allBookings as $booking) {
    $bookingOwner = strtolower(
        trim($booking['member_email'] ?? '')
    );

    if ($bookingOwner === $memberEmail) {
        $memberBookings[] = $booking;
    }
}

/*
|--------------------------------------------------------------------------
| Sort bookings by session date and time, newest session first
|--------------------------------------------------------------------------
*/

usort($memberBookings, function ($a, $b) {
    $dateA = ($a['session_date'] ?? '') . ' ' . ($a['start_time'] ?? '');
    $dateB = ($b['session_date'] ?? '') . ' ' . ($b['start_time'] ?? '');

    return strcmp($dateB, $dateA);
});

/*
|--------------------------------------------------------------------------
| Dashboard statistics
|--------------------------------------------------------------------------
*/

$totalBookings = count($memberBookings);
$confirmedBookings = 0;
$cancelledBookings = 0;
$upcomingBookings = 0;

foreach ($memberBookings as $booking) {

    $status = $booking['status'] ?? 'Confirmed';

    if ($status === 'Confirmed') {
        $confirmedBookings++;

        if (isUpcomingBooking($booking)) {
            $upcomingBookings++;
        }
    }

    if ($status === 'Cancelled') {
        $cancelledBookings++;
    }
}

$activeTab = $_GET['status'] ?? 'all';

$validTabs = ['all', 'upcoming', 'cancelled'];

if (!in_array($activeTab, $validTabs, true)) {
    $activeTab = 'all';
}

$visibleBookings = [];

foreach ($memberBookings as $booking) {

    $status = $booking['status'] ?? 'Confirmed';

    if ($activeTab === 'upcoming') {
        if ($status !== 'Confirmed' || !isUpcomingBooking($booking)) {
            continue;
        }
    } elseif ($activeTab === 'cancelled') {
        if ($status !== 'Cancelled') {
            continue;
        }
    }

    $visibleBookings[] = $booking;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Bookings | FitCore</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --fc-bg: #101010;
            --fc-card: #191919;
            --fc-orange: #ff6b2c;
            --fc-orange-hover: #ff824d;
            --fc-text: #f5f5f5;
            --fc-muted: #a8a8a8;
            --fc-border: #333333;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--fc-bg);
            color: var(--fc-text);
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: #151515;
            border-bottom: 1px solid var(--fc-border);
        }

        .navbar-brand {
            color: var(--fc-orange) !important;
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .nav-link {
            color: #dedede !important;
            transition: color .2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--fc-orange) !important;
        }

        .btn-orange {
            background: var(--fc-orange);
            border: 1px solid var(--fc-orange);
            color: #111;
            border-radius: 8px;
            padding: 11px 20px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-orange:hover {
            background: var(--fc-orange-hover);
            border-color: var(--fc-orange-hover);
            color: #111;
            transform: translateY(-1px);
        }

        .btn-outline-orange {
            border: 1px solid var(--fc-orange);
            color: var(--fc-orange);
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .btn-outline-orange:hover {
            background: var(--fc-orange);
            color: #111;
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid #a94444;
            color: #ff9696;
            border-radius: 7px;
            padding: 8px 13px;
            font-weight: 600;
            font-size: .88rem;
        }

        .btn-cancel:hover {
            background: #a12c2c;
            color: #fff;
        }

        .page-hero {
            padding: 65px 0 55px;
            background:
                linear-gradient(90deg, rgba(10,10,10,.96), rgba(10,10,10,.76)),
                url("https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1800&q=80")
                center/cover no-repeat;
            border-bottom: 1px solid var(--fc-border);
        }

        .eyebrow {
            color: var(--fc-orange);
            font-size: .85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .page-hero h1 {
            font-size: clamp(2.2rem, 5vw, 3.4rem);
            font-weight: 900;
            margin-top: 12px;
        }

        .page-hero p {
            color: #c6c6c6;
            max-width: 650px;
            line-height: 1.8;
        }

        .section-space {
            padding: 50px 0 75px;
        }

        .muted {
            color: var(--fc-muted);
        }

        .stat-card {
            height: 100%;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 22px;
            transition: transform .2s, border-color .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: #555;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: rgba(255,107,44,.12);
            color: var(--fc-orange);
            font-size: 1.25rem;
            margin-bottom: 18px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 900;
        }

        .content-panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 15px;
            overflow: hidden;
        }

        .panel-heading {
            padding: 23px;
            border-bottom: 1px solid var(--fc-border);
        }

        .panel-body {
            padding: 23px;
        }

        .booking-card {
            background: #151515;
            border: 1px solid #343434;
            border-radius: 13px;
            overflow: hidden;
            margin-bottom: 20px;
            transition: border-color .2s;
        }

        .booking-card:hover {
            border-color: #555;
        }

        .booking-card:last-child {
            margin-bottom: 0;
        }

        .booking-accent {
            height: 4px;
            background: var(--fc-orange);
        }

        .booking-content {
            padding: 22px;
        }

        .booking-title {
            font-size: 1.25rem;
            font-weight: 800;
        }

        .booking-reference {
            color: var(--fc-orange);
            font-size: .82rem;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .booking-detail {
            display: flex;
            gap: 11px;
            align-items: flex-start;
            color: #d0d0d0;
            font-size: .93rem;
        }

        .detail-icon {
            color: var(--fc-orange);
            width: 20px;
            flex-shrink: 0;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 800;
        }

        .status-confirmed {
            background: rgba(45, 180, 105, .14);
            color: #72e0a2;
            border: 1px solid rgba(45, 180, 105, .35);
        }

        .status-cancelled {
            background: rgba(230, 70, 70, .12);
            color: #ff9696;
            border: 1px solid rgba(230, 70, 70, .3);
        }

        .status-completed {
            background: rgba(100, 160, 240, .12);
            color: #9bc5ff;
            border: 1px solid rgba(100, 160, 240, .3);
        }

        .status-pending {
            background: rgba(255, 193, 7, .12);
            color: #f5d26f;
            border: 1px solid rgba(255, 193, 7, .3);
        }

        .filter-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-tab {
            display: inline-block;
            border: 1px solid #414141;
            border-radius: 8px;
            padding: 9px 15px;
            color: #ddd;
            font-weight: 600;
            font-size: .9rem;
        }

        .filter-tab:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .filter-tab.active {
            color: #111;
            background: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px;
        }

        .empty-icon {
            width: 85px;
            height: 85px;
            margin: 0 auto 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,107,44,.12);
            color: var(--fc-orange);
            font-size: 2.2rem;
        }

        .session-fee {
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--fc-orange);
        }

        .demo-notice {
            border: 1px solid #66502b;
            background: rgba(255,193,7,.07);
            color: #e8d5a5;
            padding: 15px;
            border-radius: 10px;
            font-size: .9rem;
            line-height: 1.6;
        }

        .alert-success {
            background: #173021;
            border-color: #285b3c;
            color: #c4f4d3;
        }

        .alert-danger {
            background: #321b1b;
            border-color: #683131;
            color: #ffcaca;
        }

        footer {
            background: #0a0a0a;
            border-top: 1px solid var(--fc-border);
            padding: 30px 0;
            color: var(--fc-muted);
        }

        footer a {
            color: var(--fc-orange);
        }

        @media (max-width: 767px) {
            .page-hero {
                padding: 45px 0 40px;
            }

            .section-space {
                padding: 35px 0 50px;
            }

            .panel-heading,
            .panel-body,
            .booking-content {
                padding: 18px;
            }

            .stat-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
            FITCORE<span style="color:#fff;">.</span>
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
                    <a class="nav-link active" href="my_bookings.php">My Bookings</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="payment_history.php">Payments</a>
                </li>

                <li class="nav-item">
                    <a class="btn btn-orange ms-lg-2 mt-2 mt-lg-0" href="classes.php">
                        Book a Session
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="page-hero">
    <div class="container">

        <div class="eyebrow">Your fitness journey</div>

        <h1>My <span style="color:var(--fc-orange);">Bookings</span></h1>

        <p class="mb-0">
            Keep track of your training sessions, check upcoming classes
            and manage your fitness schedule in one place.
        </p>

    </div>
</section>

<!-- Main content -->
<section class="section-space">
    <div class="container">

        <!-- Welcome row -->
        <div class="d-flex flex-column flex-md-row justify-content-between
                    align-items-md-center gap-3 mb-4">

            <div>
                <div class="eyebrow">Member area</div>
                <h2 class="fw-bold mt-2 mb-1">
                    Welcome, <?= e($memberName) ?>
                </h2>
                <p class="muted mb-0">
                    Here is an overview of your fitness bookings.
                </p>
            </div>

            <a href="classes.php" class="btn btn-orange">
                + Book a New Session
            </a>

        </div>

        <!-- Flash messages -->
        <?php if ($successMessage !== ''): ?>
            <div class="alert alert-success mb-4" role="alert">
                <strong>Success!</strong> <?= e($successMessage) ?>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage !== ''): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <strong>Unable to complete the request.</strong>
                <?= e($errorMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row g-3 mb-4">

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">📋</div>
                    <div class="stat-number"><?= $totalBookings ?></div>
                    <div class="muted">Total Bookings</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-number"><?= $upcomingBookings ?></div>
                    <div class="muted">Upcoming Sessions</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">✓</div>
                    <div class="stat-number"><?= $confirmedBookings ?></div>
                    <div class="muted">Confirmed Bookings</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">↩</div>
                    <div class="stat-number"><?= $cancelledBookings ?></div>
                    <div class="muted">Cancelled Bookings</div>
                </div>
            </div>

        </div>

        <!-- Bookings panel -->
        <div class="content-panel">

            <div class="panel-heading">
                <div class="d-flex flex-column flex-md-row
                            justify-content-between align-items-md-center gap-3">

                    <div>
                        <h4 class="fw-bold mb-1">Your Training Sessions</h4>
                        <p class="muted mb-0">
                            View and manage your class reservations.
                        </p>
                    </div>

                    <div class="filter-tabs">

                        <a
                            class="filter-tab <?= $activeTab === 'all' ? 'active' : '' ?>"
                            href="my_bookings.php?status=all"
                        >
                            All (<?= $totalBookings ?>)
                        </a>

                        <a
                            class="filter-tab <?= $activeTab === 'upcoming' ? 'active' : '' ?>"
                            href="my_bookings.php?status=upcoming"
                        >
                            Upcoming (<?= $upcomingBookings ?>)
                        </a>

                        <a
                            class="filter-tab <?= $activeTab === 'cancelled' ? 'active' : '' ?>"
                            href="my_bookings.php?status=cancelled"
                        >
                            Cancelled (<?= $cancelledBookings ?>)
                        </a>

                    </div>
                </div>
            </div>

            <div class="panel-body">

                <?php if (empty($visibleBookings)): ?>

                    <div class="empty-state">

                        <div class="empty-icon">📅</div>

                        <?php if ($totalBookings === 0): ?>

                            <h3 class="fw-bold">No Bookings Yet</h3>

                            <p class="muted mx-auto mb-4" style="max-width:480px;">
                                You haven't booked a fitness session yet.
                                Explore our classes and choose a session
                                that fits your fitness goals.
                            </p>

                            <a href="classes.php" class="btn btn-orange">
                                Explore Fitness Classes
                            </a>

                        <?php elseif ($activeTab === 'upcoming'): ?>

                            <h3 class="fw-bold">No Upcoming Sessions</h3>

                            <p class="muted mx-auto mb-4" style="max-width:480px;">
                                You don't have any upcoming confirmed sessions.
                                Browse our classes to plan your next workout.
                            </p>

                            <a href="classes.php" class="btn btn-orange">
                                Browse Classes
                            </a>

                        <?php else: ?>

                            <h3 class="fw-bold">No Cancelled Bookings</h3>

                            <p class="muted mx-auto mb-4" style="max-width:480px;">
                                There are no cancelled bookings in this view.
                            </p>

                            <a href="my_bookings.php?status=all"
                               class="btn btn-outline-orange">
                                View All Bookings
                            </a>

                        <?php endif; ?>

                    </div>

                <?php else: ?>

                    <?php foreach ($visibleBookings as $booking): ?>

                        <?php
                            $status = $booking['status'] ?? 'Confirmed';

                            if ($status === 'Cancelled') {
                                $statusClass = 'status-cancelled';
                            } elseif ($status === 'Completed') {
                                $statusClass = 'status-completed';
                            } elseif ($status === 'Pending') {
                                $statusClass = 'status-pending';
                            } else {
                                $statusClass = 'status-confirmed';
                            }

                            $canCancel = (
                                $status === 'Confirmed' &&
                                isUpcomingBooking($booking)
                            );

                            $sessionDate = $booking['session_date'] ?? '';
                            $sessionTime = $booking['start_time'] ?? '';
                            $sessionEnd = $booking['end_time'] ?? '';
                            $bookingPrice = (float)($booking['price'] ?? 0);
                        ?>

                        <div class="booking-card">

                            <div class="booking-accent"></div>

                            <div class="booking-content">

                                <div class="d-flex flex-column flex-md-row
                                            justify-content-between gap-3 mb-4">

                                    <div>
                                        <div class="booking-reference mb-2">
                                            REF:
                                            <?= e($booking['reference'] ?? 'N/A') ?>
                                        </div>

                                        <h3 class="booking-title mb-1">
                                            <?= e($booking['class_name'] ?? 'Fitness Session') ?>
                                        </h3>

                                        <div class="muted">
                                            <?= e($booking['category'] ?? 'Fitness') ?>
                                        </div>
                                    </div>

                                    <div class="text-md-end">
                                        <span class="status-badge <?= e($statusClass) ?>">
                                            <?= e($status) ?>
                                        </span>

                                        <div class="session-fee mt-2">
                                            $<?= number_format($bookingPrice, 2) ?>
                                        </div>

                                        <div class="muted small">Sample session fee</div>
                                    </div>

                                </div>

                                <div class="row g-3 mb-4">

                                    <div class="col-sm-6 col-lg-3">
                                        <div class="booking-detail">
                                            <span class="detail-icon">📅</span>
                                            <div>
                                                <div class="muted small">Session Date</div>
                                                <strong>
                                                    <?= e(formatDate($sessionDate)) ?>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-3">
                                        <div class="booking-detail">
                                            <span class="detail-icon">🕒</span>
                                            <div>
                                                <div class="muted small">Session Time</div>
                                                <strong>
                                                    <?= e(formatTime($sessionTime)) ?>
                                                </strong>

                                                <?php if ($sessionEnd !== ''): ?>
                                                    <div class="muted small">
                                                        Until <?= e(formatTime($sessionEnd)) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-3">
                                        <div class="booking-detail">
                                            <span class="detail-icon">🏋️</span>
                                            <div>
                                                <div class="muted small">Trainer</div>
                                                <strong>
                                                    <?= e($booking['trainer'] ?? 'Not assigned') ?>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-3">
                                        <div class="booking-detail">
                                            <span class="detail-icon">💳</span>
                                            <div>
                                                <div class="muted small">Payment Status</div>
                                                <strong>
                                                    <?= e($booking['payment_status'] ?? 'Not Paid') ?>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <?php if (!empty($booking['booking_note'])): ?>
                                    <div class="p-3 mb-3 rounded"
                                         style="background:#202020;">

                                        <div class="fw-bold mb-1">
                                            Note for Trainer
                                        </div>

                                        <div class="muted">
                                            <?= nl2br(e($booking['booking_note'])) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex flex-column flex-sm-row
                                            justify-content-between align-items-sm-center
                                            gap-3 pt-3"
                                     style="border-top:1px solid #333;">

                                    <div class="muted small">
                                        Booked on:
                                        <?php
                                            $createdAt = $booking['created_at'] ?? '';
                                            $createdTimestamp = $createdAt !== ''
                                                ? strtotime($createdAt)
                                                : false;
                                        ?>

                                        <?= $createdTimestamp
                                            ? e(date('d M Y, h:i A', $createdTimestamp))
                                            : 'Not available' ?>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2">

                                        <a
                                            href="classes.php"
                                            class="btn btn-outline-orange"
                                        >
                                            Browse Classes
                                        </a>

                                        <?php if ($canCancel): ?>

                                            <form
                                                method="POST"
                                                action="my_bookings.php?status=all"
                                                class="cancel-form"
                                                data-class-name="<?= e($booking['class_name'] ?? 'this session') ?>"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="csrf_token"
                                                    value="<?= e($csrfToken) ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="cancel_booking"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="booking_reference"
                                                    value="<?= e($booking['reference'] ?? '') ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-cancel"
                                                >
                                                    Cancel Booking
                                                </button>

                                            </form>

                                        <?php elseif ($status === 'Cancelled'): ?>

                                            <span class="btn btn-outline-secondary disabled">
                                                Booking Cancelled
                                            </span>

                                        <?php elseif ($status === 'Confirmed'): ?>

                                            <span class="muted small align-self-center">
                                                Cancellation unavailable for this session.
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </div>

        <!-- Demo information -->
        <div class="demo-notice mt-4">
            <strong>Portfolio demonstration:</strong>
            Booking information is stored temporarily in your PHP session.
            It is not saved to a database, and payment status is only sample
            information. Closing the session or clearing session data may
            remove your bookings.
        </div>

    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">

        <div class="row align-items-center gy-3">

            <div class="col-md-6">
                <div class="fw-bold" style="color:var(--fc-orange);">
                    FITCORE.
                </div>
                <small>Train stronger. Live better.</small>
            </div>

            <div class="col-md-6 text-md-end">
                <small>
                    Demo platform developed by
                    <a href="../index.php">Codvanta</a>
                </small>
            </div>

        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Ask for confirmation before cancelling a booking.
    document.querySelectorAll('.cancel-form').forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const className = form.dataset.className || 'this session';

            const confirmed = window.confirm(
                'Are you sure you want to cancel your booking for "' +
                className +
                '"?'
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });

    });
</script>

</body>
</html>
