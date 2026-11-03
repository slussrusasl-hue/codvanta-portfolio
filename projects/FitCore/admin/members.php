
<?php
session_start();

date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| FitCore - Member Management
|--------------------------------------------------------------------------
| Portfolio demonstration only.
| Member records and status changes are stored in the PHP session.
*/

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/* Seed demonstration records once per session */
if (!isset($_SESSION['fitcore_admin_members'])) {
    $_SESSION['fitcore_admin_members'] = [
        [
            'id' => 'FCM-1001',
            'name' => 'Olivia Bennett',
            'email' => 'olivia.bennett@example.com',
            'phone' => '+1 555-0101',
            'gender' => 'Female',
            'age' => 27,
            'plan' => 'Premium',
            'joined' => '2026-01-12',
            'expiry' => '2026-11-12',
            'status' => 'Active',
            'payment' => 'Paid',
            'sessions' => 18,
            'avatar' => 'OB'
        ],
        [
            'id' => 'FCM-1002',
            'name' => 'Ethan Brooks',
            'email' => 'ethan.brooks@example.com',
            'phone' => '+1 555-0102',
            'gender' => 'Male',
            'age' => 31,
            'plan' => 'Pro',
            'joined' => '2026-02-04',
            'expiry' => '2026-11-04',
            'status' => 'Active',
            'payment' => 'Paid',
            'sessions' => 14,
            'avatar' => 'EB'
        ],
        [
            'id' => 'FCM-1003',
            'name' => 'Sophia Mitchell',
            'email' => 'sophia.mitchell@example.com',
            'phone' => '+1 555-0103',
            'gender' => 'Female',
            'age' => 24,
            'plan' => 'Basic',
            'joined' => '2026-03-15',
            'expiry' => '2026-10-15',
            'status' => 'Active',
            'payment' => 'Pending',
            'sessions' => 9,
            'avatar' => 'SM'
        ],
        [
            'id' => 'FCM-1004',
            'name' => 'Noah Wilson',
            'email' => 'noah.wilson@example.com',
            'phone' => '+1 555-0104',
            'gender' => 'Male',
            'age' => 35,
            'plan' => 'Premium',
            'joined' => '2025-12-20',
            'expiry' => '2026-10-20',
            'status' => 'Active',
            'payment' => 'Paid',
            'sessions' => 26,
            'avatar' => 'NW'
        ],
        [
            'id' => 'FCM-1005',
            'name' => 'Ava Thompson',
            'email' => 'ava.thompson@example.com',
            'phone' => '+1 555-0105',
            'gender' => 'Female',
            'age' => 29,
            'plan' => 'Pro',
            'joined' => '2026-04-08',
            'expiry' => '2026-11-08',
            'status' => 'Active',
            'payment' => 'Paid',
            'sessions' => 12,
            'avatar' => 'AT'
        ],
        [
            'id' => 'FCM-1006',
            'name' => 'Liam Anderson',
            'email' => 'liam.anderson@example.com',
            'phone' => '+1 555-0106',
            'gender' => 'Male',
            'age' => 22,
            'plan' => 'Basic',
            'joined' => '2026-05-11',
            'expiry' => '2026-10-11',
            'status' => 'Suspended',
            'payment' => 'Pending',
            'sessions' => 5,
            'avatar' => 'LA'
        ],
        [
            'id' => 'FCM-1007',
            'name' => 'Isabella Clark',
            'email' => 'isabella.clark@example.com',
            'phone' => '+1 555-0107',
            'gender' => 'Female',
            'age' => 33,
            'plan' => 'Premium',
            'joined' => '2026-01-27',
            'expiry' => '2026-11-27',
            'status' => 'Active',
            'payment' => 'Paid',
            'sessions' => 21,
            'avatar' => 'IC'
        ],
        [
            'id' => 'FCM-1008',
            'name' => 'James Parker',
            'email' => 'james.parker@example.com',
            'phone' => '+1 555-0108',
            'gender' => 'Male',
            'age' => 28,
            'plan' => 'Pro',
            'joined' => '2026-06-02',
            'expiry' => '2026-11-02',
            'status' => 'Active',
            'payment' => 'Paid',
            'sessions' => 7,
            'avatar' => 'JP'
        ],
        [
            'id' => 'FCM-1009',
            'name' => 'Mia Robinson',
            'email' => 'mia.robinson@example.com',
            'phone' => '+1 555-0109',
            'gender' => 'Female',
            'age' => 26,
            'plan' => 'Basic',
            'joined' => '2026-07-14',
            'expiry' => '2026-10-14',
            'status' => 'Expired',
            'payment' => 'Failed',
            'sessions' => 3,
            'avatar' => 'MR'
        ],
        [
            'id' => 'FCM-1010',
            'name' => 'Lucas Martin',
            'email' => 'lucas.martin@example.com',
            'phone' => '+1 555-0110',
            'gender' => 'Male',
            'age' => 38,
            'plan' => 'Premium',
            'joined' => '2025-11-03',
            'expiry' => '2026-10-03',
            'status' => 'Expired',
            'payment' => 'Paid',
            'sessions' => 31,
            'avatar' => 'LM'
        ],
        [
            'id' => 'FCM-1011',
            'name' => 'Charlotte Davis',
            'email' => 'charlotte.davis@example.com',
            'phone' => '+1 555-0111',
            'gender' => 'Female',
            'age' => 30,
            'plan' => 'Pro',
            'joined' => '2026-07-21',
            'expiry' => '2026-11-21',
            'status' => 'Active',
            'payment' => 'Paid',
            'sessions' => 11,
            'avatar' => 'CD'
        ],
        [
            'id' => 'FCM-1012',
            'name' => 'Benjamin Harris',
            'email' => 'benjamin.harris@example.com',
            'phone' => '+1 555-0112',
            'gender' => 'Male',
            'age' => 25,
            'plan' => 'Basic',
            'joined' => '2026-08-02',
            'expiry' => '2026-10-02',
            'status' => 'Suspended',
            'payment' => 'Pending',
            'sessions' => 2,
            'avatar' => 'BH'
        ]
    ];
}

/* CSRF protection for demonstration forms */
if (empty($_SESSION['fitcore_admin_members_csrf'])) {
    $_SESSION['fitcore_admin_members_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['fitcore_admin_members_csrf'];
$notice = '';
$noticeType = 'success';

/* Process member status changes */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';

    if (!is_string($postedToken) || !hash_equals($csrfToken, $postedToken)) {
        $notice = 'Your session token is invalid. Please refresh the page and try again.';
        $noticeType = 'danger';
    } else {
        $action = $_POST['action'] ?? '';
        $memberId = $_POST['member_id'] ?? '';

        if (!is_string($memberId)) {
            $memberId = '';
        }

        $memberIndex = null;

        foreach ($_SESSION['fitcore_admin_members'] as $index => $member) {
            if ($member['id'] === $memberId) {
                $memberIndex = $index;
                break;
            }
        }

        if ($memberIndex === null) {
            $notice = 'The selected member could not be found.';
            $noticeType = 'danger';
        } elseif ($action === 'toggle_status') {
            $currentStatus = $_SESSION['fitcore_admin_members'][$memberIndex]['status'];

            if ($currentStatus === 'Active') {
                $_SESSION['fitcore_admin_members'][$memberIndex]['status'] = 'Suspended';
                $notice = 'Member account suspended successfully in this demo.';
            } elseif ($currentStatus === 'Suspended') {
                $_SESSION['fitcore_admin_members'][$memberIndex]['status'] = 'Active';
                $notice = 'Member account reactivated successfully in this demo.';
            } else {
                $notice = 'Only active or suspended accounts can be toggled.';
                $noticeType = 'warning';
            }
        } else {
            $notice = 'The requested action is not supported.';
            $noticeType = 'danger';
        }
    }
}

/* Show success feedback after POST without repeating the action */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $redirectQuery = [];

    if ($notice !== '') {
        $redirectQuery['notice'] = $notice;
        $redirectQuery['type'] = $noticeType;
    }

    $preserveSearch = $_POST['search'] ?? '';
    $preservePlan = $_POST['plan'] ?? '';
    $preserveStatus = $_POST['status'] ?? '';

    if (is_string($preserveSearch) && $preserveSearch !== '') {
        $redirectQuery['search'] = $preserveSearch;
    }

    if (is_string($preservePlan) && $preservePlan !== '') {
        $redirectQuery['plan'] = $preservePlan;
    }

    if (is_string($preserveStatus) && $preserveStatus !== '') {
        $redirectQuery['status'] = $preserveStatus;
    }

    $redirectUrl = 'members.php';

    if (!empty($redirectQuery)) {
        $redirectUrl .= '?' . http_build_query($redirectQuery);
    }

    header('Location: ' . $redirectUrl);
    exit;
}

/* Flash message */
if (isset($_GET['notice']) && is_string($_GET['notice'])) {
    $allowedNotices = [
        'Member account suspended successfully in this demo.',
        'Member account reactivated successfully in this demo.'
    ];

    if (in_array($_GET['notice'], $allowedNotices, true)) {
        $notice = $_GET['notice'];
        $noticeType = ($_GET['type'] ?? '') === 'warning' ? 'warning' : 'success';
    }
}

/* Search and filters */
$search = isset($_GET['search']) && is_string($_GET['search'])
    ? trim($_GET['search'])
    : '';

$selectedPlan = isset($_GET['plan']) && is_string($_GET['plan'])
    ? $_GET['plan']
    : 'All';

$selectedStatus = isset($_GET['status']) && is_string($_GET['status'])
    ? $_GET['status']
    : 'All';

$validPlans = ['All', 'Basic', 'Pro', 'Premium'];
$validStatuses = ['All', 'Active', 'Suspended', 'Expired'];

if (!in_array($selectedPlan, $validPlans, true)) {
    $selectedPlan = 'All';
}

if (!in_array($selectedStatus, $validStatuses, true)) {
    $selectedStatus = 'All';
}

$allMembers = $_SESSION['fitcore_admin_members'];

$filteredMembers = array_filter($allMembers, function ($member) use (
    $search,
    $selectedPlan,
    $selectedStatus
) {
    $matchesSearch = true;

    if ($search !== '') {
        $searchableText = implode(' ', [
            $member['id'],
            $member['name'],
            $member['email'],
            $member['phone']
        ]);

        $matchesSearch = stripos($searchableText, $search) !== false;
    }

    $matchesPlan = $selectedPlan === 'All'
        || $member['plan'] === $selectedPlan;

    $matchesStatus = $selectedStatus === 'All'
        || $member['status'] === $selectedStatus;

    return $matchesSearch && $matchesPlan && $matchesStatus;
});

$filteredMembers = array_values($filteredMembers);

/* Dashboard metrics */
$totalMembers = count($allMembers);

$activeMembers = count(array_filter($allMembers, function ($member) {
    return $member['status'] === 'Active';
}));

$suspendedMembers = count(array_filter($allMembers, function ($member) {
    return $member['status'] === 'Suspended';
}));

$expiredMembers = count(array_filter($allMembers, function ($member) {
    return $member['status'] === 'Expired';
}));

$planCounts = [
    'Basic' => 0,
    'Pro' => 0,
    'Premium' => 0
];

foreach ($allMembers as $member) {
    if (isset($planCounts[$member['plan']])) {
        $planCounts[$member['plan']]++;
    }
}

/* Member detail modal record */
$selectedMember = null;
$detailId = isset($_GET['view']) && is_string($_GET['view'])
    ? $_GET['view']
    : '';

if ($detailId !== '') {
    foreach ($allMembers as $member) {
        if ($member['id'] === $detailId) {
            $selectedMember = $member;
            break;
        }
    }
}

function memberStatusClass($status)
{
    return match ($status) {
        'Active' => 'status-active',
        'Suspended' => 'status-suspended',
        'Expired' => 'status-expired',
        default => 'status-neutral'
    };
}

function paymentStatusClass($status)
{
    return match ($status) {
        'Paid' => 'payment-paid',
        'Pending' => 'payment-pending',
        'Failed' => 'payment-failed',
        default => 'payment-neutral'
    };
}

function planClass($plan)
{
    return match ($plan) {
        'Basic' => 'plan-basic',
        'Pro' => 'plan-pro',
        'Premium' => 'plan-premium',
        default => 'plan-basic'
    };
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Members | FitCore Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />



    <style>
        :root {
            --fc-bg: #101010;
            --fc-sidebar: #151515;
            --fc-card: #191919;
            --fc-hover: #202020;
            --fc-border: #2b2b2b;
            --fc-orange: #ff6b2c;
            --fc-orange-soft: rgba(255, 107, 44, .13);
            --fc-text: #f5f5f5;
            --fc-muted: #999;
            --fc-green: #32c48d;
            --fc-blue: #59a6ff;
            --fc-purple: #b18aff;
            --fc-red: #ff7777;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--fc-bg);
            color: var(--fc-text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 14px;
        }

        a { text-decoration: none; }

        .sidebar {
            width: 255px;
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            background: var(--fc-sidebar);
            border-right: 1px solid var(--fc-border);
            transition: transform .25s ease;
        }

        .brand-area {
            padding: 27px 23px 24px;
            border-bottom: 1px solid var(--fc-border);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }

        .brand-mark {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--fc-orange);
            color: #111;
            border-radius: 13px;
            font-size: 21px;
        }

        .brand-name {
            font-size: 23px;
            font-weight: 800;
        }

        .brand-name span { color: var(--fc-orange); }

        .brand-subtitle {
            color: var(--fc-muted);
            font-size: 10px;
            letter-spacing: 2px;
            margin-top: 3px;
            text-transform: uppercase;
        }

        .sidebar-label {
            padding: 23px 23px 10px;
            color: #6e6e6e;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.7px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 0 12px;
            overflow-y: auto;
            flex: 1;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #a8a8a8;
            padding: 12px 14px;
            margin-bottom: 5px;
            border-radius: 9px;
            font-size: 13px;
            transition: .2s;
        }

        .nav-link-custom i {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }

        .nav-link-custom:hover {
            color: white;
            background: #222;
        }

        .nav-link-custom.active {
            color: var(--fc-orange);
            background: var(--fc-orange-soft);
            font-weight: 700;
        }

        .sidebar-bottom {
            padding: 16px 18px 20px;
            border-top: 1px solid var(--fc-border);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 7px 15px;
        }

        .admin-avatar {
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #3b281f;
            border: 1px solid #60402e;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fc-orange);
            font-weight: 800;
        }

        .admin-profile-name {
            font-size: 12px;
            font-weight: 700;
        }

        .admin-profile-role {
            color: var(--fc-muted);
            font-size: 10px;
            margin-top: 3px;
        }

        .sidebar-footer-link {
            color: #a0a0a0;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 8px;
            font-size: 12px;
        }

        .sidebar-footer-link:hover { color: var(--fc-orange); }

        .main-content {
            margin-left: 255px;
            min-height: 100vh;
        }

        .topbar {
            min-height: 83px;
            padding: 17px 31px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            border-bottom: 1px solid var(--fc-border);
            background: #131313;
        }

        .topbar-title {
            font-size: 20px;
            font-weight: 800;
            margin: 0;
        }

        .breadcrumb-text {
            color: var(--fc-muted);
            font-size: 11px;
            margin-top: 5px;
        }

        .breadcrumb-text span { color: var(--fc-orange); }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .icon-button {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--fc-border);
            border-radius: 10px;
            background: #1a1a1a;
            color: #ddd;
        }

        .icon-button:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .mobile-menu-btn { display: none; }

        .page-content {
            max-width: 1800px;
            margin: 0 auto;
            padding: 30px;
        }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: clamp(23px, 2vw, 29px);
            font-weight: 800;
            margin-bottom: 8px;
        }

        .page-title span { color: var(--fc-orange); }

        .page-description {
            color: var(--fc-muted);
            font-size: 12px;
            line-height: 1.7;
            margin: 0;
        }

        .demo-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #ffbd9f;
            background: rgba(255, 107, 44, .09);
            border: 1px solid rgba(255, 107, 44, .22);
            border-radius: 6px;
            padding: 7px 9px;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .4px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 23px;
        }

        .stat-card {
            padding: 20px;
            border: 1px solid var(--fc-border);
            background: var(--fc-card);
            border-radius: 13px;
            min-width: 0;
            transition: .2s;
        }

        .stat-card:hover {
            background: var(--fc-hover);
            transform: translateY(-2px);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 17px;
        }

        .stat-label {
            color: #b2b2b2;
            font-size: 11px;
        }

        .stat-icon {
            width: 39px;
            height: 39px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .icon-orange { color: var(--fc-orange); background: var(--fc-orange-soft); }
        .icon-green { color: var(--fc-green); background: rgba(50, 196, 141, .12); }
        .icon-blue { color: var(--fc-blue); background: rgba(89, 166, 255, .12); }
        .icon-purple { color: var(--fc-purple); background: rgba(177, 138, 255, .12); }

        .stat-value {
            font-size: clamp(24px, 2vw, 30px);
            font-weight: 800;
            margin-bottom: 8px;
        }

        .stat-caption {
            color: var(--fc-muted);
            font-size: 10px;
        }

        .stat-caption strong { color: #ddd; }

        .panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 22px;
            margin-bottom: 22px;
        }

        .panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 15px;
            font-weight: 800;
            margin: 0;
        }

        .panel-subtitle {
            color: var(--fc-muted);
            font-size: 10px;
            margin-top: 6px;
        }

        .search-filter-form {
            display: grid;
            grid-template-columns: minmax(220px, 1.5fr) minmax(130px, .7fr) minmax(140px, .8fr) auto auto;
            gap: 10px;
            align-items: end;
        }

        .form-label-custom {
            display: block;
            color: #b7b7b7;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control-custom,
        .form-select-custom {
            width: 100%;
            min-height: 42px;
            border: 1px solid #363636;
            border-radius: 8px;
            padding: 10px 12px;
            color: #eee;
            background: #202020;
            outline: none;
            font-family: inherit;
            font-size: 11px;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 3px rgba(255, 107, 44, .1);
        }

        .form-control-custom::placeholder { color: #777; }

        .btn-orange {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            background: var(--fc-orange);
            color: #151515;
            border: 1px solid var(--fc-orange);
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 800;
            transition: .2s;
            white-space: nowrap;
            cursor: pointer;
        }

        .btn-orange:hover {
            background: #ff824d;
            border-color: #ff824d;
            color: #111;
        }

        .btn-outline-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            color: #dedede;
            background: transparent;
            border: 1px solid #383838;
            padding: 9px 13px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-outline-custom:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .alert-custom {
            border-radius: 9px;
            padding: 13px 15px;
            font-size: 11px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-success-custom {
            color: #9de6c8;
            background: rgba(50, 196, 141, .09);
            border-color: rgba(50, 196, 141, .25);
        }

        .alert-danger-custom {
            color: #ffb4b4;
            background: rgba(255, 100, 100, .09);
            border-color: rgba(255, 100, 100, .25);
        }

        .alert-warning-custom {
            color: #ffda91;
            background: rgba(244, 189, 85, .09);
            border-color: rgba(244, 189, 85, .25);
        }

        .table-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .results-count {
            color: var(--fc-muted);
            font-size: 11px;
        }

        .results-count strong { color: #eee; }

        .table-responsive {
            border: 1px solid #2e2e2e;
            border-radius: 9px;
        }

        .custom-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            color: var(--fc-text);
            vertical-align: middle;
        }

        .custom-table th {
            color: #858585;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .7px;
            text-transform: uppercase;
            padding: 14px 12px;
            border-bottom: 1px solid #333;
            white-space: nowrap;
            background: #1b1b1b;
        }

        .custom-table td {
            color: #cfcfcf;
            font-size: 11px;
            padding: 13px 12px;
            border-bottom: 1px solid #292929;
            white-space: nowrap;
        }

        .custom-table tbody tr:last-child td { border-bottom: none; }
        .custom-table tbody tr:hover { background: #202020; }

        .member-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 180px;
        }

        .member-avatar {
            width: 37px;
            height: 37px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #30251f;
            color: var(--fc-orange);
            font-size: 10px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .member-name {
            color: #f2f2f2;
            font-size: 11px;
            font-weight: 700;
        }

        .member-id {
            color: #858585;
            font-size: 9px;
            margin-top: 4px;
        }

        .plan-badge,
        .status-badge,
        .payment-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 800;
        }

        .plan-basic { color: #c9c9c9; background: rgba(160, 160, 160, .12); }
        .plan-pro { color: #ff9c73; background: rgba(255, 107, 44, .12); }
        .plan-premium { color: #c9adff; background: rgba(177, 138, 255, .12); }

        .status-badge::before,
        .payment-badge::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-active,
        .payment-paid { color: var(--fc-green); background: rgba(50, 196, 141, .11); }
        .status-suspended,
        .payment-pending { color: #f4bd55; background: rgba(244, 189, 85, .11); }
        .status-expired,
        .payment-failed { color: var(--fc-red); background: rgba(255, 119, 119, .11); }
        .status-neutral,
        .payment-neutral { color: #bbb; background: rgba(180, 180, 180, .1); }

        .action-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #363636;
            border-radius: 7px;
            background: #202020;
            color: #ccc;
            cursor: pointer;
            font-size: 10px;
        }

        .action-btn:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .action-btn.status-action:hover {
            color: var(--fc-green);
            border-color: var(--fc-green);
        }

        .empty-state {
            text-align: center;
            padding: 45px 15px;
            color: var(--fc-muted);
        }

        .empty-state i {
            display: block;
            font-size: 30px;
            color: #555;
            margin-bottom: 14px;
        }

        .empty-state h4 {
            color: #ddd;
            font-size: 15px;
            margin-bottom: 7px;
        }

        .table-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
            color: var(--fc-muted);
            font-size: 10px;
            flex-wrap: wrap;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .info-item {
            padding: 13px;
            background: #202020;
            border: 1px solid #303030;
            border-radius: 8px;
            min-width: 0;
        }

        .info-label {
            color: var(--fc-muted);
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 7px;
        }

        .info-value {
            color: #f2f2f2;
            font-size: 12px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .modal-content {
            color: var(--fc-text);
            background: #191919;
            border: 1px solid #333;
            border-radius: 13px;
        }

        .modal-header,
        .modal-footer {
            border-color: #333;
        }

        .modal-title { font-size: 16px; font-weight: 800; }
        .btn-close { filter: invert(1); }

        .modal-body { padding: 22px; }

        .modal-member-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 22px;
        }

        .modal-member-header .member-avatar {
            width: 53px;
            height: 53px;
            font-size: 15px;
        }

        .modal-member-header h4 {
            font-size: 17px;
            margin-bottom: 5px;
        }

        .modal-member-header p {
            color: var(--fc-muted);
            font-size: 11px;
            margin: 0;
        }

        .dashboard-footer {
            padding: 20px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-top: 1px solid var(--fc-border);
            color: #777;
            font-size: 10px;
        }

        .dashboard-footer strong { color: var(--fc-orange); }

        .sidebar-overlay { display: none; }

        @media (max-width: 1300px) {
            .search-filter-form {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .search-filter-form .search-field {
                grid-column: 1 / -1;
            }

            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .page-content { padding: 23px; }
        }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 1040;
                background: rgba(0, 0, 0, .65);
            }

            .main-content { margin-left: 0; }
            .mobile-menu-btn { display: inline-flex; }
            .topbar { padding: 16px 22px; }
        }

        @media (max-width: 576px) {
            .topbar { padding: 14px 15px; }
            .topbar-title { font-size: 17px; }
            .topbar-right { gap: 8px; }
            .page-content { padding: 19px 13px; }

            .page-heading {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-grid { gap: 10px; }

            .stat-card { padding: 14px; }
            .stat-icon { width: 33px; height: 33px; }
            .stat-value { font-size: 24px; }
            .stat-label { font-size: 10px; }

            .panel { padding: 16px 12px; }

            .search-filter-form {
                grid-template-columns: 1fr;
            }

            .search-filter-form .search-field {
                grid-column: auto;
            }

            .search-filter-form .filter-button-row {
                display: flex;
                gap: 8px;
            }

            .search-filter-form .filter-button-row > * {
                flex: 1;
            }

            .table-bottom {
                flex-direction: column;
                align-items: flex-start;
            }

            .dashboard-footer {
                padding: 17px 14px;
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="admin-layout">

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="adminSidebar">

        <div class="brand-area">
            <a href="index.php" class="brand">
                <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
                <div>
                    <div class="brand-name">Fit<span>Core</span></div>
                    <div class="brand-subtitle">Administrator Panel</div>
                </div>
            </a>
        </div>

        <div class="sidebar-label">Main Menu</div>

        <nav class="sidebar-nav">
            <a href="index.php" class="nav-link-custom">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>

            <a href="members.php" class="nav-link-custom active">
                <i class="fas fa-users"></i>
                <span>Manage Members</span>
            </a>

            <a href="trainers.php" class="nav-link-custom">
                <i class="fas fa-user-tie"></i>
                <span>Manage Trainers</span>
            </a>

            <a href="memberships.php" class="nav-link-custom">
                <i class="fas fa-id-card"></i>
                <span>Membership Plans</span>
            </a>

            <a href="payments.php" class="nav-link-custom">
                <i class="fas fa-wallet"></i>
                <span>Manage Payments</span>
            </a>

            <div class="sidebar-label">Fitness Management</div>

            <a href="classes.php" class="nav-link-custom">
                <i class="fas fa-calendar-alt"></i>
                <span>Manage Classes</span>
            </a>

            <a href="attendance.php" class="nav-link-custom">
                <i class="fas fa-clipboard-check"></i>
                <span>Manage Attendance</span>
            </a>

            <div class="sidebar-label">Analytics</div>

            <a href="reports.php" class="nav-link-custom">
                <i class="fas fa-chart-line"></i>
                <span>Generate Reports</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="admin-profile">
                <div class="admin-avatar">MA</div>
                <div>
                    <div class="admin-profile-name">Michael Anderson</div>
                    <div class="admin-profile-role">System Administrator</div>
                </div>
            </div>

            <a href="../fitcore_demo.php" class="sidebar-footer-link">
                <i class="fas fa-exchange-alt"></i>
                <span>Switch Demo Role</span>
            </a>

            <a href="../index.php" class="sidebar-footer-link">
                <i class="fas fa-external-link-alt"></i>
                <span>Back to Codvanta</span>
            </a>
        </div>

    </aside>

    <!-- Main content -->
    <main class="main-content">

        <header class="topbar">

            <div class="d-flex align-items-center gap-3">
                <button
                    type="button"
                    class="icon-button mobile-menu-btn"
                    id="mobileMenuBtn"
                    aria-label="Open navigation menu"
                >
                    <i class="fas fa-bars"></i>
                </button>

                <div>
                    <h1 class="topbar-title">Manage Members</h1>
                    <div class="breadcrumb-text">
                        FitCore <span>/</span> Members
                    </div>
                </div>
            </div>

            <div class="topbar-right">
                <span class="demo-label">
                    <i class="fas fa-circle"></i>
                    DEMO MODE
                </span>

                <div class="admin-avatar">MA</div>
            </div>

        </header>

        <div class="page-content">

            <!-- Page heading -->
            <section class="page-heading">
                <div>
                    <div class="demo-label mb-3">
                        <i class="fas fa-users"></i>
                        MEMBER MANAGEMENT
                    </div>

                    <h2 class="page-title">
                        Gym <span>Members</span>
                    </h2>

                    <p class="page-description">
                        View member profiles, monitor memberships, and manage account status.
                        All changes on this page are demonstration-only.
                    </p>
                </div>

                <button
                    type="button"
                    class="btn-orange"
                    onclick="showAddMemberNotice()"
                >
                    <i class="fas fa-user-plus"></i>
                    Add New Member
                </button>
            </section>

            <!-- Flash message -->
            <?php if ($notice !== ''): ?>
                <div class="alert-custom
                    <?= $noticeType === 'danger'
                        ? 'alert-danger-custom'
                        : ($noticeType === 'warning'
                            ? 'alert-warning-custom'
                            : 'alert-success-custom'); ?>"
                    role="status"
                >
                    <i class="fas
                        <?= $noticeType === 'danger'
                            ? 'fa-exclamation-circle'
                            : ($noticeType === 'warning'
                                ? 'fa-exclamation-triangle'
                                : 'fa-check-circle'); ?>
                        me-2"></i>
                    <?= e($notice); ?>
                </div>
            <?php endif; ?>

            <!-- Summary cards -->
            <section class="stats-grid">

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Total Members</div>
                        <div class="stat-icon icon-orange">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= number_format($totalMembers); ?></div>
                    <div class="stat-caption">All registered demo members</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Active Members</div>
                        <div class="stat-icon icon-green">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= number_format($activeMembers); ?></div>
                    <div class="stat-caption">
                        <strong>Active</strong> accounts
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Suspended Members</div>
                        <div class="stat-icon icon-blue">
                            <i class="fas fa-user-lock"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= number_format($suspendedMembers); ?></div>
                    <div class="stat-caption">Accounts temporarily suspended</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Expired Memberships</div>
                        <div class="stat-icon icon-purple">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= number_format($expiredMembers); ?></div>
                    <div class="stat-caption">Memberships requiring renewal</div>
                </div>

            </section>

            <!-- Search and filters -->
            <section class="panel">

                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Search and Filter Members</h3>
                        <div class="panel-subtitle">
                            Find a member by name, ID, email address, or phone number.
                        </div>
                    </div>
                </div>

                <form method="GET" action="members.php" class="search-filter-form">

                    <div class="search-field">
                        <label for="search" class="form-label-custom">
                            Search Members
                        </label>

                        <input
                            type="search"
                            name="search"
                            id="search"
                            class="form-control-custom"
                            placeholder="Search name, ID, email or phone..."
                            value="<?= e($search); ?>"
                        >
                    </div>

                    <div>
                        <label for="plan" class="form-label-custom">
                            Membership Plan
                        </label>

                        <select name="plan" id="plan" class="form-select-custom">
                            <?php foreach ($validPlans as $plan): ?>
                                <option
                                    value="<?= e($plan); ?>"
                                    <?= $selectedPlan === $plan ? 'selected' : ''; ?>
                                >
                                    <?= e($plan === 'All' ? 'All Plans' : $plan); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="form-label-custom">
                            Account Status
                        </label>

                        <select name="status" id="status" class="form-select-custom">
                            <?php foreach ($validStatuses as $status): ?>
                                <option
                                    value="<?= e($status); ?>"
                                    <?= $selectedStatus === $status ? 'selected' : ''; ?>
                                >
                                    <?= e($status === 'All' ? 'All Statuses' : $status); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-button-row">
                        <button type="submit" class="btn-orange">
                            <i class="fas fa-search"></i>
                            Search
                        </button>
                    </div>

                    <div class="filter-button-row">
                        <a href="members.php" class="btn-outline-custom">
                            <i class="fas fa-redo"></i>
                            Reset
                        </a>
                    </div>

                </form>

            </section>

            <!-- Members table -->
            <section class="panel">

                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Member Directory</h3>
                        <div class="panel-subtitle">
                            Member information and membership status
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn-outline-custom"
                        onclick="window.print()"
                    >
                        <i class="fas fa-print"></i>
                        Print List
                    </button>
                </div>

                <div class="table-toolbar">
                    <div class="results-count">
                        Showing <strong><?= count($filteredMembers); ?></strong>
                        of <strong><?= $totalMembers; ?></strong> members
                    </div>

                    <div style="font-size:10px;color:var(--fc-muted);">
                        <i class="fas fa-database me-1"></i>
                        Sample records
                    </div>
                </div>

                <?php if (count($filteredMembers) > 0): ?>

                    <div class="table-responsive">

                        <table class="custom-table">

                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Contact</th>
                                    <th>Membership</th>
                                    <th>Joined Date</th>
                                    <th>Expiry Date</th>
                                    <th>Payment</th>
                                    <th>Account Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($filteredMembers as $member): ?>

                                    <tr>

                                        <td>
                                            <div class="member-cell">
                                                <div class="member-avatar">
                                                    <?= e($member['avatar']); ?>
                                                </div>

                                                <div>
                                                    <div class="member-name">
                                                        <?= e($member['name']); ?>
                                                    </div>

                                                    <div class="member-id">
                                                        <?= e($member['id']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div><?= e($member['email']); ?></div>
                                            <div style="font-size:9px;color:#888;margin-top:5px;">
                                                <?= e($member['phone']); ?>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="plan-badge <?= e(planClass($member['plan'])); ?>">
                                                <?= e($member['plan']); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= e(date('M d, Y', strtotime($member['joined']))); ?>
                                        </td>

                                        <td>
                                            <?= e(date('M d, Y', strtotime($member['expiry']))); ?>
                                        </td>

                                        <td>
                                            <span class="payment-badge <?= e(paymentStatusClass($member['payment'])); ?>">
                                                <?= e($member['payment']); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="status-badge <?= e(memberStatusClass($member['status'])); ?>">
                                                <?= e($member['status']); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="action-group">

                                                <a
                                                    class="action-btn"
                                                    href="members.php?<?= e(http_build_query([
                                                        'view' => $member['id'],
                                                        'search' => $search,
                                                        'plan' => $selectedPlan,
                                                        'status' => $selectedStatus
                                                    ])); ?>"
                                                    title="View member details"
                                                    aria-label="View member details"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <?php if (in_array($member['status'], ['Active', 'Suspended'], true)): ?>

                                                    <form
                                                        method="POST"
                                                        action="members.php"
                                                        class="m-0"
                                                        onsubmit="return confirmStatusChange('<?= e($member['name']); ?>', '<?= e($member['status']); ?>');"
                                                    >
                                                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken); ?>">
                                                        <input type="hidden" name="action" value="toggle_status">
                                                        <input type="hidden" name="member_id" value="<?= e($member['id']); ?>">
                                                        <input type="hidden" name="search" value="<?= e($search); ?>">
                                                        <input type="hidden" name="plan" value="<?= e($selectedPlan); ?>">
                                                        <input type="hidden" name="status" value="<?= e($selectedStatus); ?>">

                                                        <button
                                                            type="submit"
                                                            class="action-btn status-action"
                                                            title="<?= $member['status'] === 'Active' ? 'Suspend account' : 'Reactivate account'; ?>"
                                                            aria-label="<?= $member['status'] === 'Active' ? 'Suspend account' : 'Reactivate account'; ?>"
                                                        >
                                                            <i class="fas <?= $member['status'] === 'Active' ? 'fa-user-lock' : 'fa-user-check'; ?>"></i>
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
                        <i class="fas fa-search"></i>
                        <h4>No members found</h4>
                        <p class="mb-3">
                            Try changing your search term or filters.
                        </p>
                        <a href="members.php" class="btn-orange">
                            <i class="fas fa-redo"></i>
                            Show All Members
                        </a>
                    </div>

                <?php endif; ?>

                <div class="table-bottom">
                    <span>
                        <i class="fas fa-info-circle me-1"></i>
                        Account status changes are saved for this PHP session only.
                    </span>

                    <span>
                        <?= count($filteredMembers); ?> result(s)
                    </span>
                </div>

            </section>

            <!-- Membership plan summary -->
            <section class="panel">

                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Membership Plan Summary</h3>
                        <div class="panel-subtitle">
                            Distribution of plans among the sample member records
                        </div>
                    </div>

                    <a href="memberships.php" class="btn-outline-custom">
                        Manage Plans
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="row g-3">

                    <?php
                    $planPrices = [
                        'Basic' => 20,
                        'Pro' => 40,
                        'Premium' => 70
                    ];

                    $planDescriptions = [
                        'Basic' => 'Essential gym access',
                        'Pro' => 'Enhanced fitness access',
                        'Premium' => 'Premium membership benefits'
                    ];

                    foreach ($planCounts as $planName => $count):
                    ?>

                        <div class="col-md-4">

                            <div style="height:100%;padding:18px;border:1px solid #303030;background:#202020;border-radius:10px;">

                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <span class="plan-badge <?= e(planClass($planName)); ?>">
                                        <?= e($planName); ?> Plan
                                    </span>

                                    <i class="fas fa-id-card" style="color:var(--fc-orange);"></i>
                                </div>

                                <div style="font-size:27px;font-weight:800;">
                                    $<?= number_format($planPrices[$planName]); ?>
                                    <span style="font-size:10px;color:var(--fc-muted);font-weight:400;">
                                        / month
                                    </span>
                                </div>

                                <div style="color:var(--fc-muted);font-size:10px;margin-top:6px;">
                                    <?= e($planDescriptions[$planName]); ?>
                                </div>

                                <div style="border-top:1px solid #363636;margin-top:17px;padding-top:13px;display:flex;justify-content:space-between;gap:8px;font-size:11px;">
                                    <span style="color:var(--fc-muted);">Sample members</span>
                                    <strong><?= number_format($count); ?></strong>
                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </section>

        </div>

        <footer class="dashboard-footer">
            <div>
                © <?= date('Y'); ?> <strong>FitCore</strong>.
                Administrator demonstration by Codvanta.
            </div>

            <div>
                <i class="fas fa-shield-alt me-1"></i>
                Portfolio Demo · Sample Data Only
            </div>
        </footer>

    </main>

</div>

<!-- Member details modal -->
<?php if ($selectedMember !== null): ?>

    <div
        class="modal fade"
        id="memberDetailsModal"
        tabindex="-1"
        aria-labelledby="memberDetailsModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="memberDetailsModalLabel">
                        <i class="fas fa-user-circle me-2" style="color:var(--fc-orange);"></i>
                        Member Details
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>

                <div class="modal-body">

                    <div class="modal-member-header">
                        <div class="member-avatar">
                            <?= e($selectedMember['avatar']); ?>
                        </div>

                        <div>
                            <h4><?= e($selectedMember['name']); ?></h4>

                            <p>
                                <?= e($selectedMember['id']); ?>
                                ·
                                <?= e($selectedMember['gender']); ?>,
                                <?= (int)$selectedMember['age']; ?> years old
                            </p>

                            <div class="mt-2">
                                <span class="status-badge <?= e(memberStatusClass($selectedMember['status'])); ?>">
                                    <?= e($selectedMember['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="info-grid">

                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value"><?= e($selectedMember['email']); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value"><?= e($selectedMember['phone']); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Membership Plan</div>
                            <div class="info-value"><?= e($selectedMember['plan']); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Monthly Plan Price</div>
                            <div class="info-value">
                                $<?= number_format($planPrices[$selectedMember['plan']] ?? 0, 2); ?>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Registration Date</div>
                            <div class="info-value">
                                <?= e(date('F d, Y', strtotime($selectedMember['joined']))); ?>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Membership Expiry</div>
                            <div class="info-value">
                                <?= e(date('F d, Y', strtotime($selectedMember['expiry']))); ?>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Latest Payment Status</div>
                            <div class="info-value">
                                <span class="payment-badge <?= e(paymentStatusClass($selectedMember['payment'])); ?>">
                                    <?= e($selectedMember['payment']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Recorded Sessions</div>
                            <div class="info-value">
                                <?= (int)$selectedMember['sessions']; ?> sessions
                            </div>
                        </div>

                    </div>

                    <div style="font-size:10px;color:var(--fc-muted);line-height:1.7;margin-top:18px;">
                        <i class="fas fa-info-circle me-1"></i>
                        These details are illustrative sample records for the FitCore portfolio demonstration.
                    </div>

                </div>

                <div class="modal-footer">
                    <a
                        href="members.php?<?= e(http_build_query([
                            'search' => $search,
                            'plan' => $selectedPlan,
                            'status' => $selectedStatus
                        ])); ?>"
                        class="btn-outline-custom"
                    >
                        Close
                    </a>

                    <?php if (in_array($selectedMember['status'], ['Active', 'Suspended'], true)): ?>

                        <form
                            method="POST"
                            action="members.php"
                            onsubmit="return confirmStatusChange('<?= e($selectedMember['name']); ?>', '<?= e($selectedMember['status']); ?>');"
                        >
                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken); ?>">
                            <input type="hidden" name="action" value="toggle_status">
                            <input type="hidden" name="member_id" value="<?= e($selectedMember['id']); ?>">
                            <input type="hidden" name="search" value="<?= e($search); ?>">
                            <input type="hidden" name="plan" value="<?= e($selectedPlan); ?>">
                            <input type="hidden" name="status" value="<?= e($selectedStatus); ?>">

                            <button type="submit" class="btn-orange">
                                <i class="fas <?= $selectedMember['status'] === 'Active' ? 'fa-user-lock' : 'fa-user-check'; ?>"></i>

                                <?= $selectedMember['status'] === 'Active'
                                    ? 'Suspend Member'
                                    : 'Reactivate Member'; ?>
                            </button>
                        </form>

                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    /* Mobile sidebar */
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuButton = document.getElementById('mobileMenuBtn');

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    }

    menuButton?.addEventListener('click', function () {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', closeSidebar);

    document.querySelectorAll('.nav-link-custom').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 992) {
                closeSidebar();
            }
        });
    });

    /* Confirm account status changes */
    function confirmStatusChange(memberName, currentStatus) {
        const nextAction = currentStatus === 'Active'
            ? 'suspend'
            : 'reactivate';

        return confirm(
            'Are you sure you want to ' + nextAction +
            ' the account for ' + memberName + '?'
        );
    }

    /* Add member is intentionally a demonstration notice */
    function showAddMemberNotice() {
        alert(
            'Add New Member\n\n' +
            'This is a portfolio demonstration. The member list is populated ' +
            'with sample records, and new member registration is demonstrated ' +
            'through the customer registration page.'
        );
    }

    /* Open member detail modal automatically when ?view=... is present */
    <?php if ($selectedMember !== null): ?>
        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('memberDetailsModal');

            if (modalElement && window.bootstrap) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
    <?php endif; ?>
</script>

</body>
</html>