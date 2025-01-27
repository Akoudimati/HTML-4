<?php
require_once '../../home-page/log-in/header.php';


// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: log-in.php'); // Als niet ingelogd, doorverwijzen naar de loginpagina
    exit;
}

// Maak verbinding met de database
try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout bij verbinden met database: " . $e->getMessage()); // Toon foutmelding als de verbinding mislukt
}

// Haal gebruikersgegevens op uit de database
$username = $_SESSION['username']; // Gebruikersnaam ophalen uit de sessie
$stmt = $db->prepare("SELECT * FROM login_users WHERE name = :username");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC); // Bewaar gebruikersinformatie in variabele

// Verwerk het formulier als het is ingediend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $field = $_POST['field']; // Haal het veld op dat moet worden bewerkt
    $value = $_POST['value']; // Haal de nieuwe waarde op voor dat veld

    // Update het opgegeven veld in de database
    $stmt = $db->prepare("UPDATE login_users SET $field = :value WHERE name = :username");
    $stmt->execute([':value' => $value, ':username' => $username]);

    // Vernieuw gebruikersgegevens
    $stmt = $db->prepare("SELECT * FROM login_users WHERE name = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Werk de sessie bij als de gebruikersnaam is gewijzigd
    if ($field == 'name') {
        $_SESSION['username'] = $value; // Sla de nieuwe naam op in de sessie
    }

    // Toon een melding en vernieuw de pagina
    echo "<script>
        alert('Profiel succesvol bijgewerkt!');
        window.location.href = '../../../sd23-p01-reviewyourexperience-k-a/home-page/log-in/user_profiel.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikersprofiel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-field {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="profile-container">
        <h2 class="text-center mb-4">Gebruikersprofiel</h2>
        <div class="profile-field">
            <strong>Gebruikersnaam:</strong> <?php echo htmlspecialchars($user['name']); ?>
            <button class="btn btn-sm btn-primary float-end" onclick="editField('name')">Bewerken</button>
        </div>
        <div class="profile-field">
            <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
            <button class="btn btn-sm btn-primary float-end" onclick="editField('email')">Bewerken</button>
        </div>
        <div class="profile-field">
            <strong>Wachtwoord:</strong> ********
            <button class="btn btn-sm btn-primary float-end" onclick="editField('password')">Wijzigen</button>
        </div>
        <a href="../../index.php" class="btn btn-secondary">Terug naar Home</a>
    </div>
</div>

<!-- Modal voor bewerken -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profiel Bewerken</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <input type="hidden" id="fieldName" name="field">
                    <div class="mb-3">
                        <label for="fieldValue" class="form-label">Nieuwe Waarde:</label>
                        <input type="text" class="form-control" id="fieldValue" name="value" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Wijzigingen Opslaan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Functie om het bewerken van een veld in te schakelen
    function editField(field) {
        document.getElementById('fieldName').value = field; // Sla het veld op in het formulier
        document.getElementById('fieldValue').value = ''; // Reset de invoerwaarde

        // Als het veld het wachtwoord is, wijzig het invoertype naar wachtwoord
        if (field === 'password') {
            document.getElementById('fieldValue').type = 'password';
        } else {
            document.getElementById('fieldValue').type = 'text';
        }

        // Toon de bewerk-modal
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }
</script>
</body>
</html>
