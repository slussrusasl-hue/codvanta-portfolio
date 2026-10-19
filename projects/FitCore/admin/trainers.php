
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore Admin - Trainer Management
|--------------------------------------------------------------------------
| Demonstration only: trainer data is stored in PHP sessions.
| No database connection is required.
*/

if (!isset($_SESSION['fitcore_admin_trainers'])) {
    $_SESSION['fitcore_admin_trainers'] = [
        [
            'id' => 'FCT-2001',
            'name' => 'Alex Morgan',
            'email' => 'alex.morgan@fitcore.demo',
            'phone' => '+1 555-0101',
            'specialization' => 'Strength & Conditioning',
            'experience' => 7,
            'gender' => 'Male',
            'classes' => 'Strength Training, HIIT',
            'schedule' => 'Monday - Friday',
            'status' => 'Active',
            'joined' => '2025-01-15'
        ],
        [
            'id' => 'FCT-2002',
            'name' => 'Sarah Williams',
            'email' => 'sarah.williams@fitcore.demo',
            'phone' => '+1 555-0102',
            'specialization' => 'Yoga & Flexibility',
            'experience' => 5,
            'gender' => 'Female',
            'classes' => 'Morning Yoga, Flexibility',
            'schedule' => 'Monday, Wednesday, Friday',
            'status' => 'Active',
            'joined' => '2025-02-10'
        ],
        [
            'id' => 'FCT-2003',
            'name' => 'Daniel Carter',
            'email' => 'daniel.carter@fitcore.demo',
            'phone' => '+1 555-0103',
            'specialization' => 'Personal Fitness Training',
            'experience' => 9,
            'gender' => 'Male',
            'classes' => 'Personal Training, Core Workout',
            'schedule' => 'Tuesday - Saturday',
            'status' => 'Active',
            'joined' => '2024-11-05'
        ],
        [
            'id' => 'FCT-2004',
            'name' => 'Emily Johnson',
            'email' => 'emily.johnson@fitcore.demo',
            'phone' => '+1 555-0104',
            'specialization' => 'Cardio & Weight Loss',
            'experience' => 4,
            'gender' => 'Female',
            'classes' => 'Cardio Blast, Fat Burn',
            'schedule' => 'Monday - Friday',
            'status' => 'Active',
            'joined' => '2025-03-20'
        ],
        [
            'id' => 'FCT-2005',
            'name' => 'Michael Brown',
            'email' => 'michael.brown@fitcore.demo',
            'phone' => '+1 555-0105',
            'specialization' => 'CrossFit & HIIT',
            'experience' => 6,
            'gender' => 'Male',
            'classes' => 'CrossFit, HIIT',
            'schedule' => 'Monday - Saturday',
            'status' => 'Inactive',
            'joined' => '2025-04-12'
        ],
        [
            'id' => 'FCT-2006',
            'name' => 'Olivia Davis',
            'email' => 'olivia.davis@fitcore.demo',
            'phone' => '+1 555-0106',
            'specialization' => 'Pilates & Mobility',
            'experience' => 3,
            'gender' => 'Female',
            'classes' => 'Pilates, Mobility Training',
            'schedule' => 'Tuesday, Thursday, Saturday',
            'status' => 'Active',
            'joined' => '2025-06-01'
        ],
        [
            'id' => 'FCT-2007',
            'name' => 'Ryan Wilson',
            'email' => 'ryan.wilson@fitcore.demo',
            'phone' => '+1 555-0107',
            'specialization' => 'Strength & Conditioning',
            'experience' => 8,
            'gender' => 'Male',
            'classes' => 'Strength Training, Power Workout',
            'schedule' => 'Monday - Friday',
            'status' => 'Active',
            'joined' => '2024-09-18'
        ],
        [
            'id' => 'FCT-2008',
            'name' => 'Sophia Martinez',
            'email' => 'sophia.martinez@fitcore.demo',
            'phone' => '+1 555-0108',
            'specialization' => 'Yoga & Flexibility',
            'experience' => 6,
            'gender' => 'Female',
            'classes' => 'Evening Yoga, Stretching',
            'schedule' => 'Monday - Thursday',
            'status' => 'Inactive',
            'joined' => '2025-07-14'
        ]
    ];
}

if (empty($_SESSION['fitcore_trainers_csrf'])) {
    $_SESSION['fitcore_trainers_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['fitcore_trainers_csrf'];
$notice = '';
$noticeType = 'success';

function trainerEscape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function trainerRedirect($message, $type = 'success')
{
    $_SESSION['fitcore_trainers_notice'] = $message;
    $_SESSION['fitcore_trainers_notice_type'] = $type;

    header('Location: trainers.php');
    exit;
}

/* Display one-time notifications. */
if (isset($_SESSION['fitcore_trainers_notice'])) {
    $notice = $_SESSION['fitcore_trainers_notice'];
    $noticeType = $_SESSION['fitcore_trainers_notice_type'] ?? 'success';

    unset(
        $_SESSION['fitcore_trainers_notice'],
        $_SESSION['fitcore_trainers_notice_type']
    );
}

/* Handle administrator actions. */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($submittedToken) ||
        !hash_equals($csrfToken, $submittedToken)
    ) {
        trainerRedirect('Security verification failed. Please try again.', 'danger');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add_trainer') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $specialization = trim($_POST['specialization'] ?? '');
        $experience = filter_var(
            $_POST['experience'] ?? null,
            FILTER_VALIDATE_INT
        );
        $gender = trim($_POST['gender'] ?? '');
        $classes = trim($_POST['classes'] ?? '');
        $schedule = trim($_POST['schedule'] ?? '');

        $allowedSpecializations = [
            'Strength & Conditioning',
            'Yoga & Flexibility',
            'Personal Fitness Training',
            'Cardio & Weight Loss',
            'CrossFit & HIIT',
            'Pilates & Mobility'
        ];

        $allowedGenders = ['Male', 'Female', 'Other'];

        if (
            $name === '' ||
            strlen($name) > 100 ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($email) > 150 ||
            strlen($phone) > 30 ||
            !in_array($specialization, $allowedSpecializations, true) ||
            $experience === false ||
            $experience === null ||
            $experience < 0 ||
            $experience > 60 ||
            !in_array($gender, $allowedGenders, true) ||
            $classes === '' ||
            strlen($classes) > 200 ||
            $schedule === '' ||
            strlen($schedule) > 100
        ) {
            trainerRedirect(
                'Please enter valid information in all required fields.',
                'danger'
            );
        }

        foreach ($_SESSION['fitcore_admin_trainers'] as $existingTrainer) {
            if (strcasecmp($existingTrainer['email'], $email) === 0) {
                trainerRedirect(
                    'A trainer with this email address already exists.',
                    'danger'
                );
            }
        }

        $nextNumber = 2001;

        foreach ($_SESSION['fitcore_admin_trainers'] as $existingTrainer) {
            if (preg_match('/FCT-(\d+)/', $existingTrainer['id'], $matches)) {
                $nextNumber = max($nextNumber, (int)$matches[1] + 1);
            }
        }

        $_SESSION['fitcore_admin_trainers'][] = [
            'id' => 'FCT-' . $nextNumber,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'specialization' => $specialization,
            'experience' => $experience,
            'gender' => $gender,
            'classes' => $classes,
            'schedule' => $schedule,
            'status' => 'Active',
            'joined' => date('Y-m-d')
        ];

        trainerRedirect('Trainer added successfully in this demo.');
    }

    if ($action === 'toggle_status') {
        $trainerId = trim($_POST['trainer_id'] ?? '');
        $found = false;

        foreach ($_SESSION['fitcore_admin_trainers'] as &$trainer) {
            if ($trainer['id'] === $trainerId) {
                $trainer['status'] =
                    $trainer['status'] === 'Active' ? 'Inactive' : 'Active';

                $newStatus = $trainer['status'];
                $found = true;
                break;
            }
        }
        unset($trainer);

        if ($found) {
            trainerRedirect(
                'Trainer status changed to ' . $newStatus . ' successfully.'
            );
        }

        trainerRedirect('Trainer could not be found.', 'danger');
    }

    trainerRedirect('Unknown action.', 'danger');
}

/* Get trainer records from the session. */
$trainers = $_SESSION['fitcore_admin_trainers'];

$totalTrainers = count($trainers);
$activeTrainers = 0;
$inactiveTrainers = 0;
$specializationCounts = [];

foreach ($trainers as $trainer) {
    if ($trainer['status'] === 'Active') {
        $activeTrainers++;
    } else {
        $inactiveTrainers++;
    }

    $specializationCounts[$trainer['specialization']] =
        ($specializationCounts[$trainer['specialization']] ?? 0) + 1;
}

/* Search and filters. */
$search = trim($_GET['search'] ?? '');
$specializationFilter = trim($_GET['specialization'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

$filteredTrainers = array_filter($trainers, function ($trainer) use (
    $search,
    $specializationFilter,
    $statusFilter
) {
    $matchesSearch = true;

    if ($search !== '') {
        $searchable = implode(' ', [
            $trainer['id'],
            $trainer['name'],
            $trainer['email'],
            $trainer['phone']
        ]);

        $matchesSearch = stripos($searchable, $search) !== false;
    }

    $matchesSpecialization =
        $specializationFilter === '' ||
        $trainer['specialization'] === $specializationFilter;

    $matchesStatus =
        $statusFilter === '' ||
        $trainer['status'] === $statusFilter;

    return $matchesSearch && $matchesSpecialization && $matchesStatus;
});

$specializations = [
    'Strength & Conditioning',
    'Yoga & Flexibility',
    'Personal Fitness Training',
    'Cardio & Weight Loss',
    'CrossFit & HIIT',
    'Pilates & Mobility'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Trainers | FitCore Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        :root {
            --fc-bg: #101114;
            --fc-sidebar: #17181d;
            --fc-card: #1b1c22;
            --fc-border: #2d2e36;
            --fc-orange: #ff6b2c;
            --fc-text: #f5f5f7;
            --fc-muted: #a0a1ab;
            --fc-green: #31c48d;
            --fc-red: #ff6868;
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

        .admin-layout {
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            padding: 25px 17px;
            background: var(--fc-sidebar);
            border-right: 1px solid var(--fc-border);
            overflow-y: auto;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 10px 28px;
            border-bottom: 1px solid var(--fc-border);
            margin-bottom: 25px;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--fc-orange);
            color: #fff;
            border-radius: 12px;
            font-size: 21px;
        }

        .brand-name {
            margin: 0;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand-name span {
            color: var(--fc-orange);
        }

        .brand-subtitle {
            margin: 3px 0 0;
            color: var(--fc-muted);
            font-size: 11px;
        }

        .nav-heading {
            margin: 23px 11px 10px;
            color: #73747e;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 13px;
            margin-bottom: 5px;
            border-radius: 9px;
            color: #b5b6bf;
            font-size: 13px;
            transition: .2s ease;
        }

        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
        }

        .sidebar-link.active {
            font-weight: 700;
        }

        .sidebar-bottom {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid var(--fc-border);
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            min-width: 0;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 30px;
        }

        .page-title {
            margin: 0 0 6px;
            font-size: 28px;
            font-weight: 800;
        }

        .page-subtitle {
            margin: 0;
            color: var(--fc-muted);
            font-size: 13px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .admin-avatar {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #33343c;
            color: var(--fc-orange);
            border-radius: 50%;
            font-weight: 800;
        }

        .admin-profile strong {
            display: block;
            font-size: 13px;
        }

        .admin-profile small {
            color: var(--fc-muted);
            font-size: 11px;
        }

        .btn-orange {
            border: 1px solid var(--fc-orange);
            background: var(--fc-orange);
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 13px;
        }

        .btn-orange:hover {
            background: #ed5a1d;
            border-color: #ed5a1d;
            color: #fff;
        }

        .btn-outline-light {
            border-radius: 8px;
            font-size: 12px;
        }

        .stat-card {
            height: 100%;
            padding: 21px;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .stat-label {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 11px;
            background: rgba(255, 107, 44, .13);
            color: var(--fc-orange);
            font-size: 18px;
        }

        .stat-value {
            font-size: 29px;
            font-weight: 800;
            line-height: 1;
        }

        .stat-note {
            margin-top: 10px;
            color: var(--fc-muted);
            font-size: 11px;
        }

        .section-card {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
            padding: 22px;
        }

        .section-heading {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
        }

        .section-description {
            margin-top: 5px;
            color: var(--fc-muted);
            font-size: 12px;
        }

        .form-label {
            color: #d5d5db;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            min-height: 42px;
            background-color: #14151a;
            border: 1px solid #353640;
            color: var(--fc-text);
            border-radius: 8px;
            font-size: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #14151a;
            color: #fff;
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 .2rem rgba(255, 107, 44, .12);
        }

        .form-control::placeholder {
            color: #73747e;
        }

        .form-select option {
            background: #1b1c22;
            color: #fff;
        }

        .table-responsive {
            border-radius: 10px;
        }

        .table {
            margin-bottom: 0;
            color: var(--fc-text);
            vertical-align: middle;
            --bs-table-bg: transparent;
            --bs-table-color: var(--fc-text);
            --bs-table-border-color: var(--fc-border);
        }

        .table thead th {
            padding: 14px 12px;
            background: #22232a;
            color: #aeb0ba;
            border-bottom: 1px solid var(--fc-border);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 15px 12px;
            border-color: var(--fc-border);
            font-size: 12px;
        }

        .table tbody tr:hover td {
            background: rgba(255, 255, 255, .018);
        }

        .trainer-info {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 185px;
        }

        .trainer-avatar {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: rgba(255, 107, 44, .13);
            color: var(--fc-orange);
            font-weight: 800;
            font-size: 13px;
        }

        .trainer-name {
            display: block;
            font-weight: 800;
            font-size: 12px;
            color: #fff;
        }

        .trainer-id {
            display: block;
            color: var(--fc-muted);
            font-size: 10px;
            margin-top: 4px;
        }

        .contact-text {
            display: block;
            color: #e0e0e5;
            white-space: nowrap;
        }

        .contact-subtext {
            display: block;
            margin-top: 5px;
            color: var(--fc-muted);
            font-size: 10px;
        }

        .specialization-tag {
            display: inline-block;
            max-width: 190px;
            padding: 6px 9px;
            background: rgba(255, 107, 44, .1);
            color: #ff996f;
            border-radius: 6px;
            font-size: 10px;
            line-height: 1.4;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-active {
            background: rgba(49, 196, 141, .12);
            color: #4de0a9;
        }

        .status-inactive {
            background: rgba(255, 104, 104, .12);
            color: #ff8585;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
        }

        .icon-btn {
            width: 31px;
            height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #393a44;
            background: #24252c;
            color: #d4d4dc;
            border-radius: 7px;
            font-size: 11px;
            cursor: pointer;
        }

        .icon-btn:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .icon-btn.toggle:hover {
            color: var(--fc-green);
            border-color: var(--fc-green);
        }

        .specialization-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .specialization-row:last-child {
            margin-bottom: 0;
        }

        .specialization-name {
            color: #dddde3;
        }

        .specialization-count {
            color: var(--fc-orange);
            font-weight: 800;
        }

        .progress-track {
            height: 5px;
            margin-top: 8px;
            background: #33343c;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--fc-orange);
            border-radius: 10px;
        }

        .alert {
            border-radius: 10px;
            font-size: 13px;
        }

        .modal-content {
            background: #1b1c22;
            border: 1px solid var(--fc-border);
            color: #fff;
            border-radius: 14px;
        }

        .modal-header,
        .modal-footer {
            border-color: var(--fc-border);
        }

        .modal .btn-close {
            filter: invert(1);
        }

        .detail-label {
            display: block;
            color: var(--fc-muted);
            font-size: 11px;
            margin-bottom: 5px;
        }

        .detail-value {
            display: block;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .empty-state {
            padding: 45px 15px !important;
            text-align: center;
            color: var(--fc-muted);
        }

        .mobile-menu {
            display: none;
        }

        @media (max-width: 1100px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
                padding: 23px;
            }
        }

        @media (max-width: 767px) {
            .sidebar {
                position: static;
                width: 100%;
                padding: 15px;
                border-right: 0;
                border-bottom: 1px solid var(--fc-border);
            }

            .brand {
                padding-bottom: 15px;
                margin-bottom: 10px;
            }

            .nav-heading {
                margin-top: 14px;
            }

            .sidebar-navigation {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 3px;
            }

            .sidebar-link {
                margin-bottom: 0;
                padding: 10px;
                font-size: 12px;
            }

            .main-content {
                margin-left: 0;
                padding: 20px 14px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
                margin-bottom: 22px;
            }

            .page-title {
                font-size: 24px;
            }

            .topbar-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                width: 100%;
            }

            .section-card {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
<div class="admin-layout">

    <!-- Administrator Sidebar -->
    <aside class="sidebar">
        <a href="index.php" class="brand">
            <div class="brand-icon">
                <i class="fas fa-dumbbell"></i>
            </div>
            <div>
                <h1 class="brand-name">FIT<span>CORE</span></h1>
                <p class="brand-subtitle">Fitness Management System</p>
            </div>
        </a>

        <div class="nav-heading">Main Menu</div>

        <nav class="sidebar-navigation">
            <a href="index.php" class="sidebar-link">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <a href="members.php" class="sidebar-link">
                <i class="fas fa-users"></i> Members
            </a>

            <a href="trainers.php" class="sidebar-link active">
                <i class="fas fa-user-tie"></i> Trainers
            </a>

            <a href="memberships.php" class="sidebar-link">
                <i class="fas fa-id-card"></i> Memberships
            </a>

            <a href="payments.php" class="sidebar-link">
                <i class="fas fa-credit-card"></i> Payments
            </a>

            <a href="classes.php" class="sidebar-link">
                <i class="fas fa-calendar-alt"></i> Classes
            </a>

            <a href="attendance.php" class="sidebar-link">
                <i class="fas fa-clipboard-check"></i> Attendance
            </a>

            <a href="reports.php" class="sidebar-link">
                <i class="fas fa-chart-line"></i> Reports
            </a>
        </nav>

        <div class="sidebar-bottom">
            <a href="../fitcore_demo.php" class="sidebar-link">
                <i class="fas fa-exchange-alt"></i> Switch Demo Role
            </a>

            <a href="../index.php" class="sidebar-link">
                <i class="fas fa-globe"></i> Main Website
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <header class="topbar">
            <div>
                <h2 class="page-title">Trainer Management</h2>
                <p class="page-subtitle">
                    Manage your fitness professionals, specializations and availability.
                </p>
            </div>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="admin-profile">
                    <div class="admin-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <strong>Administrator</strong>
                        <small>FitCore Management</small>
                    </div>
                </div>

                <button
                    type="button"
                    class="btn btn-orange"
                    data-bs-toggle="modal"
                    data-bs-target="#addTrainerModal"
                >
                    <i class="fas fa-plus me-1"></i> Add Trainer
                </button>
            </div>
        </header>

        <?php if ($notice !== ''): ?>
            <div class="alert alert-<?php echo trainerEscape($noticeType); ?> alert-dismissible fade show"
                 role="alert">
                <?php echo trainerEscape($notice); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Total Trainers</span>
                        <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                    </div>
                    <div class="stat-value"><?php echo $totalTrainers; ?></div>
                    <div class="stat-note">Registered fitness professionals</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Active Trainers</span>
                        <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                    </div>
                    <div class="stat-value"><?php echo $activeTrainers; ?></div>
                    <div class="stat-note">Currently available in the demo</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Inactive Trainers</span>
                        <div class="stat-icon"><i class="fas fa-user-slash"></i></div>
                    </div>
                    <div class="stat-value"><?php echo $inactiveTrainers; ?></div>
                    <div class="stat-note">Accounts marked inactive</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Specializations</span>
                        <div class="stat-icon"><i class="fas fa-award"></i></div>
                    </div>
                    <div class="stat-value"><?php echo count($specializationCounts); ?></div>
                    <div class="stat-note">Different fitness expertise areas</div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <section class="section-card mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <h3 class="section-heading">All Trainers</h3>
                    <p class="section-description">
                        Search, filter and manage registered trainers.
                    </p>
                </div>

                <span class="status-badge status-active">
                    <i class="fas fa-database"></i>
                    Demo Records
                </span>
            </div>

            <form action="trainers.php" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-lg-5">
                        <label class="form-label" for="search">Search Trainers</label>
                        <input
                            type="text"
                            class="form-control"
                            id="search"
                            name="search"
                            placeholder="Name, trainer ID, email or phone..."
                            value="<?php echo trainerEscape($search); ?>"
                        >
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label" for="specialization">Specialization</label>
                        <select class="form-select" id="specialization" name="specialization">
                            <option value="">All Specializations</option>
                            <?php foreach ($specializations as $item): ?>
                                <option
                                    value="<?php echo trainerEscape($item); ?>"
                                    <?php echo $specializationFilter === $item ? 'selected' : ''; ?>
                                >
                                    <?php echo trainerEscape($item); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="Active" <?php echo $statusFilter === 'Active' ? 'selected' : ''; ?>>
                                Active
                            </option>
                            <option value="Inactive" <?php echo $statusFilter === 'Inactive' ? 'selected' : ''; ?>>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-orange flex-grow-1">
                            <i class="fas fa-search me-1"></i> Search
                        </button>

                        <a href="trainers.php" class="btn btn-outline-light"
                           title="Reset filters">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </section>

        <!-- Trainers Table -->
        <section class="section-card mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h3 class="section-heading">Trainer Directory</h3>
                    <p class="section-description">
                        Showing <?php echo count($filteredTrainers); ?>
                        of <?php echo $totalTrainers; ?> trainers
                    </p>
                </div>

                <button type="button" class="btn btn-outline-light btn-sm"
                        onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print Directory
                </button>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Trainer</th>
                        <th>Contact</th>
                        <th>Specialization</th>
                        <th>Experience</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (count($filteredTrainers) > 0): ?>
                        <?php foreach ($filteredTrainers as $trainer): ?>
                            <?php
                            $initials = '';
                            $nameParts = preg_split('/\s+/', trim($trainer['name']));

                            foreach (array_slice($nameParts, 0, 2) as $part) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }

                            $statusClass = $trainer['status'] === 'Active'
                                ? 'status-active'
                                : 'status-inactive';
                            ?>

                            <tr>
                                <td>
                                    <div class="trainer-info">
                                        <div class="trainer-avatar">
                                            <?php echo trainerEscape($initials); ?>
                                        </div>
                                        <div>
                                            <span class="trainer-name">
                                                <?php echo trainerEscape($trainer['name']); ?>
                                            </span>
                                            <span class="trainer-id">
                                                <?php echo trainerEscape($trainer['id']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="contact-text">
                                        <?php echo trainerEscape($trainer['email']); ?>
                                    </span>
                                    <span class="contact-subtext">
                                        <?php echo trainerEscape($trainer['phone']); ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="specialization-tag">
                                        <?php echo trainerEscape($trainer['specialization']); ?>
                                    </span>
                                </td>

                                <td>
                                    <strong><?php echo (int)$trainer['experience']; ?> years</strong>
                                </td>

                                <td>
                                    <span class="status-badge <?php echo $statusClass; ?>">
                                        <i class="fas fa-circle" style="font-size:6px"></i>
                                        <?php echo trainerEscape($trainer['status']); ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <button
                                            type="button"
                                            class="icon-btn"
                                            title="View trainer details"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trainerDetailsModal"
                                            data-id="<?php echo trainerEscape($trainer['id']); ?>"
                                            data-name="<?php echo trainerEscape($trainer['name']); ?>"
                                            data-email="<?php echo trainerEscape($trainer['email']); ?>"
                                            data-phone="<?php echo trainerEscape($trainer['phone']); ?>"
                                            data-specialization="<?php echo trainerEscape($trainer['specialization']); ?>"
                                            data-experience="<?php echo (int)$trainer['experience']; ?>"
                                            data-gender="<?php echo trainerEscape($trainer['gender']); ?>"
                                            data-classes="<?php echo trainerEscape($trainer['classes']); ?>"
                                            data-schedule="<?php echo trainerEscape($trainer['schedule']); ?>"
                                            data-status="<?php echo trainerEscape($trainer['status']); ?>"
                                            data-joined="<?php echo trainerEscape($trainer['joined']); ?>"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <form action="trainers.php" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Change this trainer account status?');">
                                            <input type="hidden" name="csrf_token"
                                                   value="<?php echo trainerEscape($csrfToken); ?>">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="trainer_id"
                                                   value="<?php echo trainerEscape($trainer['id']); ?>">

                                            <button type="submit" class="icon-btn toggle"
                                                    title="<?php echo $trainer['status'] === 'Active' ? 'Deactivate trainer' : 'Activate trainer'; ?>">
                                                <i class="fas <?php echo $trainer['status'] === 'Active' ? 'fa-user-slash' : 'fa-user-check'; ?>"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-search fa-2x mb-3 d-block"></i>
                                <strong>No trainers found</strong>
                                <div class="mt-2">
                                    Try another search term or reset the filters.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Specialization Summary -->
        <section class="section-card">
            <div class="mb-4">
                <h3 class="section-heading">Specialization Overview</h3>
                <p class="section-description">
                    Distribution of trainers across fitness expertise areas.
                </p>
            </div>

            <?php if ($totalTrainers > 0): ?>
                <div class="row g-4">
                    <?php foreach ($specializations as $item): ?>
                        <?php
                        $count = $specializationCounts[$item] ?? 0;
                        $percentage = ($count / $totalTrainers) * 100;
                        ?>
                        <div class="col-12 col-md-6">
                            <div class="specialization-row mb-0">
                                <span class="specialization-name">
                                    <?php echo trainerEscape($item); ?>
                                </span>
                                <span class="specialization-count">
                                    <?php echo $count; ?>
                                    <?php echo $count === 1 ? 'trainer' : 'trainers'; ?>
                                </span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill"
                                     style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-secondary mb-0">No trainer records are available.</p>
            <?php endif; ?>
        </section>

        <footer class="text-center mt-4 py-3"
                style="color:var(--fc-muted);font-size:11px">
            © <?php echo date('Y'); ?> FitCore Fitness Platform.
            Administrator Demonstration Interface.
        </footer>
    </main>
</div>

<!-- Add Trainer Modal -->
<div class="modal fade" id="addTrainerModal" tabindex="-1"
     aria-labelledby="addTrainerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="trainers.php" method="POST">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="addTrainerModalLabel">
                            <i class="fas fa-user-plus me-2"
                               style="color:var(--fc-orange)"></i>
                            Add New Trainer
                        </h5>
                        <small class="text-secondary">
                            Register a trainer in the demonstration system.
                        </small>
                    </div>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="csrf_token"
                           value="<?php echo trainerEscape($csrfToken); ?>">
                    <input type="hidden" name="action" value="add_trainer">

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="trainerName">Full Name *</label>
                            <input type="text" class="form-control" id="trainerName"
                                   name="name" maxlength="100" required
                                   placeholder="Enter trainer name">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="trainerEmail">Email Address *</label>
                            <input type="email" class="form-control" id="trainerEmail"
                                   name="email" maxlength="150" required
                                   placeholder="trainer@example.com">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="trainerPhone">Phone Number *</label>
                            <input type="tel" class="form-control" id="trainerPhone"
                                   name="phone" maxlength="30" required
                                   placeholder="+1 555-0109">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="trainerGender">Gender *</label>
                            <select class="form-select" id="trainerGender"
                                    name="gender" required>
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="trainerSpecialization">
                                Specialization *
                            </label>
                            <select class="form-select" id="trainerSpecialization"
                                    name="specialization" required>
                                <option value="">Select specialization</option>
                                <?php foreach ($specializations as $item): ?>
                                    <option value="<?php echo trainerEscape($item); ?>">
                                        <?php echo trainerEscape($item); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="trainerExperience">
                                Years of Experience *
                            </label>
                            <input type="number" class="form-control"
                                   id="trainerExperience" name="experience"
                                   min="0" max="60" required value="1">
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="trainerClasses">
                                Assigned Classes / Services *
                            </label>
                            <input type="text" class="form-control" id="trainerClasses"
                                   name="classes" maxlength="200" required
                                   placeholder="e.g. Strength Training, HIIT">
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="trainerSchedule">
                                Working Schedule *
                            </label>
                            <input type="text" class="form-control" id="trainerSchedule"
                                   name="schedule" maxlength="100" required
                                   placeholder="e.g. Monday - Friday">
                        </div>
                    </div>

                    <div class="alert alert-info mt-4 mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        This demonstration creates an active trainer profile
                        in the current PHP session. It does not send an email
                        or create a database record.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-orange">
                        <i class="fas fa-plus me-1"></i> Add Trainer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Trainer Details Modal -->
<div class="modal fade" id="trainerDetailsModal" tabindex="-1"
     aria-labelledby="trainerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="trainerDetailsModalLabel">
                        Trainer Profile
                    </h5>
                    <small class="text-secondary" id="detailTrainerId"></small>
                </div>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="trainer-avatar" id="detailAvatar"
                         style="width:60px;height:60px;font-size:19px"></div>
                    <div>
                        <h4 class="mb-2" id="detailName"></h4>
                        <span class="specialization-tag" id="detailSpecialization"></span>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <span class="detail-label">Email Address</span>
                        <span class="detail-value" id="detailEmail"></span>
                    </div>

                    <div class="col-12 col-md-6">
                        <span class="detail-label">Phone Number</span>
                        <span class="detail-value" id="detailPhone"></span>
                    </div>

                    <div class="col-6 col-md-3">
                        <span class="detail-label">Gender</span>
                        <span class="detail-value" id="detailGender"></span>
                    </div>

                    <div class="col-6 col-md-3">
                        <span class="detail-label">Experience</span>
                        <span class="detail-value" id="detailExperience"></span>
                    </div>

                    <div class="col-12 col-md-6">
                        <span class="detail-label">Account Status</span>
                        <span id="detailStatus"></span>
                    </div>

                    <div class="col-12">
                        <span class="detail-label">Assigned Classes / Services</span>
                        <span class="detail-value" id="detailClasses"></span>
                    </div>

                    <div class="col-12 col-md-6">
                        <span class="detail-label">Working Schedule</span>
                        <span class="detail-value" id="detailSchedule"></span>
                    </div>

                    <div class="col-12 col-md-6">
                        <span class="detail-label">Date Joined</span>
                        <span class="detail-value" id="detailJoined"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light"
                        data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailsModal = document.getElementById('trainerDetailsModal');

    if (detailsModal) {
        detailsModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const fields = {
                detailTrainerId: button.dataset.id,
                detailName: button.dataset.name,
                detailEmail: button.dataset.email,
                detailPhone: button.dataset.phone,
                detailSpecialization: button.dataset.specialization,
                detailGender: button.dataset.gender,
                detailClasses: button.dataset.classes,
                detailSchedule: button.dataset.schedule,
                detailJoined: button.dataset.joined
            };

            Object.keys(fields).forEach(function (id) {
                const element = document.getElementById(id);

                if (element) {
                    element.textContent = fields[id] || 'Not provided';
                }
            });

            const experience = document.getElementById('detailExperience');
            experience.textContent =
                (button.dataset.experience || '0') + ' years';

            const initials = (button.dataset.name || '')
                .trim()
                .split(/\s+/)
                .slice(0, 2)
                .map(function (part) {
                    return part.charAt(0).toUpperCase();
                })
                .join('');

            document.getElementById('detailAvatar').textContent = initials;

            const status = button.dataset.status || 'Inactive';
            const statusElement = document.getElementById('detailStatus');

            statusElement.textContent = '';

            const badge = document.createElement('span');
            badge.className = 'status-badge ' +
                (status === 'Active' ? 'status-active' : 'status-inactive');

            const dot = document.createElement('i');
            dot.className = 'fas fa-circle';
            dot.style.fontSize = '6px';

            badge.appendChild(dot);
            badge.appendChild(document.createTextNode(' ' + status));
            statusElement.appendChild(badge);
        });
    }
});
</script>
</body>
</html>