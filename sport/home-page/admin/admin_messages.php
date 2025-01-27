<?php
// Importeer de header (voor consistentie en sessiebeheer)
require_once '../../home-page/log-in/header.php';

// Maak een verbinding met de database
try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
} catch (PDOException $e) {
    // Als de verbinding mislukt, toon een foutmelding
    die("Database connection error: " . $e->getMessage());
}

// Controleer of de gebruiker is ingelogd en admin toegang heeft
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Als de gebruiker niet is ingelogd, doorverwijzen naar de inlogpagina
    header("Location: ../../home-page/log-in/log-in.php");
    exit;
}

// Haal alle berichten op uit de tabel 'contact_messages' en sorteer ze op aanmaakdatum (nieuwste eerst)
$query = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $query->fetchAll(PDO::FETCH_ASSOC); // Zet de resultaten om in een associatieve array
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages - Sport Review</title>
    <!-- Laad Bootstrap CSS voor de stijl van de pagina -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Container voor het weergeven van berichten -->
<div class="container my-4">
    <?php if (count($messages) > 0): ?>
        <!-- Als er berichten zijn, toon ze in een tabel -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>Date</th> <!-- Kolomkop voor de datum -->
                            <th>Name</th> <!-- Kolomkop voor de naam -->
                            <th>Email</th> <!-- Kolomkop voor het e-mailadres -->
                            <th>Message</th> <!-- Kolomkop voor het bericht -->
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($messages as $message): ?>
                            <!-- Loop door elk bericht en toon de gegevens -->
                            <tr>
                                <!-- Format de datum netjes en ontsmet de uitvoer voor beveiliging -->
                                <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($message['created_at']))) ?></td>
                                <td><?= htmlspecialchars($message['name']) ?></td>
                                <td><?= htmlspecialchars($message['email']) ?></td>
                                <!-- nl2br zorgt ervoor dat nieuwe regels in het bericht zichtbaar blijven -->
                                <td><?= nl2br(htmlspecialchars($message['message'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Als er geen berichten zijn, toon een informatieve melding -->
        <div class="alert alert-info">No messages found.</div>
    <?php endif; ?>
</div>

<!-- Laad Bootstrap JavaScript voor interactieve elementen -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
