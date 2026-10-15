
<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart - Customer Management
|--------------------------------------------------------------------------
| Demonstration features:
| - View registered customers
| - Search by customer name or email
| - View customer contact details
| - View order history
| - Block or reactivate customer accounts
|
| Data is stored in PHP sessions. This is a portfolio demonstration,
| not a production authentication or customer-management system.
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
| Initialize sample customers
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['novamart_admin_customers'])) {

    $_SESSION['novamart_admin_customers'] = [
        [
            'id' => 1,
            'name' => 'Amelia Perera',
            'email' => 'amelia.perera@example.com',
            'phone' => '077 123 4567',
            'address' => 'Colombo 05, Sri Lanka',
            'joined' => '2026-01-12',
            'status' => 'Active'
        ],
        [
            'id' => 2,
            'name' => 'Kasun Fernando',
            'email' => 'kasun.fernando@example.com',
            'phone' => '071 234 5678',
            'address' => 'Negombo, Sri Lanka',
            'joined' => '2026-02-03',
            'status' => 'Active'
        ],
        [
            'id' => 3,
            'name' => 'Nethmi Silva',
            'email' => 'nethmi.silva@example.com',
            'phone' => '076 345 6789',
            'address' => 'Kandy, Sri Lanka',
            'joined' => '2026-02-18',
            'status' => 'Active'
        ],
        [
            'id' => 4,
            'name' => 'Tharindu Jayawardena',
            'email' => 'tharindu.j@example.com',
            'phone' => '075 456 7890',
            'address' => 'Galle, Sri Lanka',
            'joined' => '2026-03-06',
            'status' => 'Blocked'
        ],
        [
            'id' => 5,
            'name' => 'Dinushi Wickramasinghe',
            'email' => 'dinushi.w@example.com',
            'phone' => '078 567 8901',
            'address' => 'Kurunegala, Sri Lanka',
            'joined' => '2026-03-21',
            'status' => 'Active'
        ],
        [
            'id' => 6,
            'name' => 'Ravindu Peris',
            'email' => 'ravindu.peris@example.com',
            'phone' => '072 678 9012',
            'address' => 'Matara, Sri Lanka',
            'joined' => '2026-04-02',
            'status' => 'Active'
        ],
        [
            'id' => 7,
            'name' => 'Sachini Gunawardena',
            'email' => 'sachini.g@example.com',
            'phone' => '074 789 0123',
            'address' => 'Nugegoda, Sri Lanka',
            'joined' => '2026-04-15',
            'status' => 'Active'
        ],
        [
            'id' => 8,
            'name' => 'Dilan Madushanka',
            'email' => 'dilan.m@example.com',
            'phone' => '070 890 1234',
            'address' => 'Panadura, Sri Lanka',
            'joined' => '2026-05-01',
            'status' => 'Blocked'
        ],
        [
            'id' => 9,
            'name' => 'Ishara Senanayake',
            'email' => 'ishara.s@example.com',
            'phone' => '077 901 2345',
            'address' => 'Ja-Ela, Sri Lanka',
            'joined' => '2026-05-16',
            'status' => 'Active'
        ],
        [
            'id' => 10,
            'name' => 'Malith Abeysekara',
            'email' => 'malith.a@example.com',
            'phone' => '071 012 3456',
            'address' => 'Gampaha, Sri Lanka',
            'joined' => '2026-06-08',
            'status' => 'Active'
        ]
    ];
}

/*
|--------------------------------------------------------------------------
| Initialize sample order history
|--------------------------------------------------------------------------
| Customer IDs link orders to the customer records above.
*/

if (!isset($_SESSION['novamart_customer_order_history'])) {

    $_SESSION['novamart_customer_order_history'] = [
        [
            'id' => 1001,
            'customer_id' => 1,
            'date' => '2026-09-20',
            'items' => [
                ['name' => 'Wireless Bluetooth Earbuds', 'quantity' => 1, 'price' => 4500],
                ['name' => 'Laptop Backpack', 'quantity' => 1, 'price' => 4200]
            ],
            'subtotal' => 8700,
            'delivery' => 350,
            'total' => 9050,
            'status' => 'Delivered',
            'payment' => 'Card'
        ],
        [
            'id' => 1002,
            'customer_id' => 2,
            'date' => '2026-09-22',
            'items' => [
                ['name' => 'Smart Watch Pro', 'quantity' => 1, 'price' => 8500]
            ],
            'subtotal' => 8500,
            'delivery' => 350,
            'total' => 8850,
            'status' => 'Processing',
            'payment' => 'Cash on Delivery'
        ],
        [
            'id' => 1003,
            'customer_id' => 3,
            'date' => '2026-09-25',
            'items' => [
                ['name' => 'Gaming Mouse', 'quantity' => 1, 'price' => 3500],
                ['name' => 'Gaming Headset', 'quantity' => 1, 'price' => 7200]
            ],
            'subtotal' => 10700,
            'delivery' => 0,
            'total' => 10700,
            'status' => 'Delivered',
            'payment' => 'Card'
        ],
        [
            'id' => 1004,
            'customer_id' => 4,
            'date' => '2026-09-27',
            'items' => [
                ['name' => 'Wireless Keyboard', 'quantity' => 1, 'price' => 5500]
            ],
            'subtotal' => 5500,
            'delivery' => 350,
            'total' => 5850,
            'status' => 'Cancelled',
            'payment' => 'Cash on Delivery'
        ],
        [
            'id' => 1005,
            'customer_id' => 5,
            'date' => '2026-09-29',
            'items' => [
                ['name' => 'Portable Blender', 'quantity' => 1, 'price' => 6800],
                ['name' => 'Face Care Set', 'quantity' => 1, 'price' => 3200]
            ],
            'subtotal' => 10000,
            'delivery' => 0,
            'total' => 10000,
            'status' => 'Shipped',
            'payment' => 'Card'
        ],
        [
            'id' => 1006,
            'customer_id' => 6,
            'date' => '2026-10-01',
            'items' => [
                ['name' => 'Laptop Stand', 'quantity' => 1, 'price' => 4000]
            ],
            'subtotal' => 4000,
            'delivery' => 350,
            'total' => 4350,
            'status' => 'Pending',
            'payment' => 'Cash on Delivery'
        ],
        [
            'id' => 1007,
            'customer_id' => 1,
            'date' => '2026-10-04',
            'items' => [
                ['name' => 'USB-C Hub', 'quantity' => 1, 'price' => 3000]
            ],
            'subtotal' => 3000,
            'delivery' => 350,
            'total' => 3350,
            'status' => 'Delivered',
            'payment' => 'Card'
        ],
        [
            'id' => 1008,
            'customer_id' => 7,
            'date' => '2026-10-06',
            'items' => [
                ['name' => 'Casual T-Shirt', 'quantity' => 2, 'price' => 2500]
            ],
            'subtotal' => 5000,
            'delivery' => 350,
            'total' => 5350,
            'status' => 'Processing',
            'payment' => 'Cash on Delivery'
        ],
        [
            'id' => 1009,
            'customer_id' => 8,
            'date' => '2026-10-08',
            'items' => [
                ['name' => 'Building Blocks Set', 'quantity' => 1, 'price' => 2800]
            ],
            'subtotal' => 2800,
            'delivery' => 350,
            'total' => 3150,
            'status' => 'Cancelled',
            'payment' => 'Card'
        ],
        [
            'id' => 1010,
            'customer_id' => 9,
            'date' => '2026-10-10',
            'items' => [
                ['name' => 'Wireless Bluetooth Earbuds', 'quantity' => 1, 'price' => 4500],
                ['name' => 'Laptop Stand', 'quantity' => 1, 'price' => 4000]
            ],
            'subtotal' => 8500,
            'delivery' => 350,
            'total' => 8850,
            'status' => 'Shipped',
            'payment' => 'Card'
        ],
        [
            'id' => 1011,
            'customer_id' => 10,
            'date' => '2026-10-12',
            'items' => [
                ['name' => 'Smart Watch Pro', 'quantity' => 1, 'price' => 8500]
            ],
            'subtotal' => 8500,
            'delivery' => 350,
            'total' => 8850,
            'status' => 'Pending',
            'payment' => 'Cash on Delivery'
        ]
    ];
}

$customers = &$_SESSION['novamart_admin_customers'];
$orders = &$_SESSION['novamart_customer_order_history'];

$message = '';
$messageType = 'success';

/*
|--------------------------------------------------------------------------
| Block / reactivate customer account
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    if ($action === 'toggle_status') {

        $customerId = (int)($_POST['customer_id'] ?? 0);
        $found = false;

        foreach ($customers as &$customer) {

            if ((int)$customer['id'] === $customerId) {

                $found = true;

                if ($customer['status'] === 'Active') {
                    $customer['status'] = 'Blocked';
                    $message = $customer['name'] .
                        ' has been blocked successfully.';
                } else {
                    $customer['status'] = 'Active';
                    $message = $customer['name'] .
                        ' has been reactivated successfully.';
                }

                break;
            }
        }

        unset($customer);

        if (!$found) {
            $message = 'The selected customer could not be found.';
            $messageType = 'danger';
        }
    }
}

/*
|--------------------------------------------------------------------------
| Search and filter customers
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? 'All';
$sort = $_GET['sort'] ?? 'newest';

$validStatuses = ['All', 'Active', 'Blocked'];

if (!in_array($statusFilter, $validStatuses, true)) {
    $statusFilter = 'All';
}

$validSorts = ['newest', 'oldest', 'name_asc', 'name_desc'];

if (!in_array($sort, $validSorts, true)) {
    $sort = 'newest';
}

$filteredCustomers = [];

foreach ($customers as $customer) {

    $matchesSearch =
        $search === '' ||
        stripos($customer['name'], $search) !== false ||
        stripos($customer['email'], $search) !== false;

    $matchesStatus =
        $statusFilter === 'All' ||
        $customer['status'] === $statusFilter;

    if ($matchesSearch && $matchesStatus) {
        $filteredCustomers[] = $customer;
    }
}

usort($filteredCustomers, function ($a, $b) use ($sort) {

    if ($sort === 'oldest') {
        return strcmp($a['joined'], $b['joined']);
    }

    if ($sort === 'name_asc') {
        return strcasecmp($a['name'], $b['name']);
    }

    if ($sort === 'name_desc') {
        return strcasecmp($b['name'], $a['name']);
    }

    return strcmp($b['joined'], $a['joined']);
});

/*
|--------------------------------------------------------------------------
| Customer order statistics
|--------------------------------------------------------------------------
*/

function getCustomerOrders($customerId, $orders)
{
    $customerOrders = [];

    foreach ($orders as $order) {
        if ((int)$order['customer_id'] === (int)$customerId) {
            $customerOrders[] = $order;
        }
    }

    usort($customerOrders, function ($a, $b) {
        return strcmp($b['date'], $a['date']);
    });

    return $customerOrders;
}

function getCustomerStats($customerId, $orders)
{
    $customerOrders = getCustomerOrders($customerId, $orders);

    $completedOrders = 0;
    $totalSpent = 0;

    foreach ($customerOrders as $order) {

        if ($order['status'] === 'Delivered') {
            $completedOrders++;
            $totalSpent += (float)$order['total'];
        }
    }

    return [
        'orders' => count($customerOrders),
        'completed' => $completedOrders,
        'spent' => $totalSpent
    ];
}

$totalCustomers = count($customers);
$activeCustomers = 0;
$blockedCustomers = 0;
$customerLifetimeSales = 0;
$totalCustomerOrders = count($orders);

foreach ($customers as $customer) {

    if ($customer['status'] === 'Active') {
        $activeCustomers++;
    } else {
        $blockedCustomers++;
    }

    $stats = getCustomerStats($customer['id'], $orders);
    $customerLifetimeSales += $stats['spent'];
}

$avatarColors = [
    ['background' => '#eaf0ff', 'color' => '#315efb'],
    ['background' => '#e5f8f0', 'color' => '#129568'],
    ['background' => '#fff2df', 'color' => '#d97706'],
    ['background' => '#f1eaff', 'color' => '#7c3aed'],
    ['background' => '#ffe9f1', 'color' => '#d63384']
];

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

    <title>Customer Management | NovaMart Admin</title>

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
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -.8px;
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

        .filter-row {
            display: grid;
            grid-template-columns: minmax(200px, 1.5fr) minmax(140px, .8fr) minmax(150px, .9fr) auto;
            gap: 12px;
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

        .btn-filter {
            background: var(--navy);
            color: #fff;
            border: 0;
            border-radius: 9px;
            min-height: 43px;
            padding: 0 19px;
            font-weight: 700;
        }

        .btn-filter:hover {
            background: #263f67;
            color: #fff;
        }

        .customer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .customer-table th {
            padding: 14px 17px;
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

        .customer-table td {
            padding: 17px;
            border-bottom: 1px solid #eef1f6;
            vertical-align: middle;
            font-size: 12px;
        }

        .customer-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .customer-table tbody tr:hover {
            background: #fbfcff;
        }

        .customer-profile {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 190px;
        }

        .customer-avatar {
            width: 43px;
            height: 43px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .customer-name {
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 5px;
            color: var(--text-main);
        }

        .customer-id {
            font-size: 10px;
            color: var(--text-muted);
        }

        .contact-line {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 5px;
            white-space: nowrap;
        }

        .contact-line:last-child {
            margin-bottom: 0;
        }

        .contact-line i {
            color: #8a98ae;
            font-size: 12px;
        }

        .contact-line a {
            color: #4d5c74;
        }

        .contact-line a:hover {
            color: var(--blue);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-active {
            background: #e5f8f0;
            color: #13865e;
        }

        .status-blocked {
            background: #fff0f0;
            color: #c62828;
        }

        .order-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 31px;
            height: 28px;
            border-radius: 8px;
            background: #edf2ff;
            color: #315efb;
            font-weight: 800;
            padding: 0 8px;
        }

        .spent-amount {
            font-weight: 800;
            white-space: nowrap;
        }

        .muted-text {
            color: var(--text-muted);
            font-size: 10px;
        }

        .action-group {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            border: 1px solid #e2e8f1;
            border-radius: 8px;
            display: inline-grid;
            place-items: center;
            background: #fff;
            color: #596780;
            transition: .2s;
        }

        .btn-action:hover {
            background: #edf2ff;
            color: var(--blue);
            border-color: #cdd8ff;
        }

        .btn-action.block:hover {
            background: #fff0f0;
            color: #d92d20;
            border-color: #ffd0d0;
        }

        .btn-action.unblock:hover {
            background: #e5f8f0;
            color: #13865e;
            border-color: #b6ead3;
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px;
        }

        .empty-state i {
            font-size: 38px;
            color: #9aa8bf;
        }

        .empty-state h4 {
            font-size: 15px;
            font-weight: 800;
            margin: 14px 0 7px;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 12px;
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

        .modal-content {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-size: 17px;
            font-weight: 800;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 17px 24px;
            border-top: 1px solid var(--border);
        }

        .detail-label {
            color: var(--text-muted);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 6px;
        }

        .detail-value {
            font-size: 12px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .detail-card {
            border: 1px solid var(--border);
            border-radius: 11px;
            padding: 14px;
            height: 100%;
        }

        .order-history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-history-table th {
            background: #f8faff;
            color: #7b879b;
            padding: 11px 10px;
            text-transform: uppercase;
            letter-spacing: .5px;
            font-size: 9px;
            white-space: nowrap;
        }

        .order-history-table td {
            border-top: 1px solid var(--border);
            padding: 13px 10px;
            font-size: 11px;
            vertical-align: middle;
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

        .btn-light-custom {
            background: #f1f4f9;
            color: #475467;
            border: 0;
            border-radius: 9px;
            padding: 10px 17px;
            font-weight: 700;
        }

        .alert {
            border-radius: 10px;
            font-size: 13px;
        }

        @media (max-width: 1300px) {
            .stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-row {
                grid-template-columns: 1fr 1fr;
            }

            .customer-table th,
            .customer-table td {
                padding: 13px;
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
                font-size: 23px;
            }

            .stat-icon {
                width: 39px;
                height: 39px;
                font-size: 18px;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .panel-header,
            .panel-body {
                padding: 17px;
            }

            .customer-table {
                min-width: 1050px;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">

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

    <a href="customers.php" class="sidebar-link active">
        <i class="bi bi-people"></i>
        <span>Customers</span>
    </a>

    <a href="reports.php" class="sidebar-link">
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

    <!-- TOPBAR -->
    <header class="topbar">

        <div>
            <div class="breadcrumb-text">
                Admin Panel / <span>Customer Management</span>
            </div>
            <h1 class="page-title">Customer Management</h1>
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

        <!-- INTRO -->
        <section class="page-intro">

            <div>
                <h2>Manage your customers</h2>
                <p>
                    View customer profiles, check contact information,
                    review purchase history, and manage account status.
                </p>
            </div>

        </section>

        <!-- FEEDBACK -->
        <?php if ($message !== ''): ?>

            <div class="alert alert-<?= e($messageType) ?> alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-info-circle-fill me-2"></i>
                <?= e($message) ?>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>

        <?php endif; ?>

        <!-- SUMMARY -->
        <section class="stat-grid">

            <div class="stat-card">
                <div>
                    <div class="stat-label">Registered Customers</div>
                    <div class="stat-number">
                        <?= number_format($totalCustomers) ?>
                    </div>
                </div>

                <div class="stat-icon icon-blue">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Active Accounts</div>
                    <div class="stat-number">
                        <?= number_format($activeCustomers) ?>
                    </div>
                </div>

                <div class="stat-icon icon-green">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Blocked Accounts</div>
                    <div class="stat-number">
                        <?= number_format($blockedCustomers) ?>
                    </div>
                </div>

                <div class="stat-icon icon-orange">
                    <i class="bi bi-person-lock"></i>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Delivered Sales</div>
                    <div class="stat-number" style="font-size:22px;">
                        <?= e(money($customerLifetimeSales)) ?>
                    </div>
                </div>

                <div class="stat-icon icon-purple">
                    <i class="bi bi-currency-exchange"></i>
                </div>
            </div>

        </section>

        <!-- INFORMATION NOTE -->
        <div class="info-note">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                <strong>Customer account management</strong><br>
                Use the customer details button to inspect contact information
                and previous orders. Blocking an account changes its
                demonstration status only; a real system must also enforce
                that status in its login and authorization logic.
            </div>
        </div>

        <!-- CUSTOMER LIST -->
        <section class="panel">

            <div class="panel-header">

                <div>
                    <h3>Registered Customers</h3>
                    <p>Search, review, and manage customer accounts.</p>
                </div>

                <span class="order-count">
                    <?= count($filteredCustomers) ?>
                </span>

            </div>

            <div class="panel-body">

                <!-- SEARCH / FILTER -->
                <form method="GET"
                      action="customers.php"
                      class="filter-row mb-4">

                    <div>
                        <label class="form-label">Search Customers</label>

                        <div class="input-group">

                            <span class="input-group-text bg-white"
                                  style="border-color:#dfe6f0;border-radius:9px 0 0 9px;">
                                <i class="bi bi-search text-secondary"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   style="border-radius:0 9px 9px 0;"
                                   placeholder="Enter customer name or email..."
                                   value="<?= e($search) ?>">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Account Status</label>

                        <select name="status" class="form-select">

                            <option value="All"
                                <?= $statusFilter === 'All' ? 'selected' : '' ?>>
                                All Accounts
                            </option>

                            <option value="Active"
                                <?= $statusFilter === 'Active' ? 'selected' : '' ?>>
                                Active
                            </option>

                            <option value="Blocked"
                                <?= $statusFilter === 'Blocked' ? 'selected' : '' ?>>
                                Blocked
                            </option>

                        </select>
                    </div>

                    <div>
                        <label class="form-label">Sort Customers</label>

                        <select name="sort" class="form-select">

                            <option value="newest"
                                <?= $sort === 'newest' ? 'selected' : '' ?>>
                                Recently Registered
                            </option>

                            <option value="oldest"
                                <?= $sort === 'oldest' ? 'selected' : '' ?>>
                                Oldest Registration
                            </option>

                            <option value="name_asc"
                                <?= $sort === 'name_asc' ? 'selected' : '' ?>>
                                Name: A to Z
                            </option>

                            <option value="name_desc"
                                <?= $sort === 'name_desc' ? 'selected' : '' ?>>
                                Name: Z to A
                            </option>

                        </select>
                    </div>

                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-filter">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>

                        <a href="customers.php"
                           class="btn btn-light-custom d-flex align-items-center">
                            Reset
                        </a>

                    </div>

                </form>

                <!-- TABLE -->
                <div class="table-responsive">

                    <table class="customer-table">

                        <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact Details</th>
                            <th>Registered</th>
                            <th>Orders</th>
                            <th>Delivered Spend</th>
                            <th>Account Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php if (count($filteredCustomers) > 0): ?>

                            <?php foreach ($filteredCustomers as $index => $customer): ?>

                                <?php
                                $stats = getCustomerStats(
                                    $customer['id'],
                                    $orders
                                );

                                $initials = '';

                                $nameParts = preg_split(
                                    '/\s+/',
                                    trim($customer['name'])
                                );

                                foreach (array_slice($nameParts, 0, 2) as $part) {
                                    $initials .= strtoupper(substr($part, 0, 1));
                                }

                                $avatar = $avatarColors[
                                    $index % count($avatarColors)
                                ];
                                ?>

                                <tr>

                                    <!-- CUSTOMER -->
                                    <td>
                                        <div class="customer-profile">

                                            <div class="customer-avatar"
                                                 style="background:<?= e($avatar['background']) ?>;color:<?= e($avatar['color']) ?>;">
                                                <?= e($initials) ?>
                                            </div>

                                            <div>
                                                <div class="customer-name">
                                                    <?= e($customer['name']) ?>
                                                </div>

                                                <div class="customer-id">
                                                    Customer ID:
                                                    #<?= (int)$customer['id'] ?>
                                                </div>
                                            </div>

                                        </div>
                                    </td>

                                    <!-- CONTACT -->
                                    <td>

                                        <div class="contact-line">
                                            <i class="bi bi-envelope"></i>
                                            <a href="mailto:<?= e($customer['email']) ?>">
                                                <?= e($customer['email']) ?>
                                            </a>
                                        </div>

                                        <div class="contact-line">
                                            <i class="bi bi-telephone"></i>
                                            <?= e($customer['phone']) ?>
                                        </div>

                                    </td>

                                    <!-- JOIN DATE -->
                                    <td>
                                        <div style="font-weight:700;">
                                            <?= e(date(
                                                'd M Y',
                                                strtotime($customer['joined'])
                                            )) ?>
                                        </div>
                                        <div class="muted-text">
                                            Registration date
                                        </div>
                                    </td>

                                    <!-- ORDERS -->
                                    <td>
                                        <span class="order-count">
                                            <?= (int)$stats['orders'] ?>
                                        </span>
                                    </td>

                                    <!-- DELIVERED SPEND -->
                                    <td>
                                        <div class="spent-amount">
                                            <?= e(money($stats['spent'])) ?>
                                        </div>
                                        <div class="muted-text">
                                            <?= (int)$stats['completed'] ?>
                                            delivered order(s)
                                        </div>
                                    </td>

                                    <!-- STATUS -->
                                    <td>

                                        <?php if ($customer['status'] === 'Active'): ?>

                                            <span class="status-badge status-active">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Active
                                            </span>

                                        <?php else: ?>

                                            <span class="status-badge status-blocked">
                                                <i class="bi bi-lock-fill"></i>
                                                Blocked
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <!-- ACTIONS -->
                                    <td>

                                        <div class="action-group">

                                            <!-- VIEW DETAILS -->
                                            <button type="button"
                                                    class="btn-action"
                                                    title="View customer and order history"
                                                    aria-label="View customer details"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#customerModal"
                                                    data-customer="<?= e(json_encode(
                                                        $customer,
                                                        JSON_HEX_APOS |
                                                        JSON_HEX_QUOT |
                                                        JSON_HEX_TAG |
                                                        JSON_HEX_AMP
                                                    )) ?>"
                                                    data-orders="<?= e(json_encode(
                                                        getCustomerOrders(
                                                            $customer['id'],
                                                            $orders
                                                        ),
                                                        JSON_HEX_APOS |
                                                        JSON_HEX_QUOT |
                                                        JSON_HEX_TAG |
                                                        JSON_HEX_AMP
                                                    )) ?>"
                                                    onclick="openCustomerModal(this)">

                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <!-- BLOCK / REACTIVATE -->
                                            <form method="POST"
                                                  action="customers.php"
                                                  onsubmit="return confirmAccountAction(
                                                      '<?= e(addslashes($customer['name'])) ?>',
                                                      '<?= e($customer['status']) ?>'
                                                  );">

                                                <input type="hidden"
                                                       name="action"
                                                       value="toggle_status">

                                                <input type="hidden"
                                                       name="customer_id"
                                                       value="<?= (int)$customer['id'] ?>">

                                                <?php if ($customer['status'] === 'Active'): ?>

                                                    <button type="submit"
                                                            class="btn-action block"
                                                            title="Block customer"
                                                            aria-label="Block customer">

                                                        <i class="bi bi-person-lock"></i>
                                                    </button>

                                                <?php else: ?>

                                                    <button type="submit"
                                                            class="btn-action unblock"
                                                            title="Reactivate customer"
                                                            aria-label="Reactivate customer">

                                                        <i class="bi bi-person-check"></i>
                                                    </button>

                                                <?php endif; ?>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7">

                                    <div class="empty-state">

                                        <i class="bi bi-people"></i>

                                        <h4>No Customers Found</h4>

                                        <p>
                                            Try another name or email address,
                                            or adjust the account status filter.
                                        </p>

                                        <a href="customers.php"
                                           class="btn btn-light-custom">
                                            Clear Filters
                                        </a>

                                    </div>

                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

        <div class="footer">
            © <?= date('Y') ?> NovaMart Admin Panel.
            Demonstration interface for Codvanta portfolio.
        </div>

    </div>

</main>

<!-- CUSTOMER DETAILS / ORDER HISTORY MODAL -->
<div class="modal fade"
     id="customerModal"
     tabindex="-1"
     aria-labelledby="customerModalTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <div>
                    <h2 class="modal-title" id="customerModalTitle">
                        Customer Details
                    </h2>

                    <p class="text-secondary mb-0 mt-1"
                       style="font-size:12px;">
                        Contact information and order history
                    </p>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>

            </div>

            <div class="modal-body">

                <!-- PROFILE -->
                <div class="d-flex align-items-center gap-3 mb-4">

                    <div id="modalAvatar"
                         class="customer-avatar"
                         style="width:60px;height:60px;font-size:18px;background:#eaf0ff;color:#315efb;">
                        CU
                    </div>

                    <div>
                        <h3 id="modalCustomerName"
                            style="font-size:18px;font-weight:800;margin-bottom:5px;">
                            Customer Name
                        </h3>

                        <div id="modalCustomerId"
                             class="muted-text">
                            Customer ID
                        </div>

                        <div class="mt-2" id="modalCustomerStatus"></div>
                    </div>

                </div>

                <!-- CONTACT CARDS -->
                <div class="row g-3 mb-4">

                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="bi bi-envelope me-1"></i>
                                Email Address
                            </div>
                            <div class="detail-value"
                                 id="modalEmail"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="bi bi-telephone me-1"></i>
                                Phone Number
                            </div>
                            <div class="detail-value"
                                 id="modalPhone"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="bi bi-geo-alt me-1"></i>
                                Address
                            </div>
                            <div class="detail-value"
                                 id="modalAddress"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="bi bi-calendar-check me-1"></i>
                                Registration Date
                            </div>
                            <div class="detail-value"
                                 id="modalJoined"></div>
                        </div>
                    </div>

                </div>

                <!-- ORDER HISTORY -->
                <div class="d-flex justify-content-between align-items-center gap-3 mb-3">

                    <div>
                        <h3 style="font-size:15px;font-weight:800;margin-bottom:5px;">
                            Order History
                        </h3>

                        <p class="text-secondary mb-0"
                           style="font-size:11px;">
                            Previous orders associated with this customer.
                        </p>
                    </div>

                    <span class="order-count" id="modalOrderCount">0</span>

                </div>

                <div class="table-responsive border rounded-3">

                    <table class="order-history-table">

                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                        </thead>

                        <tbody id="modalOrderRows">

                            <tr>
                                <td colspan="6"
                                    class="text-center text-secondary py-4">
                                    No orders found.
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

                <div class="row g-3 mt-3">

                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                Delivered Orders
                            </div>
                            <div class="detail-value"
                                 id="modalDeliveredOrders">0</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                Total Delivered Spend
                            </div>
                            <div class="detail-value"
                                 id="modalTotalSpent">Rs. 0.00</div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-light-custom"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    function confirmAccountAction(name, status) {
        if (status === 'Active') {
            return confirm(
                'Are you sure you want to block ' +
                name +
                '\'s account?'
            );
        }

        return confirm(
            'Do you want to reactivate ' +
            name +
            '\'s account?'
        );
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            const entities = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };

            return entities[char];
        });
    }

    function formatMoney(amount) {
        return 'Rs. ' + Number(amount || 0).toLocaleString(
            'en-LK',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }

    function formatDate(dateValue) {
        if (!dateValue) {
            return '—';
        }

        const parts = String(dateValue).split('-');

        if (parts.length !== 3) {
            return dateValue;
        }

        const date = new Date(
            Number(parts[0]),
            Number(parts[1]) - 1,
            Number(parts[2])
        );

        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function getInitials(name) {
        return String(name || 'Customer')
            .trim()
            .split(/\s+/)
            .slice(0, 2)
            .map(function (part) {
                return part.charAt(0).toUpperCase();
            })
            .join('');
    }

    function getStatusClass(status) {
        const classes = {
            'Delivered': 'status-delivered',
            'Processing': 'status-processing',
            'Shipped': 'status-shipped',
            'Cancelled': 'status-cancelled',
            'Pending': 'status-pending'
        };

        return classes[status] || 'status-pending';
    }

    function openCustomerModal(button) {
        let customer = {};
        let orders = [];

        try {
            customer = JSON.parse(button.dataset.customer || '{}');
            orders = JSON.parse(button.dataset.orders || '[]');
        } catch (error) {
            console.error('Could not load customer details.', error);
            return;
        }

        document.getElementById('modalCustomerName').textContent =
            customer.name || 'Customer';

        document.getElementById('modalCustomerId').textContent =
            'Customer ID: #' + (customer.id || '');

        document.getElementById('modalAvatar').textContent =
            getInitials(customer.name);

        document.getElementById('modalEmail').textContent =
            customer.email || 'Not provided';

        document.getElementById('modalPhone').textContent =
            customer.phone || 'Not provided';

        document.getElementById('modalAddress').textContent =
            customer.address || 'Not provided';

        document.getElementById('modalJoined').textContent =
            formatDate(customer.joined);

        const statusElement =
            document.getElementById('modalCustomerStatus');

        if (customer.status === 'Active') {
            statusElement.innerHTML =
                '<span class="status-badge status-active">' +
                '<i class="bi bi-check-circle-fill"></i> Active</span>';
        } else {
            statusElement.innerHTML =
                '<span class="status-badge status-blocked">' +
                '<i class="bi bi-lock-fill"></i> Blocked</span>';
        }

        document.getElementById('modalOrderCount').textContent =
            orders.length;

        const rows = document.getElementById('modalOrderRows');

        rows.innerHTML = '';

        let deliveredCount = 0;
        let deliveredSpend = 0;

        if (orders.length === 0) {

            rows.innerHTML =
                '<tr><td colspan="6" class="text-center text-secondary py-4">' +
                'This customer has not placed any orders yet.' +
                '</td></tr>';

        } else {

            orders.forEach(function (order) {

                const itemCount = Array.isArray(order.items)
                    ? order.items.reduce(function (total, item) {
                        return total + Number(item.quantity || 0);
                    }, 0)
                    : 0;

                if (order.status === 'Delivered') {
                    deliveredCount++;
                    deliveredSpend += Number(order.total || 0);
                }

                const row = document.createElement('tr');

                row.innerHTML =
                    '<td><strong>#' +
                    escapeHtml(order.id) +
                    '</strong></td>' +

                    '<td>' +
                    escapeHtml(formatDate(order.date)) +
                    '</td>' +

                    '<td>' +
                    escapeHtml(itemCount) +
                    ' item(s)</td>' +

                    '<td>' +
                    escapeHtml(order.payment || '—') +
                    '</td>' +

                    '<td><strong>' +
                    escapeHtml(formatMoney(order.total)) +
                    '</strong></td>' +

                    '<td><span class="status-badge ' +
                    escapeHtml(getStatusClass(order.status)) +
                    '">' +
                    escapeHtml(order.status) +
                    '</span></td>';

                rows.appendChild(row);
            });
        }

        document.getElementById('modalDeliveredOrders').textContent =
            deliveredCount;

        document.getElementById('modalTotalSpent').textContent =
            formatMoney(deliveredSpend);
    }
</script>

</body>
</html>