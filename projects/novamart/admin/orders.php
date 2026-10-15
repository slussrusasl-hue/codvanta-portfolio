
<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart - Order Management
|--------------------------------------------------------------------------
| Demonstration page using PHP session data.
| No database connection is required.
*/

// Escape output safely.
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('money')) {
    function money($amount) {
        return 'Rs. ' . number_format((float)$amount, 2);
    }
}

// Sample orders are initialized once per session.
if (!isset($_SESSION['novamart_admin_orders'])) {
    $_SESSION['novamart_admin_orders'] = [
        [
            'id' => 1001,
            'customer' => 'Amelia Perera',
            'email' => 'amelia@example.com',
            'phone' => '077 123 4567',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'status' => 'Pending',
            'payment' => 'Cash on Delivery',
            'payment_status' => 'Pending',
            'address' => 'No. 24, Flower Road, Colombo 07',
            'delivery_fee' => 350,
            'items' => [
                [
                    'name' => 'Wireless Bluetooth Earbuds',
                    'price' => 4500,
                    'quantity' => 1,
                    'image' => 'earbuds.jpg'
                ],
                [
                    'name' => 'Smart Watch Pro',
                    'price' => 8500,
                    'quantity' => 1,
                    'image' => 'smartwatch.jpg'
                ]
            ]
        ],
        [
            'id' => 1002,
            'customer' => 'Kasun Fernando',
            'email' => 'kasun@example.com',
            'phone' => '071 234 5678',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'status' => 'Processing',
            'payment' => 'Bank Transfer',
            'payment_status' => 'Paid',
            'address' => 'No. 15, Kandy Road, Kiribathgoda',
            'delivery_fee' => 0,
            'items' => [
                [
                    'name' => 'Laptop Backpack',
                    'price' => 3800,
                    'quantity' => 2,
                    'image' => 'backpack.jpg'
                ]
            ]
        ],
        [
            'id' => 1003,
            'customer' => 'Nethmi Silva',
            'email' => 'nethmi@example.com',
            'phone' => '076 345 6789',
            'date' => date('Y-m-d', strtotime('-2 days')),
            'status' => 'Shipped',
            'payment' => 'Card Payment',
            'payment_status' => 'Paid',
            'address' => 'No. 42, Galle Road, Dehiwala',
            'delivery_fee' => 0,
            'items' => [
                [
                    'name' => 'Wireless Bluetooth Earbuds',
                    'price' => 4500,
                    'quantity' => 1,
                    'image' => 'earbuds.jpg'
                ],
                [
                    'name' => 'Gaming Mouse',
                    'price' => 3200,
                    'quantity' => 1,
                    'image' => 'gaming-mouse.jpg'
                ]
            ]
        ],
        [
            'id' => 1004,
            'customer' => 'Tharindu Jayawardena',
            'email' => 'tharindu@example.com',
            'phone' => '075 456 7890',
            'date' => date('Y-m-d', strtotime('-3 days')),
            'status' => 'Delivered',
            'payment' => 'Cash on Delivery',
            'payment_status' => 'Paid',
            'address' => 'No. 9, Lake Road, Kurunegala',
            'delivery_fee' => 350,
            'items' => [
                [
                    'name' => 'Smart Watch Pro',
                    'price' => 8500,
                    'quantity' => 1,
                    'image' => 'smartwatch.jpg'
                ]
            ]
        ],
        [
            'id' => 1005,
            'customer' => 'Dinushi Wickramasinghe',
            'email' => 'dinushi@example.com',
            'phone' => '072 567 8901',
            'date' => date('Y-m-d', strtotime('-4 days')),
            'status' => 'Cancelled',
            'payment' => 'Card Payment',
            'payment_status' => 'Refund Pending',
            'address' => 'No. 18, Temple Road, Gampaha',
            'delivery_fee' => 350,
            'items' => [
                [
                    'name' => 'Wireless Bluetooth Earbuds',
                    'price' => 4500,
                    'quantity' => 2,
                    'image' => 'earbuds.jpg'
                ]
            ]
        ],
        [
            'id' => 1006,
            'customer' => 'Ravindu Peris',
            'email' => 'ravindu@example.com',
            'phone' => '078 678 9012',
            'date' => date('Y-m-d'),
            'status' => 'Pending',
            'payment' => 'Cash on Delivery',
            'payment_status' => 'Pending',
            'address' => 'No. 31, Main Street, Negombo',
            'delivery_fee' => 350,
            'items' => [
                [
                    'name' => 'Laptop Backpack',
                    'price' => 3800,
                    'quantity' => 1,
                    'image' => 'backpack.jpg'
                ],
                [
                    'name' => 'Gaming Mouse',
                    'price' => 3200,
                    'quantity' => 1,
                    'image' => 'gaming-mouse.jpg'
                ]
            ]
        ]
    ];
}

$allowedStatuses = [
    'Pending',
    'Processing',
    'Shipped',
    'Delivered',
    'Cancelled'
];

$successMessage = '';
$errorMessage = '';

// Handle order status updates.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $newStatus = trim($_POST['new_status'] ?? '');

    if (!in_array($newStatus, $allowedStatuses, true)) {
        $errorMessage = 'Please select a valid order status.';
    } else {
        $orderFound = false;

        foreach ($_SESSION['novamart_admin_orders'] as &$order) {
            if ((int)$order['id'] === $orderId) {
                $orderFound = true;

                $oldStatus = $order['status'];

                // Delivered and cancelled orders are treated as final.
                if (in_array($oldStatus, ['Delivered', 'Cancelled'], true)
                    && $oldStatus !== $newStatus) {
                    $errorMessage =
                        'Delivered or cancelled orders cannot be changed in this demonstration.';
                    break;
                }

                $order['status'] = $newStatus;

                // Demonstration payment update for COD orders.
                if (
                    $newStatus === 'Delivered'
                    && $order['payment'] === 'Cash on Delivery'
                ) {
                    $order['payment_status'] = 'Paid';
                }

                if ($newStatus === 'Cancelled') {
                    $order['payment_status'] =
                        ($order['payment_status'] === 'Paid')
                        ? 'Refund Pending'
                        : 'Not Required';
                }

                if ($oldStatus !== $newStatus) {
                    $successMessage =
                        'Order #' . $orderId .
                        ' status updated to ' . $newStatus . '.';
                } else {
                    $successMessage = 'The order already has this status.';
                }

                break;
            }
        }

        unset($order);

        if (!$orderFound && $errorMessage === '') {
            $errorMessage = 'The selected order could not be found.';
        }
    }
}

// Prepare orders for display.
$orders = array_values($_SESSION['novamart_admin_orders']);

foreach ($orders as &$order) {
    $subtotal = 0;
    $itemCount = 0;

    foreach ($order['items'] as $item) {
        $subtotal += (float)$item['price'] * (int)$item['quantity'];
        $itemCount += (int)$item['quantity'];
    }

    $order['subtotal'] = $subtotal;
    $order['item_count'] = $itemCount;
    $order['total'] = $subtotal + (float)$order['delivery_fee'];
}
unset($order);

// Search and filter.
$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

$filteredOrders = array_filter($orders, function ($order) use ($search, $statusFilter) {
    $matchesSearch = true;
    $matchesStatus = true;

    if ($search !== '') {
        $searchText = strtolower($search);

        $matchesSearch =
            strpos(strtolower((string)$order['id']), $searchText) !== false ||
            strpos(strtolower($order['customer']), $searchText) !== false ||
            strpos(strtolower($order['email']), $searchText) !== false;
    }

    if ($statusFilter !== '' && $statusFilter !== 'All') {
        $matchesStatus = $order['status'] === $statusFilter;
    }

    return $matchesSearch && $matchesStatus;
});

$filteredOrders = array_values($filteredOrders);

// Dashboard summary statistics.
$totalOrders = count($orders);
$pendingOrders = 0;
$processingOrders = 0;
$shippedOrders = 0;
$deliveredOrders = 0;
$cancelledOrders = 0;
$totalSales = 0;

foreach ($orders as $order) {
    switch ($order['status']) {
        case 'Pending':
            $pendingOrders++;
            break;
        case 'Processing':
            $processingOrders++;
            break;
        case 'Shipped':
            $shippedOrders++;
            break;
        case 'Delivered':
            $deliveredOrders++;
            $totalSales += $order['total'];
            break;
        case 'Cancelled':
            $cancelledOrders++;
            break;
    }
}

function statusClass($status) {
    switch ($status) {
        case 'Pending':
            return 'status-pending';
        case 'Processing':
            return 'status-processing';
        case 'Shipped':
            return 'status-shipped';
        case 'Delivered':
            return 'status-delivered';
        case 'Cancelled':
            return 'status-cancelled';
        default:
            return 'status-pending';
    }
}

function paymentClass($status) {
    if ($status === 'Paid') {
        return 'payment-paid';
    }

    if ($status === 'Refund Pending') {
        return 'payment-refund';
    }

    return 'payment-pending';
}

function orderTotal($order) {
    $subtotal = 0;

    foreach ($order['items'] as $item) {
        $subtotal += (float)$item['price'] * (int)$item['quantity'];
    }

    return $subtotal + (float)$order['delivery_fee'];
}

// Sales chart data: delivered-order revenue grouped by date.
$salesByDate = [];

foreach ($orders as $order) {
    if ($order['status'] !== 'Delivered') {
        continue;
    }

    $dateKey = $order['date'];

    if (!isset($salesByDate[$dateKey])) {
        $salesByDate[$dateKey] = 0;
    }

    $salesByDate[$dateKey] += orderTotal($order);
}

ksort($salesByDate);
$salesByDate = array_slice($salesByDate, -7, 7, true);

$chartLabels = [];
$chartValues = [];

foreach ($salesByDate as $date => $amount) {
    $chartLabels[] = date('M d', strtotime($date));
    $chartValues[] = round($amount, 2);
}

$chartLabelsJson = json_encode(
    $chartLabels,
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);

$chartValuesJson = json_encode($chartValues);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management | NovaMart Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --navy: #142b52;
            --navy-dark: #0d1e3c;
            --blue: #2864dc;
            --blue-light: #eef4ff;
            --body-bg: #f5f7fb;
            --text: #25324b;
            --muted: #7a869b;
            --border: #e8edf5;
            --green: #16865b;
            --orange: #c87916;
            --red: #d04444;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--body-bg);
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1000;
            width: 250px;
            padding: 25px 17px;
            background: var(--navy-dark);
            color: white;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 12px 30px;
            color: white;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 13px;
            background: var(--blue);
            font-size: 22px;
        }

        .sidebar-label {
            margin: 12px 13px 12px;
            color: #8293b1;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.6px;
            text-transform: uppercase;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 6px;
            padding: 13px 14px;
            border-radius: 10px;
            color: #c0cbe0;
            font-size: 14px;
            transition: .2s;
        }

        .side-link:hover,
        .side-link.active {
            background: #213d6c;
            color: white;
        }

        .side-link.active {
            box-shadow: inset 3px 0 #75a2ff;
        }

        .side-icon {
            width: 23px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-bottom {
            margin-top: 30px;
            padding: 17px 13px;
            border-top: 1px solid #2b3d5d;
            color: #9eacc5;
            font-size: 12px;
            line-height: 1.7;
        }

        .main {
            min-height: 100vh;
            margin-left: 250px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 18px 32px;
            background: white;
            border-bottom: 1px solid var(--border);
        }

        .topbar small {
            display: block;
            margin-top: 3px;
            color: var(--muted);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .avatar {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #e6edff;
            color: var(--navy);
            font-weight: 800;
        }

        .content {
            max-width: 1600px;
            margin: auto;
            padding: 28px 32px 40px;
        }

        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 23px;
        }

        .page-heading h1 {
            margin: 0;
            color: var(--navy);
            font-size: 27px;
            font-weight: 800;
        }

        .page-heading p {
            margin: 7px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .btn-navy {
            border: 0;
            border-radius: 9px;
            padding: 11px 16px;
            background: var(--navy);
            color: white;
            font-weight: 700;
        }

        .btn-navy:hover {
            background: var(--blue);
            color: white;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 24px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 21px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 4px 16px rgba(20, 43, 82, .025);
        }

        .stat-icon {
            display: grid;
            flex: 0 0 49px;
            place-items: center;
            width: 49px;
            height: 49px;
            border-radius: 13px;
            font-size: 22px;
        }

        .icon-blue {
            background: #eaf1ff;
            color: #2864dc;
        }

        .icon-orange {
            background: #fff2df;
            color: #c87916;
        }

        .icon-green {
            background: #e5f7ee;
            color: #16865b;
        }

        .icon-purple {
            background: #f1eaff;
            color: #7851c8;
        }

        .stat-label {
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        .stat-number {
            color: var(--navy);
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
        }

        .stat-note {
            margin-top: 5px;
            color: var(--muted);
            font-size: 11px;
        }

        .panel {
            margin-bottom: 23px;
            overflow: hidden;
            background: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 4px 16px rgba(20, 43, 82, .025);
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
        }

        .panel-title {
            margin: 0;
            color: var(--navy);
            font-size: 17px;
            font-weight: 800;
        }

        .panel-subtitle {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .panel-body {
            padding: 22px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: minmax(200px, 1fr) 200px auto auto;
            gap: 11px;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-control,
        .form-select {
            min-height: 43px;
            border-color: #dfe6f0;
            border-radius: 9px;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #91b2ff;
            box-shadow: 0 0 0 3px rgba(40, 100, 220, .1);
        }

        .table-responsive {
            width: 100%;
        }

        .orders-table {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
            white-space: nowrap;
        }

        .orders-table thead th {
            padding: 14px 15px;
            background: #f7f9fd;
            color: #7c879b;
            border-bottom: 1px solid var(--border);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        .orders-table tbody td {
            padding: 16px 15px;
            border-bottom: 1px solid #eef1f6;
            vertical-align: middle;
            font-size: 13px;
        }

        .orders-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .orders-table tbody tr:hover {
            background: #fbfcff;
        }

        .order-number {
            color: var(--blue);
            font-weight: 800;
        }

        .customer-name {
            color: var(--navy);
            font-weight: 700;
        }

        .customer-email {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .status-badge,
        .payment-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
        }

        .status-pending {
            background: #fff3dc;
            color: #ad6b08;
        }

        .status-processing {
            background: #e7efff;
            color: #285cc1;
        }

        .status-shipped {
            background: #f0e9ff;
            color: #7750c4;
        }

        .status-delivered {
            background: #e0f6e9;
            color: #147849;
        }

        .status-cancelled {
            background: #ffe8e8;
            color: #c13e3e;
        }

        .payment-paid {
            background: #e0f6e9;
            color: #147849;
        }

        .payment-pending {
            background: #fff3dc;
            color: #ad6b08;
        }

        .payment-refund {
            background: #ffe8e8;
            color: #c13e3e;
        }

        .btn-view {
            border: 1px solid #dce5f5;
            border-radius: 8px;
            padding: 8px 11px;
            background: white;
            color: var(--navy);
            font-size: 12px;
            font-weight: 700;
        }

        .btn-view:hover {
            border-color: var(--blue);
            color: var(--blue);
        }

        .empty-state {
            padding: 55px 15px;
            text-align: center;
            color: var(--muted);
        }

        .empty-state .empty-icon {
            margin-bottom: 12px;
            font-size: 36px;
        }

        .pagination-note {
            padding: 16px 22px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
        }

        .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: 16px;
        }

        .modal-header {
            padding: 21px 24px;
            background: var(--navy);
            color: white;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-title {
            font-weight: 800;
        }

        .modal-body {
            padding: 24px;
        }

        .detail-label {
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 11px;
        }

        .detail-value {
            color: var(--navy);
            font-size: 13px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .detail-box {
            height: 100%;
            padding: 13px;
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .product-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid var(--border);
        }

        .product-row:last-child {
            border-bottom: 0;
        }

        .product-name {
            color: var(--navy);
            font-size: 13px;
            font-weight: 700;
        }

        .product-meta {
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .modal-section-title {
            margin: 23px 0 10px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
        }

        .status-select {
            max-width: 170px;
            min-height: 36px;
            padding-top: 6px;
            padding-bottom: 6px;
            font-size: 12px;
        }

        .status-form {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .btn-update {
            border: 0;
            border-radius: 7px;
            padding: 9px 11px;
            background: var(--blue);
            color: white;
            font-size: 11px;
            font-weight: 800;
        }

        .btn-update:hover {
            background: var(--navy);
            color: white;
        }

        .alert {
            border: 0;
            border-radius: 10px;
            font-size: 13px;
        }

        .demo-note {
            padding: 15px 18px;
            border: 1px solid #dce7ff;
            border-radius: 12px;
            background: #eef4ff;
            color: #36527f;
            font-size: 12px;
            line-height: 1.7;
        }

        @media (max-width: 1250px) {
            .stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .content {
                padding-right: 22px;
                padding-left: 22px;
            }

            .topbar {
                padding-right: 22px;
                padding-left: 22px;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                position: static;
                width: 100%;
                padding: 15px;
            }

            .brand {
                padding: 0 7px 15px;
            }

            .sidebar-label,
            .sidebar-bottom {
                display: none;
            }

            .sidebar nav {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .side-link {
                margin: 0;
                padding: 10px;
                font-size: 12px;
            }

            .side-icon {
                width: auto;
            }

            .main {
                margin-left: 0;
            }

            .filter-form {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .topbar {
                padding: 15px;
            }

            .topbar h6 {
                font-size: 14px;
            }

            .content {
                padding: 20px 13px;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-heading h1 {
                font-size: 23px;
            }

            .stat-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .stat-card {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
                padding: 15px;
            }

            .stat-number {
                font-size: 21px;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .panel-header,
            .panel-body {
                padding: 16px;
            }

            .admin-profile .profile-info {
                display: none;
            }

            .sidebar nav {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .modal-body {
                padding: 17px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar">
    <a href="index.php" class="brand">
        <span class="brand-icon">N</span>
        <span>NovaMart</span>
    </a>

    <div class="sidebar-label">Main Menu</div>

    <nav>
        <a href="index.php" class="side-link">
            <span class="side-icon">▦</span>
            Dashboard
        </a>

        <a href="products.php" class="side-link">
            <span class="side-icon">□</span>
            Products
        </a>

        <a href="inventory.php" class="side-link">
            <span class="side-icon">▤</span>
            Inventory
        </a>

        <a href="orders.php" class="side-link active">
            <span class="side-icon">▣</span>
            Orders
        </a>

        <a href="categories.php" class="side-link">
            <span class="side-icon">▧</span>
            Categories
        </a>

        <a href="customers.php" class="side-link">
            <span class="side-icon">♙</span>
            Customers
        </a>

        <a href="reports.php" class="side-link">
            <span class="side-icon">▥</span>
            Reports
        </a>
    </nav>

    <div class="sidebar-bottom">
        <strong style="color:white;">NovaMart Admin</strong><br>
        Store management demonstration<br>
        <span style="color:#8293b1;">Turning shopping into a better experience.</span>
    </div>
</aside>

<!-- Main content -->
<main class="main">

    <header class="topbar">
        <div>
            <h6 class="mb-0 fw-bold" style="color:var(--navy);">
                Order Management
            </h6>
            <small>Manage and track customer orders</small>
        </div>

        <div class="admin-profile">
            <div class="text-end profile-info">
                <div class="fw-bold" style="font-size:13px;color:var(--navy);">
                    Store Administrator
                </div>
                <small>Administrator</small>
            </div>

            <div class="avatar">A</div>
        </div>
    </header>

    <section class="content">

        <div class="page-heading">
            <div>
                <h1>Order Management</h1>
                <p>Review customer purchases, track delivery, and update order statuses.</p>
            </div>

            <a href="index.php" class="btn btn-navy">
                ← Back to Dashboard
            </a>
        </div>

        <?php if ($successMessage !== ''): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <?= e($successMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage !== ''): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Unable to update:</strong> <?= e($errorMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Summary cards -->
        <div class="stat-grid">

            <div class="stat-card">
                <div class="stat-icon icon-blue">▣</div>
                <div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-number"><?= number_format($totalOrders) ?></div>
                    <div class="stat-note">All recorded demo orders</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-orange">◷</div>
                <div>
                    <div class="stat-label">Pending Orders</div>
                    <div class="stat-number"><?= number_format($pendingOrders) ?></div>
                    <div class="stat-note">Waiting for processing</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-purple">➤</div>
                <div>
                    <div class="stat-label">Shipped Orders</div>
                    <div class="stat-number"><?= number_format($shippedOrders) ?></div>
                    <div class="stat-note">Orders on the way</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-green">✓</div>
                <div>
                    <div class="stat-label">Delivered Revenue</div>
                    <div class="stat-number" style="font-size:21px;">
                        <?= money($totalSales) ?>
                    </div>
                    <div class="stat-note">Revenue from delivered demo orders</div>
                </div>
            </div>

        </div>

        <!-- Orders panel -->
        <div class="panel">

            <div class="panel-header">
                <div>
                    <h2 class="panel-title">All Orders</h2>
                    <p class="panel-subtitle">
                        Search, filter, inspect, and update customer orders.
                    </p>
                </div>

                <span class="badge rounded-pill text-bg-light">
                    <?= count($filteredOrders) ?> results
                </span>
            </div>

            <div class="panel-body">

                <!-- Search and filter -->
                <form method="GET" action="orders.php" class="filter-form">

                    <input
                        type="search"
                        name="search"
                        class="form-control"
                        placeholder="Search order number, customer, or email..."
                        value="<?= e($search) ?>"
                    >

                    <select name="status" class="form-select">
                        <option value="All"
                            <?= ($statusFilter === '' || $statusFilter === 'All') ? 'selected' : '' ?>>
                            All Order Statuses
                        </option>

                        <?php foreach ($allowedStatuses as $status): ?>
                            <option value="<?= e($status) ?>"
                                <?= $statusFilter === $status ? 'selected' : '' ?>>
                                <?= e($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-navy">
                        Search Orders
                    </button>

                    <a href="orders.php" class="btn btn-light border"
                       style="border-radius:9px;padding:11px 15px;font-size:13px;">
                        Reset
                    </a>

                </form>

                <!-- Orders table -->
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Update Status</th>
                            <th>Details</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php if (empty($filteredOrders)): ?>

                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-icon">⌕</div>
                                        <h5 style="color:var(--navy);font-weight:800;">
                                            No orders found
                                        </h5>
                                        <p class="mb-0">
                                            Try another search term or select a different status.
                                        </p>
                                    </div>
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($filteredOrders as $order): ?>
                                <?php
                                $isFinal = in_array(
                                    $order['status'],
                                    ['Delivered', 'Cancelled'],
                                    true
                                );
                                ?>

                                <tr>
                                    <td>
                                        <span class="order-number">
                                            #<?= e($order['id']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="customer-name">
                                            <?= e($order['customer']) ?>
                                        </span>
                                        <span class="customer-email">
                                            <?= e($order['email']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= e(date('d M Y', strtotime($order['date']))) ?>
                                    </td>

                                    <td>
                                        <?= (int)$order['item_count'] ?>
                                        item<?= $order['item_count'] !== 1 ? 's' : '' ?>
                                    </td>

                                    <td class="fw-bold" style="color:var(--navy);">
                                        <?= money($order['total']) ?>
                                    </td>

                                    <td>
                                        <span class="payment-badge <?= e(paymentClass($order['payment_status'])) ?>">
                                            <?= e($order['payment_status']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="status-badge <?= e(statusClass($order['status'])) ?>">
                                            <?= e($order['status']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($isFinal): ?>

                                            <span class="text-muted" style="font-size:11px;">
                                                Final status
                                            </span>

                                        <?php else: ?>

                                            <form method="POST" action="orders.php"
                                                  class="status-form">

                                                <input type="hidden" name="order_id"
                                                       value="<?= (int)$order['id'] ?>">

                                                <select name="new_status"
                                                        class="form-select status-select"
                                                        aria-label="New status for order <?= (int)$order['id'] ?>"
                                                        required>
                                                    <?php foreach ($allowedStatuses as $status): ?>
                                                        <?php
                                                        if (in_array($status, ['Delivered', 'Cancelled'], true)) {
                                                            // Final statuses are still selectable for active orders.
                                                        }
                                                        ?>
                                                        <option value="<?= e($status) ?>"
                                                            <?= $order['status'] === $status ? 'selected' : '' ?>>
                                                            <?= e($status) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <button type="submit" class="btn-update">
                                                    Save
                                                </button>

                                            </form>

                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <button
                                            type="button"
                                            class="btn-view"
                                            data-bs-toggle="modal"
                                            data-bs-target="#orderModal"
                                            data-order-id="<?= (int)$order['id'] ?>"
                                        >
                                            View
                                        </button>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-note">
                    Showing <strong><?= count($filteredOrders) ?></strong>
                    of <strong><?= $totalOrders ?></strong> demo orders.
                    Status changes are stored in the current PHP session.
                </div>

            </div>
        </div>

        <!-- Workflow guide -->
        <div class="demo-note">
            <strong>Order workflow demonstration</strong><br>
            <strong>Pending</strong> → <strong>Processing</strong> →
            <strong>Shipped</strong> → <strong>Delivered</strong>.
            Orders may be marked as <strong>Cancelled</strong> when appropriate.
            Delivered and cancelled orders are treated as final in this demo.
            This page does not contact a payment provider or delivery service.
        </div>

    </section>
</main>

<!-- Order details modal -->
<div class="modal fade" id="orderModal" tabindex="-1"
     aria-labelledby="orderModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="orderModalLabel">
                        Order Details
                    </h5>
                    <div class="small" style="color:#c9d7f0;">
                        NovaMart customer order summary
                    </div>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="orderDetailsContent">
                <div class="text-center py-4 text-muted">
                    Loading order details...
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
// Order information is encoded by PHP and read by the modal.
const orderDetails = <?= json_encode(
    array_values($orders),
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
) ?>;

const orderModal = document.getElementById('orderModal');
const orderDetailsContent = document.getElementById('orderDetailsContent');

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, function (char) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char];
    });
}

function formatMoney(value) {
    return 'Rs. ' + Number(value || 0).toLocaleString('en-LK', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function calculateOrderSubtotal(order) {
    return (order.items || []).reduce(function (sum, item) {
        return sum + (Number(item.price) * Number(item.quantity));
    }, 0);
}

orderModal.addEventListener('show.bs.modal', function (event) {
    const trigger = event.relatedTarget;

    if (!trigger) {
        return;
    }

    const orderId = Number(trigger.getAttribute('data-order-id'));
    const order = orderDetails.find(function (item) {
        return Number(item.id) === orderId;
    });

    if (!order) {
        orderDetailsContent.innerHTML =
            '<div class="alert alert-warning">Order details could not be found.</div>';
        return;
    }

    const subtotal = calculateOrderSubtotal(order);
    const deliveryFee = Number(order.delivery_fee || 0);
    const total = subtotal + deliveryFee;

    const orderDate = new Date(order.date + 'T00:00:00');
    const formattedDate = Number.isNaN(orderDate.getTime())
        ? escapeHtml(order.date)
        : orderDate.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });

    let productsHtml = '';

    (order.items || []).forEach(function (item) {
        const quantity = Number(item.quantity || 0);
        const price = Number(item.price || 0);

        productsHtml += `
            <div class="product-row">
                <div>
                    <div class="product-name">${escapeHtml(item.name)}</div>
                    <div class="product-meta">
                        ${formatMoney(price)} × ${quantity}
                    </div>
                </div>
                <div class="fw-bold text-nowrap" style="color:var(--navy);">
                    ${formatMoney(price * quantity)}
                </div>
            </div>
        `;
    });

    orderDetailsContent.innerHTML = `
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <div class="detail-label">Order Number</div>
                <div class="detail-value" style="font-size:19px;">
                    #${escapeHtml(order.id)}
                </div>
            </div>
            <span class="status-badge ${getStatusClass(order.status)}">
                ${escapeHtml(order.status)}
            </span>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="detail-box">
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">${escapeHtml(order.customer)}</div>
                    <div class="small text-muted mt-2">${escapeHtml(order.email)}</div>
                    <div class="small text-muted mt-1">${escapeHtml(order.phone)}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="detail-box">
                    <div class="detail-label">Order Date</div>
                    <div class="detail-value">${formattedDate}</div>
                    <div class="detail-label mt-3">Payment Method</div>
                    <div class="detail-value">${escapeHtml(order.payment)}</div>
                    <div class="mt-2">
                        <span class="payment-badge ${getPaymentClass(order.payment_status)}">
                            ${escapeHtml(order.payment_status)}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <h6 class="modal-section-title">Delivery Address</h6>
        <div class="detail-box">
            <div class="detail-value">${escapeHtml(order.address)}</div>
        </div>

        <h6 class="modal-section-title">Ordered Products</h6>
        <div>${productsHtml}</div>

        <div class="mt-3 ms-auto" style="max-width:330px;">
            <div class="d-flex justify-content-between py-2 small">
                <span class="text-muted">Subtotal</span>
                <span class="fw-bold">${formatMoney(subtotal)}</span>
            </div>
            <div class="d-flex justify-content-between py-2 small">
                <span class="text-muted">Delivery Fee</span>
                <span class="fw-bold">${deliveryFee === 0 ? 'Free' : formatMoney(deliveryFee)}</span>
            </div>
            <div class="d-flex justify-content-between mt-1 pt-3 border-top">
                <span class="fw-bold" style="color:var(--navy);">Order Total</span>
                <span class="fw-bold" style="font-size:18px;color:var(--navy);">
                    ${formatMoney(total)}
                </span>
            </div>
        </div>

        <div class="small text-muted mt-4">
            Demonstration data only. No real payment or delivery service is connected.
        </div>
    `;
});

function getStatusClass(status) {
    const classes = {
        'Pending': 'status-pending',
        'Processing': 'status-processing',
        'Shipped': 'status-shipped',
        'Delivered': 'status-delivered',
        'Cancelled': 'status-cancelled'
    };

    return classes[status] || 'status-pending';
}

function getPaymentClass(status) {
    if (status === 'Paid') {
        return 'payment-paid';
    }

    if (status === 'Refund Pending') {
        return 'payment-refund';
    }

    return 'payment-pending';
}
</script>

</body>
</html>
