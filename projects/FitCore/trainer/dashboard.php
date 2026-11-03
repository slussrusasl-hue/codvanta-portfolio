<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore - Trainer Dashboard
|--------------------------------------------------------------------------
| Demonstration-only trainer dashboard.
| Uses sample data and PHP sessions; no database is required.
*/

$trainerName = "Alex Morgan";
$trainerSpecialty = "Strength & Conditioning";
$trainerInitials = "AM";

$today = date("l, F j, Y");

// Sample appointments for the dashboard.
$appointments = [
    [
        "id" => "FC-TR-1001",
        "member" => "James Wilson",
        "initials" => "JW",
        "class" => "Strength Training",
        "date" => date("Y-m-d"),
        "time" => "09:00 AM - 10:00 AM",
        "status" => "Confirmed",
        "type" => "Personal Training"
    ],
    [
        "id" => "FC-TR-1002",
        "member" => "Emma Johnson",
        "initials" => "EJ",
        "class" => "Muscle Building",
        "date" => date("Y-m-d"),
        "time" => "11:00 AM - 12:00 PM",
        "status" => "Confirmed",
        "type" => "Personal Training"
    ],
    [
        "id" => "FC-TR-1003",
        "member" => "Noah Williams",
        "initials" => "NW",
        "class" => "Strength Training",
        "date" => date("Y-m-d", strtotime("+1 day")),
        "time" => "02:00 PM - 03:00 PM",
        "status" => "Pending",
        "type" => "Training Session"
    ],
    [
        "id" => "FC-TR-1004",
        "member" => "Olivia Brown",
        "initials" => "OB",
        "class" => "Functional Fitness",
        "date" => date("Y-m-d", strtotime("+1 day")),
        "time" => "04:00 PM - 05:00 PM",
        "status" => "Confirmed",
        "type" => "Training Session"
    ]
];

// Sample member list.
$members = [
    [
        "name" => "James Wilson",
        "initials" => "JW",
        "plan" => "Premium",
        "goal" => "Build Muscle",
        "status" => "Active"
    ],
    [
        "name" => "Emma Johnson",
        "initials" => "EJ",
        "plan" => "Pro",
        "goal" => "Weight Loss",
        "status" => "Active"
    ],
    [
        "name" => "Noah Williams",
        "initials" => "NW",
        "plan" => "Premium",
        "goal" => "Improve Strength",
        "status" => "Active"
    ],
    [
        "name" => "Olivia Brown",
        "initials" => "OB",
        "plan" => "Pro",
        "goal" => "Improve Fitness",
        "status" => "Active"
    ]
];

// Demonstration statistics.
$totalMembers = 48;
$totalAppointments = 126;
$completedSessions = 98;
$pendingRequests = 5;

$todayAppointments = array_filter($appointments, function ($appointment) {
    return $appointment["date"] === date("Y-m-d");
});

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function displayDate($date) {
    if ($date === date("Y-m-d")) {
        return "Today";
    }

    if ($date === date("Y-m-d", strtotime("+1 day"))) {
        return "Tomorrow";
    }

    return date("M d, Y", strtotime($date));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trainer Dashboard | FitCore</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --fc-bg: #101010;
            --fc-card: #191919;
            --fc-card-light: #202020;
            --fc-orange: #ff6b2c;
            --fc-orange-hover: #ff824c;
            --fc-text: #f5f5f5;
            --fc-muted: #a5a5a5;
            --fc-border: #303030;
            --fc-green: #38c987;
            --fc-blue: #63a8ff;
            --fc-yellow: #ffc857;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--fc-bg);
            color: var(--fc-text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 255px;
            padding: 25px 17px;
            background: #151515;
            border-right: 1px solid var(--fc-border);
            overflow-y: auto;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 10px 28px;
            border-bottom: 1px solid var(--fc-border);
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--fc-orange);
            color: #111;
            font-size: 24px;
            font-weight: bold;
        }

        .brand-name {
            font-size: 23px;
            font-weight: bold;
            letter-spacing: -0.5px;
        }

        .brand-name span {
            color: var(--fc-orange);
        }

        .brand-subtitle {
            color: var(--fc-muted);
            font-size: 11px;
            margin-top: 1px;
        }

        .sidebar-label {
            color: #777;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            padding: 25px 12px 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-radius: 9px;
            color: #bcbcbc;
            margin-bottom: 5px;
            font-size: 14px;
            transition: 0.2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 107, 44, 0.13);
            color: var(--fc-orange);
        }

        .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-bottom {
            margin-top: 30px;
            padding: 15px 12px;
            border: 1px solid var(--fc-border);
            background: #1b1b1b;
            border-radius: 12px;
        }

        .sidebar-bottom p {
            color: var(--fc-muted);
            font-size: 12px;
            margin-bottom: 10px;
        }

        .sidebar-bottom a {
            color: var(--fc-orange);
            font-size: 12px;
        }

        .main-content {
            margin-left: 255px;
            padding: 27px 32px 35px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 27px;
            font-weight: bold;
            margin: 0 0 5px;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--fc-muted);
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .today-label {
            padding: 10px 13px;
            border: 1px solid var(--fc-border);
            background: var(--fc-card);
            color: #ddd;
            border-radius: 9px;
            font-size: 12px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 107, 44, 0.17);
            border: 1px solid rgba(255, 107, 44, 0.45);
            color: var(--fc-orange);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 13px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .profile-name {
            font-size: 13px;
            font-weight: bold;
        }

        .profile-role {
            font-size: 11px;
            color: var(--fc-muted);
            margin-top: 3px;
        }

        .welcome-banner {
            position: relative;
            overflow: hidden;
            padding: 30px;
            border-radius: 16px;
            background:
                linear-gradient(100deg, rgba(28, 25, 23, 0.99) 0%,
                rgba(35, 25, 20, 0.95) 55%,
                rgba(65, 31, 17, 0.83) 100%);
            border: 1px solid #453026;
            margin-bottom: 25px;
        }

        .welcome-banner::after {
            content: "FIT";
            position: absolute;
            right: 30px;
            top: -30px;
            font-size: 150px;
            font-weight: bold;
            letter-spacing: -10px;
            color: rgba(255, 107, 44, 0.06);
            pointer-events: none;
        }

        .welcome-eyebrow {
            color: var(--fc-orange);
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .welcome-banner h2 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .welcome-banner p {
            color: #bdb5b0;
            max-width: 570px;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .btn-orange {
            background: var(--fc-orange);
            border: 1px solid var(--fc-orange);
            color: #111;
            border-radius: 8px;
            padding: 11px 17px;
            font-size: 13px;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-orange:hover {
            background: var(--fc-orange-hover);
            border-color: var(--fc-orange-hover);
            color: #111;
        }

        .btn-outline-light-custom {
            color: #eee;
            border: 1px solid #555;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 13px;
        }

        .btn-outline-light-custom:hover {
            background: #292929;
            color: #fff;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 17px;
        }

        .section-heading h3 {
            font-size: 17px;
            font-weight: bold;
            margin: 0;
        }

        .section-heading p {
            color: var(--fc-muted);
            font-size: 12px;
            margin: 5px 0 0;
        }

        .text-link {
            color: var(--fc-orange);
            font-size: 12px;
            white-space: nowrap;
        }

        .text-link:hover {
            color: var(--fc-orange-hover);
        }

        .stat-card {
            height: 100%;
            padding: 20px;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            transition: transform 0.2s, border-color 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: #514034;
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
        }

        .icon-orange {
            color: var(--fc-orange);
            background: rgba(255, 107, 44, 0.13);
        }

        .icon-green {
            color: var(--fc-green);
            background: rgba(56, 201, 135, 0.12);
        }

        .icon-blue {
            color: var(--fc-blue);
            background: rgba(99, 168, 255, 0.12);
        }

        .icon-yellow {
            color: var(--fc-yellow);
            background: rgba(255, 200, 87, 0.12);
        }

        .stat-tag {
            color: var(--fc-green);
            background: rgba(56, 201, 135, 0.1);
            padding: 5px 7px;
            border-radius: 5px;
            font-size: 10px;
        }

        .stat-number {
            font-size: 29px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--fc-muted);
        }

        .content-card {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 21px;
            height: 100%;
        }

        .appointment-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 0;
            border-bottom: 1px solid #2b2b2b;
        }

        .appointment-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .member-avatar {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border-radius: 11px;
            background: #292929;
            border: 1px solid #3b3b3b;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #eee;
            font-size: 12px;
            font-weight: bold;
        }

        .appointment-info {
            flex: 1;
            min-width: 0;
        }

        .appointment-name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .appointment-detail {
            font-size: 11px;
            color: var(--fc-muted);
            line-height: 1.6;
        }

        .status {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-confirmed {
            background: rgba(56, 201, 135, 0.12);
            color: var(--fc-green);
        }

        .status-pending {
            background: rgba(255, 200, 87, 0.12);
            color: var(--fc-yellow);
        }

        .status-active {
            background: rgba(56, 201, 135, 0.12);
            color: var(--fc-green);
        }

        .quick-action {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 14px;
            border: 1px solid #303030;
            background: #1d1d1d;
            border-radius: 10px;
            margin-bottom: 11px;
            color: var(--fc-text);
            transition: 0.2s;
        }

        .quick-action:last-child {
            margin-bottom: 0;
        }

        .quick-action:hover {
            border-color: var(--fc-orange);
            background: #24201e;
            color: #fff;
            transform: translateX(2px);
        }

        .quick-icon {
            width: 39px;
            height: 39px;
            border-radius: 9px;
            background: rgba(255, 107, 44, 0.12);
            color: var(--fc-orange);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .quick-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .quick-description {
            color: var(--fc-muted);
            font-size: 10px;
            line-height: 1.5;
        }

        .quick-arrow {
            color: #777;
            margin-left: auto;
            font-size: 18px;
        }

        .member-row {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 13px 0;
            border-bottom: 1px solid #2b2b2b;
        }

        .member-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .member-details {
            flex: 1;
            min-width: 0;
        }

        .member-details strong {
            font-size: 12px;
            display: block;
            margin-bottom: 4px;
        }

        .member-details span {
            color: var(--fc-muted);
            font-size: 11px;
        }

        .plan-label {
            font-size: 10px;
            padding: 5px 7px;
            border-radius: 5px;
            background: rgba(255, 107, 44, 0.12);
            color: var(--fc-orange);
        }

        .schedule-day {
            border: 1px solid var(--fc-border);
            border-radius: 9px;
            padding: 13px 14px;
            margin-bottom: 10px;
            background: #1b1b1b;
        }

        .schedule-day:last-child {
            margin-bottom: 0;
        }

        .schedule-day-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 7px;
        }

        .schedule-day strong {
            font-size: 12px;
        }

        .schedule-time {
            color: var(--fc-orange);
            font-size: 11px;
        }

        .schedule-description {
            font-size: 11px;
            color: var(--fc-muted);
        }

        .availability-pill {
            font-size: 10px;
            color: var(--fc-green);
        }

        .footer {
            border-top: 1px solid var(--fc-border);
            margin-top: 30px;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            color: #777;
            font-size: 11px;
        }

        .footer a {
            color: var(--fc-orange);
        }

        .mobile-menu-btn {
            display: none;
            border: 1px solid var(--fc-border);
            background: var(--fc-card);
            color: #fff;
            border-radius: 8px;
            padding: 8px 11px;
            margin-right: 10px;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1199px) {
            .main-content {
                padding: 24px;
            }

            .sidebar {
                width: 230px;
            }

            .main-content {
                margin-left: 230px;
            }

            .today-label {
                display: none;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 22px;
            }

            .mobile-menu-btn {
                display: inline-block;
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 999;
                background: rgba(0, 0, 0, 0.65);
            }
        }

        @media (max-width: 575px) {
            .main-content {
                padding: 17px 13px;
            }

            .topbar {
                align-items: flex-start;
                margin-bottom: 22px;
            }

            .page-title {
                font-size: 21px;
            }

            .page-subtitle {
                font-size: 11px;
            }

            .profile-name,
            .profile-role {
                display: none;
            }

            .welcome-banner {
                padding: 23px 19px;
            }

            .welcome-banner h2 {
                font-size: 23px;
            }

            .welcome-banner::after {
                right: 5px;
                top: 0;
                font-size: 90px;
            }

            .section-heading h3 {
                font-size: 15px;
            }

            .content-card {
                padding: 16px;
            }

            .appointment-item {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .appointment-item .status {
                margin-left: 52px;
            }

            .footer {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<!-- Mobile sidebar overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">

    <a href="dashboard.php" class="brand">
        <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
        <div>
            <div class="brand-name">Fit<span>Core</span></div>
            <div class="brand-subtitle">TRAINER PORTAL</div>
        </div>
    </a>

    <div class="sidebar-label">Workspace</div>

    <a href="dashboard.php" class="sidebar-link active">
        <span class="nav-icon">▦</span>
        Dashboard
    </a>

    <a href="appointments.php" class="sidebar-link">
        <span class="nav-icon">▣</span>
        Appointments
    </a>

    <a href="availability.php" class="sidebar-link">
        <span class="nav-icon">◷</span>
        Manage Availability
    </a>

    <a href="schedule_session.php" class="sidebar-link">
        <span class="nav-icon">＋</span>
        Schedule Sessions
    </a>

    <div class="sidebar-label">Members</div>

    <a href="members.php" class="sidebar-link">
        <span class="nav-icon">♙</span>
        My Members
    </a>

    <a href="session_notes.php" class="sidebar-link">
        <span class="nav-icon">▤</span>
        Session Notes
    </a>

    <div class="sidebar-label">Other</div>

    <a href="../customer/index.php" class="sidebar-link">
        <span class="nav-icon">⌂</span>
        Member Website
    </a>

    <a href="../fitcore_demo.php" class="sidebar-link">
        <span class="nav-icon">↩</span>
        Demo Role Selection
    </a>

    <div class="sidebar-bottom">
        <p><strong style="color:#eee;">Trainer Demo Mode</strong></p>
        <p>Explore FitCore's trainer workflow using sample information.</p>
        <a href="../index.php">← Back to Codvanta</a>
    </div>

</aside>

<!-- Main content -->
<main class="main-content">

    <!-- Top bar -->
    <header class="topbar">

        <div class="d-flex align-items-start">
            <button class="mobile-menu-btn" type="button"
                    onclick="toggleSidebar()" aria-label="Open navigation">
                ☰
            </button>

            <div>
                <h1 class="page-title">Trainer Dashboard</h1>
                <p class="page-subtitle">
                    Overview of your training activities and members.
                </p>
            </div>
        </div>

        <div class="topbar-right">

            <div class="today-label">
                ◷ &nbsp; <?= e($today) ?>
            </div>

            <div class="profile">
                <div class="avatar"><?= e($trainerInitials) ?></div>
                <div>
                    <div class="profile-name"><?= e($trainerName) ?></div>
                    <div class="profile-role"><?= e($trainerSpecialty) ?></div>
                </div>
            </div>

        </div>

    </header>

    <!-- Welcome banner -->
    <section class="welcome-banner">

        <div class="welcome-eyebrow">Your fitness workspace</div>

        <h2>Welcome back, <?= e(explode(" ", $trainerName)[0]) ?>! 💪</h2>

        <p>
            Help your members reach their fitness goals. Review upcoming
            appointments, manage your availability, and keep track of
            each member's training progress from one place.
        </p>

        <div class="d-flex flex-wrap gap-2">
            <a href="appointments.php" class="btn btn-orange">
                View Appointments &nbsp; →
            </a>

            <a href="schedule_session.php" class="btn btn-outline-light-custom">
                + Schedule a Session
            </a>
        </div>

    </section>

    <!-- Statistics -->
    <section class="mb-4">

        <div class="section-heading">
            <div>
                <h3>Your Overview</h3>
                <p>Summary of your training activity</p>
            </div>
        </div>

        <div class="row g-3">

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-orange">♙</div>
                        <span class="stat-tag">Active</span>
                    </div>
                    <div class="stat-number"><?= number_format($totalMembers) ?></div>
                    <div class="stat-label">Assigned Members</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-blue">▣</div>
                        <span class="stat-tag">All time</span>
                    </div>
                    <div class="stat-number"><?= number_format($totalAppointments) ?></div>
                    <div class="stat-label">Total Appointments</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-green">✓</div>
                        <span class="stat-tag">Completed</span>
                    </div>
                    <div class="stat-number"><?= number_format($completedSessions) ?></div>
                    <div class="stat-label">Sessions Completed</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-yellow">◷</div>
                        <span class="stat-tag"
                              style="color:var(--fc-yellow);background:rgba(255,200,87,.1);">
                            To review
                        </span>
                    </div>
                    <div class="stat-number"><?= number_format($pendingRequests) ?></div>
                    <div class="stat-label">Pending Requests</div>
                </div>
            </div>

        </div>

    </section>

    <!-- Appointments and quick actions -->
    <section class="mb-4">

        <div class="row g-3">

            <!-- Today's appointments -->
            <div class="col-12 col-xl-7">

                <div class="content-card">

                    <div class="section-heading">
                        <div>
                            <h3>Today's Appointments</h3>
                            <p>Your scheduled training sessions for today</p>
                        </div>

                        <a href="appointments.php" class="text-link">
                            View all →
                        </a>
                    </div>

                    <?php if (count($todayAppointments) > 0): ?>

                        <?php foreach ($todayAppointments as $appointment): ?>

                            <div class="appointment-item">

                                <div class="member-avatar">
                                    <?= e($appointment["initials"]) ?>
                                </div>

                                <div class="appointment-info">

                                    <div class="appointment-name">
                                        <?= e($appointment["member"]) ?>
                                    </div>

                                    <div class="appointment-detail">
                                        <?= e($appointment["class"]) ?><br>
                                        <?= e($appointment["time"]) ?>
                                    </div>

                                </div>

                                <span class="status <?= $appointment["status"] === "Confirmed" ? "status-confirmed" : "status-pending" ?>">
                                    <?= e($appointment["status"]) ?>
                                </span>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="text-center py-4">
                            <div style="font-size:32px;color:#666;">◷</div>
                            <p class="text-secondary mb-0 mt-2">
                                You have no appointments scheduled for today.
                            </p>
                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <!-- Quick actions -->
            <div class="col-12 col-xl-5">

                <div class="content-card">

                    <div class="section-heading">
                        <div>
                            <h3>Quick Actions</h3>
                            <p>Manage your daily training tasks</p>
                        </div>
                    </div>

                    <a href="appointments.php" class="quick-action">
                        <div class="quick-icon">▣</div>
                        <div>
                            <div class="quick-title">View Appointments</div>
                            <div class="quick-description">
                                Review member bookings and session requests.
                            </div>
                        </div>
                        <span class="quick-arrow">›</span>
                    </a>

                    <a href="availability.php" class="quick-action">
                        <div class="quick-icon">◷</div>
                        <div>
                            <div class="quick-title">Manage Availability</div>
                            <div class="quick-description">
                                Set the days and hours you are available.
                            </div>
                        </div>
                        <span class="quick-arrow">›</span>
                    </a>

                    <a href="members.php" class="quick-action">
                        <div class="quick-icon">♙</div>
                        <div>
                            <div class="quick-title">View My Members</div>
                            <div class="quick-description">
                                Check member profiles and fitness goals.
                            </div>
                        </div>
                        <span class="quick-arrow">›</span>
                    </a>

                    <a href="session_notes.php" class="quick-action">
                        <div class="quick-icon">▤</div>
                        <div>
                            <div class="quick-title">Record Session Notes</div>
                            <div class="quick-description">
                                Keep notes about training sessions and progress.
                            </div>
                        </div>
                        <span class="quick-arrow">›</span>
                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- Members and schedule -->
    <section class="mb-4">

        <div class="row g-3">

            <!-- Recent members -->
            <div class="col-12 col-xl-6">

                <div class="content-card">

                    <div class="section-heading">
                        <div>
                            <h3>My Members</h3>
                            <p>Members currently assigned to you</p>
                        </div>

                        <a href="members.php" class="text-link">
                            All members →
                        </a>
                    </div>

                    <?php foreach ($members as $member): ?>

                        <div class="member-row">

                            <div class="member-avatar">
                                <?= e($member["initials"]) ?>
                            </div>

                            <div class="member-details">
                                <strong><?= e($member["name"]) ?></strong>
                                <span>
                                    <?= e($member["goal"]) ?> ·
                                    <?= e($member["status"]) ?>
                                </span>
                            </div>

                            <span class="plan-label">
                                <?= e($member["plan"]) ?>
                            </span>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

            <!-- Upcoming schedule -->
            <div class="col-12 col-xl-6">

                <div class="content-card">

                    <div class="section-heading">
                        <div>
                            <h3>Upcoming Schedule</h3>
                            <p>Your next scheduled training sessions</p>
                        </div>

                        <a href="schedule_session.php" class="text-link">
                            Manage →
                        </a>
                    </div>

                    <?php
                    $upcomingAppointments = array_filter(
                        $appointments,
                        function ($appointment) {
                            return $appointment["date"] >= date("Y-m-d");
                        }
                    );
                    ?>

                    <?php foreach ($upcomingAppointments as $appointment): ?>

                        <div class="schedule-day">

                            <div class="schedule-day-top">
                                <strong>
                                    <?= e(displayDate($appointment["date"])) ?>
                                    · <?= e($appointment["member"]) ?>
                                </strong>

                                <span class="availability-pill">
                                    <?= e($appointment["status"]) ?>
                                </span>
                            </div>

                            <div class="schedule-time">
                                <?= e($appointment["time"]) ?>
                            </div>

                            <div class="schedule-description mt-1">
                                <?= e($appointment["class"]) ?>
                                · <?= e($appointment["type"]) ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

    </section>

    <!-- Footer -->
    <footer class="footer">
        <div>
            © <?= date("Y") ?> FitCore Trainer Portal.
            Demonstration website.
        </div>

        <div>
            Developed by
            <a href="../index.php">Codvanta</a>
            · Turning Vision into Digital Reality.
        </div>
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("sidebarOverlay");

        sidebar.classList.toggle("open");
        overlay.classList.toggle("show");
    }

    // Close the mobile navigation when a menu link is selected.
    document.querySelectorAll(".sidebar-link").forEach(function(link) {
        link.addEventListener("click", function() {
            if (window.innerWidth <= 991) {
                document.getElementById("sidebar").classList.remove("open");
                document.getElementById("sidebarOverlay").classList.remove("show");
            }
        });
    });
</script>

</body>
</html>