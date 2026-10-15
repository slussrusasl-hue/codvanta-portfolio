<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart Inventory Management
|--------------------------------------------------------------------------
| Portfolio demonstration.
| Data is stored in PHP sessions, not in MySQL.
*/

if (empty($_SESSION["novamart_inventory_csrf"])) {
    $_SESSION["novamart_inventory_csrf"] = bin2hex(random_bytes(32));
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function money($value)
{
    return "Rs. " . number_format((float) $value, 2);
}

function inventoryAddMovement(
    &$history,
    $productId,
    $productName,
    $type,
    $quantity,
    $before,
    $after,
    $reason
) {
    $history[] = [
        "id" => count($history) + 1,
        "product_id" => (int) $productId,
        "product_name" => $productName,
        "type" => $type,
        "quantity" => (int) $quantity,
        "before" => (int) $before,
        "after" => (int) $after,
        "reason" => $reason,
        "date" => date("Y-m-d H:i:s")
    ];

    // Keep the most recent 200 movements in this demo.
    if (count($history) > 200) {
        $history = array_slice($history, -200);
    }
}

/*
|--------------------------------------------------------------------------
| Initial inventory data
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["novamart_inventory_products"])) {
    $_SESSION["novamart_inventory_products"] = [
        1 => [
            "id" => 1,
            "name" => "Wireless Earbuds Pro",
            "category" => "Electronics",
            "price" => 8500,
            "stock" => 24,
            "minimum" => 10,
            "reorder" => 20,
            "image" => "earbuds.jpg"
        ],
        2 => [
            "id" => 2,
            "name" => "Smart Watch Series 5",
            "category" => "Accessories",
            "price" => 12500,
            "stock" => 8,
            "minimum" => 10,
            "reorder" => 15,
            "image" => "smartwatch.jpg"
        ],
        3 => [
            "id" => 3,
            "name" => "UltraBook Laptop 14",
            "category" => "Computing",
            "price" => 185000,
            "stock" => 5,
            "minimum" => 5,
            "reorder" => 10,
            "image" => "laptop.jpg"
        ],
        4 => [
            "id" => 4,
            "name" => "Bluetooth Speaker",
            "category" => "Electronics",
            "price" => 6500,
            "stock" => 18,
            "minimum" => 8,
            "reorder" => 15,
            "image" => "speaker.jpg"
        ],
        5 => [
            "id" => 5,
            "name" => "Mechanical Gaming Keyboard",
            "category" => "Gaming",
            "price" => 11500,
            "stock" => 0,
            "minimum" => 5,
            "reorder" => 15,
            "image" => "keyboard.jpg"
        ],
        6 => [
            "id" => 6,
            "name" => "Classic Casual Backpack",
            "category" => "Fashion",
            "price" => 4200,
            "stock" => 32,
            "minimum" => 10,
            "reorder" => 20,
            "image" => "backpack.jpg"
        ],
        7 => [
            "id" => 7,
            "name" => "Air Fryer 5L",
            "category" => "Home Appliances",
            "price" => 24500,
            "stock" => 11,
            "minimum" => 5,
            "reorder" => 10,
            "image" => "airfryer.jpg"
        ],
        8 => [
            "id" => 8,
            "name" => "Wireless Mouse",
            "category" => "Computing",
            "price" => 2800,
            "stock" => 3,
            "minimum" => 10,
            "reorder" => 25,
            "image" => "mouse.jpg"
        ],
        9 => [
            "id" => 9,
            "name" => "HD Webcam",
            "category" => "Computing",
            "price" => 7500,
            "stock" => 14,
            "minimum" => 5,
            "reorder" => 10,
            "image" => "webcam.jpg"
        ],
        10 => [
            "id" => 10,
            "name" => "Fitness Resistance Bands",
            "category" => "Accessories",
            "price" => 1900,
            "stock" => 27,
            "minimum" => 10,
            "reorder" => 20,
            "image" => "fitness.jpg"
        ],
        11 => [
            "id" => 11,
            "name" => "LED Desk Lamp",
            "category" => "Home Appliances",
            "price" => 3900,
            "stock" => 7,
            "minimum" => 8,
            "reorder" => 15,
            "image" => "lamp.jpg"
        ],
        12 => [
            "id" => 12,
            "name" => "Gaming Headset",
            "category" => "Gaming",
            "price" => 9800,
            "stock" => 16,
            "minimum" => 8,
            "reorder" => 12,
            "image" => "headset.jpg"
        ]
    ];
}

if (!isset($_SESSION["novamart_inventory_history"])) {
    $_SESSION["novamart_inventory_history"] = [
        [
            "id" => 1,
            "product_id" => 1,
            "product_name" => "Wireless Earbuds Pro",
            "type" => "IN",
            "quantity" => 20,
            "before" => 4,
            "after" => 24,
            "reason" => "Supplier delivery received",
            "date" => date("Y-m-d H:i:s", strtotime("-2 days"))
        ],
        [
            "id" => 2,
            "product_id" => 2,
            "product_name" => "Smart Watch Series 5",
            "type" => "OUT",
            "quantity" => 4,
            "before" => 12,
            "after" => 8,
            "reason" => "Customer orders",
            "date" => date("Y-m-d H:i:s", strtotime("-1 day"))
        ],
        [
            "id" => 3,
            "product_id" => 8,
            "product_name" => "Wireless Mouse",
            "type" => "OUT",
            "quantity" => 7,
            "before" => 10,
            "after" => 3,
            "reason" => "Customer orders",
            "date" => date("Y-m-d H:i:s", strtotime("-5 hours"))
        ]
    ];
}

/*
|--------------------------------------------------------------------------
| Sample orders for demonstrating stock deductions
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["novamart_inventory_orders"])) {
    $_SESSION["novamart_inventory_orders"] = [
        "NM-26001" => [
            "customer" => "Amelia Perera",
            "date" => date("Y-m-d H:i:s", strtotime("-2 hours")),
            "status" => "Pending",
            "items" => [
                1 => 2,
                4 => 1
            ]
        ],
        "NM-26002" => [
            "customer" => "Daniel Fernando",
            "date" => date("Y-m-d H:i:s", strtotime("-4 hours")),
            "status" => "Pending",
            "items" => [
                2 => 1,
                10 => 2
            ]
        ],
        "NM-26003" => [
            "customer" => "Sophia Silva",
            "date" => date("Y-m-d H:i:s", strtotime("-1 day")),
            "status" => "Processed",
            "items" => [
                9 => 1
            ]
        ]
    ];
}

$products = &$_SESSION["novamart_inventory_products"];
$history = &$_SESSION["novamart_inventory_history"];
$orders = &$_SESSION["novamart_inventory_orders"];

$categories = [
    "Electronics",
    "Computing",
    "Accessories",
    "Home Appliances",
    "Fashion",
    "Beauty",
    "Gaming",
    "Toys & Games"
];

$notice = "";
$noticeType = "success";

/*
|--------------------------------------------------------------------------
| Handle inventory actions
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($token) ||
        !hash_equals($_SESSION["novamart_inventory_csrf"], $token)
    ) {
        $notice = "Your session expired. Refresh the page and try again.";
        $noticeType = "danger";
    } else {
        $action = $_POST["action"] ?? "";

        /*
        | Update minimum stock and reorder quantities
        */

        if ($action === "save_thresholds") {
            $minimums = $_POST["minimum"] ?? [];
            $reorders = $_POST["reorder"] ?? [];

            if (!is_array($minimums) || !is_array($reorders)) {
                $notice = "Invalid stock threshold data.";
                $noticeType = "danger";
            } else {
                $updates = [];
                $valid = true;

                foreach ($products as $id => $product) {
                    if (
                        !isset($minimums[$id], $reorders[$id]) ||
                        !is_scalar($minimums[$id]) ||
                        !is_scalar($reorders[$id]) ||
                        filter_var($minimums[$id], FILTER_VALIDATE_INT) === false ||
                        filter_var($reorders[$id], FILTER_VALIDATE_INT) === false ||
                        (int) $minimums[$id] < 0 ||
                        (int) $reorders[$id] < 1 ||
                        (int) $minimums[$id] > 1000000 ||
                        (int) $reorders[$id] > 1000000
                    ) {
                        $valid = false;
                        break;
                    }

                    $updates[$id] = [
                        "minimum" => (int) $minimums[$id],
                        "reorder" => (int) $reorders[$id]
                    ];
                }

                if (!$valid) {
                    $notice = "Enter valid minimum and reorder quantities for every product.";
                    $noticeType = "danger";
                } else {
                    foreach ($updates as $id => $values) {
                        $products[$id]["minimum"] = $values["minimum"];
                        $products[$id]["reorder"] = $values["reorder"];
                    }

                    $notice = "Minimum stock and reorder levels updated successfully.";
                }
            }
        }

        /*
        | Manually add or reduce stock
        */

        elseif ($action === "adjust_stock") {
            $id = filter_var($_POST["product_id"] ?? "", FILTER_VALIDATE_INT);
            $type = $_POST["movement_type"] ?? "";
            $quantity = filter_var($_POST["quantity"] ?? "", FILTER_VALIDATE_INT);
            $reason = trim((string) ($_POST["reason"] ?? ""));

            if ($id === false || $id === null || !isset($products[$id])) {
                $notice = "Please select a valid product.";
                $noticeType = "danger";
            } elseif (!in_array($type, ["IN", "OUT", "CORRECTION"], true)) {
                $notice = "Please select a valid stock movement type.";
                $noticeType = "danger";
            } elseif (
                $quantity === false ||
                $quantity === null ||
                $quantity < 1 ||
                $quantity > 1000000
            ) {
                $notice = "Quantity must be a whole number greater than zero.";
                $noticeType = "danger";
            } elseif ($reason === "" || strlen($reason) > 200) {
                $notice = "Enter a reason of up to 200 characters.";
                $noticeType = "danger";
            } else {
                $before = (int) $products[$id]["stock"];

                if ($type === "IN") {
                    $after = $before + $quantity;
                } elseif ($type === "OUT") {
                    $after = $before - $quantity;
                } else {
                    // CORRECTION sets the stock to the entered quantity.
                    $after = $quantity;
                    $quantity = abs($after - $before);

                    if ($quantity === 0) {
                        $notice = "The entered stock matches the current quantity.";
                        $noticeType = "warning";
                        $after = $before;
                    }
                }

                if ($type !== "CORRECTION" && $after < 0) {
                    $notice = "Insufficient stock. The available quantity is "
                        . $before . " units.";
                    $noticeType = "danger";
                } elseif (
                    $type !== "CORRECTION" ||
                    $after !== $before
                ) {
                    if ($after > 1000000) {
                        $notice = "Stock cannot exceed 1,000,000 units.";
                        $noticeType = "danger";
                    } else {
                        $products[$id]["stock"] = $after;

                        $movementQuantity = $type === "CORRECTION"
                            ? abs($after - $before)
                            : $quantity;

                        if ($movementQuantity > 0) {
                            inventoryAddMovement(
                                $history,
                                $id,
                                $products[$id]["name"],
                                $type,
                                $movementQuantity,
                                $before,
                                $after,
                                $reason
                            );

                            $notice = "Stock updated successfully. "
                                . $products[$id]["name"]
                                . " now has " . $after . " units.";
                        }
                    }
                }
            }
        }

        /*
        | Process a sample customer order
        */

        elseif ($action === "process_order") {
            $orderId = trim((string) ($_POST["order_id"] ?? ""));

            if (!isset($orders[$orderId])) {
                $notice = "The selected order could not be found.";
                $noticeType = "danger";
            } elseif ($orders[$orderId]["status"] !== "Pending") {
                $notice = "This order has already been processed.";
                $noticeType = "warning";
            } else {
                $order = $orders[$orderId];
                $insufficient = [];

                // Check all product quantities before deducting any stock.
                foreach ($order["items"] as $productId => $quantity) {
                    if (!isset($products[$productId])) {
                        $insufficient[] = "Product ID " . $productId . " no longer exists.";
                    } elseif ($products[$productId]["stock"] < $quantity) {
                        $insufficient[] = $products[$productId]["name"]
                            . " needs " . $quantity . " units but only "
                            . $products[$productId]["stock"] . " are available.";
                    }
                }

                if (!empty($insufficient)) {
                    $notice = "Order not processed: " . implode(" ", $insufficient);
                    $noticeType = "danger";
                } else {
                    foreach ($order["items"] as $productId => $quantity) {
                        $before = (int) $products[$productId]["stock"];
                        $after = $before - $quantity;

                        $products[$productId]["stock"] = $after;

                        inventoryAddMovement(
                            $history,
                            $productId,
                            $products[$productId]["name"],
                            "OUT",
                            $quantity,
                            $before,
                            $after,
                            "Order " . $orderId . " processed"
                        );
                    }

                    $orders[$orderId]["status"] = "Processed";

                    $notice = "Order " . $orderId
                        . " processed successfully. Stock has been deducted.";
                }
            }
        } else {
            $notice = "Invalid inventory action.";
            $noticeType = "danger";
        }
    }

    $_SESSION["novamart_inventory_flash"] = [
        "message" => $notice,
        "type" => $noticeType
    ];

    header("Location: inventory.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Retrieve action feedback
|--------------------------------------------------------------------------
*/

if (isset($_SESSION["novamart_inventory_flash"])) {
    $flash = $_SESSION["novamart_inventory_flash"];
    $notice = $flash["message"] ?? "";
    $noticeType = $flash["type"] ?? "success";

    unset($_SESSION["novamart_inventory_flash"]);
}

/*
|--------------------------------------------------------------------------
| Search and filtering
|--------------------------------------------------------------------------
*/

$search = trim((string) ($_GET["search"] ?? ""));
$categoryFilter = trim((string) ($_GET["category"] ?? ""));
$stockFilter = trim((string) ($_GET["stock"] ?? ""));

$filteredProducts = [];

foreach ($products as $product) {
    if (
        $search !== "" &&
        stripos(
            $product["name"] . " " . $product["category"],
            $search
        ) === false
    ) {
        continue;
    }

    if (
        $categoryFilter !== "" &&
        $product["category"] !== $categoryFilter
    ) {
        continue;
    }

    $stock = (int) $product["stock"];
    $minimum = (int) $product["minimum"];

    if ($stockFilter === "low" && !($stock > 0 && $stock <= $minimum)) {
        continue;
    }

    if ($stockFilter === "out" && $stock !== 0) {
        continue;
    }

    if ($stockFilter === "healthy" && $stock <= $minimum) {
        continue;
    }

    $filteredProducts[] = $product;
}

usort($filteredProducts, function ($a, $b) {
    return $a["id"] <=> $b["id"];
});

/*
|--------------------------------------------------------------------------
| Inventory statistics
|--------------------------------------------------------------------------
*/

$totalProducts = count($products);
$totalUnits = 0;
$lowStockCount = 0;
$outOfStockCount = 0;
$totalInventoryValue = 0;

foreach ($products as $product) {
    $stock = (int) $product["stock"];

    $totalUnits += $stock;
    $totalInventoryValue += $stock * (float) $product["price"];

    if ($stock === 0) {
        $outOfStockCount++;
    } elseif ($stock <= (int) $product["minimum"]) {
        $lowStockCount++;
    }
}

$pendingOrders = array_filter($orders, function ($order) {
    return $order["status"] === "Pending";
});

$recentHistory = array_reverse($history);
$recentHistory = array_slice($recentHistory, 0, 10);
$allHistoryCount = count($history);

$pendingOrderCount = count($pendingOrders);
$pendingOrderCountForDisplay = $pendingOrderCount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inventory Management | NovaMart Admin</title>

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
            --orange: #d98216;
            --red: #d94747;
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

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            background: var(--navy-dark);
            color: white;
            padding: 25px 16px;
            overflow-y: auto;
        }

        .brand {
            display: block;
            color: white;
            font-size: 25px;
            font-weight: 900;
            letter-spacing: -.7px;
            padding: 0 13px;
            margin-bottom: 35px;
        }

        .brand span {
            color: #6090ff;
        }

        .sidebar-label {
            color: #8293ad;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.7px;
            font-weight: 800;
            padding: 0 13px;
            margin: 25px 0 12px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #c1cce0;
            padding: 12px 14px;
            border-radius: 9px;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: 700;
            transition: .2s;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,.08);
            color: white;
        }

        .sidebar-link.active {
            background: var(--blue);
            color: white;
            box-shadow: 0 5px 14px rgba(36,107,253,.25);
        }

        .sidebar-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-bottom {
            border-top: 1px solid rgba(255,255,255,.12);
            margin-top: 35px;
            padding-top: 18px;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            min-height: 78px;
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 15px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .topbar-title {
            font-weight: 900;
            color: var(--navy);
            font-size: 18px;
        }

        .topbar-subtitle {
            color: var(--muted);
            font-size: 12px;
            margin-top: 3px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            color: var(--blue);
            background: var(--blue-light);
            font-size: 19px;
        }

        .admin-name {
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
        }

        .admin-role {
            color: var(--muted);
            font-size: 11px;
        }

        .page-content {
            padding: 30px;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .page-heading h1 {
            color: var(--navy);
            font-size: 27px;
            font-weight: 900;
            margin: 0 0 8px;
        }

        .page-heading p {
            color: var(--muted);
            font-size: 13px;
            margin: 0;
            line-height: 1.7;
        }

        .btn-primary-custom {
            background: var(--blue);
            color: white;
            border: 0;
            padding: 11px 16px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 800;
        }

        .btn-primary-custom:hover {
            background: #1554d1;
            color: white;
        }

        .btn-secondary-custom {
            background: #f0f3f8;
            color: #47556b;
            border: 1px solid #e1e6ef;
            border-radius: 9px;
            padding: 10px 14px;
            font-size: 12px;
            font-weight: 800;
        }

        .btn-secondary-custom:hover {
            background: #e4eaf4;
            color: var(--navy);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            box-shadow: 0 5px 18px rgba(16,37,66,.025);
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .stat-number {
            color: var(--navy);
            font-size: 28px;
            font-weight: 900;
            line-height: 1.15;
        }

        .stat-note {
            color: var(--muted);
            font-size: 11px;
            margin-top: 10px;
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .icon-blue {
            color: var(--blue);
            background: var(--blue-light);
        }

        .icon-green {
            color: var(--green);
            background: #e8f8f1;
        }

        .icon-orange {
            color: var(--orange);
            background: #fff4e4;
        }

        .icon-red {
            color: var(--red);
            background: #fff0f0;
        }

        .content-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(16,37,66,.025);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header-custom {
            padding: 20px 23px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .card-header-custom h2 {
            font-size: 17px;
            color: var(--navy);
            font-weight: 900;
            margin: 0 0 5px;
        }

        .card-header-custom p {
            color: var(--muted);
            font-size: 12px;
            margin: 0;
            line-height: 1.6;
        }

        .card-body-custom {
            padding: 23px;
        }

        .filter-area {
            padding: 18px 23px;
            border-bottom: 1px solid var(--border);
            background: #fcfdff;
        }

        .form-label {
            color: #3d4a60;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            border: 1px solid #dfe5ef;
            border-radius: 9px;
            padding: 10px 12px;
            font-size: 13px;
            min-height: 42px;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--blue);
        }

        .info-banner {
            background: #eef4ff;
            border: 1px solid #d9e5ff;
            border-radius: 12px;
            padding: 14px 16px;
            color: #3d5680;
            font-size: 12px;
            line-height: 1.7;
            margin-bottom: 23px;
        }

        .info-banner strong {
            color: var(--navy);
        }

        .alert {
            border-radius: 11px;
            font-size: 13px;
        }

        .alert ul {
            margin-bottom: 0;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .product-table thead th {
            background: #f8faff;
            color: #718098;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: .5px;
            font-weight: 900;
            padding: 15px 16px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .product-table tbody td {
            padding: 15px 16px;
            border-bottom: 1px solid #edf0f5;
            vertical-align: middle;
            font-size: 12px;
        }

        .product-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .product-table tbody tr:hover {
            background: #fbfcff;
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 225px;
        }

        .product-image {
            width: 49px;
            height: 49px;
            background: #f7f9fd;
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            font-size: 22px;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .product-name {
            color: var(--navy);
            font-size: 12px;
            font-weight: 900;
            line-height: 1.5;
        }

        .product-id {
            color: var(--muted);
            font-size: 10px;
            margin-top: 4px;
        }

        .category-pill {
            display: inline-block;
            background: #eef3fc;
            color: #4c6080;
            padding: 6px 9px;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .stock-number {
            color: var(--navy);
            font-weight: 900;
            font-size: 15px;
        }

        .stock-subtext {
            color: var(--muted);
            font-size: 10px;
            margin-top: 4px;
        }

        .stock-status {
            display: inline-block;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 900;
            white-space: nowrap;
        }

        .stock-good {
            color: #0a8054;
            background: #e7f8ef;
        }

        .stock-low {
            color: #a7670b;
            background: #fff2db;
        }

        .stock-out {
            color: #c83232;
            background: #ffebeb;
        }

        .threshold-input {
            width: 75px;
            min-height: 36px !important;
            padding: 6px 8px !important;
            text-align: center;
        }

        .stock-bar {
            width: 100%;
            max-width: 100px;
            height: 5px;
            border-radius: 10px;
            background: #edf0f5;
            overflow: hidden;
            margin-top: 7px;
        }

        .stock-bar span {
            display: block;
            height: 100%;
            border-radius: 10px;
        }

        .stock-bar .bar-good {
            background: var(--green);
        }

        .stock-bar .bar-low {
            background: var(--orange);
        }

        .stock-bar .bar-out {
            background: var(--red);
        }

        .table-footer {
            padding: 16px 23px;
            background: #fcfdff;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: 12px;
        }

        .adjustment-type {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .movement-choice {
            flex: 1;
            min-width: 100px;
        }

        .movement-choice input {
            display: none;
        }

        .movement-choice label {
            display: block;
            padding: 13px 10px;
            border: 1px solid var(--border);
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            color: #506078;
            font-size: 12px;
            font-weight: 800;
            background: white;
            transition: .2s;
        }

        .movement-choice input:checked + label {
            border-color: var(--blue);
            background: var(--blue-light);
            color: var(--blue);
            box-shadow: 0 0 0 1px var(--blue);
        }

        .movement-note {
            font-size: 11px;
            color: var(--muted);
            line-height: 1.6;
            margin-top: 12px;
        }

        .movement-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .movement-table th {
            background: #f8faff;
            color: #718098;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: .5px;
            font-weight: 900;
            padding: 14px;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        .movement-table td {
            padding: 14px;
            font-size: 12px;
            border-bottom: 1px solid #edf0f5;
            vertical-align: middle;
        }

        .movement-table tr:last-child td {
            border-bottom: 0;
        }

        .movement-pill {
            display: inline-block;
            min-width: 46px;
            text-align: center;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 900;
        }

        .movement-in {
            color: #0a8054;
            background: #e7f8ef;
        }

        .movement-out {
            color: #c83232;
            background: #ffebeb;
        }

        .movement-correction {
            color: #285fc4;
            background: #eaf1ff;
        }

        .history-quantity {
            font-weight: 900;
            white-space: nowrap;
        }

        .history-date {
            color: var(--muted);
            white-space: nowrap;
            font-size: 11px;
        }

        .empty-state {
            padding: 45px 20px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        .empty-state strong {
            display: block;
            color: var(--navy);
            font-size: 16px;
            margin-bottom: 7px;
        }

        .order-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
        }

        .order-card:last-child {
            margin-bottom: 0;
        }

        .order-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        .order-number {
            color: var(--blue);
            font-weight: 900;
            font-size: 13px;
        }

        .order-customer {
            color: var(--navy);
            font-weight: 800;
            font-size: 12px;
            margin-top: 5px;
        }

        .order-meta {
            color: var(--muted);
            font-size: 11px;
            margin-top: 5px;
        }

        .order-items {
            margin-top: 12px;
            padding-top: 11px;
            border-top: 1px solid #edf0f5;
            color: #53627a;
            font-size: 11px;
            line-height: 1.9;
        }

        .order-status {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 900;
            white-space: nowrap;
        }

        .order-pending {
            color: #a7670b;
            background: #fff2db;
        }

        .order-processed {
            color: #0a8054;
            background: #e7f8ef;
        }

        .btn-process {
            border: 1px solid #c8d9ff;
            background: var(--blue-light);
            color: var(--blue);
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 11px;
            font-weight: 900;
            margin-top: 12px;
        }

        .btn-process:hover {
            background: var(--blue);
            color: white;
        }

        .btn-process:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .order-warning {
            color: var(--red);
            font-size: 11px;
            margin-top: 8px;
            line-height: 1.6;
        }

        .footer-note {
            color: var(--muted);
            font-size: 11px;
            text-align: center;
            padding: 10px 0 24px;
        }

        .mobile-menu-button {
            display: none;
            background: var(--blue-light);
            color: var(--blue);
            border: 0;
            border-radius: 8px;
            width: 39px;
            height: 39px;
            font-size: 20px;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1250px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .page-content {
                padding: 23px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 999;
                background: rgba(7,20,40,.48);
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                min-height: 70px;
                padding: 12px 16px;
            }

            .topbar-left {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .mobile-menu-button {
                display: inline-grid;
                place-items: center;
            }

            .topbar-subtitle,
            .admin-details {
                display: none;
            }

            .page-content {
                padding: 20px 14px;
            }

            .page-heading h1 {
                font-size: 23px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-number {
                font-size: 23px;
            }

            .stat-icon {
                width: 35px;
                height: 35px;
                font-size: 17px;
            }

            .card-header-custom,
            .card-body-custom {
                padding: 17px;
            }

            .filter-area {
                padding: 16px;
            }
        }

        @media (max-width: 430px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .stat-card {
                display: block;
            }

            .stat-icon {
                margin-top: 12px;
            }
        }
    </style>
</head>

<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="adminSidebar">
    <a href="index.php" class="brand">NovaMart<span>.</span></a>

    <div class="sidebar-label">Main Menu</div>

    <a href="index.php" class="sidebar-link">
        <span class="sidebar-icon">▦</span> Dashboard
    </a>

    <a href="products.php" class="sidebar-link">
        <span class="sidebar-icon">▣</span> Products
    </a>

    <a href="inventory.php" class="sidebar-link active">
        <span class="sidebar-icon">▤</span> Inventory
    </a>

    <a href="orders.php" class="sidebar-link">
        <span class="sidebar-icon">🛍</span> Orders
    </a>

    <a href="categories.php" class="sidebar-link">
        <span class="sidebar-icon">◫</span> Categories
    </a>

    <a href="customers.php" class="sidebar-link">
        <span class="sidebar-icon">♙</span> Customers
    </a>

    <a href="reports.php" class="sidebar-link">
        <span class="sidebar-icon">▥</span> Sales & Reports
    </a>

    <div class="sidebar-label">Store</div>

    <a href="../index.php" class="sidebar-link" target="_blank" rel="noopener">
        <span class="sidebar-icon">↗</span> View Store
    </a>

    <div class="sidebar-bottom">
        <a href="../index.php" class="sidebar-link">
            <span class="sidebar-icon">←</span> Back to Store
        </a>
    </div>
</aside>

<!-- Main -->
<div class="main-content">

    <header class="topbar">
        <div class="topbar-left">
            <button type="button" class="mobile-menu-button"
                    id="mobileMenuButton" aria-label="Open navigation">
                ☰
            </button>

            <div>
                <div class="topbar-title">Inventory Management</div>
                <div class="topbar-subtitle">
                    Stock monitoring, replenishment and movement tracking
                </div>
            </div>
        </div>

        <div class="admin-profile">
            <div class="admin-avatar">♙</div>
            <div class="admin-details">
                <div class="admin-name">Store Administrator</div>
                <div class="admin-role">Administrator</div>
            </div>
        </div>
    </header>

    <main class="page-content">

        <div class="page-heading">
            <div>
                <h1>Inventory Management</h1>
                <p>
                    Monitor stock levels, manage replenishment, and track
                    every inventory movement.
                </p>
            </div>

            <a href="#stockAdjustment" class="btn btn-primary-custom">
                + Adjust Stock
            </a>
        </div>

        <div class="info-banner">
            <strong>Inventory control:</strong>
            Low-stock products are identified using their individual minimum
            stock levels. Processing a pending sample order deducts its item
            quantities and creates a stock movement record.
            This is a demonstration system; its session data is not connected
            to your customer storefront.
        </div>

        <?php if ($notice !== ""): ?>
            <div class="alert alert-<?= e($noticeType) ?> alert-dismissible fade show"
                 role="alert">
                <?= e($notice) ?>
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Inventory overview -->
        <section class="stats-grid">

            <div class="stat-card">
                <div>
                    <div class="stat-label">Products Tracked</div>
                    <div class="stat-number"><?= $totalProducts ?></div>
                    <div class="stat-note">Products in this inventory</div>
                </div>
                <div class="stat-icon icon-blue">▣</div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Total Units in Stock</div>
                    <div class="stat-number"><?= number_format($totalUnits) ?></div>
                    <div class="stat-note">Across all products</div>
                </div>
                <div class="stat-icon icon-green">▤</div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Low-Stock Products</div>
                    <div class="stat-number"><?= $lowStockCount ?></div>
                    <div class="stat-note">At or below minimum stock</div>
                </div>
                <div class="stat-icon icon-orange">!</div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Out of Stock</div>
                    <div class="stat-number"><?= $outOfStockCount ?></div>
                    <div class="stat-note">Requires replenishment</div>
                </div>
                <div class="stat-icon icon-red">⌁</div>
            </div>

        </section>

        <!-- Current inventory table -->
        <section class="content-card" id="stockOverview">

            <div class="card-header-custom">
                <div>
                    <h2>Current Stock Overview</h2>
                    <p>
                        Check product quantities and configure minimum and
                        reorder levels.
                    </p>
                </div>

                <span class="category-pill">
                    <?= count($filteredProducts) ?> products shown
                </span>
            </div>

            <div class="filter-area">
                <form method="GET" action="inventory.php">
                    <div class="row g-2 align-items-end">

                        <div class="col-lg-4 col-md-6">
                            <label class="form-label" for="search">
                                Search Product
                            </label>
                            <input type="search" class="form-control"
                                   id="search" name="search"
                                   value="<?= e($search) ?>"
                                   placeholder="Product name or category">
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label" for="category">
                                Category
                            </label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= e($category) ?>"
                                        <?= $categoryFilter === $category
                                            ? "selected" : "" ?>>
                                        <?= e($category) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label" for="stock">
                                Stock Status
                            </label>
                            <select class="form-select" id="stock" name="stock">
                                <option value="">All Stock Levels</option>
                                <option value="healthy"
                                    <?= $stockFilter === "healthy" ? "selected" : "" ?>>
                                    Healthy Stock
                                </option>
                                <option value="low"
                                    <?= $stockFilter === "low" ? "selected" : "" ?>>
                                    Low Stock
                                </option>
                                <option value="out"
                                    <?= $stockFilter === "out" ? "selected" : "" ?>>
                                    Out of Stock
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6 d-grid">
                            <button type="submit" class="btn btn-primary-custom">
                                Apply Filters
                            </button>
                        </div>

                    </div>

                    <?php if ($search !== "" || $categoryFilter !== "" || $stockFilter !== ""): ?>
                        <div class="mt-3">
                            <a href="inventory.php" class="small fw-bold">
                                ✕ Clear filters
                            </a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (empty($filteredProducts)): ?>

                <div class="empty-state">
                    <strong>No matching products found</strong>
                    Try another search term or stock filter.
                </div>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Available Stock</th>
                                <th>Minimum Stock</th>
                                <th>Reorder Quantity</th>
                                <th>Stock Status</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($filteredProducts as $product): ?>
                            <?php
                                $stock = (int) $product["stock"];
                                $minimum = (int) $product["minimum"];
                                $reorder = (int) $product["reorder"];

                                if ($stock === 0) {
                                    $statusClass = "stock-out";
                                    $statusText = "Out of Stock";
                                    $barClass = "bar-out";
                                } elseif ($stock <= $minimum) {
                                    $statusClass = "stock-low";
                                    $statusText = "Low Stock";
                                    $barClass = "bar-low";
                                } else {
                                    $statusClass = "stock-good";
                                    $statusText = "Healthy";
                                    $barClass = "bar-good";
                                }

                                $barPercent = min(
                                    100,
                                    max(0, ($stock / max(1, $reorder)) * 100)
                                );

                                $imageName = basename((string) $product["image"]);
                            ?>

                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <div class="product-image">
                                            <?php if ($imageName !== ""): ?>
                                                <img
                                                    src="../../assets/images/<?= e($imageName) ?>"
                                                    alt="<?= e($product["name"]) ?>"
                                                    onerror="this.style.display='none'; this.parentElement.textContent='📦';">
                                            <?php else: ?>
                                                📦
                                            <?php endif; ?>
                                        </div>

                                        <div>
                                            <div class="product-name">
                                                <?= e($product["name"]) ?>
                                            </div>
                                            <div class="product-id">
                                                Product ID: #<?= (int) $product["id"] ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="category-pill">
                                        <?= e($product["category"]) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="stock-number">
                                        <?= number_format($stock) ?> units
                                    </div>

                                    <div class="stock-bar">
                                        <span class="<?= e($barClass) ?>"
                                              style="width: <?= $barPercent ?>%;"></span>
                                    </div>
                                </td>

                                <td>
                                    <?= $minimum ?> units
                                </td>

                                <td>
                                    <?= $reorder ?> units
                                </td>

                                <td>
                                    <span class="stock-status <?= e($statusClass) ?>">
                                        <?= e($statusText) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

            <div class="table-footer">
                <span>
                    Showing <?= count($filteredProducts) ?>
                    of <?= $totalProducts ?> products
                </span>

                <span>
                    Estimated stock value:
                    <strong><?= money($totalInventoryValue) ?></strong>
                </span>
            </div>

        </section>

        <!-- Stock thresholds -->
        <section class="content-card" id="stockThresholds">
            <div class="card-header-custom">
                <div>
                    <h2>Minimum Stock & Reorder Levels</h2>
                    <p>
                        Configure when each product should trigger a stock alert
                        and how many units to replenish.
                    </p>
                </div>
            </div>

            <div class="card-body-custom">
                <form method="POST" action="inventory.php"
                      onsubmit="return confirm('Save the minimum stock and reorder quantities for all products?');">

                    <input type="hidden" name="csrf_token"
                           value="<?= e($_SESSION["novamart_inventory_csrf"]) ?>">
                    <input type="hidden" name="action" value="save_thresholds">

                    <div class="table-responsive">
                        <table class="product-table" style="min-width: 650px;">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Current Stock</th>
                                    <th>Minimum Stock</th>
                                    <th>Reorder Quantity</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <div class="product-name">
                                            <?= e($product["name"]) ?>
                                        </div>
                                        <div class="product-id">
                                            <?= e($product["category"]) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= (int) $product["stock"] ?>
                                        </strong>
                                        units
                                    </td>

                                    <td>
                                        <input type="number"
                                               class="form-control threshold-input"
                                               name="minimum[<?= (int) $product["id"] ?>]"
                                               value="<?= (int) $product["minimum"] ?>"
                                               min="0" max="1000000" step="1"
                                               aria-label="Minimum stock for <?= e($product["name"]) ?>"
                                               required>
                                    </td>

                                    <td>
                                        <input type="number"
                                               class="form-control threshold-input"
                                               name="reorder[<?= (int) $product["id"] ?>]"
                                               value="<?= (int) $product["reorder"] ?>"
                                               min="1" max="1000000" step="1"
                                               aria-label="Reorder quantity for <?= e($product["name"]) ?>"
                                               required>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mt-4">
                        <div class="movement-note">
                            A low-stock alert appears when current stock is less
                            than or equal to the minimum stock level.
                        </div>

                        <button type="submit" class="btn btn-primary-custom">
                            Save Stock Levels
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Stock adjustment -->
        <section class="content-card" id="stockAdjustment">
            <div class="card-header-custom">
                <div>
                    <h2>Record Stock Movement</h2>
                    <p>
                        Record new deliveries, outgoing stock, or a physical
                        stock-count correction.
                    </p>
                </div>
            </div>

            <div class="card-body-custom">
                <form method="POST" action="inventory.php" id="adjustmentForm">
                    <input type="hidden" name="csrf_token"
                           value="<?= e($_SESSION["novamart_inventory_csrf"]) ?>">
                    <input type="hidden" name="action" value="adjust_stock">

                    <div class="row g-3">

                        <div class="col-lg-6">
                            <label class="form-label" for="adjustProduct">
                                Select Product *
                            </label>

                            <select class="form-select" id="adjustProduct"
                                    name="product_id" required>
                                <option value="">Choose a product</option>

                                <?php foreach ($products as $product): ?>
                                    <option value="<?= (int) $product["id"] ?>">
                                        <?= e($product["name"]) ?>
                                        — Current stock: <?= (int) $product["stock"] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="adjustQuantity">
                                Quantity *
                            </label>

                            <input type="number" class="form-control"
                                   id="adjustQuantity" name="quantity"
                                   min="1" max="1000000" step="1"
                                   placeholder="Enter the quantity"
                                   required>

                            <div class="form-text" id="quantityHint">
                                Enter the number of units to add or remove.
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label d-block">
                                Movement Type *
                            </label>

                            <div class="adjustment-type">

                                <div class="movement-choice">
                                    <input type="radio" id="typeIn"
                                           name="movement_type" value="IN"
                                           checked>
                                    <label for="typeIn">
                                        ↙ Stock In
                                        <div class="fw-normal mt-1">
                                            Receive stock
                                        </div>
                                    </label>
                                </div>

                                <div class="movement-choice">
                                    <input type="radio" id="typeOut"
                                           name="movement_type" value="OUT">
                                    <label for="typeOut">
                                        ↗ Stock Out
                                        <div class="fw-normal mt-1">
                                            Reduce stock
                                        </div>
                                    </label>
                                </div>

                                <div class="movement-choice">
                                    <input type="radio" id="typeCorrection"
                                           name="movement_type" value="CORRECTION">
                                    <label for="typeCorrection">
                                        ⚙ Correction
                                        <div class="fw-normal mt-1">
                                            Set actual stock
                                        </div>
                                    </label>
                                </div>

                            </div>

                            <div class="movement-note" id="movementHint">
                                Stock In increases the current inventory by the
                                quantity entered.
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="adjustReason">
                                Reason for Adjustment *
                            </label>

                            <input type="text" class="form-control"
                                   id="adjustReason" name="reason"
                                   maxlength="200"
                                   placeholder="e.g. Supplier delivery, damaged goods, stock count"
                                   required>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mt-4">
                        <div class="movement-note">
                            Every successful adjustment is added to the movement history.
                        </div>

                        <button type="submit" class="btn btn-primary-custom">
                            Record Stock Movement
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Sample order processing -->
        <section class="content-card" id="orderProcessing">
            <div class="card-header-custom">
                <div>
                    <h2>Process Customer Orders</h2>
                    <p>
                        Demonstrate how processing an order reduces stock
                        quantities and records outgoing movements.
                    </p>
                </div>

                <span class="category-pill">
                    <?= $pendingOrderCountForDisplay ?> pending orders
                </span>
            </div>

            <div class="card-body-custom">

                <?php if (empty($orders)): ?>

                    <div class="empty-state">
                        <strong>No sample orders available</strong>
                    </div>

                <?php else: ?>

                    <?php foreach ($orders as $orderId => $order): ?>
                        <?php
                            $canProcess = $order["status"] === "Pending";
                            $orderHasStockIssue = false;
                            $orderStockProblems = [];

                            foreach ($order["items"] as $productId => $quantity) {
                                if (
                                    !isset($products[$productId]) ||
                                    $products[$productId]["stock"] < $quantity
                                ) {
                                    $orderHasStockIssue = true;

                                    if (!isset($products[$productId])) {
                                        $orderStockProblems[] =
                                            "A product in this order no longer exists.";
                                    } else {
                                        $orderStockProblems[] =
                                            $products[$productId]["name"]
                                            . ": requires " . $quantity
                                            . ", available "
                                            . $products[$productId]["stock"] . ".";
                                    }
                                }
                            }
                        ?>

                        <div class="order-card">
                            <div class="order-top">
                                <div>
                                    <div class="order-number">
                                        <?= e($orderId) ?>
                                    </div>

                                    <div class="order-customer">
                                        <?= e($order["customer"]) ?>
                                    </div>

                                    <div class="order-meta">
                                        <?= e(date("d M Y, h:i A", strtotime($order["date"]))) ?>
                                    </div>
                                </div>

                                <span class="order-status <?= $canProcess
                                    ? "order-pending"
                                    : "order-processed" ?>">
                                    <?= e($order["status"]) ?>
                                </span>
                            </div>

                            <div class="order-items">
                                <strong>Order items:</strong><br>

                                <?php foreach ($order["items"] as $productId => $quantity): ?>
                                    <?php if (isset($products[$productId])): ?>
                                        <?= e($products[$productId]["name"]) ?>
                                        × <?= (int) $quantity ?>
                                        (Stock: <?= (int) $products[$productId]["stock"] ?>)
                                        <br>
                                    <?php else: ?>
                                        Missing product #<?= (int) $productId ?>
                                        × <?= (int) $quantity ?><br>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                            <?php if ($canProcess): ?>

                                <?php if ($orderHasStockIssue): ?>
                                    <div class="order-warning">
                                        <strong>Cannot process yet:</strong>
                                        <?= e(implode(" ", $orderStockProblems)) ?>
                                        Replenish stock or correct the sample order first.
                                    </div>
                                <?php endif; ?>

                                <form method="POST" action="inventory.php"
                                      class="process-order-form">
                                    <input type="hidden" name="csrf_token"
                                           value="<?= e($_SESSION["novamart_inventory_csrf"]) ?>">
                                    <input type="hidden" name="action"
                                           value="process_order">
                                    <input type="hidden" name="order_id"
                                           value="<?= e($orderId) ?>">

                                    <button type="submit" class="btn-process"
                                        <?= $orderHasStockIssue ? "disabled" : "" ?>>
                                        ✓ Process Order & Deduct Stock
                                    </button>
                                </form>

                            <?php else: ?>

                                <div class="movement-note mt-3">
                                    Stock deduction completed for this sample order.
                                </div>

                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </section>

        <!-- Stock movement history -->
        <section class="content-card" id="movementHistory">

            <div class="card-header-custom">
                <div>
                    <h2>Stock Movement History</h2>
                    <p>
                        A record of incoming stock, outgoing stock, corrections,
                        and processed orders.
                    </p>
                </div>

                <span class="category-pill">
                    <?= $allHistoryCount ?> movements recorded
                </span>
            </div>

            <?php if (empty($recentHistory)): ?>

                <div class="empty-state">
                    <strong>No stock movements yet</strong>
                    Record a stock adjustment to create the first movement.
                </div>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="movement-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Quantity Changed</th>
                                <th>Previous Stock</th>
                                <th>New Stock</th>
                                <th>Reason</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($recentHistory as $movement): ?>
                            <?php
                                if ($movement["type"] === "IN") {
                                    $movementClass = "movement-in";
                                    $movementLabel = "IN";
                                    $quantityPrefix = "+";
                                } elseif ($movement["type"] === "OUT") {
                                    $movementClass = "movement-out";
                                    $movementLabel = "OUT";
                                    $quantityPrefix = "-";
                                } else {
                                    $movementClass = "movement-correction";
                                    $movementLabel = "FIX";
                                    $quantityPrefix = "";
                                }
                            ?>

                            <tr>
                                <td>
                                    <div class="history-date">
                                        <?= e(date("d M Y", strtotime($movement["date"]))) ?>
                                    </div>
                                    <div class="history-date">
                                        <?= e(date("h:i A", strtotime($movement["date"]))) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="product-name">
                                        <?= e($movement["product_name"]) ?>
                                    </div>
                                    <div class="product-id">
                                        ID: #<?= (int) $movement["product_id"] ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="movement-pill <?= e($movementClass) ?>">
                                        <?= e($movementLabel) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="history-quantity">
                                        <?= e($quantityPrefix) ?>
                                        <?= number_format((int) $movement["quantity"]) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= number_format((int) $movement["before"]) ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= number_format((int) $movement["after"]) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= e($movement["reason"]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

            <div class="table-footer">
                <span>Showing the 10 most recent stock movements.</span>
                <span>Movement history is maintained in the current PHP session.</span>
            </div>
        </section>

        <div class="footer-note">
            NovaMart Administration · Inventory & Stock Control ·
            Codvanta Portfolio Demonstration
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("adminSidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const menuButton = document.getElementById("mobileMenuButton");

    function closeSidebar() {
        sidebar.classList.remove("open");
        overlay.classList.remove("open");
    }

    menuButton.addEventListener("click", function () {
        sidebar.classList.toggle("open");
        overlay.classList.toggle("open");
    });

    overlay.addEventListener("click", closeSidebar);

    const movementHint = document.getElementById("movementHint");
    const quantityHint = document.getElementById("quantityHint");
    const quantityInput = document.getElementById("adjustQuantity");

    function updateMovementHint() {
        const selected = document.querySelector(
            'input[name="movement_type"]:checked'
        );

        if (!selected) return;

        if (selected.value === "IN") {
            movementHint.textContent =
                "Stock In increases the current inventory by the quantity entered.";

            quantityHint.textContent =
                "Enter the number of new units received.";
            quantityInput.min = "1";
        } else if (selected.value === "OUT") {
            movementHint.textContent =
                "Stock Out reduces inventory. The system will prevent stock from becoming negative.";

            quantityHint.textContent =
                "Enter the number of units to remove.";
            quantityInput.min = "1";
        } else {
            movementHint.textContent =
                "Correction sets the stock to the actual physical count. Enter the final stock quantity, not the difference.";

            quantityHint.textContent =
                "Enter the actual total number of units currently on hand. Zero is allowed for a correction.";
            quantityInput.min = "0";
        }
    }

    document.querySelectorAll('input[name="movement_type"]').forEach(
        function (radio) {
            radio.addEventListener("change", updateMovementHint);
        }
    );

    updateMovementHint();

    const adjustmentForm = document.getElementById("adjustmentForm");

    adjustmentForm.addEventListener("submit", function (event) {
        const selected = document.querySelector(
            'input[name="movement_type"]:checked'
        );

        if (selected && selected.value === "CORRECTION") {
            quantityInput.required = true;
            quantityInput.min = "0";

            if (!confirm(
                "Set the product's current stock to the quantity entered? " +
                "This is the final physical stock count, not an addition."
            )) {
                event.preventDefault();
            }

            return;
        }

        quantityInput.min = "1";

        if (!adjustmentForm.checkValidity()) {
            event.preventDefault();
            adjustmentForm.reportValidity();
            return;
        }

        if (selected && selected.value === "OUT") {
            if (!confirm("Record this outgoing stock movement?")) {
                event.preventDefault();
            }
        }
    });

    document.querySelectorAll(".process-order-form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            if (!confirm(
                "Process this order and deduct its product quantities from inventory?"
            )) {
                event.preventDefault();
            }
        });
    });
});
</script>

</body>
</html>