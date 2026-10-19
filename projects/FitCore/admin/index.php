
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore Administrator Dashboard
|--------------------------------------------------------------------------
| Portfolio demonstration only.
| Uses sample data and does not require a database.
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Asia/Colombo');

$adminName = 'Michael Anderson';
$adminRole = 'System Administrator';

$today = date('l, F j, Y');

/* Dashboard summary data */
$stats = [
    [
        'label' => 'Total Members',
        'value' => '1,248',
        'change' => '+12.8%',
        'description' => 'Compared to last month',
        'icon' => 'fa-users',
        'color' => 'orange'
    ],
    [
        'label' => 'Active Trainers',
        'value' => '18',
        'change' => '+2',
        'description' => 'Compared to last month',
        'icon' => 'fa-user-tie',
        'color' => 'blue'
    ],
    [
        'label' => 'Monthly Revenue',
        'value' => '$24,680',
        'change' => '+8.4%',
        'description' => 'Compared to last month',
        'icon' => 'fa-dollar-sign',
        'color' => 'green'
    ],
    [
        'label' => 'Active Memberships',
        'value' => '986',
        'change' => '+6.2%',
        'description' => 'Currently subscribed',
        'icon' => 'fa-id-card',
        'color' => 'purple'
    ]
];

/* Membership distribution */
$membershipStats = [
    ['name' => 'Basic', 'count' => 342, 'percentage' => 35, 'color' => 'basic'],
    ['name' => 'Pro', 'count' => 418, 'percentage' => 42, 'color' => 'pro'],
    ['name' => 'Premium', 'count' => 226, 'percentage' => 23, 'color' => 'premium']
];

/* Sample revenue chart data */
$revenueMonths = ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'];
$revenueValues = [16200, 18400, 17100, 21300, 22750, 24680];

/* Recent payment transactions */
$recentPayments = [
    [
        'reference' => 'FC-PAY-1082',
        'member' => 'Olivia Bennett',
        'initials' => 'OB',
        'plan' => 'Premium',
        'amount' => 70.00,
        'date' => date('Y-m-d'),
        'status' => 'Paid'
    ],
    [
        'reference' => 'FC-PAY-1081',
        'member' => 'Ethan Brooks',
        'initials' => 'EB',
        'plan' => 'Pro',
        'amount' => 40.00,
        'date' => date('Y-m-d'),
        'status' => 'Paid'
    ],
    [
        'reference' => 'FC-PAY-1080',
        'member' => 'Sophia Mitchell',
        'initials' => 'SM',
        'plan' => 'Basic',
        'amount' => 20.00,
        'date' => date('Y-m-d', strtotime('-1 day')),
        'status' => 'Pending'
    ],
    [
        'reference' => 'FC-PAY-1079',
        'member' => 'Noah Wilson',
        'initials' => 'NW',
        'plan' => 'Premium',
        'amount' => 70.00,
        'date' => date('Y-m-d', strtotime('-1 day')),
        'status' => 'Paid'
    ],
    [
        'reference' => 'FC-PAY-1078',
        'member' => 'Ava Thompson',
        'initials' => 'AT',
        'plan' => 'Pro',
        'amount' => 40.00,
        'date' => date('Y-m-d', strtotime('-2 days')),
        'status' => 'Failed'
    ]
];

/* Upcoming fitness classes */
$upcomingClasses = [
    [
        'name' => 'Strength Training',
        'trainer' => 'Alex Morgan',
        'time' => '08:00 AM - 09:00 AM',
        'day' => 'MON',
        'date' => date('d', strtotime('next Monday')),
        'members' => 18,
        'capacity' => 24,
        'category' => 'Strength'
    ],
    [
        'name' => 'Morning Yoga',
        'trainer' => 'Sarah Williams',
        'time' => '09:00 AM - 10:00 AM',
        'day' => 'TUE',
        'date' => date('d', strtotime('next Tuesday')),
        'members' => 14,
        'capacity' => 20,
        'category' => 'Yoga'
    ],
    [
        'name' => 'HIIT Workout',
        'trainer' => 'Daniel Carter',
        'time' => '05:00 AM - 06:00 AM',
        'day' => 'WED',
        'date' => date('d', strtotime('next Wednesday')),
        'members' => 22,
        'capacity' => 24,
        'category' => 'Cardio'
    ]
];

/* Recent activity feed */
$recentActivities = [
    [
        'icon' => 'fa-user-plus',
        'color' => 'orange',
        'title' => 'New member registered',
        'description' => 'James Parker joined the Pro membership.',
        'time' => '15 minutes ago'
    ],
    [
        'icon' => 'fa-credit-card',
        'color' => 'green',
        'title' => 'Payment received',
        'description' => 'Olivia Bennett paid $70.00 for Premium.',
        'time' => '32 minutes ago'
    ],
    [
        'icon' => 'fa-calendar-check',
        'color' => 'blue',
        'title' => 'Class booking confirmed',
        'description' => 'A member booked a Strength Training session.',
        'time' => '1 hour ago'
    ],
    [
        'icon' => 'fa-user-tie',
        'color' => 'purple',
        'title' => 'Trainer profile updated',
        'description' => 'Daniel Carter updated his availability.',
        'time' => '2 hours ago'
    ]
];

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$totalRevenue = array_sum(array_column($recentPayments, 'amount'));
$paidTransactions = count(array_filter($recentPayments, function ($payment) {
    return $payment['status'] === 'Paid';
}));

$maxRevenue = max($revenueValues);
$chartMax = max(1, $maxRevenue);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | FitCore</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        :root {
            --fc-bg: #101010;
            --fc-sidebar: #151515;
            --fc-card: #191919;
            --fc-card-hover: #202020;
            --fc-border: #2b2b2b;
            --fc-orange: #ff6b2c;
            --fc-orange-soft: rgba(255, 107, 44, 0.13);
            --fc-text: #f5f5f5;
            --fc-muted: #999999;
            --fc-green: #32c48d;
            --fc-blue: #59a6ff;
            --fc-purple: #b18aff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--fc-bg);
            color: var(--fc-text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        button, input {
            font-family: inherit;
        }

        .admin-layout {
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 255px;
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1050;
            background: var(--fc-sidebar);
            border-right: 1px solid var(--fc-border);
            display: flex;
            flex-direction: column;
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
            color: var(--fc-text);
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
            letter-spacing: .4px;
        }

        .brand-name span {
            color: var(--fc-orange);
        }

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

        .nav-link-custom .nav-arrow {
            margin-left: auto;
            font-size: 10px;
            opacity: .65;
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

        .sidebar-footer-link:hover {
            color: var(--fc-orange);
        }

        /* Main content */
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

        .breadcrumb-text span {
            color: var(--fc-orange);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .today-date {
            color: #b1b1b1;
            font-size: 11px;
            white-space: nowrap;
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
            position: relative;
        }

        .icon-button:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .notification-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 6px;
            height: 6px;
            background: var(--fc-orange);
            border-radius: 50%;
        }

        .mobile-menu-btn {
            display: none;
        }

        .page-content {
            padding: 30px;
            max-width: 1800px;
            margin: 0 auto;
        }

        .welcome-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 27px;
        }

        .welcome-title {
            font-size: clamp(23px, 2vw, 29px);
            font-weight: 800;
            margin-bottom: 8px;
        }

        .welcome-title span {
            color: var(--fc-orange);
        }

        .welcome-description {
            color: var(--fc-muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .btn-orange {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            background: var(--fc-orange);
            color: #151515;
            border: 1px solid var(--fc-orange);
            padding: 11px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            transition: .2s;
            white-space: nowrap;
        }

        .btn-orange:hover {
            background: #ff824d;
            border-color: #ff824d;
            color: #111;
            transform: translateY(-1px);
        }

        .btn-outline-custom {
            color: #dedede;
            background: transparent;
            border: 1px solid #383838;
            padding: 9px 13px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
        }

        .btn-outline-custom:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        /* Summary cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 23px;
        }

        .stat-card {
            padding: 21px;
            border: 1px solid var(--fc-border);
            background: var(--fc-card);
            border-radius: 13px;
            min-width: 0;
            transition: .2s;
        }

        .stat-card:hover {
            background: var(--fc-card-hover);
            transform: translateY(-2px);
            border-color: #444;
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 19px;
        }

        .stat-label {
            color: #b2b2b2;
            font-size: 12px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .stat-icon.orange {
            color: var(--fc-orange);
            background: rgba(255, 107, 44, .13);
        }

        .stat-icon.blue {
            color: var(--fc-blue);
            background: rgba(89, 166, 255, .12);
        }

        .stat-icon.green {
            color: var(--fc-green);
            background: rgba(50, 196, 141, .12);
        }

        .stat-icon.purple {
            color: var(--fc-purple);
            background: rgba(177, 138, 255, .12);
        }

        .stat-value {
            font-size: clamp(23px, 2vw, 30px);
            font-weight: 800;
            letter-spacing: -.6px;
            margin-bottom: 11px;
            overflow-wrap: anywhere;
        }

        .stat-bottom {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 7px;
            color: var(--fc-muted);
            font-size: 10px;
        }

        .stat-change {
            color: var(--fc-green);
            font-weight: 800;
        }

        /* Panels */
        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(280px, 1fr);
            gap: 21px;
            margin-bottom: 22px;
        }

        .panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 22px;
            min-width: 0;
        }

        .panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 21px;
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

        .period-select {
            background: #202020;
            color: #ddd;
            border: 1px solid #363636;
            border-radius: 7px;
            padding: 8px 10px;
            font-size: 10px;
            outline: none;
        }

        /* Revenue chart */
        .revenue-summary {
            margin-bottom: 18px;
        }

        .revenue-total {
            font-size: 28px;
            font-weight: 800;
        }

        .revenue-caption {
            font-size: 10px;
            color: var(--fc-muted);
            margin-top: 4px;
        }

        .revenue-chart {
            height: 200px;
            display: flex;
            align-items: stretch;
            gap: 15px;
            padding-top: 10px;
        }

        .chart-y-axis {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #747474;
            font-size: 9px;
            padding-bottom: 24px;
        }

        .chart-bars {
            flex: 1;
            display: flex;
            justify-content: space-around;
            align-items: stretch;
            gap: 10px;
            min-width: 0;
            border-bottom: 1px solid #363636;
            background-image: linear-gradient(to bottom, transparent calc(25% - 1px), #292929 25%, transparent calc(25% + 1px)),
                              linear-gradient(to bottom, transparent calc(50% - 1px), #292929 50%, transparent calc(50% + 1px)),
                              linear-gradient(to bottom, transparent calc(75% - 1px), #292929 75%, transparent calc(75% + 1px));
        }

        .chart-column {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            position: relative;
        }

        .chart-bar {
            width: min(32px, 75%);
            min-height: 5px;
            background: linear-gradient(to top, #b94b1e, var(--fc-orange));
            border-radius: 5px 5px 0 0;
            position: relative;
            transition: opacity .2s;
        }

        .chart-bar:hover {
            opacity: .75;
        }

        .chart-bar.current {
            background: linear-gradient(to top, #ff6b2c, #ffb08c);
        }

        .chart-month {
            position: absolute;
            bottom: -23px;
            font-size: 10px;
            color: #aaa;
        }

        .chart-tooltip {
            display: none;
            position: absolute;
            bottom: calc(100% + 7px);
            background: #303030;
            color: #fff;
            padding: 5px 7px;
            font-size: 9px;
            border-radius: 5px;
            white-space: nowrap;
            z-index: 2;
        }

        .chart-bar:hover .chart-tooltip {
            display: block;
        }

        /* Membership breakdown */
        .membership-total {
            font-size: 27px;
            font-weight: 800;
        }

        .membership-total-caption {
            color: var(--fc-muted);
            font-size: 10px;
            margin-top: 5px;
        }

        .membership-progress {
            display: flex;
            height: 9px;
            border-radius: 20px;
            overflow: hidden;
            background: #292929;
            margin: 23px 0 24px;
        }

        .membership-progress div {
            height: 100%;
        }

        .membership-progress .basic {
            background: #727272;
        }

        .membership-progress .pro {
            background: var(--fc-orange);
        }

        .membership-progress .premium {
            background: var(--fc-purple);
        }

        .membership-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 19px;
        }

        .membership-dot {
            width: 9px;
            height: 9px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .membership-info {
            flex: 1;
            min-width: 0;
        }

        .membership-name {
            font-size: 12px;
            font-weight: 700;
        }

        .membership-percent {
            color: var(--fc-muted);
            font-size: 10px;
            margin-top: 4px;
        }

        .membership-count {
            font-size: 13px;
            font-weight: 800;
        }

        .membership-footer {
            padding-top: 15px;
            border-top: 1px solid var(--fc-border);
            display: flex;
            justify-content: space-between;
            gap: 10px;
            color: var(--fc-muted);
            font-size: 10px;
        }

        .membership-footer strong {
            color: var(--fc-text);
        }

        /* Tables */
        .table-responsive {
            border-radius: 8px;
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
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 12px 12px;
            border-bottom: 1px solid #333;
            white-space: nowrap;
            background: #1b1b1b;
        }

        .custom-table td {
            color: #cfcfcf;
            font-size: 11px;
            padding: 14px 12px;
            border-bottom: 1px solid #292929;
            white-space: nowrap;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .custom-table tbody tr:hover {
            background: #202020;
        }

        .member-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .member-avatar {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #2a2a2a;
            color: #f0f0f0;
            font-size: 10px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .member-name {
            color: #f2f2f2;
            font-size: 11px;
            font-weight: 700;
        }

        .member-plan {
            color: #858585;
            font-size: 9px;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 800;
        }

        .status-badge::before {
            content: "";
            width: 5px;
            height: 5px;
            background: currentColor;
            border-radius: 50%;
        }

        .status-paid,
        .status-present {
            color: var(--fc-green);
            background: rgba(50, 196, 141, .11);
        }

        .status-pending,
        .status-late {
            color: #f4bd55;
            background: rgba(244, 189, 85, .11);
        }

        .status-failed,
        .status-absent {
            color: #ff7676;
            background: rgba(255, 118, 118, .11);
        }

        .status-confirmed {
            color: var(--fc-blue);
            background: rgba(89, 166, 255, .11);
        }

        .table-link {
            color: var(--fc-orange);
            font-size: 10px;
            font-weight: 700;
        }

        .table-link:hover {
            color: #ff9a73;
        }

        /* Classes */
        .class-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .class-item {
            display: flex;
            align-items: center;
            gap: 13px;
            padding-bottom: 15px;
            border-bottom: 1px solid #2b2b2b;
        }

        .class-item:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }

        .class-date {
            width: 48px;
            height: 53px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid #3a3a3a;
            border-radius: 9px;
            background: #202020;
        }

        .class-date-day {
            color: var(--fc-orange);
            font-size: 9px;
            font-weight: 800;
        }

        .class-date-number {
            font-size: 19px;
            font-weight: 800;
            line-height: 1.2;
        }

        .class-info {
            flex: 1;
            min-width: 0;
        }

        .class-name {
            color: #f4f4f4;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .class-meta {
            color: var(--fc-muted);
            font-size: 10px;
            line-height: 1.7;
        }

        .class-capacity {
            min-width: 70px;
            text-align: right;
        }

        .class-capacity-number {
            font-size: 12px;
            font-weight: 800;
        }

        .class-capacity-label {
            color: var(--fc-muted);
            font-size: 9px;
            margin-top: 4px;
        }

        .capacity-bar {
            height: 4px;
            background: #353535;
            border-radius: 5px;
            margin-top: 7px;
            overflow: hidden;
        }

        .capacity-bar div {
            height: 100%;
            background: var(--fc-orange);
            border-radius: 5px;
        }

        /* Activity */
        .activity-list {
            display: flex;
            flex-direction: column;
        }

        .activity-item {
            display: flex;
            gap: 13px;
            padding-bottom: 19px;
            position: relative;
        }

        .activity-item:not(:last-child)::before {
            content: "";
            position: absolute;
            top: 35px;
            left: 16px;
            bottom: 0;
            width: 1px;
            background: #343434;
        }

        .activity-icon {
            width: 33px;
            height: 33px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            flex-shrink: 0;
            font-size: 12px;
            position: relative;
            z-index: 1;
        }

        .activity-icon.orange {
            color: var(--fc-orange);
            background: rgba(255, 107, 44, .12);
        }

        .activity-icon.green {
            color: var(--fc-green);
            background: rgba(50, 196, 141, .12);
        }

        .activity-icon.blue {
            color: var(--fc-blue);
            background: rgba(89, 166, 255, .12);
        }

        .activity-icon.purple {
            color: var(--fc-purple);
            background: rgba(177, 138, 255, .12);
        }

        .activity-title {
            color: #eaeaea;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .activity-description {
            color: var(--fc-muted);
            font-size: 10px;
            line-height: 1.6;
        }

        .activity-time {
            color: #737373;
            font-size: 9px;
            margin-top: 6px;
        }

        /* Quick management links */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 13px;
            margin-bottom: 22px;
        }

        .quick-action {
            padding: 17px;
            display: flex;
            align-items: center;
            gap: 13px;
            border: 1px solid var(--fc-border);
            border-radius: 11px;
            background: var(--fc-card);
            color: #eee;
            transition: .2s;
            min-width: 0;
        }

        .quick-action:hover {
            color: white;
            border-color: var(--fc-orange);
            background: #202020;
            transform: translateY(-2px);
        }

        .quick-action-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--fc-orange-soft);
            color: var(--fc-orange);
            font-size: 14px;
        }

        .quick-action-title {
            font-size: 11px;
            font-weight: 800;
        }

        .quick-action-description {
            color: var(--fc-muted);
            font-size: 9px;
            margin-top: 5px;
            line-height: 1.5;
        }

        .quick-action-arrow {
            color: #777;
            margin-left: auto;
            font-size: 10px;
        }

        /* Footer */
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

        .dashboard-footer strong {
            color: var(--fc-orange);
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

        .demo-label i {
            font-size: 9px;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1250px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .quick-actions {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .content-grid {
                grid-template-columns: minmax(0, 1.35fr) minmax(260px, 1fr);
            }

            .page-content {
                padding: 23px;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 1040;
                background: rgba(0, 0, 0, .65);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: inline-flex;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 16px 22px;
            }
        }

        @media (max-width: 576px) {
            .topbar {
                padding: 14px 15px;
            }

            .topbar-title {
                font-size: 17px;
            }

            .today-date {
                display: none;
            }

            .topbar-right {
                gap: 8px;
            }

            .page-content {
                padding: 19px 14px;
            }

            .welcome-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .stats-grid {
                gap: 11px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-top {
                align-items: flex-start;
                margin-bottom: 14px;
            }

            .stat-icon {
                width: 34px;
                height: 34px;
                font-size: 13px;
            }

            .stat-label {
                font-size: 10px;
                line-height: 1.5;
            }

            .stat-value {
                font-size: 23px;
            }

            .stat-bottom {
                font-size: 9px;
                gap: 4px;
            }

            .panel {
                padding: 16px 12px;
            }

            .panel-title {
                font-size: 13px;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .quick-action {
                padding: 14px;
            }

            .revenue-chart {
                gap: 8px;
                height: 175px;
            }

            .chart-bars {
                gap: 5px;
            }

            .chart-month {
                font-size: 8px;
            }

            .chart-y-axis {
                font-size: 8px;
            }

            .class-item {
                gap: 9px;
            }

            .class-date {
                width: 42px;
            }

            .class-capacity {
                min-width: 56px;
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

    <!-- Sidebar overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="adminSidebar">

        <div class="brand-area">
            <a href="index.php" class="brand">
                <div class="brand-mark">
                    <i class="fas fa-dumbbell"></i>
                </div>

                <div>
                    <div class="brand-name">Fit<span>Core</span></div>
                    <div class="brand-subtitle">Administrator Panel</div>
                </div>
            </a>
        </div>

        <div class="sidebar-label">Main Menu</div>

        <nav class="sidebar-nav">

            <a href="index.php" class="nav-link-custom active">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
                <i class="fas fa-chevron-right nav-arrow"></i>
            </a>

            <a href="members.php" class="nav-link-custom">
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
                    <div class="admin-profile-name">
                        <?= e($adminName); ?>
                    </div>
                    <div class="admin-profile-role">
                        <?= e($adminRole); ?>
                    </div>
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

    <!-- Main Content -->
    <main class="main-content">

        <!-- Topbar -->
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
                    <h1 class="topbar-title">Administrator Dashboard</h1>
                    <div class="breadcrumb-text">
                        FitCore <span>/</span> Dashboard
                    </div>
                </div>

            </div>

            <div class="topbar-right">

                <div class="today-date">
                    <i class="far fa-calendar-alt me-2"></i>
                    <?= e($today); ?>
                </div>

                <button
                    type="button"
                    class="icon-button"
                    title="Demo notifications"
                    onclick="showDemoNotice('Notifications')"
                    aria-label="Notifications"
                >
                    <i class="far fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>

                <div class="admin-avatar">MA</div>

            </div>

        </header>

        <!-- Dashboard Content -->
        <div class="page-content">

            <!-- Welcome section -->
            <section class="welcome-row">

                <div>
                    <div class="demo-label mb-3">
                        <i class="fas fa-circle"></i>
                        PORTFOLIO DEMONSTRATION
                    </div>

                    <h2 class="welcome-title">
                        Welcome back, <span>Michael!</span>
                    </h2>

                    <p class="welcome-description mb-0">
                        Here's what's happening at FitCore today.
                        Review your gym's performance and manage daily operations.
                    </p>
                </div>

                <a href="reports.php" class="btn-orange">
                    <i class="fas fa-chart-bar"></i>
                    View Reports
                </a>

            </section>

            <!-- Summary Cards -->
            <section class="stats-grid">

                <?php foreach ($stats as $stat): ?>

                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-label">
                                <?= e($stat['label']); ?>
                            </div>

                            <div class="stat-icon <?= e($stat['color']); ?>">
                                <i class="fas <?= e($stat['icon']); ?>"></i>
                            </div>
                        </div>

                        <div class="stat-value">
                            <?= e($stat['value']); ?>
                        </div>

                        <div class="stat-bottom">
                            <span class="stat-change">
                                <i class="fas fa-arrow-up me-1"></i>
                                <?= e($stat['change']); ?>
                            </span>

                            <span><?= e($stat['description']); ?></span>
                        </div>

                    </div>

                <?php endforeach; ?>

            </section>

            <!-- Revenue and Membership Charts -->
            <section class="content-grid">

                <!-- Revenue Chart -->
                <div class="panel">

                    <div class="panel-header">
                        <div>
                            <h3 class="panel-title">Revenue Overview</h3>
                            <div class="panel-subtitle">
                                Sample monthly membership revenue
                            </div>
                        </div>

                        <select
                            class="period-select"
                            id="revenuePeriod"
                            aria-label="Revenue period"
                        >
                            <option value="6">Last 6 Months</option>
                            <option value="3">Last 3 Months</option>
                        </select>
                    </div>

                    <div class="revenue-summary">
                        <div class="revenue-total" id="revenueTotal">
                            $24,680
                        </div>

                        <div class="revenue-caption">
                            <span style="color: var(--fc-green);">
                                <i class="fas fa-arrow-up me-1"></i>8.4%
                            </span>
                            revenue growth compared to last month
                        </div>
                    </div>

                    <div class="revenue-chart">

                        <div class="chart-y-axis">
                            <span>$25k</span>
                            <span>$18k</span>
                            <span>$12k</span>
                            <span>$6k</span>
                            <span>$0</span>
                        </div>

                        <div class="chart-bars" id="revenueChart">

                            <?php foreach ($revenueMonths as $index => $month): ?>

                                <?php
                                    $value = $revenueValues[$index];
                                    $height = ($value / $chartMax) * 100;
                                ?>

                                <div class="chart-column"
                                     data-index="<?= (int)$index; ?>">

                                    <div
                                        class="chart-bar <?= $index === count($revenueMonths) - 1 ? 'current' : ''; ?>"
                                        style="height: <?= e(number_format($height, 2, '.', '')); ?>%;"
                                    >
                                        <span class="chart-tooltip">
                                            $<?= number_format($value); ?>
                                        </span>
                                    </div>

                                    <span class="chart-month">
                                        <?= e($month); ?>
                                    </span>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                </div>

                <!-- Membership Breakdown -->
                <div class="panel">

                    <div class="panel-header">
                        <div>
                            <h3 class="panel-title">Membership Breakdown</h3>
                            <div class="panel-subtitle">
                                Active membership distribution
                            </div>
                        </div>

                        <a href="memberships.php" class="table-link">
                            Manage <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>

                    <div class="membership-total">986</div>
                    <div class="membership-total-caption">
                        Active subscriptions across all plans
                    </div>

                    <div class="membership-progress">

                        <?php foreach ($membershipStats as $membership): ?>

                            <div
                                class="<?= e($membership['color']); ?>"
                                style="width: <?= (int)$membership['percentage']; ?>%;"
                                title="<?= e($membership['name']); ?>: <?= (int)$membership['percentage']; ?>%"
                            ></div>

                        <?php endforeach; ?>

                    </div>

                    <?php foreach ($membershipStats as $membership): ?>

                        <div class="membership-row">

                            <div
                                class="membership-dot"
                                style="background:
                                <?=
                                    $membership['color'] === 'basic'
                                        ? '#727272'
                                        : ($membership['color'] === 'pro'
                                            ? 'var(--fc-orange)'
                                            : 'var(--fc-purple)');
                                ?>;"
                            ></div>

                            <div class="membership-info">
                                <div class="membership-name">
                                    <?= e($membership['name']); ?> Plan
                                </div>

                                <div class="membership-percent">
                                    <?= (int)$membership['percentage']; ?>% of active members
                                </div>
                            </div>

                            <div class="membership-count">
                                <?= number_format($membership['count']); ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                    <div class="membership-footer">
                        <span>Total registered members</span>
                        <strong>1,248</strong>
                    </div>

                </div>

            </section>

            <!-- Quick Actions -->
            <section class="mb-4">

                <div class="panel-header mb-3">
                    <div>
                        <h3 class="panel-title">Quick Management</h3>
                        <div class="panel-subtitle">
                            Access frequently used administrator functions
                        </div>
                    </div>
                </div>

                <div class="quick-actions">

                    <a href="members.php" class="quick-action">
                        <div class="quick-action-icon">
                            <i class="fas fa-users"></i>
                        </div>

                        <div>
                            <div class="quick-action-title">Members</div>
                            <div class="quick-action-description">
                                View and manage gym members
                            </div>
                        </div>

                        <i class="fas fa-chevron-right quick-action-arrow"></i>
                    </a>

                    <a href="trainers.php" class="quick-action">
                        <div class="quick-action-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>

                        <div>
                            <div class="quick-action-title">Trainers</div>
                            <div class="quick-action-description">
                                Manage trainer profiles
                            </div>
                        </div>

                        <i class="fas fa-chevron-right quick-action-arrow"></i>
                    </a>

                    <a href="memberships.php" class="quick-action">
                        <div class="quick-action-icon">
                            <i class="fas fa-id-card"></i>
                        </div>

                        <div>
                            <div class="quick-action-title">Memberships</div>
                            <div class="quick-action-description">
                                Update membership plans
                            </div>
                        </div>

                        <i class="fas fa-chevron-right quick-action-arrow"></i>
                    </a>

                    <a href="payments.php" class="quick-action">
                        <div class="quick-action-icon">
                            <i class="fas fa-wallet"></i>
                        </div>

                        <div>
                            <div class="quick-action-title">Payments</div>
                            <div class="quick-action-description">
                                Review transaction records
                            </div>
                        </div>

                        <i class="fas fa-chevron-right quick-action-arrow"></i>
                    </a>

                    <a href="classes.php" class="quick-action">
                        <div class="quick-action-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>

                        <div>
                            <div class="quick-action-title">Fitness Classes</div>
                            <div class="quick-action-description">
                                Organize classes and schedules
                            </div>
                        </div>

                        <i class="fas fa-chevron-right quick-action-arrow"></i>
                    </a>

                    <a href="attendance.php" class="quick-action">
                        <div class="quick-action-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>

                        <div>
                            <div class="quick-action-title">Attendance</div>
                            <div class="quick-action-description">
                                Track member participation
                            </div>
                        </div>

                        <i class="fas fa-chevron-right quick-action-arrow"></i>
                    </a>

                    <a href="reports.php" class="quick-action">
                        <div class="quick-action-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>

                        <div>
                            <div class="quick-action-title">Reports</div>
                            <div class="quick-action-description">
                                Analyze business performance
                            </div>
                        </div>

                        <i class="fas fa-chevron-right quick-action-arrow"></i>
                    </a>

                    <a href="../customer/index.php" class="quick-action">
                        <div class="quick-action-icon">
                            <i class="fas fa-eye"></i>
                        </div>

                        <div>
                            <div class="quick-action-title">Member Website</div>
                            <div class="quick-action-description">
                                Preview the customer experience
                            </div>
                        </div>

                        <i class="fas fa-chevron-right quick-action-arrow"></i>
                    </a>

                </div>

            </section>

            <!-- Recent Payments -->
            <section class="panel mb-4">

                <div class="panel-header">

                    <div>
                        <h3 class="panel-title">Recent Payments</h3>
                        <div class="panel-subtitle">
                            Latest sample membership transactions
                        </div>
                    </div>

                    <a href="payments.php" class="btn-outline-custom">
                        View All Payments
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>

                </div>

                <div class="table-responsive">

                    <table class="custom-table">

                        <thead>
                            <tr>
                                <th>Transaction / Member</th>
                                <th>Membership</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($recentPayments as $payment): ?>

                                <?php
                                    $statusClass = match ($payment['status']) {
                                        'Paid' => 'status-paid',
                                        'Pending' => 'status-pending',
                                        'Failed' => 'status-failed',
                                        default => 'status-confirmed'
                                    };
                                ?>

                                <tr>

                                    <td>
                                        <div class="member-cell">

                                            <div class="member-avatar">
                                                <?= e($payment['initials']); ?>
                                            </div>

                                            <div>
                                                <div class="member-name">
                                                    <?= e($payment['member']); ?>
                                                </div>

                                                <div class="member-plan">
                                                    <?= e($payment['reference']); ?>
                                                </div>
                                            </div>

                                        </div>
                                    </td>

                                    <td><?= e($payment['plan']); ?></td>

                                    <td>
                                        <strong>
                                            $<?= number_format($payment['amount'], 2); ?>
                                        </strong>
                                    </td>

                                    <td><?= e(date('M d, Y', strtotime($payment['date']))); ?></td>

                                    <td>
                                        <span class="status-badge <?= e($statusClass); ?>">
                                            <?= e($payment['status']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a
                                            href="payments.php"
                                            class="table-link"
                                        >
                                            Details
                                        </a>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                    <span style="font-size:10px; color:var(--fc-muted);">
                        Showing <?= count($recentPayments); ?> sample transactions
                    </span>

                    <span style="font-size:10px; color:var(--fc-muted);">
                        <?= $paidTransactions; ?> paid records in this sample
                    </span>
                </div>

            </section>

            <!-- Upcoming Classes and Activity -->
            <section class="content-grid">

                <!-- Upcoming Classes -->
                <div class="panel">

                    <div class="panel-header">

                        <div>
                            <h3 class="panel-title">Upcoming Classes</h3>
                            <div class="panel-subtitle">
                                Sample scheduled fitness sessions
                            </div>
                        </div>

                        <a href="classes.php" class="table-link">
                            Manage Classes
                            <i class="fas fa-arrow-right ms-1"></i>
                        </a>

                    </div>

                    <div class="class-list">

                        <?php foreach ($upcomingClasses as $class): ?>

                            <?php
                                $capacityPercentage = $class['capacity'] > 0
                                    ? min(100, ($class['members'] / $class['capacity']) * 100)
                                    : 0;
                            ?>

                            <div class="class-item">

                                <div class="class-date">
                                    <span class="class-date-day">
                                        <?= e($class['day']); ?>
                                    </span>

                                    <span class="class-date-number">
                                        <?= e($class['date']); ?>
                                    </span>
                                </div>

                                <div class="class-info">
                                    <div class="class-name">
                                        <?= e($class['name']); ?>
                                    </div>

                                    <div class="class-meta">
                                        <i class="fas fa-user-tie me-1"></i>
                                        <?= e($class['trainer']); ?>
                                        <br>

                                        <i class="far fa-clock me-1"></i>
                                        <?= e($class['time']); ?>
                                    </div>
                                </div>

                                <div class="class-capacity">

                                    <div class="class-capacity-number">
                                        <?= (int)$class['members']; ?>/<?= (int)$class['capacity']; ?>
                                    </div>

                                    <div class="class-capacity-label">
                                        Members
                                    </div>

                                    <div class="capacity-bar">
                                        <div style="width: <?= e(number_format($capacityPercentage, 2, '.', '')); ?>%;"></div>
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

                <!-- Recent Activity -->
                <div class="panel">

                    <div class="panel-header">

                        <div>
                            <h3 class="panel-title">Recent Activity</h3>
                            <div class="panel-subtitle">
                                Sample platform activity feed
                            </div>
                        </div>

                        <button
                            type="button"
                            class="icon-button"
                            style="width:32px;height:32px;"
                            onclick="showDemoNotice('Activity feed')"
                            aria-label="Activity options"
                        >
                            <i class="fas fa-ellipsis-h"></i>
                        </button>

                    </div>

                    <div class="activity-list">

                        <?php foreach ($recentActivities as $activity): ?>

                            <div class="activity-item">

                                <div class="activity-icon <?= e($activity['color']); ?>">
                                    <i class="fas <?= e($activity['icon']); ?>"></i>
                                </div>

                                <div>
                                    <div class="activity-title">
                                        <?= e($activity['title']); ?>
                                    </div>

                                    <div class="activity-description">
                                        <?= e($activity['description']); ?>
                                    </div>

                                    <div class="activity-time">
                                        <i class="far fa-clock me-1"></i>
                                        <?= e($activity['time']); ?>
                                    </div>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </section>

        </div>

        <!-- Footer -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    /* Mobile sidebar */
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuButton = document.getElementById('mobileMenuBtn');

    function openSidebar() {
        sidebar.classList.add('show');
        overlay.classList.add('show');
    }

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    }

    if (menuButton) {
        menuButton.addEventListener('click', function () {
            if (sidebar.classList.contains('show')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    overlay.addEventListener('click', closeSidebar);

    document.querySelectorAll('.nav-link-custom').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 992) {
                closeSidebar();
            }
        });
    });

    /* Revenue chart period filter */
    const revenuePeriod = document.getElementById('revenuePeriod');
    const chartColumns = Array.from(
        document.querySelectorAll('#revenueChart .chart-column')
    );

    const revenueData = <?= json_encode(
        array_map(
            function ($month, $value) {
                return [
                    'month' => $month,
                    'value' => $value
                ];
            },
            $revenueMonths,
            $revenueValues
        ),
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ); ?>;

    function updateRevenueChart() {
        const numberOfMonths = parseInt(revenuePeriod.value, 10) || 6;
        const visibleData = revenueData.slice(-numberOfMonths);
        const maxValue = Math.max(...visibleData.map(item => item.value));

        chartColumns.forEach(function (column, index) {
            const bar = column.querySelector('.chart-bar');
            const tooltip = column.querySelector('.chart-tooltip');
            const monthLabel = column.querySelector('.chart-month');
            const dataIndex = parseInt(column.dataset.index, 10);
            const item = revenueData[dataIndex];

            if (dataIndex < revenueData.length - numberOfMonths) {
                column.style.display = 'none';
                return;
            }

            column.style.display = 'flex';

            const height = (item.value / maxValue) * 100;

            bar.style.height = height + '%';
            tooltip.textContent = '$' + item.value.toLocaleString('en-US');
            monthLabel.textContent = item.month;

            bar.classList.toggle(
                'current',
                dataIndex === revenueData.length - 1
            );
        });

        const latestValue = visibleData[visibleData.length - 1].value;

        document.getElementById('revenueTotal').textContent =
            '$' + latestValue.toLocaleString('en-US');
    }

    revenuePeriod.addEventListener('change', updateRevenueChart);

    /* Demo-only notice */
    function showDemoNotice(section) {
        alert(
            section +
            ' is part of the FitCore portfolio demonstration. ' +
            'Sample data is displayed; no live notification or external ' +
            'service is connected.'
        );
    }
</script>

</body>
</html>