```php
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore Admin - Membership Management
|--------------------------------------------------------------------------
| Demonstration only. Plans are stored in PHP sessions.
| No database or real payment processing is used.
*/

if (!isset($_SESSION['fitcore_admin_memberships'])) {
    $_SESSION['fitcore_admin_memberships'] = [
        [
            'id' => 'FCM-PL-001',
            'name' => 'Basic',
            'price' => 20,
            'duration' => 1,
            'description' => 'A great starting point for your fitness journey.',
            'benefits' => [
                'Access to gym equipment',
                'Basic workout facilities',
                'Locker room access',
                'Standard support'
            ],
            'status' => 'Active',
            'subscribers' => 38,
            'created' => '2025-01-10',
            'color' => 'basic'
        ],
        [
            'id' => 'FCM-PL-002',
            'name' => 'Pro',
            'price' => 40,
            'duration' => 1,
            'description' => 'More flexibility and support for your goals.',
            'benefits' => [
                'Everything in Basic',
                'Group fitness classes',
                'Monthly fitness assessment',
                'Priority class booking',
                'Personalized workout guidance'
            ],
            'status' => 'Active',
            'subscribers' => 65,
            'created' => '2025-01-10',
            'color' => 'pro'
        ],
        [
            'id' => 'FCM-PL-003',
            'name' => 'Premium',
            'price' => 70,
            'duration' => 1,
            'description' => 'The complete fitness experience.',
            'benefits' => [
                'Everything in Pro',
                'Personal training sessions',
                'Priority trainer selection',
                'Advanced fitness assessments',
                'Premium recovery facilities',
                'Exclusive member support'
            ],
            'status' => 'Active',
            'subscribers' => 42,
            'created' => '2025-01-10',
            'color' => 'premium'
        ]
    ];
}

if (!isset($_SESSION['fitcore_memberships_csrf'])) {
    $_SESSION['fitcore_memberships_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['fitcore_memberships_csrf'];

function membershipEscape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function membershipRedirect($message, $type = 'success')
{
    $_SESSION['fitcore_memberships_notice'] = $message;
    $_SESSION['fitcore_memberships_notice_type'] = $type;

    header('Location: memberships.php');
    exit;
}

$notice = '';
$noticeType = 'success';

if (isset($_SESSION['fitcore_memberships_notice'])) {
    $notice = $_SESSION['fitcore_memberships_notice'];
    $noticeType = $_SESSION['fitcore_memberships_notice_type'] ?? 'success';

    unset(
        $_SESSION['fitcore_memberships_notice'],
        $_SESSION['fitcore_memberships_notice_type']
    );
}

/*
|--------------------------------------------------------------------------
| Process Membership Actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($submittedToken) ||
        !hash_equals($csrfToken, $submittedToken)
    ) {
        membershipRedirect(
            'Security verification failed. Please try again.',
            'danger'
        );
    }

    $action = $_POST['action'] ?? '';

    /*
    | Add or edit a membership plan
    */
    if ($action === 'save_plan') {
        $planId = trim($_POST['plan_id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
        $duration = filter_var($_POST['duration'] ?? null, FILTER_VALIDATE_INT);
        $description = trim($_POST['description'] ?? '');
        $benefitsText = trim($_POST['benefits'] ?? '');

        if (
            $name === '' ||
            strlen($name) > 60 ||
            $price === false ||
            $price === null ||
            $price < 0 ||
            $price > 100000 ||
            $duration === false ||
            $duration === null ||
            $duration < 1 ||
            $duration > 36 ||
            $description === '' ||
            strlen($description) > 250 ||
            $benefitsText === '' ||
            strlen($benefitsText) > 2000
        ) {
            membershipRedirect(
                'Please provide valid details for every required field.',
                'danger'
            );
        }

        $benefits = array_values(array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', $benefitsText)),
            function ($benefit) {
                return $benefit !== '';
            }
        ));

        if (count($benefits) < 1 || count($benefits) > 15) {
            membershipRedirect(
                'A plan must contain between 1 and 15 benefits.',
                'danger'
            );
        }

        $duplicateName = false;

        foreach ($_SESSION['fitcore_admin_memberships'] as $existingPlan) {
            if (
                strcasecmp($existingPlan['name'], $name) === 0 &&
                $existingPlan['id'] !== $planId
            ) {
                $duplicateName = true;
                break;
            }
        }

        if ($duplicateName) {
            membershipRedirect(
                'A membership plan with this name already exists.',
                'danger'
            );
        }

        if ($planId !== '') {
            $found = false;

            foreach ($_SESSION['fitcore_admin_memberships'] as &$plan) {
                if ($plan['id'] === $planId) {
                    $plan['name'] = $name;
                    $plan['price'] = round((float)$price, 2);
                    $plan['duration'] = $duration;
                    $plan['description'] = $description;
                    $plan['benefits'] = $benefits;
                    $found = true;
                    break;
                }
            }
            unset($plan);

            if (!$found) {
                membershipRedirect(
                    'The selected membership plan could not be found.',
                    'danger'
                );
            }

            membershipRedirect('Membership plan updated successfully in this demo.');
        }

        $nextNumber = 1;

        foreach ($_SESSION['fitcore_admin_memberships'] as $existingPlan) {
            if (preg_match('/FCM-PL-(\d+)/', $existingPlan['id'], $matches)) {
                $nextNumber = max($nextNumber, (int)$matches[1] + 1);
            }
        }

        $_SESSION['fitcore_admin_memberships'][] = [
            'id' => 'FCM-PL-' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT),
            'name' => $name,
            'price' => round((float)$price, 2),
            'duration' => $duration,
            'description' => $description,
            'benefits' => $benefits,
            'status' => 'Active',
            'subscribers' => 0,
            'created' => date('Y-m-d'),
            'color' => 'custom'
        ];

        membershipRedirect('New membership plan added successfully in this demo.');
    }

    /*
    | Activate or deactivate a membership plan
    */
    if ($action === 'toggle_status') {
        $planId = trim($_POST['plan_id'] ?? '');
        $found = false;

        foreach ($_SESSION['fitcore_admin_memberships'] as &$plan) {
            if ($plan['id'] === $planId) {
                $plan['status'] =
                    $plan['status'] === 'Active' ? 'Inactive' : 'Active';

                $newStatus = $plan['status'];
                $found = true;
                break;
            }
        }
        unset($plan);

        if ($found) {
            membershipRedirect(
                'Membership plan is now ' . $newStatus . '.'
            );
        }

        membershipRedirect('Membership plan not found.', 'danger');
    }

    membershipRedirect('Unknown action.', 'danger');
}

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

$plans = $_SESSION['fitcore_admin_memberships'];

$totalPlans = count($plans);
$activePlans = 0;
$inactivePlans = 0;
$totalSubscribers = 0;
$monthlyRevenuePotential = 0;
$mostPopularPlan = 'N/A';
$highestSubscribers = -1;

foreach ($plans as $plan) {
    $subscribers = (int)$plan['subscribers'];
    $totalSubscribers += $subscribers;

    if ($plan['status'] === 'Active') {
        $activePlans++;

        // Normalize the plan price to a monthly equivalent.
        $monthlyRevenuePotential +=
            ((float)$plan['price'] / max(1, (int)$plan['duration']))
            * $subscribers;
    } else {
        $inactivePlans++;
    }

    if ($subscribers > $highestSubscribers) {
        $highestSubscribers = $subscribers;
        $mostPopularPlan = $plan['name'];
    }
}

/*
|--------------------------------------------------------------------------
| Search and Filter
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

$filteredPlans = array_filter($plans, function ($plan) use ($search, $statusFilter) {
    $matchesSearch = true;

    if ($search !== '') {
        $searchable = $plan['id'] . ' ' . $plan['name'] . ' ' .
            $plan['description'];

        $matchesSearch = stripos($searchable, $search) !== false;
    }

    $matchesStatus =
        $statusFilter === '' || $plan['status'] === $statusFilter;

    return $matchesSearch && $matchesStatus;
});

/*
|--------------------------------------------------------------------------
| Prepare Chart Data
|--------------------------------------------------------------------------
*/

$chartLabels = [];
$chartSubscribers = [];
$chartColors = [];

foreach ($plans as $plan) {
    $chartLabels[] = $plan['name'];
    $chartSubscribers[] = (int)$plan['subscribers'];

    $chartColors[] = $plan['name'] === 'Premium'
        ? '#ff6b2c'
        : ($plan['name'] === 'Pro' ? '#ff9a6c' : '#777984');
}

$chartLabelsJson = json_encode(
    $chartLabels,
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);

$chartSubscribersJson = json_encode(
    $chartSubscribers,
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);

$chartColorsJson = json_encode(
    $chartColors,
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Membership Management | FitCore Admin</title>

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

        .stat-card,
        .section-card {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
        }

        .stat-card {
            height: 100%;
            padding: 21px;
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
            font-size: 27px;
            font-weight: 800;
            line-height: 1.2;
        }

        .stat-note {
            margin-top: 9px;
            color: var(--fc-muted);
            font-size: 11px;
        }

        .section-card {
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

        .membership-card {
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 23px;
            background: #191a20;
            border: 1px solid var(--fc-border);
            border-radius: 14px;
            overflow: hidden;
            transition: transform .2s ease, border-color .2s ease;
        }

        .membership-card:hover {
            transform: translateY(-4px);
            border-color: #555660;
        }

        .membership-card.featured {
            border-color: var(--fc-orange);
            box-shadow: 0 0 25px rgba(255, 107, 44, .07);
        }

        .featured-label {
            position: absolute;
            top: 0;
            right: 0;
            padding: 7px 12px;
            border-radius: 0 12px 0 10px;
            background: var(--fc-orange);
            color: #fff;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .plan-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            border-radius: 13px;
            font-size: 21px;
            margin-bottom: 18px;
        }

        .plan-name {
            margin: 0 0 8px;
            font-size: 21px;
            font-weight: 800;
        }

        .plan-description {
            min-height: 38px;
            color: var(--fc-muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .plan-price {
            margin: 17px 0 5px;
            font-size: 35px;
            font-weight: 900;
        }

        .plan-price span {
            color: var(--fc-muted);
            font-size: 12px;
            font-weight: 400;
        }

        .plan-duration {
            color: var(--fc-muted);
            font-size: 11px;
            margin-bottom: 20px;
        }

        .plan-divider {
            border-color: var(--fc-border);
            opacity: 1;
            margin: 0 0 19px;
        }

        .benefit-heading {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 14px;
        }

        .benefit-list {
            padding: 0;
            margin: 0 0 23px;
            list-style: none;
            flex-grow: 1;
        }

        .benefit-list li {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-bottom: 12px;
            color: #d1d1d9;
            font-size: 11px;
            line-height: 1.5;
        }

        .benefit-list i {
            margin-top: 2px;
            color: var(--fc-green);
        }

        .plan-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding-top: 16px;
            border-top: 1px solid var(--fc-border);
        }

        .subscriber-count {
            color: var(--fc-muted);
            font-size: 11px;
        }

        .subscriber-count strong {
            display: block;
            margin-top: 3px;
            color: #fff;
            font-size: 17px;
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

        textarea.form-control {
            min-height: 95px;
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
            padding: 14px 12px;
            border-color: var(--fc-border);
            font-size: 12px;
        }

        .plan-id {
            display: block;
            margin-top: 4px;
            color: var(--fc-muted);
            font-size: 10px;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
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

        .chart-container {
            position: relative;
            width: 100%;
            height: 260px;
        }

        .empty-state {
            padding: 35px 12px !important;
            text-align: center;
            color: var(--fc-muted);
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

            .section-card {
                padding: 16px;
            }

            .membership-card {
                padding: 20px;
            }
        }

        @media print {
            .sidebar,
            .topbar button,
            .filter-section,
            .plan-actions,
            .modal,
            .btn,
            footer {
                display: none !important;
            }

            body {
                background: #fff;
                color: #111;
            }

            .main-content {
                margin: 0;
                padding: 0;
            }

            .stat-card,
            .section-card,
            .membership-card {
                background: #fff;
                color: #111;
                border: 1px solid #ccc;
                box-shadow: none;
            }

            .plan-description,
            .plan-duration,
            .benefit-list li,
            .subscriber-count {
                color: #333;
            }
        }
    </style>
</head>

<body>

<div class="admin-layout">

    <!-- Sidebar -->
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

            <a href="trainers.php" class="sidebar-link">
                <i class="fas fa-user-tie"></i> Trainers
            </a>

            <a href="memberships.php" class="sidebar-link active">
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
                <h2 class="page-title">Membership Plans</h2>
                <p class="page-subtitle">
                    Create plans, manage pricing and review membership subscriptions.
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

                <button type="button" class="btn btn-orange"
                        data-bs-toggle="modal"
                        data-bs-target="#planModal"
                        onclick="prepareAddPlan()">
                    <i class="fas fa-plus me-1"></i> Add Plan
                </button>
            </div>
        </header>

        <?php if ($notice !== ''): ?>
            <div class="alert alert-<?php echo membershipEscape($noticeType); ?> alert-dismissible fade show"
                 role="alert">
                <?php echo membershipEscape($notice); ?>
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Total Plans</span>
                        <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
                    </div>
                    <div class="stat-value"><?php echo $totalPlans; ?></div>
                    <div class="stat-note">All registered membership plans</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Active Plans</span>
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                    <div class="stat-value"><?php echo $activePlans; ?></div>
                    <div class="stat-note">Plans currently available in this demo</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Monthly Revenue Potential</span>
                        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                    <div class="stat-value">
                        $<?php echo number_format($monthlyRevenuePotential, 0); ?>
                    </div>
                    <div class="stat-note">
                        Estimated monthly equivalent of active-plan subscriptions
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Most Popular Plan</span>
                        <div class="stat-icon"><i class="fas fa-star"></i></div>
                    </div>
                    <div class="stat-value" style="font-size:25px">
                        <?php echo membershipEscape($mostPopularPlan); ?>
                    </div>
                    <div class="stat-note">
                        <?php echo max(0, $highestSubscribers); ?> demo subscribers
                    </div>
                </div>
            </div>
        </div>

        <!-- Membership Plan Cards -->
        <section class="mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h3 class="section-heading">Available Membership Plans</h3>
                    <p class="section-description">
                        Review plan pricing, benefits and subscriber counts.
                    </p>
                </div>

                <button type="button" class="btn btn-outline-light btn-sm"
                        onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print Plans
                </button>
            </div>

            <div class="row g-3">
                <?php if (count($plans) > 0): ?>
                    <?php foreach ($plans as $plan): ?>
                        <?php
                        $isPremium = strcasecmp($plan['name'], 'Premium') === 0;
                        $statusClass = $plan['status'] === 'Active'
                            ? 'status-active'
                            : 'status-inactive';

                        $planIcon = strcasecmp($plan['name'], 'Basic') === 0
                            ? 'fa-seedling'
                            : (strcasecmp($plan['name'], 'Pro') === 0
                                ? 'fa-bolt'
                                : (strcasecmp($plan['name'], 'Premium') === 0
                                    ? 'fa-crown'
                                    : 'fa-dumbbell'));
                        ?>

                        <div class="col-12 col-md-6 col-xl-4">
                            <article class="membership-card <?php echo $isPremium ? 'featured' : ''; ?>">
                                <?php if ($isPremium): ?>
                                    <div class="featured-label">Premium Plan</div>
                                <?php endif; ?>

                                <div class="plan-icon">
                                    <i class="fas <?php echo $planIcon; ?>"></i>
                                </div>

                                <h4 class="plan-name">
                                    <?php echo membershipEscape($plan['name']); ?>
                                </h4>

                                <p class="plan-description">
                                    <?php echo membershipEscape($plan['description']); ?>
                                </p>

                                <div class="plan-price">
                                    $<?php echo number_format((float)$plan['price'], 2); ?>
                                    <span>
                                        / <?php echo (int)$plan['duration'] === 1
                                            ? 'month'
                                            : (int)$plan['duration'] . ' months'; ?>
                                    </span>
                                </div>

                                <div class="plan-duration">
                                    Plan ID: <?php echo membershipEscape($plan['id']); ?>
                                </div>

                                <hr class="plan-divider">

                                <div class="benefit-heading">Plan Benefits</div>

                                <ul class="benefit-list">
                                    <?php foreach ($plan['benefits'] as $benefit): ?>
                                        <li>
                                            <i class="fas fa-check-circle"></i>
                                            <span><?php echo membershipEscape($benefit); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <div class="mb-3">
                                    <span class="status-badge <?php echo $statusClass; ?>">
                                        <i class="fas fa-circle" style="font-size:6px"></i>
                                        <?php echo membershipEscape($plan['status']); ?>
                                    </span>
                                </div>

                                <div class="plan-footer">
                                    <div class="subscriber-count">
                                        Subscribers
                                        <strong><?php echo (int)$plan['subscribers']; ?></strong>
                                    </div>

                                    <div class="d-flex gap-2 plan-actions">
                                        <button
                                            type="button"
                                            class="icon-btn"
                                            title="Edit plan"
                                            data-bs-toggle="modal"
                                            data-bs-target="#planModal"
                                            data-id="<?php echo membershipEscape($plan['id']); ?>"
                                            data-name="<?php echo membershipEscape($plan['name']); ?>"
                                            data-price="<?php echo membershipEscape($plan['price']); ?>"
                                            data-duration="<?php echo (int)$plan['duration']; ?>"
                                            data-description="<?php echo membershipEscape($plan['description']); ?>"
                                            data-benefits="<?php echo membershipEscape(implode("\n", $plan['benefits'])); ?>"
                                            onclick="prepareEditPlan(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="icon-btn"
                                            title="View plan details"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewPlanModal"
                                            data-id="<?php echo membershipEscape($plan['id']); ?>"
                                            data-name="<?php echo membershipEscape($plan['name']); ?>"
                                            data-price="<?php echo membershipEscape($plan['price']); ?>"
                                            data-duration="<?php echo (int)$plan['duration']; ?>"
                                            data-description="<?php echo membershipEscape($plan['description']); ?>"
                                            data-benefits="<?php echo membershipEscape(implode("\n", $plan['benefits'])); ?>"
                                            data-subscribers="<?php echo (int)$plan['subscribers']; ?>"
                                            data-status="<?php echo membershipEscape($plan['status']); ?>"
                                            data-created="<?php echo membershipEscape($plan['created']); ?>"
                                            onclick="prepareViewPlan(this)">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <form action="memberships.php" method="POST"
                                              onsubmit="return confirm('Change the availability of this membership plan?');">
                                            <input type="hidden" name="csrf_token"
                                                   value="<?php echo membershipEscape($csrfToken); ?>">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="plan_id"
                                                   value="<?php echo membershipEscape($plan['id']); ?>">

                                            <button type="submit" class="icon-btn"
                                                    title="<?php echo $plan['status'] === 'Active' ? 'Deactivate plan' : 'Activate plan'; ?>">
                                                <i class="fas <?php echo $plan['status'] === 'Active' ? 'fa-toggle-on' : 'fa-toggle-off'; ?>"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="section-card text-center py-5">
                            <i class="fas fa-layer-group fa-2x mb-3"
                               style="color:var(--fc-orange)"></i>
                            <h4>No membership plans yet</h4>
                            <p class="text-secondary">
                                Use Add Plan to create the first plan.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Searchable Plan Directory -->
        <section class="section-card mb-4">
            <div class="mb-4">
                <h3 class="section-heading">Membership Directory</h3>
                <p class="section-description">
                    Search membership records and filter by availability.
                </p>
            </div>

            <form action="memberships.php" method="GET" class="filter-section mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-6 col-lg-7">
                        <label class="form-label" for="search">Search Plans</label>
                        <input type="text" class="form-control" id="search"
                               name="search"
                               value="<?php echo membershipEscape($search); ?>"
                               placeholder="Search by plan name, ID or description...">
                    </div>

                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label" for="status">Plan Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="Active"
                                <?php echo $statusFilter === 'Active' ? 'selected' : ''; ?>>
                                Active
                            </option>
                            <option value="Inactive"
                                <?php echo $statusFilter === 'Inactive' ? 'selected' : ''; ?>>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-orange w-100">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Subscribers</th>
                        <th>Created Date</th>
                        <th>Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (count($filteredPlans) > 0): ?>
                        <?php foreach ($filteredPlans as $plan): ?>
                            <tr>
                                <td>
                                    <strong><?php echo membershipEscape($plan['name']); ?></strong>
                                    <span class="plan-id">
                                        <?php echo membershipEscape($plan['id']); ?>
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        $<?php echo number_format((float)$plan['price'], 2); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php echo (int)$plan['duration']; ?>
                                    <?php echo (int)$plan['duration'] === 1 ? 'month' : 'months'; ?>
                                </td>

                                <td><?php echo (int)$plan['subscribers']; ?></td>

                                <td><?php echo membershipEscape($plan['created']); ?></td>

                                <td>
                                    <span class="status-badge <?php echo $plan['status'] === 'Active' ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo membershipEscape($plan['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-search fa-2x mb-3 d-block"></i>
                                No membership plans match your search.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Subscriber Chart -->
        <section class="section-card mb-4">
            <div class="mb-4">
                <h3 class="section-heading">Membership Popularity</h3>
                <p class="section-description">
                    Compare the sample subscriber count for each plan.
                </p>
            </div>

            <div class="chart-container">
                <canvas id="membershipChart"></canvas>
            </div>

            <p class="text-secondary mt-3 mb-0" style="font-size:11px">
                Subscriber counts are illustrative sample data, not live customer records.
            </p>
        </section>

        <footer class="text-center mt-4 py-3"
                style="color:var(--fc-muted);font-size:11px">
            © <?php echo date('Y'); ?> FitCore Fitness Platform.
            Administrator Demonstration Interface.
        </footer>

    </main>
</div>

<!-- Add/Edit Plan Modal -->
<div class="modal fade" id="planModal" tabindex="-1"
     aria-labelledby="planModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="memberships.php" method="POST">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="planModalLabel">
                            Add Membership Plan
                        </h5>
                        <small class="text-secondary">
                            Configure the membership price, duration and benefits.
                        </small>
                    </div>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="csrf_token"
                           value="<?php echo membershipEscape($csrfToken); ?>">
                    <input type="hidden" name="action" value="save_plan">
                    <input type="hidden" name="plan_id" id="formPlanId" value="">

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="formPlanName">
                                Plan Name *
                            </label>
                            <input type="text" class="form-control" name="name"
                                   id="formPlanName" maxlength="60" required
                                   placeholder="e.g. Student Fitness">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="formPlanPrice">
                                Price (USD) *
                            </label>
                            <input type="number" class="form-control" name="price"
                                   id="formPlanPrice" min="0" max="100000"
                                   step="0.01" required placeholder="e.g. 25.00">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="formPlanDuration">
                                Duration (Months) *
                            </label>
                            <select class="form-select" name="duration"
                                    id="formPlanDuration" required>
                                <option value="1">1 Month</option>
                                <option value="3">3 Months</option>
                                <option value="6">6 Months</option>
                                <option value="12">12 Months</option>
                                <option value="24">24 Months</option>
                                <option value="36">36 Months</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="formPlanDescription">
                                Description *
                            </label>
                            <textarea class="form-control" name="description"
                                      id="formPlanDescription" maxlength="250"
                                      required
                                      placeholder="Describe what this membership offers."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="formPlanBenefits">
                                Membership Benefits *
                            </label>
                            <textarea class="form-control" name="benefits"
                                      id="formPlanBenefits" maxlength="2000"
                                      required
                                      placeholder="Enter one benefit per line&#10;Access to gym equipment&#10;Group fitness classes&#10;Priority booking"></textarea>
                            <small class="text-secondary">
                                Enter one benefit per line. Between 1 and 15 benefits are allowed.
                            </small>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4 mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        This form updates demonstration data in the current PHP session.
                        It does not change existing payments or customer subscriptions.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-orange">
                        <i class="fas fa-save me-1"></i> Save Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Plan Modal -->
<div class="modal fade" id="viewPlanModal" tabindex="-1"
     aria-labelledby="viewPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="viewPlanModalLabel">
                        Membership Details
                    </h5>
                    <small class="text-secondary" id="viewPlanId"></small>
                </div>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="plan-icon mb-0">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <h4 class="mb-1" id="viewPlanName"></h4>
                        <div class="text-secondary" id="viewPlanDescription"
                             style="font-size:12px"></div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="section-card h-100">
                            <span class="text-secondary d-block mb-2"
                                  style="font-size:11px">Plan Price</span>
                            <strong id="viewPlanPrice" style="font-size:23px"></strong>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="section-card h-100">
                            <span class="text-secondary d-block mb-2"
                                  style="font-size:11px">Duration</span>
                            <strong id="viewPlanDuration" style="font-size:23px"></strong>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="section-card h-100">
                            <span class="text-secondary d-block mb-2"
                                  style="font-size:11px">Subscribers</span>
                            <strong id="viewPlanSubscribers" style="font-size:23px"></strong>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="section-card h-100">
                            <span class="text-secondary d-block mb-2"
                                  style="font-size:11px">Status</span>
                            <span id="viewPlanStatus"></span>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Included Benefits</h6>
                <ul id="viewPlanBenefits"
                    class="benefit-list mb-4"></ul>

                <div class="text-secondary" style="font-size:11px">
                    Created on: <span id="viewPlanCreated"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light"
                        data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js is optional. Place a local Chart.js file here to enable the chart. -->
<script src="../assets/js/chart.umd.min.js"></script>

<script>
function prepareAddPlan() {
    document.getElementById('planModalLabel').textContent = 'Add Membership Plan';
    document.getElementById('formPlanId').value = '';
    document.getElementById('formPlanName').value = '';
    document.getElementById('formPlanPrice').value = '';
    document.getElementById('formPlanDuration').value = '1';
    document.getElementById('formPlanDescription').value = '';
    document.getElementById('formPlanBenefits').value = '';
}

function prepareEditPlan(button) {
    document.getElementById('planModalLabel').textContent = 'Edit Membership Plan';

    document.getElementById('formPlanId').value = button.dataset.id || '';
    document.getElementById('formPlanName').value = button.dataset.name || '';
    document.getElementById('formPlanPrice').value = button.dataset.price || '';
    document.getElementById('formPlanDuration').value = button.dataset.duration || '1';
    document.getElementById('formPlanDescription').value =
        button.dataset.description || '';
    document.getElementById('formPlanBenefits').value =
        button.dataset.benefits || '';
}

function prepareViewPlan(button) {
    const setText = function (id, value) {
        document.getElementById(id).textContent = value || 'Not provided';
    };

    setText('viewPlanId', button.dataset.id);
    setText('viewPlanName', button.dataset.name);
    setText('viewPlanDescription', button.dataset.description);
    setText('viewPlanPrice', '$' + Number(button.dataset.price || 0).toFixed(2));
    setText('viewPlanDuration',
        button.dataset.duration + ' month' +
        (Number(button.dataset.duration) === 1 ? '' : 's'));
    setText('viewPlanSubscribers', button.dataset.subscribers);
    setText('viewPlanCreated', button.dataset.created);

    const status = button.dataset.status || 'Inactive';
    const statusElement = document.getElementById('viewPlanStatus');

    statusElement.textContent = '';

    const badge = document.createElement('span');
    badge.className = 'status-badge ' +
        (status === 'Active' ? 'status-active' : 'status-inactive');
    badge.textContent = status;
    statusElement.appendChild(badge);

    const benefitsElement = document.getElementById('viewPlanBenefits');
    benefitsElement.replaceChildren();

    const benefits = (button.dataset.benefits || '')
        .split(/\r?\n/)
        .filter(function (benefit) {
            return benefit.trim() !== '';
        });

    benefits.forEach(function (benefit) {
        const item = document.createElement('li');
        const icon = document.createElement('i');
        const text = document.createElement('span');

        icon.className = 'fas fa-check-circle';
        text.textContent = benefit;

        item.appendChild(icon);
        item.appendChild(text);
        benefitsElement.appendChild(item);
    });
}

/* Optional chart: the page continues to work without Chart.js. */
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('membershipChart');

    if (!canvas || typeof Chart === 'undefined') {
        if (canvas) {
            canvas.parentElement.innerHTML =
                '<p class="text-secondary text-center py-5">' +
                'To display the chart, add the Chart.js library at ' +
                '<code>assets/js/chart.umd.min.js</code>.</p>';
        }
        return;
    }

    const labels = <?php echo $chartLabelsJson; ?>;
    const subscribers = <?php echo $chartSubscribersJson; ?>;
    const colors = <?php echo $chartColorsJson; ?>;

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sample Subscribers',
                data: subscribers,
                backgroundColor: colors,
                borderRadius: 7,
                maxBarThickness: 65
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#c5c5cd'
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        color: '#393a44'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#a0a1ab',
                        precision: 0
                    },
                    grid: {
                        color: '#30313a'
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>

</body>
</html>