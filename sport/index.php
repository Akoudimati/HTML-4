<?php
// Start de sessie om gebruikersgegevens op te slaan
session_start();

// Controleer of de gebruiker wil uitloggen
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy(); // Vernietig de sessie, gebruiker uitloggen
    header("Location: index.php"); // Doorsturen naar de startpagina
    exit; // Stop verdere uitvoering
}

// Maak verbinding met de database
try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage()); // Toon foutmelding als verbinding mislukt
}

// Haal gegevens van de homepagina op uit de database
$query = $db->prepare("SELECT * FROM homePage");
$query->execute();

// Sla de opgevraagde data op in een array
$cars = $query->fetchAll(PDO::FETCH_ASSOC);

// Controleer of de gebruiker is ingelogd
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Toon een waarschuwing als de gebruiker niet is ingelogd
if (!$isLoggedIn) {
    $alertMessage = "Je moet inloggen om toegang te krijgen tot deze pagina.";
} else {
    // Wis de waarschuwing als de gebruiker is ingelogd
    unset($alertMessage);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthOne Sportcenter</title>
    <!-- Bootstrap CSS voor een mooi en responsief ontwerp -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #f0f0f0;
        }
        .main-image {
            height: 300px;
            background-image: url('https://taashisartpractice.wordpress.com/wp-content/uploads/2021/08/9c1cbe9f-48f0-4e24-893c-310c938af88e.jpeg?w=2000&h=');
            background-size: cover;
            background-position: center;
        }
        .product-image {
            height: 200px;
            background-size: cover;
            background-position: center;
        }
        .card-text {
            height: 100px;
            overflow: hidden;
        }
    </style>
</head>
<header class="bg-dark border-bottom shadow-sm">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center">
            <?php if ($isLoggedIn): ?>
                <!-- Welkomstbericht voor ingelogde gebruikers -->
                <div class="text-white fw-bold">Welkom, <?= htmlspecialchars($_SESSION['username']) ?></div>
            <?php else: ?>
                <!-- Logo weergeven als de gebruiker niet is ingelogd -->
                <div class="logo text-white">Sport Center</div>
            <?php endif; ?>
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <!-- Algemene links -->
                            <li class="nav-item">
                                <a href="index.php" class="nav-link">Sportcenter</a>
                            </li>
                            <li class="nav-item">
                                <a href="home-page/contact/contact.php" class="nav-link">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a href="../sport/home-page/events/events.php" class="nav-link">Events</a>
                            </li>
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                                <!-- Dropdown voor Admin-links -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Admin Panel
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="adminDropdown">


                                        <li><a class="dropdown-item" href="home-page/admin/admin_messages.php">User Messages</a></li>
                                        <li><a class="dropdown-item" href="home-page/admin/admin_editor.php">Admin Room</a></li>
                                        <li><a class="dropdown-item" href="../sport/home-page/events/add_new_events.php">Add Events</a></li>
                                        <li><a class="dropdown-item" href="home-page/events/see_order.php">orders</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['is_mod']) && $_SESSION['is_mod'] === true): ?>
                                <!-- Dropdown voor Moderator-links -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="modDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Moderator Tools
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="modDropdown">
                                        <li><a class="dropdown-item" href="home-page/admin/admin_messages.php">User Messages</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>

                            <?php if (!$isLoggedIn): ?>
                                <!-- Login- en registratielinks voor niet-ingelogde gebruikers -->
                                <li class="nav-item">
                                    <a href="home-page/log-in/log-in.php" class="btn btn-outline-light me-2">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a href="home-page/log-in/register.php" class="btn btn-danger">Register</a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a href="?action=logout" class="btn btn-danger me-2">Log Out</a>
                                </li>
                                <!-- Alleen tonen als gebruiker geen admin is -->
                                <?php if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true): ?>
                                    <li class="nav-item">
                                        <a href="home-page/log-in/user_profiel.php" class="btn btn-primary me-2">My Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="home-page/events/my_order.php" class="btn btn-outline-success me-2">Orders</a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>


<body>


<main class="container my-4">
    <div class="main-image mb-4 rounded"></div>
    <h1 class="d-flex justify-content-center p-4">Categories</h1>

    <?php if (isset($alertMessage)): ?>
        <!-- Waarschuwingsbericht als de gebruiker niet is ingelogd -->

        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($alertMessage) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($cars as $car): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <!-- Productafbeelding instellen als achtergrondafbeelding -->
                    <div class="product-image card-img-top" style="background-image: url('<?= htmlspecialchars($car['img']) ?>');"></div>
                    <div class="card-body">
                        <!-- Producttitel en beschrijving weergeven     -->
                        <h5 class="card-title"><?= htmlspecialchars($car['titel']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($car['description']) ?></p>
                        <div class="d-flex justify-content-start">
                            <!-- Link naar de categoriepagina vanuit het 'button'-veld in de database -->
                            <a href="<?= htmlspecialchars($car['button']) ?>" class="btn btn-secondary">Meer Info</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Bootstrap JS (optioneel) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
