<?php
require_once './config.php';

// Pobieranie wszystkich postów
function getAllPosts($conn) {
    $sql = "SELECT * FROM posts ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $posts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    return $posts;
}

// Pobieranie pojedynczego posta
function getPostById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Dodawanie nowego posta
function addPost($conn, $title, $content, $authorId, $categoryId) {
    $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id, category_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $content, $authorId, $categoryId);
    $stmt->execute();
}
?>
