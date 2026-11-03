
<?php
// UrbanBites Admin - Menu Management
// Front-end demonstration only. No database is required.

$foodItems = [
    [
        'id' => 1,
        'name' => 'Classic Urban Burger',
        'category' => 'Burgers',
        'price' => 1850,
        'description' => 'Juicy beef patty, fresh lettuce, tomato and our signature sauce.',
        'image' => '../assets/images/burger.jpg',
        'popular' => true,
        'available' => true
    ],
    [
        'id' => 2,
        'name' => 'Double Cheese Burger',
        'category' => 'Burgers',
        'price' => 2450,
        'description' => 'Double beef patty with melted cheese and crispy onions.',
        'image' => '../assets/images/burger.jpg',
        'popular' => true,
        'available' => true
    ],
    [
        'id' => 3,
        'name' => 'Spicy Chicken Pizza',
        'category' => 'Pizza',
        'price' => 2600,
        'description' => 'Chicken, mozzarella, peppers and our spicy pizza sauce.',
        'image' => '../assets/images/pizza.jpg',
        'popular' => true,
        'available' => true
    ],
    [
        'id' => 4,
        'name' => 'Margherita Pizza',
        'category' => 'Pizza',
        'price' => 1900,
        'description' => 'Classic pizza topped with tomato, mozzarella and basil.',
        'image' => '../assets/images/pizza.jpg',
        'popular' => false,
        'available' => true
    ],
    [
        'id' => 5,
        'name' => 'Creamy Chicken Pasta',
        'category' => 'Pasta',
        'price' => 2200,
        'description' => 'Creamy pasta with tender chicken and parmesan cheese.',
        'image' => '../assets/images/pasta.jpg',
        'popular' => false,
        'available' => true
    ],
    [
        'id' => 6,
        'name' => 'BBQ Chicken',
        'category' => 'Chicken',
        'price' => 2750,
        'description' => 'Grilled chicken glazed with smoky barbecue sauce.',
        'image' => '../assets/images/chicken.jpg',
        'popular' => true,
        'available' => false
    ],
    [
        'id' => 7,
        'name' => 'Crispy French Fries',
        'category' => 'Sides',
        'price' => 750,
        'description' => 'Golden crispy fries seasoned with our special spice mix.',
        'image' => '../assets/images/fries.jpg',
        'popular' => false,
        'available' => true
    ],
    [
        'id' => 8,
        'name' => 'Fresh Garden Salad',
        'category' => 'Sides',
        'price' => 950,
        'description' => 'Fresh greens, tomatoes, cucumber and house dressing.',
        'image' => '../assets/images/salad.jpg',
        'popular' => false,
        'available' => true
    ],
    [
        'id' => 9,
        'name' => 'Chocolate Brownie',
        'category' => 'Desserts',
        'price' => 850,
        'description' => 'Rich chocolate brownie with a soft, fudgy centre.',
        'image' => '../assets/images/dessert.jpg',
        'popular' => false,
        'available' => true
    ],
    [
        'id' => 10,
        'name' => 'Fresh Lime Mojito',
        'category' => 'Drinks',
        'price' => 650,
        'description' => 'Refreshing lime drink with mint and crushed ice.',
        'image' => '../assets/images/drinks.jpg',
        'popular' => false,
        'available' => true
    ],
    [
        'id' => 11,
        'name' => 'Chilled Cola',
        'category' => 'Drinks',
        'price' => 450,
        'description' => 'A chilled, refreshing cola served with ice.',
        'image' => '../assets/images/drinks.jpg',
        'popular' => false,
        'available' => true
    ]
];

$categories = [
    'Burgers',
    'Pizza',
    'Pasta',
    'Chicken',
    'Sides',
    'Desserts',
    'Drinks'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Menu Management | UrbanBites Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --orange: #f47721;
            --orange-light: #fff0e5;
            --dark: #20232e;
            --muted: #858b99;
            --background: #f6f7fb;
            --border: #eceef3;
            --green: #168454;
            --red: #d94848;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: #292d38;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        /* Sidebar */

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1000;
            width: 250px;
            padding: 24px 16px;
            overflow-y: auto;
            color: white;
            background: var(--dark);
            transition: transform .25s ease;
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

        .brand:hover {
            color: white;
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

        /* Main layout */

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

        .page-content {
            padding: 28px 30px 40px;
        }

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
            line-height: 1.7;
        }

        /* Summary cards */

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
        .stat-green { color: #239c67; background: #e6f8ef; }
        .stat-purple { color: #8154d8; background: #f0eaff; }

        /* Panels and controls */

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

        .btn-orange {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 16px;
            color: white;
            background: var(--orange);
            border: 1px solid var(--orange);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-orange:hover,
        .btn-orange:focus {
            color: white;
            background: #dd6415;
            border-color: #dd6415;
        }

        .btn-light-custom {
            padding: 10px 14px;
            background: white;
            border: 1px solid #e7e9ef;
            border-radius: 10px;
            font-size: 12px;
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
            border-color: var(--orange);
        }

        .form-label {
            margin-bottom: 7px;
            color: #454a58;
            font-size: 12px;
            font-weight: 700;
        }

        textarea.form-control {
            min-height: 95px;
            resize: vertical;
        }

        .search-wrap {
            position: relative;
            width: 100%;
            max-width: 340px;
        }

        .search-wrap span {
            position: absolute;
            top: 11px;
            left: 13px;
            color: #9298a5;
        }

        .search-wrap input {
            padding-left: 38px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 20px;
        }

        .toolbar-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .toolbar-filters .form-select {
            min-width: 150px;
        }

        /* Menu table */

        .table-responsive {
            overflow-x: auto;
        }

        .menu-table {
            min-width: 850px;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .menu-table thead th {
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

        .menu-table tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid #f0f1f5;
            font-size: 12px;
        }

        .menu-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .food-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 220px;
        }

        .food-image {
            flex-shrink: 0;
            width: 57px;
            height: 57px;
            object-fit: cover;
            background: #f4f4f4;
            border-radius: 12px;
        }

        .food-name {
            color: #292d38;
            font-weight: 800;
        }

        .food-description {
            max-width: 230px;
            margin-top: 5px;
            overflow: hidden;
            color: #9298a5;
            font-size: 10px;
            line-height: 1.5;
            text-overflow: ellipsis;
        }

        .category-badge {
            display: inline-block;
            padding: 6px 9px;
            color: #60697a;
            background: #f1f3f7;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 700;
        }

        .price {
            color: #292d38;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        .availability-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 9px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .availability-badge::before {
            width: 6px;
            height: 6px;
            background: currentColor;
            border-radius: 50%;
            content: "";
        }

        .available {
            color: #168454;
            background: #def7e9;
        }

        .unavailable {
            color: #c13d3d;
            background: #ffebeb;
        }

        .popular-badge {
            display: inline-block;
            margin-top: 5px;
            padding: 4px 6px;
            color: #b76814;
            background: #fff3db;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 800;
        }

        .action-group {
            display: flex;
            gap: 7px;
        }

        .action-btn {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            background: white;
            border: 1px solid #e8eaf0;
            border-radius: 9px;
            transition: .2s;
        }

        .action-btn:hover {
            color: var(--orange);
            border-color: #ffd2b4;
        }

        .action-btn.delete:hover {
            color: var(--red);
            border-color: #ffc9c9;
            background: #fff4f4;
        }

        .empty-state {
            display: none;
            padding: 45px 15px;
            color: var(--muted);
            text-align: center;
        }

        .empty-state div {
            margin-bottom: 10px;
            font-size: 36px;
        }

        .table-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 20px;
        }

        .table-footer small {
            color: var(--muted);
            font-size: 11px;
        }

        /* Add/edit modal */

        .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: 18px;
        }

        .modal-header {
            padding: 22px;
            border-bottom-color: var(--border);
        }

        .modal-header .modal-title {
            font-size: 19px;
            font-weight: 800;
        }

        .modal-body {
            padding: 23px;
        }

        .modal-footer {
            padding: 17px 23px;
            border-top-color: var(--border);
        }

        .image-preview {
            display: grid;
            place-items: center;
            width: 100%;
            height: 150px;
            overflow: hidden;
            color: #a0a5b0;
            background: #f6f7fb;
            border: 1px dashed #dce0e8;
            border-radius: 12px;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview span {
            font-size: 12px;
        }

        .form-hint {
            margin-top: 6px;
            color: #9298a5;
            font-size: 10px;
            line-height: 1.5;
        }

        .sidebar-toggle {
            display: none;
        }

        .demo-note {
            padding: 14px 16px;
            color: #8c6417;
            background: #fff8e8;
            border: 1px solid #f6e6b9;
            border-radius: 12px;
            font-size: 11px;
            line-height: 1.7;
        }

        @media (max-width: 1200px) {
            .sidebar {
                width: 225px;
            }

            .main-content {
                margin-left: 225px;
            }

            .page-content {
                padding: 24px 20px;
            }

            .topbar {
                padding: 16px 20px;
            }

            .toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .search-wrap {
                max-width: none;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: inline-grid;
            }
        }

        @media (max-width: 560px) {
            .topbar {
                padding: 13px 15px;
            }

            .topbar h1 {
                font-size: 17px;
            }

            .topbar p {
                font-size: 10px;
            }

            .page-content {
                padding: 20px 13px 30px;
            }

            .page-heading {
                font-size: 23px;
            }

            .panel {
                padding: 15px;
            }

            .panel-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .toolbar-filters {
                display: grid;
                grid-template-columns: 1fr;
            }

            .toolbar-filters .form-select {
                width: 100%;
            }

            .modal-body {
                padding: 17px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <a href="index.php" class="brand">
        <img src="../../../assets/img/main_logo.png" width="55px" height="45px" >
        <span>
            UrbanBites
            <small>ADMIN PANEL</small>
        </span>
    </a>

    <div class="nav-heading">Main Menu</div>

    <nav class="nav flex-column">
        <a class="nav-link" href="index.php">
            <span class="nav-icon">▦</span> Dashboard
        </a>

        <a class="nav-link" href="orders.php">
            <span class="nav-icon">🛍</span> Orders Management
        </a>

        <a class="nav-link active" href="menu_management.php">
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

<!-- Main content -->
<main class="main-content">

    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="icon-button sidebar-toggle"
                    id="sidebarToggle"
                    aria-label="Toggle sidebar">☰</button>

            <div>
                <h1>Menu Management</h1>
                <p>Manage food items, prices and menu availability.</p>
            </div>
        </div>

        <div class="top-actions">
            <button class="icon-button" type="button" title="Notifications">♧</button>

            <div class="admin-avatar">AD</div>

            <div class="d-none d-sm-block">
                <div style="font-size:12px;font-weight:800;">Admin User</div>
                <div style="font-size:10px;color:#858b99;">Administrator</div>
            </div>
        </div>
    </header>

    <section class="page-content">

        <div class="eyebrow">Restaurant Operations</div>

        <h2 class="page-heading">Manage Your Menu</h2>

        <p class="page-description">
            Add new dishes, update prices, manage availability and keep the
            UrbanBites restaurant menu organized.
        </p>

        <!-- Summary cards -->
        <div class="row g-3">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-orange">🍽</div>
                    <div>
                        <div class="stat-label">Total Food Items</div>
                        <div class="stat-number" id="totalItems">11</div>
                        <div class="stat-note">All registered menu items</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-green">✓</div>
                    <div>
                        <div class="stat-label">Available Items</div>
                        <div class="stat-number" id="availableItems">10</div>
                        <div class="stat-note">Currently on the menu</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-purple">◷</div>
                    <div>
                        <div class="stat-label">Unavailable Items</div>
                        <div class="stat-number" id="unavailableItems">1</div>
                        <div class="stat-note">Temporarily unavailable</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon stat-blue">▦</div>
                    <div>
                        <div class="stat-label">Categories</div>
                        <div class="stat-number" id="categoryCount">7</div>
                        <div class="stat-note">Food and drink categories</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu table -->
        <section class="panel">

            <div class="panel-heading">
                <div>
                    <h2>Food Items</h2>
                    <p>View and manage the items listed in your restaurant menu.</p>
                </div>

                <button class="btn-orange" type="button" id="addFoodBtn">
                    <span style="font-size:17px;">+</span> Register New Food
                </button>
            </div>

            <div class="toolbar">
                <div class="search-wrap">
                    <span>⌕</span>
                    <input type="search"
                           class="form-control"
                           id="foodSearch"
                           placeholder="Search food name or description...">
                </div>

                <div class="toolbar-filters">
                    <select class="form-select" id="categoryFilter"
                            aria-label="Filter by category">
                        <option value="All">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select class="form-select" id="availabilityFilter"
                            aria-label="Filter by availability">
                        <option value="All">All Availability</option>
                        <option value="Available">Available</option>
                        <option value="Unavailable">Unavailable</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table menu-table">
                    <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Availability</th>
                        <th>Popular</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody id="foodTableBody">
                    <!-- JavaScript renders the food items here. -->
                    </tbody>
                </table>
            </div>

            <div class="empty-state" id="emptyState">
                <div>🍽</div>
                <strong>No food items found</strong>
                <p class="small mt-2 mb-0">
                    Try another search or add a new menu item.
                </p>
            </div>

            <div class="table-footer">
                <small id="resultCount">Showing 11 food items</small>

                <button class="btn-light-custom" type="button" id="resetDemoBtn">
                    Reset Demo Data
                </button>
            </div>
        </section>

        <div class="demo-note mt-4">
            <strong>Demo mode:</strong>
            Adding, editing, deleting or changing food availability only updates
            the temporary page data. Changes are not stored in a database and
            will reset when the page is refreshed.
        </div>

        <div class="text-center text-secondary mt-4" style="font-size:11px;">
            © <?= date('Y') ?> UrbanBites Restaurant Management Demo
        </div>

    </section>
</main>

<!-- Add/Edit Food Modal -->
<div class="modal fade" id="foodModal" tabindex="-1"
     aria-labelledby="foodModalTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <div class="eyebrow mb-1">Menu Configuration</div>
                    <h2 class="modal-title" id="foodModalTitle">Register New Food</h2>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="foodForm">
                <div class="modal-body">

                    <input type="hidden" id="foodId">

                    <div class="row g-3">

                        <div class="col-md-7">
                            <label class="form-label" for="foodName">Food Name *</label>
                            <input type="text"
                                   class="form-control"
                                   id="foodName"
                                   maxlength="80"
                                   placeholder="e.g. Spicy Chicken Burger"
                                   required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label" for="foodCategory">Category *</label>
                            <select class="form-select" id="foodCategory" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="foodPrice">
                                Price (LKR) *
                            </label>

                            <div class="input-group">
                                <span class="input-group-text"
                                      style="border-radius:10px 0 0 10px;font-size:12px;">
                                    Rs.
                                </span>

                                <input type="number"
                                       class="form-control"
                                       id="foodPrice"
                                       min="1"
                                       max="1000000"
                                       step="1"
                                       placeholder="1850"
                                       required
                                       style="border-radius:0 10px 10px 0;">
                            </div>

                            <div class="form-hint">
                                Enter the selling price in Sri Lankan Rupees.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="foodAvailability">
                                Availability
                            </label>

                            <select class="form-select" id="foodAvailability">
                                <option value="Available">Available</option>
                                <option value="Unavailable">Unavailable</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="foodDescription">
                                Food Description *
                            </label>

                            <textarea class="form-control"
                                      id="foodDescription"
                                      maxlength="300"
                                      placeholder="Describe the ingredients and taste of this dish..."
                                      required></textarea>
                        </div>

                        <div class="col-md-7">
                            <label class="form-label" for="foodImage">
                                Food Image Path or URL
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="foodImage"
                                   placeholder="../assets/images/burger.jpg">

                            <div class="form-hint">
                                Use an existing image path or a valid image URL.
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Image Preview</label>

                            <div class="image-preview" id="imagePreview">
                                <span>No image selected</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="foodPopular">

                                <label class="form-check-label"
                                       for="foodPopular"
                                       style="font-size:12px;">
                                    Mark as a Popular Item
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn-light-custom"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn-orange" id="saveFoodBtn">
                        Register Food Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1"
     aria-labelledby="deleteModalTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h2 class="modal-title" id="deleteModalTitle">
                    Remove Food Item?
                </h2>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p id="deleteConfirmationText"
                   style="font-size:13px;line-height:1.7;">
                    Are you sure you want to remove this item?
                </p>

                <div class="alert alert-warning mb-0" style="font-size:11px;">
                    This only removes the item from the current demonstration.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn-light-custom"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button"
                        class="btn btn-danger"
                        id="confirmDeleteBtn"
                        style="border-radius:10px;font-size:12px;">
                    Remove Food
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Toast notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="demoToast"
         class="toast align-items-center border-0 text-bg-dark"
         role="status"
         aria-live="polite"
         aria-atomic="true">

        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                Menu updated successfully.
            </div>

            <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"
                    aria-label="Close"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // PHP supplies the initial sample data.
    const originalFoodItems = <?= json_encode(
        $foodItems,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ) ?>;

    // Working copy: no database or permanent storage.
    let foodItems = JSON.parse(JSON.stringify(originalFoodItems));

    let nextFoodId = Math.max(0, ...foodItems.map(item => Number(item.id))) + 1;
    let editingFoodId = null;
    let deletingFoodId = null;

    const tableBody = document.getElementById('foodTableBody');
    const foodModal = new bootstrap.Modal(document.getElementById('foodModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const demoToast = new bootstrap.Toast(document.getElementById('demoToast'));

    const searchInput = document.getElementById('foodSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const availabilityFilter = document.getElementById('availabilityFilter');

    const foodForm = document.getElementById('foodForm');
    const imageInput = document.getElementById('foodImage');
    const imagePreview = document.getElementById('imagePreview');

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

    function formatPrice(price) {
        return 'Rs. ' + Number(price).toLocaleString('en-US');
    }

    function showToast(message) {
        document.getElementById('toastMessage').textContent = message;
        demoToast.show();
    }

    function placeholderImage() {
        return 'data:image/svg+xml;charset=UTF-8,' +
            encodeURIComponent(
                '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="100">' +
                '<rect width="100%" height="100%" fill="#f1f2f6"/>' +
                '<text x="50%" y="50%" font-size="30" text-anchor="middle" dominant-baseline="middle">🍽</text>' +
                '</svg>'
            );
    }

    function safeImageSource(source) {
        const value = String(source || '').trim();

        // Allow relative paths, HTTP(S) URLs and local data previews.
        if (/^(https?:\/\/|data:image\/|\.{0,2}\/|assets\/)/i.test(value)) {
            return value;
        }

        return '';
    }

    function imageElementSource(source) {
        return safeImageSource(source) || placeholderImage();
    }

    function updateSummary() {
        const available = foodItems.filter(item => item.available).length;
        const unavailable = foodItems.length - available;
        const categoryCount = new Set(
            foodItems.map(item => item.category)
        ).size;

        document.getElementById('totalItems').textContent = foodItems.length;
        document.getElementById('availableItems').textContent = available;
        document.getElementById('unavailableItems').textContent = unavailable;
        document.getElementById('categoryCount').textContent = categoryCount;
    }

    function renderFoodItems() {
        const search = searchInput.value.trim().toLowerCase();
        const category = categoryFilter.value;
        const availability = availabilityFilter.value;

        const filteredItems = foodItems.filter(item => {
            const matchesSearch =
                item.name.toLowerCase().includes(search) ||
                item.description.toLowerCase().includes(search);

            const matchesCategory =
                category === 'All' || item.category === category;

            const matchesAvailability =
                availability === 'All' ||
                (availability === 'Available' && item.available) ||
                (availability === 'Unavailable' && !item.available);

            return matchesSearch && matchesCategory && matchesAvailability;
        });

        tableBody.innerHTML = filteredItems.map(item => `
            <tr>
                <td>
                    <div class="food-cell">
                        <img class="food-image"
                             src="${escapeHtml(imageElementSource(item.image))}"
                             alt="${escapeHtml(item.name)}"
                             onerror="this.onerror=null;this.src=placeholderImage()">

                        <div>
                            <div class="food-name">${escapeHtml(item.name)}</div>
                            <div class="food-description">
                                ${escapeHtml(item.description)}
                            </div>
                        </div>
                    </div>
                </td>

                <td>
                    <span class="category-badge">
                        ${escapeHtml(item.category)}
                    </span>
                </td>

                <td>
                    <span class="price">${formatPrice(item.price)}</span>
                </td>

                <td>
                    <span class="availability-badge ${item.available ? 'available' : 'unavailable'}">
                        ${item.available ? 'Available' : 'Unavailable'}
                    </span>
                </td>

                <td>
                    ${item.popular
                        ? '<span class="popular-badge">★ POPULAR</span>'
                        : '<span class="text-secondary">—</span>'}
                </td>

                <td>
                    <div class="action-group">
                        <button type="button"
                                class="action-btn edit-food"
                                data-id="${item.id}"
                                title="Edit food item"
                                aria-label="Edit ${escapeHtml(item.name)}">✎</button>

                        <button type="button"
                                class="action-btn toggle-availability"
                                data-id="${item.id}"
                                title="${item.available ? 'Mark unavailable' : 'Mark available'}"
                                aria-label="Toggle availability for ${escapeHtml(item.name)}">
                            ${item.available ? '✓' : '◷'}
                        </button>

                        <button type="button"
                                class="action-btn delete delete-food"
                                data-id="${item.id}"
                                title="Remove food item"
                                aria-label="Remove ${escapeHtml(item.name)}">🗑</button>
                    </div>
                </td>
            </tr>
        `).join('');

        document.getElementById('emptyState').style.display =
            filteredItems.length === 0 ? 'block' : 'none';

        document.getElementById('resultCount').textContent =
            `Showing ${filteredItems.length} of ${foodItems.length} food items`;

        updateSummary();
    }

    function resetFoodForm() {
        foodForm.reset();
        document.getElementById('foodId').value = '';
        imagePreview.innerHTML = '<span>No image selected</span>';
    }

    function previewImage(source) {
        const safeSource = safeImageSource(source);

        if (!safeSource) {
            imagePreview.innerHTML = '<span>No valid image path or URL</span>';
            return;
        }

        imagePreview.innerHTML = '';

        const img = document.createElement('img');
        img.alt = 'Food image preview';
        img.src = safeSource;

        img.onerror = function () {
            imagePreview.innerHTML = '<span>Image could not be loaded</span>';
        };

        imagePreview.appendChild(img);
    }

    function openAddFoodModal() {
        editingFoodId = null;
        resetFoodForm();

        document.getElementById('foodModalTitle').textContent = 'Register New Food';
        document.getElementById('saveFoodBtn').textContent = 'Register Food Item';
        document.getElementById('foodAvailability').value = 'Available';

        foodModal.show();
    }

    function openEditFoodModal(id) {
        const item = foodItems.find(food => food.id === id);

        if (!item) return;

        editingFoodId = id;
        resetFoodForm();

        document.getElementById('foodModalTitle').textContent = 'Edit Food Item';
        document.getElementById('saveFoodBtn').textContent = 'Save Changes';

        document.getElementById('foodId').value = item.id;
        document.getElementById('foodName').value = item.name;
        document.getElementById('foodCategory').value = item.category;
        document.getElementById('foodPrice').value = item.price;
        document.getElementById('foodDescription').value = item.description;
        document.getElementById('foodImage').value = item.image;
        document.getElementById('foodAvailability').value =
            item.available ? 'Available' : 'Unavailable';
        document.getElementById('foodPopular').checked = item.popular;

        previewImage(item.image);

        foodModal.show();
    }

    document.getElementById('addFoodBtn').addEventListener('click', openAddFoodModal);

    // Register a new item or edit an existing item.
    foodForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const name = document.getElementById('foodName').value.trim();
        const category = document.getElementById('foodCategory').value;
        const price = Number(document.getElementById('foodPrice').value);
        const description = document.getElementById('foodDescription').value.trim();
        const image = document.getElementById('foodImage').value.trim();
        const available =
            document.getElementById('foodAvailability').value === 'Available';
        const popular = document.getElementById('foodPopular').checked;

        if (!name || !category || !description ||
            !Number.isFinite(price) || price <= 0 || price > 1000000) {
            showToast('Please complete all required fields with a valid price.');
            return;
        }

        if (editingFoodId !== null) {
            const index = foodItems.findIndex(item => item.id === editingFoodId);

            if (index === -1) return;

            foodItems[index] = {
                ...foodItems[index],
                name,
                category,
                price,
                description,
                image,
                available,
                popular
            };

            showToast('Food item updated successfully.');
        } else {
            foodItems.unshift({
                id: nextFoodId++,
                name,
                category,
                price,
                description,
                image,
                available,
                popular
            });

            showToast('New food item registered successfully.');
        }

        foodModal.hide();
        renderFoodItems();
    });

    // Update image preview when the image path changes.
    imageInput.addEventListener('input', function () {
        previewImage(this.value.trim());
    });

    // Handle edit, availability and remove actions.
    tableBody.addEventListener('click', function (event) {
        const editButton = event.target.closest('.edit-food');
        const toggleButton = event.target.closest('.toggle-availability');
        const deleteButton = event.target.closest('.delete-food');

        if (editButton) {
            openEditFoodModal(Number(editButton.dataset.id));
            return;
        }

        if (toggleButton) {
            const id = Number(toggleButton.dataset.id);
            const item = foodItems.find(food => food.id === id);

            if (!item) return;

            item.available = !item.available;

            showToast(
                item.name + ' is now ' +
                (item.available ? 'available.' : 'unavailable.')
            );

            renderFoodItems();
            return;
        }

        if (deleteButton) {
            deletingFoodId = Number(deleteButton.dataset.id);

            const item = foodItems.find(food => food.id === deletingFoodId);

            if (!item) return;

            document.getElementById('deleteConfirmationText').textContent =
                'Are you sure you want to remove "' + item.name +
                '" from the demonstration menu?';

            deleteModal.show();
        }
    });

    // Confirm deletion.
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deletingFoodId === null) return;

        const item = foodItems.find(food => food.id === deletingFoodId);
        const itemName = item ? item.name : 'Food item';

        foodItems = foodItems.filter(food => food.id !== deletingFoodId);
        deletingFoodId = null;

        deleteModal.hide();
        renderFoodItems();

        showToast(itemName + ' removed from the demonstration menu.');
    });

    document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function () {
        deletingFoodId = null;
    });

    // Search and filter controls.
    searchInput.addEventListener('input', renderFoodItems);
    categoryFilter.addEventListener('change', renderFoodItems);
    availabilityFilter.addEventListener('change', renderFoodItems);

    // Reset all demo data to the original PHP sample items.
    document.getElementById('resetDemoBtn').addEventListener('click', function () {
        foodItems = JSON.parse(JSON.stringify(originalFoodItems));

        nextFoodId = Math.max(0, ...foodItems.map(item => Number(item.id))) + 1;

        searchInput.value = '';
        categoryFilter.value = 'All';
        availabilityFilter.value = 'All';

        renderFoodItems();
        showToast('Demo menu restored to its original data.');
    });

    // Responsive sidebar.
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Initial rendering.
    renderFoodItems();
</script>

</body>
</html>
