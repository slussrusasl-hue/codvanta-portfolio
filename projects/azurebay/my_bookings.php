
<?php
date_default_timezone_set('Asia/Colombo');
session_start();

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - My Bookings
|--------------------------------------------------------------------------
| Displays bookings created through booking.php.
| Demo bookings are stored in the current PHP session only.
| No database or real reservation service is connected.
*/

if (!isset($_SESSION['azurebay_bookings']) ||
    !is_array($_SESSION['azurebay_bookings'])) {
    $_SESSION['azurebay_bookings'] = [];
}

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/*
|--------------------------------------------------------------------------
| Cancel a demo booking
|--------------------------------------------------------------------------
| A POST request is used so cancellation does not happen through a GET link.
*/

$actionMessage = '';
$actionType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'cancel_booking') {

    $reference = trim($_POST['reference'] ?? '');

    if ($reference === '') {
        $actionMessage = 'Invalid booking reference.';
        $actionType = 'danger';
    } else {
        $found = false;

        foreach ($_SESSION['azurebay_bookings'] as $index => $booking) {
            if (($booking['reference'] ?? '') === $reference) {
                $found = true;

                /*
                 * Do not remove the booking.
                 * Mark it as cancelled so the demo history remains visible.
                 */
                $_SESSION['azurebay_bookings'][$index]['status'] = 'Cancelled';

                if (isset($_SESSION['azurebay_last_booking']) &&
                    ($_SESSION['azurebay_last_booking']['reference'] ?? '') === $reference) {
                    $_SESSION['azurebay_last_booking']['status'] = 'Cancelled';
                }

                $actionMessage = 'Your demo booking has been cancelled successfully.';
                break;
            }
        }

        if (!$found) {
            $actionMessage = 'The requested booking could not be found.';
            $actionType = 'danger';
        }
    }
}

/*
|--------------------------------------------------------------------------
| Get bookings
|--------------------------------------------------------------------------
*/

$bookings = array_values($_SESSION['azurebay_bookings']);

/* Newest bookings first. */
$bookings = array_reverse($bookings);

/*
|--------------------------------------------------------------------------
| Search and status filtering
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? 'all');

$allowedStatuses = [
    'all',
    'Demo Booking Received',
    'Cancelled'
];

if (!in_array($statusFilter, $allowedStatuses, true)) {
    $statusFilter = 'all';
}

$filteredBookings = [];

foreach ($bookings as $booking) {

    $reference = $booking['reference'] ?? '';
    $roomName = $booking['room_name'] ?? '';
    $guestName = $booking['full_name'] ?? '';
    $status = $booking['status'] ?? 'Demo Booking Received';

    $matchesSearch = $search === '' ||
        stripos($reference, $search) !== false ||
        stripos($roomName, $search) !== false ||
        stripos($guestName, $search) !== false;

    $matchesStatus = $statusFilter === 'all' ||
        $status === $statusFilter;

    if ($matchesSearch && $matchesStatus) {
        $filteredBookings[] = $booking;
    }
}

/*
|--------------------------------------------------------------------------
| Summary statistics
|--------------------------------------------------------------------------
*/

$totalBookings = count($bookings);
$activeBookings = 0;
$cancelledBookings = 0;
$totalEstimatedValue = 0;

foreach ($bookings as $booking) {
    $status = $booking['status'] ?? 'Demo Booking Received';

    if ($status === 'Cancelled') {
        $cancelledBookings++;
    } else {
        $activeBookings++;
        $totalEstimatedValue += (float)($booking['total'] ?? 0);
    }
}

/*
|--------------------------------------------------------------------------
| Formatting helpers
|--------------------------------------------------------------------------
*/

function displayDate($value): string
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', (string)$value);

    if (!$date || $date->format('Y-m-d') !== $value) {
        return 'Not available';
    }

    return $date->format('d M Y');
}

function statusClass($status): string
{
    return $status === 'Cancelled' ? 'status-cancelled' : 'status-active';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Bookings | Azure Bay Resort</title>

    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --ocean: #123e4a;
            --ocean-dark: #092b35;
            --gold: #c8a96b;
            --gold-light: #e6d4a8;
            --cream: #faf7f0;
            --text: #293b40;
            --muted: #718087;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: var(--ocean-dark);
            padding: 15px 0;
        }

        .navbar-brand {
            color: white;
            font-size: 25px;
            font-weight: 800;
        }

        .navbar-brand span {
            color: var(--gold);
        }

        .brand-subtitle {
            display: block;
            font-size: 10px;
            color: #d9e1df;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .navbar .nav-link {
            color: #e6eeee;
            font-size: 14px;
            margin: 0 7px;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--gold-light);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--ocean-dark);
            border: 1px solid var(--gold);
            border-radius: 4px;
            padding: 11px 19px;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-gold:hover {
            background: var(--gold-light);
            border-color: var(--gold-light);
            color: var(--ocean-dark);
        }

        .btn-outline-ocean {
            border: 1px solid var(--ocean);
            color: var(--ocean);
            border-radius: 4px;
            padding: 10px 17px;
        }

        .btn-outline-ocean:hover {
            background: var(--ocean);
            color: white;
        }

        .page-banner {
            background:
                linear-gradient(90deg, rgba(9,43,53,.96), rgba(18,62,74,.80)),
                url('assets/images/resort-hero.jpg') center/cover;
            padding: 53px 0;
            color: white;
        }

        .eyebrow {
            color: var(--gold);
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .page-banner h1 {
            font-size: clamp(30px, 4vw, 42px);
            font-weight: 800;
            margin: 10px 0;
        }

        .page-banner p {
            color: #d8e4e4;
            margin-bottom: 0;
            line-height: 1.8;
        }

        .breadcrumb-area {
            padding: 22px 0;
            color: var(--muted);
            font-size: 13px;
        }

        .breadcrumb-area a {
            color: var(--ocean);
        }

        .breadcrumb-area span {
            color: var(--gold);
            margin: 0 8px;
        }

        .stat-card {
            background: white;
            border: 1px solid #ece5d8;
            border-radius: 7px;
            padding: 22px;
            height: 100%;
            box-shadow: 0 6px 22px rgba(16, 50, 58, .03);
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 29px;
            color: var(--ocean-dark);
            font-weight: 800;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5eedf;
            color: #87692f;
            font-size: 19px;
        }

        .content-card {
            background: white;
            border: 1px solid #ece5d8;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 8px 28px rgba(16, 50, 58, .04);
            margin-bottom: 25px;
        }

        .content-card-header {
            padding: 22px 25px;
            border-bottom: 1px solid #eee8dd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .content-card-header h2 {
            color: var(--ocean-dark);
            font-size: 21px;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .content-card-header p {
            color: var(--muted);
            font-size: 12px;
            margin: 0;
        }

        .filter-area {
            padding: 20px 25px;
            background: #fffdf9;
            border-bottom: 1px solid #eee8dd;
        }

        .form-label {
            color: #52656a;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            border-color: #e2e5e1;
            border-radius: 4px;
            padding: 11px 12px;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 .2rem rgba(200, 169, 107, .15);
        }

        .booking-list {
            padding: 22px;
        }

        .booking-item {
            border: 1px solid #eee8dd;
            border-radius: 7px;
            margin-bottom: 20px;
            overflow: hidden;
            transition: box-shadow .2s ease;
        }

        .booking-item:last-child {
            margin-bottom: 0;
        }

        .booking-item:hover {
            box-shadow: 0 8px 24px rgba(16, 50, 58, .06);
        }

        .booking-top {
            padding: 17px 20px;
            background: #fffdf9;
            border-bottom: 1px solid #eee8dd;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .reference-label {
            color: var(--muted);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .reference-value {
            color: var(--ocean-dark);
            font-size: 16px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .status-badge {
            display: inline-block;
            border-radius: 30px;
            padding: 7px 11px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .3px;
        }

        .status-active {
            color: #456c47;
            background: #e8f3e6;
        }

        .status-cancelled {
            color: #8a3c34;
            background: #f9e7e4;
        }

        .booking-body {
            padding: 20px;
        }

        .room-image {
            width: 100%;
            height: 175px;
            object-fit: cover;
            background: #e7e4dc;
            border-radius: 5px;
        }

        .room-type {
            display: inline-block;
            background: #f5eedf;
            color: #87692f;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            padding: 6px 9px;
            border-radius: 3px;
            margin-bottom: 9px;
        }

        .room-name {
            color: var(--ocean-dark);
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .room-description {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .booking-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px 20px;
            margin-top: 17px;
        }

        .detail-label {
            color: var(--muted);
            font-size: 11px;
            margin-bottom: 5px;
        }

        .detail-value {
            color: var(--ocean-dark);
            font-size: 13px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .booking-total {
            background: var(--ocean);
            border-radius: 5px;
            padding: 17px;
            color: white;
            margin-top: 20px;
        }

        .booking-total span {
            color: #dce5e4;
            font-size: 11px;
        }

        .booking-total strong {
            color: var(--gold-light);
            font-size: 24px;
            display: block;
            margin-top: 4px;
        }

        .booking-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 18px;
        }

        .booking-actions .btn {
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 65px 25px;
        }

        .empty-icon {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            background: #f5eedf;
            color: #87692f;
            font-size: 32px;
        }

        .empty-state h3 {
            color: var(--ocean-dark);
            font-weight: 800;
            font-size: 24px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
            max-width: 470px;
            margin: 12px auto 22px;
        }

        .demo-note {
            background: #f8f2e4;
            border: 1px solid #ebdec1;
            color: #76663f;
            border-radius: 5px;
            padding: 15px 18px;
            font-size: 12px;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .alert {
            font-size: 13px;
        }

        .help-card {
            background: var(--ocean-dark);
            color: white;
            border-radius: 7px;
            padding: 24px;
            margin-bottom: 25px;
        }

        .help-card h3 {
            color: var(--gold-light);
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .help-card p {
            color: #d0dddd;
            font-size: 12px;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .help-card a {
            color: var(--gold-light);
            font-size: 12px;
            font-weight: 700;
        }

        .feature-strip {
            background: var(--ocean-dark);
            color: white;
            padding: 27px 0;
        }

        .feature-strip strong {
            display: block;
            color: var(--gold-light);
            font-size: 13px;
            margin-bottom: 5px;
        }

        .feature-strip span {
            font-size: 12px;
            color: #d4dfdf;
        }

        footer {
            background: #061f27;
            color: #c4d1d1;
            padding: 42px 0 20px;
        }

        footer h5 {
            color: white;
            font-size: 14px;
            margin-bottom: 18px;
        }

        footer p,
        footer a {
            font-size: 12px;
            line-height: 1.9;
            color: #c4d1d1;
        }

        footer a:hover {
            color: var(--gold-light);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.12);
            margin-top: 28px;
            padding-top: 17px;
            font-size: 11px;
            color: #91a5a8;
        }

        @media (max-width: 767px) {
            .page-banner {
                padding: 38px 0;
            }

            .booking-list {
                padding: 14px;
            }

            .booking-body {
                padding: 16px;
            }

            .booking-details {
                gap: 13px;
            }

            .room-image {
                height: 220px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            AZURE <span>BAY</span>
            <span class="brand-subtitle">Beachfront Resort & Spa</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="rooms.php">Our Rooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviews.php">Guest Reviews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-gold btn-sm" href="rooms.php">
                        Book a Stay
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="page-banner">
    <div class="container">
        <div class="eyebrow">Your Azure Bay Experience</div>
        <h1>My Bookings</h1>
        <p>
            Review your sample reservations, check your stay details
            and manage your demonstration bookings.
        </p>
    </div>
</section>

<main class="container pb-4">

    <div class="breadcrumb-area">
        <a href="index.php">Home</a>
        <span>/</span>
        My Bookings
    </div>

    <?php if ($actionMessage !== ''): ?>
        <div class="alert alert-<?= e($actionType) ?> alert-dismissible fade show"
             role="alert">
            <?= e($actionMessage) ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="demo-note">
        <strong>Portfolio demonstration:</strong>
        These bookings are stored only in your current PHP session.
        They are not real reservations, and room availability has not
        been checked against a live booking system.
    </div>

    <!-- Booking summary cards -->
    <section class="row g-3 mb-4">

        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-label">Total Bookings</div>
                    <div class="stat-icon">▤</div>
                </div>
                <div class="stat-value"><?= $totalBookings ?></div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-label">Active Demo Bookings</div>
                    <div class="stat-icon">✓</div>
                </div>
                <div class="stat-value"><?= $activeBookings ?></div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-label">Cancelled</div>
                    <div class="stat-icon">×</div>
                </div>
                <div class="stat-value"><?= $cancelledBookings ?></div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-label">Estimated Active Value</div>
                    <div class="stat-icon">$</div>
                </div>
                <div class="stat-value" style="font-size:24px">
                    $<?= number_format($totalEstimatedValue) ?>
                </div>
            </div>
        </div>

    </section>

    <div class="row g-4">

        <div class="col-lg-8">
            <section class="content-card">

                <div class="content-card-header">
                    <div>
                        <h2>Your Reservations</h2>
                        <p>
                            Showing <?= count($filteredBookings) ?>
                            of <?= $totalBookings ?> demo booking(s).
                        </p>
                    </div>

                    <a href="rooms.php" class="btn btn-gold btn-sm">
                        + New Booking
                    </a>
                </div>

                <!-- Search and filter -->
                <div class="filter-area">
                    <form method="GET" action="my_bookings.php">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-7">
                                <label for="search" class="form-label">
                                    Search Bookings
                                </label>
                                <input type="search" id="search" name="search"
                                       class="form-control"
                                       placeholder="Reference, room or guest name"
                                       value="<?= e($search) ?>">
                            </div>

                            <div class="col-md-5">
                                <label for="status" class="form-label">
                                    Booking Status
                                </label>
                                <select id="status" name="status"
                                        class="form-select">
                                    <option value="all"
                                        <?= $statusFilter === 'all' ? 'selected' : '' ?>>
                                        All Bookings
                                    </option>
                                    <option value="Demo Booking Received"
                                        <?= $statusFilter === 'Demo Booking Received' ? 'selected' : '' ?>>
                                        Active Demo Bookings
                                    </option>
                                    <option value="Cancelled"
                                        <?= $statusFilter === 'Cancelled' ? 'selected' : '' ?>>
                                        Cancelled
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-gold">
                                    Search
                                </button>
                                <a href="my_bookings.php"
                                   class="btn btn-outline-ocean">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="booking-list">

                    <?php if (empty($filteredBookings)): ?>

                        <div class="empty-state">
                            <div class="empty-icon">▤</div>

                            <?php if ($totalBookings === 0): ?>
                                <h3>No Bookings Yet</h3>
                                <p>
                                    You haven't submitted a demo booking.
                                    Explore our rooms and create a sample
                                    reservation to see how the booking workflow works.
                                </p>

                                <a href="rooms.php" class="btn btn-gold">
                                    Explore Our Rooms →
                                </a>

                            <?php else: ?>
                                <h3>No Matching Bookings</h3>
                                <p>
                                    No bookings match your current search
                                    or status filter. Try a different search
                                    or reset the filters.
                                </p>

                                <a href="my_bookings.php"
                                   class="btn btn-outline-ocean">
                                    Reset Filters
                                </a>
                            <?php endif; ?>
                        </div>

                    <?php else: ?>

                        <?php foreach ($filteredBookings as $booking): ?>

                            <?php
                            $status = $booking['status'] ?? 'Demo Booking Received';
                            $isCancelled = $status === 'Cancelled';
                            ?>

                            <article class="booking-item">

                                <div class="booking-top">
                                    <div>
                                        <div class="reference-label">
                                            Booking Reference
                                        </div>
                                        <div class="reference-value">
                                            <?= e($booking['reference'] ?? 'N/A') ?>
                                        </div>
                                    </div>

                                    <span class="status-badge <?= e(statusClass($status)) ?>">
                                        <?= e($status) ?>
                                    </span>
                                </div>

                                <div class="booking-body">
                                    <div class="row g-3">

                                        <div class="col-md-5">
                                            <img
                                                src="<?= e($booking['room_image'] ?? 'assets/images/resort-about.jpg') ?>"
                                                alt="<?= e($booking['room_name'] ?? 'Resort room') ?>"
                                                class="room-image"
                                                loading="lazy"
                                                onerror="this.style.display='none';"
                                            >
                                        </div>

                                        <div class="col-md-7">
                                            <span class="room-type">
                                                <?= e($booking['room_type'] ?? 'Resort Room') ?>
                                            </span>

                                            <h3 class="room-name">
                                                <?= e($booking['room_name'] ?? 'Room') ?>
                                            </h3>

                                            <p class="room-description">
                                                Thank you for choosing Azure Bay Resort.
                                                Review the details of your sample stay below.
                                            </p>

                                            <div class="booking-details">

                                                <div>
                                                    <div class="detail-label">
                                                        Check-in
                                                    </div>
                                                    <div class="detail-value">
                                                        <?= e(displayDate($booking['check_in'] ?? '')) ?>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="detail-label">
                                                        Check-out
                                                    </div>
                                                    <div class="detail-value">
                                                        <?= e(displayDate($booking['check_out'] ?? '')) ?>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="detail-label">
                                                        Duration
                                                    </div>
                                                    <div class="detail-value">
                                                        <?= (int)($booking['nights'] ?? 0) ?>
                                                        <?= (int)($booking['nights'] ?? 0) === 1 ? 'night' : 'nights' ?>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="detail-label">
                                                        Guests
                                                    </div>
                                                    <div class="detail-value">
                                                        <?= (int)($booking['guests'] ?? 0) ?>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="detail-label">
                                                        Guest Name
                                                    </div>
                                                    <div class="detail-value">
                                                        <?= e($booking['full_name'] ?? '') ?>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="detail-label">
                                                        Rate per Night
                                                    </div>
                                                    <div class="detail-value">
                                                        $<?= number_format((float)($booking['room_price'] ?? 0)) ?>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="booking-total">
                                        <span>Estimated Room Total</span>
                                        <strong>
                                            $<?= number_format((float)($booking['total'] ?? 0)) ?>
                                        </strong>
                                        <span class="d-block mt-1">
                                            Sample pricing only. Taxes and additional charges
                                            are not included.
                                        </span>
                                    </div>

                                    <div class="booking-actions">

                                        <button type="button"
                                                class="btn btn-outline-ocean"
                                                onclick="toggleBookingDetails('details-<?= e($booking['reference'] ?? '') ?>', this)">
                                            View Guest Details
                                        </button>

                                        <?php if (!$isCancelled): ?>
                                            <form action="my_bookings.php" method="POST"
                                                  class="cancel-form"
                                                  onsubmit="return confirm('Cancel this demo booking?');">
                                                <input type="hidden" name="action"
                                                       value="cancel_booking">
                                                <input type="hidden" name="reference"
                                                       value="<?= e($booking['reference'] ?? '') ?>">

                                                <button type="submit"
                                                        class="btn btn-outline-danger">
                                                    Cancel Demo Booking
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                    </div>

                                    <div id="details-<?= e($booking['reference'] ?? '') ?>"
                                         class="mt-3 p-3 rounded"
                                         style="display:none;background:#fffdf9;border:1px solid #eee8dd">

                                        <div class="booking-details mt-0">
                                            <div>
                                                <div class="detail-label">Email Address</div>
                                                <div class="detail-value">
                                                    <?= e($booking['email'] ?? 'Not provided') ?>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="detail-label">Phone Number</div>
                                                <div class="detail-value">
                                                    <?= e($booking['phone'] ?? 'Not provided') ?>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="detail-label">Country</div>
                                                <div class="detail-value">
                                                    <?= e($booking['country'] ?? 'Not provided') ?>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="detail-label">Submitted</div>
                                                <div class="detail-value">
                                                    <?= e($booking['created_at'] ?? 'Not available') ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <div class="detail-label">
                                                Special Requests
                                            </div>
                                            <div class="detail-value">
                                                <?= ($booking['special_requests'] ?? '') !== ''
                                                    ? nl2br(e($booking['special_requests']))
                                                    : 'No special requests provided.' ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </article>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>
            </section>
        </div>

        <!-- Help sidebar -->
        <div class="col-lg-4">

            <aside class="help-card">
                <h3>Need Help Planning Your Stay?</h3>
                <p>
                    Explore our room collection and find the accommodation
                    that best matches your next coastal getaway.
                </p>
                <a href="rooms.php">Explore All Rooms →</a>
            </aside>

            <aside class="content-card">
                <div class="content-card-header">
                    <div>
                        <h2>Booking Information</h2>
                        <p>Important details about this demo.</p>
                    </div>
                </div>

                <div class="p-4">
                    <div class="mb-4">
                        <strong class="d-block mb-2"
                                style="font-size:13px;color:#092b35">
                            Demo Reference
                        </strong>
                        <p class="text-secondary small mb-0"
                           style="line-height:1.8">
                            Each submitted booking receives a unique reference
                            for identification within the current session.
                        </p>
                    </div>

                    <div class="mb-4">
                        <strong class="d-block mb-2"
                                style="font-size:13px;color:#092b35">
                            Cancellation
                        </strong>
                        <p class="text-secondary small mb-0"
                           style="line-height:1.8">
                            You can cancel an active demo booking. The entry
                            remains visible in your history with its status
                            changed to Cancelled.
                        </p>
                    </div>

                    <div>
                        <strong class="d-block mb-2"
                                style="font-size:13px;color:#092b35">
                            Data Storage
                        </strong>
                        <p class="text-secondary small mb-0"
                           style="line-height:1.8">
                            This demo uses PHP session storage. It does not
                            connect to a database or send emails.
                        </p>
                    </div>
                </div>
            </aside>

            <div class="text-center mb-4">
                <a href="index.php" class="btn btn-outline-ocean w-100">
                    ← Return to Homepage
                </a>
            </div>

        </div>

    </div>

</main>

<section class="feature-strip">
    <div class="container">
        <div class="row g-4 text-center text-md-start">
            <div class="col-md-4">
                <strong>Thoughtful Comfort</strong>
                <span>Discover rooms designed for relaxing stays.</span>
            </div>
            <div class="col-md-4">
                <strong>Coastal Hospitality</strong>
                <span>Enjoy the atmosphere of a tropical resort.</span>
            </div>
            <div class="col-md-4">
                <strong>Easy Demo Booking</strong>
                <span>Explore the booking workflow without payment.</span>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <a href="index.php" class="navbar-brand">
                    AZURE <span>BAY</span>
                </a>
                <p class="mt-3">
                    A peaceful beachfront escape where comfort, nature
                    and warm hospitality come together.
                </p>
            </div>

            <div class="col-lg-2 col-md-6">
                <h5>Quick Links</h5>
                <p>
                    <a href="index.php">Home</a><br>
                    <a href="rooms.php">Our Rooms</a><br>
                    <a href="reviews.php">Guest Reviews</a><br>
                    <a href="contact.php">Contact Us</a>
                </p>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5>Guest Services</h5>
                <p>
                    <a href="my_bookings.php">My Bookings</a><br>
                    Room Reservations<br>
                    Resort Facilities<br>
                    Guest Assistance
                </p>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5>Contact</h5>
                <p>
                    Email: info@azurebay.example<br>
                    Phone: +94 11 234 5678<br>
                    Sri Lanka
                </p>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
            <span>© <?= date('Y') ?> Azure Bay Resort. All rights reserved.</span>
            <span>Created as a Codvanta portfolio demonstration.</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    function toggleBookingDetails(id, button) {
        const panel = document.getElementById(id);

        if (!panel) return;

        const isHidden = panel.style.display === 'none';

        panel.style.display = isHidden ? 'block' : 'none';

        button.textContent = isHidden
            ? 'Hide Guest Details'
            : 'View Guest Details';
    }
</script>

</body>
</html>