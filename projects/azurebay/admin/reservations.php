
<?php
session_start();

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Reservation Management
|--------------------------------------------------------------------------
| Demonstration only: reservation data is stored in the PHP session.
| No database connection is required.
*/

if (!isset($_SESSION['azurebay_admin_logged_in'])) {
    // Keep the demonstration accessible if the admin login is not connected.
    // Connect your own admin authentication here if required.
}

if (!isset($_SESSION['azurebay_admin_reservations'])) {
    $_SESSION['azurebay_admin_reservations'] = [
        [
            'id' => 1001,
            'reference' => 'ABR-2026-1001',
            'guest' => 'Olivia Bennett',
            'email' => 'olivia.bennett@example.com',
            'phone' => '+1 202 555 0141',
            'room' => 'Deluxe Ocean View',
            'room_number' => 'A-101',
            'check_in' => date('Y-m-d', strtotime('+2 days')),
            'check_out' => date('Y-m-d', strtotime('+5 days')),
            'guests' => 2,
            'total' => 540,
            'booking_status' => 'Confirmed',
            'payment_status' => 'Paid',
            'payment_method' => 'Credit Card',
            'created_at' => date('Y-m-d', strtotime('-4 days'))
        ],
        [
            'id' => 1002,
            'reference' => 'ABR-2026-1002',
            'guest' => 'James Anderson',
            'email' => 'james.anderson@example.com',
            'phone' => '+1 202 555 0172',
            'room' => 'Premium Beach Suite',
            'room_number' => 'B-201',
            'check_in' => date('Y-m-d', strtotime('+1 day')),
            'check_out' => date('Y-m-d', strtotime('+4 days')),
            'guests' => 2,
            'total' => 870,
            'booking_status' => 'Pending',
            'payment_status' => 'Pending',
            'payment_method' => 'Pay at Hotel',
            'created_at' => date('Y-m-d', strtotime('-2 days'))
        ],
        [
            'id' => 1003,
            'reference' => 'ABR-2026-1003',
            'guest' => 'Sophia Williams',
            'email' => 'sophia.williams@example.com',
            'phone' => '+1 202 555 0195',
            'room' => 'Family Coastal Suite',
            'room_number' => 'B-205',
            'check_in' => date('Y-m-d', strtotime('-2 days')),
            'check_out' => date('Y-m-d', strtotime('+1 day')),
            'guests' => 4,
            'total' => 1020,
            'booking_status' => 'Checked In',
            'payment_status' => 'Paid',
            'payment_method' => 'Credit Card',
            'created_at' => date('Y-m-d', strtotime('-10 days'))
        ],
        [
            'id' => 1004,
            'reference' => 'ABR-2026-1004',
            'guest' => 'William Taylor',
            'email' => 'william.taylor@example.com',
            'phone' => '+1 202 555 0123',
            'room' => 'Garden Serenity Room',
            'room_number' => 'A-102',
            'check_in' => date('Y-m-d', strtotime('-8 days')),
            'check_out' => date('Y-m-d', strtotime('-5 days')),
            'guests' => 2,
            'total' => 450,
            'booking_status' => 'Checked Out',
            'payment_status' => 'Paid',
            'payment_method' => 'Cash',
            'created_at' => date('Y-m-d', strtotime('-20 days'))
        ],
        [
            'id' => 1005,
            'reference' => 'ABR-2026-1005',
            'guest' => 'Isabella Martinez',
            'email' => 'isabella.martinez@example.com',
            'phone' => '+1 202 555 0168',
            'room' => 'Horizon Ocean Suite',
            'room_number' => 'B-208',
            'check_in' => date('Y-m-d', strtotime('+7 days')),
            'check_out' => date('Y-m-d', strtotime('+10 days')),
            'guests' => 2,
            'total' => 960,
            'booking_status' => 'Confirmed',
            'payment_status' => 'Partially Paid',
            'payment_method' => 'Bank Transfer',
            'created_at' => date('Y-m-d', strtotime('-1 day'))
        ],
        [
            'id' => 1006,
            'reference' => 'ABR-2026-1006',
            'guest' => 'Noah Wilson',
            'email' => 'noah.wilson@example.com',
            'phone' => '+1 202 555 0184',
            'room' => 'Azure Presidential Villa',
            'room_number' => 'V-001',
            'check_in' => date('Y-m-d', strtotime('+14 days')),
            'check_out' => date('Y-m-d', strtotime('+17 days')),
            'guests' => 3,
            'total' => 1350,
            'booking_status' => 'Cancelled',
            'payment_status' => 'Refunded',
            'payment_method' => 'Credit Card',
            'created_at' => date('Y-m-d', strtotime('-6 days'))
        ],
        [
            'id' => 1007,
            'reference' => 'ABR-2026-1007',
            'guest' => 'Emma Thompson',
            'email' => 'emma.thompson@example.com',
            'phone' => '+1 202 555 0117',
            'room' => 'Deluxe Ocean View',
            'room_number' => 'A-101',
            'check_in' => date('Y-m-d', strtotime('+20 days')),
            'check_out' => date('Y-m-d', strtotime('+23 days')),
            'guests' => 2,
            'total' => 540,
            'booking_status' => 'Pending',
            'payment_status' => 'Pending',
            'payment_method' => 'Pay at Hotel',
            'created_at' => date('Y-m-d')
        ]
    ];
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirectWithMessage($message, $type = 'success')
{
    $_SESSION['azurebay_reservation_flash'] = [
        'message' => $message,
        'type' => $type
    ];

    header('Location: reservations.php');
    exit;
}

$allowedBookingStatuses = [
    'Pending',
    'Confirmed',
    'Checked In',
    'Checked Out',
    'Cancelled'
];

$allowedPaymentStatuses = [
    'Pending',
    'Partially Paid',
    'Paid',
    'Refunded'
];

/*
|--------------------------------------------------------------------------
| Handle Reservation Actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $reservationId = isset($_POST['reservation_id'])
        ? (int)$_POST['reservation_id']
        : 0;

    $foundIndex = null;

    foreach ($_SESSION['azurebay_admin_reservations'] as $index => $reservation) {
        if ((int)$reservation['id'] === $reservationId) {
            $foundIndex = $index;
            break;
        }
    }

    if ($foundIndex === null) {
        redirectWithMessage('Reservation not found.', 'danger');
    }

    $reservation = &$_SESSION['azurebay_admin_reservations'][$foundIndex];
    $currentStatus = $reservation['booking_status'];

    if ($action === 'update_booking_status') {
        $newStatus = $_POST['booking_status'] ?? '';

        if (!in_array($newStatus, $allowedBookingStatuses, true)) {
            redirectWithMessage('Invalid booking status.', 'danger');
        }

        if ($currentStatus === 'Cancelled' && $newStatus !== 'Cancelled') {
            redirectWithMessage(
                'A cancelled reservation cannot be reopened in this demo.',
                'warning'
            );
        }

        if ($currentStatus === 'Checked Out' && $newStatus !== 'Checked Out') {
            redirectWithMessage(
                'A checked-out reservation cannot be changed in this demo.',
                'warning'
            );
        }

        if ($newStatus === 'Checked In' && $reservation['payment_status'] !== 'Paid') {
            redirectWithMessage(
                'Record full payment before checking in this guest.',
                'warning'
            );
        }

        if (
            $newStatus === 'Checked In' &&
            strtotime($reservation['check_in']) > strtotime(date('Y-m-d'))
        ) {
            redirectWithMessage(
                'This guest cannot check in before the reservation date.',
                'warning'
            );
        }

        if (
            $newStatus === 'Checked Out' &&
            $currentStatus !== 'Checked In'
        ) {
            redirectWithMessage(
                'Only a checked-in guest can be checked out.',
                'warning'
            );
        }

        if ($newStatus === 'Confirmed' && $currentStatus !== 'Pending') {
            redirectWithMessage(
                'Only pending reservations can be confirmed.',
                'warning'
            );
        }

        if ($newStatus === 'Cancelled' && $currentStatus === 'Checked In') {
            redirectWithMessage(
                'A checked-in guest cannot be cancelled. Check out the guest first.',
                'warning'
            );
        }

        $reservation['booking_status'] = $newStatus;

        redirectWithMessage(
            'Reservation ' . $reservation['reference'] .
            ' updated to ' . $newStatus . '.'
        );
    }

    if ($action === 'update_payment_status') {
        $newPaymentStatus = $_POST['payment_status'] ?? '';

        if (!in_array($newPaymentStatus, $allowedPaymentStatuses, true)) {
            redirectWithMessage('Invalid payment status.', 'danger');
        }

        if ($reservation['booking_status'] === 'Cancelled') {
            if ($newPaymentStatus !== 'Refunded') {
                redirectWithMessage(
                    'Cancelled reservations must remain refunded in this demo.',
                    'warning'
                );
            }
        } elseif ($newPaymentStatus === 'Refunded') {
            redirectWithMessage(
                'Mark the reservation as cancelled before recording a refund.',
                'warning'
            );
        }

        $reservation['payment_status'] = $newPaymentStatus;

        redirectWithMessage(
            'Payment status updated to ' . $newPaymentStatus . '.'
        );
    }

    if ($action === 'cancel_reservation') {
        if (in_array($currentStatus, ['Checked In', 'Checked Out'], true)) {
            redirectWithMessage(
                'This reservation cannot be cancelled at its current stage.',
                'warning'
            );
        }

        $reservation['booking_status'] = 'Cancelled';
        $reservation['payment_status'] = 'Refunded';

        redirectWithMessage(
            'Reservation ' . $reservation['reference'] . ' cancelled.'
        );
    }

    unset($reservation);

    redirectWithMessage('Unknown action.', 'danger');
}

/*
|--------------------------------------------------------------------------
| Flash Message
|--------------------------------------------------------------------------
*/

$flash = $_SESSION['azurebay_reservation_flash'] ?? null;
unset($_SESSION['azurebay_reservation_flash']);

/*
|--------------------------------------------------------------------------
| Search and Filters
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$bookingFilter = $_GET['booking_status'] ?? '';
$paymentFilter = $_GET['payment_status'] ?? '';
$checkInFilter = $_GET['check_in'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$reservations = $_SESSION['azurebay_admin_reservations'];

$filteredReservations = array_filter(
    $reservations,
    function ($reservation) use (
        $search,
        $bookingFilter,
        $paymentFilter,
        $checkInFilter
    ) {
        $searchable = strtolower(
            $reservation['reference'] . ' ' .
            $reservation['guest'] . ' ' .
            $reservation['email'] . ' ' .
            $reservation['phone'] . ' ' .
            $reservation['room'] . ' ' .
            $reservation['room_number']
        );

        if ($search !== '' && strpos($searchable, strtolower($search)) === false) {
            return false;
        }

        if (
            $bookingFilter !== '' &&
            $reservation['booking_status'] !== $bookingFilter
        ) {
            return false;
        }

        if (
            $paymentFilter !== '' &&
            $reservation['payment_status'] !== $paymentFilter
        ) {
            return false;
        }

        if (
            $checkInFilter !== '' &&
            $reservation['check_in'] !== $checkInFilter
        ) {
            return false;
        }

        return true;
    }
);

usort($filteredReservations, function ($a, $b) use ($sort) {
    if ($sort === 'oldest') {
        return strcmp($a['created_at'], $b['created_at']);
    }

    if ($sort === 'checkin_soonest') {
        return strcmp($a['check_in'], $b['check_in']);
    }

    if ($sort === 'highest_total') {
        return $b['total'] <=> $a['total'];
    }

    return strcmp($b['created_at'], $a['created_at']);
});

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

$totalBookings = count($reservations);
$pendingBookings = 0;
$checkedInGuests = 0;
$totalRevenue = 0;
$upcomingBookings = 0;

foreach ($reservations as $reservation) {
    if ($reservation['booking_status'] === 'Pending') {
        $pendingBookings++;
    }

    if ($reservation['booking_status'] === 'Checked In') {
        $checkedInGuests++;
    }

    if (
        in_array($reservation['payment_status'], ['Paid', 'Partially Paid'], true) &&
        $reservation['booking_status'] !== 'Cancelled'
    ) {
        // Demo revenue is based on recorded reservation totals.
        // Partial payments are not separated from full totals in this sample.
        $totalRevenue += (float)$reservation['total'];
    }

    if (
        strtotime($reservation['check_in']) >= strtotime(date('Y-m-d')) &&
        !in_array($reservation['booking_status'], ['Cancelled', 'Checked Out'], true)
    ) {
        $upcomingBookings++;
    }
}

function bookingBadgeClass($status)
{
    switch ($status) {
        case 'Confirmed':
            return 'status-confirmed';
        case 'Pending':
            return 'status-pending';
        case 'Checked In':
            return 'status-checkedin';
        case 'Checked Out':
            return 'status-checkedout';
        case 'Cancelled':
            return 'status-cancelled';
        default:
            return 'status-default';
    }
}

function paymentBadgeClass($status)
{
    switch ($status) {
        case 'Paid':
            return 'payment-paid';
        case 'Partially Paid':
            return 'payment-partial';
        case 'Pending':
            return 'payment-pending';
        case 'Refunded':
            return 'payment-refunded';
        default:
            return 'payment-default';
    }
}

function formatDate($date)
{
    return date('d M Y', strtotime($date));
}

function nightsBetween($checkIn, $checkOut)
{
    $days = (strtotime($checkOut) - strtotime($checkIn)) / 86400;
    return max(0, (int)$days);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservation Management | Azure Bay Resort</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --ocean: #073b4c;
            --ocean-dark: #052c39;
            --ocean-light: #eaf3f5;
            --gold: #c9a45c;
            --cream: #fbf8f1;
            --muted: #7c8991;
            --border: #e7e9eb;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7f8;
            color: #24343c;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            padding: 28px 18px;
            overflow-y: auto;
            background: var(--ocean-dark);
            color: white;
            z-index: 1000;
        }

        .brand {
            padding: 0 10px 28px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            margin-bottom: 24px;
        }

        .brand-name {
            font-size: 23px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand-name span {
            color: var(--gold);
        }

        .brand small {
            display: block;
            margin-top: 5px;
            color: #b8cbd0;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .nav-heading {
            padding: 0 12px;
            margin: 22px 0 10px;
            color: #91aeb6;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 5px;
            border-radius: 9px;
            color: #d8e4e7;
            font-size: 13px;
            text-decoration: none;
            transition: .2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: white;
            background: rgba(255,255,255,.12);
        }

        .sidebar a.active {
            border-left: 3px solid var(--gold);
        }

        .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 16px;
        }

        .main {
            margin-left: 250px;
            padding: 28px 32px 45px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
        }

        .eyebrow {
            margin-bottom: 6px;
            color: #9a7a3c;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .page-title {
            margin: 0;
            color: var(--ocean-dark);
            font-size: clamp(24px, 3vw, 31px);
            font-weight: 800;
        }

        .page-subtitle {
            margin-top: 7px;
            color: var(--muted);
            font-size: 13px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 15px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
        }

        .avatar {
            display: grid;
            width: 40px;
            height: 40px;
            place-items: center;
            border-radius: 50%;
            background: var(--ocean-light);
            color: var(--ocean);
            font-weight: bold;
        }

        .profile-name {
            font-size: 13px;
            font-weight: bold;
        }

        .profile-role {
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            padding: 22px;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 16px rgba(7,59,76,.025);
        }

        .stat-card::after {
            position: absolute;
            top: -23px;
            right: -20px;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: var(--ocean-light);
            content: "";
            opacity: .8;
        }

        .stat-icon {
            display: grid;
            width: 42px;
            height: 42px;
            place-items: center;
            margin-bottom: 16px;
            border-radius: 11px;
            background: var(--ocean-light);
            color: var(--ocean);
            font-size: 20px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
        }

        .stat-value {
            margin-top: 5px;
            color: var(--ocean-dark);
            font-size: 27px;
            font-weight: 800;
        }

        .stat-note {
            margin-top: 7px;
            color: #8a969c;
            font-size: 11px;
        }

        .panel {
            margin-bottom: 25px;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 18px rgba(7,59,76,.025);
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
        }

        .panel-title {
            margin: 0;
            color: var(--ocean-dark);
            font-size: 17px;
            font-weight: 800;
        }

        .panel-description {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .panel-body {
            padding: 23px 24px;
        }

        .form-label {
            margin-bottom: 7px;
            color: #52616a;
            font-size: 12px;
            font-weight: bold;
        }

        .form-control,
        .form-select {
            min-height: 43px;
            border-color: #dfe5e8;
            border-radius: 8px;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 .2rem rgba(201,164,92,.14);
        }

        .btn-ocean {
            min-height: 42px;
            padding: 10px 18px;
            border: 0;
            border-radius: 8px;
            background: var(--ocean);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-ocean:hover {
            background: var(--ocean-dark);
            color: white;
        }

        .btn-reset {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            padding: 10px 15px;
            border: 1px solid #dfe5e8;
            border-radius: 8px;
            color: #52616a;
            font-size: 12px;
            text-decoration: none;
        }

        .btn-reset:hover {
            background: #f4f7f8;
            color: var(--ocean);
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .reservation-table {
            width: 100%;
            min-width: 1120px;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .reservation-table thead th {
            padding: 15px 17px;
            border-bottom: 1px solid var(--border);
            background: #f8fafb;
            color: #74828a;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: .7px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .reservation-table tbody td {
            padding: 17px;
            border-bottom: 1px solid #edf0f1;
            vertical-align: middle;
            font-size: 12px;
        }

        .reservation-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .reservation-table tbody tr:hover {
            background: #fcfdfd;
        }

        .reference {
            color: #9a7a3c;
            font-size: 11px;
            font-weight: bold;
        }

        .guest-name {
            margin-bottom: 5px;
            color: var(--ocean-dark);
            font-weight: bold;
        }

        .guest-detail,
        .room-detail,
        .date-detail {
            color: #7d8b92;
            font-size: 11px;
            line-height: 1.7;
        }

        .room-name {
            color: #354a54;
            font-weight: bold;
        }

        .amount {
            color: var(--ocean-dark);
            font-size: 14px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-badge,
        .payment-badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-confirmed {
            background: #e4f5ec;
            color: #23774c;
        }

        .status-pending {
            background: #fff2d9;
            color: #94630a;
        }

        .status-checkedin {
            background: #e2efff;
            color: #205da4;
        }

        .status-checkedout {
            background: #e9e9f7;
            color: #57559a;
        }

        .status-cancelled {
            background: #fde8e7;
            color: #b33d37;
        }

        .status-default {
            background: #eef1f3;
            color: #596970;
        }

        .payment-paid {
            background: #e4f5ec;
            color: #23774c;
        }

        .payment-partial {
            background: #e2efff;
            color: #205da4;
        }

        .payment-pending {
            background: #fff2d9;
            color: #94630a;
        }

        .payment-refunded {
            background: #f2e8fa;
            color: #7b469e;
        }

        .payment-default {
            background: #eef1f3;
            color: #596970;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 7px 10px;
            border: 1px solid #dce3e6;
            border-radius: 7px;
            background: white;
            color: var(--ocean);
            font-size: 11px;
            font-weight: bold;
        }

        .action-button:hover {
            border-color: var(--gold);
            background: var(--cream);
        }

        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
        }

        .dropdown-item {
            padding: 9px 15px;
            font-size: 12px;
        }

        .dropdown-item:active {
            background: var(--ocean);
        }

        .result-count {
            color: var(--muted);
            font-size: 12px;
        }

        .empty-state {
            padding: 55px 20px;
            text-align: center;
        }

        .empty-icon {
            margin-bottom: 12px;
            color: #9aadb4;
            font-size: 40px;
        }

        .empty-title {
            color: var(--ocean-dark);
            font-size: 17px;
            font-weight: bold;
        }

        .empty-text {
            margin-top: 7px;
            color: var(--muted);
            font-size: 12px;
        }

        .footer-note {
            padding: 8px 0;
            color: #8a969c;
            font-size: 11px;
            text-align: center;
        }

        .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: 15px;
        }

        .modal-header {
            background: var(--ocean-dark);
            color: white;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .modal-title {
            font-size: 17px;
            font-weight: bold;
        }

        .modal-body {
            padding: 25px;
        }

        .detail-box {
            height: 100%;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fbfcfc;
        }

        .detail-label {
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 10px;
            font-weight: bold;
            letter-spacing: .7px;
            text-transform: uppercase;
        }

        .detail-value {
            color: var(--ocean-dark);
            font-size: 13px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .mobile-menu {
            display: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--ocean);
            padding: 8px 12px;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .main {
                padding: 25px 22px;
            }
        }

        @media (max-width: 850px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                padding: 22px 16px;
            }

            .mobile-menu {
                display: inline-block;
            }

            .topbar {
                align-items: flex-start;
            }

            .admin-profile {
                display: none;
            }

            .panel-header,
            .panel-body {
                padding: 18px;
            }
        }

        @media (max-width: 520px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .stat-card {
                padding: 18px;
            }

            .topbar {
                gap: 12px;
            }

            .page-title {
                font-size: 24px;
            }

            .filter-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-name">AZURE <span>BAY</span></div>
        <small>Resort Administration</small>
    </div>

    <div class="nav-heading">Main Menu</div>

    <a href="index.php">
        <span class="nav-icon">▦</span> Dashboard
    </a>

    <a href="rooms.php">
        <span class="nav-icon">▣</span> Room Management
    </a>

    <a href="reservations.php" class="active">
        <span class="nav-icon">▤</span> Reservations
    </a>

    <a href="customers.php">
        <span class="nav-icon">♙</span> Customers
    </a>

    <a href="guest_services.php">
        <span class="nav-icon">✦</span> Guest Services
    </a>

    <div class="nav-heading">Finance & Reports</div>

    <a href="payments.php">
        <span class="nav-icon">＄</span> Payments
    </a>

    <a href="discounts.php">
        <span class="nav-icon">％</span> Discounts
    </a>

    <a href="reports.php">
        <span class="nav-icon">▥</span> Revenue Reports
    </a>

    <a href="availability.php">
        <span class="nav-icon">◷</span> Room Availability
    </a>

    <div class="nav-heading">Website</div>

    <a href="../index.php">
        <span class="nav-icon">⌂</span> View Resort Website
    </a>

    <a href="../azurebay_demo.php">
        <span class="nav-icon">↩</span> Back to Demo Selection
    </a>
</aside>

<!-- Main Content -->
<main class="main">

    <header class="topbar">
        <div class="d-flex align-items-start gap-3">
            <button class="mobile-menu" type="button" id="menuToggle"
                    aria-label="Toggle navigation">☰</button>

            <div>
                <div class="eyebrow">Front Office Operations</div>
                <h1 class="page-title">Reservation Management</h1>
                <div class="page-subtitle">
                    Manage bookings, guest arrivals, departures and payments.
                </div>
            </div>
        </div>

        <div class="admin-profile">
            <div class="avatar">A</div>
            <div>
                <div class="profile-name">Resort Administrator</div>
                <div class="profile-role">Administrator</div>
            </div>
        </div>
    </header>

    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show"
             role="alert">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <section class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon">▤</div>
            <div class="stat-label">Total Reservations</div>
            <div class="stat-value"><?= number_format($totalBookings) ?></div>
            <div class="stat-note">All recorded demo bookings</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">◷</div>
            <div class="stat-label">Pending Reservations</div>
            <div class="stat-value"><?= number_format($pendingBookings) ?></div>
            <div class="stat-note">Require confirmation</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">♙</div>
            <div class="stat-label">Currently Checked In</div>
            <div class="stat-value"><?= number_format($checkedInGuests) ?></div>
            <div class="stat-note">Guests currently staying</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">$</div>
            <div class="stat-label">Recorded Reservation Value</div>
            <div class="stat-value">$<?= number_format($totalRevenue, 2) ?></div>
            <div class="stat-note">Paid or partially paid bookings</div>
        </div>

    </section>

    <!-- Filters -->
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Find Reservations</h2>
                <div class="panel-description">
                    Search by guest, booking reference, room or contact details.
                </div>
            </div>
        </div>

        <div class="panel-body">
            <form method="GET" action="reservations.php">
                <div class="row g-3">

                    <div class="col-lg-4 col-md-6">
                        <label for="search" class="form-label">Search</label>
                        <input
                            type="text"
                            class="form-control"
                            id="search"
                            name="search"
                            value="<?= e($search) ?>"
                            placeholder="Guest name, reference, room..."
                        >
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="booking_status" class="form-label">
                            Booking Status
                        </label>
                        <select class="form-select" id="booking_status"
                                name="booking_status">
                            <option value="">All statuses</option>
                            <?php foreach ($allowedBookingStatuses as $status): ?>
                                <option value="<?= e($status) ?>"
                                    <?= $bookingFilter === $status ? 'selected' : '' ?>>
                                    <?= e($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="payment_status" class="form-label">
                            Payment Status
                        </label>
                        <select class="form-select" id="payment_status"
                                name="payment_status">
                            <option value="">All payments</option>
                            <?php foreach ($allowedPaymentStatuses as $status): ?>
                                <option value="<?= e($status) ?>"
                                    <?= $paymentFilter === $status ? 'selected' : '' ?>>
                                    <?= e($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="check_in" class="form-label">
                            Check-in Date
                        </label>
                        <input type="date" class="form-control" id="check_in"
                               name="check_in" value="<?= e($checkInFilter) ?>">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="sort" class="form-label">Sort By</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="newest"
                                <?= $sort === 'newest' ? 'selected' : '' ?>>
                                Newest bookings
                            </option>
                            <option value="oldest"
                                <?= $sort === 'oldest' ? 'selected' : '' ?>>
                                Oldest bookings
                            </option>
                            <option value="checkin_soonest"
                                <?= $sort === 'checkin_soonest' ? 'selected' : '' ?>>
                                Check-in date
                            </option>
                            <option value="highest_total"
                                <?= $sort === 'highest_total' ? 'selected' : '' ?>>
                                Highest total
                            </option>
                        </select>
                    </div>

                    <div class="col-12 filter-actions">
                        <button type="submit" class="btn btn-ocean">
                            Search Reservations
                        </button>

                        <a href="reservations.php" class="btn-reset">
                            Reset Filters
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </section>

    <!-- Reservations Table -->
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Reservation Records</h2>
                <div class="panel-description">
                    Review booking information and update operational statuses.
                </div>
            </div>

            <span class="result-count">
                Showing <?= number_format(count($filteredReservations)) ?>
                of <?= number_format($totalBookings) ?>
            </span>
        </div>

        <?php if (empty($filteredReservations)): ?>

            <div class="empty-state">
                <div class="empty-icon">⌕</div>
                <div class="empty-title">No reservations found</div>
                <div class="empty-text">
                    Try a different search term or change your filters.
                </div>
                <a href="reservations.php" class="btn btn-ocean mt-3">
                    View All Reservations
                </a>
            </div>

        <?php else: ?>

            <div class="table-wrap">
                <table class="reservation-table">
                    <thead>
                        <tr>
                            <th>Reservation</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Stay Details</th>
                            <th>Total</th>
                            <th>Booking Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($filteredReservations as $reservation): ?>
                        <?php
                            $reservationId = (int)$reservation['id'];
                            $bookingStatus = $reservation['booking_status'];
                            $paymentStatus = $reservation['payment_status'];
                            $nights = nightsBetween(
                                $reservation['check_in'],
                                $reservation['check_out']
                            );
                        ?>

                        <tr>
                            <td>
                                <div class="reference">
                                    <?= e($reservation['reference']) ?>
                                </div>
                                <div class="guest-detail mt-2">
                                    Booked <?= e(formatDate($reservation['created_at'])) ?>
                                </div>
                            </td>

                            <td>
                                <div class="guest-name">
                                    <?= e($reservation['guest']) ?>
                                </div>
                                <div class="guest-detail">
                                    <?= e($reservation['email']) ?><br>
                                    <?= e($reservation['phone']) ?><br>
                                    <?= (int)$reservation['guests'] ?>
                                    guest(s)
                                </div>
                            </td>

                            <td>
                                <div class="room-name">
                                    <?= e($reservation['room']) ?>
                                </div>
                                <div class="room-detail">
                                    Room <?= e($reservation['room_number']) ?>
                                </div>
                            </td>

                            <td>
                                <div class="date-detail">
                                    <strong>In:</strong>
                                    <?= e(formatDate($reservation['check_in'])) ?>
                                    <br>
                                    <strong>Out:</strong>
                                    <?= e(formatDate($reservation['check_out'])) ?>
                                    <br>
                                    <?= $nights ?> night(s)
                                </div>
                            </td>

                            <td>
                                <div class="amount">
                                    $<?= number_format((float)$reservation['total'], 2) ?>
                                </div>
                                <div class="guest-detail">
                                    <?= e($reservation['payment_method']) ?>
                                </div>
                            </td>

                            <td>
                                <span class="status-badge <?= e(bookingBadgeClass($bookingStatus)) ?>">
                                    <?= e($bookingStatus) ?>
                                </span>
                            </td>

                            <td>
                                <span class="payment-badge <?= e(paymentBadgeClass($paymentStatus)) ?>">
                                    <?= e($paymentStatus) ?>
                                </span>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button
                                        class="action-button dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Manage
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <button
                                                class="dropdown-item"
                                                type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailsModal"
                                                data-reference="<?= e($reservation['reference']) ?>"
                                                data-guest="<?= e($reservation['guest']) ?>"
                                                data-email="<?= e($reservation['email']) ?>"
                                                data-phone="<?= e($reservation['phone']) ?>"
                                                data-room="<?= e($reservation['room']) ?>"
                                                data-room-number="<?= e($reservation['room_number']) ?>"
                                                data-check-in="<?= e(formatDate($reservation['check_in'])) ?>"
                                                data-check-out="<?= e(formatDate($reservation['check_out'])) ?>"
                                                data-guests="<?= (int)$reservation['guests'] ?>"
                                                data-total="<?= number_format((float)$reservation['total'], 2) ?>"
                                                data-booking-status="<?= e($bookingStatus) ?>"
                                                data-payment-status="<?= e($paymentStatus) ?>"
                                                data-payment-method="<?= e($reservation['payment_method']) ?>"
                                                data-nights="<?= $nights ?>">
                                                View Details
                                            </button>
                                        </li>

                                        <?php if ($bookingStatus === 'Pending'): ?>
                                            <li>
                                                <form method="POST" action="reservations.php"
                                                      onsubmit="return confirm('Confirm this reservation?');">
                                                    <input type="hidden" name="action"
                                                           value="update_booking_status">
                                                    <input type="hidden" name="reservation_id"
                                                           value="<?= $reservationId ?>">
                                                    <input type="hidden" name="booking_status"
                                                           value="Confirmed">
                                                    <button class="dropdown-item" type="submit">
                                                        Confirm Reservation
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($bookingStatus === 'Confirmed'): ?>
                                            <?php if ($paymentStatus !== 'Paid'): ?>
                                                <li>
                                                    <form method="POST" action="reservations.php"
                                                          onsubmit="return confirm('Record full payment for this reservation?');">
                                                        <input type="hidden" name="action"
                                                               value="update_payment_status">
                                                        <input type="hidden" name="reservation_id"
                                                               value="<?= $reservationId ?>">
                                                        <input type="hidden" name="payment_status"
                                                               value="Paid">
                                                        <button class="dropdown-item" type="submit">
                                                            Record Full Payment
                                                        </button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>

                                            <li>
                                                <form method="POST" action="reservations.php"
                                                      onsubmit="return confirm('Check in this guest? Full payment is required in this demo.');">
                                                    <input type="hidden" name="action"
                                                           value="update_booking_status">
                                                    <input type="hidden" name="reservation_id"
                                                           value="<?= $reservationId ?>">
                                                    <input type="hidden" name="booking_status"
                                                           value="Checked In">
                                                    <button class="dropdown-item" type="submit">
                                                        Check In Guest
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($bookingStatus === 'Checked In'): ?>
                                            <li>
                                                <form method="POST" action="reservations.php"
                                                      onsubmit="return confirm('Check out this guest?');">
                                                    <input type="hidden" name="action"
                                                           value="update_booking_status">
                                                    <input type="hidden" name="reservation_id"
                                                           value="<?= $reservationId ?>">
                                                    <input type="hidden" name="booking_status"
                                                           value="Checked Out">
                                                    <button class="dropdown-item" type="submit">
                                                        Check Out Guest
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (
                                            !in_array(
                                                $bookingStatus,
                                                ['Cancelled', 'Checked In', 'Checked Out'],
                                                true
                                            )
                                        ): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="reservations.php"
                                                      onsubmit="return confirm('Cancel this reservation and mark it refunded?');">
                                                    <input type="hidden" name="action"
                                                           value="cancel_reservation">
                                                    <input type="hidden" name="reservation_id"
                                                           value="<?= $reservationId ?>">
                                                    <button class="dropdown-item text-danger" type="submit">
                                                        Cancel Reservation
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </section>

    <div class="footer-note">
        Azure Bay Resort · Reservation Management · Demonstration Environment
    </div>

</main>

<!-- Reservation Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1"
     aria-labelledby="detailsModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="detailsModalLabel">
                        Reservation Details
                    </h2>
                    <div class="small text-white-50 mt-1" id="modalReference"></div>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <h6 class="fw-bold mb-3" style="color: var(--ocean-dark);">
                    Guest Information
                </h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">Guest Name</div>
                            <div class="detail-value" id="modalGuest"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">Email Address</div>
                            <div class="detail-value" id="modalEmail"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">Contact Number</div>
                            <div class="detail-value" id="modalPhone"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">Number of Guests</div>
                            <div class="detail-value" id="modalGuests"></div>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-3" style="color: var(--ocean-dark);">
                    Booking Information
                </h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">Room</div>
                            <div class="detail-value" id="modalRoom"></div>
                            <div class="guest-detail mt-1" id="modalRoomNumber"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">Length of Stay</div>
                            <div class="detail-value" id="modalNights"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">Check-in</div>
                            <div class="detail-value" id="modalCheckIn"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">Check-out</div>
                            <div class="detail-value" id="modalCheckOut"></div>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-3" style="color: var(--ocean-dark);">
                    Payment Summary
                </h6>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="detail-box">
                            <div class="detail-label">Reservation Total</div>
                            <div class="detail-value" id="modalTotal"></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="detail-box">
                            <div class="detail-label">Booking Status</div>
                            <div class="detail-value" id="modalBookingStatus"></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="detail-box">
                            <div class="detail-label">Payment Status</div>
                            <div class="detail-value" id="modalPaymentStatus"></div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="detail-box">
                            <div class="detail-label">Payment Method</div>
                            <div class="detail-value" id="modalPaymentMethod"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-ocean"
                            data-bs-dismiss="modal">
                        Close Details
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Mobile sidebar
    document.getElementById('menuToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Populate the reservation details modal using the selected table row.
    const detailsModal = document.getElementById('detailsModal');

    detailsModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const fields = {
            'modalReference': 'reference',
            'modalGuest': 'guest',
            'modalEmail': 'email',
            'modalPhone': 'phone',
            'modalRoom': 'room',
            'modalRoomNumber': 'room-number',
            'modalCheckIn': 'check-in',
            'modalCheckOut': 'check-out',
            'modalGuests': 'guests',
            'modalTotal': 'total',
            'modalBookingStatus': 'booking-status',
            'modalPaymentStatus': 'payment-status',
            'modalPaymentMethod': 'payment-method',
            'modalNights': 'nights'
        };

        Object.entries(fields).forEach(function ([elementId, dataName]) {
            const element = document.getElementById(elementId);
            element.textContent = button.dataset[dataName] || '—';
        });

        document.getElementById('modalTotal').textContent =
            '$' + (button.dataset.total || '0.00');

        document.getElementById('modalGuests').textContent =
            (button.dataset.guests || '0') + ' guest(s)';

        document.getElementById('modalNights').textContent =
            (button.dataset.nights || '0') + ' night(s)';
    });
</script>

</body>
</html>