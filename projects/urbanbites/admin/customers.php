
<?php
// UrbanBites Admin - Customer Management
// Demonstration only. No database or authentication changes are performed.

$customers = [
    [
        'id' => 'CUS-001',
        'name' => 'Kasun Perera',
        'email' => 'kasun.perera@example.com',
        'phone' => '077 123 4567',
        'city' => 'Colombo',
        'joined' => '2026-08-12',
        'orders' => 18,
        'spent' => 42500,
        'status' => 'Active',
        'last_order' => '2026-10-12',
        'payment' => 'Card Payment'
    ],
    [
        'id' => 'CUS-002',
        'name' => 'Nethmi Fernando',
        'email' => 'nethmi.fernando@example.com',
        'phone' => '071 234 5678',
        'city' => 'Dehiwala',
        'joined' => '2026-09-05',
        'orders' => 12,
        'spent' => 28600,
        'status' => 'Active',
        'last_order' => '2026-10-13',
        'payment' => 'Cash on Delivery'
    ],
    [
        'id' => 'CUS-003',
        'name' => 'Amal Jayawardena',
        'email' => 'amal.j@example.com',
        'phone' => '076 345 6789',
        'city' => 'Nugegoda',
        'joined' => '2026-07-21',
        'orders' => 25,
        'spent' => 67400,
        'status' => 'Active',
        'last_order' => '2026-10-13',
        'payment' => 'Card Payment'
    ],
    [
        'id' => 'CUS-004',
        'name' => 'Dinushi Silva',
        'email' => 'dinushi.silva@example.com',
        'phone' => '075 456 7890',
        'city' => 'Rajagiriya',
        'joined' => '2026-09-18',
        'orders' => 4,
        'spent' => 8400,
        'status' => 'Blocked',
        'last_order' => '2026-09-29',
        'payment' => 'Cash on Delivery'
    ],
    [
        'id' => 'CUS-005',
        'name' => 'Tharindu Kumara',
        'email' => 'tharindu.k@example.com',
        'phone' => '078 567 8901',
        'city' => 'Borella',
        'joined' => '2026-06-14',
        'orders' => 31,
        'spent' => 89200,
        'status' => 'Active',
        'last_order' => '2026-10-11',
        'payment' => 'Card Payment'
    ],
    [
        'id' => 'CUS-006',
        'name' => 'Piumi Rathnayake',
        'email' => 'piumi.r@example.com',
        'phone' => '070 678 9012',
        'city' => 'Maharagama',
        'joined' => '2026-10-02',
        'orders' => 2,
        'spent' => 5300,
        'status' => 'Blocked',
        'last_order' => '2026-10-04',
        'payment' => 'Cash on Delivery'
    ],
    [
        'id' => 'CUS-007',
        'name' => 'Sahan Wijesinghe',
        'email' => 'sahan.w@example.com',
        'phone' => '072 789 0123',
        'city' => 'Kotte',
        'joined' => '2026-10-09',
        'orders' => 1,
        'spent' => 3200,
        'status' => 'Active',
        'last_order' => '2026-10-09',
        'payment' => 'Card Payment'
    ],
    [
        'id' => 'CUS-008',
        'name' => 'Isuri Madushani',
        'email' => 'isuri.m@example.com',
        'phone' => '074 890 1234',
        'city' => 'Wattala',
        'joined' => '2026-09-25',
        'orders' => 7,
        'spent' => 16400,
        'status' => 'Active',
        'last_order' => '2026-10-10',
        'payment' => 'Cash on Delivery'
    ]
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

    <title>Customers | UrbanBites Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --orange: #f47721;
            --orange-light: #fff0e5;
            --dark: #20232e;
            --muted: #858b99;
            --background: #f6f7fb;
            --border: #eceef3;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--background);
            color: #292d38;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1000;
            width: 250px;
            padding: 24px 16px;
            overflow-y: auto;
            background: var(--dark);
            color: white;
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
            background: var(--orange);
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
            letter-spacing: 1.5px;
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
            background: var(--orange);
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
            background: rgba(255,255,255,.96);
            border-bottom: 1px solid var(--border);
        }

        .topbar h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
        }

        .topbar p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-button {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid var(--border);
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

        .page-content { padding: 28px 30px 40px; }

        .eyebrow {
            margin-bottom: 8px;
            color: var(--orange);
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
            color: var(--muted);
            font-size: 13px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 14px;
            height: 100%;
            padding: 19px;
            background: white;
            border: 1px solid var(--border);
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
            color: var(--muted);
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
        .stat-red { color: #d94c55; background: #ffeaec; }
        .stat-green { color: #239c67; background: #e6f8ef; }

        .panel {
            margin-top: 25px;
            padding: 23px;
            background: white;
            border: 1px solid var(--border);
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
            color: var(--muted);
            font-size: 12px;
        }

        .demo-badge {
            padding: 7px 10px;
            color: #9b5a14;
            background: #fff5dc;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .filters {
            display: grid;
            grid-template-columns: minmax(200px, 1fr) 210px;
            gap: 12px;
            margin-bottom: 20px;
        }

        .form-control, .form-select {
            min-height: 43px;
            border-color: #e7e9ef;
            border-radius: 10px;
            font-size: 12px;
            box-shadow: none !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--orange);
        }

        .search-wrap { position: relative; }

        .search-wrap span {
            position: absolute;
            top: 11px;
            left: 13px;
            color: #9298a5;
        }

        .search-wrap input { padding-left: 38px; }

        .customers-table {
            min-width: 900px;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .customers-table thead th {
            padding: 13px 12px;
            color: #858b99;
            background: #f8f9fc;
            border-bottom: 1px solid var(--border);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .7px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .customers-table tbody td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f1f5;
            font-size: 12px;
        }

        .customers-table tbody tr:last-child td { border-bottom: 0; }

        .customer-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 190px;
        }

        .customer-avatar {
            display: grid;
            flex-shrink: 0;
            place-items: center;
            width: 40px;
            height: 40px;
            color: #a65a20;
            background: #fff0e4;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
        }

        .customer-name {
            margin-bottom: 4px;
            font-weight: 800;
        }

        .customer-email {
            color: #9298a5;
            font-size: 10px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
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

        .status-active {
            color: #168454;
            background: #def7e9;
        }

        .status-blocked {
            color: #c33c49;
            background: #ffeaec;
        }

        .action-btn {
            padding: 7px 10px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .btn-block-customer {
            color: #c33c49;
            background: #fff0f1;
            border: 1px solid #ffd7da;
        }

        .btn-block-customer:hover {
            color: white;
            background: #d94c55;
        }

        .btn-allow-customer {
            color: #168454;
            background: #e6f8ef;
            border: 1px solid #c8eed9;
        }

        .btn-allow-customer:hover {
            color: white;
            background: #239c67;
        }

        .view-btn {
            display: inline-grid;
            place-items: center;
            width: 33px;
            height: 33px;
            color: #626a7b;
            background: white;
            border: 1px solid #e9ebf0;
            border-radius: 9px;
        }

        .view-btn:hover {
            color: var(--orange);
            border-color: #ffd2b4;
        }

        .empty-state {
            display: none;
            padding: 45px 15px;
            color: var(--muted);
            text-align: center;
        }

        .empty-icon { margin-bottom: 10px; font-size: 35px; }

        .info-banner {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-top: 22px;
            padding: 16px;
            background: #fff8e9;
            border: 1px solid #ffebbd;
            border-radius: 13px;
        }

        .info-banner .info-icon { font-size: 20px; }

        .info-banner strong {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .info-banner p {
            margin: 0;
            color: #7f7153;
            font-size: 11px;
            line-height: 1.7;
        }

        .modal-content { border: 0; border-radius: 18px; }

        .modal-header {
            padding: 22px;
            border-bottom-color: var(--border);
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
            margin-bottom: 18px;
            font-size: 13px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .sidebar-toggle { display: none; }

        .btn-orange {
            padding: 10px 15px;
            color: white;
            background: var(--orange);
            border: 1px solid var(--orange);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-orange:hover { color: white; background: #dd6415; }

        .btn-light-custom {
            padding: 10px 14px;
            background: white;
            border: 1px solid #e7e9ef;
            border-radius: 10px;
            font-size: 12px;
        }

        @media (max-width: 1200px) {
            .sidebar { width: 225px; }
            .main-content { margin-left: 225px; }
            .page-content { padding: 24px 20px; }
            .topbar { padding: 16px 20px; }
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: inline-grid; }
        }

        @media (max-width: 560px) {
            .topbar { padding: 13px 15px; }
            .topbar h1 { font-size: 18px; }
            .topbar p { font-size: 10px; }
            .page-content { padding: 20px 13px 30px; }
            .page-heading { font-size: 23px; }
            .panel { padding: 15px; }
            .filters { grid-template-columns: 1fr; }
            .panel-heading { align-items: flex-start; }
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
        <a class="nav-link" href="orders.php">
            <span class="nav-icon">🛍</span> Orders Management
        </a>
        <a class="nav-link" href="menu_management.php">
            <span class="nav-icon">🍕</span> Menu Management
        </a>
        <a class="nav-link active" href="customers.php">
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
            <button class="icon-button sidebar-toggle" id="sidebarToggle"
                    aria-label="Toggle sidebar">☰</button>
            <div>
                <h1>Customer Management</h1>
                <p>Manage customer profiles and account access.</p>
            </div>
        </div>

        <div class="top-actions">
            <button class="icon-button" type="button" title="Demo notifications">♧</button>
            <div class="admin-avatar">AD</div>
            <div class="d-none d-sm-block">
                <div style="font-size:12px;font-weight:800;">Admin User</div>
                <div style="font-size:10px;color:#858b99;">Administrator</div>
            </div>
        </div>
    </header>

    <section class="page-content">
        <div class="eyebrow">Customer Relations</div>
        <h2 class="page-heading">Customers Overview</h2>
        <p class="page-description">
            Find customers, review their activity and manage access to the UrbanBites ordering system.
        </p>

        <div class="row g-3">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-orange">♙</div>
                    <div>
                        <div class="stat-label">Total Customers</div>
                        <div class="stat-number" id="totalCustomers">8</div>
                        <div class="stat-note">Registered demo accounts</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-green">✓</div>
                    <div>
                        <div class="stat-label">Active Customers</div>
                        <div class="stat-number" id="activeCustomers">6</div>
                        <div class="stat-note">Access currently allowed</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-red">⊘</div>
                    <div>
                        <div class="stat-label">Blocked Customers</div>
                        <div class="stat-number" id="blockedCustomers">2</div>
                        <div class="stat-note">Access currently restricted</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-blue">✦</div>
                    <div>
                        <div class="stat-label">New Customers</div>
                        <div class="stat-number" id="newCustomers">3</div>
                        <div class="stat-note">Joined in the last 30 days</div>
                    </div>
                </div>
            </div>
        </div>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2>Registered Customers</h2>
                    <p>Search customer records and manage access status.</p>
                </div>
                <span class="demo-badge">DEMO DATA</span>
            </div>

            <div class="filters">
                <div class="search-wrap">
                    <span>⌕</span>
                    <input type="search" class="form-control" id="customerSearch"
                           placeholder="Search name, email or phone...">
                </div>

                <select class="form-select" id="statusFilter"
                        aria-label="Filter customers by account status">
                    <option value="All">All Account Statuses</option>
                    <option value="Active">Active Customers</option>
                    <option value="Blocked">Blocked Customers</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table customers-table">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Customer ID</th>
                        <th>City</th>
                        <th>Total Orders</th>
                        <th>Total Spent</th>
                        <th>Account Status</th>
                        <th>Manage Access</th>
                        <th>Details</th>
                    </tr>
                    </thead>

                    <tbody id="customersTableBody">
                    <?php foreach ($customers as $index => $customer): ?>
                        <tr data-index="<?= $index ?>">
                            <td>
                                <div class="customer-cell">
                                    <div class="customer-avatar">
                                        <?= e(strtoupper(
                                            implode('', array_map(
                                                fn($part) => substr($part, 0, 1),
                                                explode(' ', $customer['name'])
                                            ))
                                        )) ?>
                                    </div>
                                    <div>
                                        <div class="customer-name"><?= e($customer['name']) ?></div>
                                        <div class="customer-email"><?= e($customer['email']) ?></div>
                                    </div>
                                </div>
                            </td>

                            <td><strong><?= e($customer['id']) ?></strong></td>
                            <td><?= e($customer['city']) ?></td>
                            <td><?= (int)$customer['orders'] ?></td>
                            <td><strong>Rs. <?= number_format($customer['spent']) ?></strong></td>

                            <td>
                                <span class="status-badge <?= $customer['status'] === 'Active' ? 'status-active' : 'status-blocked' ?>"
                                      data-status-badge>
                                    <?= e($customer['status']) ?>
                                </span>
                            </td>

                            <td>
                                <button type="button"
                                        class="action-btn <?= $customer['status'] === 'Active' ? 'btn-block-customer' : 'btn-allow-customer' ?> access-btn"
                                        data-index="<?= $index ?>">
                                    <?= $customer['status'] === 'Active' ? '⊘ Block' : '✓ Allow Access' ?>
                                </button>
                            </td>

                            <td>
                                <button type="button" class="view-btn view-customer"
                                        data-index="<?= $index ?>"
                                        title="View customer details"
                                        aria-label="View customer details">↗</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="empty-state" id="emptyState">
                <div class="empty-icon">🔎</div>
                <strong>No customers found</strong>
                <div class="small mt-2">Try a different name, email, phone number or status.</div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                <small class="text-secondary" id="resultCount">Showing 8 customers</small>
                <button class="btn-light-custom" id="resetDemo" type="button">
                    Reset Demo Data
                </button>
            </div>
        </section>

        <div class="info-banner">
            <div class="info-icon">ℹ️</div>
            <div>
                <strong>About customer access control</strong>
                <p>
                    Blocking a customer in a real application should prevent that account from
                    signing in or placing orders, according to the system's rules. This demo only
                    changes the displayed status; it does not modify authentication or any real account.
                </p>
            </div>
        </div>

        <div class="text-center text-secondary mt-4" style="font-size:11px;">
            © <?= date('Y') ?> UrbanBites Restaurant Management Demo
        </div>
    </section>
</main>

<!-- Customer details modal -->
<div class="modal fade" id="customerModal" tabindex="-1"
     aria-labelledby="customerModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="eyebrow mb-1">Customer Profile</div>
                    <h2 class="modal-title fs-5 fw-bold" id="customerModalTitle">
                        Customer Details
                    </h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body" id="customerModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn-light-custom"
                        data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Block / allow confirmation modal -->
<div class="modal fade" id="accessModal" tabindex="-1"
     aria-labelledby="accessModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5 fw-bold" id="accessModalTitle">
                    Confirm Account Action
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="accessConfirmationText" class="mb-0"
                   style="font-size:13px;line-height:1.8;"></p>
                <div class="alert alert-warning mt-3 mb-0" style="font-size:12px;">
                    This is a demonstration. No real customer account will be blocked or enabled.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-light-custom"
                        data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-orange" id="confirmAccessChange">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    const originalCustomers = <?= json_encode(
        $customers,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ) ?>;

    let demoCustomers = JSON.parse(JSON.stringify(originalCustomers));

    const tableBody = document.getElementById('customersTableBody');
    const searchInput = document.getElementById('customerSearch');
    const statusFilter = document.getElementById('statusFilter');
    const emptyState = document.getElementById('emptyState');

    const customerModal = new bootstrap.Modal(
        document.getElementById('customerModal')
    );

    const accessModal = new bootstrap.Modal(
        document.getElementById('accessModal')
    );

    let pendingAction = null;

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[char]);
    }

    function formatMoney(amount) {
        return 'Rs. ' + Number(amount).toLocaleString('en-US');
    }

    function updateSummary() {
        const total = demoCustomers.length;
        const active = demoCustomers.filter(c => c.status === 'Active').length;
        const blocked = demoCustomers.filter(c => c.status === 'Blocked').length;

        // Sample new-customer metric, based on joining within 30 days.
        const now = new Date('2026-10-13T12:00:00');
        const thirtyDaysAgo = new Date(now);
        thirtyDaysAgo.setDate(now.getDate() - 30);

        const newCustomers = demoCustomers.filter(customer => {
            const joined = new Date(customer.joined + 'T12:00:00');
            return joined >= thirtyDaysAgo && joined <= now;
        }).length;

        document.getElementById('totalCustomers').textContent = total;
        document.getElementById('activeCustomers').textContent = active;
        document.getElementById('blockedCustomers').textContent = blocked;
        document.getElementById('newCustomers').textContent = newCustomers;
    }

    function renderCustomers() {
        const query = searchInput.value.trim().toLowerCase();
        const selectedStatus = statusFilter.value;
        let visibleCount = 0;

        Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
            const index = Number(row.dataset.index);
            const customer = demoCustomers[index];

            const searchableText = [
                customer.name,
                customer.email,
                customer.phone,
                customer.id,
                customer.city
            ].join(' ').toLowerCase();

            const matchesSearch = searchableText.includes(query);
            const matchesStatus =
                selectedStatus === 'All' || customer.status === selectedStatus;

            const visible = matchesSearch && matchesStatus;
            row.style.display = visible ? '' : 'none';

            if (visible) visibleCount++;

            const badge = row.querySelector('[data-status-badge]');
            badge.className = 'status-badge ' +
                (customer.status === 'Active' ? 'status-active' : 'status-blocked');
            badge.textContent = customer.status;

            const button = row.querySelector('.access-btn');

            if (customer.status === 'Active') {
                button.className = 'action-btn btn-block-customer access-btn';
                button.textContent = '⊘ Block';
            } else {
                button.className = 'action-btn btn-allow-customer access-btn';
                button.textContent = '✓ Allow Access';
            }
        });

        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';

        document.getElementById('resultCount').textContent =
            'Showing ' + visibleCount + ' of ' + demoCustomers.length + ' customers';

        updateSummary();
    }

    function showCustomerDetails(index) {
        const customer = demoCustomers[index];
        if (!customer) return;

        document.getElementById('customerModalTitle').textContent = customer.name;

        document.getElementById('customerModalBody').innerHTML = `
            <div class="row">
                <div class="col-sm-6">
                    <div class="detail-label">Customer ID</div>
                    <div class="detail-value">${escapeHtml(customer.id)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Account Status</div>
                    <div class="detail-value">
                        <span class="status-badge ${
                            customer.status === 'Active' ? 'status-active' : 'status-blocked'
                        }">${escapeHtml(customer.status)}</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Full Name</div>
                    <div class="detail-value">${escapeHtml(customer.name)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value">${escapeHtml(customer.email)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value">${escapeHtml(customer.phone)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">City</div>
                    <div class="detail-value">${escapeHtml(customer.city)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Registration Date</div>
                    <div class="detail-value">${escapeHtml(customer.joined)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Last Order</div>
                    <div class="detail-value">${escapeHtml(customer.last_order)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Total Orders</div>
                    <div class="detail-value">${Number(customer.orders)}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Total Amount Spent</div>
                    <div class="detail-value">${formatMoney(customer.spent)}</div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Preferred / Most Recent Payment Method</div>
                    <div class="detail-value">${escapeHtml(customer.payment)}</div>
                </div>
            </div>
        `;

        customerModal.show();
    }

    // Open confirmation dialog for block/allow.
    tableBody.addEventListener('click', function(event) {
        const accessButton = event.target.closest('.access-btn');

        if (accessButton) {
            const index = Number(accessButton.dataset.index);
            const customer = demoCustomers[index];
            const newStatus = customer.status === 'Active' ? 'Blocked' : 'Active';

            pendingAction = { index, newStatus };

            document.getElementById('accessModalTitle').textContent =
                newStatus === 'Blocked' ? 'Block Customer?' : 'Allow Customer Access?';

            document.getElementById('accessConfirmationText').textContent =
                newStatus === 'Blocked'
                    ? 'Are you sure you want to block ' + customer.name +
                      '? In a real system, this would restrict their access according to the account policy.'
                    : 'Allow ' + customer.name +
                      ' to access the UrbanBites system again?';

            document.getElementById('confirmAccessChange').textContent =
                newStatus === 'Blocked' ? 'Block Customer' : 'Allow Access';

            accessModal.show();
            return;
        }

        const viewButton = event.target.closest('.view-customer');

        if (viewButton) {
            showCustomerDetails(Number(viewButton.dataset.index));
        }
    });

    document.getElementById('confirmAccessChange').addEventListener('click', function() {
        if (!pendingAction) return;

        demoCustomers[pendingAction.index].status = pendingAction.newStatus;
        pendingAction = null;

        accessModal.hide();
        renderCustomers();
    });

    document.getElementById('accessModal').addEventListener('hidden.bs.modal', function() {
        pendingAction = null;
    });

    searchInput.addEventListener('input', renderCustomers);
    statusFilter.addEventListener('change', renderCustomers);

    document.getElementById('resetDemo').addEventListener('click', function() {
        demoCustomers = JSON.parse(JSON.stringify(originalCustomers));
        searchInput.value = '';
        statusFilter.value = 'All';
        renderCustomers();
    });

    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });

    renderCustomers();
</script>

</body>
</html>
