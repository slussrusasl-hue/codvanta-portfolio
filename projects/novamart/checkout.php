
<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart - Demonstration Checkout Page
|--------------------------------------------------------------------------
| Demo only: no database, real payment processing, or actual order delivery.
*/

if (!isset($_SESSION["novamart_cart"]) || !is_array($_SESSION["novamart_cart"])) {
    $_SESSION["novamart_cart"] = [];
}

if (empty($_SESSION["novamart_csrf"])) {
    $_SESSION["novamart_csrf"] = bin2hex(random_bytes(32));
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function money($amount)
{
    return "Rs. " . number_format((float) $amount, 2);
}

function calculateCart($cart)
{
    $subtotal = 0;
    $quantity = 0;

    foreach ($cart as $item) {
        $price = (float) ($item["price"] ?? 0);
        $itemQuantity = max(1, (int) ($item["quantity"] ?? 1));

        $subtotal += $price * $itemQuantity;
        $quantity += $itemQuantity;
    }

    $delivery = $subtotal == 0 ? 0 : ($subtotal >= 10000 ? 0 : 350);

    return [
        "subtotal" => $subtotal,
        "quantity" => $quantity,
        "delivery" => $delivery,
        "total" => $subtotal + $delivery
    ];
}

/* Show the order confirmation after a successful demo checkout. */
if (isset($_GET["success"]) && $_GET["success"] === "1") {
    $completedOrder = $_SESSION["novamart_last_order"] ?? null;

    if (!$completedOrder) {
        header("Location: cart.php");
        exit;
    }

    $showSuccess = true;
} else {
    $showSuccess = false;
}

/* Redirect users who have no items in their cart. */
if (!$showSuccess && empty($_SESSION["novamart_cart"])) {
    header("Location: cart.php");
    exit;
}

$cart = $_SESSION["novamart_cart"];
$summary = calculateCart($cart);

$errors = [];

$form = [
    "full_name" => "",
    "email" => "",
    "phone" => "",
    "address" => "",
    "city" => "",
    "postal_code" => "",
    "delivery_note" => "",
    "payment_method" => "cod"
];

/* Process the checkout form. */
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$showSuccess) {
    foreach ($form as $field => $default) {
        if (isset($_POST[$field]) && is_string($_POST[$field])) {
            $form[$field] = trim($_POST[$field]);
        }
    }

    $submittedToken = $_POST["csrf_token"] ?? "";

    if (
        !is_string($submittedToken) ||
        !hash_equals($_SESSION["novamart_csrf"], $submittedToken)
    ) {
        $errors[] = "Your session has expired. Please refresh the page and try again.";
    }

    if ($form["full_name"] === "" || strlen($form["full_name"]) < 2) {
        $errors[] = "Please enter your full name.";
    }

    if (!filter_var($form["email"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $form["phone"])) {
        $errors[] = "Please enter a valid phone number.";
    }

    if ($form["address"] === "" || strlen($form["address"]) < 5) {
        $errors[] = "Please enter your delivery address.";
    }

    if ($form["city"] === "") {
        $errors[] = "Please enter your city.";
    }

    if (
        $form["postal_code"] !== "" &&
        !preg_match('/^[a-zA-Z0-9\s-]{3,12}$/', $form["postal_code"])
    ) {
        $errors[] = "Please enter a valid postal code.";
    }

    $allowedPaymentMethods = ["cod", "bank", "card"];

    if (!in_array($form["payment_method"], $allowedPaymentMethods, true)) {
        $errors[] = "Please select a valid payment method.";
    }

    /* Recalculate the order using the current session cart. */
    $cart = $_SESSION["novamart_cart"];
    $summary = calculateCart($cart);

    if (empty($cart)) {
        $errors[] = "Your cart is empty. Please add products before checkout.";
    }

    if (empty($errors)) {
        $orderNumber = "NM-" . date("ymd") . "-" . strtoupper(bin2hex(random_bytes(3)));

        $paymentLabels = [
            "cod" => "Cash on Delivery",
            "bank" => "Bank Transfer",
            "card" => "Card Payment (Demo)"
        ];

        $orderItems = [];

        foreach ($cart as $item) {
            $orderItems[] = [
                "name" => $item["name"] ?? "Product",
                "price" => (float) ($item["price"] ?? 0),
                "quantity" => max(1, (int) ($item["quantity"] ?? 1)),
                "image" => $item["image"] ?? "",
                "emoji" => $item["emoji"] ?? "📦"
            ];
        }

        $completedOrder = [
            "order_number" => $orderNumber,
            "date" => date("d M Y, h:i A"),
            "customer" => $form["full_name"],
            "email" => $form["email"],
            "phone" => $form["phone"],
            "address" => $form["address"],
            "city" => $form["city"],
            "postal_code" => $form["postal_code"],
            "delivery_note" => $form["delivery_note"],
            "payment_method" => $paymentLabels[$form["payment_method"]],
            "items" => $orderItems,
            "subtotal" => $summary["subtotal"],
            "delivery" => $summary["delivery"],
            "total" => $summary["total"],
            "status" => "Order Placed"
        ];

        /*
         * Demo order: save the confirmation in the session.
         * A real system should save orders in a database.
         */
        $_SESSION["novamart_last_order"] = $completedOrder;
        $_SESSION["novamart_cart"] = [];
        $_SESSION["novamart_csrf"] = bin2hex(random_bytes(32));

        header("Location: checkout.php?success=1");
        exit;
    }
}

$cart = $_SESSION["novamart_cart"];
$summary = calculateCart($cart);

$cartCount = $summary["quantity"];

$paymentDescriptions = [
    "cod" => "Pay in cash when your order is delivered.",
    "bank" => "Transfer the payment to the store's bank account.",
    "card" => "Demonstration only — no real card details or payment are required."
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $showSuccess ? "Order Confirmation" : "Checkout" ?> | NovaMart
    </title>

    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --navy: #102542;
            --navy-dark: #0a192f;
            --blue: #246bfd;
            --blue-light: #edf3ff;
            --text: #253047;
            --muted: #7b8799;
            --border: #e7ebf2;
            --page-bg: #f6f8fc;
            --green: #15976b;
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

        .navbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 16px 0;
        }

        .navbar-brand {
            font-size: 25px;
            font-weight: 800;
            color: var(--navy) !important;
            letter-spacing: -.7px;
        }

        .brand-dot {
            color: var(--blue);
        }

        .nav-link {
            color: #4c5870 !important;
            font-weight: 600;
            margin: 0 8px;
        }

        .nav-link:hover {
            color: var(--blue) !important;
        }

        .nav-cart {
            background: var(--blue-light);
            color: var(--blue);
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 700;
        }

        .checkout-banner {
            background: linear-gradient(120deg, var(--navy-dark), #183d70);
            color: white;
            padding: 38px 0;
        }

        .checkout-banner h1 {
            font-size: clamp(28px, 4vw, 38px);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .checkout-banner p {
            color: #d6e2f5;
            margin: 0;
        }

        .breadcrumb {
            margin-bottom: 0;
            font-size: 14px;
        }

        .breadcrumb-item a {
            color: #d8e5ff;
        }

        .breadcrumb-item.active {
            color: #fff;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #b6c7e4;
        }

        .checkout-section {
            padding: 35px 0 65px;
        }

        .checkout-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 17px;
            padding: 27px;
            box-shadow: 0 8px 25px rgba(16, 37, 66, .035);
            margin-bottom: 22px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 24px;
        }

        .heading-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            background: var(--blue-light);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .section-heading h2 {
            font-size: 20px;
            color: var(--navy);
            font-weight: 800;
            margin: 0 0 4px;
        }

        .section-heading p {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
        }

        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #344158;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 47px;
            border: 1px solid #dfe5ef;
            border-radius: 9px;
            padding: 11px 13px;
            font-size: 14px;
            box-shadow: none !important;
        }

        textarea.form-control {
            min-height: 95px;
            resize: vertical;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--blue);
        }

        .required-star {
            color: #e34c4c;
        }

        .payment-option {
            display: block;
            cursor: pointer;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            transition: .2s ease;
        }

        .payment-option:hover {
            border-color: #9bbaff;
            background: #fbfcff;
        }

        .payment-option:has(input:checked) {
            border-color: var(--blue);
            background: #f4f7ff;
            box-shadow: 0 0 0 1px var(--blue);
        }

        .payment-top {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .payment-option input {
            accent-color: var(--blue);
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .payment-icon {
            width: 43px;
            height: 43px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 21px;
            flex-shrink: 0;
        }

        .payment-title {
            font-weight: 800;
            font-size: 14px;
            color: var(--navy);
        }

        .payment-description {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
            margin: 5px 0 0 29px;
        }

        .demo-payment-note {
            background: #fff8e8;
            color: #87651c;
            border: 1px solid #f5e5b9;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12px;
            line-height: 1.6;
        }

        .order-summary {
            position: sticky;
            top: 20px;
        }

        .summary-title {
            color: var(--navy);
            font-weight: 800;
            font-size: 21px;
            margin-bottom: 22px;
        }

        .summary-product {
            display: flex;
            gap: 13px;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #edf0f5;
        }

        .product-image {
            width: 65px;
            height: 65px;
            border: 1px solid var(--border);
            background: #f7f9fd;
            border-radius: 11px;
            display: grid;
            place-items: center;
            overflow: hidden;
            flex-shrink: 0;
            font-size: 28px;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .product-info {
            min-width: 0;
            flex: 1;
        }

        .product-name {
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .product-quantity {
            font-size: 12px;
            color: var(--muted);
        }

        .product-price {
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
            color: var(--navy);
        }

        .summary-lines {
            padding-top: 19px;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .summary-line span:first-child {
            color: #647087;
        }

        .summary-line strong {
            color: var(--navy);
        }

        .free-delivery {
            color: var(--green) !important;
        }

        .summary-total {
            padding-top: 18px;
            border-top: 1px dashed #dce2ec;
            margin-top: 8px;
        }

        .summary-total span:first-child {
            font-weight: 800;
            color: var(--navy);
            font-size: 16px;
        }

        .summary-total strong {
            color: var(--blue);
            font-size: 23px;
            font-weight: 900;
        }

        .delivery-progress {
            background: #f3f7ff;
            border: 1px solid #e0eaff;
            border-radius: 11px;
            padding: 14px;
            font-size: 12px;
            color: #425675;
            margin-top: 18px;
            line-height: 1.6;
        }

        .btn-place-order {
            display: block;
            width: 100%;
            border: 0;
            border-radius: 11px;
            background: var(--blue);
            color: white;
            font-weight: 800;
            padding: 15px;
            font-size: 15px;
            margin-top: 20px;
            transition: .2s ease;
        }

        .btn-place-order:hover {
            background: #1554d1;
            color: white;
            transform: translateY(-1px);
        }

        .secure-note {
            text-align: center;
            font-size: 12px;
            color: var(--muted);
            margin-top: 14px;
        }

        .back-link {
            color: #5b6980;
            font-size: 13px;
            font-weight: 700;
        }

        .back-link:hover {
            color: var(--blue);
        }

        .trust-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 20px;
            padding-top: 18px;
            margin-top: 20px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
        }

        .trust-row span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .checkout-footer {
            background: var(--navy-dark);
            color: #b8c5d9;
            padding: 27px 0;
            font-size: 13px;
        }

        .checkout-footer strong {
            color: white;
        }

        /* Order confirmation */
        .success-wrapper {
            max-width: 820px;
            margin: 0 auto;
        }

        .success-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 12px 35px rgba(16, 37, 66, .05);
            margin-bottom: 22px;
        }

        .success-icon {
            width: 78px;
            height: 78px;
            display: grid;
            place-items: center;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #e5f8ef;
            color: var(--green);
            font-size: 38px;
            font-weight: 900;
        }

        .success-card h2 {
            color: var(--navy);
            font-size: 29px;
            font-weight: 900;
            text-align: center;
        }

        .success-message {
            color: var(--muted);
            text-align: center;
            line-height: 1.7;
        }

        .order-number-box {
            background: #f4f7ff;
            border: 1px dashed #aac1ff;
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            margin: 25px 0;
        }

        .order-number-box small {
            display: block;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .order-number-box strong {
            color: var(--blue);
            font-size: 21px;
            letter-spacing: .5px;
        }

        .confirmation-details {
            border-top: 1px solid var(--border);
            padding-top: 22px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .detail-row span {
            color: var(--muted);
        }

        .detail-row strong {
            color: var(--navy);
            text-align: right;
        }

        .status-pill {
            display: inline-block;
            background: #e5f8ef;
            color: #08794e;
            padding: 6px 11px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 800;
        }

        .confirmation-product {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid #edf0f5;
            font-size: 13px;
        }

        .success-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-top: 25px;
        }

        .btn-primary-custom {
            display: inline-block;
            background: var(--blue);
            color: white;
            border-radius: 10px;
            padding: 13px 22px;
            font-size: 14px;
            font-weight: 800;
        }

        .btn-primary-custom:hover {
            color: white;
            background: #1554d1;
        }

        .btn-outline-custom {
            display: inline-block;
            background: white;
            color: var(--navy);
            border: 1px solid #dce3ef;
            border-radius: 10px;
            padding: 13px 22px;
            font-size: 14px;
            font-weight: 800;
        }

        .btn-outline-custom:hover {
            background: #f6f8fc;
            color: var(--blue);
        }

        @media (max-width: 991px) {
            .order-summary {
                position: static;
            }
        }

        @media (max-width: 576px) {
            .checkout-card,
            .success-card {
                padding: 20px;
            }

            .checkout-banner {
                padding: 28px 0;
            }

            .success-card h2 {
                font-size: 24px;
            }

            .summary-total strong {
                font-size: 20px;
            }

            .detail-row {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="../images/novamart/novamart_logo.png" width="40px" height="40px">
            NovaMart<span class="brand-dot">.</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shop.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shop.php?deals=1">Deals</a>
                </li>
            </ul>

            <a href="cart.php" class="nav-cart">
                🛒 Cart
                <?php if (!$showSuccess && $cartCount > 0): ?>
                    (<?= (int) $cartCount ?>)
                <?php endif; ?>
            </a>
        </div>
    </div>
</nav>

<?php if ($showSuccess): ?>

    <!-- Order confirmation banner -->
    <section class="checkout-banner">
        <div class="container">
            <h1>Order Confirmation</h1>
            <p>Thank you for choosing NovaMart. Your demo order has been placed.</p>
        </div>
    </section>

    <main class="checkout-section">
        <div class="container">
            <div class="success-wrapper">

                <div class="success-card">
                    <div class="success-icon">✓</div>

                    <h2>Thank You for Your Order!</h2>

                    <p class="success-message">
                        Hi <?= e($completedOrder["customer"]) ?>,
                        your demonstration order has been placed successfully.
                        This is a portfolio demo; no actual payment or delivery
                        has been arranged.
                    </p>

                    <div class="order-number-box">
                        <small>Your Demo Order Number</small>
                        <strong><?= e($completedOrder["order_number"]) ?></strong>
                    </div>

                    <div class="confirmation-details">
                        <div class="detail-row">
                            <span>Order date</span>
                            <strong><?= e($completedOrder["date"]) ?></strong>
                        </div>

                        <div class="detail-row">
                            <span>Order status</span>
                            <strong>
                                <span class="status-pill">
                                    <?= e($completedOrder["status"]) ?>
                                </span>
                            </strong>
                        </div>

                        <div class="detail-row">
                            <span>Customer</span>
                            <strong><?= e($completedOrder["customer"]) ?></strong>
                        </div>

                        <div class="detail-row">
                            <span>Email</span>
                            <strong><?= e($completedOrder["email"]) ?></strong>
                        </div>

                        <div class="detail-row">
                            <span>Phone</span>
                            <strong><?= e($completedOrder["phone"]) ?></strong>
                        </div>

                        <div class="detail-row">
                            <span>Delivery address</span>
                            <strong>
                                <?= e($completedOrder["address"]) ?>,
                                <?= e($completedOrder["city"]) ?>
                                <?php if ($completedOrder["postal_code"] !== ""): ?>
                                    - <?= e($completedOrder["postal_code"]) ?>
                                <?php endif; ?>
                            </strong>
                        </div>

                        <div class="detail-row">
                            <span>Payment method</span>
                            <strong><?= e($completedOrder["payment_method"]) ?></strong>
                        </div>

                        <?php if ($completedOrder["delivery_note"] !== ""): ?>
                            <div class="detail-row">
                                <span>Delivery note</span>
                                <strong><?= e($completedOrder["delivery_note"]) ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="checkout-card">
                    <h3 class="summary-title">Order Items</h3>

                    <?php foreach ($completedOrder["items"] as $item): ?>
                        <div class="confirmation-product">
                            <div>
                                <strong><?= e($item["name"]) ?></strong>
                                <div class="text-muted mt-1">
                                    Quantity: <?= (int) $item["quantity"] ?>
                                </div>
                            </div>

                            <strong>
                                <?= money($item["price"] * $item["quantity"]) ?>
                            </strong>
                        </div>
                    <?php endforeach; ?>

                    <div class="summary-lines">
                        <div class="summary-line">
                            <span>Subtotal</span>
                            <strong><?= money($completedOrder["subtotal"]) ?></strong>
                        </div>

                        <div class="summary-line">
                            <span>Delivery fee</span>
                            <strong class="<?= $completedOrder["delivery"] == 0 ? "free-delivery" : "" ?>">
                                <?= $completedOrder["delivery"] == 0
                                    ? "FREE"
                                    : money($completedOrder["delivery"]) ?>
                            </strong>
                        </div>

                        <div class="summary-line summary-total">
                            <span>Total</span>
                            <strong><?= money($completedOrder["total"]) ?></strong>
                        </div>
                    </div>

                    <div class="demo-payment-note mt-3">
                        <strong>Demo notice:</strong> This order exists only in
                        the current PHP session. It is not saved permanently
                        and will not appear in a real order management system.
                    </div>

                    <div class="success-actions">
                        <a href="shop.php" class="btn-primary-custom">
                            Continue Shopping
                        </a>

                        <a href="index.php" class="btn-outline-custom">
                            Back to Home
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>

<?php else: ?>

    <!-- Checkout banner -->
    <section class="checkout-banner">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="cart.php">Cart</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Checkout
                    </li>
                </ol>
            </nav>

            <h1>Complete Your Order</h1>
            <p>Almost there! Enter your delivery details to finish your purchase.</p>
        </div>
    </section>

    <main class="checkout-section">
        <div class="container">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-3 mb-4" role="alert">
                    <strong>Please check the following:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($errors as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="checkout.php" id="checkoutForm">
                <input type="hidden" name="csrf_token"
                       value="<?= e($_SESSION["novamart_csrf"]) ?>">

                <div class="row g-4">

                    <!-- Customer details and payment -->
                    <div class="col-lg-7">

                        <!-- Contact information -->
                        <div class="checkout-card">
                            <div class="section-heading">
                                <div class="heading-icon">👤</div>
                                <div>
                                    <h2>Contact Information</h2>
                                    <p>Enter the details for your order.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="full_name" class="form-label">
                                        Full Name <span class="required-star">*</span>
                                    </label>

                                    <input type="text" class="form-control"
                                           id="full_name" name="full_name"
                                           placeholder="Enter your full name"
                                           value="<?= e($form["full_name"]) ?>"
                                           minlength="2" maxlength="100"
                                           autocomplete="name" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        Email Address <span class="required-star">*</span>
                                    </label>

                                    <input type="email" class="form-control"
                                           id="email" name="email"
                                           placeholder="you@example.com"
                                           value="<?= e($form["email"]) ?>"
                                           maxlength="150"
                                           autocomplete="email" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">
                                        Phone Number <span class="required-star">*</span>
                                    </label>

                                    <input type="tel" class="form-control"
                                           id="phone" name="phone"
                                           placeholder="+94 77 123 4567"
                                           value="<?= e($form["phone"]) ?>"
                                           maxlength="20"
                                           autocomplete="tel" required>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery details -->
                        <div class="checkout-card">
                            <div class="section-heading">
                                <div class="heading-icon">📦</div>
                                <div>
                                    <h2>Delivery Address</h2>
                                    <p>Where should we deliver your order?</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="address" class="form-label">
                                        Street Address <span class="required-star">*</span>
                                    </label>

                                    <textarea class="form-control"
                                              id="address" name="address"
                                              placeholder="House number, street, area"
                                              minlength="5" maxlength="300"
                                              autocomplete="street-address"
                                              required><?= e($form["address"]) ?></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label for="city" class="form-label">
                                        City <span class="required-star">*</span>
                                    </label>

                                    <input type="text" class="form-control"
                                           id="city" name="city"
                                           placeholder="Enter your city"
                                           value="<?= e($form["city"]) ?>"
                                           maxlength="100"
                                           autocomplete="address-level2" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="postal_code" class="form-label">
                                        Postal Code
                                    </label>

                                    <input type="text" class="form-control"
                                           id="postal_code" name="postal_code"
                                           placeholder="Optional"
                                           value="<?= e($form["postal_code"]) ?>"
                                           maxlength="12"
                                           autocomplete="postal-code">
                                </div>

                                <div class="col-12">
                                    <label for="delivery_note" class="form-label">
                                        Delivery Instructions
                                        <span class="text-muted fw-normal">(Optional)</span>
                                    </label>

                                    <textarea class="form-control"
                                              id="delivery_note"
                                              name="delivery_note"
                                              placeholder="Any special instructions for delivery?"
                                              maxlength="500"><?= e($form["delivery_note"]) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment method -->
                        <div class="checkout-card">
                            <div class="section-heading">
                                <div class="heading-icon">💳</div>
                                <div>
                                    <h2>Payment Method</h2>
                                    <p>Select how you would like to pay.</p>
                                </div>
                            </div>

                            <label class="payment-option">
                                <div class="payment-top">
                                    <input type="radio" name="payment_method"
                                           value="cod"
                                           <?= $form["payment_method"] === "cod" ? "checked" : "" ?>>
                                    <div class="payment-icon">💵</div>
                                    <div>
                                        <div class="payment-title">Cash on Delivery</div>
                                        <div class="text-muted small">
                                            Pay when your order arrives.
                                        </div>
                                    </div>
                                </div>
                                <p class="payment-description">
                                    No online payment is required in this demonstration.
                                </p>
                            </label>

                            <label class="payment-option">
                                <div class="payment-top">
                                    <input type="radio" name="payment_method"
                                           value="bank"
                                           <?= $form["payment_method"] === "bank" ? "checked" : "" ?>>
                                    <div class="payment-icon">🏦</div>
                                    <div>
                                        <div class="payment-title">Bank Transfer</div>
                                        <div class="text-muted small">
                                            Select a bank transfer as your preferred method.
                                        </div>
                                    </div>
                                </div>
                                <p class="payment-description">
                                    This demo does not initiate a bank transfer.
                                </p>
                            </label>

                            <label class="payment-option">
                                <div class="payment-top">
                                    <input type="radio" name="payment_method"
                                           value="card"
                                           <?= $form["payment_method"] === "card" ? "checked" : "" ?>>
                                    <div class="payment-icon">💳</div>
                                    <div>
                                        <div class="payment-title">Card Payment</div>
                                        <div class="text-muted small">
                                            Demonstration payment option.
                                        </div>
                                    </div>
                                </div>
                                <p class="payment-description">
                                    No card number, CVV, or other payment credentials
                                    are collected.
                                </p>
                            </label>

                            <div class="demo-payment-note">
                                <strong>Portfolio demonstration:</strong>
                                Selecting a payment method does not process a real
                                transaction. Please do not enter any actual payment
                                details.
                            </div>
                        </div>

                        <a href="cart.php" class="back-link">
                            ← Return to Shopping Cart
                        </a>

                    </div>

                    <!-- Order summary -->
                    <div class="col-lg-5">
                        <aside class="checkout-card order-summary">
                            <h2 class="summary-title">Your Order</h2>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">
                                    <?= (int) $cartCount ?>
                                    item<?= $cartCount === 1 ? "" : "s" ?>
                                </span>

                                <a href="cart.php" class="small fw-bold">
                                    Edit Cart
                                </a>
                            </div>

                            <?php foreach ($cart as $key => $item): ?>
                                <?php
                                    $itemName = $item["name"] ?? "Product";
                                    $itemPrice = (float) ($item["price"] ?? 0);
                                    $itemQuantity = max(1, (int) ($item["quantity"] ?? 1));
                                    $itemImage = basename((string) ($item["image"] ?? ""));
                                    $itemEmoji = $item["emoji"] ?? "📦";
                                ?>

                                <div class="summary-product">
                                    <div class="product-image">
                                        <?php if ($itemImage !== ""): ?>
                                            <img
                                                src="../assets/images/<?= e($itemImage) ?>"
                                                alt="<?= e($itemName) ?>"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <span style="display:none;">
                                                <?= e($itemEmoji) ?>
                                            </span>
                                        <?php else: ?>
                                            <?= e($itemEmoji) ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="product-info">
                                        <div class="product-name">
                                            <?= e($itemName) ?>
                                        </div>

                                        <div class="product-quantity">
                                            Qty: <?= (int) $itemQuantity ?>
                                            × <?= money($itemPrice) ?>
                                        </div>
                                    </div>

                                    <div class="product-price">
                                        <?= money($itemPrice * $itemQuantity) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="summary-lines">
                                <div class="summary-line">
                                    <span>Subtotal</span>
                                    <strong><?= money($summary["subtotal"]) ?></strong>
                                </div>

                                <div class="summary-line">
                                    <span>Delivery fee</span>

                                    <strong class="<?= $summary["delivery"] == 0 ? "free-delivery" : "" ?>">
                                        <?= $summary["delivery"] == 0
                                            ? "FREE"
                                            : money($summary["delivery"]) ?>
                                    </strong>
                                </div>

                                <div class="summary-line summary-total">
                                    <span>Total</span>
                                    <strong><?= money($summary["total"]) ?></strong>
                                </div>
                            </div>

                            <div class="delivery-progress">
                                <?php if ($summary["subtotal"] >= 10000): ?>
                                    🎉 <strong>You've unlocked free delivery!</strong>
                                <?php else: ?>
                                    <?php $remaining = 10000 - $summary["subtotal"]; ?>
                                    🚚 Add another
                                    <strong><?= money($remaining) ?></strong>
                                    to unlock free delivery.
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn-place-order"
                                    id="placeOrderButton">
                                Place Demo Order →
                            </button>

                            <div class="secure-note">
                                🔒 Demo checkout · No real payment processed
                            </div>

                            <div class="trust-row">
                                <span>✓ Clear pricing</span>
                                <span>✓ Demo order confirmation</span>
                                <span>✓ Secure form validation</span>
                            </div>
                        </aside>
                    </div>

                </div>
            </form>

        </div>
    </main>

<?php endif; ?>

<!-- Footer -->
<footer class="checkout-footer">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <strong>NovaMart.</strong>
                <span class="ms-2">Smart Shopping. Better Living.</span>
            </div>

            <div class="col-md-6 text-md-end">
                <span>© <?= date("Y") ?> NovaMart.</span>
                <span class="ms-2">A Codvanta portfolio demonstration.</span>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const checkoutForm = document.getElementById("checkoutForm");
    const placeOrderButton = document.getElementById("placeOrderButton");

    if (checkoutForm && placeOrderButton) {
        checkoutForm.addEventListener("submit", function (event) {
            if (!checkoutForm.checkValidity()) {
                event.preventDefault();
                checkoutForm.reportValidity();
                return;
            }

            placeOrderButton.disabled = true;
            placeOrderButton.textContent = "Placing Demo Order...";
        });
    }
});
</script>

</body>
</html>
