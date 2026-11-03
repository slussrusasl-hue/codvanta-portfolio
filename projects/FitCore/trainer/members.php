
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore - Trainer Members
|--------------------------------------------------------------------------
| Demonstration page only.
| Member information is sample data stored in this file.
| No database connection is required.
|--------------------------------------------------------------------------
*/

$trainerName = "Alex Morgan";
$trainerSpecialty = "Strength & Conditioning";

/*
|--------------------------------------------------------------------------
| Sample Member Data
|--------------------------------------------------------------------------
*/

$members = [
    [
        "id" => "FCM-1001",
        "name" => "Olivia Bennett",
        "email" => "olivia.bennett@example.com",
        "phone" => "+1 555-0101",
        "membership" => "Premium",
        "joined" => "2026-08-12",
        "goal" => "Build Muscle",
        "sessions" => 12,
        "status" => "Active",
        "avatar" => "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80"
    ],
    [
        "id" => "FCM-1002",
        "name" => "Ethan Brooks",
        "email" => "ethan.brooks@example.com",
        "phone" => "+1 555-0102",
        "membership" => "Pro",
        "joined" => "2026-08-18",
        "goal" => "Weight Loss",
        "sessions" => 8,
        "status" => "Active",
        "avatar" => "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80"
    ],
    [
        "id" => "FCM-1003",
        "name" => "Sophia Mitchell",
        "email" => "sophia.mitchell@example.com",
        "phone" => "+1 555-0103",
        "membership" => "Premium",
        "joined" => "2026-07-25",
        "goal" => "Improve Flexibility",
        "sessions" => 15,
        "status" => "Active",
        "avatar" => "https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=120&q=80"
    ],
    [
        "id" => "FCM-1004",
        "name" => "Noah Wilson",
        "email" => "noah.wilson@example.com",
        "phone" => "+1 555-0104",
        "membership" => "Basic",
        "joined" => "2026-09-02",
        "goal" => "General Fitness",
        "sessions" => 4,
        "status" => "Active",
        "avatar" => "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=120&q=80"
    ],
    [
        "id" => "FCM-1005",
        "name" => "Ava Thompson",
        "email" => "ava.thompson@example.com",
        "phone" => "+1 555-0105",
        "membership" => "Pro",
        "joined" => "2026-08-29",
        "goal" => "Build Muscle",
        "sessions" => 10,
        "status" => "Active",
        "avatar" => "https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=120&q=80"
    ],
    [
        "id" => "FCM-1006",
        "name" => "Liam Anderson",
        "email" => "liam.anderson@example.com",
        "phone" => "+1 555-0106",
        "membership" => "Basic",
        "joined" => "2026-06-14",
        "goal" => "Weight Loss",
        "sessions" => 6,
        "status" => "Inactive",
        "avatar" => "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80"
    ],
    [
        "id" => "FCM-1007",
        "name" => "Isabella Clark",
        "email" => "isabella.clark@example.com",
        "phone" => "+1 555-0107",
        "membership" => "Premium",
        "joined" => "2026-09-10",
        "goal" => "Improve Endurance",
        "sessions" => 5,
        "status" => "Active",
        "avatar" => "https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=120&q=80"
    ],
    [
        "id" => "FCM-1008",
        "name" => "James Parker",
        "email" => "james.parker@example.com",
        "phone" => "+1 555-0108",
        "membership" => "Pro",
        "joined" => "2026-07-31",
        "goal" => "General Fitness",
        "sessions" => 9,
        "status" => "Active",
        "avatar" => "https://images.unsplash.com/photo-1504257432389-52343af06ae3?auto=format&fit=crop&w=120&q=80"
    ]
];

/*
|--------------------------------------------------------------------------
| Search and Filter
|--------------------------------------------------------------------------
*/

$search = trim($_GET["search"] ?? "");
$membershipFilter = $_GET["membership"] ?? "all";
$statusFilter = $_GET["status"] ?? "all";
$goalFilter = $_GET["goal"] ?? "all";

$allowedMemberships = ["all", "Basic", "Pro", "Premium"];
$allowedStatuses = ["all", "Active", "Inactive"];
$allowedGoals = [
    "all",
    "Build Muscle",
    "Weight Loss",
    "Improve Flexibility",
    "Improve Endurance",
    "General Fitness"
];

if (!in_array($membershipFilter, $allowedMemberships, true)) {
    $membershipFilter = "all";
}

if (!in_array($statusFilter, $allowedStatuses, true)) {
    $statusFilter = "all";
}

if (!in_array($goalFilter, $allowedGoals, true)) {
    $goalFilter = "all";
}

$filteredMembers = array_filter($members, function ($member) use (
    $search,
    $membershipFilter,
    $statusFilter,
    $goalFilter
) {
    $searchText = strtolower(
        $member["name"] . " " .
        $member["id"] . " " .
        $member["email"] . " " .
        $member["goal"]
    );

    $matchesSearch = $search === "" ||
        strpos($searchText, strtolower($search)) !== false;

    $matchesMembership = $membershipFilter === "all" ||
        $member["membership"] === $membershipFilter;

    $matchesStatus = $statusFilter === "all" ||
        $member["status"] === $statusFilter;

    $matchesGoal = $goalFilter === "all" ||
        $member["goal"] === $goalFilter;

    return $matchesSearch &&
        $matchesMembership &&
        $matchesStatus &&
        $matchesGoal;
});

$totalMembers = count($members);

$activeMembers = count(array_filter($members, function ($member) {
    return $member["status"] === "Active";
}));

$premiumMembers = count(array_filter($members, function ($member) {
    return $member["membership"] === "Premium";
}));

$totalSessions = array_sum(array_column($members, "sessions"));

/*
|--------------------------------------------------------------------------
| Demo Member Details
|--------------------------------------------------------------------------
*/

$selectedMember = null;
$selectedMemberId = $_GET["view"] ?? "";

if ($selectedMemberId !== "") {
    foreach ($members as $member) {
        if ($member["id"] === $selectedMemberId) {
            $selectedMember = $member;
            break;
        }
    }
}

function escapeHtml($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function membershipClass($membership)
{
    switch ($membership) {
        case "Premium":
            return "premium";
        case "Pro":
            return "pro";
        default:
            return "basic";
    }
}

function statusClass($status)
{
    return $status === "Active" ? "active" : "inactive";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Members | FitCore Trainer</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --fc-bg: #101010;
            --fc-sidebar: #151515;
            --fc-card: #191919;
            --fc-card-light: #202020;
            --fc-orange: #ff6b2c;
            --fc-orange-hover: #ff824d;
            --fc-text: #f7f7f7;
            --fc-muted: #a2a2a2;
            --fc-border: #303030;
            --fc-green: #43d99b;
            --fc-red: #ff7272;
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

        .sidebar {
            width: 250px;
            position: fixed;
            inset: 0 auto 0 0;
            background: var(--fc-sidebar);
            border-right: 1px solid var(--fc-border);
            padding: 25px 17px;
            overflow-y: auto;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--fc-text);
            font-size: 23px;
            font-weight: 800;
            padding: 0 10px;
            margin-bottom: 36px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: var(--fc-orange);
            color: #111;
            display: grid;
            place-items: center;
            border-radius: 12px;
            font-size: 23px;
        }

        .brand span:last-child span {
            color: var(--fc-orange);
        }

        .sidebar-label {
            color: #777;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            padding: 0 12px;
            margin: 23px 0 10px;
            text-transform: uppercase;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 13px;
            border-radius: 9px;
            color: #aaa;
            font-size: 13px;
            margin-bottom: 5px;
            transition: .2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
        }

        .sidebar-link.active {
            border-left: 3px solid var(--fc-orange);
            padding-left: 10px;
        }

        .sidebar-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .trainer-mini {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 15px 10px;
            border-top: 1px solid var(--fc-border);
            margin-top: 28px;
        }

        .trainer-avatar {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #333;
            color: var(--fc-orange);
            font-weight: 800;
        }

        .trainer-mini strong {
            display: block;
            font-size: 12px;
        }

        .trainer-mini small {
            color: var(--fc-muted);
            font-size: 10px;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px 34px 45px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .breadcrumb-text {
            color: var(--fc-muted);
            font-size: 12px;
            margin-bottom: 7px;
        }

        .breadcrumb-text span {
            color: var(--fc-orange);
        }

        .page-title {
            font-size: clamp(25px, 3vw, 32px);
            font-weight: 800;
            margin: 0;
        }

        .page-subtitle {
            color: var(--fc-muted);
            font-size: 13px;
            margin-top: 7px;
        }

        .btn-orange {
            background: var(--fc-orange);
            border: 1px solid var(--fc-orange);
            color: #111;
            font-size: 12px;
            font-weight: 800;
            padding: 11px 17px;
            border-radius: 8px;
        }

        .btn-orange:hover {
            background: var(--fc-orange-hover);
            border-color: var(--fc-orange-hover);
            color: #111;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #444;
            color: #ddd;
            font-size: 11px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 7px;
        }

        .btn-outline:hover {
            border-color: var(--fc-orange);
            color: var(--fc-orange);
        }

        .stat-card {
            height: 100%;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
            padding: 22px;
            position: relative;
            overflow: hidden;
        }

        .stat-label {
            color: var(--fc-muted);
            font-size: 12px;
            margin-bottom: 13px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .stat-note {
            font-size: 11px;
            color: #858585;
        }

        .stat-icon {
            position: absolute;
            right: 20px;
            top: 20px;
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            border-radius: 11px;
            font-size: 20px;
        }

        .section-card {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
            padding: 22px;
            margin-top: 25px;
        }

        .section-heading {
            font-size: 17px;
            font-weight: 800;
            margin: 0;
        }

        .section-description {
            color: var(--fc-muted);
            font-size: 12px;
            margin-top: 6px;
        }

        .filter-label {
            display: block;
            color: #bbb;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background-color: #111;
            color: #eee;
            border: 1px solid #383838;
            border-radius: 8px;
            font-size: 12px;
            min-height: 42px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #111;
            color: #fff;
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 .2rem rgba(255, 107, 44, .12);
        }

        .form-select option {
            background: #191919;
            color: #fff;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .members-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
            margin-top: 20px;
        }

        .members-table th {
            padding: 15px 13px;
            color: #888;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--fc-border);
            font-weight: 800;
            white-space: nowrap;
        }

        .members-table td {
            padding: 16px 13px;
            border-bottom: 1px solid #292929;
            vertical-align: middle;
            font-size: 12px;
        }

        .members-table tbody tr:hover {
            background: rgba(255, 255, 255, .018);
        }

        .member-profile {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 190px;
        }

        .member-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #3a3a3a;
            flex-shrink: 0;
        }

        .member-name {
            font-weight: 800;
            font-size: 12px;
            color: #f5f5f5;
            display: block;
        }

        .member-id {
            display: block;
            color: #888;
            font-size: 10px;
            margin-top: 5px;
        }

        .muted-text {
            color: var(--fc-muted);
        }

        .membership-badge,
        .status-badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
        }

        .membership-badge.basic {
            background: #292929;
            color: #bbb;
        }

        .membership-badge.pro {
            background: rgba(78, 153, 255, .13);
            color: #82b7ff;
        }

        .membership-badge.premium {
            background: rgba(255, 107, 44, .13);
            color: var(--fc-orange);
        }

        .status-badge.active {
            background: rgba(67, 217, 155, .12);
            color: var(--fc-green);
        }

        .status-badge.inactive {
            background: rgba(255, 114, 114, .12);
            color: var(--fc-red);
        }

        .goal-tag {
            color: #ddd;
            font-size: 11px;
        }

        .session-count {
            font-size: 15px;
            font-weight: 800;
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px;
            color: var(--fc-muted);
        }

        .empty-icon {
            font-size: 38px;
            margin-bottom: 15px;
        }

        .detail-panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
            padding: 24px;
            margin-top: 25px;
        }

        .detail-avatar {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--fc-orange);
        }

        .detail-value {
            color: #f2f2f2;
            font-size: 13px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .detail-label {
            color: var(--fc-muted);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 7px;
        }

        .notice {
            background: rgba(255, 107, 44, .08);
            border: 1px solid rgba(255, 107, 44, .2);
            border-radius: 9px;
            padding: 13px 15px;
            color: #d6b09f;
            font-size: 11px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid var(--fc-border);
            color: #777;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .footer a {
            color: var(--fc-orange);
        }

        .mobile-topbar {
            display: none;
        }

        @media (max-width: 1100px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
                padding: 25px 22px 40px;
            }
        }

        @media (max-width: 767px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 18px 14px 30px;
            }

            .mobile-topbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: var(--fc-sidebar);
                border: 1px solid var(--fc-border);
                border-radius: 10px;
                padding: 13px;
                margin-bottom: 22px;
            }

            .mobile-brand {
                font-weight: 800;
                color: #fff;
            }

            .mobile-brand span {
                color: var(--fc-orange);
            }

            .mobile-menu {
                background: transparent;
                border: 1px solid #383838;
                border-radius: 7px;
                padding: 6px 10px;
                color: #fff;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
                margin-bottom: 22px;
            }

            .topbar .btn-orange {
                width: 100%;
            }

            .stat-value {
                font-size: 25px;
            }

            .section-card {
                padding: 16px;
            }

            .footer {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar">
    <a href="dashboard.php" class="brand">
        <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
        <span>FIT<span>CORE</span></span>
    </a>

    <div class="sidebar-label">Trainer Workspace</div>

    <a href="dashboard.php" class="sidebar-link">
        <span class="sidebar-icon">▦</span>
        Dashboard
    </a>

    <a href="appointments.php" class="sidebar-link">
        <span class="sidebar-icon">▣</span>
        Appointments
    </a>

    <a href="availability.php" class="sidebar-link">
        <span class="sidebar-icon">◷</span>
        My Availability
    </a>

    <a href="schedule_session.php" class="sidebar-link">
        <span class="sidebar-icon">＋</span>
        Schedule Session
    </a>

    <a href="members.php" class="sidebar-link active">
        <span class="sidebar-icon">♙</span>
        Members
    </a>

    <a href="session_notes.php" class="sidebar-link">
        <span class="sidebar-icon">▤</span>
        Session Notes
    </a>

    <div class="sidebar-label">Quick Links</div>

    <a href="../customer/index.php" class="sidebar-link">
        <span class="sidebar-icon">⌂</span>
        Member Website
    </a>

    <a href="../fitcore_demo.php" class="sidebar-link">
        <span class="sidebar-icon">↩</span>
        Demo Selection
    </a>

    <a href="../index.php" class="sidebar-link">
        <span class="sidebar-icon">🌐</span>
        Codvanta Home
    </a>

    <div class="trainer-mini">
        <div class="trainer-avatar">AM</div>
        <div>
            <strong><?= escapeHtml($trainerName) ?></strong>
            <small><?= escapeHtml($trainerSpecialty) ?></small>
        </div>
    </div>
</aside>

<!-- Main Content -->
<main class="main-content">

    <!-- Mobile Navigation -->
    <div class="mobile-topbar">
        <a href="dashboard.php" class="mobile-brand">
            ⚡ FIT<span>CORE</span>
        </a>

        <button class="mobile-menu"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mobileNavigation"
                aria-expanded="false"
                aria-controls="mobileNavigation">
            ☰ Menu
        </button>
    </div>

    <div class="collapse mb-4" id="mobileNavigation">
        <div class="section-card mt-0">
            <a href="dashboard.php" class="sidebar-link">▦ Dashboard</a>
            <a href="appointments.php" class="sidebar-link">▣ Appointments</a>
            <a href="availability.php" class="sidebar-link">◷ My Availability</a>
            <a href="schedule_session.php" class="sidebar-link">＋ Schedule Session</a>
            <a href="members.php" class="sidebar-link active">♙ Members</a>
            <a href="session_notes.php" class="sidebar-link">▤ Session Notes</a>
            <a href="../customer/index.php" class="sidebar-link">⌂ Member Website</a>
            <a href="../fitcore_demo.php" class="sidebar-link">↩ Demo Selection</a>
        </div>
    </div>

    <!-- Page Heading -->
    <div class="topbar">
        <div>
            <div class="breadcrumb-text">
                Trainer Workspace / <span>Members</span>
            </div>

            <h1 class="page-title">Member Directory</h1>

            <p class="page-subtitle">
                View member profiles, membership plans, fitness goals and training activity.
            </p>
        </div>

        <a href="schedule_session.php" class="btn btn-orange">
            + Schedule a Session
        </a>
    </div>

    <!-- Statistics -->
    <div class="row g-3">

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon">♙</div>
                <div class="stat-label">Total Members</div>
                <div class="stat-value"><?= $totalMembers ?></div>
                <div class="stat-note">Sample member profiles</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon">✓</div>
                <div class="stat-label">Active Members</div>
                <div class="stat-value"><?= $activeMembers ?></div>
                <div class="stat-note">Currently marked active</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon">★</div>
                <div class="stat-label">Premium Members</div>
                <div class="stat-value"><?= $premiumMembers ?></div>
                <div class="stat-note">Premium membership plan</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon">◷</div>
                <div class="stat-label">Training Sessions</div>
                <div class="stat-value"><?= $totalSessions ?></div>
                <div class="stat-note">Combined sample activity</div>
            </div>
        </div>

    </div>

    <!-- Member Details Panel -->
    <?php if ($selectedMember !== null): ?>
        <section class="detail-panel" id="memberDetails">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
                <div class="d-flex align-items-center gap-3">
                    <img
                        src="<?= escapeHtml($selectedMember["avatar"]) ?>"
                        alt="<?= escapeHtml($selectedMember["name"]) ?>"
                        class="detail-avatar"
                    >

                    <div>
                        <h2 class="section-heading">
                            <?= escapeHtml($selectedMember["name"]) ?>
                        </h2>

                        <div class="muted-text small mt-2">
                            <?= escapeHtml($selectedMember["id"]) ?>
                        </div>

                        <span class="membership-badge <?= membershipClass($selectedMember["membership"]) ?> mt-2">
                            <?= escapeHtml($selectedMember["membership"]) ?> Member
                        </span>
                    </div>
                </div>

                <a
                    href="members.php<?= !empty($_SERVER["QUERY_STRING"]) ? "?" . escapeHtml(http_build_query(array_merge($_GET, ["view" => null]))) : "" ?>"
                    class="btn btn-outline"
                >
                    ✕ Close Details
                </a>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value">
                        <?= escapeHtml($selectedMember["email"]) ?>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value">
                        <?= escapeHtml($selectedMember["phone"]) ?>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="detail-label">Fitness Goal</div>
                    <div class="detail-value">
                        <?= escapeHtml($selectedMember["goal"]) ?>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="detail-label">Membership Status</div>
                    <div class="detail-value">
                        <span class="status-badge <?= statusClass($selectedMember["status"]) ?>">
                            <?= escapeHtml($selectedMember["status"]) ?>
                        </span>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="detail-label">Registration Date</div>
                    <div class="detail-value">
                        <?= date("d M Y", strtotime($selectedMember["joined"])) ?>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="detail-label">Completed Sessions</div>
                    <div class="detail-value">
                        <?= (int)$selectedMember["sessions"] ?> sessions
                    </div>
                </div>
            </div>

            <div class="notice">
                This profile contains sample demonstration information. Contact details and
                training history are not connected to a real member account.
            </div>
        </section>
    <?php elseif ($selectedMemberId !== ""): ?>
        <div class="alert alert-warning mt-4">
            The requested member profile could not be found.
        </div>
    <?php endif; ?>

    <!-- Search and Filters -->
    <section class="section-card">

        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
            <div>
                <h2 class="section-heading">All Members</h2>
                <p class="section-description">
                    Search member profiles and narrow the results using filters.
                </p>
            </div>

            <span class="membership-badge premium">
                <?= count($filteredMembers) ?> results
            </span>
        </div>

        <form method="GET" action="members.php" id="memberFilterForm">
            <div class="row g-3 align-items-end">

                <div class="col-12 col-md-6 col-xl-3">
                    <label class="filter-label" for="search">Search Members</label>
                    <input
                        type="search"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Name, member ID, email..."
                        value="<?= escapeHtml($search) ?>"
                    >
                </div>

                <div class="col-12 col-md-6 col-xl-2">
                    <label class="filter-label" for="membership">Membership</label>
                    <select name="membership" id="membership" class="form-select">
                        <option value="all" <?= $membershipFilter === "all" ? "selected" : "" ?>>
                            All Plans
                        </option>
                        <option value="Basic" <?= $membershipFilter === "Basic" ? "selected" : "" ?>>
                            Basic
                        </option>
                        <option value="Pro" <?= $membershipFilter === "Pro" ? "selected" : "" ?>>
                            Pro
                        </option>
                        <option value="Premium" <?= $membershipFilter === "Premium" ? "selected" : "" ?>>
                            Premium
                        </option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl-2">
                    <label class="filter-label" for="status">Member Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="all" <?= $statusFilter === "all" ? "selected" : "" ?>>
                            All Statuses
                        </option>
                        <option value="Active" <?= $statusFilter === "Active" ? "selected" : "" ?>>
                            Active
                        </option>
                        <option value="Inactive" <?= $statusFilter === "Inactive" ? "selected" : "" ?>>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label class="filter-label" for="goal">Fitness Goal</label>
                    <select name="goal" id="goal" class="form-select">
                        <?php foreach ($allowedGoals as $goal): ?>
                            <option
                                value="<?= escapeHtml($goal) ?>"
                                <?= $goalFilter === $goal ? "selected" : "" ?>
                            >
                                <?= $goal === "all" ? "All Fitness Goals" : escapeHtml($goal) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-xl-2 d-flex gap-2">
                    <button type="submit" class="btn btn-orange flex-grow-1">
                        Search
                    </button>

                    <a href="members.php" class="btn btn-outline">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <!-- Members Table -->
        <?php if (count($filteredMembers) > 0): ?>

            <div class="table-responsive">
                <table class="members-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Membership</th>
                            <th>Fitness Goal</th>
                            <th>Sessions</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($filteredMembers as $member): ?>
                            <tr>
                                <td>
                                    <div class="member-profile">
                                        <img
                                            src="<?= escapeHtml($member["avatar"]) ?>"
                                            alt="<?= escapeHtml($member["name"]) ?>"
                                            class="member-avatar"
                                            loading="lazy"
                                        >

                                        <div>
                                            <span class="member-name">
                                                <?= escapeHtml($member["name"]) ?>
                                            </span>

                                            <span class="member-id">
                                                <?= escapeHtml($member["id"]) ?>
                                            </span>

                                            <span class="member-id">
                                                <?= escapeHtml($member["email"]) ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="membership-badge <?= membershipClass($member["membership"]) ?>">
                                        <?= escapeHtml($member["membership"]) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="goal-tag">
                                        <?= escapeHtml($member["goal"]) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="session-count">
                                        <?= (int)$member["sessions"] ?>
                                    </span>
                                    <span class="muted-text"> sessions</span>
                                </td>

                                <td>
                                    <span class="status-badge <?= statusClass($member["status"]) ?>">
                                        <?= escapeHtml($member["status"]) ?>
                                    </span>
                                </td>

                                <td class="muted-text">
                                    <?= date("d M Y", strtotime($member["joined"])) ?>
                                </td>

                                <td>
                                    <a
                                        href="members.php?<?= escapeHtml(http_build_query(array_merge($_GET, ["view" => $member["id"]]))) ?>#memberDetails"
                                        class="btn btn-outline"
                                    >
                                        View Profile
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>

            <div class="empty-state">
                <div class="empty-icon">⌕</div>
                <h3 class="section-heading mb-2">No Members Found</h3>
                <p class="section-description">
                    Try changing your search term or selecting different filters.
                </p>

                <a href="members.php" class="btn btn-orange mt-3">
                    Clear All Filters
                </a>
            </div>

        <?php endif; ?>

    </section>

    <!-- Demo Information -->
    <div class="notice">
        <strong>Demo notice:</strong>
        FitCore currently uses sample member profiles. Searching and filtering work,
        and View Profile displays additional sample details. This page does not connect
        to a database, send messages, or modify actual member accounts.
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div>
            © <?= date("Y") ?> FitCore Fitness Platform
        </div>

        <div>
            Designed by
            <a href="../index.php">Codvanta</a>
            · Turning Vision into Digital Reality.
        </div>
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Automatically submit membership/status/goal filters when changed.
    // The search field is submitted using the Search button or Enter.
    document.querySelectorAll("#membership, #status, #goal").forEach(function (field) {
        field.addEventListener("change", function () {
            document.getElementById("memberFilterForm").submit();
        });
    });
</script>

</body>
</html>