
<?php
session_start();

date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Administrator Dashboard
|--------------------------------------------------------------------------
| Portfolio demonstration using illustrative sample data.
| No database connection is required.
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/*
|--------------------------------------------------------------------------
| Demonstration Statistics
|--------------------------------------------------------------------------
| These figures are sample values for displaying the dashboard.
|--------------------------------------------------------------------------
*/

$stats = [
    'total_rooms'       => 48,
    'available_rooms'   => 15,
    'reservations'      => 126,
    'pending_bookings'  => 8,
    'customers'         => 94,
    'occupancy'         => 78,
    'monthly_revenue'   => 2847500,
    'pending_payments'  => 87500
];

$currency = 'USD';

$revenueData = [
    ['month' => 'May', 'revenue' => 1850000],
    ['month' => 'Jun', 'revenue' => 2120000],
    ['month' => 'Jul', 'revenue' => 1980000],
    ['month' => 'Aug', 'revenue' => 2450000],
    ['month' => 'Sep', 'revenue' => 2310000],
    ['month' => 'Oct', 'revenue' => 2847500]
];

$reservations = [
    [
        'id' => 'ABR-2026-1042',
        'guest' => 'Amelia Fernando',
        'room' => 'Deluxe Ocean View',
        'check_in' => date('Y-m-d'),
        'check_out' => date('Y-m-d', strtotime('+3 days')),
        'amount' => 540,
        'status' => 'Confirmed'
    ],
    [
        'id' => 'ABR-2026-1043',
        'guest' => 'Daniel Perera',
        'room' => 'Premium Beach Suite',
        'check_in' => date('Y-m-d'),
        'check_out' => date('Y-m-d', strtotime('+2 days')),
        'amount' => 580,
        'status' => 'Checked In'
    ],
    [
        'id' => 'ABR-2026-1044',
        'guest' => 'Sophia Williams',
        'room' => 'Family Coastal Suite',
        'check_in' => date('Y-m-d', strtotime('+1 day')),
        'check_out' => date('Y-m-d', strtotime('+5 days')),
        'amount' => 1360,
        'status' => 'Pending'
    ],
    [
        'id' => 'ABR-2026-1045',
        'guest' => 'Oliver Silva',
        'room' => 'Horizon Ocean Suite',
        'check_in' => date('Y-m-d', strtotime('+2 days')),
        'check_out' => date('Y-m-d', strtotime('+4 days')),
        'amount' => 640,
        'status' => 'Confirmed'
    ],
    [
        'id' => 'ABR-2026-1046',
        'guest' => 'Isabella Martin',
        'room' => 'Azure Presidential Villa',
        'check_in' => date('Y-m-d', strtotime('-1 day')),
        'check_out' => date('Y-m-d', strtotime('+1 day')),
        'amount' => 900,
        'status' => 'Checked In'
    ]
];

$arrivals = [
    [
        'guest' => 'Amelia Fernando',
        'room' => 'Deluxe Ocean View',
        'time' => '10:30 AM',
        'status' => 'Expected'
    ],
    [
        'guest' => 'Oliver Silva',
        'room' => 'Horizon Ocean Suite',
        'time' => '02:00 PM',
        'status' => 'Expected'
    ],
    [
        'guest' => 'Daniel Perera',
        'room' => 'Premium Beach Suite',
        'time' => '09:15 AM',
        'status' => 'Checked In'
    ]
];

$departures = [
    [
        'guest' => 'Isabella Martin',
        'room' => 'Azure Presidential Villa',
        'time' => '11:00 AM',
        'status' => 'Expected'
    ],
    [
        'guest' => 'Noah Jayasinghe',
        'room' => 'Garden Serenity Room',
        'time' => '12:00 PM',
        'status' => 'Checked Out'
    ]
];

$roomTypes = [
    ['name' => 'Deluxe Rooms', 'total' => 18, 'occupied' => 14],
    ['name' => 'Ocean Suites', 'total' => 16, 'occupied' => 13],
    ['name' => 'Family Suites', 'total' => 8, 'occupied' => 6],
    ['name' => 'Presidential Villas', 'total' => 6, 'occupied' => 4]
];

$recentActivities = [
    [
        'icon' => 'calendar-check',
        'title' => 'New reservation received',
        'detail' => 'Sophia Williams · Family Coastal Suite',
        'time' => '15 minutes ago'
    ],
    [
        'icon' => 'credit-card',
        'title' => 'Payment recorded',
        'detail' => 'Reservation ABR-2026-1042',
        'time' => '32 minutes ago'
    ],
    [
        'icon' => 'log-in',
        'title' => 'Guest checked in',
        'detail' => 'Daniel Perera · Premium Beach Suite',
        'time' => '1 hour ago'
    ],
    [
        'icon' => 'bed-double',
        'title' => 'Room maintenance scheduled',
        'detail' => 'Room B-204',
        'time' => '2 hours ago'
    ]
];

function money($amount)
{
    return '$' . number_format((float) $amount, 2);
}

function dateLabel($date)
{
    return date('d M Y', strtotime($date));
}

function statusClass($status)
{
    $classes = [
        'Confirmed'  => 'status-confirmed',
        'Pending'    => 'status-pending',
        'Checked In' => 'status-checked-in',
        'Checked Out'=> 'status-checked-out',
        'Expected'   => 'status-expected'
    ];

    return $classes[$status] ?? 'status-pending';
}

$maxRevenue = max(array_column($revenueData, 'revenue'));
$today = date('l, d F Y');
$currentMonth = date('F Y');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Azure Bay Resort</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --ocean: #073b4c;
            --ocean-dark: #052c39;
            --ocean-light: #0b6573;
            --gold: #c9a45c;
            --gold-light: #ead7a8;
            --cream: #fbf8f1;
            --page: #f5f7f8;
            --white: #ffffff;
            --text: #273940;
            --muted: #7a898e;
            --border: #e5eaec;
            --green: #198754;
            --red: #c94c4c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page);
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        button,
        input,
        select {
            font-family: inherit;
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        /* Sidebar */

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            width: 258px;
            overflow-y: auto;
            background: var(--ocean-dark);
            color: #fff;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 83px;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            color: #fff;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 39px;
            width: 39px;
            height: 39px;
            border-radius: 50%;
            background: var(--gold);
            color: white;
            font-size: 21px;
            font-weight: 800;
        }

        .brand-name {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: .6px;
        }

        .brand-caption {
            margin-top: 3px;
            color: #b9cbd0;
            font-size: 10px;
            letter-spacing: 1.5px;
        }

        .sidebar-label {
            padding: 24px 22px 10px;
            color: #8da9af;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            padding: 0 12px;
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-radius: 6px;
            color: #c7d6d9;
            font-size: 13px;
            transition: .2s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        .sidebar-menu a.active {
            background: var(--gold);
            color: #fff;
            font-weight: 800;
        }

        .menu-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 21px;
            font-size: 17px;
        }

        .menu-count {
            margin-left: auto;
            padding: 3px 7px;
            border-radius: 20px;
            background: rgba(255,255,255,.13);
            color: #fff;
            font-size: 10px;
        }

        .sidebar-bottom {
            margin: 25px 12px 20px;
            padding: 17px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 8px;
            background: rgba(255,255,255,.04);
        }

        .sidebar-bottom p {
            margin-bottom: 12px;
            color: #c1d0d3;
            font-size: 12px;
            line-height: 1.7;
        }

        .sidebar-bottom a {
            color: var(--gold-light);
            font-size: 12px;
            font-weight: 800;
        }

        /* Main area */

        .main-content {
            min-height: 100vh;
            margin-left: 258px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            min-height: 83px;
            padding: 14px 30px;
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,.97);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar h1 {
            margin: 0;
            color: var(--ocean);
            font-size: 22px;
            font-weight: 800;
        }

        .topbar-subtitle {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .icon-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: #fff;
            color: var(--ocean);
            font-size: 19px;
        }

        .icon-button:hover {
            background: var(--cream);
        }

        .mobile-menu-btn {
            display: none;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .date-display {
            color: var(--muted);
            font-size: 12px;
            text-align: right;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 17px;
            border-left: 1px solid var(--border);
        }

        .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 41px;
            height: 41px;
            border-radius: 50%;
            background: #f3e8cf;
            color: var(--ocean);
            font-weight: 800;
        }

        .profile-name {
            color: var(--ocean);
            font-size: 12px;
            font-weight: 800;
        }

        .profile-role {
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .content-area {
            padding: 28px 30px 40px;
        }

        .welcome-banner {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 27px;
            padding: 29px 30px;
            overflow: hidden;
            border-radius: 10px;
            background:
                linear-gradient(100deg,
                    rgba(5,44,57,.98),
                    rgba(7,59,76,.91)),
                url('../assets/images/resort-hero.jpg') center / cover;
            color: #fff;
        }

        .welcome-banner::after {
            position: absolute;
            top: -75px;
            right: -25px;
            width: 260px;
            height: 260px;
            border: 1px solid rgba(234,215,168,.23);
            border-radius: 50%;
            content: "";
        }

        .welcome-copy,
        .welcome-action {
            position: relative;
            z-index: 1;
        }

        .welcome-label {
            margin-bottom: 9px;
            color: var(--gold-light);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .welcome-banner h2 {
            margin-bottom: 9px;
            font-size: 25px;
            font-weight: 800;
        }

        .welcome-banner p {
            margin-bottom: 0;
            color: #d4e1e3;
            font-size: 13px;
            line-height: 1.8;
        }

        .btn-gold {
            display: inline-block;
            padding: 11px 17px;
            border: 1px solid var(--gold);
            border-radius: 5px;
            background: var(--gold);
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .btn-gold:hover {
            border-color: #b38c43;
            background: #b38c43;
            color: #fff;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }

        .section-heading h3 {
            margin: 0;
            color: var(--ocean);
            font-size: 16px;
            font-weight: 800;
        }

        .section-heading a {
            color: var(--ocean-light);
            font-size: 12px;
            font-weight: 800;
        }

        .section-heading a:hover {
            color: var(--gold);
        }

        /* Statistic cards */

        .stat-card {
            position: relative;
            height: 100%;
            padding: 20px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
            transition: .2s ease;
        }

        .stat-card:hover {
            border-color: var(--gold-light);
            box-shadow: 0 8px 24px rgba(7,59,76,.06);
            transform: translateY(-2px);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 19px;
        }

        .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 9px;
            background: #eef5f6;
            color: var(--ocean-light);
            font-size: 21px;
        }

        .stat-icon.gold {
            background: #f7f0df;
            color: #a7833c;
        }

        .stat-icon.green {
            background: #e7f5ed;
            color: var(--green);
        }

        .stat-icon.red {
            background: #fbeaea;
            color: var(--red);
        }

        .stat-change {
            color: var(--green);
            font-size: 10px;
            font-weight: 800;
        }

        .stat-label {
            margin-bottom: 7px;
            color: var(--muted);
            font-size: 12px;
        }

        .stat-value {
            color: var(--ocean);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -.5px;
            overflow-wrap: anywhere;
        }

        .stat-footer {
            margin-top: 10px;
            color: #96a2a5;
            font-size: 11px;
        }

        /* Panels */

        .panel {
            height: 100%;
            padding: 23px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
        }

        .panel-subtitle {
            margin-top: 6px;
            color: var(--muted);
            font-size: 11px;
        }

        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            color: var(--muted);
            font-size: 11px;
        }

        .legend-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            margin-right: 5px;
            border-radius: 50%;
            background: var(--gold);
        }

        .revenue-chart {
            display: flex;
            align-items: stretch;
            gap: 14px;
            height: 230px;
            margin-top: 25px;
            padding-top: 10px;
        }

        .chart-column {
            display: flex;
            flex: 1;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            min-width: 0;
        }

        .chart-value {
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 9px;
            text-align: center;
            white-space: nowrap;
        }

        .chart-bar-wrap {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            width: 100%;
            height: 155px;
            border-bottom: 1px solid var(--border);
            background-image: linear-gradient(to top,
                transparent 24%,
                #edf0f1 25%,
                transparent 25%,
                transparent 49%,
                #edf0f1 50%,
                transparent 50%,
                transparent 74%,
                #edf0f1 75%,
                transparent 75%);
        }

        .chart-bar {
            width: min(36px, 75%);
            min-height: 8px;
            border-radius: 5px 5px 0 0;
            background: #9bbdc2;
            transition: height .3s ease;
        }

        .chart-bar.current {
            background: var(--gold);
        }

        .chart-month {
            margin-top: 10px;
            color: var(--muted);
            font-size: 10px;
        }

        /* Occupancy */

        .occupancy-summary {
            display: flex;
            align-items: center;
            gap: 24px;
            margin: 12px 0 24px;
        }

        .occupancy-ring {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 126px;
            width: 126px;
            height: 126px;
            border-radius: 50%;
            background: conic-gradient(
                var(--gold) 0% 78%,
                #e9eef0 78% 100%
            );
        }

        .occupancy-ring-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            width: 98px;
            height: 98px;
            border-radius: 50%;
            background: #fff;
        }

        .occupancy-ring-inner strong {
            color: var(--ocean);
            font-size: 25px;
            font-weight: 800;
        }

        .occupancy-ring-inner span {
            color: var(--muted);
            font-size: 10px;
        }

        .occupancy-text strong {
            display: block;
            margin-bottom: 5px;
            color: var(--ocean);
            font-size: 16px;
        }

        .occupancy-text p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .room-type-row {
            margin-bottom: 17px;
        }

        .room-type-heading {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
            color: var(--text);
            font-size: 11px;
        }

        .room-type-heading span:last-child {
            color: var(--muted);
        }

        .progress {
            height: 7px;
            border-radius: 20px;
            background: #edf0f1;
        }

        .progress-bar {
            border-radius: 20px;
            background: var(--ocean-light);
        }

        .room-type-row:nth-of-type(2) .progress-bar {
            background: var(--gold);
        }

        .room-type-row:nth-of-type(3) .progress-bar {
            background: #73a99a;
        }

        .room-type-row:nth-of-type(4) .progress-bar {
            background: #b1a3c8;
        }

        /* Tables */

        .table-panel {
            padding: 0;
            overflow: hidden;
        }

        .table-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 22px 23px;
        }

        .table-responsive {
            border-top: 1px solid var(--border);
        }

        .table {
            min-width: 740px;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            background: #fbfcfc;
            color: #839095;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 15px 18px;
            border-color: #f0f2f3;
            color: #52646a;
            font-size: 11px;
        }

        .table tbody tr:hover {
            background: #fcfbf7;
        }

        .booking-reference {
            color: var(--ocean);
            font-weight: 800;
            white-space: nowrap;
        }

        .guest-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 155px;
        }

        .guest-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 34px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f2ead8;
            color: var(--ocean);
            font-size: 11px;
            font-weight: 800;
        }

        .guest-name {
            color: var(--text);
            font-size: 11px;
            font-weight: 800;
        }

        .guest-room {
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-confirmed {
            background: #e8f5ed;
            color: #198754;
        }

        .status-pending {
            background: #fff2d9;
            color: #9b6b11;
        }

        .status-checked-in {
            background: #e8f0fc;
            color: #315d9b;
        }

        .status-checked-out {
            background: #edf0f1;
            color: #65757b;
        }

        .status-expected {
            background: #fff2d9;
            color: #9b6b11;
        }

        .view-link {
            color: var(--ocean-light);
            font-size: 11px;
            font-weight: 800;
        }

        .view-link:hover {
            color: var(--gold);
        }

        /* Guest activity */

        .guest-activity {
            padding: 20px 22px;
            border-bottom: 1px solid #edf0f1;
        }

        .guest-activity:last-child {
            border-bottom: none;
        }

        .guest-activity-top {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 7px;
        }

        .guest-activity-name {
            color: var(--ocean);
            font-size: 12px;
            font-weight: 800;
        }

        .guest-activity-time {
            color: var(--muted);
            font-size: 10px;
            white-space: nowrap;
        }

        .guest-activity-room {
            color: var(--muted);
            font-size: 11px;
        }

        .guest-activity-footer {
            margin-top: 10px;
        }

        .activity-tabs {
            display: flex;
            gap: 8px;
        }

        .activity-tab {
            padding: 8px 11px;
            border: 1px solid var(--border);
            border-radius: 5px;
            background: #fff;
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
        }

        .activity-tab.active {
            border-color: var(--ocean);
            background: var(--ocean);
            color: #fff;
        }

        .activity-list {
            display: none;
        }

        .activity-list.active {
            display: block;
        }

        /* Activity timeline */

        .timeline-item {
            display: flex;
            gap: 13px;
            padding: 14px 0;
            border-bottom: 1px solid #edf0f1;
        }

        .timeline-item:last-child {
            border-bottom: none;
        }

        .timeline-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 35px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #f6f0e1;
            color: #9b7a39;
            font-size: 15px;
        }

        .timeline-title {
            color: var(--ocean);
            font-size: 11px;
            font-weight: 800;
        }

        .timeline-detail {
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
            line-height: 1.6;
        }

        .timeline-time {
            margin-top: 6px;
            color: #9ca8ab;
            font-size: 10px;
        }

        /* Quick actions */

        .quick-action {
            display: flex;
            align-items: center;
            gap: 13px;
            height: 100%;
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--text);
            transition: .2s ease;
        }

        .quick-action:hover {
            border-color: var(--gold);
            box-shadow: 0 6px 20px rgba(7,59,76,.05);
            color: var(--ocean);
            transform: translateY(-2px);
        }

        .quick-action-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: #eef5f6;
            color: var(--ocean-light);
            font-size: 19px;
        }

        .quick-action strong {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .quick-action small {
            color: var(--muted);
            font-size: 10px;
        }

        .demo-disclaimer {
            margin-top: 28px;
            padding: 13px 16px;
            border-left: 3px solid var(--gold);
            border-radius: 4px;
            background: #fffdf7;
            color: #7a7e78;
            font-size: 11px;
            line-height: 1.8;
        }

        .footer {
            padding: 0 30px 22px;
            color: #97a4a7;
            font-size: 11px;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1199px) {
            .sidebar {
                width: 235px;
            }

            .main-content {
                margin-left: 235px;
            }

            .content-area {
                padding-right: 22px;
                padding-left: 22px;
            }

            .topbar {
                padding-right: 22px;
                padding-left: 22px;
            }

            .stat-value {
                font-size: 23px;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                position: fixed;
                inset: 0;
                z-index: 1030;
                display: block;
                background: rgba(0,0,0,.4);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: inline-flex;
            }

            .welcome-banner h2 {
                font-size: 22px;
            }
        }

        @media (max-width: 575px) {
            .topbar {
                min-height: 72px;
                padding: 12px 14px;
            }

            .topbar h1 {
                font-size: 18px;
            }

            .topbar-subtitle,
            .date-display,
            .profile-details {
                display: none;
            }

            .topbar-right {
                gap: 8px;
            }

            .admin-profile {
                padding-left: 8px;
            }

            .content-area {
                padding: 18px 14px 30px;
            }

            .welcome-banner {
                align-items: flex-start;
                flex-direction: column;
                padding: 23px;
            }

            .welcome-banner h2 {
                font-size: 21px;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-value {
                font-size: 22px;
            }

            .panel {
                padding: 17px;
            }

            .table-panel {
                padding: 0;
            }

            .table-panel-header {
                padding: 18px;
            }

            .occupancy-summary {
                align-items: flex-start;
                flex-direction: column;
                gap: 15px;
            }

            .revenue-chart {
                gap: 7px;
            }

            .chart-value {
                font-size: 8px;
            }

            .footer {
                padding: 0 14px 20px;
            }
        }
    </style>
</head>

<body>

<div class="admin-wrapper">

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="adminSidebar">

        <a href="index.php" class="sidebar-brand">
            <span class="brand-mark">A</span>

            <span>
                <span class="brand-name d-block">AZURE BAY</span>
                <span class="brand-caption d-block">RESORT ADMINISTRATION</span>
            </span>
        </a>

        <div class="sidebar-label">Main Menu</div>

        <ul class="sidebar-menu">
            <li>
                <a href="index.php" class="active">
                    <span class="menu-icon">▦</span>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="rooms.php">
                    <span class="menu-icon">▤</span>
                    Room Management
                </a>
            </li>

            <li>
                <a href="reservations.php">
                    <span class="menu-icon">▣</span>
                    Reservations
                    <span class="menu-count">8</span>
                </a>
            </li>

            <li>
                <a href="customers.php">
                    <span class="menu-icon">♙</span>
                    Customers
                </a>
            </li>

            <li>
                <a href="guest_services.php">
                    <span class="menu-icon">⇄</span>
                    Check-in / Check-out
                </a>
            </li>
        </ul>

        <div class="sidebar-label">Financial Management</div>

        <ul class="sidebar-menu">
            <li>
                <a href="payments.php">
                    <span class="menu-icon">＄</span>
                    Payments
                </a>
            </li>

            <li>
                <a href="discounts.php">
                    <span class="menu-icon">％</span>
                    Discounts
                </a>
            </li>

            <li>
                <a href="reports.php">
                    <span class="menu-icon">▥</span>
                    Reports & Analytics
                </a>
            </li>
        </ul>

        <div class="sidebar-label">Room Operations</div>

        <ul class="sidebar-menu">
            <li>
                <a href="availability.php">
                    <span class="menu-icon">◷</span>
                    Room Availability
                </a>
            </li>
        </ul>

        <div class="sidebar-label">Website</div>

        <ul class="sidebar-menu">
            <li>
                <a href="../index.php" target="_blank" rel="noopener">
                    <span class="menu-icon">↗</span>
                    Customer Website
                </a>
            </li>

            <li>
                <a href="../azurebay_demo.php">
                    <span class="menu-icon">⌂</span>
                    Demo Selection
                </a>
            </li>
        </ul>

        <div class="sidebar-bottom">
            <p>
                You're viewing the Azure Bay Resort administration
                demonstration.
            </p>

            <a href="../azurebay_demo.php">
                Return to Demo Portal &rarr;
            </a>
        </div>

    </aside>


    <!-- Main Content -->
    <main class="main-content">

        <!-- Topbar -->
        <header class="topbar">

            <div class="topbar-left">

                <button type="button"
                        class="icon-button mobile-menu-btn"
                        id="menuToggle"
                        aria-label="Open navigation menu">
                    ☰
                </button>

                <div>
                    <h1>Dashboard</h1>

                    <div class="topbar-subtitle">
                        Welcome back! Here's what's happening at your resort.
                    </div>
                </div>

            </div>

            <div class="topbar-right">

                <div class="date-display">
                    <strong><?= e($today) ?></strong>
                    <div class="mt-1">Sri Lanka Standard Time</div>
                </div>

                <div class="admin-profile">
                    <div class="avatar">AD</div>

                    <div class="profile-details">
                        <div class="profile-name">Resort Administrator</div>
                        <div class="profile-role">Administrator Demo</div>
                    </div>
                </div>

            </div>

        </header>


        <!-- Dashboard Content -->
        <div class="content-area">

            <!-- Welcome Banner -->
            <section class="welcome-banner">

                <div class="welcome-copy">
                    <div class="welcome-label">
                        Azure Bay Resort · Administration
                    </div>

                    <h2>Welcome to Your Resort Dashboard</h2>

                    <p>
                        Monitor bookings, manage guest experiences, and
                        review your resort's performance from one place.
                        <br>
                        Today is <?= e($today) ?>.
                    </p>
                </div>

                <div class="welcome-action">
                    <a href="reservations.php" class="btn-gold">
                        View Reservations &rarr;
                    </a>
                </div>

            </section>


            <!-- Statistics -->
            <div class="section-heading">
                <h3>Resort Overview</h3>
                <span class="text-secondary small">
                    Illustrative figures
                </span>
            </div>

            <div class="row g-3 mb-4">

                <div class="col-6 col-xl-3">
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon">▤</div>
                            <span class="stat-change">Room Inventory</span>
                        </div>

                        <div class="stat-label">Total Rooms</div>

                        <div class="stat-value">
                            <?= number_format($stats['total_rooms']) ?>
                        </div>

                        <div class="stat-footer">
                            <?= number_format($stats['available_rooms']) ?>
                            currently available
                        </div>

                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon gold">▣</div>
                            <span class="status-badge status-pending">
                                <?= $stats['pending_bookings'] ?> pending
                            </span>
                        </div>

                        <div class="stat-label">Total Reservations</div>

                        <div class="stat-value">
                            <?= number_format($stats['reservations']) ?>
                        </div>

                        <div class="stat-footer">
                            All sample reservation records
                        </div>

                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon green">♙</div>
                            <span class="stat-change">Guest Records</span>
                        </div>

                        <div class="stat-label">Registered Customers</div>

                        <div class="stat-value">
                            <?= number_format($stats['customers']) ?>
                        </div>

                        <div class="stat-footer">
                            Sample customer accounts
                        </div>

                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon gold">◉</div>
                            <span class="stat-change">Room Utilization</span>
                        </div>

                        <div class="stat-label">Occupancy Rate</div>

                        <div class="stat-value">
                            <?= number_format($stats['occupancy']) ?>%
                        </div>

                        <div class="stat-footer">
                            Illustrative occupancy statistic
                        </div>

                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon green">＄</div>
                            <span class="stat-change">Revenue Summary</span>
                        </div>

                        <div class="stat-label">Monthly Revenue</div>

                        <div class="stat-value">
                            <?= money($stats['monthly_revenue']) ?>
                        </div>

                        <div class="stat-footer">
                            Sample total for <?= e($currentMonth) ?>
                        </div>

                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon red">◷</div>
                            <span class="status-badge status-pending">
                                Attention
                            </span>
                        </div>

                        <div class="stat-label">Pending Payments</div>

                        <div class="stat-value">
                            <?= money($stats['pending_payments']) ?>
                        </div>

                        <div class="stat-footer">
                            Outstanding sample payment amount
                        </div>

                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon">⇥</div>
                            <span class="stat-change">Guest Services</span>
                        </div>

                        <div class="stat-label">Today's Arrivals</div>

                        <div class="stat-value">
                            <?= count($arrivals) ?>
                        </div>

                        <div class="stat-footer">
                            Includes guests already checked in
                        </div>

                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon gold">⇤</div>
                            <span class="stat-change">Guest Services</span>
                        </div>

                        <div class="stat-label">Today's Departures</div>

                        <div class="stat-value">
                            <?= count($departures) ?>
                        </div>

                        <div class="stat-footer">
                            Expected and completed departures
                        </div>

                    </div>
                </div>

            </div>


            <!-- Revenue Chart and Occupancy -->
            <div class="row g-3 mb-4">

                <div class="col-xl-7">
                    <section class="panel">

                        <div class="section-heading">
                            <div>
                                <h3>Revenue Overview</h3>
                                <div class="panel-subtitle">
                                    Monthly sample revenue · <?= e($currency) ?>
                                </div>
                            </div>

                            <a href="reports.php">Full Reports &rarr;</a>
                        </div>

                        <div class="chart-legend">
                            <span>
                                <span class="legend-dot"></span>
                                Revenue
                            </span>
                            <span>
                                <span class="legend-dot"
                                      style="background:#9bbdc2;"></span>
                                Previous months
                            </span>
                        </div>

                        <div class="revenue-chart"
                             role="img"
                             aria-label="Illustrative monthly revenue bar chart">

                            <?php foreach ($revenueData as $index => $item): ?>
                                <?php
                                    $height = ($item['revenue'] / $maxRevenue) * 100;
                                ?>

                                <div class="chart-column"
                                     title="<?= e($item['month']) ?>: <?= e(money($item['revenue'])) ?>">

                                    <div class="chart-value">
                                        <?= e(number_format($item['revenue'] / 1000000, 2)) ?>M
                                    </div>

                                    <div class="chart-bar-wrap">
                                        <div
                                            class="chart-bar <?= $index === count($revenueData) - 1 ? 'current' : '' ?>"
                                            style="height: <?= e($height) ?>%;"
                                        ></div>
                                    </div>

                                    <div class="chart-month">
                                        <?= e($item['month']) ?>
                                    </div>

                                </div>
                            <?php endforeach; ?>

                        </div>

                        <div class="demo-disclaimer mt-3">
                            Revenue figures are illustrative and are not
                            calculated from real transactions.
                        </div>

                    </section>
                </div>


                <div class="col-xl-5">
                    <section class="panel">

                        <div class="section-heading">
                            <div>
                                <h3>Room Occupancy</h3>
                                <div class="panel-subtitle">
                                    Sample occupied rooms by category
                                </div>
                            </div>

                            <a href="availability.php">View Availability &rarr;</a>
                        </div>

                        <div class="occupancy-summary">

                            <div class="occupancy-ring">
                                <div class="occupancy-ring-inner">
                                    <strong>
                                        <?= number_format($stats['occupancy']) ?>%
                                    </strong>
                                    <span>Occupied</span>
                                </div>
                            </div>

                            <div class="occupancy-text">
                                <strong>
                                    <?= $stats['total_rooms'] - $stats['available_rooms'] ?>
                                    Rooms in Use
                                </strong>

                                <p>
                                    <?= $stats['available_rooms'] ?> rooms
                                    are marked available in the sample overview.
                                </p>
                            </div>

                        </div>

                        <?php foreach ($roomTypes as $type): ?>
                            <?php
                                $percentage = $type['total'] > 0
                                    ? ($type['occupied'] / $type['total']) * 100
                                    : 0;
                            ?>

                            <div class="room-type-row">

                                <div class="room-type-heading">
                                    <span><?= e($type['name']) ?></span>

                                    <span>
                                        <?= $type['occupied'] ?>/<?= $type['total'] ?>
                                        rooms
                                    </span>
                                </div>

                                <div class="progress">
                                    <div
                                        class="progress-bar"
                                        role="progressbar"
                                        style="width: <?= e($percentage) ?>%;"
                                        aria-valuenow="<?= e($percentage) ?>"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                    ></div>
                                </div>

                            </div>
                        <?php endforeach; ?>

                        <div class="demo-disclaimer mt-4">
                            Occupancy categories use illustrative sample
                            counts and are intended to demonstrate the
                            reporting interface.
                        </div>

                    </section>
                </div>

            </div>


            <!-- Recent Reservations -->
            <div class="section-heading">
                <h3>Recent Reservations</h3>
                <a href="reservations.php">Manage All Reservations &rarr;</a>
            </div>

            <section class="panel table-panel mb-4">

                <div class="table-responsive">
                    <table class="table">

                        <thead>
                            <tr>
                                <th>Reservation</th>
                                <th>Guest / Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>

                                <?php
                                    $initials = '';

                                    $nameParts = preg_split(
                                        '/\s+/',
                                        trim($reservation['guest'])
                                    );

                                    foreach (array_slice($nameParts, 0, 2) as $part) {
                                        $initials .= strtoupper(substr($part, 0, 1));
                                    }
                                ?>

                                <tr>

                                    <td>
                                        <span class="booking-reference">
                                            <?= e($reservation['id']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="guest-cell">

                                            <div class="guest-avatar">
                                                <?= e($initials) ?>
                                            </div>

                                            <div>
                                                <div class="guest-name">
                                                    <?= e($reservation['guest']) ?>
                                                </div>

                                                <div class="guest-room">
                                                    <?= e($reservation['room']) ?>
                                                </div>
                                            </div>

                                        </div>
                                    </td>

                                    <td>
                                        <?= e(dateLabel($reservation['check_in'])) ?>
                                    </td>

                                    <td>
                                        <?= e(dateLabel($reservation['check_out'])) ?>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= e(money($reservation['amount'])) ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="status-badge <?= e(statusClass($reservation['status'])) ?>">
                                            <?= e($reservation['status']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a class="view-link"
                                           href="reservations.php">
                                            View
                                        </a>
                                    </td>

                                </tr>

                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            </section>


            <!-- Guest Arrivals and Departures / Activity -->
            <div class="row g-3 mb-4">

                <div class="col-xl-7">
                    <section class="panel" style="padding:0;">

                        <div class="table-panel-header">
                            <div>
                                <h3 class="mb-1"
                                    style="color:var(--ocean);font-size:16px;font-weight:800;">
                                    Guest Activity
                                </h3>

                                <div class="panel-subtitle">
                                    Arrivals and departures for today
                                </div>
                            </div>

                            <a href="guest_services.php"
                               class="view-link">
                                Manage Guests &rarr;
                            </a>
                        </div>

                        <div class="px-3 pb-3">
                            <div class="activity-tabs">

                                <button type="button"
                                        class="activity-tab active"
                                        data-activity="arrivals">
                                    Arrivals (<?= count($arrivals) ?>)
                                </button>

                                <button type="button"
                                        class="activity-tab"
                                        data-activity="departures">
                                    Departures (<?= count($departures) ?>)
                                </button>

                            </div>
                        </div>

                        <div class="activity-list active" id="arrivals">

                            <?php foreach ($arrivals as $guest): ?>
                                <div class="guest-activity">

                                    <div class="guest-activity-top">
                                        <span class="guest-activity-name">
                                            <?= e($guest['guest']) ?>
                                        </span>

                                        <span class="guest-activity-time">
                                            <?= e($guest['time']) ?>
                                        </span>
                                    </div>

                                    <div class="guest-activity-room">
                                        <?= e($guest['room']) ?>
                                    </div>

                                    <div class="guest-activity-footer">
                                        <span class="status-badge <?= e(statusClass($guest['status'])) ?>">
                                            <?= e($guest['status']) ?>
                                        </span>
                                    </div>

                                </div>
                            <?php endforeach; ?>

                        </div>

                        <div class="activity-list" id="departures">

                            <?php foreach ($departures as $guest): ?>
                                <div class="guest-activity">

                                    <div class="guest-activity-top">
                                        <span class="guest-activity-name">
                                            <?= e($guest['guest']) ?>
                                        </span>

                                        <span class="guest-activity-time">
                                            <?= e($guest['time']) ?>
                                        </span>
                                    </div>

                                    <div class="guest-activity-room">
                                        <?= e($guest['room']) ?>
                                    </div>

                                    <div class="guest-activity-footer">
                                        <span class="status-badge <?= e(statusClass($guest['status'])) ?>">
                                            <?= e($guest['status']) ?>
                                        </span>
                                    </div>

                                </div>
                            <?php endforeach; ?>

                        </div>

                    </section>
                </div>


                <div class="col-xl-5">
                    <section class="panel">

                        <div class="section-heading">
                            <div>
                                <h3>Recent Activity</h3>
                                <div class="panel-subtitle">
                                    Sample resort operation events
                                </div>
                            </div>
                        </div>

                        <?php foreach ($recentActivities as $activity): ?>

                            <div class="timeline-item">

                                <div class="timeline-icon">
                                    <?php
                                        $activityIcons = [
                                            'calendar-check' => '▣',
                                            'credit-card' => '＄',
                                            'log-in' => '⇥',
                                            'bed-double' => '▤'
                                        ];
                                    ?>

                                    <?= e($activityIcons[$activity['icon']] ?? '•') ?>
                                </div>

                                <div>
                                    <div class="timeline-title">
                                        <?= e($activity['title']) ?>
                                    </div>

                                    <div class="timeline-detail">
                                        <?= e($activity['detail']) ?>
                                    </div>

                                    <div class="timeline-time">
                                        <?= e($activity['time']) ?>
                                    </div>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    </section>
                </div>

            </div>


            <!-- Quick Actions -->
            <div class="section-heading">
                <h3>Quick Actions</h3>
            </div>

            <div class="row g-3">

                <div class="col-6 col-xl-3">
                    <a href="rooms.php" class="quick-action">

                        <div class="quick-action-icon">＋</div>

                        <div>
                            <strong>Manage Rooms</strong>
                            <small>Add, edit, and price rooms</small>
                        </div>

                    </a>
                </div>

                <div class="col-6 col-xl-3">
                    <a href="reservations.php" class="quick-action">

                        <div class="quick-action-icon">▣</div>

                        <div>
                            <strong>Reservations</strong>
                            <small>Review guest bookings</small>
                        </div>

                    </a>
                </div>

                <div class="col-6 col-xl-3">
                    <a href="guest_services.php" class="quick-action">

                        <div class="quick-action-icon">⇄</div>

                        <div>
                            <strong>Guest Services</strong>
                            <small>Check guests in or out</small>
                        </div>

                    </a>
                </div>

                <div class="col-6 col-xl-3">
                    <a href="reports.php" class="quick-action">

                        <div class="quick-action-icon">▥</div>

                        <div>
                            <strong>View Reports</strong>
                            <small>Revenue and occupancy</small>
                        </div>

                    </a>
                </div>

            </div>


            <!-- Demo Notice -->
            <div class="demo-disclaimer">
                <strong>Portfolio demonstration:</strong>
                All figures, reservations, customers, revenue, and activity
                shown on this dashboard are sample data. They are not
                connected to a live resort or payment provider. The dashboard
                links lead to the individual demonstration pages as you
                create them.
            </div>

        </div>


        <!-- Footer -->
        <footer class="footer d-flex flex-column flex-md-row
                       justify-content-between gap-2">

            <span>
                &copy; <?= date('Y') ?> Azure Bay Resort
                · Administration Demo
            </span>

            <span>
                Developed for the Codvanta portfolio
            </span>

        </footer>

    </main>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Responsive sidebar
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuToggle = document.getElementById('menuToggle');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function () {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    // Arrivals / departures tabs
    const activityTabs = document.querySelectorAll('[data-activity]');

    activityTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activityTabs.forEach(function (item) {
                item.classList.remove('active');
            });

            document.querySelectorAll('.activity-list').forEach(function (list) {
                list.classList.remove('active');
            });

            tab.classList.add('active');

            const target = document.getElementById(
                tab.getAttribute('data-activity')
            );

            if (target) {
                target.classList.add('active');
            }
        });
    });
</script>

</body>
</html>