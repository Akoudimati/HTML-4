<?php
// Start the session
session_start();

// Log out logic
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ../../index.php");
    exit;
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Search functionality
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sort order
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'asc'; // Default to ascending
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Esport</title>
    <link rel="stylesheet" href="offline_Esport_style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            overflow: hidden;
            height: 100%;
            background-color: #fff;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
        }

        .card-img-top {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 1rem;
        }

        .card-text {
            font-size: 1rem;
            color: #636e72;
            line-height: 1.6;
            flex-grow: 1;
            margin-bottom: 1.5rem;
            max-height: 80px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #0984e3;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0770c5;
            transform: translateY(-2px);
        }

        .middle-image {
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .middle-image-container {
            padding: 50px;
            background-image: url("https://d33wubrfki0l68.cloudfront.net/b4759e96fa9ada8ee8caa4c771fcd503f289d791/6de77/static/triangle_background-9df4fa2e10f0e294779511e99083c2bc.jpg");
            text-align: center;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .middle-image-text {
            font-size: 4rem;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #f0f0f0;
        }

        .nav-link {
            color: #f0f0f0;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #0984e3;
        }

        .btn-outline-primary, .btn-outline-danger {
            border-width: 2px;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
        }

        .btn-outline-primary:hover, .btn-outline-danger:hover {
            transform: translateY(-2px);
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-input {
            border-radius: 20px;
            padding: 10px 20px;
            border: 2px solid #0984e3;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #0770c5;
            box-shadow: 0 0 0 0.2rem rgba(9, 132, 227, 0.25);
        }

        .btn-group {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .btn-group .btn {
            padding: 8px 16px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-group .btn:hover {
            transform: translateY(-1px);
        }

        .btn-group .btn-outline-primary {
            background-color: #fff;
        }

        .btn-group .btn-outline-primary:hover {
            background-color: #f8f9fa;
        }

        .filter-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<header class="bg-dark border-bottom">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">Sport Center</div>
            <nav>
                <a href="../../index.php" class="text-decoration-none text-secondary me-3">Sportcenter</a>
                <a href="../../home-page/contact/contact.php" class="text-decoration-none text-secondary me-3">Contact</a>
                <?php if ($isLoggedIn): ?>
                    <a href="?action=logout" class="btn btn-outline-danger">Logout</a>
                    <a href="../../../sd23-p01-reviewyourexperience-k-a/home-page/log-in/user_profiel.php" class="btn btn-outline-primary">My Profile</a>
                <?php else: ?>
                    <a href="../../home-page/log-in/log-in.php" class="text-decoration-none text-secondary me-3">Login</a>
                    <a href="../../home-page/log-in/register.php" type="button" class="btn btn-outline-danger">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

<main class="container p-3">
    <div class="row mb-4">
        <div class="col">
            <div class="bg-secondary text-white middle-image-container d-flex justify-content-center align-items-center">
                <div class="middle-image">
                    <span class="middle-image-text">Online Esport</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Container -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="" method="get" class="d-flex">
                <input
                        type="search"
                        name="search"
                        class="form-control search-input flex-grow-1 me-2"
                        placeholder="Search games..."
                        value="<?= htmlspecialchars($searchQuery) ?>"
                >
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="col-md-4">
            <select class="form-select" onchange="window.location.href=this.value">
                <option value="?<?= !empty($searchQuery) ? 'search=' . urlencode($searchQuery) . '&' : '' ?>sort=asc"
                    <?= $sortOrder !== 'desc' ? 'selected' : '' ?>>
                    A to Z
                </option>
                <option value="?<?= !empty($searchQuery) ? 'search=' . urlencode($searchQuery) . '&' : '' ?>sort=desc"
                    <?= $sortOrder === 'desc' ? 'selected' : '' ?>>
                    Z to A
                </option>
            </select>
        </div>
    </div>

    <div class="row row-cols-2 g-4">

        <?php
        // Database connection
        try {
            $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error!: " . $e->getMessage());
        }

        // Determine which table to use based on the current page
        $tableName = (strpos($_SERVER['PHP_SELF'], 'online_Esport') !== false) ? 'online_esport' : 'offline_esports';

        // Prepare the query with search functionality and sorting
        $query = "SELECT * FROM " . $tableName;

        // Add search condition if search query exists
        if (!empty($searchQuery)) {
            $query .= " WHERE titel LIKE :search";
        }

        // Add sorting
        $query .= " ORDER BY titel " . ($sortOrder === 'desc' ? 'DESC' : 'ASC');

        // Prepare and execute the statement
        $stmt = $db->prepare($query);

        if (!empty($searchQuery)) {
            $searchParam = "%{$searchQuery}%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }

        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if no games found
        if (empty($games)) {
            echo '<div class="col-12"><div class="alert alert-warning">No games found matching your search.</div></div>';
        }

        // Loop through the fetched data and display each game in a card
        foreach ($games as $game): ?>
            <div class="col">
                <div class="card">
                    <img src="<?= htmlspecialchars($game['img']) ?>" class="card-img-top" alt="<?= htmlspecialchars($game['titel']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($game['titel']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($game['description']) ?></p>
                        <?php if ($isLoggedIn): ?>
                            <div>
                                <a href="../../categories/online_Esport/review.php?id=<?= $game['id'] ?>" class="btn btn-primary">
                                    View Reviews
                                </a>
                            </div>
                        <?php else: ?>
                            <div>
                                <a href="../../home-page/log-in/log-in.php" class="btn btn-secondary">
                                    Login to View Reviews
                                </a>
                                <div class="login-prompt text-danger">
                                    Please log in to access reviews
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>






