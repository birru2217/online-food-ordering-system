<?php
session_start();
include 'includes/config.php';

$cart_total = 0;
$cart_items = [];

if(!empty($_SESSION['cart'])){
    $ids = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM products WHERE id IN ($ids)";
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_assoc($result)){
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $cart_total += $row['subtotal'];
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - FoodOrder | Your Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #f8f9fa;
        }
        
        /* Custom Navbar */
        .navbar {
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 8px;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: #FF6B6B !important;
            transform: translateY(-2px);
        }
        
        /* Cart Hero */
        .cart-hero {
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 50%, #2a1a3a 100%);
            padding: 80px 0 50px;
            position: relative;
            overflow: hidden;
        }
        
        .cart-hero::before {
            content: '🛒';
            position: absolute;
            font-size: 200px;
            opacity: 0.05;
            right: -50px;
            bottom: -80px;
        }
        
        /* Cart Table */
        .cart-table {
            background: white;
            border-radius: 24px;
            overflow: hidden;
        }
        
        .cart-table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-name {
            font-weight: 700;
            color: #333;
        }
        
        /* Quantity Controls */
        .quantity-control {
            display: inline-flex;
            align-items: center;
            background: #f0f2f5;
            border-radius: 50px;
            padding: 4px;
        }
        
        .quantity-btn {
            background: white;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #FF6B6B;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .quantity-btn:hover {
            background: #FF6B6B;
            color: white;
            transform: scale(1.05);
        }
        
        .quantity-number {
            padding: 0 15px;
            font-weight: 600;
            min-width: 40px;
            text-align: center;
        }
        
        /* Order Summary Card */
        .summary-card {
            background: white;
            border-radius: 24px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .summary-header {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            color: white;
            padding: 20px;
        }
        
        .checkout-btn {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border: none;
            padding: 15px;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255,107,107,0.4);
        }
        
        /* Empty Cart */
        .empty-cart {
            background: white;
            border-radius: 30px;
            padding: 80px 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .empty-cart-icon {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .browse-btn {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border: none;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .browse-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255,107,107,0.4);
        }
        
        /* Delivery Info Card */
        .delivery-card {
            background: linear-gradient(135deg, #0a0a2a, #1a1a3a);
            color: white;
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
        }
        
        /* Remove Button */
        .remove-btn {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 18px;
            transition: all 0.3s;
        }
        
        .remove-btn:hover {
            color: #ff4757;
            transform: scale(1.1);
        }
        
        /* Footer */
        .footer {
            background: #0a0a2a;
            color: white;
            padding: 60px 0 20px;
            margin-top: 60px;
        }
        
        .footer a {
            color: #aaa;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer a:hover {
            color: #FF6B6B;
            transform: translateX(5px);
            display: inline-block;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            transition: all 0.3s;
        }
        
        .social-icon:hover {
            background: #FF6B6B;
            transform: translateY(-3px);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .cart-item {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
        }
        
        .cart-item:nth-child(1) { animation-delay: 0.1s; }
        .cart-item:nth-child(2) { animation-delay: 0.15s; }
        .cart-item:nth-child(3) { animation-delay: 0.2s; }
        .cart-item:nth-child(4) { animation-delay: 0.25s; }
        
        /* Scroll to top */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 0 5px 20px rgba(255,107,107,0.4);
        }
        
        .scroll-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-top:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                🍔 FoodOrder
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="menu.php">
                            <i class="fas fa-utensils"></i> Menu
                        </a>
                    </li>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="cart.php">
                                <i class="fas fa-shopping-cart"></i> Cart
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="order_history.php">
                                <i class="fas fa-history"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-id-card"></i> My Profile</a></li>
                                <li><a class="dropdown-item" href="order_history.php"><i class="fas fa-shopping-bag"></i> My Orders</a></li>
                                <li><a class="dropdown-item" href="track_order.php"><i class="fas fa-truck"></i> Track Order</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn-login" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-register ms-2" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="cart-hero">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-3" data-aos="fade-up">Your <span class="text-warning">Shopping Cart</span></h1>
            <p class="fs-4 mb-0 opacity-75" data-aos="fade-up" data-aos-delay="100">
                <?php if(count($cart_items) > 0): ?>
                    You have <?php echo count($cart_items); ?> item(s) in your cart
                <?php else: ?>
                    Your cart is waiting for delicious food
                <?php endif; ?>
            </p>
        </div>
    </div>
    
    <div class="container mt-5">
        <?php if(empty($cart_items)): ?>
            <!-- Empty Cart -->
            <div class="empty-cart" data-aos="fade-up">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <h3 class="fw-bold mb-3">Your cart is empty!</h3>
                <p class="text-muted fs-5 mb-4">Looks like you haven't added any items to your cart yet.</p>
                <a href="menu.php" class="btn browse-btn text-white">
                    <i class="fas fa-utensils me-2"></i> Browse Menu
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-table" data-aos="fade-up">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="ps-3">Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($cart_items as $index => $item): ?>
                                        <tr class="cart-item">
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="images/<?php echo $item['image']; ?>" 
                                                         onerror="this.src='https://via.placeholder.com/80x80?text=🍕'">
                                                    <div>
                                                        <h6 class="product-name mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                        <small class="text-muted"><?php echo htmlspecialchars($item['category']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-success fw-bold"><?php echo formatPrice($item['price']); ?></td>
                                            <td>
                                                <div class="quantity-control">
                                                    <a href="update_cart.php?id=<?php echo $item['id']; ?>&action=decrease" class="quantity-btn">
                                                        <i class="fas fa-minus"></i>
                                                    </a>
                                                    <span class="quantity-number"><?php echo $item['quantity']; ?></span>
                                                    <a href="update_cart.php?id=<?php echo $item['id']; ?>&action=increase" class="quantity-btn">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="fw-bold"><?php echo formatPrice($item['subtotal']); ?></td>
                                            <td>
                                                <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" 
                                                   class="remove-btn"
                                                   onclick="return confirm('Remove this item from cart?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="summary-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="summary-header">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-receipt me-2"></i> Order Summary
                            </h5>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-bold"><?php echo formatPrice($cart_total); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Delivery Fee</span>
                                <span class="fw-bold"><?php echo formatPrice(DELIVERY_FEE); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                                <span class="text-muted">Tax (5%)</span>
                                <span class="fw-bold"><?php echo formatPrice($cart_total * 0.05); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mt-3 mb-4">
                                <span class="fs-5 fw-bold">Total</span>
                                <span class="fs-4 fw-bold text-success"><?php echo formatPrice($cart_total + DELIVERY_FEE + ($cart_total * 0.05)); ?></span>
                            </div>
                            
                            <?php if(isLoggedIn()): ?>
                                <a href="checkout.php" class="btn checkout-btn text-white w-100">
                                    <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            <?php else: ?>
                                <div class="alert alert-warning text-center mb-3">
                                    <i class="fas fa-info-circle me-2"></i> Please login to checkout
                                </div>
                                <a href="login.php" class="btn btn-primary w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Delivery Info -->
                    <div class="delivery-card" data-aos="fade-up" data-aos-delay="150">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-truck fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Fast Delivery</h6>
                                <small class="opacity-75">Estimated: 30-45 minutes</small>
                            </div>
                        </div>
                        <hr class="bg-white opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tag fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Free Delivery</h6>
                                <small class="opacity-75">On orders over $50</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Continue Shopping -->
                    <a href="menu.php" class="btn btn-outline-secondary w-100 mt-3 rounded-pill py-2">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h3 class="fw-bold mb-3">🍔 FoodOrder</h3>
                    <p class="text-muted">Delivering happiness to your doorstep since 2025. Fresh, fast, and delicious!</p>
                    <div class="mt-3">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php">Home</a></li>
                        <li class="mb-2"><a href="menu.php">Menu</a></li>
                        <li class="mb-2"><a href="track_order.php">Track Order</a></li>
                        <li class="mb-2"><a href="profile.php">My Account</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">FAQ</a></li>
                        <li class="mb-2"><a href="#">Terms & Conditions</a></li>
                        <li class="mb-2"><a href="#">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#">Refund Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold mb-3">Contact Info</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +251 911 234 567</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@foodorder.com</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Addis Ababa, Ethiopia</li>
                        <li class="mb-2"><i class="fas fa-clock me-2"></i> 9:00 AM - 11:00 PM</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 FoodOrdering System. All rights reserved. 🍕 Made with ❤️ for food lovers</p>
            </div>
        </div>
    </footer>
    
    <!-- Scroll to Top Button -->
    <div class="scroll-top" id="scrollTop">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        // Scroll to top
        const scrollTop = document.getElementById('scrollTop');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollTop.classList.add('show');
            } else {
                scrollTop.classList.remove('show');
            }
        });
        scrollTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(0, 0, 0, 0.98)';
                navbar.style.padding = '10px 0';
            } else {
                navbar.style.background = 'rgba(0, 0, 0, 0.95)';
                navbar.style.padding = '15px 0';
            }
        });
    </script>
</body>
</html>