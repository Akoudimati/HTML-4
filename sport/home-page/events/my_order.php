<?php
require_once '../../home-page/log-in/header.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "sports");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's orders
$sql = "SELECT o.order_id, o.quantity, o.total_price, o.order_date, o.status, 
               e.event_name, e.event_date 
        FROM orders o 
        JOIN events e ON o.event_id = e.event_id 
        WHERE o.user_id = ? 
        ORDER BY o.order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/my_order.css">
    <!-- Custom CSS -->
    <style>

    </style>
</head>
<body>
<div class="container">
    <div class="orders-container">
        <h2 class="page-title">My Orders</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th>Customer number</th>
                        <th>Event</th>
                        <th>Event Date</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()):
                        $statusClass = match(strtolower($row['status'])) {
                            'completed' => 'bg-success',
                            'pending' => 'bg-warning',
                            'cancelled' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                        ?>
                        <tr>
                            <td>#<?php echo $row['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                            <td><?php echo date('F j, Y, g:i A', strtotime($row['event_date'])); ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo date('F j, Y, g:i A', strtotime($row['order_date'])); ?></td>
                            <td><span class="status-badge <?php echo $statusClass; ?> text-white">
                                <?php echo ucfirst($row['status']); ?>
                            </span></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-orders">
                <h4>You haven't placed any orders yet.</h4>
                <p class="text-muted mt-2">Start exploring events and make your first purchase!</p>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="events.php" class="browse-link">Browse More Events</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>