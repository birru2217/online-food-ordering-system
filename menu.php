<?php
session_start();
if(isset($_GET['set_currency'])){
    $_SESSION['currency'] = $_GET['set_currency'];
    // Remove the parameter and redirect to clean URL
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}
include 'includes/config.php';

// Handle search and filters
$where = "WHERE is_available = 1";
$search = '';
$category = '';

if(isset($_GET['search']) && !empty($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND name LIKE '%$search%'";
}

if(isset($_GET['category']) && !empty($_GET['category'])){
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $where .= " AND category = '$category'";
}

$query = "SELECT * FROM products $where ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Get categories
$cat_query = mysqli_query($conn, "SELECT DISTINCT category FROM products WHERE category IS NOT NULL");

// Get cart count
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - FoodOrder | Delicious Food Delivery</title>
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
            position: relative;
        }
        
        .nav-link:hover {
            color: #FF6B6B !important;
            transform: translateY(-2px);
        }
        
        .cart-badge {
            background: #FF6B6B;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 11px;
            margin-left: 5px;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Hero Section */
        .menu-hero {
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 50%, #2a1a3a 100%);
            padding: 80px 0 50px;
            position: relative;
            overflow: hidden;
        }
        
        .menu-hero::before {
            content: '🍕';
            position: absolute;
            font-size: 200px;
            opacity: 0.05;
            right: -50px;
            bottom: -80px;
            transform: rotate(-15deg);
        }
        
        .menu-hero::after {
            content: '🍔';
            position: absolute;
            font-size: 150px;
            opacity: 0.05;
            left: -50px;
            top: -80px;
            transform: rotate(15deg);
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
            border: none;
            border-radius: 60px;
            font-size: 1rem;
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .search-input:focus {
            outline: none;
            box-shadow: 0 8px 30px rgba(255,107,107,0.2);
            transform: translateY(-2px);
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
        
        /* Category Pills */
        .category-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin: 30px 0;
        }
        
        .category-pill {
            background: white;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }
        
        .category-pill:hover {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255,107,107,0.3);
            border-color: transparent;
        }
        
        .category-pill.active {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            color: white;
            box-shadow: 0 8px 20px rgba(255,107,107,0.3);
            border-color: transparent;
        }
        
        /* Food Cards */
        .food-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            height: 100%;
            position: relative;
        }
        
        .food-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 45px rgba(255,107,107,0.15);
        }
        
        .card-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            color: white;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .food-img {
            height: 240px;
            object-fit: cover;
            width: 100%;
            transition: transform 0.5s ease;
        }
        
        .food-card:hover .food-img {
            transform: scale(1.05);
        }
        
        .price-tag {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            color: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            display: inline-block;
        }
        
        .btn-add-cart {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-add-cart:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(255,107,107,0.4);
        }
        
        /* Rating Stars */
        .rating {
            color: #FFD700;
            font-size: 13px;
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
        
        /* Loading Animation */
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
        
        .food-card {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .food-card:nth-child(1) { animation-delay: 0.1s; }
        .food-card:nth-child(2) { animation-delay: 0.15s; }
        .food-card:nth-child(3) { animation-delay: 0.2s; }
        .food-card:nth-child(4) { animation-delay: 0.25s; }
        .food-card:nth-child(5) { animation-delay: 0.3s; }
        .food-card:nth-child(6) { animation-delay: 0.35s; }
        .food-card:nth-child(7) { animation-delay: 0.4s; }
        .food-card:nth-child(8) { animation-delay: 0.45s; }
        
        /* Scroll to top button */
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
                        <a class="nav-link text-white active" href="menu.php">
                            <i class="fas fa-utensils"></i> Menu
                        </a>
                    </li>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="cart.php">
                                <i class="fas fa-shopping-cart"></i> Cart
                                <?php if($cart_count > 0): ?>
                                    <span class="cart-badge"><?php echo $cart_count; ?></span>
                                <?php endif; ?>
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
<!-- Currency Switcher -->

<li class="nav-item dropdown">

    <button class="btn btn-outline-secondary dropdown-toggle ms-2"
            type="button"
            data-bs-toggle="dropdown">

        💰 Currency

    </button>

    <ul class="dropdown-menu dropdown-menu-end">

        <li>

            <a class="dropdown-item"
               href="?set_currency=USD">

               💵 US Dollar ($)

            </a>

        </li>

        <li>

            <a class="dropdown-item"
               href="?set_currency=ETB">

               🇪🇹 Ethiopian Birr (Br)

            </a>

        </li>

    </ul>

</li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="menu-hero">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-3" data-aos="fade-up">Our <span class="text-warning">Delicious</span> Menu</h1>
            <p class="fs-4 mb-0 opacity-75" data-aos="fade-up" data-aos-delay="100">Freshly prepared just for you 🍕</p>
        </div>
    </div>
    
    <div class="container mt-4">
        <!-- Search Bar -->
        <div class="row justify-content-center mb-5" data-aos="fade-up" data-aos-delay="150">
            <div class="col-md-8">
                <div class="search-wrapper">
                    <form method="GET">
                        <input type="text" name="search" class="search-input" 
                               placeholder="🔍 Search for delicious food..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Categories Filter -->
        <div class="category-wrapper" data-aos="fade-up" data-aos-delay="200">
            <a href="menu.php" class="category-pill <?php echo !$category ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> All
            </a>
            <?php while($cat = mysqli_fetch_assoc($cat_query)): ?>
                <a href="?category=<?php echo urlencode($cat['category']); ?>" class="category-pill <?php echo $category == $cat['category'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat['category']); ?>
                </a>
            <?php endwhile; ?>
        </div>
        
        <!-- Results Count -->
        <div class="text-center mb-4" data-aos="fade-up" data-aos-delay="250">
            <span class="badge bg-dark px-3 py-2 rounded-pill">
                <i class="fas fa-utensils me-1"></i> 
                <?php echo mysqli_num_rows($result); ?> items found
            </span>
        </div>
        
        <!-- Food Grid -->
        <div class="row g-4">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="food-card">
                            <div class="card-badge">
                                <i class="fas fa-fire"></i> Hot Deal
                            </div>
                            <img src="images/<?php echo $row['image']; ?>" 
                                 class="food-img" 
                                 onerror="this.src='https://via.placeholder.com/300x240?text=🍕+Food+Image'">
                            <div class="p-3">
                                <div class="rating mb-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="text-muted ms-1">(4.5)</span>
                                </div>
                                <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="text-muted small mb-2">
                                    <?php echo substr(htmlspecialchars($row['description']), 0, 55); ?>...
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                 <span class="price-badge"><?php echo formatPrice($row['price']); ?></span>
                                    <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn-add-cart text-white text-decoration-none text-center" style="width: auto; padding: 8px 18px;">
                                        <i class="fas fa-cart-plus"></i> Add
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-5x text-muted mb-4"></i>
                    <h3 class="fw-bold">No food items found!</h3>
                    <p class="text-muted fs-5">Try a different search or category.</p>
                    <a href="menu.php" class="btn btn-primary mt-3 rounded-pill px-4 py-2">
                        <i class="fas fa-eye"></i> View All Menu
                    </a>
                </div>
            <?php endif; ?>
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
                        <li class="mb-2"><a href="#">About Us</a></li>
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
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
        
        // Scroll to top functionality
        const scrollTop = document.getElementById('scrollTop');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollTop.classList.add('show');
            } else {
                scrollTop.classList.remove('show');
            }
        });
        
        scrollTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
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
        
        // Add to cart animation
        document.querySelectorAll('.btn-add-cart').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                
                // Create flying animation
                const cart = document.querySelector('.cart-badge');
                if (cart) {
                    cart.style.transform = 'scale(1.3)';
                    setTimeout(() => {
                        cart.style.transform = 'scale(1)';
                    }, 300);
                }
                
                // Redirect after animation
                setTimeout(() => {
                    window.location.href = url;
                }, 200);
            });
        });
    </script>
</body>
</html>