<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/* ==========================================
   AZURE BAY RESORT - PAYMENT MANAGEMENT
   Session-based demonstration; no database.
========================================== */

if (!isset($_SESSION['azurebay_admin_payments'])) {
    $_SESSION['azurebay_admin_payments'] = [
        [
            'id' => 1001,
            'reservation' => 'ABR-2026-001',
            'guest' => 'Emma Wilson',
            'email' => 'emma.wilson@example.com',
            'room' => 'A-101',
            'amount' => 540,
            'paid' => 540,
            'method' => 'Credit Card',
            'status' => 'Paid',
            'date' => date('Y-m-d\TH:i', strtotime('-2 days')),
            'reference' => 'TXN-AB1001',
            'notes' => 'Three-night ocean-view reservation.'
        ],
        [
            'id' => 1002,
            'reservation' => 'ABR-2026-002',
            'guest' => 'James Anderson',
            'email' => 'james.anderson@example.com',
            'room' => 'B-205',
            'amount' => 680,
            'paid' => 200,
            'method' => 'Bank Transfer',
            'status' => 'Partial',
            'date' => date('Y-m-d\TH:i', strtotime('-1 day')),
            'reference' => 'TXN-AB1002',
            'notes' => 'Advance payment received.'
        ],
        [
            'id' => 1003,
            'reservation' => 'ABR-2026-003',
            'guest' => 'Sophia Martinez',
            'email' => 'sophia.martinez@example.com',
            'room' => 'B-201',
            'amount' => 870,
            'paid' => 0,
            'method' => 'Cash',
            'status' => 'Pending',
            'date' => date('Y-m-d\TH:i'),
            'reference' => '',
            'notes' => 'Payment due at check-in.'
        ],
        [
            'id' => 1004,
            'reservation' => 'ABR-2026-004',
            'guest' => 'Oliver Brown',
            'email' => 'oliver.brown@example.com',
            'room' => 'A-102',
            'amount' => 300,
            'paid' => 300,
            'method' => 'Cash',
            'status' => 'Paid',
            'date' => date('Y-m-d\TH:i', strtotime('-3 days')),
            'reference' => 'CASH-1004',
            'notes' => 'Payment received at reception.'
        ],
        [
            'id' => 1005,
            'reservation' => 'ABR-2026-005',
            'guest' => 'Isabella Taylor',
            'email' => 'isabella.taylor@example.com',
            'room' => 'B-208',
            'amount' => 640,
            'paid' => 640,
            'method' => 'Online Payment',
            'status' => 'Paid',
            'date' => date('Y-m-d\TH:i', strtotime('-4 days')),
            'reference' => 'ONL-AB1005',
            'notes' => 'Paid online before arrival.'
        ],
        [
            'id' => 1006,
            'reservation' => 'ABR-2026-006',
            'guest' => 'William Harris',
            'email' => 'william.harris@example.com',
            'room' => 'V-001',
            'amount' => 1350,
            'paid' => 1350,
            'method' => 'Credit Card',
            'status' => 'Refunded',
            'date' => date('Y-m-d\TH:i', strtotime('-6 days')),
            'reference' => 'REF-AB1006',
            'notes' => 'Reservation cancelled; full refund recorded.'
        ],
        [
            'id' => 1007,
            'reservation' => 'ABR-2026-007',
            'guest' => 'Ava Thompson',
            'email' => 'ava.thompson@example.com',
            'room' => 'A-101',
            'amount' => 360,
            'paid' => 150,
            'method' => 'Credit Card',
            'status' => 'Partial',
            'date' => date('Y-m-d\TH:i', strtotime('-5 hours')),
            'reference' => 'TXN-AB1007',
            'notes' => 'Remaining balance payable before checkout.'
        ],
        [
            'id' => 1008,
            'reservation' => 'ABR-2026-008',
            'guest' => 'Noah White',
            'email' => 'noah.white@example.com',
            'room' => 'B-205',
            'amount' => 680,
            'paid' => 0,
            'method' => 'Bank Transfer',
            'status' => 'Pending',
            'date' => date('Y-m-d\TH:i', strtotime('-2 hours')),
            'reference' => '',
            'notes' => 'Awaiting payment confirmation.'
        ]
    ];
}

if (!isset($_SESSION['azurebay_admin_payment_refunds'])) {
    $_SESSION['azurebay_admin_payment_refunds'] = [];
}

$payments = &$_SESSION['azurebay_admin_payments'];
$refunds = &$_SESSION['azurebay_admin_payment_refunds'];

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function money($amount) {
    return '$' . number_format((float)$amount, 2);
}

function paymentStatus($payment) {
    return $payment['status'];
}

function statusClass($status) {
    switch ($status) {
        case 'Paid':
            return 'paid';
        case 'Partial':
            return 'partial';
        case 'Pending':
            return 'pending';
        case 'Refunded':
            return 'refunded';
        default:
            return 'pending';
    }
}

function paymentRedirect() {
    $query = $_SERVER['QUERY_STRING'] ?? '';
    header('Location: payments.php' . ($query ? '?' . $query : ''));
    exit;
}

$flash = '';
$flashType = 'success';

/* ==========================================
   HANDLE PAYMENT ACTIONS
========================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_payment') {
        $guest = trim($_POST['guest'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $reservation = trim($_POST['reservation'] ?? '');
        $room = trim($_POST['room'] ?? '');
        $amount = (float)($_POST['amount'] ?? 0);
        $paid = (float)($_POST['paid'] ?? 0);
        $method = trim($_POST['method'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $reference = trim($_POST['reference'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        $methods = [
            'Credit Card',
            'Debit Card',
            'Cash',
            'Bank Transfer',
            'Online Payment'
        ];

        $dateValid = DateTime::createFromFormat('Y-m-d\TH:i', $date);

        if (
            $guest === '' ||
            $reservation === '' ||
            $room === '' ||
            $amount <= 0 ||
            $paid < 0 ||
            $paid > $amount ||
            !in_array($method, $methods, true) ||
            !$dateValid ||
            ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))
        ) {
            $flash = 'Please check the payment details. The amount paid cannot exceed the total amount.';
            $flashType = 'danger';
        } else {
            $nextId = 1001;

            foreach ($payments as $payment) {
                $nextId = max($nextId, (int)$payment['id'] + 1);
            }

            if ($paid >= $amount) {
                $status = 'Paid';
            } elseif ($paid > 0) {
                $status = 'Partial';
            } else {
                $status = 'Pending';
            }

            $payments[] = [
                'id' => $nextId,
                'reservation' => $reservation,
                'guest' => $guest,
                'email' => $email,
                'room' => $room,
                'amount' => $amount,
                'paid' => $paid,
                'method' => $method,
                'status' => $status,
                'date' => $date,
                'reference' => $reference,
                'notes' => $notes
            ];

            $_SESSION['azurebay_admin_payments'] = $payments;
            $flash = 'Payment record #' . $nextId . ' was created successfully.';
        }
    }

    elseif ($action === 'update_payment') {
        $id = (int)($_POST['id'] ?? 0);
        $found = false;

        foreach ($payments as &$payment) {
            if ((int)$payment['id'] !== $id) {
                continue;
            }

            $found = true;

            $amount = (float)($_POST['amount'] ?? $payment['amount']);
            $paid = (float)($_POST['paid'] ?? $payment['paid']);
            $method = trim($_POST['method'] ?? $payment['method']);
            $reference = trim($_POST['reference'] ?? $payment['reference']);
            $notes = trim($_POST['notes'] ?? $payment['notes']);

            $methods = [
                'Credit Card',
                'Debit Card',
                'Cash',
                'Bank Transfer',
                'Online Payment'
            ];

            if (
                $amount <= 0 ||
                $paid < 0 ||
                $paid > $amount ||
                !in_array($method, $methods, true)
            ) {
                $flash = 'Invalid payment details. Please check the amounts and payment method.';
                $flashType = 'danger';
                break;
            }

            if ($payment['status'] === 'Refunded') {
                $flash = 'Refunded records cannot be edited in this demonstration.';
                $flashType = 'warning';
                break;
            }

            $payment['amount'] = $amount;
            $payment['paid'] = $paid;
            $payment['method'] = $method;
            $payment['reference'] = $reference;
            $payment['notes'] = $notes;

            if ($paid >= $amount) {
                $payment['status'] = 'Paid';
            } elseif ($paid > 0) {
                $payment['status'] = 'Partial';
            } else {
                $payment['status'] = 'Pending';
            }

            $flash = 'Payment record #' . $id . ' was updated successfully.';
            break;
        }

        unset($payment);

        if (!$found) {
            $flash = 'Payment record not found.';
            $flashType = 'danger';
        }

        $_SESSION['azurebay_admin_payments'] = $payments;
    }

    elseif ($action === 'record_refund') {
        $id = (int)($_POST['id'] ?? 0);
        $refundAmount = (float)($_POST['refund_amount'] ?? 0);
        $refundReason = trim($_POST['refund_reason'] ?? '');

        $found = false;

        foreach ($payments as &$payment) {
            if ((int)$payment['id'] !== $id) {
                continue;
            }

            $found = true;

            $alreadyRefunded = 0;

            foreach ($refunds as $refund) {
                if ((int)$refund['payment_id'] === $id) {
                    $alreadyRefunded += (float)$refund['amount'];
                }
            }

            $remainingRefundable = max(0, (float)$payment['paid'] - $alreadyRefunded);

            if (
                $payment['status'] === 'Refunded' ||
                $refundAmount <= 0 ||
                $refundAmount > $remainingRefundable ||
                $refundReason === ''
            ) {
                $flash = 'The refund amount or reason is invalid. It cannot exceed the refundable paid amount.';
                $flashType = 'danger';
                break;
            }

            $refunds[] = [
                'id' => count($refunds) + 1,
                'payment_id' => $id,
                'guest' => $payment['guest'],
                'amount' => $refundAmount,
                'reason' => $refundReason,
                'date' => date('Y-m-d H:i:s')
            ];

            $totalRefunded = $alreadyRefunded + $refundAmount;

            if ($totalRefunded >= (float)$payment['paid']) {
                $payment['status'] = 'Refunded';
            }

            $payment['notes'] = trim(
                $payment['notes'] . ' Refund recorded: ' .
                money($refundAmount) . '. ' . $refundReason
            );

            $flash = 'Refund of ' . money($refundAmount) . ' recorded for payment #' . $id . '.';
            break;
        }

        unset($payment);

        if (!$found) {
            $flash = 'Payment record not found.';
            $flashType = 'danger';
        }

        $_SESSION['azurebay_admin_payments'] = $payments;
        $_SESSION['azurebay_admin_payment_refunds'] = $refunds;
    }

    paymentRedirect();
}

/* ==========================================
   SEARCH AND FILTERS
========================================== */

$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$methodFilter = trim($_GET['method'] ?? '');
$dateFrom = trim($_GET['from'] ?? '');
$dateTo = trim($_GET['to'] ?? '');
$sort = trim($_GET['sort'] ?? 'newest');

$validStatuses = ['Paid', 'Partial', 'Pending', 'Refunded'];
$validMethods = [
    'Credit Card',
    'Debit Card',
    'Cash',
    'Bank Transfer',
    'Online Payment'
];

if (!in_array($statusFilter, $validStatuses, true)) {
    $statusFilter = '';
}

if (!in_array($methodFilter, $validMethods, true)) {
    $methodFilter = '';
}

$filteredPayments = array_values(array_filter(
    $payments,
    function ($payment) use (
        $search,
        $statusFilter,
        $methodFilter,
        $dateFrom,
        $dateTo
    ) {
        $searchable = implode(' ', [
            $payment['id'],
            $payment['reservation'],
            $payment['guest'],
            $payment['email'],
            $payment['room'],
            $payment['reference']
        ]);

        if (
            $search !== '' &&
            stripos($searchable, $search) === false
        ) {
            return false;
        }

        if (
            $statusFilter !== '' &&
            $payment['status'] !== $statusFilter
        ) {
            return false;
        }

        if (
            $methodFilter !== '' &&
            $payment['method'] !== $methodFilter
        ) {
            return false;
        }

        $paymentDate = substr($payment['date'], 0, 10);

        if ($dateFrom !== '' && $paymentDate < $dateFrom) {
            return false;
        }

        if ($dateTo !== '' && $paymentDate > $dateTo) {
            return false;
        }

        return true;
    }
));

switch ($sort) {
    case 'oldest':
        usort($filteredPayments, function ($a, $b) {
            return strcmp($a['date'], $b['date']);
        });
        break;

    case 'highest':
        usort($filteredPayments, function ($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });
        break;

    case 'lowest':
        usort($filteredPayments, function ($a, $b) {
            return $a['amount'] <=> $b['amount'];
        });
        break;

    default:
        usort($filteredPayments, function ($a, $b) {
            return strcmp($b['date'], $a['date']);
        });
}

/* ==========================================
   DASHBOARD STATISTICS
========================================== */

$totalRecords = count($payments);
$totalRevenue = 0;
$totalOutstanding = 0;
$totalCollected = 0;
$totalPending = 0;
$totalPartial = 0;
$totalRefunded = 0;
$todayCollected = 0;
$today = date('Y-m-d');

foreach ($payments as $payment) {
    $totalCollected += (float)$payment['paid'];

    if ($payment['status'] !== 'Refunded') {
        $totalRevenue += (float)$payment['amount'];
        $totalOutstanding += max(
            0,
            (float)$payment['amount'] - (float)$payment['paid']
        );
    }

    if ($payment['status'] === 'Pending') {
        $totalPending++;
    }

    if ($payment['status'] === 'Partial') {
        $totalPartial++;
    }

    if ($payment['status'] === 'Refunded') {
        $totalRefunded++;
    }

    if (
        substr($payment['date'], 0, 10) === $today &&
        $payment['status'] !== 'Refunded'
    ) {
        $todayCollected += (float)$payment['paid'];
    }
}

$totalRefundAmount = 0;

foreach ($refunds as $refund) {
    $totalRefundAmount += (float)$refund['amount'];
}

$flashMessages = [
    'success' => 'success',
    'danger' => 'danger',
    'warning' => 'warning'
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment Management | Azure Bay Resort</title>

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
            --green: #198754;
            --red: #dc3545;
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
            top: 0;
            left: 0;
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
            font-size: 25px;
            font-weight: bold;
            overflow-wrap: anywhere;
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
            min-height: 260px;
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

        .guest-name {
            color: var(--ocean);
            font-weight: bold;
            font-size: 13px;
        }

        .small-muted {
            color: var(--muted);
            font-size: 11px;
            margin-top: 4px;
        }

        .amount {
            font-weight: bold;
            color: var(--ocean);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-badge.paid {
            color: #157347;
            background: #d9f3e5;
        }

        .status-badge.partial {
            color: #8a6200;
            background: #fff0c2;
        }

        .status-badge.pending {
            color: #a33a32;
            background: #fde5e2;
        }

        .status-badge.refunded {
            color: #5c4a92;
            background: #eee8ff;
        }

        .method-icon {
            display: inline-flex;
            width: 29px;
            height: 29px;
            align-items: center;
            justify-content: center;
            background: #edf4f5;
            border-radius: 8px;
            margin-right: 6px;
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

        .summary-box {
            padding: 20px;
            border-radius: 12px;
            background: var(--ocean);
            color: white;
            height: 100%;
        }

        .summary-box .summary-label {
            color: #d0e0e4;
            font-size: 12px;
        }

        .summary-box .summary-value {
            font-size: 25px;
            font-weight: bold;
            margin: 8px 0;
        }

        .summary-box .summary-note {
            color: #c6d8dc;
            font-size: 11px;
        }

        .refund-summary {
            background: var(--cream);
            border: 1px solid #eee4ce;
            color: var(--ocean);
        }

        .refund-summary .summary-label,
        .refund-summary .summary-note {
            color: #7e7157;
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
            padding: 55px 20px;
            color: var(--muted);
        }

        .empty-state .empty-icon {
            font-size: 38px;
            margin-bottom: 10px;
        }

        .sidebar-overlay {
            display: none;
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
                font-size: 22px;
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
        <a href="payments.php" class="nav-link active">
            <span class="nav-symbol">$</span> Payments
        </a>
        <a href="discounts.php" class="nav-link">
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
                    Finance Management
                </div>
                <div class="topbar-title">
                    Azure Bay Resort / Payments
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
                <h1 class="page-title">Payment Management</h1>
                <p class="page-subtitle mb-0">
                    Track guest payments, outstanding balances and refunds.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2 no-print">
                <button
                    class="btn btn-outline-secondary"
                    type="button"
                    onclick="window.print()">
                    <span class="me-1">▤</span> Print Report
                </button>

                <button
                    class="btn btn-gold"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#addPaymentModal">
                    + Record Payment
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
                        <span class="stat-label">Recorded Payments</span>
                        <span class="stat-icon">▤</span>
                    </div>
                    <div class="stat-value"><?= number_format($totalRecords) ?></div>
                    <div class="stat-note">All payment records</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Amount Collected</span>
                        <span class="stat-icon">💳</span>
                    </div>
                    <div class="stat-value"><?= money($totalCollected) ?></div>
                    <div class="stat-note">Gross payments recorded</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Outstanding Balance</span>
                        <span class="stat-icon">◷</span>
                    </div>
                    <div class="stat-value"><?= money($totalOutstanding) ?></div>
                    <div class="stat-note">Remaining on non-refunded records</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Collected Today</span>
                        <span class="stat-icon">↗</span>
                    </div>
                    <div class="stat-value"><?= money($todayCollected) ?></div>
                    <div class="stat-note"><?= e(date('d M Y')) ?></div>
                </div>
            </div>

        </div>

        <!-- PAYMENT OVERVIEW -->

        <div class="row g-3 mb-4">

            <div class="col-12 col-lg-8">
                <div class="summary-box">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="summary-label">Total Outstanding Revenue</div>
                            <div class="summary-value"><?= money($totalOutstanding) ?></div>
                            <div class="summary-note">
                                Outstanding balance from pending and partially paid records.
                            </div>
                        </div>
                        <div style="font-size:30px;">◷</div>
                    </div>

                    <hr style="border-color:rgba(255,255,255,.2);">

                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="summary-label">Pending</div>
                            <div class="fw-bold fs-5"><?= $totalPending ?></div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="summary-label">Partial</div>
                            <div class="fw-bold fs-5"><?= $totalPartial ?></div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="summary-label">Refunded</div>
                            <div class="fw-bold fs-5"><?= $totalRefunded ?></div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="summary-label">Refund Amount</div>
                            <div class="fw-bold fs-5"><?= money($totalRefundAmount) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="summary-box refund-summary">
                    <div class="summary-label">Payment Collection</div>
                    <div class="summary-value"><?= money($totalCollected) ?></div>
                    <div class="summary-note">
                        Total amount entered as collected across the payment records.
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small">Fully paid records</span>
                            <strong>
                                <?= count(array_filter($payments, function ($p) {
                                    return $p['status'] === 'Paid';
                                })) ?>
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="small">Refund transactions</span>
                            <strong><?= count($refunds) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- PAYMENT TABLE -->

        <section class="content-card">

            <div class="card-heading">
                <div>
                    <h5>Guest Payment Records</h5>
                    <div class="small-muted">
                        Search, review and update payment information.
                    </div>
                </div>

                <span class="badge text-bg-light border">
                    <?= count($filteredPayments) ?> record(s)
                </span>
            </div>

            <div class="filter-area no-print">
                <form method="GET" action="payments.php">
                    <div class="row g-3 align-items-end">

                        <div class="col-12 col-lg-4">
                            <label class="form-label">Search Payments</label>
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Guest, reservation, room or reference..."
                                value="<?= e($search) ?>">
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="form-label">Payment Status</label>
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
                            <label class="form-label">Payment Method</label>
                            <select name="method" class="form-select">
                                <option value="">All methods</option>
                                <?php foreach ($validMethods as $method): ?>
                                    <option value="<?= e($method) ?>"
                                        <?= $methodFilter === $method ? 'selected' : '' ?>>
                                        <?= e($method) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="form-label">From Date</label>
                            <input
                                type="date"
                                name="from"
                                class="form-control"
                                value="<?= e($dateFrom) ?>">
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="form-label">To Date</label>
                            <input
                                type="date"
                                name="to"
                                class="form-control"
                                value="<?= e($dateTo) ?>">
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>
                                    Newest first
                                </option>
                                <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>
                                    Oldest first
                                </option>
                                <option value="highest" <?= $sort === 'highest' ? 'selected' : '' ?>>
                                    Highest amount
                                </option>
                                <option value="lowest" <?= $sort === 'lowest' ? 'selected' : '' ?>>
                                    Lowest amount
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-8 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-ocean">
                                Apply Filters
                            </button>

                            <a href="payments.php" class="btn btn-outline-secondary">
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
                        <th>Payment / Reservation</th>
                        <th>Guest</th>
                        <th>Amount</th>
                        <th>Collected</th>
                        <th>Balance</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="actions-column">Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($filteredPayments)): ?>

                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon">▤</div>
                                    <h6 class="fw-bold" style="color:var(--ocean);">
                                        No payment records found
                                    </h6>
                                    <p class="mb-0">
                                        Try changing the filters or create a new payment record.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($filteredPayments as $payment): ?>
                            <?php
                                $balance = $payment['status'] === 'Refunded'
                                    ? 0
                                    : max(0, (float)$payment['amount'] - (float)$payment['paid']);

                                $methodSymbol = [
                                    'Credit Card' => '▣',
                                    'Debit Card' => '▣',
                                    'Cash' => '$',
                                    'Bank Transfer' => '⇄',
                                    'Online Payment' => '⌘'
                                ][$payment['method']] ?? '$';
                            ?>

                            <tr>
                                <td>
                                    <div class="guest-name">
                                        #<?= (int)$payment['id'] ?>
                                    </div>
                                    <div class="small-muted">
                                        <?= e($payment['reservation']) ?>
                                    </div>
                                    <div class="small-muted">
                                        Room <?= e($payment['room']) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="guest-name">
                                        <?= e($payment['guest']) ?>
                                    </div>
                                    <div class="small-muted">
                                        <?= e($payment['email'] ?: 'No email provided') ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="amount">
                                        <?= money($payment['amount']) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="amount" style="color:var(--green);">
                                        <?= money($payment['paid']) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="amount" style="color:<?= $balance > 0 ? 'var(--red)' : 'var(--green)' ?>;">
                                        <?= money($balance) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="method-icon"><?= e($methodSymbol) ?></span>
                                    <?= e($payment['method']) ?>
                                </td>

                                <td>
                                    <span class="status-badge <?= e(statusClass($payment['status'])) ?>">
                                        <?= e($payment['status']) ?>
                                    </span>
                                </td>

                                <td>
                                    <div>
                                        <?= e(date('d M Y', strtotime($payment['date']))) ?>
                                    </div>
                                    <div class="small-muted">
                                        <?= e(date('h:i A', strtotime($payment['date']))) ?>
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
                                                    class="dropdown-item view-payment-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewPaymentModal"
                                                    data-id="<?= (int)$payment['id'] ?>"
                                                    data-reservation="<?= e($payment['reservation']) ?>"
                                                    data-guest="<?= e($payment['guest']) ?>"
                                                    data-email="<?= e($payment['email']) ?>"
                                                    data-room="<?= e($payment['room']) ?>"
                                                    data-amount="<?= e($payment['amount']) ?>"
                                                    data-paid="<?= e($payment['paid']) ?>"
                                                    data-method="<?= e($payment['method']) ?>"
                                                    data-status="<?= e($payment['status']) ?>"
                                                    data-date="<?= e($payment['date']) ?>"
                                                    data-reference="<?= e($payment['reference']) ?>"
                                                    data-notes="<?= e($payment['notes']) ?>">
                                                    View / Edit Payment
                                                </button>
                                            </li>

                                            <?php if ($payment['status'] !== 'Refunded' && $payment['paid'] > 0): ?>
                                                <li>
                                                    <button
                                                        type="button"
                                                        class="dropdown-item text-danger refund-payment-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#refundModal"
                                                        data-id="<?= (int)$payment['id'] ?>"
                                                        data-guest="<?= e($payment['guest']) ?>"
                                                        data-paid="<?= e($payment['paid']) ?>"
                                                        data-status="<?= e($payment['status']) ?>"
                                                        data-amount="<?= e($payment['amount']) ?>">
                                                        Record Refund
                                                    </button>
                                                </li>
                                            <?php endif; ?>

                                            <li>
                                                <button
                                                    type="button"
                                                    class="dropdown-item"
                                                    onclick="printPayment(<?= (int)$payment['id'] ?>)">
                                                    Print Payment Details
                                                </button>
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
                    Showing <?= count($filteredPayments) ?> of <?= $totalRecords ?> payment records
                </div>
                <div>
                    Outstanding on filtered records:
                    <strong>
                        <?php
                        $filteredOutstanding = 0;
                        foreach ($filteredPayments as $p) {
                            if ($p['status'] !== 'Refunded') {
                                $filteredOutstanding += max(
                                    0,
                                    (float)$p['amount'] - (float)$p['paid']
                                );
                            }
                        }
                        echo money($filteredOutstanding);
                        ?>
                    </strong>
                </div>
            </div>

        </section>

        <div class="text-center mt-4 small-muted">
            Azure Bay Resort &copy; <?= date('Y') ?> |
            Finance Management Demonstration
        </div>

    </div>
</main>

<!-- ==========================================
     ADD PAYMENT MODAL
========================================== -->

<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">Record New Payment</h5>
                    <div class="small" style="color:#d2e0e4;">
                        Add a guest payment to the demonstration records.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form method="POST" action="payments.php">
                <input type="hidden" name="action" value="add_payment">

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Guest Name *</label>
                            <input type="text" name="guest" class="form-control"
                                   maxlength="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Guest Email</label>
                            <input type="email" name="email" class="form-control"
                                   maxlength="150">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Reservation Number *</label>
                            <input type="text" name="reservation" class="form-control"
                                   placeholder="ABR-2026-009" maxlength="50" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Room Number *</label>
                            <input type="text" name="room" class="form-control"
                                   placeholder="A-101" maxlength="30" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Total Amount ($) *</label>
                            <input type="number" name="amount" id="addAmount"
                                   class="form-control" min="0.01" step="0.01"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Amount Collected ($) *</label>
                            <input type="number" name="paid" id="addPaid"
                                   class="form-control" min="0" step="0.01"
                                   value="0" required>
                            <div class="small-muted" id="addBalancePreview">
                                Remaining: $0.00
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Payment Method *</label>
                            <select name="method" class="form-select" required>
                                <option value="">Select method</option>
                                <?php foreach ($validMethods as $method): ?>
                                    <option value="<?= e($method) ?>">
                                        <?= e($method) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Date *</label>
                            <input type="datetime-local" name="date" id="addDate"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Transaction Reference</label>
                            <input type="text" name="reference" class="form-control"
                                   placeholder="TXN-AB1009" maxlength="100">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Payment Notes</label>
                            <textarea name="notes" class="form-control" rows="3"
                                      maxlength="1000"
                                      placeholder="Add any relevant payment information..."></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-gold">
                        Save Payment
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ==========================================
     VIEW / EDIT PAYMENT MODAL
========================================== -->

<div class="modal fade" id="viewPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">Payment Details</h5>
                    <div class="small" style="color:#d2e0e4;">
                        Review and update payment information.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form method="POST" action="payments.php">
                <input type="hidden" name="action" value="update_payment">
                <input type="hidden" name="id" id="editId">

                <div class="modal-body">
                    <div class="alert alert-info small">
                        Refunded records are locked in this demonstration.
                        Updating the collected amount recalculates the payment status.
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Guest</label>
                            <input type="text" id="editGuest" class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Guest Email</label>
                            <input type="text" id="editEmail" class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Reservation Number</label>
                            <input type="text" id="editReservation"
                                   class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Room Number</label>
                            <input type="text" id="editRoom" class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Total Amount ($) *</label>
                            <input type="number" name="amount" id="editAmount"
                                   class="form-control" min="0.01" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Amount Collected ($) *</label>
                            <input type="number" name="paid" id="editPaid"
                                   class="form-control" min="0" step="0.01" required>
                            <div class="small-muted" id="editBalancePreview"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Method *</label>
                            <select name="method" id="editMethod"
                                    class="form-select" required>
                                <?php foreach ($validMethods as $method): ?>
                                    <option value="<?= e($method) ?>">
                                        <?= e($method) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Status</label>
                            <input type="text" id="editStatus" class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Date</label>
                            <input type="text" id="editDate" class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Transaction Reference</label>
                            <input type="text" name="reference" id="editReference"
                                   class="form-control" maxlength="100">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="editNotes" class="form-control"
                                      rows="3" maxlength="1500"></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" id="editSaveButton" class="btn btn-gold">
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ==========================================
     REFUND MODAL
========================================== -->

<div class="modal fade" id="refundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">Record Refund</h5>
                    <div class="small" style="color:#d2e0e4;">
                        Register a refund against a guest payment.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form method="POST" action="payments.php" id="refundForm">
                <input type="hidden" name="action" value="record_refund">
                <input type="hidden" name="id" id="refundId">

                <div class="modal-body">
                    <div class="alert alert-warning small">
                        This records a refund in the demo only. It does not transfer
                        money through a bank or payment gateway.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Guest</label>
                        <input type="text" id="refundGuest" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Original Payment Amount</label>
                        <input type="text" id="refundOriginalAmount"
                               class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount Collected</label>
                        <input type="text" id="refundPaidDisplay"
                               class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Refund Amount ($) *</label>
                        <input type="number" name="refund_amount" id="refundAmount"
                               class="form-control" min="0.01" step="0.01" required>
                        <div class="small-muted" id="refundLimitText"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason for Refund *</label>
                        <textarea name="refund_reason" class="form-control"
                                  rows="3" maxlength="500" required
                                  placeholder="Explain why the refund is being issued..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Confirm Refund
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

    // Set the default date/time for new payment records.
    const addDate = document.getElementById('addDate');

    if (addDate) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        addDate.value = now.toISOString().slice(0, 16);
    }

    // Add payment balance preview.
    const addAmount = document.getElementById('addAmount');
    const addPaid = document.getElementById('addPaid');
    const addBalancePreview = document.getElementById('addBalancePreview');

    function updateAddBalance() {
        const total = parseFloat(addAmount.value) || 0;
        const paid = parseFloat(addPaid.value) || 0;
        const balance = Math.max(0, total - paid);

        addBalancePreview.textContent =
            'Remaining: $' + balance.toFixed(2);

        addPaid.max = total > 0 ? total : '';
    }

    addAmount.addEventListener('input', updateAddBalance);
    addPaid.addEventListener('input', updateAddBalance);

    // Populate payment details modal.
    const editAmount = document.getElementById('editAmount');
    const editPaid = document.getElementById('editPaid');
    const editBalancePreview = document.getElementById('editBalancePreview');
    const editSaveButton = document.getElementById('editSaveButton');

    function updateEditBalance() {
        const total = parseFloat(editAmount.value) || 0;
        const paid = parseFloat(editPaid.value) || 0;

        editBalancePreview.textContent =
            'Remaining balance: $' + Math.max(0, total - paid).toFixed(2);

        editPaid.max = total > 0 ? total : '';
    }

    editAmount.addEventListener('input', updateEditBalance);
    editPaid.addEventListener('input', updateEditBalance);

    document.querySelectorAll('.view-payment-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const d = this.dataset;

            document.getElementById('editId').value = d.id;
            document.getElementById('editGuest').value = d.guest;
            document.getElementById('editEmail').value = d.email || 'Not provided';
            document.getElementById('editReservation').value = d.reservation;
            document.getElementById('editRoom').value = d.room;
            document.getElementById('editAmount').value = d.amount;
            document.getElementById('editPaid').value = d.paid;
            document.getElementById('editMethod').value = d.method;
            document.getElementById('editStatus').value = d.status;
            document.getElementById('editDate').value = d.date.replace('T', ' ');
            document.getElementById('editReference').value = d.reference;
            document.getElementById('editNotes').value = d.notes;

            const isRefunded = d.status === 'Refunded';

            editAmount.readOnly = isRefunded;
            editPaid.readOnly = isRefunded;
            document.getElementById('editMethod').disabled = isRefunded;
            document.getElementById('editReference').readOnly = isRefunded;
            document.getElementById('editNotes').readOnly = isRefunded;
            editSaveButton.disabled = isRefunded;

            updateEditBalance();
        });
    });

    // Populate refund modal.
    document.querySelectorAll('.refund-payment-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const d = this.dataset;

            document.getElementById('refundId').value = d.id;
            document.getElementById('refundGuest').value = d.guest;
            document.getElementById('refundOriginalAmount').value =
                '$' + parseFloat(d.amount).toFixed(2);
            document.getElementById('refundPaidDisplay').value =
                '$' + parseFloat(d.paid).toFixed(2);

            // This is a demo limit. Server-side validation remains authoritative.
            const limit = parseFloat(d.paid) || 0;

            const refundAmount = document.getElementById('refundAmount');
            refundAmount.max = limit;
            refundAmount.value = '';

            document.getElementById('refundLimitText').textContent =
                'Maximum refund for this record: $' + limit.toFixed(2);
        });
    });

    // Client-side validation for payment forms.
    document.getElementById('refundForm').addEventListener('submit', function(event) {
        const amount = parseFloat(document.getElementById('refundAmount').value);

        if (!Number.isFinite(amount) || amount <= 0) {
            event.preventDefault();
            alert('Enter a valid refund amount.');
        }
    });

    // Print a single payment record.
    function printPayment(id) {
        const row = Array.from(document.querySelectorAll('tbody tr')).find(function(tr) {
            return tr.querySelector('.view-payment-btn') &&
                tr.querySelector('.view-payment-btn').dataset.id === String(id);
        });

        if (!row) {
            alert('Please clear the filters if you cannot see this payment record.');
            return;
        }

        const button = row.querySelector('.view-payment-btn');
        const d = button.dataset;

        const safe = function(value) {
            return String(value || '').replace(/[&<>"']/g, function(char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                }[char];
            });
        };

        const paid = parseFloat(d.paid) || 0;
        const amount = parseFloat(d.amount) || 0;
        const balance = d.status === 'Refunded' ? 0 : Math.max(0, amount - paid);

        const printWindow = window.open('', '_blank', 'width=800,height=650');

        if (!printWindow) {
            alert('Please allow pop-ups to print payment details.');
            return;
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Payment #${safe(d.id)} - Azure Bay Resort</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        color: #23343c;
                        padding: 35px;
                    }
                    h1 { color: #073b4c; margin-bottom: 5px; }
                    .subtitle { color: #777; margin-bottom: 30px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td {
                        padding: 12px;
                        text-align: left;
                        border-bottom: 1px solid #ddd;
                    }
                    th { width: 35%; background: #f4f7f8; }
                    .notice { margin-top: 30px; font-size: 12px; color: #777; }
                </style>
            </head>
            <body>
                <h1>Azure Bay Resort</h1>
                <div class="subtitle">Payment Record · #${safe(d.id)}</div>
                <table>
                    <tr><th>Reservation</th><td>${safe(d.reservation)}</td></tr>
                    <tr><th>Guest</th><td>${safe(d.guest)}</td></tr>
                    <tr><th>Email</th><td>${safe(d.email || 'Not provided')}</td></tr>
                    <tr><th>Room</th><td>${safe(d.room)}</td></tr>
                    <tr><th>Total Amount</th><td>$${amount.toFixed(2)}</td></tr>
                    <tr><th>Collected</th><td>$${paid.toFixed(2)}</td></tr>
                    <tr><th>Outstanding Balance</th><td>$${balance.toFixed(2)}</td></tr>
                    <tr><th>Payment Method</th><td>${safe(d.method)}</td></tr>
                    <tr><th>Status</th><td>${safe(d.status)}</td></tr>
                    <tr><th>Payment Date</th><td>${safe(d.date.replace('T', ' '))}</td></tr>
                    <tr><th>Transaction Reference</th><td>${safe(d.reference || 'Not provided')}</td></tr>
                    <tr><th>Notes</th><td>${safe(d.notes || 'No notes')}</td></tr>
                </table>
                <div class="notice">
                    Demonstration record only. This printout is not a bank-issued receipt.
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                    };
                <\/script>
            </body>
            </html>
        `);

        printWindow.document.close();
    }
</script>

</body>
</html>