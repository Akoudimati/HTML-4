<?php
require_once '../../home-page/log-in/header.php';

$conn = new mysqli("localhost", "root", "", "sports");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $event_id = $_POST['event_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("SELECT price, available_tickets FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if ($event['available_tickets'] >= $quantity) {
        $total_price = $event['price'] * $quantity;

        $sql = "INSERT INTO orders (user_id, event_id, quantity, total_price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $_SESSION['user_id'], $event_id, $quantity, $total_price);

        if ($stmt->execute()) {
            $new_quantity = $event['available_tickets'] - $quantity;
            $sql = "UPDATE events SET available_tickets = ? WHERE event_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $new_quantity, $event_id);
            $stmt->execute();

            header("Location: ../../home-page/events/checkout.php");
        }
    }
}

$result = $conn->query("SELECT * FROM events WHERE available_tickets > 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Sports Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/events.css">    <style>

    </style>
</head>
<body>


<div class="container">
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="login-notice">
            <i class="fas fa-user-lock fa-2x mb-3"></i>
            <h3>Exclusive Access Required</h3>
            <p class="mb-0">Please <a href="../../home-page/log-in/log-in.php" class="login-link">sign in</a> to secure your premium tickets.</p>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-lg-6">
                <div class="event-container">
                    <?php if($row['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                             alt="<?php echo htmlspecialchars($row['event_name']); ?>"
                             class="event-image">
                    <?php endif; ?>

                    <div class="date-badge">
                        <i class="far fa-calendar-alt"></i>
                        <?php echo date('M j, Y', strtotime($row['event_date'])); ?>
                    </div>

                    <div class="event-content">
                        <h2 class="event-name"><?php echo htmlspecialchars($row['event_name']); ?></h2>
                        <p class="event-description"><?php echo htmlspecialchars($row['event_description']); ?></p>

                        <div class="event-details">
                            <div class="price">
                                <i class="fas fa-tag price-tag"></i>
                                $<?php echo number_format($row['price'], 2); ?>
                            </div>
                            <div class="tickets-info">
                                <i class="fas fa-ticket-alt me-2"></i>
                                <?php echo $row['available_tickets']; ?> tickets remaining
                            </div>
                        </div>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form method="POST" class="purchase-form">
                                <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                <input type="number"
                                       name="quantity"
                                       min="1"
                                       max="<?php echo $row['available_tickets']; ?>"
                                       value="1"
                                       class="form-control quantity-input">
                                <button type="submit" class="btn btn-purchase">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Reserve Now
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>