<?php
// UrbanBites - Menu Page
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Menu | UrbanBites</title>

    <link
        rel="stylesheet"
        href="../../../assets/bootstrap/bootstrap.min.css">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Trebuchet MS", Arial, sans-serif;
            background: #fafafa;
            color: #222;
        }

        /* =========================================
           NAVBAR
        ========================================= */

        .navbar {
            background: #ffffff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .navbar-brand {
            font-size: 29px;
            font-weight: 800;
            color: #e63946 !important;
            letter-spacing: -0.5px;
        }

        .nav-link {
            color: #333 !important;
            font-weight: 600;
            margin: 0 7px;
        }

        .nav-link:hover {
            color: #e63946 !important;
        }

        .cart-link {
            position: relative;
            font-size: 20px;
        }

        .cart-count {
            position: absolute;
            top: 0;
            right: -8px;
            min-width: 19px;
            height: 19px;
            padding: 0 5px;
            background: #e63946;
            color: white;
            border-radius: 20px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        /* =========================================
           HERO
        ========================================= */

        .menu-hero {
            min-height: 390px;

            background:
                linear-gradient(
                    rgba(0,0,0,0.65),
                    rgba(0,0,0,0.65)
                ),
                url("assets/images/menu-banner.jpg")
                center/cover no-repeat;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;

            color: white;
        }

        .menu-hero-content {
            max-width: 700px;
            padding: 30px;
        }

        .hero-label {
            display: inline-block;
            color: #ffb3ba;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .menu-hero h1 {
            font-size: 58px;
            font-weight: 900;
            margin-bottom: 15px;
        }

        .menu-hero p {
            font-size: 18px;
            color: #eee;
            margin-bottom: 0;
        }

        /* =========================================
           MENU SECTION
        ========================================= */

        .menu-section {
            padding: 70px 0 90px;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-heading span {
            color: #e63946;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .section-heading h2 {
            font-size: 38px;
            font-weight: 900;
            margin-top: 8px;
        }

        .section-heading p {
            color: #777;
            max-width: 650px;
            margin: 10px auto 0;
        }

        /* =========================================
           SEARCH
        ========================================= */

        .search-container {
            max-width: 700px;
            margin: 0 auto 35px;
            position: relative;
        }

        .search-container input {
            width: 100%;
            height: 58px;
            border: 1px solid #ddd;
            border-radius: 30px;
            padding: 0 60px 0 25px;
            font-size: 15px;
            outline: none;
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .search-container input:focus {
            border-color: #e63946;
            box-shadow:
                0 5px 20px rgba(230,57,70,0.12);
        }

        .search-icon {
            position: absolute;
            right: 22px;
            top: 17px;
            font-size: 22px;
        }

        /* =========================================
           CATEGORY BUTTONS
        ========================================= */

        .category-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 45px;
        }

        .category-btn {
            border: 1px solid #ddd;
            background: white;
            color: #555;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: 0.25s;
        }

        .category-btn:hover {
            border-color: #e63946;
            color: #e63946;
        }

        .category-btn.active {
            background: #e63946;
            color: white;
            border-color: #e63946;
        }

        /* =========================================
           FOOD CARD
        ========================================= */

        .food-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #eee;
            height: 100%;
            transition: 0.3s;
            position: relative;
        }

        .food-card:hover {
            transform: translateY(-6px);
            box-shadow:
                0 15px 35px rgba(0,0,0,0.10);
        }

        .food-image-container {
            height: 220px;
            position: relative;
            overflow: hidden;
            background: #eee;
        }

        .food-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.4s;
        }

        .food-card:hover .food-image {
            transform: scale(1.06);
        }

        .popular-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #e63946;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .food-category {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 10px;
        }

        .food-content {
            padding: 22px;
        }

        .food-content h3 {
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .food-description {
            color: #777;
            font-size: 13px;
            line-height: 1.6;
            min-height: 42px;
        }

        .food-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
        }

        .food-price {
            color: #e63946;
            font-size: 20px;
            font-weight: 900;
        }

        .add-cart-btn {
            border: none;
            background: #171717;
            color: white;
            padding: 10px 16px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.25s;
        }

        .add-cart-btn:hover {
            background: #e63946;
            transform: translateY(-2px);
        }

        /* =========================================
           EMPTY SEARCH
        ========================================= */

        .no-results {
            display: none;
            text-align: center;
            padding: 60px 20px;
        }

        .no-results-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .no-results h3 {
            font-weight: 800;
        }

        .no-results p {
            color: #777;
        }

        /* =========================================
           CART FLOATING BAR
        ========================================= */

        .cart-bar {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #171717;
            color: white;
            border-radius: 14px;
            padding: 13px 18px;
            display: none;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.25);
            z-index: 1000;
        }

        .cart-bar-info {
            font-size: 13px;
        }

        .cart-bar-info strong {
            display: block;
        }

        .cart-bar-info small {
            color: #aaa;
        }

        .view-cart-btn {
            background: #e63946;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
        }

        .view-cart-btn:hover {
            background: #c92f3c;
            color: white;
        }

        /* =========================================
           TOAST
        ========================================= */

        .cart-toast {
            position: fixed;
            top: 90px;
            right: 25px;
            background: #171717;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 2000;
            transform: translateX(450px);
            transition: 0.4s;
        }

        .cart-toast.show {
            transform: translateX(0);
        }

        .toast-title {
            font-weight: 800;
        }

        .toast-message {
            color: #bbb;
            font-size: 12px;
            margin-top: 3px;
        }

        /* =========================================
           CTA
        ========================================= */

        .order-cta {
            margin-top: 70px;
            background:
                linear-gradient(
                    120deg,
                    #171717,
                    #2b2022
                );
            color: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
        }

        .order-cta h2 {
            font-size: 32px;
            font-weight: 900;
        }

        .order-cta p {
            color: #bbb;
            max-width: 600px;
            margin: 12px auto 25px;
        }

        .cta-btn {
            display: inline-block;
            background: #e63946;
            color: white;
            padding: 13px 25px;
            border-radius: 9px;
            text-decoration: none;
            font-weight: 800;
        }

        .cta-btn:hover {
            background: #c92f3c;
            color: white;
        }

        /* =========================================
           FOOTER
        ========================================= */

        footer {
            background: #151515;
            color: #ccc;
            padding: 55px 0 25px;
        }

        footer h4 {
            color: white;
            font-weight: 800;
            margin-bottom: 15px;
        }

        footer p {
            color: #999;
            font-size: 14px;
        }

        .footer-link {
            color: #999;
            text-decoration: none;
        }

        .footer-link:hover {
            color: #e63946;
        }

        .footer-bottom {
            border-top: 1px solid #333;
            margin-top: 35px;
            padding-top: 20px;
            text-align: center;
            color: #777;
            font-size: 13px;
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 768px) {

            .menu-hero {
                min-height: 320px;
            }

            .menu-hero h1 {
                font-size: 42px;
            }

            .menu-hero p {
                font-size: 16px;
            }

            .menu-section {
                padding: 50px 0 70px;
            }

            .section-heading h2 {
                font-size: 31px;
            }

            .food-image-container {
                height: 200px;
            }

            .cart-bar {
                left: 15px;
                right: 15px;
                bottom: 15px;
                justify-content: space-between;
            }

            .cart-toast {
                right: 15px;
                left: 15px;
                transform: translateY(-150px);
            }

            .cart-toast.show {
                transform: translateY(0);
            }

            .order-cta {
                padding: 35px 20px;
            }

        }

    </style>

</head>


<body>


<!-- =========================================
     NAVBAR
========================================= -->

<nav class="navbar navbar-expand-lg sticky-top">

    <div class="container">

        <a
            class="navbar-brand"
            href="index.php">

            UrbanBites

        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="mainNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php">

                        Home

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="menu.php">

                        Menu

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php#about">

                        About

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php#reviews">

                        Reviews

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="menu.php">

                        Order Food

                    </a>

                </li>


                <li class="nav-item ms-lg-3">

                    <a
                        class="nav-link cart-link"
                        href="cart.php">

                        🛒

                        <span
                            id="cartCount"
                            class="cart-count">

                            0

                        </span>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- =========================================
     HERO
========================================= -->

<section class="menu-hero">

    <div class="menu-hero-content">

        <span class="hero-label">
            Fresh • Delicious • Urban
        </span>

        <h1>
            Our Menu
        </h1>

        <p>
            Explore our delicious selection of burgers,
            pizzas, pasta, drinks and more.
        </p>

    </div>

</section>


<!-- =========================================
     MENU
========================================= -->

<section class="menu-section">

    <div class="container">


        <!-- Heading -->

        <div class="section-heading">

            <span>
                What are you craving?
            </span>

            <h2>
                Explore Our Menu
            </h2>

            <p>
                Carefully prepared meals made with quality
                ingredients and served with the UrbanBites touch.
            </p>

        </div>


        <!-- Search -->

        <div class="search-container">

            <input
                type="text"
                id="searchInput"
                placeholder="Search for burgers, pizza, pasta..."
                onkeyup="searchFood()">

            <span class="search-icon">
                🔍
            </span>

        </div>


        <!-- Categories -->

        <div class="category-container">

            <button
                class="category-btn active"
                data-category="all"
                onclick="filterCategory('all', this)">

                All

            </button>


            <button
                class="category-btn"
                data-category="burgers"
                onclick="filterCategory('burgers', this)">

                🍔 Burgers

            </button>


            <button
                class="category-btn"
                data-category="pizza"
                onclick="filterCategory('pizza', this)">

                🍕 Pizza

            </button>


            <button
                class="category-btn"
                data-category="pasta"
                onclick="filterCategory('pasta', this)">

                🍝 Pasta

            </button>


            <button
                class="category-btn"
                data-category="chicken"
                onclick="filterCategory('chicken', this)">

                🍗 Chicken

            </button>


            <button
                class="category-btn"
                data-category="sides"
                onclick="filterCategory('sides', this)">

                🍟 Sides

            </button>


            <button
                class="category-btn"
                data-category="desserts"
                onclick="filterCategory('desserts', this)">

                🍰 Desserts

            </button>


            <button
                class="category-btn"
                data-category="drinks"
                onclick="filterCategory('drinks', this)">

                🥤 Drinks

            </button>

        </div>


        <!-- =====================================
             FOOD GRID
        ====================================== -->

        <div
            class="row g-4"
            id="foodGrid">


            <!-- BURGER 1 -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="burgers"
                data-name="Classic Urban Burger">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/burger.jpg"
                            class="food-image"
                            alt="Classic Urban Burger">

                        <span class="popular-badge">
                            Popular
                        </span>

                        <span class="food-category">
                            Burgers
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Classic Urban Burger
                        </h3>

                        <p class="food-description">
                            Juicy beef patty, fresh lettuce,
                            tomato, cheese and our signature sauce.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 1,850
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    1,
                                    'Classic Urban Burger',
                                    1850,
                                    'assets/images/burger.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- BURGER 2 -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="burgers"
                data-name="Double Cheese Burger">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/burger.jpg"
                            class="food-image"
                            alt="Double Cheese Burger">

                        <span class="food-category">
                            Burgers
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Double Cheese Burger
                        </h3>

                        <p class="food-description">
                            Two juicy beef patties with double
                            cheese and creamy burger sauce.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 2,450
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    2,
                                    'Double Cheese Burger',
                                    2450,
                                    'assets/images/burger.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- PIZZA 1 -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="pizza"
                data-name="Spicy Chicken Pizza">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/pizza.jpg"
                            class="food-image"
                            alt="Spicy Chicken Pizza">

                        <span class="popular-badge">
                            Bestseller
                        </span>

                        <span class="food-category">
                            Pizza
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Spicy Chicken Pizza
                        </h3>

                        <p class="food-description">
                            Tender chicken, mozzarella cheese,
                            peppers and our spicy house sauce.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 2,600
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    3,
                                    'Spicy Chicken Pizza',
                                    2600,
                                    'assets/images/pizza.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- PIZZA 2 -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="pizza"
                data-name="Margherita Pizza">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/pizza.jpg"
                            class="food-image"
                            alt="Margherita Pizza">

                        <span class="food-category">
                            Pizza
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Margherita Pizza
                        </h3>

                        <p class="food-description">
                            Classic tomato sauce, mozzarella,
                            basil and Italian herbs.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 1,900
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    4,
                                    'Margherita Pizza',
                                    1900,
                                    'assets/images/pizza.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- PASTA -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="pasta"
                data-name="Creamy Chicken Pasta">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/pasta.jpg"
                            class="food-image"
                            alt="Creamy Chicken Pasta">

                        <span class="popular-badge">
                            Popular
                        </span>

                        <span class="food-category">
                            Pasta
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Creamy Chicken Pasta
                        </h3>

                        <p class="food-description">
                            Creamy Alfredo sauce, grilled chicken,
                            herbs and perfectly cooked pasta.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 2,200
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    5,
                                    'Creamy Chicken Pasta',
                                    2200,
                                    'assets/images/pasta.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- CHICKEN -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="chicken"
                data-name="BBQ Chicken">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/chicken.jpg"
                            class="food-image"
                            alt="BBQ Chicken">

                        <span class="food-category">
                            Chicken
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            BBQ Chicken
                        </h3>

                        <p class="food-description">
                            Tender grilled chicken coated with
                            smoky BBQ sauce and fresh herbs.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 2,750
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    6,
                                    'BBQ Chicken',
                                    2750,
                                    'assets/images/chicken.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FRIES -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="sides"
                data-name="Crispy French Fries">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/fries.jpg"
                            class="food-image"
                            alt="Crispy French Fries">

                        <span class="food-category">
                            Sides
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Crispy French Fries
                        </h3>

                        <p class="food-description">
                            Golden crispy fries lightly seasoned
                            and served with our signature dip.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 750
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    7,
                                    'Crispy French Fries',
                                    750,
                                    'assets/images/fries.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- SALAD -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="sides"
                data-name="Fresh Garden Salad">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/salad.jpg"
                            class="food-image"
                            alt="Fresh Garden Salad">

                        <span class="food-category">
                            Sides
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Fresh Garden Salad
                        </h3>

                        <p class="food-description">
                            Fresh lettuce, tomatoes, cucumber,
                            onions and homemade dressing.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 950
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    8,
                                    'Fresh Garden Salad',
                                    950,
                                    'assets/images/salad.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- DESSERT -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="desserts"
                data-name="Chocolate Brownie">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/dessert.jpg"
                            class="food-image"
                            alt="Chocolate Brownie">

                        <span class="popular-badge">
                            Sweet Pick
                        </span>

                        <span class="food-category">
                            Desserts
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Chocolate Brownie
                        </h3>

                        <p class="food-description">
                            Rich chocolate brownie served warm
                            with chocolate sauce.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 850
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    9,
                                    'Chocolate Brownie',
                                    850,
                                    'assets/images/dessert.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- DRINK -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="drinks"
                data-name="Fresh Lime Mojito">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/drinks.jpg"
                            class="food-image"
                            alt="Fresh Lime Mojito">

                        <span class="food-category">
                            Drinks
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Fresh Lime Mojito
                        </h3>

                        <p class="food-description">
                            Refreshing lime, mint and sparkling
                            water served chilled.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 650
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    10,
                                    'Fresh Lime Mojito',
                                    650,
                                    'assets/images/drinks.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- COLA -->

            <div
                class="col-lg-4 col-md-6 food-item"
                data-category="drinks"
                data-name="Chilled Cola">

                <div class="food-card">

                    <div class="food-image-container">

                        <img
                            src="assets/images/drinks.jpg"
                            class="food-image"
                            alt="Chilled Cola">

                        <span class="food-category">
                            Drinks
                        </span>

                    </div>


                    <div class="food-content">

                        <h3>
                            Chilled Cola
                        </h3>

                        <p class="food-description">
                            Ice-cold refreshing cola served
                            with plenty of ice.
                        </p>


                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 450
                            </span>

                            <button
                                class="add-cart-btn"
                                onclick="addToCart(
                                    11,
                                    'Chilled Cola',
                                    450,
                                    'assets/images/drinks.jpg'
                                )">

                                + Add to Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>


        </div>


        <!-- NO RESULTS -->

        <div
            id="noResults"
            class="no-results">

            <div class="no-results-icon">
                🔍
            </div>

            <h3>
                No food found
            </h3>

            <p>
                Try another search term or choose
                a different category.
            </p>

        </div>


        <!-- CTA -->

        <div class="order-cta">

            <h2>
                Ready to satisfy your cravings?
            </h2>

            <p>
                Add your favourite meals to the cart
                and place your order in just a few clicks.
            </p>

            <a
                href="cart.php"
                class="cta-btn">

                View My Cart →

            </a>

        </div>


    </div>

</section>


<!-- =========================================
     FLOATING CART BAR
========================================= -->

<div
    class="cart-bar"
    id="cartBar">

    <div class="cart-bar-info">

        <strong id="cartBarItems">
            0 items
        </strong>

        <small id="cartBarTotal">
            Rs. 0
        </small>

    </div>


    <a
        href="cart.php"
        class="view-cart-btn">

        View Cart →

    </a>

</div>


<!-- =========================================
     TOAST
========================================= -->

<div
    id="cartToast"
    class="cart-toast">

    <div class="toast-title">
        ✓ Added to Cart
    </div>

    <div
        id="toastMessage"
        class="toast-message">

        Item added successfully.

    </div>

</div>


<!-- =========================================
     FOOTER
========================================= -->

<footer>

    <div class="container">

        <div class="row g-4">

            <div class="col-md-5">

                <h4>
                    UrbanBites
                </h4>

                <p>
                    Delicious food, simple ordering and
                    a better dining experience.
                </p>

            </div>


            <div class="col-md-3">

                <h4>
                    Quick Links
                </h4>

                <p>
                    <a
                        href="index.php"
                        class="footer-link">

                        Home

                    </a>
                </p>

                <p>
                    <a
                        href="menu.php"
                        class="footer-link">

                        Menu

                    </a>
                </p>

                <p>
                    <a
                        href="cart.php"
                        class="footer-link">

                        Cart

                    </a>
                </p>

                <p>
                    <a
                        href="checkout.php"
                        class="footer-link">

                        Checkout

                    </a>
                </p>

            </div>


            <div class="col-md-4">

                <h4>
                    Contact
                </h4>

                <p>
                    📍 Colombo, Sri Lanka
                </p>

                <p>
                    📞 +94 7X XXX XXXX
                </p>

                <p>
                    ✉ hello@urbanbites.com
                </p>

            </div>

        </div>


        <div class="footer-bottom">

            © 2026 UrbanBites.
            All Rights Reserved.

        </div>

    </div>

</footer>


<script src="../../assets/bootstrap/bootstrap.bundle.min.js"></script>


<script>

    /*
    ============================================
    CART
    ============================================
    */

    let cart = JSON.parse(
        localStorage.getItem("urbanBitesCart")
    ) || [];


    /*
    ============================================
    INITIALIZE
    ============================================
    */

    document.addEventListener(
        "DOMContentLoaded",
        function() {

            updateCartUI();

        }
    );


    /*
    ============================================
    ADD TO CART
    ============================================
    */

    function addToCart(
        id,
        name,
        price,
        image
    ) {

        const existingItem =
            cart.find(
                item => Number(item.id) === Number(id)
            );


        if (existingItem) {

            existingItem.quantity++;

        } else {

            cart.push({

                id: id,

                name: name,

                price: Number(price),

                image: image,

                quantity: 1

            });

        }


        saveCart();

        updateCartUI();

        showToast(
            name + " added to your cart."
        );

    }


    /*
    ============================================
    SAVE CART
    ============================================
    */

    function saveCart() {

        localStorage.setItem(
            "urbanBitesCart",
            JSON.stringify(cart)
        );

    }


    /*
    ============================================
    UPDATE CART UI
    ============================================
    */

    function updateCartUI() {

        const itemCount =
            cart.reduce(
                (total, item) =>
                    total + Number(item.quantity),
                0
            );


        const total =
            cart.reduce(
                (sum, item) =>
                    sum +
                    (
                        Number(item.price) *
                        Number(item.quantity)
                    ),
                0
            );


        document.getElementById("cartCount")
            .textContent = itemCount;


        document.getElementById("cartBarItems")
            .textContent =
            itemCount +
            (itemCount === 1
                ? " item"
                : " items");


        document.getElementById("cartBarTotal")
            .textContent =
            "Rs. " +
            total.toLocaleString();


        const cartBar =
            document.getElementById("cartBar");


        if (itemCount > 0) {

            cartBar.style.display = "flex";

        } else {

            cartBar.style.display = "none";

        }

    }


    /*
    ============================================
    TOAST
    ============================================
    */

    function showToast(message) {

        const toast =
            document.getElementById("cartToast");

        const toastMessage =
            document.getElementById("toastMessage");


        toastMessage.textContent = message;

        toast.classList.add("show");


        setTimeout(
            function() {

                toast.classList.remove("show");

            },
            2500
        );

    }


    /*
    ============================================
    CATEGORY FILTER
    ============================================
    */

    function filterCategory(
        category,
        button
    ) {

        const items =
            document.querySelectorAll(
                ".food-item"
            );


        document
            .querySelectorAll(".category-btn")
            .forEach(
                function(btn) {

                    btn.classList.remove("active");

                }
            );


        button.classList.add("active");


        /*
        Clear search when changing category.
        */

        document.getElementById(
            "searchInput"
        ).value = "";


        let visibleCount = 0;


        items.forEach(
            function(item) {

                const itemCategory =
                    item.dataset.category;


                if (
                    category === "all" ||
                    itemCategory === category
                ) {

                    item.style.display = "block";

                    visibleCount++;

                } else {

                    item.style.display = "none";

                }

            }
        );


        showNoResults(
            visibleCount
        );

    }


    /*
    ============================================
    SEARCH
    ============================================
    */

    function searchFood() {

        const searchValue =
            document.getElementById(
                "searchInput"
            )
            .value
            .toLowerCase()
            .trim();


        const items =
            document.querySelectorAll(
                ".food-item"
            );


        /*
        Reset category buttons.
        */

        document
            .querySelectorAll(".category-btn")
            .forEach(
                function(btn) {

                    btn.classList.remove("active");

                }
            );


        document
            .querySelector(
                '[data-category="all"]'
            )
            .classList.add("active");


        let visibleCount = 0;


        items.forEach(
            function(item) {

                const name =
                    item.dataset.name.toLowerCase();

                const category =
                    item.dataset.category.toLowerCase();


                if (
                    name.includes(searchValue) ||
                    category.includes(searchValue)
                ) {

                    item.style.display = "block";

                    visibleCount++;

                } else {

                    item.style.display = "none";

                }

            }
        );


        showNoResults(
            visibleCount
        );

    }


    /*
    ============================================
    NO RESULTS
    ============================================
    */

    function showNoResults(
        count
    ) {

        const noResults =
            document.getElementById(
                "noResults"
            );


        if (count === 0) {

            noResults.style.display = "block";

        } else {

            noResults.style.display = "none";

        }

    }

</script>


</body>
</html>