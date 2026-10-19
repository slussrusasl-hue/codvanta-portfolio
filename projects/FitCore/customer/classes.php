
<?php
/*
|--------------------------------------------------------------------------
| FitCore - Fitness Classes
|--------------------------------------------------------------------------
| Demonstration page with sample class schedules.
| No database connection or real booking is required.
*/

$classes = [
    [
        'id' => 1,
        'name' => 'Strength Training',
        'category' => 'Strength',
        'trainer' => 'Alex Morgan',
        'day' => 'Monday',
        'start_time' => '08:00 AM',
        'end_time' => '09:00 AM',
        'duration' => '60 Minutes',
        'intensity' => 'High',
        'level' => 'Intermediate',
        'capacity' => 15,
        'enrolled' => 9,
        'price' => 10,
        'icon' => 'ST',
        'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=900&q=85',
        'description' => 'Build strength and improve lifting technique through a structured full-body workout.',
        'benefits' => ['Muscle Strength', 'Weight Training', 'Technique'],
        'featured' => true
    ],
    [
        'id' => 2,
        'name' => 'Morning Yoga',
        'category' => 'Yoga',
        'trainer' => 'Sarah Williams',
        'day' => 'Tuesday',
        'start_time' => '09:00 AM',
        'end_time' => '10:00 AM',
        'duration' => '60 Minutes',
        'intensity' => 'Low',
        'level' => 'Beginner',
        'capacity' => 20,
        'enrolled' => 12,
        'price' => 8,
        'icon' => 'YG',
        'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=900&q=85',
        'description' => 'Start your day with guided yoga movements focused on flexibility, balance, and relaxation.',
        'benefits' => ['Flexibility', 'Balance', 'Relaxation'],
        'featured' => true
    ],
    [
        'id' => 3,
        'name' => 'HIIT Workout',
        'category' => 'Cardio',
        'trainer' => 'Daniel Carter',
        'day' => 'Wednesday',
        'start_time' => '05:00 AM',
        'end_time' => '06:00 AM',
        'duration' => '60 Minutes',
        'intensity' => 'High',
        'level' => 'Advanced',
        'capacity' => 12,
        'enrolled' => 10,
        'price' => 12,
        'icon' => 'HI',
        'image' => 'https://images.unsplash.com/photo-1549060279-7e168fcee0c2?auto=format&fit=crop&w=900&q=85',
        'description' => 'Challenge your endurance with alternating high-intensity exercises and recovery intervals.',
        'benefits' => ['Cardio Fitness', 'Endurance', 'Conditioning'],
        'featured' => true
    ],
    [
        'id' => 4,
        'name' => 'Cardio Blast',
        'category' => 'Cardio',
        'trainer' => 'Olivia Bennett',
        'day' => 'Thursday',
        'start_time' => '07:00 AM',
        'end_time' => '08:00 AM',
        'duration' => '60 Minutes',
        'intensity' => 'Medium',
        'level' => 'Beginner',
        'capacity' => 18,
        'enrolled' => 8,
        'price' => 9,
        'icon' => 'CB',
        'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=900&q=85',
        'description' => 'Improve stamina and cardiovascular fitness with an energetic, coach-led workout.',
        'benefits' => ['Stamina', 'Cardio', 'Energy'],
        'featured' => false
    ],
    [
        'id' => 5,
        'name' => 'Muscle Building',
        'category' => 'Strength',
        'trainer' => 'Michael Brooks',
        'day' => 'Friday',
        'start_time' => '05:00 PM',
        'end_time' => '06:00 PM',
        'duration' => '60 Minutes',
        'intensity' => 'High',
        'level' => 'Intermediate',
        'capacity' => 14,
        'enrolled' => 11,
        'price' => 12,
        'icon' => 'MB',
        'image' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=900&q=85',
        'description' => 'Explore progressive resistance exercises designed around proper lifting form and muscle development.',
        'benefits' => ['Muscle Gain', 'Resistance', 'Strength'],
        'featured' => false
    ],
    [
        'id' => 6,
        'name' => 'Functional Fitness',
        'category' => 'Functional',
        'trainer' => 'Emma Wilson',
        'day' => 'Saturday',
        'start_time' => '10:00 AM',
        'end_time' => '11:00 AM',
        'duration' => '60 Minutes',
        'intensity' => 'Medium',
        'level' => 'All Levels',
        'capacity' => 16,
        'enrolled' => 7,
        'price' => 10,
        'icon' => 'FF',
        'image' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=900&q=85',
        'description' => 'Practise everyday movement patterns to develop coordination, mobility, and functional strength.',
        'benefits' => ['Mobility', 'Coordination', 'Balance'],
        'featured' => false
    ],
    [
        'id' => 7,
        'name' => 'Stretch & Recover',
        'category' => 'Recovery',
        'trainer' => 'Sarah Williams',
        'day' => 'Sunday',
        'start_time' => '09:00 AM',
        'end_time' => '09:45 AM',
        'duration' => '45 Minutes',
        'intensity' => 'Low',
        'level' => 'All Levels',
        'capacity' => 20,
        'enrolled' => 6,
        'price' => 7,
        'icon' => 'SR',
        'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=900&q=85',
        'description' => 'A gentle session focused on controlled stretching, mobility, and post-workout recovery.',
        'benefits' => ['Recovery', 'Stretching', 'Mobility'],
        'featured' => false
    ],
    [
        'id' => 8,
        'name' => 'Personal Training',
        'category' => 'Personal',
        'trainer' => 'Daniel Carter',
        'day' => 'Monday',
        'start_time' => '10:00 AM',
        'end_time' => '11:00 AM',
        'duration' => '60 Minutes',
        'intensity' => 'Medium',
        'level' => 'All Levels',
        'capacity' => 1,
        'enrolled' => 0,
        'price' => 25,
        'icon' => 'PT',
        'image' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=900&q=85',
        'description' => 'A one-to-one demonstration session with personalized guidance from a fitness trainer.',
        'benefits' => ['Personal Goals', 'Individual Support', 'Technique'],
        'featured' => false
    ]
];

$categories = array_values(array_unique(
    array_column($classes, 'category')
));

$searchTerm = trim($_GET['search'] ?? '');
$selectedCategory = trim($_GET['category'] ?? '');
$selectedDay = trim($_GET['day'] ?? '');
$selectedLevel = trim($_GET['level'] ?? '');

$validDays = [
    'Monday', 'Tuesday', 'Wednesday', 'Thursday',
    'Friday', 'Saturday', 'Sunday'
];

$validLevels = [
    'Beginner', 'Intermediate', 'Advanced', 'All Levels'
];

$filteredClasses = array_filter(
    $classes,
    function ($class) use (
        $searchTerm,
        $selectedCategory,
        $selectedDay,
        $selectedLevel,
        $validDays,
        $validLevels,
        $categories
    ) {
        $searchMatch = true;
        $categoryMatch = true;
        $dayMatch = true;
        $levelMatch = true;

        if ($searchTerm !== '') {
            $searchableText = $class['name'] . ' ' .
                $class['category'] . ' ' .
                $class['trainer'] . ' ' .
                $class['description'] . ' ' .
                implode(' ', $class['benefits']);

            $searchMatch = stripos($searchableText, $searchTerm) !== false;
        }

        if ($selectedCategory !== '') {
            $categoryMatch = in_array(
                $selectedCategory,
                $categories,
                true
            ) && $class['category'] === $selectedCategory;
        }

        if ($selectedDay !== '') {
            $dayMatch = in_array($selectedDay, $validDays, true)
                && $class['day'] === $selectedDay;
        }

        if ($selectedLevel !== '') {
            $levelMatch = in_array($selectedLevel, $validLevels, true)
                && $class['level'] === $selectedLevel;
        }

        return $searchMatch && $categoryMatch && $dayMatch && $levelMatch;
    }
);

function classEscape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function classAvailability($class)
{
    $remaining = max(0, (int) $class['capacity'] - (int) $class['enrolled']);

    if ($remaining === 0) {
        return [
            'text' => 'Full',
            'class' => 'availability-full',
            'remaining' => 0
        ];
    }

    if ($remaining <= 3) {
        return [
            'text' => 'Limited Spots',
            'class' => 'availability-limited',
            'remaining' => $remaining
        ];
    }

    return [
        'text' => 'Available',
        'class' => 'availability-open',
        'remaining' => $remaining
    ];
}

$totalSpots = 0;
$bookedSpots = 0;

foreach ($classes as $class) {
    $totalSpots += (int) $class['capacity'];
    $bookedSpots += (int) $class['enrolled'];
}

$availableSpots = max(0, $totalSpots - $bookedSpots);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fitness Classes | FitCore</title>

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
            --fc-red: #ff7373;
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
            padding: 80px 0 70px;
            background:
                radial-gradient(
                    circle at 85% 15%,
                    rgba(255, 107, 44, 0.16),
                    transparent 35%
                ),
                linear-gradient(135deg, #171717, #101010 70%);
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
            font-size: clamp(38px, 6vw, 62px);
            line-height: 1.08;
            font-weight: 900;
            margin: 18px 0;
        }

        .hero h1 span {
            color: var(--fc-orange);
        }

        .hero-description {
            max-width: 590px;
            color: #b8b8b8;
            font-size: 15px;
            line-height: 1.9;
            margin-bottom: 27px;
        }

        .hero-image-wrap {
            position: relative;
            max-width: 480px;
            margin: 0 auto;
        }

        .hero-image {
            width: 100%;
            height: 420px;
            object-fit: cover;
            border-radius: 18px;
            border: 1px solid #454545;
        }

        .hero-image-label {
            position: absolute;
            left: 18px;
            right: 18px;
            bottom: 18px;
            background: rgba(16, 16, 16, 0.93);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 17px;
        }

        .hero-image-label strong {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .hero-image-label span {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .stats-strip {
            padding: 25px 0;
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
            max-width: 640px;
            margin: 0 auto;
            color: var(--fc-muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .filter-panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 22px;
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

        .class-card {
            height: 100%;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.25s, border-color 0.25s;
        }

        .class-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 107, 44, 0.65);
        }

        .class-image-wrap {
            height: 220px;
            position: relative;
            overflow: hidden;
            background: #252525;
        }

        .class-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .class-card:hover .class-image {
            transform: scale(1.04);
        }

        .category-badge {
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

        .price-badge {
            position: absolute;
            right: 15px;
            bottom: 15px;
            background: rgba(16, 16, 16, 0.94);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 8px 11px;
            border-radius: 7px;
            font-size: 15px;
            font-weight: 900;
        }

        .price-badge small {
            color: var(--fc-muted);
            font-size: 10px;
            font-weight: 400;
        }

        .class-body {
            padding: 22px;
        }

        .class-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 9px;
        }

        .class-title-row h3 {
            font-size: 20px;
            font-weight: 900;
            margin: 0;
        }

        .featured-label {
            flex-shrink: 0;
            color: var(--fc-orange);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .class-description {
            color: #b2b2b2;
            font-size: 13px;
            line-height: 1.75;
            min-height: 68px;
            margin-bottom: 17px;
        }

        .class-trainer {
            display: flex;
            align-items: center;
            gap: 11px;
            background: #202020;
            border-radius: 9px;
            padding: 12px;
            margin-bottom: 17px;
        }

        .trainer-avatar {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 107, 44, 0.15);
            border: 1px solid rgba(255, 107, 44, 0.3);
            color: var(--fc-orange);
            font-size: 11px;
            font-weight: 900;
            flex-shrink: 0;
        }

        .class-trainer strong {
            display: block;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .class-trainer span {
            display: block;
            color: var(--fc-muted);
            font-size: 10px;
        }

        .class-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 11px 0;
            border-top: 1px solid var(--fc-border);
            font-size: 12px;
        }

        .class-meta span:first-child {
            color: var(--fc-muted);
        }

        .class-meta strong {
            text-align: right;
            font-weight: 800;
        }

        .intensity {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 900;
        }

        .intensity-low {
            color: var(--fc-green);
            background: rgba(54, 201, 143, 0.1);
        }

        .intensity-medium {
            color: var(--fc-yellow);
            background: rgba(245, 196, 81, 0.1);
        }

        .intensity-high {
            color: #ff8585;
            background: rgba(255, 115, 115, 0.1);
        }

        .availability-open {
            color: var(--fc-green);
        }

        .availability-limited {
            color: var(--fc-yellow);
        }

        .availability-full {
            color: var(--fc-red);
        }

        .class-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin: 15px 0;
        }

        .class-tag {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 5px;
            background: #242424;
            border: 1px solid #363636;
            color: #cfcfcf;
            font-size: 10px;
        }

        .class-actions {
            display: flex;
            gap: 9px;
            margin-top: 18px;
        }

        .class-actions a {
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
            max-width: 460px;
            line-height: 1.8;
        }

        .schedule-section {
            background: #151515;
            border-top: 1px solid var(--fc-border);
            border-bottom: 1px solid var(--fc-border);
        }

        .schedule-card {
            height: 100%;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 12px;
            padding: 22px;
        }

        .schedule-day {
            color: var(--fc-orange);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .schedule-item {
            padding: 12px 0;
            border-top: 1px solid var(--fc-border);
        }

        .schedule-item strong {
            display: block;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .schedule-item span {
            color: var(--fc-muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .booking-steps {
            display: flex;
            gap: 15px;
            align-items: flex-start;
            height: 100%;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 12px;
            padding: 23px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--fc-orange);
            color: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            flex-shrink: 0;
        }

        .booking-steps h3 {
            font-size: 16px;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .booking-steps p {
            color: var(--fc-muted);
            font-size: 12px;
            line-height: 1.8;
            margin: 0;
        }

        .demo-notice {
            border: 1px solid rgba(245, 196, 81, 0.3);
            background: rgba(245, 196, 81, 0.06);
            color: #e8d59c;
            border-radius: 11px;
            padding: 16px 18px;
            font-size: 12px;
            line-height: 1.8;
            margin-top: 25px;
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
                height: 350px;
            }
        }

        @media (max-width: 575px) {
            .hero {
                padding: 48px 0;
            }

            .hero-image {
                height: 300px;
            }

            .section {
                padding: 55px 0;
            }

            .class-image-wrap {
                height: 240px;
            }

            .class-actions {
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
                    <a class="nav-link active" href="classes.php">Classes</a>
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

<!-- Hero -->
<header class="hero">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-7">
                <div class="eyebrow">Find Your Next Workout</div>

                <h1>
                    Classes That Push
                    <span>Your Limits.</span>
                </h1>

                <p class="hero-description">
                    Explore fitness classes for different goals, fitness
                    levels, and training styles. Browse sample schedules,
                    meet the trainers, and discover the FitCore class-booking
                    experience.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="#classDirectory" class="btn-fitcore">
                        Explore Classes &darr;
                    </a>

                    <a href="trainers.php" class="btn-outline-fitcore">
                        Meet Our Trainers
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-4">
                        <div class="hero-stat">
                            <strong>08</strong>
                            <span>Sample Classes</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="hero-stat">
                            <strong>07</strong>
                            <span>Training Days</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="hero-stat">
                            <strong>$7+</strong>
                            <span>Sample Prices</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-image-wrap">
                    <img
                        class="hero-image"
                        src="https://images.unsplash.com/photo-1549060279-7e168fcee0c2?auto=format&fit=crop&w=1000&q=85"
                        alt="Fitness training session at a gym"
                        loading="eager"
                    >

                    <div class="hero-image-label">
                        <strong>Make Every Session Count</strong>
                        <span>Discover your class. Find your rhythm. Build consistency.</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Stats -->
<section class="stats-strip">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <strong><?= count($classes) ?></strong>
                    <span>Sample Class Types</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <strong><?= count($categories) ?></strong>
                    <span>Training Categories</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <strong><?= $availableSpots ?></strong>
                    <span>Sample Open Spots</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-item">
                    <strong>All Levels</strong>
                    <span>Beginner to Advanced</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Class Directory -->
<section class="section" id="classDirectory">
    <div class="container">

        <div class="section-heading">
            <div class="eyebrow">Explore The Schedule</div>

            <h2>Find Your Fitness Class</h2>

            <p>
                Browse the available demonstration sessions. Filter by
                category, day, or experience level to find a class that
                matches your interests.
            </p>
        </div>

        <!-- Search and Filters -->
        <form
            method="GET"
            action="classes.php#classDirectory"
            class="filter-panel"
        >
            <div class="row g-3 align-items-end">

                <div class="col-lg-4">
                    <label for="search">Search Classes</label>

                    <input
                        type="search"
                        class="form-control"
                        id="search"
                        name="search"
                        placeholder="Class name, trainer, or goal..."
                        value="<?= classEscape($searchTerm) ?>"
                    >
                </div>

                <div class="col-md-4 col-lg-3">
                    <label for="category">Category</label>

                    <select
                        class="form-select"
                        id="category"
                        name="category"
                    >
                        <option value="">All Categories</option>

                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= classEscape($category) ?>"
                                <?= $selectedCategory === $category
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= classEscape($category) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 col-lg-2">
                    <label for="day">Day</label>

                    <select class="form-select" id="day" name="day">
                        <option value="">Any Day</option>

                        <?php foreach ($validDays as $day): ?>
                            <option
                                value="<?= classEscape($day) ?>"
                                <?= $selectedDay === $day ? 'selected' : '' ?>
                            >
                                <?= classEscape($day) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 col-lg-2">
                    <label for="level">Fitness Level</label>

                    <select class="form-select" id="level" name="level">
                        <option value="">All Levels</option>

                        <?php foreach ($validLevels as $level): ?>
                            <option
                                value="<?= classEscape($level) ?>"
                                <?= $selectedLevel === $level ? 'selected' : '' ?>
                            >
                                <?= classEscape($level) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-lg-1 d-grid">
                    <button type="submit" class="btn-fitcore">
                        Go
                    </button>
                </div>

            </div>

            <?php if (
                $searchTerm !== '' ||
                $selectedCategory !== '' ||
                $selectedDay !== '' ||
                $selectedLevel !== ''
            ): ?>
                <div class="mt-3">
                    <a
                        href="classes.php#classDirectory"
                        class="btn-outline-fitcore"
                    >
                        Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </form>

        <div class="results-label">
            Showing
            <strong><?= count($filteredClasses) ?></strong>
            of <?= count($classes) ?> sample classes
        </div>

        <?php if (!empty($filteredClasses)): ?>

            <div class="row g-4">

                <?php foreach ($filteredClasses as $class): ?>

                    <?php
                    $availability = classAvailability($class);

                    $intensityClass = 'intensity-medium';

                    if ($class['intensity'] === 'Low') {
                        $intensityClass = 'intensity-low';
                    } elseif ($class['intensity'] === 'High') {
                        $intensityClass = 'intensity-high';
                    }

                    $trainerInitials = '';

                    $nameParts = explode(' ', $class['trainer']);

                    foreach ($nameParts as $part) {
                        $trainerInitials .= strtoupper(substr($part, 0, 1));
                    }
                    ?>

                    <div class="col-md-6 col-xl-4">
                        <article class="class-card">

                            <div class="class-image-wrap">
                                <img
                                    class="class-image"
                                    src="<?= classEscape($class['image']) ?>"
                                    alt="<?= classEscape($class['name']) ?>"
                                    loading="lazy"
                                >

                                <span class="category-badge">
                                    <?= classEscape($class['category']) ?>
                                </span>

                                <span class="price-badge">
                                    $<?= number_format(
                                        (float) $class['price'],
                                        2
                                    ) ?>
                                    <small>/ session</small>
                                </span>
                            </div>

                            <div class="class-body">

                                <div class="class-title-row">
                                    <h3>
                                        <?= classEscape($class['name']) ?>
                                    </h3>

                                    <?php if ($class['featured']): ?>
                                        <span class="featured-label">
                                            Featured
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <p class="class-description">
                                    <?= classEscape($class['description']) ?>
                                </p>

                                <div class="class-trainer">
                                    <div class="trainer-avatar">
                                        <?= classEscape($trainerInitials) ?>
                                    </div>

                                    <div>
                                        <strong>
                                            <?= classEscape($class['trainer']) ?>
                                        </strong>

                                        <span>Fitness Trainer</span>
                                    </div>
                                </div>

                                <div class="class-meta">
                                    <span>Schedule</span>
                                    <strong>
                                        <?= classEscape($class['day']) ?>
                                    </strong>
                                </div>

                                <div class="class-meta">
                                    <span>Time</span>
                                    <strong>
                                        <?= classEscape($class['start_time']) ?>
                                        -
                                        <?= classEscape($class['end_time']) ?>
                                    </strong>
                                </div>

                                <div class="class-meta">
                                    <span>Duration</span>
                                    <strong>
                                        <?= classEscape($class['duration']) ?>
                                    </strong>
                                </div>

                                <div class="class-meta">
                                    <span>Fitness Level</span>
                                    <strong>
                                        <?= classEscape($class['level']) ?>
                                    </strong>
                                </div>

                                <div class="class-meta">
                                    <span>Intensity</span>
                                    <strong>
                                        <span class="intensity <?= $intensityClass ?>">
                                            <?= classEscape($class['intensity']) ?>
                                        </span>
                                    </strong>
                                </div>

                                <div class="class-meta">
                                    <span>Availability</span>
                                    <strong class="<?= $availability['class'] ?>">
                                        <?= classEscape($availability['text']) ?>
                                    </strong>
                                </div>

                                <div class="class-meta">
                                    <span>Sample Spots Left</span>
                                    <strong>
                                        <?= (int) $availability['remaining'] ?>
                                        / <?= (int) $class['capacity'] ?>
                                    </strong>
                                </div>

                                <div class="class-tags">
                                    <?php foreach ($class['benefits'] as $benefit): ?>
                                        <span class="class-tag">
                                            <?= classEscape($benefit) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="class-actions">
                                    <?php if ($availability['remaining'] > 0): ?>
                                        <a
                                            href="book_session.php?class_id=<?= (int) $class['id'] ?>"
                                            class="btn-fitcore"
                                        >
                                            Book Session
                                        </a>
                                    <?php else: ?>
                                        <a
                                            href="#classDirectory"
                                            class="btn-outline-fitcore"
                                            aria-disabled="true"
                                        >
                                            Class Full
                                        </a>
                                    <?php endif; ?>

                                    <a
                                        href="trainers.php"
                                        class="btn-outline-fitcore"
                                    >
                                        View Trainers
                                    </a>
                                </div>

                            </div>
                        </article>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                <h3>No Classes Found</h3>

                <p>
                    We couldn't find a sample class matching those filters.
                    Try a different keyword, category, day, or fitness level.
                </p>

                <a
                    href="classes.php#classDirectory"
                    class="btn-fitcore"
                >
                    Show All Classes
                </a>
            </div>

        <?php endif; ?>

        <div class="demo-notice">
            <strong>Demo notice:</strong>
            Class schedules, trainer assignments, prices, enrolment counts,
            and available spots are sample data. The displayed availability
            does not change when visitors click a booking link.
        </div>

    </div>
</section>

<!-- Weekly Schedule Overview -->
<section class="section schedule-section">
    <div class="container">

        <div class="section-heading">
            <div class="eyebrow">Plan Your Week</div>

            <h2>Weekly Class Highlights</h2>

            <p>
                A quick overview of the sample training sessions scheduled
                throughout the week.
            </p>
        </div>

        <div class="row g-4">

            <?php foreach ($validDays as $day): ?>
                <?php
                $dayClasses = array_filter(
                    $classes,
                    function ($class) use ($day) {
                        return $class['day'] === $day;
                    }
                );
                ?>

                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="schedule-card">
                        <div class="schedule-day">
                            <?= classEscape($day) ?>
                        </div>

                        <?php if (!empty($dayClasses)): ?>

                            <?php foreach ($dayClasses as $dayClass): ?>
                                <div class="schedule-item">
                                    <strong>
                                        <?= classEscape($dayClass['name']) ?>
                                    </strong>

                                    <span>
                                        <?= classEscape($dayClass['start_time']) ?>
                                        -
                                        <?= classEscape($dayClass['end_time']) ?>
                                        <br>
                                        Trainer:
                                        <?= classEscape($dayClass['trainer']) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="schedule-item">
                                <strong>No sample classes</strong>
                                <span>Check the full class directory for other sessions.</span>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>

<!-- Booking Steps -->
<section class="section">
    <div class="container">

        <div class="section-heading">
            <div class="eyebrow">Simple Booking Journey</div>

            <h2>How Class Booking Works</h2>

            <p>
                The FitCore demonstration is designed to show visitors the
                main steps a member would follow to book a fitness session.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="booking-steps">
                    <div class="step-number">01</div>

                    <div>
                        <h3>Choose a Class</h3>

                        <p>
                            Browse the sample schedule and select a session
                            that matches your interests and fitness level.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="booking-steps">
                    <div class="step-number">02</div>

                    <div>
                        <h3>Review the Session</h3>

                        <p>
                            Check the trainer, date, time, duration, and
                            demonstration price before continuing.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="booking-steps">
                    <div class="step-number">03</div>

                    <div>
                        <h3>Explore Your Booking</h3>

                        <p>
                            Continue to the session-booking demonstration
                            to explore the next part of the member workflow.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <div class="cta-card">

            <div class="eyebrow">Build Your Routine</div>

            <h2>Your Next Workout Starts Here</h2>

            <p>
                Explore FitCore membership options and discover how a gym
                platform can bring trainer profiles, fitness classes, and
                member activities together in one place.
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="register.php" class="btn-fitcore">
                    Explore Membership Plans
                </a>

                <a href="trainers.php" class="btn-outline-fitcore">
                    Meet Our Trainers
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
            All classes, prices, schedules, trainers, and availability
            figures are sample data for portfolio demonstration purposes.
        </p>
    </div>
</footer>

<script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>