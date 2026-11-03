
<?php
// Azure Bay Resort - Rooms and Availability Page

date_default_timezone_set('Asia/Colombo');

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$checkIn = isset($_GET['check_in']) &&
           is_string($_GET['check_in'])
    ? $_GET['check_in']
    : $today;

$checkOut = isset($_GET['check_out']) &&
            is_string($_GET['check_out'])
    ? $_GET['check_out']
    : $tomorrow;

$guests = isset($_GET['guests']) &&
          is_scalar($_GET['guests'])
    ? filter_var($_GET['guests'], FILTER_VALIDATE_INT)
    : 2;

$datePattern = '/^\d{4}-\d{2}-\d{2}$/';

if (
    !preg_match($datePattern, $checkIn) ||
    !preg_match($datePattern, $checkOut) ||
    strtotime($checkIn) === false ||
    strtotime($checkOut) === false ||
    date('Y-m-d', strtotime($checkIn)) !== $checkIn ||
    date('Y-m-d', strtotime($checkOut)) !== $checkOut
) {
    $checkIn = $today;
    $checkOut = $tomorrow;
}

if ($checkIn < $today) {
    $checkIn = $today;
}

if ($checkOut <= $checkIn) {
    $checkOut = date(
        'Y-m-d',
        strtotime($checkIn . ' +1 day')
    );
}

if ($checkIn >= $checkOut) {
    $checkIn = $today;
    $checkOut = $tomorrow;
}

if ($guests === false || $guests < 1 || $guests > 6) {
    $guests = 2;
}

$checkInTimestamp = strtotime($checkIn);
$checkOutTimestamp = strtotime($checkOut);

$nights = (int)(($checkOutTimestamp - $checkInTimestamp) / 86400);

// Demonstration room data.
// These prices and availability indicators are illustrative.
// They are not retrieved from a live reservation database.

$rooms = [
    [
        'id' => 1,
        'name' => 'Deluxe Ocean View',
        'type' => 'Deluxe Room',
        'category' => 'Ocean View',
        'image' => '../images/azurabay/9041.jpg',
        'gallery' => [
            'assets/images/deluxe-ocean.jpg',
            'assets/images/resort-about.jpg'
        ],
        'description' =>
            'Wake up to beautiful ocean views in a refined room with elegant interiors, a comfortable king bed, and a relaxing coastal atmosphere.',
        'price' => 180,
        'guests' => 2,
        'size' => '38 m²',
        'bed' => '1 King Bed',
        'amenities' => [
            'Ocean View',
            'Free Wi-Fi',
            'Air Conditioning',
            'Rain Shower'
        ],
        'badge' => 'POPULAR CHOICE',
        'available' => true
    ],
    [
        'id' => 2,
        'name' => 'Garden Serenity Room',
        'type' => 'Deluxe Room',
        'category' => 'Garden View',
        'image' => '../images/azurabay/925.jpg',
        'gallery' => [
            'assets/images/garden-room.jpg',
            'assets/images/resort-lounge.jpg'
        ],
        'description' =>
            'Enjoy a peaceful retreat surrounded by tropical greenery, soft natural tones, and thoughtfully selected comforts.',
        'price' => 150,
        'guests' => 2,
        'size' => '34 m²',
        'bed' => '1 King Bed',
        'amenities' => [
            'Garden View',
            'Free Wi-Fi',
            'Air Conditioning',
            'Private Bathroom'
        ],
        'badge' => '',
        'available' => true
    ],
    [
        'id' => 3,
        'name' => 'Premium Beach Suite',
        'type' => 'Suite',
        'category' => 'Premium Suite',
        'image' => '../images/azurabay/6669.jpg',
        'gallery' => [
            'assets/images/beach-suite.jpg',
            'assets/images/resort-about.jpg'
        ],
        'description' =>
            'Relax in a spacious suite with a private balcony, generous living space, premium furnishings, and stunning coastal scenery.',
        'price' => 290,
        'guests' => 3,
        'size' => '56 m²',
        'bed' => '1 King Bed',
        'amenities' => [
            'Private Balcony',
            'Ocean View',
            'Lounge Area',
            'Mini Bar'
        ],
        'badge' => 'GUEST FAVOURITE',
        'available' => true
    ],
    [
        'id' => 4,
        'name' => 'Family Coastal Suite',
        'type' => 'Suite',
        'category' => 'Family Suite',
        'image' => '../images/azurabay/1065.jpg',
        'gallery' => [
            'assets/images/family-suite.jpg',
            'assets/images/resort-lounge.jpg'
        ],
        'description' =>
            'A spacious family-friendly retreat with flexible sleeping arrangements and room to relax together after a day by the sea.',
        'price' => 340,
        'guests' => 5,
        'size' => '68 m²',
        'bed' => 'King Bed + Twin Beds',
        'amenities' => [
            'Family Space',
            'Free Wi-Fi',
            'Two Sleeping Areas',
            'Rain Shower'
        ],
        'badge' => 'FAMILY PICK',
        'available' => true
    ],
    [
        'id' => 5,
        'name' => 'Azure Presidential Villa',
        'type' => 'Villa',
        'category' => 'Exclusive Villa',
        'image' => '../images/azurabay/651.jpg',
        'gallery' => [
            'assets/images/presidential-villa.jpg',
            'assets/images/resort-pool.jpg'
        ],
        'description' =>
            'Discover exceptional privacy in an expansive villa with sophisticated interiors, generous living areas, and an exclusive retreat atmosphere.',
        'price' => 450,
        'guests' => 4,
        'size' => '95 m²',
        'bed' => '1 King Bed + Twin Beds',
        'amenities' => [
            'Private Terrace',
            'Premium Lounge',
            'Ocean View',
            'Luxury Bathroom'
        ],
        'badge' => 'EXCLUSIVE',
        'available' => true
    ],
    [
        'id' => 6,
        'name' => 'Horizon Ocean Suite',
        'type' => 'Suite',
        'category' => 'Ocean Suite',
        'image' => '../images/azurabay/8954.jpg',
        'gallery' => [
            'assets/images/horizon-suite.jpg',
            'assets/images/beach-suite.jpg'
        ],
        'description' =>
            'Experience understated luxury in a bright suite with expansive windows, elegant furnishings, and inspiring views of the horizon.',
        'price' => 320,
        'guests' => 3,
        'size' => '61 m²',
        'bed' => '1 King Bed',
        'amenities' => [
            'Panoramic Views',
            'Free Wi-Fi',
            'Lounge Area',
            'Private Balcony'
        ],
        'badge' => '',
        'available' => true
    ]
];

// Filter the sample room list for the requested guest capacity.
// This does NOT check booked rooms or actual date availability.
$matchingRooms = array_values(array_filter(
    $rooms,
    function ($room) use ($guests) {
        return $room['guests'] >= $guests;
    }
));

$roomCount = count($matchingRooms);
$roomTypes = ['Deluxe Room', 'Suite', 'Villa'];

$checkInDisplay = date('d M Y', strtotime($checkIn));
$checkOutDisplay = date('d M Y', strtotime($checkOut));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="Explore the rooms and suites at Azure Bay Resort. Compare accommodation styles, guest capacity, amenities, and illustrative nightly prices.">

    <meta name="theme-color" content="#103c4b">

    <title>Rooms & Suites | Azure Bay Resort</title>

    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --ocean: #103c4b;
            --ocean-dark: #092b37;
            --ocean-light: #1d6875;
            --gold: #c8a66a;
            --gold-light: #e9d4a7;
            --sand: #f7f2e9;
            --cream: #fcfaf6;
            --text: #263b44;
            --muted: #73828a;
            --border: #e6e8e3;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }

        body {
            margin: 0;
            overflow-x: hidden;
            background: #fafbf9;
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
        }

        .container {
            max-width: 1200px;
        }

        .announcement {
            padding: 9px 12px;
            background: var(--ocean-dark);
            color: #e7ddc8;
            text-align: center;
            font-size: 10px;
            letter-spacing: 1.5px;
        }

        .announcement span {
            color: var(--gold-light);
        }

        /* Navigation */

        .main-navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0;
            border-bottom: 1px solid #e9ece9;
            background: rgba(255, 255, 255, 0.98);
        }

        .navbar-inner {
            min-height: 82px;
        }

        .resort-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--ocean);
        }

        .resort-brand:hover {
            color: var(--ocean-light);
        }

        .brand-symbol {
            display: flex;
            width: 43px;
            height: 43px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--gold);
            border-radius: 50%;
            color: #a17f43;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 23px;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            line-height: 1.3;
        }

        .brand-subtitle {
            margin-top: 3px;
            font-size: 9px;
            font-weight: 400;
            letter-spacing: 3px;
        }

        .navbar-nav {
            gap: 23px;
        }

        .navbar-nav .nav-link {
            padding: 10px 0 !important;
            color: #43575f;
            font-size: 12px;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #a17f43;
        }

        .nav-book {
            display: inline-block;
            padding: 13px 19px;
            background: var(--ocean);
            color: white;
            font-size: 11px;
            font-weight: 700;
        }

        .nav-book:hover {
            background: var(--ocean-light);
            color: white;
        }

        /* Page banner */

        .page-banner {
            position: relative;
            display: flex;
            min-height: 340px;
            align-items: center;
            background:
                linear-gradient(
                    90deg,
                    rgba(6, 35, 47, 0.84),
                    rgba(6, 35, 47, 0.38)
                ),
                url("../images/azurabay/653.jpg")
                center 55% / cover no-repeat,
                linear-gradient(135deg, #164e60, #397d83);
            color: white;
        }

        .banner-content {
            padding-top: 55px;
            padding-bottom: 80px;
        }

        .breadcrumb-line {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            color: #d4e0e1;
            font-size: 11px;
        }

        .breadcrumb-line a {
            color: var(--gold-light);
        }

        .banner-eyebrow {
            margin-bottom: 15px;
            color: var(--gold-light);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .page-banner h1 {
            margin-bottom: 18px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(40px, 5vw, 61px);
            font-weight: 400;
        }

        .page-banner p {
            max-width: 600px;
            margin: 0;
            color: #e2ebed;
            font-size: 14px;
            line-height: 1.9;
        }

        /* Search form */

        .search-wrap {
            position: relative;
            z-index: 3;
            margin-top: -53px;
        }

        .search-panel {
            padding: 26px 28px;
            border-top: 3px solid var(--gold);
            background: white;
            box-shadow: 0 12px 36px rgba(9, 43, 55, 0.11);
        }

        .search-heading {
            margin-bottom: 19px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 22px;
        }

        .field-label {
            display: block;
            margin-bottom: 9px;
            color: #687880;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .field-control {
            width: 100%;
            min-height: 47px;
            padding: 10px 12px;
            border: 1px solid #dfe5e4;
            border-radius: 2px;
            background: white;
            color: var(--ocean);
            font-size: 12px;
        }

        .field-control:focus {
            border-color: var(--gold);
            outline: 2px solid rgba(200, 166, 106, 0.15);
        }

        .search-button {
            width: 100%;
            min-height: 47px;
            padding: 12px;
            border: 0;
            border-radius: 2px;
            background: var(--ocean);
            color: white;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: background 0.2s ease;
        }

        .search-button:hover {
            background: var(--ocean-light);
        }

        .search-note {
            margin: 17px 0 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        /* Search summary */

        .summary-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 27px;
            padding-bottom: 21px;
            border-bottom: 1px solid var(--border);
        }

        .summary-bar h2 {
            margin: 0 0 7px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 28px;
            font-weight: 400;
        }

        .summary-text {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .demo-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 13px;
            border: 1px solid #eadbb9;
            background: #fffaf0;
            color: #806333;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .demo-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
        }

        /* Room listing layout */

        .listing-section {
            padding: 63px 0 85px;
        }

        .listing-layout {
            display: grid;
            grid-template-columns: 245px minmax(0, 1fr);
            gap: 30px;
            align-items: start;
        }

        /* Filters */

        .filter-panel {
            padding: 24px 21px;
            border: 1px solid var(--border);
            background: white;
        }

        .filter-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 22px;
        }

        .filter-reset {
            border: 0;
            background: none;
            color: #a17f43;
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 10px;
            cursor: pointer;
        }

        .filter-group {
            margin-bottom: 24px;
        }

        .filter-group:last-child {
            margin-bottom: 0;
        }

        .filter-heading {
            margin-bottom: 15px;
            color: var(--ocean);
            font-size: 12px;
            font-weight: 700;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 13px;
            color: #64747c;
            font-size: 12px;
            cursor: pointer;
        }

        .filter-option:last-child {
            margin-bottom: 0;
        }

        .filter-option input {
            width: 15px;
            height: 15px;
            accent-color: var(--ocean);
            cursor: pointer;
        }

        .price-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            color: #64747c;
            font-size: 11px;
        }

        .price-slider {
            width: 100%;
            accent-color: var(--ocean);
            cursor: pointer;
        }

        .price-help {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 10px;
            line-height: 1.7;
        }

        /* Listing toolbar */

        .listing-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 21px;
        }

        .result-count {
            color: var(--muted);
            font-size: 12px;
        }

        .result-count strong {
            color: var(--ocean);
        }

        .sort-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sort-wrap label {
            color: #65757d;
            font-size: 11px;
        }

        .sort-select {
            min-width: 155px;
            min-height: 39px;
            padding: 8px 10px;
            border: 1px solid var(--border);
            background: white;
            color: var(--ocean);
            font-size: 11px;
        }

        /* Room card */

        .room-card {
            display: grid;
            grid-template-columns: minmax(0, 39%) minmax(0, 1fr);
            min-width: 0;
            margin-bottom: 24px;
            border: 1px solid var(--border);
            background: white;
            transition: box-shadow 0.25s ease;
        }

        .room-card:hover {
            box-shadow: 0 12px 32px rgba(9, 43, 55, 0.08);
        }

        .room-photo {
            position: relative;
            min-height: 310px;
            overflow: hidden;
            background: linear-gradient(135deg, #c8ded8, #7aa7a8);
        }

        .room-photo img {
            width: 100%;
            height: 100%;
            min-height: 310px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .room-card:hover .room-photo img {
            transform: scale(1.04);
        }

        .room-badge {
            position: absolute;
            top: 15px;
            left: 14px;
            padding: 8px 10px;
            background: rgba(9, 43, 55, 0.88);
            color: white;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .room-body {
            display: flex;
            min-width: 0;
            flex-direction: column;
            padding: 24px 24px 20px;
        }

        .room-category {
            margin-bottom: 10px;
            color: #a17f43;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.7px;
            text-transform: uppercase;
        }

        .room-title {
            margin-bottom: 12px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 25px;
            font-weight: 400;
            line-height: 1.3;
        }

        .room-description {
            margin-bottom: 17px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.9;
        }

        .room-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 18px;
            margin-bottom: 18px;
            color: #5e7078;
            font-size: 10px;
        }

        .room-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .amenity-list {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 20px;
        }

        .amenity {
            padding: 6px 8px;
            border: 1px solid #e7ece8;
            background: #f8faf8;
            color: #64747b;
            font-size: 9px;
        }

        .room-bottom {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            margin-top: auto;
            padding-top: 17px;
            border-top: 1px solid var(--border);
        }

        .room-price {
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 29px;
            line-height: 1.2;
        }

        .room-price small {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 10px;
        }

        .room-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
        }

        .btn-details,
        .btn-book {
            display: inline-flex;
            min-height: 40px;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border: 1px solid var(--ocean);
            font-size: 10px;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .btn-details {
            background: white;
            color: var(--ocean);
        }

        .btn-details:hover {
            background: var(--ocean);
            color: white;
        }

        .btn-book {
            background: var(--ocean);
            color: white;
        }

        .btn-book:hover {
            border-color: var(--ocean-light);
            background: var(--ocean-light);
            color: white;
        }

        .room-card[hidden] {
            display: none !important;
        }

        /* Empty results */

        .empty-results {
            display: none;
            padding: 45px 24px;
            border: 1px solid var(--border);
            background: white;
            text-align: center;
        }

        .empty-results.show {
            display: block;
        }

        .empty-symbol {
            margin-bottom: 14px;
            color: var(--gold);
            font-size: 36px;
        }

        .empty-results h3 {
            margin-bottom: 10px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 25px;
            font-weight: 400;
        }

        .empty-results p {
            margin-bottom: 20px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        /* Information box */

        .demo-notice {
            margin-top: 25px;
            padding: 17px 18px;
            border-left: 3px solid var(--gold);
            background: #fffaf0;
            color: #796746;
            font-size: 11px;
            line-height: 1.9;
        }

        /* Benefits strip */

        .benefits-strip {
            padding: 32px 0;
            border-top: 1px solid #e4e9e6;
            border-bottom: 1px solid #e4e9e6;
            background: var(--sand);
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: 13px;
            height: 100%;
        }

        .benefit-icon {
            display: flex;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border: 1px solid #d7c69f;
            color: #9b793e;
            font-size: 20px;
        }

        .benefit-item strong {
            display: block;
            margin-bottom: 5px;
            color: var(--ocean);
            font-size: 12px;
        }

        .benefit-item span {
            color: var(--muted);
            font-size: 10px;
            line-height: 1.7;
        }

        /* Footer */

        .main-footer {
            padding: 53px 0 0;
            background: var(--ocean-dark);
            color: #c1d0d3;
        }

        .footer-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            color: white;
        }

        .footer-brand .brand-symbol {
            color: var(--gold-light);
        }

        .footer-description {
            max-width: 340px;
            color: #b5c6ca;
            font-size: 12px;
            line-height: 1.9;
        }

        .footer-heading {
            margin-bottom: 19px;
            color: white;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 18px;
            font-weight: 400;
        }

        .footer-links {
            padding: 0;
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #bdcbce;
            font-size: 12px;
        }

        .footer-links a:hover {
            color: var(--gold-light);
        }

        .footer-bottom {
            margin-top: 35px;
            padding: 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            color: #a7b8bc;
            font-size: 10px;
            line-height: 1.8;
        }

        .footer-bottom a {
            color: var(--gold-light);
        }

        /* Responsive */

        @media (max-width: 991px) {
            .navbar-inner {
                min-height: 73px;
            }

            .navbar-nav {
                gap: 4px;
                padding: 12px 0;
            }

            .nav-book {
                margin-bottom: 14px;
                text-align: center;
            }

            .listing-layout {
                grid-template-columns: 210px minmax(0, 1fr);
                gap: 20px;
            }

            .room-card {
                grid-template-columns: minmax(0, 1fr);
            }

            .room-photo {
                min-height: 260px;
                height: 260px;
            }

            .room-photo img {
                min-height: 260px;
            }

            .room-body {
                padding: 23px;
            }
        }

        @media (max-width: 767px) {
            .announcement {
                font-size: 9px;
                letter-spacing: 0.8px;
            }

            .page-banner {
                min-height: 320px;
                background-position: center;
            }

            .banner-content {
                padding-top: 45px;
                padding-bottom: 80px;
            }

            .search-wrap {
                margin-top: -48px;
            }

            .search-panel {
                padding: 22px 19px;
            }

            .listing-section {
                padding: 45px 0 60px;
            }

            .listing-layout {
                grid-template-columns: minmax(0, 1fr);
                gap: 25px;
            }

            .filter-panel {
                padding: 21px;
            }

            .filter-title {
                margin-bottom: 18px;
            }

            .filter-group {
                margin-bottom: 19px;
            }

            .summary-bar h2 {
                font-size: 25px;
            }

            .room-card {
                grid-template-columns: minmax(0, 1fr);
            }

            .room-photo {
                height: 245px;
                min-height: 245px;
            }

            .room-photo img {
                min-height: 245px;
            }

            .room-title {
                font-size: 23px;
            }

            .room-body {
                padding: 20px;
            }

            .room-bottom {
                align-items: flex-start;
            }

            .room-actions {
                width: 100%;
            }

            .room-actions a {
                flex: 1;
            }

            .sort-wrap {
                width: 100%;
                justify-content: space-between;
            }

            .sort-select {
                flex: 1;
                max-width: 220px;
            }

            .benefit-item {
                align-items: flex-start;
            }

            .footer-bottom .text-md-end {
                text-align: left !important;
            }
        }

        @media (max-width: 380px) {
            .brand-name {
                font-size: 13px;
            }

            .brand-subtitle {
                letter-spacing: 2px;
            }

            .room-actions {
                flex-direction: column;
            }

            .room-actions a {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>

<!-- Announcement -->
<div class="announcement">
    DISCOVER YOUR NEXT COASTAL ESCAPE
    <span>&nbsp; | &nbsp;</span>
    AZURE BAY RESORT
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg main-navbar">
    <div class="container navbar-inner">

        <a class="resort-brand" href="index.php">
            <span class="brand-symbol" aria-hidden="true">A</span>

            <span>
                <span class="brand-name d-block">AZURE BAY</span>
                <span class="brand-subtitle d-block">
                    LUXURY RESORT
                </span>
            </span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavigation"
                aria-controls="mainNavigation"
                aria-expanded="false"
                aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse"
             id="mainNavigation">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active"
                       aria-current="page"
                       href="rooms.php">Rooms & Suites</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="index.php#experiences">
                        Experiences
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="index.php#reviews">
                        Guest Reviews
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="contact.php">Contact</a>
                </li>

            </ul>

            <a href="#roomListings" class="nav-book ms-lg-4">
                EXPLORE ROOMS
            </a>

        </div>
    </div>
</nav>

<main>

    <!-- Page Banner -->
    <section class="page-banner">

        <div class="container">
            <div class="banner-content">

                <div class="breadcrumb-line">
                    <a href="index.php">Home</a>
                    <span>/</span>
                    <span>Rooms & Suites</span>
                </div>

                <div class="banner-eyebrow">
                    Your Perfect Coastal Retreat
                </div>

                <h1>Rooms & Suites</h1>

                <p>
                    Discover a collection of thoughtfully
                    designed rooms, spacious suites, and
                    exclusive villas. Find your ideal
                    accommodation for a memorable beachfront
                    escape.
                </p>

            </div>
        </div>

    </section>

    <!-- Booking Search -->
    <section class="search-wrap" id="availabilitySearch">

        <div class="container">

            <div class="search-panel">

                <h2 class="search-heading">
                    Find Your Perfect Stay
                </h2>

                <form action="rooms.php"
                      method="GET"
                      id="roomSearchForm">

                    <div class="row g-3 align-items-end">

                        <div class="col-lg-3 col-md-6">
                            <label class="field-label"
                                   for="check_in">
                                Check-in
                            </label>

                            <input
                                type="date"
                                class="field-control"
                                id="check_in"
                                name="check_in"
                                min="<?php echo e($today); ?>"
                                value="<?php echo e($checkIn); ?>"
                                required>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="field-label"
                                   for="check_out">
                                Check-out
                            </label>

                            <input
                                type="date"
                                class="field-control"
                                id="check_out"
                                name="check_out"
                                min="<?php echo e($tomorrow); ?>"
                                value="<?php echo e($checkOut); ?>"
                                required>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="field-label"
                                   for="guests">
                                Number of Guests
                            </label>

                            <select class="field-control"
                                    id="guests"
                                    name="guests"
                                    required>

                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option
                                        value="<?php echo $i; ?>"
                                        <?php echo $guests === $i
                                            ? 'selected'
                                            : ''; ?>>

                                        <?php echo $i; ?>
                                        <?php echo $i === 1
                                            ? 'Guest'
                                            : 'Guests'; ?>

                                    </option>
                                <?php endfor; ?>

                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <button type="submit"
                                    class="search-button">
                                SEARCH ROOMS &nbsp; →
                            </button>
                        </div>

                    </div>

                    <p class="search-note">
                        Selected dates:
                        <strong><?php echo e($checkInDisplay); ?></strong>
                        to
                        <strong><?php echo e($checkOutDisplay); ?></strong>.
                        Your stay is
                        <strong>
                            <?php echo $nights; ?>
                            <?php echo $nights === 1
                                ? 'night'
                                : 'nights'; ?>
                        </strong>.
                    </p>

                </form>

            </div>

        </div>

    </section>

    <!-- Rooms and Filters -->
    <section class="listing-section" id="roomListings">

        <div class="container">

            <div class="summary-bar">

                <div>
                    <h2>Discover Your Room</h2>

                    <p class="summary-text">
                        Browse our collection and choose
                        the accommodation that suits your stay.
                    </p>
                </div>

                <div class="demo-label">
                    <span class="demo-dot"></span>
                    DEMONSTRATION ROOM DATA
                </div>

            </div>

            <div class="listing-layout">

                <!-- Filter Sidebar -->
                <aside class="filter-panel"
                       aria-label="Room filters">

                    <div class="filter-title">
                        <span>Refine Your Search</span>

                        <button type="button"
                                class="filter-reset"
                                id="resetFilters">
                            RESET
                        </button>
                    </div>

                    <!-- Room type -->
                    <div class="filter-group">

                        <h3 class="filter-heading">
                            Accommodation Type
                        </h3>

                        <?php foreach ($roomTypes as $index => $type): ?>

                            <label class="filter-option">

                                <input
                                    type="checkbox"
                                    class="type-filter"
                                    value="<?php echo e($type); ?>"
                                    id="type<?php echo $index; ?>">

                                <span><?php echo e($type); ?></span>

                            </label>

                        <?php endforeach; ?>

                    </div>

                    <!-- Capacity -->
                    <div class="filter-group">

                        <h3 class="filter-heading">
                            Guest Capacity
                        </h3>

                        <label class="filter-option">

                            <input type="radio"
                                   name="capacity"
                                   class="capacity-filter"
                                   value="0"
                                   checked>

                            <span>All capacities</span>

                        </label>

                        <label class="filter-option">

                            <input type="radio"
                                   name="capacity"
                                   class="capacity-filter"
                                   value="2">

                            <span>Up to 2 guests</span>

                        </label>

                        <label class="filter-option">

                            <input type="radio"
                                   name="capacity"
                                   class="capacity-filter"
                                   value="3">

                            <span>Up to 3 guests</span>

                        </label>

                        <label class="filter-option">

                            <input type="radio"
                                   name="capacity"
                                   class="capacity-filter"
                                   value="4">

                            <span>Up to 4 guests</span>

                        </label>

                        <label class="filter-option">

                            <input type="radio"
                                   name="capacity"
                                   class="capacity-filter"
                                   value="5">

                            <span>Up to 5 guests</span>

                        </label>

                        <label class="filter-option">

                            <input type="radio"
                                   name="capacity"
                                   class="capacity-filter"
                                   value="6">

                            <span>Up to 6 guests</span>

                        </label>

                    </div>

                    <!-- Price -->
                    <div class="filter-group">

                        <h3 class="filter-heading">
                            Maximum Price per Night
                        </h3>

                        <div class="price-display">
                            <span>Up to</span>

                            <strong id="priceValue">$500</strong>
                        </div>

                        <input
                            type="range"
                            class="price-slider"
                            id="priceFilter"
                            min="100"
                            max="500"
                            step="10"
                            value="500"
                            aria-label="Maximum nightly price">

                        <div class="price-display mt-2">
                            <span>$100</span>
                            <span>$500</span>
                        </div>

                        <p class="price-help">
                            Adjust the slider to filter rooms
                            by their illustrative nightly price.
                        </p>

                    </div>

                </aside>

                <!-- Results -->
                <div class="room-results">

                    <div class="listing-toolbar">

                        <div class="result-count"
                             aria-live="polite">

                            Showing
                            <strong id="visibleCount">
                                <?php echo $roomCount; ?>
                            </strong>
                            of
                            <strong><?php echo $roomCount; ?></strong>
                            rooms suitable for
                            <strong><?php echo $guests; ?></strong>
                            <?php echo $guests === 1
                                ? 'guest'
                                : 'guests'; ?>.

                        </div>

                        <div class="sort-wrap">

                            <label for="sortRooms">
                                Sort by
                            </label>

                            <select class="sort-select"
                                    id="sortRooms">

                                <option value="recommended">
                                    Recommended
                                </option>

                                <option value="price-low">
                                    Price: Low to High
                                </option>

                                <option value="price-high">
                                    Price: High to Low
                                </option>

                                <option value="capacity">
                                    Guest Capacity
                                </option>

                            </select>

                        </div>

                    </div>

                    <div id="roomList">

                        <?php foreach ($matchingRooms as $room): ?>

                            <article
                                class="room-card"
                                data-id="<?php echo $room['id']; ?>"
                                data-type="<?php echo e($room['type']); ?>"
                                data-price="<?php echo $room['price']; ?>"
                                data-guests="<?php echo $room['guests']; ?>">

                                <div class="room-photo">

                                    <img
                                        src="<?php echo e($room['image']); ?>"
                                        alt="<?php echo e($room['name']); ?>"
                                        loading="lazy"
                                        onerror="this.style.display='none';">

                                    <?php if ($room['badge'] !== ''): ?>

                                        <span class="room-badge">
                                            <?php echo e($room['badge']); ?>
                                        </span>

                                    <?php endif; ?>

                                </div>

                                <div class="room-body">

                                    <div class="room-category">
                                        <?php echo e($room['category']); ?>
                                    </div>

                                    <h3 class="room-title">
                                        <?php echo e($room['name']); ?>
                                    </h3>

                                    <p class="room-description">
                                        <?php echo e($room['description']); ?>
                                    </p>

                                    <div class="room-meta">

                                        <span>
                                            ◇
                                            <?php echo e($room['size']); ?>
                                        </span>

                                        <span>
                                            ♙
                                            Up to
                                            <?php echo e($room['guests']); ?>
                                            guests
                                        </span>

                                        <span>
                                            ♧
                                            <?php echo e($room['bed']); ?>
                                        </span>

                                    </div>

                                    <div class="amenity-list">

                                        <?php foreach (
                                            $room['amenities'] as $amenity
                                        ): ?>

                                            <span class="amenity">
                                                <?php echo e($amenity); ?>
                                            </span>

                                        <?php endforeach; ?>

                                    </div>

                                    <div class="room-bottom">

                                        <div class="room-price">

                                            $<?php echo number_format(
                                                $room['price']
                                            ); ?>

                                            <small>
                                                Per room / night
                                            </small>

                                        </div>

                                        <div class="room-actions">

                                            <a
                                                href="room_details.php?id=<?php
                                                    echo $room['id'];
                                                ?>&amp;check_in=<?php
                                                    echo e($checkIn);
                                                ?>&amp;check_out=<?php
                                                    echo e($checkOut);
                                                ?>&amp;guests=<?php
                                                    echo $guests;
                                                ?>"
                                                class="btn-details">

                                                VIEW DETAILS

                                            </a>

                                            <a
                                                href="booking.php?room_id=<?php
                                                    echo $room['id'];
                                                ?>&amp;check_in=<?php
                                                    echo e($checkIn);
                                                ?>&amp;check_out=<?php
                                                    echo e($checkOut);
                                                ?>&amp;guests=<?php
                                                    echo $guests;
                                                ?>"
                                                class="btn-book">

                                                BOOK THIS ROOM →

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                    <!-- Empty results -->
                    <div class="empty-results" id="emptyResults">

                        <div class="empty-symbol" aria-hidden="true">
                            ◇
                        </div>

                        <h3>No Matching Rooms</h3>

                        <p>
                            No rooms match your current filters.
                            Try another room type or increase
                            your maximum nightly price.
                        </p>

                        <button type="button"
                                class="btn-book"
                                id="emptyReset">

                            CLEAR FILTERS

                        </button>

                    </div>

                    <!-- Demonstration Notice -->
                    <div class="demo-notice">

                        <strong>About this demonstration:</strong>
                        Room descriptions, prices, and sample
                        capacity data are illustrative.
                        This version filters sample room records
                        but does not query a live reservation
                        database or verify date-based availability.
                        Final availability and pricing would be
                        confirmed by the booking workflow.

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Benefits -->
    <section class="benefits-strip">

        <div class="container">

            <div class="row g-4">

                <div class="col-lg-4 col-md-6">

                    <div class="benefit-item">

                        <div class="benefit-icon"
                             aria-hidden="true">◇</div>

                        <div>
                            <strong>Thoughtfully Selected Rooms</strong>

                            <span>
                                Explore accommodation options
                                for different travel needs.
                            </span>
                        </div>

                    </div>

                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="benefit-item">

                        <div class="benefit-icon"
                             aria-hidden="true">◷</div>

                        <div>
                            <strong>Convenient Booking Journey</strong>

                            <span>
                                Carry your preferred dates
                                into the reservation workflow.
                            </span>
                        </div>

                    </div>

                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="benefit-item">

                        <div class="benefit-icon"
                             aria-hidden="true">♡</div>

                        <div>
                            <strong>Comfort Comes First</strong>

                            <span>
                                Find the room that best suits
                                your next coastal escape.
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<!-- Footer -->
<footer class="main-footer">

    <div class="container">

        <div class="row g-5">

            <div class="col-lg-5 col-md-6">

                <a href="index.php" class="footer-brand">

                    <span class="brand-symbol"
                          aria-hidden="true">A</span>

                    <span>
                        <span class="brand-name d-block">
                            AZURE BAY
                        </span>

                        <span class="brand-subtitle d-block">
                            LUXURY RESORT
                        </span>
                    </span>

                </a>

                <p class="footer-description">
                    Discover elegant accommodation, memorable
                    coastal experiences, and a convenient
                    resort booking journey with Azure Bay Resort.
                </p>

            </div>

            <div class="col-lg-2 col-6">

                <h3 class="footer-heading">Explore</h3>

                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="rooms.php">Rooms & Suites</a></li>
                    <li>
                        <a href="index.php#experiences">
                            Experiences
                        </a>
                    </li>
                    <li>
                        <a href="index.php#reviews">
                            Guest Reviews
                        </a>
                    </li>
                </ul>

            </div>

            <div class="col-lg-2 col-6">

                <h3 class="footer-heading">Guest Services</h3>

                <ul class="footer-links">
                    <li><a href="my_bookings.php">My Bookings</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="reviews.php">Write a Review</a></li>
                    <li><a href="index.php#booking">Plan Your Stay</a></li>
                </ul>

            </div>

            <div class="col-lg-3 col-md-6">

                <h3 class="footer-heading">Your Coastal Escape</h3>

                <p class="footer-description">
                    Explore our collection of rooms and suites
                    and start planning your next getaway.
                </p>

                <a href="#availabilitySearch"
                   class="btn-book">

                    FIND YOUR ROOM →

                </a>

            </div>

        </div>

        <div class="footer-bottom">

            <div class="row g-3 align-items-center">

                <div class="col-md-6">
                    &copy; <?php echo date('Y'); ?>
                    Azure Bay Resort. All rights reserved.
                </div>

                <div class="col-md-6 text-md-end">
                    Demonstration Project by
                    <a href="azurebay_demo.php">Codvanta</a>
                </div>

            </div>

        </div>

    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    const searchForm = document.getElementById('roomSearchForm');

    const roomList = document.getElementById('roomList');
    const emptyResults = document.getElementById('emptyResults');
    const visibleCount = document.getElementById('visibleCount');

    const typeFilters = Array.from(
        document.querySelectorAll('.type-filter')
    );

    const capacityFilters = Array.from(
        document.querySelectorAll('.capacity-filter')
    );

    const priceFilter = document.getElementById('priceFilter');
    const priceValue = document.getElementById('priceValue');
    const sortRooms = document.getElementById('sortRooms');

    const resetFilters = document.getElementById('resetFilters');
    const emptyReset = document.getElementById('emptyReset');

    const cards = Array.from(
        document.querySelectorAll('.room-card')
    );

    function formatLocalDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    function updateCheckoutMinimum() {
        if (!checkIn.value) {
            return;
        }

        // Use local date arithmetic instead of UTC date conversion.
        const nextDate = new Date(checkIn.value + 'T12:00:00');
        nextDate.setDate(nextDate.getDate() + 1);

        const minimumCheckout = formatLocalDate(nextDate);

        checkOut.min = minimumCheckout;

        if (!checkOut.value ||
            checkOut.value < minimumCheckout) {

            checkOut.value = minimumCheckout;
        }
    }

    checkIn.addEventListener('change', updateCheckoutMinimum);

    searchForm.addEventListener('submit', function (event) {

        if (!checkIn.value || !checkOut.value) {
            event.preventDefault();

            alert('Please select both check-in and check-out dates.');
            return;
        }

        if (checkIn.value < checkIn.min) {
            event.preventDefault();

            alert('Please select a valid check-in date.');
            checkIn.focus();
            return;
        }

        if (checkOut.value <= checkIn.value) {
            event.preventDefault();

            alert('Check-out must be at least one day after check-in.');
            checkOut.focus();
        }
    });

    function getSelectedCapacity() {
        const selected = capacityFilters.find(function (filter) {
            return filter.checked;
        });

        return selected ? Number(selected.value) : 0;
    }

    function applyFilters() {

        const selectedTypes = typeFilters
            .filter(function (filter) {
                return filter.checked;
            })
            .map(function (filter) {
                return filter.value;
            });

        const selectedCapacity = getSelectedCapacity();
        const maximumPrice = Number(priceFilter.value);

        priceValue.textContent = '$' + maximumPrice;

        const sortMode = sortRooms.value;

        // Sort the cards using their sample data.
        const sortedCards = [...cards].sort(function (a, b) {

            const priceA = Number(a.dataset.price);
            const priceB = Number(b.dataset.price);

            const guestsA = Number(a.dataset.guests);
            const guestsB = Number(b.dataset.guests);

            if (sortMode === 'price-low') {
                return priceA - priceB;
            }

            if (sortMode === 'price-high') {
                return priceB - priceA;
            }

            if (sortMode === 'capacity') {
                return guestsA - guestsB || priceA - priceB;
            }

            // Recommended: retain the original order.
            return Number(a.dataset.id) - Number(b.dataset.id);
        });

        sortedCards.forEach(function (card) {

            const typeMatches =
                selectedTypes.length === 0 ||
                selectedTypes.includes(card.dataset.type);

            const capacityMatches =
                selectedCapacity === 0 ||
                Number(card.dataset.guests) >= selectedCapacity;

            const priceMatches =
                Number(card.dataset.price) <= maximumPrice;

            // Guest capacity is a minimum required capacity.
            // For example, a 4-guest room can accommodate 2 guests.
            // The URL guest search has already been applied in PHP.
            const matches =
                typeMatches && capacityMatches && priceMatches;

            card.hidden = !matches;

            roomList.appendChild(card);
        });

        const count = sortedCards.filter(function (card) {
            return !card.hidden;
        }).length;

        visibleCount.textContent = count;
        emptyResults.classList.toggle('show', count === 0);
    }

    function resetAllFilters() {

        typeFilters.forEach(function (filter) {
            filter.checked = false;
        });

        capacityFilters.forEach(function (filter) {
            filter.checked = filter.value === '0';
        });

        priceFilter.value = '500';
        sortRooms.value = 'recommended';

        applyFilters();
    }

    typeFilters.forEach(function (filter) {
        filter.addEventListener('change', applyFilters);
    });

    capacityFilters.forEach(function (filter) {
        filter.addEventListener('change', applyFilters);
    });

    priceFilter.addEventListener('input', applyFilters);
    sortRooms.addEventListener('change', applyFilters);

    resetFilters.addEventListener('click', resetAllFilters);
    emptyReset.addEventListener('click', resetAllFilters);

    updateCheckoutMinimum();
    applyFilters();

});
</script>

</body>
</html>