<?php
// UrbanBites
// Restaurant Ordering & Management System
// Menu Page
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Menu | UrbanBites</title>


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

        .cart-btn {
            position: relative;
            margin-left: 15px;
            font-size: 21px;
            color: #333;
            text-decoration: none;
        }

        .cart-btn:hover {
            color: #e63946;
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

        .menu-hero {
            min-height: 350px;

            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.70),
                    rgba(0, 0, 0, 0.70)
                ),
                url("../../assets/img/menu-banner.jpg");

            background-size: cover;
            background-position: center;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;
            color: white;
        }

        .menu-hero-content {
            max-width: 750px;
        }

        .menu-label {
            display: inline-block;
            color: #f4a261;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .menu-hero h1 {
            font-size: clamp(42px, 6vw, 65px);
            font-weight: 900;
            margin-bottom: 15px;
        }

        .menu-hero p {
            color: #eee;
            font-size: 17px;
            line-height: 1.7;
            max-width: 620px;
            margin: auto;
        }


        /* =========================================
           MENU AREA
        ========================================= */

        .menu-section {
            padding: 75px 0;
        }


        /* =========================================
           SEARCH AREA
        ========================================= */

        .menu-toolbar {
            background: white;
            border-radius: 20px;
            padding: 22px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            margin-bottom: 40px;
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }

        .search-input {
            width: 100%;
            height: 52px;
            border: 1px solid #ddd;
            border-radius: 30px;
            padding: 0 20px 0 50px;
            outline: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .search-input:focus {
            border-color: #e63946;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.08);
        }


        /* =========================================
           CATEGORY FILTERS
        ========================================= */

        .category-filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .category-btn {
            border: 1px solid #ddd;
            background: white;
            color: #555;
            padding: 9px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            transition: 0.3s;
        }

        .category-btn:hover,
        .category-btn.active {
            background: #e63946;
            color: white;
            border-color: #e63946;
        }


        /* =========================================
           MENU HEADER
        ========================================= */

        .menu-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .menu-heading h2 {
            font-size: 32px;
            font-weight: 900;
            margin: 0;
        }

        .menu-heading p {
            margin: 5px 0 0;
            color: #888;
            font-size: 14px;
        }

        .result-count {
            color: #888;
            font-size: 14px;
        }


        /* =========================================
           FOOD CARD
        ========================================= */

        .food-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
            border: 1px solid #eee;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
            transition: 0.3s;
        }

        .food-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .food-image {
            height: 225px;
            position: relative;
            overflow: hidden;
        }

        .food-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
        }

        .food-card:hover .food-image img {
            transform: scale(1.08);
        }

        .food-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            background: #e63946;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .favorite-btn {
            position: absolute;
            right: 15px;
            top: 15px;
            width: 38px;
            height: 38px;
            border: none;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            color: #555;
            transition: 0.3s;
        }

        .favorite-btn:hover,
        .favorite-btn.active {
            color: #e63946;
        }

        .food-body {
            padding: 22px;
        }

        .food-category {
            color: #e63946;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .food-name {
            font-size: 19px;
            font-weight: 900;
            margin: 7px 0;
        }

        .food-description {
            color: #888;
            font-size: 13px;
            line-height: 1.55;
            min-height: 42px;
        }

        .food-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 12px;
            font-size: 12px;
        }

        .food-rating i {
            color: #f4a261;
        }

        .rating-number {
            color: #777;
        }

        .food-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 18px;
        }

        .food-price {
            font-size: 20px;
            font-weight: 900;
            color: #e63946;
        }

        .add-cart-btn {
            width: 43px;
            height: 43px;
            border: none;
            background: #fff0f1;
            color: #e63946;
            border-radius: 50%;
            font-size: 18px;
            transition: 0.3s;
        }

        .add-cart-btn:hover {
            background: #e63946;
            color: white;
        }


        /* =========================================
           EMPTY RESULT
        ========================================= */

        .no-results {
            display: none;
            text-align: center;
            padding: 70px 20px;
        }

        .no-results i {
            font-size: 55px;
            color: #ddd;
        }

        .no-results h4 {
            margin-top: 20px;
            font-weight: 800;
        }

        .no-results p {
            color: #888;
        }


        /* =========================================
           CART SIDEBAR
        ========================================= */

        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -420px;
            width: 400px;
            max-width: 92%;
            height: 100vh;
            background: white;
            z-index: 2000;
            box-shadow: -10px 0 40px rgba(0, 0, 0, 0.18);
            transition: 0.4s;
            display: flex;
            flex-direction: column;
        }

        .cart-sidebar.active {
            right: 0;
        }

        .cart-header {
            padding: 22px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-header h4 {
            margin: 0;
            font-weight: 900;
        }

        .close-cart {
            border: none;
            background: transparent;
            font-size: 25px;
            color: #555;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .cart-item {
            display: flex;
            gap: 12px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item-image {
            width: 65px;
            height: 65px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .cart-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-content {
            flex: 1;
        }

        .cart-item-content h6 {
            font-weight: 800;
            margin-bottom: 4px;
        }

        .cart-item-price {
            color: #e63946;
            font-weight: 800;
            font-size: 13px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .quantity-controls button {
            width: 25px;
            height: 25px;
            border: 1px solid #ddd;
            border-radius: 50%;
            background: white;
            font-size: 12px;
        }

        .quantity-controls span {
            font-size: 13px;
            font-weight: 800;
        }

        .remove-item {
            border: none;
            background: transparent;
            color: #aaa;
            font-size: 17px;
        }

        .remove-item:hover {
            color: #e63946;
        }

        .empty-cart {
            text-align: center;
            padding: 70px 20px;
            color: #999;
        }

        .empty-cart i {
            font-size: 55px;
            color: #ddd;
        }

        .cart-footer {
            border-top: 1px solid #eee;
            padding: 20px;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
            font-size: 18px;
            font-weight: 900;
        }

        .checkout-btn {
            width: 100%;
            background: #e63946;
            border: none;
            color: white;
            padding: 14px;
            border-radius: 30px;
            font-weight: 800;
        }

        .checkout-btn:hover {
            background: #c92f3c;
        }


        /* =========================================
           CART OVERLAY
        ========================================= */

        .cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1500;
            display: none;
        }

        .cart-overlay.active {
            display: block;
        }


        /* =========================================
           FOOD MODAL
        ========================================= */

        .food-modal-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
        }

        .modal-food-title {
            font-size: 28px;
            font-weight: 900;
        }

        .modal-food-price {
            color: #e63946;
            font-size: 24px;
            font-weight: 900;
        }

        .modal-food-description {
            color: #777;
            line-height: 1.7;
        }

        .modal-add-btn {
            background: #e63946;
            color: white;
            border: none;
            padding: 13px 25px;
            border-radius: 30px;
            font-weight: 800;
        }

        .modal-add-btn:hover {
            background: #c92f3c;
        }


        /* =========================================
           FOOTER
        ========================================= */

        footer {
            background: #171717;
            color: #ddd;
            padding: 60px 0 25px;
            margin-top: 20px;
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

            .menu-heading {
                align-items: start;
                flex-direction: column;
                gap: 10px;
            }

        }


        @media (max-width: 767px) {

            .menu-section {
                padding: 55px 0;
            }

            .menu-hero {
                min-height: 300px;
            }

            .menu-hero h1 {
                font-size: 43px;
            }

            .menu-hero p {
                font-size: 15px;
            }

            .food-image {
                height: 210px;
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

                    <a class="nav-link active"
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
                       href="#menu">

                        Order Now

                    </a>

                </li>


                <li class="nav-item">

                    <a href="#"
                       class="cart-btn"
                       id="openCart">

                        <i class="bi bi-bag"></i>

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
     MENU HERO
================================================== -->

<section class="menu-hero">

    <div class="menu-hero-content">

        <span class="menu-label">
            UrbanBites Menu
        </span>

        <h1>
            Choose Your Favourite
        </h1>

        <p>
            Explore our delicious selection of freshly prepared
            meals, snacks, desserts and refreshing drinks.
        </p>

    </div>

</section>



<!-- ==================================================
     MENU SECTION
================================================== -->

<section class="menu-section"
         id="menu">

    <div class="container">


        <!-- SEARCH + FILTER -->

        <div class="menu-toolbar">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <div class="search-wrapper">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               id="searchInput"
                               class="search-input"
                               placeholder="Search for burgers, pizza, pasta...">

                    </div>

                </div>


                <div class="col-lg-6">

                    <div class="category-filters">

                        <button class="category-btn active"
                                data-category="All">

                            All

                        </button>

                        <button class="category-btn"
                                data-category="Burgers">

                            Burgers

                        </button>

                        <button class="category-btn"
                                data-category="Pizza">

                            Pizza

                        </button>

                        <button class="category-btn"
                                data-category="Rice">

                            Rice

                        </button>

                        <button class="category-btn"
                                data-category="Pasta">

                            Pasta

                        </button>

                        <button class="category-btn"
                                data-category="Desserts">

                            Desserts

                        </button>

                        <button class="category-btn"
                                data-category="Drinks">

                            Drinks

                        </button>

                    </div>

                </div>

            </div>

        </div>



        <!-- MENU HEADING -->

        <div class="menu-heading">

            <div>

                <h2>
                    Our Menu
                </h2>

                <p>
                    Freshly prepared just for you.
                </p>

            </div>

            <div class="result-count"
                 id="resultCount">

                Showing 12 items

            </div>

        </div>



        <!-- ==================================================
             FOOD GRID
        ================================================== -->

        <div class="row g-4"
             id="foodGrid">


            <!-- FOOD 1 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Burgers"
                 data-name="Urban Classic Burger">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/clssic_burger.jpg"
                             alt="Urban Classic Burger">

                        <span class="food-badge">
                            Popular
                        </span>

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Burgers
                        </span>

                        <h5 class="food-name">
                            Urban Classic Burger
                        </h5>

                        <p class="food-description">
                            Juicy beef patty, fresh lettuce,
                            cheese and signature sauce.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>

                            <span class="rating-number">
                                4.8 (126)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 1,250
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(1)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 2 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Burgers"
                 data-name="Crispy Chicken Burger">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/crispy-chicken-burger.jpg"
                             alt="Crispy Chicken Burger">

                        <span class="food-badge">
                            Bestseller
                        </span>

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Burgers
                        </span>

                        <h5 class="food-name">
                            Crispy Chicken Burger
                        </h5>

                        <p class="food-description">
                            Crispy chicken fillet with lettuce,
                            cheese and creamy sauce.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>

                            <span class="rating-number">
                                4.6 (94)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 1,450
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(2)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 3 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Pizza"
                 data-name="Chicken Supreme Pizza">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/super_pizza.jpg"
                             alt="Chicken Supreme Pizza">

                        <span class="food-badge">
                            Bestseller
                        </span>

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Pizza
                        </span>

                        <h5 class="food-name">
                            Chicken Supreme Pizza
                        </h5>

                        <p class="food-description">
                            Chicken, mushrooms, peppers,
                            onions and mozzarella.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>

                            <span class="rating-number">
                                4.9 (218)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 2,450
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(3)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 4 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Pizza"
                 data-name="Spicy Chicken Pizza">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/spicy-pizza.jpg"
                             alt="Spicy Chicken Pizza">

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Pizza
                        </span>

                        <h5 class="food-name">
                            Spicy Chicken Pizza
                        </h5>

                        <p class="food-description">
                            Spicy chicken, jalapeños,
                            onions and melted cheese.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>

                            <span class="rating-number">
                                4.5 (83)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 2,250
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(4)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 5 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Rice"
                 data-name="Urban Special Fried Rice">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/rice.jpg"
                             alt="Urban Special Fried Rice">

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Rice
                        </span>

                        <h5 class="food-name">
                            Urban Special Rice
                        </h5>

                        <p class="food-description">
                            Fragrant fried rice with chicken,
                            vegetables and special seasoning.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>

                            <span class="rating-number">
                                4.7 (107)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 1,650
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(5)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 6 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Rice"
                 data-name="Chicken Kottu">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/kottu.jpeg"
                             alt="Chicken Kottu">

                        <span class="food-badge">
                            Local Favourite
                        </span>

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Rice
                        </span>

                        <h5 class="food-name">
                            Chicken Kottu
                        </h5>

                        <p class="food-description">
                            Chopped roti mixed with chicken,
                            vegetables, egg and spices.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>

                            <span class="rating-number">
                                4.7 (141)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 1,550
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(6)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 7 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Pasta"
                 data-name="Creamy Chicken Pasta">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/super_pasta.jpg"
                             alt="Creamy Chicken Pasta">

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Pasta
                        </span>

                        <h5 class="food-name">
                            Creamy Chicken Pasta
                        </h5>

                        <p class="food-description">
                            Creamy pasta with grilled chicken,
                            herbs and parmesan cheese.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>

                            <span class="rating-number">
                                4.6 (91)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 1,850
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(7)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 8 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Pasta"
                 data-name="Spicy Seafood Pasta">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/seafood-pasta.jpg"
                             alt="Spicy Seafood Pasta">

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Pasta
                        </span>

                        <h5 class="food-name">
                            Spicy Seafood Pasta
                        </h5>

                        <p class="food-description">
                            Seafood pasta with prawns,
                            herbs and spicy tomato sauce.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>

                            <span class="rating-number">
                                4.7 (76)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 2,150
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(8)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 9 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Desserts"
                 data-name="Chocolate Lava Cake">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/dessert.jpg"
                             alt="Chocolate Lava Cake">

                        <span class="food-badge">
                            New
                        </span>

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Desserts
                        </span>

                        <h5 class="food-name">
                            Chocolate Lava Cake
                        </h5>

                        <p class="food-description">
                            Warm chocolate cake with a
                            rich melted chocolate centre.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>

                            <span class="rating-number">
                                4.9 (68)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 950
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(9)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 10 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Desserts"
                 data-name="Strawberry Cheesecake">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/cheesecake.jpg"
                             alt="Strawberry Cheesecake">

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Desserts
                        </span>

                        <h5 class="food-name">
                            Strawberry Cheesecake
                        </h5>

                        <p class="food-description">
                            Creamy cheesecake topped with
                            fresh strawberry sauce.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>

                            <span class="rating-number">
                                4.8 (52)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 1,050
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(10)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 11 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Drinks"
                 data-name="Fresh Mango Juice">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/drinks.webp"
                             alt="Fresh Mango Juice">

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Drinks
                        </span>

                        <h5 class="food-name">
                            Fresh Mango Juice
                        </h5>

                        <p class="food-description">
                            Freshly prepared mango juice
                            served chilled.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>

                            <span class="rating-number">
                                4.6 (48)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 650
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(11)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- FOOD 12 -->

            <div class="col-md-6 col-lg-4 col-xl-3 food-item"
                 data-category="Drinks"
                 data-name="Iced Coffee">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/iced-coffee.jpg"
                             alt="Iced Coffee">

                        <span class="food-badge">
                            Popular
                        </span>

                        <button class="favorite-btn">

                            <i class="bi bi-heart"></i>

                        </button>

                    </div>


                    <div class="food-body">

                        <span class="food-category">
                            Drinks
                        </span>

                        <h5 class="food-name">
                            Urban Iced Coffee
                        </h5>

                        <p class="food-description">
                            Smooth chilled coffee with
                            milk and a touch of sweetness.
                        </p>


                        <div class="food-rating">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>

                            <span class="rating-number">
                                4.7 (72)
                            </span>

                        </div>


                        <div class="food-footer">

                            <span class="food-price">
                                Rs. 700
                            </span>

                            <button class="add-cart-btn"
                                    onclick="addToCart(12)">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>


        </div>



        <!-- NO RESULTS -->

        <div class="no-results"
             id="noResults">

            <i class="bi bi-search"></i>

            <h4>
                No food found
            </h4>

            <p>
                Try another food name or choose a different category.
            </p>

        </div>

    </div>

</section>



<!-- ==================================================
     CART OVERLAY
================================================== -->

<div class="cart-overlay"
     id="cartOverlay">
</div>



<!-- ==================================================
     CART SIDEBAR
================================================== -->

<div class="cart-sidebar"
     id="cartSidebar">


    <div class="cart-header">

        <h4>
            Your Cart
        </h4>

        <button class="close-cart"
                id="closeCart">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>


    <div class="cart-items"
         id="cartItems">

        <div class="empty-cart"
             id="emptyCart">

            <i class="bi bi-bag"></i>

            <h5 class="mt-3">
                Your cart is empty
            </h5>

            <p>
                Add some delicious food to get started.
            </p>

        </div>

    </div>


    <div class="cart-footer">

        <div class="cart-total">

            <span>
                Total
            </span>

            <span id="cartTotal">
                Rs. 0
            </span>

        </div>


        <button class="checkout-btn"
                onclick="goToCheckout()">

            Proceed to Checkout

            <i class="bi bi-arrow-right ms-1"></i>

        </button>

    </div>

</div>



<!-- ==================================================
     FOOD DETAILS MODAL
================================================== -->

<div class="modal fade"
     id="foodModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <div class="modal-body p-4">

                <div class="row g-4 align-items-center">

                    <div class="col-md-6">

                        <img id="modalImage"
                             class="food-modal-image"
                             src=""
                             alt="Food">

                    </div>


                    <div class="col-md-6">

                        <span class="food-category"
                              id="modalCategory">

                        </span>


                        <h2 class="modal-food-title mt-2"
                            id="modalTitle">

                        </h2>


                        <div class="modal-food-price my-3"
                             id="modalPrice">

                        </div>


                        <p class="modal-food-description"
                           id="modalDescription">

                        </p>


                        <div class="d-flex gap-2 mt-4">

                            <button class="modal-add-btn"
                                    id="modalAddButton">

                                <i class="bi bi-bag-plus me-1"></i>

                                Add to Cart

                            </button>


                            <button type="button"
                                    class="btn btn-light rounded-pill px-4"
                                    data-bs-dismiss="modal">

                                Close

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



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
                        <a href="index.php#about">
                            About
                        </a>
                    </li>

                    <li>
                        <a href="index.php#reviews">
                            Reviews
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



<!-- ==================================================
     JAVASCRIPT
================================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>



<script>

    /* =========================================
       FOOD DATA
    ========================================= */

    const foods = {

        1: {
            name: "Urban Classic Burger",
            category: "Burgers",
            price: 1250,
            image: "../../assets/img/clssic_burger.jpg",
            description:
                "Juicy beef patty, fresh lettuce, cheese and our signature UrbanBites sauce."
        },

        2: {
            name: "Crispy Chicken Burger",
            category: "Burgers",
            price: 1450,
            image: "../../assets/img/crispy-chicken-burger.jpg",
            description:
                "Crispy chicken fillet with fresh lettuce, cheese and creamy sauce."
        },

        3: {
            name: "Chicken Supreme",
            category: "Pizza",
            price: 2450,
            image: "../../assets/img/super_pizza.jpg",
            description:
                "Chicken, mushrooms, peppers, onions and mozzarella on a delicious pizza base."
        },

        4: {
            name: "Spicy Chicken Pizza",
            category: "Pizza",
            price: 2250,
            image: "../../assets/img/spicy-pizza.jpg",
            description:
                "Spicy chicken, jalapeños, onions and melted mozzarella cheese."
        },

        5: {
            name: "Urban Special Rice",
            category: "Rice",
            price: 1650,
            image: "../../assets/img/rice.jpg",
            description:
                "Fragrant fried rice with chicken, vegetables and special UrbanBites seasoning."
        },

        6: {
            name: "Chicken Kottu",
            category: "Rice",
            price: 1550,
            image: "../../assets/img/kottu.jpeg",
            description:
                "Traditional chopped roti mixed with chicken, vegetables, egg and spices."
        },

        7: {
            name: "Creamy Chicken Pasta",
            category: "Pasta",
            price: 1850,
            image: "../../assets/img/super_pasta.jpg",
            description:
                "Creamy pasta with grilled chicken, herbs and parmesan cheese."
        },

        8: {
            name: "Spicy Seafood Pasta",
            category: "Pasta",
            price: 2150,
            image: "../../assets/img/seafood-pasta.jpg",
            description:
                "Seafood pasta with prawns, herbs and spicy tomato sauce."
        },

        9: {
            name: "Chocolate Lava Cake",
            category: "Desserts",
            price: 950,
            image: "../../assets/img/dessert.jpg",
            description:
                "Warm chocolate cake with a rich melted chocolate centre."
        },

        10: {
            name: "Strawberry Cheesecake",
            category: "Desserts",
            price: 1050,
            image: "../../assets/img/cheesecake.jpg",
            description:
                "Creamy cheesecake topped with fresh strawberry sauce."
        },

        11: {
            name: "Fresh Mango Juice",
            category: "Drinks",
            price: 650,
            image: "../../assets/img/drinks.webp",
            description:
                "Freshly prepared mango juice served chilled."
        },

        12: {
            name: "Urban Iced Coffee",
            category: "Drinks",
            price: 700,
            image: "../../assets/img/iced-coffee.jpg",
            description:
                "Smooth chilled coffee with milk and a touch of sweetness."
        }

    };


    /* =========================================
       CART
    ========================================= */

    let cart = [];


    function addToCart(id) {

        const food = foods[id];

        const existingItem = cart.find(
            item => item.id === id
        );


        if (existingItem) {

            existingItem.quantity++;

        } else {

            cart.push({

                id: id,
                name: food.name,
                price: food.price,
                image: food.image,
                quantity: 1

            });

        }


        updateCart();


        showCartNotification(food.name);

    }



    function updateCart() {

        const cartItems = document.getElementById("cartItems");

        const cartCount = document.getElementById("cartCount");

        const cartTotal = document.getElementById("cartTotal");


        let totalItems = 0;

        let totalPrice = 0;


        cart.forEach(item => {

            totalItems += item.quantity;

            totalPrice +=
                item.price * item.quantity;

        });


        cartCount.textContent = totalItems;


        cartTotal.textContent =
            "Rs. " + totalPrice.toLocaleString();


        if (cart.length === 0) {

            cartItems.innerHTML = `

                <div class="empty-cart">

                    <i class="bi bi-bag"></i>

                    <h5 class="mt-3">
                        Your cart is empty
                    </h5>

                    <p>
                        Add some delicious food to get started.
                    </p>

                </div>

            `;

            return;

        }


        cartItems.innerHTML = "";


        cart.forEach(item => {

            cartItems.innerHTML += `

                <div class="cart-item">

                    <div class="cart-item-image">

                        <img src="${item.image}"
                             alt="${item.name}">

                    </div>


                    <div class="cart-item-content">

                        <h6>
                            ${item.name}
                        </h6>

                        <div class="cart-item-price">

                            Rs. ${item.price.toLocaleString()}

                        </div>


                        <div class="quantity-controls">

                            <button
                                onclick="changeQuantity(${item.id}, -1)">

                                <i class="bi bi-dash"></i>

                            </button>


                            <span>
                                ${item.quantity}
                            </span>


                            <button
                                onclick="changeQuantity(${item.id}, 1)">

                                <i class="bi bi-plus"></i>

                            </button>

                        </div>

                    </div>


                    <button class="remove-item"
                            onclick="removeFromCart(${item.id})">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>

            `;

        });

    }



    function changeQuantity(id, amount) {

        const item = cart.find(
            item => item.id === id
        );


        if (!item) {
            return;
        }


        item.quantity += amount;


        if (item.quantity <= 0) {

            cart = cart.filter(
                item => item.id !== id
            );

        }


        updateCart();

    }



    function removeFromCart(id) {

        cart = cart.filter(
            item => item.id !== id
        );


        updateCart();

    }



    /* =========================================
       CART SIDEBAR
    ========================================= */

    const openCart =
        document.getElementById("openCart");

    const closeCart =
        document.getElementById("closeCart");

    const cartSidebar =
        document.getElementById("cartSidebar");

    const cartOverlay =
        document.getElementById("cartOverlay");


    openCart.addEventListener("click", function(e) {

        e.preventDefault();

        cartSidebar.classList.add("active");

        cartOverlay.classList.add("active");

    });


    closeCart.addEventListener("click", closeCartSidebar);

    cartOverlay.addEventListener(
        "click",
        closeCartSidebar
    );


    function closeCartSidebar() {

        cartSidebar.classList.remove("active");

        cartOverlay.classList.remove("active");

    }



    /* =========================================
       SEARCH
    ========================================= */

    const searchInput =
        document.getElementById("searchInput");


    searchInput.addEventListener(
        "input",
        filterFoods
    );



    /* =========================================
       CATEGORY FILTER
    ========================================= */

    const categoryButtons =
        document.querySelectorAll(".category-btn");


    categoryButtons.forEach(button => {

        button.addEventListener("click", function() {

            categoryButtons.forEach(btn => {

                btn.classList.remove("active");

            });


            this.classList.add("active");


            filterFoods();

        });

    });



    function filterFoods() {

        const searchValue =
            searchInput.value
                .toLowerCase()
                .trim();


        const activeCategory =
            document.querySelector(
                ".category-btn.active"
            ).dataset.category;


        const foodItems =
            document.querySelectorAll(".food-item");


        let visibleCount = 0;


        foodItems.forEach(item => {

            const name =
                item.dataset.name.toLowerCase();


            const category =
                item.dataset.category;


            const matchesSearch =
                name.includes(searchValue);


            const matchesCategory =
                activeCategory === "All" ||
                category === activeCategory;


            if (
                matchesSearch &&
                matchesCategory
            ) {

                item.style.display = "";

                visibleCount++;

            } else {

                item.style.display = "none";

            }

        });


        document.getElementById(
            "resultCount"
        ).textContent =
            "Showing " + visibleCount + " items";


        const noResults =
            document.getElementById("noResults");


        if (visibleCount === 0) {

            noResults.style.display = "block";

        } else {

            noResults.style.display = "none";

        }

    }



    /* =========================================
       FAVORITES
    ========================================= */

    document.querySelectorAll(
        ".favorite-btn"
    ).forEach(button => {

        button.addEventListener(
            "click",
            function() {

                this.classList.toggle("active");


                const icon =
                    this.querySelector("i");


                if (
                    this.classList.contains("active")
                ) {

                    icon.classList.remove(
                        "bi-heart"
                    );

                    icon.classList.add(
                        "bi-heart-fill"
                    );

                } else {

                    icon.classList.remove(
                        "bi-heart-fill"
                    );

                    icon.classList.add(
                        "bi-heart"
                    );

                }

            }
        );

    });



    /* =========================================
       FOOD CARD DETAILS
    ========================================= */

    document.querySelectorAll(
        ".food-card"
    ).forEach((card, index) => {

        card.querySelector(
            ".food-image"
        ).addEventListener(
            "dblclick",
            function() {

                const foodId = index + 1;

                if (foods[foodId]) {

                    showFoodDetails(foodId);

                }

            }
        );

    });



    function showFoodDetails(id) {

        const food = foods[id];


        if (!food) {
            return;
        }


        document.getElementById(
            "modalImage"
        ).src = food.image;


        document.getElementById(
            "modalCategory"
        ).textContent = food.category;


        document.getElementById(
            "modalTitle"
        ).textContent = food.name;


        document.getElementById(
            "modalPrice"
        ).textContent =
            "Rs. " +
            food.price.toLocaleString();


        document.getElementById(
            "modalDescription"
        ).textContent = food.description;


        document.getElementById(
            "modalAddButton"
        ).onclick = function() {

            addToCart(id);

            bootstrap.Modal
                .getInstance(
                    document.getElementById("foodModal")
                )
                .hide();

        };


        const modal =
            new bootstrap.Modal(
                document.getElementById("foodModal")
            );


        modal.show();

    }



    /* =========================================
       CHECKOUT
    ========================================= */

    function goToCheckout() {

        if (cart.length === 0) {

            alert(
                "Your cart is empty. Please add some food first."
            );

            return;

        }


        /*
         * Later this will redirect to:
         *
         * checkout.php
         *
         * and the cart will be stored
         * using PHP session/database.
         */

        window.location.href =
            "cart.php";

    }



    /* =========================================
       SIMPLE ADD TO CART MESSAGE
    ========================================= */

    function showCartNotification(foodName) {

        const message =
            document.createElement("div");


        message.innerHTML = `

            <div style="
                position:fixed;
                bottom:25px;
                right:25px;
                background:#222;
                color:white;
                padding:14px 20px;
                border-radius:30px;
                z-index:5000;
                box-shadow:0 8px 30px rgba(0,0,0,.25);
                font-size:13px;
            ">

                <i class="bi bi-check-circle-fill"
                   style="color:#4ade80;margin-right:7px;"></i>

                ${foodName} added to cart

            </div>

        `;


        document.body.appendChild(message);


        setTimeout(function() {

            message.remove();

        }, 2200);

    }



    /* =========================================
       URL CATEGORY SUPPORT
       
       Example:
       menu.php?category=Pizza
    ========================================= */

    const urlParams =
        new URLSearchParams(
            window.location.search
        );


    const urlCategory =
        urlParams.get("category");


    if (urlCategory) {

        const matchingButton =
            document.querySelector(
                `.category-btn[data-category="${urlCategory}"]`
            );


        if (matchingButton) {

            categoryButtons.forEach(
                btn => btn.classList.remove("active")
            );


            matchingButton.classList.add("active");


            filterFoods();

        }

    }

</script>


</body>

</html>