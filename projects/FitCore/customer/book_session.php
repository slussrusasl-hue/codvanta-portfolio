
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore - Book a Fitness Session
|--------------------------------------------------------------------------
| Demonstration only:
| - Uses sample fitness classes.
| - Stores bookings in PHP sessions, not a database.
| - Does not process real payments.
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| Sample class data
|--------------------------------------------------------------------------
| These IDs are used by links such as:
| book_session.php?class_id=1
*/

$classes = [
    1 => [
        'name' => 'Strength Training',
        'category' => 'Strength',
        'trainer' => 'Alex Morgan',
        'day' => 'Monday',
        'start_time' => '08:00',
        'end_time' => '09:00',
        'level' => 'Beginner',
        'duration' => 60,
        'price' => 10,
        'description' => 'Build strength and improve your fitness with guided resistance exercises.',
        'image' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=1000&q=80'
    ],
    2 => [
        'name' => 'Morning Yoga',
        'category' => 'Yoga',
        'trainer' => 'Sarah Williams',
        'day' => 'Tuesday',
        'start_time' => '09:00',
        'end_time' => '10:00',
        'level' => 'All Levels',
        'duration' => 60,
        'price' => 8,
        'description' => 'Improve flexibility, balance and relaxation through guided yoga.',
        'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=1000&q=80'
    ],
    3 => [
        'name' => 'HIIT Workout',
        'category' => 'Cardio',
        'trainer' => 'Daniel Carter',
        'day' => 'Wednesday',
        'start_time' => '05:00',
        'end_time' => '06:00',
        'level' => 'Intermediate',
        'duration' => 60,
        'price' => 12,
        'description' => 'Challenge yourself with high-intensity intervals and energetic exercises.',
        'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1000&q=80'
    ],
    4 => [
        'name' => 'Cardio Blast',
        'category' => 'Cardio',
        'trainer' => 'Daniel Carter',
        'day' => 'Thursday',
        'start_time' => '07:00',
        'end_time' => '08:00',
        'level' => 'Beginner',
        'duration' => 60,
        'price' => 10,
        'description' => 'An energetic workout designed to improve cardiovascular fitness.',
        'image' => 'https://images.unsplash.com/photo-1534258936925-c58bed479fcb?auto=format&fit=crop&w=1000&q=80'
    ],
    5 => [
        'name' => 'Muscle Building',
        'category' => 'Strength',
        'trainer' => 'Alex Morgan',
        'day' => 'Friday',
        'start_time' => '10:00',
        'end_time' => '11:00',
        'level' => 'Intermediate',
        'duration' => 60,
        'price' => 15,
        'description' => 'Focus on progressive resistance training and proper exercise technique.',
        'image' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=1000&q=80'
    ],
    6 => [
        'name' => 'Functional Fitness',
        'category' => 'Fitness',
        'trainer' => 'Daniel Carter',
        'day' => 'Saturday',
        'start_time' => '08:00',
        'end_time' => '09:00',
        'level' => 'All Levels',
        'duration' => 60,
        'price' => 12,
        'description' => 'Develop balance, coordination and strength for everyday activities.',
        'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1000&q=80'
    ],
    7 => [
        'name' => 'Stretch & Recover',
        'category' => 'Recovery',
        'trainer' => 'Sarah Williams',
        'day' => 'Sunday',
        'start_time' => '09:00',
        'end_time' => '10:00',
        'level' => 'All Levels',
        'duration' => 60,
        'price' => 8,
        'description' => 'A gentle session focused on mobility, stretching and recovery.',
        'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=1000&q=80'
    ],
    8 => [
        'name' => 'Personal Training',
        'category' => 'Personal Training',
        'trainer' => 'Alex Morgan',
        'day' => 'Monday',
        'start_time' => '14:00',
        'end_time' => '15:00',
        'level' => 'All Levels',
        'duration' => 60,
        'price' => 25,
        'description' => 'Receive individual guidance tailored to your personal fitness goals.',
        'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=1000&q=80'
    ]
];

/*
|--------------------------------------------------------------------------
| Helper functions
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function formatTime($time)
{
    return date('h:i A', strtotime($time));
}

function nextClassDate($dayName)
{
    $today = new DateTimeImmutable('today');
    $target = new DateTimeImmutable('next ' . $dayName);

    if ($today->format('l') === $dayName) {
        $target = $today;
    }

    return $target->format('Y-m-d');
}

/*
|--------------------------------------------------------------------------
| Check member session
|--------------------------------------------------------------------------
*/

$member = $_SESSION['fitcore_demo_member'] ?? null;

if (!is_array($member) || empty($member['email'])) {
    header('Location: register.php?message=register_first');
    exit;
}

/*
|--------------------------------------------------------------------------
| Select class
|--------------------------------------------------------------------------
*/

$classId = filter_input(INPUT_GET, 'class_id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classId = filter_var(
        $_POST['class_id'] ?? null,
        FILTER_VALIDATE_INT
    );
}

if (!$classId || !isset($classes[$classId])) {
    $classId = 1;
}

$selectedClass = $classes[$classId];

/*
|--------------------------------------------------------------------------
| Booking form processing
|--------------------------------------------------------------------------
*/

$errors = [];
$success = false;
$bookingReference = '';

$selectedDate = $_POST['session_date'] ?? nextClassDate($selectedClass['day']);
$selectedTime = $_POST['session_time'] ?? $selectedClass['start_time'];
$bookingNote = trim($_POST['booking_note'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $submittedClassId = filter_var(
        $_POST['class_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$submittedClassId || !isset($classes[$submittedClassId])) {
        $errors[] = 'Please select a valid fitness class.';
    } else {
        $classId = $submittedClassId;
        $selectedClass = $classes[$classId];
    }

    $selectedDate = trim($_POST['session_date'] ?? '');
    $selectedTime = trim($_POST['session_time'] ?? '');
    $bookingNote = trim($_POST['booking_note'] ?? '');

    if ($selectedDate === '') {
        $errors[] = 'Please select a session date.';
    } else {
        $dateObject = DateTimeImmutable::createFromFormat('!Y-m-d', $selectedDate);
        $dateErrors = DateTimeImmutable::getLastErrors();

        if (
            !$dateObject ||
            ($dateErrors !== false &&
                ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) ||
            $dateObject->format('Y-m-d') !== $selectedDate
        ) {
            $errors[] = 'Please enter a valid session date.';
        } else {
            $today = new DateTimeImmutable('today');

            if ($dateObject < $today) {
                $errors[] = 'You cannot book a session in the past.';
            } elseif ($dateObject > $today->modify('+30 days')) {
                $errors[] = 'Bookings are available for the next 30 days only.';
            } elseif ($dateObject->format('l') !== $selectedClass['day']) {
                $errors[] = 'Please select a ' . $selectedClass['day'] . ' for this class.';
            }
        }
    }

    if ($selectedTime !== $selectedClass['start_time']) {
        $errors[] = 'Please select the scheduled time for this class.';
    }

    if (strlen($bookingNote) > 300) {
        $errors[] = 'Your booking note must not exceed 300 characters.';
    }

    /*
    |--------------------------------------------------------------------------
    | Save demonstration booking
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        if (!isset($_SESSION['fitcore_demo_bookings'])) {
            $_SESSION['fitcore_demo_bookings'] = [];
        }

        $existingBookings = $_SESSION['fitcore_demo_bookings'];
        $memberEmail = strtolower(trim($member['email']));
        $duplicate = false;

        foreach ($existingBookings as $booking) {
            if (
                strtolower($booking['member_email']) === $memberEmail &&
                $booking['class_id'] == $classId &&
                $booking['session_date'] === $selectedDate &&
                $booking['status'] !== 'Cancelled'
            ) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {
            $errors[] = 'You already have a booking for this class on the selected date.';
        } else {

            $bookingReference = 'FC-' .
                date('ymd') . '-' .
                strtoupper(bin2hex(random_bytes(3)));

            $newBooking = [
                'reference' => $bookingReference,
                'class_id' => $classId,
                'class_name' => $selectedClass['name'],
                'category' => $selectedClass['category'],
                'trainer' => $selectedClass['trainer'],
                'session_date' => $selectedDate,
                'session_day' => $selectedClass['day'],
                'start_time' => $selectedClass['start_time'],
                'end_time' => $selectedClass['end_time'],
                'price' => $selectedClass['price'],
                'booking_note' => $bookingNote,
                'member_name' => $member['full_name'] ?? 'FitCore Member',
                'member_email' => $memberEmail,
                'status' => 'Confirmed',
                'payment_status' => 'Not Paid',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $_SESSION['fitcore_demo_bookings'][] = $newBooking;

            /*
             * Redirect to avoid duplicate submissions when refreshing.
             */
            header('Location: my_bookings.php?booked=' . urlencode($bookingReference));
            exit;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Date restrictions for the form
|--------------------------------------------------------------------------
*/

$today = date('Y-m-d');
$maxDate = date('Y-m-d', strtotime('+30 days'));
$firstClassDate = nextClassDate($selectedClass['day']);

if (
    $selectedDate < $today ||
    $selectedDate > $maxDate ||
    date('l', strtotime($selectedDate)) !== $selectedClass['day']
) {
    $selectedDate = $firstClassDate;
}

$memberName = $member['full_name'] ?? 'FitCore Member';
$memberEmail = $member['email'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book a Session | FitCore</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --fc-bg: #101010;
            --fc-card: #191919;
            --fc-card-light: #202020;
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
            font-weight: 800;
            font-size: 1.65rem;
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
            color: #111;
            border: 1px solid var(--fc-orange);
            border-radius: 8px;
            padding: 11px 22px;
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
            color: var(--fc-orange);
            border: 1px solid var(--fc-orange);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-outline-orange:hover {
            background: var(--fc-orange);
            color: #111;
        }

        .page-hero {
            position: relative;
            padding: 70px 0 55px;
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
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            font-weight: 900;
            margin-top: 12px;
        }

        .page-hero p {
            color: #c6c6c6;
            max-width: 650px;
            line-height: 1.8;
        }

        .section-space {
            padding: 55px 0 75px;
        }

        .panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 16px;
            overflow: hidden;
        }

        .panel-heading {
            padding: 22px 25px;
            border-bottom: 1px solid var(--fc-border);
        }

        .panel-body {
            padding: 25px;
        }

        .section-title {
            font-weight: 800;
            margin-bottom: 7px;
        }

        .muted {
            color: var(--fc-muted);
        }

        .class-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }

        .class-image-wrap {
            position: relative;
        }

        .category-tag {
            position: absolute;
            left: 18px;
            bottom: 18px;
            background: var(--fc-orange);
            color: #111;
            font-size: .78rem;
            font-weight: 800;
            padding: 7px 12px;
            border-radius: 6px;
        }

        .class-details {
            padding: 23px;
        }

        .class-name {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .trainer-line {
            color: var(--fc-muted);
            margin-top: 10px;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid #303030;
        }

        .info-row:last-child {
            border-bottom: 0;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .form-label {
            color: #eaeaea;
            font-weight: 700;
            margin-bottom: 9px;
        }

        .form-control,
        .form-select {
            background-color: #111;
            color: #f5f5f5;
            border: 1px solid #414141;
            border-radius: 8px;
            padding: 12px 13px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #111;
            color: #fff;
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 .2rem rgba(255, 107, 44, .14);
        }

        .form-control::placeholder {
            color: #777;
        }

        .form-select option {
            background: #171717;
            color: #fff;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .price-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            background: #111;
            border: 1px solid #383838;
            padding: 18px;
            border-radius: 10px;
            margin-top: 22px;
        }

        .price-amount {
            font-size: 1.9rem;
            color: var(--fc-orange);
            font-weight: 900;
        }

        .demo-notice {
            border: 1px solid #66502b;
            background: rgba(255, 193, 7, .07);
            color: #e8d5a5;
            padding: 15px;
            border-radius: 10px;
            font-size: .9rem;
            line-height: 1.6;
        }

        .alert-danger {
            background: #321b1b;
            border-color: #683131;
            color: #ffcaca;
        }

        .alert-success {
            background: #173021;
            border-color: #285b3c;
            color: #c4f4d3;
        }

        .feature-item {
            color: #d5d5d5;
            padding: 7px 0;
        }

        .feature-item span {
            color: var(--fc-orange);
            margin-right: 9px;
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
                padding: 50px 0 40px;
            }

            .section-space {
                padding: 35px 0 50px;
            }

            .panel-body {
                padding: 19px;
            }

            .class-image {
                height: 210px;
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
                    <a class="nav-link" href="my_bookings.php">My Bookings</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="payment_history.php">Payments</a>
                </li>

                <li class="nav-item">
                    <a class="btn btn-orange ms-lg-2 mt-2 mt-lg-0" href="index.php">
                        Member Home
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="page-hero">
    <div class="container">

        <div class="eyebrow">Your next workout starts here</div>

        <h1>Book Your <span style="color:var(--fc-orange);">Session</span></h1>

        <p class="mb-0">
            Choose your preferred date, confirm your scheduled fitness class
            and take another step towards your fitness goals.
        </p>

    </div>
</section>

<!-- Booking content -->
<section class="section-space">
    <div class="container">

        <div class="row g-4">

            <!-- Left: Class details -->
            <div class="col-lg-5">

                <div class="panel mb-4">

                    <div class="class-image-wrap">
                        <img
                            src="<?= e($selectedClass['image']) ?>"
                            alt="<?= e($selectedClass['name']) ?>"
                            class="class-image"
                        >

                        <span class="category-tag">
                            <?= e($selectedClass['category']) ?>
                        </span>
                    </div>

                    <div class="class-details">

                        <div class="eyebrow">Selected fitness class</div>

                        <h2 class="class-name mt-2">
                            <?= e($selectedClass['name']) ?>
                        </h2>

                        <p class="muted mt-2">
                            <?= e($selectedClass['description']) ?>
                        </p>

                        <div class="info-row">
                            <div class="info-icon">🏋️</div>
                            <div>
                                <div class="fw-bold">Trainer</div>
                                <div class="muted">
                                    <?= e($selectedClass['trainer']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">📅</div>
                            <div>
                                <div class="fw-bold">Scheduled Day</div>
                                <div class="muted">
                                    <?= e($selectedClass['day']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">🕒</div>
                            <div>
                                <div class="fw-bold">Session Time</div>
                                <div class="muted">
                                    <?= e(formatTime($selectedClass['start_time'])) ?>
                                    –
                                    <?= e(formatTime($selectedClass['end_time'])) ?>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">⏱️</div>
                            <div>
                                <div class="fw-bold">Duration</div>
                                <div class="muted">
                                    <?= e($selectedClass['duration']) ?> minutes
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">💪</div>
                            <div>
                                <div class="fw-bold">Fitness Level</div>
                                <div class="muted">
                                    <?= e($selectedClass['level']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="price-box">
                            <div>
                                <div class="muted small">Sample session fee</div>
                                <div class="fw-bold">Per session</div>
                            </div>

                            <div class="price-amount">
                                $<?= number_format($selectedClass['price'], 2) ?>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="demo-notice">
                    <strong>Demo notice:</strong>
                    Class details and prices are sample data. This page does
                    not process payments or save bookings to a database.
                    A successful booking is stored in your current PHP session.
                </div>

            </div>

            <!-- Right: Booking form -->
            <div class="col-lg-7">

                <div class="panel">

                    <div class="panel-heading">
                        <div class="eyebrow">Booking details</div>
                        <h2 class="section-title mt-2">Confirm Your Session</h2>
                        <p class="muted mb-0">
                            Review your information and choose a date for your class.
                        </p>
                    </div>

                    <div class="panel-body">

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger mb-4">
                                <strong>Please check the following:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= e($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                Your booking has been completed.
                                Reference: <?= e($bookingReference) ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <div class="muted small mb-1">BOOKING FOR</div>
                            <h5 class="fw-bold mb-1"><?= e($memberName) ?></h5>
                            <div class="muted"><?= e($memberEmail) ?></div>
                        </div>

                        <hr style="border-color:#383838;">

                        <form method="POST" action="book_session.php" id="bookingForm">

                            <input
                                type="hidden"
                                name="class_id"
                                value="<?= e($classId) ?>"
                            >

                            <div class="mb-4">
                                <label for="session_date" class="form-label">
                                    Select Session Date
                                </label>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="session_date"
                                    name="session_date"
                                    min="<?= e($today) ?>"
                                    max="<?= e($maxDate) ?>"
                                    value="<?= e($selectedDate) ?>"
                                    required
                                >

                                <small class="muted d-block mt-2">
                                    This class runs on <?= e($selectedClass['day']) ?>.
                                    Select a <?= e($selectedClass['day']) ?> within the next 30 days.
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="session_time" class="form-label">
                                    Session Time
                                </label>

                                <select
                                    class="form-select"
                                    id="session_time"
                                    name="session_time"
                                    required
                                >
                                    <option value="<?= e($selectedClass['start_time']) ?>">
                                        <?= e(formatTime($selectedClass['start_time'])) ?>
                                        –
                                        <?= e(formatTime($selectedClass['end_time'])) ?>
                                    </option>
                                </select>

                                <small class="muted d-block mt-2">
                                    The time is based on the selected class schedule.
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="booking_note" class="form-label">
                                    Note for Trainer
                                    <span class="muted fw-normal">(Optional)</span>
                                </label>

                                <textarea
                                    class="form-control"
                                    id="booking_note"
                                    name="booking_note"
                                    rows="4"
                                    maxlength="300"
                                    placeholder="Tell your trainer about your goals or anything they should know..."
                                ><?= e($bookingNote) ?></textarea>

                                <div class="text-end muted small mt-1">
                                    <span id="characterCount"><?= strlen($bookingNote) ?></span>/300 characters
                                </div>
                            </div>

                            <div class="panel p-3 mb-4" style="background:#111;">
                                <h6 class="fw-bold mb-3">Booking Summary</h6>

                                <div class="d-flex justify-content-between gap-3 mb-2">
                                    <span class="muted">Fitness class</span>
                                    <span class="text-end fw-bold">
                                        <?= e($selectedClass['name']) ?>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between gap-3 mb-2">
                                    <span class="muted">Trainer</span>
                                    <span class="text-end">
                                        <?= e($selectedClass['trainer']) ?>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between gap-3 mb-2">
                                    <span class="muted">Day</span>
                                    <span><?= e($selectedClass['day']) ?></span>
                                </div>

                                <hr style="border-color:#383838;">

                                <div class="d-flex justify-content-between gap-3">
                                    <span class="fw-bold">Sample session fee</span>
                                    <span class="fw-bold" style="color:var(--fc-orange);">
                                        $<?= number_format($selectedClass['price'], 2) ?>
                                    </span>
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="btn btn-orange w-100"
                                id="confirmButton"
                            >
                                Confirm Booking
                            </button>

                            <a
                                href="classes.php"
                                class="btn btn-outline-orange w-100 mt-3"
                            >
                                Back to Classes
                            </a>

                        </form>

                        <p class="muted small mt-4 mb-0 text-center">
                            By confirming, you agree to attend the selected session.
                            This is a demonstration booking; no payment is collected.
                        </p>

                    </div>
                </div>

            </div>

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
    // Character counter for the optional trainer note.
    const noteField = document.getElementById('booking_note');
    const characterCount = document.getElementById('characterCount');

    noteField.addEventListener('input', function () {
        characterCount.textContent = this.value.length;
    });

    // Validate that the selected date matches the class schedule.
    const dateInput = document.getElementById('session_date');
    const requiredDay = <?= json_encode($selectedClass['day']) ?>;

    dateInput.addEventListener('change', function () {
        if (!this.value) {
            return;
        }

        const parts = this.value.split('-').map(Number);
        const selectedDate = new Date(parts[0], parts[1] - 1, parts[2]);

        const dayNames = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];

        if (dayNames[selectedDate.getDay()] !== requiredDay) {
            alert('This class is scheduled for ' + requiredDay + '. Please select the correct day.');
        }
    });

    // Prevent accidental double-clicks while submitting the form.
    document.getElementById('bookingForm').addEventListener('submit', function () {
        const button = document.getElementById('confirmButton');
        button.disabled = true;
        button.textContent = 'Confirming Booking...';
    });
</script>

</body>
</html>
