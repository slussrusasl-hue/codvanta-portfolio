<?php
// UrbanBites - Restaurant Ordering & Management System
// Homepage
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>UrbanBites | Fresh Food. Fast Delivery.</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="../../assets/bootstrap/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

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
            background: #fff;
            color: #222;
        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar {
            background: rgba(255, 255, 255, 0.97);
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.08);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: 800;
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
            margin-left: 10px;
        }

        .nav-order-btn:hover {
            background: #c92f3c;
            color: white !important;
        }

        .cart-btn {
            position: relative;
            margin-left: 15px;
            font-size: 20px;
            color: #333;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -10px;
            background: #e63946;
            color: white;
            font-size: 10px;
            width: 19px;
            height: 19px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }


        /* =========================
           HERO
        ========================= */

        .hero {
            min-height: 680px;
            position: relative;
            overflow: hidden;

            background:
                linear-gradient(
                    90deg,
                    rgba(0, 0, 0, 0.82) 0%,
                    rgba(0, 0, 0, 0.65) 40%,
                    rgba(0, 0, 0, 0.15) 75%
                ),
                url("../../assets/img/close-up-delicious-burger-meal.jpg");

            background-size: cover;
            background-position: center;
        }

        .hero-content {
            min-height: 680px;
            display: flex;
            align-items: center;
        }

        .hero-text {
            max-width: 650px;
            color: white;
        }

        .hero-label {
            display: inline-block;
            background: rgba(230, 57, 70, 0.95);
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .hero h1 {
            font-size: clamp(45px, 6vw, 75px);
            line-height: 1.05;
            font-weight: 900;
            margin-bottom: 25px;
        }

        .hero h1 span {
            color: #f4a261;
        }

        .hero p {
            font-size: 18px;
            line-height: 1.7;
            color: #eee;
            max-width: 560px;
            margin-bottom: 35px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background: #e63946;
            color: white;
            padding: 14px 28px;
            border-radius: 30px;
            font-weight: bold;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            background: #c92f3c;
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-custom {
            border: 2px solid white;
            color: white;
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-outline-custom:hover {
            background: white;
            color: #222;
        }


        /* =========================
           QUICK INFO
        ========================= */

        .quick-info {
            margin-top: -55px;
            position: relative;
            z-index: 5;
        }

        .quick-card {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            gap: 18px;
            height: 100%;
        }

        .quick-icon {
            width: 55px;
            height: 55px;
            background: #fff0f1;
            color: #e63946;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .quick-card h6 {
            margin-bottom: 5px;
            font-weight: 800;
        }

        .quick-card p {
            margin: 0;
            color: #777;
            font-size: 13px;
        }


        /* =========================
           GENERAL SECTION
        ========================= */

        .section {
            padding: 90px 0;
        }

        .section-label {
            color: #e63946;
            text-transform: uppercase;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 42px;
            font-weight: 900;
            margin-bottom: 15px;
        }

        .section-description {
            color: #777;
            max-width: 620px;
            line-height: 1.7;
        }


        /* =========================
           FOOD CATEGORIES
        ========================= */

        .categories {
            background: #fafafa;
        }

        .category-card {
            background: white;
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            border: 1px solid #eee;
            transition: 0.3s;
            height: 100%;
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .category-image {
            width: 115px;
            height: 115px;
            margin: auto auto 20px;
            border-radius: 50%;
            overflow: hidden;
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.4s;
        }

        .category-card:hover img {
            transform: scale(1.1);
        }

        .category-card h5 {
            font-weight: 800;
            margin-bottom: 7px;
        }

        .category-card p {
            color: #888;
            font-size: 13px;
            margin: 0;
        }


        /* =========================
           POPULAR FOODS
        ========================= */

        .food-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            height: 100%;
            transition: 0.3s;
        }

        .food-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.13);
        }

        .food-image {
            height: 230px;
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

        .popular-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #e63946;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .food-content {
            padding: 22px;
        }

        .food-content h5 {
            font-weight: 800;
            margin-bottom: 8px;
        }

        .food-description {
            color: #888;
            font-size: 13px;
            line-height: 1.5;
            min-height: 40px;
        }

        .food-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 18px;
        }

        .food-price {
            color: #e63946;
            font-size: 20px;
            font-weight: 900;
        }

        .add-btn {
            width: 42px;
            height: 42px;
            border: none;
            background: #fff0f1;
            color: #e63946;
            border-radius: 50%;
            font-size: 19px;
            transition: 0.3s;
        }

        .add-btn:hover {
            background: #e63946;
            color: white;
        }


        /* =========================
           PROMOTION
        ========================= */

        .promotion {
            padding: 0 0 90px;
        }

        .promotion-box {
            min-height: 380px;
            border-radius: 28px;
            overflow: hidden;
            position: relative;

            background:
                linear-gradient(
                    90deg,
                    rgba(0, 0, 0, 0.82),
                    rgba(0, 0, 0, 0.25)
                ),
                url("../../assets/img/promotion.jpg");

            background-size: cover;
            background-position: center;

            display: flex;
            align-items: center;
        }

        .promotion-content {
            color: white;
            padding: 50px;
            max-width: 650px;
        }

        .promotion-content .small-label {
            color: #f4a261;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 13px;
        }

        .promotion-content h2 {
            font-size: 45px;
            font-weight: 900;
            margin: 15px 0;
        }

        .promotion-content p {
            color: #eee;
            line-height: 1.7;
            margin-bottom: 25px;
        }


        /* =========================
           ABOUT
        ========================= */

        .about-image {
            border-radius: 25px;
            overflow: hidden;
            height: 470px;
        }

        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-content {
            padding-left: 30px;
        }

        .about-content h2 {
            font-size: 42px;
            font-weight: 900;
            margin-bottom: 20px;
        }

        .about-content p {
            color: #777;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .about-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .about-feature i {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #fff0f1;
            color: #e63946;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        /* =========================
           TESTIMONIAL
        ========================= */

        .testimonials {
            background: #fafafa;
        }

        .review-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            height: 100%;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }

        .stars {
            color: #f4a261;
            margin-bottom: 15px;
        }

        .review-card p {
            color: #666;
            line-height: 1.7;
            font-style: italic;
        }

        .review-user {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
        }

        .review-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #e63946;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .review-user h6 {
            margin: 0;
            font-weight: 800;
        }

        .review-user small {
            color: #999;
        }


        /* =========================
           CTA
        ========================= */

        .cta {
            padding: 80px 0;
        }

        .cta-box {
            background: #e63946;
            border-radius: 28px;
            padding: 60px;
            color: white;
            text-align: center;
        }

        .cta-box h2 {
            font-size: 42px;
            font-weight: 900;
            margin-bottom: 15px;
        }

        .cta-box p {
            max-width: 650px;
            margin: 0 auto 25px;
            color: #ffecee;
            line-height: 1.7;
        }

        .btn-white {
            background: white;
            color: #e63946;
            padding: 14px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 800;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-white:hover {
            background: #222;
            color: white;
        }


        /* =========================
           FOOTER
        ========================= */

        footer {
            background: #171717;
            color: #ddd;
            padding: 65px 0 25px;
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
            margin-top: 45px;
            padding-top: 20px;
            color: #777;
            font-size: 13px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

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
            }

            .hero {
                min-height: 600px;
            }

            .hero-content {
                min-height: 600px;
            }

            .about-content {
                padding-left: 0;
                margin-top: 35px;
            }

            .quick-info {
                margin-top: 30px;
            }

        }


        @media (max-width: 767px) {

            .section {
                padding: 65px 0;
            }

            .section-title {
                font-size: 34px;
            }

            .hero {
                min-height: 600px;
            }

            .hero-content {
                min-height: 600px;
            }

            .hero h1 {
                font-size: 45px;
            }

            .hero p {
                font-size: 16px;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: flex-start;
            }

            .promotion-content {
                padding: 35px;
            }

            .promotion-content h2 {
                font-size: 34px;
            }

            .about-image {
                height: 350px;
            }

            .about-content h2 {
                font-size: 34px;
            }

            .cta-box {
                padding: 40px 25px;
            }

            .cta-box h2 {
                font-size: 32px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================
     NAVBAR
===================================== -->

<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            <img src="../../assets/img/main_logo.png" width="75px" height="60px" style="margin-top: -30px;">
            Urban<span class="brand-dot">Bites</span>
        </a>


        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#urbanNavbar">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div class="collapse navbar-collapse" id="urbanNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="menu.php">
                        Menu
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#reviews">
                        Reviews
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#contact">
                        Contact
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-order-btn"
                       href="menu.php">
                        Order Now
                    </a>
                </li>

                <li class="nav-item">

                    <a href="cart.php"
                       class="cart-btn">

                        <i class="bi bi-bag"></i>

                        <span class="cart-count">0</span>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- =====================================
     HERO
===================================== -->

<section class="hero">

    <div class="container hero-content">

        <div class="hero-text">

            <span class="hero-label">
                FRESH • FAST • DELICIOUS
            </span>

            <h1>
                Good food.<br>
                <span>Good mood.</span>
            </h1>

            <p>
                Discover delicious meals made with fresh ingredients,
                prepared with passion and delivered straight to your
                doorstep.
            </p>


            <div class="hero-buttons">

                <a href="menu.php"
                   class="btn-primary-custom">

                    Explore Our Menu

                    <i class="bi bi-arrow-right"></i>

                </a>


                <a href="#popular"
                   class="btn-outline-custom">

                    View Popular Dishes

                </a>

            </div>

        </div>

    </div>

</section>



<!-- =====================================
     QUICK INFORMATION
===================================== -->

<section class="quick-info">

    <div class="container">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="quick-card">

                    <div class="quick-icon">

                        <i class="bi bi-clock"></i>

                    </div>

                    <div>

                        <h6>Fast Preparation</h6>

                        <p>
                            Fresh meals prepared quickly
                        </p>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="quick-card">

                    <div class="quick-icon">

                        <i class="bi bi-truck"></i>

                    </div>

                    <div>

                        <h6>Quick Delivery</h6>

                        <p>
                            Hot food delivered to your door
                        </p>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="quick-card">

                    <div class="quick-icon">

                        <i class="bi bi-star"></i>

                    </div>

                    <div>

                        <h6>Quality Food</h6>

                        <p>
                            Carefully selected fresh ingredients
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================
     FOOD CATEGORIES
===================================== -->

<section class="section categories">

    <div class="container">

        <div class="text-center mb-5">

            <div class="section-label">
                Explore Our Menu
            </div>

            <h2 class="section-title">
                What are you craving?
            </h2>

            <p class="section-description mx-auto">
                From juicy burgers to delicious pizzas and refreshing
                drinks, find something you'll love at UrbanBites.
            </p>

        </div>


        <div class="row g-4">


            <!-- Category 1 -->

            <div class="col-6 col-md-4 col-lg-2">

                <a href="menu.php?category=Burgers"
                   style="text-decoration:none;color:inherit;">

                    <div class="category-card">

                        <div class="category-image">

                            <img src="../../assets/img/burger.jpg"
                                 alt="Burgers">

                        </div>

                        <h5>Burgers</h5>

                        <p>Juicy & delicious</p>

                    </div>

                </a>

            </div>


            <!-- Category 2 -->

            <div class="col-6 col-md-4 col-lg-2">

                <a href="menu.php?category=Pizza"
                   style="text-decoration:none;color:inherit;">

                    <div class="category-card">

                        <div class="category-image">

                            <img src="../../assets/img/pizza.jpg"
                                 alt="Pizza">

                        </div>

                        <h5>Pizza</h5>

                        <p>Fresh from oven</p>

                    </div>

                </a>

            </div>


            <!-- Category 3 -->

            <div class="col-6 col-md-4 col-lg-2">

                <a href="menu.php?category=Rice"
                   style="text-decoration:none;color:inherit;">

                    <div class="category-card">

                        <div class="category-image">

                            <img src="../../assets/img/rice.jpg"
                                 alt="Rice">

                        </div>

                        <h5>Rice</h5>

                        <p>Hearty meals</p>

                    </div>

                </a>

            </div>


            <!-- Category 4 -->

            <div class="col-6 col-md-4 col-lg-2">

                <a href="menu.php?category=Pasta"
                   style="text-decoration:none;color:inherit;">

                    <div class="category-card">

                        <div class="category-image">

                            <img src="../../assets/img/pasta.jpg"
                                 alt="Pasta">

                        </div>

                        <h5>Pasta</h5>

                        <p>Italian favourites</p>

                    </div>

                </a>

            </div>


            <!-- Category 5 -->

            <div class="col-6 col-md-4 col-lg-2">

                <a href="menu.php?category=Desserts"
                   style="text-decoration:none;color:inherit;">

                    <div class="category-card">

                        <div class="category-image">

                            <img src="../../assets/img/dessert.jpg"
                                 alt="Desserts">

                        </div>

                        <h5>Desserts</h5>

                        <p>Sweet treats</p>

                    </div>

                </a>

            </div>


            <!-- Category 6 -->

            <div class="col-6 col-md-4 col-lg-2">

                <a href="menu.php?category=Drinks"
                   style="text-decoration:none;color:inherit;">

                    <div class="category-card">

                        <div class="category-image">

                            <img src="../../assets/img/drinks.jpg"
                                 alt="Drinks">

                        </div>

                        <h5>Drinks</h5>

                        <p>Cool & refreshing</p>

                    </div>

                </a>

            </div>

        </div>

    </div>

</section>



<!-- =====================================
     POPULAR FOOD
===================================== -->

<section class="section" id="popular">

    <div class="container">

        <div class="row align-items-end mb-5">

            <div class="col-md-8">

                <div class="section-label">
                    Customer Favourites
                </div>

                <h2 class="section-title">
                    Popular dishes
                </h2>

                <p class="section-description">
                    Some of the meals our customers keep coming back for.
                </p>

            </div>

            <div class="col-md-4 text-md-end mt-3 mt-md-0">

                <a href="menu.php"
                   class="btn-primary-custom">

                    View Full Menu

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>


        <div class="row g-4">


            <!-- FOOD 1 -->

            <div class="col-md-6 col-lg-3">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/clssic_burger.jpg"
                             alt="Urban Classic Burger">

                        <span class="popular-badge">
                            POPULAR
                        </span>

                    </div>

                    <div class="food-content">

                        <h5>
                            Urban Classic Burger
                        </h5>

                        <p class="food-description">

                            Juicy beef patty, fresh lettuce,
                            cheese and our signature sauce.

                        </p>

                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 1,250
                            </span>

                            <button class="add-btn"
                                    title="Add to cart">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOD 2 -->

            <div class="col-md-6 col-lg-3">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/super_pizza.jpg"
                             alt="Chicken Supreme Pizza">

                        <span class="popular-badge">
                            BEST SELLER
                        </span>

                    </div>

                    <div class="food-content">

                        <h5>
                            Chicken Supreme Pizza
                        </h5>

                        <p class="food-description">

                            Tender chicken, vegetables,
                            mozzarella and rich pizza sauce.

                        </p>

                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 2,450
                            </span>

                            <button class="add-btn"
                                    title="Add to cart">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOD 3 -->

            <div class="col-md-6 col-lg-3">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/super_rice.jpg"
                             alt="Urban Special Fried Rice">

                    </div>

                    <div class="food-content">

                        <h5>
                            Urban Special Rice
                        </h5>

                        <p class="food-description">

                            Fragrant fried rice with chicken,
                            vegetables and a special seasoning.

                        </p>

                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 1,650
                            </span>

                            <button class="add-btn"
                                    title="Add to cart">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOD 4 -->

            <div class="col-md-6 col-lg-3">

                <div class="food-card">

                    <div class="food-image">

                        <img src="../../assets/img/super_pasta.jpg"
                             alt="Creamy Chicken Pasta">

                    </div>

                    <div class="food-content">

                        <h5>
                            Creamy Chicken Pasta
                        </h5>

                        <p class="food-description">

                            Creamy pasta with grilled chicken,
                            herbs and parmesan cheese.

                        </p>

                        <div class="food-bottom">

                            <span class="food-price">
                                Rs. 1,850
                            </span>

                            <button class="add-btn"
                                    title="Add to cart">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================
     PROMOTION
===================================== -->

<section class="promotion">

    <div class="container">

        <div class="promotion-box">

            <div class="promotion-content">

                <span class="small-label">
                    Limited Time Offer
                </span>

                <h2>
                    Your favourite food,
                    delivered for less.
                </h2>

                <p>
                    Order your favourite UrbanBites meals today
                    and enjoy special offers available for
                    selected orders.
                </p>

                <a href="menu.php"
                   class="btn-primary-custom">

                    Order Now

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>

    </div>

</section>



<!-- =====================================
     ABOUT URBANBITES
===================================== -->

<section class="section" id="about">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <div class="about-image">

                    <img src="../../assets/img/about-restaurant.avif"
                         alt="UrbanBites Restaurant">

                </div>

            </div>


            <div class="col-lg-6">

                <div class="about-content">

                    <div class="section-label">
                        About UrbanBites
                    </div>

                    <h2>
                        Great food made
                        for great moments.
                    </h2>

                    <p>
                        UrbanBites brings together delicious food,
                        quality ingredients and convenient ordering
                        in one modern restaurant experience.
                    </p>

                    <p>
                        Whether you're looking for a quick lunch,
                        a family dinner or something special for
                        the weekend, we've got something for you.
                    </p>


                    <div class="about-feature">

                        <i class="bi bi-check-lg"></i>

                        Fresh ingredients

                    </div>


                    <div class="about-feature">

                        <i class="bi bi-check-lg"></i>

                        Carefully prepared meals

                    </div>


                    <div class="about-feature">

                        <i class="bi bi-check-lg"></i>

                        Easy online ordering

                    </div>


                    <div class="about-feature">

                        <i class="bi bi-check-lg"></i>

                        Reliable delivery

                    </div>


                    <a href="menu.php"
                       class="btn-primary-custom mt-3">

                        Explore Our Menu

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================
     REVIEWS
===================================== -->

<section class="section testimonials"
         id="reviews">

    <div class="container">

        <div class="text-center mb-5">

            <div class="section-label">
                Customer Reviews
            </div>

            <h2 class="section-title">
                What our customers say
            </h2>

            <p class="section-description mx-auto">
                We believe great food should create great
                experiences.
            </p>

        </div>


        <div class="row g-4">


            <div class="col-md-4">

                <div class="review-card">

                    <div class="stars">

                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>

                    </div>

                    <p>
                        "The burger was amazing and the delivery
                        was surprisingly fast. Definitely ordering
                        again!"
                    </p>

                    <div class="review-user">

                        <div class="review-avatar">
                            AS
                        </div>

                        <div>

                            <h6>
                                Alex Silva
                            </h6>

                            <small>
                                Regular Customer
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="review-card">

                    <div class="stars">

                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>

                    </div>

                    <p>
                        "The ordering process is really simple.
                        Food arrived hot and everything was
                        exactly as ordered."
                    </p>

                    <div class="review-user">

                        <div class="review-avatar">
                            RM
                        </div>

                        <div>

                            <h6>
                                Rachel Morgan
                            </h6>

                            <small>
                                Customer
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="review-card">

                    <div class="stars">

                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>

                    </div>

                    <p>
                        "Great variety of food and the pizza is
                        one of the best I've tried. Highly
                        recommended."
                    </p>

                    <div class="review-user">

                        <div class="review-avatar">
                            DK
                        </div>

                        <div>

                            <h6>
                                Daniel Kumar
                            </h6>

                            <small>
                                Customer
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================
     CTA
===================================== -->

<section class="cta">

    <div class="container">

        <div class="cta-box">

            <h2>
                Hungry already?
            </h2>

            <p>
                Browse our menu, choose your favourites and
                place your order in just a few clicks.
            </p>

            <a href="menu.php"
               class="btn-white">

                Start Your Order

                <i class="bi bi-arrow-right ms-1"></i>

            </a>

        </div>

    </div>

</section>



<!-- =====================================
     FOOTER
===================================== -->

<footer id="contact">

    <div class="container">

        <div class="row g-5">


            <!-- Brand -->

            <div class="col-lg-5">

                <div class="footer-brand">
                    <img src="../../assets/img/main_logo.png" width="75px" height="60px" style="margin-top: -30px;">
                    Urban<span>Bites</span>
                </div>

                <p class="footer-description">

                    Delicious food, simple ordering and
                    reliable service. Experience the UrbanBites
                    difference.

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


            <!-- Quick Links -->

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
                        <a href="#about">
                            About
                        </a>
                    </li>

                    <li>
                        <a href="#reviews">
                            Reviews
                        </a>
                    </li>

                </ul>

            </div>


            <!-- Services -->

            <div class="col-6 col-lg-2">

                <h6 class="footer-title">
                    Services
                </h6>

                <ul class="footer-links">

                    <li>
                        <a href="menu.php">
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


            <!-- Contact -->

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



</body>

</html>