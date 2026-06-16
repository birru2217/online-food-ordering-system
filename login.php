<?php
session_start();
include 'includes/config.php';

$error = '';

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: menu.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FoodOrder | Welcome Back</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 50%, #2a1a3a 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '🍕';
            position: absolute;
            font-size: 300px;
            opacity: 0.03;
            right: -50px;
            bottom: -100px;
        }
        
        body::after {
            content: '🍔';
            position: absolute;
            font-size: 250px;
            opacity: 0.03;
            left: -50px;
            top: -80px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(255,107,107,0.2);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo i {
            font-size: 60px;
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .logo h2 {
            font-weight: 800;
            margin-top: 10px;
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }
        
        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #FF6B6B;
            z-index: 2;
        }
        
        .input-group-custom input {
            padding-left: 45px;
            height: 55px;
            border-radius: 50px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
            background: white;
        }
        
        .input-group-custom input:focus {
            border-color: #FF6B6B;
            box-shadow: 0 0 0 3px rgba(255,107,107,0.1);
            outline: none;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            border: none;
            height: 55px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255,107,107,0.4);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .register-link a {
            color: #FF6B6B;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .floating-food {
            position: absolute;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-food-1 { top: 15%; left: 5%; font-size: 40px; animation-delay: 0s; }
        .floating-food-2 { bottom: 20%; right: 8%; font-size: 35px; animation-delay: 1s; }
        .floating-food-3 { top: 40%; right: 15%; font-size: 30px; animation-delay: 2s; }
        .floating-food-4 { bottom: 30%; left: 10%; font-size: 45px; animation-delay: 0.5s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        
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
        
        .login-card {
            animation: fadeInUp 0.6s ease-out forwards;
        }
    </style>
</head>
<body>
    <!-- Floating Food Emojis -->
    <div class="floating-food floating-food-1">🍕</div>
    <div class="floating-food floating-food-2">🍔</div>
    <div class="floating-food floating-food-3">🌮</div>
    <div class="floating-food floating-food-4">🍜</div>
    
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="login-card" data-aos="fade-up">
                    <div class="logo">
                        <i class="fas fa-utensils"></i>
                        <h2>FoodOrder</h2>
                        <p class="text-muted">Welcome back! Please login to your account</p>
                    </div>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" autocomplete="off">
                        <div class="input-group-custom">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-login text-white w-100">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </form>
                    
                  <div class="register-link">
                    <p>Don't have an account? <a href="register.php">Create Account</a></p>
                  </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>