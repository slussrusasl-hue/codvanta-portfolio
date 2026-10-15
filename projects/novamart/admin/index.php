
<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart Administrator Dashboard
|--------------------------------------------------------------------------
| Portfolio demonstration using sample data.
| No database connection is required.
*/

date_default_timezone_set("Asia/Colombo");

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function money($amount)
{
    return "Rs. " . number_format((float) $amount, 2);
}

/* -------------------------------------------------------
   DEMONSTRATION STATISTICS
------------------------------------------------------- */

$stats = [
    "total_sales" => 1248,
    "total_revenue" => 4867500,
    "today_revenue" => 84500,
    "total_orders" => 1248,
    "pending_orders" => 18,
    "registered_customers" => 856,
    "total_products" => 246,
    "low_stock_products" => 7
];

/* Revenue comparison with the previous period. */
$revenueGrowth = 12.8;
$ordersGrowth = 8.4;
$customersGrowth = 5.2;
$productsGrowth = 3.1;

/* -------------------------------------------------------
   SALES CHART - LAST SEVEN DAYS
------------------------------------------------------- */

$salesChart = [
    ["day" => "Thu", "date" => "08 Oct", "sales" => 48500],
    ["day" => "Fri", "date" => "09 Oct", "sales" => 62700],
    ["day" => "Sat", "date" => "10 Oct", "sales" => 79500],
    ["day" => "Sun", "date" => "11 Oct", "sales" => 56800],
    ["day" => "Mon", "date" => "12 Oct", "sales" => 91200],
    ["day" => "Tue", "date" => "13 Oct", "sales" => 73400],
    ["day" => "Wed", "date" => "14 Oct", "sales" => 84500]
];

$chartMax = 100000;
$chartWidth = 700;
$chartHeight = 245;
$chartPaddingLeft = 55;
$chartPaddingRight = 18;
$chartPaddingTop = 15;
$chartPaddingBottom = 35;

$plotWidth = $chartWidth - $chartPaddingLeft - $chartPaddingRight;
$plotHeight = $chartHeight - $chartPaddingTop - $chartPaddingBottom;

$chartPoints = [];
$chartCoordinates = [];

foreach ($salesChart as $index => $sale) {
    $x = $chartPaddingLeft +
        ($index * ($plotWidth / (count($salesChart) - 1)));

    $y = $chartPaddingTop +
        ($plotHeight - (($sale["sales"] / $chartMax) * $plotHeight));

    $chartPoints[] = round($x, 2) . "," . round($y, 2);

    $chartCoordinates[] = [
        "x" => round($x, 2),
        "y" => round($y, 2),
        "day" => $sale["day"],
        "date" => $sale["date"],
        "sales" => $sale["sales"]
    ];
}

$polylinePoints = implode(" ", $chartPoints);

$firstPoint = $chartCoordinates[0];
$lastPoint = $chartCoordinates[count($chartCoordinates) - 1];

$areaPoints = $firstPoint["x"] . "," . ($chartHeight - $chartPaddingBottom)
    . " " . $polylinePoints
    . " " . $lastPoint["x"] . "," . ($chartHeight - $chartPaddingBottom);

/* -------------------------------------------------------
   RECENT ORDERS - SAMPLE DATA
------------------------------------------------------- */

$recentOrders = [
    [
        "id" => "NM-261014-1042",
        "customer" => "Amelia Fernando",
        "initials" => "AF",
        "product" => "Wireless Headphones",
        "date" => "14 Oct 2026",
        "amount" => 18500,
        "status" => "Pending"
    ],
    [
        "id" => "NM-261014-1041",
        "customer" => "Daniel Perera",
        "initials" => "DP",
        "product" => "Smart Watch",
        "date" => "14 Oct 2026",
        "amount" => 24900,
        "status" => "Processing"
    ],
    [
        "id" => "NM-261013-1038",
        "customer" => "Sarah Jayasinghe",
        "initials" => "SJ",
        "product" => "Laptop Backpack",
        "date" => "13 Oct 2026",
        "amount" => 8500,
        "status" => "Delivered"
    ],
    [
        "id" => "NM-261013-1035",
        "customer" => "Kevin Silva",
        "initials" => "KS",
        "product" => "Bluetooth Speaker",
        "date" => "13 Oct 2026",
        "amount" => 12500,
        "status" => "Shipped"
    ],
    [
        "id" => "NM-261012-1029",
        "customer" => "Olivia Peris",
        "initials" => "OP",
        "product" => "Gaming Mouse",
        "date" => "12 Oct 2026",
        "amount" => 7900,
        "status" => "Pending"
    ],
    [
        "id" => "NM-261012-1025",
        "customer" => "Ryan Fernando",
        "initials" => "RF",
        "product" => "Laptop",
        "date" => "12 Oct 2026",
        "amount" => 185000,
        "status" => "Delivered"
    ]
];

/* -------------------------------------------------------
   LOW-STOCK ALERTS - SAMPLE DATA
------------------------------------------------------- */

$lowStockProducts = [
    [
        "name" => "Wireless Earbuds",
        "sku" => "ELE-001",
        "stock" => 3,
        "minimum" => 10,
        "category" => "Electronics",
        "icon" => "🎧"
    ],
    [
        "name" => "Smart Watch Pro",
        "sku" => "WEA-004",
        "stock" => 5,
        "minimum" => 15,
        "category" => "Wearables",
        "icon" => "⌚"
    ],
    [
        "name" => "Gaming Mouse",
        "sku" => "GAM-012",
        "stock" => 2,
        "minimum" => 10,
        "category" => "Gaming",
        "icon" => "🖱️"
    ],
    [
        "name" => "USB-C Charger",
        "sku" => "ACC-019",
        "stock" => 7,
        "minimum" => 20,
        "category" => "Accessories",
        "icon" => "🔌"
    ],
    [
        "name" => "Bluetooth Speaker",
        "sku" => "ELE-025",
        "stock" => 4,
        "minimum" => 12,
        "category" => "Electronics",
        "icon" => "🔊"
    ]
];

/* -------------------------------------------------------
   SIDEBAR NAVIGATION
------------------------------------------------------- */

$currentPage = "dashboard";
$adminName = "Admin User";
$adminInitials = "AU";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | NovaMart</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --navy: #102542;
            --navy-dark: #0a192f;
            --blue: #246bfd;
            --blue-light: #edf3ff;
            --text: #253047;
            --muted: #7b8799;
            --border: #e7ebf2;
            --page-bg: #f5f7fb;
            --green: #15976b;
            --red: #e34d59;
            --orange: #e89a23;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        button,
        input {
            font-family: inherit;
        }

        /* SIDEBAR */

        .sidebar {
            width: 255px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            background: var(--navy-dark);
            color: #fff;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            padding: 25px 23px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, .09);
        }

        .brand-name {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -.8px;
            color: #fff;
        }

        .brand-name span {
            color: #65a0ff;
        }

        .brand-caption {
            color: #91a4c3;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .sidebar-label {
            padding: 25px 23px 10px;
            color: #8295b4;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 0 12px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 14px;
            margin-bottom: 5px;
            border-radius: 9px;
            color: #bac7dc;
            font-size: 13px;
            font-weight: 600;
            transition: .2s;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff;
        }

        .sidebar-link.active {
            background: var(--blue);
            color: white;
            box-shadow: 0 5px 15px rgba(36, 107, 253, .25);
        }

        .sidebar-icon {
            width: 23px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-count {
            margin-left: auto;
            background: #e9a23b;
            color: #17243a;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 900;
            padding: 3px 7px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 18px 14px;
            border-top: 1px solid rgba(255, 255, 255, .09);
        }

        .sidebar-profile {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 7px;
        }

        .profile-avatar {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            background: #274c83;
            color: #fff;
            font-size: 13px;
            font-weight: 900;
            flex-shrink: 0;
        }

        .profile-name {
            font-size: 12px;
            font-weight: 800;
            color: #fff;
        }

        .profile-role {
            font-size: 11px;
            color: #91a4c3;
            margin-top: 3px;
        }

        /* MAIN LAYOUT */

        .main-wrapper {
            margin-left: 255px;
            min-height: 100vh;
        }

        .topbar {
            min-height: 78px;
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 15px 32px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-context {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 4px;
        }

        .topbar-title {
            font-size: 19px;
            font-weight: 900;
            color: var(--navy);
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .store-link {
            display: flex;
            align-items: center;
            gap: 7px;
            background: var(--blue-light);
            color: var(--blue);
            border-radius: 9px;
            padding: 10px 13px;
            font-size: 12px;
            font-weight: 800;
        }

        .store-link:hover {
            background: #dfeaff;
            color: #1554d1;
        }

        .date-label {
            color: #67748a;
            font-size: 12px;
            font-weight: 700;
        }

        .menu-toggle {
            display: none;
            background: #f0f4fb;
            border: 0;
            border-radius: 8px;
            color: var(--navy);
            width: 39px;
            height: 39px;
            font-size: 21px;
        }

        .dashboard-content {
            padding: 30px 32px 40px;
            max-width: 1700px;
            margin: 0 auto;
        }

        .welcome-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 25px;
        }

        .welcome-row h1 {
            color: var(--navy);
            font-size: 27px;
            font-weight: 900;
            margin: 0 0 8px;
            letter-spacing: -.5px;
        }

        .welcome-row p {
            color: var(--muted);
            font-size: 13px;
            margin: 0;
            line-height: 1.6;
        }

        .welcome-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-light-custom,
        .btn-blue-custom {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            border-radius: 9px;
            padding: 11px 15px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
            transition: .2s;
        }

        .btn-light-custom {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--navy);
        }

        .btn-light-custom:hover {
            background: #f0f4fb;
            color: var(--navy);
        }

        .btn-blue-custom {
            background: var(--blue);
            border: 1px solid var(--blue);
            color: white;
        }

        .btn-blue-custom:hover {
            background: #1554d1;
            color: white;
        }

        /* STATISTICS CARDS */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 23px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 21px;
            min-width: 0;
            box-shadow: 0 4px 15px rgba(16, 37, 66, .025);
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(16, 37, 66, .06);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-label {
            font-size: 12px;
            color: #758198;
            font-weight: 700;
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 21px;
            flex-shrink: 0;
        }

        .icon-blue {
            color: #246bfd;
            background: #edf3ff;
        }

        .icon-green {
            color: #15976b;
            background: #e6f8f0;
        }

        .icon-orange {
            color: #d98718;
            background: #fff4e3;
        }

        .icon-purple {
            color: #8159d9;
            background: #f1ebff;
        }

        .icon-red {
            color: #e34d59;
            background: #fff0f1;
        }

        .stat-value {
            font-size: clamp(21px, 2vw, 29px);
            font-weight: 900;
            color: var(--navy);
            line-height: 1.2;
            margin-bottom: 12px;
            overflow-wrap: anywhere;
        }

        .stat-footer {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            font-size: 11px;
            color: var(--muted);
        }

        .growth-positive {
            color: var(--green);
            font-weight: 900;
        }

        .growth-warning {
            color: #d98718;
            font-weight: 900;
        }

        .stat-link {
            display: inline-block;
            margin-top: 13px;
            font-size: 11px;
            font-weight: 800;
            color: var(--blue);
        }

        .stat-link:hover {
            color: #1554d1;
        }

        /* PANELS */

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(16, 37, 66, .025);
            min-width: 0;
            overflow: hidden;
        }

        .panel-header {
            padding: 22px 23px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            border-bottom: 1px solid #edf0f5;
        }

        .panel-heading {
            color: var(--navy);
            font-size: 16px;
            font-weight: 900;
            margin: 0 0 5px;
        }

        .panel-subtitle {
            color: var(--muted);
            font-size: 11px;
            margin: 0;
        }

        .panel-link {
            color: var(--blue);
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .panel-link:hover {
            color: #1554d1;
        }

        .panel-body {
            padding: 22px 23px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(280px, 1fr);
            gap: 22px;
            margin-bottom: 23px;
        }

        /* SALES CHART */

        .chart-summary {
            margin-bottom: 18px;
        }

        .chart-total {
            font-size: 25px;
            color: var(--navy);
            font-weight: 900;
            margin-bottom: 7px;
        }

        .chart-total-caption {
            font-size: 11px;
            color: var(--muted);
        }

        .chart-legend {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #68758a;
            font-size: 11px;
            font-weight: 700;
        }

        .legend-dot {
            width: 9px;
            height: 9px;
            background: var(--blue);
            border-radius: 50%;
        }

        .chart-scroll {
            width: 100%;
            overflow-x: auto;
        }

        .sales-svg {
            width: 100%;
            min-width: 390px;
            display: block;
            overflow: visible;
        }

        .chart-grid-line {
            stroke: #edf0f5;
            stroke-width: 1;
        }

        .chart-axis-label {
            fill: #8893a5;
            font-size: 11px;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        .chart-area {
            fill: url(#salesGradient);
        }

        .chart-line {
            fill: none;
            stroke: var(--blue);
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .chart-point {
            fill: white;
            stroke: var(--blue);
            stroke-width: 3;
        }

        .chart-point:hover {
            fill: var(--blue);
        }

        .chart-day {
            fill: #78859a;
            font-size: 11px;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        /* QUICK ACTIONS */

        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .quick-action {
            min-height: 110px;
            padding: 17px 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: space-between;
            transition: .2s;
            background: #fff;
        }

        .quick-action:hover {
            background: #f8faff;
            border-color: #b6ccff;
            transform: translateY(-2px);
        }

        .quick-action-icon {
            width: 35px;
            height: 35px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            font-size: 18px;
            margin-bottom: 13px;
        }

        .quick-action-title {
            font-size: 12px;
            font-weight: 800;
            color: var(--navy);
        }

        .quick-action-desc {
            font-size: 10px;
            color: var(--muted);
            margin-top: 5px;
            line-height: 1.4;
        }

        /* RECENT ORDERS TABLE */

        .table-responsive {
            width: 100%;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .orders-table thead th {
            background: #fafbfd;
            color: #7b8799;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: .45px;
            text-transform: uppercase;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
        }

        .orders-table tbody td {
            padding: 16px;
            border-bottom: 1px solid #f0f2f6;
            font-size: 11px;
            color: #5e6b80;
            vertical-align: middle;
        }

        .orders-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .orders-table tbody tr:hover {
            background: #fbfcff;
        }

        .order-id {
            font-weight: 900;
            color: var(--blue);
            font-size: 11px;
        }

        .customer-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .customer-avatar {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: #edf3ff;
            color: #246bfd;
            font-size: 10px;
            font-weight: 900;
            flex-shrink: 0;
        }

        .customer-name {
            font-size: 12px;
            color: var(--navy);
            font-weight: 800;
        }

        .customer-product {
            font-size: 10px;
            color: var(--muted);
            margin-top: 4px;
        }

        .order-amount {
            font-weight: 900;
            color: var(--navy) !important;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 20px;
            padding: 6px 9px;
            font-size: 10px;
            font-weight: 900;
        }

        .status-badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-pending {
            color: #c67d10;
            background: #fff5df;
        }

        .status-processing {
            color: #246bfd;
            background: #edf3ff;
        }

        .status-shipped {
            color: #8258d8;
            background: #f1ebff;
        }

        .status-delivered {
            color: #12845d;
            background: #e6f8f0;
        }

        .view-order {
            color: var(--blue);
            font-weight: 900;
            font-size: 11px;
        }

        /* STOCK ALERTS */

        .alert-count {
            background: #fff0e8;
            color: #d66c26;
            border-radius: 8px;
            padding: 6px 9px;
            font-size: 11px;
            font-weight: 900;
        }

        .stock-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 0;
            border-bottom: 1px solid #edf0f5;
        }

        .stock-alert:first-child {
            padding-top: 0;
        }

        .stock-alert:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .stock-product-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            background: #f5f7fb;
            border: 1px solid var(--border);
            display: grid;
            place-items: center;
            font-size: 21px;
            flex-shrink: 0;
        }

        .stock-product-info {
            flex: 1;
            min-width: 0;
        }

        .stock-product-name {
            color: var(--navy);
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 5px;
        }

        .stock-product-sku {
            color: var(--muted);
            font-size: 10px;
        }

        .stock-quantity {
            text-align: right;
            flex-shrink: 0;
        }

        .stock-quantity strong {
            display: block;
            font-size: 13px;
            color: var(--red);
            font-weight: 900;
        }

        .stock-quantity span {
            color: var(--muted);
            font-size: 10px;
        }

        .stock-progress {
            height: 4px;
            width: 100%;
            border-radius: 10px;
            background: #edf0f5;
            margin-top: 9px;
            overflow: hidden;
        }

        .stock-progress-bar {
            height: 100%;
            border-radius: 10px;
            background: var(--red);
        }

        .stock-alert-footer {
            background: #fff8f2;
            border-top: 1px solid #ffeadb;
            padding: 15px 22px;
        }

        .stock-alert-footer a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #c96b24;
            font-size: 12px;
            font-weight: 900;
        }

        /* BOTTOM SUMMARY */

        .bottom-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .mini-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 19px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 13px;
        }

        .mini-card-icon {
            width: 45px;
            height: 45px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            font-size: 21px;
            flex-shrink: 0;
        }

        .mini-card-label {
            color: var(--muted);
            font-size: 11px;
            margin-bottom: 5px;
        }

        .mini-card-value {
            color: var(--navy);
            font-size: 19px;
            font-weight: 900;
        }

        .demo-notice {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 13px 16px;
            margin-top: 23px;
            background: #edf3ff;
            border: 1px solid #dce7ff;
            border-radius: 10px;
            color: #516784;
            font-size: 11px;
            line-height: 1.6;
        }

        .dashboard-footer {
            border-top: 1px solid var(--border);
            padding: 20px 32px;
            color: #8792a5;
            font-size: 11px;
            background: #fff;
        }

        .dashboard-footer strong {
            color: var(--navy);
        }

        .sidebar-overlay {
            display: none;
        }

        /* RESPONSIVE */

        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .content-grid {
                grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
            }

            .dashboard-content {
                padding-left: 24px;
                padding-right: 24px;
            }

            .topbar {
                padding-left: 24px;
                padding-right: 24px;
            }
        }

        @media (max-width: 1100px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 1040;
                background: rgba(7, 18, 36, .5);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .menu-toggle {
                display: inline-grid;
                place-items: center;
            }

            .content-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .bottom-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .dashboard-content {
                padding: 22px 14px 30px;
            }

            .topbar {
                padding: 13px 14px;
                min-height: 68px;
            }

            .topbar-title {
                font-size: 16px;
            }

            .date-label {
                display: none;
            }

            .store-link {
                padding: 9px 10px;
                font-size: 11px;
            }

            .welcome-row {
                flex-direction: column;
            }

            .welcome-row h1 {
                font-size: 24px;
            }

            .welcome-actions {
                width: 100%;
            }

            .stats-grid {
                gap: 12px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-top {
                margin-bottom: 14px;
                align-items: flex-start;
            }

            .stat-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }

            .stat-value {
                font-size: 21px;
            }

            .stat-label {
                font-size: 11px;
            }

            .panel-header {
                padding: 18px 16px;
            }

            .panel-body {
                padding: 18px 16px;
            }

            .bottom-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-footer {
                padding: 18px 14px;
            }
        }

        @media (max-width: 380px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .topbar-right {
                gap: 7px;
            }

            .stat-value {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

<!-- Mobile sidebar overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- =====================================================
     SIDEBAR
===================================================== -->
<aside class="sidebar" id="adminSidebar">

    <div class="sidebar-brand">
        <a href="index.php" class="brand-name">
            NovaMart<span>.</span>
        </a>

        <div class="brand-caption">
            Administration Panel
        </div>
    </div>

    <div class="sidebar-label">Main Menu</div>

    <nav class="sidebar-nav">

        <a href="index.php"
           class="sidebar-link <?= $currentPage === "dashboard" ? "active" : "" ?>">
            <span class="sidebar-icon">▦</span>
            <span>Dashboard</span>
        </a>

        <a href="orders.php" class="sidebar-link">
            <span class="sidebar-icon">🛍️</span>
            <span>Order Management</span>
            <span class="sidebar-count"><?= (int) $stats["pending_orders"] ?></span>
        </a>

        <a href="products.php" class="sidebar-link">
            <span class="sidebar-icon">📦</span>
            <span>Product Management</span>
        </a>

        <a href="categories.php" class="sidebar-link">
            <span class="sidebar-icon">▤</span>
            <span>Categories</span>
        </a>

        <a href="inventory.php" class="sidebar-link">
            <span class="sidebar-icon">📋</span>
            <span>Inventory</span>

            <span class="sidebar-count">
                <?= (int) $stats["low_stock_products"] ?>
            </span>
        </a>

        <div class="sidebar-label">Business Management</div>

        <a href="customers.php" class="sidebar-link">
            <span class="sidebar-icon">👥</span>
            <span>Customers</span>
        </a>

        <a href="reports.php" class="sidebar-link">
            <span class="sidebar-icon">📊</span>
            <span>Sales & Reports</span>
        </a>

    </nav>

    <div class="sidebar-bottom">
        <div class="sidebar-profile">
            <div class="profile-avatar"><?= e($adminInitials) ?></div>

            <div>
                <div class="profile-name"><?= e($adminName) ?></div>
                <div class="profile-role">Store Administrator</div>
            </div>
        </div>

        <a href="../index.php" class="sidebar-link mt-2">
            <span class="sidebar-icon">↗</span>
            <span>View Customer Store</span>
        </a>
    </div>

</aside>

<!-- =====================================================
     MAIN CONTENT
===================================================== -->
<div class="main-wrapper">

    <!-- TOP BAR -->
    <header class="topbar">

        <div class="topbar-left">

            <button type="button" class="menu-toggle"
                    id="menuToggle" aria-label="Toggle navigation"
                    aria-expanded="false">
                ☰
            </button>

            <div>
                <div class="page-context">
                    NovaMart / Administration
                </div>

                <h2 class="topbar-title">Dashboard Overview</h2>
            </div>

        </div>

        <div class="topbar-right">

            <div class="date-label">
                📅 <?= date("d M Y") ?>
            </div>

            <a href="../index.php" class="store-link">
                ↗ View Store
            </a>

        </div>

    </header>

    <!-- DASHBOARD -->
    <main class="dashboard-content">

        <!-- WELCOME -->
        <section class="welcome-row">

            <div>
                <h1>Good day, Administrator 👋</h1>

                <p>
                    Here's what's happening with your store today.
                    Monitor sales, manage orders, and keep track of inventory.
                </p>
            </div>

            <div class="welcome-actions">
                <a href="reports.php" class="btn-light-custom">
                    📊 View Reports
                </a>

                <a href="products.php" class="btn-blue-custom">
                    ＋ Add Product
                </a>
            </div>

        </section>

        <!-- STATISTICS -->
        <section class="stats-grid">

            <!-- Total Revenue -->
            <article class="stat-card">

                <div class="stat-top">
                    <div class="stat-label">Total Revenue</div>

                    <div class="stat-icon icon-green">💰</div>
                </div>

                <div class="stat-value">
                    <?= money($stats["total_revenue"]) ?>
                </div>

                <div class="stat-footer">
                    <span class="growth-positive">
                        ↗ <?= e($revenueGrowth) ?>%
                    </span>
                    <span>vs previous period</span>
                </div>

                <a href="reports.php" class="stat-link">
                    View revenue reports →
                </a>

            </article>

            <!-- Total Orders -->
            <article class="stat-card">

                <div class="stat-top">
                    <div class="stat-label">Total Orders</div>

                    <div class="stat-icon icon-blue">🛍️</div>
                </div>

                <div class="stat-value">
                    <?= number_format($stats["total_orders"]) ?>
                </div>

                <div class="stat-footer">
                    <span class="growth-positive">
                        ↗ <?= e($ordersGrowth) ?>%
                    </span>
                    <span>vs previous period</span>
                </div>

                <a href="orders.php" class="stat-link">
                    Manage orders →
                </a>

            </article>

            <!-- Registered Customers -->
            <article class="stat-card">

                <div class="stat-top">
                    <div class="stat-label">Registered Customers</div>

                    <div class="stat-icon icon-purple">👥</div>
                </div>

                <div class="stat-value">
                    <?= number_format($stats["registered_customers"]) ?>
                </div>

                <div class="stat-footer">
                    <span class="growth-positive">
                        ↗ <?= e($customersGrowth) ?>%
                    </span>
                    <span>vs previous period</span>
                </div>

                <a href="customers.php" class="stat-link">
                    View customers →
                </a>

            </article>

            <!-- Total Products -->
            <article class="stat-card">

                <div class="stat-top">
                    <div class="stat-label">Total Products</div>

                    <div class="stat-icon icon-orange">📦</div>
                </div>

                <div class="stat-value">
                    <?= number_format($stats["total_products"]) ?>
                </div>

                <div class="stat-footer">
                    <span class="growth-positive">
                        ↗ <?= e($productsGrowth) ?>%
                    </span>
                    <span>vs previous period</span>
                </div>

                <a href="products.php" class="stat-link">
                    Manage products →
                </a>

            </article>

            <!-- Today's Revenue -->
            <article class="stat-card">

                <div class="stat-top">
                    <div class="stat-label">Today's Revenue</div>

                    <div class="stat-icon icon-green">📈</div>
                </div>

                <div class="stat-value">
                    <?= money($stats["today_revenue"]) ?>
                </div>

                <div class="stat-footer">
                    <span>Sales recorded for today</span>
                </div>

                <a href="reports.php" class="stat-link">
                    View today's report →
                </a>

            </article>

            <!-- Pending Orders -->
            <article class="stat-card">

                <div class="stat-top">
                    <div class="stat-label">Pending Orders</div>

                    <div class="stat-icon icon-orange">⏳</div>
                </div>

                <div class="stat-value">
                    <?= number_format($stats["pending_orders"]) ?>
                </div>

                <div class="stat-footer">
                    <span class="growth-warning">Needs attention</span>
                    <span>Awaiting processing</span>
                </div>

                <a href="orders.php" class="stat-link">
                    Review pending orders →
                </a>

            </article>

            <!-- Low Stock -->
            <article class="stat-card">

                <div class="stat-top">
                    <div class="stat-label">Low-Stock Products</div>

                    <div class="stat-icon icon-red">⚠️</div>
                </div>

                <div class="stat-value">
                    <?= number_format($stats["low_stock_products"]) ?>
                </div>

                <div class="stat-footer">
                    <span class="growth-warning">Stock alert</span>
                    <span>Restocking recommended</span>
                </div>

                <a href="inventory.php" class="stat-link">
                    Check inventory →
                </a>

            </article>

            <!-- Total Sales -->
            <article class="stat-card">

                <div class="stat-top">
                    <div class="stat-label">Total Sales</div>

                    <div class="stat-icon icon-purple">🧾</div>
                </div>

                <div class="stat-value">
                    <?= number_format($stats["total_sales"]) ?>
                </div>

                <div class="stat-footer">
                    <span>Recorded store orders</span>
                </div>

                <a href="reports.php" class="stat-link">
                    Explore sales reports →
                </a>

            </article>

        </section>

        <!-- SALES CHART AND QUICK ACTIONS -->
        <section class="content-grid">

            <!-- SALES CHART -->
            <article class="panel">

                <div class="panel-header">

                    <div>
                        <h3 class="panel-heading">Sales Overview</h3>

                        <p class="panel-subtitle">
                            Revenue performance over the last seven days
                        </p>
                    </div>

                    <a href="reports.php" class="panel-link">
                        Full Report →
                    </a>

                </div>

                <div class="panel-body">

                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">

                        <div class="chart-summary">
                            <div class="chart-total">
                                <?= money(array_sum(array_column($salesChart, "sales"))) ?>
                            </div>

                            <div class="chart-total-caption">
                                Total revenue for the displayed period
                            </div>
                        </div>

                        <div class="chart-legend">
                            <span class="legend-dot"></span>
                            Revenue
                        </div>

                    </div>

                    <div class="chart-scroll">

                        <svg class="sales-svg"
                             viewBox="0 0 <?= $chartWidth ?> <?= $chartHeight ?>"
                             role="img"
                             aria-label="Sales chart for the last seven days">

                            <defs>
                                <linearGradient id="salesGradient"
                                                x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%"
                                          stop-color="#246bfd"
                                          stop-opacity=".22"/>

                                    <stop offset="100%"
                                          stop-color="#246bfd"
                                          stop-opacity="0"/>
                                </linearGradient>
                            </defs>

                            <?php
                            $gridValues = [100000, 75000, 50000, 25000, 0];

                            foreach ($gridValues as $value):
                                $gridY = $chartPaddingTop +
                                    ($plotHeight - (($value / $chartMax) * $plotHeight));
                            ?>

                                <line
                                    x1="<?= $chartPaddingLeft ?>"
                                    y1="<?= round($gridY, 2) ?>"
                                    x2="<?= $chartWidth - $chartPaddingRight ?>"
                                    y2="<?= round($gridY, 2) ?>"
                                    class="chart-grid-line"/>

                                <text
                                    x="<?= $chartPaddingLeft - 10 ?>"
                                    y="<?= round($gridY + 4, 2) ?>"
                                    text-anchor="end"
                                    class="chart-axis-label">
                                    <?= $value === 0 ? "0" : number_format($value / 1000) . "k" ?>
                                </text>

                            <?php endforeach; ?>

                            <polygon
                                points="<?= e($areaPoints) ?>"
                                class="chart-area"/>

                            <polyline
                                points="<?= e($polylinePoints) ?>"
                                class="chart-line"/>

                            <?php foreach ($chartCoordinates as $point): ?>

                                <circle
                                    cx="<?= e($point["x"]) ?>"
                                    cy="<?= e($point["y"]) ?>"
                                    r="4.5"
                                    class="chart-point">

                                    <title>
                                        <?= e($point["date"]) ?>:
                                        <?= e(money($point["sales"])) ?>
                                    </title>

                                </circle>

                                <text
                                    x="<?= e($point["x"]) ?>"
                                    y="<?= $chartHeight - 10 ?>"
                                    text-anchor="middle"
                                    class="chart-day">
                                    <?= e($point["day"]) ?>
                                </text>

                            <?php endforeach; ?>

                        </svg>

                    </div>

                    <div class="text-muted mt-2" style="font-size:10px;">
                        Sample demonstration data. Hover over a chart point
                        to see its revenue value.
                    </div>

                </div>

            </article>

            <!-- QUICK ACTIONS -->
            <article class="panel">

                <div class="panel-header">

                    <div>
                        <h3 class="panel-heading">Quick Actions</h3>

                        <p class="panel-subtitle">
                            Common store management tasks
                        </p>
                    </div>

                </div>

                <div class="panel-body">

                    <div class="quick-actions">

                        <a href="products.php" class="quick-action">
                            <div>
                                <div class="quick-action-icon icon-blue">
                                    ＋
                                </div>

                                <div class="quick-action-title">
                                    Add Product
                                </div>

                                <div class="quick-action-desc">
                                    Register a new store product
                                </div>
                            </div>
                        </a>

                        <a href="orders.php" class="quick-action">
                            <div>
                                <div class="quick-action-icon icon-green">
                                    🛍️
                                </div>

                                <div class="quick-action-title">
                                    Manage Orders
                                </div>

                                <div class="quick-action-desc">
                                    Review and update orders
                                </div>
                            </div>
                        </a>

                        <a href="inventory.php" class="quick-action">
                            <div>
                                <div class="quick-action-icon icon-orange">
                                    📋
                                </div>

                                <div class="quick-action-title">
                                    Update Inventory
                                </div>

                                <div class="quick-action-desc">
                                    Review stock quantities
                                </div>
                            </div>
                        </a>

                        <a href="categories.php" class="quick-action">
                            <div>
                                <div class="quick-action-icon icon-purple">
                                    ▤
                                </div>

                                <div class="quick-action-title">
                                    Categories
                                </div>

                                <div class="quick-action-desc">
                                    Organize store products
                                </div>
                            </div>
                        </a>

                        <a href="customers.php" class="quick-action">
                            <div>
                                <div class="quick-action-icon icon-blue">
                                    👥
                                </div>

                                <div class="quick-action-title">
                                    Customers
                                </div>

                                <div class="quick-action-desc">
                                    View customer information
                                </div>
                            </div>
                        </a>

                        <a href="reports.php" class="quick-action">
                            <div>
                                <div class="quick-action-icon icon-green">
                                    📊
                                </div>

                                <div class="quick-action-title">
                                    Sales Reports
                                </div>

                                <div class="quick-action-desc">
                                    Review revenue and sales
                                </div>
                            </div>
                        </a>

                    </div>

                </div>

            </article>

        </section>

        <!-- RECENT ORDERS AND LOW STOCK -->
        <section class="content-grid">

            <!-- RECENT ORDERS -->
            <article class="panel">

                <div class="panel-header">

                    <div>
                        <h3 class="panel-heading">Recent Orders</h3>

                        <p class="panel-subtitle">
                            Latest customer purchases
                        </p>
                    </div>

                    <a href="orders.php" class="panel-link">
                        View All Orders →
                    </a>

                </div>

                <div class="table-responsive">

                    <table class="orders-table">

                        <thead>
                            <tr>
                                <th>Order / Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($recentOrders as $order): ?>

                                <?php
                                $statusClass = [
                                    "Pending" => "status-pending",
                                    "Processing" => "status-processing",
                                    "Shipped" => "status-shipped",
                                    "Delivered" => "status-delivered"
                                ][$order["status"]] ?? "status-pending";
                                ?>

                                <tr>

                                    <td>
                                        <div class="customer-cell">

                                            <div class="customer-avatar">
                                                <?= e($order["initials"]) ?>
                                            </div>

                                            <div>
                                                <div class="order-id">
                                                    <?= e($order["id"]) ?>
                                                </div>

                                                <div class="customer-name mt-1">
                                                    <?= e($order["customer"]) ?>
                                                </div>

                                                <div class="customer-product">
                                                    <?= e($order["product"]) ?>
                                                </div>
                                            </div>

                                        </div>
                                    </td>

                                    <td><?= e($order["date"]) ?></td>

                                    <td class="order-amount">
                                        <?= money($order["amount"]) ?>
                                    </td>

                                    <td>
                                        <span class="status-badge <?= e($statusClass) ?>">
                                            <?= e($order["status"]) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a href="orders.php" class="view-order">
                                            View
                                        </a>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </article>

            <!-- LOW STOCK ALERTS -->
            <article class="panel">

                <div class="panel-header">

                    <div>
                        <h3 class="panel-heading">Low-Stock Alerts</h3>

                        <p class="panel-subtitle">
                            Products requiring attention
                        </p>
                    </div>

                    <span class="alert-count">
                        <?= (int) $stats["low_stock_products"] ?> Alerts
                    </span>

                </div>

                <div class="panel-body">

                    <?php foreach ($lowStockProducts as $product): ?>

                        <?php
                        $stockPercentage = min(
                            100,
                            ($product["stock"] / max(1, $product["minimum"])) * 100
                        );
                        ?>

                        <div class="stock-alert">

                            <div class="stock-product-icon">
                                <?= e($product["icon"]) ?>
                            </div>

                            <div class="stock-product-info">

                                <div class="stock-product-name">
                                    <?= e($product["name"]) ?>
                                </div>

                                <div class="stock-product-sku">
                                    SKU: <?= e($product["sku"]) ?>
                                    · <?= e($product["category"]) ?>
                                </div>

                                <div class="stock-progress">
                                    <div class="stock-progress-bar"
                                         style="width: <?= e($stockPercentage) ?>%;">
                                    </div>
                                </div>

                            </div>

                            <div class="stock-quantity">
                                <strong><?= (int) $product["stock"] ?> left</strong>

                                <span>
                                    Min. <?= (int) $product["minimum"] ?>
                                </span>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <div class="stock-alert-footer">
                    <a href="inventory.php">
                        <span>Review inventory and restock</span>
                        <span>→</span>
                    </a>
                </div>

            </article>

        </section>

        <!-- BOTTOM SUMMARY -->
        <section class="bottom-grid">

            <article class="mini-card">

                <div class="mini-card-icon icon-green">💵</div>

                <div>
                    <div class="mini-card-label">
                        Revenue Today
                    </div>

                    <div class="mini-card-value">
                        <?= money($stats["today_revenue"]) ?>
                    </div>
                </div>

            </article>

            <article class="mini-card">

                <div class="mini-card-icon icon-orange">⏳</div>

                <div>
                    <div class="mini-card-label">
                        Orders Awaiting Processing
                    </div>

                    <div class="mini-card-value">
                        <?= number_format($stats["pending_orders"]) ?> Orders
                    </div>
                </div>

            </article>

            <article class="mini-card">

                <div class="mini-card-icon icon-purple">📦</div>

                <div>
                    <div class="mini-card-label">
                        Products in Catalogue
                    </div>

                    <div class="mini-card-value">
                        <?= number_format($stats["total_products"]) ?> Products
                    </div>
                </div>

            </article>

        </section>

        <!-- DEMO NOTICE -->
        <div class="demo-notice">
            <span>ℹ️</span>

            <div>
                <strong>Portfolio demonstration dashboard.</strong>
                All statistics, orders, customer records, chart values, and
                inventory alerts displayed here are sample data created for
                the NovaMart demonstration. They are not connected to actual
                customer transactions or a live inventory database.
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="dashboard-footer">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>
                © <?= date("Y") ?> <strong>NovaMart</strong>.
                Administration Panel.
            </span>

            <span>
                A <strong>Codvanta</strong> portfolio demonstration.
            </span>
        </div>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("adminSidebar");
    const toggle = document.getElementById("menuToggle");
    const overlay = document.getElementById("sidebarOverlay");

    function closeSidebar() {
        sidebar.classList.remove("open");
        overlay.classList.remove("show");
        toggle.setAttribute("aria-expanded", "false");
    }

    toggle.addEventListener("click", function () {
        const isOpen = sidebar.classList.toggle("open");

        overlay.classList.toggle("show", isOpen);
        toggle.setAttribute("aria-expanded", String(isOpen));
    });

    overlay.addEventListener("click", closeSidebar);

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeSidebar();
        }
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 1100) {
            closeSidebar();
        }
    });
});
</script>

</body>
</html>