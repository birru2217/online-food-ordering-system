<?php
session_start();
include 'includes/config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(empty($_SESSION['cart'])){
    header("Location: menu.php");
    exit();
}

if(isset($_POST['place_order'])){
    $user_id = $_SESSION['user_id'];
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $payment_method = sanitize($_POST['payment_method']);
    $notes = isset($_POST['notes']) ? sanitize($_POST['notes']) : '';
    
    // Calculate cart total
    $cart_total = 0;
    $cart_items = [];
    
    if(!empty($_SESSION['cart'])){
        $ids = implode(',', array_keys($_SESSION['cart']));
        $query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");
        
        while($row = mysqli_fetch_assoc($query)){
            $qty = $_SESSION['cart'][$row['id']];
            $subtotal = $row['price'] * $qty;
            $cart_total += $subtotal;
            $cart_items[] = [
                'product' => $row,
                'quantity' => $qty,
                'subtotal' => $subtotal
            ];
        }
    }
    
    $grand_total = $cart_total + DELIVERY_FEE;
    
    // Generate order number
    $order_number = 'ORD' . date('Ymd') . rand(1000, 9999);
    $estimated_time = date('h:i A', strtotime('+45 minutes'));
    
    // Insert order
    $query = "INSERT INTO orders (order_number, user_id, total_amount, delivery_fee, grand_total, 
              payment_method, delivery_address, phone, notes, estimated_delivery_time, order_status) 
              VALUES ('$order_number', '$user_id', '$cart_total', '" . DELIVERY_FEE . "', '$grand_total', 
              '$payment_method', '$address', '$phone', '$notes', '$estimated_time', 'pending')";
    
    if(mysqli_query($conn, $query)){
        $order_id = mysqli_insert_id($conn);
        
        // Insert order items and update stock
        foreach($cart_items as $item){
            $product_id = $item['product']['id'];
            $quantity = $item['quantity'];
            $price = $item['product']['price'];
            
            // Insert order item
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                VALUES ('$order_id', '$product_id', '$quantity', '$price')");
            
            // Update stock (decrease quantity)
            mysqli_query($conn, "UPDATE products SET stock = stock - $quantity WHERE id = '$product_id'");
        }
        
        // Clear cart
        unset($_SESSION['cart']);
        
        $_SESSION['order_success'] = "Order placed successfully! Your order # is: $order_number";
        header("Location: order_history.php");
        exit();
    } else {
        $_SESSION['order_error'] = "Failed to place order: " . mysqli_error($conn);
        header("Location: checkout.php");
        exit();
    }
} else {
    header("Location: checkout.php");
    exit();
}
?>