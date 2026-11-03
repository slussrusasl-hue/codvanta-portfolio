
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Require customer login
if (empty($_SESSION['proserve_customer'])) {
    $_SESSION['proserve_profile_notice'] = [
        'type' => 'warning',
        'message' => 'Please log in to access your customer dashboard.'
    ];

    header('Location: login.php');
    exit;
}

$customer = $_SESSION['proserve_customer'];
$profile = $_SESSION['proserve_profile'] ?? [];

$customerName = $profile['name']
    ?? $customer['name']
    ?? 'Customer';

$customerEmail = $profile['email']
    ?? $customer['email']
    ?? '';

$firstName = explode(' ', trim($customerName))[0] ?: 'Customer';

// Demo records are stored in PHP sessions by the other ProServe pages.
$appointments = $_SESSION['proserve_appointments'] ?? [];
$quotes = $_SESSION['proserve_quotes'] ?? [];
$enquiries = $_SESSION['proserve_enquiries'] ?? [];

// Match customer records by email when an email is available.
function belongsToCustomer($record, $email) {
    if ($email === '') {
        return true;
    }

    $recordEmail = $record['email']
        ?? $record['customer_email']
        ?? '';

    return strcasecmp(trim((string)$recordEmail), trim((string)$email)) === 0;
}

$myAppointments = [];
foreach ($appointments as $appointment) {
    if (is_array($appointment) && belongsToCustomer($appointment, $customerEmail)) {
        $myAppointments[] = $appointment;
    }
}

$myQuotes = [];
foreach ($quotes as $quote) {
    if (is_array($quote) && belongsToCustomer($quote, $customerEmail)) {
        $myQuotes[] = $quote;
    }
}

$myEnquiries = [];
foreach ($enquiries as $enquiry) {
    if (is_array($enquiry) && belongsToCustomer($enquiry, $customerEmail)) {
        $myEnquiries[] = $enquiry;
    }
}

// Sort recent records first.
$sortRecent = function (&$records) {
    usort($records, function ($a, $b) {
        $dateA = strtotime(
            $a['created_at']
            ?? $a['appointment_date']
            ?? $a['date']
            ?? ''
        ) ?: 0;

        $dateB = strtotime(
            $b['created_at']
            ?? $b['appointment_date']
            ?? $b['date']
            ?? ''
        ) ?: 0;

        return $dateB <=> $dateA;
    });
};

$sortRecent($myAppointments);
$sortRecent($myQuotes);
$sortRecent($myEnquiries);

// Summary statistics
$totalAppointments = count($myAppointments);
$pendingAppointments = 0;
$confirmedAppointments = 0;
$completedAppointments = 0;

foreach ($myAppointments as $appointment) {
    $status = strtoupper(trim($appointment['status'] ?? ''));

    if (in_array($status, ['PENDING', 'PENDING CONFIRMATION'], true)) {
        $pendingAppointments++;
    } elseif (in_array($status, ['CONFIRMED', 'APPROVED', 'SCHEDULED'], true)) {
        $confirmedAppointments++;
    } elseif (in_array($status, ['COMPLETED', 'FINISHED'], true)) {
        $completedAppointments++;
    }
}

$pendingQuotes = 0;
foreach ($myQuotes as $quote) {
    $status = strtoupper(trim($quote['status'] ?? 'PENDING'));

    if (in_array($status, ['PENDING', 'NEW', 'NEW REQUEST', 'UNDER REVIEW'], true)) {
        $pendingQuotes++;
    }
}

$totalEnquiries = count($myEnquiries);

// Format a readable date safely.
function formatDate($value, $format = 'd M Y') {
    if (empty($value)) {
        return '—';
    }

    $timestamp = strtotime((string)$value);

    return $timestamp ? date($format, $timestamp) : e($value);
}

// Status badge styles
function statusClass($status) {
    $status = strtoupper(trim((string)$status));

    if (in_array($status, ['CONFIRMED', 'APPROVED', 'COMPLETED', 'PAID', 'RESOLVED'], true)) {
        return 'status-success';
    }

    if (in_array($status, ['CANCELLED', 'REJECTED', 'DECLINED', 'CLOSED'], true)) {
        return 'status-danger';
    }

    if (in_array($status, ['IN PROGRESS', 'PROCESSING', 'UNDER REVIEW', 'SCHEDULED'], true)) {
        return 'status-info';
    }

    return 'status-pending';
}

$initials = '';
foreach (array_slice(preg_split('/\s+/', trim($customerName)) ?: [], 0, 2) as $part) {
    if ($part !== '') {
        $initials .= mb_strtoupper(mb_substr($part, 0, 1));
    }
}
if ($initials === '') {
    $initials = 'C';
}

$recentAppointments = array_slice($myAppointments, 0, 5);
$recentQuotes = array_slice($myQuotes, 0, 4);
$recentEnquiries = array_slice($myEnquiries, 0, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Dashboard | ProServe</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10233f;
            --muted: #64748b;
            --background: #f4f7fb;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: #1e293b;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .navbar-brand {
            color: var(--navy);
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-highlight {
            color: var(--primary);
        }

        .navbar .nav-link {
            color: #475569;
            font-weight: 600;
            font-size: .9rem;
            padding: .7rem .75rem;
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
            border-radius: 10px;
            padding: 11px 17px;
            font-size: .88rem;
            font-weight: 700;
            transition: .2s;
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
            gap: 7px;
            padding: 10px 15px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            color: #334155;
            font-size: .86rem;
            font-weight: 700;
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .dashboard-wrap {
            padding-top: 32px;
            padding-bottom: 45px;
        }

        .eyebrow {
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: .76rem;
            font-weight: 800;
        }

        .page-title {
            color: var(--navy);
            font-size: clamp(1.7rem, 4vw, 2.35rem);
            font-weight: 800;
            letter-spacing: -1.1px;
            margin: 7px 0;
        }

        .muted-text {
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.7;
        }

        .welcome-panel {
            background: linear-gradient(120deg, #163e91, #2563eb 65%, #60a5fa);
            color: #fff;
            border-radius: 20px;
            padding: 30px;
            margin: 24px 0;
            position: relative;
            overflow: hidden;
        }

        .welcome-panel::after {
            content: '';
            position: absolute;
            right: -55px;
            top: -100px;
            width: 250px;
            height: 250px;
            border: 35px solid rgba(255,255,255,.12);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-panel h2 {
            font-size: clamp(1.35rem, 3vw, 1.8rem);
            font-weight: 800;
            margin-bottom: 9px;
        }

        .welcome-panel p {
            color: rgba(255,255,255,.85);
            max-width: 620px;
            font-size: .9rem;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .welcome-panel .btn-white {
            background: #fff;
            color: var(--primary-dark);
            border: 1px solid #fff;
            border-radius: 10px;
            padding: 11px 16px;
            font-size: .87rem;
            font-weight: 800;
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }

        .welcome-panel .btn-white:hover {
            background: #eff6ff;
        }

        .welcome-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,.17);
            border: 2px solid rgba(255,255,255,.45);
            color: #fff;
            font-size: 1.5rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 21px;
            height: 100%;
            box-shadow: 0 4px 18px rgba(15,23,42,.025);
            transition: .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(15,23,42,.06);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 19px;
        }

        .stat-label {
            color: var(--muted);
            font-size: .82rem;
            font-weight: 600;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            font-size: 1.2rem;
        }

        .icon-blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .icon-orange {
            background: #fff7ed;
            color: #ea580c;
        }

        .icon-green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .icon-purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .stat-number {
            color: var(--navy);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .stat-caption {
            color: var(--muted);
            font-size: .77rem;
            margin-top: 9px;
        }

        .section-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 17px;
            overflow: hidden;
            margin-top: 24px;
            box-shadow: 0 4px 18px rgba(15,23,42,.025);
        }

        .section-header {
            padding: 21px 23px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            border-bottom: 1px solid var(--border);
        }

        .section-header h3 {
            color: var(--navy);
            font-size: 1rem;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .section-header p {
            color: var(--muted);
            font-size: .81rem;
            margin: 0;
        }

        .table-responsive {
            padding: 0 20px 8px;
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            color: #64748b;
            font-size: .73rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 16px 12px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table tbody td {
            color: #475569;
            font-size: .83rem;
            padding: 17px 12px;
            border-color: #f1f5f9;
        }

        .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .record-title {
            color: var(--navy);
            font-weight: 700;
            margin-bottom: 4px;
        }

        .record-subtitle {
            color: var(--muted);
            font-size: .75rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 9px;
            border-radius: 30px;
            font-size: .69rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-success {
            color: #15803d;
            background: #dcfce7;
        }

        .status-pending {
            color: #a16207;
            background: #fef9c3;
        }

        .status-danger {
            color: #b91c1c;
            background: #fee2e2;
        }

        .status-info {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .quick-action {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 17px;
            border: 1px solid var(--border);
            border-radius: 13px;
            color: inherit;
            background: #fff;
            height: 100%;
            transition: .2s;
        }

        .quick-action:hover {
            border-color: #93c5fd;
            background: #f8fbff;
            transform: translateY(-2px);
        }

        .quick-action-icon {
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #eff6ff;
            color: var(--primary);
            font-size: 1.15rem;
        }

        .quick-action h4 {
            color: var(--navy);
            font-size: .88rem;
            font-weight: 800;
            margin: 2px 0 6px;
        }

        .quick-action p {
            color: var(--muted);
            font-size: .78rem;
            line-height: 1.6;
            margin: 0;
        }

        .empty-state {
            padding: 38px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: var(--primary);
            font-size: 1.5rem;
            margin: 0 auto 14px;
        }

        .empty-state h4 {
            color: var(--navy);
            font-size: .98rem;
            font-weight: 800;
        }

        .empty-state p {
            color: var(--muted);
            font-size: .83rem;
            margin-bottom: 18px;
        }

        .side-panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 17px;
            padding: 22px;
            margin-top: 24px;
            box-shadow: 0 4px 18px rgba(15,23,42,.025);
        }

        .side-panel h3 {
            color: var(--navy);
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            padding-bottom: 18px;
            position: relative;
        }

        .activity-item:last-child {
            padding-bottom: 0;
        }

        .activity-dot {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            flex-shrink: 0;
            background: #eff6ff;
            color: var(--primary);
        }

        .activity-item h4 {
            color: var(--navy);
            font-size: .82rem;
            font-weight: 700;
            margin: 2px 0 5px;
        }

        .activity-item p {
            color: var(--muted);
            font-size: .76rem;
            line-height: 1.6;
            margin: 0;
            overflow-wrap: anywhere;
        }

        .profile-mini {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .mini-avatar {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--primary);
            background: #eff6ff;
            font-weight: 800;
        }

        .profile-mini h4 {
            color: var(--navy);
            font-size: .9rem;
            font-weight: 800;
            margin-bottom: 4px;
            overflow-wrap: anywhere;
        }

        .profile-mini p {
            color: var(--muted);
            font-size: .77rem;
            margin: 0;
            overflow-wrap: anywhere;
        }

        footer {
            background: #fff;
            border-top: 1px solid var(--border);
            padding: 24px 0;
            color: var(--muted);
            font-size: .82rem;
        }

        footer strong {
            color: var(--navy);
        }

        @media (max-width: 767px) {
            .dashboard-wrap {
                padding-top: 24px;
            }

            .welcome-panel {
                padding: 23px;
            }

            .welcome-avatar {
                width: 55px;
                height: 55px;
                font-size: 1.2rem;
            }

            .section-header {
                padding: 19px;
            }

            .table-responsive {
                padding: 0 12px 8px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            Pro<span class="brand-highlight">Serve</span>
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
                    <a class="nav-link" href="services.php">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="request_quote.php">Request a Quote</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="book_appointment.php">Book Appointment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_appointments.php">My Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./my_quotations.php">My Quotations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="profile.php" class="btn-outline-custom">
                        <i class="bi bi-person-circle"></i> My Profile
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container dashboard-wrap">

    <div class="d-flex flex-column flex-md-row justify-content-between
                align-items-md-center gap-3">
        <div>
            <div class="eyebrow">Customer portal</div>
            <h1 class="page-title">Dashboard</h1>
            <p class="muted-text mb-0">
                Manage your services, appointments, and business enquiries in one place.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="services.php" class="btn-outline-custom">
                <i class="bi bi-grid"></i> Explore Services
            </a>
            <a href="book_appointment.php" class="btn-primary-custom">
                <i class="bi bi-calendar-plus"></i> Book Appointment
            </a>
        </div>
    </div>

    <section class="welcome-panel">
        <div class="welcome-content d-flex flex-column flex-md-row
                    align-items-md-center justify-content-between gap-4">
            <div>
                <div class="small mb-2" style="color:#dbeafe;font-weight:700;">
                    YOUR PROSERVE ACCOUNT
                </div>
                <h2>Welcome back, <?= e($firstName) ?>!</h2>
                <p>
                    We're here to help your business run smoothly. Review your
                    recent activity, track appointments, or request a service
                    from your customer dashboard.
                </p>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="request_quote.php" class="btn-white">
                        <i class="bi bi-file-earmark-text"></i> Request a Quote
                    </a>
                    <a href="my_appointments.php"
                       class="btn btn-outline-light rounded-3 fw-bold px-3 py-2">
                        View Appointments
                    </a>
                </div>
            </div>

            <div class="welcome-avatar">
                <?= e($initials) ?>
            </div>
        </div>
    </section>

    <!-- Dashboard summary -->
    <div class="row g-3">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Appointments</span>
                    <div class="stat-icon icon-blue">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $totalAppointments ?></div>
                <div class="stat-caption">All recorded appointments</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Pending Appointments</span>
                    <div class="stat-icon icon-orange">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $pendingAppointments ?></div>
                <div class="stat-caption">Awaiting confirmation</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Quote Requests</span>
                    <div class="stat-icon icon-purple">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
                <div class="stat-number"><?= count($myQuotes) ?></div>
                <div class="stat-caption">
                    <?= $pendingQuotes ?> pending or under review
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Contact Enquiries</span>
                    <div class="stat-icon icon-green">
                        <i class="bi bi-chat-square-text"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $totalEnquiries ?></div>
                <div class="stat-caption">Enquiries submitted</div>
            </div>
        </div>
    </div>

    <!-- Main content and side panel -->
    <div class="row g-4">
        <div class="col-lg-8">

            <!-- Recent appointments -->
            <section class="section-card">
                <div class="section-header">
                    <div>
                        <h3>Recent Appointments</h3>
                        <p>Review your latest service bookings.</p>
                    </div>
                    <a href="my_appointments.php" class="btn-outline-custom">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <?php if (!empty($recentAppointments)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Appointment</th>
                                    <th>Date &amp; Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentAppointments as $appointment): ?>
                                    <?php
                                    $appointmentStatus = $appointment['status']
                                        ?? 'PENDING CONFIRMATION';

                                    $appointmentService = $appointment['service']
                                        ?? $appointment['service_name']
                                        ?? 'Service Appointment';

                                    $appointmentDate = $appointment['appointment_date']
                                        ?? $appointment['date']
                                        ?? '';

                                    $appointmentTime = $appointment['time_label']
                                        ?? $appointment['appointment_time']
                                        ?? $appointment['time']
                                        ?? '';
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="record-title">
                                                <?= e($appointmentService) ?>
                                            </div>
                                            <div class="record-subtitle">
                                                Ref:
                                                <?= e($appointment['reference'] ?? 'N/A') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="record-title">
                                                <?= e(formatDate($appointmentDate)) ?>
                                            </div>
                                            <div class="record-subtitle">
                                                <?= e($appointmentTime ?: 'Time not specified') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge <?= e(statusClass($appointmentStatus)) ?>">
                                                <?= e(ucwords(strtolower($appointmentStatus))) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-calendar2-plus"></i>
                        </div>
                        <h4>No appointments yet</h4>
                        <p>
                            Book your first service appointment and it will appear here.
                        </p>
                        <a href="book_appointment.php" class="btn-primary-custom">
                            <i class="bi bi-plus-lg"></i> Book an Appointment
                        </a>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Recent quote requests -->
            <section class="section-card">
                <div class="section-header">
                    <div>
                        <h3>Recent Quote Requests</h3>
                        <p>Track the service quotations you've requested.</p>
                    </div>
                    <a href="request_quote.php" class="btn-outline-custom">
                        New Request <i class="bi bi-plus-lg"></i>
                    </a>
                </div>

                <?php if (!empty($recentQuotes)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentQuotes as $quote): ?>
                                    <?php
                                    $quoteStatus = $quote['status'] ?? 'PENDING';
                                    $quoteService = $quote['service']
                                        ?? $quote['service_name']
                                        ?? $quote['service_type']
                                        ?? 'Service Quote';

                                    $quoteDate = $quote['created_at']
                                        ?? $quote['date']
                                        ?? '';
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="record-title">
                                                <?= e($quoteService) ?>
                                            </div>
                                            <div class="record-subtitle">
                                                Ref:
                                                <?= e($quote['reference'] ?? $quote['quote_reference'] ?? 'N/A') ?>
                                            </div>
                                        </td>
                                        <td><?= e(formatDate($quoteDate)) ?></td>
                                        <td>
                                            <span class="status-badge <?= e(statusClass($quoteStatus)) ?>">
                                                <?= e(ucwords(strtolower($quoteStatus))) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-file-earmark-plus"></i>
                        </div>
                        <h4>No quote requests yet</h4>
                        <p>
                            Tell us what you need and request a quotation for your project.
                        </p>
                        <a href="request_quote.php" class="btn-primary-custom">
                            Request a Quote
                        </a>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Recent contact enquiries -->
            <section class="section-card">
                <div class="section-header">
                    <div>
                        <h3>Recent Contact Enquiries</h3>
                        <p>Your latest messages submitted through the contact form.</p>
                    </div>
                    <a href="contact.php" class="btn-outline-custom">
                        Contact Us <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <?php if (!empty($recentEnquiries)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentEnquiries as $enquiry): ?>
                                    <?php
                                    $enquirySubject = $enquiry['subject']
                                        ?? $enquiry['service']
                                        ?? 'General Enquiry';

                                    $enquiryDate = $enquiry['created_at']
                                        ?? $enquiry['date']
                                        ?? '';

                                    $enquiryStatus = $enquiry['status'] ?? 'NEW LEAD';
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="record-title">
                                                <?= e($enquirySubject) ?>
                                            </div>
                                            <div class="record-subtitle">
                                                Ref:
                                                <?= e($enquiry['reference'] ?? 'N/A') ?>
                                            </div>
                                        </td>
                                        <td><?= e(formatDate($enquiryDate)) ?></td>
                                        <td>
                                            <span class="status-badge <?= e(statusClass($enquiryStatus)) ?>">
                                                <?= e(ucwords(strtolower($enquiryStatus))) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-chat-square-dots"></i>
                        </div>
                        <h4>No enquiries yet</h4>
                        <p>
                            Have a question? Send our team a message.
                        </p>
                        <a href="contact.php" class="btn-primary-custom">
                            <i class="bi bi-envelope"></i> Contact ProServe
                        </a>
                    </div>
                <?php endif; ?>
            </section>

        </div>

        <!-- Right-side dashboard panels -->
        <aside class="col-lg-4">

            <section class="side-panel">
                <h3>Quick Actions</h3>

                <div class="row g-3">
                    <div class="col-12">
                        <a href="book_appointment.php" class="quick-action">
                            <div class="quick-action-icon">
                                <i class="bi bi-calendar-plus"></i>
                            </div>
                            <div>
                                <h4>Book an Appointment</h4>
                                <p>Schedule a consultation or service visit.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12">
                        <a href="request_quote.php" class="quick-action">
                            <div class="quick-action-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div>
                                <h4>Request a Quote</h4>
                                <p>Tell us about your project and requirements.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12">
                        <a href="services.php" class="quick-action">
                            <div class="quick-action-icon">
                                <i class="bi bi-grid"></i>
                            </div>
                            <div>
                                <h4>Explore Our Services</h4>
                                <p>Discover services available for your business.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12">
                        <a href="profile.php" class="quick-action">
                            <div class="quick-action-icon">
                                <i class="bi bi-person-gear"></i>
                            </div>
                            <div>
                                <h4>Update My Profile</h4>
                                <p>Keep your personal and company details current.</p>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <section class="side-panel">
                <h3>My Account</h3>

                <div class="profile-mini">
                    <div class="mini-avatar"><?= e($initials) ?></div>
                    <div>
                        <h4><?= e($customerName) ?></h4>
                        <p><?= e($customerEmail ?: 'No email provided') ?></p>
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--border);">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="muted-text">Confirmed appointments</span>
                    <strong><?= $confirmedAppointments ?></strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="muted-text">Completed appointments</span>
                    <strong><?= $completedAppointments ?></strong>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="muted-text">Pending quotes</span>
                    <strong><?= $pendingQuotes ?></strong>
                </div>

                <a href="profile.php"
                   class="btn-outline-custom w-100 mt-4">
                    <i class="bi bi-pencil-square"></i> Manage Profile
                </a>
            </section>

            <section class="side-panel">
                <h3>Recent Activity</h3>

                <?php if (!empty($recentAppointments) || !empty($recentQuotes) || !empty($recentEnquiries)): ?>

                    <?php foreach (array_slice($recentAppointments, 0, 2) as $appointment): ?>
                        <div class="activity-item">
                            <div class="activity-dot">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div>
                                <h4>Appointment recorded</h4>
                                <p>
                                    <?= e($appointment['service'] ?? $appointment['service_name'] ?? 'Service appointment') ?>
                                    &middot;
                                    <?= e(formatDate($appointment['created_at'] ?? $appointment['appointment_date'] ?? '')) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach (array_slice($recentQuotes, 0, 2) as $quote): ?>
                        <div class="activity-item">
                            <div class="activity-dot">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div>
                                <h4>Quote request submitted</h4>
                                <p>
                                    <?= e($quote['service'] ?? $quote['service_name'] ?? 'Service quote') ?>
                                    &middot;
                                    <?= e(formatDate($quote['created_at'] ?? $quote['date'] ?? '')) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach (array_slice($recentEnquiries, 0, 1) as $enquiry): ?>
                        <div class="activity-item">
                            <div class="activity-dot">
                                <i class="bi bi-chat-square-text"></i>
                            </div>
                            <div>
                                <h4>Contact enquiry submitted</h4>
                                <p>
                                    <?= e($enquiry['subject'] ?? 'General enquiry') ?>
                                    &middot;
                                    <?= e(formatDate($enquiry['created_at'] ?? $enquiry['date'] ?? '')) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="bi bi-clock-history fs-3 text-primary"></i>
                        <p class="muted-text mt-2 mb-0">
                            Your recent activity will appear here when you use the portal.
                        </p>
                    </div>
                <?php endif; ?>
            </section>

        </aside>
    </div>

</main>

<footer>
    <div class="container d-flex flex-column flex-md-row
                justify-content-between align-items-center gap-2">
        <div>
            <strong>ProServe</strong> &mdash; CRM &amp; Service Management System
        </div>
        <div>Portfolio demonstration by <strong>Codvanta</strong>.</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
