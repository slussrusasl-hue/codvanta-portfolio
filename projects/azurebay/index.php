
<?php
// Azure Bay Resort - Customer Homepage

$today = date('Y-m-d');

$featuredRooms = [
    [
        'name' => 'Deluxe Ocean View',
        'category' => 'OCEAN VIEW ROOM',
        'image' => '../images/azurabay/9041.jpg',
        'description' => 'Wake up to breathtaking ocean views in a beautifully appointed room designed for relaxation.',
        'price' => 180,
        'guests' => 2,
        'size' => '38 m²',
        'bed' => 'King Bed'
    ],
    [
        'name' => 'Premium Beach Suite',
        'category' => 'PREMIUM SUITE',
        'image' => '../images/azurabay/6669.jpg',
        'description' => 'Enjoy spacious interiors, refined comforts, and a private balcony overlooking the tropical coastline.',
        'price' => 290,
        'guests' => 3,
        'size' => '56 m²',
        'bed' => 'King Bed'
    ],
    [
        'name' => 'Azure Presidential Villa',
        'category' => 'EXCLUSIVE VILLA',
        'image' => '../images/azurabay/651.jpg',
        'description' => 'Experience elevated luxury with generous living spaces and an exceptional private retreat.',
        'price' => 450,
        'guests' => 4,
        'size' => '95 m²',
        'bed' => 'King Bed'
    ]
];

$experiences = [
    [
        'title' => 'Oceanfront Dining',
        'description' => 'Discover fresh local ingredients, memorable flavours, and beautiful oceanfront dining.',
        'image' => '../images/azurabay/3019.jpg',
        'number' => '01'
    ],
    [
        'title' => 'Infinity Pool',
        'description' => 'Unwind beside crystal-clear waters with uninterrupted views of the Indian Ocean.',
        'image' => '../images/azurabay/1064.jpg',
        'number' => '02'
    ],
    [
        'title' => 'Beachside Serenity',
        'description' => 'Enjoy golden sands, tropical sunsets, and peaceful moments beside the sea.',
        'image' => '../images/azurabay/4501.jpg',
        'number' => '03'
    ]
];

$reviews = [
    [
        'name' => 'Sophia M.',
        'location' => 'London, United Kingdom',
        'rating' => 5,
        'text' => 'A beautiful resort experience. The ocean views, elegant rooms, and peaceful atmosphere made our holiday unforgettable.'
    ],
    [
        'name' => 'Daniel R.',
        'location' => 'Melbourne, Australia',
        'rating' => 5,
        'text' => 'The perfect place to unwind. Everything felt thoughtfully designed, from the accommodation to the beachfront setting.'
    ],
    [
        'name' => 'Amelia K.',
        'location' => 'Singapore',
        'rating' => 5,
        'text' => 'A wonderful tropical escape with a lovely atmosphere. We would happily return for another relaxing holiday.'
    ]
];

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="Discover Azure Bay Resort, a luxury beachfront escape featuring elegant rooms, ocean views, exceptional dining, and an effortless resort booking experience.">

    <meta name="theme-color" content="#103c4b">

    <title>Azure Bay Resort | A Luxury Beachfront Escape</title>

    <!-- Local Bootstrap 5 -->
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
            --white: #ffffff;
            --border: #e7e6e0;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 85px;
        }

        body {
            margin: 0;
            background: var(--white);
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            overflow-x: hidden;
        }

        img {
            max-width: 100%;
        }

        a {
            text-decoration: none;
        }

        button,
        input,
        select {
            font: inherit;
        }

        .container {
            max-width: 1200px;
        }

        .section-padding {
            padding: 88px 0;
        }

        .section-eyebrow {
            margin-bottom: 13px;
            color: #a17f43;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .section-title {
            margin-bottom: 18px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(31px, 4vw, 45px);
            font-weight: 400;
            line-height: 1.2;
        }

        .section-description {
            max-width: 660px;
            margin: 0 auto;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.9;
        }

        .btn-luxury {
            display: inline-flex;
            min-height: 49px;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 13px 24px;
            border: 1px solid var(--gold);
            border-radius: 3px;
            background: var(--gold);
            color: var(--ocean-dark);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: 0.25s ease;
        }

        .btn-luxury:hover {
            border-color: var(--gold-light);
            background: var(--gold-light);
            color: var(--ocean-dark);
            transform: translateY(-2px);
        }

        .btn-outline-luxury {
            display: inline-flex;
            min-height: 47px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 22px;
            border: 1px solid var(--ocean);
            border-radius: 3px;
            color: var(--ocean);
            font-size: 12px;
            font-weight: 700;
            transition: 0.25s ease;
        }

        .btn-outline-luxury:hover {
            background: var(--ocean);
            color: white;
        }

        /* Announcement bar */

        .announcement {
            padding: 9px 15px;
            background: var(--ocean-dark);
            color: #e8dfcc;
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
            border-bottom: 1px solid rgba(16, 60, 75, 0.08);
            background: rgba(255, 255, 255, 0.98);
        }

        .navbar-inner {
            min-height: 83px;
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
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--gold);
            border-radius: 50%;
            color: #a27f42;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 24px;
        }

        .brand-name {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 1px;
            line-height: 1.3;
        }

        .brand-subtitle {
            margin-top: 3px;
            font-size: 9px;
            font-weight: 400;
            letter-spacing: 4px;
        }

        .navbar-nav {
            gap: 24px;
        }

        .navbar-nav .nav-link {
            padding: 10px 0 !important;
            color: #42545b;
            font-size: 12px;
            transition: color 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #a17f43;
        }

        .nav-book {
            padding: 13px 20px;
            background: var(--ocean);
            color: white;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: background 0.2s ease;
        }

        .nav-book:hover {
            background: var(--ocean-light);
            color: white;
        }

        /* Hero */

        .hero {
            position: relative;
            display: flex;
            min-height: 610px;
            align-items: center;
            background:
                linear-gradient(
                    90deg,
                    rgba(6, 35, 47, 0.82) 0%,
                    rgba(6, 35, 47, 0.53) 48%,
                    rgba(6, 35, 47, 0.12) 100%
                ),
                url("../images/azurabay/azurabay_hero.jpg")
                center 90% / cover no-repeat,
                linear-gradient(135deg, #164e60, #397d83);
            color: white;
        }

        .hero-content {
            max-width: 770px;
            padding-top: 78px;
            padding-bottom: 140px;
        }

        .hero-kicker {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 23px;
            color: var(--gold-light);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .hero-kicker::before {
            width: 39px;
            height: 1px;
            background: var(--gold);
            content: "";
        }

        .hero h1 {
            margin-bottom: 24px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(43px, 6vw, 75px);
            font-weight: 400;
            line-height: 1.12;
        }

        .hero h1 em {
            color: var(--gold-light);
            font-weight: 400;
        }

        .hero-description {
            max-width: 530px;
            margin-bottom: 31px;
            color: #e4edef;
            font-size: 15px;
            line-height: 1.95;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 13px;
        }

        .hero-secondary {
            display: inline-flex;
            min-height: 49px;
            align-items: center;
            justify-content: center;
            padding: 13px 23px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            color: white;
            font-size: 12px;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .hero-secondary:hover {
            background: white;
            color: var(--ocean);
        }

        .hero-caption {
            position: absolute;
            right: 5%;
            bottom: 100px;
            color: #f6eddb;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Booking panel */

        .booking-section {
            position: relative;
            z-index: 5;
            margin-top: -66px;
        }

        .booking-panel {
            padding: 28px 30px 24px;
            border-top: 3px solid var(--gold);
            background: white;
            box-shadow: 0 15px 45px rgba(11, 43, 55, 0.12);
        }

        .booking-heading {
            margin-bottom: 22px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 23px;
        }

        .booking-label {
            display: block;
            margin-bottom: 10px;
            color: #687880;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .booking-control {
            width: 100%;
            min-height: 48px;
            padding: 10px 12px;
            border: 1px solid #dfe5e4;
            border-radius: 2px;
            background-color: white;
            color: var(--ocean);
            font-size: 13px;
        }

        .booking-control:focus {
            border-color: var(--gold);
            outline: 2px solid rgba(200, 166, 106, 0.16);
        }

        .booking-submit {
            width: 100%;
            min-height: 48px;
            border: 0;
            border-radius: 2px;
            background: var(--ocean);
            color: white;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.7px;
            transition: background 0.2s ease;
        }

        .booking-submit:hover {
            background: var(--ocean-light);
        }

        .booking-note {
            margin-top: 16px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        /* Intro */

        .intro-section {
            padding-top: 92px;
            background: var(--cream);
        }

        .intro-art {
            position: relative;
            min-height: 430px;
            padding: 0 32px 35px 0;
        }

        .intro-main-image {
            width: 100%;
            height: 590px;
            object-fit: cover;
            background: #d7e6e0;
        }

        .intro-small-image {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 46%;
            height: 190px;
            border: 8px solid var(--cream);
            object-fit: cover;
            background: #c5ded9;
        }

        .intro-stamp {
            position: absolute;
            top: 24px;
            left: -16px;
            padding: 16px 19px;
            background: var(--ocean);
            color: white;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 15px;
            line-height: 1.5;
        }

        .intro-text {
            color: var(--muted);
            font-size: 14px;
            line-height: 2;
        }

        .intro-points {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin: 27px 0 30px;
        }

        .intro-point {
            padding-left: 13px;
            border-left: 2px solid var(--gold);
        }

        .intro-point strong {
            display: block;
            margin-bottom: 5px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 17px;
            font-weight: 400;
        }

        .intro-point span {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        /* Featured rooms */

        .rooms-section {
            background: white;
        }

        .room-card {
            height: 100%;
            overflow: hidden;
            border: 1px solid var(--border);
            background: white;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(12, 44, 57, 0.09);
        }

        .room-image-wrap {
            position: relative;
            height: 250px;
            overflow: hidden;
            background: linear-gradient(135deg, #d2e4df, #80b4b5);
        }

        .room-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s ease;
        }

        .room-card:hover .room-image {
            transform: scale(1.045);
        }

        .room-category {
            position: absolute;
            bottom: 14px;
            left: 14px;
            padding: 8px 11px;
            background: rgba(9, 43, 55, 0.88);
            color: white;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.3px;
        }

        .room-body {
            padding: 23px 22px 21px;
        }

        .room-body h3 {
            margin-bottom: 12px;
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 23px;
            font-weight: 400;
        }

        .room-description {
            min-height: 73px;
            margin-bottom: 18px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.9;
        }

        .room-specs {
            display: flex;
            flex-wrap: wrap;
            gap: 13px;
            padding-bottom: 17px;
            border-bottom: 1px solid var(--border);
            color: #65767e;
            font-size: 10px;
        }

        .room-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-top: 17px;
        }

        .room-price {
            color: var(--ocean);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 25px;
        }

        .room-price small {
            display: block;
            margin-top: 2px;
            color: var(--muted);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 10px;
        }

        .room-link {
            color: #98753d;
            font-size: 11px;
            font-weight: 700;
        }

        .room-link:hover {
            color: var(--ocean);
        }

        .section-action {
            margin-top: 39px;
        }

        /* Resort experiences */

        .experience-section {
            background: var(--sand);
        }

        .experience-card {
            position: relative;
            height: 380px;
            overflow: hidden;
            background: linear-gradient(135deg, #164e60, #9bc2bb);
        }

        .experience-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .experience-card:hover img {
            transform: scale(1.06);
        }

        .experience-card::after {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(5, 31, 42, 0.91),
                rgba(5, 31, 42, 0.08) 72%
            );
            content: "";
        }

        .experience-content {
            position: absolute;
            z-index: 2;
            right: 22px;
            bottom: 25px;
            left: 22px;
            color: white;
        }

        .experience-number {
            display: block;
            margin-bottom: 10px;
            color: var(--gold-light);
            font-size: 10px;
            letter-spacing: 2px;
        }

        .experience-content h3 {
            margin-bottom: 10px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 25px;
            font-weight: 400;
        }

        .experience-content p {
            margin-bottom: 0;
            color: #e0e8e9;
            font-size: 12px;
            line-height: 1.8;
        }

        /* Feature banner */

        .feature-banner {
            position: relative;
            padding: 70px 0;
            overflow: hidden;
            background: var(--ocean);
            color: white;
        }

        .feature-banner::before,
        .feature-banner::after {
            position: absolute;
            width: 250px;
            height: 250px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            content: "";
        }

        .feature-banner::before {
            top: -130px;
            left: -60px;
        }

        .feature-banner::after {
            right: -80px;
            bottom: -160px;
            width: 340px;
            height: 340px;
        }

        .feature-banner .section-title {
            color: white;
        }

        .feature-banner .section-description {
            color: #c6d9dc;
        }

        .benefit {
            height: 100%;
            padding: 26px 18px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            text-align: center;
        }

        .benefit-icon {
            display: flex;
            width: 49px;
            height: 49px;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 1px solid rgba(233, 212, 167, 0.6);
            border-radius: 50%;
            color: var(--gold-light);
            font-size: 22px;
        }

        .benefit h3 {
            margin-bottom: 10px;
            color: white;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 18px;
            font-weight: 400;
        }

        .benefit p {
            margin: 0;
            color: #c5d4d7;
            font-size: 11px;
            line-height: 1.9;
        }

        /* Guest reviews */

        .reviews-section {
            background: var(--cream);
        }

        .review-card {
            height: 100%;
            padding: 28px 25px;
            border: 1px solid var(--border);
            background: white;
        }

        .review-stars {
            margin-bottom: 18px;
            color: #b58b42;
            font-size: 15px;
            letter-spacing: 3px;
        }

        .review-text {
            min-height: 112px;
            margin-bottom: 22px;
            color: #62737b;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 15px;
            font-style: italic;
            line-height: 1.9;
        }

        .review-author {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 17px;
            border-top: 1px solid var(--border);
        }

        .review-avatar {
            display: flex;
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e4eeeb;
            color: var(--ocean);
            font-size: 13px;
            font-weight: 700;
        }

        .review-author strong {
            display: block;
            margin-bottom: 4px;
            color: var(--ocean);
            font-size: 12px;
        }

        .review-author span {
            color: var(--muted);
            font-size: 10px;
        }

        /* Call to action */

        .cta-section {
            padding: 75px 0;
            background:
                linear-gradient(
                    90deg,
                    rgba(8, 41, 53, 0.91),
                    rgba(8, 41, 53, 0.71)
                ),
                url("assets/images/resort-beach.jpg")
                center 50% / cover no-repeat,
                #164e60;
            color: white;
            text-align: center;
        }

        .cta-section .section-title {
            color: white;
        }

        .cta-section .section-description {
            margin-bottom: 27px;
            color: #e0e9eb;
        }

        /* Contact */

        .contact-section {
            padding: 65px 0;
            background: white;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .contact-icon {
            display: flex;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border: 1px solid #e6d8bb;
            color: #a17f43;
            font-size: 18px;
        }

        .contact-item strong {
            display: block;
            margin-bottom: 6px;
            color: var(--ocean);
            font-size: 12px;
        }

        .contact-item p,
        .contact-item a {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .contact-item a:hover {
            color: #a17f43;
        }

        /* Footer */

        .main-footer {
            padding: 57px 0 0;
            background: var(--ocean-dark);
            color: #c1d0d3;
        }

        .footer-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 19px;
            color: white;
        }

        .footer-brand .brand-symbol {
            color: var(--gold-light);
        }

        .footer-brand .brand-name {
            color: white;
        }

        .footer-description {
            max-width: 330px;
            color: #b5c6ca;
            font-size: 12px;
            line-height: 2;
        }

        .footer-heading {
            margin-bottom: 21px;
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
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--gold-light);
        }

        .footer-bottom {
            margin-top: 42px;
            padding: 22px 0;
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
                min-height: 72px;
            }

            .navbar-nav {
                gap: 5px;
                padding: 15px 0;
            }

            .navbar-nav .nav-link {
                padding: 8px 0 !important;
            }

            .nav-book {
                display: inline-block;
                margin-bottom: 15px;
                text-align: center;
            }

            .hero {
                min-height: 550px;
            }

            .hero-content {
                padding-bottom: 125px;
            }

            .hero-caption {
                display: none;
            }

            .intro-art {
                margin-bottom: 25px;
            }

            .experience-card {
                height: 350px;
            }
        }

        @media (max-width: 767px) {
            .section-padding {
                padding: 62px 0;
            }

            .announcement {
                font-size: 9px;
                letter-spacing: 0.8px;
            }

            .brand-name {
                font-size: 15px;
            }

            .brand-symbol {
                width: 39px;
                height: 39px;
            }

            .hero {
                min-height: 510px;
                background-position: 57% center;
            }

            .hero-content {
                padding-top: 65px;
                padding-bottom: 115px;
            }

            .hero-description {
                font-size: 13px;
            }

            .booking-section {
                margin-top: -58px;
            }

            .booking-panel {
                padding: 24px 20px;
            }

            .intro-section {
                padding-top: 60px;
            }

            .intro-art {
                min-height: 340px;
                padding-right: 20px;
            }

            .intro-main-image {
                height: 315px;
            }

            .intro-small-image {
                height: 145px;
                width: 47%;
            }

            .intro-stamp {
                left: -4px;
                font-size: 12px;
            }

            .room-image-wrap {
                height: 235px;
            }

            .room-description {
                min-height: auto;
            }

            .experience-card {
                height: 330px;
            }

            .review-text {
                min-height: auto;
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
                letter-spacing: 2.5px;
            }

            .hero h1 {
                font-size: 39px;
            }

            .hero-buttons a {
                width: 100%;
            }

            .intro-points {
                grid-template-columns: 1fr;
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
    YOUR PRIVATE ESCAPE AWAITS
    <span> &nbsp; | &nbsp; </span>
    DISCOVER AZURE BAY RESORT
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg main-navbar">
    <div class="container navbar-inner">

        <a class="resort-brand" href="index.php"
           aria-label="Azure Bay Resort home">

            <span class="brand-symbol" aria-hidden="true">A</span>

            <span>
                <span class="brand-name d-block">
                    AZURE BAY
                </span>
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
                    <a class="nav-link" href="#home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">Our Story</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./rooms.php">Rooms & Suites</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#experiences">
                        Experiences
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./reviews.php">Guest Reviews</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./contact.php">Contact</a>
                </li>

            </ul>

            <a href="#booking"
               class="nav-book ms-lg-4">
                BOOK YOUR STAY
            </a>

        </div>
    </div>
</nav>

<main>

    <!-- Hero -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">

                <div class="hero-kicker">
                    A World of Tranquility
                </div>

                <h1>
                    Where the Ocean<br>
                    Meets <em>Elegance.</em>
                </h1>

                <p class="hero-description">
                    Escape to a world of coastal beauty, refined
                    comfort, and unforgettable moments. Discover
                    a beachfront sanctuary where every stay
                    becomes a beautiful memory.
                </p>

                <div class="hero-buttons">
                    <a href="#booking" class="btn-luxury">
                        RESERVE YOUR ESCAPE
                        <span aria-hidden="true">→</span>
                    </a>

                    <a href="#rooms" class="hero-secondary">
                        EXPLORE OUR ROOMS
                    </a>
                </div>

            </div>
        </div>

        <div class="hero-caption">
            Luxury · Nature · Serenity
        </div>
    </section>

    <!-- Booking Search -->
    <section class="booking-section" id="booking">
        <div class="container">

            <div class="booking-panel">

                <h2 class="booking-heading">
                    Plan Your Perfect Stay
                </h2>

                <form action="rooms.php"
                      method="GET"
                      id="bookingForm">

                    <div class="row g-3 align-items-end">

                        <div class="col-lg-3 col-md-6">
                            <label for="check_in"
                                   class="booking-label">
                                Check-in Date
                            </label>

                            <input type="date"
                                   class="booking-control"
                                   id="check_in"
                                   name="check_in"
                                   min="<?php echo e($today); ?>"
                                   value="<?php echo e($today); ?>"
                                   required>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="check_out"
                                   class="booking-label">
                                Check-out Date
                            </label>

                            <input type="date"
                                   class="booking-control"
                                   id="check_out"
                                   name="check_out"
                                   min="<?php echo e(date(
                                       'Y-m-d',
                                       strtotime('+1 day')
                                   )); ?>"
                                   value="<?php echo e(date(
                                       'Y-m-d',
                                       strtotime('+1 day')
                                   )); ?>"
                                   required>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="guests"
                                   class="booking-label">
                                Number of Guests
                            </label>

                            <select class="booking-control"
                                    id="guests"
                                    name="guests"
                                    required>

                                <option value="1">1 Guest</option>
                                <option value="2" selected>
                                    2 Guests
                                </option>
                                <option value="3">3 Guests</option>
                                <option value="4">4 Guests</option>
                                <option value="5">5 Guests</option>
                                <option value="6">6 Guests</option>

                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <button type="submit"
                                    class="booking-submit">
                                CHECK AVAILABILITY &nbsp; →
                            </button>
                        </div>

                    </div>

                    <p class="booking-note">
                        <span aria-hidden="true">ⓘ</span>
                        Choose your preferred dates to explore
                        available accommodation. Availability
                        will be confirmed on the booking pages.
                    </p>

                </form>

            </div>
        </div>
    </section>

    <!-- About the Resort -->
    <section class="intro-section section-padding"
             id="about">

        <div class="container">
            <div class="row g-5 align-items-center">

                <div class="col-lg-6">

                    <div class="intro-art">

                        <img
                            src="../images/azurabay/3479.jpg"
                            alt="Beautiful tropical resort surrounded
                                 by lush gardens"
                            class="intro-main-image"
                            loading="lazy"
                            onerror="this.style.display='none';">

                        <img
                            src="../images/azurabay/resort-about.jpg"
                            alt="Elegant resort lounge"
                            class="intro-small-image"
                            loading="lazy"
                            onerror="this.style.display='none';">

                        <div class="intro-stamp">
                            Your sanctuary<br>
                            by the sea
                        </div>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="section-eyebrow">
                        Welcome to Azure Bay
                    </div>

                    <h2 class="section-title">
                        An Extraordinary Escape,
                        Naturally.
                    </h2>

                    <p class="intro-text">
                        Azure Bay Resort is imagined as a
                        destination where the beauty of the
                        coastline meets thoughtful hospitality.
                        From sunlit rooms to tranquil beachfront
                        moments, every detail celebrates the
                        art of slowing down.
                    </p>

                    <p class="intro-text">
                        Whether you are planning a romantic
                        getaway, a family holiday, or a peaceful
                        escape from everyday life, discover a
                        stay designed around comfort, discovery,
                        and lasting memories.
                    </p>

                    <div class="intro-points">

                        <div class="intro-point">
                            <strong>Oceanfront Living</strong>
                            <span>
                                Beautiful coastal views and
                                peaceful surroundings.
                            </span>
                        </div>

                        <div class="intro-point">
                            <strong>Thoughtful Comfort</strong>
                            <span>
                                Elegant accommodation and
                                welcoming hospitality.
                            </span>
                        </div>

                        <div class="intro-point">
                            <strong>Memorable Dining</strong>
                            <span>
                                Inspiring flavours and
                                delightful dining experiences.
                            </span>
                        </div>

                        <div class="intro-point">
                            <strong>Personalized Stays</strong>
                            <span>
                                Options for couples, families,
                                and leisure travellers.
                            </span>
                        </div>

                    </div>

                    <a href="#experiences"
                       class="btn-outline-luxury">
                        DISCOVER OUR RESORT
                        <span aria-hidden="true">→</span>
                    </a>

                </div>

            </div>
        </div>
    </section>

    <!-- Featured Rooms -->
    <section class="rooms-section section-padding"
             id="rooms">

        <div class="container">

            <div class="text-center mb-5">

                <div class="section-eyebrow">
                    Your Perfect Retreat
                </div>

                <h2 class="section-title">
                    Rooms & Suites
                </h2>

                <p class="section-description">
                    Discover thoughtfully designed spaces,
                    soothing interiors, and refined comforts.
                    Find the accommodation that suits your
                    next coastal escape.
                </p>

            </div>

            <div class="row g-4">

                <?php foreach ($featuredRooms as $room): ?>

                    <div class="col-lg-4 col-md-6">

                        <article class="room-card">

                            <div class="room-image-wrap">

                                <img
                                    src="<?php echo e($room['image']); ?>"
                                    alt="<?php echo e($room['name']); ?>"
                                    class="room-image"
                                    loading="lazy"
                                    onerror="this.style.display='none';">

                                <span class="room-category">
                                    <?php echo e($room['category']); ?>
                                </span>

                            </div>

                            <div class="room-body">

                                <h3>
                                    <?php echo e($room['name']); ?>
                                </h3>

                                <p class="room-description">
                                    <?php echo e($room['description']); ?>
                                </p>

                                <div class="room-specs">
                                    <span>
                                        ◇ <?php echo e($room['size']); ?>
                                    </span>

                                    <span>
                                        ♙ Up to
                                        <?php echo e($room['guests']); ?>
                                        guests
                                    </span>

                                    <span>
                                        <?php echo e($room['bed']); ?>
                                    </span>
                                </div>

                                <div class="room-bottom">

                                    <div class="room-price">
                                        $<?php echo number_format(
                                            $room['price']
                                        ); ?>

                                        <small>
                                            Per night · Illustrative price
                                        </small>
                                    </div>

                                    <a
                                        href="rooms.php?guests=<?php
                                            echo e($room['guests']);
                                        ?>"
                                        class="room-link">

                                        EXPLORE ROOM →
                                    </a>

                                </div>

                            </div>

                        </article>

                    </div>

                <?php endforeach; ?>

            </div>

            <div class="text-center section-action">
                <a href="rooms.php"
                   class="btn-outline-luxury">
                    VIEW ALL ROOMS & SUITES
                    <span aria-hidden="true">→</span>
                </a>
            </div>

        </div>
    </section>

    <!-- Resort Experiences -->
    <section class="experience-section section-padding"
             id="experiences">

        <div class="container">

            <div class="text-center mb-5">

                <div class="section-eyebrow">
                    Beyond Your Room
                </div>

                <h2 class="section-title">
                    Moments Worth Remembering
                </h2>

                <p class="section-description">
                    Make every moment special with experiences
                    inspired by the ocean, local flavours, and
                    the beauty of a tropical escape.
                </p>

            </div>

            <div class="row g-4">

                <?php foreach ($experiences as $experience): ?>

                    <div class="col-lg-4 col-md-6">

                        <article class="experience-card">

                            <img
                                src="<?php echo e(
                                    $experience['image']
                                ); ?>"
                                alt="<?php echo e(
                                    $experience['title']
                                ); ?>"
                                loading="lazy"
                                onerror="this.style.display='none';">

                            <div class="experience-content">

                                <span class="experience-number">
                                    EXPERIENCE
                                    <?php echo e(
                                        $experience['number']
                                    ); ?>
                                </span>

                                <h3>
                                    <?php echo e(
                                        $experience['title']
                                    ); ?>
                                </h3>

                                <p>
                                    <?php echo e(
                                        $experience['description']
                                    ); ?>
                                </p>

                            </div>

                        </article>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="feature-banner">

        <div class="container position-relative"
             style="z-index:1;">

            <div class="text-center mb-5">

                <div class="section-eyebrow"
                     style="color:#e9d4a7;">
                    The Azure Bay Difference
                </div>

                <h2 class="section-title">
                    Designed Around Your Stay
                </h2>

                <p class="section-description">
                    A beautiful getaway deserves a seamless
                    experience, from choosing your room to
                    planning your arrival.
                </p>

            </div>

            <div class="row g-4">

                <div class="col-lg-3 col-6">
                    <div class="benefit">

                        <div class="benefit-icon"
                             aria-hidden="true">◇</div>

                        <h3>Elegant Rooms</h3>

                        <p>
                            Thoughtfully presented accommodation
                            for memorable stays.
                        </p>

                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="benefit">

                        <div class="benefit-icon"
                             aria-hidden="true">◷</div>

                        <h3>Easy Booking</h3>

                        <p>
                            A convenient way to explore rooms
                            and plan your preferred dates.
                        </p>

                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="benefit">

                        <div class="benefit-icon"
                             aria-hidden="true">♡</div>

                        <h3>Guest Focused</h3>

                        <p>
                            A welcoming experience centred
                            around comfort and relaxation.
                        </p>

                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="benefit">

                        <div class="benefit-icon"
                             aria-hidden="true">✧</div>

                        <h3>Coastal Escape</h3>

                        <p>
                            Discover the atmosphere of a
                            peaceful beachfront retreat.
                        </p>

                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Guest Reviews -->
    <section class="reviews-section section-padding"
             id="reviews">

        <div class="container">

            <div class="text-center mb-5">

                <div class="section-eyebrow">
                    Guest Impressions
                </div>

                <h2 class="section-title">
                    Stories of Memorable Stays
                </h2>

                <p class="section-description">
                    Every getaway should leave you with
                    beautiful memories. Here is a preview of
                    the guest review experience planned for
                    Azure Bay Resort.
                </p>

            </div>

            <div class="row g-4">

                <?php foreach ($reviews as $review): ?>

                    <div class="col-lg-4 col-md-6">

                        <article class="review-card">

                            <div class="review-stars"
                                 aria-label="<?php
                                    echo e($review['rating']);
                                 ?> out of 5 stars">

                                <?php
                                echo str_repeat(
                                    '★',
                                    $review['rating']
                                );
                                ?>

                            </div>

                            <p class="review-text">
                                "<?php echo e($review['text']); ?>"
                            </p>

                            <div class="review-author">

                                <div class="review-avatar">
                                    <?php
                                    echo e(
                                        strtoupper(
                                            substr($review['name'], 0, 1)
                                        )
                                    );
                                    ?>
                                </div>

                                <div>
                                    <strong>
                                        <?php echo e($review['name']); ?>
                                    </strong>

                                    <span>
                                        <?php echo e(
                                            $review['location']
                                        ); ?>
                                    </span>
                                </div>

                            </div>

                        </article>

                    </div>

                <?php endforeach; ?>

            </div>

            <p class="text-center mt-4"
               style="color:#87949a;font-size:10px;">
                The reviews above are illustrative demonstration
                content, not verified customer testimonials.
            </p>

        </div>
    </section>

    <!-- Booking Call to Action -->
    <section class="cta-section">

        <div class="container">

            <div class="section-eyebrow"
                 style="color:#e9d4a7;">
                Your Next Escape Begins Here
            </div>

            <h2 class="section-title">
                Let the Ocean Be Your Welcome.
            </h2>

            <p class="section-description">
                Find your perfect room, choose your dates,
                and begin planning your Azure Bay experience.
            </p>

            <a href="#booking" class="btn-luxury">
                PLAN YOUR STAY
                <span aria-hidden="true">→</span>
            </a>

        </div>

    </section>

    <!-- Contact Information -->
    <section class="contact-section" id="contact">

        <div class="container">

            <div class="text-center mb-5">

                <div class="section-eyebrow">
                    We're Here to Help
                </div>

                <h2 class="section-title">
                    Contact Azure Bay
                </h2>

                <p class="section-description">
                    Have a question about your stay or need
                    assistance planning your visit? Explore
                    our contact options.
                </p>

            </div>

            <div class="row g-4">

                <div class="col-lg-4 col-md-6">
                    <div class="contact-item">

                        <div class="contact-icon"
                             aria-hidden="true">⌖</div>

                        <div>
                            <strong>Our Location</strong>

                            <p>
                                Beachfront Resort<br>
                                Sri Lanka
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="contact-item">

                        <div class="contact-icon"
                             aria-hidden="true">✉</div>

                        <div>
                            <strong>Email Enquiries</strong>

                            <a href="contact.php">
                                Contact our resort team
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="contact-item">

                        <div class="contact-icon"
                             aria-hidden="true">☏</div>

                        <div>
                            <strong>Guest Assistance</strong>

                            <p>
                                Contact us through our
                                enquiry page for assistance.
                            </p>
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
                    A luxury beachfront resort booking
                    platform concept, bringing accommodation,
                    reservations, and guest experiences
                    together in one elegant destination.
                </p>

            </div>

            <div class="col-lg-2 col-6">

                <h3 class="footer-heading">
                    Explore
                </h3>

                <ul class="footer-links">

                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">Our Story</a></li>
                    <li><a href="rooms.php">Rooms & Suites</a></li>
                    <li><a href="#experiences">Experiences</a></li>
                    <li><a href="#reviews">Guest Reviews</a></li>

                </ul>

            </div>

            <div class="col-lg-2 col-6">

                <h3 class="footer-heading">
                    Guest Services
                </h3>

                <ul class="footer-links">

                    <li><a href="#booking">Book Your Stay</a></li>
                    <li><a href="my_bookings.php">My Bookings</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="reviews.php">Write a Review</a></li>

                </ul>

            </div>

            <div class="col-lg-3 col-md-6">

                <h3 class="footer-heading">
                    Your Next Escape
                </h3>

                <p class="footer-description">
                    Explore the rooms, select your preferred
                    dates, and discover the possibilities
                    of your next beachfront getaway.
                </p>

                <a href="#booking" class="btn-luxury">
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

<!-- Local Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    const bookingForm = document.getElementById('bookingForm');

    // Ensure checkout is at least one day after check-in.
    function updateCheckoutMinimum() {

        if (!checkIn.value) {
            return;
        }

        const selectedDate = new Date(
            checkIn.value + 'T00:00:00'
        );

        selectedDate.setDate(selectedDate.getDate() + 1);

        const minimumCheckout = [
            selectedDate.getFullYear(),
            String(selectedDate.getMonth() + 1).padStart(2, '0'),
            String(selectedDate.getDate()).padStart(2, '0')
        ].join('-');

        checkOut.min = minimumCheckout;

        if (!checkOut.value ||
            checkOut.value < minimumCheckout) {

            checkOut.value = minimumCheckout;
        }
    }

    checkIn.addEventListener('change', updateCheckoutMinimum);

    // Validate the booking search before navigating.
    bookingForm.addEventListener('submit', function (event) {

        if (!checkIn.value || !checkOut.value) {
            event.preventDefault();
            alert('Please select both check-in and check-out dates.');
            return;
        }

        if (checkOut.value <= checkIn.value) {
            event.preventDefault();

            alert(
                'Check-out must be at least one day after check-in.'
            );

            checkOut.focus();
        }
    });

    updateCheckoutMinimum();

});
</script>

</body>
</html>