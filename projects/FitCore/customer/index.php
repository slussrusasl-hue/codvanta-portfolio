
<?php
// FitCore - Member Homepage
// Codvanta Portfolio Demonstration

$membershipPlans = [
    [
        'name' => 'BASIC',
        'price' => 20,
        'description' => 'A great starting point for your fitness journey.',
        'features' => [
            'Gym access',
            'Basic workout facilities',
            'View fitness class schedules'
        ],
        'popular' => false
    ],
    [
        'name' => 'PRO',
        'price' => 40,
        'description' => 'For members who want to take their training further.',
        'features' => [
            'Everything in Basic',
            'Group fitness classes',
            'Training session bookings'
        ],
        'popular' => true
    ],
    [
        'name' => 'PREMIUM',
        'price' => 70,
        'description' => 'A premium fitness experience with personalized support.',
        'features' => [
            'Everything in Pro',
            'Priority session booking',
            'Personal training benefits'
        ],
        'popular' => false
    ]
];

$trainers = [
    [
        'name' => 'Alex Morgan',
        'specialty' => 'Strength & Conditioning',
        'experience' => '8 Years Experience',
        'image' => 'https://images.unsplash.com/photo-1567013127542-490d757e51fc?auto=format&fit=crop&w=700&q=80',
        'description' => 'Helping members build strength, improve technique, and achieve their fitness goals.'
    ],
    [
        'name' => 'Sarah Williams',
        'specialty' => 'Yoga & Flexibility',
        'experience' => '6 Years Experience',
        'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=700&q=80',
        'description' => 'Supporting flexibility, balance, mobility, and mindful movement.'
    ],
    [
        'name' => 'Daniel Carter',
        'specialty' => 'Personal Fitness Training',
        'experience' => '7 Years Experience',
        'image' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=700&q=80',
        'description' => 'Designing structured workouts to improve endurance, strength, and performance.'
    ]
];

$classes = [
    [
        'name' => 'Strength Training',
        'category' => 'STRENGTH',
        'trainer' => 'Alex Morgan',
        'day' => 'Monday',
        'time' => '08:00 AM - 09:00 AM',
        'level' => 'All Levels',
        'spots' => 5,
        'icon' => '🏋'
    ],
    [
        'name' => 'Morning Yoga',
        'category' => 'WELLNESS',
        'trainer' => 'Sarah Williams',
        'day' => 'Tuesday',
        'time' => '09:00 AM - 10:00 AM',
        'level' => 'Beginner Friendly',
        'spots' => 8,
        'icon' => '🧘'
    ],
    [
        'name' => 'HIIT Workout',
        'category' => 'CARDIO',
        'trainer' => 'Daniel Carter',
        'day' => 'Wednesday',
        'time' => '05:00 PM - 06:00 PM',
        'level' => 'Intermediate',
        'spots' => 3,
        'icon' => '⚡'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FitCore | Fitness Starts Here</title>

    <!-- Local Bootstrap 5 -->
    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --primary: #ff6b2c;
            --primary-hover: #e9561b;
            --background: #0b0d10;
            --surface: #15191f;
            --surface-light: #1d222a;
            --text: #f5f5f5;
            --muted: #a6adb7;
            --border: rgba(255, 255, 255, 0.09);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            margin: 0;
            background: var(--background);
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            line-height: 1.65;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
        }

        /* Navigation */

        .navbar {
            background: rgba(11, 13, 16, 0.97);
            border-bottom: 1px solid var(--border);
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 1.65rem;
            font-weight: 900;
            color: #fff !important;
            letter-spacing: 1px;
        }

        .brand-highlight {
            color: var(--primary);
        }

        .nav-link {
            color: #c6cbd3 !important;
            margin: 0 8px;
            font-size: 0.93rem;
            transition: color 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary) !important;
        }

        .navbar-toggler {
            border-color: var(--border);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px rgba(255, 107, 44, 0.3);
        }

        /* Buttons */

        .btn-fitcore {
            display: inline-block;
            background: var(--primary);
            border: 1px solid var(--primary);
            border-radius: 7px;
            color: #fff;
            padding: 12px 22px;
            font-weight: 800;
            transition: 0.3s;
        }

        .btn-fitcore:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-outline-fitcore {
            display: inline-block;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 7px;
            color: #fff;
            padding: 12px 22px;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-outline-fitcore:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Hero */

        .hero {
            min-height: 620px;
            display: flex;
            align-items: center;
            background:
                linear-gradient(
                    90deg,
                    rgba(11, 13, 16, 0.98) 0%,
                    rgba(11, 13, 16, 0.86) 48%,
                    rgba(11, 13, 16, 0.28) 100%
                ),
                url("https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1900&q=85")
                center/cover no-repeat;
        }

        .hero-content {
            padding-top: 80px;
            padding-bottom: 80px;
            max-width: 720px;
        }

        .eyebrow {
            display: inline-block;
            background: rgba(255, 107, 44, 0.12);
            border: 1px solid rgba(255, 107, 44, 0.3);
            color: var(--primary);
            border-radius: 30px;
            padding: 7px 15px;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-size: clamp(2.7rem, 6vw, 5rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 25px;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero p {
            color: #c4c9d1;
            max-width: 620px;
            font-size: 1.08rem;
            margin-bottom: 30px;
        }

        .hero-note {
            color: #c4c9d1;
            font-size: 0.85rem;
            margin-top: 20px;
        }

        /* General sections */

        .section {
            padding: 85px 0;
        }

        .section-alt {
            background: #101318;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .section-heading {
            max-width: 720px;
            text-align: center;
            margin: 0 auto 45px;
        }

        .section-label {
            display: inline-block;
            color: var(--primary);
            font-size: 0.8rem;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .section-heading h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900;
            margin: 12px 0 15px;
        }

        .section-heading p {
            color: var(--muted);
            margin-bottom: 0;
        }

        /* Membership */

        .plan-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 30px 25px;
            position: relative;
            transition: 0.3s;
        }

        .plan-card:hover {
            transform: translateY(-7px);
            border-color: rgba(255, 107, 44, 0.65);
        }

        .plan-card.featured {
            border: 1px solid var(--primary);
            box-shadow: 0 12px 35px rgba(255, 107, 44, 0.08);
        }

        .popular-label {
            position: absolute;
            right: 16px;
            top: 16px;
            background: var(--primary);
            color: #fff;
            border-radius: 30px;
            padding: 5px 10px;
            font-size: 0.68rem;
            font-weight: 900;
        }

        .plan-name {
            font-weight: 900;
            letter-spacing: 1.5px;
            color: var(--primary);
        }

        .plan-price {
            font-size: 3rem;
            font-weight: 900;
            line-height: 1.3;
            margin-top: 15px;
        }

        .plan-price span {
            font-size: 0.95rem;
            font-weight: 400;
            color: var(--muted);
        }

        .plan-description {
            color: var(--muted);
            min-height: 55px;
            margin: 15px 0 20px;
        }

        .plan-divider {
            border-color: var(--border);
            margin: 0 0 22px;
        }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 28px;
        }

        .plan-features li {
            margin-bottom: 12px;
            color: #d7dbe1;
            font-size: 0.92rem;
        }

        .plan-features li::before {
            content: "\\2713";
            color: var(--primary);
            font-weight: 900;
            margin-right: 10px;
        }

        .plan-button {
            width: 100%;
            text-align: center;
            margin-top: auto;
        }

        /* Trainers */

        .trainer-card {
            height: 100%;
            overflow: hidden;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            transition: 0.3s;
        }

        .trainer-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 107, 44, 0.55);
        }

        .trainer-image {
            width: 100%;
            height: 290px;
            object-fit: cover;
            object-position: center;
            background: var(--surface-light);
        }

        .trainer-body {
            padding: 23px;
        }

        .trainer-body h3 {
            font-size: 1.3rem;
            font-weight: 900;
            margin-bottom: 7px;
        }

        .trainer-specialty {
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .trainer-description {
            color: var(--muted);
            font-size: 0.92rem;
            min-height: 75px;
        }

        .trainer-experience {
            color: #d7dbe1;
            font-size: 0.85rem;
            margin-bottom: 18px;
        }

        /* Classes */

        .class-card {
            height: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 13px;
            padding: 25px;
            transition: 0.3s;
        }

        .class-card:hover {
            border-color: rgba(255, 107, 44, 0.6);
        }

        .class-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(255, 107, 44, 0.12);
            font-size: 1.7rem;
            margin-bottom: 20px;
        }

        .class-category {
            display: inline-block;
            color: var(--primary);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 1.3px;
            margin-bottom: 8px;
        }

        .class-card h3 {
            font-size: 1.3rem;
            font-weight: 900;
        }

        .class-details {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .class-details li {
            color: #c5cad2;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .class-details strong {
            color: #fff;
        }

        .spots-label {
            color: #91e6a3;
            font-size: 0.85rem;
            margin-bottom: 18px;
        }

        /* Feature strip */

        .feature-box {
            height: 100%;
            padding: 25px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .feature-icon {
            font-size: 1.8rem;
            margin-bottom: 12px;
        }

        .feature-box h3 {
            font-size: 1.1rem;
            font-weight: 800;
        }

        .feature-box p {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* CTA */

        .cta-section {
            padding: 75px 0;
            text-align: center;
            background:
                linear-gradient(
                    120deg,
                    rgba(255, 107, 44, 0.12),
                    rgba(255, 107, 44, 0.02)
                ),
                #111419;
            border-top: 1px solid var(--border);
        }

        .cta-section h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900;
            margin-bottom: 15px;
        }

        .cta-section p {
            color: var(--muted);
            max-width: 650px;
            margin: 0 auto 25px;
        }

        /* Footer */

        .footer {
            background: #08090b;
            border-top: 1px solid var(--border);
            padding: 35px 0;
        }

        .footer p {
            color: var(--muted);
            font-size: 0.88rem;
            margin: 5px 0;
        }

        .footer a {
            color: var(--primary);
        }

        .footer-links a {
            color: #c5cad2;
            margin: 0 8px;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        /* Demo notice */

        .demo-notice {
            color: #d5d9df;
            font-size: 0.85rem;
            padding: 15px 18px;
            background: rgba(255, 255, 255, 0.035);
            border: 1px solid var(--border);
            border-radius: 9px;
        }

        /* Responsive */

        @media (max-width: 991px) {
            .hero {
                min-height: 540px;
                background-position: 60% center;
            }

            .navbar-nav {
                padding-top: 12px;
            }

            .nav-link {
                margin-left: 0;
                padding: 9px 0;
            }
        }

        @media (max-width: 767px) {
            .hero {
                min-height: 530px;
                background:
                    linear-gradient(
                        rgba(11, 13, 16, 0.84),
                        rgba(11, 13, 16, 0.96)
                    ),
                    url("https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1000&q=80")
                    center/cover no-repeat;
            }

            .hero-content {
                padding-top: 65px;
                padding-bottom: 65px;
            }

            .section {
                padding: 60px 0;
            }

            .trainer-image {
                height: 320px;
            }

            .plan-description,
            .trainer-description {
                min-height: auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>

<!-- ==============================
     NAVIGATION
================================== -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
            FIT<span class="brand-highlight">CORE</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#memberNavbar"
                aria-controls="memberNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="memberNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link active" href="#home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#membership">Membership</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./trainers.php">Trainers</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./classes.php">Classes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./book_session.php">Book Session</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./my_bookings.php">My Bookings</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="payment_history.php">Payments</a>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a class="btn-fitcore" href="register.php">
                        Join Now
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>


<!-- ==============================
     HERO
================================== -->
<section class="hero" id="home">
    <div class="container">

        <div class="hero-content">

            <span class="eyebrow">
                YOUR FITNESS JOURNEY STARTS HERE
            </span>

            <h1>
                PUSH YOUR<br>
                <span>LIMITS.</span><br>
                OWN YOUR GOALS.
            </h1>

            <p>
                Discover a better way to train. Choose your membership,
                connect with professional trainers, join exciting fitness
                classes, and take the next step towards a stronger you.
            </p>

            <div class="d-flex flex-wrap gap-3">

                <a href="#membership" class="btn-fitcore">
                    Explore Memberships &rarr;
                </a>

                <a href="#classes" class="btn-outline-fitcore">
                    Explore Classes
                </a>

            </div>

            <div class="hero-note">
                <span style="color: var(--primary);">&#10003;</span>
                Flexible membership plans &nbsp; | &nbsp;
                Professional trainers &nbsp; | &nbsp;
                Easy session booking
            </div>

        </div>
    </div>
</section>


<!-- ==============================
     MEMBERSHIP PLANS
================================== -->
<section class="section" id="membership">

    <div class="container">

        <div class="section-heading">

            <span class="section-label">Membership Plans</span>

            <h2>Invest in Your Fitness</h2>

            <p>
                Choose a membership that matches your goals.
                Start with the essentials or unlock a more personalized
                fitness experience.
            </p>

        </div>

        <div class="row g-4">

            <?php foreach ($membershipPlans as $plan): ?>

                <div class="col-lg-4 col-md-6">

                    <div class="plan-card <?php echo $plan['popular'] ? 'featured' : ''; ?>">

                        <?php if ($plan['popular']): ?>
                            <span class="popular-label">MOST POPULAR</span>
                        <?php endif; ?>

                        <div class="plan-name">
                            <?php echo htmlspecialchars($plan['name']); ?>
                        </div>

                        <div class="plan-price">
                            $<?php echo number_format($plan['price'], 0); ?>
                            <span>/ month</span>
                        </div>

                        <p class="plan-description">
                            <?php echo htmlspecialchars($plan['description']); ?>
                        </p>

                        <hr class="plan-divider">

                        <ul class="plan-features">

                            <?php foreach ($plan['features'] as $feature): ?>

                                <li>
                                    <?php echo htmlspecialchars($feature); ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                        <a
                            href="register.php?plan=<?php echo urlencode(strtolower($plan['name'])); ?>"
                            class="btn-fitcore plan-button">

                            Choose <?php echo htmlspecialchars($plan['name']); ?>

                            &rarr;

                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <p class="text-center mt-4" style="color: var(--muted); font-size: 0.85rem;">
            Membership payments in this portfolio demonstration are simulated.
        </p>

    </div>
</section>


<!-- ==============================
     TRAINER PROFILES
================================== -->
<section class="section section-alt" id="trainers">

    <div class="container">

        <div class="section-heading">

            <span class="section-label">Meet Your Trainers</span>

            <h2>Train With the Best</h2>

            <p>
                Discover experienced fitness professionals who can
                guide your workouts and help you work towards your goals.
            </p>

        </div>

        <div class="row g-4">

            <?php foreach ($trainers as $trainer): ?>

                <div class="col-lg-4 col-md-6">

                    <div class="trainer-card">

                        <img
                            src="<?php echo htmlspecialchars($trainer['image']); ?>"
                            alt="<?php echo htmlspecialchars($trainer['name']); ?>"
                            class="trainer-image"
                            loading="lazy"
                        >

                        <div class="trainer-body">

                            <h3>
                                <?php echo htmlspecialchars($trainer['name']); ?>
                            </h3>

                            <div class="trainer-specialty">
                                <?php echo htmlspecialchars($trainer['specialty']); ?>
                            </div>

                            <p class="trainer-description">
                                <?php echo htmlspecialchars($trainer['description']); ?>
                            </p>

                            <div class="trainer-experience">
                                &#9733;
                                <?php echo htmlspecialchars($trainer['experience']); ?>
                            </div>

                            <a href="trainers.php" class="btn-outline-fitcore w-100 text-center">
                                View Trainer Details
                            </a>

                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="text-center mt-4">
            <a href="trainers.php" class="btn-fitcore">
                Explore All Trainers &rarr;
            </a>
        </div>

    </div>
</section>


<!-- ==============================
     FITNESS CLASS SCHEDULES
================================== -->
<section class="section" id="classes">

    <div class="container">

        <div class="section-heading">

            <span class="section-label">Fitness Classes</span>

            <h2>Find Your Next Workout</h2>

            <p>
                Explore sample classes, discover their trainers,
                and choose a session that suits your schedule.
            </p>

        </div>

        <div class="row g-4">

            <?php foreach ($classes as $class): ?>

                <div class="col-lg-4 col-md-6">

                    <div class="class-card">

                        <div class="class-icon" aria-hidden="true">
                            <?php echo $class['icon']; ?>
                        </div>

                        <div class="class-category">
                            <?php echo htmlspecialchars($class['category']); ?>
                        </div>

                        <h3>
                            <?php echo htmlspecialchars($class['name']); ?>
                        </h3>

                        <ul class="class-details">

                            <li>
                                <strong>Trainer:</strong>
                                <?php echo htmlspecialchars($class['trainer']); ?>
                            </li>

                            <li>
                                <strong>Day:</strong>
                                <?php echo htmlspecialchars($class['day']); ?>
                            </li>

                            <li>
                                <strong>Time:</strong>
                                <?php echo htmlspecialchars($class['time']); ?>
                            </li>

                            <li>
                                <strong>Level:</strong>
                                <?php echo htmlspecialchars($class['level']); ?>
                            </li>

                        </ul>

                        <div class="spots-label">
                            <?php echo (int) $class['spots']; ?> sample spaces available
                        </div>

                        <a
                            href="book_session.php"
                            class="btn-fitcore w-100 text-center">

                            Book This Session &rarr;

                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="text-center mt-4">
            <a href="classes.php" class="btn-outline-fitcore">
                View Full Class Schedule &rarr;
            </a>
        </div>

    </div>
</section>


<!-- ==============================
     MEMBER FEATURES
================================== -->
<section class="section section-alt">

    <div class="container">

        <div class="section-heading">

            <span class="section-label">Member Portal</span>

            <h2>Your Fitness, Your Control</h2>

            <p>
                Access the tools you need to manage your membership
                and training activities from one place.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon">&#128100;</div>

                    <h3>Member Registration</h3>

                    <p>
                        Create a member profile and choose a suitable
                        monthly membership.
                    </p>

                    <a href="register.php" class="btn-outline-fitcore mt-3">
                        Register &rarr;
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon">&#128197;</div>

                    <h3>Session Bookings</h3>

                    <p>
                        Browse available sessions and review or cancel
                        your existing bookings.
                    </p>

                    <a href="my_bookings.php" class="btn-outline-fitcore mt-3">
                        My Bookings &rarr;
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon">&#128179;</div>

                    <h3>Payment History</h3>

                    <p>
                        Explore a sample payment history for membership
                        subscriptions and related transactions.
                    </p>

                    <a href="payment_history.php" class="btn-outline-fitcore mt-3">
                        View Payments &rarr;
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon">&#128170;</div>

                    <h3>Personal Training</h3>

                    <p>
                        Discover trainers and find sessions that
                        support your fitness goals.
                    </p>

                    <a href="book_session.php" class="btn-outline-fitcore mt-3">
                        Book a Session &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ==============================
     CALL TO ACTION
================================== -->
<section class="cta-section">

    <div class="container">

        <span class="section-label">Your Journey Begins Today</span>

        <h2>Ready to Become Stronger?</h2>

        <p>
            Choose your membership, meet your trainers, and discover
            a smarter way to manage your fitness journey with FitCore.
        </p>

        <div class="d-flex justify-content-center flex-wrap gap-3">

            <a href="register.php" class="btn-fitcore">
                Become a Member &rarr;
            </a>

            <a href="../fitcore_demo.php" class="btn-outline-fitcore">
                Back to Demo Selection
            </a>

        </div>

    </div>

</section>


<!-- ==============================
     FOOTER
================================== -->
<footer class="footer">

    <div class="container">

        <div class="row gy-4 align-items-center">

            <div class="col-md-6 text-center text-md-start">

                <a href="index.php" class="navbar-brand">
                    FIT<span class="brand-highlight">CORE</span>
                </a>

                <p>Train Smarter. Get Stronger. Live Better.</p>

                <p>
                    A fitness platform demonstration by
                    <a href="../index.php">Codvanta</a>.
                </p>

            </div>

            <div class="col-md-6 text-center text-md-end">

                <div class="footer-links mb-2">
                    <a href="#home">Home</a>
                    <a href="#membership">Membership</a>
                    <a href="#trainers">Trainers</a>
                    <a href="#classes">Classes</a>
                </div>

                <p>
                    &copy; <?php echo date("Y"); ?> FitCore.
                    All rights reserved.
                </p>

                <p>
                    Portfolio demonstration only.
                    No real payments are processed.
                </p>

            </div>

        </div>

    </div>

</footer>


<!-- Local Bootstrap JavaScript -->
<script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>