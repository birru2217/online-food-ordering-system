<?php
session_start();
include 'includes/config.php';

// Get some stats for the counter
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products WHERE is_available=1"))['count'];
$happy_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) as count FROM orders"))['count'];
$total_delivered = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE order_status='delivered'"))['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodOrder - Best Food Delivery in Town</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            overflow-x: hidden;
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
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: #FF6B6B;
            transition: width 0.3s;
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        .btn-login {
            background: transparent;
            border: 2px solid #FF6B6B;
            color: #FF6B6B;
            border-radius: 50px;
            padding: 8px 25px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: #FF6B6B;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border: none;
            border-radius: 50px;
            padding: 8px 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255,107,107,0.4);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 50%, #2a1a3a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        
        .hero-gradient-text {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D, #FF6B6B);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .floating-food {
            position: absolute;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-food-1 { top: 20%; right: 10%; width: 100px; animation-delay: 0s; }
        .floating-food-2 { bottom: 15%; left: 5%; width: 80px; animation-delay: 1s; }
        .floating-food-3 { top: 50%; right: 5%; width: 60px; animation-delay: 2s; }
        .floating-food-4 { bottom: 30%; left: 15%; width: 70px; animation-delay: 0.5s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        
        /* Stats Counter */
        .stats-section {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            padding: 60px 0;
            margin-top: -50px;
            position: relative;
            z-index: 10;
            border-radius: 30px;
            margin-bottom: 60px;
        }
        
        .stat-item {
            text-align: center;
            color: white;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        
        /* Popular Categories */
        .category-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,107,107,0.1), transparent);
            transition: left 0.5s;
        }
        
        .category-card:hover::before {
            left: 100%;
        }
        
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(255,107,107,0.2);
        }
        
        .category-icon {
            font-size: 60px;
            margin-bottom: 20px;
            display: inline-block;
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* How It Works */
        .step-card {
            text-align: center;
            padding: 30px;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            font-weight: 800;
            color: white;
        }
        
        /* Testimonials */
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
        }
        
        .testimonial-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-right: 15px;
        }
        
        .rating {
            color: #FFD700;
            margin-bottom: 10px;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
            padding: 80px 0;
            border-radius: 30px;
            margin: 60px 0;
            position: relative;
            overflow: hidden;
        }
        
        .btn-cta {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .btn-cta:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(255,107,107,0.4);
        }
        
        /* Footer */
        .footer {
            background: #0a0a2a;
            color: white;
            padding: 60px 0 20px;
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
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                🍔 FoodOrder
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="menu.php">Menu</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link text-white" href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="order_history.php">My Orders</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link btn-login ms-2" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link btn-login" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link btn-register ms-2" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="hero">
        <div class="floating-food floating-food-1">🍕</div>
        <div class="floating-food floating-food-2">🍔</div>
        <div class="floating-food floating-food-3">🌮</div>
        <div class="floating-food floating-food-4">🍜</div>
        
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-7 text-white fade-in-up">
                    <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill">
                        <i class="fas fa-fire"></i> 50% OFF First Order
                    </span>
                    <h1 class="display-1 fw-bold mb-4">
                        Craving <span class="hero-gradient-text">Delicious</span><br>Food?
                    </h1>
                    <p class="fs-4 mb-4 opacity-75">Get your favorite meals delivered hot & fresh to your doorstep in 30 minutes or less!</p>
                    <div class="d-flex gap-3">
                        <a href="menu.php" class="btn btn-cta text-white">
                            Order Now <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="#features" class="btn btn-outline-light rounded-pill px-4">
                            Learn More <i class="fas fa-play ms-2"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <img src="https://cdn.dribbble.com/users/253035/screenshots/6655702/media/655a9ccb6a6f65ce45a789fabdc11808.gif" 
                         alt="Food Delivery" class="img-fluid" style="border-radius: 30px;">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Counter -->
    <div class="container">
        <div class="stats-section" data-aos="fade-up">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">
                            <span class="counter" data-target="<?php echo $total_products; ?>">0</span>+
                        </div>
                        <p class="mb-0">Delicious Dishes</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">
                            <span class="counter" data-target="<?php echo $happy_customers; ?>">0</span>k+
                        </div>
                        <p class="mb-0">Happy Customers</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">
                            <span class="counter" data-target="<?php echo $total_delivered; ?>">0</span>k+
                        </div>
                        <p class="mb-0">Orders Delivered</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">40<span>min</span></div>
                        <p class="mb-0">Fast Delivery</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Features Section -->
    <div class="container" id="features">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill">Why Choose Us</span>
            <h2 class="display-5 fw-bold">We Deliver <span class="text-danger">Happiness</span></h2>
            <p class="fs-5 text-muted">Experience the best food delivery service in town</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Lightning Fast Delivery</h4>
                    <p class="text-muted">Get your food delivered within 30 minutes. Hot, fresh, and right to your doorstep.</p>
                    <div class="mt-3">
                        <i class="fas fa-check-circle text-success"></i> Free delivery over $30
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Quality Ingredients</h4>
                    <p class="text-muted">We use only the freshest ingredients sourced from local farms and trusted suppliers.</p>
                    <div class="mt-3">
                        <i class="fas fa-check-circle text-success"></i> 100% Fresh guarantee
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Best Prices</h4>
                    <p class="text-muted">Enjoy delicious meals at affordable prices with amazing deals and discounts.</p>
                    <div class="mt-3">
                        <i class="fas fa-check-circle text-success"></i> 50% OFF first order
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- How It Works -->
    <div class="container mt-5 pt-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill">Simple Process</span>
            <h2 class="display-5 fw-bold">How It <span class="text-danger">Works</span></h2>
            <p class="fs-5 text-muted">Get your food in 3 easy steps</p>
        </div>
        
        <div class="row">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <i class="fas fa-utensils fa-3x text-danger mb-3"></i>
                    <h4 class="fw-bold">Choose Your Food</h4>
                    <p class="text-muted">Browse through our delicious menu and pick your favorite dishes</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <i class="fas fa-shopping-cart fa-3x text-danger mb-3"></i>
                    <h4 class="fw-bold">Place Your Order</h4>
                    <p class="text-muted">Add items to cart, checkout, and pay securely</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <i class="fas fa-truck fa-3x text-danger mb-3"></i>
                    <h4 class="fw-bold">Get Delivered</h4>
                    <p class="text-muted">Track your order and enjoy hot food at your doorstep</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Popular Categories -->
    <div class="container mt-5 pt-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill">Categories</span>
            <h2 class="display-5 fw-bold">Popular <span class="text-danger">Categories</span></h2>
            <p class="fs-5 text-muted">Explore our most loved food categories</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-2 col-6" data-aos="flip-left" data-aos-delay="100">
                <a href="menu.php?category=Pizza" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">🍕</div>
                        <h5 class="fw-bold mb-0">Pizza</h5>
                        <small class="text-muted">12+ items</small>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6" data-aos="flip-left" data-aos-delay="150">
                <a href="menu.php?category=Burger" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">🍔</div>
                        <h5 class="fw-bold mb-0">Burger</h5>
                        <small class="text-muted">8+ items</small>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6" data-aos="flip-left" data-aos-delay="200">
                <a href="menu.php?category=Pasta" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">🍝</div>
                        <h5 class="fw-bold mb-0">Pasta</h5>
                        <small class="text-muted">6+ items</small>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6" data-aos="flip-left" data-aos-delay="250">
                <a href="menu.php?category=Drinks" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">🥤</div>
                        <h5 class="fw-bold mb-0">Drinks</h5>
                        <small class="text-muted">10+ items</small>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6" data-aos="flip-left" data-aos-delay="300">
                <a href="menu.php?category=Salads" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">🥗</div>
                        <h5 class="fw-bold mb-0">Salads</h5>
                        <small class="text-muted">5+ items</small>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6" data-aos="flip-left" data-aos-delay="350">
                <a href="menu.php?category=Chinese" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">🥡</div>
                        <h5 class="fw-bold mb-0">Chinese</h5>
                        <small class="text-muted">8+ items</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Testimonials -->
    <div class="container mt-5 pt-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill">Testimonials</span>
            <h2 class="display-5 fw-bold">What Our <span class="text-danger">Customers Say</span></h2>
            <p class="fs-5 text-muted">Don't just take our word for it</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="testimonial-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">John Doe</h5>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">"Amazing food! Fast delivery and great customer service. Best food delivery app I've used!"</p>
                    <i class="fas fa-quote-right text-danger opacity-50"></i>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="testimonial-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Jane Smith</h5>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">"The pizza is incredible! Always fresh and hot. Highly recommend to everyone!"</p>
                    <i class="fas fa-quote-right text-danger opacity-50"></i>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="testimonial-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Mike Johnson</h5>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">"Great variety of food options. The delivery is always on time. Will order again!"</p>
                    <i class="fas fa-quote-right text-danger opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="container">
        <div class="cta-section" data-aos="zoom-in">
            <div class="text-center text-white">
                <h2 class="display-4 fw-bold mb-4">Ready to Order?</h2>
                <p class="fs-4 mb-4 opacity-75">Get 50% OFF on your first order!</p>
                <a href="menu.php" class="btn btn-cta text-white">
                    Order Now <i class="fas fa-arrow-right ms-2"></i>
                </a>
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
                        <li class="mb-2"><a href="#features">About Us</a></li>
                        <li class="mb-2"><a href="track_order.php">Track Order</a></li>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 1000,
            once: true
        });
        
        // Counter animation
        const counters = document.querySelectorAll('.counter');
        const speed = 200;
        
        counters.forEach(counter => {
            const updateCount = () => {
                const target = parseInt(counter.getAttribute('data-target'));
                const count = parseInt(counter.innerText);
                const increment = Math.ceil(target / speed);
                
                if (count < target) {
                    counter.innerText = count + increment;
                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = target;
                }
            }
            updateCount();
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