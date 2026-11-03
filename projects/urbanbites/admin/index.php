<?php
// UrbanBites Admin Dashboard
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>UrbanBites | Admin Dashboard</title>

    <link
        rel="stylesheet"
        href="../../../assets/bootstrap/bootstrap.min.css">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Trebuchet MS", Arial, sans-serif;
            background: #f5f6f8;
            color: #222;
        }

        /* ==============================
           SIDEBAR
        ============================== */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #171717;
            color: white;
            z-index: 1000;
            transition: 0.3s;
            overflow-y: auto;
        }

        .brand {
            padding: 25px 24px;
            border-bottom: 1px solid #303030;
        }

        .brand h2 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            color: #e63946;
        }

        .brand small {
            color: #999;
        }

        .admin-profile {
            padding: 22px;
            border-bottom: 1px solid #303030;
        }

        .admin-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #e63946;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 18px;
        }

        .admin-name {
            font-weight: 700;
            margin-bottom: 2px;
        }

        .admin-role {
            color: #999;
            font-size: 13px;
        }

        .sidebar-menu {
            padding: 20px 12px;
        }

        .menu-title {
            color: #777;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 12px;
            font-weight: 800;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px 15px;
            margin-bottom: 4px;
            border-radius: 9px;
            color: #bbb;
            text-decoration: none;
            transition: 0.25s;
            cursor: pointer;
        }

        .sidebar-link:hover {
            background: #292929;
            color: white;
        }

        .sidebar-link.active {
            background: #e63946;
            color: white;
        }

        .sidebar-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-badge {
            margin-left: auto;
            background: #e63946;
            color: white;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 11px;
        }

        .sidebar-link.active .sidebar-badge {
            background: white;
            color: #e63946;
        }

        /* ==============================
           MAIN CONTENT
        ============================== */

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        /* ==============================
           TOPBAR
        ============================== */

        .topbar {
            height: 75px;
            background: white;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .page-heading h1 {
            margin: 0;
            font-size: 23px;
            font-weight: 800;
        }

        .page-heading p {
            margin: 3px 0 0;
            color: #888;
            font-size: 13px;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification {
            position: relative;
            font-size: 21px;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            width: 17px;
            height: 17px;
            background: #e63946;
            color: white;
            border-radius: 50%;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .view-site {
            border: 1px solid #ddd;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            font-size: 13px;
            font-weight: 700;
        }

        .view-site:hover {
            background: #f5f5f5;
        }

        .mobile-menu-btn {
            display: none;
            border: none;
            background: none;
            font-size: 24px;
        }

        /* ==============================
           DASHBOARD AREA
        ============================== */

        .dashboard {
            padding: 30px;
        }

        .welcome-box {
            background:
                linear-gradient(
                    120deg,
                    #191919,
                    #302326
                );

            color: white;
            border-radius: 16px;
            padding: 28px 30px;
            margin-bottom: 25px;
        }

        .welcome-box h2 {
            margin: 0 0 7px;
            font-size: 25px;
            font-weight: 800;
        }

        .welcome-box p {
            margin: 0;
            color: #bbb;
        }

        .welcome-date {
            color: #e63946;
            font-weight: 700;
        }

        /* ==============================
           STAT CARDS
        ============================== */

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 23px;
            border: 1px solid #eee;
            height: 100%;
            transition: 0.25s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .icon-red {
            background: #fff0f1;
        }

        .icon-green {
            background: #eaf8f0;
        }

        .icon-orange {
            background: #fff5e6;
        }

        .icon-blue {
            background: #edf5ff;
        }

        .stat-label {
            color: #888;
            font-size: 13px;
            margin-top: 18px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            margin-top: 3px;
        }

        .stat-change {
            font-size: 12px;
            margin-top: 7px;
        }

        .positive {
            color: #198754;
        }

        .warning {
            color: #f08c00;
        }

        /* ==============================
           CONTENT CARDS
        ============================== */

        .content-card {
            background: white;
            border-radius: 14px;
            border: 1px solid #eee;
            overflow: hidden;
            height: 100%;
        }

        .card-header-custom {
            padding: 20px 22px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-custom h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
        }

        .card-header-custom a {
            color: #e63946;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        .card-body-custom {
            padding: 22px;
        }

        /* ==============================
           ORDERS TABLE
        ============================== */

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th {
            color: #888;
            font-size: 11px;
            text-transform: uppercase;
            padding: 12px;
            background: #fafafa;
            white-space: nowrap;
        }

        .orders-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: middle;
        }

        .orders-table tr:last-child td {
            border-bottom: none;
        }

        .order-id {
            font-weight: 800;
            color: #333;
        }

        .customer-name {
            font-weight: 700;
        }

        .order-items {
            color: #888;
            font-size: 12px;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
        }

        .status-pending {
            background: #fff3cd;
            color: #997404;
        }

        .status-preparing {
            background: #cff4fc;
            color: #087990;
        }

        .status-ready {
            background: #d1e7dd;
            color: #146c43;
        }

        .status-delivered {
            background: #e2e3e5;
            color: #41464b;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #b02a37;
        }

        /* ==============================
           QUICK ACTIONS
        ============================== */

        .quick-action {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px;
            border: 1px solid #eee;
            border-radius: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            transition: 0.2s;
        }

        .quick-action:hover {
            border-color: #e63946;
            background: #fff8f8;
            color: #e63946;
        }

        .quick-icon {
            width: 42px;
            height: 42px;
            border-radius: 9px;
            background: #fff0f1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quick-text strong {
            display: block;
            font-size: 14px;
        }

        .quick-text small {
            color: #888;
        }

        /* ==============================
           POPULAR ITEMS
        ============================== */

        .popular-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 0;
            border-bottom: 1px solid #eee;
        }

        .popular-item:last-child {
            border-bottom: none;
        }

        .food-image {
            width: 50px;
            height: 50px;
            border-radius: 9px;
            object-fit: cover;
            background: #eee;
        }

        .food-info {
            flex: 1;
        }

        .food-info strong {
            display: block;
            font-size: 14px;
        }

        .food-info small {
            color: #888;
        }

        .food-sales {
            font-weight: 800;
            font-size: 13px;
        }

        /* ==============================
           ALERT
        ============================== */

        .stock-alert {
            background: #fff8e8;
            border: 1px solid #ffe4a8;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 10px;
            display: flex;
            gap: 12px;
        }

        .stock-alert-icon {
            font-size: 20px;
        }

        .stock-alert strong {
            display: block;
            font-size: 14px;
        }

        .stock-alert small {
            color: #888;
        }

        /* ==============================
           FOOTER
        ============================== */

        .dashboard-footer {
            padding: 25px 30px;
            color: #999;
            font-size: 12px;
            text-align: center;
        }

        /* ==============================
           RESPONSIVE
        ============================== */

        @media (max-width: 991px) {

            .sidebar {
                left: -250px;
            }

            .sidebar.open {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

        }

        @media (max-width: 768px) {

            .topbar {
                padding: 0 18px;
            }

            .dashboard {
                padding: 20px 15px;
            }

            .welcome-box {
                padding: 22px;
            }

            .view-site {
                display: none;
            }

            .orders-wrapper {
                overflow-x: auto;
            }

            .orders-table {
                min-width: 750px;
            }

        }

    </style>

</head>


<body>


<!-- ==============================
     SIDEBAR
============================== -->

<aside class="sidebar" id="sidebar">

    <div class="brand">
        <img src="../../../assets/img/main_logo.png" width="55px" height="45px" >
        <h2>UrbanBites</h2>

        <small>Restaurant Management</small>

    </div>


    <div class="admin-profile d-flex align-items-center gap-3">

        <div class="admin-avatar">
            A
        </div>

        <div>

            <div class="admin-name">
                Admin User
            </div>

            <div class="admin-role">
                Administrator
            </div>

        </div>

    </div>


    <div class="sidebar-menu">

        <div class="menu-title">
            Main
        </div>


        <a
            href="index.php"
            class="sidebar-link active">

            <span class="sidebar-icon">📊</span>

            Dashboard

        </a>


        <a
            href="orders.php"
            class="sidebar-link">

            <span class="sidebar-icon">🛒</span>

            Orders

            <span class="sidebar-badge">
                8
            </span>

        </a>


        <a
            href="menu_management.php"
            class="sidebar-link">

            <span class="sidebar-icon">🍔</span>

            Menu Management

        </a>


        <a
            href="customers.php"
            class="sidebar-link">

            <span class="sidebar-icon">👥</span>

            Customers

        </a>


        <div class="menu-title mt-3">
            Management
        </div>


        <a
            href="inventory.php"
            class="sidebar-link">

            <span class="sidebar-icon">📦</span>

            Inventory

        </a>


        <a
            href="#"
            class="sidebar-link">

            <span class="sidebar-icon">🚚</span>

            Delivery

        </a>


        <a
            href="#"
            class="sidebar-link">

            <span class="sidebar-icon">⭐</span>

            Reviews

        </a>


        <a
            href="#"
            class="sidebar-link">

            <span class="sidebar-icon">🎟️</span>

            Promotions

        </a>


        <div class="menu-title mt-3">
            Reports
        </div>


        <a
            href="#"
            class="sidebar-link">

            <span class="sidebar-icon">📈</span>

            Sales Reports

        </a>


        <a
            href="#"
            class="sidebar-link">

            <span class="sidebar-icon">💰</span>

            Revenue

        </a>


        <div class="menu-title mt-3">
            System
        </div>


        <a
            href="#"
            class="sidebar-link">

            <span class="sidebar-icon">⚙️</span>

            Settings

        </a>


        <a
            href="#"
            class="sidebar-link">

            <span class="sidebar-icon">🚪</span>

            Logout

        </a>

    </div>

</aside>


<!-- ==============================
     MAIN CONTENT
============================== -->

<main class="main-content">


    <!-- TOPBAR -->

    <header class="topbar">

        <div class="d-flex align-items-center gap-3">

            <button
                class="mobile-menu-btn"
                onclick="toggleSidebar()">

                ☰

            </button>


            <div class="page-heading">

                <h1>Dashboard</h1>

                <p>
                    Monitor and manage your restaurant
                </p>

            </div>

        </div>


        <div class="topbar-actions">

            <div class="notification">

                🔔

                <span class="notification-badge">
                    3
                </span>

            </div>


            <a
                href="../index.php"
                class="view-site">

                View Website →

            </a>

        </div>

    </header>


    <!-- DASHBOARD -->

    <section class="dashboard">


        <!-- WELCOME -->

        <div class="welcome-box">

            <h2>
                Good afternoon, Admin 👋
            </h2>

            <p>
                Here's what's happening with UrbanBites today.
                <span class="welcome-date">
                    October 08, 2026
                </span>
            </p>

        </div>


        <!-- STATISTICS -->

        <div class="row g-4 mb-4">


            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon icon-red">
                            🛒
                        </div>

                        <span class="positive">
                            ↑ 12.5%
                        </span>

                    </div>

                    <div class="stat-label">
                        Today's Orders
                    </div>

                    <div class="stat-value">
                        128
                    </div>

                    <div class="stat-change positive">
                        Compared with yesterday
                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon icon-green">
                            💰
                        </div>

                        <span class="positive">
                            ↑ 8.4%
                        </span>

                    </div>

                    <div class="stat-label">
                        Today's Revenue
                    </div>

                    <div class="stat-value">
                        Rs. 486K
                    </div>

                    <div class="stat-change positive">
                        Compared with yesterday
                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon icon-orange">
                            ⏳
                        </div>

                        <span class="warning">
                            Action needed
                        </span>

                    </div>

                    <div class="stat-label">
                        Pending Orders
                    </div>

                    <div class="stat-value">
                        08
                    </div>

                    <div class="stat-change warning">
                        Waiting for preparation
                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon icon-blue">
                            👥
                        </div>

                        <span class="positive">
                            ↑ 6.2%
                        </span>

                    </div>

                    <div class="stat-label">
                        Total Customers
                    </div>

                    <div class="stat-value">
                        2,845
                    </div>

                    <div class="stat-change positive">
                        This month
                    </div>

                </div>

            </div>

        </div>


        <!-- MAIN GRID -->

        <div class="row g-4 mb-4">


            <!-- RECENT ORDERS -->

            <div class="col-xl-8">

                <div class="content-card">

                    <div class="card-header-custom">

                        <h3>
                            Recent Orders
                        </h3>

                        <a href="orders.php">
                            View All Orders →
                        </a>

                    </div>


                    <div class="orders-wrapper">

                        <table class="orders-table">

                            <thead>

                                <tr>

                                    <th>
                                        Order
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Items
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                                <tr>

                                    <td>
                                        <span class="order-id">
                                            #UB10482
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            02:35 PM
                                        </small>
                                    </td>

                                    <td>
                                        <span class="customer-name">
                                            Kasun Perera
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            Delivery
                                        </small>
                                    </td>

                                    <td>
                                        <span class="order-items">
                                            Classic Burger × 2
                                            <br>
                                            Fries × 1
                                        </span>
                                    </td>

                                    <td>
                                        <strong>
                                            Rs. 4,850
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="status status-preparing">
                                            Preparing
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <span class="order-id">
                                            #UB10481
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            02:21 PM
                                        </small>
                                    </td>

                                    <td>
                                        <span class="customer-name">
                                            Nethmi Silva
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            Pickup
                                        </small>
                                    </td>

                                    <td>
                                        <span class="order-items">
                                            Chicken Pizza × 1
                                            <br>
                                            Coke × 2
                                        </span>
                                    </td>

                                    <td>
                                        <strong>
                                            Rs. 5,200
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="status status-ready">
                                            Ready
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <span class="order-id">
                                            #UB10480
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            02:05 PM
                                        </small>
                                    </td>

                                    <td>
                                        <span class="customer-name">
                                            Mohamed Rizwan
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            Delivery
                                        </small>
                                    </td>

                                    <td>
                                        <span class="order-items">
                                            BBQ Chicken × 1
                                            <br>
                                            Garlic Bread × 2
                                        </span>
                                    </td>

                                    <td>
                                        <strong>
                                            Rs. 3,950
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="status status-pending">
                                            Pending
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <span class="order-id">
                                            #UB10479
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            01:48 PM
                                        </small>
                                    </td>

                                    <td>
                                        <span class="customer-name">
                                            Dinuka Fernando
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            Delivery
                                        </small>
                                    </td>

                                    <td>
                                        <span class="order-items">
                                            Beef Burger × 1
                                            <br>
                                            Pasta × 1
                                        </span>
                                    </td>

                                    <td>
                                        <strong>
                                            Rs. 4,400
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="status status-delivered">
                                            Delivered
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <span class="order-id">
                                            #UB10478
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            01:32 PM
                                        </small>
                                    </td>

                                    <td>
                                        <span class="customer-name">
                                            Amaya Jayasinghe
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            Pickup
                                        </small>
                                    </td>

                                    <td>
                                        <span class="order-items">
                                            Margherita Pizza × 2
                                        </span>
                                    </td>

                                    <td>
                                        <strong>
                                            Rs. 3,800
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="status status-delivered">
                                            Delivered
                                        </span>
                                    </td>

                                </tr>


                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <!-- QUICK ACTIONS -->

            <div class="col-xl-4">

                <div class="content-card">

                    <div class="card-header-custom">

                        <h3>
                            Quick Actions
                        </h3>

                    </div>


                    <div class="card-body-custom">


                        <a
                            href="orders.php"
                            class="quick-action">

                            <div class="quick-icon">
                                🛒
                            </div>

                            <div class="quick-text">

                                <strong>
                                    Manage Orders
                                </strong>

                                <small>
                                    Review and update orders
                                </small>

                            </div>

                        </a>


                        <a
                            href="#"
                            class="quick-action">

                            <div class="quick-icon">
                                🍔
                            </div>

                            <div class="quick-text">

                                <strong>
                                    Manage Menu
                                </strong>

                                <small>
                                    Add or update food items
                                </small>

                            </div>

                        </a>


                        <a
                            href="#"
                            class="quick-action">

                            <div class="quick-icon">
                                ➕
                            </div>

                            <div class="quick-text">

                                <strong>
                                    Add New Food
                                </strong>

                                <small>
                                    Create a new menu item
                                </small>

                            </div>

                        </a>


                        <a
                            href="#"
                            class="quick-action">

                            <div class="quick-icon">
                                📈
                            </div>

                            <div class="quick-text">

                                <strong>
                                    View Reports
                                </strong>

                                <small>
                                    Analyze restaurant performance
                                </small>

                            </div>

                        </a>


                    </div>

                </div>

            </div>

        </div>


        <!-- SECOND ROW -->

        <div class="row g-4">


            <!-- POPULAR ITEMS -->

            <div class="col-xl-5">

                <div class="content-card">

                    <div class="card-header-custom">

                        <h3>
                            Popular Menu Items
                        </h3>

                        <a href="menu.php">
                            Manage →
                        </a>

                    </div>


                    <div class="card-body-custom">


                        <div class="popular-item">

                            <img
                                src="../assets/images/burger.jpg"
                                class="food-image"
                                alt="Classic Burger"
                                onerror="this.style.display='none';">

                            <div class="food-info">

                                <strong>
                                    Classic Urban Burger
                                </strong>

                                <small>
                                    Burgers
                                </small>

                            </div>

                            <div class="food-sales">
                                184 sold
                            </div>

                        </div>


                        <div class="popular-item">

                            <img
                                src="../assets/images/pizza.jpg"
                                class="food-image"
                                alt="Chicken Pizza"
                                onerror="this.style.display='none';">

                            <div class="food-info">

                                <strong>
                                    Spicy Chicken Pizza
                                </strong>

                                <small>
                                    Pizza
                                </small>

                            </div>

                            <div class="food-sales">
                                156 sold
                            </div>

                        </div>


                        <div class="popular-item">

                            <div class="food-image d-flex align-items-center justify-content-center">
                                🍝
                            </div>

                            <div class="food-info">

                                <strong>
                                    Creamy Chicken Pasta
                                </strong>

                                <small>
                                    Pasta
                                </small>

                            </div>

                            <div class="food-sales">
                                128 sold
                            </div>

                        </div>


                        <div class="popular-item">

                            <div class="food-image d-flex align-items-center justify-content-center">
                                🍗
                            </div>

                            <div class="food-info">

                                <strong>
                                    BBQ Chicken
                                </strong>

                                <small>
                                    Main Course
                                </small>

                            </div>

                            <div class="food-sales">
                                115 sold
                            </div>

                        </div>


                    </div>

                </div>

            </div>


            <!-- INVENTORY ALERTS -->

            <div class="col-xl-4">

                <div class="content-card">

                    <div class="card-header-custom">

                        <h3>
                            Inventory Alerts
                        </h3>

                        <a href="#">
                            View Inventory →
                        </a>

                    </div>


                    <div class="card-body-custom">


                        <div class="stock-alert">

                            <div class="stock-alert-icon">
                                ⚠️
                            </div>

                            <div>

                                <strong>
                                    Chicken Breast
                                </strong>

                                <small>
                                    Only 8 portions remaining
                                </small>

                            </div>

                        </div>


                        <div class="stock-alert">

                            <div class="stock-alert-icon">
                                ⚠️
                            </div>

                            <div>

                                <strong>
                                    Mozzarella Cheese
                                </strong>

                                <small>
                                    Only 5 kg remaining
                                </small>

                            </div>

                        </div>


                        <div class="stock-alert">

                            <div class="stock-alert-icon">
                                ⚠️
                            </div>

                            <div>

                                <strong>
                                    Burger Buns
                                </strong>

                                <small>
                                    Only 12 pieces remaining
                                </small>

                            </div>

                        </div>


                    </div>

                </div>

            </div>


            <!-- TODAY SUMMARY -->

            <div class="col-xl-3">

                <div class="content-card">

                    <div class="card-header-custom">

                        <h3>
                            Today's Summary
                        </h3>

                    </div>


                    <div class="card-body-custom">

                        <div class="summary-stat mb-4">

                            <small class="text-muted">
                                Completed Orders
                            </small>

                            <h3 class="fw-bold mt-1">
                                96
                            </h3>

                        </div>


                        <div class="summary-stat mb-4">

                            <small class="text-muted">
                                Cancelled Orders
                            </small>

                            <h3 class="fw-bold mt-1 text-danger">
                                4
                            </h3>

                        </div>


                        <div class="summary-stat mb-4">

                            <small class="text-muted">
                                Average Order
                            </small>

                            <h3 class="fw-bold mt-1">
                                Rs. 3,797
                            </h3>

                        </div>


                        <div class="summary-stat">

                            <small class="text-muted">
                                Customer Rating
                            </small>

                            <h3 class="fw-bold mt-1">
                                ⭐ 4.8
                            </h3>

                        </div>

                    </div>

                </div>

            </div>


        </div>


    </section>


    <div class="dashboard-footer">

        UrbanBites Restaurant Management System
        · Admin Dashboard · 2026

    </div>


</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>



<script>

    function toggleSidebar() {

        document
            .getElementById("sidebar")
            .classList.toggle("open");

    }


    /*
    Close mobile sidebar when
    clicking outside it.
    */

    document.addEventListener(
        "click",
        function(event) {

            const sidebar =
                document.getElementById("sidebar");

            const button =
                document.querySelector(
                    ".mobile-menu-btn"
                );


            if (
                window.innerWidth <= 991 &&
                sidebar.classList.contains("open") &&
                !sidebar.contains(event.target) &&
                !button.contains(event.target)
            ) {

                sidebar.classList.remove("open");

            }

        }
    );

</script>

</body>
</html>