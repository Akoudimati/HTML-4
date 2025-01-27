<?php
session_start();

// Database connection
try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for admin credentials
    if ($username === 'admin' && $password === '123') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = true; // Mark as admin
        header('Location: ../../index.php'); // Redirect to the homepage
        exit;
    }

    // Check for moderator credentials
    if ($username === 'mod' && $password === '123') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['is_mod'] = true; // Mark as moderator
        header('Location: ../../index.php'); // Redirect to the homepage
        exit;
    }

    // Prepare SQL query to find the user
    $query = $db->prepare("SELECT * FROM login_users WHERE name = :name");
    $query->execute([':name' => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Verify user and password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        header('Location: ../../index.php');
        exit;
    } else {
        echo "<script>alert('Invalid username or password. Please try again.');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 5rem auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 1rem;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            margin-bottom: 1rem;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header class="bg-dark border-bottom">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">Sport center</div>
            <nav>
                <a href="../../index.php" class="text-decoration-none text-secondary me-3">Sportcenter</a>
                <a href="../../home-page/contact/contact.php" class="text-decoration-none text-secondary me-3">Contact</a>
                <a href="../../home-page/log-in/log-in.php" class="text-decoration-none text-secondary me-3">Login</a>
                <a href="../../home-page/log-in/register.php" class="btn btn-outline-danger">Register</a>
            </nav>
        </div>
    </div>
</header>
<div class="login-container">
    <h2>Login</h2>
    <form action="log-in.php" method="post">
        <input type="text" name="username" placeholder="Gebruikersnaam" required>
        <input type="password" name="password" placeholder="Wachtwoord" required>
        <button type="submit">Log In</button><br>
        <div class="login-link">
            Heb je geen account? <a href="../../home-page/log-in/register.php">Registreer</a>
        </div>
    </form>
</div>
</body>
</html>
