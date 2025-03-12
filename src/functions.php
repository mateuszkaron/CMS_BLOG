<?php
// functions.php

/**
 * Rejestracja użytkownika
 */
function register($conn, $username, $password, $role = 'author') {
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role, active) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $username, $passwordHash, $role);
    return $stmt->execute();
}

/**
 * Logowanie użytkownika
 */
function login($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();
    if ($user) {
        if ($user['active'] == 0) {
            return "Twoje konto nie zostało jeszcze aktywowane.";
        }
        if (password_verify($password, $user['password_hash'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }
    return false;
}

/**
 * Pobieranie nieaktywnych użytkowników
 */
function getInactiveUsers($conn) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE active = 0");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Aktywacja użytkownika
 */
function activateUser($conn, $userId) {
    $stmt = $conn->prepare("UPDATE users SET active = 1 WHERE id = ?");
    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}

/**
 * Usuwanie użytkownika
 */
function deleteUser($conn, $userId) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}

/**
 * Zmiana roli użytkownika
 */
function changeUserRole($conn, $userId, $newRole) {
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $userId);
    return $stmt->execute();
}

/**
 * Pobieranie wszystkich użytkowników
 */
function getAllUsers($conn) {
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Usuwanie posta
 */
function deletePost($conn, $postId) {
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $postId);
    return $stmt->execute();
}

/**
 * Pobieranie wszystkich postów
 */
function getAllPosts($conn) {
    $stmt = $conn->prepare("SELECT posts.id, posts.title, posts.content, posts.category_id, posts.author_id, users.username AS author_name 
                            FROM posts 
                            JOIN users ON posts.author_id = users.id");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Pobieranie wszystkich wiadomości
 */
function getAllMessages($conn) {
    $stmt = $conn->prepare("SELECT * FROM messages ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Usuwanie wiadomości
 */
function deleteMessage($conn, $messageId) {
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $messageId);
    return $stmt->execute();
}
