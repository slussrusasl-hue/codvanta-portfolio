<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart Admin - Product Management
|--------------------------------------------------------------------------
| Portfolio demonstration only.
| Product changes are stored in the PHP session, not a database.
*/

if (empty($_SESSION["novamart_admin_csrf"])) {
    $_SESSION["novamart_admin_csrf"] = bin2hex(random_bytes(32));
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function money($amount)
{
    return "Rs. " . number_format((float) $amount, 2);
}

/*
|--------------------------------------------------------------------------
| Sample product catalog
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["novamart_admin_products"])) {
    $_SESSION["novamart_admin_products"] = [
        1 => [
            "id" => 1,
            "name" => "Wireless Earbuds Pro",
            "description" => "Premium wireless earbuds with clear sound, a compact charging case, and comfortable everyday wear.",
            "category" => "Electronics",
            "price" => 8500,
            "stock" => 24,
            "image" => "earbuds.jpg",
            "active" => true
        ],
        2 => [
            "id" => 2,
            "name" => "Smart Watch Series 5",
            "description" => "A stylish smartwatch featuring activity tracking, notifications, and a bright touchscreen display.",
            "category" => "Accessories",
            "price" => 12500,
            "stock" => 8,
            "image" => "smartwatch.jpg",
            "active" => true
        ],
        3 => [
            "id" => 3,
            "name" => "UltraBook Laptop 14",
            "description" => "A lightweight laptop designed for productivity, online meetings, study, and everyday computing.",
            "category" => "Computing",
            "price" => 185000,
            "stock" => 5,
            "image" => "laptop.jpg",
            "active" => true
        ],
        4 => [
            "id" => 4,
            "name" => "Bluetooth Speaker",
            "description" => "Portable wireless speaker with rich audio, simple controls, and a compact travel-friendly design.",
            "category" => "Electronics",
            "price" => 6500,
            "stock" => 18,
            "image" => "speaker.jpg",
            "active" => true
        ],
        5 => [
            "id" => 5,
            "name" => "Mechanical Gaming Keyboard",
            "description" => "A responsive mechanical keyboard with comfortable keys and an attractive gaming-inspired design.",
            "category" => "Gaming",
            "price" => 11500,
            "stock" => 0,
            "image" => "keyboard.jpg",
            "active" => true
        ],
        6 => [
            "id" => 6,
            "name" => "Classic Casual Backpack",
            "description" => "A versatile backpack with practical storage compartments for commuting, work, and study.",
            "category" => "Fashion",
            "price" => 4200,
            "stock" => 32,
            "image" => "backpack.jpg",
            "active" => true
        ],
        7 => [
            "id" => 7,
            "name" => "Air Fryer 5L",
            "description" => "A convenient kitchen appliance with adjustable temperature controls and a spacious cooking basket.",
            "category" => "Home Appliances",
            "price" => 24500,
            "stock" => 11,
            "image" => "airfryer.jpg",
            "active" => true
        ],
        8 => [
            "id" => 8,
            "name" => "Wireless Mouse",
            "description" => "A compact wireless mouse for smooth everyday navigation at home, school, or the office.",
            "category" => "Computing",
            "price" => 2800,
            "stock" => 3,
            "image" => "mouse.jpg",
            "active" => false
        ],
        9 => [
            "id" => 9,
            "name" => "HD Webcam",
            "description" => "A desktop webcam suitable for online meetings, video calls, and remote learning.",
            "category" => "Computing",
            "price" => 7500,
            "stock" => 14,
            "image" => "webcam.jpg",
            "active" => true
        ],
        10 => [
            "id" => 10,
            "name" => "Fitness Resistance Bands",
            "description" => "A lightweight fitness set for stretching, home workouts, and strength exercises.",
            "category" => "Accessories",
            "price" => 1900,
            "stock" => 27,
            "image" => "fitness.jpg",
            "active" => true
        ],
        11 => [
            "id" => 11,
            "name" => "LED Desk Lamp",
            "description" => "A modern desk lamp designed to provide comfortable lighting for work and study.",
            "category" => "Home Appliances",
            "price" => 3900,
            "stock" => 7,
            "image" => "lamp.jpg",
            "active" => true
        ],
        12 => [
            "id" => 12,
            "name" => "Gaming Headset",
            "description" => "An over-ear headset with a built-in microphone for gaming, voice chat, and media.",
            "category" => "Gaming",
            "price" => 9800,
            "stock" => 16,
            "image" => "headset.jpg",
            "active" => true
        ]
    ];

    $_SESSION["novamart_admin_next_product_id"] = 13;
}

$products = &$_SESSION["novamart_admin_products"];

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

$lowStockLimit = 10;
$notice = "";
$noticeType = "success";
$errors = [];

/*
|--------------------------------------------------------------------------
| Handle product actions
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $submittedToken = $_POST["csrf_token"] ?? "";

    if (
        !is_string($submittedToken) ||
        !hash_equals($_SESSION["novamart_admin_csrf"], $submittedToken)
    ) {
        $errors[] = "Your session has expired. Refresh the page and try again.";
    } else {
        $action = $_POST["action"] ?? "";

        /*
        | Add or update a product
        */

        if ($action === "save_product") {
            $id = filter_var(
                $_POST["product_id"] ?? "",
                FILTER_VALIDATE_INT
            );

            $name = trim((string) ($_POST["name"] ?? ""));
            $description = trim((string) ($_POST["description"] ?? ""));
            $category = trim((string) ($_POST["category"] ?? ""));
            $priceInput = trim((string) ($_POST["price"] ?? ""));
            $stockInput = trim((string) ($_POST["stock"] ?? ""));
            $image = trim((string) ($_POST["image"] ?? ""));

            $isEdit = $id !== false && $id !== null && $id > 0;

            if ($isEdit && !isset($products[$id])) {
                $errors[] = "The selected product could not be found.";
            }

            if ($name === "" || strlen($name) < 2 || strlen($name) > 120) {
                $errors[] = "Product name must contain between 2 and 120 characters.";
            }

            if ($description === "" || strlen($description) < 5 || strlen($description) > 1000) {
                $errors[] = "Description must contain between 5 and 1000 characters.";
            }

            if (!in_array($category, $categories, true)) {
                $errors[] = "Please select a valid product category.";
            }

            if (
                $priceInput === "" ||
                !is_numeric($priceInput) ||
                (float) $priceInput <= 0 ||
                (float) $priceInput > 100000000
            ) {
                $errors[] = "Enter a valid product price greater than zero.";
            }

            if (
                $stockInput === "" ||
                filter_var($stockInput, FILTER_VALIDATE_INT) === false ||
                (int) $stockInput < 0 ||
                (int) $stockInput > 1000000
            ) {
                $errors[] = "Stock quantity must be a whole number between 0 and 1,000,000.";
            }

            if (
                $image !== "" &&
                (
                    strlen($image) > 150 ||
                    !preg_match('/^[a-zA-Z0-9_-]+(?:\.[a-zA-Z0-9]+)?$/', $image)
                )
            ) {
                $errors[] = "Enter a valid image filename, such as earbuds.jpg.";
            }

            if (empty($errors)) {
                if ($isEdit) {
                    $existing = $products[$id];

                    $products[$id] = [
                        "id" => $id,
                        "name" => $name,
                        "description" => $description,
                        "category" => $category,
                        "price" => (float) $priceInput,
                        "stock" => (int) $stockInput,
                        "image" => $image,
                        "active" => $existing["active"]
                    ];

                    $notice = "Product updated successfully.";
                } else {
                    $newId = (int) ($_SESSION["novamart_admin_next_product_id"] ?? 13);
                    $_SESSION["novamart_admin_next_product_id"] = $newId + 1;

                    $products[$newId] = [
                        "id" => $newId,
                        "name" => $name,
                        "description" => $description,
                        "category" => $category,
                        "price" => (float) $priceInput,
                        "stock" => (int) $stockInput,
                        "image" => $image,
                        "active" => true
                    ];

                    $notice = "New product added successfully.";
                }
            }
        }

        /*
        | Activate or deactivate
        */

        elseif ($action === "toggle_status") {
            $id = filter_var(
                $_POST["product_id"] ?? "",
                FILTER_VALIDATE_INT
            );

            if ($id !== false && $id !== null && isset($products[$id])) {
                $products[$id]["active"] = !$products[$id]["active"];

                $notice = $products[$id]["active"]
                    ? "Product activated successfully."
                    : "Product deactivated successfully.";
            } else {
                $errors[] = "Product not found.";
            }
        }

        /*
        | Delete a product
        */

        elseif ($action === "delete_product") {
            $id = filter_var(
                $_POST["product_id"] ?? "",
                FILTER_VALIDATE_INT
            );

            if ($id !== false && $id !== null && isset($products[$id])) {
                unset($products[$id]);
                $notice = "Product removed from the demonstration catalog.";
            } else {
                $errors[] = "Product not found.";
            }
        }

        else {
            $errors[] = "Invalid action.";
        }
    }

    if (!empty($errors)) {
        $notice = implode(" ", $errors);
        $noticeType = "danger";
    }

    /*
     * PRG pattern: refresh the page without repeating a POST action.
     * Keep the message in the session for the next request.
     */
    $_SESSION["novamart_admin_products_flash"] = [
        "message" => $notice,
        "type" => $noticeType
    ];

    header("Location: products.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Retrieve one-time feedback message
|--------------------------------------------------------------------------
*/

if (isset($_SESSION["novamart_admin_products_flash"])) {
    $flash = $_SESSION["novamart_admin_products_flash"];
    $notice = $flash["message"] ?? "";
    $noticeType = $flash["type"] ?? "success";

    unset($_SESSION["novamart_admin_products_flash"]);
}

/*
|--------------------------------------------------------------------------
| Search and filters
|--------------------------------------------------------------------------
*/

$search = trim((string) ($_GET["search"] ?? ""));
$categoryFilter = trim((string) ($_GET["category"] ?? ""));
$statusFilter = trim((string) ($_GET["status"] ?? ""));
$stockFilter = trim((string) ($_GET["stock"] ?? ""));

$filteredProducts = [];

foreach ($products as $product) {
    if (
        $search !== "" &&
        stripos(
            $product["name"] . " " .
            $product["description"] . " " .
            $product["category"],
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

    if ($statusFilter === "active" && !$product["active"]) {
        continue;
    }

    if ($statusFilter === "inactive" && $product["active"]) {
        continue;
    }

    if ($stockFilter === "low" && $product["stock"] > $lowStockLimit) {
        continue;
    }

    if ($stockFilter === "out" && $product["stock"] !== 0) {
        continue;
    }

    $filteredProducts[] = $product;
}

usort($filteredProducts, function ($a, $b) {
    return $b["id"] <=> $a["id"];
});

/*
|--------------------------------------------------------------------------
| Dashboard statistics
|--------------------------------------------------------------------------
*/

$totalProducts = count($products);
$activeProducts = 0;
$inactiveProducts = 0;
$lowStockProducts = 0;
$outOfStockProducts = 0;
$inventoryValue = 0;

foreach ($products as $product) {
    if ($product["active"]) {
        $activeProducts++;
    } else {
        $inactiveProducts++;
    }

    if ($product["stock"] <= $lowStockLimit) {
        $lowStockProducts++;
    }

    if ($product["stock"] === 0) {
        $outOfStockProducts++;
    }

    $inventoryValue += $product["price"] * $product["stock"];
}

$editProduct = null;

if (isset($_GET["edit"])) {
    $editId = filter_var($_GET["edit"], FILTER_VALIDATE_INT);

    if ($editId !== false && $editId !== null && isset($products[$editId])) {
        $editProduct = $products[$editId];
    }
}

$isEditing = $editProduct !== null;
$formProduct = $editProduct ?? [
    "id" => "",
    "name" => "",
    "description" => "",
    "category" => "",
    "price" => "",
    "stock" => "",
    "image" => ""
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Product Management | NovaMart Admin</title>

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
            left: 0;
            top: 0;
            z-index: 1000;
            background: var(--navy-dark);
            color: white;
            padding: 25px 16px;
            overflow-y: auto;
        }

        .brand {
            color: white;
            font-size: 25px;
            font-weight: 900;
            letter-spacing: -.7px;
            padding: 0 13px;
            display: block;
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
            height: 78px;
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
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
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .page-heading h1 {
            font-size: 27px;
            font-weight: 900;
            color: var(--navy);
            margin: 0 0 8px;
        }

        .page-heading p {
            font-size: 13px;
            color: var(--muted);
            margin: 0;
        }

        .btn-primary-custom {
            background: var(--blue);
            color: white;
            border: 0;
            padding: 12px 17px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 800;
            transition: .2s;
        }

        .btn-primary-custom:hover {
            background: #1554d1;
            color: white;
            transform: translateY(-1px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 25px;
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
            line-height: 1.1;
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
            margin-bottom: 25px;
        }

        .card-header-custom {
            padding: 21px 23px;
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
        }

        .card-body-custom {
            padding: 23px;
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

        textarea.form-control {
            min-height: 95px;
        }

        .form-hint {
            color: var(--muted);
            font-size: 11px;
            margin-top: 6px;
        }

        .btn-secondary-custom {
            background: #f0f3f8;
            color: #47556b;
            border: 1px solid #e1e6ef;
            border-radius: 9px;
            padding: 10px 15px;
            font-size: 13px;
            font-weight: 800;
        }

        .btn-secondary-custom:hover {
            background: #e4eaf4;
            color: var(--navy);
        }

        .filter-area {
            padding: 20px 23px;
            border-bottom: 1px solid var(--border);
            background: #fcfdff;
        }

        .filter-area .form-control,
        .filter-area .form-select {
            background-color: white;
        }

        .table-responsive {
            width: 100%;
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
            letter-spacing: .6px;
            font-weight: 900;
            padding: 15px 18px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .product-table tbody td {
            padding: 16px 18px;
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
            gap: 12px;
            min-width: 235px;
        }

        .product-image {
            width: 55px;
            height: 55px;
            border: 1px solid var(--border);
            background: #f7f9fd;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            font-size: 24px;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .product-name {
            color: var(--navy);
            font-size: 13px;
            font-weight: 900;
            margin-bottom: 5px;
        }

        .product-description {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.5;
            max-width: 270px;
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

        .price-value {
            color: var(--navy);
            font-size: 13px;
            font-weight: 900;
            white-space: nowrap;
        }

        .stock-value {
            color: var(--navy);
            font-weight: 900;
            font-size: 13px;
        }

        .stock-status {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 6px;
            margin-top: 5px;
            font-size: 10px;
            font-weight: 800;
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

        .status-pill {
            display: inline-block;
            border-radius: 30px;
            padding: 6px 10px;
            font-size: 10px;
            font-weight: 900;
            white-space: nowrap;
        }

        .status-active {
            color: #0c8055;
            background: #e5f8ef;
        }

        .status-inactive {
            color: #68758b;
            background: #eef1f5;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn {
            width: 33px;
            height: 33px;
            border: 1px solid #e3e8f0;
            border-radius: 8px;
            display: inline-grid;
            place-items: center;
            background: white;
            color: #4f607b;
            font-size: 14px;
            cursor: pointer;
            transition: .2s;
        }

        .action-btn:hover {
            background: var(--blue-light);
            border-color: #c8d9ff;
            color: var(--blue);
        }

        .action-btn.danger:hover {
            color: var(--red);
            background: #fff0f0;
            border-color: #ffd0d0;
        }

        .action-btn.success:hover {
            color: var(--green);
            background: #e9f9f1;
            border-color: #c8efdd;
        }

        .inline-form {
            display: inline;
            margin: 0;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 68px;
            height: 68px;
            margin: 0 auto 18px;
            border-radius: 18px;
            background: var(--blue-light);
            display: grid;
            place-items: center;
            font-size: 29px;
        }

        .empty-state h3 {
            color: var(--navy);
            font-size: 18px;
            font-weight: 900;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 13px;
        }

        .image-preview {
            width: 85px;
            height: 85px;
            border: 1px dashed #cbd5e4;
            background: #f8faff;
            border-radius: 12px;
            display: grid;
            place-items: center;
            overflow: hidden;
            font-size: 25px;
            margin-top: 10px;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .alert {
            border-radius: 11px;
            font-size: 13px;
        }

        .alert ul {
            margin-bottom: 0;
        }

        .catalog-footer {
            padding: 18px 23px;
            background: #fcfdff;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 12px;
            color: var(--muted);
        }

        .info-banner {
            background: #eef4ff;
            border: 1px solid #d9e5ff;
            border-radius: 12px;
            padding: 14px 16px;
            color: #3d5680;
            font-size: 12px;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        .info-banner strong {
            color: var(--navy);
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

        @media (max-width: 1200px) {
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
                background: rgba(7, 20, 40, .48);
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                height: 70px;
                padding: 0 17px;
            }

            .mobile-menu-button {
                display: inline-grid;
                place-items: center;
            }

            .topbar-left {
                display: flex;
                align-items: center;
                gap: 12px;
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

            .stats-grid {
                gap: 10px;
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
        }

        @media (max-width: 430px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .stat-card {
                display: block;
            }

            .stat-icon {
                margin-top: 12px;
            }

            .page-heading .btn-primary-custom {
                width: 100%;
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
        <span class="sidebar-icon">▦</span>
        Dashboard
    </a>

    <a href="products.php" class="sidebar-link active">
        <span class="sidebar-icon">▣</span>
        Products
    </a>

    <a href="inventory.php" class="sidebar-link">
        <span class="sidebar-icon">▤</span>
        Inventory
    </a>

    <a href="orders.php" class="sidebar-link">
        <span class="sidebar-icon">🛍</span>
        Orders
    </a>

    <a href="categories.php" class="sidebar-link">
        <span class="sidebar-icon">◫</span>
        Categories
    </a>

    <a href="customers.php" class="sidebar-link">
        <span class="sidebar-icon">♙</span>
        Customers
    </a>

    <a href="reports.php" class="sidebar-link">
        <span class="sidebar-icon">▥</span>
        Sales & Reports
    </a>

    <div class="sidebar-label">Store</div>

    <a href="../index.php" class="sidebar-link" target="_blank" rel="noopener">
        <span class="sidebar-icon">↗</span>
        View Store
    </a>

    <div class="sidebar-bottom">
        <a href="../index.php" class="sidebar-link">
            <span class="sidebar-icon">←</span>
            Back to Store
        </a>
    </div>
</aside>

<!-- Main content -->
<div class="main-content">

    <!-- Top bar -->
    <header class="topbar">
        <div class="topbar-left">
            <button type="button" class="mobile-menu-button"
                    id="mobileMenuButton" aria-label="Open navigation">
                ☰
            </button>

            <div>
                <div class="topbar-title">Product Management</div>
                <div class="topbar-subtitle">
                    Manage your store's product catalog
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

        <!-- Page heading -->
        <div class="page-heading">
            <div>
                <h1>Products</h1>
                <p>Add, update, and manage products available in your store.</p>
            </div>

            <a href="#productForm" class="btn btn-primary-custom">
                + Add New Product
            </a>
        </div>

        <div class="info-banner">
            <strong>Demonstration mode:</strong>
            Product changes are stored in your PHP session. They are useful for
            exploring the administrator workflow but are not permanently saved
            to a database. The sample catalog is initialized when the session
            does not already contain one.
        </div>

        <?php if ($notice !== ""): ?>
            <div class="alert alert-<?= e($noticeType) ?> alert-dismissible fade show"
                 role="alert">
                <?= e($notice) ?>

                <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <section class="stats-grid">
            <div class="stat-card">
                <div>
                    <div class="stat-label">Total Products</div>
                    <div class="stat-number"><?= $totalProducts ?></div>
                    <div class="stat-note">All catalog products</div>
                </div>
                <div class="stat-icon icon-blue">▣</div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Active Products</div>
                    <div class="stat-number"><?= $activeProducts ?></div>
                    <div class="stat-note">Currently enabled</div>
                </div>
                <div class="stat-icon icon-green">✓</div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Low Stock</div>
                    <div class="stat-number"><?= $lowStockProducts ?></div>
                    <div class="stat-note">Stock at or below <?= $lowStockLimit ?></div>
                </div>
                <div class="stat-icon icon-orange">!</div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Out of Stock</div>
                    <div class="stat-number"><?= $outOfStockProducts ?></div>
                    <div class="stat-note">Products with zero stock</div>
                </div>
                <div class="stat-icon icon-red">⌁</div>
            </div>
        </section>

        <!-- Add/Edit Product Form -->
        <section class="content-card" id="productForm">
            <div class="card-header-custom">
                <div>
                    <h2><?= $isEditing ? "Edit Product" : "Add New Product" ?></h2>
                    <p>
                        <?= $isEditing
                            ? "Update the selected product's information."
                            : "Enter the details for a new catalog product." ?>
                    </p>
                </div>

                <?php if ($isEditing): ?>
                    <a href="products.php" class="btn btn-secondary-custom">
                        Cancel Editing
                    </a>
                <?php endif; ?>
            </div>

            <div class="card-body-custom">
                <form method="POST" action="products.php" id="productEditor">
                    <input type="hidden" name="csrf_token"
                           value="<?= e($_SESSION["novamart_admin_csrf"]) ?>">
                    <input type="hidden" name="action" value="save_product">
                    <input type="hidden" name="product_id"
                           value="<?= e($formProduct["id"]) ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="productName" class="form-label">
                                Product Name *
                            </label>

                            <input type="text" class="form-control"
                                   id="productName" name="name"
                                   value="<?= e($formProduct["name"]) ?>"
                                   placeholder="e.g. Wireless Headphones"
                                   minlength="2" maxlength="120" required>
                        </div>

                        <div class="col-md-6">
                            <label for="productCategory" class="form-label">
                                Category *
                            </label>

                            <select class="form-select" id="productCategory"
                                    name="category" required>
                                <option value="">Choose a category</option>

                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= e($category) ?>"
                                        <?= $formProduct["category"] === $category
                                            ? "selected"
                                            : "" ?>>
                                        <?= e($category) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="productDescription" class="form-label">
                                Product Description *
                            </label>

                            <textarea class="form-control"
                                      id="productDescription"
                                      name="description"
                                      placeholder="Describe the product's features and benefits..."
                                      minlength="5" maxlength="1000"
                                      required><?= e($formProduct["description"]) ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="productPrice" class="form-label">
                                Selling Price (Rs.) *
                            </label>

                            <input type="number" class="form-control"
                                   id="productPrice" name="price"
                                   value="<?= e($formProduct["price"]) ?>"
                                   placeholder="0.00" min="0.01"
                                   max="100000000" step="0.01" required>
                        </div>

                        <div class="col-md-4">
                            <label for="productStock" class="form-label">
                                Stock Quantity *
                            </label>

                            <input type="number" class="form-control"
                                   id="productStock" name="stock"
                                   value="<?= e($formProduct["stock"]) ?>"
                                   placeholder="Available units"
                                   min="0" max="1000000" step="1" required>
                        </div>

                        <div class="col-md-4">
                            <label for="productImage" class="form-label">
                                Product Image Filename
                            </label>

                            <input type="text" class="form-control"
                                   id="productImage" name="image"
                                   value="<?= e($formProduct["image"]) ?>"
                                   placeholder="e.g. earbuds.jpg"
                                   maxlength="150"
                                   pattern="[a-zA-Z0-9_-]+(\.[a-zA-Z0-9]+)?">

                            <div class="form-hint">
                                Enter a filename from the shared assets/images folder.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-primary-custom">
                            <?= $isEditing ? "✓ Save Product Changes" : "+ Add Product" ?>
                        </button>

                        <?php if ($isEditing): ?>
                            <a href="products.php" class="btn btn-secondary-custom">
                                Cancel
                            </a>
                        <?php else: ?>
                            <button type="reset" class="btn btn-secondary-custom">
                                Clear Form
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </section>

        <!-- Product catalog -->
        <section class="content-card" id="productCatalog">
            <div class="card-header-custom">
                <div>
                    <h2>Product Catalog</h2>
                    <p>View and manage every product in the store.</p>
                </div>

                <span class="category-pill">
                    <?= count($filteredProducts) ?> products shown
                </span>
            </div>

            <!-- Search and filters -->
            <div class="filter-area">
                <form method="GET" action="products.php">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label for="search" class="form-label">
                                Search Products
                            </label>

                            <input type="search" class="form-control"
                                   id="search" name="search"
                                   value="<?= e($search) ?>"
                                   placeholder="Search by name or description...">
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="categoryFilter" class="form-label">
                                Category
                            </label>

                            <select class="form-select" id="categoryFilter"
                                    name="category">
                                <option value="">All Categories</option>

                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= e($category) ?>"
                                        <?= $categoryFilter === $category
                                            ? "selected"
                                            : "" ?>>
                                        <?= e($category) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label for="statusFilter" class="form-label">
                                Status
                            </label>

                            <select class="form-select" id="statusFilter" name="status">
                                <option value="">All Statuses</option>
                                <option value="active"
                                    <?= $statusFilter === "active" ? "selected" : "" ?>>
                                    Active
                                </option>
                                <option value="inactive"
                                    <?= $statusFilter === "inactive" ? "selected" : "" ?>>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label for="stockFilter" class="form-label">
                                Stock
                            </label>

                            <select class="form-select" id="stockFilter" name="stock">
                                <option value="">All Stock</option>
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

                        <div class="col-lg-1 col-md-4 d-grid">
                            <button type="submit" class="btn btn-primary-custom">
                                Filter
                            </button>
                        </div>
                    </div>

                    <?php if (
                        $search !== "" ||
                        $categoryFilter !== "" ||
                        $statusFilter !== "" ||
                        $stockFilter !== ""
                    ): ?>
                        <div class="mt-3">
                            <a href="products.php" class="small fw-bold">
                                ✕ Clear all filters
                            </a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (empty($filteredProducts)): ?>
                <div class="empty-state">
                    <div class="empty-icon">🔎</div>
                    <h3>No Products Found</h3>
                    <p>Try changing your search terms or filters.</p>

                    <a href="products.php" class="btn btn-primary-custom">
                        View All Products
                    </a>
                </div>
            <?php else: ?>

                <div class="table-responsive">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($filteredProducts as $product): ?>
                            <?php
                                if ($product["stock"] === 0) {
                                    $stockClass = "stock-out";
                                    $stockLabel = "Out of Stock";
                                } elseif ($product["stock"] <= $lowStockLimit) {
                                    $stockClass = "stock-low";
                                    $stockLabel = "Low Stock";
                                } else {
                                    $stockClass = "stock-good";
                                    $stockLabel = "In Stock";
                                }

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

                                            <div class="product-description">
                                                <?= e($product["description"]) ?>
                                            </div>

                                            <div class="text-muted mt-1"
                                                 style="font-size:10px;">
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
                                    <span class="price-value">
                                        <?= money($product["price"]) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="stock-value">
                                        <?= (int) $product["stock"] ?> units
                                    </div>

                                    <span class="stock-status <?= e($stockClass) ?>">
                                        <?= e($stockLabel) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($product["active"]): ?>
                                        <span class="status-pill status-active">
                                            ● Active
                                        </span>
                                    <?php else: ?>
                                        <span class="status-pill status-inactive">
                                            ● Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <a href="products.php?edit=<?= (int) $product["id"] ?>#productForm"
                                           class="action-btn"
                                           title="Edit product"
                                           aria-label="Edit <?= e($product["name"]) ?>">
                                            ✎
                                        </a>

                                        <form method="POST" action="products.php"
                                              class="inline-form status-form">
                                            <input type="hidden" name="csrf_token"
                                                   value="<?= e($_SESSION["novamart_admin_csrf"]) ?>">
                                            <input type="hidden" name="action"
                                                   value="toggle_status">
                                            <input type="hidden" name="product_id"
                                                   value="<?= (int) $product["id"] ?>">

                                            <button type="submit"
                                                    class="action-btn <?= $product["active"] ? "" : "success" ?>"
                                                    title="<?= $product["active"] ? "Deactivate product" : "Activate product" ?>"
                                                    aria-label="<?= $product["active"] ? "Deactivate" : "Activate" ?> <?= e($product["name"]) ?>">
                                                <?= $product["active"] ? "⏻" : "✓" ?>
                                            </button>
                                        </form>

                                        <form method="POST" action="products.php"
                                              class="inline-form delete-form">
                                            <input type="hidden" name="csrf_token"
                                                   value="<?= e($_SESSION["novamart_admin_csrf"]) ?>">
                                            <input type="hidden" name="action"
                                                   value="delete_product">
                                            <input type="hidden" name="product_id"
                                                   value="<?= (int) $product["id"] ?>">

                                            <button type="submit"
                                                    class="action-btn danger"
                                                    title="Remove product"
                                                    aria-label="Remove <?= e($product["name"]) ?>">
                                                🗑
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

            <div class="catalog-footer">
                <span>
                    Showing <?= count($filteredProducts) ?>
                    of <?= $totalProducts ?> catalog products
                </span>

                <span>
                    Estimated stock value:
                    <strong><?= money($inventoryValue) ?></strong>
                </span>
            </div>
        </section>

        <div class="text-center text-muted small pb-4">
            NovaMart Administration · Product Catalog Management · Codvanta Demo
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

    document.querySelectorAll(".delete-form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            const productRow = form.closest("tr");
            const productName =
                productRow?.querySelector(".product-name")?.textContent.trim()
                || "this product";

            if (!confirm(
                "Are you sure you want to remove \"" + productName +
                "\" from the demonstration catalog?"
            )) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll(".status-form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            const button = form.querySelector("button");
            const isDeactivating = button.title === "Deactivate product";

            if (isDeactivating && !confirm(
                "Deactivate this product? It will remain in the catalog but be marked inactive."
            )) {
                event.preventDefault();
            }
        });
    });

    const productImage = document.getElementById("productImage");

    if (productImage) {
        productImage.addEventListener("input", function () {
            // Image filenames are stored as catalog data in this demo.
            // No file is uploaded or written to the server.
        });
    }
});
</script>

</body>
</html>
