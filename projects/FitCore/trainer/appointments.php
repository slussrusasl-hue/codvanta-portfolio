
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore Trainer Portal - Appointments
|--------------------------------------------------------------------------
| Demonstration features:
| - View appointments
| - Search appointments
| - Filter by status and date
| - View appointment details
| - Accept or decline pending requests
|
| Data is stored in the PHP session, not in a database.
*/

date_default_timezone_set('Asia/Colombo');

$trainerName = "Alex Morgan";
$trainerSpecialty = "Strength & Conditioning";

/* Escape output safely */
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/* Sample appointments are initialized once per browser session. */
if (!isset($_SESSION['fitcore_trainer_appointments'])) {

    $_SESSION['fitcore_trainer_appointments'] = [
        [
            'id' => 'FC-TR-1001',
            'member' => 'James Wilson',
            'email' => 'james.wilson@example.com',
            'phone' => '+94 71 234 5678',
            'initials' => 'JW',
            'class' => 'Strength Training',
            'type' => 'Personal Training',
            'date' => date('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'duration' => 60,
            'status' => 'Confirmed',
            'payment_status' => 'Paid',
            'goal' => 'Build muscle and improve strength',
            'note' => 'Focus on upper-body strength and correct lifting form.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'id' => 'FC-TR-1002',
            'member' => 'Emma Johnson',
            'email' => 'emma.johnson@example.com',
            'phone' => '+94 77 345 6789',
            'initials' => 'EJ',
            'class' => 'Personal Training',
            'type' => 'One-to-one Session',
            'date' => date('Y-m-d'),
            'start_time' => '11:00',
            'end_time' => '12:00',
            'duration' => 60,
            'status' => 'Confirmed',
            'payment_status' => 'Paid',
            'goal' => 'Improve fitness and lose weight',
            'note' => 'Begin with a warm-up and moderate cardio.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'id' => 'FC-TR-1003',
            'member' => 'Noah Williams',
            'email' => 'noah.williams@example.com',
            'phone' => '+94 76 456 7890',
            'initials' => 'NW',
            'class' => 'Strength Training',
            'type' => 'Training Session',
            'date' => date('Y-m-d', strtotime('+1 day')),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'duration' => 60,
            'status' => 'Pending',
            'payment_status' => 'Pending',
            'goal' => 'Increase overall strength',
            'note' => 'Member requested an introductory strength session.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
        ],
        [
            'id' => 'FC-TR-1004',
            'member' => 'Olivia Brown',
            'email' => 'olivia.brown@example.com',
            'phone' => '+94 70 567 8901',
            'initials' => 'OB',
            'class' => 'Functional Fitness',
            'type' => 'Training Session',
            'date' => date('Y-m-d', strtotime('+1 day')),
            'start_time' => '16:00',
            'end_time' => '17:00',
            'duration' => 60,
            'status' => 'Confirmed',
            'payment_status' => 'Paid',
            'goal' => 'Improve mobility and stamina',
            'note' => 'Include balance, mobility, and bodyweight exercises.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
        ],
        [
            'id' => 'FC-TR-1005',
            'member' => 'Sophia Davis',
            'email' => 'sophia.davis@example.com',
            'phone' => '+94 72 678 9012',
            'initials' => 'SD',
            'class' => 'Muscle Building',
            'type' => 'Personal Training',
            'date' => date('Y-m-d', strtotime('+2 days')),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'duration' => 60,
            'status' => 'Pending',
            'payment_status' => 'Pending',
            'goal' => 'Build lean muscle',
            'note' => 'Member would like to discuss a beginner workout plan.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))
        ],
        [
            'id' => 'FC-TR-1006',
            'member' => 'Daniel Miller',
            'email' => 'daniel.miller@example.com',
            'phone' => '+94 75 789 0123',
            'initials' => 'DM',
            'class' => 'Strength Training',
            'type' => 'Personal Training',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'start_time' => '08:00',
            'end_time' => '09:00',
            'duration' => 60,
            'status' => 'Completed',
            'payment_status' => 'Paid',
            'goal' => 'Improve strength and consistency',
            'note' => 'Completed the planned full-body workout.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
        ],
        [
            'id' => 'FC-TR-1007',
            'member' => 'Ava Taylor',
            'email' => 'ava.taylor@example.com',
            'phone' => '+94 78 890 1234',
            'initials' => 'AT',
            'class' => 'Personal Training',
            'type' => 'One-to-one Session',
            'date' => date('Y-m-d', strtotime('+3 days')),
            'start_time' => '15:00',
            'end_time' => '16:00',
            'duration' => 60,
            'status' => 'Cancelled',
            'payment_status' => 'Refund Pending',
            'goal' => 'Improve general fitness',
            'note' => 'Member requested cancellation.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'id' => 'FC-TR-1008',
            'member' => 'Liam Anderson',
            'email' => 'liam.anderson@example.com',
            'phone' => '+94 74 901 2345',
            'initials' => 'LA',
            'class' => 'Functional Fitness',
            'type' => 'Training Session',
            'date' => date('Y-m-d', strtotime('+4 days')),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'duration' => 60,
            'status' => 'Confirmed',
            'payment_status' => 'Paid',
            'goal' => 'Increase stamina',
            'note' => 'Focus on conditioning and core stability.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ]
    ];
}

/* Create a CSRF token for appointment actions. */
if (empty($_SESSION['fitcore_trainer_appointments_csrf'])) {
    $_SESSION['fitcore_trainer_appointments_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['fitcore_trainer_appointments_csrf'];

/* Flash messages */
$flashMessage = $_SESSION['fitcore_trainer_appointments_flash'] ?? null;
unset($_SESSION['fitcore_trainer_appointments_flash']);

/* Process accept and decline actions. */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!is_string($submittedToken) ||
        !hash_equals($csrfToken, $submittedToken)) {

        $_SESSION['fitcore_trainer_appointments_flash'] = [
            'type' => 'danger',
            'message' => 'Your request could not be verified. Please try again.'
        ];

        header('Location: appointments.php');
        exit;
    }

    $appointmentId = $_POST['appointment_id'] ?? '';
    $action = $_POST['action'] ?? '';

    $allowedActions = ['accept', 'decline'];

    if (!in_array($action, $allowedActions, true)) {

        $_SESSION['fitcore_trainer_appointments_flash'] = [
            'type' => 'danger',
            'message' => 'Invalid appointment action.'
        ];

        header('Location: appointments.php');
        exit;
    }

    $appointmentFound = false;

    foreach ($_SESSION['fitcore_trainer_appointments'] as $index => $appointment) {

        if ($appointment['id'] !== $appointmentId) {
            continue;
        }

        $appointmentFound = true;

        if ($appointment['status'] !== 'Pending') {

            $_SESSION['fitcore_trainer_appointments_flash'] = [
                'type' => 'warning',
                'message' => 'Only pending appointments can be accepted or declined.'
            ];

            break;
        }

        if ($action === 'accept') {
            $_SESSION['fitcore_trainer_appointments'][$index]['status'] = 'Confirmed';

            $_SESSION['fitcore_trainer_appointments_flash'] = [
                'type' => 'success',
                'message' => 'Appointment ' . $appointmentId . ' has been accepted.'
            ];
        } else {
            $_SESSION['fitcore_trainer_appointments'][$index]['status'] = 'Declined';

            $_SESSION['fitcore_trainer_appointments_flash'] = [
                'type' => 'info',
                'message' => 'Appointment ' . $appointmentId . ' has been declined.'
            ];
        }

        break;
    }

    if (!$appointmentFound) {
        $_SESSION['fitcore_trainer_appointments_flash'] = [
            'type' => 'danger',
            'message' => 'Appointment not found.'
        ];
    }

    header('Location: appointments.php');
    exit;
}

/* Retrieve current appointments. */
$appointments = $_SESSION['fitcore_trainer_appointments'];

/* Search and filter inputs */
$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? 'All';
$dateFilter = $_GET['date'] ?? 'All';

$validStatuses = [
    'All',
    'Pending',
    'Confirmed',
    'Completed',
    'Cancelled',
    'Declined'
];

$validDateFilters = [
    'All',
    'Today',
    'Upcoming',
    'Past'
];

if (!in_array($statusFilter, $validStatuses, true)) {
    $statusFilter = 'All';
}

if (!in_array($dateFilter, $validDateFilters, true)) {
    $dateFilter = 'All';
}

/* Filter the appointments. */
$filteredAppointments = array_filter(
    $appointments,
    function ($appointment) use ($search, $statusFilter, $dateFilter) {

        $searchText = strtolower(
            $appointment['id'] . ' ' .
            $appointment['member'] . ' ' .
            $appointment['email'] . ' ' .
            $appointment['class'] . ' ' .
            $appointment['type']
        );

        if ($search !== '' &&
            strpos($searchText, strtolower($search)) === false) {
            return false;
        }

        if ($statusFilter !== 'All' &&
            $appointment['status'] !== $statusFilter) {
            return false;
        }

        $today = date('Y-m-d');

        if ($dateFilter === 'Today' &&
            $appointment['date'] !== $today) {
            return false;
        }

        if ($dateFilter === 'Upcoming' &&
            ($appointment['date'] < $today ||
             in_array($appointment['status'], ['Cancelled', 'Declined'], true))) {
            return false;
        }

        if ($dateFilter === 'Past' &&
            $appointment['date'] >= $today) {
            return false;
        }

        return true;
    }
);

$filteredAppointments = array_values($filteredAppointments);

/* Sort appointments by date and time. */
usort($filteredAppointments, function ($a, $b) {
    $aDateTime = $a['date'] . ' ' . $a['start_time'];
    $bDateTime = $b['date'] . ' ' . $b['start_time'];

    return strcmp($aDateTime, $bDateTime);
});

/* Statistics */
$totalAppointments = count($appointments);
$pendingCount = 0;
$confirmedCount = 0;
$completedCount = 0;
$todayCount = 0;

foreach ($appointments as $appointment) {

    if ($appointment['status'] === 'Pending') {
        $pendingCount++;
    }

    if ($appointment['status'] === 'Confirmed') {
        $confirmedCount++;
    }

    if ($appointment['status'] === 'Completed') {
        $completedCount++;
    }

    if ($appointment['date'] === date('Y-m-d') &&
        $appointment['status'] !== 'Cancelled' &&
        $appointment['status'] !== 'Declined') {
        $todayCount++;
    }
}

/* Formatting helpers */
function formatDate($date)
{
    return date('M d, Y', strtotime($date));
}

function formatTime($time)
{
    return date('h:i A', strtotime($time));
}

function statusClass($status)
{
    $classes = [
        'Pending' => 'status-pending',
        'Confirmed' => 'status-confirmed',
        'Completed' => 'status-completed',
        'Cancelled' => 'status-cancelled',
        'Declined' => 'status-declined'
    ];

    return $classes[$status] ?? 'status-pending';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Appointments | FitCore Trainer Portal</title>

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
            --fc-red: #ff6969;
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
            transition: transform .25s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 10px 28px;
            border-bottom: 1px solid var(--fc-border);
            color: var(--fc-text);
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
            letter-spacing: -.5px;
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
            transition: .2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 107, 44, .13);
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
            margin-bottom: 27px;
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

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 107, 44, .17);
            border: 1px solid rgba(255, 107, 44, .45);
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

        .breadcrumb-custom {
            display: flex;
            gap: 9px;
            align-items: center;
            color: #777;
            font-size: 12px;
            margin-bottom: 22px;
        }

        .breadcrumb-custom a {
            color: var(--fc-orange);
        }

        .breadcrumb-custom span {
            color: #555;
        }

        .stat-card {
            height: 100%;
            padding: 19px;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 17px;
        }

        .stat-icon {
            width: 41px;
            height: 41px;
            border-radius: 11px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 19px;
        }

        .icon-orange {
            color: var(--fc-orange);
            background: rgba(255, 107, 44, .13);
        }

        .icon-yellow {
            color: var(--fc-yellow);
            background: rgba(255, 200, 87, .12);
        }

        .icon-green {
            color: var(--fc-green);
            background: rgba(56, 201, 135, .12);
        }

        .icon-blue {
            color: var(--fc-blue);
            background: rgba(99, 168, 255, .12);
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--fc-muted);
        }

        .stat-caption {
            font-size: 10px;
            color: #777;
            margin-top: 8px;
        }

        .panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 22px;
        }

        .panel-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .panel-heading h2 {
            font-size: 17px;
            font-weight: bold;
            margin: 0 0 5px;
        }

        .panel-heading p {
            color: var(--fc-muted);
            font-size: 12px;
            margin: 0;
        }

        .filter-form {
            display: grid;
            grid-template-columns: minmax(190px, 1.6fr) repeat(2, minmax(135px, 1fr)) auto auto;
            gap: 10px;
            align-items: end;
        }

        .form-label {
            color: #c9c9c9;
            font-size: 11px;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            background-color: #111;
            color: #eee;
            border: 1px solid #383838;
            border-radius: 8px;
            padding: 10px 11px;
            font-size: 12px;
            min-height: 41px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #151515;
            color: #fff;
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 .2rem rgba(255, 107, 44, .12);
        }

        .form-select option {
            background: #181818;
            color: #fff;
        }

        .btn-orange {
            background: var(--fc-orange);
            border: 1px solid var(--fc-orange);
            color: #111;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .btn-orange:hover {
            background: var(--fc-orange-hover);
            border-color: var(--fc-orange-hover);
            color: #111;
        }

        .btn-outline-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid #444;
            color: #ddd;
            border-radius: 7px;
            padding: 8px 11px;
            font-size: 11px;
            white-space: nowrap;
        }

        .btn-outline-custom:hover {
            background: #292929;
            border-color: #666;
            color: #fff;
        }

        .btn-accept {
            background: rgba(56, 201, 135, .1);
            border: 1px solid rgba(56, 201, 135, .35);
            color: var(--fc-green);
            border-radius: 6px;
            padding: 6px 9px;
            font-size: 10px;
        }

        .btn-accept:hover {
            background: rgba(56, 201, 135, .2);
            color: #69e6ac;
        }

        .btn-decline {
            background: rgba(255, 105, 105, .08);
            border: 1px solid rgba(255, 105, 105, .3);
            color: var(--fc-red);
            border-radius: 6px;
            padding: 6px 9px;
            font-size: 10px;
        }

        .btn-decline:hover {
            background: rgba(255, 105, 105, .16);
            color: #ff9696;
        }

        .table-responsive {
            border-radius: 9px;
        }

        .appointments-table {
            width: 100%;
            min-width: 970px;
            margin: 0;
            vertical-align: middle;
            color: #eee;
        }

        .appointments-table thead th {
            background: #202020;
            color: #999;
            border-bottom: 1px solid #383838;
            border-top: none;
            padding: 14px 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .7px;
            white-space: nowrap;
        }

        .appointments-table tbody td {
            background: transparent;
            color: #ddd;
            border-bottom: 1px solid #2c2c2c;
            padding: 15px 12px;
            font-size: 11px;
        }

        .appointments-table tbody tr:last-child td {
            border-bottom: none;
        }

        .appointments-table tbody tr:hover td {
            background: rgba(255, 255, 255, .018);
        }

        .member-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 175px;
        }

        .member-avatar {
            width: 37px;
            height: 37px;
            flex-shrink: 0;
            border-radius: 10px;
            background: #292929;
            border: 1px solid #3b3b3b;
            color: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 11px;
            font-weight: bold;
        }

        .member-name {
            color: #f3f3f3;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .member-email {
            color: #888;
            font-size: 10px;
        }

        .class-name {
            color: #eee;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .muted-small {
            color: #888;
            font-size: 10px;
            line-height: 1.6;
        }

        .status {
            display: inline-block;
            padding: 6px 8px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-pending {
            color: var(--fc-yellow);
            background: rgba(255, 200, 87, .12);
        }

        .status-confirmed {
            color: var(--fc-green);
            background: rgba(56, 201, 135, .12);
        }

        .status-completed {
            color: var(--fc-blue);
            background: rgba(99, 168, 255, .12);
        }

        .status-cancelled,
        .status-declined {
            color: var(--fc-red);
            background: rgba(255, 105, 105, .1);
        }

        .payment-paid {
            color: var(--fc-green);
        }

        .payment-pending {
            color: var(--fc-yellow);
        }

        .payment-refund {
            color: var(--fc-red);
        }

        .action-cell {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            min-width: 135px;
        }

        .action-form {
            display: inline;
            margin: 0;
        }

        .result-count {
            color: var(--fc-muted);
            font-size: 11px;
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px;
        }

        .empty-icon {
            width: 60px;
            height: 60px;
            border-radius: 17px;
            background: #252525;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--fc-orange);
            font-size: 25px;
            margin: 0 auto 17px;
        }

        .empty-state h3 {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .alert {
            border-radius: 9px;
            font-size: 12px;
        }

        .alert-success {
            background: rgba(56, 201, 135, .1);
            border: 1px solid rgba(56, 201, 135, .25);
            color: #76e4b0;
        }

        .alert-danger {
            background: rgba(255, 105, 105, .1);
            border: 1px solid rgba(255, 105, 105, .25);
            color: #ff9c9c;
        }

        .alert-warning {
            background: rgba(255, 200, 87, .1);
            border: 1px solid rgba(255, 200, 87, .25);
            color: #ffda8a;
        }

        .alert-info {
            background: rgba(99, 168, 255, .1);
            border: 1px solid rgba(99, 168, 255, .25);
            color: #9ac7ff;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .detail-item {
            background: #202020;
            border: 1px solid #303030;
            border-radius: 8px;
            padding: 12px;
        }

        .detail-label {
            color: #888;
            font-size: 10px;
            margin-bottom: 6px;
        }

        .detail-value {
            color: #f1f1f1;
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .modal-content {
            background: #191919;
            color: #eee;
            border: 1px solid #383838;
            border-radius: 13px;
        }

        .modal-header,
        .modal-footer {
            border-color: #333;
        }

        .modal-title {
            font-size: 17px;
            font-weight: bold;
        }

        .btn-close {
            filter: invert(1);
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

        @media (max-width: 1350px) {
            .filter-form {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-search {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 1199px) {
            .sidebar {
                width: 230px;
            }

            .main-content {
                margin-left: 230px;
                padding: 24px;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
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
                background: rgba(0, 0, 0, .65);
            }
        }

        @media (max-width: 575px) {
            .main-content {
                padding: 17px 13px;
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

            .filter-form {
                grid-template-columns: 1fr;
            }

            .filter-search {
                grid-column: auto;
            }

            .panel {
                padding: 15px;
            }

            .panel-heading {
                flex-direction: column;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .footer {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

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

    <a href="dashboard.php" class="sidebar-link">
        <span class="nav-icon">▦</span>
        Dashboard
    </a>

    <a href="appointments.php" class="sidebar-link active">
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
        <p>Review and manage sample appointment records.</p>
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
                <h1 class="page-title">Appointments</h1>
                <p class="page-subtitle">
                    Review bookings and manage your training requests.
                </p>
            </div>
        </div>

        <div class="profile">
            <div class="avatar">AM</div>
            <div>
                <div class="profile-name"><?= e($trainerName) ?></div>
                <div class="profile-role"><?= e($trainerSpecialty) ?></div>
            </div>
        </div>

    </header>

    <div class="breadcrumb-custom">
        <a href="dashboard.php">Dashboard</a>
        <span>/</span>
        <div>Appointments</div>
    </div>

    <!-- Flash message -->
    <?php if ($flashMessage): ?>
        <div class="alert alert-<?= e($flashMessage['type']) ?> alert-dismissible fade show mb-4"
             role="alert">
            <?= e($flashMessage['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <section class="mb-4">

        <div class="row g-3">

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-orange">▣</div>
                    </div>
                    <div class="stat-number"><?= number_format($totalAppointments) ?></div>
                    <div class="stat-label">Total Appointments</div>
                    <div class="stat-caption">All sample booking records</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-yellow">◷</div>
                    </div>
                    <div class="stat-number"><?= number_format($pendingCount) ?></div>
                    <div class="stat-label">Pending Requests</div>
                    <div class="stat-caption">Awaiting trainer review</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-green">✓</div>
                    </div>
                    <div class="stat-number"><?= number_format($confirmedCount) ?></div>
                    <div class="stat-label">Confirmed</div>
                    <div class="stat-caption">Accepted appointments</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-blue">◴</div>
                    </div>
                    <div class="stat-number"><?= number_format($todayCount) ?></div>
                    <div class="stat-label">Today's Sessions</div>
                    <div class="stat-caption">Not cancelled or declined</div>
                </div>
            </div>

        </div>

    </section>

    <!-- Filters -->
    <section class="panel mb-4">

        <div class="panel-heading">
            <div>
                <h2>Find Appointments</h2>
                <p>Search by member, appointment reference, or class.</p>
            </div>
        </div>

        <form action="appointments.php" method="GET" class="filter-form">

            <div class="filter-search">
                <label for="search" class="form-label">Search</label>
                <input
                    type="search"
                    id="search"
                    name="search"
                    class="form-control"
                    placeholder="Name, class, email or reference..."
                    value="<?= e($search) ?>"
                >
            </div>

            <div>
                <label for="status" class="form-label">Appointment Status</label>
                <select name="status" id="status" class="form-select">
                    <?php foreach ($validStatuses as $status): ?>
                        <option value="<?= e($status) ?>"
                            <?= $statusFilter === $status ? 'selected' : '' ?>>
                            <?= e($status === 'All' ? 'All statuses' : $status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="date" class="form-label">Date Range</label>
                <select name="date" id="date" class="form-select">
                    <?php foreach ($validDateFilters as $dateOption): ?>
                        <option value="<?= e($dateOption) ?>"
                            <?= $dateFilter === $dateOption ? 'selected' : '' ?>>
                            <?= e($dateOption === 'All' ? 'All dates' : $dateOption) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-orange">
                Search
            </button>

            <a href="appointments.php" class="btn-outline-custom">
                Reset
            </a>

        </form>

    </section>

    <!-- Appointment table -->
    <section class="panel">

        <div class="panel-heading">

            <div>
                <h2>Appointment Records</h2>
                <p>Review session details and manage pending requests.</p>
            </div>

            <div class="result-count">
                Showing <?= count($filteredAppointments) ?>
                of <?= $totalAppointments ?> appointments
            </div>

        </div>

        <?php if (count($filteredAppointments) > 0): ?>

            <div class="table-responsive">

                <table class="table appointments-table">

                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Class / Session</th>
                            <th>Date & Time</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($filteredAppointments as $appointment): ?>

                            <tr>

                                <td>
                                    <div class="member-cell">
                                        <div class="member-avatar">
                                            <?= e($appointment['initials']) ?>
                                        </div>

                                        <div>
                                            <div class="member-name">
                                                <?= e($appointment['member']) ?>
                                            </div>
                                            <div class="member-email">
                                                <?= e($appointment['id']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="class-name">
                                        <?= e($appointment['class']) ?>
                                    </div>
                                    <div class="muted-small">
                                        <?= e($appointment['type']) ?>
                                    </div>
                                </td>

                                <td>
                                    <div style="font-weight:bold;margin-bottom:5px;">
                                        <?= e(formatDate($appointment['date'])) ?>
                                    </div>
                                    <div class="muted-small">
                                        <?= e(formatTime($appointment['start_time'])) ?>
                                        –
                                        <?= e(formatTime($appointment['end_time'])) ?>
                                    </div>
                                </td>

                                <td>
                                    <?php
                                    $paymentClass = 'payment-pending';

                                    if ($appointment['payment_status'] === 'Paid') {
                                        $paymentClass = 'payment-paid';
                                    } elseif ($appointment['payment_status'] === 'Refund Pending') {
                                        $paymentClass = 'payment-refund';
                                    }
                                    ?>
                                    <span class="<?= e($paymentClass) ?>">
                                        <?= e($appointment['payment_status']) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="status <?= e(statusClass($appointment['status'])) ?>">
                                        <?= e($appointment['status']) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="action-cell">

                                        <button
                                            type="button"
                                            class="btn-outline-custom"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailsModal"
                                            data-id="<?= e($appointment['id']) ?>"
                                            data-member="<?= e($appointment['member']) ?>"
                                            data-email="<?= e($appointment['email']) ?>"
                                            data-phone="<?= e($appointment['phone']) ?>"
                                            data-class="<?= e($appointment['class']) ?>"
                                            data-type="<?= e($appointment['type']) ?>"
                                            data-date="<?= e(formatDate($appointment['date'])) ?>"
                                            data-start="<?= e(formatTime($appointment['start_time'])) ?>"
                                            data-end="<?= e(formatTime($appointment['end_time'])) ?>"
                                            data-duration="<?= e($appointment['duration']) ?>"
                                            data-status="<?= e($appointment['status']) ?>"
                                            data-payment="<?= e($appointment['payment_status']) ?>"
                                            data-goal="<?= e($appointment['goal']) ?>"
                                            data-note="<?= e($appointment['note']) ?>"
                                        >
                                            Details
                                        </button>

                                        <?php if ($appointment['status'] === 'Pending'): ?>

                                            <form method="POST" action="appointments.php"
                                                  class="action-form"
                                                  onsubmit="return confirm('Accept this appointment request?');">

                                                <input type="hidden" name="csrf_token"
                                                       value="<?= e($csrfToken) ?>">

                                                <input type="hidden" name="appointment_id"
                                                       value="<?= e($appointment['id']) ?>">

                                                <input type="hidden" name="action" value="accept">

                                                <button type="submit" class="btn-accept">
                                                    Accept
                                                </button>

                                            </form>

                                            <form method="POST" action="appointments.php"
                                                  class="action-form"
                                                  onsubmit="return confirm('Decline this appointment request?');">

                                                <input type="hidden" name="csrf_token"
                                                       value="<?= e($csrfToken) ?>">

                                                <input type="hidden" name="appointment_id"
                                                       value="<?= e($appointment['id']) ?>">

                                                <input type="hidden" name="action" value="decline">

                                                <button type="submit" class="btn-decline">
                                                    Decline
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

        <?php else: ?>

            <div class="empty-state">
                <div class="empty-icon">⌕</div>
                <h3>No appointments found</h3>
                <p>Try another search term or change your filters.</p>
                <a href="appointments.php" class="btn btn-orange mt-2">
                    Clear Filters
                </a>
            </div>

        <?php endif; ?>

    </section>

    <!-- Footer -->
    <footer class="footer">
        <div>
            © <?= date('Y') ?> FitCore Trainer Portal.
            Demonstration website.
        </div>

        <div>
            Developed by <a href="../index.php">Codvanta</a>
            · Turning Vision into Digital Reality.
        </div>
    </footer>

</main>

<!-- Appointment details modal -->
<div class="modal fade" id="detailsModal" tabindex="-1"
     aria-labelledby="detailsModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="detailsModalLabel">
                        Appointment Details
                    </h5>
                    <div class="muted-small mt-1" id="detailReference"></div>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="avatar" id="detailAvatar">MB</div>
                    <div>
                        <div style="font-weight:bold;font-size:16px;" id="detailMember"></div>
                        <div class="muted-small" id="detailClass"></div>
                    </div>
                    <div class="ms-auto">
                        <span class="status status-pending" id="detailStatus"></span>
                    </div>
                </div>

                <div class="detail-grid">

                    <div class="detail-item">
                        <div class="detail-label">Member Email</div>
                        <div class="detail-value" id="detailEmail"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Contact Number</div>
                        <div class="detail-value" id="detailPhone"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Session Date</div>
                        <div class="detail-value" id="detailDate"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Session Time</div>
                        <div class="detail-value" id="detailTime"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value" id="detailDuration"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Payment Status</div>
                        <div class="detail-value" id="detailPayment"></div>
                    </div>

                    <div class="detail-item" style="grid-column:1/-1;">
                        <div class="detail-label">Member Fitness Goal</div>
                        <div class="detail-value" id="detailGoal"></div>
                    </div>

                    <div class="detail-item" style="grid-column:1/-1;">
                        <div class="detail-label">Member / Session Note</div>
                        <div class="detail-value" id="detailNote"></div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-outline-custom"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("open");
        document.getElementById("sidebarOverlay").classList.toggle("show");
    }

    document.querySelectorAll(".sidebar-link").forEach(function(link) {
        link.addEventListener("click", function() {
            if (window.innerWidth <= 991) {
                document.getElementById("sidebar").classList.remove("open");
                document.getElementById("sidebarOverlay").classList.remove("show");
            }
        });
    });

    /*
     * Populate the appointment detail modal using the selected row's
     * data attributes. textContent is used to display values safely.
     */
    const detailsModal = document.getElementById("detailsModal");

    detailsModal.addEventListener("show.bs.modal", function(event) {
        const button = event.relatedTarget;

        const fields = {
            detailReference: "id",
            detailMember: "member",
            detailEmail: "email",
            detailPhone: "phone",
            detailClass: "class",
            detailDate: "date",
            detailDuration: "duration",
            detailPayment: "payment",
            detailGoal: "goal",
            detailNote: "note"
        };

        Object.keys(fields).forEach(function(elementId) {
            const element = document.getElementById(elementId);
            element.textContent = button.getAttribute("data-" + fields[elementId]) || "Not provided";
        });

        const start = button.getAttribute("data-start") || "";
        const end = button.getAttribute("data-end") || "";

        document.getElementById("detailTime").textContent = start + " – " + end;

        const member = button.getAttribute("data-member") || "Member";
        const initials = member.split(/\s+/)
            .filter(Boolean)
            .map(function(part) { return part.charAt(0); })
            .slice(0, 2)
            .join("")
            .toUpperCase();

        document.getElementById("detailAvatar").textContent = initials;

        const status = button.getAttribute("data-status") || "Pending";
        const statusElement = document.getElementById("detailStatus");

        statusElement.textContent = status;
        statusElement.className = "status";

        const statusClasses = {
            "Pending": "status-pending",
            "Confirmed": "status-confirmed",
            "Completed": "status-completed",
            "Cancelled": "status-cancelled",
            "Declined": "status-declined"
        };

        statusElement.classList.add(statusClasses[status] || "status-pending");
    });
</script>

</body>
</html>