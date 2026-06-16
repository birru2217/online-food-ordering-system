<?php
session_start();
include 'includes/config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

// Calculate total
$cart_total = 0;
if(!empty($_SESSION['cart'])){
    $ids = implode(',', array_keys($_SESSION['cart']));
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");
    
    while($row = mysqli_fetch_assoc($query)){
        $cart_total += $row['price'] * $_SESSION['cart'][$row['id']];
    }
}
$grand_total = $cart_total + DELIVERY_FEE;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - FoodOrder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .checkout-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .order-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
        }
        .place-order-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">🍔 FoodOrder</a>
            <a href="cart.php" class="btn btn-outline-light">
                <i class="fas fa-arrow-left"></i> Back to Cart
            </a>
        </div>
    </nav>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="checkout-card">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">
                            <i class="fas fa-credit-card"></i> Checkout
                        </h2>
                        
                        <?php if(isset($_SESSION['order_error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['order_error']; unset($_SESSION['order_error']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="place_order.php">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-truck"></i> Delivery Details
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="<?php echo $user['fullname']; ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo $user['email']; ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Delivery Address *</label>
                                    <textarea name="address" class="form-control" rows="3" required><?php echo $user['address']; ?></textarea>
                                </div>
                            </div>
                            
                            <h5 class="fw-bold mb-3 mt-3">
                                <i class="fas fa-credit-card"></i> Payment Method
                            </h5>
                            
                            <div class="mb-3">
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="Cash">💵 Cash on Delivery</option>
                                    <option value="Telebirr">📱 Telebirr</option>
                                    <option value="Chapa">💳 Chapa</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Additional Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Any special requests?"></textarea>
                            </div>
                            
                            <div class="order-summary mt-4">
                                <h6 class="fw-bold">Order Summary</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span><?php echo formatPrice($cart_total); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Delivery Fee:</span>
                                    <span><?php echo formatPrice(DELIVERY_FEE); ?></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total Amount:</span>
                                    <span class="text-success"><?php echo formatPrice($grand_total); ?></span>
                                </div>
                            </div>
                            
                            <button type="submit" name="place_order" class="btn place-order-btn text-white w-100 mt-4">
                                <i class="fas fa-check-circle"></i> Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2025 FoodOrdering System. All rights reserved.</p>
    </footer>
</body>
</html>