<?php
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Room Details
|--------------------------------------------------------------------------
| Demonstration page using sample room data.
| No database or live room availability is connected yet.
*/

$rooms = [
    1 => [
        'name' => 'Deluxe Ocean View',
        'type' => 'Deluxe Room',
        'price' => 180,
        'guests' => 2,
        'bed' => 'King-size bed',
        'size' => '32 m²',
        'image' => '../images/azurabay/9041.jpg',
        'gallery' => [
            '../images/azurabay/9041.jpg',
            '../images/azurabay/369.jpg',
            '../images/azurabay/6573.jpg'
        ],
        'description' => 'Wake up to beautiful ocean views in our elegant Deluxe Ocean View room. Designed for couples and solo travellers, this room combines modern comfort with the relaxing atmosphere of a tropical beachfront escape.',
        'amenities' => [
            'Private balcony with ocean views',
            'King-size bed',
            'Air conditioning',
            'Complimentary Wi-Fi',
            'Flat-screen television',
            'Private bathroom with premium toiletries',
            'Tea and coffee facilities',
            'In-room safe'
        ]
    ],
    2 => [
        'name' => 'Garden Serenity Room',
        'type' => 'Deluxe Room',
        'price' => 150,
        'guests' => 2,
        'bed' => 'Queen-size bed',
        'size' => '28 m²',
        'image' => 'assets/images/garden-room.jpg',
        'gallery' => [
            '../images/azurabay/925.jpg',
            '../images/azurabay/5032.jpg',
            '../images/azurabay/9029.jpg'
        ],
        'description' => 'Surrounded by tropical greenery, the Garden Serenity Room offers a peaceful retreat away from everyday stress. Enjoy a calm atmosphere, comfortable interiors and convenient access to the resort gardens.',
        'amenities' => [
            'Private garden-facing balcony',
            'Queen-size bed',
            'Air conditioning',
            'Complimentary Wi-Fi',
            'Flat-screen television',
            'Private bathroom',
            'Tea and coffee facilities',
            'Daily housekeeping'
        ]
    ],
    3 => [
        'name' => 'Premium Beach Suite',
        'type' => 'Suite',
        'price' => 290,
        'guests' => 3,
        'bed' => 'King-size bed and sofa bed',
        'size' => '48 m²',
        'image' => 'assets/images/beach-suite.jpg',
        'gallery' => [
            '../images/azurabay/6669.jpg',
            '../images/azurabay/305.jpg',
            '../images/azurabay/127.jpg'
        ],
        'description' => 'Experience elevated beachfront living in our Premium Beach Suite. With spacious interiors, a relaxing seating area and beautiful coastal surroundings, it is ideal for guests seeking an extra level of comfort.',
        'amenities' => [
            'Ocean-view balcony',
            'King-size bed and sofa bed',
            'Separate seating area',
            'Air conditioning',
            'Complimentary Wi-Fi',
            'Premium bathroom amenities',
            'Mini refrigerator',
            'Tea and coffee facilities'
        ]
    ],
    4 => [
        'name' => 'Family Coastal Suite',
        'type' => 'Suite',
        'price' => 340,
        'guests' => 5,
        'bed' => 'King-size bed and twin beds',
        'size' => '62 m²',
        'image' => 'assets/images/family-suite.jpg',
        'gallery' => [
            '../images/azurabay/1064.jpg',
            '../images/azurabay/6864.jpg',
            '../images/azurabay/369.jpg'
        ],
        'description' => 'Create unforgettable family memories in our spacious Coastal Suite. Thoughtfully designed for families, it offers comfortable sleeping arrangements, generous living space and convenient access to resort facilities.',
        'amenities' => [
            'Family-friendly living area',
            'King-size bed and twin beds',
            'Private balcony',
            'Air conditioning',
            'Complimentary Wi-Fi',
            'Flat-screen television',
            'Spacious private bathroom',
            'Mini refrigerator'
        ]
    ],
    5 => [
        'name' => 'Azure Presidential Villa',
        'type' => 'Villa',
        'price' => 450,
        'guests' => 4,
        'bed' => 'King-size bed and twin beds',
        'size' => '95 m²',
        'image' => 'assets/images/presidential-villa.jpg',
        'gallery' => [
            '../images/azurabay/651.jpg',
            '../images/azurabay/4501.jpg',
            '../images/azurabay/369.jpg'
        ],
        'description' => 'Discover exceptional privacy and luxury in the Azure Presidential Villa. Featuring elegant interiors, generous living areas and an exclusive atmosphere, this villa is perfect for guests celebrating a special occasion.',
        'amenities' => [
            'Private terrace',
            'Separate living and sleeping areas',
            'Premium bedding',
            'Air conditioning',
            'Complimentary Wi-Fi',
            'Luxury bathroom amenities',
            'Mini refrigerator',
            'Personal in-room safe'
        ]
    ],
    6 => [
        'name' => 'Horizon Ocean Suite',
        'type' => 'Suite',
        'price' => 320,
        'guests' => 3,
        'bed' => 'King-size bed and sofa bed',
        'size' => '52 m²',
        'image' => 'assets/images/horizon-suite.jpg',
        'gallery' => [
            '../images/azurabay/8954.jpg',
            '../images/azurabay/6573.jpg',
            '../images/azurabay/4501.jpg'
        ],
        'description' => 'Enjoy sweeping coastal scenery from the Horizon Ocean Suite. Its sophisticated design, comfortable lounge space and peaceful atmosphere make it a wonderful choice for a memorable seaside stay.',
        'amenities' => [
            'Panoramic ocean-view balcony',
            'King-size bed and sofa bed',
            'Separate lounge area',
            'Air conditioning',
            'Complimentary Wi-Fi',
            'Flat-screen television',
            'Premium bathroom amenities',
            'Tea and coffee facilities'
        ]
    ]
];

/* Get and validate the selected room ID. */
$roomId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$roomId || !isset($rooms[$roomId])) {
    http_response_code(404);
    $room = null;
} else {
    $room = $rooms[$roomId];
}

/* Validate booking dates. */
$today = new DateTimeImmutable('today');

$checkInInput = $_GET['check_in'] ?? $today->format('Y-m-d');
$checkOutInput = $_GET['check_out'] ?? $today->modify('+1 day')->format('Y-m-d');

function validDate(string $value): bool
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

    return $date !== false && $date->format('Y-m-d') === $value;
}

$checkIn = validDate((string)$checkInInput)
    ? new DateTimeImmutable($checkInInput)
    : $today;

$checkOut = validDate((string)$checkOutInput)
    ? new DateTimeImmutable($checkOutInput)
    : $checkIn->modify('+1 day');

if ($checkIn < $today) {
    $checkIn = $today;
}

if ($checkOut <= $checkIn) {
    $checkOut = $checkIn->modify('+1 day');
}

$guests = filter_input(INPUT_GET, 'guests', FILTER_VALIDATE_INT);

if ($guests === false || $guests === null || $guests < 1 || $guests > 6) {
    $guests = 2;
}

$nights = (int)$checkIn->diff($checkOut)->days;

$roomTotal = $room ? $room['price'] * $nights : 0;

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$checkInValue = $checkIn->format('Y-m-d');
$checkOutValue = $checkOut->format('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $room ? e($room['name']) . ' | Azure Bay Resort' : 'Room Not Found | Azure Bay Resort' ?>
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
            letter-spacing: .5px;
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
            margin-top: 2px;
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
            font-weight: 700;
            padding: 11px 21px;
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
            padding: 10px 18px;
        }

        .btn-outline-ocean:hover {
            background: var(--ocean);
            color: white;
        }

        .breadcrumb-area {
            padding: 22px 0 12px;
            font-size: 13px;
            color: var(--muted);
        }

        .breadcrumb-area a {
            color: var(--ocean);
        }

        .breadcrumb-area span {
            margin: 0 8px;
            color: var(--gold);
        }

        .room-heading {
            margin-bottom: 25px;
        }

        .eyebrow {
            color: #997a3e;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .room-heading h1 {
            color: var(--ocean-dark);
            font-weight: 800;
            font-size: clamp(29px, 4vw, 42px);
            margin: 8px 0;
        }

        .room-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 19px;
            color: var(--muted);
            font-size: 14px;
            margin-top: 13px;
        }

        .room-meta span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .gallery {
            display: grid;
            grid-template-columns: 1.55fr 1fr;
            grid-template-rows: repeat(2, 205px);
            gap: 12px;
            margin-bottom: 35px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            background: #e7e4dc;
        }

        .gallery-item:first-child {
            grid-row: span 2;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.04);
        }

        .gallery-label {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(9, 43, 53, .88);
            color: white;
            padding: 7px 12px;
            border-radius: 3px;
            font-size: 11px;
            letter-spacing: .5px;
        }

        .section-title {
            color: var(--ocean-dark);
            font-size: 23px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .description {
            line-height: 1.9;
            color: #637278;
            font-size: 15px;
        }

        .detail-panel {
            background: white;
            border: 1px solid #eee8db;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .amenity-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 18px;
            margin-top: 20px;
        }

        .amenity {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            line-height: 1.5;
            color: #52656a;
        }

        .amenity-icon {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            color: #87692f;
            background: #f5eedf;
            font-size: 13px;
        }

        .booking-card {
            position: sticky;
            top: 20px;
            background: white;
            border: 1px solid #e8e0d1;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(16, 50, 58, .07);
            margin-bottom: 25px;
        }

        .booking-card-header {
            background: var(--ocean);
            color: white;
            padding: 22px;
        }

        .booking-card-header small {
            color: #d5e0df;
            font-size: 12px;
        }

        .price {
            font-size: 33px;
            font-weight: 800;
            color: var(--gold-light);
            margin-top: 5px;
        }

        .price small {
            font-size: 13px;
            font-weight: 400;
        }

        .booking-card-body {
            padding: 22px;
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

        .price-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 12px 0;
            color: #718087;
            font-size: 13px;
        }

        .price-row.total {
            border-top: 1px solid #e9e5dc;
            padding-top: 15px;
            color: var(--ocean-dark);
            font-weight: 800;
            font-size: 16px;
        }

        .booking-note {
            background: #f7f3e9;
            color: #76663f;
            padding: 12px;
            font-size: 11px;
            line-height: 1.7;
            border-radius: 4px;
            margin-top: 15px;
        }

        .info-strip {
            background: var(--ocean-dark);
            color: white;
            padding: 24px 0;
            margin-top: 35px;
        }

        .info-strip strong {
            display: block;
            color: var(--gold-light);
            margin-bottom: 5px;
            font-size: 13px;
        }

        .info-strip span {
            color: #d4dfdf;
            font-size: 12px;
        }

        footer {
            background: #061f27;
            color: #c4d1d1;
            padding: 45px 0 20px;
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

        .not-found {
            text-align: center;
            padding: 100px 20px;
        }

        .not-found h1 {
            color: var(--ocean-dark);
            font-weight: 800;
        }

        @media (max-width: 991px) {
            .gallery {
                grid-template-rows: repeat(2, 160px);
            }

            .booking-card {
                position: static;
            }
        }

        @media (max-width: 575px) {
            .gallery {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 240px 125px;
                gap: 8px;
            }

            .gallery-item:first-child {
                grid-column: span 2;
                grid-row: auto;
            }

            .amenity-grid {
                grid-template-columns: 1fr;
            }

            .room-meta {
                gap: 10px 15px;
            }

            .detail-panel {
                padding: 19px;
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
                    <a class="nav-link active" href="rooms.php">Our Rooms</a>
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

<?php if (!$room): ?>

    <main class="container not-found">
        <div class="eyebrow">Azure Bay Resort</div>
        <h1 class="mt-3">Room Not Found</h1>
        <p class="text-secondary mt-3">
            Sorry, the room you are looking for does not exist in this demonstration.
        </p>
        <a href="rooms.php" class="btn btn-gold mt-3">Explore Our Rooms</a>
    </main>

<?php else: ?>

<main class="container pb-3">

    <div class="breadcrumb-area">
        <a href="index.php">Home</a>
        <span>/</span>
        <a href="rooms.php">Our Rooms</a>
        <span>/</span>
        <?= e($room['name']) ?>
    </div>

    <section class="room-heading">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="eyebrow">Your private coastal retreat</div>
                <h1><?= e($room['name']) ?></h1>
                <p class="text-secondary mb-0">
                    Experience comfort, elegance and the beauty of the coast.
                </p>

                <div class="room-meta">
                    <span>⌂ <?= e($room['type']) ?></span>
                    <span>♙ Up to <?= (int)$room['guests'] ?> guests</span>
                    <span>▱ <?= e($room['size']) ?></span>
                    <span>▤ <?= e($room['bed']) ?></span>
                </div>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="rooms.php" class="btn btn-outline-ocean btn-sm">
                    ← Back to Rooms
                </a>
            </div>
        </div>
    </section>

    <!-- Room photo gallery -->
    <section class="gallery" aria-label="Room photo gallery">
        <?php foreach ($room['gallery'] as $index => $image): ?>
            <div class="gallery-item">
                <img
                    src="<?= e($image) ?>"
                    alt="<?= e($room['name']) ?> - photo <?= $index + 1 ?>"
                    loading="<?= $index === 0 ? 'eager' : 'lazy' ?>"
                    onerror="this.style.display='none';"
                >
                <?php if ($index === 0): ?>
                    <span class="gallery-label">ROOM GALLERY</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>

    <div class="row g-4">

        <!-- Room information -->
        <div class="col-lg-7">

            <section class="detail-panel">
                <div class="eyebrow mb-2">Welcome to your escape</div>
                <h2 class="section-title">About This Room</h2>

                <p class="description mb-0">
                    <?= e($room['description']) ?>
                </p>
            </section>

            <section class="detail-panel">
                <div class="eyebrow mb-2">Thoughtful details</div>
                <h2 class="section-title">Room Amenities</h2>

                <p class="text-secondary small">
                    Everything you need for a comfortable and memorable stay.
                </p>

                <div class="amenity-grid">
                    <?php foreach ($room['amenities'] as $amenity): ?>
                        <div class="amenity">
                            <span class="amenity-icon">✓</span>
                            <span><?= e($amenity) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="detail-panel">
                <div class="eyebrow mb-2">The Azure Bay experience</div>
                <h2 class="section-title">Resort Highlights</h2>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="d-flex gap-3">
                            <div class="amenity-icon">☼</div>
                            <div>
                                <strong class="d-block small">Beachfront Location</strong>
                                <small class="text-secondary">
                                    Enjoy the beauty of the tropical coastline.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="d-flex gap-3">
                            <div class="amenity-icon">♨</div>
                            <div>
                                <strong class="d-block small">Relaxing Spa</strong>
                                <small class="text-secondary">
                                    Unwind with a peaceful wellness experience.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="d-flex gap-3">
                            <div class="amenity-icon">♨</div>
                            <div>
                                <strong class="d-block small">Swimming Pool</strong>
                                <small class="text-secondary">
                                    Relax beside our resort-style pool.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="d-flex gap-3">
                            <div class="amenity-icon">✧</div>
                            <div>
                                <strong class="d-block small">Guest Hospitality</strong>
                                <small class="text-secondary">
                                    A welcoming stay from arrival to departure.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <!-- Booking card -->
        <div class="col-lg-5">
            <aside class="booking-card">

                <div class="booking-card-header">
                    <small>ROOM RATE FROM</small>
                    <div class="price">
                        $<?= number_format($room['price']) ?>
                        <small>/ night</small>
                    </div>
                    <small>Sample demonstration pricing</small>
                </div>

                <div class="booking-card-body">
                    <h2 class="section-title fs-5">Plan Your Stay</h2>

                    <form action="booking.php" method="GET" id="bookingForm">
                        <input type="hidden" name="room_id"
                               value="<?= (int)$roomId ?>">

                        <div class="mb-3">
                            <label for="check_in" class="form-label">
                                Check-in Date
                            </label>
                            <input type="date" class="form-control"
                                   id="check_in" name="check_in"
                                   value="<?= e($checkInValue) ?>"
                                   min="<?= e($today->format('Y-m-d')) ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="check_out" class="form-label">
                                Check-out Date
                            </label>
                            <input type="date" class="form-control"
                                   id="check_out" name="check_out"
                                   value="<?= e($checkOutValue) ?>"
                                   min="<?= e($checkIn->modify('+1 day')->format('Y-m-d')) ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="guests" class="form-label">
                                Number of Guests
                            </label>
                            <select class="form-select" id="guests" name="guests" required>
                                <?php for ($i = 1; $i <= $room['guests']; $i++): ?>
                                    <option value="<?= $i ?>"
                                        <?= $guests === $i ? 'selected' : '' ?>>
                                        <?= $i ?> <?= $i === 1 ? 'Guest' : 'Guests' ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="price-row">
                            <span>Room rate per night</span>
                            <span>$<?= number_format($room['price']) ?></span>
                        </div>

                        <div class="price-row">
                            <span>Number of nights</span>
                            <span id="nightCount"><?= $nights ?></span>
                        </div>

                        <div class="price-row total">
                            <span>Estimated room total</span>
                            <span id="roomTotal">$<?= number_format($roomTotal) ?></span>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 mt-3">
                            Continue to Booking →
                        </button>

                        <a href="rooms.php" class="btn btn-outline-ocean w-100 mt-2">
                            Explore Other Rooms
                        </a>

                        <div class="booking-note">
                            <strong>Demo notice:</strong>
                            This page uses sample room rates. Room availability,
                            taxes and final booking confirmation have not yet been
                            connected to a database.
                        </div>
                    </form>
                </div>
            </aside>
        </div>

    </div>
</main>

<section class="info-strip">
    <div class="container">
        <div class="row g-4 text-center text-md-start">
            <div class="col-md-4">
                <strong>Thoughtful Comfort</strong>
                <span>Rooms designed for a relaxing coastal escape.</span>
            </div>
            <div class="col-md-4">
                <strong>Beautiful Surroundings</strong>
                <span>Discover the atmosphere of tropical beachfront living.</span>
            </div>
            <div class="col-md-4">
                <strong>Memorable Stays</strong>
                <span>Make your next getaway a special experience.</span>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <a href="index.php" class="navbar-brand">
                    AZURE <span>BAY</span>
                </a>
                <p class="mt-3">
                    A peaceful beachfront escape where comfort, nature and
                    warm hospitality come together. Discover your next
                    unforgettable getaway with Azure Bay Resort.
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

    const roomRate = <?= $room ? (int)$room['price'] : 0 ?>;

    function updateStaySummary() {
        if (!checkInInput || !checkOutInput) return;

        const checkIn = new Date(checkInInput.value + 'T00:00:00');
        const checkOut = new Date(checkOutInput.value + 'T00:00:00');

        if (Number.isNaN(checkIn.getTime()) ||
            Number.isNaN(checkOut.getTime())) {
            return;
        }

        const millisecondsPerDay = 1000 * 60 * 60 * 24;
        const nights = Math.round(
            (checkOut.getTime() - checkIn.getTime()) / millisecondsPerDay
        );

        document.getElementById('nightCount').textContent =
            nights > 0 ? nights : '—';

        document.getElementById('roomTotal').textContent =
            nights > 0
                ? '$' + (roomRate * nights).toLocaleString('en-US')
                : '—';
    }

    if (checkInInput && checkOutInput) {
        checkInInput.addEventListener('change', function () {
            if (!checkInInput.value) return;

            const nextDay = new Date(checkInInput.value + 'T00:00:00');
            nextDay.setDate(nextDay.getDate() + 1);

            const minCheckout =
                nextDay.getFullYear() + '-' +
                String(nextDay.getMonth() + 1).padStart(2, '0') + '-' +
                String(nextDay.getDate()).padStart(2, '0');

            checkOutInput.min = minCheckout;

            if (!checkOutInput.value || checkOutInput.value < minCheckout) {
                checkOutInput.value = minCheckout;
            }

            updateStaySummary();
        });

        checkOutInput.addEventListener('change', updateStaySummary);
        updateStaySummary();
    }

    document.getElementById('bookingForm')?.addEventListener('submit', function (event) {
        if (checkOutInput.value <= checkInInput.value) {
            event.preventDefault();
            alert('Please select a check-out date after your check-in date.');
            checkOutInput.focus();
        }
    });
</script>

</body>
</html>