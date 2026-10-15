<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart Shopping Cart
|--------------------------------------------------------------------------
| Demonstration features:
| - Display cart products
| - Update product quantities
| - Remove individual products
| - Clear the entire cart
| - Calculate subtotal, delivery fee and total
| - Continue shopping or proceed to checkout
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["novamart_cart"]) ||
    !is_array($_SESSION["novamart_cart"])) {
    $_SESSION["novamart_cart"] = [];
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function money($amount) {
    return "Rs. " . number_format((float)$amount, 2);
}

$notice = "";
$noticeType = "success";

/*
|--------------------------------------------------------------------------
| Process cart actions
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Update quantities
    if (isset($_POST["update_cart"])) {

        $quantities = $_POST["quantities"] ?? [];

        foreach ($_SESSION["novamart_cart"] as $key => &$item) {

            if (isset($quantities[$key])) {
                $quantity = filter_var(
                    $quantities[$key],
                    FILTER_VALIDATE_INT
                );

                if ($quantity !== false && $quantity >= 1 && $quantity <= 10) {
                    $item["quantity"] = $quantity;
                }
            }
        }

        unset($item);

        $notice = "Your shopping cart has been updated.";
    }

    // Remove one product
    elseif (isset($_POST["remove_item"])) {

        $removeKey = (string)($_POST["remove_key"] ?? "");

        if (array_key_exists(
            $removeKey,
            $_SESSION["novamart_cart"]
        )) {
            $removedName =
                $_SESSION["novamart_cart"][$removeKey]["name"]
                ?? "Product";

            unset($_SESSION["novamart_cart"][$removeKey]);

            $notice = $removedName .
                " has been removed from your cart.";
        } else {
            $notice = "The selected product was not found.";
            $noticeType = "warning";
        }
    }

    // Clear the entire cart
    elseif (isset($_POST["clear_cart"])) {

        $_SESSION["novamart_cart"] = [];

        $notice = "Your shopping cart has been cleared.";
    }
}

/*
|--------------------------------------------------------------------------
| Calculate cart totals
|--------------------------------------------------------------------------
*/

$cartItems = $_SESSION["novamart_cart"];

$cartCount = 0;
$subtotal = 0;

foreach ($cartItems as $item) {
    $quantity = max(0, (int)($item["quantity"] ?? 0));
    $price = max(0, (float)($item["price"] ?? 0));

    $cartCount += $quantity;
    $subtotal += $price * $quantity;
}

// Demonstration delivery policy
$freeDeliveryThreshold = 10000;
$deliveryFee = 0;

if ($cartCount > 0 && $subtotal < $freeDeliveryThreshold) {
    $deliveryFee = 350;
}

$total = $subtotal + $deliveryFee;

$amountForFreeDelivery = max(
    0,
    $freeDeliveryThreshold - $subtotal
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart | NovaMart</title>

    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --navy: #101d3a;
            --navy-light: #1c3158;
            --blue: #315efb;
            --blue-hover: #2448d8;
            --light-bg: #f5f7fb;
            --muted: #718096;
            --border: #e6eaf1;
            --green: #198754;
            --red: #dc3545;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f7f9fc;
            color: #263248;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .top-strip {
            background: var(--navy);
            color: #e7ecf7;
            padding: 9px 0;
            font-size: 12px;
        }

        .main-header {
            padding: 17px 0;
            border-bottom: 1px solid var(--border);
            background: white;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--navy);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .brand:hover {
            color: var(--navy);
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 13px;
            color: white;
            background: var(--blue);
            font-size: 22px;
        }

        .header-search {
            display: flex;
            width: 100%;
            max-width: 530px;
            overflow: hidden;
            border: 1px solid #dfe5ef;
            border-radius: 10px;
            background: #f8faff;
        }

        .header-search input {
            width: 100%;
            min-width: 0;
            padding: 12px 15px;
            border: none;
            outline: none;
            background: transparent;
        }

        .header-search button {
            padding: 0 18px;
            border: none;
            color: white;
            background: var(--blue);
            font-weight: 700;
        }

        .header-action {
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
        }

        .cart-badge {
            display: inline-grid;
            place-items: center;
            min-width: 22px;
            height: 22px;
            margin-left: 4px;
            border-radius: 50%;
            color: white;
            background: var(--blue);
            font-size: 12px;
        }

        .main-nav {
            border-bottom: 1px solid var(--border);
            background: white;
        }

        .main-nav a {
            display: inline-block;
            padding: 14px 18px;
            color: #44516a;
            font-size: 14px;
            font-weight: 700;
        }

        .main-nav a:hover {
            color: var(--blue);
        }

        .breadcrumb-area {
            padding: 18px 0;
            background: #eef2f8;
            font-size: 13px;
        }

        .breadcrumb-area a {
            color: var(--muted);
        }

        .breadcrumb-area .current {
            color: var(--navy);
            font-weight: 700;
        }

        .page-section {
            padding: 42px 0 70px;
        }

        .page-title {
            margin: 0;
            color: var(--navy);
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .page-subtitle {
            margin-top: 9px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .step-number {
            display: grid;
            place-items: center;
            width: 27px;
            height: 27px;
            border-radius: 50%;
            background: #e5eaf4;
            color: #6d7890;
        }

        .step.active {
            color: var(--blue);
        }

        .step.active .step-number {
            color: white;
            background: var(--blue);
        }

        .step-line {
            width: 24px;
            height: 1px;
            background: #d9dfeb;
        }

        .cart-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 18px rgba(16, 29, 58, .025);
        }

        .cart-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 21px 24px;
            border-bottom: 1px solid var(--border);
        }

        .cart-card-header h2 {
            margin: 0;
            color: var(--navy);
            font-size: 17px;
            font-weight: 800;
        }

        .item-count {
            color: var(--muted);
            font-size: 12px;
        }

        .clear-cart-button {
            padding: 0;
            border: none;
            color: #d44a58;
            background: transparent;
            font-size: 12px;
            font-weight: 700;
        }

        .clear-cart-button:hover {
            text-decoration: underline;
        }

        .cart-table-header {
            display: grid;
            grid-template-columns: minmax(0, 2.4fr) .8fr .8fr .7fr;
            gap: 15px;
            padding: 13px 24px;
            border-bottom: 1px solid var(--border);
            color: #8490a5;
            background: #fbfcff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        .cart-item {
            display: grid;
            grid-template-columns: minmax(0, 2.4fr) .8fr .8fr .7fr;
            align-items: center;
            gap: 15px;
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .product-image {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 92px;
            height: 92px;
            overflow: hidden;
            border-radius: 11px;
            background: #f1f4fa;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            padding: 8px;
            object-fit: contain;
        }

        .product-emoji {
            font-size: 38px;
        }

        .product-name {
            display: block;
            margin-bottom: 7px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .product-name:hover {
            color: var(--blue);
        }

        .product-category {
            margin-bottom: 9px;
            color: var(--muted);
            font-size: 11px;
        }

        .availability {
            color: var(--green);
            font-size: 11px;
            font-weight: 700;
        }

        .unit-price {
            color: #46536a;
            font-size: 13px;
            font-weight: 700;
        }

        .quantity-control {
            display: inline-flex;
            align-items: center;
            overflow: hidden;
            border: 1px solid #dce2ed;
            border-radius: 8px;
            background: white;
        }

        .quantity-control button {
            width: 30px;
            height: 35px;
            border: none;
            color: var(--navy);
            background: white;
            font-size: 18px;
        }

        .quantity-control button:hover {
            background: #eef2ff;
        }

        .quantity-control input {
            width: 35px;
            height: 35px;
            padding: 0;
            border: none;
            outline: none;
            color: var(--navy);
            background: white;
            text-align: center;
            font-size: 12px;
            font-weight: 800;
            appearance: textfield;
            -moz-appearance: textfield;
        }

        .quantity-control input::-webkit-inner-spin-button,
        .quantity-control input::-webkit-outer-spin-button {
            margin: 0;
            -webkit-appearance: none;
        }

        .line-total {
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .remove-button {
            display: inline-block;
            margin-top: 9px;
            padding: 0;
            border: none;
            color: #d44a58;
            background: transparent;
            font-size: 11px;
            font-weight: 700;
        }

        .remove-button:hover {
            text-decoration: underline;
        }

        .cart-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            padding: 19px 24px;
            border-top: 1px solid var(--border);
            background: #fbfcff;
        }

        .continue-link {
            color: var(--blue);
            font-size: 13px;
            font-weight: 800;
        }

        .btn-update {
            padding: 10px 17px;
            border: 1px solid #dce4ff;
            border-radius: 8px;
            color: var(--blue);
            background: white;
            font-size: 12px;
            font-weight: 800;
        }

        .btn-update:hover {
            background: #f0f3ff;
        }

        .summary-card {
            position: sticky;
            top: 20px;
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 18px rgba(16, 29, 58, .035);
        }

        .summary-title {
            margin: 0 0 23px;
            color: var(--navy);
            font-size: 19px;
            font-weight: 800;
        }

        .summary-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 17px;
            color: #69758b;
            font-size: 13px;
        }

        .summary-row strong {
            color: var(--navy);
            font-weight: 800;
            text-align: right;
        }

        .summary-divider {
            margin: 19px 0;
            border: none;
            border-top: 1px solid var(--border);
            opacity: 1;
        }

        .summary-total {
            margin-bottom: 21px;
            color: var(--navy);
            font-size: 15px;
            font-weight: 800;
        }

        .summary-total strong {
            font-size: 22px;
        }

        .btn-checkout {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 48px;
            padding: 13px 18px;
            border: none;
            border-radius: 9px;
            color: white;
            background: var(--blue);
            font-size: 14px;
            font-weight: 800;
            transition: .2s;
        }

        .btn-checkout:hover {
            color: white;
            background: var(--blue-hover);
            transform: translateY(-1px);
        }

        .secure-note {
            margin-top: 13px;
            color: var(--muted);
            text-align: center;
            font-size: 11px;
            line-height: 1.6;
        }

        .delivery-progress {
            margin: 22px 0;
            padding: 14px;
            border: 1px solid #d9f0e3;
            border-radius: 10px;
            background: #f0faf4;
        }

        .delivery-progress strong {
            display: block;
            margin-bottom: 7px;
            color: #187849;
            font-size: 12px;
        }

        .delivery-progress p {
            margin: 0;
            color: #527461;
            font-size: 11px;
            line-height: 1.6;
        }

        .progress-track {
            height: 6px;
            margin: 10px 0 7px;
            overflow: hidden;
            border-radius: 10px;
            background: #d6eadf;
        }

        .progress-fill {
            height: 100%;
            border-radius: 10px;
            background: #23975b;
        }

        .promo-note {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-top: 20px;
            padding-top: 17px;
            border-top: 1px solid var(--border);
        }

        .promo-note span {
            font-size: 20px;
        }

        .promo-note p {
            margin: 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .promo-note strong {
            color: var(--navy);
        }

        .empty-cart {
            padding: 65px 25px;
            text-align: center;
        }

        .empty-cart-icon {
            display: grid;
            place-items: center;
            width: 100px;
            height: 100px;
            margin: 0 auto 22px;
            border-radius: 50%;
            background: #edf1ff;
            font-size: 45px;
        }

        .empty-cart h2 {
            margin-bottom: 12px;
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
        }

        .empty-cart p {
            max-width: 420px;
            margin: 0 auto 24px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .btn-shop {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 22px;
            border-radius: 9px;
            color: white;
            background: var(--blue);
            font-size: 13px;
            font-weight: 800;
        }

        .btn-shop:hover {
            color: white;
            background: var(--blue-hover);
        }

        .benefit-strip {
            padding: 25px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: white;
        }

        .benefit {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .benefit-icon {
            font-size: 27px;
        }

        .benefit strong {
            display: block;
            color: var(--navy);
            font-size: 13px;
        }

        .benefit small {
            color: var(--muted);
            font-size: 11px;
        }

        .site-footer {
            padding: 42px 0 20px;
            color: #d7deec;
            background: var(--navy);
        }

        .site-footer h5 {
            margin-bottom: 15px;
            color: white;
            font-size: 15px;
            font-weight: 800;
        }

        .site-footer p,
        .site-footer a:not(.brand) {
            color: #b9c4d9;
            font-size: 13px;
            line-height: 2;
        }

        .site-footer a:hover {
            color: white;
        }

        .footer-bottom {
            margin-top: 30px;
            padding-top: 18px;
            border-top: 1px solid #2b3a58;
            color: #9eabc4;
            font-size: 12px;
        }

        @media (max-width: 991px) {
            .step-indicator {
                justify-content: flex-start;
                margin-top: 18px;
            }

            .summary-card {
                position: static;
            }
        }

        @media (max-width: 767px) {
            .brand {
                font-size: 23px;
            }

            .header-search {
                max-width: none;
                margin-top: 14px;
            }

            .header-action {
                font-size: 12px;
            }

            .main-nav {
                overflow-x: auto;
                white-space: nowrap;
            }

            .main-nav a {
                padding: 12px;
            }

            .page-section {
                padding: 28px 0 45px;
            }

            .page-title {
                font-size: 27px;
            }

            .cart-table-header {
                display: none;
            }

            .cart-item {
                grid-template-columns: 1fr auto;
                gap: 15px 10px;
                padding: 19px 15px;
            }

            .product-info {
                grid-column: 1 / -1;
                align-items: flex-start;
            }

            .product-image {
                width: 78px;
                height: 78px;
            }

            .cart-item .unit-price::before {
                display: block;
                margin-bottom: 5px;
                color: var(--muted);
                content: "Unit price";
                font-size: 10px;
                font-weight: 400;
            }

            .cart-item .quantity-column {
                justify-self: end;
                text-align: right;
            }

            .cart-item .quantity-column::before {
                display: block;
                margin-bottom: 5px;
                color: var(--muted);
                content: "Quantity";
                font-size: 10px;
            }

            .cart-item .total-column {
                grid-column: 1 / -1;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-top: 12px;
                border-top: 1px dashed var(--border);
            }

            .cart-item .total-column::before {
                color: var(--muted);
                content: "Item total";
                font-size: 11px;
            }

            .cart-actions {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<!-- Top announcement -->
<div class="top-strip">
    <div class="container d-flex justify-content-between gap-2 flex-wrap">
        <span>Welcome to NovaMart — your everyday shopping destination</span>
        <span>Simple shopping · Convenient checkout</span>
    </div>
</div>

<!-- Header -->
<header class="main-header">
    <div class="container">
        <div class="row align-items-center g-3">

            <div class="col-6 col-lg-3">
                <a href="index.php" class="brand">
                    <span class="brand-icon">N</span>
                    <span>NovaMart</span>
                </a>
            </div>

            <div class="col-12 col-lg-6 order-3 order-lg-2">
                <form action="shop.php" method="GET" class="header-search">
                    <input
                        type="search"
                        name="search"
                        placeholder="Search products, brands and more..."
                        aria-label="Search products"
                    >
                    <button type="submit">Search</button>
                </form>
            </div>

            <div class="col-6 col-lg-3 order-2 order-lg-3">
                <div class="d-flex justify-content-end align-items-center gap-3">
                    <a href="login.php" class="header-action">
                        <span class="d-none d-sm-inline">Sign in</span>
                        <span class="d-sm-none">Account</span>
                    </a>

                    <a href="cart.php" class="header-action">
                        🛒 Cart
                        <span class="cart-badge"><?= (int)$cartCount ?></span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Navigation -->
<nav class="main-nav">
    <div class="container">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop All</a>
        <a href="shop.php?category=Electronics">Electronics</a>
        <a href="shop.php?category=Computing">Computing</a>
        <a href="shop.php?category=Fashion">Fashion</a>
        <a href="shop.php?deals=1">Special Deals</a>
    </div>
</nav>

<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <a href="index.php">Home</a>
        <span class="mx-2">›</span>
        <a href="shop.php">Shop</a>
        <span class="mx-2">›</span>
        <span class="current">Shopping Cart</span>
    </div>
</div>

<main>
    <section class="page-section">
        <div class="container">

            <!-- Heading -->
            <div class="row align-items-start mb-4">
                <div class="col-lg-7">
                    <h1 class="page-title">Shopping Cart</h1>
                    <p class="page-subtitle">
                        Review your selected products before proceeding to checkout.
                    </p>
                </div>

                <div class="col-lg-5">
                    <div class="step-indicator">
                        <div class="step active">
                            <span class="step-number">1</span>
                            <span>Cart</span>
                        </div>

                        <span class="step-line"></span>

                        <div class="step">
                            <span class="step-number">2</span>
                            <span>Checkout</span>
                        </div>

                        <span class="step-line"></span>

                        <div class="step">
                            <span class="step-number">3</span>
                            <span>Complete</span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($notice !== ""): ?>
                <div class="alert alert-<?= e($noticeType) ?> alert-dismissible fade show"
                     role="alert">
                    <?= e($notice) ?>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($cartItems)): ?>

                <!-- Empty cart -->
                <div class="cart-card">
                    <div class="empty-cart">
                        <div class="empty-cart-icon">🛒</div>

                        <h2>Your shopping cart is empty</h2>

                        <p>
                            You haven't added any products yet.
                            Explore our catalogue and discover something you love.
                        </p>

                        <a href="shop.php" class="btn-shop">
                            Start Shopping →
                        </a>
                    </div>
                </div>

            <?php else: ?>

                <div class="row g-4">

                    <!-- Cart products -->
                    <div class="col-lg-8">
                        <div class="cart-card">

                            <div class="cart-card-header">
                                <div>
                                    <h2>Your Products</h2>
                                    <span class="item-count">
                                        <?= (int)$cartCount ?>
                                        <?= $cartCount === 1 ? "item" : "items" ?>
                                        in your cart
                                    </span>
                                </div>

                                <form method="POST"
                                      onsubmit="return confirm('Are you sure you want to remove all products from your cart?');">
                                    <button type="submit"
                                            name="clear_cart"
                                            class="clear-cart-button">
                                        Clear Cart
                                    </button>
                                </form>
                            </div>

                            <div class="cart-table-header">
                                <div>Product</div>
                                <div>Unit Price</div>
                                <div>Quantity</div>
                                <div>Subtotal</div>
                            </div>

                            <form method="POST" id="updateCartForm">

                                <?php foreach ($cartItems as $key => $item): ?>

                                    <?php
                                    $itemName = $item["name"] ?? "Product";
                                    $itemPrice = max(0, (float)($item["price"] ?? 0));
                                    $itemQuantity = max(
                                        1,
                                        min(10, (int)($item["quantity"] ?? 1))
                                    );
                                    $itemImage = $item["image"] ?? "";
                                    $itemEmoji = $item["emoji"] ?? "🛍️";
                                    $itemId = (int)($item["id"] ?? 0);
                                    $lineTotal = $itemPrice * $itemQuantity;
                                    ?>

                                    <div class="cart-item">

                                        <!-- Product information -->
                                        <div class="product-info">
                                            <a href="product_details.php?id=<?= $itemId ?>"
                                               class="product-image">

                                                <img
                                                    src="../assets/images/<?= e($itemImage) ?>"
                                                    alt="<?= e($itemName) ?>"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                                >

                                                <span class="product-emoji" style="display:none;">
                                                    <?= e($itemEmoji) ?>
                                                </span>
                                            </a>

                                            <div>
                                                <a href="product_details.php?id=<?= $itemId ?>"
                                                   class="product-name">
                                                    <?= e($itemName) ?>
                                                </a>

                                                <div class="product-category">
                                                    NovaMart product
                                                </div>

                                                <span class="availability">
                                                    ✓ Available in demo catalogue
                                                </span>

                                                <br>

                                                <button
                                                    type="submit"
                                                    form="removeForm<?= e($key) ?>"
                                                    class="remove-button">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Unit price -->
                                        <div class="unit-price">
                                            <?= money($itemPrice) ?>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="quantity-column">
                                            <div class="quantity-control">

                                                <button type="button"
                                                        class="decrease-quantity"
                                                        aria-label="Decrease quantity">
                                                    −
                                                </button>

                                                <input
                                                    type="number"
                                                    name="quantities[<?= e($key) ?>]"
                                                    value="<?= $itemQuantity ?>"
                                                    min="1"
                                                    max="10"
                                                    class="quantity-input"
                                                    aria-label="Quantity for <?= e($itemName) ?>"
                                                >

                                                <button type="button"
                                                        class="increase-quantity"
                                                        aria-label="Increase quantity">
                                                    +
                                                </button>

                                            </div>
                                        </div>

                                        <!-- Item subtotal -->
                                        <div class="total-column">
                                            <div class="line-total">
                                                <?= money($lineTotal) ?>
                                            </div>
                                        </div>

                                    </div>

                                <?php endforeach; ?>

                                <div class="cart-actions">
                                    <a href="shop.php" class="continue-link">
                                        ← Continue Shopping
                                    </a>

                                    <button type="submit"
                                            name="update_cart"
                                            class="btn-update">
                                        Update Cart
                                    </button>
                                </div>

                            </form>

                            <!-- Separate forms for removing individual items -->
                            <?php foreach ($cartItems as $key => $item): ?>
                                <form method="POST"
                                      id="removeForm<?= e($key) ?>"
                                      class="d-none">
                                    <input type="hidden"
                                           name="remove_key"
                                           value="<?= e($key) ?>">
                                    <button type="submit" name="remove_item">
                                        Remove
                                    </button>
                                </form>
                            <?php endforeach; ?>

                        </div>

                        <!-- Delivery information -->
                        <div class="mt-4 p-3 rounded-3 border bg-white">
                            <div class="d-flex align-items-start gap-3">
                                <span style="font-size:25px;">🚚</span>

                                <div>
                                    <strong style="color:var(--navy);font-size:13px;">
                                        Delivery information
                                    </strong>

                                    <p class="muted mb-0 mt-1"
                                       style="font-size:12px;line-height:1.7;">
                                        Delivery charges and availability are illustrative.
                                        Final delivery information would normally be confirmed
                                        during checkout.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order summary -->
                    <div class="col-lg-4">
                        <aside class="summary-card">

                            <h2 class="summary-title">Order Summary</h2>

                            <div class="summary-row">
                                <span>Subtotal (<?= (int)$cartCount ?> items)</span>
                                <strong><?= money($subtotal) ?></strong>
                            </div>

                            <div class="summary-row">
                                <span>Delivery fee</span>

                                <strong>
                                    <?php if ($deliveryFee === 0): ?>
                                        <span style="color:var(--green);">FREE</span>
                                    <?php else: ?>
                                        <?= money($deliveryFee) ?>
                                    <?php endif; ?>
                                </strong>
                            </div>

                            <hr class="summary-divider">

                            <div class="summary-row summary-total">
                                <span>Estimated Total</span>
                                <strong><?= money($total) ?></strong>
                            </div>

                            <a href="checkout.php" class="btn-checkout">
                                Proceed to Checkout →
                            </a>

                            <div class="secure-note">
                                🔒 Secure checkout demonstration<br>
                                No real payment will be processed by this page.
                            </div>

                            <!-- Free delivery progress -->
                            <div class="delivery-progress">

                                <?php if ($subtotal >= $freeDeliveryThreshold): ?>

                                    <strong>✓ You've unlocked free delivery!</strong>

                                    <p>
                                        Your order qualifies for the demonstration
                                        free-delivery offer.
                                    </p>

                                    <div class="progress-track">
                                        <div class="progress-fill" style="width:100%;"></div>
                                    </div>

                                <?php else: ?>

                                    <strong>You're close to free delivery!</strong>

                                    <p>
                                        Add <?= money($amountForFreeDelivery) ?> more
                                        to reach the free-delivery threshold.
                                    </p>

                                    <div class="progress-track">
                                        <?php
                                        $progress = min(
                                            100,
                                            ($subtotal / $freeDeliveryThreshold) * 100
                                        );
                                        ?>
                                        <div class="progress-fill"
                                             style="width:<?= $progress ?>%;"></div>
                                    </div>

                                    <p>
                                        Free delivery on orders of
                                        <?= money($freeDeliveryThreshold) ?> or more.
                                    </p>

                                <?php endif; ?>

                            </div>

                            <div class="promo-note">
                                <span>🏷️</span>

                                <p>
                                    <strong>Looking for a better deal?</strong><br>
                                    Explore the latest products and offers in our shop.
                                    Promotions shown in this demo are illustrative.
                                </p>
                            </div>

                            <a href="shop.php?deals=1"
                               class="d-block text-center mt-3"
                               style="color:var(--blue);font-size:12px;font-weight:800;">
                                Explore Special Deals →
                            </a>

                        </aside>
                    </div>

                </div>

            <?php endif; ?>

        </div>
    </section>
</main>

<!-- Benefits -->
<section class="benefit-strip">
    <div class="container">
        <div class="row g-4">

            <div class="col-6 col-lg-3">
                <div class="benefit">
                    <span class="benefit-icon">🚚</span>
                    <div>
                        <strong>Convenient Shopping</strong>
                        <small>Shop from anywhere</small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="benefit">
                    <span class="benefit-icon">💳</span>
                    <div>
                        <strong>Simple Checkout</strong>
                        <small>Easy ordering process</small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="benefit">
                    <span class="benefit-icon">🛍️</span>
                    <div>
                        <strong>Product Variety</strong>
                        <small>Explore product categories</small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="benefit">
                    <span class="benefit-icon">💬</span>
                    <div>
                        <strong>Customer Support</strong>
                        <small>Help when you need it</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Footer -->
<footer class="site-footer">
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-4">
                <a href="index.php" class="brand text-white mb-3">
                    <span class="brand-icon">N</span>
                    <span>NovaMart</span>
                </a>

                <p>
                    Your everyday shopping destination.
                    Discover electronics, computing products,
                    fashion and more.
                </p>
            </div>

            <div class="col-6 col-lg-2">
                <h5>Explore</h5>
                <a href="index.php" class="d-block">Home</a>
                <a href="shop.php" class="d-block">Shop All</a>
                <a href="shop.php?deals=1" class="d-block">Special Deals</a>
            </div>

            <div class="col-6 col-lg-3">
                <h5>Customer Service</h5>
                <a href="cart.php" class="d-block">Shopping Cart</a>
                <a href="login.php" class="d-block">My Account</a>
                <a href="shop.php" class="d-block">Browse Products</a>
            </div>

            <div class="col-lg-3">
                <h5>About NovaMart</h5>
                <p>
                    A demonstration e-commerce project created
                    for the Codvanta portfolio.
                </p>
            </div>

        </div>

        <div class="footer-bottom d-flex justify-content-between flex-wrap gap-2">
            <span>© <?= date("Y") ?> NovaMart. All rights reserved.</span>
            <span>Turning Vision into Digital Reality.</span>
        </div>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
document.querySelectorAll(".cart-item").forEach(function (item) {

    const quantityInput = item.querySelector(".quantity-input");
    const decreaseButton = item.querySelector(".decrease-quantity");
    const increaseButton = item.querySelector(".increase-quantity");

    if (!quantityInput || !decreaseButton || !increaseButton) {
        return;
    }

    decreaseButton.addEventListener("click", function () {
        const current = parseInt(quantityInput.value || "1", 10);
        quantityInput.value = Math.max(1, current - 1);
    });

    increaseButton.addEventListener("click", function () {
        const current = parseInt(quantityInput.value || "1", 10);
        quantityInput.value = Math.min(10, current + 1);
    });

    quantityInput.addEventListener("change", function () {
        let quantity = parseInt(quantityInput.value || "1", 10);

        if (isNaN(quantity)) {
            quantity = 1;
        }

        quantityInput.value = Math.max(1, Math.min(10, quantity));
    });
});
</script>

</body>
</html>