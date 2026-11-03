<?php
// UrbanBites - Checkout Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout | UrbanBites</title>

    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">

    <style>
        body {
            font-family: "Trebuchet MS", Arial, sans-serif;
            background: #f8f9fa;
            color: #222;
        }

        .navbar {
            background: #ffffff;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: 800;
            color: #e63946 !important;
        }

        .nav-link {
            font-weight: 600;
            color: #333 !important;
            margin: 0 7px;
        }

        .nav-link:hover {
            color: #e63946 !important;
        }

        .cart-icon {
            font-size: 21px;
            color: #e63946;
        }

        /* Hero */
        .checkout-hero {
            min-height: 300px;
            background:
                linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),
                url("assets/images/menu-banner.jpg") center/cover no-repeat;

            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 60px;
        }

        .checkout-hero h1 {
            font-size: 52px;
            font-weight: 800;
        }

        .checkout-hero p {
            font-size: 18px;
            opacity: 0.9;
        }

        /* Main */
        .checkout-section {
            padding-bottom: 80px;
        }

        .checkout-card {
            background: white;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.07);
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 25px;
        }

        .section-title span {
            color: #e63946;
        }

        .form-label {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 50px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #e63946;
            box-shadow: 0 0 0 0.2rem rgba(230,57,70,0.12);
        }

        /* Order type */
        .order-type {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .order-option {
            flex: 1;
            position: relative;
        }

        .order-option input {
            display: none;
        }

        .order-option label {
            display: block;
            padding: 18px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            transition: 0.3s;
        }

        .order-option label:hover {
            border-color: #e63946;
        }

        .order-option input:checked + label {
            border-color: #e63946;
            background: #fff1f2;
            color: #e63946;
        }

        .order-option-icon {
            display: block;
            font-size: 28px;
            margin-bottom: 5px;
        }

        /* Payment */
        .payment-option {
            border: 2px solid #ddd;
            border-radius: 12px;
            padding: 17px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: 0.3s;
        }

        .payment-option:hover {
            border-color: #e63946;
        }

        .payment-option input {
            margin-right: 10px;
        }

        .payment-option.selected {
            border-color: #e63946;
            background: #fff1f2;
        }

        /* Summary */
        .summary-card {
            position: sticky;
            top: 100px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 13px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .food-name {
            font-weight: 700;
        }

        .food-quantity {
            color: #777;
            font-size: 14px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 24px;
            font-weight: 800;
            padding-top: 20px;
            margin-top: 15px;
            border-top: 2px solid #eee;
        }

        .summary-total span:last-child {
            color: #e63946;
        }

        .discount-row {
            color: #198754;
        }

        .place-order-btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: #e63946;
            color: white;
            font-size: 18px;
            font-weight: 800;
            margin-top: 20px;
            transition: 0.3s;
        }

        .place-order-btn:hover {
            background: #c92f3c;
            transform: translateY(-2px);
        }

        .secure-note {
            text-align: center;
            color: #777;
            font-size: 14px;
            margin-top: 15px;
        }

        /* Empty */
        .empty-checkout {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-checkout h2 {
            font-weight: 800;
            margin-bottom: 15px;
        }

        .empty-checkout p {
            color: #777;
            margin-bottom: 25px;
        }

        .menu-btn {
            display: inline-block;
            padding: 13px 25px;
            background: #e63946;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
        }

        .menu-btn:hover {
            background: #c92f3c;
            color: white;
        }

        /* Confirmation */
        .confirmation {
            display: none;
            text-align: center;
            padding: 70px 20px;
        }

        .confirmation-icon {
            width: 90px;
            height: 90px;
            background: #d1e7dd;
            color: #198754;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 45px;
            font-weight: bold;
        }

        .confirmation h2 {
            font-size: 38px;
            font-weight: 800;
        }

        .confirmation p {
            color: #666;
            font-size: 17px;
        }

        .order-number {
            display: inline-block;
            background: #f1f1f1;
            padding: 12px 22px;
            border-radius: 10px;
            font-weight: 800;
            margin: 15px 0 25px;
        }

        /* Footer */
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
            color: #aaa;
        }

        .footer-bottom {
            border-top: 1px solid #333;
            margin-top: 35px;
            padding-top: 20px;
            text-align: center;
            color: #777;
        }

        @media (max-width: 768px) {

            .checkout-hero h1 {
                font-size: 38px;
            }

            .order-type {
                flex-direction: column;
            }

            .checkout-card {
                padding: 22px;
            }

            .summary-card {
                position: static;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            <img src="../../assets/img/main_logo.png" width="75px" height="60px" style="margin-top: -30px;">
            UrbanBites
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar">

            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="menu.php">Menu</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php#about">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php#reviews">Reviews</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="menu.php">
                        Order Food
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a class="nav-link" href="cart.php">
                        🛒
                        <span id="cartCount" class="badge bg-danger">0</span>
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>


<!-- HERO -->
<section class="checkout-hero">

    <div>
        <h1>Checkout</h1>
        <p>Complete your order and enjoy delicious food.</p>
    </div>

</section>


<!-- CHECKOUT -->
<section class="checkout-section">

    <div class="container">

        <!-- Empty cart -->
        <div id="emptyCheckout" class="checkout-card empty-checkout">

            <h2>Your cart is empty</h2>

            <p>
                Add some delicious food from our menu before proceeding
                to checkout.
            </p>

            <a href="menu.php" class="menu-btn">
                Browse Menu
            </a>

        </div>


        <!-- Checkout content -->
        <div id="checkoutContent" style="display:none;">

            <div class="row g-4">

                <!-- LEFT SIDE -->
                <div class="col-lg-7">

                    <!-- Customer information -->
                    <div class="checkout-card">

                        <h2 class="section-title">
                            <span>01.</span> Customer Information
                        </h2>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    First Name
                                </label>

                                <input
                                    type="text"
                                    id="firstName"
                                    class="form-control"
                                    placeholder="Enter your first name"
                                    required>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Last Name
                                </label>

                                <input
                                    type="text"
                                    id="lastName"
                                    class="form-control"
                                    placeholder="Enter your last name"
                                    required>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    class="form-control"
                                    placeholder="you@example.com"
                                    required>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input
                                    type="tel"
                                    id="phone"
                                    class="form-control"
                                    placeholder="+94 7X XXX XXXX"
                                    required>

                            </div>

                        </div>

                    </div>


                    <!-- Order type -->
                    <div class="checkout-card">

                        <h2 class="section-title">
                            <span>02.</span> Order Type
                        </h2>

                        <div class="order-type">

                            <div class="order-option">

                                <input
                                    type="radio"
                                    name="orderType"
                                    id="delivery"
                                    value="delivery"
                                    checked
                                    onchange="changeOrderType()">

                                <label for="delivery">

                                    <span class="order-option-icon">
                                        🚚
                                    </span>

                                    Delivery

                                </label>

                            </div>


                            <div class="order-option">

                                <input
                                    type="radio"
                                    name="orderType"
                                    id="pickup"
                                    value="pickup"
                                    onchange="changeOrderType()">

                                <label for="pickup">

                                    <span class="order-option-icon">
                                        🏪
                                    </span>

                                    Pickup

                                </label>

                            </div>

                        </div>


                        <!-- Delivery information -->
                        <div id="deliveryFields">

                            <div class="mb-3">

                                <label class="form-label">
                                    Delivery Address
                                </label>

                                <textarea
                                    id="address"
                                    class="form-control"
                                    rows="3"
                                    placeholder="House number, street, area..."></textarea>

                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        City
                                    </label>

                                    <input
                                        type="text"
                                        id="city"
                                        class="form-control"
                                        placeholder="City">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Postal Code
                                    </label>

                                    <input
                                        type="text"
                                        id="postalCode"
                                        class="form-control"
                                        placeholder="Postal code">

                                </div>

                            </div>

                        </div>


                        <!-- Pickup information -->
                        <div id="pickupMessage"
                             style="display:none;">

                            <div class="alert alert-light border">

                                <strong>🏪 Restaurant Pickup</strong>

                                <p class="mb-0 mt-2">
                                    Your order will be prepared and kept
                                    ready for collection at the UrbanBites
                                    restaurant.
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- Payment -->
                    <div class="checkout-card">

                        <h2 class="section-title">
                            <span>03.</span> Payment Method
                        </h2>


                        <label
                            class="payment-option selected"
                            onclick="selectPayment(this)">

                            <input
                                type="radio"
                                name="payment"
                                value="card"
                                checked>

                            💳
                            <strong>Credit / Debit Card</strong>

                            <small class="d-block text-muted ms-4">
                                Secure online card payment
                            </small>

                        </label>


                        <label
                            class="payment-option"
                            onclick="selectPayment(this)">

                            <input
                                type="radio"
                                name="payment"
                                value="cash">

                            💵
                            <strong>Cash on Delivery</strong>

                            <small class="d-block text-muted ms-4">
                                Pay when your order arrives
                            </small>

                        </label>


                        <label
                            class="payment-option"
                            onclick="selectPayment(this)">

                            <input
                                type="radio"
                                name="payment"
                                value="pickup">

                            🏪
                            <strong>Pay at Restaurant</strong>

                            <small class="d-block text-muted ms-4">
                                Available for restaurant pickup
                            </small>

                        </label>

                    </div>


                    <!-- Additional notes -->
                    <div class="checkout-card">

                        <h2 class="section-title">
                            <span>04.</span> Order Notes
                        </h2>

                        <textarea
                            id="orderNotes"
                            class="form-control"
                            rows="4"
                            placeholder="Any special instructions? e.g. No onions, extra spicy..."></textarea>

                    </div>

                </div>


                <!-- RIGHT SIDE -->
                <div class="col-lg-5">

                    <div class="checkout-card summary-card">

                        <h2 class="section-title">
                            Your <span>Order</span>
                        </h2>


                        <div id="orderItems"></div>


                        <!-- Promo -->
                        <div class="mt-4">

                            <label class="form-label">
                                Promo Code
                            </label>

                            <div class="input-group">

                                <input
                                    type="text"
                                    id="promoCode"
                                    class="form-control"
                                    placeholder="Enter promo code">

                                <button
                                    class="btn btn-dark"
                                    type="button"
                                    onclick="applyPromo()">

                                    Apply

                                </button>

                            </div>

                            <small
                                id="promoMessage"
                                class="d-block mt-2">
                            </small>

                        </div>


                        <!-- Summary -->
                        <div class="mt-4">

                            <div class="summary-item">

                                <span>Subtotal</span>

                                <strong id="subtotal">
                                    Rs. 0
                                </strong>

                            </div>


                            <div class="summary-item">

                                <span>Delivery Fee</span>

                                <strong id="deliveryFee">
                                    Rs. 300
                                </strong>

                            </div>


                            <div
                                class="summary-item discount-row"
                                id="discountRow"
                                style="display:none;">

                                <span>Discount (10%)</span>

                                <strong id="discount">
                                    - Rs. 0
                                </strong>

                            </div>


                            <div class="summary-total">

                                <span>Total</span>

                                <span id="total">
                                    Rs. 0
                                </span>

                            </div>

                        </div>


                        <button
                            class="place-order-btn"
                            onclick="placeOrder()">

                            Place Order

                        </button>


                        <p class="secure-note">
                            🔒 Your order information is handled securely.
                        </p>


                        <div class="text-center mt-3">

                            <a
                                href="cart.php"
                                class="text-decoration-none">

                                ← Back to Cart

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Confirmation -->
        <div
            id="confirmation"
            class="checkout-card confirmation">

            <div class="confirmation-icon">
                ✓
            </div>

            <h2>
                Order Confirmed!
            </h2>

            <p>
                Thank you for ordering from UrbanBites.
                Your delicious meal is being prepared.
            </p>

            <div class="order-number">
                Order #<span id="orderNumber"></span>
            </div>

            <p>
                We have received your order successfully.
            </p>

            <a
                href="index.php"
                class="menu-btn">

                Back to Home

            </a>

            <a
                href="menu.php"
                class="btn btn-outline-dark ms-2">

                Order More Food

            </a>

        </div>

    </div>

</section>


<!-- FOOTER -->
<footer>

    <div class="container">

        <div class="row g-4">

            <div class="col-md-5">
                <img src="../../assets/img/main_logo.png" width="75px" height="60px" style="margin-top: -30px;">
                <h4>UrbanBites</h4>

                <p>
                    Delicious food, simple ordering and
                    a better dining experience.
                </p>

            </div>


            <div class="col-md-3">

                <h4>Quick Links</h4>

                <p>
                    <a
                        href="index.php"
                        class="text-decoration-none text-secondary">

                        Home

                    </a>
                </p>

                <p>
                    <a
                        href="menu.php"
                        class="text-decoration-none text-secondary">

                        Menu

                    </a>
                </p>

                <p>
                    <a
                        href="cart.php"
                        class="text-decoration-none text-secondary">

                        Cart

                    </a>
                </p>

            </div>


            <div class="col-md-4">

                <h4>Contact</h4>

                <p>📍 Colombo, Sri Lanka</p>
                <p>📞 +94 7X XXX XXXX</p>
                <p>✉ hello@urbanbites.com</p>

            </div>

        </div>


        <div class="footer-bottom">

            © 2026 UrbanBites. All Rights Reserved.

        </div>

    </div>

</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>



<script>

    let cart = JSON.parse(
        localStorage.getItem("urbanBitesCart")
    ) || [];

    let discountAmount = 0;
    let promoApplied = false;


    /*
    --------------------------------------------------
    INITIALIZE
    --------------------------------------------------
    */

    document.addEventListener("DOMContentLoaded", function() {

        updateCartCount();

        if (cart.length === 0) {

            document.getElementById("emptyCheckout")
                .style.display = "block";

            document.getElementById("checkoutContent")
                .style.display = "none";

        } else {

            document.getElementById("emptyCheckout")
                .style.display = "none";

            document.getElementById("checkoutContent")
                .style.display = "block";

            displayOrder();

        }

    });


    /*
    --------------------------------------------------
    CART COUNT
    --------------------------------------------------
    */

    function updateCartCount() {

        let count = cart.reduce(
            (total, item) => total + Number(item.quantity),
            0
        );

        document.getElementById("cartCount")
            .textContent = count;

    }


    /*
    --------------------------------------------------
    DISPLAY ORDER
    --------------------------------------------------
    */

    function displayOrder() {

        const orderItems =
            document.getElementById("orderItems");

        orderItems.innerHTML = "";

        cart.forEach(function(item) {

            const itemTotal =
                Number(item.price) * Number(item.quantity);

            const row = document.createElement("div");

            row.className = "summary-item";

            row.innerHTML = `

                <div>

                    <div class="food-name">
                        ${item.name}
                    </div>

                    <div class="food-quantity">
                        Qty: ${item.quantity}
                    </div>

                </div>

                <strong>
                    Rs. ${itemTotal.toLocaleString()}
                </strong>

            `;

            orderItems.appendChild(row);

        });

        updateSummary();

    }


    /*
    --------------------------------------------------
    SUBTOTAL
    --------------------------------------------------
    */

    function getSubtotal() {

        return cart.reduce(function(total, item) {

            return total +
                (Number(item.price) *
                Number(item.quantity));

        }, 0);

    }


    /*
    --------------------------------------------------
    SUMMARY
    --------------------------------------------------
    */

    function updateSummary() {

        const subtotal = getSubtotal();

        const orderType =
            document.querySelector(
                'input[name="orderType"]:checked'
            ).value;

        let deliveryFee =
            orderType === "delivery" ? 300 : 0;

        document.getElementById("subtotal")
            .textContent =
            "Rs. " + subtotal.toLocaleString();

        document.getElementById("deliveryFee")
            .textContent =
            "Rs. " + deliveryFee.toLocaleString();


        let total =
            subtotal +
            deliveryFee -
            discountAmount;

        if (total < 0) {
            total = 0;
        }

        document.getElementById("total")
            .textContent =
            "Rs. " + total.toLocaleString();

    }


    /*
    --------------------------------------------------
    ORDER TYPE
    --------------------------------------------------
    */

    function changeOrderType() {

        const type =
            document.querySelector(
                'input[name="orderType"]:checked'
            ).value;


        const deliveryFields =
            document.getElementById("deliveryFields");

        const pickupMessage =
            document.getElementById("pickupMessage");


        if (type === "delivery") {

            deliveryFields.style.display = "block";
            pickupMessage.style.display = "none";

        } else {

            deliveryFields.style.display = "none";
            pickupMessage.style.display = "block";

        }

        updateSummary();

    }


    /*
    --------------------------------------------------
    PAYMENT SELECTION
    --------------------------------------------------
    */

    function selectPayment(element) {

        document
            .querySelectorAll(".payment-option")
            .forEach(function(option) {

                option.classList.remove("selected");

            });

        element.classList.add("selected");

        element.querySelector("input").checked = true;

    }


    /*
    --------------------------------------------------
    PROMO CODE
    --------------------------------------------------
    */

    function applyPromo() {

        const code =
            document.getElementById("promoCode")
                .value
                .trim()
                .toUpperCase();

        const message =
            document.getElementById("promoMessage");

        const subtotal = getSubtotal();


        if (code === "URBAN10") {

            discountAmount =
                subtotal * 0.10;

            promoApplied = true;

            document.getElementById("discountRow")
                .style.display = "flex";

            document.getElementById("discount")
                .textContent =
                "- Rs. " +
                discountAmount.toLocaleString(
                    undefined,
                    {
                        maximumFractionDigits: 0
                    }
                );

            message.textContent =
                "✓ Promo code applied successfully.";

            message.className =
                "d-block mt-2 text-success";

            updateSummary();

        } else {

            discountAmount = 0;
            promoApplied = false;

            document.getElementById("discountRow")
                .style.display = "none";

            message.textContent =
                "Invalid promo code.";

            message.className =
                "d-block mt-2 text-danger";

            updateSummary();

        }

    }


    /*
    --------------------------------------------------
    VALIDATE ORDER
    --------------------------------------------------
    */

    function validateOrder() {

        const firstName =
            document.getElementById("firstName")
                .value.trim();

        const lastName =
            document.getElementById("lastName")
                .value.trim();

        const email =
            document.getElementById("email")
                .value.trim();

        const phone =
            document.getElementById("phone")
                .value.trim();


        if (
            firstName === "" ||
            lastName === "" ||
            email === "" ||
            phone === ""
        ) {

            alert(
                "Please complete all customer information."
            );

            return false;

        }


        const orderType =
            document.querySelector(
                'input[name="orderType"]:checked'
            ).value;


        if (orderType === "delivery") {

            const address =
                document.getElementById("address")
                    .value.trim();

            const city =
                document.getElementById("city")
                    .value.trim();


            if (
                address === "" ||
                city === ""
            ) {

                alert(
                    "Please enter your delivery address and city."
                );

                return false;

            }

        }


        return true;

    }


    /*
    --------------------------------------------------
    PLACE ORDER
    --------------------------------------------------
    */

    function placeOrder() {

        if (!validateOrder()) {
            return;
        }


        const orderType =
            document.querySelector(
                'input[name="orderType"]:checked'
            ).value;


        const paymentMethod =
            document.querySelector(
                'input[name="payment"]:checked'
            ).value;


        /*
        Demo restriction:
        Pickup should preferably use pay-at-restaurant,
        but this is only a portfolio demonstration.
        */


        const orderNumber =
            "UB" +
            Math.floor(
                100000 +
                Math.random() * 900000
            );


        /*
        In a real application this information
        would be sent to PHP and saved in MySQL.
        */

        const orderData = {

            order_number: orderNumber,

            customer: {

                first_name:
                    document.getElementById("firstName").value,

                last_name:
                    document.getElementById("lastName").value,

                email:
                    document.getElementById("email").value,

                phone:
                    document.getElementById("phone").value

            },

            order_type: orderType,

            payment_method: paymentMethod,

            address:
                document.getElementById("address").value,

            city:
                document.getElementById("city").value,

            postal_code:
                document.getElementById("postalCode").value,

            notes:
                document.getElementById("orderNotes").value,

            items: cart,

            subtotal: getSubtotal(),

            discount: discountAmount,

            total:
                getSubtotal() +
                (orderType === "delivery" ? 300 : 0) -
                discountAmount

        };


        console.log(
            "UrbanBites Order:",
            orderData
        );


        /*
        Clear cart after successful
        simulated order.
        */

        localStorage.removeItem(
            "urbanBitesCart"
        );


        document.getElementById("checkoutContent")
            .style.display = "none";

        document.getElementById("emptyCheckout")
            .style.display = "none";

        document.getElementById("confirmation")
            .style.display = "block";


        document.getElementById("orderNumber")
            .textContent = orderNumber;


        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

    }

</script>

</body>
</html>