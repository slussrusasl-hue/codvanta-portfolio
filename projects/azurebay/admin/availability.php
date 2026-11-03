<?php
session_start();

date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Room Availability Management
|--------------------------------------------------------------------------
| Demonstration page using PHP sessions.
| No database connection is required.
*/

if (!isset($_SESSION['azurebay_admin_availability'])) {
    $_SESSION['azurebay_admin_availability'] = [];
}

if (!isset($_SESSION['azurebay_admin_availability_notes'])) {
    $_SESSION['azurebay_admin_availability_notes'] = [];
}

$rooms = [
    [
        'id' => 1,
        'number' => 'A-101',
        'name' => 'Deluxe Ocean View',
        'category' => 'Deluxe Room',
        'price' => 180,
        'capacity' => 2,
        'default' => 'Available'
    ],
    [
        'id' => 2,
        'number' => 'A-102',
        'name' => 'Garden Serenity Room',
        'category' => 'Deluxe Room',
        'price' => 150,
        'capacity' => 2,
        'default' => 'Available'
    ],
    [
        'id' => 3,
        'number' => 'B-201',
        'name' => 'Premium Beach Suite',
        'category' => 'Suite',
        'price' => 290,
        'capacity' => 3,
        'default' => 'Occupied'
    ],
    [
        'id' => 4,
        'number' => 'B-205',
        'name' => 'Family Coastal Suite',
        'category' => 'Family Suite',
        'price' => 340,
        'capacity' => 5,
        'default' => 'Available'
    ],
    [
        'id' => 5,
        'number' => 'V-001',
        'name' => 'Azure Presidential Villa',
        'category' => 'Villa',
        'price' => 450,
        'capacity' => 4,
        'default' => 'Maintenance'
    ],
    [
        'id' => 6,
        'number' => 'B-208',
        'name' => 'Horizon Ocean Suite',
        'category' => 'Suite',
        'price' => 320,
        'capacity' => 3,
        'default' => 'Available'
    ],
];

$statuses = ['Available', 'Occupied', 'Reserved', 'Maintenance'];

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function getRoomStatus($room, $date)
{
    if (isset($_SESSION['azurebay_admin_availability'][$date][$room['id']])) {
        return $_SESSION['azurebay_admin_availability'][$date][$room['id']];
    }

    // Sample schedule for the demonstration.
    $dayNumber = (int)date('j', strtotime($date));

    if ($room['id'] === 5) {
        return 'Maintenance';
    }

    if ($room['id'] === 3) {
        return ($dayNumber % 3 === 0) ? 'Available' : 'Occupied';
    }

    if ($room['id'] === 4) {
        return ($dayNumber % 4 === 0) ? 'Reserved' : 'Available';
    }

    if ($room['id'] === 6) {
        return ($dayNumber % 5 === 0) ? 'Reserved' : 'Available';
    }

    if ($room['id'] === 2 && $dayNumber % 6 === 0) {
        return 'Occupied';
    }

    return 'Available';
}

function statusClass($status)
{
    switch ($status) {
        case 'Available':
            return 'available';
        case 'Occupied':
            return 'occupied';
        case 'Reserved':
            return 'reserved';
        case 'Maintenance':
            return 'maintenance';
        default:
            return 'available';
    }
}

function statusIcon($status)
{
    switch ($status) {
        case 'Available':
            return 'bi-check-circle-fill';
        case 'Occupied':
            return 'bi-person-fill';
        case 'Reserved':
            return 'bi-calendar-check-fill';
        case 'Maintenance':
            return 'bi-tools';
        default:
            return 'bi-circle';
    }
}

$today = date('Y-m-d');
$selectedDate = $_GET['date'] ?? $today;

$dateObject = DateTime::createFromFormat('!Y-m-d', $selectedDate);

if (!$dateObject || $dateObject->format('Y-m-d') !== $selectedDate) {
    $selectedDate = $today;
}

$selectedCategory = trim($_GET['category'] ?? '');
$selectedStatus = trim($_GET['status'] ?? '');
$search = trim($_GET['search'] ?? '');

$message = '';
$messageType = 'success';

/*
|--------------------------------------------------------------------------
| Update Availability
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_availability') {
        $roomId = (int)($_POST['room_id'] ?? 0);
        $updateDate = $_POST['update_date'] ?? '';
        $newStatus = $_POST['new_status'] ?? '';
        $note = trim($_POST['note'] ?? '');

        $validDate = DateTime::createFromFormat('!Y-m-d', $updateDate);
        $roomExists = false;

        foreach ($rooms as $room) {
            if ($room['id'] === $roomId) {
                $roomExists = true;
                break;
            }
        }

        if (
            $roomExists &&
            $validDate &&
            $validDate->format('Y-m-d') === $updateDate &&
            in_array($newStatus, $statuses, true)
        ) {
            $_SESSION['azurebay_admin_availability'][$updateDate][$roomId] = $newStatus;
            $_SESSION['azurebay_admin_availability_notes'][$updateDate][$roomId] = $note;

            header(
                'Location: availability.php?date=' .
                urlencode($updateDate) .
                '&updated=1'
            );
            exit;
        } else {
            $message = 'Please provide valid room availability details.';
            $messageType = 'danger';
        }
    }

    if ($action === 'bulk_update') {
        $bulkDate = $_POST['bulk_date'] ?? '';
        $bulkStatus = $_POST['bulk_status'] ?? '';
        $bulkCategory = $_POST['bulk_category'] ?? 'All';

        $validDate = DateTime::createFromFormat('!Y-m-d', $bulkDate);

        if (
            $validDate &&
            $validDate->format('Y-m-d') === $bulkDate &&
            in_array($bulkStatus, $statuses, true)
        ) {
            $updatedCount = 0;

            foreach ($rooms as $room) {
                if (
                    $bulkCategory === 'All' ||
                    $room['category'] === $bulkCategory
                ) {
                    $_SESSION['azurebay_admin_availability'][$bulkDate][$room['id']] = $bulkStatus;
                    $_SESSION['azurebay_admin_availability_notes'][$bulkDate][$room['id']] =
                        'Bulk availability update';

                    $updatedCount++;
                }
            }

            header(
                'Location: availability.php?date=' .
                urlencode($bulkDate) .
                '&bulk_updated=' . $updatedCount
            );
            exit;
        } else {
            $message = 'Please select a valid date and status for the bulk update.';
            $messageType = 'danger';
        }
    }
}

if (isset($_GET['updated'])) {
    $message = 'Room availability has been updated successfully.';
}

if (isset($_GET['bulk_updated'])) {
    $message = (int)$_GET['bulk_updated'] .
        ' room(s) updated successfully for the selected date.';
}

/*
|--------------------------------------------------------------------------
| Filter Rooms
|--------------------------------------------------------------------------
*/

$filteredRooms = [];

foreach ($rooms as $room) {
    $roomStatus = getRoomStatus($room, $selectedDate);

    if (
        $selectedCategory !== '' &&
        $room['category'] !== $selectedCategory
    ) {
        continue;
    }

    if (
        $selectedStatus !== '' &&
        $roomStatus !== $selectedStatus
    ) {
        continue;
    }

    if (
        $search !== '' &&
        stripos($room['number'], $search) === false &&
        stripos($room['name'], $search) === false &&
        stripos($room['category'], $search) === false
    ) {
        continue;
    }

    $room['current_status'] = $roomStatus;
    $filteredRooms[] = $room;
}

/*
|--------------------------------------------------------------------------
| Statistics For Selected Date
|--------------------------------------------------------------------------
*/

$totalRooms = count($rooms);
$availableCount = 0;
$occupiedCount = 0;
$reservedCount = 0;
$maintenanceCount = 0;

foreach ($rooms as $room) {
    $roomStatus = getRoomStatus($room, $selectedDate);

    if ($roomStatus === 'Available') {
        $availableCount++;
    } elseif ($roomStatus === 'Occupied') {
        $occupiedCount++;
    } elseif ($roomStatus === 'Reserved') {
        $reservedCount++;
    } elseif ($roomStatus === 'Maintenance') {
        $maintenanceCount++;
    }
}

$occupancyRate = $totalRooms > 0
    ? round(($occupiedCount / $totalRooms) * 100)
    : 0;

$bookableRooms = $totalRooms - $maintenanceCount;
$bookableOccupancyRate = $bookableRooms > 0
    ? round((($occupiedCount + $reservedCount) / $bookableRooms) * 100)
    : 0;

$categories = array_values(array_unique(array_column($rooms, 'category')));

$selectedDateLabel = date('l, d F Y', strtotime($selectedDate));

/*
|--------------------------------------------------------------------------
| Seven-Day Overview
|--------------------------------------------------------------------------
*/

$weekDates = [];

for ($i = 0; $i < 7; $i++) {
    $weekDates[] = date(
        'Y-m-d',
        strtotime($selectedDate . " +$i days")
    );
}

/*
|--------------------------------------------------------------------------
| Calendar Month
|--------------------------------------------------------------------------
*/

$monthStart = new DateTime(date('Y-m-01', strtotime($selectedDate)));
$daysInMonth = (int)$monthStart->format('t');
$firstWeekday = (int)$monthStart->format('N');

$previousMonth = date('Y-m-d', strtotime($selectedDate . ' first day of previous month'));
$nextMonth = date('Y-m-d', strtotime($selectedDate . ' first day of next month'));

$calendarMonth = $monthStart->format('F Y');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Room Availability | Azure Bay Resort Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --navy: #123247;
            --navy-dark: #0b2536;
            --ocean: #197b91;
            --ocean-light: #eaf5f7;
            --gold: #c9a45c;
            --cream: #fbf8f1;
            --text: #34495e;
            --muted: #81909d;
            --border: #e6edf0;
            --green: #23855b;
            --red: #c84d50;
            --orange: #d18a25;
            --purple: #7857ad;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f7f9;
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1000;
            overflow-y: auto;
            background: var(--navy-dark);
            color: #fff;
            padding: 25px 17px;
        }

        .brand {
            padding: 0 12px 27px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            margin-bottom: 25px;
        }

        .brand-mark {
            width: 43px;
            height: 43px;
            border-radius: 14px;
            background: var(--gold);
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: var(--navy-dark);
            font-size: 23px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .brand-name {
            display: inline-block;
            vertical-align: middle;
            font-size: 19px;
            font-weight: bold;
            line-height: 1.2;
        }

        .brand-name small {
            display: block;
            color: #b8c8d1;
            font-size: 10px;
            font-weight: normal;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .menu-label {
            font-size: 10px;
            color: #8299a7;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            padding: 0 13px;
            margin: 22px 0 9px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-radius: 9px;
            margin: 4px 0;
            color: #c8d5dc;
            font-size: 13px;
            transition: .2s;
        }

        .sidebar-link i {
            font-size: 17px;
            width: 22px;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: rgba(201,164,92,.18);
        }

        .sidebar-link.active {
            border-left: 3px solid var(--gold);
            padding-left: 10px;
        }

        .sidebar-footer {
            margin: 30px 5px 0;
            padding: 16px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            color: #bdcbd3;
            font-size: 11px;
            line-height: 1.7;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            min-height: 78px;
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 17px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .topbar small {
            color: var(--muted);
        }

        .admin-avatar {
            width: 39px;
            height: 39px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--ocean-light);
            color: var(--ocean);
            font-weight: bold;
        }

        .content {
            padding: 29px;
            max-width: 1650px;
            margin: auto;
        }

        .page-title {
            font-size: 27px;
            color: var(--navy);
            font-weight: bold;
            margin-bottom: 7px;
        }

        .page-subtitle {
            color: var(--muted);
            margin-bottom: 25px;
        }

        .gold-text {
            color: var(--gold);
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 22px;
            margin-bottom: 23px;
            box-shadow: 0 4px 15px rgba(25,54,72,.025);
        }

        .panel-title {
            font-size: 17px;
            color: var(--navy);
            font-weight: bold;
            margin-bottom: 5px;
        }

        .panel-subtitle {
            color: var(--muted);
            font-size: 12px;
        }

        .stat-card {
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--border);
            padding: 19px;
            height: 100%;
            box-shadow: 0 4px 15px rgba(25,54,72,.025);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 20px;
        }

        .icon-blue {
            color: var(--ocean);
            background: #eaf5f7;
        }

        .icon-green {
            color: var(--green);
            background: #e8f6ee;
        }

        .icon-orange {
            color: var(--orange);
            background: #fff4df;
        }

        .icon-red {
            color: var(--red);
            background: #fff0ef;
        }

        .stat-number {
            font-size: 29px;
            font-weight: bold;
            color: var(--navy);
            line-height: 1.1;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            margin-top: 7px;
        }

        .btn-ocean {
            background: var(--navy);
            color: #fff;
            border: 1px solid var(--navy);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-ocean:hover {
            background: var(--ocean);
            color: #fff;
            border-color: var(--ocean);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--navy-dark);
            border: 1px solid var(--gold);
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-gold:hover {
            background: #b78f43;
            color: #fff;
        }

        .btn-light-custom {
            border: 1px solid var(--border);
            color: var(--text);
            background: #fff;
            border-radius: 8px;
            padding: 9px 13px;
            font-size: 12px;
        }

        .form-label {
            font-size: 12px;
            font-weight: bold;
            color: var(--navy);
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            border: 1px solid #dfe7eb;
            border-radius: 8px;
            font-size: 12px;
            min-height: 42px;
            color: var(--text);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--ocean);
            box-shadow: 0 0 0 .2rem rgba(25,123,145,.1);
        }

        .selected-date {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: var(--cream);
            border: 1px solid #eee2c8;
            color: var(--navy);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 20px;
            padding: 7px 10px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-available {
            background: #e7f6ed;
            color: #207b4c;
        }

        .status-occupied {
            background: #ffeded;
            color: #bc4146;
        }

        .status-reserved {
            background: #fff3dc;
            color: #a66a12;
        }

        .status-maintenance {
            background: #eee8fa;
            color: #7150a6;
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #697b87;
            font-size: 11px;
            margin: 5px 12px 5px 0;
        }

        .legend-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
        }

        .dot-available { background: var(--green); }
        .dot-occupied { background: var(--red); }
        .dot-reserved { background: var(--orange); }
        .dot-maintenance { background: var(--purple); }

        .availability-table {
            vertical-align: middle;
            margin-bottom: 0;
        }

        .availability-table thead th {
            background: #f7fafb;
            color: #6e818d;
            border-bottom: 1px solid var(--border);
            padding: 14px 13px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .6px;
            white-space: nowrap;
        }

        .availability-table tbody td {
            padding: 15px 13px;
            border-color: #edf1f3;
            font-size: 12px;
        }

        .room-name {
            font-weight: bold;
            color: var(--navy);
            margin-bottom: 4px;
        }

        .room-number {
            font-size: 11px;
            color: var(--muted);
        }

        .room-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--ocean-light);
            color: var(--ocean);
            font-size: 19px;
        }

        .table-action {
            width: 34px;
            height: 34px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            border: 1px solid var(--border);
            color: var(--navy);
            background: #fff;
        }

        .table-action:hover {
            color: var(--ocean);
            background: var(--ocean-light);
        }

        .week-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(75px, 1fr));
            gap: 9px;
            margin-top: 18px;
        }

        .week-day {
            min-width: 0;
            border: 1px solid var(--border);
            border-radius: 11px;
            padding: 12px 7px;
            text-align: center;
            color: var(--text);
            background: #fff;
            transition: .2s;
        }

        .week-day:hover {
            border-color: var(--gold);
            background: var(--cream);
        }

        .week-day.selected {
            background: var(--navy);
            color: #fff;
            border-color: var(--navy);
        }

        .week-day .day-name {
            display: block;
            font-size: 10px;
            opacity: .75;
            margin-bottom: 8px;
        }

        .week-day .day-number {
            font-size: 20px;
            font-weight: bold;
        }

        .week-day .day-month {
            display: block;
            font-size: 10px;
            margin-top: 4px;
            opacity: .8;
        }

        .week-summary {
            margin-top: 10px;
            font-size: 10px;
            line-height: 1.7;
            color: var(--muted);
        }

        .week-day.selected .week-summary {
            color: #e1eaf0;
        }

        .progress {
            height: 7px;
            border-radius: 20px;
            background: #edf1f3;
        }

        .progress-bar {
            background: var(--ocean);
            border-radius: 20px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 5px;
        }

        .calendar-weekday {
            text-align: center;
            color: var(--muted);
            font-size: 10px;
            padding: 9px 0;
            font-weight: bold;
        }

        .calendar-day {
            min-height: 65px;
            border: 1px solid #edf1f3;
            border-radius: 8px;
            padding: 7px;
            text-align: center;
            background: #fff;
            color: var(--text);
        }

        .calendar-day:hover {
            background: var(--cream);
            border-color: var(--gold);
        }

        .calendar-day.active {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
        }

        .calendar-day.today {
            border-color: var(--gold);
        }

        .calendar-day.empty {
            border-color: transparent;
            background: transparent;
        }

        .calendar-day-number {
            font-size: 12px;
            font-weight: bold;
        }

        .calendar-day-info {
            font-size: 9px;
            margin-top: 7px;
            opacity: .8;
        }

        .empty-state {
            padding: 45px 15px;
            text-align: center;
            color: var(--muted);
        }

        .empty-state i {
            font-size: 35px;
            color: #b7c8d0;
        }

        .empty-state h6 {
            color: var(--navy);
            margin-top: 12px;
        }

        .footer {
            padding: 20px 30px;
            color: var(--muted);
            font-size: 11px;
            border-top: 1px solid var(--border);
            background: #fff;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            background: var(--navy);
            color: #fff;
            padding: 20px 23px;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 15px 24px 23px;
            border-top: 0;
        }

        @media (max-width: 1100px) {
            .sidebar {
                width: 215px;
            }

            .main {
                margin-left: 215px;
            }

            .content {
                padding: 22px;
            }

            .week-grid {
                gap: 5px;
            }
        }

        @media (max-width: 767px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 15px;
            }

            .brand {
                padding-bottom: 15px;
                margin-bottom: 12px;
            }

            .sidebar .menu-label,
            .sidebar-footer {
                display: none;
            }

            .sidebar nav {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .sidebar-link {
                padding: 9px 10px;
                margin: 0;
                font-size: 11px;
            }

            .sidebar-link i {
                font-size: 14px;
                width: auto;
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                padding: 15px 18px;
            }

            .content {
                padding: 18px 12px;
            }

            .page-title {
                font-size: 23px;
            }

            .panel {
                padding: 16px;
            }

            .week-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .week-day {
                padding: 10px 4px;
            }

            .stat-number {
                font-size: 25px;
            }

            .footer {
                padding: 18px;
            }
        }

        @media print {
            .sidebar,
            .topbar,
            .no-print,
            .footer,
            .table-action {
                display: none !important;
            }

            .main {
                margin: 0;
            }

            .content {
                padding: 0;
                max-width: none;
            }

            body {
                background: #fff;
            }

            .panel,
            .stat-card {
                box-shadow: none;
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <span class="brand-mark">
            <i class="bi bi-water"></i>
        </span>
        <span class="brand-name">
            AZURE BAY
            <small>RESORT ADMINISTRATION</small>
        </span>
    </div>

    <nav>
        <div class="menu-label">Main Menu</div>

        <a href="index.php" class="sidebar-link">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <a href="rooms.php" class="sidebar-link">
            <i class="bi bi-door-open"></i> Room Management
        </a>

        <a href="availability.php" class="sidebar-link active">
            <i class="bi bi-calendar2-week"></i> Room Availability
        </a>

        <a href="reservations.php" class="sidebar-link">
            <i class="bi bi-calendar-check"></i> Reservations
        </a>

        <a href="customers.php" class="sidebar-link">
            <i class="bi bi-people"></i> Customers
        </a>

        <div class="menu-label">Hotel Operations</div>

        <a href="checkin_checkout.php" class="sidebar-link">
            <i class="bi bi-person-check"></i> Check-in / Check-out
        </a>

        <a href="payments.php" class="sidebar-link">
            <i class="bi bi-credit-card"></i> Payments
        </a>

        <a href="discounts.php" class="sidebar-link">
            <i class="bi bi-tags"></i> Discounts & Offers
        </a>

        <a href="guest_services.php" class="sidebar-link">
            <i class="bi bi-bell"></i> Guest Services
        </a>

        <div class="menu-label">Analytics</div>

        <a href="reports.php" class="sidebar-link">
            <i class="bi bi-bar-chart-line"></i> Revenue Reports
        </a>

        <a href="../index.php" class="sidebar-link">
            <i class="bi bi-box-arrow-up-right"></i> View Resort Website
        </a>
    </nav>

    <div class="sidebar-footer">
        <i class="bi bi-shield-check gold-text"></i>
        <strong class="text-white">Administrator Demo</strong>
        <br>
        You are viewing a simulated room availability management system.
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="main">

    <!-- TOPBAR -->
    <header class="topbar">
        <div>
            <div class="fw-bold" style="color:var(--navy);">
                Room Availability
            </div>
            <small>Manage room status and daily inventory</small>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div class="fw-bold" style="font-size:12px;color:var(--navy);">
                    Resort Administrator
                </div>
                <small>System Management</small>
            </div>

            <div class="admin-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
        </div>
    </header>

    <div class="content">

        <!-- PAGE HEADING -->
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <div class="page-title">
                    Room <span class="gold-text">Availability</span>
                </div>

                <p class="page-subtitle mb-0">
                    Monitor occupancy, manage maintenance blocks and control
                    room availability by date.
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap no-print">
                <button type="button"
                        class="btn-light-custom"
                        onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print Report
                </button>

                <button type="button"
                        class="btn-ocean"
                        data-bs-toggle="modal"
                        data-bs-target="#bulkUpdateModal">
                    <i class="bi bi-calendar2-check me-1"></i>
                    Bulk Update
                </button>
            </div>
        </div>

        <?php if ($message !== ''): ?>
            <div class="alert alert-<?= e($messageType) ?> alert-dismissible fade show"
                 role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <?= e($message) ?>
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- SELECTED DATE -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div class="selected-date">
                <i class="bi bi-calendar3 gold-text fs-5"></i>
                <span><?= e($selectedDateLabel) ?></span>
            </div>

            <form method="GET" class="d-flex gap-2 align-items-center no-print">
                <label for="date" class="form-label mb-0">View date</label>

                <input type="date"
                       class="form-control"
                       id="date"
                       name="date"
                       value="<?= e($selectedDate) ?>"
                       style="width:170px;"
                       required>

                <button type="submit" class="btn-ocean">
                    <i class="bi bi-search me-1"></i> View
                </button>
            </form>
        </div>

        <!-- STATISTICS -->
        <div class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-blue">
                            <i class="bi bi-door-closed"></i>
                        </div>
                        <span class="text-muted" style="font-size:10px;">TOTAL INVENTORY</span>
                    </div>

                    <div class="stat-number"><?= $totalRooms ?></div>
                    <div class="stat-label">Registered resort rooms</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-green">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <span class="text-muted" style="font-size:10px;">AVAILABLE</span>
                    </div>

                    <div class="stat-number"><?= $availableCount ?></div>
                    <div class="stat-label">Rooms available for booking</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-red">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="text-muted" style="font-size:10px;">OCCUPIED</span>
                    </div>

                    <div class="stat-number"><?= $occupiedCount ?></div>
                    <div class="stat-label">Currently marked as occupied</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-orange">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <span class="text-muted" style="font-size:10px;">OCCUPANCY</span>
                    </div>

                    <div class="stat-number"><?= $occupancyRate ?>%</div>
                    <div class="stat-label">Occupied rooms / total rooms</div>

                    <div class="progress mt-3">
                        <div class="progress-bar"
                             style="width:<?= $occupancyRate ?>%;"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- WEEKLY OVERVIEW -->
        <section class="panel">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="panel-title">7-Day Availability Overview</div>
                    <div class="panel-subtitle">
                        Select a date to view the room schedule for that day.
                    </div>
                </div>

                <div class="d-flex gap-2 no-print">
                    <a href="availability.php?date=<?= e($previousMonth) ?>"
                       class="btn-light-custom">
                        <i class="bi bi-chevron-left"></i>
                        Previous month
                    </a>

                    <a href="availability.php?date=<?= e($nextMonth) ?>"
                       class="btn-light-custom">
                        Next month
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div class="week-grid">
                <?php foreach ($weekDates as $dayDate): ?>
                    <?php
                    $dayAvailable = 0;
                    $dayOccupied = 0;
                    $dayReserved = 0;
                    $dayMaintenance = 0;

                    foreach ($rooms as $room) {
                        $dayStatus = getRoomStatus($room, $dayDate);

                        if ($dayStatus === 'Available') {
                            $dayAvailable++;
                        } elseif ($dayStatus === 'Occupied') {
                            $dayOccupied++;
                        } elseif ($dayStatus === 'Reserved') {
                            $dayReserved++;
                        } elseif ($dayStatus === 'Maintenance') {
                            $dayMaintenance++;
                        }
                    }

                    $isSelected = $dayDate === $selectedDate;
                    ?>

                    <a href="availability.php?date=<?= e($dayDate) ?>"
                       class="week-day <?= $isSelected ? 'selected' : '' ?>">

                        <span class="day-name">
                            <?= e(date('D', strtotime($dayDate))) ?>
                        </span>

                        <span class="day-number">
                            <?= e(date('d', strtotime($dayDate))) ?>
                        </span>

                        <span class="day-month">
                            <?= e(date('M', strtotime($dayDate))) ?>
                        </span>

                        <div class="week-summary">
                            <div>
                                <i class="bi bi-check-circle-fill"></i>
                                <?= $dayAvailable ?> available
                            </div>
                            <div>
                                <i class="bi bi-person-fill"></i>
                                <?= $dayOccupied ?> occupied
                            </div>
                            <div>
                                <i class="bi bi-calendar-check"></i>
                                <?= $dayReserved ?> reserved
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="mt-4">
                <span class="legend-item">
                    <span class="legend-dot dot-available"></span> Available
                </span>
                <span class="legend-item">
                    <span class="legend-dot dot-occupied"></span> Occupied
                </span>
                <span class="legend-item">
                    <span class="legend-dot dot-reserved"></span> Reserved
                </span>
                <span class="legend-item">
                    <span class="legend-dot dot-maintenance"></span> Maintenance
                </span>
            </div>
        </section>

        <!-- MONTH CALENDAR AND OCCUPANCY SUMMARY -->
        <div class="row g-3 mb-4">

            <div class="col-xl-7">
                <section class="panel h-100 mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="panel-title">Monthly Calendar</div>
                            <div class="panel-subtitle">
                                <?= e($calendarMonth) ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2 no-print">
                            <a href="availability.php?date=<?= e($previousMonth) ?>"
                               class="table-action"
                               aria-label="Previous month">
                                <i class="bi bi-chevron-left"></i>
                            </a>

                            <a href="availability.php?date=<?= e($nextMonth) ?>"
                               class="table-action"
                               aria-label="Next month">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="calendar-grid">
                        <?php foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $weekday): ?>
                            <div class="calendar-weekday">
                                <?= e($weekday) ?>
                            </div>
                        <?php endforeach; ?>

                        <?php for ($blank = 1; $blank < $firstWeekday; $blank++): ?>
                            <div class="calendar-day empty"></div>
                        <?php endfor; ?>

                        <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                            <?php
                            $calendarDate = $monthStart->format('Y-m-') .
                                str_pad((string)$day, 2, '0', STR_PAD_LEFT);

                            $calendarAvailable = 0;
                            $calendarOccupied = 0;

                            foreach ($rooms as $room) {
                                $calendarStatus = getRoomStatus($room, $calendarDate);

                                if ($calendarStatus === 'Available') {
                                    $calendarAvailable++;
                                } elseif ($calendarStatus === 'Occupied') {
                                    $calendarOccupied++;
                                }
                            }

                            $calendarClass = 'calendar-day';

                            if ($calendarDate === $selectedDate) {
                                $calendarClass .= ' active';
                            }

                            if ($calendarDate === $today) {
                                $calendarClass .= ' today';
                            }
                            ?>

                            <a href="availability.php?date=<?= e($calendarDate) ?>"
                               class="<?= e($calendarClass) ?>">

                                <div class="calendar-day-number">
                                    <?= $day ?>
                                </div>

                                <div class="calendar-day-info">
                                    <?= $calendarAvailable ?> free
                                    <br>
                                    <?= $calendarOccupied ?> occupied
                                </div>
                            </a>
                        <?php endfor; ?>
                    </div>

                    <div class="mt-3 text-muted" style="font-size:10px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Choose any date to inspect or update room availability.
                    </div>
                </section>
            </div>

            <div class="col-xl-5">
                <section class="panel h-100 mb-0">
                    <div class="panel-title">Occupancy Summary</div>

                    <div class="panel-subtitle mb-4">
                        Room status distribution for <?= e(date('d M Y', strtotime($selectedDate))) ?>
                    </div>

                    <?php
                    $summaryRows = [
                        [
                            'label' => 'Available',
                            'count' => $availableCount,
                            'color' => '#23855b'
                        ],
                        [
                            'label' => 'Occupied',
                            'count' => $occupiedCount,
                            'color' => '#c84d50'
                        ],
                        [
                            'label' => 'Reserved',
                            'count' => $reservedCount,
                            'color' => '#d18a25'
                        ],
                        [
                            'label' => 'Maintenance',
                            'count' => $maintenanceCount,
                            'color' => '#7857ad'
                        ],
                    ];
                    ?>

                    <?php foreach ($summaryRows as $summary): ?>
                        <?php
                        $percentage = $totalRooms > 0
                            ? round(($summary['count'] / $totalRooms) * 100)
                            : 0;
                        ?>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span style="font-size:12px;font-weight:bold;color:var(--navy);">
                                    <?= e($summary['label']) ?>
                                </span>

                                <span style="font-size:12px;color:var(--muted);">
                                    <?= $summary['count'] ?> rooms
                                    (<?= $percentage ?>%)
                                </span>
                            </div>

                            <div class="progress">
                                <div class="progress-bar"
                                     style="width:<?= $percentage ?>%;background:<?= e($summary['color']) ?>;">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="p-3 rounded-3 mt-2"
                         style="background:var(--cream);border:1px solid #eee2c8;">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-size:12px;color:var(--navy);font-weight:bold;">
                                Sellable rooms
                            </span>

                            <strong style="color:var(--navy);">
                                <?= $bookableRooms ?>
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size:11px;color:var(--muted);">
                                Occupied + reserved / sellable rooms
                            </span>

                            <strong class="gold-text">
                                <?= $bookableOccupancyRate ?>%
                            </strong>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- ROOM AVAILABILITY TABLE -->
        <section class="panel">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <div class="panel-title">Room Availability Register</div>
                    <div class="panel-subtitle">
                        View room status and make changes for the selected date.
                    </div>
                </div>

                <div class="no-print">
                    <span class="badge rounded-pill text-bg-light border px-3 py-2">
                        <?= count($filteredRooms) ?> room(s) displayed
                    </span>
                </div>
            </div>

            <!-- FILTERS -->
            <form method="GET" class="row g-3 align-items-end mb-4 no-print">

                <div class="col-md-3">
                    <label class="form-label" for="filterDate">Availability date</label>
                    <input type="date"
                           name="date"
                           id="filterDate"
                           class="form-control"
                           value="<?= e($selectedDate) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="search">Search room</label>
                    <input type="text"
                           name="search"
                           id="search"
                           class="form-control"
                           placeholder="Room number or name"
                           value="<?= e($search) ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="category">Room category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All categories</option>

                        <?php foreach ($categories as $category): ?>
                            <option value="<?= e($category) ?>"
                                <?= $selectedCategory === $category ? 'selected' : '' ?>>
                                <?= e($category) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="status">Room status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All statuses</option>

                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= e($status) ?>"
                                <?= $selectedStatus === $status ? 'selected' : '' ?>>
                                <?= e($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn-ocean flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>

                    <a href="availability.php?date=<?= e($selectedDate) ?>"
                       class="btn-light-custom d-flex align-items-center justify-content-center"
                       title="Reset filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>

            </form>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table availability-table">
                    <thead>
                    <tr>
                        <th>Room</th>
                        <th>Category</th>
                        <th>Capacity</th>
                        <th>Nightly Rate</th>
                        <th>Status</th>
                        <th>Schedule Note</th>
                        <th class="no-print">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (count($filteredRooms) > 0): ?>

                        <?php foreach ($filteredRooms as $room): ?>
                            <?php
                            $roomNote = $_SESSION['azurebay_admin_availability_notes']
                                [$selectedDate][$room['id']] ?? '';

                            if ($roomNote === '') {
                                if ($room['current_status'] === 'Maintenance') {
                                    $roomNote = 'Maintenance block';
                                } elseif ($room['current_status'] === 'Reserved') {
                                    $roomNote = 'Existing reservation';
                                } elseif ($room['current_status'] === 'Occupied') {
                                    $roomNote = 'Guest currently occupying';
                                } else {
                                    $roomNote = 'Open for bookings';
                                }
                            }
                            ?>

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="room-icon">
                                            <i class="bi bi-door-closed"></i>
                                        </div>

                                        <div>
                                            <div class="room-name">
                                                <?= e($room['name']) ?>
                                            </div>
                                            <div class="room-number">
                                                Room <?= e($room['number']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td><?= e($room['category']) ?></td>

                                <td>
                                    <i class="bi bi-people me-1 text-muted"></i>
                                    <?= (int)$room['capacity'] ?> guests
                                </td>

                                <td>
                                    <strong style="color:var(--navy);">
                                        $<?= number_format($room['price'], 2) ?>
                                    </strong>
                                    <div class="text-muted" style="font-size:10px;">
                                        per night
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge status-<?= e(statusClass($room['current_status'])) ?>">
                                        <i class="bi <?= e(statusIcon($room['current_status'])) ?>"></i>
                                        <?= e($room['current_status']) ?>
                                    </span>
                                </td>

                                <td>
                                    <span style="font-size:11px;color:var(--muted);">
                                        <?= e($roomNote) ?>
                                    </span>
                                </td>

                                <td class="no-print">
                                    <button type="button"
                                            class="table-action"
                                            title="Manage availability"
                                            data-bs-toggle="modal"
                                            data-bs-target="#availabilityModal"
                                            data-room-id="<?= (int)$room['id'] ?>"
                                            data-room-number="<?= e($room['number']) ?>"
                                            data-room-name="<?= e($room['name']) ?>"
                                            data-room-status="<?= e($room['current_status']) ?>"
                                            data-room-note="<?= e($roomNote) ?>"
                                            data-update-date="<?= e($selectedDate) ?>">

                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-search"></i>
                                    <h6>No rooms found</h6>
                                    <p class="mb-0">
                                        Try changing your search or filter settings.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-muted" style="font-size:10px;">
                <i class="bi bi-info-circle me-1"></i>
                Availability is displayed for <?= e($selectedDateLabel) ?>.
                Room rates are sample demonstration prices.
            </div>
        </section>

        <!-- MANAGEMENT GUIDANCE -->
        <section class="panel">
            <div class="d-flex gap-3 align-items-start">
                <div class="stat-icon icon-blue flex-shrink-0">
                    <i class="bi bi-lightbulb"></i>
                </div>

                <div>
                    <div class="panel-title">Availability Management Guide</div>

                    <p class="panel-subtitle mb-2">
                        Use the availability controls to demonstrate everyday
                        hotel room management.
                    </p>

                    <ul class="mb-0 ps-3" style="font-size:12px;line-height:2;color:var(--text);">
                        <li><strong>Available:</strong> The room can be offered for new reservations.</li>
                        <li><strong>Occupied:</strong> A guest is currently staying in the room.</li>
                        <li><strong>Reserved:</strong> The room has a reservation for the selected date.</li>
                        <li><strong>Maintenance:</strong> The room is temporarily unavailable for maintenance or repair.</li>
                    </ul>
                </div>
            </div>
        </section>

    </div>

    <footer class="footer d-flex justify-content-between flex-wrap gap-2">
        <span>© <?= date('Y') ?> Azure Bay Resort. All rights reserved.</span>
        <span>Room Availability Management · Administrator Demo</span>
    </footer>
</main>

<!-- EDIT AVAILABILITY MODAL -->
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold mb-1">
                            Manage Room Availability
                        </h5>
                        <small class="text-white-50">
                            Update the status for a specific date
                        </small>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden"
                           name="action"
                           value="update_availability">

                    <input type="hidden" name="room_id" id="modalRoomId">

                    <div class="p-3 rounded-3 mb-4"
                         style="background:var(--ocean-light);">

                        <div class="d-flex align-items-center gap-3">
                            <div class="room-icon">
                                <i class="bi bi-door-closed"></i>
                            </div>

                            <div>
                                <div id="modalRoomName"
                                     class="fw-bold"
                                     style="color:var(--navy);">
                                </div>

                                <div id="modalRoomNumber"
                                     class="text-muted"
                                     style="font-size:11px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="modalUpdateDate">
                            Availability Date
                        </label>

                        <input type="date"
                               name="update_date"
                               id="modalUpdateDate"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="modalNewStatus">
                            Room Status
                        </label>

                        <select name="new_status"
                                id="modalNewStatus"
                                class="form-select"
                                required>

                            <option value="Available">Available</option>
                            <option value="Occupied">Occupied</option>
                            <option value="Reserved">Reserved</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="modalNote">
                            Schedule Note
                        </label>

                        <textarea name="note"
                                  id="modalNote"
                                  class="form-control"
                                  rows="3"
                                  maxlength="500"
                                  placeholder="e.g. Bathroom repair, guest reservation, or room inspection"></textarea>
                    </div>

                    <div class="alert alert-warning mb-0"
                         style="font-size:11px;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        This demonstration saves changes in the current PHP
                        session. It does not modify a real reservation or
                        maintenance database.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn-light-custom"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn-gold">
                        <i class="bi bi-check2-circle me-1"></i>
                        Save Availability
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- BULK UPDATE MODAL -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold mb-1">
                            Bulk Availability Update
                        </h5>
                        <small class="text-white-50">
                            Update several rooms at once
                        </small>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden"
                           name="action"
                           value="bulk_update">

                    <div class="alert alert-warning"
                         style="font-size:11px;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Bulk updates overwrite the selected status for every
                        matching room on the chosen date. Use this carefully
                        in the demonstration.
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="bulkDate">
                            Availability Date
                        </label>

                        <input type="date"
                               class="form-control"
                               name="bulk_date"
                               id="bulkDate"
                               value="<?= e($selectedDate) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="bulkCategory">
                            Apply to Room Category
                        </label>

                        <select class="form-select"
                                name="bulk_category"
                                id="bulkCategory">

                            <option value="All">All room categories</option>

                            <?php foreach ($categories as $category): ?>
                                <option value="<?= e($category) ?>">
                                    <?= e($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="bulkStatus">
                            New Status
                        </label>

                        <select class="form-select"
                                name="bulk_status"
                                id="bulkStatus"
                                required>

                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= e($status) ?>">
                                    <?= e($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn-light-custom"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn-ocean">
                        <i class="bi bi-calendar2-check me-1"></i>
                        Apply Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const availabilityModal = document.getElementById('availabilityModal');

    availabilityModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        if (!button) {
            return;
        }

        document.getElementById('modalRoomId').value =
            button.getAttribute('data-room-id');

        document.getElementById('modalRoomName').textContent =
            button.getAttribute('data-room-name');

        document.getElementById('modalRoomNumber').textContent =
            'Room ' + button.getAttribute('data-room-number');

        document.getElementById('modalUpdateDate').value =
            button.getAttribute('data-update-date');

        document.getElementById('modalNewStatus').value =
            button.getAttribute('data-room-status');

        document.getElementById('modalNote').value =
            button.getAttribute('data-room-note');
    });
});
</script>

</body>
</html>