
<?php
date_default_timezone_set('Asia/Colombo');
session_start();

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Booking Demonstration
|--------------------------------------------------------------------------
| This is a demo booking flow.
| Bookings are stored in the PHP session, not a database.
| No real payment or room availability check is performed.
*/

$rooms = [
    1 => [
        'name' => 'Deluxe Ocean View',
        'type' => 'Deluxe Room',
        'price' => 180,
        'guests' => 2,
        'size' => '32 m²',
        'bed' => 'King-size bed',
        'image' => 'assets/images/deluxe-ocean.jpg',
        'description' => 'Elegant accommodation with beautiful ocean views.'
    ],
    2 => [
        'name' => 'Garden Serenity Room',
        'type' => 'Deluxe Room',
        'price' => 150,
        'guests' => 2,
        'size' => '28 m²',
        'bed' => 'Queen-size bed',
        'image' => 'assets/images/garden-room.jpg',
        'description' => 'A peaceful retreat surrounded by tropical greenery.'
    ],
    3 => [
        'name' => 'Premium Beach Suite',
        'type' => 'Suite',
        'price' => 290,
        'guests' => 3,
        'size' => '48 m²',
        'bed' => 'King-size bed and sofa bed',
        'image' => 'assets/images/beach-suite.jpg',
        'description' => 'A spacious suite designed for an elevated coastal stay.'
    ],
    4 => [
        'name' => 'Family Coastal Suite',
        'type' => 'Suite',
        'price' => 340,
        'guests' => 5,
        'size' => '62 m²',
        'bed' => 'King-size bed and twin beds',
        'image' => 'assets/images/family-suite.jpg',
        'description' => 'A generous family suite with comfortable living space.'
    ],
    5 => [
        'name' => 'Azure Presidential Villa',
        'type' => 'Villa',
        'price' => 450,
        'guests' => 4,
        'size' => '95 m²',
        'bed' => 'King-size bed and twin beds',
        'image' => 'assets/images/presidential-villa.jpg',
        'description' => 'An exclusive villa for a private luxury getaway.'
    ],
    6 => [
        'name' => 'Horizon Ocean Suite',
        'type' => 'Suite',
        'price' => 320,
        'guests' => 3,
        'size' => '52 m²',
        'bed' => 'King-size bed and sofa bed',
        'image' => 'assets/images/horizon-suite.jpg',
        'description' => 'A sophisticated suite with panoramic coastal scenery.'
    ]
];

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function validDate(string $value): bool
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

    return $date !== false && $date->format('Y-m-d') === $value;
}

$today = new DateTimeImmutable('today');

$errors = [];
$submitted = false;
$booking = null;

/*
|--------------------------------------------------------------------------
| Process a new demo booking
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $roomId = filter_input(INPUT_POST, 'room_id', FILTER_VALIDATE_INT);

    $checkInValue = trim($_POST['check_in'] ?? '');
    $checkOutValue = trim($_POST['check_out'] ?? '');
    $guestValue = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT);

    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $specialRequests = trim($_POST['special_requests'] ?? '');

    if (!$roomId || !isset($rooms[$roomId])) {
        $errors[] = 'Please select a valid room.';
    }

    if (!validDate($checkInValue)) {
        $errors[] = 'Please select a valid check-in date.';
    }

    if (!validDate($checkOutValue)) {
        $errors[] = 'Please select a valid check-out date.';
    }

    if (validDate($checkInValue) && validDate($checkOutValue)) {
        $checkInDate = new DateTimeImmutable($checkInValue);
        $checkOutDate = new DateTimeImmutable($checkOutValue);

        if ($checkInDate < $today) {
            $errors[] = 'Check-in cannot be in the past.';
        }

        if ($checkOutDate <= $checkInDate) {
            $errors[] = 'Check-out must be after check-in.';
        }

        // Protect the demonstration from excessively long date ranges.
        if ($checkOutDate > $checkInDate &&
            $checkInDate->diff($checkOutDate)->days > 90) {
            $errors[] = 'Demo bookings can be up to 90 nights.';
        }
    }

    if ($guestValue === false || $guestValue === null ||
        $guestValue < 1 || $guestValue > 6) {
        $errors[] = 'Please select a valid number of guests.';
    } elseif ($roomId && isset($rooms[$roomId]) &&
        $guestValue > $rooms[$roomId]['guests']) {
        $errors[] = 'The selected room cannot accommodate that many guests.';
    }

    if ($fullName === '' || mb_strlen($fullName) < 2 ||
        mb_strlen($fullName) > 100) {
        $errors[] = 'Enter your name using 2 to 100 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($email) > 254) {
        $errors[] = 'Enter a valid email address.';
    }

    if (strlen($phone) > 30) {
        $errors[] = 'Your phone number is too long.';
    }

    if ($country === '' || mb_strlen($country) > 80) {
        $errors[] = 'Please enter your country.';
    }

    if (mb_strlen($specialRequests) > 1000) {
        $errors[] = 'Special requests must be under 1,000 characters.';
    }

    /*
     * Successful demo submission.
     * This generates a simulated reference and stores the booking
     * in the current PHP session only.
     */

    if (empty($errors)) {
        $room = $rooms[$roomId];

        $nights = (int)$checkInDate->diff($checkOutDate)->days;
        $roomTotal = $nights * $room['price'];

        $reference = 'ABR-' . strtoupper(bin2hex(random_bytes(3)));

        $booking = [
            'reference' => $reference,
            'room_id' => $roomId,
            'room_name' => $room['name'],
            'room_type' => $room['type'],
            'room_image' => $room['image'],
            'room_price' => $room['price'],
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'country' => $country,
            'check_in' => $checkInDate->format('Y-m-d'),
            'check_out' => $checkOutDate->format('Y-m-d'),
            'guests' => $guestValue,
            'nights' => $nights,
            'total' => $roomTotal,
            'special_requests' => $specialRequests,
            'status' => 'Demo Booking Received',
            'created_at' => date('Y-m-d H:i:s')
        ];

        if (!isset($_SESSION['azurebay_bookings'])) {
            $_SESSION['azurebay_bookings'] = [];
        }

        $_SESSION['azurebay_bookings'][] = $booking;

        $_SESSION['azurebay_last_booking'] = $booking;

        /*
         * Redirect after submission to avoid duplicate form submissions.
         */
        header('Location: booking.php?confirmation=1');
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Display booking confirmation after redirect
|--------------------------------------------------------------------------
*/

if (isset($_GET['confirmation']) && $_GET['confirmation'] === '1') {
    $booking = $_SESSION['azurebay_last_booking'] ?? null;

    if ($booking) {
        $submitted = true;
    }
}

/*
|--------------------------------------------------------------------------
| Load room and booking details for the form
|--------------------------------------------------------------------------
*/

if (!$submitted) {

    $roomId = filter_input(
        INPUT_POST,
        'room_id',
        FILTER_VALIDATE_INT
    );

    if (!$roomId) {
        $roomId = filter_input(
            INPUT_GET,
            'room_id',
            FILTER_VALIDATE_INT
        );
    }

    if (!$roomId || !isset($rooms[$roomId])) {
        $roomId = null;
    }

    $room = $roomId ? $rooms[$roomId] : null;

    $checkInValue = $_POST['check_in'] ?? $_GET['check_in'] ?? $today->format('Y-m-d');
    $checkOutValue = $_POST['check_out'] ?? $_GET['check_out'] ?? $today->modify('+1 day')->format('Y-m-d');

    if (!validDate((string)$checkInValue) ||
        new DateTimeImmutable($checkInValue) < $today) {
        $checkInValue = $today->format('Y-m-d');
    }

    if (!validDate((string)$checkOutValue) ||
        new DateTimeImmutable($checkOutValue) <= new DateTimeImmutable($checkInValue)) {
        $checkOutValue = (new DateTimeImmutable($checkInValue))
            ->modify('+1 day')
            ->format('Y-m-d');
    }

    $guestValue = filter_input(INPUT_GET, 'guests', FILTER_VALIDATE_INT);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $guestValue = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT);
    }

    if ($guestValue === false || $guestValue === null ||
        $guestValue < 1 || $guestValue > 6) {
        $guestValue = 2;
    }

    if ($room && $guestValue > $room['guests']) {
        $guestValue = $room['guests'];
    }

    $nights = (int)(new DateTimeImmutable($checkInValue))
        ->diff(new DateTimeImmutable($checkOutValue))->days;

    $roomTotal = $room ? $nights * $room['price'] : 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $submitted ? 'Booking Confirmation' : 'Complete Your Booking' ?>
        | Azure Bay Resort
    </title>

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

        .navbar .nav-link:hover {
            color: var(--gold-light);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--ocean-dark);
            border: 1px solid var(--gold);
            border-radius: 4px;
            padding: 12px 20px;
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
            padding: 11px 18px;
        }

        .btn-outline-ocean:hover {
            background: var(--ocean);
            color: white;
        }

        .page-banner {
            background:
                linear-gradient(90deg, rgba(9,43,53,.96), rgba(18,62,74,.78)),
                url('assets/images/resort-hero.jpg') center/cover;
            padding: 55px 0;
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
            font-size: clamp(30px, 4vw, 43px);
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

        .booking-card,
        .summary-card,
        .confirmation-card {
            background: white;
            border: 1px solid #ece5d8;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(16, 50, 58, .05);
            margin-bottom: 25px;
        }

        .card-heading {
            padding: 23px 25px;
            border-bottom: 1px solid #eee8dd;
        }

        .card-heading h2 {
            font-size: 21px;
            font-weight: 800;
            color: var(--ocean-dark);
            margin: 0 0 6px;
        }

        .card-heading p {
            font-size: 13px;
            color: var(--muted);
            margin: 0;
        }

        .card-body-custom {
            padding: 25px;
        }

        .section-label {
            font-size: 11px;
            color: #997a3e;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 800;
            margin-bottom: 17px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #52656a;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            border-color: #e2e5e1;
            border-radius: 4px;
            padding: 12px;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 .2rem rgba(200, 169, 107, .15);
        }

        .form-text {
            font-size: 11px;
            color: var(--muted);
        }

        .room-image {
            width: 100%;
            height: 205px;
            object-fit: cover;
            background: #e7e4dc;
        }

        .summary-content {
            padding: 22px;
        }

        .room-name {
            font-size: 19px;
            font-weight: 800;
            color: var(--ocean-dark);
            margin-bottom: 7px;
        }

        .room-description {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .room-type {
            display: inline-block;
            padding: 6px 10px;
            background: #f5eedf;
            color: #87692f;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .6px;
            border-radius: 3px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 13px;
            margin: 15px 0;
            color: var(--muted);
        }

        .summary-row strong {
            color: var(--ocean-dark);
            text-align: right;
        }

        .summary-total {
            background: var(--ocean);
            color: white;
            padding: 22px;
        }

        .summary-total span {
            color: #dce5e4;
            font-size: 12px;
        }

        .summary-total strong {
            display: block;
            color: var(--gold-light);
            font-size: 30px;
            margin-top: 5px;
        }

        .demo-alert {
            background: #f8f2e4;
            border: 1px solid #ebdec1;
            color: #76663f;
            border-radius: 5px;
            padding: 15px;
            font-size: 12px;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .error-alert {
            background: #fff0ee;
            border: 1px solid #f0c9c4;
            color: #8b3027;
            border-radius: 5px;
            padding: 16px 20px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .confirmation-card {
            max-width: 800px;
            margin: 55px auto;
        }

        .confirmation-top {
            background: var(--ocean-dark);
            color: white;
            text-align: center;
            padding: 40px 25px;
        }

        .success-icon {
            width: 66px;
            height: 66px;
            border-radius: 50%;
            background: var(--gold);
            color: var(--ocean-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 800;
            margin: 0 auto 20px;
        }

        .confirmation-top h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .confirmation-top p {
            color: #d5e0df;
            font-size: 14px;
            margin-bottom: 0;
        }

        .reference-box {
            background: #f7f3e9;
            border: 1px dashed var(--gold);
            border-radius: 5px;
            padding: 16px;
            text-align: center;
            margin-bottom: 25px;
        }

        .reference-box small {
            display: block;
            color: var(--muted);
            font-size: 11px;
            margin-bottom: 5px;
        }

        .reference-box strong {
            color: var(--ocean-dark);
            font-size: 23px;
            letter-spacing: 1px;
        }

        .confirmation-note {
            color: #76663f;
            background: #f8f2e4;
            border-radius: 5px;
            padding: 14px;
            font-size: 12px;
            line-height: 1.8;
            margin-top: 20px;
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

            .card-body-custom {
                padding: 20px;
            }

            .confirmation-top {
                padding: 32px 20px;
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
                    <a class="btn btn-gold btn-sm" href="my_bookings.php">
                        My Bookings
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php if ($submitted && $booking): ?>

    <main class="container">
        <div class="confirmation-card">
            <div class="confirmation-top">
                <div class="success-icon">✓</div>
                <div class="eyebrow">Thank you for choosing Azure Bay</div>
                <h1 class="mt-2">Demo Booking Received!</h1>
                <p>
                    Thank you, <?= e($booking['full_name']) ?>.
                    Your sample booking details have been recorded for this
                    demonstration session.
                </p>
            </div>

            <div class="card-body-custom">
                <div class="reference-box">
                    <small>YOUR DEMO BOOKING REFERENCE</small>
                    <strong><?= e($booking['reference']) ?></strong>
                </div>

                <div class="row g-4">
                    <div class="col-md-5">
                        <img
                            src="<?= e($booking['room_image']) ?>"
                            alt="<?= e($booking['room_name']) ?>"
                            class="room-image rounded"
                            onerror="this.style.display='none';"
                        >
                    </div>

                    <div class="col-md-7">
                        <span class="room-type">
                            <?= e($booking['room_type']) ?>
                        </span>

                        <h2 class="room-name">
                            <?= e($booking['room_name']) ?>
                        </h2>

                        <p class="room-description">
                            Booking details for your selected Azure Bay room.
                        </p>

                        <div class="summary-row">
                            <span>Guest name</span>
                            <strong><?= e($booking['full_name']) ?></strong>
                        </div>

                        <div class="summary-row">
                            <span>Check-in</span>
                            <strong>
                                <?= e(date('d M Y', strtotime($booking['check_in']))) ?>
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Check-out</span>
                            <strong>
                                <?= e(date('d M Y', strtotime($booking['check_out']))) ?>
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Duration</span>
                            <strong>
                                <?= (int)$booking['nights'] ?>
                                <?= $booking['nights'] === 1 ? 'night' : 'nights' ?>
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Guests</span>
                            <strong><?= (int)$booking['guests'] ?></strong>
                        </div>

                        <div class="summary-row">
                            <span>Email</span>
                            <strong><?= e($booking['email']) ?></strong>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="summary-row">
                    <span>Room rate per night</span>
                    <strong>$<?= number_format($booking['room_price']) ?></strong>
                </div>

                <div class="summary-row">
                    <span>Number of nights</span>
                    <strong><?= (int)$booking['nights'] ?></strong>
                </div>

                <div class="summary-row fs-5">
                    <span class="text-dark fw-bold">Estimated room total</span>
                    <strong>
                        $<?= number_format($booking['total']) ?>
                    </strong>
                </div>

                <div class="confirmation-note">
                    <strong>Demonstration only:</strong>
                    This is not a real reservation. No room has been reserved,
                    no payment has been collected, and no confirmation email
                    has been sent. The booking reference is for this demo only.
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="rooms.php" class="btn btn-gold">
                        Explore More Rooms
                    </a>
                    <a href="my_bookings.php" class="btn btn-outline-ocean">
                        My Bookings
                    </a>
                    <a href="index.php" class="btn btn-outline-ocean">
                        Return Home
                    </a>
                </div>
            </div>
        </div>
    </main>

<?php elseif (!$room): ?>

    <main class="container py-5 text-center">
        <div class="eyebrow">Azure Bay Resort</div>
        <h1 class="mt-3 fw-bold" style="color:#092b35">
            Please Select a Room
        </h1>
        <p class="text-secondary mt-3">
            Choose a room before continuing to the booking page.
        </p>
        <a href="rooms.php" class="btn btn-gold mt-3">
            Browse Available Rooms
        </a>
    </main>

<?php else: ?>

<section class="page-banner">
    <div class="container">
        <div class="eyebrow">Your coastal escape begins here</div>
        <h1>Complete Your Booking</h1>
        <p>
            Review your stay, provide your guest details and prepare
            for a relaxing Azure Bay experience.
        </p>
    </div>
</section>

<main class="container pb-4">

    <div class="breadcrumb-area">
        <a href="index.php">Home</a>
        <span>/</span>
        <a href="rooms.php">Our Rooms</a>
        <span>/</span>
        <a href="room_details.php?id=<?= (int)$roomId ?>">
            Room Details
        </a>
        <span>/</span>
        Booking
    </div>

    <?php if ($errors): ?>
        <div class="error-alert" role="alert">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="demo-alert">
        <strong>Demo booking:</strong>
        This is a portfolio demonstration. The form simulates a booking
        request and does not verify live room availability or collect payment.
    </div>

    <div class="row g-4">

        <!-- Booking form -->
        <div class="col-lg-7">
            <div class="booking-card">

                <div class="card-heading">
                    <h2>Guest Information</h2>
                    <p>Enter the details for your sample reservation.</p>
                </div>

                <div class="card-body-custom">
                    <form action="booking.php" method="POST" id="bookingForm">

                        <input type="hidden" name="room_id"
                               value="<?= (int)$roomId ?>">

                        <div class="section-label">
                            01 — Your Details
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="full_name" class="form-label">
                                    Full Name *
                                </label>
                                <input type="text" id="full_name"
                                       name="full_name" class="form-control"
                                       placeholder="Enter your full name"
                                       maxlength="100" autocomplete="name"
                                       value="<?= e($_POST['full_name'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email Address *
                                </label>
                                <input type="email" id="email" name="email"
                                       class="form-control"
                                       placeholder="you@example.com"
                                       maxlength="254" autocomplete="email"
                                       value="<?= e($_POST['email'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    Phone Number
                                </label>
                                <input type="tel" id="phone" name="phone"
                                       class="form-control"
                                       placeholder="+94 77 123 4567"
                                       maxlength="30" autocomplete="tel"
                                       value="<?= e($_POST['phone'] ?? '') ?>">
                            </div>

                            <div class="col-12">
                                <label for="country" class="form-label">
                                    Country of Residence *
                                </label>
                                <input type="text" id="country" name="country"
                                       class="form-control"
                                       placeholder="Enter your country"
                                       maxlength="80" autocomplete="country-name"
                                       value="<?= e($_POST['country'] ?? '') ?>"
                                       required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="section-label">
                            02 — Your Stay
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="check_in" class="form-label">
                                    Check-in Date *
                                </label>
                                <input type="date" id="check_in" name="check_in"
                                       class="form-control"
                                       min="<?= e($today->format('Y-m-d')) ?>"
                                       value="<?= e($checkInValue) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="check_out" class="form-label">
                                    Check-out Date *
                                </label>
                                <input type="date" id="check_out" name="check_out"
                                       class="form-control"
                                       min="<?= e((new DateTimeImmutable($checkInValue))->modify('+1 day')->format('Y-m-d')) ?>"
                                       value="<?= e($checkOutValue) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="guests" class="form-label">
                                    Number of Guests *
                                </label>
                                <select id="guests" name="guests"
                                        class="form-select" required>
                                    <?php for ($i = 1; $i <= $room['guests']; $i++): ?>
                                        <option value="<?= $i ?>"
                                            <?= (int)$guestValue === $i ? 'selected' : '' ?>>
                                            <?= $i ?> <?= $i === 1 ? 'Guest' : 'Guests' ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <div class="form-text">
                                    Maximum capacity: <?= (int)$room['guests'] ?> guests.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="special_requests" class="form-label">
                                    Special Requests
                                </label>
                                <textarea id="special_requests"
                                          name="special_requests"
                                          class="form-control" rows="2"
                                          maxlength="1000"
                                          placeholder="Any preferences?"><?= e($_POST['special_requests'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox"
                                   id="demo_acknowledgement" required>
                            <label class="form-check-label small"
                                   for="demo_acknowledgement">
                                I understand that this is a demonstration booking
                                and not a real reservation.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-gold w-100">
                            Submit Demo Booking →
                        </button>

                        <a href="room_details.php?id=<?= (int)$roomId ?>&check_in=<?= e($checkInValue) ?>&check_out=<?= e($checkOutValue) ?>&guests=<?= (int)$guestValue ?>"
                           class="btn btn-outline-ocean w-100 mt-2">
                            ← Return to Room Details
                        </a>

                    </form>
                </div>
            </div>
        </div>

        <!-- Booking summary -->
        <div class="col-lg-5">
            <aside class="summary-card">

                <img src="<?= e($room['image']) ?>"
                     alt="<?= e($room['name']) ?>"
                     class="room-image"
                     onerror="this.style.display='none';">

                <div class="summary-content">
                    <span class="room-type"><?= e($room['type']) ?></span>

                    <h2 class="room-name"><?= e($room['name']) ?></h2>

                    <p class="room-description">
                        <?= e($room['description']) ?>
                    </p>

                    <div class="summary-row">
                        <span>Room size</span>
                        <strong><?= e($room['size']) ?></strong>
                    </div>

                    <div class="summary-row">
                        <span>Bed arrangement</span>
                        <strong><?= e($room['bed']) ?></strong>
                    </div>

                    <div class="summary-row">
                        <span>Maximum guests</span>
                        <strong><?= (int)$room['guests'] ?></strong>
                    </div>

                    <hr>

                    <div class="summary-row">
                        <span>Rate per night</span>
                        <strong>$<?= number_format($room['price']) ?></strong>
                    </div>

                    <div class="summary-row">
                        <span>Number of nights</span>
                        <strong id="nightCount"><?= (int)$nights ?></strong>
                    </div>

                    <div class="summary-row">
                        <span>Guests selected</span>
                        <strong id="guestCount"><?= (int)$guestValue ?></strong>
                    </div>
                </div>

                <div class="summary-total">
                    <span>Estimated Room Total</span>
                    <strong id="roomTotal">$<?= number_format($roomTotal) ?></strong>
                    <span class="d-block mt-2">
                        Sample rate only. Taxes and additional charges
                        are not included.
                    </span>
                </div>
            </aside>

            <div class="demo-alert">
                <strong>What happens next?</strong><br>
                Submitting this form creates a sample confirmation and
                reference number for your current PHP session. No payment
                is required.
            </div>
        </div>

    </div>
</main>

<?php endif; ?>

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
                <span>Experience the booking workflow without payment.</span>
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
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const guestsInput = document.getElementById('guests');

    const ratePerNight = <?= $room ? (int)$room['price'] : 0 ?>;

    function updateBookingSummary() {
        if (!checkInInput || !checkOutInput) return;

        const checkIn = new Date(checkInInput.value + 'T00:00:00');
        const checkOut = new Date(checkOutInput.value + 'T00:00:00');

        if (Number.isNaN(checkIn.getTime()) ||
            Number.isNaN(checkOut.getTime())) {
            return;
        }

        const dayMs = 1000 * 60 * 60 * 24;
        const nights = Math.round(
            (checkOut.getTime() - checkIn.getTime()) / dayMs
        );

        const validNights = nights > 0;

        const nightCount = document.getElementById('nightCount');
        const roomTotal = document.getElementById('roomTotal');

        if (nightCount) {
            nightCount.textContent = validNights ? nights : '—';
        }

        if (roomTotal) {
            roomTotal.textContent = validNights
                ? '$' + (ratePerNight * nights).toLocaleString('en-US')
                : '—';
        }

        const guestCount = document.getElementById('guestCount');

        if (guestCount && guestsInput) {
            guestCount.textContent = guestsInput.value;
        }
    }

    if (checkInInput && checkOutInput) {
        checkInInput.addEventListener('change', function () {
            if (!checkInInput.value) return;

            const nextDay = new Date(checkInInput.value + 'T00:00:00');
            nextDay.setDate(nextDay.getDate() + 1);

            const minDate =
                nextDay.getFullYear() + '-' +
                String(nextDay.getMonth() + 1).padStart(2, '0') + '-' +
                String(nextDay.getDate()).padStart(2, '0');

            checkOutInput.min = minDate;

            if (!checkOutInput.value || checkOutInput.value < minDate) {
                checkOutInput.value = minDate;
            }

            updateBookingSummary();
        });

        checkOutInput.addEventListener('change', updateBookingSummary);
    }

    guestsInput?.addEventListener('change', updateBookingSummary);

    document.getElementById('bookingForm')?.addEventListener('submit', function (event) {
        if (checkOutInput.value <= checkInInput.value) {
            event.preventDefault();
            alert('Check-out must be after check-in.');
            checkOutInput.focus();
        }
    });

    updateBookingSummary();
</script>

</body>
</html>