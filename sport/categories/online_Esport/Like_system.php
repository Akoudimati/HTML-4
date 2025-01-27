<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User must be logged in']);
    exit;
}

try {
    $db = new PDO("mysql:host=localhost;dbname=sports", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_id'])) {
        $reviewId = intval($_POST['review_id']);
        $userId = $_SESSION['user_id'];
        $reviewType = $_POST['review_type'] ?? 'online';

        // Check if user already liked this review
        $checkLike = $db->prepare("SELECT id FROM review_likes 
                                  WHERE review_id = :review_id 
                                  AND user_id = :user_id 
                                  AND review_type = :review_type");
        $checkLike->execute([
            ':review_id' => $reviewId,
            ':user_id' => $userId,
            ':review_type' => $reviewType
        ]);

        $reviewTable = ($reviewType === 'online') ? 'online_esport_reviews' : 'offline_esport_reviews';

        if ($checkLike->rowCount() > 0) {
            // Unlike: Remove the like
            $db->prepare("DELETE FROM review_likes 
                         WHERE review_id = :review_id 
                         AND user_id = :user_id 
                         AND review_type = :review_type")
                ->execute([
                    ':review_id' => $reviewId,
                    ':user_id' => $userId,
                    ':review_type' => $reviewType
                ]);

            $db->prepare("UPDATE {$reviewTable} 
                         SET likes_count = likes_count - 1 
                         WHERE id = :review_id")
                ->execute([':review_id' => $reviewId]);

            echo json_encode(['status' => 'success', 'action' => 'unliked', 'message' => 'Like removed']);
        } else {
            // Like: Add new like
            $db->prepare("INSERT INTO review_likes (review_id, user_id, review_type) 
                         VALUES (:review_id, :user_id, :review_type)")
                ->execute([
                    ':review_id' => $reviewId,
                    ':user_id' => $userId,
                    ':review_type' => $reviewType
                ]);

            $db->prepare("UPDATE {$reviewTable} 
                         SET likes_count = likes_count + 1 
                         WHERE id = :review_id")
                ->execute([':review_id' => $reviewId]);

            echo json_encode(['status' => 'success', 'action' => 'liked', 'message' => 'Like added']);
        }

        // Get updated like count
        $getLikes = $db->prepare("SELECT likes_count FROM {$reviewTable} WHERE id = :review_id");
        $getLikes->execute([':review_id' => $reviewId]);
        $likesCount = $getLikes->fetchColumn();

        echo json_encode(['status' => 'success', 'likes_count' => $likesCount]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}