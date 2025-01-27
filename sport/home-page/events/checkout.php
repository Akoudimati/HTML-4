<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sports");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['first_name']) || empty($_POST['last_name']) ||
        empty($_POST['email']) || empty($_POST['phone_number']) ||
        empty($_POST['postcode'])) {
        $error_message = "All fields are required";
    } else {
        $conn->begin_transaction();

        try {
            // Get only the most recent pending order for this user
            $check_order = $conn->prepare("SELECT order_id FROM orders WHERE user_id = ? AND status = 'pending' ORDER BY order_date DESC LIMIT 1");
            $check_order->bind_param("i", $_SESSION['user_id']);
            $check_order->execute();
            $order_result = $check_order->get_result();

            if ($order_result->num_rows > 0) {
                $order = $order_result->fetch_assoc();
                $order_id = $order['order_id'];

                // Insert customer details
                $insert_details = $conn->prepare("INSERT INTO customer_details (order_id, first_name, last_name, email, phone_number, postcode) VALUES (?, ?, ?, ?, ?, ?)");
                $insert_details->bind_param("isssss",
                    $order_id,
                    $_POST['first_name'],
                    $_POST['last_name'],
                    $_POST['email'],
                    $_POST['phone_number'],
                    $_POST['postcode']
                );
                $insert_details->execute();

                // Update only this specific order's status
                $update_order = $conn->prepare("UPDATE orders SET status = 'pending' WHERE order_id = ? AND user_id = ?");
                $update_order->bind_param("ii", $order_id, $_SESSION['user_id']);
                $update_order->execute();

                $conn->commit();
                header("Location: events.php");
                exit();
            } else {
                throw new Exception("No pending order found");
            }
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Error processing order: " . $e->getMessage();
        }
    }
}

// Get ONLY the most recent pending order details
$order_query = $conn->prepare("
    SELECT o.*, e.event_name, e.event_date 
    FROM orders o 
    JOIN events e ON o.event_id = e.event_id 
    WHERE o.user_id = ? AND o.status = 'pending'
    ORDER BY o.order_date DESC
    LIMIT 1
");
$order_query->bind_param("i", $_SESSION['user_id']);
$order_query->execute();
$result = $order_query->get_result();

// Redirect if no pending orders
if ($result->num_rows == 0) {
    header("Location: events.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/checkout.css">

</head>
<body>
<div class="top-banner">
    <div class="container">
        <h1 class="banner-title">Checkout</h1>
    </div>
</div>

<div class="container">
    <div class="checkout-container">
        <?php if ($error_message): ?>
            <div class="error">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <h3>Order Summary</h3>
        <table>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Quantity</th>
                <th>Total Price</th>
            </tr>
            <?php
            $total = 0;
            while($row = $result->fetch_assoc()):
                $total += $row['total_price'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                    <td><?php echo date('F j, Y, g:i a', strtotime($row['event_date'])); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="total">
            <i class="fas fa-tag me-2"></i>
            Total Amount: $<?php echo number_format($total, 2); ?>
        </div>

        <form method="POST">
            <h3>Personal Information</h3>
            <div class="form-group">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="tel" name="phone_number" placeholder="Phone Number" required>
                <input type="text" name="postcode" placeholder="Postcode" required>
            </div>

            <button type="submit">
                <i class="fas fa-lock me-2"></i>
                Complete Purchase
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>