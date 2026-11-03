
<?php
/*
|--------------------------------------------------------------------------
| FitCore - Trainers Page
|--------------------------------------------------------------------------
| Demonstration page with sample trainer profiles.
| No database connection is required.
*/

$trainers = [
    [
        'id' => 1,
        'name' => 'Alex Morgan',
        'specialization' => 'Strength & Conditioning',
        'experience' => '8 Years',
        'rating' => '4.9',
        'reviews' => 124,
        'sessions' => 'Personal Training, Strength Training',
        'availability' => 'Mon - Fri',
        'time' => '06:00 AM - 12:00 PM',
        'image' => 'https://images.unsplash.com/photo-1567013127542-490d757e51fc?auto=format&fit=crop&w=900&q=85',
        'description' => 'Helping members build strength, improve endurance, and develop sustainable workout routines.',
        'tags' => ['Weight Training', 'Muscle Building', 'Fitness Goals'],
        'featured' => true
    ],
    [
        'id' => 2,
        'name' => 'Sarah Williams',
        'specialization' => 'Yoga & Flexibility',
        'experience' => '6 Years',
        'rating' => '4.8',
        'reviews' => 98,
        'sessions' => 'Yoga, Stretching, Mobility',
        'availability' => 'Mon - Sat',
        'time' => '08:00 AM - 02:00 PM',
        'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=900&q=85',
        'description' => 'Guiding members towards better flexibility, balance, relaxation, and mind-body wellness.',
        'tags' => ['Yoga', 'Flexibility', 'Wellness'],
        'featured' => false
    ],
    [
        'id' => 3,
        'name' => 'Daniel Carter',
        'specialization' => 'Personal Fitness Training',
        'experience' => '7 Years',
        'rating' => '4.9',
        'reviews' => 110,
        'sessions' => 'Personal Training, HIIT',
        'availability' => 'Tue - Sat',
        'time' => '05:00 AM - 11:00 AM',
        'image' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=900&q=85',
        'description' => 'Creating practical training plans for members who want to improve fitness and overall performance.',
        'tags' => ['HIIT', 'Fat Loss', 'Personal Training'],
        'featured' => true
    ],
    [
        'id' => 4,
        'name' => 'Olivia Bennett',
        'specialization' => 'Cardio & Endurance',
        'experience' => '5 Years',
        'rating' => '4.7',
        'reviews' => 86,
        'sessions' => 'Cardio, Endurance, Group Fitness',
        'availability' => 'Mon - Fri',
        'time' => '07:00 AM - 01:00 PM',
        'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=900&q=85',
        'description' => 'Supporting members as they improve stamina, cardiovascular fitness, and workout consistency.',
        'tags' => ['Cardio', 'Endurance', 'Group Training'],
        'featured' => false
    ],
    [
        'id' => 5,
        'name' => 'Michael Brooks',
        'specialization' => 'Bodybuilding & Muscle Gain',
        'experience' => '10 Years',
        'rating' => '5.0',
        'reviews' => 142,
        'sessions' => 'Bodybuilding, Weight Training',
        'availability' => 'Mon - Sat',
        'time' => '02:00 PM - 08:00 PM',
        'image' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=900&q=85',
        'description' => 'Helping members learn proper lifting techniques and follow structured muscle-building programmes.',
        'tags' => ['Bodybuilding', 'Muscle Gain', 'Weight Training'],
        'featured' => true
    ],
    [
        'id' => 6,
        'name' => 'Emma Wilson',
        'specialization' => 'Functional Fitness',
        'experience' => '4 Years',
        'rating' => '4.8',
        'reviews' => 73,
        'sessions' => 'Functional Training, Mobility',
        'availability' => 'Tue - Sun',
        'time' => '09:00 AM - 03:00 PM',
        'image' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=900&q=85',
        'description' => 'Designing movement-focused sessions that help members develop balance, coordination, and everyday strength.',
        'tags' => ['Functional Fitness', 'Mobility', 'Balance'],
        'featured' => false
    ]
];

$specializations = array_unique(
    array_column($trainers, 'specialization')
);

$searchTerm = trim($_GET['search'] ?? '');
$selectedSpecialization = trim($_GET['specialization'] ?? '');

$filteredTrainers = array_filter(
    $trainers,
    function ($trainer) use ($searchTerm, $selectedSpecialization) {
        $searchMatch = true;
        $specializationMatch = true;

        if ($searchTerm !== '') {
            $searchableText = $trainer['name'] . ' ' .
                $trainer['specialization'] . ' ' .
                $trainer['description'] . ' ' .
                implode(' ', $trainer['tags']);

            $searchMatch = stripos($searchableText, $searchTerm) !== false;
        }

        if (
            $selectedSpecialization !== '' &&
            in_array($selectedSpecialization, [
                'Strength & Conditioning',
                'Yoga & Flexibility',
                'Personal Fitness Training',
                'Cardio & Endurance',
                'Bodybuilding & Muscle Gain',
                'Functional Fitness'
            ], true)
        ) {
            $specializationMatch =
                $trainer['specialization'] === $selectedSpecialization;
        } elseif ($selectedSpecialization !== '') {
            $specializationMatch = false;
        }

        return $searchMatch && $specializationMatch;
    }
);

function trainerEscape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Our Trainers | FitCore Fitness</title>

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

        html {
            scroll-behavior: smooth;
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

        .btn-fitcore {
            display: inline-block;
            background: var(--fc-orange);
            color: #111;
            border: 0;
            padding: 12px 20px;
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
            border: 1px solid #4a4a4a;
            color: #eee;
            padding: 11px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            transition: 0.2s;
        }

        .btn-outline-fitcore:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 85px 0 75px;
            background:
                radial-gradient(
                    circle at 85% 15%,
                    rgba(255, 107, 44, 0.17),
                    transparent 34%
                ),
                linear-gradient(135deg, #151515, #101010 70%);
            border-bottom: 1px solid var(--fc-border);
        }

        .eyebrow {
            color: var(--fc-orange);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 12px;
            font-weight: 900;
        }

        .hero h1 {
            font-size: clamp(38px, 6vw, 64px);
            line-height: 1.08;
            font-weight: 900;
            margin: 18px 0;
        }

        .hero h1 span {
            color: var(--fc-orange);
        }

        .hero-description {
            max-width: 600px;
            color: #b8b8b8;
            font-size: 16px;
            line-height: 1.9;
            margin-bottom: 28px;
        }

        .hero-stat {
            padding-left: 18px;
            border-left: 2px solid var(--fc-orange);
        }

        .hero-stat strong {
            display: block;
            font-size: 23px;
            font-weight: 900;
        }

        .hero-stat span {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .hero-image-wrap {
            position: relative;
            max-width: 480px;
            margin: 0 auto;
        }

        .hero-image {
            width: 100%;
            height: 440px;
            object-fit: cover;
            border-radius: 18px;
            border: 1px solid #454545;
            filter: saturate(0.85);
        }

        .hero-image-label {
            position: absolute;
            left: 18px;
            right: 18px;
            bottom: 18px;
            background: rgba(16, 16, 16, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 16px 18px;
        }

        .hero-image-label strong {
            display: block;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .hero-image-label span {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .stats-strip {
            padding: 24px 0;
            background: #151515;
            border-bottom: 1px solid var(--fc-border);
        }

        .stat-item {
            text-align: center;
            padding: 10px;
        }

        .stat-item strong {
            display: block;
            color: var(--fc-orange);
            font-size: 25px;
            font-weight: 900;
        }

        .stat-item span {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .section {
            padding: 75px 0;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 38px;
        }

        .section-heading h2 {
            font-size: clamp(29px, 4vw, 40px);
            font-weight: 900;
            margin: 12px 0;
        }

        .section-heading p {
            max-width: 620px;
            margin: 0 auto;
            color: var(--fc-muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .filter-panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .filter-panel label {
            color: #d7d7d7;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background-color: #111;
            color: #eee;
            border: 1px solid #3a3a3a;
            min-height: 45px;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #111;
            color: #fff;
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 44, 0.15);
        }

        .form-control::placeholder {
            color: #858585;
        }

        .form-select option {
            background: #191919;
            color: #fff;
        }

        .results-label {
            color: var(--fc-muted);
            font-size: 13px;
            margin-bottom: 18px;
        }

        .trainer-card {
            height: 100%;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.25s, border-color 0.25s;
        }

        .trainer-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 107, 44, 0.65);
        }

        .trainer-image-wrap {
            height: 245px;
            position: relative;
            overflow: hidden;
            background: #242424;
        }

        .trainer-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .trainer-card:hover .trainer-image {
            transform: scale(1.04);
        }

        .featured-label {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--fc-orange);
            color: #111;
            padding: 7px 10px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .rating-label {
            position: absolute;
            right: 15px;
            bottom: 15px;
            padding: 7px 10px;
            border-radius: 6px;
            background: rgba(16, 16, 16, 0.93);
            color: #ffca63;
            font-size: 12px;
            font-weight: 900;
        }

        .trainer-body {
            padding: 22px;
        }

        .trainer-body h3 {
            font-size: 21px;
            font-weight: 900;
            margin-bottom: 7px;
        }

        .trainer-specialization {
            color: var(--fc-orange);
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .trainer-description {
            color: #b2b2b2;
            font-size: 13px;
            line-height: 1.75;
            min-height: 68px;
            margin-bottom: 17px;
        }

        .trainer-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid var(--fc-border);
            font-size: 12px;
        }

        .trainer-meta span:first-child {
            color: var(--fc-muted);
        }

        .trainer-meta strong {
            text-align: right;
            font-weight: 800;
        }

        .trainer-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin: 17px 0;
        }

        .trainer-tag {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 5px;
            background: #242424;
            border: 1px solid #363636;
            color: #cfcfcf;
            font-size: 10px;
        }

        .trainer-actions {
            display: flex;
            gap: 9px;
            margin-top: 18px;
        }

        .trainer-actions a {
            flex: 1;
            text-align: center;
            padding: 11px 8px;
            font-size: 11px;
        }

        .empty-state {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state h3 {
            font-weight: 900;
            font-size: 22px;
        }

        .empty-state p {
            color: var(--fc-muted);
            font-size: 14px;
            margin: 10px auto 22px;
            max-width: 450px;
            line-height: 1.8;
        }

        .benefits-section {
            background: #151515;
            border-top: 1px solid var(--fc-border);
            border-bottom: 1px solid var(--fc-border);
        }

        .benefit-card {
            height: 100%;
            padding: 25px;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 12px;
        }

        .benefit-icon {
            width: 47px;
            height: 47px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 107, 44, 0.13);
            border-radius: 10px;
            color: var(--fc-orange);
            font-size: 22px;
            font-weight: 900;
            margin-bottom: 18px;
        }

        .benefit-card h3 {
            font-size: 17px;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .benefit-card p {
            color: var(--fc-muted);
            font-size: 13px;
            line-height: 1.8;
            margin: 0;
        }

        .cta-section {
            padding: 70px 0;
        }

        .cta-card {
            padding: 45px 30px;
            text-align: center;
            border-radius: 18px;
            border: 1px solid rgba(255, 107, 44, 0.35);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(255, 107, 44, 0.15),
                    transparent 45%
                ),
                var(--fc-card);
        }

        .cta-card h2 {
            font-size: clamp(28px, 4vw, 39px);
            font-weight: 900;
            margin: 13px 0;
        }

        .cta-card p {
            color: var(--fc-muted);
            max-width: 600px;
            margin: 0 auto 25px;
            line-height: 1.8;
            font-size: 14px;
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

        @media (max-width: 991px) {
            .hero {
                padding: 60px 0;
            }

            .hero-image {
                height: 360px;
            }

            .trainer-image-wrap {
                height: 225px;
            }
        }

        @media (max-width: 575px) {
            .hero {
                padding: 48px 0;
            }

            .hero-image {
                height: 310px;
            }

            .section {
                padding: 55px 0;
            }

            .trainer-image-wrap {
                height: 260px;
            }

            .trainer-actions {
                flex-direction: column;
            }

            .cta-card {
                padding: 35px 20px;
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
                    <a class="nav-link active" href="trainers.php">Trainers</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="classes.php">Classes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="my_bookings.php">My Bookings</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="payment_history.php">
                        Payment History
                    </a>
                </li>

                <li class="nav-item ms-lg-2">
                    <a class="btn-fitcore" href="register.php">
                        Join FitCore
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="hero">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-7">
                <div class="eyebrow">Meet Your Fitness Team</div>

                <h1>
                    Train With The
                    <span>Best.</span>
                </h1>

                <p class="hero-description">
                    Great results start with the right guidance. Discover
                    our demonstration trainer profiles, explore their
                    specializations, and find a training style that fits
                    your fitness goals.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="#trainerDirectory" class="btn-fitcore">
                        Explore Trainers &darr;
                    </a>

                    <a href="classes.php" class="btn-outline-fitcore">
                        View Fitness Classes
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-4">
                        <div class="hero-stat">
                            <strong>06</strong>
                            <span>Sample Trainers</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="hero-stat">
                            <strong>06</strong>
                            <span>Specializations</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="hero-stat">
                            <strong>4.8+</strong>
                            <span>Sample Ratings</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-image-wrap">
                    <img
                        class="hero-image"
                        src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1000&q=85"
                        alt="Modern gym training area"
                        loading="eager"
                    >

                    <div class="hero-image-label">
                        <strong>Stronger Every Day</strong>
                        <span>Expert guidance. Consistent effort. Better habits.</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Stats Strip -->
<section class="stats-strip">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <strong>1-on-1</strong>
                    <span>Personal Training</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <strong>Flexible</strong>
                    <span>Training Schedules</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <strong>Goal-Based</strong>
                    <span>Training Approaches</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <strong>Group</strong>
                    <span>Fitness Sessions</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trainer Directory -->
<section class="section" id="trainerDirectory">
    <div class="container">

        <div class="section-heading">
            <div class="eyebrow">Find Your Trainer</div>

            <h2>Meet Our Trainers</h2>

            <p>
                Browse the sample profiles below. Search by name or
                specialization to explore different training options.
            </p>
        </div>

        <!-- Search and Filter -->
        <form
            method="GET"
            action="trainers.php#trainerDirectory"
            class="filter-panel"
        >
            <div class="row g-3 align-items-end">

                <div class="col-lg-5">
                    <label for="search">Search Trainers</label>

                    <input
                        type="search"
                        class="form-control"
                        id="search"
                        name="search"
                        placeholder="Enter trainer name or fitness goal..."
                        value="<?= trainerEscape($searchTerm) ?>"
                    >
                </div>

                <div class="col-lg-5">
                    <label for="specialization">Specialization</label>

                    <select
                        class="form-select"
                        id="specialization"
                        name="specialization"
                    >
                        <option value="">All Specializations</option>

                        <?php foreach ($specializations as $specialization): ?>
                            <option
                                value="<?= trainerEscape($specialization) ?>"
                                <?= $selectedSpecialization === $specialization
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= trainerEscape($specialization) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-lg-2 d-grid">
                    <button type="submit" class="btn-fitcore">
                        Search
                    </button>
                </div>

            </div>

            <?php if ($searchTerm !== '' || $selectedSpecialization !== ''): ?>
                <div class="mt-3">
                    <a href="trainers.php#trainerDirectory"
                       class="btn-outline-fitcore">
                        Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </form>

        <div class="results-label">
            Showing
            <strong><?= count($filteredTrainers) ?></strong>
            of <?= count($trainers) ?> sample trainers
        </div>

        <?php if (!empty($filteredTrainers)): ?>

            <div class="row g-4">

                <?php foreach ($filteredTrainers as $trainer): ?>

                    <div class="col-md-6 col-xl-4">
                        <article class="trainer-card">

                            <div class="trainer-image-wrap">
                                <img
                                    class="trainer-image"
                                    src="<?= trainerEscape($trainer['image']) ?>"
                                    alt="<?= trainerEscape($trainer['name']) ?>"
                                    loading="lazy"
                                >

                                <?php if ($trainer['featured']): ?>
                                    <span class="featured-label">
                                        Featured Trainer
                                    </span>
                                <?php endif; ?>

                                <span class="rating-label">
                                    &#9733;
                                    <?= trainerEscape($trainer['rating']) ?>
                                    / 5
                                </span>
                            </div>

                            <div class="trainer-body">

                                <h3>
                                    <?= trainerEscape($trainer['name']) ?>
                                </h3>

                                <div class="trainer-specialization">
                                    <?= trainerEscape(
                                        $trainer['specialization']
                                    ) ?>
                                </div>

                                <p class="trainer-description">
                                    <?= trainerEscape(
                                        $trainer['description']
                                    ) ?>
                                </p>

                                <div class="trainer-meta">
                                    <span>Experience</span>
                                    <strong>
                                        <?= trainerEscape(
                                            $trainer['experience']
                                        ) ?>
                                    </strong>
                                </div>

                                <div class="trainer-meta">
                                    <span>Availability</span>
                                    <strong>
                                        <?= trainerEscape(
                                            $trainer['availability']
                                        ) ?>
                                    </strong>
                                </div>

                                <div class="trainer-meta">
                                    <span>Training Hours</span>
                                    <strong>
                                        <?= trainerEscape($trainer['time']) ?>
                                    </strong>
                                </div>

                                <div class="trainer-meta">
                                    <span>Sample Reviews</span>
                                    <strong>
                                        <?= (int) $trainer['reviews'] ?> reviews
                                    </strong>
                                </div>

                                <div class="trainer-tags">
                                    <?php foreach ($trainer['tags'] as $tag): ?>
                                        <span class="trainer-tag">
                                            <?= trainerEscape($tag) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="trainer-actions">
                                    <a
                                        href="classes.php"
                                        class="btn-fitcore"
                                    >
                                        View Classes
                                    </a>

                                    <a
                                        href="register.php"
                                        class="btn-outline-fitcore"
                                    >
                                        Join Now
                                    </a>
                                </div>

                            </div>
                        </article>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                <h3>No Trainers Found</h3>

                <p>
                    We couldn't find a sample trainer matching your search.
                    Try another name or select a different specialization.
                </p>

                <a
                    href="trainers.php#trainerDirectory"
                    class="btn-fitcore"
                >
                    Show All Trainers
                </a>
            </div>

        <?php endif; ?>

    </div>
</section>

<!-- Why Train With Us -->
<section class="section benefits-section">
    <div class="container">

        <div class="section-heading">
            <div class="eyebrow">The FitCore Experience</div>

            <h2>More Than Just A Workout</h2>

            <p>
                Explore how a fitness platform can connect members with
                trainers and help them organize their training activities.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <div class="benefit-card">
                    <div class="benefit-icon" aria-hidden="true">
                        &#9733;
                    </div>

                    <h3>Personal Guidance</h3>

                    <p>
                        Discover trainer profiles and identify training
                        approaches that match your personal fitness goals.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="benefit-card">
                    <div class="benefit-icon" aria-hidden="true">
                        &#9200;
                    </div>

                    <h3>Flexible Scheduling</h3>

                    <p>
                        Explore sample trainer availability and class
                        schedules to plan your fitness activities.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="benefit-card">
                    <div class="benefit-icon" aria-hidden="true">
                        &#9829;
                    </div>

                    <h3>Different Training Styles</h3>

                    <p>
                        Browse strength, yoga, cardio, bodybuilding, and
                        functional fitness specializations.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="benefit-card">
                    <div class="benefit-icon" aria-hidden="true">
                        &#10003;
                    </div>

                    <h3>Organized Fitness Journey</h3>

                    <p>
                        Explore the member pages for membership registration,
                        session booking, and payment-history demonstrations.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <div class="cta-card">

            <div class="eyebrow">Your Next Step</div>

            <h2>Ready To Start Training?</h2>

            <p>
                Explore the FitCore membership plans and continue through
                the demonstration registration and simulated payment
                workflow.
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="register.php" class="btn-fitcore">
                    Explore Membership Plans
                </a>

                <a href="classes.php" class="btn-outline-fitcore">
                    Browse Fitness Classes
                </a>
            </div>

        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-2">
            &copy; <?= date('Y') ?> FitCore Fitness Platform.
            Demonstration website by
            <a href="../index.php">Codvanta</a>.
        </p>

        <p class="mb-0">
            Trainer profiles, ratings, reviews, and schedules are sample data
            for portfolio demonstration purposes.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


</body>
</html>