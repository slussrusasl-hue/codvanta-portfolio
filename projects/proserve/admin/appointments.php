
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe - Administrator Appointment Management
|--------------------------------------------------------------------------
| Demonstration features:
| - View customer appointment requests
| - Search and filter appointments
| - Accept or reject appointment requests
| - Reschedule appointments
| - Mark appointments as completed
| - Cancel appointments
|
| Uses PHP sessions only. No database required.
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['proserve_appointments'])) {
    $_SESSION['proserve_appointments'] = [
        [
            'reference' => 'PS-APT-26001',
            'full_name' => 'Daniel Perera',
            'email' => 'daniel@example.com',
            'phone' => '+94 77 123 4567',
            'company' => 'Perera Holdings',
            'service' => 'Website Development Consultation',
            'appointment_type' => 'Online Meeting',
            'appointment_date' => date('Y-m-d', strtotime('+1 day')),
            'appointment_time' => '10:00',
            'time_label' => '10:00 AM',
            'status' => 'PENDING CONFIRMATION',
            'location' => 'Online',
            'notes' => 'Discuss a new corporate website.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'reference' => 'PS-APT-26002',
            'full_name' => 'Sarah Fernando',
            'email' => 'sarah@example.com',
            'phone' => '+94 71 555 1234',
            'company' => 'Bright Solutions',
            'service' => 'Digital Marketing Consultation',
            'appointment_type' => 'Online Meeting',
            'appointment_date' => date('Y-m-d', strtotime('+2 days')),
            'appointment_time' => '14:00',
            'time_label' => '02:00 PM',
            'status' => 'CONFIRMED',
            'location' => 'Online',
            'notes' => 'SEO and social media marketing discussion.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'reference' => 'PS-APT-26003',
            'full_name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'phone' => '+94 76 444 7788',
            'company' => 'Silva Engineering',
            'service' => 'Business Management System',
            'appointment_type' => 'In-Person Meeting',
            'appointment_date' => date('Y-m-d', strtotime('+3 days')),
            'appointment_time' => '11:30',
            'time_label' => '11:30 AM',
            'status' => 'PENDING CONFIRMATION',
            'location' => 'Customer Office',
            'notes' => 'Needs an inventory and employee management system.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
        ],
        [
            'reference' => 'PS-APT-26004',
            'full_name' => 'Emma Wijesinghe',
            'email' => 'emma@example.com',
            'phone' => '+94 72 333 9900',
            'company' => 'Nova Retail',
            'service' => 'E-commerce Development',
            'appointment_type' => 'Online Meeting',
            'appointment_date' => date('Y-m-d', strtotime('-2 days')),
            'appointment_time' => '09:30',
            'time_label' => '09:30 AM',
            'status' => 'COMPLETED',
            'location' => 'Online',
            'notes' => 'Discussed an online store and payment integration.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
        ],
        [
            'reference' => 'PS-APT-26005',
            'full_name' => 'John Peris',
            'email' => 'john@example.com',
            'phone' => '+94 75 222 6677',
            'company' => 'Peris Services',
            'service' => 'IT Consulting',
            'appointment_type' => 'Phone Call',
            'appointment_date' => date('Y-m-d', strtotime('+4 days')),
            'appointment_time' => '15:00',
            'time_label' => '03:00 PM',
            'status' => 'RESCHEDULED',
            'location' => 'Phone Call',
            'notes' => 'Review current IT infrastructure.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'reference' => 'PS-APT-26006',
            'full_name' => 'Olivia Jayawardena',
            'email' => 'olivia@example.com',
            'phone' => '+94 78 111 2233',
            'company' => 'Olivia Fashion',
            'service' => 'Custom Web Application Development',
            'appointment_type' => 'Online Meeting',
            'appointment_date' => date('Y-m-d', strtotime('+5 days')),
            'appointment_time' => '13:00',
            'time_label' => '01:00 PM',
            'status' => 'REJECTED',
            'location' => 'Online',
            'notes' => 'Discuss a custom business application.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
        ]
    ];
}

if (!isset($_SESSION['proserve_appointments_csrf'])) {
    $_SESSION['proserve_appointments_csrf'] = bin2hex(random_bytes(32));
}

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function appointmentStatusClass($status): string
{
    return match (strtoupper(trim((string)$status))) {
        'CONFIRMED', 'APPROVED' => 'status-confirmed',
        'COMPLETED' => 'status-completed',
        'REJECTED', 'CANCELLED' => 'status-rejected',
        'RESCHEDULED' => 'status-rescheduled',
        default => 'status-pending'
    };
}

function appointmentDateLabel($date): string
{
    if (!$date) {
        return 'Not specified';
    }

    $timestamp = strtotime($date);

    return $timestamp ? date('d M Y', $timestamp) : 'Not specified';
}

function appointmentTimeLabel($appointment): string
{
    if (!empty($appointment['time_label'])) {
        return (string)$appointment['time_label'];
    }

    if (!empty($appointment['appointment_time'])) {
        $timestamp = strtotime((string)$appointment['appointment_time']);

        return $timestamp ? date('h:i A', $timestamp) : (string)$appointment['appointment_time'];
    }

    return 'Not specified';
}

$allowedStatuses = [
    'PENDING CONFIRMATION',
    'CONFIRMED',
    'RESCHEDULED',
    'COMPLETED',
    'REJECTED',
    'CANCELLED'
];

$notice = '';
$noticeType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = (string)($_POST['csrf_token'] ?? '');
    $sessionToken = (string)$_SESSION['proserve_appointments_csrf'];

    if (!hash_equals($sessionToken, $submittedToken)) {
        $notice = 'Your session verification failed. Please try again.';
        $noticeType = 'danger';
    } else {
        $action = (string)($_POST['action'] ?? '');
        $reference = trim((string)($_POST['reference'] ?? ''));
        $appointmentIndex = null;

        foreach ($_SESSION['proserve_appointments'] as $index => $appointment) {
            if (
                isset($appointment['reference']) &&
                hash_equals((string)$appointment['reference'], $reference)
            ) {
                $appointmentIndex = $index;
                break;
            }
        }

        if ($appointmentIndex === null) {
            $notice = 'Appointment not found.';
            $noticeType = 'danger';
        } else {
            $currentStatus = strtoupper(trim(
                (string)($_SESSION['proserve_appointments'][$appointmentIndex]['status'] ?? '')
            ));

            switch ($action) {
                case 'confirm':
                    if (!in_array($currentStatus, ['PENDING CONFIRMATION', 'PENDING'], true)) {
                        $notice = 'Only pending appointments can be confirmed.';
                        $noticeType = 'warning';
                    } else {
                        $_SESSION['proserve_appointments'][$appointmentIndex]['status'] = 'CONFIRMED';
                        $_SESSION['proserve_appointments'][$appointmentIndex]['updated_at'] = date('Y-m-d H:i:s');
                        $notice = 'Appointment confirmed successfully.';
                    }
                    break;

                case 'reject':
                    if (!in_array($currentStatus, ['PENDING CONFIRMATION', 'PENDING', 'RESCHEDULED'], true)) {
                        $notice = 'This appointment cannot be rejected in its current status.';
                        $noticeType = 'warning';
                    } else {
                        $_SESSION['proserve_appointments'][$appointmentIndex]['status'] = 'REJECTED';
                        $_SESSION['proserve_appointments'][$appointmentIndex]['updated_at'] = date('Y-m-d H:i:s');
                        $notice = 'Appointment request rejected.';
                    }
                    break;

                case 'complete':
                    if (!in_array($currentStatus, ['CONFIRMED', 'RESCHEDULED'], true)) {
                        $notice = 'Only confirmed or rescheduled appointments can be completed.';
                        $noticeType = 'warning';
                    } else {
                        $_SESSION['proserve_appointments'][$appointmentIndex]['status'] = 'COMPLETED';
                        $_SESSION['proserve_appointments'][$appointmentIndex]['updated_at'] = date('Y-m-d H:i:s');
                        $notice = 'Appointment marked as completed.';
                    }
                    break;

                case 'cancel':
                    if (in_array($currentStatus, ['COMPLETED', 'CANCELLED'], true)) {
                        $notice = 'This appointment can no longer be cancelled.';
                        $noticeType = 'warning';
                    } else {
                        $_SESSION['proserve_appointments'][$appointmentIndex]['status'] = 'CANCELLED';
                        $_SESSION['proserve_appointments'][$appointmentIndex]['updated_at'] = date('Y-m-d H:i:s');
                        $notice = 'Appointment cancelled.';
                    }
                    break;

                case 'reschedule':
                    $newDate = trim((string)($_POST['appointment_date'] ?? ''));
                    $newTime = trim((string)($_POST['appointment_time'] ?? ''));

                    $validDate = DateTime::createFromFormat('!Y-m-d', $newDate);
                    $validTime = DateTime::createFromFormat('!H:i', $newTime);

                    $dateValid = $validDate && $validDate->format('Y-m-d') === $newDate;
                    $timeValid = $validTime && $validTime->format('H:i') === $newTime;

                    if (in_array($currentStatus, ['COMPLETED', 'CANCELLED', 'REJECTED'], true)) {
                        $notice = 'This appointment cannot be rescheduled.';
                        $noticeType = 'warning';
                    } elseif (!$dateValid || !$timeValid) {
                        $notice = 'Please select a valid date and time.';
                        $noticeType = 'danger';
                    } elseif ($newDate < date('Y-m-d') ||
                        ($newDate === date('Y-m-d') && $newTime <= date('H:i'))) {
                        $notice = 'Please choose a future appointment date and time.';
                        $noticeType = 'warning';
                    } else {
                        $_SESSION['proserve_appointments'][$appointmentIndex]['appointment_date'] = $newDate;
                        $_SESSION['proserve_appointments'][$appointmentIndex]['appointment_time'] = $newTime;
                        $_SESSION['proserve_appointments'][$appointmentIndex]['time_label'] =
                            date('h:i A', strtotime($newTime));
                        $_SESSION['proserve_appointments'][$appointmentIndex]['status'] = 'RESCHEDULED';
                        $_SESSION['proserve_appointments'][$appointmentIndex]['updated_at'] = date('Y-m-d H:i:s');

                        $notice = 'Appointment rescheduled successfully.';
                    }
                    break;

                default:
                    $notice = 'Unknown appointment action.';
                    $noticeType = 'danger';
            }
        }
    }

    // Prevent accidental form resubmission when refreshing.
    $_SESSION['proserve_appointments_notice'] = [
        'message' => $notice,
        'type' => $noticeType
    ];

    header('Location: appointments.php');
    exit;
}

if (isset($_SESSION['proserve_appointments_notice'])) {
    $savedNotice = $_SESSION['proserve_appointments_notice'];
    $notice = (string)($savedNotice['message'] ?? '');
    $noticeType = (string)($savedNotice['type'] ?? 'success');
    unset($_SESSION['proserve_appointments_notice']);
}

$appointments = array_values($_SESSION['proserve_appointments']);

usort($appointments, function ($a, $b) {
    $dateA = ($a['appointment_date'] ?? '') . ' ' . ($a['appointment_time'] ?? '');
    $dateB = ($b['appointment_date'] ?? '') . ' ' . ($b['appointment_time'] ?? '');

    return strcmp($dateA, $dateB);
});

$totalAppointments = count($appointments);
$pendingCount = 0;
$confirmedCount = 0;
$completedCount = 0;
$cancelledCount = 0;

foreach ($appointments as $appointment) {
    $status = strtoupper(trim((string)($appointment['status'] ?? '')));

    if (in_array($status, ['PENDING', 'PENDING CONFIRMATION'], true)) {
        $pendingCount++;
    } elseif (in_array($status, ['CONFIRMED', 'APPROVED', 'RESCHEDULED'], true)) {
        $confirmedCount++;
    } elseif ($status === 'COMPLETED') {
        $completedCount++;
    } elseif (in_array($status, ['CANCELLED', 'REJECTED'], true)) {
        $cancelledCount++;
    }
}

$search = trim((string)($_GET['search'] ?? ''));
$statusFilter = strtoupper(trim((string)($_GET['status'] ?? 'ALL')));
$dateFilter = trim((string)($_GET['date'] ?? ''));

if (!in_array($statusFilter, array_merge(['ALL'], $allowedStatuses), true)) {
    $statusFilter = 'ALL';
}

if ($dateFilter !== '') {
    $parsedFilterDate = DateTime::createFromFormat('!Y-m-d', $dateFilter);

    if (!$parsedFilterDate || $parsedFilterDate->format('Y-m-d') !== $dateFilter) {
        $dateFilter = '';
    }
}

$filteredAppointments = array_values(array_filter(
    $appointments,
    function ($appointment) use ($search, $statusFilter, $dateFilter) {
        $status = strtoupper(trim((string)($appointment['status'] ?? '')));

        if ($statusFilter !== 'ALL' && $status !== $statusFilter) {
            return false;
        }

        if ($dateFilter !== '' && ($appointment['appointment_date'] ?? '') !== $dateFilter) {
            return false;
        }

        $searchable = implode(' ', [
            $appointment['reference'] ?? '',
            $appointment['full_name'] ?? '',
            $appointment['name'] ?? '',
            $appointment['email'] ?? '',
            $appointment['phone'] ?? '',
            $appointment['company'] ?? '',
            $appointment['service'] ?? '',
            $appointment['appointment_type'] ?? ''
        ]);

        return $search === '' || stripos($searchable, $search) !== false;
    }
));

$today = date('Y-m-d');
$todayCount = count(array_filter($appointments, function ($appointment) use ($today) {
    return ($appointment['appointment_date'] ?? '') === $today;
}));

$upcomingCount = count(array_filter($appointments, function ($appointment) use ($today) {
    $date = (string)($appointment['appointment_date'] ?? '');
    $status = strtoupper(trim((string)($appointment['status'] ?? '')));

    return $date >= $today &&
        !in_array($status, ['COMPLETED', 'CANCELLED', 'REJECTED'], true);
}));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Management | ProServe Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10233f;
            --muted: #718096;
            --border: #e8edf4;
            --background: #f5f8fc;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--background);
            color: #26364b;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 14px;
        }

        a { text-decoration: none; }

        .sidebar {
            width: 250px;
            position: fixed;
            inset: 0 auto 0 0;
            background: var(--navy);
            color: white;
            padding: 26px 17px;
            z-index: 1030;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: white;
            font-weight: 800;
            font-size: 23px;
            padding: 0 12px;
            margin-bottom: 34px;
        }

        .brand-icon {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: var(--primary);
            font-size: 21px;
        }

        .sidebar-label {
            color: #94a8c5;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            padding: 0 12px;
            margin: 22px 0 9px;
        }

        .nav-link {
            color: #c4d0e1;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 9px;
            margin-bottom: 5px;
            font-size: 13px;
            transition: .2s;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(59, 130, 246, .22);
        }

        .nav-link.active {
            box-shadow: inset 3px 0 #60a5fa;
        }

        .nav-link i { font-size: 17px; }

        .sidebar-bottom {
            margin-top: 35px;
            border-top: 1px solid rgba(255,255,255,.12);
            padding-top: 16px;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            min-height: 76px;
            border-bottom: 1px solid var(--border);
            padding: 16px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .topbar-title { font-weight: 800; color: var(--navy); font-size: 17px; }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-avatar {
            width: 39px;
            height: 39px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: var(--primary);
            background: #e9f0ff;
            font-weight: 800;
        }

        .content { padding: 28px 30px; }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .page-heading h1 {
            font-size: 25px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -.7px;
            margin-bottom: 7px;
        }

        .page-heading p { color: var(--muted); margin: 0; }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .metric-card, .panel {
            background: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 4px 16px rgba(16, 35, 63, .025);
        }

        .metric-card {
            padding: 19px;
            height: 100%;
        }

        .metric-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 15px;
        }

        .metric-label { color: var(--muted); font-size: 12px; font-weight: 600; }

        .metric-icon {
            width: 41px;
            height: 41px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            font-size: 19px;
        }

        .metric-value {
            font-size: 28px;
            line-height: 1.1;
            color: var(--navy);
            font-weight: 800;
            margin-bottom: 6px;
        }

        .metric-note { font-size: 11px; color: var(--muted); }

        .panel { overflow: hidden; margin-top: 25px; }

        .panel-heading {
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .panel-heading h2 {
            color: var(--navy);
            font-size: 16px;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .panel-heading p { margin: 0; color: var(--muted); font-size: 12px; }

        .filters {
            display: grid;
            grid-template-columns: minmax(190px, 1.5fr) minmax(155px, 1fr) minmax(150px, 1fr) auto;
            gap: 10px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
        }

        .form-control, .form-select {
            border-color: #dce4ef;
            border-radius: 8px;
            font-size: 12px;
            min-height: 40px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #93b4ff;
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .09);
        }

        .table-responsive { width: 100%; }

        .table { margin: 0; white-space: nowrap; }

        .table thead th {
            background: #f8fafd;
            color: #718096;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            padding: 15px 16px;
            border-color: #edf1f6;
            vertical-align: middle;
            font-size: 12px;
        }

        .table tbody tr:hover { background: #fbfdff; }

        .customer-name { color: var(--navy); font-weight: 700; font-size: 12px; }
        .subtext { color: var(--muted); font-size: 10px; margin-top: 5px; }

        .reference {
            display: inline-block;
            padding: 5px 7px;
            border-radius: 6px;
            background: #f0f5ff;
            color: #315bb2;
            font-size: 10px;
            font-weight: 800;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 800;
        }

        .status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-pending { color: #b7791f; background: #fff6df; }
        .status-confirmed { color: #24704b; background: #e4f8ed; }
        .status-completed { color: #285bb5; background: #e8f0ff; }
        .status-rejected { color: #c24141; background: #ffeded; }
        .status-rescheduled { color: #7652ba; background: #f1eaff; }

        .action-btn {
            border: 1px solid #e0e7f0;
            width: 31px;
            height: 31px;
            border-radius: 8px;
            background: white;
            color: #64748b;
            display: inline-grid;
            place-items: center;
            transition: .2s;
        }

        .action-btn:hover { color: var(--primary); border-color: #b8ccff; background: #f5f8ff; }

        .empty-state { text-align: center; padding: 55px 20px; color: var(--muted); }
        .empty-state i { font-size: 37px; color: #aabbd3; display: block; margin-bottom: 12px; }
        .empty-state h3 { font-size: 16px; color: var(--navy); font-weight: 800; }

        .modal-content { border: 0; border-radius: 15px; overflow: hidden; }
        .modal-header { background: #f7f9fd; border-bottom: 1px solid var(--border); }
        .modal-title { color: var(--navy); font-weight: 800; font-size: 16px; }
        .detail-label { color: var(--muted); font-size: 10px; font-weight: 700; margin-bottom: 5px; }
        .detail-value { color: var(--navy); font-weight: 600; font-size: 13px; overflow-wrap: anywhere; }
        .detail-block { background: #f8fafd; padding: 12px; border-radius: 9px; height: 100%; }

        .page-footer {
            color: var(--muted);
            font-size: 11px;
            padding: 22px 30px;
            border-top: 1px solid var(--border);
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 1200px) {
            .sidebar { width: 220px; }
            .main { margin-left: 220px; }
            .filters { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 767px) {
            .sidebar {
                position: relative;
                width: 100%;
                padding: 15px;
            }

            .brand { margin-bottom: 14px; }
            .sidebar-label { margin-top: 12px; }
            .sidebar nav { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; }
            .nav-link { margin: 0; padding: 10px; }
            .sidebar-bottom { margin-top: 15px; }
            .main { margin-left: 0; }
            .topbar { padding: 15px 18px; }
            .content { padding: 22px 15px; }
            .filters { grid-template-columns: 1fr; padding: 15px; }
            .panel-heading { padding: 17px 15px; }
            .page-heading h1 { font-size: 22px; }
            .page-footer { padding: 20px 15px; }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <a href="index.php" class="brand">
        <span class="brand-icon"><i class="bi bi-layers-half"></i></span>
        ProServe
    </a>

    <div class="sidebar-label">MAIN MENU</div>

    <nav>
        <a href="index.php" class="nav-link">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="customers.php" class="nav-link">
            <i class="bi bi-people"></i> Customers
        </a>
        <a href="leads.php" class="nav-link">
            <i class="bi bi-funnel"></i> Lead Management
        </a>
        <a href="appointments.php" class="nav-link active">
            <i class="bi bi-calendar-check"></i> Appointments
        </a>
        <a href="services.php" class="nav-link">
            <i class="bi bi-grid"></i> Services
        </a>
        <a href="quotations.php" class="nav-link">
            <i class="bi bi-file-earmark-text"></i> Quotations
        </a>
        <a href="invoices.php" class="nav-link">
            <i class="bi bi-receipt"></i> Invoices
        </a>
        <a href="employees.php" class="nav-link">
            <i class="bi bi-person-workspace"></i> Employees
        </a>
        <a href="enquiries.php" class="nav-link">
            <i class="bi bi-chat-left-text"></i> Contact Enquiries
        </a>
        <a href="reports.php" class="nav-link">
            <i class="bi bi-bar-chart"></i> Reports
        </a>
        <a href="settings.php" class="nav-link">
            <i class="bi bi-gear"></i> Settings
        </a>
    </nav>

    <div class="sidebar-bottom">
        <a href="../index.php" class="nav-link">
            <i class="bi bi-box-arrow-up-right"></i> View Customer Site
        </a>
        <a href="../dashboard.php" class="nav-link">
            <i class="bi bi-person-circle"></i> Customer Dashboard
        </a>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <div>
            <div class="topbar-title">Appointment Management</div>
            <div class="text-secondary small mt-1">ProServe / Administrator / Appointments</div>
        </div>

        <div class="admin-profile">
            <div class="text-end d-none d-sm-block">
                <div class="fw-bold" style="font-size:12px;color:#10233f;">Administrator</div>
                <div class="text-secondary" style="font-size:10px;">System Manager</div>
            </div>
            <div class="admin-avatar">A</div>
        </div>
    </header>

    <main class="content">
        <div class="page-heading">
            <div>
                <h1>Appointment Management</h1>
                <p>Review requests, coordinate meetings, and manage customer appointments.</p>
            </div>
            <button class="btn btn-primary btn-sm px-3 py-2" type="button"
                    data-bs-toggle="modal" data-bs-target="#appointmentInfoModal">
                <i class="bi bi-info-circle me-1"></i> Workflow Guide
            </button>
        </div>

        <?php if ($notice !== ''): ?>
            <div class="alert alert-<?= e(in_array($noticeType, ['success', 'warning', 'danger', 'info'], true) ? $noticeType : 'info') ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i><?= e($notice) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-3">
            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Total Appointments</div>
                        <div class="metric-icon" style="background:#eaf0ff;color:#2563eb;">
                            <i class="bi bi-calendar2-week"></i>
                        </div>
                    </div>
                    <div class="metric-value"><?= $totalAppointments ?></div>
                    <div class="metric-note">All appointment records</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Pending Requests</div>
                        <div class="metric-icon" style="background:#fff4dc;color:#b7791f;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="metric-value"><?= $pendingCount ?></div>
                    <div class="metric-note">Awaiting administrator action</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Confirmed / Rescheduled</div>
                        <div class="metric-icon" style="background:#e1f8eb;color:#238453;">
                            <i class="bi bi-calendar2-check"></i>
                        </div>
                    </div>
                    <div class="metric-value"><?= $confirmedCount ?></div>
                    <div class="metric-note">Active appointments</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Upcoming Appointments</div>
                        <div class="metric-icon" style="background:#f0eaff;color:#7652ba;">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                    </div>
                    <div class="metric-value"><?= $upcomingCount ?></div>
                    <div class="metric-note"><?= $todayCount ?> scheduled for today</div>
                </div>
            </div>
        </div>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2><i class="bi bi-calendar-check me-2" style="color:#2563eb;"></i>Appointment Requests</h2>
                    <p>Search customer records and update appointment status.</p>
                </div>
                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    <?= count($filteredAppointments) ?> result(s)
                </span>
            </div>

            <form method="GET" action="appointments.php" class="filters">
                <div>
                    <input type="search" name="search" class="form-control"
                           placeholder="Search name, reference, service..."
                           value="<?= e($search) ?>">
                </div>

                <div>
                    <select name="status" class="form-select" aria-label="Filter by status">
                        <option value="ALL" <?= $statusFilter === 'ALL' ? 'selected' : '' ?>>All statuses</option>
                        <?php foreach ($allowedStatuses as $statusOption): ?>
                            <option value="<?= e($statusOption) ?>"
                                <?= $statusFilter === $statusOption ? 'selected' : '' ?>>
                                <?= e(ucwords(strtolower($statusOption))) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <input type="date" name="date" class="form-control"
                           value="<?= e($dateFilter) ?>" aria-label="Filter by appointment date">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="appointments.php" class="btn btn-light border btn-sm px-3">Reset</a>
                </div>
            </form>

            <?php if (count($filteredAppointments) > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Reference / Customer</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Meeting Type</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($filteredAppointments as $appointment): ?>
                            <?php
                                $ref = (string)($appointment['reference'] ?? 'N/A');
                                $name = (string)($appointment['full_name'] ?? $appointment['name'] ?? 'Customer');
                                $status = strtoupper(trim((string)($appointment['status'] ?? 'PENDING CONFIRMATION')));
                                $date = (string)($appointment['appointment_date'] ?? '');
                                $time = (string)($appointment['appointment_time'] ?? '');
                            ?>
                            <tr>
                                <td>
                                    <span class="reference"><?= e($ref) ?></span>
                                    <div class="customer-name mt-2"><?= e($name) ?></div>
                                    <div class="subtext"><?= e($appointment['email'] ?? '') ?></div>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="color:#26364b;max-width:210px;white-space:normal;">
                                        <?= e($appointment['service'] ?? 'General Consultation') ?>
                                    </div>
                                    <div class="subtext"><?= e($appointment['company'] ?? 'Individual customer') ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold" style="color:#10233f;">
                                        <?= e(appointmentDateLabel($date)) ?>
                                    </div>
                                    <div class="subtext"><?= e(appointmentTimeLabel($appointment)) ?></div>
                                </td>
                                <td>
                                    <div><?= e($appointment['appointment_type'] ?? 'Online Meeting') ?></div>
                                    <div class="subtext"><?= e($appointment['location'] ?? 'Online') ?></div>
                                </td>
                                <td>
                                    <span class="status <?= e(appointmentStatusClass($status)) ?>">
                                        <?= e(ucwords(strtolower($status))) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button type="button" class="action-btn"
                                                title="View details"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailsModal"
                                                data-appointment="<?= e(json_encode($appointment, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <?php if (in_array($status, ['PENDING', 'PENDING CONFIRMATION'], true)): ?>
                                            <form method="POST" action="appointments.php">
                                                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_appointments_csrf']) ?>">
                                                <input type="hidden" name="reference" value="<?= e($ref) ?>">
                                                <input type="hidden" name="action" value="confirm">
                                                <button type="submit" class="action-btn text-success"
                                                        title="Confirm appointment"
                                                        onclick="return confirm('Confirm this appointment?');">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if (!in_array($status, ['COMPLETED', 'CANCELLED', 'REJECTED'], true)): ?>
                                            <button type="button" class="action-btn"
                                                    title="Reschedule appointment"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rescheduleModal"
                                                    data-reference="<?= e($ref) ?>"
                                                    data-date="<?= e($date) ?>"
                                                    data-time="<?= e($time) ?>">
                                                <i class="bi bi-calendar2-event"></i>
                                            </button>
                                        <?php endif; ?>

                                        <div class="dropdown">
                                            <button class="action-btn" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false" title="More actions">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <?php if (in_array($status, ['CONFIRMED', 'RESCHEDULED'], true)): ?>
                                                    <li>
                                                        <form method="POST" action="appointments.php">
                                                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_appointments_csrf']) ?>">
                                                            <input type="hidden" name="reference" value="<?= e($ref) ?>">
                                                            <input type="hidden" name="action" value="complete">
                                                            <button type="submit" class="dropdown-item small"
                                                                    onclick="return confirm('Mark this appointment as completed?');">
                                                                <i class="bi bi-check-circle me-2 text-primary"></i>Mark Completed
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if (in_array($status, ['PENDING', 'PENDING CONFIRMATION', 'RESCHEDULED'], true)): ?>
                                                    <li>
                                                        <form method="POST" action="appointments.php">
                                                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_appointments_csrf']) ?>">
                                                            <input type="hidden" name="reference" value="<?= e($ref) ?>">
                                                            <input type="hidden" name="action" value="reject">
                                                            <button type="submit" class="dropdown-item small text-danger"
                                                                    onclick="return confirm('Reject this appointment request?');">
                                                                <i class="bi bi-x-circle me-2"></i>Reject Request
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if (!in_array($status, ['COMPLETED', 'CANCELLED'], true)): ?>
                                                    <li>
                                                        <form method="POST" action="appointments.php">
                                                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_appointments_csrf']) ?>">
                                                            <input type="hidden" name="reference" value="<?= e($ref) ?>">
                                                            <input type="hidden" name="action" value="cancel">
                                                            <button type="submit" class="dropdown-item small text-danger"
                                                                    onclick="return confirm('Cancel this appointment?');">
                                                                <i class="bi bi-calendar-x me-2"></i>Cancel Appointment
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h3>No appointments found</h3>
                    <p class="mb-3">Try another search term or change the filters.</p>
                    <a href="appointments.php" class="btn btn-outline-primary btn-sm">Clear filters</a>
                </div>
            <?php endif; ?>
        </section>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="metric-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="metric-icon flex-shrink-0" style="background:#e8f0ff;color:#2563eb;">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:#10233f;">Completed appointments</div>
                            <div class="text-secondary small mt-1">
                                <?= $completedCount ?> appointment(s) marked as completed.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="metric-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="metric-icon flex-shrink-0" style="background:#ffeded;color:#c24141;">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:#10233f;">Rejected or cancelled</div>
                            <div class="text-secondary small mt-1">
                                <?= $cancelledCount ?> appointment(s) rejected or cancelled.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="page-footer">
        <span>© <?= date('Y') ?> ProServe — CRM &amp; Service Management System</span>
        <span>Portfolio demonstration by <strong>Codvanta</strong></span>
    </footer>
</div>

<!-- Appointment Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header px-4 py-3">
                <div>
                    <h2 class="modal-title" id="detailsModalLabel">Appointment Details</h2>
                    <div class="small text-secondary mt-1" id="detailReference">Appointment information</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">CUSTOMER NAME</div>
                            <div class="detail-value" id="detailName">—</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">COMPANY</div>
                            <div class="detail-value" id="detailCompany">—</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">EMAIL ADDRESS</div>
                            <div class="detail-value" id="detailEmail">—</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">PHONE NUMBER</div>
                            <div class="detail-value" id="detailPhone">—</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">SERVICE</div>
                            <div class="detail-value" id="detailService">—</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">MEETING TYPE</div>
                            <div class="detail-value" id="detailType">—</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">APPOINTMENT DATE</div>
                            <div class="detail-value" id="detailDate">—</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">APPOINTMENT TIME</div>
                            <div class="detail-value" id="detailTime">—</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">LOCATION</div>
                            <div class="detail-value" id="detailLocation">—</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-block">
                            <div class="detail-label">STATUS</div>
                            <div class="detail-value" id="detailStatus">—</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-block">
                            <div class="detail-label">CUSTOMER NOTES</div>
                            <div class="detail-value" id="detailNotes" style="white-space:pre-wrap;">No additional notes.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-4">
                <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="appointments.php">
                <div class="modal-header px-4 py-3">
                    <div>
                        <h2 class="modal-title" id="rescheduleModalLabel">Reschedule Appointment</h2>
                        <div class="small text-secondary mt-1">Choose a new date and time.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_appointments_csrf']) ?>">
                    <input type="hidden" name="action" value="reschedule">
                    <input type="hidden" name="reference" id="rescheduleReference">

                    <div class="mb-3">
                        <label for="rescheduleDate" class="form-label fw-semibold small">New appointment date</label>
                        <input type="date" class="form-control" name="appointment_date" id="rescheduleDate"
                               min="<?= e(date('Y-m-d')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="rescheduleTime" class="form-label fw-semibold small">New appointment time</label>
                        <input type="time" class="form-control" name="appointment_time" id="rescheduleTime" required>
                    </div>

                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        This demonstration updates the appointment in the current PHP session only.
                    </div>
                </div>
                <div class="modal-footer px-4">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-calendar-check me-1"></i> Save New Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Workflow Guide Modal -->
<div class="modal fade" id="appointmentInfoModal" tabindex="-1" aria-labelledby="appointmentInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header px-4 py-3">
                <h2 class="modal-title" id="appointmentInfoModalLabel">Appointment Workflow</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex gap-3 mb-4">
                    <div class="metric-icon flex-shrink-0" style="background:#fff4dc;color:#b7791f;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="fw-bold">1. Pending Confirmation</div>
                        <div class="text-secondary small mt-1">Review the customer request and confirm or reject it.</div>
                    </div>
                </div>
                <div class="d-flex gap-3 mb-4">
                    <div class="metric-icon flex-shrink-0" style="background:#e1f8eb;color:#238453;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="fw-bold">2. Confirmed or Rescheduled</div>
                        <div class="text-secondary small mt-1">Coordinate the meeting time or select a new schedule.</div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="metric-icon flex-shrink-0" style="background:#e8f0ff;color:#2563eb;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="fw-bold">3. Completed</div>
                        <div class="text-secondary small mt-1">Mark the appointment complete after the meeting.</div>
                    </div>
                </div>
                <hr class="my-4">
                <p class="small text-secondary mb-0">
                    Status changes are stored in the current PHP session. This is a portfolio demonstration, not a live booking or notification service.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailsModal = document.getElementById('detailsModal');

    if (detailsModal) {
        detailsModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            let appointment = {};

            try {
                appointment = JSON.parse(button.getAttribute('data-appointment') || '{}');
            } catch (error) {
                appointment = {};
            }

            const setText = function (id, value, fallback = 'Not specified') {
                const element = document.getElementById(id);

                if (element) {
                    element.textContent = value === undefined || value === null || value === ''
                        ? fallback
                        : String(value);
                }
            };

            setText('detailReference', appointment.reference, 'Appointment');
            setText('detailName', appointment.full_name || appointment.name);
            setText('detailCompany', appointment.company, 'Individual customer');
            setText('detailEmail', appointment.email);
            setText('detailPhone', appointment.phone);
            setText('detailService', appointment.service || appointment.service_name);
            setText('detailType', appointment.appointment_type, 'Online Meeting');
            setText('detailDate', appointment.appointment_date);
            setText('detailTime', appointment.time_label || appointment.appointment_time);
            setText('detailLocation', appointment.location, 'Online');
            setText('detailStatus', appointment.status);
            setText('detailNotes', appointment.notes, 'No additional notes.');
        });
    }

    const rescheduleModal = document.getElementById('rescheduleModal');

    if (rescheduleModal) {
        rescheduleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            document.getElementById('rescheduleReference').value =
                button.getAttribute('data-reference') || '';

            document.getElementById('rescheduleDate').value =
                button.getAttribute('data-date') || '';

            document.getElementById('rescheduleTime').value =
                button.getAttribute('data-time') || '';
        });
    }
});
</script>
</body>
</html>
