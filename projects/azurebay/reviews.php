<?php
date_default_timezone_set('Asia/Colombo');
session_start();

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Guest Reviews
|--------------------------------------------------------------------------
| Portfolio demonstration only.
| Reviews are stored in the current PHP session.
| No database or external review service is connected.
*/

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/*
|--------------------------------------------------------------------------
| Sample guest reviews
|--------------------------------------------------------------------------
*/

$sampleReviews = [
    [
        'id' => 'REV1001',
        'name' => 'Olivia Bennett',
        'country' => 'United Kingdom',
        'rating' => 5,
        'title' => 'A Beautiful Beachfront Escape',
        'comment' => 'Our stay at Azure Bay was absolutely wonderful. The ocean-view room was beautiful, the surroundings were peaceful, and the staff made us feel welcome throughout our visit. We especially loved watching the sunset from the terrace.',
        'date' => '2026-09-18',
        'room' => 'Deluxe Ocean View',
        'verified' => true
    ],
    [
        'id' => 'REV1002',
        'name' => 'Daniel Martin',
        'country' => 'Australia',
        'rating' => 5,
        'title' => 'Perfect for a Relaxing Holiday',
        'comment' => 'Everything felt calm and thoughtfully designed. The swimming pool was lovely, the room was comfortable, and the beachfront location was the highlight of our trip. I would happily recommend this resort to couples looking for a relaxing escape.',
        'date' => '2026-09-12',
        'room' => 'Premium Beach Suite',
        'verified' => true
    ],
    [
        'id' => 'REV1003',
        'name' => 'Sophia Williams',
        'country' => 'Canada',
        'rating' => 4,
        'title' => 'Comfortable Stay and Lovely Views',
        'comment' => 'We enjoyed the beautiful scenery and the comfortable accommodation. The room was spacious and clean, and the beach was easy to reach. The check-in process took a little longer than expected, but overall we had a very pleasant experience.',
        'date' => '2026-09-05',
        'room' => 'Garden Serenity Room',
        'verified' => true
    ],
    [
        'id' => 'REV1004',
        'name' => 'Liam Anderson',
        'country' => 'New Zealand',
        'rating' => 5,
        'title' => 'Wonderful Family Holiday',
        'comment' => 'We stayed as a family and really appreciated the extra space and peaceful atmosphere. The room was comfortable, and the resort gave us a great place to relax after exploring the area. We would love to return for another holiday.',
        'date' => '2026-08-27',
        'room' => 'Family Coastal Suite',
        'verified' => true
    ],
    [
        'id' => 'REV1005',
        'name' => 'Isabella Rossi',
        'country' => 'Italy',
        'rating' => 4,
        'title' => 'A Relaxing Coastal Experience',
        'comment' => 'The ocean views were memorable, and the resort had a peaceful atmosphere. Our room was well presented and comfortable. There is room for improvement in the dining options, but the overall stay was enjoyable.',
        'date' => '2026-08-20',
        'room' => 'Horizon Ocean Suite',
        'verified' => true
    ],
    [
        'id' => 'REV1006',
        'name' => 'Noah Thompson',
        'country' => 'United States',
        'rating' => 5,
        'title' => 'Luxury and Hospitality',
        'comment' => 'From the attractive surroundings to the comfortable room, the experience was excellent. The peaceful beach setting made it easy to unwind. This would be a great choice for anyone looking for a relaxing tropical getaway.',
        'date' => '2026-08-11',
        'room' => 'Azure Presidential Villa',
        'verified' => true
    ],
    [
        'id' => 'REV1007',
        'name' => 'Amelia Clarke',
        'country' => 'Ireland',
        'rating' => 3,
        'title' => 'Nice Location',
        'comment' => 'The location was the strongest part of our stay, with lovely coastal scenery and a pleasant atmosphere. The accommodation was comfortable, although some facilities could benefit from a little more attention. It was a reasonable overall experience.',
        'date' => '2026-08-02',
        'room' => 'Deluxe Ocean View',
        'verified' => true
    ],
    [
        'id' => 'REV1008',
        'name' => 'Ethan Wilson',
        'country' => 'Singapore',
        'rating' => 5,
        'title' => 'A Memorable Weekend',
        'comment' => 'We had a relaxing weekend surrounded by beautiful views. The room was inviting, and the resort atmosphere was exactly what we wanted. I would recommend it to anyone who wants to take a break from a busy schedule.',
        'date' => '2026-07-24',
        'room' => 'Premium Beach Suite',
        'verified' => true
    ]
];

/*
|--------------------------------------------------------------------------
| Initialise session review storage
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['azurebay_reviews']) ||
    !is_array($_SESSION['azurebay_reviews'])) {
    $_SESSION['azurebay_reviews'] = [];
}

/*
|--------------------------------------------------------------------------
| Handle review submission
|--------------------------------------------------------------------------
*/

$formMessage = '';
$formType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'submit_review') {

    $name = trim($_POST['name'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $rating = filter_var(
        $_POST['rating'] ?? null,
        FILTER_VALIDATE_INT
    );
    $title = trim($_POST['title'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $room = trim($_POST['room'] ?? '');

    if (
        $name === '' ||
        mb_strlen($name) > 80 ||
        $country === '' ||
        mb_strlen($country) > 80 ||
        $rating === false ||
        $rating < 1 ||
        $rating > 5 ||
        $title === '' ||
        mb_strlen($title) > 100 ||
        $comment === '' ||
        mb_strlen($comment) < 10 ||
        mb_strlen($comment) > 1200
    ) {
        $formMessage = 'Please complete all required fields correctly. Your review must contain at least 10 characters.';
        $formType = 'danger';
    } else {
        $allowedRooms = [
            'Deluxe Ocean View',
            'Garden Serenity Room',
            'Premium Beach Suite',
            'Family Coastal Suite',
            'Azure Presidential Villa',
            'Horizon Ocean Suite'
        ];

        if (!in_array($room, $allowedRooms, true)) {
            $room = 'General Resort Experience';
        }

        $newReview = [
            'id' => 'DEMO-' . strtoupper(bin2hex(random_bytes(4))),
            'name' => $name,
            'country' => $country,
            'rating' => (int)$rating,
            'title' => $title,
            'comment' => $comment,
            'date' => date('Y-m-d'),
            'room' => $room,
            'verified' => false
        ];

        array_unshift($_SESSION['azurebay_reviews'], $newReview);

        /*
         * Post/Redirect/Get prevents accidental repeat submissions
         * when the visitor refreshes the page.
         */
        $_SESSION['azurebay_review_flash'] =
            'Thank you! Your sample review has been added successfully.';

        header('Location: reviews.php?submitted=1');
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Review submission feedback after redirect
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['azurebay_review_flash'])) {
    $formMessage = $_SESSION['azurebay_review_flash'];
    $formType = 'success';
    unset($_SESSION['azurebay_review_flash']);
}

/*
|--------------------------------------------------------------------------
| Combine sample reviews and visitor-submitted demo reviews
|--------------------------------------------------------------------------
*/

$allReviews = array_merge(
    $_SESSION['azurebay_reviews'],
    $sampleReviews
);

/*
|--------------------------------------------------------------------------
| Filters and sorting
|--------------------------------------------------------------------------
*/

$ratingFilter = filter_var(
    $_GET['rating'] ?? 0,
    FILTER_VALIDATE_INT
);

if ($ratingFilter === false || $ratingFilter < 0 || $ratingFilter > 5) {
    $ratingFilter = 0;
}

$sort = $_GET['sort'] ?? 'newest';

$allowedSorts = ['newest', 'oldest', 'highest', 'lowest'];

if (!in_array($sort, $allowedSorts, true)) {
    $sort = 'newest';
}

$filteredReviews = array_values(array_filter(
    $allReviews,
    function ($review) use ($ratingFilter) {
        return $ratingFilter === 0 ||
            (int)$review['rating'] === $ratingFilter;
    }
));

usort($filteredReviews, function ($a, $b) use ($sort) {
    switch ($sort) {
        case 'oldest':
            return strcmp($a['date'], $b['date']);

        case 'highest':
            return (int)$b['rating'] <=> (int)$a['rating'];

        case 'lowest':
            return (int)$a['rating'] <=> (int)$b['rating'];

        case 'newest':
        default:
            return strcmp($b['date'], $a['date']);
    }
});

/*
|--------------------------------------------------------------------------
| Rating summary
|--------------------------------------------------------------------------
*/

$totalReviews = count($allReviews);
$totalStars = 0;
$ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

foreach ($allReviews as $review) {
    $reviewRating = (int)$review['rating'];

    $totalStars += $reviewRating;

    if (isset($ratingCounts[$reviewRating])) {
        $ratingCounts[$reviewRating]++;
    }
}

$averageRating = $totalReviews > 0
    ? $totalStars / $totalReviews
    : 0;

function renderStars($rating): string
{
    $rating = max(0, min(5, (int)$rating));

    return str_repeat('★', $rating) .
        str_repeat('☆', 5 - $rating);
}

function formatReviewDate($date): string
{
    $parsedDate = DateTimeImmutable::createFromFormat(
        '!Y-m-d',
        (string)$date
    );

    if (!$parsedDate || $parsedDate->format('Y-m-d') !== $date) {
        return 'Date unavailable';
    }

    return $parsedDate->format('d M Y');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Guest Reviews | Azure Bay Resort</title>

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
                linear-gradient(90deg, rgba(9,43,53,.94), rgba(18,62,74,.77)),
                url('assets/images/resort-hero.jpg') center/cover;
            padding: 58px 0;
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
            max-width: 680px;
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

        .section-heading {
            color: var(--ocean-dark);
            font-weight: 800;
            font-size: 27px;
        }

        .section-subtitle {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .rating-overview {
            background: white;
            border: 1px solid #ece5d8;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 8px 28px rgba(16, 50, 58, .04);
        }

        .average-rating {
            font-size: 55px;
            color: var(--ocean-dark);
            font-weight: 800;
            line-height: 1.2;
        }

        .stars {
            color: #c39a43;
            letter-spacing: 2px;
            font-size: 17px;
        }

        .rating-count {
            color: var(--muted);
            font-size: 12px;
            margin-top: 8px;
        }

        .rating-row {
            display: grid;
            grid-template-columns: 45px 1fr 35px;
            gap: 12px;
            align-items: center;
            margin-bottom: 11px;
            font-size: 12px;
            color: var(--muted);
        }

        .rating-row:last-child {
            margin-bottom: 0;
        }

        .rating-bar {
            height: 7px;
            background: #eeeae1;
            border-radius: 20px;
            overflow: hidden;
        }

        .rating-bar-fill {
            height: 100%;
            background: var(--gold);
            border-radius: 20px;
        }

        .review-section {
            padding: 50px 0 65px;
        }

        .filter-card {
            background: white;
            border: 1px solid #ece5d8;
            border-radius: 7px;
            padding: 22px;
            margin-bottom: 22px;
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
            padding: 11px 12px;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 .2rem rgba(200, 169, 107, .15);
        }

        .review-card {
            background: white;
            border: 1px solid #ece5d8;
            border-radius: 7px;
            padding: 25px;
            height: 100%;
            box-shadow: 0 7px 25px rgba(16, 50, 58, .035);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(16, 50, 58, .08);
        }

        .reviewer-avatar {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--ocean);
            color: var(--gold-light);
            font-size: 18px;
            font-weight: 800;
        }

        .reviewer-name {
            color: var(--ocean-dark);
            font-weight: 800;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .reviewer-country {
            color: var(--muted);
            font-size: 11px;
        }

        .review-date {
            color: var(--muted);
            font-size: 11px;
            white-space: nowrap;
        }

        .review-title {
            color: var(--ocean-dark);
            font-size: 17px;
            font-weight: 800;
            margin: 17px 0 10px;
        }

        .review-comment {
            color: #64747a;
            font-size: 12px;
            line-height: 1.9;
            margin-bottom: 20px;
        }

        .review-footer {
            border-top: 1px solid #eee8dd;
            padding-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .room-tag {
            display: inline-block;
            background: #f5eedf;
            color: #87692f;
            padding: 6px 9px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 700;
        }

        .verified-tag {
            font-size: 10px;
            color: #49724c;
            font-weight: 700;
        }

        .review-count-heading {
            color: var(--muted);
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px;
            background: white;
            border: 1px solid #ece5d8;
            border-radius: 7px;
        }

        .empty-state h3 {
            color: var(--ocean-dark);
            font-weight: 800;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .review-form-card {
            background: white;
            border: 1px solid #ece5d8;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 8px 28px rgba(16, 50, 58, .04);
        }

        .review-form-card h3 {
            color: var(--ocean-dark);
            font-weight: 800;
            font-size: 23px;
        }

        .form-help {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 6px;
        }

        .rating-input input {
            position: absolute;
            opacity: 0;
            width: 1px;
            height: 1px;
        }

        .rating-input label {
            color: #d5d0c4;
            cursor: pointer;
            font-size: 30px;
            line-height: 1;
            transition: color .15s ease;
        }

        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #c39a43;
        }

        .rating-input input:focus-visible + label {
            outline: 2px solid var(--ocean);
            outline-offset: 3px;
        }

        .review-notice {
            background: #f8f2e4;
            border: 1px solid #ebdec1;
            color: #76663f;
            border-radius: 5px;
            padding: 14px 16px;
            font-size: 11px;
            line-height: 1.8;
        }

        .quote-panel {
            background: var(--ocean-dark);
            color: white;
            padding: 30px;
            border-radius: 7px;
        }

        .quote-mark {
            color: var(--gold);
            font-size: 45px;
            line-height: 1;
        }

        .quote-panel p {
            color: #d9e3e3;
            font-size: 13px;
            line-height: 1.9;
        }

        .quote-panel strong {
            color: var(--gold-light);
            font-size: 12px;
        }

        .feature-strip {
            background: var(--ocean-dark);
            color: white;
            padding: 28px 0;
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
                padding: 40px 0;
            }

            .rating-overview {
                padding: 22px;
            }

            .review-card {
                padding: 20px;
            }

            .review-form-card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

<!-- Navigation -->
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
                    <a class="nav-link active" href="reviews.php">Guest Reviews</a>
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

<!-- Page banner -->
<section class="page-banner">
    <div class="container">
        <div class="eyebrow">Experiences That Matter</div>
        <h1>Guest Reviews & Testimonials</h1>
        <p>
            Discover the experiences of our sample guests, explore their
            feedback, and share your own review of the Azure Bay experience.
        </p>
    </div>
</section>

<main>

    <div class="container">
        <div class="breadcrumb-area">
            <a href="index.php">Home</a>
            <span>/</span>
            Guest Reviews
        </div>

        <?php if ($formMessage !== ''): ?>
            <div class="alert alert-<?= e($formType) ?> alert-dismissible fade show"
                 role="alert">
                <?= e($formMessage) ?>
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Rating summary -->
    <section class="container pb-4">
        <div class="row g-4 align-items-stretch">

            <div class="col-lg-5">
                <div class="rating-overview h-100">
                    <div class="eyebrow mb-3">Guest Satisfaction</div>

                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <div>
                            <div class="average-rating">
                                <?= number_format($averageRating, 1) ?>
                            </div>
                            <div class="stars">
                                <?= e(renderStars((int)round($averageRating))) ?>
                            </div>
                            <div class="rating-count">
                                Based on <?= $totalReviews ?>
                                sample review<?= $totalReviews === 1 ? '' : 's' ?>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <p class="section-subtitle mb-0">
                                Every guest experience is different.
                                Explore the ratings and feedback below to
                                understand what visitors value about their stay.
                            </p>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color:#eee8dd">

                    <?php for ($star = 5; $star >= 1; $star--): ?>
                        <?php
                        $percentage = $totalReviews > 0
                            ? ($ratingCounts[$star] / $totalReviews) * 100
                            : 0;
                        ?>
                        <div class="rating-row">
                            <span><?= $star ?> star<?= $star === 1 ? '' : 's' ?></span>

                            <div class="rating-bar">
                                <div class="rating-bar-fill"
                                     style="width:<?= number_format($percentage, 2, '.', '') ?>%">
                                </div>
                            </div>

                            <span class="text-end">
                                <?= $ratingCounts[$star] ?>
                            </span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="quote-panel h-100 d-flex flex-column justify-content-center">
                    <div class="quote-mark">“</div>

                    <p class="mt-3 mb-4" style="font-size:16px">
                        A memorable resort experience is about more than
                        beautiful rooms. It is about comfort, warm hospitality,
                        peaceful surroundings, and the moments guests carry
                        home with them.
                    </p>

                    <strong>THE AZURE BAY EXPERIENCE</strong>

                    <div class="mt-4">
                        <a href="#write-review" class="btn btn-gold">
                            Share Your Experience
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Review listing -->
    <section class="review-section">
        <div class="container">

            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
                <div>
                    <div class="eyebrow mb-2">Words From Our Guests</div>
                    <h2 class="section-heading mb-2">What Guests Are Saying</h2>
                    <p class="section-subtitle mb-0">
                        Read the sample feedback and browse reviews by rating.
                    </p>
                </div>

                <div class="review-count-heading">
                    <?= count($filteredReviews) ?> review(s) displayed
                </div>
            </div>

            <!-- Filters -->
            <div class="filter-card">
                <form method="GET" action="reviews.php">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-5">
                            <label for="rating" class="form-label">
                                Filter by Rating
                            </label>
                            <select class="form-select" id="rating" name="rating">
                                <option value="0"
                                    <?= (int)$ratingFilter === 0 ? 'selected' : '' ?>>
                                    All Ratings
                                </option>

                                <?php for ($star = 5; $star >= 1; $star--): ?>
                                    <option value="<?= $star ?>"
                                        <?= (int)$ratingFilter === $star ? 'selected' : '' ?>>
                                        <?= $star ?> Star<?= $star === 1 ? '' : 's' ?>
                                        (<?= $ratingCounts[$star] ?>)
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label for="sort" class="form-label">
                                Sort Reviews
                            </label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="newest"
                                    <?= $sort === 'newest' ? 'selected' : '' ?>>
                                    Newest First
                                </option>
                                <option value="oldest"
                                    <?= $sort === 'oldest' ? 'selected' : '' ?>>
                                    Oldest First
                                </option>
                                <option value="highest"
                                    <?= $sort === 'highest' ? 'selected' : '' ?>>
                                    Highest Rating
                                </option>
                                <option value="lowest"
                                    <?= $sort === 'lowest' ? 'selected' : '' ?>>
                                    Lowest Rating
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-gold">
                                Apply
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <?php if (empty($filteredReviews)): ?>

                <div class="empty-state">
                    <h3>No Reviews Found</h3>
                    <p>
                        There are no reviews matching this rating.
                        Try another filter or view all reviews.
                    </p>
                    <a href="reviews.php" class="btn btn-outline-ocean">
                        View All Reviews
                    </a>
                </div>

            <?php else: ?>

                <div class="row g-4">

                    <?php foreach ($filteredReviews as $review): ?>
                        <?php
                        $initial = mb_strtoupper(
                            mb_substr($review['name'], 0, 1)
                        );
                        ?>

                        <div class="col-lg-6">
                            <article class="review-card">

                                <div class="d-flex align-items-start gap-3">
                                    <div class="reviewer-avatar">
                                        <?= e($initial) ?>
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="reviewer-name">
                                            <?= e($review['name']) ?>
                                        </div>

                                        <div class="reviewer-country">
                                            <?= e($review['country']) ?>
                                        </div>

                                        <div class="stars mt-2"
                                             aria-label="<?= (int)$review['rating'] ?> out of 5 stars">
                                            <?= e(renderStars($review['rating'])) ?>
                                        </div>
                                    </div>

                                    <div class="review-date">
                                        <?= e(formatReviewDate($review['date'])) ?>
                                    </div>
                                </div>

                                <h3 class="review-title">
                                    <?= e($review['title']) ?>
                                </h3>

                                <p class="review-comment">
                                    <?= nl2br(e($review['comment'])) ?>
                                </p>

                                <div class="review-footer">
                                    <span class="room-tag">
                                        <?= e($review['room']) ?>
                                    </span>

                                    <?php if (!empty($review['verified'])): ?>
                                        <span class="verified-tag">
                                            ✓ Sample Guest Review
                                        </span>
                                    <?php else: ?>
                                        <span class="verified-tag"
                                              style="color:#87692f">
                                            Demo Submission
                                        </span>
                                    <?php endif; ?>
                                </div>

                            </article>
                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

            <div class="text-center mt-5">
                <a href="rooms.php" class="btn btn-gold">
                    Explore Our Rooms →
                </a>
            </div>

        </div>
    </section>

    <!-- Submit a review -->
    <section class="container pb-5" id="write-review">
        <div class="row g-4 justify-content-center">

            <div class="col-lg-8">
                <div class="review-form-card">
                    <div class="eyebrow mb-2">Your Feedback Matters</div>
                    <h3>Share Your Experience</h3>

                    <p class="section-subtitle mb-4">
                        Submit a sample review to experience the review
                        submission workflow.
                    </p>

                    <form method="POST" action="reviews.php#write-review">

                        <input type="hidden" name="action"
                               value="submit_review">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    Full Name *
                                </label>
                                <input type="text" id="name" name="name"
                                       class="form-control"
                                       maxlength="80"
                                       required
                                       placeholder="Enter your name"
                                       value="<?= e(
                                           $_SERVER['REQUEST_METHOD'] === 'POST'
                                               ? ($_POST['name'] ?? '')
                                               : ''
                                       ) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="country" class="form-label">
                                    Country *
                                </label>
                                <input type="text" id="country" name="country"
                                       class="form-control"
                                       maxlength="80"
                                       required
                                       placeholder="e.g. Sri Lanka"
                                       value="<?= e(
                                           $_SERVER['REQUEST_METHOD'] === 'POST'
                                               ? ($_POST['country'] ?? '')
                                               : ''
                                       ) ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label d-block">
                                    Your Rating *
                                </label>

                                <div class="rating-input">
                                    <input type="radio" id="rating-5"
                                           name="rating" value="5"
                                           required
                                           <?= (($_POST['rating'] ?? '') === '5') ? 'checked' : '' ?>>
                                    <label for="rating-5"
                                           title="5 stars">★</label>

                                    <input type="radio" id="rating-4"
                                           name="rating" value="4"
                                           <?= (($_POST['rating'] ?? '') === '4') ? 'checked' : '' ?>>
                                    <label for="rating-4"
                                           title="4 stars">★</label>

                                    <input type="radio" id="rating-3"
                                           name="rating" value="3"
                                           <?= (($_POST['rating'] ?? '') === '3') ? 'checked' : '' ?>>
                                    <label for="rating-3"
                                           title="3 stars">★</label>

                                    <input type="radio" id="rating-2"
                                           name="rating" value="2"
                                           <?= (($_POST['rating'] ?? '') === '2') ? 'checked' : '' ?>>
                                    <label for="rating-2"
                                           title="2 stars">★</label>

                                    <input type="radio" id="rating-1"
                                           name="rating" value="1"
                                           <?= (($_POST['rating'] ?? '') === '1') ? 'checked' : '' ?>>
                                    <label for="rating-1"
                                           title="1 star">★</label>
                                </div>

                                <div class="form-help mt-2">
                                    Select one to five stars to rate your experience.
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="room" class="form-label">
                                    Room or Accommodation
                                </label>

                                <select class="form-select" id="room" name="room">
                                    <option value="">General Resort Experience</option>

                                    <?php
                                    $roomOptions = [
                                        'Deluxe Ocean View',
                                        'Garden Serenity Room',
                                        'Premium Beach Suite',
                                        'Family Coastal Suite',
                                        'Azure Presidential Villa',
                                        'Horizon Ocean Suite'
                                    ];
                                    ?>

                                    <?php foreach ($roomOptions as $roomOption): ?>
                                        <option value="<?= e($roomOption) ?>"
                                            <?= (($_POST['room'] ?? '') === $roomOption) ? 'selected' : '' ?>>
                                            <?= e($roomOption) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="title" class="form-label">
                                    Review Title *
                                </label>
                                <input type="text" id="title" name="title"
                                       class="form-control"
                                       maxlength="100"
                                       required
                                       placeholder="Summarise your experience"
                                       value="<?= e(
                                           $_SERVER['REQUEST_METHOD'] === 'POST'
                                               ? ($_POST['title'] ?? '')
                                               : ''
                                       ) ?>">
                            </div>

                            <div class="col-12">
                                <label for="comment" class="form-label">
                                    Your Review *
                                </label>
                                <textarea id="comment" name="comment"
                                          class="form-control"
                                          rows="5"
                                          maxlength="1200"
                                          minlength="10"
                                          required
                                          placeholder="Tell us about your experience..."><?= e(
                                              $_SERVER['REQUEST_METHOD'] === 'POST'
                                                  ? ($_POST['comment'] ?? '')
                                                  : ''
                                          ) ?></textarea>

                                <div class="form-help mt-2">
                                    Write between 10 and 1,200 characters.
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="review-notice">
                                    <strong>Demonstration notice:</strong>
                                    This form creates a sample review stored in
                                    your current PHP session. It does not verify
                                    a real stay or publish to a public review service.
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-gold">
                                    Submit Demo Review →
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>

</main>

<!-- Feature strip -->
<section class="feature-strip">
    <div class="container">
        <div class="row g-4 text-center text-md-start">

            <div class="col-md-4">
                <strong>Comfort by the Coast</strong>
                <span>Relax in a peaceful beachfront setting.</span>
            </div>

            <div class="col-md-4">
                <strong>Memorable Stays</strong>
                <span>Discover accommodation for different travel needs.</span>
            </div>

            <div class="col-md-4">
                <strong>Guest-Focused Hospitality</strong>
                <span>Explore experiences shared by sample guests.</span>
            </div>

        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-4 col-md-6">
                <a href="index.php" class="navbar-brand">
                    AZURE <span>BAY</span>
                </a>

                <p class="mt-3">
                    A peaceful beachfront escape where comfort, nature,
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


</body>
</html>