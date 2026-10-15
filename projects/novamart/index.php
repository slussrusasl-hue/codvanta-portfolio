
<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart - Customer Homepage
| Codvanta Portfolio Demonstration
|--------------------------------------------------------------------------
*/

// Sample products for the demonstration.
// Later, we can load these products from the MySQL database.

$products = [
    [
        "id" => 1,
        "name" => "Wireless Earbuds Pro",
        "category" => "Accessories",
        "price" => 79.99,
        "old_price" => 99.99,
        "rating" => 4.8,
        "reviews" => 124,
        "image" => "assets/images/earbuds.jpg",
        "badge" => "Best Seller",
        "emoji" => "🎧"
    ],
    [
        "id" => 2,
        "name" => "Smart Watch Series 8",
        "category" => "Electronics",
        "price" => 169.99,
        "old_price" => 199.99,
        "rating" => 4.7,
        "reviews" => 98,
        "image" => "assets/images/smartwatch.jpg",
        "badge" => "15% OFF",
        "emoji" => "⌚"
    ],
    [
        "id" => 3,
        "name" => "UltraBook Air 14",
        "category" => "Computing",
        "price" => 899.00,
        "old_price" => 999.00,
        "rating" => 4.9,
        "reviews" => 210,
        "image" => "assets/images/laptop.jpg",
        "badge" => "New Arrival",
        "emoji" => "💻"
    ],
    [
        "id" => 4,
        "name" => "Wireless Game Controller",
        "category" => "Gaming",
        "price" => 59.99,
        "old_price" => 79.99,
        "rating" => 4.6,
        "reviews" => 76,
        "image" => "assets/images/controller.jpg",
        "badge" => "25% OFF",
        "emoji" => "🎮"
    ],
    [
        "id" => 5,
        "name" => "Skincare Beauty Set",
        "category" => "Beauty",
        "price" => 49.99,
        "old_price" => 59.99,
        "rating" => 4.5,
        "reviews" => 63,
        "image" => "assets/images/beauty.jpg",
        "badge" => "Popular",
        "emoji" => "🧴"
    ],
    [
        "id" => 6,
        "name" => "Travel Backpack",
        "category" => "Fashion",
        "price" => 64.99,
        "old_price" => 79.99,
        "rating" => 4.7,
        "reviews" => 87,
        "image" => "assets/images/backpack.jpg",
        "badge" => "18% OFF",
        "emoji" => "🎒"
    ],
    [
        "id" => 7,
        "name" => "Premium Headphones",
        "category" => "Accessories",
        "price" => 119.99,
        "old_price" => 149.99,
        "rating" => 4.8,
        "reviews" => 145,
        "image" => "assets/images/headphones.jpg",
        "badge" => "Trending",
        "emoji" => "🎧"
    ],
    [
        "id" => 8,
        "name" => "Portable Air Fryer",
        "category" => "Home Appliances",
        "price" => 89.99,
        "old_price" => 109.99,
        "rating" => 4.6,
        "reviews" => 54,
        "image" => "assets/images/air-fryer.jpg",
        "badge" => "Special Offer",
        "emoji" => "🍳"
    ]
];

$categories = [
    [
        "name" => "Electronics",
        "icon" => "📱",
        "description" => "Smart devices"
    ],
    [
        "name" => "Computing",
        "icon" => "💻",
        "description" => "Laptops & PCs"
    ],
    [
        "name" => "Accessories",
        "icon" => "🎧",
        "description" => "Everyday tech"
    ],
    [
        "name" => "Home Appliances",
        "icon" => "🏠",
        "description" => "Better living"
    ],
    [
        "name" => "Fashion",
        "icon" => "👟",
        "description" => "Style & comfort"
    ],
    [
        "name" => "Beauty",
        "icon" => "🧴",
        "description" => "Personal care"
    ],
    [
        "name" => "Gaming",
        "icon" => "🎮",
        "description" => "Level up"
    ],
    [
        "name" => "Toys & Games",
        "icon" => "🧸",
        "description" => "Fun for everyone"
    ]
];

// Initialize the demonstration cart.
if (!isset($_SESSION["novamart_cart"])) {
    $_SESSION["novamart_cart"] = [];
}

$notice = "";
$noticeType = "success";

// Add a product to the session cart.
if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["add_to_cart"])) {

    $productId = filter_input(
        INPUT_POST,
        "product_id",
        FILTER_VALIDATE_INT
    );

    $selectedProduct = null;

    foreach ($products as $product) {
        if ($product["id"] === $productId) {
            $selectedProduct = $product;
            break;
        }
    }

    if ($selectedProduct !== null) {
        $cartKey = (string) $productId;

        if (!isset($_SESSION["novamart_cart"][$cartKey])) {
            $_SESSION["novamart_cart"][$cartKey] = [
                "id" => $productId,
                "name" => $selectedProduct["name"],
                "price" => $selectedProduct["price"],
                "image" => $selectedProduct["image"],
                "emoji" => $selectedProduct["emoji"],
                "quantity" => 0
            ];
        }

        $_SESSION["novamart_cart"][$cartKey]["quantity"]++;

        $notice = $selectedProduct["name"]
                . " has been added to your cart.";
    } else {
        $notice = "The selected product could not be found.";
        $noticeType = "danger";
    }
}

// Count all cart items.
$cartCount = 0;

foreach ($_SESSION["novamart_cart"] as $cartItem) {
    $cartCount += (int) $cartItem["quantity"];
}

// HTML output helper.
function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        "UTF-8"
    );
}

// Currency formatter.
// Sample prices are shown in USD for this demonstration.
function money($amount) {
    return "$" . number_format((float) $amount, 2);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="Discover electronics, fashion, home appliances and more at NovaMart. Enjoy a smarter online shopping experience.">

    <title>NovaMart | Smarter Shopping, Better Living</title>

    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet"
          href="../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --navy: #0b1d3b;
            --navy-light: #122c53;
            --blue: #1769ff;
            --blue-dark: #0754df;
            --light-blue: #eaf2ff;
            --text: #142342;
            --muted: #718096;
            --border: #e6ebf3;
            --white: #ffffff;
            --page: #f7f9fd;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Trebuchet MS", Arial, sans-serif;
            color: var(--text);
            background: var(--white);
        }

        a {
            text-decoration: none;
        }

        button,
        input {
            font-family: inherit;
        }

        .container-wide {
            width: min(1440px, 100%);
            margin: auto;
            padding-left: 28px;
            padding-right: 28px;
        }

        /* =========================
           HEADER
        ========================= */

        .top-header {
            background: var(--navy);
            color: white;
        }

        .header-main {
            min-height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .brand {
            color: white;
            font-size: 30px;
            font-weight: 900;
            letter-spacing: -1px;
            white-space: nowrap;
        }

        .brand:hover {
            color: white;
        }

        .brand .brand-blue {
            color: #4b8cff;
        }

        .brand-symbol {
            display: inline-flex;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            margin-right: 6px;
            border: 2px solid #7baaff;
            border-radius: 12px;
            font-size: 23px;
            vertical-align: middle;
        }

        .brand-subtitle {
            display: block;
            color: #c2cee2;
            font-size: 10px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-left: 50px;
            margin-top: -3px;
        }

        .search-form {
            display: flex;
            width: min(500px, 42%);
            height: 44px;
            overflow: hidden;
            border-radius: 30px;
            background: white;
            border: 1px solid #dbe4f2;
        }

        .search-form input {
            min-width: 0;
            flex: 1;
            padding: 0 20px;
            border: 0;
            outline: 0;
            font-size: 14px;
            color: var(--text);
        }

        .search-form button {
            width: 54px;
            border: 0;
            background: var(--blue);
            color: white;
            font-size: 22px;
            cursor: pointer;
        }

        .search-form button:hover {
            background: var(--blue-dark);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 23px;
        }

        .header-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: white;
            font-size: 14px;
            white-space: nowrap;
        }

        .header-link:hover {
            color: #9dbdff;
        }

        .header-icon {
            font-size: 23px;
        }

        .cart-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 21px;
            height: 21px;
            padding: 0 5px;
            border-radius: 20px;
            background: #ff4058;
            color: white;
            font-size: 11px;
            font-weight: 800;
        }

        /* =========================
           NAVIGATION
        ========================= */

        .main-nav {
            border-top: 1px solid rgba(255,255,255,.12);
            background: #10264a;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 48px;
        }

        .nav-link {
            display: inline-block;
            padding: 14px 20px;
            color: #e5ecf8;
            font-size: 14px;
            font-weight: 700;
            border-radius: 8px 8px 0 0;
            white-space: nowrap;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background: var(--blue);
        }

        .shipping-message {
            margin-left: auto;
            color: #e4edff;
            font-size: 12px;
            white-space: nowrap;
        }

        /* =========================
           HERO
        ========================= */

        .hero-wrap {
            padding-top: 20px;
            padding-bottom: 22px;
            background: linear-gradient(
                180deg,
                #edf4ff 0%,
                #ffffff 100%
            );
        }

        .hero {
            position: relative;
            min-height: 330px;
            overflow: hidden;
            display: flex;
            align-items: center;
            border-radius: 16px;
            background:
                radial-gradient(
                    circle at 77% 25%,
                    rgba(255,255,255,.95),
                    transparent 24%
                ),
                linear-gradient(
                    115deg,
                    #f5f9ff 0%,
                    #d9eaff 55%,
                    #b4d2ff 100%
                );
        }

        .hero::after {
            content: "";
            position: absolute;
            right: -110px;
            bottom: -220px;
            width: 600px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255,255,255,.35);
            transform: rotate(-15deg);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            width: 55%;
            padding: 42px 38px;
        }

        .hero-tag {
            display: inline-block;
            margin-bottom: 17px;
            padding: 7px 14px;
            border-radius: 30px;
            color: #0759e9;
            background: #c8e4ff;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero h1 {
            max-width: 530px;
            margin: 0 0 16px;
            color: var(--navy);
            font-size: clamp(35px, 4.3vw, 56px);
            font-weight: 900;
            line-height: 1.12;
            letter-spacing: -1.7px;
        }

        .hero h1 span {
            color: var(--blue);
        }

        .hero p {
            max-width: 430px;
            margin-bottom: 24px;
            color: #40577b;
            font-size: 16px;
            line-height: 1.8;
        }

        .primary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 23px;
            border: 0;
            border-radius: 10px;
            background: var(--blue);
            color: white;
            font-size: 14px;
            font-weight: 800;
            transition: .2s;
        }

        .primary-btn:hover {
            color: white;
            background: var(--blue-dark);
            transform: translateY(-2px);
        }

        .hero-image {
            position: absolute;
            z-index: 1;
            right: 3%;
            bottom: 0;
            width: 48%;
            height: 94%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            mix-blend-mode: multiply;
        }

        .hero-placeholder {
            font-size: clamp(80px, 12vw, 155px);
            filter: drop-shadow(0 20px 20px rgba(20,50,100,.12));
        }

        .discount-bubble {
            position: absolute;
            z-index: 3;
            top: 22px;
            right: 30px;
            display: flex;
            width: 100px;
            height: 100px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-radius: 50%;
            background: var(--blue);
            color: white;
            box-shadow: 0 10px 25px rgba(23,105,255,.22);
            transform: rotate(7deg);
        }

        .discount-bubble small {
            font-size: 11px;
        }

        .discount-bubble strong {
            font-size: 27px;
            line-height: 1.1;
        }

        .discount-bubble span {
            font-size: 12px;
            font-weight: 800;
        }

        /* =========================
           GENERAL SECTION STYLES
        ========================= */

        .section {
            padding-top: 17px;
            padding-bottom: 22px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .section-title {
            position: relative;
            margin: 0;
            padding-left: 16px;
            color: var(--navy);
            font-size: 25px;
            font-weight: 900;
            letter-spacing: -.5px;
        }

        .section-title::before {
            content: "";
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 5px;
            border-radius: 5px;
            background: var(--blue);
        }

        .view-all {
            color: var(--blue);
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        .view-all:hover {
            color: var(--blue-dark);
        }

        /* =========================
           CATEGORY CARDS
        ========================= */

        .category-grid {
            display: grid;
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 13px;
        }

        .category-card {
            display: flex;
            min-width: 0;
            min-height: 145px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 15px 7px;
            border: 1px solid transparent;
            border-radius: 15px;
            background: #f0f5ff;
            color: var(--navy);
            text-align: center;
            transition: .25s;
        }

        .category-card:hover {
            transform: translateY(-4px);
            border-color: #c5d8ff;
            background: #e5efff;
            color: var(--blue);
        }

        .category-icon {
            display: flex;
            width: 75px;
            height: 75px;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(255,255,255,.7);
            font-size: 40px;
        }

        .category-name {
            font-size: 12px;
            font-weight: 800;
            line-height: 1.4;
        }

        /* =========================
           PRODUCT CARDS
        ========================= */

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .product-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: white;
            box-shadow: 0 5px 16px rgba(19,42,80,.035);
            transition: .25s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            border-color: #c9d9ff;
            box-shadow: 0 13px 28px rgba(19,42,80,.10);
        }

        .product-image-wrap {
            position: relative;
            height: 205px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(
                145deg,
                #f5f8ff,
                #eaf1fc
            );
        }

        .product-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
            transition: transform .35s;
        }

        .product-card:hover .product-image-wrap img {
            transform: scale(1.06);
        }

        .product-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-size: 75px;
        }

        .product-badge {
            position: absolute;
            z-index: 2;
            top: 12px;
            left: 12px;
            padding: 6px 10px;
            border-radius: 20px;
            background: var(--blue);
            color: white;
            font-size: 10px;
            font-weight: 900;
        }

        .product-badge.sale {
            background: #ef4444;
        }

        .product-badge.new {
            background: #15945d;
        }

        .product-body {
            padding: 16px;
        }

        .product-category {
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .product-name {
            display: block;
            min-height: 40px;
            margin-bottom: 7px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 900;
            line-height: 1.45;
        }

        .product-name:hover {
            color: var(--blue);
        }

        .product-rating {
            margin-bottom: 12px;
            color: #f4a51c;
            font-size: 13px;
        }

        .product-rating small {
            margin-left: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .product-price-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 14px;
        }

        .product-price {
            color: var(--blue);
            font-size: 19px;
            font-weight: 900;
        }

        .old-price {
            color: #98a2b3;
            font-size: 12px;
            text-decoration: line-through;
        }

        .add-cart-btn {
            width: 100%;
            padding: 11px 12px;
            border: 0;
            border-radius: 9px;
            background: var(--blue);
            color: white;
            font-size: 13px;
            font-weight: 800;
            transition: .2s;
            cursor: pointer;
        }

        .add-cart-btn:hover {
            background: var(--blue-dark);
        }

        .add-cart-btn:disabled {
            cursor: not-allowed;
            opacity: .75;
        }

        /* =========================
           PROMOTION BANNERS
        ========================= */

        .promo-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            padding-top: 8px;
            padding-bottom: 25px;
        }

        .promo-card {
            position: relative;
            min-height: 225px;
            display: flex;
            align-items: center;
            overflow: hidden;
            border-radius: 15px;
            padding: 28px;
            color: white;
        }

        .promo-card.gaming {
            background:
                radial-gradient(
                    circle at 80% 70%,
                    #2456c9,
                    transparent 35%
                ),
                linear-gradient(120deg, #0b1e45, #102e68);
        }

        .promo-card.home {
            background:
                radial-gradient(
                    circle at 85% 65%,
                    #4baf88,
                    transparent 38%
                ),
                linear-gradient(120deg, #14634f, #24896c);
        }

        .promo-content {
            position: relative;
            z-index: 2;
            width: 62%;
        }

        .promo-content h3 {
            margin-bottom: 9px;
            font-size: 25px;
            font-weight: 900;
            line-height: 1.3;
        }

        .promo-content p {
            margin-bottom: 18px;
            color: #e1eafa;
            font-size: 13px;
            line-height: 1.7;
        }

        .promo-card.home .promo-content p {
            color: #e4fff5;
        }

        .promo-btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 30px;
            background: white;
            color: var(--blue);
            font-size: 12px;
            font-weight: 900;
        }

        .promo-btn:hover {
            background: #eaf1ff;
            color: var(--blue-dark);
        }

        .promo-emoji {
            position: absolute;
            right: 20px;
            bottom: 5px;
            font-size: clamp(65px, 9vw, 125px);
            filter: drop-shadow(0 12px 12px rgba(0,0,0,.15));
        }

        /* =========================
           BENEFITS
        ========================= */

        .benefits-section {
            padding: 30px 0;
            background: #f1f6ff;
        }

        .benefits-title {
            margin-bottom: 26px;
            color: var(--navy);
            font-size: 23px;
            font-weight: 900;
            text-align: center;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .benefit {
            padding: 10px 20px;
            text-align: center;
            border-right: 1px solid #dbe5f5;
        }

        .benefit:last-child {
            border-right: 0;
        }

        .benefit-icon {
            margin-bottom: 10px;
            color: var(--blue);
            font-size: 32px;
        }

        .benefit h4 {
            margin-bottom: 7px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 900;
        }

        .benefit p {
            max-width: 210px;
            margin: auto;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer {
            padding-top: 40px;
            background: var(--navy);
            color: #c3cfe4;
        }

        .footer .brand {
            display: inline-block;
            margin-bottom: 15px;
            font-size: 26px;
        }

        .footer-description {
            max-width: 280px;
            color: #b5c3dc;
            font-size: 13px;
            line-height: 1.8;
        }

        .footer h4 {
            margin-bottom: 18px;
            color: white;
            font-size: 15px;
            font-weight: 900;
        }

        .footer-links {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 11px;
        }

        .footer-links a {
            color: #c3cfe4;
            font-size: 12px;
        }

        .footer-links a:hover {
            color: #78a7ff;
        }

        .newsletter-form {
            display: flex;
            max-width: 330px;
            overflow: hidden;
            border-radius: 8px;
            background: white;
        }

        .newsletter-form input {
            min-width: 0;
            flex: 1;
            padding: 12px;
            border: 0;
            outline: 0;
            font-size: 12px;
        }

        .newsletter-form button {
            width: 44px;
            border: 0;
            background: var(--blue);
            color: white;
            cursor: pointer;
        }

        .footer-bottom {
            margin-top: 35px;
            padding: 18px 0;
            border-top: 1px solid rgba(255,255,255,.13);
            color: #aebdd5;
            font-size: 11px;
        }

        .social-links {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .social-links a {
            display: flex;
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            border: 1px solid #344b70;
            border-radius: 50%;
            color: white;
            font-size: 12px;
        }

        .social-links a:hover {
            background: var(--blue);
            border-color: var(--blue);
        }

        .demo-label {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 9px;
            border-radius: 5px;
            background: rgba(255,255,255,.09);
            color: #c6d8f5;
            font-size: 10px;
        }

        /* =========================
           ALERT
        ========================= */

        .cart-alert {
            position: relative;
            z-index: 10;
            margin-top: 15px;
            border-radius: 10px;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (min-width: 1450px) {
            .product-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (max-width: 1100px) {
            .container-wide {
                padding-left: 20px;
                padding-right: 20px;
            }

            .header-main {
                gap: 15px;
            }

            .brand {
                font-size: 25px;
            }

            .header-actions {
                gap: 13px;
            }

            .nav-link {
                padding-left: 13px;
                padding-right: 13px;
            }

            .category-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .hero-content {
                width: 58%;
            }

            .hero-image {
                width: 43%;
            }
        }

        @media (max-width: 767px) {
            .container-wide {
                padding-left: 16px;
                padding-right: 16px;
            }

            .header-main {
                padding-top: 16px;
                padding-bottom: 16px;
                flex-wrap: wrap;
                gap: 14px;
            }

            .brand {
                font-size: 25px;
            }

            .brand-subtitle {
                font-size: 9px;
            }

            .search-form {
                order: 3;
                width: 100%;
            }

            .header-actions {
                gap: 13px;
            }

            .header-link {
                font-size: 12px;
            }

            .header-link .link-text {
                display: none;
            }

            .nav-inner {
                overflow-x: auto;
                gap: 2px;
            }

            .nav-link {
                padding: 15px 14px;
                font-size: 12px;
            }

            .shipping-message {
                display: none;
            }

            .hero-wrap {
                padding-top: 14px;
            }

            .hero {
                min-height: 450px;
                align-items: flex-start;
            }

            .hero-content {
                width: 100%;
                padding: 28px 23px;
            }

            .hero h1 {
                max-width: 430px;
                font-size: 38px;
            }

            .hero p {
                max-width: 380px;
                font-size: 14px;
            }

            .hero-image {
                right: 0;
                bottom: 0;
                width: 100%;
                height: 47%;
                opacity: .9;
            }

            .hero-placeholder {
                font-size: 105px;
            }

            .discount-bubble {
                top: auto;
                right: 15px;
                bottom: 120px;
                width: 75px;
                height: 75px;
            }

            .discount-bubble strong {
                font-size: 21px;
            }

            .section-title {
                font-size: 21px;
            }

            .category-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .category-card {
                min-height: 125px;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .product-image-wrap {
                height: 155px;
            }

            .product-image-wrap img {
                padding: 14px;
            }

            .product-body {
                padding: 12px;
            }

            .product-name {
                font-size: 12px;
                min-height: 35px;
            }

            .product-price {
                font-size: 16px;
            }

            .product-rating {
                font-size: 11px;
            }

            .product-badge {
                top: 8px;
                left: 8px;
                font-size: 9px;
            }

            .promo-grid {
                grid-template-columns: 1fr;
                gap: 13px;
            }

            .promo-card {
                min-height: 200px;
                padding: 22px;
            }

            .promo-content {
                width: 68%;
            }

            .promo-content h3 {
                font-size: 21px;
            }

            .promo-emoji {
                right: 8px;
                font-size: 80px;
            }

            .benefits-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                row-gap: 22px;
            }

            .benefit:nth-child(2) {
                border-right: 0;
            }

            .benefit {
                padding: 10px;
            }

            .footer .row > div {
                margin-bottom: 27px;
            }

            .footer-bottom .text-end {
                text-align: left !important;
                margin-top: 8px;
            }
        }

        @media (max-width: 380px) {
            .hero h1 {
                font-size: 32px;
            }

            .header-actions {
                gap: 9px;
            }

            .product-image-wrap {
                height: 130px;
            }

            .product-price-row {
                gap: 5px;
            }

            .old-price {
                font-size: 10px;
            }
        }
    </style>
</head>

<body>

<!-- =========================
     HEADER
========================= -->

<header class="top-header">

    <div class="container-wide">

        <div class="header-main">

            <a href="index.php" class="brand">
                <span class="brand-symbol">🛍</span>Nova<span
                    class="brand-blue">Mart</span>

                <span class="brand-subtitle">
                    Shop Smarter. Live Better.
                </span>
            </a>


            <!-- Product Search -->
            <form action="shop.php"
                  method="GET"
                  class="search-form">

                <input
                    type="search"
                    name="search"
                    placeholder="Search for products, brands and more..."
                    aria-label="Search products"
                    required
                >

                <button type="submit" aria-label="Search">
                    &#9906;
                </button>

            </form>


            <!-- Account and Cart -->
            <div class="header-actions">

                <a href="login.php" class="header-link">
                    <span class="header-icon">&#9818;</span>
                    <span class="link-text">Login</span>
                </a>

                <a href="register.php" class="header-link">
                    <span class="header-icon">&#128100;</span>
                    <span class="link-text">Register</span>
                </a>

                <a href="cart.php" class="header-link">
                    <span class="header-icon">&#128722;</span>
                    <span class="link-text">Cart</span>

                    <span class="cart-count">
                        <?php echo $cartCount; ?>
                    </span>
                </a>

            </div>

        </div>

    </div>


    <!-- Navigation -->
    <nav class="main-nav">

        <div class="container-wide">

            <div class="nav-inner">

                <a href="index.php" class="nav-link active">
                    &#8962; Home
                </a>

                <a href="shop.php" class="nav-link">
                    Categories
                </a>

                <a href="shop.php?sort=discount" class="nav-link">
                    Deals
                </a>

                <a href="#why-novamart" class="nav-link">
                    About
                </a>

                <a href="#footer" class="nav-link">
                    Contact
                </a>

                <div class="shipping-message">
                    &#128666; Free Shipping on Orders Over $50
                </div>

            </div>

        </div>

    </nav>

</header>


<!-- =========================
     MAIN CONTENT
========================= -->

<main>

    <?php if ($notice !== ""): ?>

        <div class="container-wide">

            <div class="alert alert-<?php echo e($noticeType); ?> alert-dismissible fade show cart-alert"
                 role="alert">

                <?php echo e($notice); ?>

                <a href="cart.php" class="alert-link ms-2">
                    View Cart
                </a>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>

            </div>

        </div>

    <?php endif; ?>


    <!-- =========================
         HERO BANNER
    ========================= -->

    <section class="hero-wrap">

        <div class="container-wide">

            <div class="hero">

                <div class="hero-content">

                    <span class="hero-tag">
                        New Arrivals
                    </span>

                    <h1>
                        Smarter Shopping,<br>
                        <span>Better Living</span>
                    </h1>

                    <p>
                        Discover top-quality products at unbeatable
                        prices. Shop more, save more with NovaMart.
                    </p>

                    <a href="shop.php" class="primary-btn">
                        Shop Now <span>&rarr;</span>
                    </a>

                </div>


                <div class="hero-image">

                    <img
                        src="assets/images/novamart-hero.png"
                        alt="Discover the latest technology and lifestyle products"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >

                    <div class="hero-placeholder"
                         style="display:none;"
                         aria-hidden="true">
                        💻 🎧 📱
                    </div>

                </div>


                <div class="discount-bubble">
                    <small>Up to</small>
                    <strong>50%</strong>
                    <span>OFF</span>
                </div>

            </div>

        </div>

    </section>


    <!-- =========================
         SHOP BY CATEGORY
    ========================= -->

    <section class="section" id="categories">

        <div class="container-wide">

            <div class="section-heading">

                <h2 class="section-title">
                    Shop by Category
                </h2>

                <a href="shop.php" class="view-all">
                    View All Categories &rarr;
                </a>

            </div>


            <div class="category-grid">

                <?php foreach ($categories as $category): ?>

                    <a
                        href="shop.php?category=<?php echo urlencode($category["name"]); ?>"
                        class="category-card"
                    >

                        <div class="category-icon">
                            <?php echo e($category["icon"]); ?>
                        </div>

                        <span class="category-name">
                            <?php echo e($category["name"]); ?>
                        </span>

                    </a>

                <?php endforeach; ?>

            </div>

        </div>

    </section>


    <!-- =========================
         FEATURED PRODUCTS
    ========================= -->

    <section class="section" id="featured-products">

        <div class="container-wide">

            <div class="section-heading">

                <h2 class="section-title">
                    Featured Products
                </h2>

                <a href="shop.php" class="view-all">
                    View All Products &rarr;
                </a>

            </div>


            <div class="product-grid">

                <?php foreach ($products as $product): ?>

                    <article class="product-card">

                        <div class="product-image-wrap">

                            <?php
                            $badgeClass = "";

                            if (
                                strpos($product["badge"], "OFF") !== false
                            ) {
                                $badgeClass = "sale";
                            } elseif (
                                $product["badge"] === "New Arrival"
                            ) {
                                $badgeClass = "new";
                            }
                            ?>

                            <span class="product-badge <?php echo e($badgeClass); ?>">
                                <?php echo e($product["badge"]); ?>
                            </span>


                            <img
                                src="<?php echo e($product["image"]); ?>"
                                alt="<?php echo e($product["name"]); ?>"
                                loading="lazy"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >

                            <div class="product-placeholder"
                                 style="display:none;"
                                 aria-hidden="true">
                                <?php echo e($product["emoji"]); ?>
                            </div>

                        </div>


                        <div class="product-body">

                            <div class="product-category">
                                <?php echo e($product["category"]); ?>
                            </div>

                            <a
                                href="product_details.php?id=<?php echo (int) $product["id"]; ?>"
                                class="product-name"
                            >
                                <?php echo e($product["name"]); ?>
                            </a>


                            <div class="product-rating">

                                <span aria-label="Rated <?php echo e($product["rating"]); ?> out of 5">
                                    &#9733;&#9733;&#9733;&#9733;&#9733;
                                </span>

                                <small>
                                    (<?php echo (int) $product["reviews"]; ?>)
                                </small>

                            </div>


                            <div class="product-price-row">

                                <span class="product-price">
                                    <?php echo money($product["price"]); ?>
                                </span>

                                <?php if ($product["old_price"] > $product["price"]): ?>

                                    <span class="old-price">
                                        <?php echo money($product["old_price"]); ?>
                                    </span>

                                <?php endif; ?>

                            </div>


                            <!-- Session-based demonstration cart -->
                            <form method="POST" action="index.php">

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?php echo (int) $product["id"]; ?>"
                                >

                                <button
                                    type="submit"
                                    name="add_to_cart"
                                    value="1"
                                    class="add-cart-btn"
                                >
                                    &#128722; Add to Cart
                                </button>

                            </form>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        </div>

    </section>


    <!-- =========================
         PROMOTIONAL BANNERS
    ========================= -->

    <section class="container-wide">

        <div class="promo-grid">

            <div class="promo-card gaming">

                <div class="promo-content">

                    <h3>
                        Gaming Essentials<br>
                        Level Up Your Play
                    </h3>

                    <p>
                        Discover consoles, controllers, headphones,
                        and gaming accessories.
                    </p>

                    <a href="shop.php?category=Gaming"
                       class="promo-btn">
                        Shop Gaming &rarr;
                    </a>

                </div>

                <div class="promo-emoji" aria-hidden="true">
                    🎮
                </div>

            </div>


            <div class="promo-card home">

                <div class="promo-content">

                    <h3>
                        Better Living<br>
                        Everyday Essentials
                    </h3>

                    <p>
                        Explore practical products for your home
                        and everyday lifestyle.
                    </p>

                    <a href="shop.php?category=Home%20Appliances"
                       class="promo-btn">
                        Explore Products &rarr;
                    </a>

                </div>

                <div class="promo-emoji" aria-hidden="true">
                    🏡
                </div>

            </div>

        </div>

    </section>


    <!-- =========================
         WHY CHOOSE NOVAMART
    ========================= -->

    <section class="benefits-section" id="why-novamart">

        <div class="container-wide">

            <h2 class="benefits-title">
                Why Shop at NovaMart?
            </h2>

            <div class="benefits-grid">

                <div class="benefit">

                    <div class="benefit-icon" aria-hidden="true">
                        &#128666;
                    </div>

                    <h4>Fast & Reliable Delivery</h4>

                    <p>
                        Convenient delivery options to help
                        your orders arrive safely.
                    </p>

                </div>


                <div class="benefit">

                    <div class="benefit-icon" aria-hidden="true">
                        &#128274;
                    </div>

                    <h4>Secure Shopping</h4>

                    <p>
                        Shop with confidence and enjoy a
                        straightforward checkout experience.
                    </p>

                </div>


                <div class="benefit">

                    <div class="benefit-icon" aria-hidden="true">
                        &#9742;
                    </div>

                    <h4>Customer Support</h4>

                    <p>
                        Get assistance when you need help
                        with products or orders.
                    </p>

                </div>


                <div class="benefit">

                    <div class="benefit-icon" aria-hidden="true">
                        &#10004;
                    </div>

                    <h4>Quality Products</h4>

                    <p>
                        Explore a carefully organized range
                        of products at competitive prices.
                    </p>

                </div>

            </div>

        </div>

    </section>

</main>


<!-- =========================
     FOOTER
========================= -->

<footer class="footer" id="footer">

    <div class="container-wide">

        <div class="row g-4">

            <!-- Brand -->
            <div class="col-lg-4 col-md-6">

                <a href="index.php" class="brand">
                    🛍 Nova<span class="brand-blue">Mart</span>
                </a>

                <p class="footer-description">
                    Shop smarter and live better. Discover a modern
                    online shopping experience built around convenience,
                    choice, and value.
                </p>

                <span class="demo-label">
                    Codvanta Portfolio Demonstration
                </span>

                <div class="social-links">
                    <a href="#footer" aria-label="Facebook">f</a>
                    <a href="#footer" aria-label="Instagram">ig</a>
                    <a href="#footer" aria-label="YouTube">▶</a>
                </div>

            </div>


            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">

                <h4>Quick Links</h4>

                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="shop.php">Shop Products</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li><a href="#featured-products">Featured Products</a></li>
                    <li><a href="cart.php">Shopping Cart</a></li>
                </ul>

            </div>


            <!-- Customer Service -->
            <div class="col-lg-2 col-md-6">

                <h4>Customer Service</h4>

                <ul class="footer-links">
                    <li><a href="#footer">Help Centre</a></li>
                    <li><a href="#footer">Shipping Information</a></li>
                    <li><a href="#footer">Returns Policy</a></li>
                    <li><a href="#footer">Privacy Policy</a></li>
                    <li><a href="#footer">Terms & Conditions</a></li>
                </ul>

            </div>


            <!-- Newsletter -->
            <div class="col-lg-4 col-md-6">

                <h4>Stay Updated</h4>

                <p class="footer-description">
                    Discover new arrivals, special offers, and
                    shopping updates.
                </p>

                <form
                    class="newsletter-form"
                    onsubmit="event.preventDefault(); alert('Thank you for your interest! Newsletter registration is a demonstration feature.');"
                >

                    <input
                        type="email"
                        placeholder="Enter your email address"
                        aria-label="Email address"
                        required
                    >

                    <button type="submit" aria-label="Subscribe">
                        &rarr;
                    </button>

                </form>

                <p class="mt-3 mb-0"
                   style="font-size:11px;color:#aebdd5;">
                    Demonstration only. No email will be stored.
                </p>

            </div>

        </div>


        <div class="footer-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">
                    &copy; <?php echo date("Y"); ?>
                    NovaMart. All rights reserved.
                </div>

                <div class="col-md-6 text-end">
                    E-Commerce &amp; Inventory Management Platform
                    <br>
                    Designed by Codvanta
                </div>

            </div>

        </div>

    </div>

</footer>


<!-- Local Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Prevent accidental double-clicks while adding an item.
    document.querySelectorAll(".add-cart-btn").forEach(function(button) {
        button.addEventListener("click", function() {
            button.textContent = "Adding to Cart...";
            button.disabled = true;
        });
    });
</script>

</body>
</html>
