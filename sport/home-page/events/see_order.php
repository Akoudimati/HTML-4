<?php
require_once '../../home-page/log-in/header.php';
$conn = new mysqli("localhost", "root", "", "sports");

// Initialize error message variable
$error_message = '';
$success_message = '';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle order deletion
if (isset($_POST['delete_order'])) {
    $order_id = intval($_POST['order_id']);

    // Start transaction
    $conn->begin_transaction();
    try {
        $sql = "DELETE FROM customer_details WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $sql = "DELETE FROM orders WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $conn->commit();
        $success_message = "Order successfully deleted";
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Error deleting order: " . $e->getMessage();
    }
}

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['new_status'];

    // Validate status
    $allowed_statuses = ['pending', 'completed', 'cancelled'];
    if (in_array($new_status, $allowed_statuses)) {
        try {
            $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_status, $order_id);

            if ($stmt->execute()) {
                $success_message = "Status successfully updated";
            } else {
                $error_message = "Error updating status";
            }
        } catch (Exception $e) {
            $error_message = "Error updating status: " . $e->getMessage();
        }
    } else {
        $error_message = "Invalid status value";
    }
}

// Get all orders with user information
$sql = "SELECT o.*, e.event_name, e.event_date, u.name as user_name, u.email,
        cd.first_name, cd.last_name, cd.phone_number, cd.postcode
        FROM orders o 
        JOIN events e ON o.event_id = e.event_id 
        JOIN login_users u ON o.user_id = u.id 
        LEFT JOIN customer_details cd ON o.order_id = cd.order_id
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);

// Calculate statistics
$total_orders = $result->num_rows;
$total_revenue = 0;
$pending_orders = 0;
while($row = $result->fetch_assoc()) {
    $total_revenue += $row['total_price'];
    if ($row['status'] == 'pending') $pending_orders++;
}
$result->data_seek(0); // Reset result pointer
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Orders</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/see_order.css">
</head>
<body>
<div class="container-fluid">
    <!-- Display Messages -->
    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="dashboard-header">
        <h2 class="mb-0">Orders Management</h2>
    </div>

    <!-- Statistics Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <h6 class="text-muted">Total Orders</h6>
                <div class="stats-number"><?php echo $total_orders; ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h6 class="text-muted">Pending Orders</h6>
                <div class="stats-number"><?php echo $pending_orders; ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h6 class="text-muted">Total Revenue</h6>
                <div class="stats-number">$<?php echo number_format($total_revenue, 2); ?></div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Event</th>
                        <th>Event Date</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                            <td><?php echo $row['event_date']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>$<?php echo $row['total_price']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo htmlspecialchars($row['status']); ?>">
                                    <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Status Update Form -->
                                    <form method="POST" class="d-inline-flex align-items-center">
                                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                        <select name="new_status" class="form-select form-select-sm me-2">
                                            <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="completed" <?php echo $row['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo $row['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-action">Update</button>
                                    </form>

                                    <!-- View Details Button -->
                                    <button type="button" class="btn btn-info btn-action text-white"
                                            onclick="window.location.href='?view_details=<?php echo $row['order_id']; ?>'">
                                        View
                                    </button>

                                    <!-- Delete Form -->
                                    <form method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this order?');">
                                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                        <button type="submit" name="delete_order" class="btn btn-danger btn-action">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No orders found in the system.</div>
        <?php endif; ?>
    </div>

    <!-- Customer Details Section -->
    <?php if (isset($_GET['view_details'])):
        $detail_id = intval($_GET['view_details']);
        $result->data_seek(0);
        while($row = $result->fetch_assoc()) {
            if ($row['order_id'] == $detail_id):
                ?>
                <div class="detail-card mt-4">
                    <h3 class="mb-4">Customer Details</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-group">
                                <div class="detail-label">Name</div>
                                <div><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Email</div>
                                <div><?php echo htmlspecialchars($row['email']); ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Phone</div>
                                <div><?php echo htmlspecialchars($row['phone_number']); ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Postcode</div>
                                <div><?php echo htmlspecialchars($row['postcode']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-group">
                                <div class="detail-label">Event</div>
                                <div><?php echo htmlspecialchars($row['event_name']); ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Event Date</div>
                                <div><?php echo $row['event_date']; ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Quantity</div>
                                <div><?php echo $row['quantity']; ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Total Price</div>
                                <div>$<?php echo $row['total_price']; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Back to Orders</a>
                    </div>
                </div>
            <?php
            endif;
        }
    endif;
    ?>

    <!-- Manage Events Link -->
    <div class="mt-4">
        <a href="add_new_events.php" class="btn btn-primary">Manage Events</a>
    </div>
</div>

<!-- Add Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>