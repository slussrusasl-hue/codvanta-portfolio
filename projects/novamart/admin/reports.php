
<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart - Sales and Inventory Reports
|--------------------------------------------------------------------------
| Demonstration features:
| - Sales and revenue summaries
| - Daily, weekly and monthly order reports
| - Best-selling products
| - Low-stock and out-of-stock reports
| - Date-range filters
| - Printable reports
|
| Demo data is stored in PHP sessions.
*/

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function money($amount)
{
    return 'Rs. ' . number_format((float)$amount, 2);
}

/*
|--------------------------------------------------------------------------
| Sample order data
|--------------------------------------------------------------------------
| For reporting purposes, only Delivered orders count as completed sales.
| Other statuses remain visible in order-count summaries.
*/

if (!isset($_SESSION['novamart_report_orders'])) {
    $_SESSION['novamart_report_orders'] = [
        [
            'id' => 1001,
            'date' => '2026-10-01',
            'customer' => 'Amelia Perera',
            'status' => 'Delivered',
            'total' => 9050,
            'items' => [
                ['name' => 'Wireless Bluetooth Earbuds', 'quantity' => 2, 'price' => 4500],
                ['name' => 'Laptop Backpack', 'quantity' => 1, 'price' => 4200]
            ]
        ],
        [
            'id' => 1002,
            'date' => '2026-10-02',
            'customer' => 'Kasun Fernando',
            'status' => 'Delivered',
            'total' => 8850,
            'items' => [
                ['name' => 'Smart Watch Pro', 'quantity' => 1, 'price' => 8500]
            ]
        ],
        [
            'id' => 1003,
            'date' => '2026-10-03',
            'customer' => 'Nethmi Silva',
            'status' => 'Processing',
            'total' => 10700,
            'items' => [
                ['name' => 'Gaming Mouse', 'quantity' => 2, 'price' => 3500],
                ['name' => 'Gaming Headset', 'quantity' => 1, 'price' => 7200]
            ]
        ],
        [
            'id' => 1004,
            'date' => '2026-10-04',
            'customer' => 'Ravindu Peris',
            'status' => 'Delivered',
            'total' => 4350,
            'items' => [
                ['name' => 'Laptop Stand', 'quantity' => 1, 'price' => 4000]
            ]
        ],
        [
            'id' => 1005,
            'date' => '2026-10-05',
            'customer' => 'Dinushi Wickramasinghe',
            'status' => 'Shipped',
            'total' => 10000,
            'items' => [
                ['name' => 'Portable Blender', 'quantity' => 1, 'price' => 6800],
                ['name' => 'Face Care Set', 'quantity' => 1, 'price' => 3200]
            ]
        ],
        [
            'id' => 1006,
            'date' => '2026-10-06',
            'customer' => 'Sachini Gunawardena',
            'status' => 'Delivered',
            'total' => 5350,
            'items' => [
                ['name' => 'Casual T-Shirt', 'quantity' => 2, 'price' => 2500]
            ]
        ],
        [
            'id' => 1007,
            'date' => '2026-10-07',
            'customer' => 'Ishara Senanayake',
            'status' => 'Pending',
            'total' => 8850,
            'items' => [
                ['name' => 'Wireless Bluetooth Earbuds', 'quantity' => 1, 'price' => 4500],
                ['name' => 'Laptop Stand', 'quantity' => 1, 'price' => 4000]
            ]
        ],
        [
            'id' => 1008,
            'date' => '2026-10-08',
            'customer' => 'Malith Abeysekara',
            'status' => 'Delivered',
            'total' => 8850,
            'items' => [
                ['name' => 'Smart Watch Pro', 'quantity' => 1, 'price' => 8500]
            ]
        ],
        [
            'id' => 1009,
            'date' => '2026-10-09',
            'customer' => 'Amelia Perera',
            'status' => 'Cancelled',
            'total' => 3150,
            'items' => [
                ['name' => 'Building Blocks Set', 'quantity' => 1, 'price' => 2800]
            ]
        ],
        [
            'id' => 1010,
            'date' => '2026-10-10',
            'customer' => 'Kasun Fernando',
            'status' => 'Delivered',
            'total' => 12500,
            'items' => [
                ['name' => 'Gaming Headset', 'quantity' => 1, 'price' => 7200],
                ['name' => 'Gaming Mouse', 'quantity' => 1, 'price' => 3500]
            ]
        ],
        [
            'id' => 1011,
            'date' => '2026-10-11',
            'customer' => 'Nethmi Silva',
            'status' => 'Delivered',
            'total' => 6500,
            'items' => [
                ['name' => 'Portable Blender', 'quantity' => 1, 'price' => 6800]
            ]
        ],
        [
            'id' => 1012,
            'date' => '2026-10-12',
            'customer' => 'Tharindu Jayawardena',
            'status' => 'Processing',
            'total' => 5850,
            'items' => [
                ['name' => 'Wireless Keyboard', 'quantity' => 1, 'price' => 5500]
            ]
        ],
        [
            'id' => 1013,
            'date' => '2026-10-13',
            'customer' => 'Dinushi Wickramasinghe',
            'status' => 'Delivered',
            'total' => 15200,
            'items' => [
                ['name' => 'Smart Watch Pro', 'quantity' => 1, 'price' => 8500],
                ['name' => 'Wireless Bluetooth Earbuds', 'quantity' => 1, 'price' => 4500]
            ]
        ],
        [
            'id' => 1014,
            'date' => '2026-10-14',
            'customer' => 'Ravindu Peris',
            'status' => 'Pending',
            'total' => 4350,
            'items' => [
                ['name' => 'Laptop Stand', 'quantity' => 1, 'price' => 4000]
            ]
        ],
        [
            'id' => 1015,
            'date' => '2026-10-15',
            'customer' => 'Sachini Gunawardena',
            'status' => 'Delivered',
            'total' => 10800,
            'items' => [
                ['name' => 'Gaming Headset', 'quantity' => 1, 'price' => 7200],
                ['name' => 'Gaming Mouse', 'quantity' => 1, 'price' => 3500]
            ]
        ],
        [
            'id' => 1016,
            'date' => '2026-10-15',
            'customer' => 'Ishara Senanayake',
            'status' => 'Shipped',
            'total' => 9250,
            'items' => [
                ['name' => 'Wireless Bluetooth Earbuds', 'quantity' => 1, 'price' => 4500],
                ['name' => 'Laptop Backpack', 'quantity' => 1, 'price' => 4200]
            ]
        ]
    ];
}

/*
|--------------------------------------------------------------------------
| Sample inventory data
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['novamart_report_inventory'])) {
    $_SESSION['novamart_report_inventory'] = [
        [
            'name' => 'Wireless Bluetooth Earbuds',
            'sku' => 'ELE-001',
            'category' => 'Electronics',
            'stock' => 7,
            'reorder_level' => 10
        ],
        [
            'name' => 'Smart Watch Pro',
            'sku' => 'ELE-002',
            'category' => 'Electronics',
            'stock' => 18,
            'reorder_level' => 8
        ],
        [
            'name' => 'Laptop Backpack',
            'sku' => 'ACC-001',
            'category' => 'Accessories',
            'stock' => 4,
            'reorder_level' => 10
        ],
        [
            'name' => 'Gaming Mouse',
            'sku' => 'GAM-001',
            'category' => 'Gaming',
            'stock' => 0,
            'reorder_level' => 5
        ],
        [
            'name' => 'Gaming Headset',
            'sku' => 'GAM-002',
            'category' => 'Gaming',
            'stock' => 3,
            'reorder_level' => 8
        ],
        [
            'name' => 'Laptop Stand',
            'sku' => 'ACC-002',
            'category' => 'Accessories',
            'stock' => 14,
            'reorder_level' => 6
        ],
        [
            'name' => 'Portable Blender',
            'sku' => 'HOM-001',
            'category' => 'Home Appliances',
            'stock' => 2,
            'reorder_level' => 8
        ],
        [
            'name' => 'Wireless Keyboard',
            'sku' => 'ACC-003',
            'category' => 'Accessories',
            'stock' => 0,
            'reorder_level' => 6
        ],
        [
            'name' => 'Casual T-Shirt',
            'sku' => 'FAS-001',
            'category' => 'Fashion',
            'stock' => 22,
            'reorder_level' => 10
        ],
        [
            'name' => 'Face Care Set',
            'sku' => 'BEA-001',
            'category' => 'Beauty',
            'stock' => 6,
            'reorder_level' => 8
        ],
        [
            'name' => 'USB-C Hub',
            'sku' => 'ACC-004',
            'category' => 'Accessories',
            'stock' => 11,
            'reorder_level' => 5
        ],
        [
            'name' => 'Building Blocks Set',
            'sku' => 'TOY-001',
            'category' => 'Toys & Games',
            'stock' => 5,
            'reorder_level' => 10
        ]
    ];
}

$orders = $_SESSION['novamart_report_orders'];
$inventory = $_SESSION['novamart_report_inventory'];

/*
|--------------------------------------------------------------------------
| Date filters and reporting period
|--------------------------------------------------------------------------
*/

$today = date('Y-m-d');

$defaultStart = date('Y-m-01');

$startDate = $_GET['start_date'] ?? $defaultStart;
$endDate = $_GET['end_date'] ?? $today;
$period = $_GET['period'] ?? 'daily';

$validPeriods = ['daily', 'weekly', 'monthly'];

if (!in_array($period, $validPeriods, true)) {
    $period = 'daily';
}

$dateError = '';

if (
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) ||
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate) ||
    !strtotime($startDate) ||
    !strtotime($endDate)
) {
    $startDate = $defaultStart;
    $endDate = $today;
    $dateError = 'Invalid date selection. Default dates have been applied.';
} elseif ($startDate > $endDate) {
    $dateError = 'The start date cannot be later than the end date.';
    $startDate = $defaultStart;
    $endDate = $today;
}

/*
|--------------------------------------------------------------------------
| Filter orders for the selected date range
|--------------------------------------------------------------------------
*/

$filteredOrders = [];

foreach ($orders as $order) {
    if (
        $order['date'] >= $startDate &&
        $order['date'] <= $endDate
    ) {
        $filteredOrders[] = $order;
    }
}

/*
|--------------------------------------------------------------------------
| Calculate sales summaries
|--------------------------------------------------------------------------
*/

$totalOrders = count($filteredOrders);
$deliveredOrders = 0;
$pendingOrders = 0;
$processingOrders = 0;
$shippedOrders = 0;
$cancelledOrders = 0;

$revenue = 0;
$allOrderValue = 0;

foreach ($filteredOrders as $order) {

    $allOrderValue += (float)$order['total'];

    switch ($order['status']) {
        case 'Delivered':
            $deliveredOrders++;
            $revenue += (float)$order['total'];
            break;

        case 'Pending':
            $pendingOrders++;
            break;

        case 'Processing':
            $processingOrders++;
            break;

        case 'Shipped':
            $shippedOrders++;
            break;

        case 'Cancelled':
            $cancelledOrders++;
            break;
    }
}

$averageOrderValue = $deliveredOrders > 0
    ? $revenue / $deliveredOrders
    : 0;

$completionRate = $totalOrders > 0
    ? ($deliveredOrders / $totalOrders) * 100
    : 0;

/*
|--------------------------------------------------------------------------
| Build best-selling product report
|--------------------------------------------------------------------------
| Quantities and sales values are calculated from Delivered orders only.
*/

$productSales = [];

foreach ($filteredOrders as $order) {

    if ($order['status'] !== 'Delivered') {
        continue;
    }

    foreach ($order['items'] as $item) {

        $productName = $item['name'];
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];

        if (!isset($productSales[$productName])) {
            $productSales[$productName] = [
                'name' => $productName,
                'quantity' => 0,
                'revenue' => 0
            ];
        }

        $productSales[$productName]['quantity'] += $quantity;
        $productSales[$productName]['revenue'] += $quantity * $price;
    }
}

$bestSellers = array_values($productSales);

usort($bestSellers, function ($a, $b) {
    if ($a['quantity'] === $b['quantity']) {
        return $b['revenue'] <=> $a['revenue'];
    }

    return $b['quantity'] <=> $a['quantity'];
});

$bestSellers = array_slice($bestSellers, 0, 8);

/*
|--------------------------------------------------------------------------
| Low-stock report
|--------------------------------------------------------------------------
*/

$lowStockProducts = [];
$outOfStockCount = 0;

foreach ($inventory as $product) {

    if ((int)$product['stock'] <= 0) {
        $outOfStockCount++;
    }

    if ((int)$product['stock'] <= (int)$product['reorder_level']) {
        $lowStockProducts[] = $product;
    }
}

usort($lowStockProducts, function ($a, $b) {
    return (int)$a['stock'] <=> (int)$b['stock'];
});

/*
|--------------------------------------------------------------------------
| Daily, weekly and monthly order summaries
|--------------------------------------------------------------------------
*/

$groupedReports = [];

foreach ($filteredOrders as $order) {

    $date = new DateTime($order['date']);

    if ($period === 'daily') {
        $key = $date->format('Y-m-d');
        $label = $date->format('d M Y');
    } elseif ($period === 'weekly') {
        $weekStart = clone $date;
        $weekStart->modify('monday this week');

        $weekEnd = clone $weekStart;
        $weekEnd->modify('+6 days');

        $key = $weekStart->format('Y-m-d');
        $label = $weekStart->format('d M') .
            ' - ' . $weekEnd->format('d M Y');
    } else {
        $key = $date->format('Y-m');
        $label = $date->format('F Y');
    }

    if (!isset($groupedReports[$key])) {
        $groupedReports[$key] = [
            'label' => $label,
            'orders' => 0,
            'delivered' => 0,
            'pending' => 0,
            'revenue' => 0
        ];
    }

    $groupedReports[$key]['orders']++;

    if ($order['status'] === 'Delivered') {
        $groupedReports[$key]['delivered']++;
        $groupedReports[$key]['revenue'] += (float)$order['total'];
    } elseif (
        $order['status'] === 'Pending' ||
        $order['status'] === 'Processing'
    ) {
        $groupedReports[$key]['pending']++;
    }
}

ksort($groupedReports);

$groupedReports = array_values($groupedReports);

$maxRevenue = 0;

foreach ($groupedReports as $group) {
    $maxRevenue = max($maxRevenue, (float)$group['revenue']);
}

function orderStatusClass($status)
{
    switch ($status) {
        case 'Delivered':
            return 'status-delivered';
        case 'Processing':
            return 'status-processing';
        case 'Shipped':
            return 'status-shipped';
        case 'Cancelled':
            return 'status-cancelled';
        default:
            return 'status-pending';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sales & Inventory Reports | NovaMart Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --navy: #142747;
            --navy-dark: #0e1c33;
            --blue: #315efb;
            --page-bg: #f4f7fc;
            --text-main: #24324b;
            --text-muted: #7a879c;
            --border: #e8edf5;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: var(--text-main);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background: var(--navy);
            color: #fff;
            padding: 25px 17px;
            overflow-y: auto;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 12px 29px;
            color: #fff;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            background: var(--blue);
            font-size: 22px;
        }

        .brand-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .brand small {
            display: block;
            color: #aebbd0;
            font-size: 10px;
            letter-spacing: 1.5px;
            margin-top: 2px;
        }

        .nav-label {
            color: #8fa1bd;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            padding: 13px 13px 9px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 14px;
            border-radius: 9px;
            margin-bottom: 5px;
            color: #c5d0e1;
            transition: .2s;
            font-size: 13px;
        }

        .sidebar-link i {
            font-size: 17px;
            width: 21px;
            text-align: center;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: #29466f;
        }

        .sidebar-link.active {
            box-shadow: inset 3px 0 #7090ff;
        }

        .sidebar-bottom {
            border-top: 1px solid #344864;
            padding: 18px 12px 0;
            margin-top: 30px;
            color: #aebbd0;
            font-size: 11px;
            line-height: 1.7;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 15px 32px;
            border-bottom: 1px solid var(--border);
        }

        .breadcrumb-text {
            color: var(--text-muted);
            font-size: 12px;
            margin-bottom: 4px;
        }

        .breadcrumb-text span {
            color: var(--text-main);
        }

        .page-title {
            font-size: 23px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -.5px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9efff;
            color: var(--blue);
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 14px;
        }

        .admin-name {
            font-size: 12px;
            font-weight: 800;
        }

        .admin-role {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .content {
            padding: 29px 32px 40px;
            max-width: 1700px;
            margin: auto;
        }

        .page-intro {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 25px;
        }

        .page-intro h2 {
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .page-intro p {
            color: var(--text-muted);
            margin: 0;
            line-height: 1.7;
        }

        .btn-primary-custom {
            background: var(--navy);
            color: #fff;
            border: 0;
            border-radius: 9px;
            padding: 11px 17px;
            font-weight: 700;
            white-space: nowrap;
        }

        .btn-primary-custom:hover {
            background: #263f67;
            color: #fff;
        }

        .btn-outline-custom {
            background: #fff;
            border: 1px solid #dfe6f0;
            color: #43516a;
            border-radius: 9px;
            padding: 10px 15px;
            font-weight: 700;
        }

        .btn-outline-custom:hover {
            background: #f1f4f9;
            color: var(--navy);
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #fff;
            padding: 21px;
            border: 1px solid var(--border);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 3px 12px rgba(28, 47, 80, .025);
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 12px;
            margin-bottom: 9px;
        }

        .stat-number {
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.7px;
            overflow-wrap: anywhere;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .icon-blue {
            color: #315efb;
            background: #eaf0ff;
        }

        .icon-green {
            color: #129568;
            background: #e5f8f0;
        }

        .icon-orange {
            color: #d97706;
            background: #fff2df;
        }

        .icon-purple {
            color: #7c3aed;
            background: #f1eaff;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(28, 47, 80, .025);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .panel-header {
            padding: 21px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid var(--border);
        }

        .panel-header h3 {
            font-size: 15px;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .panel-header p {
            color: var(--text-muted);
            font-size: 12px;
            margin: 0;
        }

        .panel-body {
            padding: 23px 24px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 14px;
            align-items: end;
        }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 7px;
            color: #45536b;
        }

        .form-control,
        .form-select {
            border: 1px solid #dfe6f0;
            border-radius: 9px;
            min-height: 43px;
            font-size: 13px;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #8aa5ff;
        }

        .report-range {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 12px;
        }

        .period-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .period-tab {
            padding: 8px 14px;
            border-radius: 8px;
            color: #68768d;
            border: 1px solid #e2e8f1;
            font-size: 11px;
            font-weight: 800;
            background: #fff;
        }

        .period-tab:hover {
            color: var(--blue);
            background: #f6f8ff;
        }

        .period-tab.active {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            padding: 14px 16px;
            color: #7b879b;
            background: #f8faff;
            text-align: left;
            font-size: 10px;
            letter-spacing: .8px;
            text-transform: uppercase;
            font-weight: 800;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        .report-table td {
            padding: 15px 16px;
            border-bottom: 1px solid #eef1f6;
            vertical-align: middle;
            font-size: 12px;
        }

        .report-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .report-table tbody tr:hover {
            background: #fbfcff;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-delivered {
            background: #e5f8f0;
            color: #13865e;
        }

        .status-processing {
            background: #fff2df;
            color: #b96b08;
        }

        .status-shipped {
            background: #eaf0ff;
            color: #315efb;
        }

        .status-cancelled {
            background: #fff0f0;
            color: #c62828;
        }

        .status-pending {
            background: #edf0f5;
            color: #657187;
        }

        .chart-row {
            display: grid;
            grid-template-columns: 125px minmax(0, 1fr) 110px;
            gap: 12px;
            align-items: center;
            margin-bottom: 18px;
        }

        .chart-row:last-child {
            margin-bottom: 0;
        }

        .chart-label {
            color: #526079;
            font-size: 11px;
            font-weight: 700;
        }

        .chart-track {
            background: #eef2f8;
            border-radius: 20px;
            height: 13px;
            overflow: hidden;
        }

        .chart-fill {
            background: #315efb;
            border-radius: 20px;
            height: 100%;
            min-width: 0;
        }

        .chart-value {
            text-align: right;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .product-rank {
            display: inline-grid;
            place-items: center;
            width: 29px;
            height: 29px;
            border-radius: 8px;
            background: #edf2ff;
            color: #315efb;
            font-weight: 800;
        }

        .product-name {
            font-weight: 800;
            color: var(--text-main);
        }

        .product-sub {
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .stock-count {
            font-weight: 800;
        }

        .stock-low {
            color: #d97706;
        }

        .stock-zero {
            color: #d92d20;
        }

        .stock-normal {
            color: #13865e;
        }

        .empty-state {
            text-align: center;
            padding: 35px 20px;
            color: var(--text-muted);
            font-size: 12px;
        }

        .empty-state i {
            font-size: 29px;
            display: block;
            margin-bottom: 9px;
        }

        .info-note {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            background: #edf4ff;
            border: 1px solid #d8e6ff;
            color: #35527f;
            padding: 15px 17px;
            border-radius: 11px;
            font-size: 12px;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .info-note i {
            font-size: 18px;
            margin-top: 1px;
        }

        .footer {
            color: #98a3b5;
            font-size: 11px;
            text-align: center;
            padding: 8px 0 22px;
        }

        .print-header {
            display: none;
        }

        @media (max-width: 1300px) {
            .stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 76px;
                padding: 22px 10px;
            }

            .brand {
                justify-content: center;
                padding: 0 0 27px;
            }

            .brand-text,
            .nav-label,
            .sidebar-link span,
            .sidebar-bottom {
                display: none;
            }

            .sidebar-link {
                justify-content: center;
                padding: 13px 8px;
            }

            .sidebar-link i {
                font-size: 19px;
            }

            .main {
                margin-left: 76px;
            }

            .content {
                padding: 24px 20px;
            }

            .topbar {
                padding: 15px 20px;
            }
        }

        @media (max-width: 650px) {
            .topbar {
                padding: 14px;
            }

            .page-title {
                font-size: 19px;
            }

            .admin-profile .admin-info {
                display: none;
            }

            .content {
                padding: 20px 13px;
            }

            .page-intro {
                flex-direction: column;
            }

            .stat-grid {
                gap: 10px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-number {
                font-size: 20px;
            }

            .stat-icon {
                width: 38px;
                height: 38px;
                font-size: 17px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .panel-header,
            .panel-body {
                padding: 17px;
            }

            .chart-row {
                grid-template-columns: 90px minmax(0, 1fr);
                gap: 9px;
            }

            .chart-value {
                grid-column: 2;
                text-align: left;
                margin-top: -6px;
            }

            .report-table {
                min-width: 700px;
            }
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            body {
                background: #fff !important;
                color: #111 !important;
                font-size: 10px;
            }

            .sidebar,
            .topbar,
            .no-print,
            .footer {
                display: none !important;
            }

            .main {
                margin: 0 !important;
                width: 100% !important;
                min-height: 0 !important;
            }

            .content {
                max-width: none;
                padding: 0 !important;
                margin: 0 !important;
            }

            .print-header {
                display: block !important;
                margin-bottom: 18px;
                border-bottom: 2px solid #142747;
                padding-bottom: 12px;
            }

            .print-header h1 {
                font-size: 23px;
                margin-bottom: 5px;
            }

            .print-header p {
                margin: 3px 0;
            }

            .page-intro {
                margin-bottom: 12px;
            }

            .page-intro h2 {
                font-size: 16px;
            }

            .stat-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 7px;
                margin-bottom: 12px;
            }

            .stat-card,
            .panel {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                border-radius: 5px;
                break-inside: avoid;
            }

            .stat-card {
                padding: 9px;
            }

            .stat-number {
                font-size: 15px;
            }

            .stat-label {
                font-size: 9px;
            }

            .stat-icon {
                display: none;
            }

            .panel {
                margin-bottom: 12px;
            }

            .panel-header {
                padding: 10px;
            }

            .panel-body {
                padding: 10px;
            }

            .report-table th,
            .report-table td {
                padding: 7px;
                font-size: 9px;
                border-color: #ddd;
            }

            .chart-row {
                margin-bottom: 10px;
            }

            .chart-track {
                border: 1px solid #ccc;
            }

            .info-note {
                background: #fff !important;
                color: #333 !important;
                border: 1px solid #ccc;
            }

            .print-hide-section {
                display: none !important;
            }

            .report-table thead {
                display: table-header-group;
            }

            tr {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar no-print">

    <a href="index.php" class="brand">
        <div class="brand-icon">
            <i class="bi bi-bag-check-fill"></i>
        </div>

        <div class="brand-text">
            <div class="brand-name">NovaMart</div>
            <small>ADMIN PANEL</small>
        </div>
    </a>

    <div class="nav-label">Main Menu</div>

    <a href="index.php" class="sidebar-link">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
    </a>

    <a href="products.php" class="sidebar-link">
        <i class="bi bi-box-seam"></i>
        <span>Product Management</span>
    </a>

    <a href="categories.php" class="sidebar-link">
        <i class="bi bi-folder2-open"></i>
        <span>Categories</span>
    </a>

    <a href="inventory.php" class="sidebar-link">
        <i class="bi bi-clipboard-data"></i>
        <span>Inventory</span>
    </a>

    <a href="orders.php" class="sidebar-link">
        <i class="bi bi-receipt"></i>
        <span>Orders</span>
    </a>

    <a href="customers.php" class="sidebar-link">
        <i class="bi bi-people"></i>
        <span>Customers</span>
    </a>

    <a href="reports.php" class="sidebar-link active">
        <i class="bi bi-bar-chart-line"></i>
        <span>Sales Reports</span>
    </a>

    <div class="nav-label">Store</div>

    <a href="../index.php" class="sidebar-link">
        <i class="bi bi-shop"></i>
        <span>View Store</span>
    </a>

    <div class="sidebar-bottom">
        <div><i class="bi bi-shield-check me-1"></i> Admin workspace</div>
        <div>NovaMart Demo v1.0</div>
    </div>

</aside>

<!-- MAIN -->
<main class="main">

    <header class="topbar no-print">

        <div>
            <div class="breadcrumb-text">
                Admin Panel / <span>Sales & Inventory Reports</span>
            </div>

            <h1 class="page-title">Sales & Inventory Reports</h1>
        </div>

        <div class="admin-profile">
            <div class="admin-avatar">AD</div>

            <div class="admin-info">
                <div class="admin-name">Store Administrator</div>
                <div class="admin-role">Super Admin</div>
            </div>
        </div>

    </header>

    <div class="content">

        <!-- PRINT HEADER -->
        <div class="print-header">
            <h1>NovaMart</h1>
            <p><strong>Sales and Inventory Report</strong></p>
            <p>
                Report period:
                <?= e(date('d M Y', strtotime($startDate))) ?>
                to
                <?= e(date('d M Y', strtotime($endDate))) ?>
            </p>
            <p>
                Generated on:
                <?= e(date('d M Y, h:i A')) ?>
            </p>
        </div>

        <!-- PAGE INTRO -->
        <section class="page-intro">

            <div>
                <h2>Business Performance Overview</h2>

                <p>
                    Analyze revenue, track order performance, identify
                    popular products, and monitor stock levels.
                </p>
            </div>

            <div class="d-flex gap-2 no-print">

                <button type="button"
                        class="btn btn-outline-custom"
                        onclick="window.print()">

                    <i class="bi bi-printer me-1"></i>
                    Print Report
                </button>

            </div>

        </section>

        <?php if ($dateError !== ''): ?>

            <div class="alert alert-warning no-print">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= e($dateError) ?>
            </div>

        <?php endif; ?>

        <!-- DATE FILTERS -->
        <section class="panel no-print">

            <div class="panel-header">

                <div>
                    <h3>Report Filters</h3>
                    <p>Choose the date range and reporting interval.</p>
                </div>

                <i class="bi bi-calendar-range fs-4 text-secondary"></i>

            </div>

            <div class="panel-body">

                <form method="GET" action="reports.php">

                    <div class="filter-grid">

                        <div>
                            <label class="form-label" for="start_date">
                                Start Date
                            </label>

                            <input type="date"
                                   class="form-control"
                                   id="start_date"
                                   name="start_date"
                                   value="<?= e($startDate) ?>"
                                   max="<?= e($today) ?>"
                                   required>
                        </div>

                        <div>
                            <label class="form-label" for="end_date">
                                End Date
                            </label>

                            <input type="date"
                                   class="form-control"
                                   id="end_date"
                                   name="end_date"
                                   value="<?= e($endDate) ?>"
                                   max="<?= e($today) ?>"
                                   required>
                        </div>

                        <div>
                            <label class="form-label" for="period">
                                Report Interval
                            </label>

                            <select class="form-select"
                                    id="period"
                                    name="period">

                                <option value="daily"
                                    <?= $period === 'daily' ? 'selected' : '' ?>>
                                    Daily Report
                                </option>

                                <option value="weekly"
                                    <?= $period === 'weekly' ? 'selected' : '' ?>>
                                    Weekly Report
                                </option>

                                <option value="monthly"
                                    <?= $period === 'monthly' ? 'selected' : '' ?>>
                                    Monthly Report
                                </option>

                            </select>
                        </div>

                        <div>
                            <label class="form-label d-block">
                                &nbsp;
                            </label>

                            <button type="submit"
                                    class="btn btn-primary-custom w-100">

                                <i class="bi bi-funnel me-1"></i>
                                Generate
                            </button>
                        </div>

                    </div>

                    <div class="report-range">
                        <i class="bi bi-calendar-check me-1"></i>

                        Showing reports from
                        <strong><?= e(date('d M Y', strtotime($startDate))) ?></strong>
                        to
                        <strong><?= e(date('d M Y', strtotime($endDate))) ?></strong>.
                    </div>

                </form>

                <div class="d-flex flex-wrap gap-2 mt-3">

                    <span class="form-label align-self-center me-1">
                        Quick ranges:
                    </span>

                    <button type="button"
                            class="period-tab"
                            onclick="setRange('today')">
                        Today
                    </button>

                    <button type="button"
                            class="period-tab"
                            onclick="setRange('7days')">
                        Last 7 Days
                    </button>

                    <button type="button"
                            class="period-tab"
                            onclick="setRange('30days')">
                        Last 30 Days
                    </button>

                    <button type="button"
                            class="period-tab"
                            onclick="setRange('month')">
                        This Month
                    </button>

                </div>

            </div>

        </section>

        <!-- SUMMARY CARDS -->
        <section class="stat-grid">

            <div class="stat-card">

                <div>
                    <div class="stat-label">Delivered Revenue</div>

                    <div class="stat-number"
                         style="font-size:22px;">
                        <?= e(money($revenue)) ?>
                    </div>
                </div>

                <div class="stat-icon icon-green">
                    <i class="bi bi-currency-exchange"></i>
                </div>

            </div>

            <div class="stat-card">

                <div>
                    <div class="stat-label">Total Orders</div>

                    <div class="stat-number">
                        <?= number_format($totalOrders) ?>
                    </div>

                    <div class="product-sub">
                        All statuses
                    </div>
                </div>

                <div class="stat-icon icon-blue">
                    <i class="bi bi-receipt"></i>
                </div>

            </div>

            <div class="stat-card">

                <div>
                    <div class="stat-label">Average Delivered Order</div>

                    <div class="stat-number"
                         style="font-size:22px;">
                        <?= e(money($averageOrderValue)) ?>
                    </div>
                </div>

                <div class="stat-icon icon-purple">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

            </div>

            <div class="stat-card">

                <div>
                    <div class="stat-label">Low-Stock Products</div>

                    <div class="stat-number">
                        <?= count($lowStockProducts) ?>
                    </div>

                    <div class="product-sub">
                        <?= $outOfStockCount ?> out of stock
                    </div>
                </div>

                <div class="stat-icon icon-orange">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

            </div>

        </section>

        <!-- ORDER STATUS SUMMARY -->
        <section class="panel">

            <div class="panel-header">
                <div>
                    <h3>Order Status Summary</h3>
                    <p>Order distribution for the selected date range.</p>
                </div>
            </div>

            <div class="panel-body">

                <div class="row g-3">

                    <div class="col-6 col-lg-2">
                        <div class="detail-card">
                            <div class="detail-label">Delivered</div>
                            <div class="stat-number"
                                 style="font-size:22px;">
                                <?= $deliveredOrders ?>
                            </div>
                            <span class="status-badge status-delivered">
                                Completed
                            </span>
                        </div>
                    </div>

                    <div class="col-6 col-lg-2">
                        <div class="detail-card">
                            <div class="detail-label">Pending</div>
                            <div class="stat-number"
                                 style="font-size:22px;">
                                <?= $pendingOrders ?>
                            </div>
                            <span class="status-badge status-pending">
                                Awaiting action
                            </span>
                        </div>
                    </div>

                    <div class="col-6 col-lg-2">
                        <div class="detail-card">
                            <div class="detail-label">Processing</div>
                            <div class="stat-number"
                                 style="font-size:22px;">
                                <?= $processingOrders ?>
                            </div>
                            <span class="status-badge status-processing">
                                In progress
                            </span>
                        </div>
                    </div>

                    <div class="col-6 col-lg-2">
                        <div class="detail-card">
                            <div class="detail-label">Shipped</div>
                            <div class="stat-number"
                                 style="font-size:22px;">
                                <?= $shippedOrders ?>
                            </div>
                            <span class="status-badge status-shipped">
                                In transit
                            </span>
                        </div>
                    </div>

                    <div class="col-6 col-lg-2">
                        <div class="detail-card">
                            <div class="detail-label">Cancelled</div>
                            <div class="stat-number"
                                 style="font-size:22px;">
                                <?= $cancelledOrders ?>
                            </div>
                            <span class="status-badge status-cancelled">
                                Cancelled
                            </span>
                        </div>
                    </div>

                    <div class="col-6 col-lg-2">
                        <div class="detail-card">
                            <div class="detail-label">Completion Rate</div>
                            <div class="stat-number"
                                 style="font-size:22px;">
                                <?= number_format($completionRate, 1) ?>%
                            </div>
                            <div class="product-sub">
                                Delivered / all orders
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section>

        <!-- SALES TREND -->
        <section class="panel">

            <div class="panel-header">

                <div>
                    <h3>
                        <?= ucfirst($period) ?> Sales Report
                    </h3>

                    <p>
                        Delivered sales grouped by the selected interval.
                    </p>
                </div>

                <div class="no-print">
                    <span class="status-badge status-shipped">
                        <?= ucfirst($period) ?> view
                    </span>
                </div>

            </div>

            <div class="panel-body">

                <?php if (count($groupedReports) > 0): ?>

                    <div class="mb-4">

                        <?php foreach ($groupedReports as $group): ?>

                            <?php
                            $width = $maxRevenue > 0
                                ? ($group['revenue'] / $maxRevenue) * 100
                                : 0;
                            ?>

                            <div class="chart-row">

                                <div class="chart-label">
                                    <?= e($group['label']) ?>
                                </div>

                                <div class="chart-track"
                                     title="<?= e(money($group['revenue'])) ?>">

                                    <div class="chart-fill"
                                         style="width:<?= e(number_format($width, 2, '.', '')) ?>%;"></div>

                                </div>

                                <div class="chart-value">
                                    <?= e(money($group['revenue'])) ?>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty-state">
                        <i class="bi bi-bar-chart"></i>
                        No sales data exists for the selected date range.
                    </div>

                <?php endif; ?>

                <div class="table-responsive">

                    <table class="report-table">

                        <thead>
                        <tr>
                            <th>Period</th>
                            <th>Total Orders</th>
                            <th>Delivered Orders</th>
                            <th>Pending / Processing</th>
                            <th>Delivered Revenue</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php if (count($groupedReports) > 0): ?>

                            <?php foreach ($groupedReports as $group): ?>

                                <tr>
                                    <td>
                                        <strong><?= e($group['label']) ?></strong>
                                    </td>

                                    <td><?= (int)$group['orders'] ?></td>

                                    <td>
                                        <span class="status-badge status-delivered">
                                            <?= (int)$group['delivered'] ?>
                                        </span>
                                    </td>

                                    <td><?= (int)$group['pending'] ?></td>

                                    <td>
                                        <strong>
                                            <?= e(money($group['revenue'])) ?>
                                        </strong>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    No records found for this period.
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

        <!-- BEST-SELLING PRODUCTS -->
        <section class="panel">

            <div class="panel-header">

                <div>
                    <h3>Best-Selling Products</h3>
                    <p>
                        Ranked by quantities sold in delivered orders.
                    </p>
                </div>

                <i class="bi bi-trophy fs-4 text-secondary"></i>

            </div>

            <div class="panel-body">

                <div class="table-responsive">

                    <table class="report-table">

                        <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Product</th>
                            <th>Units Sold</th>
                            <th>Sales Value</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php if (count($bestSellers) > 0): ?>

                            <?php foreach ($bestSellers as $index => $product): ?>

                                <tr>

                                    <td>
                                        <span class="product-rank">
                                            <?= $index + 1 ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="product-name">
                                            <?= e($product['name']) ?>
                                        </div>

                                        <div class="product-sub">
                                            Best-selling product report
                                        </div>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= number_format($product['quantity']) ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= e(money($product['revenue'])) ?>
                                        </strong>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="4">

                                    <div class="empty-state">
                                        <i class="bi bi-box-seam"></i>
                                        No delivered product sales were found.
                                    </div>

                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

        <!-- LOW-STOCK REPORT -->
        <section class="panel">

            <div class="panel-header">

                <div>
                    <h3>Low-Stock Product Report</h3>
                    <p>
                        Products at or below their minimum reorder level.
                    </p>
                </div>

                <span class="status-badge status-cancelled">
                    <?= count($lowStockProducts) ?> alerts
                </span>

            </div>

            <div class="panel-body">

                <?php if (count($lowStockProducts) > 0): ?>

                    <div class="table-responsive">

                        <table class="report-table">

                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Reorder Level</th>
                                <th>Stock Status</th>
                            </tr>
                            </thead>

                            <tbody>

                            <?php foreach ($lowStockProducts as $product): ?>

                                <?php
                                $stock = (int)$product['stock'];
                                $reorderLevel = (int)$product['reorder_level'];

                                if ($stock <= 0) {
                                    $stockLabel = 'Out of Stock';
                                    $stockClass = 'status-cancelled';
                                    $numberClass = 'stock-zero';
                                } else {
                                    $stockLabel = 'Low Stock';
                                    $stockClass = 'status-processing';
                                    $numberClass = 'stock-low';
                                }
                                ?>

                                <tr>

                                    <td>
                                        <div class="product-name">
                                            <?= e($product['name']) ?>
                                        </div>
                                    </td>

                                    <td><?= e($product['sku']) ?></td>

                                    <td><?= e($product['category']) ?></td>

                                    <td>
                                        <span class="stock-count <?= e($numberClass) ?>">
                                            <?= $stock ?>
                                        </span>
                                        <span class="product-sub">units</span>
                                    </td>

                                    <td><?= $reorderLevel ?> units</td>

                                    <td>
                                        <span class="status-badge <?= e($stockClass) ?>">
                                            <?= e($stockLabel) ?>
                                        </span>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php else: ?>

                    <div class="empty-state">
                        <i class="bi bi-check-circle"></i>
                        All products are above their reorder levels.
                    </div>

                <?php endif; ?>

                <div class="info-note mt-3 mb-0">
                    <i class="bi bi-info-circle-fill"></i>

                    <div>
                        <strong>Inventory recommendation</strong><br>
                        Review low-stock products and arrange replenishment
                        before available stock reaches zero.
                    </div>
                </div>

            </div>

        </section>

        <!-- ORDER DETAIL REPORT -->
        <section class="panel">

            <div class="panel-header">

                <div>
                    <h3>Order Details</h3>
                    <p>
                        Orders included in the selected reporting period.
                    </p>
                </div>

                <span class="order-count">
                    <?= count($filteredOrders) ?> records
                </span>

            </div>

            <div class="panel-body">

                <div class="table-responsive">

                    <table class="report-table">

                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Order Value</th>
                            <th>Status</th>
                            <th>Revenue Included?</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php if (count($filteredOrders) > 0): ?>

                            <?php foreach ($filteredOrders as $order): ?>

                                <tr>

                                    <td>
                                        <strong>#<?= (int)$order['id'] ?></strong>
                                    </td>

                                    <td>
                                        <?= e(date(
                                            'd M Y',
                                            strtotime($order['date'])
                                        )) ?>
                                    </td>

                                    <td><?= e($order['customer']) ?></td>

                                    <td><?= e(money($order['total'])) ?></td>

                                    <td>
                                        <span class="status-badge <?= e(orderStatusClass($order['status'])) ?>">
                                            <?= e($order['status']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($order['status'] === 'Delivered'): ?>

                                            <span class="status-badge status-delivered">
                                                <i class="bi bi-check-circle"></i>
                                                Included
                                            </span>

                                        <?php else: ?>

                                            <span class="status-badge status-pending">
                                                Excluded
                                            </span>

                                        <?php endif; ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6">

                                    <div class="empty-state">
                                        <i class="bi bi-receipt"></i>
                                        No orders were found in the selected
                                        date range.
                                    </div>

                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

        <!-- REPORT NOTES -->
        <div class="info-note">
            <i class="bi bi-info-circle-fill"></i>

            <div>
                <strong>How to interpret this report</strong><br>

                Revenue and best-selling product totals are calculated from
                delivered orders only. Pending, processing, and shipped orders
                appear in order summaries but are not counted as completed
                sales. Cancelled orders are excluded from revenue and
                best-selling product calculations. The inventory report uses
                the current sample stock levels.
            </div>
        </div>

        <div class="footer no-print">
            © <?= date('Y') ?> NovaMart Admin Panel.
            Demonstration interface for Codvanta portfolio.
        </div>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    /*
    |--------------------------------------------------------------------------
    | Quick date-range selection
    |--------------------------------------------------------------------------
    */

    function localDateString(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return year + '-' + month + '-' + day;
    }

    function setRange(range) {
        const today = new Date();
        const start = new Date(today);

        if (range === 'today') {
            // Keep start as today.
        } else if (range === '7days') {
            start.setDate(start.getDate() - 6);
        } else if (range === '30days') {
            start.setDate(start.getDate() - 29);
        } else if (range === 'month') {
            start.setDate(1);
        }

        document.getElementById('start_date').value =
            localDateString(start);

        document.getElementById('end_date').value =
            localDateString(today);

        document.querySelector(
            'form[action="reports.php"]'
        ).requestSubmit();
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent an invalid date range before submitting
    |--------------------------------------------------------------------------
    */

    document.querySelector('form[action="reports.php"]')
        .addEventListener('submit', function (event) {

            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;

            if (start && end && start > end) {
                event.preventDefault();

                alert(
                    'The start date cannot be later than the end date.'
                );
            }
        });

    /*
    |--------------------------------------------------------------------------
    | Print report
    |--------------------------------------------------------------------------
    */

    window.addEventListener('beforeprint', function () {
        document.title = 'NovaMart-Sales-Inventory-Report';
    });
</script>

</body>
</html>