<?php
session_start();
include 'includes/config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// Get user details
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);

// Update profile
if(isset($_POST['update_profile'])){
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $update_query = "UPDATE users SET fullname='$fullname', phone='$phone', address='$address' WHERE id='$user_id'";
    
    if(mysqli_query($conn, $update_query)){
        $_SESSION['user_name'] = $fullname;
        $message = "Profile updated successfully!";
        $message_type = "success";
        // Refresh user data
        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));
    } else {
        $message = "Update failed: " . mysqli_error($conn);
        $message_type = "danger";
    }
}

// Change password
if(isset($_POST['change_password'])){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    
    if(empty($current) || empty($new) || empty($confirm)){
        $message = "Please fill all password fields!";
        $message_type = "danger";
    } elseif($new !== $confirm){
        $message = "New passwords don't match!";
        $message_type = "danger";
    } elseif(strlen($new) < 4){
        $message = "Password must be at least 4 characters!";
        $message_type = "danger";
    } else {
        if(password_verify($current, $user['password'])){
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            if(mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE id='$user_id'")){
                $message = "Password changed successfully!";
                $message_type = "success";
            } else {
                $message = "Failed to update password!";
                $message_type = "danger";
            }
        } else {
            $message = "Current password is incorrect!";
            $message_type = "danger";
        }
    }
}

// Get order stats
$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE user_id='$user_id'"))['count'];
$total_spent = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(grand_total),0) as total FROM orders WHERE user_id='$user_id' AND order_status='delivered'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - FoodOrder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #f0f2f5;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .profile-avatar i {
            font-size: 50px;
            color: #667eea;
        }
        
        .info-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .form-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
        }
        
        .form-card-body {
            padding: 25px;
        }
        
        .btn-update {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 10px 25px;
            font-weight: 500;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">🍔 FoodOrder</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="order_history.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="track_order.php">Track</a></li>
                    <li class="nav-item"><a class="nav-link active" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white ms-2" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4 py-4">
        <!-- Message Alert -->
        <?php if($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row g-4">
            <!-- Left Column - Profile Info -->
            <div class="col-md-4">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h4 class="mb-1"><?php echo htmlspecialchars($user['fullname']); ?></h4>
                        <p class="mb-0 opacity-75"><?php echo htmlspecialchars($user['email']); ?></p>
                        <small class="mt-2 d-block">
                            <i class="fas fa-calendar-alt"></i> Member since <?php echo date('M Y', strtotime($user['created_at'])); ?>
                        </small>
                    </div>
                    <div class="p-4">
                        <div class="info-item d-flex justify-content-between">
                            <span><i class="fas fa-phone text-primary"></i> Phone:</span>
                            <span class="fw-bold"><?php echo htmlspecialchars($user['phone']) ?: 'Not set'; ?></span>
                        </div>
                        <div class="info-item d-flex justify-content-between">
                            <span><i class="fas fa-map-marker-alt text-danger"></i> Address:</span>
                            <span class="text-end"><?php echo htmlspecialchars($user['address']) ?: 'Not set'; ?></span>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="order_history.php" class="btn btn-outline-primary">
                                <i class="fas fa-history"></i> My Orders
                            </a>
                            <a href="cart.php" class="btn btn-outline-success">
                                <i class="fas fa-shopping-cart"></i> My Cart
                            </a>
                            <a href="track_order.php" class="btn btn-outline-info">
                                <i class="fas fa-map-marker-alt"></i> Track Order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Edit Forms -->
            <div class="col-md-8">
                <!-- Statistics Row -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="stat-card">
                            <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
                            <h3 class="mb-0"><?php echo $order_count; ?></h3>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="stat-card">
                            <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                            <h3 class="mb-0">$<?php echo number_format($total_spent, 2); ?></h3>
                            <p class="text-muted mb-0">Total Spent</p>
                        </div>
                    </div>
                </div>
                
                <!-- Update Profile Form -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Profile Information</h5>
                    </div>
                    <div class="form-card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" name="fullname" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control bg-light" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Delivery Address</label>
                                <textarea name="address" class="form-control" rows="3" 
                                          placeholder="Enter your full delivery address"><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary btn-update">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Change Password Form -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h5 class="mb-0"><i class="fas fa-key me-2"></i> Change Password</h5>
                    </div>
                    <div class="form-card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-warning">
                                <i class="fas fa-lock"></i> Change Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">&copy; 2025 FoodOrdering System. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>