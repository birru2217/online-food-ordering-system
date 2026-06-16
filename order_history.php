<?php
session_start();
include 'includes/config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC");

// Get order statistics
$total_orders = mysqli_num_rows($query);
$total_spent = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(grand_total),0) as total FROM orders WHERE user_id='$user_id' AND order_status='delivered'"))['total'];
$pending_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE user_id='$user_id' AND order_status='pending'"))['count'];
$delivered_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE user_id='$user_id' AND order_status='delivered'"))['count'];

// Reset pointer for main query
mysqli_data_seek($query, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - FoodOrder | Your Order History</title>
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
        .orders-hero {
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 50%, #2a1a3a 100%);
            padding: 80px 0 50px;
            position: relative;
            overflow: hidden;
        }
        
        .orders-hero::before {
            content: '📦';
            position: absolute;
            font-size: 200px;
            opacity: 0.05;
            right: -50px;
            bottom: -80px;
        }
        
        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255,107,107,0.15);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .stat-icon i {
            font-size: 24px;
            color: white;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 5px;
        }
        
        /* Order Cards */
        .order-card {
            background: white;
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
        }
        
        .order-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(255,107,107,0.15);
        }
        
        .order-header {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            background: linear-gradient(135deg, #f8f9fa, #fff);
        }
        
        .order-body {
            padding: 20px;
        }
        
        .status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        /* Empty State */
        .empty-state {
            background: white;
            border-radius: 30px;
            padding: 80px 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .order-btn {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border: none;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255,107,107,0.4);
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
        
        .order-card {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .order-card:nth-child(1) { animation-delay: 0.1s; }
        .order-card:nth-child(2) { animation-delay: 0.15s; }
        .order-card:nth-child(3) { animation-delay: 0.2s; }
        .order-card:nth-child(4) { animation-delay: 0.25s; }
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
                    <li class="nav-item">
                        <a class="nav-link text-white" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="order_history.php">
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
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="orders-hero">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-3" data-aos="fade-up">My <span class="text-warning">Order History</span></h1>
            <p class="fs-4 mb-0 opacity-75" data-aos="fade-up" data-aos-delay="100">
                Track and manage all your orders
            </p>
        </div>
    </div>
    
    <div class="container mt-5">
        <!-- Success Message -->
        <?php if(isset($_SESSION['order_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert" data-aos="fade-up">
                <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['order_success']; unset($_SESSION['order_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if($total_orders > 0): ?>
            <!-- Stats Cards -->
            <div class="row g-4 mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="stat-number"><?php echo $total_orders; ?></div>
                        <p class="text-muted mb-0">Total Orders</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-number">$<?php echo number_format($total_spent, 2); ?></div>
                        <p class="text-muted mb-0">Total Spent</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-number"><?php echo $pending_orders; ?></div>
                        <p class="text-muted mb-0">Pending Orders</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-number"><?php echo $delivered_orders; ?></div>
                        <p class="text-muted mb-0">Delivered</p>
                    </div>
                </div>
            </div>
            
            <!-- Orders List -->
            <div class="row g-4">
                <?php while($order = mysqli_fetch_assoc($query)): ?>
                    <div class="col-md-6">
                        <div class="order-card">
                            <div class="order-header">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <small class="text-muted">Order #</small>
                                        <h5 class="fw-bold mb-0"><?php echo $order['order_number']; ?></h5>
                                    </div>
                                    <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                        <i class="fas fa-<?php 
                                            echo $order['order_status'] == 'delivered' ? 'check-circle' : 
                                                ($order['order_status'] == 'cancelled' ? 'times-circle' : 
                                                    ($order['order_status'] == 'processing' ? 'spinner fa-pulse' : 'clock')); 
                                        ?>"></i>
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                </div>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-calendar-alt me-1"></i> 
                                    <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?>
                                </p>
                            </div>
                            
                            <div class="order-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Total Amount</small>
                                            <strong class="text-success fs-5"><?php echo formatPrice($order['grand_total']); ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Payment Method</small>
                                            <span>
                                                <i class="fas fa-credit-card me-1"></i>
                                                <?php echo $order['payment_method']; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block">Delivery Address</small>
                                    <p class="mb-0 small"><?php echo htmlspecialchars($order['delivery_address']); ?></p>
                                </div>
                                
                                <?php if($order['estimated_delivery_time']): ?>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Estimated Delivery Time</small>
                                        <span><i class="fas fa-clock me-1 text-warning"></i> <?php echo $order['estimated_delivery_time']; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <hr>
                                
                                <div class="d-flex gap-2">
                                    <a href="track_order.php?order_number=<?php echo $order['order_number']; ?>" 
                                       class="btn btn-outline-primary btn-sm flex-grow-1">
                                        <i class="fas fa-map-marker-alt me-1"></i> Track Order
                                    </a>
                                    <?php if($order['order_status'] == 'delivered'): ?>
                                        <button class="btn btn-outline-success btn-sm" onclick="alert('Thank you! Rate your order on the app.')">
                                            <i class="fas fa-star me-1"></i> Rate
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state" data-aos="fade-up">
                <div class="empty-icon mb-4">
                    <i class="fas fa-box-open fa-5x text-muted"></i>
                </div>
                <h3 class="fw-bold mb-3">No orders yet!</h3>
                <p class="text-muted fs-5 mb-4">Looks like you haven't placed any orders yet.</p>
                <a href="menu.php" class="btn order-btn text-white">
                    <i class="fas fa-utensils me-2"></i> Start Ordering
                </a>
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