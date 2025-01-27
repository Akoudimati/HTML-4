<?php
// Start de sessie om gebruikersgegevens en loginstatus te beheren
session_start();

// Uitlogfunctionaliteit als de URL 'action=logout' bevat
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy(); // Vernietig de sessie om uit te loggen
    header("Location: ../../index.php"); // Stuur de gebruiker door naar de startpagina
    exit;
}

// Controleer of de gebruiker is ingelogd door de sessiestatus te controleren
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Review</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>

</style>
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
                                <a href="../../../sd23-p01-reviewyourexperience-k-a/index.php" class="nav-link">Sportcenter</a>
                            </li>
                            <li class="nav-item">
                                <a href="../../../sd23-p01-reviewyourexperience-k-a/home-page/contact/contact.php" class="nav-link">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a href="../../../sd23-p01-reviewyourexperience-k-a/home-page/events/events.php" class="nav-link">Events</a>
                            </li>
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                                <!-- Dropdown voor Admin-links -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Admin Panel
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                        <li><a class="dropdown-item" href="../../../sd23-p01-reviewyourexperience-k-a/home-page/admin/dashboard.php">Dashboard</a></li>
                                        <li><a class="dropdown-item" href="../../../sd23-p01-reviewyourexperience-k-a/home-page/admin/notifications.php">Notifications</a></li>
                                        <li><a class="dropdown-item" href="../../../sd23-p01-reviewyourexperience-k-a/home-page/admin/statistics.php">Statistics</a></li>
                                        <li><a class="dropdown-item" href="../../../sd23-p01-reviewyourexperience-k-a/home-page/admin/admin_messages.php">User Messages</a></li>
                                        <li><a class="dropdown-item" href="../../../sd23-p01-reviewyourexperience-k-a/home-page/admin/admin_editor.php">Admin Room</a></li>
                                        <li><a class="dropdown-item" href="../../../sd23-p01-reviewyourexperience-k-a/home-page/events/add_new_events.php">Add Events</a></li>
                                        <li><a class="dropdown-item" href="../../../sd23-p01-reviewyourexperience-k-a/home-page/events/see_order.php">orders</a></li>
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
                                    </ul>
                                </li>
                            <?php endif; ?>

                            <?php if (!$isLoggedIn): ?>
                                <!-- Login- en registratielinks voor niet-ingelogde gebruikers -->
                                <li class="nav-item">
                                    <a href="../../../sd23-p01-reviewyourexperience-k-a/home-page/log-in/log-in.php" class="btn btn-outline-light me-2">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a href="../../../sd23-p01-reviewyourexperience-k-a/home-page/log-in/register.php" class="btn btn-danger">Register</a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a href="?action=logout" class="btn btn-danger me-2">Log Out</a>
                                </li>
                                <!-- Alleen tonen als gebruiker geen admin is -->
                                <?php if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true): ?>
                                    <li class="nav-item">
                                        <a href="../../../sd23-p01-reviewyourexperience-k-a/home-page/log-in/user_profiel.php" class="btn btn-primary me-2">My Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="../../../sd23-p01-reviewyourexperience-k-a/home-page/events/my_order.php" class="btn btn-outline-success me-2">Orders</a>
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
