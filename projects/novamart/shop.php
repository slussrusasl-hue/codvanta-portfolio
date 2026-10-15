
<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart - Shop Catalogue
| Codvanta Portfolio Demonstration
|--------------------------------------------------------------------------
*/

// Demonstration products.
// Keep product IDs consistent with index.php so that the session cart
// identifies the same products across both pages.

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
        "emoji" => "🎧",
        "popularity" => 95,
        "date_added" => "2026-10-10"
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
        "emoji" => "⌚",
        "popularity" => 88,
        "date_added" => "2026-10-08"
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
        "emoji" => "💻",
        "popularity" => 99,
        "date_added" => "2026-10-14"
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
        "emoji" => "🎮",
        "popularity" => 82,
        "date_added" => "2026-09-30"
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
        "emoji" => "🧴",
        "popularity" => 75,
        "date_added" => "2026-09-28"
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
        "emoji" => "🎒",
        "popularity" => 85,
        "date_added" => "2026-10-01"
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
        "emoji" => "🎧",
        "popularity" => 92,
        "date_added" => "2026-10-05"
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
        "emoji" => "🍳",
        "popularity" => 78,
        "date_added" => "2026-09-25"
    ],
    [
        "id" => 9,
        "name" => "Bluetooth Speaker Mini",
        "category" => "Accessories",
        "price" => 39.99,
        "old_price" => 49.99,
        "rating" => 4.4,
        "reviews" => 59,
        "image" => "assets/images/speaker.jpg",
        "badge" => "20% OFF",
        "emoji" => "🔊",
        "popularity" => 70,
        "date_added" => "2026-10-12"
    ],
    [
        "id" => 10,
        "name" => "Running Sneakers",
        "category" => "Fashion",
        "price" => 74.99,
        "old_price" => 89.99,
        "rating" => 4.6,
        "reviews" => 110,
        "image" => "assets/images/sneakers.jpg",
        "badge" => "Trending",
        "emoji" => "👟",
        "popularity" => 89,
        "date_added" => "2026-10-09"
    ],
    [
        "id" => 11,
        "name" => "Mechanical Gaming Keyboard",
        "category" => "Gaming",
        "price" => 69.99,
        "old_price" => 89.99,
        "rating" => 4.8,
        "reviews" => 132,
        "image" => "assets/images/keyboard.jpg",
        "badge" => "Best Seller",
        "emoji" => "⌨️",
        "popularity" => 93,
        "date_added" => "2026-10-11"
    ],
    [
        "id" => 12,
        "name" => "Smart Home Lamp",
        "category" => "Home Appliances",
        "price" => 34.99,
        "old_price" => 44.99,
        "rating" => 4.3,
        "reviews" => 41,
        "image" => "assets/images/lamp.jpg",
        "badge" => "New Arrival",
        "emoji" => "💡",
        "popularity" => 67,
        "date_added" => "2026-10-13"
    ],
    [
        "id" => 13,
        "name" => "Wireless Gaming Headset",
        "category" => "Gaming",
        "price" => 94.99,
        "old_price" => 119.99,
        "rating" => 4.7,
        "reviews" => 86,
        "image" => "assets/images/gaming-headset.jpg",
        "badge" => "20% OFF",
        "emoji" => "🎧",
        "popularity" => 86,
        "date_added" => "2026-10-07"
    ],
    [
        "id" => 14,
        "name" => "Digital Camera",
        "category" => "Electronics",
        "price" => 329.99,
        "old_price" => 379.99,
        "rating" => 4.8,
        "reviews" => 103,
        "image" => "assets/images/camera.jpg",
        "badge" => "Popular",
        "emoji" => "📷",
        "popularity" => 90,
        "date_added" => "2026-10-03"
    ],
    [
        "id" => 15,
        "name" => "Premium Water Bottle",
        "category" => "Fashion",
        "price" => 19.99,
        "old_price" => 24.99,
        "rating" => 4.4,
        "reviews" => 36,
        "image" => "assets/images/bottle.jpg",
        "badge" => "Special Offer",
        "emoji" => "🥤",
        "popularity" => 62,
        "date_added" => "2026-09-20"
    ],
    [
        "id" => 16,
        "name" => "Home Coffee Maker",
        "category" => "Home Appliances",
        "price" => 109.99,
        "old_price" => 139.99,
        "rating" => 4.7,
        "reviews" => 72,
        "image" => "assets/images/coffee-maker.jpg",
        "badge" => "Best Seller",
        "emoji" => "☕",
        "popularity" => 83,
        "date_added" => "2026-10-06"
    ]
];

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

// Initialize session cart if needed.
if (!isset($_SESSION["novamart_cart"])) {
    $_SESSION["novamart_cart"] = [];
}

$notice = "";
$noticeType = "success";

// Add product to the demonstration cart.
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["add_to_cart"])
) {
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

// Read and validate search/filter inputs.
$search = trim((string) ($_GET["search"] ?? ""));
$category = trim((string) ($_GET["category"] ?? ""));
$sort = (string) ($_GET["sort"] ?? "featured");

$minPriceInput = trim((string) ($_GET["min_price"] ?? ""));
$maxPriceInput = trim((string) ($_GET["max_price"] ?? ""));

$minPrice = is_numeric($minPriceInput)
    ? max(0, (float) $minPriceInput)
    : null;

$maxPrice = is_numeric($maxPriceInput)
    ? max(0, (float) $maxPriceInput)
    : null;

$minRating = isset($_GET["rating"])
    && is_numeric($_GET["rating"])
    ? (float) $_GET["rating"]
    : 0;

$minRating = max(0, min(5, $minRating));

$dealsOnly = isset($_GET["deals"])
    && $_GET["deals"] === "1";

// Apply filters.
$filteredProducts = array_values(array_filter(
    $products,
    function ($product) use (
        $search,
        $category,
        $minPrice,
        $maxPrice,
        $minRating,
        $dealsOnly
    ) {
        $matchesSearch = $search === ""
            || stripos($product["name"], $search) !== false
            || stripos($product["category"], $search) !== false;

        $matchesCategory = $category === ""
            || strcasecmp($product["category"], $category) === 0;

        $matchesMinPrice = $minPrice === null
            || $product["price"] >= $minPrice;

        $matchesMaxPrice = $maxPrice === null
            || $product["price"] <= $maxPrice;

        $matchesRating = $product["rating"] >= $minRating;

        $matchesDeals = !$dealsOnly
            || $product["old_price"] > $product["price"];

        return $matchesSearch
            && $matchesCategory
            && $matchesMinPrice
            && $matchesMaxPrice
            && $matchesRating
            && $matchesDeals;
    }
));

// Sorting.
switch ($sort) {
    case "price_low":
        usort($filteredProducts, function ($a, $b) {
            return $a["price"] <=> $b["price"];
        });
        break;

    case "price_high":
        usort($filteredProducts, function ($a, $b) {
            return $b["price"] <=> $a["price"];
        });
        break;

    case "popular":
        usort($filteredProducts, function ($a, $b) {
            return $b["popularity"] <=> $a["popularity"];
        });
        break;

    case "rating":
        usort($filteredProducts, function ($a, $b) {
            return $b["rating"] <=> $a["rating"];
        });
        break;

    case "newest":
        usort($filteredProducts, function ($a, $b) {
            return strcmp($b["date_added"], $a["date_added"]);
        });
        break;

    case "discount":
        usort($filteredProducts, function ($a, $b) {
            $discountA = $a["old_price"] > 0
                ? ($a["old_price"] - $a["price"]) / $a["old_price"]
                : 0;

            $discountB = $b["old_price"] > 0
                ? ($b["old_price"] - $b["price"]) / $b["old_price"]
                : 0;

            return $discountB <=> $discountA;
        });
        break;

    default:
        usort($filteredProducts, function ($a, $b) {
            return $b["popularity"] <=> $a["popularity"];
        });
        break;
}

// Demonstration pagination.
$perPage = 8;
$totalProducts = count($filteredProducts);
$totalPages = max(1, (int) ceil($totalProducts / $perPage));

$page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
$page = $page === false || $page === null ? 1 : max(1, $page);
$page = min($page, $totalPages);

$offset = ($page - 1) * $perPage;

$visibleProducts = array_slice(
    $filteredProducts,
    $offset,
    $perPage
);

// Cart item count.
$cartCount = 0;

foreach ($_SESSION["novamart_cart"] as $item) {
    $cartCount += (int) ($item["quantity"] ?? 0);
}

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        "UTF-8"
    );
}

function money($amount) {
    return "$" . number_format((float) $amount, 2);
}

// Preserve current filters when navigating pages.
function pageUrl($pageNumber) {
    $params = $_GET;
    $params["page"] = $pageNumber;

    return "shop.php?" . http_build_query($params);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="Browse electronics, computing, fashion, gaming accessories and home products at NovaMart.">

    <title>Shop Products | NovaMart</title>

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
            background: #fff;
        }

        a {
            text-decoration: none;
        }

        .container-wide {
            width: min(1440px, 100%);
            margin: auto;
            padding-left: 28px;
            padding-right: 28px;
        }

        /* Header */

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

        .brand-blue {
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
        }

        .search-form input {
            min-width: 0;
            flex: 1;
            padding: 0 20px;
            border: 0;
            outline: 0;
            font-size: 14px;
        }

        .search-form button {
            width: 54px;
            border: 0;
            background: var(--blue);
            color: white;
            font-size: 22px;
            cursor: pointer;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-link {
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
            min-width: 21px;
            height: 21px;
            padding: 0 5px;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: #ff4058;
            color: white;
            font-size: 11px;
            font-weight: 800;
        }

        /* Navigation */

        .main-nav {
            border-top: 1px solid rgba(255,255,255,.12);
            background: #10264a;
        }

        .nav-inner {
            min-height: 48px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link {
            display: inline-block;
            padding: 14px 20px;
            color: #e5ecf8;
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--blue);
            color: white;
        }

        .shipping-message {
            margin-left: auto;
            color: #e4edff;
            font-size: 12px;
            white-space: nowrap;
        }

        /* Shop banner */

        .shop-banner {
            position: relative;
            margin-top: 24px;
            padding: 32px;
            overflow: hidden;
            border-radius: 16px;
            background:
                radial-gradient(
                    circle at 85% 20%,
                    rgba(255,255,255,.95),
                    transparent 25%
                ),
                linear-gradient(115deg, #f0f6ff, #c9dfff);
        }

        .shop-banner-content {
            position: relative;
            z-index: 2;
            max-width: 700px;
        }

        .banner-tag {
            display: inline-block;
            margin-bottom: 12px;
            padding: 6px 12px;
            border-radius: 30px;
            background: #d8e8ff;
            color: var(--blue-dark);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .8px;
        }

        .shop-banner h1 {
            margin: 0 0 12px;
            color: var(--navy);
            font-size: clamp(30px, 4vw, 43px);
            font-weight: 900;
            letter-spacing: -1px;
        }

        .shop-banner p {
            margin: 0;
            color: #526887;
            font-size: 15px;
            line-height: 1.8;
        }

        .banner-decoration {
            position: absolute;
            right: 45px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 110px;
            opacity: .9;
        }

        /* Breadcrumb */

        .breadcrumb-wrap {
            padding-top: 22px;
            padding-bottom: 22px;
        }

        .breadcrumb {
            margin: 0;
            font-size: 12px;
        }

        .breadcrumb a {
            color: var(--blue);
        }

        .breadcrumb-item.active {
            color: var(--muted);
        }

        /* Layout */

        .shop-layout {
            display: grid;
            grid-template-columns: 245px minmax(0, 1fr);
            align-items: start;
            gap: 25px;
            padding-bottom: 50px;
        }

        .filter-panel {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: white;
        }

        .filter-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px;
            border-bottom: 1px solid var(--border);
        }

        .filter-heading h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 900;
        }

        .clear-link {
            color: var(--blue);
            font-size: 12px;
            font-weight: 800;
        }

        .filter-group {
            padding: 18px;
            border-bottom: 1px solid var(--border);
        }

        .filter-group:last-child {
            border-bottom: 0;
        }

        .filter-group h3 {
            margin: 0 0 15px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 900;
        }

        .category-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 13px;
            color: #53647d;
            font-size: 13px;
            cursor: pointer;
        }

        .category-option:last-child {
            margin-bottom: 0;
        }

        .category-option:hover,
        .category-option.selected {
            color: var(--blue);
            font-weight: 800;
        }

        .category-option input {
            margin-right: 8px;
            accent-color: var(--blue);
        }

        .category-option .category-label {
            display: flex;
            align-items: center;
        }

        .filter-count {
            color: #9aa7b9;
            font-size: 11px;
        }

        .price-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .price-inputs input {
            width: 100%;
            min-width: 0;
            padding: 10px 8px;
            border: 1px solid var(--border);
            border-radius: 8px;
            outline: 0;
            font-size: 12px;
        }

        .price-inputs input:focus {
            border-color: var(--blue);
        }

        .filter-apply {
            width: 100%;
            margin-top: 12px;
            padding: 10px;
            border: 0;
            border-radius: 8px;
            background: var(--blue);
            color: white;
            font-size: 12px;
            font-weight: 900;
            cursor: pointer;
        }

        .filter-apply:hover {
            background: var(--blue-dark);
        }

        .rating-option {
            display: block;
            margin-bottom: 12px;
            color: #53647d;
            font-size: 12px;
            cursor: pointer;
        }

        .rating-option:last-child {
            margin-bottom: 0;
        }

        .rating-option input {
            margin-right: 6px;
            accent-color: var(--blue);
        }

        .rating-stars {
            color: #f4a51c;
            letter-spacing: -1px;
        }

        /* Product toolbar */

        .catalogue-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 17px;
        }

        .catalogue-header h2 {
            margin: 0 0 5px;
            color: var(--navy);
            font-size: 23px;
            font-weight: 900;
        }

        .catalogue-subtitle {
            color: var(--muted);
            font-size: 12px;
        }

        .sort-control {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #63718a;
            font-size: 12px;
            white-space: nowrap;
        }

        .sort-control select {
            max-width: 190px;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--text);
            font-size: 12px;
            outline: 0;
        }

        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 10px;
            border: 1px solid #d7e5ff;
            border-radius: 20px;
            background: #f0f6ff;
            color: #2459ad;
            font-size: 11px;
        }

        .filter-chip a {
            color: #2459ad;
            font-weight: 900;
        }

        /* Product cards */

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .product-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: white;
            box-shadow: 0 4px 15px rgba(19,42,80,.035);
            transition: .25s;
        }

        .product-card:hover {
            transform: translateY(-4px);
            border-color: #c9d9ff;
            box-shadow: 0 12px 25px rgba(19,42,80,.09);
        }

        .product-image-wrap {
            position: relative;
            height: 175px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(145deg, #f5f8ff, #eaf1fc);
        }

        .product-image-wrap img {
            width: 100%;
            height: 100%;
            padding: 17px;
            object-fit: contain;
            transition: transform .3s;
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
            font-size: 65px;
        }

        .product-badge {
            position: absolute;
            z-index: 2;
            top: 9px;
            left: 9px;
            padding: 5px 8px;
            border-radius: 20px;
            background: var(--blue);
            color: white;
            font-size: 9px;
            font-weight: 900;
        }

        .product-badge.sale {
            background: #ef4444;
        }

        .product-badge.new {
            background: #15945d;
        }

        .product-body {
            padding: 13px;
        }

        .product-category {
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .product-name {
            display: block;
            min-height: 39px;
            margin-bottom: 7px;
            color: var(--navy);
            font-size: 13px;
            font-weight: 900;
            line-height: 1.5;
        }

        .product-name:hover {
            color: var(--blue);
        }

        .product-rating {
            margin-bottom: 10px;
            color: #f4a51c;
            font-size: 11px;
        }

        .product-rating small {
            color: var(--muted);
        }

        .product-price-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 12px;
        }

        .product-price {
            color: var(--blue);
            font-size: 17px;
            font-weight: 900;
        }

        .old-price {
            color: #98a2b3;
            font-size: 10px;
            text-decoration: line-through;
        }

        .discount-percent {
            color: #15945d;
            font-size: 10px;
            font-weight: 900;
        }

        .add-cart-btn {
            width: 100%;
            padding: 10px 8px;
            border: 0;
            border-radius: 8px;
            background: var(--blue);
            color: white;
            font-size: 12px;
            font-weight: 900;
            cursor: pointer;
        }

        .add-cart-btn:hover {
            background: var(--blue-dark);
        }

        .add-cart-btn:disabled {
            opacity: .7;
            cursor: wait;
        }

        /* Empty results */

        .empty-state {
            padding: 65px 20px;
            border: 1px dashed #cbd8ec;
            border-radius: 13px;
            background: #f8faff;
            text-align: center;
        }

        .empty-icon {
            margin-bottom: 15px;
            font-size: 55px;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--navy);
            font-size: 21px;
            font-weight: 900;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 13px;
        }

        .empty-state .primary-btn {
            display: inline-block;
            margin-top: 8px;
            padding: 11px 18px;
            border-radius: 8px;
            background: var(--blue);
            color: white;
            font-size: 12px;
            font-weight: 900;
        }

        /* Pagination */

        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 27px;
        }

        .pagination-info {
            color: var(--muted);
            font-size: 12px;
        }

        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin: 0;
        }

        .page-link {
            display: flex;
            min-width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--navy);
            font-size: 12px;
        }

        .page-link:hover,
        .page-link.active {
            border-color: var(--blue);
            background: var(--blue);
            color: white;
        }

        .page-link.disabled {
            color: #a6b0bf;
            pointer-events: none;
        }

        /* Benefits and footer */

        .benefits-section {
            padding: 30px 0;
            background: #f1f6ff;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
        }

        .benefit {
            text-align: center;
            padding: 10px;
        }

        .benefit-icon {
            margin-bottom: 9px;
            color: var(--blue);
            font-size: 30px;
        }

        .benefit h4 {
            margin-bottom: 7px;
            color: var(--navy);
            font-size: 13px;
            font-weight: 900;
        }

        .benefit p {
            margin: auto;
            max-width: 220px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .footer {
            padding-top: 35px;
            background: var(--navy);
            color: #c3cfe4;
        }

        .footer .brand {
            display: inline-block;
            margin-bottom: 15px;
            font-size: 25px;
        }

        .footer-description {
            max-width: 300px;
            color: #b5c3dc;
            font-size: 12px;
            line-height: 1.8;
        }

        .footer h4 {
            margin-bottom: 15px;
            color: white;
            font-size: 14px;
            font-weight: 900;
        }

        .footer-links {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #c3cfe4;
            font-size: 12px;
        }

        .footer-links a:hover {
            color: #78a7ff;
        }

        .footer-bottom {
            margin-top: 25px;
            padding: 18px 0;
            border-top: 1px solid rgba(255,255,255,.13);
            color: #aebdd5;
            font-size: 11px;
        }

        .cart-alert {
            margin-top: 16px;
            border-radius: 10px;
        }

        /* Responsive */

        @media (max-width: 1250px) {
            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .container-wide {
                padding-left: 20px;
                padding-right: 20px;
            }

            .header-actions {
                gap: 12px;
            }

            .nav-link {
                padding-left: 13px;
                padding-right: 13px;
            }
        }

        @media (max-width: 900px) {
            .header-main {
                flex-wrap: wrap;
                padding-top: 15px;
                padding-bottom: 15px;
            }

            .search-form {
                order: 3;
                width: 100%;
            }

            .shop-layout {
                grid-template-columns: 210px minmax(0, 1fr);
                gap: 15px;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .banner-decoration {
                right: 20px;
                font-size: 80px;
            }

            .shipping-message {
                display: none;
            }
        }

        @media (max-width: 650px) {
            .container-wide {
                padding-left: 15px;
                padding-right: 15px;
            }

            .brand {
                font-size: 25px;
            }

            .header-link {
                font-size: 12px;
            }

            .header-link .link-text {
                display: none;
            }

            .nav-inner {
                overflow-x: auto;
            }

            .nav-link {
                padding: 14px 12px;
                font-size: 12px;
            }

            .shop-banner {
                margin-top: 15px;
                padding: 25px 20px;
            }

            .shop-banner h1 {
                font-size: 31px;
            }

            .shop-banner p {
                max-width: 290px;
                font-size: 13px;
            }

            .banner-decoration {
                right: 8px;
                bottom: 4px;
                top: auto;
                transform: none;
                font-size: 58px;
                opacity: .35;
            }

            .breadcrumb-wrap {
                padding-top: 17px;
                padding-bottom: 17px;
            }

            .shop-layout {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .filter-panel {
                width: 100%;
            }

            .filter-group {
                padding: 15px;
            }

            .catalogue-area {
                width: 100%;
            }

            .catalogue-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .catalogue-header h2 {
                font-size: 22px;
            }

            .sort-control {
                width: 100%;
                justify-content: space-between;
            }

            .sort-control select {
                flex: 1;
            }

            .product-image-wrap {
                height: 145px;
            }

            .product-body {
                padding: 11px;
            }

            .product-name {
                font-size: 12px;
            }

            .product-price {
                font-size: 15px;
            }

            .pagination-wrap {
                align-items: flex-start;
                flex-direction: column;
            }

            .benefits-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .footer .row > div {
                margin-bottom: 18px;
            }
        }

        @media (max-width: 360px) {
            .product-grid {
                gap: 9px;
            }

            .product-image-wrap {
                height: 120px;
            }

            .product-price-row {
                gap: 4px;
            }

            .old-price {
                font-size: 9px;
            }
        }
    </style>
</head>

<body>

<!-- HEADER -->

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

            <form action="shop.php"
                  method="GET"
                  class="search-form">

                <input
                    type="search"
                    name="search"
                    placeholder="Search for products, brands and more..."
                    value="<?php echo e($search); ?>"
                    aria-label="Search products"
                >

                <?php if ($category !== ""): ?>
                    <input type="hidden"
                           name="category"
                           value="<?php echo e($category); ?>">
                <?php endif; ?>

                <button type="submit" aria-label="Search">
                    &#9906;
                </button>

            </form>

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

    <nav class="main-nav">

        <div class="container-wide">

            <div class="nav-inner">

                <a href="index.php" class="nav-link">
                    &#8962; Home
                </a>

                <a href="shop.php" class="nav-link active">
                    Shop Products
                </a>

                <a href="shop.php?deals=1" class="nav-link">
                    Deals
                </a>

                <a href="index.php#why-novamart" class="nav-link">
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


    <!-- PAGE BANNER -->

    <section class="container-wide">

        <div class="shop-banner">

            <div class="shop-banner-content">

                <span class="banner-tag">
                    THE NOVAMART COLLECTION
                </span>

                <h1>Find Your Next Favourite</h1>

                <p>
                    Explore our collection of electronics, fashion,
                    gaming accessories, home essentials and more.
                    Quality products, all in one place.
                </p>

            </div>

            <div class="banner-decoration" aria-hidden="true">
                🛍️
            </div>

        </div>

    </section>


    <!-- BREADCRUMB -->

    <div class="container-wide breadcrumb-wrap">

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="index.php">Home</a>
                </li>

                <li class="breadcrumb-item active"
                    aria-current="page">
                    Shop Products
                </li>

            </ol>

        </nav>

    </div>


    <!-- SHOP CONTENT -->

    <section class="container-wide">

        <div class="shop-layout">

            <!-- FILTER SIDEBAR -->

            <aside class="filter-panel">

                <div class="filter-heading">

                    <h2>&#9881; Filters</h2>

                    <a href="shop.php" class="clear-link">
                        Clear All
                    </a>

                </div>


                <form action="shop.php"
                      method="GET"
                      id="filterForm">

                    <?php if ($search !== ""): ?>
                        <input type="hidden"
                               name="search"
                               value="<?php echo e($search); ?>">
                    <?php endif; ?>

                    <?php if ($sort !== "featured"): ?>
                        <input type="hidden"
                               name="sort"
                               value="<?php echo e($sort); ?>">
                    <?php endif; ?>

                    <!-- Categories -->

                    <div class="filter-group">

                        <h3>Categories</h3>

                        <label class="category-option <?php echo $category === "" ? "selected" : ""; ?>">

                            <span class="category-label">
                                <input
                                    type="radio"
                                    name="category"
                                    value=""
                                    <?php echo $category === "" ? "checked" : ""; ?>
                                    onchange="this.form.submit()"
                                >

                                All Categories
                            </span>

                        </label>

                        <?php foreach ($categories as $cat): ?>

                            <?php
                            $categoryCount = 0;

                            foreach ($products as $product) {
                                if ($product["category"] === $cat) {
                                    $categoryCount++;
                                }
                            }
                            ?>

                            <label class="category-option <?php echo strcasecmp($category, $cat) === 0 ? "selected" : ""; ?>">

                                <span class="category-label">

                                    <input
                                        type="radio"
                                        name="category"
                                        value="<?php echo e($cat); ?>"
                                        <?php echo strcasecmp($category, $cat) === 0 ? "checked" : ""; ?>
                                        onchange="this.form.submit()"
                                    >

                                    <?php echo e($cat); ?>

                                </span>

                                <span class="filter-count">
                                    <?php echo $categoryCount; ?>
                                </span>

                            </label>

                        <?php endforeach; ?>

                    </div>


                    <!-- Price Filter -->

                    <div class="filter-group">

                        <h3>Price Range</h3>

                        <div class="price-inputs">

                            <input
                                type="number"
                                name="min_price"
                                min="0"
                                step="0.01"
                                placeholder="Min $"
                                aria-label="Minimum price"
                                value="<?php echo e($minPriceInput); ?>"
                            >

                            <input
                                type="number"
                                name="max_price"
                                min="0"
                                step="0.01"
                                placeholder="Max $"
                                aria-label="Maximum price"
                                value="<?php echo e($maxPriceInput); ?>"
                            >

                        </div>

                        <button type="submit"
                                class="filter-apply">
                            Apply Price Filter
                        </button>

                    </div>


                    <!-- Rating Filter -->

                    <div class="filter-group">

                        <h3>Customer Rating</h3>

                        <?php foreach ([4, 3, 2, 1] as $rating): ?>

                            <label class="rating-option">

                                <input
                                    type="radio"
                                    name="rating"
                                    value="<?php echo $rating; ?>"
                                    <?php echo (int) $minRating === $rating ? "checked" : ""; ?>
                                    onchange="this.form.submit()"
                                >

                                <span class="rating-stars">
                                    <?php echo str_repeat("★", $rating); ?>
                                </span>

                                &amp; above

                            </label>

                        <?php endforeach; ?>

                    </div>


                    <!-- Special Offers -->

                    <div class="filter-group">

                        <h3>Special Offers</h3>

                        <label class="category-option">

                            <span class="category-label">

                                <input
                                    type="checkbox"
                                    name="deals"
                                    value="1"
                                    <?php echo $dealsOnly ? "checked" : ""; ?>
                                    onchange="this.form.submit()"
                                >

                                Discounted Products

                            </span>

                        </label>

                    </div>

                </form>

            </aside>


            <!-- PRODUCT CATALOGUE -->

            <div class="catalogue-area">

                <div class="catalogue-header">

                    <div>

                        <h2>
                            <?php
                            echo $category !== ""
                                ? e($category)
                                : "All Products";
                            ?>
                        </h2>

                        <div class="catalogue-subtitle">

                            Showing
                            <?php echo $totalProducts > 0 ? $offset + 1 : 0; ?>
                            -
                            <?php echo min($offset + $perPage, $totalProducts); ?>

                            of <?php echo $totalProducts; ?> products

                        </div>

                    </div>


                    <!-- Sorting -->

                    <form method="GET"
                          action="shop.php"
                          class="sort-control">

                        <?php if ($search !== ""): ?>
                            <input type="hidden"
                                   name="search"
                                   value="<?php echo e($search); ?>">
                        <?php endif; ?>

                        <?php if ($category !== ""): ?>
                            <input type="hidden"
                                   name="category"
                                   value="<?php echo e($category); ?>">
                        <?php endif; ?>

                        <?php if ($minPriceInput !== ""): ?>
                            <input type="hidden"
                                   name="min_price"
                                   value="<?php echo e($minPriceInput); ?>">
                        <?php endif; ?>

                        <?php if ($maxPriceInput !== ""): ?>
                            <input type="hidden"
                                   name="max_price"
                                   value="<?php echo e($maxPriceInput); ?>">
                        <?php endif; ?>

                        <?php if ($minRating > 0): ?>
                            <input type="hidden"
                                   name="rating"
                                   value="<?php echo e($minRating); ?>">
                        <?php endif; ?>

                        <?php if ($dealsOnly): ?>
                            <input type="hidden" name="deals" value="1">
                        <?php endif; ?>

                        <label for="sort">Sort by:</label>

                        <select name="sort"
                                id="sort"
                                onchange="this.form.submit()">

                            <option value="featured"
                                <?php echo $sort === "featured" ? "selected" : ""; ?>>
                                Featured
                            </option>

                            <option value="popular"
                                <?php echo $sort === "popular" ? "selected" : ""; ?>>
                                Most Popular
                            </option>

                            <option value="newest"
                                <?php echo $sort === "newest" ? "selected" : ""; ?>>
                                Newest Arrivals
                            </option>

                            <option value="price_low"
                                <?php echo $sort === "price_low" ? "selected" : ""; ?>>
                                Price: Low to High
                            </option>

                            <option value="price_high"
                                <?php echo $sort === "price_high" ? "selected" : ""; ?>>
                                Price: High to Low
                            </option>

                            <option value="rating"
                                <?php echo $sort === "rating" ? "selected" : ""; ?>>
                                Highest Rated
                            </option>

                            <option value="discount"
                                <?php echo $sort === "discount" ? "selected" : ""; ?>>
                                Biggest Discount
                            </option>

                        </select>

                    </form>

                </div>


                <!-- Active Filters -->

                <?php if (
                    $search !== ""
                    || $category !== ""
                    || $minPrice !== null
                    || $maxPrice !== null
                    || $minRating > 0
                    || $dealsOnly
                ): ?>

                    <div class="active-filters">

                        <?php if ($search !== ""): ?>

                            <span class="filter-chip">
                                Search: <?php echo e($search); ?>
                                <a href="<?php
                                    $params = $_GET;
                                    unset($params["search"], $params["page"]);
                                    echo "shop.php?" . http_build_query($params);
                                ?>" aria-label="Remove search filter">&times;</a>
                            </span>

                        <?php endif; ?>

                        <?php if ($category !== ""): ?>

                            <span class="filter-chip">
                                <?php echo e($category); ?>

                                <a href="<?php
                                    $params = $_GET;
                                    unset($params["category"], $params["page"]);
                                    echo "shop.php?" . http_build_query($params);
                                ?>" aria-label="Remove category filter">&times;</a>
                            </span>

                        <?php endif; ?>

                        <?php if ($minPrice !== null || $maxPrice !== null): ?>

                            <span class="filter-chip">
                                Price:
                                <?php echo $minPrice !== null ? money($minPrice) : "Any"; ?>
                                -
                                <?php echo $maxPrice !== null ? money($maxPrice) : "Any"; ?>
                            </span>

                        <?php endif; ?>

                        <?php if ($minRating > 0): ?>

                            <span class="filter-chip">
                                Rating: <?php echo e($minRating); ?>+ stars
                            </span>

                        <?php endif; ?>

                        <?php if ($dealsOnly): ?>

                            <span class="filter-chip">
                                Discounted products
                            </span>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>


                <!-- PRODUCTS -->

                <?php if (count($visibleProducts) > 0): ?>

                    <div class="product-grid">

                        <?php foreach ($visibleProducts as $product): ?>

                            <?php
                            $badgeClass = "";

                            if (strpos($product["badge"], "OFF") !== false) {
                                $badgeClass = "sale";
                            } elseif ($product["badge"] === "New Arrival") {
                                $badgeClass = "new";
                            }

                            $discountPercent = $product["old_price"] > $product["price"]
                                ? round(
                                    (
                                        $product["old_price"] - $product["price"]
                                    ) / $product["old_price"] * 100
                                )
                                : 0;
                            ?>

                            <article class="product-card">

                                <div class="product-image-wrap">

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

                                        <?php if ($discountPercent > 0): ?>

                                            <span class="old-price">
                                                <?php echo money($product["old_price"]); ?>
                                            </span>

                                            <span class="discount-percent">
                                                -<?php echo $discountPercent; ?>%
                                            </span>

                                        <?php endif; ?>

                                    </div>


                                    <form action="shop.php"
                                          method="POST">

                                        <!-- Preserve current filters after adding -->
                                        <?php foreach ($_GET as $key => $value): ?>

                                            <?php if (
                                                is_scalar($value)
                                                && $key !== "page"
                                            ): ?>

                                                <input
                                                    type="hidden"
                                                    name="return_params[<?php echo e($key); ?>]"
                                                    value="<?php echo e($value); ?>"
                                                >

                                            <?php endif; ?>

                                        <?php endforeach; ?>

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


                    <!-- PAGINATION -->

                    <?php if ($totalPages > 1): ?>

                        <div class="pagination-wrap">

                            <div class="pagination-info">
                                Page <?php echo $page; ?>
                                of <?php echo $totalPages; ?>
                            </div>

                            <nav aria-label="Product pages">

                                <div class="pagination">

                                    <a
                                        class="page-link <?php echo $page <= 1 ? "disabled" : ""; ?>"
                                        href="<?php echo e(pageUrl(max(1, $page - 1))); ?>"
                                        aria-label="Previous page"
                                    >
                                        &laquo;
                                    </a>

                                    <?php for (
                                        $pageNumber = 1;
                                        $pageNumber <= $totalPages;
                                        $pageNumber++
                                    ): ?>

                                        <a
                                            class="page-link <?php echo $pageNumber === $page ? "active" : ""; ?>"
                                            href="<?php echo e(pageUrl($pageNumber)); ?>"
                                            <?php echo $pageNumber === $page ? 'aria-current="page"' : ""; ?>
                                        >
                                            <?php echo $pageNumber; ?>
                                        </a>

                                    <?php endfor; ?>

                                    <a
                                        class="page-link <?php echo $page >= $totalPages ? "disabled" : ""; ?>"
                                        href="<?php echo e(pageUrl(min($totalPages, $page + 1))); ?>"
                                        aria-label="Next page"
                                    >
                                        &raquo;
                                    </a>

                                </div>

                            </nav>

                        </div>

                    <?php endif; ?>


                <?php else: ?>

                    <!-- NO RESULTS -->

                    <div class="empty-state">

                        <div class="empty-icon" aria-hidden="true">
                            &#128269;
                        </div>

                        <h3>No Products Found</h3>

                        <p>
                            We couldn't find products matching your
                            search or filters. Try changing your
                            search terms or price range.
                        </p>

                        <a href="shop.php" class="primary-btn">
                            View All Products
                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </section>


    <!-- SHOPPING BENEFITS -->

    <section class="benefits-section">

        <div class="container-wide">

            <div class="benefits-grid">

                <div class="benefit">
                    <div class="benefit-icon">&#128666;</div>
                    <h4>Convenient Delivery</h4>
                    <p>Explore products and place orders with ease.</p>
                </div>

                <div class="benefit">
                    <div class="benefit-icon">&#128274;</div>
                    <h4>Secure Shopping</h4>
                    <p>A straightforward shopping experience.</p>
                </div>

                <div class="benefit">
                    <div class="benefit-icon">&#9742;</div>
                    <h4>Customer Support</h4>
                    <p>Help for your product and order questions.</p>
                </div>

                <div class="benefit">
                    <div class="benefit-icon">&#10004;</div>
                    <h4>Great Selection</h4>
                    <p>Discover products for work, home and play.</p>
                </div>

            </div>

        </div>

    </section>

</main>


<!-- FOOTER -->

<footer class="footer" id="footer">

    <div class="container-wide">

        <div class="row g-4">

            <div class="col-lg-4 col-md-6">

                <a href="index.php" class="brand">
                    🛍 Nova<span class="brand-blue">Mart</span>
                </a>

                <p class="footer-description">
                    Shop smarter and live better. Discover a modern
                    online shopping experience with products for
                    everyday life.
                </p>

                <span style="font-size:10px;color:#aebdd5;">
                    Codvanta Portfolio Demonstration
                </span>

            </div>


            <div class="col-lg-2 col-md-6">

                <h4>Quick Links</h4>

                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="shop.php">Shop Products</a></li>
                    <li><a href="cart.php">Shopping Cart</a></li>
                    <li><a href="login.php">My Account</a></li>
                </ul>

            </div>


            <div class="col-lg-2 col-md-6">

                <h4>Customer Service</h4>

                <ul class="footer-links">
                    <li><a href="#footer">Help Centre</a></li>
                    <li><a href="#footer">Shipping Information</a></li>
                    <li><a href="#footer">Returns Policy</a></li>
                    <li><a href="#footer">Privacy Policy</a></li>
                </ul>

            </div>


            <div class="col-lg-4 col-md-6">

                <h4>Shopping Made Simple</h4>

                <p class="footer-description">
                    Browse our collection, compare prices and
                    discover products that match your needs.
                </p>

                <a href="index.php" class="footer-links"
                   style="color:#7baaff;font-size:12px;">
                    Return to Homepage &rarr;
                </a>

            </div>

        </div>


        <div class="footer-bottom">

            <div class="row">

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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Disable a clicked Add to Cart button to prevent accidental
    // repeated submissions while the request is being processed.
    document.querySelectorAll(".add-cart-btn").forEach(function(button) {
        button.addEventListener("click", function() {
            button.textContent = "Adding to Cart...";
            button.disabled = true;
        });
    });
</script>

</body>
</html>
