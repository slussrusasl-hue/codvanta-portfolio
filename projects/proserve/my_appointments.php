
<?php
session_start();

date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe - My Appointments
|--------------------------------------------------------------------------
| Portfolio demonstration:
| - Uses PHP session storage
| - No database connection
| - No emails or SMS messages
| - Customers can view and cancel pending demo appointments
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["proserve_appointments"])) {
    $_SESSION["proserve_appointments"] = [];
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

/*
|--------------------------------------------------------------------------
| CSRF protection
|--------------------------------------------------------------------------
*/

if (empty($_SESSION["proserve_appointments_csrf"])) {
    $_SESSION["proserve_appointments_csrf"] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION["proserve_appointments_csrf"];

/*
|--------------------------------------------------------------------------
| Demo customer identity
|--------------------------------------------------------------------------
| If the customer is logged in, use the email stored by login.php.
| Otherwise, use a session-only demo customer identity.
|--------------------------------------------------------------------------
*/

$loggedInCustomer = $_SESSION["proserve_customer"] ?? null;

$customerEmail = "";
$customerName = "Demo Customer";

if (is_array($loggedInCustomer)) {
    $customerEmail = trim((string)($loggedInCustomer["email"] ?? ""));
    $customerName = trim((string)($loggedInCustomer["name"] ?? $loggedInCustomer["full_name"] ?? ""));
}

/*
|--------------------------------------------------------------------------
| Flash messages
|--------------------------------------------------------------------------
*/

$flashMessage = $_SESSION["proserve_appointments_flash"] ?? null;
unset($_SESSION["proserve_appointments_flash"]);

$flashType = "success";

/*
|--------------------------------------------------------------------------
| Cancel appointment
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $postedToken = $_POST["csrf_token"] ?? "";
    $reference = trim((string)($_POST["reference"] ?? ""));

    if (
        !is_string($postedToken) ||
        !hash_equals($csrfToken, $postedToken)
    ) {
        $_SESSION["proserve_appointments_flash"] =
            "Your request could not be verified. Please try again.";

        header("Location: my_appointments.php");
        exit;
    }

    $found = false;

    foreach ($_SESSION["proserve_appointments"] as $index => $appointment) {

        if (($appointment["reference"] ?? "") !== $reference) {
            continue;
        }

        // In a real system, ownership must be checked against an authenticated user ID.
        // Here, the session is treated as one demo customer's booking space.
        $found = true;

        $status = $appointment["status"] ?? "";

        if ($status === "CANCELLED") {
            $_SESSION["proserve_appointments_flash"] =
                "This appointment has already been cancelled.";

        } elseif ($status !== "PENDING CONFIRMATION") {
            $_SESSION["proserve_appointments_flash"] =
                "Only appointments pending confirmation can be cancelled in this demonstration.";

        } else {
            $_SESSION["proserve_appointments"][$index]["status"] = "CANCELLED";
            $_SESSION["proserve_appointments"][$index]["cancelled_at"] = date("Y-m-d H:i:s");

            $_SESSION["proserve_appointments_flash"] =
                "Appointment " . $reference . " has been cancelled successfully.";
        }

        break;
    }

    if (!$found) {
        $_SESSION["proserve_appointments_flash"] =
            "The requested appointment could not be found.";
    }

    header("Location: my_appointments.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get appointments for this session
|--------------------------------------------------------------------------
*/

$appointments = $_SESSION["proserve_appointments"];

/*
|--------------------------------------------------------------------------
| Search and status filters
|--------------------------------------------------------------------------
*/

$search = trim((string)($_GET["search"] ?? ""));
$statusFilter = trim((string)($_GET["status"] ?? ""));

$allowedStatuses = [
    "",
    "PENDING CONFIRMATION",
    "CONFIRMED",
    "COMPLETED",
    "CANCELLED"
];

if (!in_array($statusFilter, $allowedStatuses, true)) {
    $statusFilter = "";
}

$filteredAppointments = [];

foreach ($appointments as $appointment) {

    // If the demo login has an email, display only matching appointments.
    // If no email is stored by the login demo, show session appointments.
    if (
        $customerEmail !== "" &&
        strcasecmp((string)($appointment["email"] ?? ""), $customerEmail) !== 0
    ) {
        continue;
    }

    $status = (string)($appointment["status"] ?? "PENDING CONFIRMATION");

    if ($statusFilter !== "" && $status !== $statusFilter) {
        continue;
    }

    $searchable = implode(" ", [
        (string)($appointment["reference"] ?? ""),
        (string)($appointment["service"] ?? ""),
        (string)($appointment["appointment_type"] ?? ""),
        (string)($appointment["appointment_date"] ?? ""),
        (string)($appointment["full_name"] ?? ""),
        (string)($appointment["email"] ?? "")
    ]);

    if (
        $search !== "" &&
        stripos($searchable, $search) === false
    ) {
        continue;
    }

    $filteredAppointments[] = $appointment;
}

/*
|--------------------------------------------------------------------------
| Sort newest appointments first
|--------------------------------------------------------------------------
*/

usort($filteredAppointments, function ($a, $b) {
    return strcmp(
        (string)($b["created_at"] ?? ""),
        (string)($a["created_at"] ?? "")
    );
});

/*
|--------------------------------------------------------------------------
| Summary statistics
|--------------------------------------------------------------------------
*/

$totalAppointments = 0;
$pendingCount = 0;
$confirmedCount = 0;
$completedCount = 0;
$cancelledCount = 0;

foreach ($appointments as $appointment) {

    if (
        $customerEmail !== "" &&
        strcasecmp((string)($appointment["email"] ?? ""), $customerEmail) !== 0
    ) {
        continue;
    }

    $totalAppointments++;

    switch ($appointment["status"] ?? "PENDING CONFIRMATION") {
        case "PENDING CONFIRMATION":
            $pendingCount++;
            break;

        case "CONFIRMED":
            $confirmedCount++;
            break;

        case "COMPLETED":
            $completedCount++;
            break;

        case "CANCELLED":
            $cancelledCount++;
            break;
    }
}

function statusClass($status)
{
    switch ($status) {
        case "CONFIRMED":
            return "status-confirmed";

        case "COMPLETED":
            return "status-completed";

        case "CANCELLED":
            return "status-cancelled";

        case "PENDING CONFIRMATION":
        default:
            return "status-pending";
    }
}

function formatAppointmentDate($date)
{
    $timestamp = strtotime((string)$date);

    if ($timestamp === false) {
        return (string)$date;
    }

    return date("D, d M Y", $timestamp);
}

function formatAppointmentTime($appointment)
{
    if (!empty($appointment["time_label"])) {
        return $appointment["time_label"];
    }

    $time = (string)($appointment["appointment_time"] ?? "");

    if ($time !== "") {
        $timestamp = strtotime($time);

        if ($timestamp !== false) {
            return date("h:i A", $timestamp);
        }
    }

    return "Time not specified";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Appointments | ProServe</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --primary: #1769e0;
            --primary-dark: #1052b8;
            --navy: #10243e;
            --muted: #68778b;
            --light: #f4f8fd;
            --border: #e2eaf4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Inter", "Trebuchet MS", sans-serif;
            color: var(--navy);
            background: #f7f9fc;
            line-height: 1.65;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: rgba(255, 255, 255, .97);
            border-bottom: 1px solid var(--border);
        }

        .navbar-brand {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -.7px;
        }

        .brand-highlight {
            color: var(--primary);
        }

        .navbar .nav-link {
            color: #526176;
            font-weight: 600;
            padding: 10px 13px !important;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--primary);
        }

        .btn-primary-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--primary);
            color: #fff;
            border: 1px solid var(--primary);
            padding: 11px 18px;
            border-radius: 10px;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-outline-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border: 1px solid #d4dfed;
            border-radius: 10px;
            color: var(--navy);
            font-weight: 700;
            background: #fff;
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .hero {
            background:
                radial-gradient(circle at 90% 10%, #dceaff 0, transparent 28%),
                linear-gradient(135deg, #f3f8ff, #ffffff 70%);
            padding: 55px 0 45px;
        }

        .breadcrumb-text {
            color: #7b8ba0;
            font-size: .9rem;
            margin-bottom: 15px;
        }

        .breadcrumb-text a {
            color: var(--primary);
            font-weight: 600;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 30px;
            background: #e7f0ff;
            color: var(--primary-dark);
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .hero h1 {
            margin: 17px 0 12px;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1.5px;
        }

        .hero p {
            max-width: 720px;
            color: var(--muted);
            margin-bottom: 0;
        }

        .dashboard-section {
            padding: 38px 0 75px;
        }

        .welcome-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 26px;
        }

        .welcome-row h2 {
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .welcome-row p {
            color: var(--muted);
            margin: 0;
            font-size: .92rem;
        }

        .stat-card {
            height: 100%;
            padding: 22px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(24, 53, 91, .035);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .stat-label {
            color: var(--muted);
            font-size: .88rem;
            font-weight: 600;
        }

        .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #eaf2ff;
            color: var(--primary);
            font-weight: 800;
        }

        .stat-value {
            margin-top: 13px;
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .stat-caption {
            margin-top: 7px;
            color: #8997a9;
            font-size: .82rem;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(24, 53, 91, .04);
        }

        .panel-header {
            padding: 24px 26px;
            border-bottom: 1px solid var(--border);
        }

        .panel-header h2 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 800;
        }

        .panel-header p {
            color: var(--muted);
            font-size: .88rem;
            margin: 5px 0 0;
        }

        .panel-body {
            padding: 24px 26px;
        }

        .form-label {
            color: #243b57;
            font-size: .88rem;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            min-height: 45px;
            border: 1px solid #dbe5f0;
            border-radius: 10px;
            padding: 10px 12px;
            color: var(--navy);
            font-size: .9rem;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #79a9f6;
            box-shadow: 0 0 0 3px rgba(23, 105, 224, .1) !important;
        }

        .table {
            margin: 0;
            vertical-align: middle;
        }

        .table thead th {
            background: #f8fafd;
            color: #69798e;
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .45px;
            font-weight: 800;
            white-space: nowrap;
            padding: 15px 17px;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            padding: 17px;
            border-bottom: 1px solid #edf1f7;
            font-size: .88rem;
        }

        .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .service-name {
            font-weight: 800;
            color: var(--navy);
        }

        .reference-text {
            display: block;
            margin-top: 4px;
            color: #8290a2;
            font-size: .77rem;
        }

        .date-main {
            display: block;
            font-weight: 700;
            white-space: nowrap;
        }

        .date-time {
            display: block;
            color: var(--muted);
            font-size: .8rem;
            margin-top: 3px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: .72rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-pending {
            background: #fff4d6;
            color: #8a6200;
        }

        .status-confirmed {
            background: #e5efff;
            color: #1758ac;
        }

        .status-completed {
            background: #dff6e9;
            color: #167047;
        }

        .status-cancelled {
            background: #ffe8e8;
            color: #b42318;
        }

        .btn-small {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 10px;
            border: 1px solid #d8e2ef;
            border-radius: 8px;
            background: #fff;
            color: #31455e;
            font-size: .79rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-small:hover {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-small-danger {
            color: #b42318;
            border-color: #f2cccc;
        }

        .btn-small-danger:hover {
            background: #fff2f2;
            color: #9d1c13;
            border-color: #e4a6a6;
        }

        .empty-state {
            text-align: center;
            padding: 60px 22px;
        }

        .empty-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eaf2ff;
            color: var(--primary);
            border-radius: 20px;
            font-size: 1.8rem;
            font-weight: 800;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            font-weight: 800;
        }

        .empty-state p {
            max-width: 470px;
            margin: 8px auto 22px;
            color: var(--muted);
            font-size: .92rem;
        }

        .notice-box {
            padding: 15px;
            border-radius: 12px;
            background: #f3f8ff;
            color: #536781;
            font-size: .85rem;
        }

        .help-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            height: 100%;
        }

        .help-card h3 {
            font-size: 1rem;
            font-weight: 800;
        }

        .help-card p {
            color: var(--muted);
            font-size: .88rem;
            margin-bottom: 14px;
        }

        footer {
            background: #0d1e34;
            color: #fff;
            padding: 48px 0 20px;
        }

        footer .footer-text {
            color: #a9b7c9;
            font-size: .9rem;
        }

        footer a {
            color: #c3d0e1;
            font-size: .9rem;
        }

        footer a:hover {
            color: #fff;
        }

        .footer-title {
            font-weight: 800;
            margin-bottom: 14px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.12);
            margin-top: 35px;
            padding-top: 18px;
            color: #a9b7c9;
            font-size: .83rem;
        }

        @media (max-width: 767px) {
            .hero {
                padding: 42px 0 35px;
            }

            .dashboard-section {
                padding: 28px 0 55px;
            }

            .panel-header,
            .panel-body {
                padding: 20px;
            }

            .table tbody td {
                padding: 14px;
            }
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container py-2">

        <a class="navbar-brand" href="index.php">
            Pro<span class="brand-highlight">Serve</span>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="services.php">Services</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="request_quote.php">Request a Quote</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="book_appointment.php">Book Appointment</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="my_appointments.php">My Appointments</a>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a class="btn-primary-custom" href="login.php">
                        Customer Login
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">

        <div class="breadcrumb-text">
            <a href="index.php">Home</a>
            <span class="mx-2">/</span>
            <a href="login.php">Customer Account</a>
            <span class="mx-2">/</span>
            My Appointments
        </div>

        <span class="eyebrow">Customer Dashboard</span>

        <h1>
            My <span class="brand-highlight">Appointments</span>
        </h1>

        <p>
            View your appointment requests, check their current status,
            and manage pending bookings in the ProServe demonstration.
        </p>

    </div>
</section>

<!-- Dashboard -->
<section class="dashboard-section">
    <div class="container">

        <?php if ($flashMessage): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= e($flashMessage) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>
            </div>
        <?php endif; ?>

        <div class="welcome-row">

            <div>
                <h2>
                    Welcome back<?= $customerName !== "" ? ", " . e($customerName) : "" ?>!
                </h2>

                <p>
                    Here's an overview of your appointment activity.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="services.php" class="btn-outline-custom">
                    Browse Services
                </a>

                <a href="book_appointment.php" class="btn-primary-custom">
                    + Book Appointment
                </a>
            </div>

        </div>

        <!-- Statistics -->
        <div class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Total Appointments</span>
                        <span class="stat-icon">#</span>
                    </div>

                    <div class="stat-value"><?= $totalAppointments ?></div>

                    <div class="stat-caption">All recorded bookings</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Pending</span>
                        <span class="stat-icon">◷</span>
                    </div>

                    <div class="stat-value"><?= $pendingCount ?></div>

                    <div class="stat-caption">Awaiting confirmation</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Confirmed</span>
                        <span class="stat-icon">✓</span>
                    </div>

                    <div class="stat-value"><?= $confirmedCount ?></div>

                    <div class="stat-caption">Confirmed appointments</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Cancelled</span>
                        <span class="stat-icon">×</span>
                    </div>

                    <div class="stat-value"><?= $cancelledCount ?></div>

                    <div class="stat-caption">Cancelled appointments</div>
                </div>
            </div>

        </div>

        <!-- Filters -->
        <div class="panel mb-4">

            <div class="panel-header">
                <h2>Find an Appointment</h2>
                <p>Search your bookings or filter by appointment status.</p>
            </div>

            <div class="panel-body">

                <form method="GET" action="my_appointments.php">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-6">
                            <label class="form-label" for="search">
                                Search Appointments
                            </label>

                            <input
                                type="search"
                                class="form-control"
                                id="search"
                                name="search"
                                value="<?= e($search) ?>"
                                placeholder="Search reference, service, date..."
                            >
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label" for="status">
                                Appointment Status
                            </label>

                            <select class="form-select" id="status" name="status">
                                <option value="" <?= $statusFilter === "" ? "selected" : "" ?>>
                                    All Statuses
                                </option>

                                <option
                                    value="PENDING CONFIRMATION"
                                    <?= $statusFilter === "PENDING CONFIRMATION" ? "selected" : "" ?>
                                >
                                    Pending Confirmation
                                </option>

                                <option
                                    value="CONFIRMED"
                                    <?= $statusFilter === "CONFIRMED" ? "selected" : "" ?>
                                >
                                    Confirmed
                                </option>

                                <option
                                    value="COMPLETED"
                                    <?= $statusFilter === "COMPLETED" ? "selected" : "" ?>
                                >
                                    Completed
                                </option>

                                <option
                                    value="CANCELLED"
                                    <?= $statusFilter === "CANCELLED" ? "selected" : "" ?>
                                >
                                    Cancelled
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-2 d-flex gap-2">
                            <button type="submit" class="btn-primary-custom w-100">
                                Search
                            </button>
                        </div>

                    </div>

                    <?php if ($search !== "" || $statusFilter !== ""): ?>
                        <div class="mt-3">
                            <a href="my_appointments.php" class="small fw-semibold">
                                Clear filters
                            </a>
                        </div>
                    <?php endif; ?>

                </form>

            </div>
        </div>

        <!-- Appointments Table -->
        <div class="panel">

            <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h2>Appointment History</h2>
                    <p>
                        Showing <?= count($filteredAppointments) ?>
                        appointment<?= count($filteredAppointments) === 1 ? "" : "s" ?>.
                    </p>
                </div>

                <span class="small text-secondary">
                    Session-based demonstration
                </span>
            </div>

            <?php if (empty($filteredAppointments)): ?>

                <div class="empty-state">

                    <div class="empty-icon">◷</div>

                    <?php if ($totalAppointments === 0): ?>

                        <h3>No Appointments Yet</h3>

                        <p>
                            You haven't submitted any appointments in this
                            session. Book a consultation or service visit to
                            see it appear here.
                        </p>

                    <?php else: ?>

                        <h3>No Matching Appointments</h3>

                        <p>
                            No appointments match your current search or
                            status filter. Try changing your search criteria.
                        </p>

                    <?php endif; ?>

                    <a href="book_appointment.php" class="btn-primary-custom">
                        Book an Appointment
                    </a>

                </div>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Service / Reference</th>
                                <th>Date & Time</th>
                                <th>Appointment Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($filteredAppointments as $appointment): ?>

                                <?php
                                    $status = (string)($appointment["status"] ?? "PENDING CONFIRMATION");
                                    $reference = (string)($appointment["reference"] ?? "");
                                ?>

                                <tr>
                                    <td>
                                        <span class="service-name">
                                            <?= e($appointment["service"] ?? "Service") ?>
                                        </span>

                                        <span class="reference-text">
                                            <?= e($reference) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="date-main">
                                            <?= e(formatAppointmentDate($appointment["appointment_date"] ?? "")) ?>
                                        </span>

                                        <span class="date-time">
                                            <?= e(formatAppointmentTime($appointment)) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= e($appointment["appointment_type"] ?? "Consultation") ?>

                                        <?php if (!empty($appointment["location"])): ?>
                                            <span class="reference-text">
                                                Location: <?= e($appointment["location"]) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="status-badge <?= e(statusClass($status)) ?>">
                                            <?= e($status) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-wrap gap-2">

                                            <button
                                                type="button"
                                                class="btn-small"
                                                data-bs-toggle="modal"
                                                data-bs-target="#appointmentModal"
                                                data-reference="<?= e($reference) ?>"
                                                data-service="<?= e($appointment["service"] ?? "") ?>"
                                                data-date="<?= e(formatAppointmentDate($appointment["appointment_date"] ?? "")) ?>"
                                                data-time="<?= e(formatAppointmentTime($appointment)) ?>"
                                                data-type="<?= e($appointment["appointment_type"] ?? "") ?>"
                                                data-status="<?= e($status) ?>"
                                                data-email="<?= e($appointment["email"] ?? "") ?>"
                                                data-phone="<?= e($appointment["phone"] ?? "") ?>"
                                                data-company="<?= e($appointment["company"] ?? "") ?>"
                                                data-location="<?= e($appointment["location"] ?? "") ?>"
                                                data-notes="<?= e($appointment["notes"] ?? "") ?>"
                                                data-created="<?= e($appointment["created_at"] ?? "") ?>"
                                            >
                                                View
                                            </button>

                                            <?php if ($status === "PENDING CONFIRMATION"): ?>

                                                <form
                                                    method="POST"
                                                    action="my_appointments.php"
                                                    class="cancel-form"
                                                >
                                                    <input
                                                        type="hidden"
                                                        name="csrf_token"
                                                        value="<?= e($csrfToken) ?>"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="reference"
                                                        value="<?= e($reference) ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn-small btn-small-danger"
                                                    >
                                                        Cancel
                                                    </button>
                                                </form>

                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </div>

        <!-- Demo Notice -->
        <div class="notice-box mt-4">
            <strong>Important:</strong>
            Appointments are stored in the current PHP session, not a
            permanent database. This is a portfolio demonstration, not a
            live booking service. The current login and session workflow
            is not a substitute for production authentication or
            authorization.
        </div>

        <!-- Helpful links -->
        <div class="row g-3 mt-2">

            <div class="col-md-4">
                <div class="help-card">
                    <h3>Need Another Service?</h3>

                    <p>
                        Browse the service categories and choose a suitable
                        service before booking.
                    </p>

                    <a href="services.php" class="btn-outline-custom">
                        Browse Services
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="help-card">
                    <h3>Need a Price Estimate?</h3>

                    <p>
                        Submit your project details and estimated budget
                        using the quotation request form.
                    </p>

                    <a href="request_quote.php" class="btn-outline-custom">
                        Request a Quote
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="help-card">
                    <h3>Have a Question?</h3>

                    <p>
                        Use the contact page to demonstrate a general
                        customer enquiry.
                    </p>

                    <a href="contact.php" class="btn-outline-custom">
                        Contact Us
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Appointment Details Modal -->
<div
    class="modal fade"
    id="appointmentModal"
    tabindex="-1"
    aria-labelledby="appointmentModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content border-0 rounded-4">

            <div class="modal-header border-bottom px-4 py-3">
                <div>
                    <h2 class="modal-title fs-5 fw-bold" id="appointmentModalLabel">
                        Appointment Details
                    </h2>

                    <div class="small text-secondary" id="modalReference"></div>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>

            <div class="modal-body p-4">

                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="small text-secondary">Service</div>
                        <div class="fw-bold" id="modalService"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary">Appointment Type</div>
                        <div class="fw-bold" id="modalType"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary">Date</div>
                        <div class="fw-bold" id="modalDate"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary">Time</div>
                        <div class="fw-bold" id="modalTime"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary">Status</div>
                        <div class="fw-bold" id="modalStatus"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary">Company</div>
                        <div class="fw-bold" id="modalCompany"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary">Email</div>
                        <div class="fw-bold text-break" id="modalEmail"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary">Phone</div>
                        <div class="fw-bold" id="modalPhone"></div>
                    </div>

                    <div class="col-12">
                        <div class="small text-secondary">Location</div>
                        <div class="fw-bold" id="modalLocation"></div>
                    </div>

                    <div class="col-12">
                        <div class="small text-secondary">Additional Notes</div>
                        <div
                            class="p-3 rounded-3 mt-1"
                            style="background:#f5f8fc; white-space:pre-wrap; overflow-wrap:anywhere;"
                            id="modalNotes"
                        ></div>
                    </div>

                    <div class="col-12">
                        <div class="small text-secondary">Submitted At</div>
                        <div class="fw-bold" id="modalCreated"></div>
                    </div>

                </div>

            </div>

            <div class="modal-footer border-top px-4 py-3">
                <button
                    type="button"
                    class="btn-outline-custom"
                    data-bs-dismiss="modal"
                >
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-5">
                <a href="index.php" class="navbar-brand text-white">
                    Pro<span class="brand-highlight">Serve</span>
                </a>

                <p class="footer-text mt-3">
                    A service management platform concept designed to
                    demonstrate customer bookings and business workflows.
                </p>

                <p class="footer-text mb-0">
                    Portfolio demonstration by <strong>Codvanta</strong>.
                </p>
            </div>

            <div class="col-6 col-lg-2">
                <div class="footer-title">Explore</div>

                <div class="d-flex flex-column gap-2">
                    <a href="index.php">Home</a>
                    <a href="services.php">Services</a>
                    <a href="book_appointment.php">Book Appointment</a>
                    <a href="my_appointments.php">My Appointments</a>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <div class="footer-title">Customer</div>

                <div class="d-flex flex-column gap-2">
                    <a href="register.php">Register</a>
                    <a href="login.php">Login</a>
                    <a href="request_quote.php">Request a Quote</a>
                    <a href="contact.php">Contact</a>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="footer-title">Service Categories</div>

                <p class="footer-text">
                    Technology, maintenance, cleaning, consulting,
                    construction, and business services.
                </p>
            </div>

        </div>

        <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
            <span>
                &copy; <?= date("Y") ?> ProServe. Demonstration website.
            </span>

            <span>Turning Vision into Digital Reality.</span>
        </div>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Populate the appointment details modal safely using textContent.
    const appointmentModal = document.getElementById("appointmentModal");

    if (appointmentModal) {
        appointmentModal.addEventListener("show.bs.modal", function (event) {

            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const fields = {
                modalReference: "reference",
                modalService: "service",
                modalDate: "date",
                modalTime: "time",
                modalType: "type",
                modalStatus: "status",
                modalEmail: "email",
                modalPhone: "phone",
                modalCompany: "company",
                modalLocation: "location",
                modalNotes: "notes",
                modalCreated: "created"
            };

            Object.entries(fields).forEach(function ([elementId, dataKey]) {
                const element = document.getElementById(elementId);

                if (element) {
                    element.textContent = button.dataset[dataKey] || "Not provided";
                }
            });
        });
    }

    // Confirm cancellation before submitting.
    document.querySelectorAll(".cancel-form").forEach(function (form) {
        form.addEventListener("submit", function (event) {

            const confirmed = window.confirm(
                "Are you sure you want to cancel this pending appointment?"
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
</script>

</body>
</html>
