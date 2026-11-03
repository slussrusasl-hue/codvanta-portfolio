<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore Admin - Payment Management
|--------------------------------------------------------------------------
| Demonstration page using PHP sessions.
| No database connection or real payment gateway is used.
*/

if (!isset($_SESSION['fitcore_admin_payments'])) {
    $_SESSION['fitcore_admin_payments'] = [
        [
            'id' => 'FCP-2026-1001',
            'member_id' => 'FCM-1001',
            'member' => 'Olivia Bennett',
            'email' => 'olivia.bennett@example.com',
            'plan' => 'Premium',
            'amount' => 70.00,
            'method' => 'Credit Card',
            'status' => 'Completed',
            'date' => '2026-10-19 08:35:00',
            'reference' => 'CC-78451201',
            'description' => 'Premium monthly membership'
        ],
        [
            'id' => 'FCP-2026-1002',
            'member_id' => 'FCM-1002',
            'member' => 'Ethan Brooks',
            'email' => 'ethan.brooks@example.com',
            'plan' => 'Pro',
            'amount' => 40.00,
            'method' => 'Debit Card',
            'status' => 'Completed',
            'date' => '2026-10-19 09:10:00',
            'reference' => 'DC-78451202',
            'description' => 'Pro monthly membership'
        ],
        [
            'id' => 'FCP-2026-1003',
            'member_id' => 'FCM-1003',
            'member' => 'Sophia Mitchell',
            'email' => 'sophia.mitchell@example.com',
            'plan' => 'Basic',
            'amount' => 20.00,
            'method' => 'Cash',
            'status' => 'Pending',
            'date' => '2026-10-19 09:25:00',
            'reference' => 'CASH-1003',
            'description' => 'Basic monthly membership'
        ],
        [
            'id' => 'FCP-2026-1004',
            'member_id' => 'FCM-1004',
            'member' => 'Noah Wilson',
            'email' => 'noah.wilson@example.com',
            'plan' => 'Premium',
            'amount' => 70.00,
            'method' => 'Credit Card',
            'status' => 'Completed',
            'date' => '2026-10-18 14:20:00',
            'reference' => 'CC-78451204',
            'description' => 'Premium monthly membership'
        ],
        [
            'id' => 'FCP-2026-1005',
            'member_id' => 'FCM-1005',
            'member' => 'Ava Thompson',
            'email' => 'ava.thompson@example.com',
            'plan' => 'Pro',
            'amount' => 40.00,
            'method' => 'Bank Transfer',
            'status' => 'Pending',
            'date' => '2026-10-18 15:45:00',
            'reference' => 'BT-78451205',
            'description' => 'Pro monthly membership'
        ],
        [
            'id' => 'FCP-2026-1006',
            'member_id' => 'FCM-1006',
            'member' => 'Liam Anderson',
            'email' => 'liam.anderson@example.com',
            'plan' => 'Basic',
            'amount' => 20.00,
            'method' => 'Debit Card',
            'status' => 'Failed',
            'date' => '2026-10-18 11:15:00',
            'reference' => 'DC-78451206',
            'description' => 'Basic monthly membership'
        ],
        [
            'id' => 'FCP-2026-1007',
            'member_id' => 'FCM-1007',
            'member' => 'Isabella Clark',
            'email' => 'isabella.clark@example.com',
            'plan' => 'Premium',
            'amount' => 70.00,
            'method' => 'Credit Card',
            'status' => 'Completed',
            'date' => '2026-10-17 10:05:00',
            'reference' => 'CC-78451207',
            'description' => 'Premium monthly membership'
        ],
        [
            'id' => 'FCP-2026-1008',
            'member_id' => 'FCM-1008',
            'member' => 'James Parker',
            'email' => 'james.parker@example.com',
            'plan' => 'Pro',
            'amount' => 40.00,
            'method' => 'Cash',
            'status' => 'Completed',
            'date' => '2026-10-16 16:30:00',
            'reference' => 'CASH-1008',
            'description' => 'Pro monthly membership'
        ],
        [
            'id' => 'FCP-2026-1009',
            'member_id' => 'FCM-1009',
            'member' => 'Mia Robinson',
            'email' => 'mia.robinson@example.com',
            'plan' => 'Basic',
            'amount' => 20.00,
            'method' => 'Debit Card',
            'status' => 'Refunded',
            'date' => '2026-10-15 12:40:00',
            'reference' => 'RF-78451209',
            'description' => 'Refunded Basic membership payment'
        ],
        [
            'id' => 'FCP-2026-1010',
            'member_id' => 'FCM-1010',
            'member' => 'Lucas Martin',
            'email' => 'lucas.martin@example.com',
            'plan' => 'Premium',
            'amount' => 70.00,
            'method' => 'Bank Transfer',
            'status' => 'Failed',
            'date' => '2026-10-14 13:55:00',
            'reference' => 'BT-78451210',
            'description' => 'Premium monthly membership'
        ],
        [
            'id' => 'FCP-2026-1011',
            'member_id' => 'FCM-1011',
            'member' => 'Charlotte Davis',
            'email' => 'charlotte.davis@example.com',
            'plan' => 'Pro',
            'amount' => 40.00,
            'method' => 'Credit Card',
            'status' => 'Completed',
            'date' => '2026-10-13 09:00:00',
            'reference' => 'CC-78451211',
            'description' => 'Pro monthly membership'
        ],
        [
            'id' => 'FCP-2026-1012',
            'member_id' => 'FCM-1012',
            'member' => 'Benjamin Harris',
            'email' => 'benjamin.harris@example.com',
            'plan' => 'Basic',
            'amount' => 20.00,
            'method' => 'Cash',
            'status' => 'Pending',
            'date' => '2026-10-12 10:30:00',
            'reference' => 'CASH-1012',
            'description' => 'Basic monthly membership'
        ]
    ];
}

if (!isset($_SESSION['fitcore_payments_csrf'])) {
    $_SESSION['fitcore_payments_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['fitcore_payments_csrf'];

function paymentEscape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function paymentRedirect($message, $type = 'success')
{
    $_SESSION['fitcore_payments_notice'] = $message;
    $_SESSION['fitcore_payments_notice_type'] = $type;

    header('Location: payments.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Flash Message
|--------------------------------------------------------------------------
*/

$notice = '';
$noticeType = 'success';

if (isset($_SESSION['fitcore_payments_notice'])) {
    $notice = $_SESSION['fitcore_payments_notice'];
    $noticeType = $_SESSION['fitcore_payments_notice_type'] ?? 'success';

    unset(
        $_SESSION['fitcore_payments_notice'],
        $_SESSION['fitcore_payments_notice_type']
    );
}

/*
|--------------------------------------------------------------------------
| Update Demonstration Payment Status
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($submittedToken) ||
        !hash_equals($csrfToken, $submittedToken)
    ) {
        paymentRedirect(
            'Security verification failed. Please try again.',
            'danger'
        );
    }

    $action = $_POST['action'] ?? '';
    $paymentId = trim($_POST['payment_id'] ?? '');

    if ($action === 'update_status') {
        $newStatus = $_POST['new_status'] ?? '';

        if (!in_array($newStatus, ['Completed', 'Failed'], true)) {
            paymentRedirect('Invalid payment status selected.', 'danger');
        }

        $found = false;

        foreach ($_SESSION['fitcore_admin_payments'] as &$payment) {
            if ($payment['id'] === $paymentId) {
                if ($payment['status'] !== 'Pending') {
                    unset($payment);

                    paymentRedirect(
                        'Only pending payments can be updated in this demo.',
                        'warning'
                    );
                }

                $payment['status'] = $newStatus;
                $found = true;
                break;
            }
        }

        unset($payment);

        if ($found) {
            paymentRedirect(
                'Payment ' . $paymentId . ' marked as ' .
                strtolower($newStatus) . ' in this demo.'
            );
        }

        paymentRedirect('The selected payment could not be found.', 'danger');
    }

    paymentRedirect('Unknown action.', 'danger');
}

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

$payments = $_SESSION['fitcore_admin_payments'];

$totalTransactions = count($payments);
$completedCount = 0;
$pendingCount = 0;
$failedCount = 0;
$refundedCount = 0;
$completedRevenue = 0.00;
$pendingAmount = 0.00;
$failedAmount = 0.00;

foreach ($payments as $payment) {
    switch ($payment['status']) {
        case 'Completed':
            $completedCount++;
            $completedRevenue += (float)$payment['amount'];
            break;

        case 'Pending':
            $pendingCount++;
            $pendingAmount += (float)$payment['amount'];
            break;

        case 'Failed':
            $failedCount++;
            $failedAmount += (float)$payment['amount'];
            break;

        case 'Refunded':
            $refundedCount++;
            break;
    }
}

/*
|--------------------------------------------------------------------------
| Search and Filters
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$methodFilter = trim($_GET['method'] ?? '');

$allowedStatuses = ['Completed', 'Pending', 'Failed', 'Refunded'];
$allowedMethods = ['Credit Card', 'Debit Card', 'Bank Transfer', 'Cash'];

if (!in_array($statusFilter, $allowedStatuses, true)) {
    $statusFilter = '';
}

if (!in_array($methodFilter, $allowedMethods, true)) {
    $methodFilter = '';
}

$filteredPayments = array_filter(
    $payments,
    function ($payment) use ($search, $statusFilter, $methodFilter) {
        $searchable = implode(' ', [
            $payment['id'],
            $payment['member_id'],
            $payment['member'],
            $payment['email'],
            $payment['plan'],
            $payment['reference']
        ]);

        $matchesSearch = $search === '' ||
            stripos($searchable, $search) !== false;

        $matchesStatus = $statusFilter === '' ||
            $payment['status'] === $statusFilter;

        $matchesMethod = $methodFilter === '' ||
            $payment['method'] === $methodFilter;

        return $matchesSearch && $matchesStatus && $matchesMethod;
    }
);

/*
|--------------------------------------------------------------------------
| Helpers for Display
|--------------------------------------------------------------------------
*/

function paymentStatusClass($status)
{
    switch ($status) {
        case 'Completed':
            return 'status-completed';

        case 'Pending':
            return 'status-pending';

        case 'Failed':
            return 'status-failed';

        case 'Refunded':
            return 'status-refunded';

        default:
            return 'status-pending';
    }
}

function paymentMethodIcon($method)
{
    switch ($method) {
        case 'Credit Card':
            return 'fa-credit-card';

        case 'Debit Card':
            return 'fa-credit-card';

        case 'Bank Transfer':
            return 'fa-university';

        case 'Cash':
            return 'fa-money-bill-wave';

        default:
            return 'fa-wallet';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment Management | FitCore Admin</title>

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
            --fc-yellow: #ffc857;
            --fc-red: #ff6868;
            --fc-blue: #73a9ff;
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
            color: var(--fc-text);
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
            gap: 8px;
            margin-bottom: 18px;
        }

        .stat-label {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 107, 44, .13);
            color: var(--fc-orange);
            border-radius: 11px;
            font-size: 18px;
        }

        .stat-icon.green {
            background: rgba(49, 196, 141, .12);
            color: var(--fc-green);
        }

        .stat-icon.yellow {
            background: rgba(255, 200, 87, .12);
            color: var(--fc-yellow);
        }

        .stat-icon.red {
            background: rgba(255, 104, 104, .12);
            color: var(--fc-red);
        }

        .stat-value {
            font-size: 27px;
            font-weight: 800;
            line-height: 1.2;
            overflow-wrap: anywhere;
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

        .btn-orange {
            border: 1px solid var(--fc-orange);
            background: var(--fc-orange);
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 12px;
        }

        .btn-orange:hover {
            background: #ed5a1d;
            border-color: #ed5a1d;
            color: #fff;
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

        .table {
            margin-bottom: 0;
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

        .transaction-id {
            display: block;
            color: var(--fc-orange);
            font-size: 11px;
            font-weight: 800;
        }

        .transaction-date {
            display: block;
            margin-top: 5px;
            color: var(--fc-muted);
            font-size: 10px;
        }

        .member-name {
            display: block;
            font-weight: 700;
        }

        .member-email {
            display: block;
            margin-top: 4px;
            color: var(--fc-muted);
            font-size: 10px;
            overflow-wrap: anywhere;
        }

        .plan-label {
            display: inline-block;
            padding: 5px 8px;
            background: #2a2b33;
            border: 1px solid #393a44;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
        }

        .payment-method {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .method-icon {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #282930;
            border: 1px solid #393a44;
            border-radius: 8px;
            color: #d1d1d9;
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

        .status-completed {
            background: rgba(49, 196, 141, .12);
            color: #4de0a9;
        }

        .status-pending {
            background: rgba(255, 200, 87, .12);
            color: #ffd16e;
        }

        .status-failed {
            background: rgba(255, 104, 104, .12);
            color: #ff8585;
        }

        .status-refunded {
            background: rgba(115, 169, 255, .12);
            color: #91bbff;
        }

        .icon-btn {
            width: 33px;
            height: 33px;
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

        .empty-state {
            padding: 38px 12px !important;
            text-align: center;
            color: var(--fc-muted);
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

        .detail-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid var(--fc-border);
            font-size: 12px;
        }

        .detail-row:last-child {
            border-bottom: 0;
        }

        .detail-label {
            color: var(--fc-muted);
        }

        .detail-value {
            max-width: 65%;
            text-align: right;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .notice-box {
            padding: 14px;
            background: rgba(255, 107, 44, .08);
            border: 1px solid rgba(255, 107, 44, .2);
            border-radius: 10px;
            color: #d9d9df;
            font-size: 11px;
            line-height: 1.7;
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
                padding: 15px;
            }
        }

        @media print {
            .sidebar,
            .topbar .admin-profile,
            .filter-section,
            .action-column,
            .modal,
            .no-print,
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
            .section-card {
                background: #fff;
                color: #111;
                border: 1px solid #ccc;
            }

            .table {
                --bs-table-color: #111;
                --bs-table-border-color: #ccc;
            }

            .table thead th {
                background: #eee;
                color: #111;
            }
        }
    </style>
</head>

<body>

<div class="admin-layout">

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="index.php" class="brand">
            <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
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

            <a href="memberships.php" class="sidebar-link">
                <i class="fas fa-id-card"></i> Memberships
            </a>

            <a href="payments.php" class="sidebar-link active">
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
                <h2 class="page-title">Payment Management</h2>
                <p class="page-subtitle">
                    Monitor transactions, review payment statuses and track membership revenue.
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

                <button type="button" class="btn btn-orange no-print"
                        onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print Report
                </button>
            </div>
        </header>

        <?php if ($notice !== ''): ?>
            <div class="alert alert-<?php echo paymentEscape($noticeType); ?> alert-dismissible fade show"
                 role="alert">
                <?php echo paymentEscape($notice); ?>
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Completed Revenue</span>
                        <div class="stat-icon green">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>

                    <div class="stat-value">
                        $<?php echo number_format($completedRevenue, 2); ?>
                    </div>

                    <div class="stat-note">
                        From <?php echo $completedCount; ?> completed demo payments
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Total Transactions</span>
                        <div class="stat-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>

                    <div class="stat-value"><?php echo $totalTransactions; ?></div>

                    <div class="stat-note">
                        All sample payment records
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Pending Payments</span>
                        <div class="stat-icon yellow">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>

                    <div class="stat-value"><?php echo $pendingCount; ?></div>

                    <div class="stat-note">
                        $<?php echo number_format($pendingAmount, 2); ?> awaiting review
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Failed Payments</span>
                        <div class="stat-icon red">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>

                    <div class="stat-value"><?php echo $failedCount; ?></div>

                    <div class="stat-note">
                        $<?php echo number_format($failedAmount, 2); ?> in failed attempts
                    </div>
                </div>
            </div>

        </div>

        <!-- Payment Status Overview -->
        <section class="section-card mb-4">
            <div class="mb-3">
                <h3 class="section-heading">Transaction Overview</h3>
                <p class="section-description">
                    Summary of payment records by transaction status.
                </p>
            </div>

            <div class="row g-3">

                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon green">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <div class="stat-label">Completed</div>
                            <div class="fw-bold fs-4"><?php echo $completedCount; ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon yellow">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div>
                            <div class="stat-label">Pending</div>
                            <div class="fw-bold fs-4"><?php echo $pendingCount; ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon red">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="stat-label">Failed</div>
                            <div class="fw-bold fs-4"><?php echo $failedCount; ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon">
                            <i class="fas fa-undo"></i>
                        </div>
                        <div>
                            <div class="stat-label">Refunded</div>
                            <div class="fw-bold fs-4"><?php echo $refundedCount; ?></div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Transactions Table -->
        <section class="section-card mb-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h3 class="section-heading">Payment Transactions</h3>
                    <p class="section-description">
                        Search and filter the transaction records below.
                    </p>
                </div>

                <span class="text-secondary" style="font-size:11px">
                    <?php echo count($filteredPayments); ?> record(s) found
                </span>
            </div>

            <!-- Search and Filters -->
            <form action="payments.php" method="GET" class="filter-section mb-4">
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-lg-5">
                        <label class="form-label" for="search">
                            Search Transactions
                        </label>

                        <input type="text"
                               class="form-control"
                               id="search"
                               name="search"
                               value="<?php echo paymentEscape($search); ?>"
                               placeholder="Transaction ID, member, email or reference...">
                    </div>

                    <div class="col-6 col-lg-2">
                        <label class="form-label" for="status">
                            Payment Status
                        </label>

                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <?php foreach ($allowedStatuses as $status): ?>
                                <option value="<?php echo paymentEscape($status); ?>"
                                    <?php echo $statusFilter === $status ? 'selected' : ''; ?>>
                                    <?php echo paymentEscape($status); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-6 col-lg-3">
                        <label class="form-label" for="method">
                            Payment Method
                        </label>

                        <select class="form-select" id="method" name="method">
                            <option value="">All Methods</option>
                            <?php foreach ($allowedMethods as $method): ?>
                                <option value="<?php echo paymentEscape($method); ?>"
                                    <?php echo $methodFilter === $method ? 'selected' : ''; ?>>
                                    <?php echo paymentEscape($method); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-orange flex-grow-1">
                            <i class="fas fa-search me-1"></i> Search
                        </button>

                        <a href="payments.php"
                           class="btn btn-outline-light"
                           title="Reset filters">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>

                </div>
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Member</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th class="action-column">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (count($filteredPayments) > 0): ?>

                        <?php foreach ($filteredPayments as $payment): ?>
                            <tr>
                                <td>
                                    <span class="transaction-id">
                                        <?php echo paymentEscape($payment['id']); ?>
                                    </span>

                                    <span class="transaction-date">
                                        <?php
                                        echo paymentEscape(
                                            date('d M Y, h:i A', strtotime($payment['date']))
                                        );
                                        ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="member-name">
                                        <?php echo paymentEscape($payment['member']); ?>
                                    </span>

                                    <span class="member-email">
                                        <?php echo paymentEscape($payment['email']); ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="plan-label">
                                        <?php echo paymentEscape($payment['plan']); ?>
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        $<?php echo number_format((float)$payment['amount'], 2); ?>
                                    </strong>
                                </td>

                                <td>
                                    <div class="payment-method">
                                        <span class="method-icon">
                                            <i class="fas <?php echo paymentMethodIcon($payment['method']); ?>"></i>
                                        </span>

                                        <span>
                                            <?php echo paymentEscape($payment['method']); ?>
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge <?php echo paymentStatusClass($payment['status']); ?>">
                                        <i class="fas fa-circle" style="font-size:6px"></i>
                                        <?php echo paymentEscape($payment['status']); ?>
                                    </span>
                                </td>

                                <td class="action-column">
                                    <div class="d-flex gap-2">

                                        <button
                                            type="button"
                                            class="icon-btn"
                                            title="View transaction details"
                                            data-bs-toggle="modal"
                                            data-bs-target="#paymentDetailsModal"
                                            data-id="<?php echo paymentEscape($payment['id']); ?>"
                                            data-memberid="<?php echo paymentEscape($payment['member_id']); ?>"
                                            data-member="<?php echo paymentEscape($payment['member']); ?>"
                                            data-email="<?php echo paymentEscape($payment['email']); ?>"
                                            data-plan="<?php echo paymentEscape($payment['plan']); ?>"
                                            data-amount="<?php echo paymentEscape(number_format((float)$payment['amount'], 2, '.', '')); ?>"
                                            data-method="<?php echo paymentEscape($payment['method']); ?>"
                                            data-status="<?php echo paymentEscape($payment['status']); ?>"
                                            data-date="<?php echo paymentEscape(date('d M Y, h:i A', strtotime($payment['date']))); ?>"
                                            data-reference="<?php echo paymentEscape($payment['reference']); ?>"
                                            data-description="<?php echo paymentEscape($payment['description']); ?>"
                                            onclick="showPaymentDetails(this)">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <?php if ($payment['status'] === 'Pending'): ?>
                                            <button
                                                type="button"
                                                class="icon-btn"
                                                title="Review pending payment"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reviewPaymentModal"
                                                data-id="<?php echo paymentEscape($payment['id']); ?>"
                                                data-member="<?php echo paymentEscape($payment['member']); ?>"
                                                data-amount="<?php echo paymentEscape(number_format((float)$payment['amount'], 2)); ?>"
                                                onclick="preparePaymentReview(this)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fas fa-search fa-2x mb-3 d-block"></i>
                                <strong>No matching transactions found.</strong>
                                <p class="mt-2 mb-0">
                                    Try changing your search text or filters.
                                </p>
                            </td>
                        </tr>

                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </section>

        <!-- Demo Information -->
        <section class="section-card">
            <h3 class="section-heading mb-3">
                <i class="fas fa-info-circle me-2"
                   style="color:var(--fc-orange)"></i>
                Payment Management Notes
            </h3>

            <div class="notice-box">
                <strong>Demonstration only:</strong>
                These transactions are sample records stored in PHP sessions.
                No real payment is collected, no bank or card details are processed,
                and changing a pending payment's status does not verify a real transaction.

                <br><br>

                <strong>Completed revenue:</strong>
                The revenue figure represents the sum of sample transactions marked
                Completed. It is not a financial statement and does not include
                refunded or failed payments.
            </div>
        </section>

        <footer class="text-center mt-4 py-3"
                style="color:var(--fc-muted);font-size:11px">
            © <?php echo date('Y'); ?> FitCore Fitness Platform.
            Administrator Demonstration Interface.
        </footer>

    </main>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1"
     aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="paymentDetailsModalLabel">
                        Transaction Details
                    </h5>
                    <small class="text-secondary" id="detailTransactionId"></small>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="text-center py-3 mb-3">
                    <div class="stat-icon mx-auto mb-3"
                         style="width:58px;height:58px;font-size:23px">
                        <i class="fas fa-receipt"></i>
                    </div>

                    <div class="text-secondary mb-2" style="font-size:12px">
                        Transaction Amount
                    </div>

                    <div class="fw-bold" id="detailAmount"
                         style="font-size:34px"></div>

                    <div class="mt-3" id="detailStatus"></div>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Member</span>
                    <span class="detail-value" id="detailMember"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Member ID</span>
                    <span class="detail-value" id="detailMemberId"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value" id="detailEmail"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Membership Plan</span>
                    <span class="detail-value" id="detailPlan"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value" id="detailMethod"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Transaction Date</span>
                    <span class="detail-value" id="detailDate"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Reference Number</span>
                    <span class="detail-value" id="detailReference"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Description</span>
                    <span class="detail-value" id="detailDescription"></span>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light"
                        data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-orange"
                        onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Review Pending Payment Modal -->
<div class="modal fade" id="reviewPaymentModal" tabindex="-1"
     aria-labelledby="reviewPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <form action="payments.php" method="POST"
                  onsubmit="return confirmPaymentReview();">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="reviewPaymentModalLabel">
                            Review Pending Payment
                        </h5>
                        <small class="text-secondary">
                            Update the status of a sample transaction.
                        </small>
                    </div>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="csrf_token"
                           value="<?php echo paymentEscape($csrfToken); ?>">

                    <input type="hidden" name="action" value="update_status">

                    <input type="hidden" name="payment_id" id="reviewPaymentId">

                    <div class="section-card mb-4">
                        <div class="text-secondary mb-2" style="font-size:11px">
                            Transaction
                        </div>

                        <strong id="reviewTransactionDisplay"></strong>

                        <div class="text-secondary mt-2" style="font-size:12px">
                            Member: <span id="reviewMemberDisplay"></span>
                        </div>

                        <div class="mt-3 fw-bold"
                             style="font-size:25px;color:var(--fc-orange)">
                            $<span id="reviewAmountDisplay"></span>
                        </div>
                    </div>

                    <label class="form-label" for="newStatus">
                        Select New Status
                    </label>

                    <select class="form-select" name="new_status"
                            id="newStatus" required>
                        <option value="Completed">Mark as Completed</option>
                        <option value="Failed">Mark as Failed</option>
                    </select>

                    <div class="notice-box mt-3">
                        This action changes the demonstration record only.
                        It does not verify a bank transfer or process a payment.
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light"
                            data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-orange">
                        <i class="fas fa-save me-1"></i> Update Status
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
function setText(id, value) {
    document.getElementById(id).textContent = value || 'Not provided';
}

function showPaymentDetails(button) {
    setText('detailTransactionId', button.dataset.id);
    setText('detailMember', button.dataset.member);
    setText('detailMemberId', button.dataset.memberid);
    setText('detailEmail', button.dataset.email);
    setText('detailPlan', button.dataset.plan);
    setText('detailMethod', button.dataset.method);
    setText('detailDate', button.dataset.date);
    setText('detailReference', button.dataset.reference);
    setText('detailDescription', button.dataset.description);

    document.getElementById('detailAmount').textContent =
        '$' + Number(button.dataset.amount || 0).toFixed(2);

    const status = button.dataset.status || 'Pending';
    const statusElement = document.getElementById('detailStatus');

    statusElement.replaceChildren();

    const badge = document.createElement('span');
    badge.className = 'status-badge ' + statusClass(status);
    badge.textContent = status;
    statusElement.appendChild(badge);
}

function statusClass(status) {
    switch (status) {
        case 'Completed':
            return 'status-completed';
        case 'Pending':
            return 'status-pending';
        case 'Failed':
            return 'status-failed';
        case 'Refunded':
            return 'status-refunded';
        default:
            return 'status-pending';
    }
}

function preparePaymentReview(button) {
    document.getElementById('reviewPaymentId').value =
        button.dataset.id || '';

    setText('reviewTransactionDisplay', button.dataset.id);
    setText('reviewMemberDisplay', button.dataset.member);
    setText('reviewAmountDisplay', button.dataset.amount);

    document.getElementById('newStatus').value = 'Completed';
}

function confirmPaymentReview() {
    const status = document.getElementById('newStatus').value;

    return confirm(
        'Are you sure you want to mark this demonstration payment as ' +
        status.toLowerCase() + '?'
    );
}
</script>

</body>
</html>