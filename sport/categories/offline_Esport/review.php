<?php
session_start();

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ../../index.php");
    exit;
}

// Database connection
try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage());
}

// Check if there's an ID for the game
if (isset($_GET['id'])) {
    $gameId = intval($_GET['id']);
    $reviewType = 'offline'; // Set to offline for this page

    // Handle star rating selection
    if (isset($_GET['set_rating'])) {
        $selectedRating = intval($_GET['set_rating']);
        if ($selectedRating >= 1 && $selectedRating <= 5) {
            $_SESSION['temp_rating'] = $selectedRating;
        }
        header("Location: review.php?id=" . $gameId . "&type=" . $reviewType);
        exit;
    }

    // Fetch game data
    $query = $db->prepare("SELECT * FROM offline_esports WHERE id = :id");
    $query->bindParam(':id', $gameId);
    $query->execute();
    $game = $query->fetch(PDO::FETCH_ASSOC);

    // Handle review submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        $rating = isset($_SESSION['temp_rating']) ? $_SESSION['temp_rating'] : null;
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if (!empty($comment) && !empty($rating)) {
            $insertQuery = $db->prepare("INSERT INTO offline_esport_reviews (game_id, user_id, comment, rating, created_at) 
                                       VALUES (:game_id, :user_id, :comment, :rating, NOW())");
            $insertQuery->bindParam(':game_id', $gameId);
            $insertQuery->bindParam(':comment', $comment);
            $insertQuery->bindParam(':rating', $rating);

            if ($userId !== null) {
                $insertQuery->bindParam(':user_id', $userId);
            } else {
                $insertQuery->bindValue(':user_id', null, PDO::PARAM_NULL);
            }

            $insertQuery->execute();
            unset($_SESSION['temp_rating']);

            header("Location: review.php?id=" . $gameId . "&type=" . $reviewType);
            exit;
        }
    }

    // Handle review deletion (for admins and moderators)
    if (isset($_GET['delete_review']) && (isset($_SESSION['is_admin']) || isset($_SESSION['is_mod']))) {
        $reviewId = intval($_GET['delete_review']);
        $deleteQuery = $db->prepare("DELETE FROM offline_esport_reviews WHERE id = :review_id");
        $deleteQuery->bindParam(':review_id', $reviewId);
        $deleteQuery->execute();

        header("Location: review.php?id=" . $gameId . "&type=" . $reviewType);
        exit;
    }

    // Handle like system
    if (isset($_POST['like_review'])) {
        $reviewId = intval($_POST['review_id']);
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($userId) {
            // Check if like exists
            $checkLike = $db->prepare("SELECT id FROM review_likes 
                                     WHERE review_id = :review_id 
                                     AND user_id = :user_id 
                                     AND review_type = 'offline'");
            $checkLike->bindParam(':review_id', $reviewId);
            $checkLike->bindParam(':user_id', $userId);
            $checkLike->execute();

            if ($checkLike->fetch()) {
                // Unlike
                $db->prepare("DELETE FROM review_likes 
                            WHERE review_id = :review_id 
                            AND user_id = :user_id 
                            AND review_type = 'offline'")
                    ->execute(['review_id' => $reviewId, 'user_id' => $userId]);

                $db->prepare("UPDATE offline_esport_reviews 
                            SET likes_count = likes_count - 1 
                            WHERE id = :review_id")
                    ->execute(['review_id' => $reviewId]);
            } else {
                // Like
                $db->prepare("INSERT INTO review_likes (review_id, user_id, review_type) 
                            VALUES (:review_id, :user_id, 'offline')")
                    ->execute(['review_id' => $reviewId, 'user_id' => $userId]);

                $db->prepare("UPDATE offline_esport_reviews 
                            SET likes_count = likes_count + 1 
                            WHERE id = :review_id")
                    ->execute(['review_id' => $reviewId]);
            }
        }
        header("Location: review.php?id=" . $gameId . "&type=" . $reviewType);
        exit;
    }

    // Fetch reviews with user information and like status
    $reviewsQuery = $db->prepare("
        SELECT r.*, l.name as username,
               CASE WHEN rl.id IS NOT NULL THEN 1 ELSE 0 END as user_has_liked
        FROM offline_esport_reviews r 
        LEFT JOIN login_users l ON r.user_id = l.id 
        LEFT JOIN review_likes rl ON r.id = rl.review_id 
            AND rl.user_id = :current_user_id 
            AND rl.review_type = 'offline'
        WHERE r.game_id = :game_id 
        ORDER BY r.likes_count DESC, r.created_at DESC
    ");

    $currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $reviewsQuery->bindParam(':current_user_id', $currentUserId);
    $reviewsQuery->bindParam(':game_id', $gameId);
    $reviewsQuery->execute();
    $reviews = $reviewsQuery->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: index.php");
    exit;
}

// Function to generate star rating HTML
function generateStarRating($currentRating = 0, $isInput = true, $gameId = null) {
    $html = '<div class="star-rating">';
    for ($i = 1; $i <= 5; $i++) {
        if ($isInput) {
            $html .= sprintf(
                '<a href="?id=%d&set_rating=%d" class="star %s">★</a>',
                $gameId,
                $i,
                ($i <= $currentRating ? 'selected' : '')
            );
        } else {
            $html .= sprintf(
                '<span class="star %s">★</span>',
                ($i <= $currentRating ? 'selected' : '')
            );
        }
    }
    $html .= '</div>';
    return $html;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review <?= htmlspecialchars($game['titel']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .star-rating { font-size: 24px; line-height: 1; margin: 10px 0; }
        .star { text-decoration: none; color: #e4e5e9; margin-right: 5px; transition: color 0.2s; }
        .star.selected { color: #ffd700; }
        .review-card { margin-bottom: 20px; border: 1px solid #ddd; border-radius: 8px; padding: 15px; background-color: white; }
        .like-button { background: none; border: none; cursor: pointer; padding: 5px 10px; }
        .like-button.liked i { color: #e74c3c; }
        body { background-color: #f0f0f0; }
    </style>
</head>
<body>
<!-- Header -->
<header class="bg-dark border-bottom">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo text-white">Sport Center</div>
            <nav>
                <a href="../../index.php" class="text-decoration-none text-secondary me-3">Sportcenter</a>
                <a href="../../home-page/contact/contact.php" class="text-decoration-none text-secondary me-3">Contact</a>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
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

<!-- Main Content -->
<main class="container p-3">
    <!-- Game Details -->
    <div class="card mb-4">
        <img src="<?= htmlspecialchars($game['img']) ?>" class="card-img-top" alt="<?= htmlspecialchars($game['titel']) ?>">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($game['titel']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($game['description']) ?></p>
        </div>
    </div>

    <!-- Review Form -->
    <div class="card mb-4">
        <div class="card-body">
            <h2>Leave a Review</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <?= generateStarRating(isset($_SESSION['temp_rating']) ? $_SESSION['temp_rating'] : 0, true, $gameId) ?>
                    <?php if (isset($_SESSION['temp_rating'])): ?>
                        <span class="current-rating">Selected rating: <?= $_SESSION['temp_rating'] ?> star(s)</span>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea id="comment" name="comment" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" <?= !isset($_SESSION['temp_rating']) ? 'disabled' : '' ?>>Submit Review</button>
            </form>
        </div>
    </div>

    <!-- Reviews List -->
    <h2>Reviews</h2>
    <?php if ($reviews): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <?= generateStarRating($review['rating'], false) ?>
                <p class="review-meta">
                    By <?= htmlspecialchars($review['username'] ?? 'Guest') ?> on
                    <?= date('F j, Y, g:i a', strtotime($review['created_at'])) ?>

                <form method="POST" style="display: inline;">
                    <input type="hidden" name="like_review" value="1">
                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                    <button type="submit" class="like-button <?= $review['user_has_liked'] ? 'liked' : '' ?>"
                        <?= !isset($_SESSION['user_id']) ? 'disabled' : '' ?>>
                        <i class="fas fa-heart"></i>
                        <span class="like-count"><?= $review['likes_count'] ?? 0 ?></span>
                    </button>
                </form>

                <?php if (isset($_SESSION['is_admin']) || isset($_SESSION['is_mod'])): ?>
                    <a href="?id=<?= $gameId ?>&delete_review=<?= $review['id'] ?>"
                       class="text-danger ms-3"
                       onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
                <?php endif; ?>
                </p>
                <p class="mt-2"><?= htmlspecialchars($review['comment']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-secondary">No reviews yet. Be the first to review!</div>
    <?php endif; ?>
</main>
</body>
</html>