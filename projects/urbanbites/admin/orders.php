
<?php
// UrbanBites - Orders Management Page
// Demonstration only: no database or backend required.

$orders = [
    [
        'id' => 'UB-2026-001',
        'customer' => 'Kasun Perera',
        'initials' => 'KP',
        'phone' => '077 123 4567',
        'date' => '2026-10-13',
        'time' => '09:15 AM',
        'type' => 'Delivery',
        'address' => 'Colombo 05, Sri Lanka',
        'items' => [
            ['name' => 'Classic Urban Burger', 'qty' => 2, 'price' => 1850],
            ['name' => 'Crispy French Fries', 'qty' => 1, 'price' => 750],
            ['name' => 'Chilled Cola', 'qty' => 2, 'price' => 450]
        ],
        'subtotal' => 5350,
        'delivery' => 300,
        'total' => 5650,
        'payment' => 'Cash on Delivery',
        'status' => 'Pending',
        'notes' => 'Please call when you arrive.'
    ],
    [
        'id' => 'UB-2026-002',
        'customer' => 'Nethmi Fernando',
        'initials' => 'NF',
        'phone' => '071 234 5678',
        'date' => '2026-10-13',
        'time' => '09:22 AM',
        'type' => 'Pickup',
        'address' => 'Restaurant pickup counter',
        'items' => [
            ['name' => 'Margherita Pizza', 'qty' => 1, 'price' => 1900],
            ['name' => 'Fresh Lime Mojito', 'qty' => 2, 'price' => 650]
        ],
        'subtotal' => 3200,
        'delivery' => 0,
        'total' => 3200,
        'payment' => 'Card Payment',
        'status' => 'Confirmed',
        'notes' => 'Extra lime, please.'
    ],
    [
        'id' => 'UB-2026-003',
        'customer' => 'Amal Jayawardena',
        'initials' => 'AJ',
        'phone' => '076 345 6789',
        'date' => '2026-10-13',
        'time' => '09:30 AM',
        'type' => 'Delivery',
        'address' => 'Nugegoda, Sri Lanka',
        'items' => [
            ['name' => 'Spicy Chicken Pizza', 'qty' => 1, 'price' => 2600],
            ['name' => 'Chocolate Brownie', 'qty' => 2, 'price' => 850]
        ],
        'subtotal' => 4300,
        'delivery' => 300,
        'total' => 4600,
        'payment' => 'Cash on Delivery',
        'status' => 'Preparing',
        'notes' => 'Medium spicy.'
    ],
    [
        'id' => 'UB-2026-004',
        'customer' => 'Dinushi Silva',
        'initials' => 'DS',
        'phone' => '075 456 7890',
        'date' => '2026-10-13',
        'time' => '09:35 AM',
        'type' => 'Pickup',
        'address' => 'Restaurant pickup counter',
        'items' => [
            ['name' => 'Double Cheese Burger', 'qty' => 2, 'price' => 2450],
            ['name' => 'Crispy French Fries', 'qty' => 2, 'price' => 750]
        ],
        'subtotal' => 6400,
        'delivery' => 0,
        'total' => 6400,
        'payment' => 'Card Payment',
        'status' => 'Ready',
        'notes' => 'Customer will collect shortly.'
    ],
    [
        'id' => 'UB-2026-005',
        'customer' => 'Tharindu Kumara',
        'initials' => 'TK',
        'phone' => '078 567 8901',
        'date' => '2026-10-13',
        'time' => '09:40 AM',
        'type' => 'Delivery',
        'address' => 'Rajagiriya, Sri Lanka',
        'items' => [
            ['name' => 'BBQ Chicken', 'qty' => 1, 'price' => 2750],
            ['name' => 'Fresh Garden Salad', 'qty' => 1, 'price' => 950],
            ['name' => 'Chilled Cola', 'qty' => 1, 'price' => 450]
        ],
        'subtotal' => 4150,
        'delivery' => 300,
        'total' => 4450,
        'payment' => 'Cash on Delivery',
        'status' => 'Out for Delivery',
        'notes' => 'Deliver to the front gate.'
    ],
    [
        'id' => 'UB-2026-006',
        'customer' => 'Piumi Rathnayake',
        'initials' => 'PR',
        'phone' => '070 678 9012',
        'date' => '2026-10-13',
        'time' => '09:05 AM',
        'type' => 'Delivery',
        'address' => 'Borella, Sri Lanka',
        'items' => [
            ['name' => 'Creamy Chicken Pasta', 'qty' => 2, 'price' => 2200],
            ['name' => 'Fresh Lime Mojito', 'qty' => 1, 'price' => 650]
        ],
        'subtotal' => 5050,
        'delivery' => 300,
        'total' => 5350,
        'payment' => 'Card Payment',
        'status' => 'Delivered',
        'notes' => 'Thank you!'
    ]
];

$statuses = [
    'Pending',
    'Confirmed',
    'Preparing',
    'Ready',
    'Out for Delivery',
    'Delivered'
];

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management | UrbanBites Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --ub-orange: #f47721;
            --ub-orange-light: #fff0e5;
            --ub-dark: #20232e;
            --ub-muted: #858b99;
            --ub-bg: #f6f7fb;
            --ub-border: #eceef3;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--ub-bg);
            color: #292d38;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            padding: 24px 16px;
            background: var(--ub-dark);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 12px 28px;
            color: white;
            font-size: 23px;
            font-weight: 800;
            text-decoration: none;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            background: var(--ub-orange);
            border-radius: 13px;
            font-size: 23px;
        }

        .brand small {
            display: block;
            margin-top: 2px;
            color: #aeb3c1;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 2px;
        }

        .nav-heading {
            padding: 14px 13px 8px;
            color: #858b9c;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.6px;
            text-transform: uppercase;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 3px 0;
            padding: 12px 13px;
            color: #c6cad4;
            border-radius: 10px;
            font-size: 13px;
            transition: .2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: var(--ub-orange);
        }

        .nav-icon {
            width: 21px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-bottom {
            margin-top: 25px;
            padding-top: 18px;
            border-top: 1px solid #3b3e49;
        }

        .main-content {
            min-height: 100vh;
            margin-left: 250px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 17px 30px;
            background: rgba(255,255,255,.95);
            border-bottom: 1px solid var(--ub-border);
        }

        .topbar h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
        }

        .topbar p {
            margin: 4px 0 0;
            color: var(--ub-muted);
            font-size: 12px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-button {
            position: relative;
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid var(--ub-border);
            border-radius: 12px;
            font-size: 18px;
        }

        .admin-avatar {
            display: grid;
            place-items: center;
            width: 39px;
            height: 39px;
            color: white;
            background: #282c38;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 800;
        }

        .page-content {
            padding: 28px 30px 40px;
        }

        .eyebrow {
            margin-bottom: 8px;
            color: var(--ub-orange);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .page-heading {
            margin-bottom: 7px;
            font-size: 27px;
            font-weight: 800;
        }

        .page-description {
            margin-bottom: 25px;
            color: var(--ub-muted);
            font-size: 13px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 14px;
            height: 100%;
            padding: 19px;
            background: white;
            border: 1px solid var(--ub-border);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(30,35,50,.025);
        }

        .stat-icon {
            display: grid;
            flex-shrink: 0;
            place-items: center;
            width: 47px;
            height: 47px;
            border-radius: 14px;
            font-size: 21px;
        }

        .stat-label {
            margin-bottom: 5px;
            color: var(--ub-muted);
            font-size: 12px;
        }

        .stat-number {
            font-size: 25px;
            font-weight: 800;
            line-height: 1;
        }

        .stat-note {
            margin-top: 6px;
            color: #999eaa;
            font-size: 10px;
        }

        .stat-orange { color: #e86d19; background: #fff0e4; }
        .stat-blue { color: #3979dc; background: #eaf2ff; }
        .stat-purple { color: #8154d8; background: #f0eaff; }
        .stat-green { color: #239c67; background: #e6f8ef; }

        .panel {
            margin-top: 25px;
            padding: 23px;
            background: white;
            border: 1px solid var(--ub-border);
            border-radius: 17px;
            box-shadow: 0 4px 16px rgba(30,35,50,.025);
        }

        .panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .panel-heading h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
        }

        .panel-heading p {
            margin: 5px 0 0;
            color: var(--ub-muted);
            font-size: 12px;
        }

        .demo-badge {
            padding: 7px 10px;
            color: #9b5a14;
            background: #fff5dc;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
        }

        .filters {
            display: grid;
            grid-template-columns: minmax(200px, 1fr) 210px 150px;
            gap: 12px;
            margin-bottom: 20px;
        }

        .form-control,
        .form-select {
            min-height: 43px;
            border-color: #e7e9ef;
            border-radius: 10px;
            font-size: 12px;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--ub-orange);
        }

        .search-wrap { position: relative; }

        .search-wrap span {
            position: absolute;
            top: 11px;
            left: 13px;
            color: #9298a5;
        }

        .search-wrap input { padding-left: 38px; }

        .table-responsive { overflow-x: auto; }

        .orders-table {
            min-width: 1000px;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .orders-table thead th {
            padding: 13px 12px;
            color: #858b99;
            background: #f8f9fc;
            border-bottom: 1px solid var(--ub-border);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .7px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .orders-table tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid #f0f1f5;
            font-size: 12px;
        }

        .orders-table tbody tr:last-child td { border-bottom: 0; }

        .order-id {
            color: #303442;
            font-weight: 800;
            white-space: nowrap;
        }

        .customer-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 175px;
        }

        .customer-avatar {
            display: grid;
            flex-shrink: 0;
            place-items: center;
            width: 37px;
            height: 37px;
            color: #a65a20;
            background: #fff0e4;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
        }

        .customer-name { font-weight: 700; }

        .customer-phone {
            margin-top: 4px;
            color: #9298a5;
            font-size: 10px;
        }

        .order-type {
            display: inline-block;
            padding: 6px 8px;
            color: #555e70;
            background: #f1f3f7;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 700;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 9px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-badge::before {
            width: 6px;
            height: 6px;
            background: currentColor;
            border-radius: 50%;
            content: "";
        }

        .status-pending { color: #a86a08; background: #fff3d6; }
        .status-confirmed { color: #2866bd; background: #e7f0ff; }
        .status-preparing { color: #8150c5; background: #f1e8ff; }
        .status-ready { color: #087e83; background: #dff7f5; }
        .status-out-for-delivery { color: #ca6419; background: #ffeadb; }
        .status-delivered { color: #168454; background: #def7e9; }

        .amount {
            font-weight: 800;
            white-space: nowrap;
        }

        .table-action {
            display: inline-grid;
            place-items: center;
            width: 33px;
            height: 33px;
            color: #626a7b;
            background: white;
            border: 1px solid #e9ebf0;
            border-radius: 9px;
        }

        .table-action:hover {
            color: var(--ub-orange);
            border-color: #ffd2b4;
        }

        .table-status-select {
            min-width: 145px;
            min-height: 34px;
            padding: 5px 25px 5px 8px;
            border-radius: 8px;
            font-size: 10px;
        }

        .empty-state {
            display: none;
            padding: 45px 15px;
            color: var(--ub-muted);
            text-align: center;
        }

        .empty-state .empty-icon {
            margin-bottom: 10px;
            font-size: 35px;
        }

        .workflow-panel { margin-top: 25px; }

        .workflow {
            display: grid;
            grid-template-columns: repeat(6, minmax(100px, 1fr));
            gap: 10px;
        }

        .workflow-step {
            padding: 15px 10px;
            background: #fafbfe;
            border: 1px solid var(--ub-border);
            border-radius: 12px;
            text-align: center;
        }

        .workflow-number {
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            margin: 0 auto 9px;
            color: white;
            background: var(--ub-orange);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 800;
        }

        .workflow-step strong {
            display: block;
            font-size: 11px;
        }

        .workflow-step small {
            display: block;
            margin-top: 5px;
            color: #9298a5;
            font-size: 10px;
        }

        .btn-orange {
            padding: 10px 15px;
            color: white;
            background: var(--ub-orange);
            border: 1px solid var(--ub-orange);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-orange:hover {
            color: white;
            background: #dd6415;
        }

        .btn-light-custom {
            padding: 10px 14px;
            background: white;
            border: 1px solid #e7e9ef;
            border-radius: 10px;
            font-size: 12px;
        }

        .modal-content {
            border: 0;
            border-radius: 18px;
        }

        .modal-header {
            padding: 22px;
            border-bottom-color: var(--ub-border);
        }

        .modal-body { padding: 22px; }

        .detail-label {
            margin-bottom: 5px;
            color: #8a90a0;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .detail-value {
            margin-bottom: 17px;
            font-size: 13px;
            font-weight: 700;
        }

        .items-list {
            margin-bottom: 18px;
            padding: 14px;
            background: #f8f9fc;
            border-radius: 12px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            font-size: 12px;
        }

        .item-row + .item-row { border-top: 1px solid #e9ebf0; }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid #e2e5eb;
            font-size: 15px;
            font-weight: 800;
        }

        .sidebar-toggle { display: none; }

        @media (max-width: 1200px) {
            .sidebar { width: 225px; }
            .main-content { margin-left: 225px; }
            .page-content { padding: 24px 20px; }
            .topbar { padding: 16px 20px; }
            .workflow { grid-template-columns: repeat(3, minmax(100px, 1fr)); }
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: inline-grid; }
            .filters { grid-template-columns: 1fr 1fr; }
            .search-wrap { grid-column: 1 / -1; }
        }

        @media (max-width: 560px) {
            .topbar { padding: 13px 15px; }
            .topbar h1 { font-size: 18px; }
            .topbar p { font-size: 10px; }
            .page-content { padding: 20px 13px 30px; }
            .page-heading { font-size: 23px; }
            .panel { padding: 15px; }
            .filters { grid-template-columns: 1fr; }
            .search-wrap { grid-column: auto; }
            .workflow { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .panel-heading { align-items: flex-start; }
            .demo-badge { white-space: nowrap; }
        }
    </style>
</head>

<body>

<aside class="sidebar" id="sidebar">
    <a href="index.php" class="brand">
        <img src="../../../assets/img/main_logo.png" width="55px" height="45px" >
        <span>UrbanBites<small>ADMIN PANEL</small></span>
    </a>

    <div class="nav-heading">Main Menu</div>

    <nav class="nav flex-column">
        <a class="nav-link" href="index.php">
            <span class="nav-icon">▦</span> Dashboard
        </a>
        <a class="nav-link active" href="orders.php">
            <span class="nav-icon">🛍</span> Orders Management
        </a>
        <a class="nav-link" href="./menu_management.php">
            <span class="nav-icon">🍕</span> Menu Management
        </a>
        <a class="nav-link" href="customers.php">
            <span class="nav-icon">♙</span> Customers
        </a>
        <a class="nav-link" href="inventory.php">
            <span class="nav-icon">📦</span> Inventory
        </a>
        <a class="nav-link" href="#">
            <span class="nav-icon">🛵</span> Delivery Management
        </a>
        <a class="nav-link" href="#">
            <span class="nav-icon">☆</span> Reviews
        </a>
    </nav>

    <div class="nav-heading mt-3">Business</div>

    <nav class="nav flex-column">
        <a class="nav-link" href="#">
            <span class="nav-icon">🏷</span> Promotions
        </a>
        <a class="nav-link" href="#">
            <span class="nav-icon">▤</span> Sales Reports
        </a>
        <a class="nav-link" href="#">
            <span class="nav-icon">💰</span> Revenue
        </a>
        <a class="nav-link" href="#">
            <span class="nav-icon">⚙</span> Settings
        </a>
    </nav>

    <div class="sidebar-bottom">
        <a class="nav-link" href="#">
            <span class="nav-icon">↗</span> View Restaurant
        </a>
        <a class="nav-link" href="#">
            <span class="nav-icon">⇥</span> Exit Admin Demo
        </a>
    </div>
</aside>

<main class="main-content">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="icon-button sidebar-toggle"
                    id="sidebarToggle"
                    aria-label="Toggle sidebar">☰</button>
            <div>
                <h1>Orders Management</h1>
                <p>Manage and track your restaurant orders.</p>
            </div>
        </div>

        <div class="top-actions">
            <button class="icon-button" title="Demo notifications">♧</button>
            <div class="admin-avatar">AD</div>
            <div class="d-none d-sm-block">
                <div style="font-size:12px;font-weight:800;">Admin User</div>
                <div style="font-size:10px;color:#858b99;">Administrator</div>
            </div>
        </div>
    </header>

    <section class="page-content">
        <div class="eyebrow">Restaurant Operations</div>
        <h2 class="page-heading">Orders Overview</h2>
        <p class="page-description">
            Review customer orders, check order details and demonstrate the order status workflow.
        </p>

        <div class="row g-3" id="statCards">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-orange">🛍</div>
                    <div>
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-number" id="totalOrders">6</div>
                        <div class="stat-note">All sample orders</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-blue">◷</div>
                    <div>
                        <div class="stat-label">Pending Orders</div>
                        <div class="stat-number" id="pendingOrders">1</div>
                        <div class="stat-note">Awaiting confirmation</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-purple">♨</div>
                    <div>
                        <div class="stat-label">In Progress</div>
                        <div class="stat-number" id="inProgressOrders">3</div>
                        <div class="stat-note">Confirmed, preparing or ready</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-green">✓</div>
                    <div>
                        <div class="stat-label">Delivered</div>
                        <div class="stat-number" id="deliveredOrders">1</div>
                        <div class="stat-note">Successfully completed</div>
                    </div>
                </div>
            </div>
        </div>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2>All Orders</h2>
                    <p>Search, filter and update demonstration orders.</p>
                </div>
                <span class="demo-badge">DEMO DATA</span>
            </div>

            <div class="filters">
                <div class="search-wrap">
                    <span>⌕</span>
                    <input type="search" class="form-control" id="orderSearch"
                           placeholder="Search order ID or customer...">
                </div>

                <select class="form-select" id="statusFilter"
                        aria-label="Filter by order status">
                    <option value="All">All Order Statuses</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= e($status) ?>"><?= e($status) ?></option>
                    <?php endforeach; ?>
                </select>

                <select class="form-select" id="typeFilter"
                        aria-label="Filter by order type">
                    <option value="All">All Order Types</option>
                    <option value="Delivery">Delivery</option>
                    <option value="Pickup">Pickup</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table orders-table">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Order Type</th>
                        <th>Date / Time</th>
                        <th>Total</th>
                        <th>Order Status</th>
                        <th>Change Status</th>
                        <th>Details</th>
                    </tr>
                    </thead>

                    <tbody id="ordersTableBody">
                    <?php foreach ($orders as $index => $order): ?>
                        <tr data-index="<?= $index ?>"
                            data-order-id="<?= e($order['id']) ?>"
                            data-customer="<?= e($order['customer']) ?>"
                            data-type="<?= e($order['type']) ?>"
                            data-status="<?= e($order['status']) ?>">

                            <td>
                                <div class="order-id"><?= e($order['id']) ?></div>
                            </td>

                            <td>
                                <div class="customer-cell">
                                    <div class="customer-avatar"><?= e($order['initials']) ?></div>
                                    <div>
                                        <div class="customer-name"><?= e($order['customer']) ?></div>
                                        <div class="customer-phone"><?= e($order['phone']) ?></div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="order-type"><?= e($order['type']) ?></span>
                            </td>

                            <td>
                                <div><?= e($order['date']) ?></div>
                                <div class="customer-phone"><?= e($order['time']) ?></div>
                            </td>

                            <td class="amount">
                                Rs. <?= number_format($order['total']) ?>
                            </td>

                            <td>
                                <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $order['status'])) ?>"
                                      data-status-badge>
                                    <?= e($order['status']) ?>
                                </span>
                            </td>

                            <td>
                                <select class="form-select table-status-select status-select"
                                        aria-label="Change status for <?= e($order['id']) ?>">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= e($status) ?>"
                                            <?= $status === $order['status'] ? 'selected' : '' ?>>
                                            <?= e($status) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td>
                                <button class="table-action view-order"
                                        type="button"
                                        data-index="<?= $index ?>"
                                        aria-label="View order details"
                                        title="View order details">↗</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="empty-state" id="emptyState">
                <div class="empty-icon">🔎</div>
                <strong>No matching orders found</strong>
                <div class="mt-2 small">Try another search term or filter.</div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                <small class="text-secondary" id="resultCount">Showing 6 sample orders</small>
                <button class="btn-light-custom" type="button" id="resetDemo">
                    Reset Demo Data
                </button>
            </div>
        </section>

        <section class="panel workflow-panel">
            <div class="panel-heading">
                <div>
                    <h2>Order Status Workflow</h2>
                    <p>Typical order progress from receipt to completion.</p>
                </div>
            </div>

            <div class="workflow">
                <div class="workflow-step">
                    <div class="workflow-number">1</div>
                    <strong>Pending</strong>
                    <small>Order received</small>
                </div>
                <div class="workflow-step">
                    <div class="workflow-number">2</div>
                    <strong>Confirmed</strong>
                    <small>Order accepted</small>
                </div>
                <div class="workflow-step">
                    <div class="workflow-number">3</div>
                    <strong>Preparing</strong>
                    <small>Kitchen preparing</small>
                </div>
                <div class="workflow-step">
                    <div class="workflow-number">4</div>
                    <strong>Ready</strong>
                    <small>Ready for handover</small>
                </div>
                <div class="workflow-step">
                    <div class="workflow-number">5</div>
                    <strong>Out for Delivery</strong>
                    <small>With delivery rider</small>
                </div>
                <div class="workflow-step">
                    <div class="workflow-number">6</div>
                    <strong>Delivered</strong>
                    <small>Order completed</small>
                </div>
            </div>

            <div class="mt-3 text-secondary" style="font-size:11px;">
                This is a portfolio demonstration. Status changes are temporary and will reset when the page is refreshed.
            </div>
        </section>

        <div class="text-center text-secondary mt-4" style="font-size:11px;">
            © <?= date('Y') ?> UrbanBites Restaurant Management Demo
        </div>
    </section>
</main>

<!-- Order details modal -->
<div class="modal fade" id="orderModal" tabindex="-1"
     aria-labelledby="orderModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="eyebrow mb-1">Order Information</div>
                    <h2 class="modal-title fs-5 fw-bold" id="orderModalTitle">Order Details</h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body" id="orderModalBody"></div>

            <div class="modal-footer">
                <button type="button" class="btn-light-custom"
                        data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Status change confirmation -->
<div class="modal fade" id="statusModal" tabindex="-1"
     aria-labelledby="statusModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5 fw-bold" id="statusModalTitle">
                    Update Order Status
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="statusConfirmationText" class="mb-0"
                   style="font-size:13px;line-height:1.7;"></p>
                <div class="alert alert-warning mt-3 mb-0"
                     style="font-size:12px;">
                    Demo only: this change is not saved to a database.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-light-custom"
                        data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-orange"
                        id="confirmStatusChange">Confirm Change</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Sample orders are generated by PHP and embedded as JSON for this demo.
    const originalOrders = <?= json_encode(
        $orders,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ) ?>;

    const statuses = <?= json_encode(
        $statuses,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ) ?>;

    // Create a separate working copy so changes do not modify the PHP data.
    let demoOrders = JSON.parse(JSON.stringify(originalOrders));

    const tableBody = document.getElementById('ordersTableBody');
    const searchInput = document.getElementById('orderSearch');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const emptyState = document.getElementById('emptyState');

    const orderModal = new bootstrap.Modal(
        document.getElementById('orderModal')
    );

    const statusModal = new bootstrap.Modal(
        document.getElementById('statusModal')
    );

    let pendingStatusChange = null;

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function formatMoney(amount) {
        return 'Rs. ' + Number(amount).toLocaleString('en-US');
    }

    function statusClass(status) {
        return 'status-' + status.toLowerCase().replace(/\s+/g, '-');
    }

    function updateSummary() {
        const total = demoOrders.length;
        const pending = demoOrders.filter(o => o.status === 'Pending').length;
        const inProgress = demoOrders.filter(o =>
            ['Confirmed', 'Preparing', 'Ready'].includes(o.status)
        ).length;
        const delivered = demoOrders.filter(o => o.status === 'Delivered').length;

        document.getElementById('totalOrders').textContent = total;
        document.getElementById('pendingOrders').textContent = pending;
        document.getElementById('inProgressOrders').textContent = inProgress;
        document.getElementById('deliveredOrders').textContent = delivered;
    }

    function renderOrders() {
        const search = searchInput.value.trim().toLowerCase();
        const selectedStatus = statusFilter.value;
        const selectedType = typeFilter.value;

        let visibleCount = 0;

        Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
            const index = Number(row.dataset.index);
            const order = demoOrders[index];

            const matchesSearch =
                order.id.toLowerCase().includes(search) ||
                order.customer.toLowerCase().includes(search) ||
                order.phone.toLowerCase().includes(search);

            const matchesStatus =
                selectedStatus === 'All' || order.status === selectedStatus;

            const matchesType =
                selectedType === 'All' || order.type === selectedType;

            const visible = matchesSearch && matchesStatus && matchesType;

            row.style.display = visible ? '' : 'none';

            if (visible) visibleCount++;

            const badge = row.querySelector('[data-status-badge]');
            badge.className = 'status-badge ' + statusClass(order.status);
            badge.textContent = order.status;

            row.querySelector('.status-select').value = order.status;
        });

        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        document.getElementById('resultCount').textContent =
            'Showing ' + visibleCount + ' of ' + demoOrders.length + ' sample orders';

        updateSummary();
    }

    function renderOrderDetails(index) {
        const order = demoOrders[index];

        if (!order) return;

        const itemsHtml = order.items.map(item => `
            <div class="item-row">
                <span>
                    ${escapeHtml(item.name)}
                    <span class="text-secondary">× ${Number(item.qty)}</span>
                </span>
                <strong>${formatMoney(item.price * item.qty)}</strong>
            </div>
        `).join('');

        document.getElementById('orderModalTitle').textContent = order.id;

        document.getElementById('orderModalBody').innerHTML = `
            <div class="row">
                <div class="col-sm-6">
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">${escapeHtml(order.customer)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Contact Number</div>
                    <div class="detail-value">${escapeHtml(order.phone)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Order Date & Time</div>
                    <div class="detail-value">${escapeHtml(order.date)} · ${escapeHtml(order.time)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Order Type</div>
                    <div class="detail-value">${escapeHtml(order.type)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value">${escapeHtml(order.payment)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Current Status</div>
                    <div class="detail-value">
                        <span class="status-badge ${statusClass(order.status)}">
                            ${escapeHtml(order.status)}
                        </span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Delivery / Collection Location</div>
                    <div class="detail-value">${escapeHtml(order.address)}</div>
                </div>
            </div>

            <h3 class="fs-6 fw-bold mb-3">Ordered Items</h3>
            <div class="items-list">${itemsHtml}</div>

            <div class="d-flex justify-content-between mb-2" style="font-size:12px;">
                <span class="text-secondary">Subtotal</span>
                <strong>${formatMoney(order.subtotal)}</strong>
            </div>
            <div class="d-flex justify-content-between mb-3" style="font-size:12px;">
                <span class="text-secondary">Delivery Fee</span>
                <strong>${formatMoney(order.delivery)}</strong>
            </div>
            <div class="total-row mb-3">
                <span>Order Total</span>
                <span style="color:#f47721;">${formatMoney(order.total)}</span>
            </div>

            <div class="detail-label">Order Notes</div>
            <div class="p-3 rounded-3" style="background:#f8f9fc;font-size:12px;">
                ${escapeHtml(order.notes || 'No additional notes.')}
            </div>
        `;

        orderModal.show();
    }

    // Ask for confirmation before demonstrating a status update.
    tableBody.addEventListener('change', function (event) {
        if (!event.target.classList.contains('status-select')) return;

        const row = event.target.closest('tr');
        const index = Number(row.dataset.index);
        const newStatus = event.target.value;
        const oldStatus = demoOrders[index].status;

        if (newStatus === oldStatus) return;

        pendingStatusChange = { index, newStatus, oldStatus };

        document.getElementById('statusConfirmationText').textContent =
            'Change order ' + demoOrders[index].id +
            ' from "' + oldStatus + '" to "' + newStatus + '"?';

        statusModal.show();
    });

    document.getElementById('confirmStatusChange').addEventListener('click', function () {
        if (!pendingStatusChange) return;

        const { index, newStatus } = pendingStatusChange;
        demoOrders[index].status = newStatus;

        pendingStatusChange = null;
        statusModal.hide();

        renderOrders();
    });

    document.getElementById('statusModal').addEventListener('hidden.bs.modal', function () {
        if (pendingStatusChange) {
            pendingStatusChange = null;
            renderOrders();
        }
    });

    tableBody.addEventListener('click', function (event) {
        const button = event.target.closest('.view-order');

        if (button) {
            renderOrderDetails(Number(button.dataset.index));
        }
    });

    searchInput.addEventListener('input', renderOrders);
    statusFilter.addEventListener('change', renderOrders);
    typeFilter.addEventListener('change', renderOrders);

    document.getElementById('resetDemo').addEventListener('click', function () {
        demoOrders = JSON.parse(JSON.stringify(originalOrders));
        searchInput.value = '';
        statusFilter.value = 'All';
        typeFilter.value = 'All';
        renderOrders();
    });

    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Initial page setup
    renderOrders();
</script>

</body>
</html>
