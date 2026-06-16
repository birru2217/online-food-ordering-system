<?php
session_start();
include 'includes/config.php';

$order = null;
$error = '';

if(isset($_POST['track']) || isset($_GET['order_number'])){
    $order_number = isset($_POST['order_number']) ? mysqli_real_escape_string($conn, $_POST['order_number']) : mysqli_real_escape_string($conn, $_GET['order_number']);
    
    $query = "SELECT o.*, u.fullname, u.email, u.phone 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.order_number = '$order_number'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0){
        $order = mysqli_fetch_assoc($result);
        
        // Get order items
        $items_query = mysqli_query($conn, "SELECT oi.*, p.name, p.image 
                                            FROM order_items oi 
                                            JOIN products p ON oi.product_id = p.id 
                                            WHERE oi.order_id = '{$order['id']}'");
    } else {
        $error = "Order not found! Please check your order number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - FoodOrder | Live Order Tracking</title>
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
        
        /* Hero Section */
        .track-hero {
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 50%, #2a1a3a 100%);
            padding: 80px 0 50px;
            position: relative;
            overflow: hidden;
        }
        
        .track-hero::before {
            content: '📍';
            position: absolute;
            font-size: 200px;
            opacity: 0.05;
            right: -50px;
            bottom: -80px;
        }
        
        /* Track Card */
        .track-card {
            background: white;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .track-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(255,107,107,0.15);
        }
        
        /* Status Timeline */
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        
        .status-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .step-icon {
            width: 60px;
            height: 60px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            transition: all 0.4s ease;
            position: relative;
            z-index: 2;
            font-size: 24px;
            color: #6c757d;
        }
        
        .step-icon.completed {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 5px 15px rgba(40,167,69,0.3);
            animation: pulse 2s infinite;
        }
        
        .step-icon.active {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            color: white;
            box-shadow: 0 5px 15px rgba(255,107,107,0.4);
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .step-label {
            font-weight: 700;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .step-desc {
            font-size: 11px;
            color: #6c757d;
        }
        
        .status-line {
            position: absolute;
            top: 40px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e9ecef;
            z-index: 1;
        }
        
        .status-line-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: linear-gradient(90deg, #28a745, #FF6B6B);
            transition: width 0.5s ease;
            border-radius: 3px;
        }
        
        /* Order Info Card */
        .info-card {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 20px;
            height: 100%;
            transition: all 0.3s;
        }
        
        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Search Box */
        .search-wrapper {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .search-input {
            width: 100%;
            padding: 15px 50px 15px 25px;
            border: 2px solid #e0e0e0;
            border-radius: 60px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #FF6B6B;
            box-shadow: 0 0 0 3px rgba(255,107,107,0.1);
        }
        
        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .search-btn:hover {
            transform: translateY(-50%) scale(1.02);
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
        
        .track-card {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .order-item {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
        }
        
        .order-item:nth-child(1) { animation-delay: 0.1s; }
        .order-item:nth-child(2) { animation-delay: 0.15s; }
        .order-item:nth-child(3) { animation-delay: 0.2s; }
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
                            <a class="nav-link text-white" href="cart.php">
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
                            <a class="nav-link text-white" href="login.php">Login</a>
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
    <div class="track-hero">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-3" data-aos="fade-up">Track Your <span class="text-warning">Order</span></h1>
            <p class="fs-4 mb-0 opacity-75" data-aos="fade-up" data-aos-delay="100">
                Real-time delivery tracking
            </p>
        </div>
    </div>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="track-card" data-aos="fade-up">
                    <div class="text-center mb-4">
                        <i class="fas fa-map-marker-alt fa-3x text-danger mb-2"></i>
                        <h2 class="fw-bold">Track Your Order</h2>
                        <p class="text-muted">Enter your order number to get real-time updates</p>
                    </div>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!$order): ?>
                        <!-- Search Form -->
                        <form method="POST">
                            <div class="search-wrapper">
                                <input type="text" name="order_number" class="search-input" 
                                       placeholder="🔍 Enter your order number (e.g., ORD202412011001)" required>
                                <button type="submit" name="track" class="search-btn">
                                    <i class="fas fa-search"></i> Track
                                </button>
                            </div>
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Example: ORD202412011001
                                </small>
                            </div>
                        </form>
                    <?php else: ?>
                        <!-- Order Found - Display Details -->
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle me-2"></i> 
                            <strong>Order #: <?php echo $order['order_number']; ?></strong>
                            <br>
                            <small>Placed on <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></small>
                        </div>
                        
                        <!-- Order Status Timeline -->
                        <div class="timeline mb-5">
                            <div class="status-line">
                                <div class="status-line-fill" style="width: 
                                    <?php 
                                        $status_percent = [
                                            'pending' => 25,
                                            'processing' => 60,
                                            'delivered' => 100
                                        ];
                                        echo $status_percent[$order['order_status']] ?? 0;
                                    ?>%;">
                                </div>
                            </div>
                            <div class="row">
                                <?php 
                                $statuses = [
                                    'pending' => ['icon' => 'clock', 'label' => 'Order Confirmed', 'desc' => 'Your order has been received'],
                                    'processing' => ['icon' => 'cogs', 'label' => 'Preparing', 'desc' => 'Chef is preparing your food'],
                                    'delivered' => ['icon' => 'check', 'label' => 'Delivered', 'desc' => 'Enjoy your meal!']
                                ];
                                $current_status = $order['order_status'];
                                $status_order = array_keys($statuses);
                                $current_index = array_search($current_status, $status_order);
                                ?>
                                <?php foreach($statuses as $key => $status): 
                                    $index = array_search($key, $status_order);
                                ?>
                                <div class="col-md-4 status-step">
                                    <div class="step-icon 
                                        <?php 
                                        if($index < $current_index) echo 'completed';
                                        elseif($index == $current_index) echo 'active';
                                        ?>">
                                        <i class="fas fa-<?php echo $status['icon']; ?>"></i>
                                    </div>
                                    <div class="step-label"><?php echo $status['label']; ?></div>
                                    <div class="step-desc"><?php echo $status['desc']; ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Estimated Delivery -->
                        <?php if($order['estimated_delivery_time'] && $order['order_status'] != 'delivered'): ?>
                            <div class="alert alert-warning text-center mb-4">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Estimated Delivery Time:</strong> <?php echo $order['estimated_delivery_time']; ?>
                                <i class="fas fa-motorcycle ms-2"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Order Items -->
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-receipt me-2 text-danger"></i> Order Items
                        </h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $subtotal = 0;
                                    while($item = mysqli_fetch_assoc($items_query)): 
                                        $item_subtotal = $item['quantity'] * $item['price'];
                                        $subtotal += $item_subtotal;
                                    ?>
                                    <tr class="order-item">
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-utensils text-muted"></i>
                                                <?php echo $item['name']; ?>
                                            </div>
                                        </td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo formatPrice($item['price']); ?></td>
                                        <td><?php echo formatPrice($item_subtotal); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                        <td><?php echo formatPrice($subtotal); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Delivery Fee:</td>
                                        <td><?php echo formatPrice($order['delivery_fee']); ?></td>
                                    </tr>
                                    <tr style="background: linear-gradient(135deg, #FF6B6B, #FFE66D); color: white;">
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="fw-bold"><?php echo formatPrice($order['grand_total']); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Delivery Information -->
                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <i class="fas fa-map-marker-alt fa-2x text-danger mb-2"></i>
                                    <h6 class="fw-bold mb-2">Delivery Address</h6>
                                    <p class="mb-0 small"><?php echo htmlspecialchars($order['delivery_address']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <i class="fas fa-credit-card fa-2x text-success mb-2"></i>
                                    <h6 class="fw-bold mb-2">Payment Method</h6>
                                    <p class="mb-0"><?php echo $order['payment_method']; ?></p>
                                    <small class="text-muted">Status: <?php echo ucfirst($order['payment_status']); ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mt-4 justify-content-center">
                            <a href="menu.php" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-utensils me-2"></i> Order More Food
                            </a>
                            <?php if($order['order_status'] == 'delivered'): ?>
                                <button class="btn btn-outline-warning rounded-pill px-4" onclick="alert('Thank you! Rate your experience on the app.')">
                                    <i class="fas fa-star me-2"></i> Rate Order
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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