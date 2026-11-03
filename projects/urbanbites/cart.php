
<?php
// UrbanBites
// Restaurant Ordering & Management System
// Shopping Cart Page
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Cart | UrbanBites</title>


    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="../../assets/bootstrap/bootstrap.min.css">


    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>

        /* =========================================
           GLOBAL
        ========================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Trebuchet MS", Arial, sans-serif;
            background: #fafafa;
            color: #222;
        }


        /* =========================================
           NAVBAR
        ========================================= */

        .navbar {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.07);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: 900;
            color: #e63946 !important;
            letter-spacing: -1px;
        }

        .brand-dot {
            color: #f4a261;
        }

        .navbar-nav .nav-link {
            color: #333;
            font-size: 15px;
            font-weight: 600;
            margin: 0 10px;
            transition: 0.3s;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #e63946;
        }

        .nav-order-btn {
            background: #e63946;
            color: white !important;
            padding: 10px 20px !important;
            border-radius: 30px;
        }

        .nav-order-btn:hover {
            background: #c92f3c;
        }


        /* =========================================
           CART ICON
        ========================================= */

        .cart-btn {
            position: relative;
            margin-left: 15px;
            font-size: 21px;
            color: #e63946;
            text-decoration: none;
        }

        .cart-count {
            position: absolute;
            top: -9px;
            right: -10px;
            background: #e63946;
            color: white;
            width: 19px;
            height: 19px;
            border-radius: 50%;
            font-size: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }


        /* =========================================
           PAGE HERO
        ========================================= */

        .cart-hero {
            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.70),
                    rgba(0, 0, 0, 0.70)
                ),
                url("assets/images/menu-banner.jpg");

            background-size: cover;
            background-position: center;

            min-height: 280px;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;

            color: white;
        }

        .cart-hero-content {
            max-width: 700px;
        }

        .cart-label {
            color: #f4a261;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 13px;
            font-weight: 800;
        }

        .cart-hero h1 {
            font-size: clamp(40px, 6vw, 58px);
            font-weight: 900;
            margin: 12px 0;
        }

        .cart-hero p {
            color: #ddd;
            font-size: 16px;
        }


        /* =========================================
           CART SECTION
        ========================================= */

        .cart-section {
            padding: 70px 0;
        }


        /* =========================================
           SECTION TITLES
        ========================================= */

        .section-title {
            font-size: 30px;
            font-weight: 900;
            margin-bottom: 5px;
        }

        .section-subtitle {
            color: #888;
            font-size: 14px;
            margin-bottom: 30px;
        }


        /* =========================================
           CART BOX
        ========================================= */

        .cart-box {
            background: white;
            border-radius: 20px;
            border: 1px solid #eee;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }


        /* =========================================
           CART HEADER
        ========================================= */

        .cart-box-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-box-header h5 {
            margin: 0;
            font-weight: 900;
        }

        .item-count-label {
            color: #888;
            font-size: 13px;
        }


        /* =========================================
           CART ITEM
        ========================================= */

        .cart-item {
            padding: 22px 25px;
            border-bottom: 1px solid #eee;

            display: flex;
            align-items: center;
            gap: 18px;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            width: 100px;
            height: 100px;
            border-radius: 15px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .cart-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        .cart-item-info {
            flex: 1;
        }

        .cart-item-category {
            color: #e63946;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
        }

        .cart-item-name {
            font-size: 18px;
            font-weight: 900;
            margin: 4px 0;
        }

        .cart-item-price {
            color: #777;
            font-size: 13px;
        }


        /* =========================================
           QUANTITY
        ========================================= */

        .quantity-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;

            border: 1px solid #ddd;
            background: white;

            border-radius: 50%;

            display: flex;
            justify-content: center;
            align-items: center;

            transition: 0.3s;
        }

        .quantity-btn:hover {
            background: #e63946;
            border-color: #e63946;
            color: white;
        }

        .quantity-value {
            min-width: 25px;
            text-align: center;
            font-weight: 900;
        }


        /* =========================================
           ITEM TOTAL
        ========================================= */

        .item-total {
            width: 120px;
            text-align: right;
            font-size: 17px;
            font-weight: 900;
            color: #e63946;
        }


        /* =========================================
           REMOVE BUTTON
        ========================================= */

        .remove-btn {
            border: none;
            background: transparent;
            color: #aaa;
            font-size: 18px;
            transition: 0.3s;
        }

        .remove-btn:hover {
            color: #e63946;
        }


        /* =========================================
           EMPTY CART
        ========================================= */

        .empty-cart {
            padding: 90px 30px;
            text-align: center;
        }

        .empty-cart-icon {
            width: 90px;
            height: 90px;

            border-radius: 50%;
            background: #fff0f1;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto 25px;

            color: #e63946;
            font-size: 40px;
        }

        .empty-cart h3 {
            font-weight: 900;
            margin-bottom: 10px;
        }

        .empty-cart p {
            color: #888;
            margin-bottom: 25px;
        }

        .browse-btn {
            display: inline-block;
            background: #e63946;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 800;
            transition: 0.3s;
        }

        .browse-btn:hover {
            background: #c92f3c;
            color: white;
        }


        /* =========================================
           ORDER SUMMARY
        ========================================= */

        .summary-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #eee;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            padding: 25px;

            position: sticky;
            top: 100px;
        }

        .summary-title {
            font-size: 21px;
            font-weight: 900;
            margin-bottom: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .summary-row strong {
            color: #222;
        }

        .summary-divider {
            border-top: 1px solid #eee;
            margin: 20px 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .summary-total span:first-child {
            font-size: 17px;
            font-weight: 900;
        }

        .summary-total span:last-child {
            color: #e63946;
            font-size: 25px;
            font-weight: 900;
        }

        .checkout-btn {
            width: 100%;
            border: none;
            background: #e63946;
            color: white;
            padding: 14px;
            border-radius: 30px;
            font-weight: 900;
            transition: 0.3s;
        }

        .checkout-btn:hover {
            background: #c92f3c;
            transform: translateY(-2px);
        }

        .continue-btn {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #555;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
        }

        .continue-btn:hover {
            color: #e63946;
        }


        /* =========================================
           PROMO BOX
        ========================================= */

        .promo-box {
            margin-top: 20px;
            padding: 20px;
            border-radius: 16px;
            background: #fff7ef;
            border: 1px solid #f9dfc7;
        }

        .promo-box h6 {
            font-weight: 900;
            margin-bottom: 6px;
        }

        .promo-box p {
            color: #888;
            font-size: 12px;
            margin-bottom: 12px;
        }

        .promo-input {
            display: flex;
            gap: 7px;
        }

        .promo-input input {
            min-width: 0;
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 9px 11px;
            outline: none;
            font-size: 12px;
        }

        .promo-input button {
            border: none;
            background: #222;
            color: white;
            border-radius: 8px;
            padding: 0 15px;
            font-size: 12px;
            font-weight: 800;
        }


        /* =========================================
           BENEFITS
        ========================================= */

        .benefit-box {
            margin-top: 20px;
            padding: 20px;
            border-radius: 16px;
            background: #f8f8f8;
        }

        .benefit {
            display: flex;
            gap: 12px;
            margin-bottom: 15px;
        }

        .benefit:last-child {
            margin-bottom: 0;
        }

        .benefit-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #fff0f1;
            color: #e63946;

            display: flex;
            justify-content: center;
            align-items: center;

            flex-shrink: 0;
        }

        .benefit h6 {
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .benefit p {
            color: #888;
            font-size: 11px;
            margin: 0;
        }


        /* =========================================
           FOOTER
        ========================================= */

        footer {
            background: #171717;
            color: #ddd;
            padding: 60px 0 25px;
        }

        .footer-brand {
            font-size: 27px;
            font-weight: 900;
            color: white;
            margin-bottom: 15px;
        }

        .footer-brand span {
            color: #e63946;
        }

        .footer-description {
            color: #999;
            line-height: 1.7;
            max-width: 350px;
        }

        .footer-title {
            color: white;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            text-decoration: none;
            color: #999;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: #e63946;
        }

        .social-links {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #292929;
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            text-decoration: none;
            transition: 0.3s;
        }

        .social-links a:hover {
            background: #e63946;
        }

        .footer-bottom {
            border-top: 1px solid #303030;
            margin-top: 40px;
            padding-top: 20px;
            color: #777;
            font-size: 13px;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 991px) {

            .navbar-nav {
                padding-top: 15px;
            }

            .nav-order-btn {
                display: inline-block;
                margin: 10px 0;
                width: fit-content;
            }

            .cart-btn {
                display: inline-block;
                margin-left: 0;
                margin-top: 10px;
            }

            .summary-card {
                position: static;
            }

        }


        @media (max-width: 767px) {

            .cart-section {
                padding: 50px 0;
            }

            .cart-item {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .cart-item-image {
                width: 80px;
                height: 80px;
            }

            .cart-item-info {
                min-width: calc(100% - 110px);
            }

            .quantity-wrapper {
                margin-left: 98px;
            }

            .item-total {
                width: auto;
                margin-left: auto;
            }

        }

    </style>

</head>


<body>


<!-- ==================================================
     NAVBAR
================================================== -->

<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a class="navbar-brand"
           href="index.php">
            <img src="../../assets/img/main_logo.png" width="75px" height="60px" style="margin-top: -30px;">
            Urban<span class="brand-dot">Bites</span>

        </a>


        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#urbanNavbar">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div class="collapse navbar-collapse"
             id="urbanNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link"
                       href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="menu.php">
                        Menu
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="index.php#about">
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="index.php#reviews">
                        Reviews
                    </a>
                </li>

                <li class="nav-item">

                    <a class="nav-link nav-order-btn"
                       href="menu.php">

                        Order Food

                    </a>

                </li>

                <li class="nav-item">

                    <a href="cart.php"
                       class="cart-btn">

                        <i class="bi bi-bag-fill"></i>

                        <span class="cart-count"
                              id="cartCount">
                            0
                        </span>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- ==================================================
     HERO
================================================== -->

<section class="cart-hero">

    <div class="cart-hero-content">

        <span class="cart-label">
            UrbanBites
        </span>

        <h1>
            Your Cart
        </h1>

        <p>
            Review your selected items before placing your order.
        </p>

    </div>

</section>



<!-- ==================================================
     CART SECTION
================================================== -->

<section class="cart-section">

    <div class="container">

        <div class="row g-4">


            <!-- =========================================
                 CART ITEMS
            ========================================== -->

            <div class="col-lg-8">

                <h2 class="section-title">
                    Shopping Cart
                </h2>

                <p class="section-subtitle">
                    Check your items and update quantities before checkout.
                </p>


                <div class="cart-box"
                     id="cartBox">

                    <!-- Javascript inserts items here -->

                </div>

            </div>



            <!-- =========================================
                 ORDER SUMMARY
            ========================================== -->

            <div class="col-lg-4">

                <div class="summary-card">

                    <h4 class="summary-title">
                        Order Summary
                    </h4>


                    <div class="summary-row">

                        <span>
                            Items
                        </span>

                        <strong id="summaryItems">
                            0
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong id="summarySubtotal">
                            Rs. 0
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Delivery Fee
                        </span>

                        <strong id="deliveryFee">
                            Rs. 300
                        </strong>

                    </div>


                    <div class="summary-divider">
                    </div>


                    <div class="summary-total">

                        <span>
                            Total
                        </span>

                        <span id="summaryTotal">
                            Rs. 0
                        </span>

                    </div>


                    <button class="checkout-btn"
                            id="checkoutButton"
                            onclick="goToCheckout()">

                        Proceed to Checkout

                        <i class="bi bi-arrow-right ms-1"></i>

                    </button>


                    <a href="menu.php"
                       class="continue-btn">

                        <i class="bi bi-arrow-left me-1"></i>

                        Continue Shopping

                    </a>


                    <!-- PROMO -->

                    <div class="promo-box">

                        <h6>
                            Have a promo code?
                        </h6>

                        <p>
                            Enter your promotional code to receive a discount.
                        </p>


                        <div class="promo-input">

                            <input type="text"
                                   id="promoCode"
                                   placeholder="Promo code">


                            <button onclick="applyPromo()">

                                Apply

                            </button>

                        </div>

                    </div>


                    <!-- BENEFITS -->

                    <div class="benefit-box">

                        <div class="benefit">

                            <div class="benefit-icon">

                                <i class="bi bi-shield-check"></i>

                            </div>

                            <div>

                                <h6>
                                    Secure Ordering
                                </h6>

                                <p>
                                    Your order information is protected.
                                </p>

                            </div>

                        </div>


                        <div class="benefit">

                            <div class="benefit-icon">

                                <i class="bi bi-clock"></i>

                            </div>

                            <div>

                                <h6>
                                    Fast Preparation
                                </h6>

                                <p>
                                    We start preparing your order quickly.
                                </p>

                            </div>

                        </div>


                        <div class="benefit">

                            <div class="benefit-icon">

                                <i class="bi bi-truck"></i>

                            </div>

                            <div>

                                <h6>
                                    Reliable Delivery
                                </h6>

                                <p>
                                    Fresh food delivered to your location.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ==================================================
     FOOTER
================================================== -->

<footer>

    <div class="container">

        <div class="row g-5">


            <div class="col-lg-5">

                <div class="footer-brand">
                    <img src="../../assets/img/main_logo.png" width="75px" height="60px" style="margin-top: -30px;">
                    Urban<span>Bites</span>

                </div>

                <p class="footer-description">

                    Delicious food, simple ordering and reliable
                    service. Experience the UrbanBites difference.

                </p>


                <div class="social-links">

                    <a href="#">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="#">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="#">
                        <i class="bi bi-whatsapp"></i>
                    </a>

                    <a href="#">
                        <i class="bi bi-tiktok"></i>
                    </a>

                </div>

            </div>


            <div class="col-6 col-lg-2">

                <h6 class="footer-title">
                    Quick Links
                </h6>

                <ul class="footer-links">

                    <li>
                        <a href="index.php">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="menu.php">
                            Menu
                        </a>
                    </li>

                    <li>
                        <a href="cart.php">
                            Cart
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Contact
                        </a>
                    </li>

                </ul>

            </div>


            <div class="col-6 col-lg-2">

                <h6 class="footer-title">
                    Services
                </h6>

                <ul class="footer-links">

                    <li>
                        <a href="#">
                            Online Ordering
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Delivery
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Takeaway
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Catering
                        </a>
                    </li>

                </ul>

            </div>


            <div class="col-lg-3">

                <h6 class="footer-title">
                    Contact Us
                </h6>

                <ul class="footer-links">

                    <li>
                        <i class="bi bi-geo-alt me-2"></i>
                        Colombo, Sri Lanka
                    </li>

                    <li>
                        <i class="bi bi-telephone me-2"></i>
                        +94 77 123 4567
                    </li>

                    <li>
                        <i class="bi bi-envelope me-2"></i>
                        hello@urbanbites.com
                    </li>

                </ul>

            </div>

        </div>


        <div class="footer-bottom">

            <div class="row">

                <div class="col-md-6">

                    © 2026 UrbanBites.
                    All Rights Reserved.

                </div>

                <div class="col-md-6 text-md-end mt-2 mt-md-0">

                    Restaurant Ordering & Management System

                </div>

            </div>

        </div>

    </div>

</footer>



<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>



<script>

    /* =========================================
       CART DATA
    ========================================= */

    /*
       At the moment we use localStorage.

       Later:
       localStorage
           ↓
       PHP Session
           ↓
       MySQL
    */


    let cart = JSON.parse(
        localStorage.getItem("urbanBitesCart")
    ) || [];


    let deliveryFee = 300;

    let discount = 0;



    /* =========================================
       SAVE CART
    ========================================= */

    function saveCart() {

        localStorage.setItem(
            "urbanBitesCart",
            JSON.stringify(cart)
        );

    }



    /* =========================================
       DISPLAY CART
    ========================================= */

    function displayCart() {

        const cartBox =
            document.getElementById("cartBox");


        if (cart.length === 0) {

            cartBox.innerHTML = `

                <div class="empty-cart">

                    <div class="empty-cart-icon">

                        <i class="bi bi-bag"></i>

                    </div>

                    <h3>
                        Your cart is empty
                    </h3>

                    <p>
                        Looks like you haven't added anything
                        delicious yet.
                    </p>

                    <a href="menu.php"
                       class="browse-btn">

                        Browse Menu

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

            `;


            updateSummary();

            return;

        }


        let html = "";


        cart.forEach((item, index) => {

            const itemTotal =
                item.price * item.quantity;


            html += `

                <div class="cart-item">

                    <div class="cart-item-image">

                        <img src="${item.image}"
                             alt="${item.name}">

                    </div>


                    <div class="cart-item-info">

                        <span class="cart-item-category">

                            ${item.category || "Food"}

                        </span>


                        <h5 class="cart-item-name">

                            ${item.name}

                        </h5>


                        <span class="cart-item-price">

                            Rs. ${Number(item.price).toLocaleString()}
                            each

                        </span>

                    </div>


                    <div class="quantity-wrapper">

                        <button class="quantity-btn"
                                onclick="changeQuantity(${index}, -1)">

                            <i class="bi bi-dash"></i>

                        </button>


                        <span class="quantity-value">

                            ${item.quantity}

                        </span>


                        <button class="quantity-btn"
                                onclick="changeQuantity(${index}, 1)">

                            <i class="bi bi-plus"></i>

                        </button>

                    </div>


                    <div class="item-total">

                        Rs. ${itemTotal.toLocaleString()}

                    </div>


                    <button class="remove-btn"
                            onclick="removeItem(${index})"
                            title="Remove item">

                        <i class="bi bi-trash3"></i>

                    </button>

                </div>

            `;

        });


        cartBox.innerHTML = `

            <div class="cart-box-header">

                <h5>
                    Selected Items
                </h5>

                <span class="item-count-label">

                    ${getTotalItems()} items

                </span>

            </div>

            ${html}

        `;


        updateSummary();

    }



    /* =========================================
       CHANGE QUANTITY
    ========================================= */

    function changeQuantity(index, amount) {

        cart[index].quantity += amount;


        if (cart[index].quantity <= 0) {

            cart.splice(index, 1);

        }


        saveCart();

        displayCart();

    }



    /* =========================================
       REMOVE ITEM
    ========================================= */

    function removeItem(index) {

        cart.splice(index, 1);

        saveCart();

        displayCart();

    }



    /* =========================================
       TOTAL ITEMS
    ========================================= */

    function getTotalItems() {

        return cart.reduce(
            (total, item) =>
                total + Number(item.quantity),
            0
        );

    }



    /* =========================================
       SUBTOTAL
    ========================================= */

    function getSubtotal() {

        return cart.reduce(
            (total, item) =>
                total +
                (Number(item.price) *
                 Number(item.quantity)),
            0
        );

    }



    /* =========================================
       UPDATE SUMMARY
    ========================================= */

    function updateSummary() {

        const itemCount =
            getTotalItems();


        const subtotal =
            getSubtotal();


        document.getElementById(
            "summaryItems"
        ).textContent =
            itemCount;


        document.getElementById(
            "summarySubtotal"
        ).textContent =
            "Rs. " +
            subtotal.toLocaleString();


        document.getElementById(
            "cartCount"
        ).textContent =
            itemCount;


        let finalDeliveryFee =
            subtotal > 0
                ? deliveryFee
                : 0;


        let total =
            subtotal +
            finalDeliveryFee -
            discount;


        if (total < 0) {
            total = 0;
        }


        document.getElementById(
            "deliveryFee"
        ).textContent =
            "Rs. " +
            finalDeliveryFee.toLocaleString();


        document.getElementById(
            "summaryTotal"
        ).textContent =
            "Rs. " +
            total.toLocaleString();

    }



    /* =========================================
       PROMO CODE
    ========================================= */

    function applyPromo() {

        const promoInput =
            document.getElementById("promoCode");


        const code =
            promoInput.value
                .trim()
                .toUpperCase();


        const subtotal =
            getSubtotal();


        if (subtotal === 0) {

            alert(
                "Please add items to your cart first."
            );

            return;

        }


        if (code === "URBAN10") {

            discount =
                Math.round(subtotal * 0.10);


            alert(
                "Promo code applied! You received 10% off."
            );


        } else {

            discount = 0;


            alert(
                "Invalid promo code."
            );

        }


        updateSummary();

    }



    /* =========================================
       CHECKOUT
    ========================================= */

    function goToCheckout() {

        if (cart.length === 0) {

            alert(
                "Your cart is empty. Please add food before checkout."
            );

            return;

        }


        /*
           Next page:

           checkout.php

           The cart data will later be
           transferred to PHP session/database.
        */


        window.location.href =
            "checkout.php";

    }



    /* =========================================
       INITIAL LOAD
    ========================================= */

    displayCart();

</script>


</body>

</html>

