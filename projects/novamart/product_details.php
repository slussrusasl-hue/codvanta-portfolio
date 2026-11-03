
<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart - Product Details Page
|--------------------------------------------------------------------------
| Demonstration features:
| - Product details and specifications
| - Discount and rating display
| - Quantity selector
| - Add to cart using PHP sessions
| - Related products
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["novamart_cart"])) {
    $_SESSION["novamart_cart"] = [];
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function money($amount) {
    return "Rs. " . number_format((float)$amount, 2);
}

/*
|--------------------------------------------------------------------------
| Demo product catalogue
|--------------------------------------------------------------------------
| Keep product IDs, names, prices and image filenames synchronized
| with the catalogue in shop.php.
*/

$products = [
    1 => [
        "id" => 1,
        "name" => "Wireless Bluetooth Earbuds",
        "category" => "Electronics",
        "price" => 4500,
        "old_price" => 6000,
        "rating" => 4.8,
        "reviews" => 124,
        "image" => "../images/novamart/SBT-907-web-png-min.webp",
        "emoji" => "🎧",
        "badge" => "Best Seller",
        "stock" => 18,
        "brand" => "NovaSound",
        "description" => "Enjoy your favourite music with clear sound, comfortable earbuds and a compact charging case. Designed for everyday listening, online meetings and travel.",
        "features" => [
            "High-quality stereo sound",
            "Bluetooth wireless connectivity",
            "Compact charging case",
            "Built-in microphone",
            "Comfortable in-ear design"
        ],
        "specifications" => [
            "Brand" => "NovaSound",
            "Connectivity" => "Bluetooth",
            "Colour" => "Black",
            "Usage" => "Music and calls",
            "Warranty" => "6 months"
        ]
    ],
    2 => [
        "id" => 2,
        "name" => "Smart Fitness Watch",
        "category" => "Electronics",
        "price" => 7500,
        "old_price" => 9500,
        "rating" => 4.6,
        "reviews" => 86,
        "image" => "../images/novamart/smart_watch.webp",
        "emoji" => "⌚",
        "badge" => "Popular",
        "stock" => 12,
        "brand" => "NovaFit",
        "description" => "Keep your daily activities organised with a stylish smartwatch. Its modern design makes it suitable for workouts, daily routines and casual occasions.",
        "features" => [
            "Activity tracking",
            "Step counter",
            "Call and message notifications",
            "Multiple watch faces",
            "Rechargeable battery"
        ],
        "specifications" => [
            "Brand" => "NovaFit",
            "Display" => "Colour touchscreen",
            "Connectivity" => "Bluetooth",
            "Colour" => "Black",
            "Warranty" => "1 year"
        ]
    ],
    3 => [
        "id" => 3,
        "name" => "Performance Laptop",
        "category" => "Computing",
        "price" => 185000,
        "old_price" => 210000,
        "rating" => 4.9,
        "reviews" => 58,
        "image" => "../images/novamart/laptop.png",
        "emoji" => "💻",
        "badge" => "Premium",
        "stock" => 7,
        "brand" => "NovaTech",
        "description" => "A versatile laptop for studying, office productivity, browsing and everyday computing. Its modern design makes it a practical choice for students and professionals.",
        "features" => [
            "High-resolution display",
            "Multitasking performance",
            "Built-in webcam",
            "Wi-Fi connectivity",
            "Portable design"
        ],
        "specifications" => [
            "Brand" => "NovaTech",
            "Category" => "Laptop",
            "Memory" => "8 GB RAM",
            "Storage" => "512 GB SSD",
            "Warranty" => "1 year"
        ]
    ],
    4 => [
        "id" => 4,
        "name" => "Wireless Gaming Mouse",
        "category" => "Gaming",
        "price" => 3200,
        "old_price" => 4200,
        "rating" => 4.5,
        "reviews" => 72,
        "image" => "../images/novamart/gaming_mouse.jpg",
        "emoji" => "🖱️",
        "badge" => "Deal",
        "stock" => 24,
        "brand" => "NovaPlay",
        "description" => "Upgrade your desktop setup with a responsive wireless mouse designed for gaming, work and everyday browsing.",
        "features" => [
            "Wireless operation",
            "Ergonomic design",
            "Adjustable sensitivity",
            "Comfortable grip",
            "Suitable for gaming and office use"
        ],
        "specifications" => [
            "Brand" => "NovaPlay",
            "Connectivity" => "Wireless",
            "Type" => "Optical mouse",
            "Colour" => "Black",
            "Warranty" => "6 months"
        ]
    ],
    5 => [
        "id" => 5,
        "name" => "Portable Bluetooth Speaker",
        "category" => "Electronics",
        "price" => 5500,
        "old_price" => 7000,
        "rating" => 4.7,
        "reviews" => 93,
        "image" => "../images/novamart/speaker.png",
        "emoji" => "🔊",
        "badge" => "Popular",
        "stock" => 15,
        "brand" => "NovaSound",
        "description" => "Take your music anywhere with a portable Bluetooth speaker suitable for home listening, outdoor activities and small gatherings.",
        "features" => [
            "Bluetooth connectivity",
            "Portable body",
            "Rechargeable battery",
            "Easy-to-use controls",
            "Suitable for indoor and outdoor use"
        ],
        "specifications" => [
            "Brand" => "NovaSound",
            "Connectivity" => "Bluetooth",
            "Type" => "Portable speaker",
            "Colour" => "Black",
            "Warranty" => "6 months"
        ]
    ],
    6 => [
        "id" => 6,
        "name" => "Premium Smartphone",
        "category" => "Electronics",
        "price" => 89000,
        "old_price" => 99000,
        "rating" => 4.7,
        "reviews" => 112,
        "image" => "../images/novamart/smartphone.png",
        "emoji" => "📱",
        "badge" => "Hot Deal",
        "stock" => 9,
        "brand" => "NovaTech",
        "description" => "A modern smartphone for communication, photography, entertainment and daily productivity. Enjoy a clean design and an easy-to-use experience.",
        "features" => [
            "Large colour display",
            "Front and rear cameras",
            "Wireless connectivity",
            "Touchscreen interface",
            "Rechargeable battery"
        ],
        "specifications" => [
            "Brand" => "NovaTech",
            "Category" => "Smartphone",
            "Storage" => "128 GB",
            "Connectivity" => "4G / Wi-Fi",
            "Warranty" => "1 year"
        ]
    ],
    7 => [
        "id" => 7,
        "name" => "Mechanical Gaming Keyboard",
        "category" => "Gaming",
        "price" => 6800,
        "old_price" => 8500,
        "rating" => 4.6,
        "reviews" => 64,
        "image" => "../images/novamart/keyboard.png",
        "emoji" => "⌨️",
        "badge" => "Deal",
        "stock" => 11,
        "brand" => "NovaPlay",
        "description" => "A stylish keyboard for gaming, programming and everyday computer use, with a comfortable layout and responsive typing experience.",
        "features" => [
            "Mechanical-style key switches",
            "Comfortable key layout",
            "Durable construction",
            "Suitable for gaming and work",
            "Easy USB connection"
        ],
        "specifications" => [
            "Brand" => "NovaPlay",
            "Connection" => "USB",
            "Layout" => "Full-size",
            "Colour" => "Black",
            "Warranty" => "1 year"
        ]
    ],
    8 => [
        "id" => 8,
        "name" => "Everyday Backpack",
        "category" => "Fashion",
        "price" => 2800,
        "old_price" => 3500,
        "rating" => 4.4,
        "reviews" => 41,
        "image" => "../images/novamart/backpack.png",
        "emoji" => "🎒",
        "badge" => "",
        "stock" => 20,
        "brand" => "NovaStyle",
        "description" => "Carry your daily essentials in a practical backpack suitable for school, university, work and travel.",
        "features" => [
            "Spacious main compartment",
            "Multiple storage pockets",
            "Adjustable shoulder straps",
            "Everyday casual design",
            "Lightweight construction"
        ],
        "specifications" => [
            "Brand" => "NovaStyle",
            "Category" => "Backpack",
            "Material" => "Fabric",
            "Colour" => "Black",
            "Usage" => "School, work and travel"
        ]
    ],
    9 => [
        "id" => 9,
        "name" => "Digital Air Fryer",
        "category" => "Home Appliances",
        "price" => 14500,
        "old_price" => 17500,
        "rating" => 4.6,
        "reviews" => 39,
        "image" => "../images/novamart/air-fryer.png",
        "emoji" => "🍟",
        "badge" => "Special Offer",
        "stock" => 6,
        "brand" => "NovaHome",
        "description" => "Prepare a variety of meals with a convenient countertop air fryer designed for modern kitchens and everyday cooking.",
        "features" => [
            "Digital control panel",
            "Adjustable cooking settings",
            "Removable cooking basket",
            "Easy-to-clean design",
            "Compact countertop size"
        ],
        "specifications" => [
            "Brand" => "NovaHome",
            "Category" => "Kitchen appliance",
            "Control" => "Digital",
            "Colour" => "Black",
            "Warranty" => "1 year"
        ]
    ],
    10 => [
        "id" => 10,
        "name" => "Wireless Headphones",
        "category" => "Accessories",
        "price" => 6200,
        "old_price" => 8000,
        "rating" => 4.5,
        "reviews" => 76,
        "image" => "../images/novamart/headphones.webp",
        "emoji" => "🎧",
        "badge" => "Popular",
        "stock" => 14,
        "brand" => "NovaSound",
        "description" => "Enjoy music, videos and online meetings with comfortable over-ear wireless headphones for everyday use.",
        "features" => [
            "Wireless connectivity",
            "Over-ear design",
            "Built-in microphone",
            "Rechargeable battery",
            "Comfortable ear cushions"
        ],
        "specifications" => [
            "Brand" => "NovaSound",
            "Type" => "Over-ear headphones",
            "Connectivity" => "Bluetooth",
            "Colour" => "Black",
            "Warranty" => "6 months"
        ]
    ],
    11 => [
        "id" => 11,
        "name" => "Classic Wristwatch",
        "category" => "Fashion",
        "price" => 4200,
        "old_price" => 5200,
        "rating" => 4.3,
        "reviews" => 32,
        "image" => "../images/novamart/watch.webp",
        "emoji" => "⌚",
        "badge" => "",
        "stock" => 16,
        "brand" => "NovaStyle",
        "description" => "A classic wristwatch with a versatile design that complements everyday and smart-casual outfits.",
        "features" => [
            "Classic dial design",
            "Comfortable strap",
            "Easy-to-read display",
            "Suitable for daily wear",
            "Gift-friendly presentation"
        ],
        "specifications" => [
            "Brand" => "NovaStyle",
            "Type" => "Wristwatch",
            "Style" => "Classic",
            "Colour" => "Black",
            "Warranty" => "6 months"
        ]
    ],
    12 => [
        "id" => 12,
        "name" => "Portable Power Bank",
        "category" => "Accessories",
        "price" => 3500,
        "old_price" => 4500,
        "rating" => 4.5,
        "reviews" => 67,
        "image" => "../images/novamart/power-bank.png",
        "emoji" => "🔋",
        "badge" => "Deal",
        "stock" => 25,
        "brand" => "NovaTech",
        "description" => "Keep your compatible devices charged while travelling, studying or working away from a power outlet.",
        "features" => [
            "Portable design",
            "USB charging output",
            "LED battery indicators",
            "Easy to carry",
            "Suitable for compatible devices"
        ],
        "specifications" => [
            "Brand" => "NovaTech",
            "Type" => "Power bank",
            "Capacity" => "10,000 mAh",
            "Colour" => "Black",
            "Warranty" => "6 months"
        ]
    ],
    13 => [
        "id" => 13,
        "name" => "Desk Lamp",
        "category" => "Home Appliances",
        "price" => 2400,
        "old_price" => 3000,
        "rating" => 4.2,
        "reviews" => 28,
        "image" => "../images/novamart/desk_lamp.png",
        "emoji" => "💡",
        "badge" => "",
        "stock" => 17,
        "brand" => "NovaHome",
        "description" => "A practical desk lamp for reading, studying and working at home or in the office.",
        "features" => [
            "Compact desk-friendly design",
            "Adjustable lamp direction",
            "Suitable for study desks",
            "Simple controls",
            "Modern appearance"
        ],
        "specifications" => [
            "Brand" => "NovaHome",
            "Category" => "Lighting",
            "Usage" => "Study and office",
            "Colour" => "White",
            "Warranty" => "6 months"
        ]
    ],
    14 => [
        "id" => 14,
        "name" => "Running Shoes",
        "category" => "Fashion",
        "price" => 5900,
        "old_price" => 7500,
        "rating" => 4.4,
        "reviews" => 47,
        "image" => "../images/novamart/running_shoes.png",
        "emoji" => "👟",
        "badge" => "Sale",
        "stock" => 13,
        "brand" => "NovaStyle",
        "description" => "Lightweight everyday sports shoes suitable for walking, casual activities and light exercise.",
        "features" => [
            "Lightweight construction",
            "Comfortable inner lining",
            "Flexible sole",
            "Casual sports design",
            "Suitable for everyday use"
        ],
        "specifications" => [
            "Brand" => "NovaStyle",
            "Category" => "Footwear",
            "Style" => "Sports",
            "Colour" => "Black",
            "Sizes" => "Multiple sizes"
        ]
    ],
    15 => [
        "id" => 15,
        "name" => "Skincare Essentials Set",
        "category" => "Beauty",
        "price" => 3800,
        "old_price" => 4800,
        "rating" => 4.3,
        "reviews" => 35,
        "image" => "../images/novamart/skincare.png",
        "emoji" => "🧴",
        "badge" => "Special Offer",
        "stock" => 10,
        "brand" => "NovaCare",
        "description" => "A convenient skincare gift set for everyday personal-care routines. Check individual product labels for ingredients and usage directions.",
        "features" => [
            "Everyday personal-care essentials",
            "Convenient set packaging",
            "Suitable as a gift",
            "Easy-to-store packaging",
            "Check labels before use"
        ],
        "specifications" => [
            "Brand" => "NovaCare",
            "Category" => "Personal care",
            "Package" => "Gift set",
            "Usage" => "Follow product labels",
            "Warranty" => "Not applicable"
        ]
    ],
    16 => [
        "id" => 16,
        "name" => "Educational Building Blocks",
        "category" => "Toys & Games",
        "price" => 2900,
        "old_price" => 3600,
        "rating" => 4.7,
        "reviews" => 51,
        "image" => "../images/novamart/building_blocks.png",
        "emoji" => "🧩",
        "badge" => "Popular",
        "stock" => 19,
        "brand" => "NovaKids",
        "description" => "A colourful building-block set designed for creative play and construction activities. Check the package for the recommended age range.",
        "features" => [
            "Colourful building pieces",
            "Encourages creative play",
            "Multiple building possibilities",
            "Convenient storage",
            "Age guidance on packaging"
        ],
        "specifications" => [
            "Brand" => "NovaKids",
            "Category" => "Toy",
            "Material" => "Plastic",
            "Usage" => "Creative play",
            "Age range" => "Check packaging"
        ]
    ]
];

/*
|--------------------------------------------------------------------------
| Handle Add to Cart
|--------------------------------------------------------------------------
*/

$notice = "";
$noticeType = "success";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_cart"])) {
    $postedId = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);

    $postedId = $postedId ?: 0;
    $quantity = $quantity ?: 1;
    $quantity = max(1, min(10, $quantity));

    if (isset($products[$postedId])) {
        $product = $products[$postedId];
        $cartKey = (string)$postedId;

        if (isset($_SESSION["novamart_cart"][$cartKey])) {
            $_SESSION["novamart_cart"][$cartKey]["quantity"] += $quantity;
        } else {
            $_SESSION["novamart_cart"][$cartKey] = [
                "id" => $product["id"],
                "name" => $product["name"],
                "price" => $product["price"],
                "image" => $product["image"],
                "emoji" => $product["emoji"],
                "quantity" => $quantity
            ];
        }

        $notice = $product["name"] . " added to your cart!";
    } else {
        $notice = "The selected product could not be found.";
        $noticeType = "danger";
    }
}

/*
|--------------------------------------------------------------------------
| Select current product
|--------------------------------------------------------------------------
*/

$productId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$productId = $productId ?: 1;

if (!isset($products[$productId])) {
    http_response_code(404);
    $productId = 1;
    $notFound = true;
} else {
    $notFound = false;
}

$product = $products[$productId];

$discount = 0;
if ($product["old_price"] > $product["price"]) {
    $discount = round(
        (($product["old_price"] - $product["price"]) / $product["old_price"]) * 100
    );
}

$cartCount = 0;
foreach ($_SESSION["novamart_cart"] as $cartItem) {
    $cartCount += (int)($cartItem["quantity"] ?? 0);
}

$relatedProducts = [];
foreach ($products as $related) {
    if ($related["id"] !== $product["id"] &&
        ($related["category"] === $product["category"] || count($relatedProducts) < 4)) {
        $relatedProducts[] = $related;
    }

    if (count($relatedProducts) >= 4) {
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($product["name"]) ?> | NovaMart</title>

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
            --success: #198754;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fff;
            color: #263248;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .top-strip {
            background: var(--navy);
            color: #e7ecf7;
            font-size: 12px;
            padding: 9px 0;
        }

        .main-header {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 17px 0;
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

        .brand-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            color: white;
            background: var(--blue);
            border-radius: 13px;
            font-size: 22px;
        }

        .header-search {
            display: flex;
            width: 100%;
            max-width: 530px;
            border: 1px solid #dfe5ef;
            border-radius: 10px;
            overflow: hidden;
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
            border: none;
            padding: 0 18px;
            color: #fff;
            background: var(--blue);
            font-weight: 700;
        }

        .header-action {
            color: var(--navy);
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
            background: var(--blue);
            color: white;
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

        .main-nav a:hover,
        .main-nav a.active {
            color: var(--blue);
        }

        .breadcrumb-area {
            background: var(--light-bg);
            padding: 18px 0;
            font-size: 13px;
        }

        .breadcrumb-area a {
            color: var(--muted);
        }

        .breadcrumb-area .current {
            color: var(--navy);
            font-weight: 700;
        }

        .product-section {
            padding: 38px 0 65px;
        }

        .product-gallery {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 420px;
            padding: 28px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: linear-gradient(145deg, #f7f9fe, #eef2fa);
            overflow: hidden;
        }

        .product-gallery img {
            display: block;
            width: 100%;
            max-height: 390px;
            object-fit: contain;
            transition: transform .3s ease;
        }

        .product-gallery:hover img {
            transform: scale(1.035);
        }

        .product-emoji {
            display: none;
            font-size: 130px;
            line-height: 1;
        }

        .discount-badge {
            position: absolute;
            top: 18px;
            left: 18px;
            padding: 8px 12px;
            border-radius: 7px;
            background: #ffe6e8;
            color: #cf3044;
            font-size: 12px;
            font-weight: 800;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--success);
            background: #e8f7ef;
            padding: 7px 11px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 800;
        }

        .product-category {
            display: inline-block;
            margin-bottom: 10px;
            color: var(--blue);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .product-title {
            color: var(--navy);
            font-size: clamp(27px, 3vw, 37px);
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -.8px;
        }

        .rating-line {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin: 17px 0;
            font-size: 13px;
        }

        .stars {
            color: #f4a623;
            letter-spacing: 2px;
            font-size: 17px;
        }

        .rating-number {
            color: var(--navy);
            font-weight: 800;
        }

        .muted {
            color: var(--muted);
        }

        .price-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin: 22px 0 6px;
        }

        .current-price {
            color: var(--navy);
            font-size: 32px;
            font-weight: 800;
        }

        .old-price {
            color: #9aa3b3;
            font-size: 17px;
            text-decoration: line-through;
        }

        .save-label {
            color: #16834a;
            background: #e8f7ef;
            padding: 5px 8px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 800;
        }

        .tax-note {
            color: var(--muted);
            font-size: 12px;
        }

        .product-description {
            margin: 23px 0;
            color: #667187;
            font-size: 15px;
            line-height: 1.9;
        }

        .feature-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 13px 18px;
            margin: 20px 0 25px;
            padding: 0;
            list-style: none;
        }

        .feature-list li {
            display: flex;
            gap: 9px;
            align-items: flex-start;
            color: #46536a;
            font-size: 13px;
        }

        .feature-check {
            display: inline-grid;
            place-items: center;
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #e7f6ee;
            color: #16834a;
            font-size: 12px;
            font-weight: 900;
        }

        .purchase-panel {
            margin-top: 25px;
            padding: 22px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fbfcff;
        }

        .quantity-control {
            display: inline-flex;
            align-items: center;
            border: 1px solid #dce2ed;
            border-radius: 9px;
            overflow: hidden;
            background: white;
        }

        .quantity-control button {
            width: 38px;
            height: 43px;
            border: none;
            background: white;
            color: var(--navy);
            font-size: 20px;
        }

        .quantity-control button:hover {
            background: #eef2ff;
        }

        .quantity-control input {
            width: 44px;
            height: 43px;
            border: none;
            outline: none;
            text-align: center;
            font-weight: 800;
            background: white;
        }

        .btn-add-cart {
            min-height: 46px;
            padding: 12px 23px;
            border: none;
            border-radius: 9px;
            color: white;
            background: var(--blue);
            font-weight: 800;
            transition: .2s;
        }

        .btn-add-cart:hover {
            background: var(--blue-hover);
            color: white;
            transform: translateY(-1px);
        }

        .btn-buy {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-height: 46px;
            padding: 12px 23px;
            border: 1px solid #dce2ed;
            border-radius: 9px;
            color: var(--navy);
            background: white;
            font-weight: 800;
        }

        .btn-buy:hover {
            border-color: var(--blue);
            color: var(--blue);
        }

        .delivery-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 24px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
        }

        .delivery-item {
            display: flex;
            align-items: flex-start;
            gap: 11px;
        }

        .delivery-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eef2ff;
            font-size: 18px;
        }

        .delivery-item strong {
            display: block;
            color: var(--navy);
            font-size: 12px;
        }

        .delivery-item small {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.5;
        }

        .detail-tabs {
            margin-top: 55px;
        }

        .section-heading {
            margin-bottom: 23px;
            color: var(--navy);
            font-size: 24px;
            font-weight: 800;
        }

        .spec-card {
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: white;
        }

        .spec-table {
            width: 100%;
            border-collapse: collapse;
        }

        .spec-table tr:not(:last-child) {
            border-bottom: 1px solid var(--border);
        }

        .spec-table td {
            padding: 14px 10px;
            font-size: 14px;
        }

        .spec-table td:first-child {
            width: 38%;
            color: var(--muted);
        }

        .spec-table td:last-child {
            color: var(--navy);
            font-weight: 700;
        }

        .related-section {
            padding: 55px 0;
            background: var(--light-bg);
        }

        .related-card {
            height: 100%;
            padding: 15px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: white;
            transition: .2s;
        }

        .related-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(16, 29, 58, .08);
        }

        .related-image {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 165px;
            margin-bottom: 15px;
            border-radius: 10px;
            background: #f3f6fc;
            overflow: hidden;
        }

        .related-image img {
            width: 100%;
            height: 100%;
            padding: 10px;
            object-fit: contain;
        }

        .related-emoji {
            font-size: 60px;
        }

        .related-name {
            min-height: 42px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
            line-height: 1.5;
        }

        .related-price {
            margin-top: 8px;
            color: var(--navy);
            font-weight: 800;
        }

        .related-old-price {
            margin-left: 5px;
            color: #9aa3b3;
            font-size: 12px;
            text-decoration: line-through;
        }

        .view-product {
            display: block;
            margin-top: 13px;
            padding: 9px;
            border: 1px solid #dce4ff;
            border-radius: 8px;
            color: var(--blue);
            text-align: center;
            font-size: 12px;
            font-weight: 800;
        }

        .view-product:hover {
            background: var(--blue);
            color: white;
        }

        .benefit-strip {
            padding: 25px 0;
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
        .site-footer a {
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

            .product-section {
                padding-top: 22px;
            }

            .product-gallery {
                min-height: 300px;
                margin-bottom: 28px;
            }

            .product-gallery img {
                max-height: 270px;
            }

            .feature-list {
                grid-template-columns: 1fr;
            }

            .delivery-info {
                grid-template-columns: 1fr;
            }

            .purchase-buttons {
                width: 100%;
            }

            .purchase-buttons .btn-add-cart,
            .purchase-buttons .btn-buy {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<!-- Top announcement -->
<div class="top-strip">
    <div class="container d-flex justify-content-between gap-2 flex-wrap">
        <span>Welcome to NovaMart — your everyday shopping destination</span>
        <span>Secure shopping · Customer support</span>
    </div>
</div>

<!-- Main header -->
<header class="main-header">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-6 col-lg-3">
                <a href="index.php" class="brand">
                    <img src="../images/novamart/novamart_logo.png" width="40px" height="40px">
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
        <a href="shop.php" class="active">Shop All</a>
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
        <a href="shop.php?category=<?= urlencode($product["category"]) ?>">
            <?= e($product["category"]) ?>
        </a>
        <span class="mx-2">›</span>
        <span class="current"><?= e($product["name"]) ?></span>
    </div>
</div>

<!-- Product details -->
<main>
    <section class="product-section">
        <div class="container">

            <?php if ($notFound): ?>
                <div class="alert alert-warning">
                    The requested product ID was not found in this demonstration catalogue.
                    A sample product is shown below.
                    <a href="shop.php" class="alert-link">Return to the shop</a>.
                </div>
            <?php endif; ?>

            <?php if ($notice !== ""): ?>
                <div class="alert alert-<?= e($noticeType) ?> alert-dismissible fade show" role="alert">
                    <?= e($notice) ?>
                    <a href="cart.php" class="alert-link ms-2">View cart</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4 g-lg-5">

                <!-- Product image -->
                <div class="col-lg-6">
                    <div class="product-gallery">
                        <?php if ($discount > 0): ?>
                            <span class="discount-badge">
                                <?= (int)$discount ?>% OFF
                            </span>
                        <?php endif; ?>

                        <img
                            src="<?= e($product["image"]) ?>"
                            alt="<?= e($product["name"]) ?>"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                        >

                        <div class="product-emoji">
                            <?= e($product["emoji"]) ?>
                        </div>
                    </div>

                    <p class="muted text-center mt-3 mb-0" style="font-size:12px;">
                        Product images are for demonstration purposes.
                    </p>
                </div>

                <!-- Product information -->
                <div class="col-lg-6">
                    <div class="product-category">
                        <?= e($product["category"]) ?>
                    </div>

                    <h1 class="product-title">
                        <?= e($product["name"]) ?>
                    </h1>

                    <div class="rating-line">
                        <span class="stars">★★★★★</span>
                        <span class="rating-number">
                            <?= number_format($product["rating"], 1) ?>/5
                        </span>
                        <span class="muted">
                            (<?= (int)$product["reviews"] ?> reviews)
                        </span>
                        <span class="muted">|</span>
                        <span class="muted">
                            Brand: <?= e($product["brand"]) ?>
                        </span>
                    </div>

                    <div class="price-row">
                        <span class="current-price">
                            <?= money($product["price"]) ?>
                        </span>

                        <?php if ($product["old_price"] > $product["price"]): ?>
                            <span class="old-price">
                                <?= money($product["old_price"]) ?>
                            </span>

                            <span class="save-label">
                                Save <?= (int)$discount ?>%
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="tax-note">
                        Demonstration price in Sri Lankan Rupees (LKR).
                    </div>

                    <p class="product-description">
                        <?= e($product["description"]) ?>
                    </p>

                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <?php if ($product["stock"] > 0): ?>
                            <span class="stock-badge">● In Stock</span>
                            <span class="muted" style="font-size:12px;">
                                <?= (int)$product["stock"] ?> units available in demo inventory
                            </span>
                        <?php else: ?>
                            <span class="badge text-bg-danger">Out of Stock</span>
                        <?php endif; ?>
                    </div>

                    <ul class="feature-list">
                        <?php foreach ($product["features"] as $feature): ?>
                            <li>
                                <span class="feature-check">✓</span>
                                <span><?= e($feature) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Add to cart -->
                    <div class="purchase-panel">
                        <form method="POST" action="product_details.php?id=<?= (int)$product["id"] ?>">
                            <input type="hidden" name="product_id" value="<?= (int)$product["id"] ?>">

                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div>
                                    <div class="fw-bold mb-2" style="color:var(--navy);font-size:13px;">
                                        Quantity
                                    </div>

                                    <div class="quantity-control">
                                        <button type="button" id="decreaseQty" aria-label="Decrease quantity">−</button>

                                        <input
                                            type="number"
                                            id="quantity"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            max="<?= min(10, max(1, (int)$product["stock"])) ?>"
                                            aria-label="Product quantity"
                                        >

                                        <button type="button" id="increaseQty" aria-label="Increase quantity">+</button>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="muted" style="font-size:12px;">Total price</div>
                                    <div class="fw-bold" id="totalPrice"
                                         style="color:var(--navy);font-size:20px;">
                                        <?= money($product["price"]) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="purchase-buttons d-flex flex-wrap gap-2 mt-4">
                                <?php if ($product["stock"] > 0): ?>
                                    <button type="submit" name="add_to_cart" class="btn-add-cart flex-grow-1">
                                        🛒 Add to Cart
                                    </button>

                                    <a href="cart.php" class="btn-buy">
                                        View Cart
                                    </a>
                                <?php else: ?>
                                    <button type="button" class="btn-add-cart flex-grow-1" disabled>
                                        Currently Unavailable
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>

                        <div class="delivery-info">
                            <div class="delivery-item">
                                <span class="delivery-icon">🚚</span>
                                <div>
                                    <strong>Delivery Information</strong>
                                    <small>Delivery details are provided during checkout.</small>
                                </div>
                            </div>

                            <div class="delivery-item">
                                <span class="delivery-icon">↩️</span>
                                <div>
                                    <strong>Returns & Support</strong>
                                    <small>Contact customer support for assistance.</small>
                                </div>
                            </div>

                            <div class="delivery-item">
                                <span class="delivery-icon">🔒</span>
                                <div>
                                    <strong>Secure Checkout</strong>
                                    <small>Checkout is simulated for this portfolio demo.</small>
                                </div>
                            </div>

                            <div class="delivery-item">
                                <span class="delivery-icon">✓</span>
                                <div>
                                    <strong>Product Quality</strong>
                                    <small>Product specifications are illustrative.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specifications -->
            <div class="detail-tabs">
                <h2 class="section-heading">Product Information</h2>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="spec-card h-100">
                            <h3 class="h5 fw-bold mb-3" style="color:var(--navy);">
                                Product Specifications
                            </h3>

                            <table class="spec-table">
                                <?php foreach ($product["specifications"] as $label => $value): ?>
                                    <tr>
                                        <td><?= e($label) ?></td>
                                        <td><?= e($value) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="spec-card h-100">
                            <h3 class="h5 fw-bold mb-3" style="color:var(--navy);">
                                Why Shop with NovaMart?
                            </h3>

                            <p class="muted" style="font-size:14px;line-height:1.8;">
                                NovaMart brings different product categories together in one convenient online shopping experience.
                            </p>

                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <span class="text-success fw-bold me-2">✓</span>
                                    Easy product discovery
                                </li>
                                <li class="mb-3">
                                    <span class="text-success fw-bold me-2">✓</span>
                                    Clear product pricing
                                </li>
                                <li class="mb-3">
                                    <span class="text-success fw-bold me-2">✓</span>
                                    Convenient shopping cart
                                </li>
                                <li>
                                    <span class="text-success fw-bold me-2">✓</span>
                                    Multiple product categories
                                </li>
                            </ul>

                            <a href="shop.php" class="btn-add-cart d-inline-block mt-4">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Related products -->
    <section class="related-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <div class="product-category mb-2">Discover More</div>
                    <h2 class="section-heading mb-0">You May Also Like</h2>
                </div>

                <a href="shop.php" class="fw-bold" style="color:var(--blue);font-size:13px;">
                    View All Products →
                </a>
            </div>

            <div class="row g-3">
                <?php foreach ($relatedProducts as $related): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="related-card">
                            <a href="product_details.php?id=<?= (int)$related["id"] ?>">
                                <div class="related-image">
                                    <img
                                        src="<?= e($related["image"]) ?>"
                                        alt="<?= e($related["name"]) ?>"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                    >
                                    <span class="related-emoji" style="display:none;">
                                        <?= e($related["emoji"]) ?>
                                    </span>
                                </div>
                            </a>

                            <div class="muted text-uppercase mb-1"
                                 style="font-size:10px;letter-spacing:.7px;">
                                <?= e($related["category"]) ?>
                            </div>

                            <a href="product_details.php?id=<?= (int)$related["id"] ?>">
                                <div class="related-name">
                                    <?= e($related["name"]) ?>
                                </div>
                            </a>

                            <div class="related-price">
                                <?= money($related["price"]) ?>

                                <?php if ($related["old_price"] > $related["price"]): ?>
                                    <span class="related-old-price">
                                        <?= money($related["old_price"]) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <a href="product_details.php?id=<?= (int)$related["id"] ?>"
                               class="view-product">
                                View Product
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Benefits strip -->
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
                            <small>Explore multiple categories</small>
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
</main>

<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a href="index.php" class="brand text-white mb-3">
                    <img src="../images/novamart/novamart_logo.png" width="40px" height="40px">
                    <span>NovaMart</span>
                </a>

                <p>
                    Your everyday shopping destination. Discover electronics,
                    computing products, fashion and more.
                </p>
            </div>

            <div class="col-6 col-lg-2">
                <h5>Explore</h5>
                <a class="d-block" href="index.php">Home</a>
                <a class="d-block" href="shop.php">Shop All</a>
                <a class="d-block" href="shop.php?deals=1">Special Deals</a>
            </div>

            <div class="col-6 col-lg-3">
                <h5>Customer Service</h5>
                <a class="d-block" href="cart.php">Shopping Cart</a>
                <a class="d-block" href="login.php">My Account</a>
                <a class="d-block" href="shop.php">Browse Products</a>
            </div>

            <div class="col-lg-3">
                <h5>About NovaMart</h5>
                <p>
                    A demonstration e-commerce project created for the Codvanta portfolio.
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
    const quantityInput = document.getElementById("quantity");
    const decreaseButton = document.getElementById("decreaseQty");
    const increaseButton = document.getElementById("increaseQty");
    const totalPriceElement = document.getElementById("totalPrice");

    const unitPrice = <?= json_encode((float)$product["price"]) ?>;
    const maxQuantity = <?= min(10, max(1, (int)$product["stock"])) ?>;

    function formatMoney(amount) {
        return "Rs. " + amount.toLocaleString("en-LK", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function updateTotal() {
        let quantity = parseInt(quantityInput.value, 10);

        if (isNaN(quantity)) {
            quantity = 1;
        }

        quantity = Math.max(1, Math.min(maxQuantity, quantity));
        quantityInput.value = quantity;

        totalPriceElement.textContent = formatMoney(unitPrice * quantity);
    }

    decreaseButton.addEventListener("click", function () {
        quantityInput.value = Math.max(1, parseInt(quantityInput.value || 1, 10) - 1);
        updateTotal();
    });

    increaseButton.addEventListener("click", function () {
        quantityInput.value = Math.min(
            maxQuantity,
            parseInt(quantityInput.value || 1, 10) + 1
        );
        updateTotal();
    });

    quantityInput.addEventListener("input", updateTotal);
    quantityInput.addEventListener("change", updateTotal);

    updateTotal();
</script>

</body>
</html>
