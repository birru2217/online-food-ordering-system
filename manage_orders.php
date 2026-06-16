<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include '../includes/config.php';

$result = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-dark min-vh-100 p-0">
                <h4 class="text-white text-center py-4">Admin Panel</h4>
                <a href="dashboard.php" class="text-white d-block p-3 text-decoration-none">Dashboard</a>
                <a href="manage_products.php" class="text-white d-block p-3 text-decoration-none">Products</a>
                <a href="add_product.php" class="text-white d-block p-3 text-decoration-none">Add Product</a>
                <a href="manage_orders.php" class="text-white d-block p-3 bg-primary text-decoration-none">Orders</a>
                <a href="logout.php" class="text-white d-block p-3 text-decoration-none">Logout</a>
            </div>
            <div class="col-md-10 p-4">
                <h2>Manage Orders</h2>
                
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr><th>Order #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['order_number']; ?></td>
                                <td><?php echo $row['user_id']; ?></td>
                                <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                                <td><?php echo $row['payment_method']; ?></td>
                                <td>
                                    <select onchange="updateStatus(<?php echo $row['id']; ?>, this.value)" class="form-select form-select-sm">
                                        <option value="pending" <?php echo $row['order_status']=='pending'?'selected':''; ?>>Pending</option>
                                        <option value="processing" <?php echo $row['order_status']=='processing'?'selected':''; ?>>Processing</option>
                                        <option value="delivered" <?php echo $row['order_status']=='delivered'?'selected':''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $row['order_status']=='cancelled'?'selected':''; ?>>Cancelled</option>
                                    </select>
                                 </td>
                                <td>
                                    <a href="update_order.php?id=<?php echo $row['id']; ?>&status=delivered" class="btn btn-success btn-sm">Deliver</a>
                                    <a href="update_order.php?id=<?php echo $row['id']; ?>&status=cancelled" class="btn btn-danger btn-sm">Cancel</a>
                                 </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
    function updateStatus(orderId, status){
        window.location.href = `update_order.php?id=${orderId}&status=${status}`;
    }
    </script>
</body>
</html>