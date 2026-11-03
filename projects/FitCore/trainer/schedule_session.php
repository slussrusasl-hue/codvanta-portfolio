
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore - Schedule Training Session
|--------------------------------------------------------------------------
| Demonstration functionality:
| - Select a member
| - Select a training activity
| - Choose a date and time
| - Add session notes
| - View and cancel sessions created on this page
|
| Data is stored in the current PHP session, not in a database.
|--------------------------------------------------------------------------
*/

$trainerName = "Alex Morgan";
$trainerSpecialty = "Strength & Conditioning";

/*
|--------------------------------------------------------------------------
| Sample Members
|--------------------------------------------------------------------------
*/

$members = [
    [
        "id" => "FCM-1001",
        "name" => "Olivia Bennett",
        "membership" => "Premium",
        "status" => "Active"
    ],
    [
        "id" => "FCM-1002",
        "name" => "Ethan Brooks",
        "membership" => "Pro",
        "status" => "Active"
    ],
    [
        "id" => "FCM-1003",
        "name" => "Sophia Mitchell",
        "membership" => "Premium",
        "status" => "Active"
    ],
    [
        "id" => "FCM-1004",
        "name" => "Noah Wilson",
        "membership" => "Basic",
        "status" => "Active"
    ],
    [
        "id" => "FCM-1005",
        "name" => "Ava Thompson",
        "membership" => "Pro",
        "status" => "Active"
    ],
    [
        "id" => "FCM-1006",
        "name" => "Liam Anderson",
        "membership" => "Basic",
        "status" => "Inactive"
    ],
    [
        "id" => "FCM-1007",
        "name" => "Isabella Clark",
        "membership" => "Premium",
        "status" => "Active"
    ],
    [
        "id" => "FCM-1008",
        "name" => "James Parker",
        "membership" => "Pro",
        "status" => "Active"
    ]
];

/*
|--------------------------------------------------------------------------
| Training Activities
|--------------------------------------------------------------------------
*/

$activities = [
    "Personal Training",
    "Strength Training",
    "Muscle Building",
    "Weight Loss Training",
    "Cardio Training",
    "HIIT Workout",
    "Functional Fitness",
    "Stretching & Recovery"
];

/*
|--------------------------------------------------------------------------
| Initialise Session Data
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["fitcore_trainer_sessions"]) ||
    !is_array($_SESSION["fitcore_trainer_sessions"])) {
    $_SESSION["fitcore_trainer_sessions"] = [];
}

if (empty($_SESSION["fitcore_schedule_csrf"])) {
    $_SESSION["fitcore_schedule_csrf"] = bin2hex(random_bytes(32));
}

$errors = [];
$successMessage = "";

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function esc($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function findMemberById($members, $id)
{
    foreach ($members as $member) {
        if ($member["id"] === $id) {
            return $member;
        }
    }

    return null;
}

function validDateString($date)
{
    if (!is_string($date)) {
        return false;
    }

    $parsed = DateTimeImmutable::createFromFormat("!Y-m-d", $date);
    $dateErrors = DateTimeImmutable::getLastErrors();

    return $parsed !== false
        && ($dateErrors === false ||
            ($dateErrors["warning_count"] === 0 &&
             $dateErrors["error_count"] === 0))
        && $parsed->format("Y-m-d") === $date;
}

function validTimeString($time)
{
    if (!is_string($time)) {
        return false;
    }

    $parsed = DateTimeImmutable::createFromFormat("!H:i", $time);
    $timeErrors = DateTimeImmutable::getLastErrors();

    return $parsed !== false
        && ($timeErrors === false ||
            ($timeErrors["warning_count"] === 0 &&
             $timeErrors["error_count"] === 0))
        && $parsed->format("H:i") === $time;
}

function formatSessionDate($date)
{
    return date("D, d M Y", strtotime($date));
}

function formatSessionTime($time)
{
    return date("h:i A", strtotime($time));
}

/*
|--------------------------------------------------------------------------
| Handle POST Actions
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $submittedToken = $_POST["csrf_token"] ?? "";

    if (
        !is_string($submittedToken) ||
        !hash_equals($_SESSION["fitcore_schedule_csrf"], $submittedToken)
    ) {
        $errors[] = "Your session has expired. Refresh the page and try again.";
    } else {
        $action = $_POST["action"] ?? "";

        /*
        |------------------------------------------------------------------
        | Cancel a Scheduled Session
        |------------------------------------------------------------------
        */

        if ($action === "cancel_session") {
            $reference = $_POST["reference"] ?? "";

            if (!is_string($reference) || $reference === "") {
                $errors[] = "Invalid session reference.";
            } else {
                $found = false;

                foreach ($_SESSION["fitcore_trainer_sessions"] as &$session) {
                    if (
                        $session["reference"] === $reference &&
                        $session["status"] === "Scheduled"
                    ) {
                        $session["status"] = "Cancelled";
                        $session["cancelled_at"] = date("Y-m-d H:i:s");
                        $found = true;
                        break;
                    }
                }

                unset($session);

                if ($found) {
                    $_SESSION["fitcore_schedule_flash"] =
                        "The training session has been cancelled.";

                    header("Location: schedule_session.php");
                    exit;
                }

                $errors[] = "The session could not be cancelled.";
            }
        }

        /*
        |------------------------------------------------------------------
        | Create a New Session
        |------------------------------------------------------------------
        */

        elseif ($action === "schedule_session") {
            $memberId = trim($_POST["member_id"] ?? "");
            $activity = trim($_POST["activity"] ?? "");
            $sessionDate = trim($_POST["session_date"] ?? "");
            $startTime = trim($_POST["start_time"] ?? "");
            $endTime = trim($_POST["end_time"] ?? "");
            $sessionType = trim($_POST["session_type"] ?? "In-Person");
            $notes = trim($_POST["notes"] ?? "");

            $member = findMemberById($members, $memberId);

            if ($member === null) {
                $errors[] = "Please select a valid member.";
            } elseif ($member["status"] !== "Active") {
                $errors[] = "Sessions can only be scheduled for active members.";
            }

            if (!in_array($activity, $activities, true)) {
                $errors[] = "Please select a valid training activity.";
            }

            if (!validDateString($sessionDate)) {
                $errors[] = "Please select a valid session date.";
            } else {
                $today = date("Y-m-d");

                if ($sessionDate < $today) {
                    $errors[] = "The session date cannot be in the past.";
                }
            }

            if (!validTimeString($startTime)) {
                $errors[] = "Please enter a valid start time.";
            }

            if (!validTimeString($endTime)) {
                $errors[] = "Please enter a valid end time.";
            }

            if (
                validTimeString($startTime) &&
                validTimeString($endTime) &&
                $endTime <= $startTime
            ) {
                $errors[] = "The end time must be later than the start time.";
            }

            if (!in_array($sessionType, ["In-Person", "Online"], true)) {
                $errors[] = "Please select a valid session type.";
            }

            if (strlen($notes) > 500) {
                $errors[] = "Session notes cannot exceed 500 characters.";
            }

            /*
            | Check whether this trainer already has an overlapping session.
            | Cancelled sessions are ignored.
            */

            if (
                empty($errors) &&
                $sessionDate !== "" &&
                validTimeString($startTime) &&
                validTimeString($endTime)
            ) {
                foreach ($_SESSION["fitcore_trainer_sessions"] as $existing) {
                    if (
                        $existing["date"] === $sessionDate &&
                        $existing["status"] === "Scheduled"
                    ) {
                        $overlaps =
                            $startTime < $existing["end_time"] &&
                            $endTime > $existing["start_time"];

                        if ($overlaps) {
                            $errors[] =
                                "You already have a scheduled session during that time. " .
                                "Choose a different time.";
                            break;
                        }
                    }
                }
            }

            /*
            |------------------------------------------------------------------
            | Store the Session in PHP Session Data
            |------------------------------------------------------------------
            */

            if (empty($errors)) {
                $reference = "FCS-" . strtoupper(bin2hex(random_bytes(3)));

                $_SESSION["fitcore_trainer_sessions"][] = [
                    "reference" => $reference,
                    "member_id" => $member["id"],
                    "member_name" => $member["name"],
                    "membership" => $member["membership"],
                    "activity" => $activity,
                    "date" => $sessionDate,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "session_type" => $sessionType,
                    "notes" => $notes,
                    "trainer" => $trainerName,
                    "status" => "Scheduled",
                    "created_at" => date("Y-m-d H:i:s")
                ];

                $_SESSION["fitcore_schedule_flash"] =
                    "Training session scheduled successfully!";

                header("Location: schedule_session.php");
                exit;
            }
        } else {
            $errors[] = "Invalid request. Please try again.";
        }
    }
}

/*
|--------------------------------------------------------------------------
| Flash Message
|--------------------------------------------------------------------------
*/

if (!empty($_SESSION["fitcore_schedule_flash"])) {
    $successMessage = $_SESSION["fitcore_schedule_flash"];
    unset($_SESSION["fitcore_schedule_flash"]);
}

/*
|--------------------------------------------------------------------------
| Build Session Lists and Statistics
|--------------------------------------------------------------------------
*/

$scheduledSessions = $_SESSION["fitcore_trainer_sessions"];

usort($scheduledSessions, function ($a, $b) {
    $aDateTime = $a["date"] . " " . $a["start_time"];
    $bDateTime = $b["date"] . " " . $b["start_time"];

    return strcmp($aDateTime, $bDateTime);
});

$totalScheduled = count(array_filter($scheduledSessions, function ($session) {
    return $session["status"] === "Scheduled";
}));

$totalCancelled = count(array_filter($scheduledSessions, function ($session) {
    return $session["status"] === "Cancelled";
}));

$todayDate = date("Y-m-d");

$todaySessions = count(array_filter($scheduledSessions, function ($session) use ($todayDate) {
    return $session["date"] === $todayDate &&
        $session["status"] === "Scheduled";
}));

$upcomingSessions = array_filter($scheduledSessions, function ($session) use ($todayDate) {
    return $session["date"] >= $todayDate &&
        $session["status"] === "Scheduled";
});

/*
|--------------------------------------------------------------------------
| Default Form Values
|--------------------------------------------------------------------------
*/

$defaultDate = date("Y-m-d", strtotime("+1 day"));

$formMember = $_POST["member_id"] ?? "";
$formActivity = $_POST["activity"] ?? "Personal Training";
$formDate = $_POST["session_date"] ?? $defaultDate;
$formStart = $_POST["start_time"] ?? "10:00";
$formEnd = $_POST["end_time"] ?? "11:00";
$formType = $_POST["session_type"] ?? "In-Person";
$formNotes = $_POST["notes"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Schedule Session | FitCore Trainer</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --fc-bg: #101010;
            --fc-sidebar: #151515;
            --fc-card: #191919;
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

        .mobile-topbar {
            display: none;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
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
            line-height: 1.6;
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
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 21px;
            height: 100%;
            position: relative;
        }

        .stat-icon {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            border-radius: 10px;
            font-size: 19px;
            margin-bottom: 17px;
        }

        .stat-label {
            color: var(--fc-muted);
            font-size: 12px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 29px;
            font-weight: 800;
        }

        .section-card {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
            padding: 24px;
            margin-top: 24px;
        }

        .section-heading {
            font-size: 17px;
            font-weight: 800;
            margin: 0;
        }

        .section-description {
            color: var(--fc-muted);
            font-size: 12px;
            margin-top: 7px;
            line-height: 1.6;
        }

        .form-label {
            color: #d6d6d6;
            font-size: 12px;
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
            min-height: 44px;
        }

        textarea.form-control {
            min-height: 115px;
            resize: vertical;
        }

        .form-control::placeholder {
            color: #777;
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

        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .form-hint {
            display: block;
            color: #858585;
            font-size: 10px;
            margin-top: 7px;
        }

        .member-preview {
            display: none;
            background: #222;
            border: 1px solid #383838;
            border-radius: 9px;
            padding: 13px;
            margin-top: 12px;
        }

        .member-preview strong {
            font-size: 12px;
        }

        .member-preview small {
            display: block;
            color: var(--fc-muted);
            margin-top: 5px;
            font-size: 11px;
        }

        .preview-label {
            color: var(--fc-orange);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .alert {
            border-radius: 9px;
            font-size: 12px;
        }

        .alert-success {
            background: rgba(67, 217, 155, .1);
            color: var(--fc-green);
            border: 1px solid rgba(67, 217, 155, .25);
        }

        .alert-danger {
            background: rgba(255, 114, 114, .1);
            color: #ff9292;
            border: 1px solid rgba(255, 114, 114, .25);
        }

        .alert ul {
            margin-bottom: 0;
        }

        .session-list {
            display: flex;
            flex-direction: column;
            gap: 13px;
            margin-top: 20px;
        }

        .session-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            background: #202020;
            border: 1px solid #303030;
            border-radius: 11px;
            padding: 17px;
        }

        .session-date-box {
            flex-shrink: 0;
            width: 58px;
            text-align: center;
            border-radius: 9px;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            padding: 9px 4px;
        }

        .session-date-box strong {
            display: block;
            font-size: 21px;
            line-height: 1.1;
        }

        .session-date-box small {
            display: block;
            font-size: 10px;
            font-weight: 800;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .session-details {
            flex: 1;
            min-width: 0;
        }

        .session-details h3 {
            font-size: 14px;
            font-weight: 800;
            margin: 0 0 7px;
        }

        .session-meta {
            color: var(--fc-muted);
            font-size: 11px;
            line-height: 1.9;
        }

        .session-reference {
            color: #888;
            font-size: 10px;
            margin-top: 9px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 6px;
            background: rgba(67, 217, 155, .12);
            color: var(--fc-green);
            font-size: 10px;
            font-weight: 800;
        }

        .cancelled-badge {
            background: rgba(255, 114, 114, .12);
            color: var(--fc-red);
        }

        .empty-state {
            padding: 36px 15px;
            text-align: center;
            color: var(--fc-muted);
        }

        .empty-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .tip-card {
            border: 1px solid rgba(255, 107, 44, .2);
            background: rgba(255, 107, 44, .06);
            padding: 17px;
            border-radius: 11px;
            margin-top: 20px;
        }

        .tip-card h3 {
            color: var(--fc-orange);
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .tip-card p {
            color: #bdb0aa;
            font-size: 11px;
            line-height: 1.8;
            margin-bottom: 0;
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
            }

            .topbar .btn-orange {
                width: 100%;
            }

            .section-card {
                padding: 17px;
            }

            .session-item {
                gap: 10px;
                padding: 12px;
            }

            .session-date-box {
                width: 49px;
            }

            .session-date-box strong {
                font-size: 18px;
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

    <a href="schedule_session.php" class="sidebar-link active">
        <span class="sidebar-icon">＋</span>
        Schedule Session
    </a>

    <a href="members.php" class="sidebar-link">
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
            <strong><?= esc($trainerName) ?></strong>
            <small><?= esc($trainerSpecialty) ?></small>
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

        <button
            class="mobile-menu"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mobileNavigation"
            aria-expanded="false"
            aria-controls="mobileNavigation"
        >
            ☰ Menu
        </button>
    </div>

    <div class="collapse mb-4" id="mobileNavigation">
        <div class="section-card mt-0">
            <a href="dashboard.php" class="sidebar-link">▦ Dashboard</a>
            <a href="appointments.php" class="sidebar-link">▣ Appointments</a>
            <a href="availability.php" class="sidebar-link">◷ My Availability</a>
            <a href="schedule_session.php" class="sidebar-link active">＋ Schedule Session</a>
            <a href="members.php" class="sidebar-link">♙ Members</a>
            <a href="session_notes.php" class="sidebar-link">▤ Session Notes</a>
            <a href="../customer/index.php" class="sidebar-link">⌂ Member Website</a>
            <a href="../fitcore_demo.php" class="sidebar-link">↩ Demo Selection</a>
        </div>
    </div>

    <!-- Heading -->
    <div class="topbar">
        <div>
            <div class="breadcrumb-text">
                Trainer Workspace / <span>Schedule Session</span>
            </div>

            <h1 class="page-title">Schedule a Session</h1>

            <p class="page-subtitle">
                Plan training sessions, choose a member and organise your upcoming training schedule.
            </p>
        </div>

        <a href="appointments.php" class="btn btn-outline">
            View Appointments →
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if ($successMessage !== ""): ?>
        <div class="alert alert-success mb-4">
            ✓ <?= esc($successMessage) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4">
            <strong>Please check the following:</strong>
            <ul class="mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="row g-3">

        <div class="col-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-icon">▣</div>
                <div class="stat-label">Scheduled Sessions</div>
                <div class="stat-value"><?= $totalScheduled ?></div>
            </div>
        </div>

        <div class="col-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-icon">◷</div>
                <div class="stat-label">Scheduled for Today</div>
                <div class="stat-value"><?= $todaySessions ?></div>
            </div>
        </div>

        <div class="col-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-icon">×</div>
                <div class="stat-label">Cancelled Sessions</div>
                <div class="stat-value"><?= $totalCancelled ?></div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <!-- Schedule Form -->
        <div class="col-12 col-xl-7">
            <section class="section-card">

                <h2 class="section-heading">New Training Session</h2>

                <p class="section-description">
                    Complete the form below to add a session to your trainer schedule.
                </p>

                <form method="POST" action="schedule_session.php" id="scheduleForm" class="mt-4">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= esc($_SESSION["fitcore_schedule_csrf"]) ?>"
                    >

                    <input type="hidden" name="action" value="schedule_session">

                    <div class="mb-4">
                        <label for="member_id" class="form-label">
                            Select Member *
                        </label>

                        <select
                            name="member_id"
                            id="member_id"
                            class="form-select"
                            required
                        >
                            <option value="">Choose a member</option>

                            <?php foreach ($members as $member): ?>
                                <?php if ($member["status"] === "Active"): ?>
                                    <option
                                        value="<?= esc($member["id"]) ?>"
                                        data-name="<?= esc($member["name"]) ?>"
                                        data-membership="<?= esc($member["membership"]) ?>"
                                        <?= $formMember === $member["id"] ? "selected" : "" ?>
                                    >
                                        <?= esc($member["name"]) ?>
                                        (<?= esc($member["id"]) ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>

                        <span class="form-hint">
                            Only active sample members can be selected.
                        </span>

                        <div class="member-preview" id="memberPreview">
                            <div class="preview-label">Selected Member</div>
                            <strong id="previewName"></strong>
                            <small id="previewMembership"></small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="activity" class="form-label">
                            Training Activity *
                        </label>

                        <select name="activity" id="activity" class="form-select" required>
                            <?php foreach ($activities as $activity): ?>
                                <option
                                    value="<?= esc($activity) ?>"
                                    <?= $formActivity === $activity ? "selected" : "" ?>
                                >
                                    <?= esc($activity) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="session_type" class="form-label">
                            Session Type *
                        </label>

                        <select name="session_type" id="session_type" class="form-select" required>
                            <option value="In-Person" <?= $formType === "In-Person" ? "selected" : "" ?>>
                                In-Person Training
                            </option>
                            <option value="Online" <?= $formType === "Online" ? "selected" : "" ?>>
                                Online Training
                            </option>
                        </select>

                        <span class="form-hint">
                            Online sessions are demonstration entries; no video link is generated.
                        </span>
                    </div>

                    <div class="mb-4">
                        <label for="session_date" class="form-label">
                            Session Date *
                        </label>

                        <input
                            type="date"
                            name="session_date"
                            id="session_date"
                            class="form-control"
                            min="<?= date("Y-m-d") ?>"
                            value="<?= esc($formDate) ?>"
                            required
                        >
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label for="start_time" class="form-label">
                                Start Time *
                            </label>

                            <input
                                type="time"
                                name="start_time"
                                id="start_time"
                                class="form-control"
                                value="<?= esc($formStart) ?>"
                                required
                            >
                        </div>

                        <div class="col-sm-6">
                            <label for="end_time" class="form-label">
                                End Time *
                            </label>

                            <input
                                type="time"
                                name="end_time"
                                id="end_time"
                                class="form-control"
                                value="<?= esc($formEnd) ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            Session Notes
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            class="form-control"
                            maxlength="500"
                            placeholder="Example: Focus on squat technique, warm-up and strength progression..."
                        ><?= esc($formNotes) ?></textarea>

                        <div class="d-flex justify-content-between mt-2">
                            <span class="form-hint">
                                Add a short description of the planned session.
                            </span>

                            <span class="form-hint" id="noteCount">0 / 500</span>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-orange">
                            ✓ Schedule Session
                        </button>

                        <button type="reset" class="btn btn-outline" id="resetForm">
                            Reset Form
                        </button>
                    </div>

                </form>
            </section>
        </div>

        <!-- Tips / Quick Info -->
        <div class="col-12 col-xl-5">

            <section class="section-card">
                <h2 class="section-heading">Scheduling Guidelines</h2>

                <p class="section-description">
                    A few reminders when arranging training sessions.
                </p>

                <div class="tip-card">
                    <h3>01. Choose the Right Member</h3>
                    <p>
                        Select an active member and choose a training activity that suits
                        their fitness goal and training needs.
                    </p>
                </div>

                <div class="tip-card">
                    <h3>02. Check Your Availability</h3>
                    <p>
                        Review your availability before scheduling a session. This demo
                        checks for overlapping sessions created on this page.
                    </p>
                </div>

                <div class="tip-card">
                    <h3>03. Add Session Notes</h3>
                    <p>
                        Use the notes field to record the intended focus of the session,
                        such as strength, cardio, flexibility or recovery.
                    </p>
                </div>

                <div class="tip-card">
                    <h3>04. Confirm the Schedule</h3>
                    <p>
                        Newly scheduled sessions appear in the session list below.
                        You can cancel a scheduled entry if your plans change.
                    </p>
                </div>
            </section>

            <section class="section-card">
                <h2 class="section-heading">Session Overview</h2>

                <p class="section-description">
                    Your current schedule in this browser session.
                </p>

                <div class="d-flex justify-content-between align-items-center py-3"
                     style="border-bottom:1px solid #303030;">
                    <span class="muted-text small">Upcoming entries</span>
                    <strong><?= count($upcomingSessions) ?></strong>
                </div>

                <div class="d-flex justify-content-between align-items-center py-3"
                     style="border-bottom:1px solid #303030;">
                    <span class="muted-text small">Cancelled entries</span>
                    <strong><?= $totalCancelled ?></strong>
                </div>

                <div class="d-flex justify-content-between align-items-center py-3">
                    <span class="muted-text small">Trainer</span>
                    <strong><?= esc($trainerName) ?></strong>
                </div>
            </section>

        </div>
    </div>

    <!-- Scheduled Sessions List -->
    <section class="section-card">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h2 class="section-heading">Your Scheduled Sessions</h2>
                <p class="section-description">
                    Sessions created through this demonstration page.
                </p>
            </div>

            <span class="status-badge">
                <?= $totalScheduled ?> Scheduled
            </span>
        </div>

        <?php if (!empty($scheduledSessions)): ?>

            <div class="session-list">

                <?php foreach (array_reverse($scheduledSessions) as $session): ?>
                    <?php
                    $isCancelled = $session["status"] === "Cancelled";
                    $dateTimestamp = strtotime($session["date"]);
                    ?>

                    <div class="session-item">

                        <div class="session-date-box">
                            <strong><?= date("d", $dateTimestamp) ?></strong>
                            <small><?= date("M", $dateTimestamp) ?></small>
                        </div>

                        <div class="session-details">
                            <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">

                                <h3><?= esc($session["activity"]) ?></h3>

                                <span class="status-badge <?= $isCancelled ? "cancelled-badge" : "" ?>">
                                    <?= esc($session["status"]) ?>
                                </span>

                            </div>

                            <div class="session-meta">
                                <div>
                                    <strong>Member:</strong>
                                    <?= esc($session["member_name"]) ?>
                                    (<?= esc($session["member_id"]) ?>)
                                </div>

                                <div>
                                    <strong>Date:</strong>
                                    <?= esc(formatSessionDate($session["date"])) ?>
                                </div>

                                <div>
                                    <strong>Time:</strong>
                                    <?= esc(formatSessionTime($session["start_time"])) ?>
                                    –
                                    <?= esc(formatSessionTime($session["end_time"])) ?>
                                </div>

                                <div>
                                    <strong>Type:</strong>
                                    <?= esc($session["session_type"]) ?>
                                </div>

                                <?php if ($session["notes"] !== ""): ?>
                                    <div>
                                        <strong>Notes:</strong>
                                        <?= nl2br(esc($session["notes"])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="session-reference">
                                Reference: <?= esc($session["reference"]) ?>
                            </div>
                        </div>

                        <?php if (!$isCancelled): ?>
                            <form
                                method="POST"
                                action="schedule_session.php"
                                onsubmit="return confirm('Are you sure you want to cancel this session?');"
                            >
                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= esc($_SESSION["fitcore_schedule_csrf"]) ?>"
                                >

                                <input type="hidden" name="action" value="cancel_session">

                                <input
                                    type="hidden"
                                    name="reference"
                                    value="<?= esc($session["reference"]) ?>"
                                >

                                <button type="submit" class="btn btn-outline">
                                    Cancel
                                </button>
                            </form>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                <div class="empty-icon">▣</div>

                <h3 class="section-heading mb-2">
                    No Sessions Scheduled Yet
                </h3>

                <p class="section-description">
                    Complete the form above to add your first training session.
                </p>
            </div>

        <?php endif; ?>

    </section>

    <div class="alert alert-success mt-4" style="background:rgba(255,107,44,.06);color:#d6b09f;border-color:rgba(255,107,44,.2);">
        <strong>Demo limitation:</strong>
        Scheduled sessions are stored in PHP session data for this browser session only.
        They are not saved to a database and are not automatically added to the member's
        booking history or the separate appointments page.
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div>
            © <?= date("Y") ?> FitCore Fitness Platform
        </div>

        <div>
            Designed by <a href="../index.php">Codvanta</a>
            · Turning Vision into Digital Reality.
        </div>
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Show selected member information.
    const memberSelect = document.getElementById("member_id");
    const memberPreview = document.getElementById("memberPreview");
    const previewName = document.getElementById("previewName");
    const previewMembership = document.getElementById("previewMembership");

    function updateMemberPreview() {
        const selectedOption = memberSelect.options[memberSelect.selectedIndex];

        if (selectedOption && selectedOption.value !== "") {
            previewName.textContent = selectedOption.dataset.name || "";
            previewMembership.textContent =
                (selectedOption.dataset.membership || "") + " Membership";
            memberPreview.style.display = "block";
        } else {
            memberPreview.style.display = "none";
        }
    }

    memberSelect.addEventListener("change", updateMemberPreview);
    updateMemberPreview();

    // Character counter for session notes.
    const notesField = document.getElementById("notes");
    const noteCount = document.getElementById("noteCount");

    function updateNoteCount() {
        noteCount.textContent = notesField.value.length + " / 500";
    }

    notesField.addEventListener("input", updateNoteCount);
    updateNoteCount();

    // Basic client-side time validation.
    document.getElementById("scheduleForm").addEventListener("submit", function (event) {
        const startTime = document.getElementById("start_time").value;
        const endTime = document.getElementById("end_time").value;

        if (startTime && endTime && endTime <= startTime) {
            event.preventDefault();
            alert("The end time must be later than the start time.");
        }
    });

    // Reset the member preview and character counter after resetting the form.
    document.getElementById("resetForm").addEventListener("click", function () {
        setTimeout(function () {
            updateMemberPreview();
            updateNoteCount();
        }, 0);
    });
</script>

</body>
</html>