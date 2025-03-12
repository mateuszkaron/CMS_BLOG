<?php
require_once '../config.php';

// Pobieranie komentarzy dla posta
function getComments($conn, $postId) {
    $stmt = $conn->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    return $comments;
}

// Dodawanie nowego komentarza
function addComment($conn, $postId, $authorName, $content) {
    $stmt = $conn->prepare("INSERT INTO comments (post_id, author_name, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $postId, $authorName, $content);
    $stmt->execute();
}
?>
