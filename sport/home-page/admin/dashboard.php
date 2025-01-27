<?php
// Start een PHP-sessie om gebruikersinformatie te beheren
session_start();

// Controleer of de gebruiker is ingelogd en adminrechten heeft
if (!isset($_SESSION['loggedin']) || $_SESSION['is_admin'] !== true) {
    // Als de gebruiker niet is ingelogd of geen adminrechten heeft, doorverwijzen naar de inlogpagina
    header("Location: ../../home-page/log-in/log-in.php");
    exit;
}

// Maak een verbinding met de database
try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Zet de foutmeldingsmodus op uitzonderingen
} catch (PDOException $e) {
    // Toon een foutmelding als de verbinding mislukt
    die("Database connection failed: " . $e->getMessage());
}

// Haal het totaal aantal websitebezoekers op uit de 'users'-tabel
$totalVisitors = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Haal het totaal aantal evenementregistraties op uit de 'orders'-tabel
$totalRegistrations = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// Definieer de categorieën en de bijbehorende tabellen en URLs
$categories = [
    'Individuele sporten' => [
        'table' => 'individuele_sporten', // Tabelnaam in de database
        'url' => '../../categories/Individuele_sporten/sport4.php' // Link naar de categoriepagina
    ],
    'Teamsporten' => [
        'table' => 'teamsporten',
        'url' => '../../categories/Teamsporten/sport3.php'
    ],
    'Offline Esports' => [
        'table' => 'offline_esports',
        'url' => '../../categories/offline_Esport/offline_esport.php'
    ],
    'Online Esports' => [
        'table' => 'online_esport',
        'url' => '../../categories/online_Esport/sport1.php'
    ],
];

// Haal gegevens op voor elke categorie
$categoryData = [];
foreach ($categories as $categoryName => $info) {
    $query = $db->prepare("SELECT * FROM {$info['table']}"); // Query om gegevens uit de categorie-tabel te halen
    $query->execute(); // Voer de query uit
    $categoryData[$categoryName] = [
        'data' => $query->fetchAll(PDO::FETCH_ASSOC), // Bewaar de opgehaalde gegevens
        'url' => $info['url'] // Bewaar de URL van de categoriepagina
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS voor styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .info-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
        }
        .category-card {
            border: none;
            border-radius: 15px;
            background-color: #fff;
        }
        .category-table th {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>
<header class="bg-dark border-bottom">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-white">Admin Dashboard</h1>
            <nav>
                <!-- Navigatielinks -->
                <a href="../../index.php" class="text-decoration-none text-light me-3">Home</a>
                <a href="../../home-page/contact/contact.php" class="text-decoration-none text-light me-3">Contact</a>
                <a href="?action=logout" class="btn btn-outline-danger">Logout</a>
            </nav>
        </div>
    </div>
</header>

<div class="container my-4">
    <div class="row mb-4">
        <!-- Totaal aantal bezoekers -->
        <div class="col-md-6">
            <div class="card info-card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <h4>Total Website Visitors</h4>
                    <p class="fs-3"><?= htmlspecialchars($totalVisitors) ?> Visitors</p>
                    <a href="../../home-page/admin/statistics.php" class="btn btn-light">View Details</a>
                </div>
            </div>
        </div>

        <!-- Totaal aantal evenementregistraties -->
        <div class="col-md-6">
            <div class="card info-card bg-success text-white shadow-sm">
                <div class="card-body">
                    <h4>Total Event Registrations</h4>
                    <p class="fs-3"><?= htmlspecialchars($totalRegistrations) ?> Registrations</p>
                    <a href="../../home-page/events/events.php" class="btn btn-light">View Events</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Categorieoverzicht -->
    <h2 class="mb-4 text-center">Categorieën Overzicht</h2>
    <div class="row">
        <?php foreach ($categoryData as $categoryName => $info): ?>
            <div class="col-lg-6 mb-4">
                <div class="card category-card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h3 class="mb-0"><?= htmlspecialchars($categoryName) ?></h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($info['data'])): ?>
                            <!-- Toon een bericht als er geen sporten zijn in de categorie -->
                            <p class="text-muted">Geen sporten beschikbaar in deze categorie.</p>
                        <?php else: ?>
                            <table class="table category-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Naam</th>
                                    <th>Populariteit</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($info['data'] as $sport): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($sport['id']) ?></td>
                                        <!-- Gebruik 'sport_name' of 'titel', afhankelijk van de tabel -->
                                        <td><?= htmlspecialchars($sport['sport_name'] ?? $sport['titel']) ?></td>
                                        <td><?= htmlspecialchars($sport['popularity'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-end">
                        <!-- Link naar de categoriepagina -->
                        <a href="<?= htmlspecialchars($info['url']) ?>" class="btn btn-primary">
                            Bekijk <?= htmlspecialchars($categoryName) ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
