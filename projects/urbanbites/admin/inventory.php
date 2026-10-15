
<?php
// UrbanBites Inventory Management
// Demonstration only: no database or real inventory updates.

$ingredients = [
    [
        'id' => 'ING-001',
        'name' => 'Chicken Breast',
        'category' => 'Meat & Poultry',
        'quantity' => 8,
        'unit' => 'kg',
        'minimum' => 15,
        'price' => 1800,
        'supplier' => 'FreshFarm Suppliers',
        'updated' => '2026-10-13'
    ],
    [
        'id' => 'ING-002',
        'name' => 'Chicken Sausages',
        'category' => 'Meat & Poultry',
        'quantity' => 4,
        'unit' => 'kg',
        'minimum' => 8,
        'price' => 1450,
        'supplier' => 'Prime Meat Distributors',
        'updated' => '2026-10-12'
    ],
    [
        'id' => 'ING-003',
        'name' => 'All-Purpose Flour',
        'category' => 'Bakery',
        'quantity' => 32,
        'unit' => 'kg',
        'minimum' => 15,
        'price' => 280,
        'supplier' => 'Golden Grain Traders',
        'updated' => '2026-10-13'
    ],
    [
        'id' => 'ING-004',
        'name' => 'Burger Buns',
        'category' => 'Bakery',
        'quantity' => 18,
        'unit' => 'pcs',
        'minimum' => 50,
        'price' => 120,
        'supplier' => 'City Bakery',
        'updated' => '2026-10-13'
    ],
    [
        'id' => 'ING-005',
        'name' => 'Mozzarella Cheese',
        'category' => 'Dairy',
        'quantity' => 3,
        'unit' => 'kg',
        'minimum' => 10,
        'price' => 3200,
        'supplier' => 'Dairy Fresh Lanka',
        'updated' => '2026-10-12'
    ],
    [
        'id' => 'ING-006',
        'name' => 'Tomato Sauce',
        'category' => 'Sauces & Condiments',
        'quantity' => 12,
        'unit' => 'bottles',
        'minimum' => 10,
        'price' => 650,
        'supplier' => 'TastePro Foods',
        'updated' => '2026-10-11'
    ],
    [
        'id' => 'ING-007',
        'name' => 'French Fries',
        'category' => 'Frozen Foods',
        'quantity' => 0,
        'unit' => 'kg',
        'minimum' => 12,
        'price' => 950,
        'supplier' => 'FrostLine Distributors',
        'updated' => '2026-10-13'
    ],
    [
        'id' => 'ING-008',
        'name' => 'Cooking Oil',
        'category' => 'Cooking Essentials',
        'quantity' => 24,
        'unit' => 'litres',
        'minimum' => 10,
        'price' => 580,
        'supplier' => 'Golden Oil Traders',
        'updated' => '2026-10-12'
    ],
    [
        'id' => 'ING-009',
        'name' => 'Fresh Tomatoes',
        'category' => 'Vegetables',
        'quantity' => 6,
        'unit' => 'kg',
        'minimum' => 12,
        'price' => 700,
        'supplier' => 'Green Basket Suppliers',
        'updated' => '2026-10-13'
    ],
    [
        'id' => 'ING-010',
        'name' => 'Lettuce',
        'category' => 'Vegetables',
        'quantity' => 2,
        'unit' => 'kg',
        'minimum' => 5,
        'price' => 900,
        'supplier' => 'Green Basket Suppliers',
        'updated' => '2026-10-13'
    ],
    [
        'id' => 'ING-011',
        'name' => 'Eggs',
        'category' => 'Dairy',
        'quantity' => 96,
        'unit' => 'pcs',
        'minimum' => 30,
        'price' => 55,
        'supplier' => 'Happy Hen Farm',
        'updated' => '2026-10-12'
    ],
    [
        'id' => 'ING-012',
        'name' => 'Chocolate',
        'category' => 'Bakery',
        'quantity' => 7,
        'unit' => 'kg',
        'minimum' => 8,
        'price' => 2400,
        'supplier' => 'Sweet Supply Co.',
        'updated' => '2026-10-10'
    ]
];

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function getStockStatus($item) {
    if ($item['quantity'] <= 0) {
        return 'Out of Stock';
    }

    if ($item['quantity'] <= $item['minimum']) {
        return 'Low Stock';
    }

    return 'In Stock';
}

$totalIngredients = count($ingredients);
$lowStockCount = 0;
$outOfStockCount = 0;
$inventoryValue = 0;

foreach ($ingredients as $ingredient) {
    $status = getStockStatus($ingredient);

    if ($status === 'Low Stock') {
        $lowStockCount++;
    } elseif ($status === 'Out of Stock') {
        $outOfStockCount++;
    }

    $inventoryValue += $ingredient['quantity'] * $ingredient['price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inventory Management | UrbanBites Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --orange: #f47721;
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

        .main-content { min-height: 100vh; margin-left: 250px; }

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

        .topbar h1 { margin: 0; font-size: 22px; font-weight: 800; }

        .topbar p { margin: 4px 0 0; color: var(--muted); font-size: 12px; }

        .top-actions { display: flex; align-items: center; gap: 12px; }

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

        .page-heading { margin-bottom: 7px; font-size: 27px; font-weight: 800; }

        .page-description { margin-bottom: 25px; color: var(--muted); font-size: 13px; }

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

        .stat-label { margin-bottom: 5px; color: var(--muted); font-size: 12px; }

        .stat-number { font-size: 24px; font-weight: 800; line-height: 1.2; }

        .stat-note { margin-top: 6px; color: #999eaa; font-size: 10px; }

        .stat-orange { color: #e86d19; background: #fff0e4; }
        .stat-red { color: #d94c55; background: #ffeaec; }
        .stat-yellow { color: #a86a08; background: #fff3d6; }
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

        .panel-heading h2 { margin: 0; font-size: 18px; font-weight: 800; }

        .panel-heading p { margin: 5px 0 0; color: var(--muted); font-size: 12px; }

        .demo-badge {
            padding: 7px 10px;
            color: #9b5a14;
            background: #fff5dc;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .alert-list { display: grid; gap: 12px; }

        .stock-alert {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 15px;
            border: 1px solid;
            border-radius: 12px;
        }

        .stock-alert.danger { background: #fff0f1; border-color: #ffd9dc; }
        .stock-alert.warning { background: #fff8e9; border-color: #ffebbd; }

        .alert-symbol {
            display: grid;
            flex-shrink: 0;
            place-items: center;
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,.8);
            border-radius: 10px;
            font-size: 17px;
        }

        .alert-title { margin-bottom: 4px; font-size: 12px; font-weight: 800; }

        .stock-alert.danger .alert-title { color: #bd3543; }
        .stock-alert.warning .alert-title { color: #94600b; }

        .alert-description { color: #777d89; font-size: 11px; line-height: 1.6; }

        .filters {
            display: grid;
            grid-template-columns: minmax(200px, 1fr) 210px 190px;
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

        .form-control:focus, .form-select:focus { border-color: var(--orange); }

        .search-wrap { position: relative; }

        .search-wrap span {
            position: absolute;
            top: 11px;
            left: 13px;
            color: #9298a5;
        }

        .search-wrap input { padding-left: 38px; }

        .inventory-table {
            min-width: 1050px;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .inventory-table thead th {
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

        .inventory-table tbody td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f1f5;
            font-size: 12px;
        }

        .inventory-table tbody tr:last-child td { border-bottom: 0; }

        .ingredient-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 180px;
        }

        .ingredient-icon {
            display: grid;
            flex-shrink: 0;
            place-items: center;
            width: 40px;
            height: 40px;
            background: #fff3e8;
            border-radius: 12px;
            font-size: 21px;
        }

        .ingredient-name { margin-bottom: 4px; font-weight: 800; }

        .ingredient-id { color: #9298a5; font-size: 10px; }

        .category-tag {
            display: inline-block;
            padding: 6px 8px;
            color: #626a7b;
            background: #f1f3f7;
            border-radius: 7px;
            font-size: 10px;
            white-space: nowrap;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 9px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .stock-badge::before {
            width: 6px;
            height: 6px;
            background: currentColor;
            border-radius: 50%;
            content: "";
        }

        .stock-in { color: #168454; background: #def7e9; }
        .stock-low { color: #a86a08; background: #fff3d6; }
        .stock-out { color: #c33c49; background: #ffeaec; }

        .quantity-value { font-size: 13px; font-weight: 800; }

        .quantity-low { color: #c33c49; }

        .stock-progress {
            width: 100px;
            height: 5px;
            margin-top: 7px;
            overflow: hidden;
            background: #edf0f5;
            border-radius: 20px;
        }

        .stock-progress span {
            display: block;
            height: 100%;
            border-radius: 20px;
        }

        .progress-good { background: #2caa70; }
        .progress-low { background: #f0aa30; }
        .progress-out { background: #dd4e59; }

        .action-btn {
            padding: 7px 10px;
            color: #c76720;
            background: #fff0e4;
            border: 1px solid #ffdcc5;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .action-btn:hover { color: white; background: var(--orange); }

        .empty-state {
            display: none;
            padding: 40px 15px;
            color: var(--muted);
            text-align: center;
        }

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

        .modal-content { border: 0; border-radius: 18px; }
        .modal-header { padding: 22px; border-bottom-color: var(--border); }
        .modal-body { padding: 22px; }

        .detail-label {
            margin-bottom: 5px;
            color: #8a90a0;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .detail-value { margin-bottom: 17px; font-size: 13px; font-weight: 700; }

        .sidebar-toggle { display: none; }

        @media (max-width: 1300px) {
            .filters { grid-template-columns: 1fr 1fr; }
            .search-wrap { grid-column: 1 / -1; }
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
            .search-wrap { grid-column: auto; }
            .panel-heading { align-items: flex-start; }
        }
    </style>
</head>

<body>

<aside class="sidebar" id="sidebar">
    <a href="index.php" class="brand">
        <span class="brand-icon">🍔</span>
        <span>UrbanBites<small>ADMIN PANEL</small></span>
    </a>

    <div class="nav-heading">Main Menu</div>

    <nav class="nav flex-column">
        <a class="nav-link" href="index.php"><span class="nav-icon">▦</span> Dashboard</a>
        <a class="nav-link" href="orders.php"><span class="nav-icon">🛍</span> Orders Management</a>
        <a class="nav-link" href="menu_management.php"><span class="nav-icon">🍕</span> Menu Management</a>
        <a class="nav-link" href="customers.php"><span class="nav-icon">♙</span> Customers</a>
        <a class="nav-link active" href="inventory.php"><span class="nav-icon">📦</span> Inventory</a>
        <a class="nav-link" href="#"><span class="nav-icon">🛵</span> Delivery Management</a>
        <a class="nav-link" href="#"><span class="nav-icon">☆</span> Reviews</a>
    </nav>

    <div class="nav-heading mt-3">Business</div>

    <nav class="nav flex-column">
        <a class="nav-link" href="#"><span class="nav-icon">🏷</span> Promotions</a>
        <a class="nav-link" href="#"><span class="nav-icon">▤</span> Sales Reports</a>
        <a class="nav-link" href="#"><span class="nav-icon">💰</span> Revenue</a>
        <a class="nav-link" href="#"><span class="nav-icon">⚙</span> Settings</a>
    </nav>

    <div class="sidebar-bottom">
        <a class="nav-link" href="#"><span class="nav-icon">↗</span> View Restaurant</a>
        <a class="nav-link" href="#"><span class="nav-icon">⇥</span> Exit Admin Demo</a>
    </div>
</aside>

<main class="main-content">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="icon-button sidebar-toggle" id="sidebarToggle"
                    aria-label="Toggle sidebar">☰</button>
            <div>
                <h1>Inventory Management</h1>
                <p>Monitor ingredients and keep the kitchen stocked.</p>
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
        <div class="eyebrow">Kitchen & Stock Control</div>
        <h2 class="page-heading">Inventory Overview</h2>
        <p class="page-description">
            Monitor ingredient quantities, identify stock shortages and simulate restocking.
        </p>

        <div class="row g-3">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-orange">📦</div>
                    <div>
                        <div class="stat-label">Total Ingredients</div>
                        <div class="stat-number" id="totalIngredients"><?= $totalIngredients ?></div>
                        <div class="stat-note">Tracked ingredient types</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-yellow">⚠</div>
                    <div>
                        <div class="stat-label">Low Stock</div>
                        <div class="stat-number" id="lowStockCount"><?= $lowStockCount ?></div>
                        <div class="stat-note">Restocking recommended</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-red">⊘</div>
                    <div>
                        <div class="stat-label">Out of Stock</div>
                        <div class="stat-number" id="outOfStockCount"><?= $outOfStockCount ?></div>
                        <div class="stat-note">Immediate action needed</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-green">₨</div>
                    <div>
                        <div class="stat-label">Inventory Value</div>
                        <div class="stat-number" style="font-size:21px;" id="inventoryValue">
                            Rs. <?= number_format($inventoryValue) ?>
                        </div>
                        <div class="stat-note">Estimated sample stock value</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock alerts -->
        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2>⚠ Inventory Alerts</h2>
                    <p>Ingredients that need attention before the next busy period.</p>
                </div>
                <span class="demo-badge">LIVE DEMO</span>
            </div>

            <div class="alert-list" id="alertList">
                <?php
                $alertItems = array_values(array_filter(
                    $ingredients,
                    fn($item) => getStockStatus($item) !== 'In Stock'
                ));
                ?>

                <?php if (count($alertItems) === 0): ?>
                    <div class="stock-alert warning">
                        <div class="alert-symbol">✓</div>
                        <div>
                            <div class="alert-title">All ingredients are sufficiently stocked</div>
                            <div class="alert-description">No stock alerts at the moment.</div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($alertItems as $item): ?>
                        <?php $status = getStockStatus($item); ?>
                        <div class="stock-alert <?= $status === 'Out of Stock' ? 'danger' : 'warning' ?>">
                            <div class="alert-symbol">
                                <?= $status === 'Out of Stock' ? '🚨' : '⚠️' ?>
                            </div>
                            <div>
                                <div class="alert-title">
                                    <?= $status === 'Out of Stock' ? 'OUT OF STOCK' : 'LOW STOCK' ?>
                                    — <?= e($item['name']) ?>
                                </div>
                                <div class="alert-description">
                                    Current quantity:
                                    <strong><?= e($item['quantity']) ?> <?= e($item['unit']) ?></strong>.
                                    Minimum required:
                                    <?= e($item['minimum']) ?> <?= e($item['unit']) ?>.
                                    Supplier: <?= e($item['supplier']) ?>.
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Inventory table -->
        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2>Ingredient Stock</h2>
                    <p>Current quantities, minimum stock levels and supplier information.</p>
                </div>
                <span class="demo-badge">DEMO DATA</span>
            </div>

            <div class="filters">
                <div class="search-wrap">
                    <span>⌕</span>
                    <input type="search" class="form-control" id="ingredientSearch"
                           placeholder="Search ingredients or suppliers...">
                </div>

                <select class="form-select" id="categoryFilter" aria-label="Filter by category">
                    <option value="All">All Categories</option>
                    <?php
                    $categories = array_unique(array_column($ingredients, 'category'));
                    sort($categories);
                    foreach ($categories as $category):
                    ?>
                        <option value="<?= e($category) ?>"><?= e($category) ?></option>
                    <?php endforeach; ?>
                </select>

                <select class="form-select" id="stockFilter" aria-label="Filter by stock status">
                    <option value="All">All Stock Statuses</option>
                    <option value="In Stock">In Stock</option>
                    <option value="Low Stock">Low Stock</option>
                    <option value="Out of Stock">Out of Stock</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table inventory-table">
                    <thead>
                    <tr>
                        <th>Ingredient</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Minimum Stock</th>
                        <th>Unit Cost</th>
                        <th>Stock Status</th>
                        <th>Supplier</th>
                        <th>Restock</th>
                    </tr>
                    </thead>

                    <tbody id="inventoryTableBody">
                    <?php foreach ($ingredients as $index => $item): ?>
                        <?php
                        $status = getStockStatus($item);
                        $percentage = $item['minimum'] > 0
                            ? min(100, ($item['quantity'] / ($item['minimum'] * 2)) * 100)
                            : 100;
                        $iconMap = [
                            'Meat & Poultry' => '🍗',
                            'Bakery' => '🥖',
                            'Dairy' => '🧀',
                            'Sauces & Condiments' => '🍅',
                            'Frozen Foods' => '🍟',
                            'Cooking Essentials' => '🫗',
                            'Vegetables' => '🥬'
                        ];
                        ?>
                        <tr data-index="<?= $index ?>">
                            <td>
                                <div class="ingredient-cell">
                                    <div class="ingredient-icon">
                                        <?= $iconMap[$item['category']] ?? '📦' ?>
                                    </div>
                                    <div>
                                        <div class="ingredient-name"><?= e($item['name']) ?></div>
                                        <div class="ingredient-id"><?= e($item['id']) ?></div>
                                    </div>
                                </div>
                            </td>

                            <td><span class="category-tag"><?= e($item['category']) ?></span></td>

                            <td>
                                <div class="quantity-value <?= $status === 'In Stock' ? '' : 'quantity-low' ?>"
                                     data-quantity>
                                    <?= e($item['quantity']) ?> <?= e($item['unit']) ?>
                                </div>
                                <div class="stock-progress">
                                    <span class="<?= $status === 'Out of Stock' ? 'progress-out' : ($status === 'Low Stock' ? 'progress-low' : 'progress-good') ?>"
                                          style="width:<?= $percentage ?>%"
                                          data-progress></span>
                                </div>
                            </td>

                            <td><?= e($item['minimum']) ?> <?= e($item['unit']) ?></td>
                            <td>Rs. <?= number_format($item['price']) ?></td>

                            <td>
                                <span class="stock-badge <?= $status === 'In Stock' ? 'stock-in' : ($status === 'Low Stock' ? 'stock-low' : 'stock-out') ?>"
                                      data-stock-badge>
                                    <?= e($status) ?>
                                </span>
                            </td>

                            <td><?= e($item['supplier']) ?></td>

                            <td>
                                <button type="button" class="action-btn restock-btn"
                                        data-index="<?= $index ?>">＋ Restock</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="empty-state" id="emptyState">
                <div style="font-size:35px;margin-bottom:10px;">🔎</div>
                <strong>No ingredients found</strong>
                <div class="small mt-2">Try a different search or filter.</div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                <small class="text-secondary" id="resultCount">
                    Showing <?= $totalIngredients ?> ingredients
                </small>
                <button class="btn-light-custom" id="resetDemo" type="button">
                    Reset Demo Data
                </button>
            </div>
        </section>

        <div class="text-center text-secondary mt-4" style="font-size:11px;">
            © <?= date('Y') ?> UrbanBites Restaurant Management Demo
        </div>
    </section>
</main>

<!-- Restock modal -->
<div class="modal fade" id="restockModal" tabindex="-1"
     aria-labelledby="restockModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="eyebrow mb-1">Inventory Update</div>
                    <h2 class="modal-title fs-5 fw-bold" id="restockModalTitle">
                        Receive New Stock
                    </h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <div class="detail-label">Selected Ingredient</div>
                    <div class="detail-value" id="restockIngredientName">Ingredient</div>
                </div>

                <div class="mb-3">
                    <div class="detail-label">Current Quantity</div>
                    <div class="detail-value" id="restockCurrentQuantity">0</div>
                </div>

                <label for="restockQuantity" class="form-label fw-bold"
                       style="font-size:12px;">Quantity Received</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="restockQuantity"
                           min="1" step="1" placeholder="Enter quantity received">
                    <span class="input-group-text" id="restockUnit">units</span>
                </div>

                <div class="form-text">
                    The received quantity will be added to the existing stock for this demonstration.
                </div>
                <div class="alert alert-warning mt-3 mb-0" style="font-size:12px;">
                    Demo only: this change is temporary and is not stored in a database.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-light-custom"
                        data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-orange" id="confirmRestock">
                    Update Stock
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    const originalIngredients = <?= json_encode(
        $ingredients,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ) ?>;

    let demoIngredients = JSON.parse(JSON.stringify(originalIngredients));
    let selectedIngredientIndex = null;

    const tableBody = document.getElementById('inventoryTableBody');
    const searchInput = document.getElementById('ingredientSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const stockFilter = document.getElementById('stockFilter');
    const emptyState = document.getElementById('emptyState');

    const restockModal = new bootstrap.Modal(
        document.getElementById('restockModal')
    );

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[char]);
    }

    function getStatus(item) {
        if (item.quantity <= 0) return 'Out of Stock';
        if (item.quantity <= item.minimum) return 'Low Stock';
        return 'In Stock';
    }

    function formatMoney(amount) {
        return 'Rs. ' + Number(amount).toLocaleString('en-US');
    }

    function updateSummary() {
        let low = 0;
        let out = 0;
        let value = 0;

        demoIngredients.forEach(item => {
            const status = getStatus(item);

            if (status === 'Low Stock') low++;
            if (status === 'Out of Stock') out++;

            value += item.quantity * item.price;
        });

        document.getElementById('totalIngredients').textContent =
            demoIngredients.length;
        document.getElementById('lowStockCount').textContent = low;
        document.getElementById('outOfStockCount').textContent = out;
        document.getElementById('inventoryValue').textContent = formatMoney(value);
    }

    function renderAlerts() {
        const alertList = document.getElementById('alertList');
        const alertItems = demoIngredients.filter(item => getStatus(item) !== 'In Stock');

        if (alertItems.length === 0) {
            alertList.innerHTML = `
                <div class="stock-alert warning">
                    <div class="alert-symbol">✓</div>
                    <div>
                        <div class="alert-title">All ingredients are sufficiently stocked</div>
                        <div class="alert-description">No stock alerts at the moment.</div>
                    </div>
                </div>
            `;
            return;
        }

        alertList.innerHTML = alertItems.map(item => {
            const status = getStatus(item);
            const out = status === 'Out of Stock';

            return `
                <div class="stock-alert ${out ? 'danger' : 'warning'}">
                    <div class="alert-symbol">${out ? '🚨' : '⚠️'}</div>
                    <div>
                        <div class="alert-title">
                            ${out ? 'OUT OF STOCK' : 'LOW STOCK'} — ${escapeHtml(item.name)}
                        </div>
                        <div class="alert-description">
                            Current quantity:
                            <strong>${Number(item.quantity)} ${escapeHtml(item.unit)}</strong>.
                            Minimum required: ${Number(item.minimum)} ${escapeHtml(item.unit)}.
                            Supplier: ${escapeHtml(item.supplier)}.
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderInventory() {
        const query = searchInput.value.trim().toLowerCase();
        const category = categoryFilter.value;
        const statusFilterValue = stockFilter.value;
        let visibleCount = 0;

        Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
            const index = Number(row.dataset.index);
            const item = demoIngredients[index];
            const status = getStatus(item);

            const searchText = [
                item.name, item.id, item.category, item.supplier
            ].join(' ').toLowerCase();

            const matchesSearch = searchText.includes(query);
            const matchesCategory = category === 'All' || item.category === category;
            const matchesStatus = statusFilterValue === 'All' || status === statusFilterValue;
            const visible = matchesSearch && matchesCategory && matchesStatus;

            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;

            const quantity = row.querySelector('[data-quantity]');
            quantity.textContent = Number(item.quantity) + ' ' + item.unit;
            quantity.className = 'quantity-value ' +
                (status === 'In Stock' ? '' : 'quantity-low');

            const badge = row.querySelector('[data-stock-badge]');
            badge.className = 'stock-badge ' +
                (status === 'In Stock' ? 'stock-in' :
                status === 'Low Stock' ? 'stock-low' : 'stock-out');
            badge.textContent = status;

            const percentage = item.minimum > 0
                ? Math.min(100, (item.quantity / (item.minimum * 2)) * 100)
                : 100;

            const progress = row.querySelector('[data-progress]');
            progress.style.width = percentage + '%';
            progress.className =
                status === 'Out of Stock' ? 'progress-out' :
                status === 'Low Stock' ? 'progress-low' : 'progress-good';
        });

        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';

        document.getElementById('resultCount').textContent =
            'Showing ' + visibleCount + ' of ' + demoIngredients.length + ' ingredients';

        updateSummary();
        renderAlerts();
    }

    tableBody.addEventListener('click', function(event) {
        const button = event.target.closest('.restock-btn');
        if (!button) return;

        selectedIngredientIndex = Number(button.dataset.index);
        const item = demoIngredients[selectedIngredientIndex];

        document.getElementById('restockIngredientName').textContent = item.name;
        document.getElementById('restockCurrentQuantity').textContent =
            item.quantity + ' ' + item.unit;
        document.getElementById('restockUnit').textContent = item.unit;
        document.getElementById('restockQuantity').value = '';
        document.getElementById('restockQuantity').min = '1';

        restockModal.show();
    });

    document.getElementById('confirmRestock').addEventListener('click', function() {
        if (selectedIngredientIndex === null) return;

        const input = document.getElementById('restockQuantity');
        const quantity = Number(input.value);

        if (!Number.isInteger(quantity) || quantity <= 0) {
            input.setCustomValidity('Enter a whole number greater than zero.');
            input.reportValidity();
            input.setCustomValidity('');
            return;
        }

        demoIngredients[selectedIngredientIndex].quantity += quantity;

        selectedIngredientIndex = null;
        restockModal.hide();
        renderInventory();
    });

    searchInput.addEventListener('input', renderInventory);
    categoryFilter.addEventListener('change', renderInventory);
    stockFilter.addEventListener('change', renderInventory);

    document.getElementById('resetDemo').addEventListener('click', function() {
        demoIngredients = JSON.parse(JSON.stringify(originalIngredients));
        searchInput.value = '';
        categoryFilter.value = 'All';
        stockFilter.value = 'All';
        renderInventory();
    });

    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });

    renderInventory();
</script>

</body>
</html>
