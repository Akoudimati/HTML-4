<?php
session_start();

$conn = new mysqli("localhost", "root", "", "sports");
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ../../index.php");
    exit;
}

$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: add_new_events.php");
}

// Handle Add/Edit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $image_url = $_POST['image_url'];
    $event_date = $_POST['event_date'];
    $price = $_POST['price'];
    $available_tickets = $_POST['available_tickets'];

    if (isset($_POST['event_id']) && $_POST['event_id'] !== '') {
        $sql = "UPDATE events SET event_name=?, event_description=?, image_url=?, event_date=?, price=?, available_tickets=? WHERE event_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdii", $event_name, $event_description, $image_url, $event_date, $price, $available_tickets, $_POST['event_id']);
    } else {
        $sql = "INSERT INTO events (event_name, event_description, image_url, event_date, price, available_tickets) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdi", $event_name, $event_description, $image_url, $event_date, $price, $available_tickets);
    }

    $stmt->execute();
    header("Location: add_new_events.php");
}

// Fetch event for editing
$editEvent = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM events WHERE event_id = $id");
    if ($result->num_rows > 0) {
        $editEvent = $result->fetch_assoc();
    }
}

// Get all events
$result = $conn->query("SELECT * FROM events");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/add_new_events.css">
    <header class="bg-dark border-bottom">
        <div class="container py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">Sport center</div>
                <nav>
                    <a href="../../index.php" class="text-decoration-none text-secondary me-3">Sportcenter</a>
                    <a href="../../home-page/contact/contact.php" class="text-decoration-none text-secondary me-3">Contact</a>
                    <?php if (!$isLoggedIn): ?>
                        <a href="../../home-page/log-in/log-in.php" class="text-decoration-none text-secondary me-3">Login</a>
                        <a href="../../home-page/log-in/register.php" type="button" class="btn btn-outline-danger">Register</a>
                    <?php else: ?>
                        <a href="?action=logout" class="btn btn-outline-danger">Log Out</a>
                        <a href="../../../sd23-p01-reviewyourexperience-k-a/home-page/log-in/user_profiel.php" class="btn btn-outline-primary">Mijn Profiel</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <style>
        .event-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .table-responsive {
            margin-top: 2rem;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><?= $editEvent ? 'Edit Event' : 'Add New Event' ?></h2>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="event_id" value="<?= $editEvent['event_id'] ?? '' ?>">
                <div class="col-md-6">
                    <label for="event_name" class="form-label">Event Name</label>
                    <input type="text" class="form-control" id="event_name" name="event_name" value="<?= htmlspecialchars($editEvent['event_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="image_url" class="form-label">Image URL</label>
                    <input type="url" class="form-control" id="image_url" name="image_url" value="<?= htmlspecialchars($editEvent['image_url'] ?? '') ?>" required>
                </div>
                <div class="col-12">
                    <label for="event_description" class="form-label">Event Description</label>
                    <textarea class="form-control" id="event_description" name="event_description" rows="3"><?= htmlspecialchars($editEvent['event_description'] ?? '') ?></textarea>
                </div>
                <div class="col-md-4">
                    <label for="event_date" class="form-label">Event Date</label>
                    <input type="datetime-local" class="form-control" id="event_date" name="event_date" value="<?= $editEvent['event_date'] ?? '' ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= $editEvent['price'] ?? '' ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="available_tickets" class="form-label">Available Tickets</label>
                    <input type="number" class="form-control" id="available_tickets" name="available_tickets" value="<?= $editEvent['available_tickets'] ?? '' ?>" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><?= $editEvent ? 'Update Event' : 'Add Event' ?></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Current Events</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Event Name</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Price</th>
                        <th>Available Tickets</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if ($row['image_url']): ?>
                                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['event_name']) ?>" class="event-image rounded">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['event_name']) ?></td>
                            <td><?= htmlspecialchars($row['event_description']) ?></td>
                            <td><?= $row['event_date'] ?></td>
                            <td>$<?= number_format($row['price'], 2) ?></td>
                            <td><?= $row['available_tickets'] ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="?edit=<?= $row['event_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?delete=<?= $row['event_id'] ?>" onclick="return confirm('Are you sure you want to delete this event?')" class="btn btn-danger btn-sm">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
