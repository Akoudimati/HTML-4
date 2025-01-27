<?php
// Start de sessie om gebruikersgegevens en loginstatus te beheren
session_start();

// Probeer verbinding te maken met de database
try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
} catch (PDOException $e) {
    die("Fout bij verbinden met database: " . $e->getMessage()); // Als de verbinding mislukt, toon een foutmelding
}

// Initialiseer een variabele voor het succesbericht
$successMessage = '';

// Controleer of het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haal de gegevens uit het formulier op
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Bereid een SQL-instructie voor om het bericht in de database op te slaan
    $stmt = $db->prepare("INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);

    // Voer de SQL-instructie uit en controleer of deze succesvol is
    if ($stmt->execute()) {
        $successMessage = "Bericht succesvol verzonden!";
    } else {
        $successMessage = "Fout bij het verzenden van het bericht.";
    }
}

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
    <title>Contactpagina - Sport Review</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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


<div class="container mt-5">
    <h1>Contactinformatie</h1>

    <!-- Contactinformatie van het sportcentrum -->
    <div class="row">
        <div class="col-md-6">
            <h3>Sportcentrum HealthOne</h3>
            <p>Adres: Zuidhoornseweg 6a, 2635 DJ Den Hoorn</p>
            <p>Telefoon: 015 - 2578924</p>
            <p>Email: info@healthone.nl</p>
        </div>
        <div class="col-md-6">
            <h3>Openingstijden</h3>
            <ul>
                <li>Maandag: 7.00 – 20.00 uur</li>
                <li>Dinsdag: 8.00 – 20.00 uur</li>
                <li>Woensdag: 7.00 – 20.00 uur</li>
                <li>Donderdag: 8.00 – 20.00 uur</li>
                <li>Vrijdag: 7.00 – 20.30 uur</li>
                <li>Zaterdag: 8.00 – 13.00 uur</li>
                <li>Zondag: 8.00 – 13.00 uur</li>
            </ul>
        </div>
    </div>


    <!-- Google Maps Iframe -->
    <div class="mt-4">
        <h3>Locatie op de kaart</h3>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2462.2719426636823!2d4.344660416091546!3d51.98506597971432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5d0c09f761f45%3A0x76f2d5404f7d08e1!2sZuidhoornseweg%206a%2C%202635%20DJ%20Den%20Hoorn!5e0!3m2!1snl!2snl!4v1694184850409!5m2!1snl!2snl" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>


    <!-- Contactformulier -->
    <div class="mt-4">
        <h3>Contactformulier</h3>
        <form action="contact.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Naam</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Emailadres</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Bericht</label>
                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Verstuur</button>
        </form>
    </div>
</div>


<!-- Succesbericht onderaan de pagina -->
<?php if (!empty($successMessage)): ?>
    <div class="container mt-3">
        <div class="alert alert-success text-center" role="alert">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    </div>
<?php endif; ?>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
