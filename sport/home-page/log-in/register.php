<?php
session_start();

// Database connection
try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}

// Process registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $query = $db->prepare("INSERT INTO login_users (name, email, password) VALUES (:name, :email, :password)");
        $success = $query->execute([
            ':name' => $username,
            ':email' => $email,
            ':password' => $hashed_password
        ]);

        if ($success) {
            echo "<script>alert('Registration successful! Redirecting to login page.');</script>";
            header('Location: log-in.php');
            exit;
        } else {
            echo "<script>alert('An error occurred. Please try again.');</script>";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=
::contentReference[oaicite:0]{index=0}1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
        }

        .register-container {
            background-color: white;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            margin: auto;
            margin-top: 50px;
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
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
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
                <a href="../../home-page/log-in/register.php" type="button" class="btn btn-outline-danger">Register</a>
            </nav>
        </div>
    </div>
</header>

<div class="register-container">
    <h2>Register</h2>
    <form action="register.php" method="post">
        <input type="text" name="username" placeholder="Gebruikersnaam" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Wachtwoord" required>
        <input type="password" name="confirm_password" placeholder="Bevestig wachtwoord" required>
        <button type="submit">Register</button>
    </form>
    <div class="login-link">
        Heb je al een account? <a href="../../home-page/log-in/log-in.php">Log in</a>
    </div>
</div>

</body>
</html>


