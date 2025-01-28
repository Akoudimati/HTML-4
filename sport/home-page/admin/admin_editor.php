<?php
// Start de sessie
session_start();

// Controleer of de admin is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php"); // Doorsturen naar de inlogpagina als niet ingelogd als admin
    exit;
}

// Maak verbinding met de database
try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Zet foutmeldingen aan voor debugging
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage()); // Toon foutmelding als verbinding mislukt
}

// Bepaal de huidige categorie uit de URL of standaard naar 'homepage'
$current_category = isset($_GET['category']) ? $_GET['category'] : 'homepage';

// Koppel categorieën aan tabellen in de database
$category_tables = [
    'homepage' => 'homepage',
    'offline_esports' => 'offline_esports',
    'online_esport' => 'online_esport',
    'teamsporten' => 'teamsporten'
];

// Controleer of de opgegeven categorie geldig is
if (!array_key_exists($current_category, $category_tables)) {
    $current_category = 'homepage'; // Standaard naar 'homepage' bij ongeldige categorie
}

$current_table = $category_tables[$current_category]; // Zet de huidige tabelnaam

// Verwerk het formulier voor het toevoegen van een nieuwe kaart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_card'])) {
    try {
        $stmt = $db->prepare("INSERT INTO $current_table (titel, description, img, button) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['title'], // Titel van de kaart
            $_POST['description'], // Beschrijving van de kaart
            $_POST['image'], // Afbeeldings-URL
            $_POST['button'] // Link-URL
        ]);
        $message = "Kaart succesvol toegevoegd aan $current_category!";
    } catch (PDOException $e) {
        $error = "Fout bij toevoegen van kaart: " . $e->getMessage();
    }
}

// Verwerk het verwijderen van een kaart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_card'])) {
    try {
        $stmt = $db->prepare("DELETE FROM $current_table WHERE id = ?");
        $stmt->execute([$_POST['card_id']]);
        $deleteMessage = "Kaart succesvol verwijderd!";
    } catch (PDOException $e) {
        $error = "Fout bij verwijderen van kaart: " . $e->getMessage();
    }
}

// Haal bestaande kaarten op voor de huidige categorie
try {
    $query = $db->prepare("SELECT * FROM $current_table");
    $query->execute();
    $cards = $query->fetchAll(PDO::FETCH_ASSOC); // Sla alle kaarten op in een array
} catch (PDOException $e) {
    $error = "Fout bij ophalen van kaarten: " . $e->getMessage();
}

// Controleer of de gebruiker is ingelogd
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kaartenbeheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Header -->
<header class="bg-dark border-bottom">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center">
            <?php if ($isLoggedIn): ?>
                <div class="text-white">Welkom, <?= htmlspecialchars($_SESSION['username']) ?> </div>
            <?php else: ?>
                <div class="logo text-white">Sport Center</div>
            <?php endif; ?>
            <nav>
                <a href="../../index.php" class="text-decoration-none text-secondary me-3">Sportcenter</a>
                <a href="../../home-page/contact/contact.php" class="text-decoration-none text-secondary me-3">Contact</a>

                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                    <a href="../../home-page/admin/admin_messages.php" class="text-decoration-none text-secondary me-3">User Messages</a>
                    <a href="../../home-page/admin/admin_editor.php" class="text-decoration-none text-secondary me-3">Admin Room</a>
                <?php endif; ?>

                <?php if (!$isLoggedIn): ?>
                    <a href="../../home-page/log-in/log-in.php" class="text-decoration-none text-secondary me-3">Login</a>
                    <a href="../../home-page/log-in/register.php" class="btn btn-outline-danger">Register</a>
                <?php else: ?>
                    <a href="?action=logout" class="btn btn-outline-danger">Log Out</a>
                    <a href="../../home-page/log-in/user_profiel.php" class="btn btn-outline-primary">My Profiel</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

<div class="container mt-5">
    <h1 class="mb-4">Admin Kaartenbeheer</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (isset($deleteMessage)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($deleteMessage); ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Categorie Navigatie met Terug-knop -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3>Selecteer Categorie</h3>
            <div class="btn-group">
                <a href="?category=homepage" class="btn <?= $current_category == 'homepage' ? 'btn-primary' : 'btn-outline-primary' ?>">Homepage</a>
                <a href="?category=offline_esports" class="btn <?= $current_category == 'offline_esports' ? 'btn-primary' : 'btn-outline-primary' ?>">Offline Esports</a>
                <a href="?category=online_esport" class="btn <?= $current_category == 'online_esport' ? 'btn-primary' : 'btn-outline-primary' ?>">Online Esports</a>
                <a href="?category=teamsporten" class="btn <?= $current_category == 'teamsporten' ? 'btn-primary' : 'btn-outline-primary' ?>">Teamsporten</a>
            </div>
        </div>
        <div>
            <a href="../../index.php" class="btn btn-secondary">Ga Terug</a>
        </div>
    </div>

    <div class="row">
        <!-- Formulier voor Toevoegen van Nieuwe Kaart -->
        <div class="col-md-5">
            <h2 class="mb-4">Nieuwe Kaart Toevoegen aan <?= ucfirst($current_category) ?></h2>
            <form method="POST" action="?category=<?= $current_category ?>">
                <div class="mb-3">
                    <label for="title" class="form-label">Titel</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Beschrijving</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Afbeeldings-URL</label>
                    <input type="text" name="image" id="image" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="button" class="form-label">Button Link</label>
                    <input type="text" name="button" id="button" class="form-control" required>
                </div>

                <button type="submit" name="add_card" class="btn btn-success">Kaart Toevoegen</button>
            </form>
        </div>

        <!-- Lijst met Bestaande Kaarten -->
        <div class="col-md-7">
            <h2 class="mb-4">Bestaande Kaarten in <?= ucfirst($current_category) ?></h2>
            <div class="list-group">
                <?php if (empty($cards)): ?>
                    <p class="alert alert-info">Geen kaarten gevonden in deze categorie.</p>
                <?php else: ?>
                    <?php foreach ($cards as $card): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong><?= htmlspecialchars(!empty($card['titel']) ? $card['titel'] : 'Naamloze Kaart'); ?></strong><br>
                                <small><?= htmlspecialchars(substr($card['description'] ?? '', 0, 100) . '...'); ?></small>
                            </div>
                            <form method="POST" action="?category=<?= $current_category ?>" style="display:inline;">
                                <input type="hidden" name="card_id" value="<?= $card['id']; ?>">
                                <button type="submit" name="delete_card" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Weet je zeker dat je deze kaart wilt verwijderen?');">
                                    Verwijder
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>