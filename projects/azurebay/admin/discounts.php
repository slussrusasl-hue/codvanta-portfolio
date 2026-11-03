
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/* ==========================================
   AZURE BAY RESORT - DISCOUNT MANAGEMENT
   Session-based demonstration; no database.
========================================== */

if (!isset($_SESSION['azurebay_admin_discounts'])) {
    $_SESSION['azurebay_admin_discounts'] = [
        [
            'id' => 1,
            'code' => 'WELCOME10',
            'name' => 'Welcome to Azure Bay',
            'description' => 'A welcome discount for guests making their first reservation.',
            'type' => 'Percentage',
            'value' => 10,
            'min_booking' => 100,
            'max_discount' => 100,
            'start_date' => date('Y-m-d', strtotime('-15 days')),
            'end_date' => date('Y-m-d', strtotime('+60 days')),
            'usage_limit' => 100,
            'used_count' => 28,
            'room_type' => 'All Rooms',
            'status' => 'Active'
        ],
        [
            'id' => 2,
            'code' => 'SUMMER20',
            'name' => 'Summer Escape',
            'description' => 'Enjoy a seasonal discount on your resort getaway.',
            'type' => 'Percentage',
            'value' => 20,
            'min_booking' => 250,
            'max_discount' => 200,
            'start_date' => date('Y-m-d', strtotime('-5 days')),
            'end_date' => date('Y-m-d', strtotime('+30 days')),
            'usage_limit' => 80,
            'used_count' => 35,
            'room_type' => 'All Rooms',
            'status' => 'Active'
        ],
        [
            'id' => 3,
            'code' => 'SUITE50',
            'name' => 'Suite Special',
            'description' => 'A special fixed-value offer for premium suite bookings.',
            'type' => 'Fixed Amount',
            'value' => 50,
            'min_booking' => 300,
            'max_discount' => 50,
            'start_date' => date('Y-m-d', strtotime('-10 days')),
            'end_date' => date('Y-m-d', strtotime('+90 days')),
            'usage_limit' => 50,
            'used_count' => 17,
            'room_type' => 'Suites',
            'status' => 'Active'
        ],
        [
            'id' => 4,
            'code' => 'FAMILY15',
            'name' => 'Family Holiday',
            'description' => 'Save on selected family accommodation.',
            'type' => 'Percentage',
            'value' => 15,
            'min_booking' => 400,
            'max_discount' => 150,
            'start_date' => date('Y-m-d', strtotime('+5 days')),
            'end_date' => date('Y-m-d', strtotime('+75 days')),
            'usage_limit' => 40,
            'used_count' => 0,
            'room_type' => 'Family Suites',
            'status' => 'Active'
        ],
        [
            'id' => 5,
            'code' => 'OLDGUEST',
            'name' => 'Returning Guest Reward',
            'description' => 'A previous promotional campaign.',
            'type' => 'Percentage',
            'value' => 12,
            'min_booking' => 150,
            'max_discount' => 120,
            'start_date' => date('Y-m-d', strtotime('-90 days')),
            'end_date' => date('Y-m-d', strtotime('-10 days')),
            'usage_limit' => 60,
            'used_count' => 41,
            'room_type' => 'All Rooms',
            'status' => 'Active'
        ],
        [
            'id' => 6,
            'code' => 'VIP25',
            'name' => 'VIP Guest Privilege',
            'description' => 'An exclusive offer currently disabled by management.',
            'type' => 'Percentage',
            'value' => 25,
            'min_booking' => 500,
            'max_discount' => 250,
            'start_date' => date('Y-m-d', strtotime('-10 days')),
            'end_date' => date('Y-m-d', strtotime('+120 days')),
            'usage_limit' => 20,
            'used_count' => 3,
            'room_type' => 'All Rooms',
            'status' => 'Inactive'
        ]
    ];
}

$discounts = &$_SESSION['azurebay_admin_discounts'];

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function money($value) {
    return '$' . number_format((float)$value, 2);
}

function discountCodeExists($code, $excludeId = 0) {
    foreach ($_SESSION['azurebay_admin_discounts'] as $discount) {
        if (
            strtoupper($discount['code']) === strtoupper($code) &&
            (int)$discount['id'] !== (int)$excludeId
        ) {
            return true;
        }
    }

    return false;
}

function discountDateStatus($discount) {
    $today = date('Y-m-d');

    if ($discount['status'] !== 'Active') {
        return 'Inactive';
    }

    if ($discount['used_count'] >= $discount['usage_limit']) {
        return 'Limit Reached';
    }

    if ($today < $discount['start_date']) {
        return 'Scheduled';
    }

    if ($today > $discount['end_date']) {
        return 'Expired';
    }

    return 'Available';
}

function discountBadgeClass($status) {
    switch ($status) {
        case 'Available':
            return 'available';
        case 'Scheduled':
            return 'scheduled';
        case 'Expired':
            return 'expired';
        case 'Inactive':
            return 'inactive';
        case 'Limit Reached':
            return 'limit-reached';
        default:
            return 'inactive';
    }
}

function redirectDiscounts() {
    header('Location: discounts.php');
    exit;
}

$flash = '';
$flashType = 'success';

$roomTypes = [
    'All Rooms',
    'Deluxe Rooms',
    'Suites',
    'Family Suites',
    'Villas'
];

/* ==========================================
   HANDLE FORM SUBMISSIONS
========================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    /* CREATE DISCOUNT */

    if ($action === 'create') {
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $value = (float)($_POST['value'] ?? 0);
        $minBooking = (float)($_POST['min_booking'] ?? 0);
        $maxDiscount = (float)($_POST['max_discount'] ?? 0);
        $startDate = trim($_POST['start_date'] ?? '');
        $endDate = trim($_POST['end_date'] ?? '');
        $usageLimit = (int)($_POST['usage_limit'] ?? 0);
        $roomType = trim($_POST['room_type'] ?? '');
        $status = trim($_POST['status'] ?? 'Active');

        $validTypes = ['Percentage', 'Fixed Amount'];
        $validStatuses = ['Active', 'Inactive'];

        $start = DateTime::createFromFormat('!Y-m-d', $startDate);
        $end = DateTime::createFromFormat('!Y-m-d', $endDate);

        $validDates = $start && $end &&
            $start->format('Y-m-d') === $startDate &&
            $end->format('Y-m-d') === $endDate;

        $validValue = $type === 'Percentage'
            ? ($value > 0 && $value <= 100)
            : ($type === 'Fixed Amount' && $value > 0);

        if (
            $code === '' ||
            !preg_match('/^[A-Z0-9_-]{3,30}$/', $code) ||
            $name === '' ||
            strlen($name) > 100 ||
            strlen($description) > 500 ||
            !in_array($type, $validTypes, true) ||
            !$validValue ||
            $minBooking < 0 ||
            $maxDiscount < 0 ||
            $usageLimit < 1 ||
            !in_array($roomType, $roomTypes, true) ||
            !in_array($status, $validStatuses, true) ||
            !$validDates ||
            $endDate < $startDate
        ) {
            $flash = 'Please check all fields. Discount codes must be 3–30 characters and use only letters, numbers, hyphens or underscores.';
            $flashType = 'danger';
        } elseif (discountCodeExists($code)) {
            $flash = 'That discount code already exists. Please choose another code.';
            $flashType = 'danger';
        } else {
            $nextId = 1;

            foreach ($discounts as $discount) {
                $nextId = max($nextId, (int)$discount['id'] + 1);
            }

            $discounts[] = [
                'id' => $nextId,
                'code' => $code,
                'name' => $name,
                'description' => $description,
                'type' => $type,
                'value' => $value,
                'min_booking' => $minBooking,
                'max_discount' => $maxDiscount,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'usage_limit' => $usageLimit,
                'used_count' => 0,
                'room_type' => $roomType,
                'status' => $status
            ];

            $_SESSION['azurebay_admin_discounts'] = $discounts;

            $flash = 'Discount code ' . $code . ' was created successfully.';
        }
    }

    /* EDIT DISCOUNT */

    elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $found = false;

        foreach ($discounts as &$discount) {
            if ((int)$discount['id'] !== $id) {
                continue;
            }

            $found = true;

            $code = strtoupper(trim($_POST['code'] ?? ''));
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $value = (float)($_POST['value'] ?? 0);
            $minBooking = (float)($_POST['min_booking'] ?? 0);
            $maxDiscount = (float)($_POST['max_discount'] ?? 0);
            $startDate = trim($_POST['start_date'] ?? '');
            $endDate = trim($_POST['end_date'] ?? '');
            $usageLimit = (int)($_POST['usage_limit'] ?? 0);
            $roomType = trim($_POST['room_type'] ?? '');
            $status = trim($_POST['status'] ?? '');

            $validTypes = ['Percentage', 'Fixed Amount'];
            $validStatuses = ['Active', 'Inactive'];

            $start = DateTime::createFromFormat('!Y-m-d', $startDate);
            $end = DateTime::createFromFormat('!Y-m-d', $endDate);

            $validDates = $start && $end &&
                $start->format('Y-m-d') === $startDate &&
                $end->format('Y-m-d') === $endDate;

            $validValue = $type === 'Percentage'
                ? ($value > 0 && $value <= 100)
                : ($type === 'Fixed Amount' && $value > 0);

            if (
                $code === '' ||
                !preg_match('/^[A-Z0-9_-]{3,30}$/', $code) ||
                $name === '' ||
                strlen($name) > 100 ||
                strlen($description) > 500 ||
                !in_array($type, $validTypes, true) ||
                !$validValue ||
                $minBooking < 0 ||
                $maxDiscount < 0 ||
                $usageLimit < max(1, (int)$discount['used_count']) ||
                !in_array($roomType, $roomTypes, true) ||
                !in_array($status, $validStatuses, true) ||
                !$validDates ||
                $endDate < $startDate
            ) {
                $flash = 'Invalid discount details. Check the dates, value, and usage limit.';
                $flashType = 'danger';
                break;
            }

            if (discountCodeExists($code, $id)) {
                $flash = 'That discount code is already being used by another offer.';
                $flashType = 'danger';
                break;
            }

            $discount['code'] = $code;
            $discount['name'] = $name;
            $discount['description'] = $description;
            $discount['type'] = $type;
            $discount['value'] = $value;
            $discount['min_booking'] = $minBooking;
            $discount['max_discount'] = $maxDiscount;
            $discount['start_date'] = $startDate;
            $discount['end_date'] = $endDate;
            $discount['usage_limit'] = $usageLimit;
            $discount['room_type'] = $roomType;
            $discount['status'] = $status;

            $flash = 'Discount ' . $code . ' was updated successfully.';
            break;
        }

        unset($discount);

        if (!$found) {
            $flash = 'Discount record not found.';
            $flashType = 'danger';
        }

        $_SESSION['azurebay_admin_discounts'] = $discounts;
    }

    /* ACTIVATE / DEACTIVATE */

    elseif ($action === 'toggle_status') {
        $id = (int)($_POST['id'] ?? 0);
        $found = false;

        foreach ($discounts as &$discount) {
            if ((int)$discount['id'] === $id) {
                $found = true;

                $discount['status'] =
                    $discount['status'] === 'Active'
                        ? 'Inactive'
                        : 'Active';

                $flash = 'Discount ' . $discount['code'] .
                    ' is now ' . strtolower($discount['status']) . '.';

                break;
            }
        }

        unset($discount);

        if (!$found) {
            $flash = 'Discount record not found.';
            $flashType = 'danger';
        }

        $_SESSION['azurebay_admin_discounts'] = $discounts;
    }

    /* DELETE DISCOUNT */

    elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $found = false;

        foreach ($discounts as $discount) {
            if ((int)$discount['id'] === $id) {
                $found = true;

                if ((int)$discount['used_count'] > 0) {
                    $flash = 'This discount has usage history. Deactivate it instead of deleting it.';
                    $flashType = 'warning';
                } else {
                    $discounts = array_values(array_filter(
                        $discounts,
                        function ($item) use ($id) {
                            return (int)$item['id'] !== $id;
                        }
                    ));

                    $_SESSION['azurebay_admin_discounts'] = $discounts;
                    $flash = 'Discount was deleted successfully.';
                }

                break;
            }
        }

        if (!$found) {
            $flash = 'Discount record not found.';
            $flashType = 'danger';
        }
    }

    redirectDiscounts();
}

/* ==========================================
   SEARCH AND FILTER
========================================== */

$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$typeFilter = trim($_GET['type'] ?? '');
$roomFilter = trim($_GET['room_type'] ?? '');
$sort = trim($_GET['sort'] ?? 'newest');

$validStatuses = ['Active', 'Inactive'];
$validTypes = ['Percentage', 'Fixed Amount'];

if (!in_array($statusFilter, $validStatuses, true)) {
    $statusFilter = '';
}

if (!in_array($typeFilter, $validTypes, true)) {
    $typeFilter = '';
}

if (!in_array($roomFilter, $roomTypes, true)) {
    $roomFilter = '';
}

$filteredDiscounts = array_values(array_filter(
    $discounts,
    function ($discount) use (
        $search,
        $statusFilter,
        $typeFilter,
        $roomFilter
    ) {
        $searchable = implode(' ', [
            $discount['code'],
            $discount['name'],
            $discount['description']
        ]);

        if (
            $search !== '' &&
            stripos($searchable, $search) === false
        ) {
            return false;
        }

        if (
            $statusFilter !== '' &&
            $discount['status'] !== $statusFilter
        ) {
            return false;
        }

        if (
            $typeFilter !== '' &&
            $discount['type'] !== $typeFilter
        ) {
            return false;
        }

        if (
            $roomFilter !== '' &&
            $discount['room_type'] !== $roomFilter
        ) {
            return false;
        }

        return true;
    }
));

switch ($sort) {
    case 'oldest':
        usort($filteredDiscounts, function ($a, $b) {
            return (int)$a['id'] <=> (int)$b['id'];
        });
        break;

    case 'usage':
        usort($filteredDiscounts, function ($a, $b) {
            return (int)$b['used_count'] <=> (int)$a['used_count'];
        });
        break;

    case 'expiry':
        usort($filteredDiscounts, function ($a, $b) {
            return strcmp($a['end_date'], $b['end_date']);
        });
        break;

    default:
        usort($filteredDiscounts, function ($a, $b) {
            return (int)$b['id'] <=> (int)$a['id'];
        });
}

/* ==========================================
   STATISTICS
========================================== */

$totalDiscounts = count($discounts);
$activeDiscounts = 0;
$inactiveDiscounts = 0;
$availableDiscounts = 0;
$scheduledDiscounts = 0;
$expiredDiscounts = 0;
$limitReached = 0;
$totalRedemptions = 0;

foreach ($discounts as $discount) {
    if ($discount['status'] === 'Active') {
        $activeDiscounts++;
    } else {
        $inactiveDiscounts++;
    }

    $state = discountDateStatus($discount);

    if ($state === 'Available') {
        $availableDiscounts++;
    } elseif ($state === 'Scheduled') {
        $scheduledDiscounts++;
    } elseif ($state === 'Expired') {
        $expiredDiscounts++;
    } elseif ($state === 'Limit Reached') {
        $limitReached++;
    }

    $totalRedemptions += (int)$discount['used_count'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Discount Management | Azure Bay Resort</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --ocean: #073b4c;
            --ocean-dark: #052c39;
            --ocean-light: #eaf3f5;
            --gold: #c9a45c;
            --cream: #fbf8f1;
            --muted: #77858d;
            --border: #e5e9eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f7f8;
            color: #253640;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1050;
            background: var(--ocean-dark);
            color: white;
            overflow-y: auto;
            transition: transform .25s ease;
        }

        .brand {
            padding: 25px 22px 21px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--ocean-dark);
            background: var(--gold);
            font-size: 23px;
            font-weight: bold;
            margin-right: 10px;
        }

        .brand-name {
            color: white;
            font-size: 19px;
            font-weight: bold;
        }

        .brand-subtitle {
            color: #b9cbd0;
            font-size: 11px;
            letter-spacing: 1.8px;
        }

        .nav-heading {
            color: #93aeb7;
            padding: 22px 22px 8px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar .nav-link {
            color: #d2e0e4;
            padding: 12px 20px;
            margin: 3px 12px;
            border-radius: 9px;
            font-size: 13px;
            transition: .2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(201,164,92,.18);
        }

        .sidebar .nav-link.active {
            border-left: 3px solid var(--gold);
            padding-left: 17px;
        }

        .nav-symbol {
            display: inline-block;
            width: 28px;
            font-size: 16px;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            padding: 17px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
        }

        .topbar-title {
            font-size: 13px;
            color: var(--muted);
        }

        .admin-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--ocean-light);
            color: var(--ocean);
            font-weight: bold;
        }

        .page-content {
            padding: 28px 30px 40px;
        }

        .page-title {
            color: var(--ocean);
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: var(--muted);
            font-size: 13px;
        }

        .btn-gold {
            background: var(--gold);
            border: 1px solid var(--gold);
            color: var(--ocean-dark);
            font-weight: bold;
        }

        .btn-gold:hover {
            background: #d9b977;
            border-color: #d9b977;
            color: var(--ocean-dark);
        }

        .btn-ocean {
            background: var(--ocean);
            border: 1px solid var(--ocean);
            color: white;
        }

        .btn-ocean:hover {
            background: var(--ocean-dark);
            color: white;
        }

        .stat-card {
            height: 100%;
            padding: 20px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(10,40,50,.025);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 17px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
        }

        .stat-icon {
            width: 39px;
            height: 39px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: var(--ocean-light);
            font-size: 19px;
        }

        .stat-value {
            color: var(--ocean);
            font-size: 27px;
            font-weight: bold;
        }

        .stat-note {
            color: var(--muted);
            font-size: 11px;
            margin-top: 6px;
        }

        .content-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 13px;
            overflow: hidden;
        }

        .card-heading {
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .card-heading h5 {
            color: var(--ocean);
            font-weight: bold;
            font-size: 16px;
            margin: 0;
        }

        .filter-area {
            padding: 18px 22px;
            background: #fcfdfd;
            border-bottom: 1px solid var(--border);
        }

        .form-label {
            color: #455761;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            border-color: #dfe5e8;
            border-radius: 8px;
            font-size: 13px;
            padding: 10px 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 .2rem rgba(201,164,92,.15);
        }

        .table-responsive {
            min-height: 220px;
        }

        .table {
            margin: 0;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table thead th {
            padding: 15px 16px;
            background: #f7f9fa;
            color: #687983;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .7px;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            padding: 16px;
            border-color: #edf0f2;
            font-size: 12px;
        }

        .table tbody tr:hover {
            background: #fbfcfc;
        }

        .discount-name {
            color: var(--ocean);
            font-weight: bold;
            font-size: 13px;
        }

        .discount-code {
            display: inline-block;
            padding: 6px 9px;
            background: var(--cream);
            color: #765b22;
            border: 1px dashed var(--gold);
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: .7px;
        }

        .small-muted {
            color: var(--muted);
            font-size: 11px;
            margin-top: 4px;
            white-space: normal;
            max-width: 270px;
        }

        .discount-value {
            font-size: 18px;
            color: var(--ocean);
            font-weight: bold;
        }

        .discount-type {
            color: var(--muted);
            font-size: 10px;
        }

        .state-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: bold;
        }

        .state-badge.available {
            color: #157347;
            background: #d9f3e5;
        }

        .state-badge.scheduled {
            color: #805b00;
            background: #fff0c2;
        }

        .state-badge.expired {
            color: #a33a32;
            background: #fde5e2;
        }

        .state-badge.inactive {
            color: #5e6870;
            background: #e9edef;
        }

        .state-badge.limit-reached {
            color: #5c4a92;
            background: #eee8ff;
        }

        .progress {
            height: 6px;
            background: #edf0f2;
            border-radius: 10px;
        }

        .progress-bar {
            background: var(--gold);
        }

        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 16px 22px;
            color: var(--muted);
            font-size: 12px;
            flex-wrap: wrap;
        }

        .promo-banner {
            padding: 23px;
            background: var(--ocean);
            color: white;
            border-radius: 13px;
            height: 100%;
        }

        .promo-banner h5 {
            font-weight: bold;
        }

        .promo-banner p {
            color: #d0e0e4;
            font-size: 12px;
            line-height: 1.7;
        }

        .promo-number {
            font-size: 27px;
            font-weight: bold;
            color: var(--gold);
        }

        .promo-light {
            padding: 23px;
            background: var(--cream);
            border: 1px solid #eee4ce;
            color: var(--ocean);
            border-radius: 13px;
            height: 100%;
        }

        .promo-light p {
            color: #7e7157;
            font-size: 12px;
            line-height: 1.7;
        }

        .modal-content {
            border: none;
            border-radius: 14px;
            overflow: hidden;
        }

        .modal-header {
            background: var(--ocean);
            color: white;
            padding: 20px 24px;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 16px 24px;
            background: #fafbfb;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: var(--muted);
        }

        .empty-icon {
            font-size: 38px;
            margin-bottom: 10px;
        }

        .sidebar-overlay {
            display: none;
        }

        .required-star {
            color: #dc3545;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.4);
                z-index: 1040;
            }

            .page-content {
                padding: 22px 16px 30px;
            }

            .topbar {
                padding: 15px 18px;
            }
        }

        @media (max-width: 575px) {
            .page-title {
                font-size: 23px;
            }

            .stat-value {
                font-size: 23px;
            }

            .card-heading,
            .filter-area {
                padding: 16px;
            }
        }

        @media print {
            .sidebar,
            .sidebar-overlay,
            .topbar,
            .no-print,
            .filter-area,
            .actions-column {
                display: none !important;
            }

            .main-content {
                margin-left: 0;
            }

            body {
                background: white;
            }

            .page-content {
                padding: 0;
            }

            .content-card,
            .stat-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">

    <div class="brand">
        <div class="d-flex align-items-center">
            <div class="brand-icon">A</div>
            <div>
                <div class="brand-name">Azure Bay</div>
                <div class="brand-subtitle">RESORT ADMINISTRATION</div>
            </div>
        </div>
    </div>

    <div class="nav-heading">Overview</div>
    <nav class="nav flex-column">
        <a href="index.php" class="nav-link">
            <span class="nav-symbol">▦</span> Dashboard
        </a>
    </nav>

    <div class="nav-heading">Hotel Operations</div>
    <nav class="nav flex-column">
        <a href="rooms.php" class="nav-link">
            <span class="nav-symbol">▤</span> Room Management
        </a>
        <a href="reservations.php" class="nav-link">
            <span class="nav-symbol">▣</span> Reservations
        </a>
        <a href="customers.php" class="nav-link">
            <span class="nav-symbol">♙</span> Customers
        </a>
        <a href="guest_services.php" class="nav-link">
            <span class="nav-symbol">✧</span> Guest Services
        </a>
        <a href="availability.php" class="nav-link">
            <span class="nav-symbol">◷</span> Room Availability
        </a>
    </nav>

    <div class="nav-heading">Finance & Reports</div>
    <nav class="nav flex-column">
        <a href="payments.php" class="nav-link">
            <span class="nav-symbol">$</span> Payments
        </a>
        <a href="discounts.php" class="nav-link active">
            <span class="nav-symbol">%</span> Discounts
        </a>
        <a href="reports.php" class="nav-link">
            <span class="nav-symbol">▥</span> Reports
        </a>
    </nav>

    <div class="nav-heading">Website</div>
    <nav class="nav flex-column pb-4">
        <a href="../index.php" class="nav-link">
            <span class="nav-symbol">⌂</span> Resort Website
        </a>
        <a href="../azurebay_demo.php" class="nav-link">
            <span class="nav-symbol">↩</span> Demo Selection
        </a>
    </nav>

</aside>

<main class="main-content">

    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button
                class="btn btn-light d-lg-none"
                type="button"
                id="sidebarToggle"
                aria-label="Toggle navigation">
                ☰
            </button>

            <div>
                <div class="fw-bold" style="color:var(--ocean);">
                    Promotions & Offers
                </div>
                <div class="topbar-title">
                    Azure Bay Resort / Discount Management
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="text-end d-none d-sm-block">
                <div class="fw-bold" style="font-size:13px;color:var(--ocean);">
                    Resort Administrator
                </div>
                <div class="small-muted">Demo Account</div>
            </div>
            <div class="admin-avatar">AD</div>
        </div>
    </header>

    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h1 class="page-title">Discount Management</h1>
                <p class="page-subtitle mb-0">
                    Create promotional offers and manage booking discount rules.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2 no-print">
                <button type="button" class="btn btn-outline-secondary"
                        onclick="window.print()">
                    ▤ Print Report
                </button>

                <button type="button" class="btn btn-gold"
                        data-bs-toggle="modal"
                        data-bs-target="#createDiscountModal">
                    + Create Discount
                </button>
            </div>
        </div>

        <?php if ($flash !== ''): ?>
            <div class="alert alert-<?= e($flashType) ?> alert-dismissible fade show mb-4"
                 role="alert">
                <?= e($flash) ?>
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- STATISTICS -->

        <div class="row g-3 mb-4">

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Total Discounts</span>
                        <span class="stat-icon">%</span>
                    </div>
                    <div class="stat-value"><?= $totalDiscounts ?></div>
                    <div class="stat-note">All promotional campaigns</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Active Offers</span>
                        <span class="stat-icon">✓</span>
                    </div>
                    <div class="stat-value"><?= $activeDiscounts ?></div>
                    <div class="stat-note">Offers enabled by management</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Currently Available</span>
                        <span class="stat-icon">✧</span>
                    </div>
                    <div class="stat-value"><?= $availableDiscounts ?></div>
                    <div class="stat-note">Within dates and usage limits</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Total Redemptions</span>
                        <span class="stat-icon">↗</span>
                    </div>
                    <div class="stat-value"><?= number_format($totalRedemptions) ?></div>
                    <div class="stat-note">Recorded promotional usage</div>
                </div>
            </div>

        </div>

        <!-- PROMOTION OVERVIEW -->

        <div class="row g-3 mb-4">

            <div class="col-12 col-lg-8">
                <div class="promo-banner">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <h5>Make Every Stay Special</h5>
                            <p class="mb-0">
                                Manage exclusive offers for returning guests, families,
                                suite bookings and seasonal resort campaigns.
                            </p>
                        </div>
                        <div style="font-size:34px;color:var(--gold);">%</div>
                    </div>

                    <hr style="border-color:rgba(255,255,255,.2);">

                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="promo-number"><?= $scheduledDiscounts ?></div>
                            <div class="small">Scheduled</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="promo-number"><?= $expiredDiscounts ?></div>
                            <div class="small">Expired</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="promo-number"><?= $limitReached ?></div>
                            <div class="small">Limit Reached</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="promo-number"><?= $inactiveDiscounts ?></div>
                            <div class="small">Deactivated</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="promo-light">
                    <h5 class="fw-bold">Discount Rules</h5>
                    <p>
                        <strong>Percentage:</strong> Reduce the eligible booking
                        amount by a selected percentage.
                    </p>
                    <p>
                        <strong>Fixed amount:</strong> Deduct a specified amount
                        from an eligible booking.
                    </p>
                    <p class="mb-0">
                        Minimum booking amounts, maximum discount caps, room
                        categories, dates and redemption limits help control offers.
                    </p>
                </div>
            </div>

        </div>

        <!-- DISCOUNT TABLE -->

        <section class="content-card">

            <div class="card-heading">
                <div>
                    <h5>Promotional Campaigns</h5>
                    <div class="small-muted">
                        Review offers and manage their status.
                    </div>
                </div>

                <span class="badge text-bg-light border">
                    <?= count($filteredDiscounts) ?> offer(s)
                </span>
            </div>

            <div class="filter-area no-print">
                <form method="GET" action="discounts.php">
                    <div class="row g-3 align-items-end">

                        <div class="col-12 col-lg-3">
                            <label class="form-label">Search Offers</label>
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Discount code or name..."
                                value="<?= e($search) ?>">
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="form-label">Management Status</label>
                            <select name="status" class="form-select">
                                <option value="">All statuses</option>
                                <?php foreach ($validStatuses as $status): ?>
                                    <option value="<?= e($status) ?>"
                                        <?= $statusFilter === $status ? 'selected' : '' ?>>
                                        <?= e($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="form-label">Discount Type</label>
                            <select name="type" class="form-select">
                                <option value="">All types</option>
                                <?php foreach ($validTypes as $type): ?>
                                    <option value="<?= e($type) ?>"
                                        <?= $typeFilter === $type ? 'selected' : '' ?>>
                                        <?= e($type) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="form-label">Room Category</label>
                            <select name="room_type" class="form-select">
                                <option value="">All categories</option>
                                <?php foreach ($roomTypes as $roomType): ?>
                                    <option value="<?= e($roomType) ?>"
                                        <?= $roomFilter === $roomType ? 'selected' : '' ?>>
                                        <?= e($roomType) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6 col-lg-3">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>
                                    Recently created
                                </option>
                                <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>
                                    Oldest created
                                </option>
                                <option value="usage" <?= $sort === 'usage' ? 'selected' : '' ?>>
                                    Most redeemed
                                </option>
                                <option value="expiry" <?= $sort === 'expiry' ? 'selected' : '' ?>>
                                    Earliest expiry
                                </option>
                            </select>
                        </div>

                        <div class="col-12 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-ocean">
                                Apply Filters
                            </button>
                            <a href="discounts.php" class="btn btn-outline-secondary">
                                Clear Filters
                            </a>
                        </div>

                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Offer / Code</th>
                        <th>Discount</th>
                        <th>Room Category</th>
                        <th>Minimum Booking</th>
                        <th>Validity</th>
                        <th>Usage</th>
                        <th>Availability</th>
                        <th class="actions-column">Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($filteredDiscounts)): ?>

                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-icon">%</div>
                                    <h6 class="fw-bold" style="color:var(--ocean);">
                                        No discount offers found
                                    </h6>
                                    <p class="mb-0">
                                        Try another filter or create a new discount.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($filteredDiscounts as $discount): ?>
                            <?php
                                $state = discountDateStatus($discount);
                                $usagePercent = $discount['usage_limit'] > 0
                                    ? min(
                                        100,
                                        ($discount['used_count'] / $discount['usage_limit']) * 100
                                    )
                                    : 0;
                            ?>

                            <tr>
                                <td>
                                    <div class="discount-name">
                                        <?= e($discount['name']) ?>
                                    </div>

                                    <div class="mt-2">
                                        <span class="discount-code">
                                            <?= e($discount['code']) ?>
                                        </span>
                                    </div>

                                    <div class="small-muted">
                                        <?= e($discount['description']) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="discount-value">
                                        <?php if ($discount['type'] === 'Percentage'): ?>
                                            <?= number_format((float)$discount['value'], 2) ?>%
                                        <?php else: ?>
                                            <?= money($discount['value']) ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="discount-type">
                                        <?= e($discount['type']) ?>
                                    </div>

                                    <?php if ((float)$discount['max_discount'] > 0): ?>
                                        <div class="small-muted">
                                            Cap: <?= money($discount['max_discount']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= e($discount['room_type']) ?>
                                </td>

                                <td>
                                    <span class="fw-bold">
                                        <?= money($discount['min_booking']) ?>
                                    </span>
                                </td>

                                <td>
                                    <div>
                                        <?= e(date('d M Y', strtotime($discount['start_date']))) ?>
                                    </div>
                                    <div class="small-muted">
                                        Until <?= e(date('d M Y', strtotime($discount['end_date']))) ?>
                                    </div>
                                </td>

                                <td style="min-width:130px;">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">
                                            <?= (int)$discount['used_count'] ?>
                                        </span>
                                        <span class="small-muted">
                                            / <?= (int)$discount['usage_limit'] ?>
                                        </span>
                                    </div>

                                    <div class="progress">
                                        <div
                                            class="progress-bar"
                                            role="progressbar"
                                            style="width:<?= $usagePercent ?>%;"
                                            aria-valuenow="<?= $usagePercent ?>"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>

                                    <div class="small-muted mt-2">
                                        <?= number_format($usagePercent, 0) ?>% used
                                    </div>
                                </td>

                                <td>
                                    <span class="state-badge <?= e(discountBadgeClass($state)) ?>">
                                        <?= e($state) ?>
                                    </span>

                                    <div class="small-muted">
                                        <?= e($discount['status']) ?> in management
                                    </div>
                                </td>

                                <td class="actions-column">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            Manage
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>
                                                <button
                                                    type="button"
                                                    class="dropdown-item edit-discount-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editDiscountModal"
                                                    data-id="<?= (int)$discount['id'] ?>"
                                                    data-code="<?= e($discount['code']) ?>"
                                                    data-name="<?= e($discount['name']) ?>"
                                                    data-description="<?= e($discount['description']) ?>"
                                                    data-type="<?= e($discount['type']) ?>"
                                                    data-value="<?= e($discount['value']) ?>"
                                                    data-min-booking="<?= e($discount['min_booking']) ?>"
                                                    data-max-discount="<?= e($discount['max_discount']) ?>"
                                                    data-start-date="<?= e($discount['start_date']) ?>"
                                                    data-end-date="<?= e($discount['end_date']) ?>"
                                                    data-usage-limit="<?= e($discount['usage_limit']) ?>"
                                                    data-used-count="<?= e($discount['used_count']) ?>"
                                                    data-room-type="<?= e($discount['room_type']) ?>"
                                                    data-status="<?= e($discount['status']) ?>">
                                                    Edit Discount
                                                </button>
                                            </li>

                                            <li>
                                                <form method="POST" action="discounts.php">
                                                    <input type="hidden" name="action" value="toggle_status">
                                                    <input type="hidden" name="id"
                                                           value="<?= (int)$discount['id'] ?>">

                                                    <button type="submit" class="dropdown-item">
                                                        <?= $discount['status'] === 'Active'
                                                            ? 'Deactivate Offer'
                                                            : 'Activate Offer' ?>
                                                    </button>
                                                </form>
                                            </li>

                                            <li><hr class="dropdown-divider"></li>

                                            <li>
                                                <?php if ((int)$discount['used_count'] === 0): ?>
                                                    <button
                                                        type="button"
                                                        class="dropdown-item text-danger delete-discount-btn"
                                                        data-id="<?= (int)$discount['id'] ?>"
                                                        data-code="<?= e($discount['code']) ?>"
                                                        data-name="<?= e($discount['name']) ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteDiscountModal">
                                                        Delete Discount
                                                    </button>
                                                <?php else: ?>
                                                    <span class="dropdown-item-text small text-muted">
                                                        Has usage history
                                                    </span>
                                                <?php endif; ?>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div>
                    Showing <?= count($filteredDiscounts) ?> of <?= $totalDiscounts ?> offers
                </div>
                <div>
                    Total recorded redemptions:
                    <strong><?= number_format($totalRedemptions) ?></strong>
                </div>
            </div>

        </section>

        <div class="text-center mt-4 small-muted">
            Azure Bay Resort &copy; <?= date('Y') ?> |
            Promotions Management Demonstration
        </div>

    </div>
</main>

<!-- ==========================================
     CREATE DISCOUNT MODAL
========================================== -->

<div class="modal fade" id="createDiscountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">Create Discount Offer</h5>
                    <div class="small" style="color:#d2e0e4;">
                        Configure a new promotional campaign.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form method="POST" action="discounts.php" class="discount-form">
                <input type="hidden" name="action" value="create">

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                Discount Code <span class="required-star">*</span>
                            </label>
                            <input type="text" name="code" class="form-control code-input"
                                   maxlength="30" pattern="[A-Za-z0-9_-]{3,30}"
                                   placeholder="e.g. BEACH20" required>
                            <div class="small-muted">
                                Letters, numbers, hyphens and underscores only.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Campaign Name <span class="required-star">*</span>
                            </label>
                            <input type="text" name="name" class="form-control"
                                   maxlength="100" required
                                   placeholder="e.g. Beach Holiday Special">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control"
                                      rows="2" maxlength="500"
                                      placeholder="Describe this promotional offer..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Discount Type <span class="required-star">*</span>
                            </label>
                            <select name="type" class="form-select discount-type-select" required>
                                <option value="Percentage">Percentage (%)</option>
                                <option value="Fixed Amount">Fixed Amount ($)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Discount Value <span class="required-star">*</span>
                            </label>
                            <input type="number" name="value"
                                   class="form-control discount-value-input"
                                   min="0.01" max="100" step="0.01"
                                   required placeholder="e.g. 15">
                            <div class="small-muted value-help">
                                Percentage must be between 0.01% and 100%.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Minimum Booking Amount ($)</label>
                            <input type="number" name="min_booking" class="form-control"
                                   min="0" step="0.01" value="0" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Maximum Discount ($)</label>
                            <input type="number" name="max_discount" class="form-control"
                                   min="0" step="0.01" value="0" required>
                            <div class="small-muted">
                                Zero means no additional cap for this demo.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Start Date <span class="required-star">*</span>
                            </label>
                            <input type="date" name="start_date"
                                   class="form-control start-date-input" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                End Date <span class="required-star">*</span>
                            </label>
                            <input type="date" name="end_date"
                                   class="form-control end-date-input" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Usage Limit <span class="required-star">*</span>
                            </label>
                            <input type="number" name="usage_limit" class="form-control"
                                   min="1" step="1" value="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Eligible Room Category</label>
                            <select name="room_type" class="form-select" required>
                                <?php foreach ($roomTypes as $roomType): ?>
                                    <option value="<?= e($roomType) ?>">
                                        <?= e($roomType) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Management Status</label>
                            <select name="status" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-gold">
                        Create Discount
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ==========================================
     EDIT DISCOUNT MODAL
========================================== -->

<div class="modal fade" id="editDiscountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">Edit Discount Offer</h5>
                    <div class="small" style="color:#d2e0e4;">
                        Update the selected campaign's rules.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form method="POST" action="discounts.php" class="discount-form">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editDiscountId">

                <div class="modal-body">

                    <div class="alert alert-info small">
                        Used redemption counts are preserved. The usage limit cannot
                        be lower than the number of recorded redemptions.
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Discount Code *</label>
                            <input type="text" name="code" id="editCode"
                                   class="form-control code-input"
                                   maxlength="30" pattern="[A-Za-z0-9_-]{3,30}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Campaign Name *</label>
                            <input type="text" name="name" id="editName"
                                   class="form-control" maxlength="100" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editDescription"
                                      class="form-control" rows="2"
                                      maxlength="500"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Discount Type *</label>
                            <select name="type" id="editType"
                                    class="form-select discount-type-select" required>
                                <option value="Percentage">Percentage (%)</option>
                                <option value="Fixed Amount">Fixed Amount ($)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Discount Value *</label>
                            <input type="number" name="value" id="editValue"
                                   class="form-control discount-value-input"
                                   min="0.01" max="100" step="0.01" required>
                            <div class="small-muted value-help"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Minimum Booking Amount ($)</label>
                            <input type="number" name="min_booking" id="editMinBooking"
                                   class="form-control" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Maximum Discount ($)</label>
                            <input type="number" name="max_discount" id="editMaxDiscount"
                                   class="form-control" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date *</label>
                            <input type="date" name="start_date" id="editStartDate"
                                   class="form-control start-date-input" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date *</label>
                            <input type="date" name="end_date" id="editEndDate"
                                   class="form-control end-date-input" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Usage Limit *</label>
                            <input type="number" name="usage_limit" id="editUsageLimit"
                                   class="form-control" min="1" step="1" required>
                            <div class="small-muted" id="editUsageHelp"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Eligible Room Category</label>
                            <select name="room_type" id="editRoomType"
                                    class="form-select" required>
                                <?php foreach ($roomTypes as $roomType): ?>
                                    <option value="<?= e($roomType) ?>">
                                        <?= e($roomType) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Management Status</label>
                            <select name="status" id="editStatus" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-gold">
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ==========================================
     DELETE DISCOUNT MODAL
========================================== -->

<div class="modal fade" id="deleteDiscountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Delete Discount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form method="POST" action="discounts.php">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteDiscountId">

                <div class="modal-body">
                    <p>
                        Are you sure you want to delete this discount campaign?
                    </p>

                    <div class="p-3 rounded" style="background:var(--cream);">
                        <div class="fw-bold" id="deleteDiscountName"
                             style="color:var(--ocean);"></div>
                        <div class="mt-2">
                            <span class="discount-code" id="deleteDiscountCode"></span>
                        </div>
                    </div>

                    <div class="small text-muted mt-3">
                        Only offers with no recorded redemptions can be deleted.
                        Offers with usage history should be deactivated instead.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Delete Discount
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Responsive sidebar
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    sidebarOverlay.addEventListener('click', toggleSidebar);

    // Convert discount codes to uppercase while typing.
    document.querySelectorAll('.code-input').forEach(function(input) {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });

    // Set correct value limits for percentage and fixed-amount discounts.
    function setupDiscountType(select) {
        const form = select.closest('form');
        const valueInput = form.querySelector('.discount-value-input');
        const help = form.querySelector('.value-help');

        function update() {
            if (select.value === 'Percentage') {
                valueInput.max = '100';
                valueInput.min = '0.01';
                help.textContent = 'Enter a percentage from 0.01% to 100%.';
            } else {
                valueInput.removeAttribute('max');
                valueInput.min = '0.01';
                help.textContent = 'Enter a fixed discount amount greater than zero.';
            }
        }

        select.addEventListener('change', update);
        update();
    }

    document.querySelectorAll('.discount-type-select').forEach(setupDiscountType);

    // Validate campaign date ranges.
    document.querySelectorAll('.discount-form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            const start = form.querySelector('.start-date-input');
            const end = form.querySelector('.end-date-input');

            if (start && end && start.value && end.value && end.value < start.value) {
                event.preventDefault();
                alert('The end date must be on or after the start date.');
                end.focus();
            }
        });
    });

    // Populate the edit modal.
    document.querySelectorAll('.edit-discount-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const d = this.dataset;

            document.getElementById('editDiscountId').value = d.id;
            document.getElementById('editCode').value = d.code;
            document.getElementById('editName').value = d.name;
            document.getElementById('editDescription').value = d.description;
            document.getElementById('editType').value = d.type;
            document.getElementById('editValue').value = d.value;
            document.getElementById('editMinBooking').value = d.minBooking;
            document.getElementById('editMaxDiscount').value = d.maxDiscount;
            document.getElementById('editStartDate').value = d.startDate;
            document.getElementById('editEndDate').value = d.endDate;
            document.getElementById('editUsageLimit').value = d.usageLimit;
            document.getElementById('editRoomType').value = d.roomType;
            document.getElementById('editStatus').value = d.status;

            document.getElementById('editUsageLimit').min =
                Math.max(1, parseInt(d.usedCount, 10) || 0);

            document.getElementById('editUsageHelp').textContent =
                'Recorded redemptions: ' + d.usedCount +
                '. The limit cannot be set below this count.';

            document.getElementById('editType').dispatchEvent(
                new Event('change')
            );
        });
    });

    // Populate the delete confirmation modal.
    document.querySelectorAll('.delete-discount-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            document.getElementById('deleteDiscountId').value = this.dataset.id;
            document.getElementById('deleteDiscountName').textContent =
                this.dataset.name;
            document.getElementById('deleteDiscountCode').textContent =
                this.dataset.code;
        });
    });
</script>

</body>
</html>
