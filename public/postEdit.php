<?php
require_once '../config.php'; 

session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();


        if ($_SESSION['role'] !== 'admin' && $_SESSION['id'] != $post['author_id']) {
            header('Location: ../index.php'); 
            exit();
        }
    } else {
        header('Location: ../index.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete'])) {
            $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->bind_param("i", $postId);
            $stmt->execute();

            header('Location: ../index.php');
            exit();
        } else {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $categoryId = $_POST['category_id'];

            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, category_id = ? WHERE id = ?");
            $stmt->bind_param("ssii", $title, $content, $categoryId, $postId);
            $stmt->execute();

            header('Location: post.php?id=' . $postId);
            exit();
        }
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Post</title>
    <link rel="stylesheet" href="../public/css/postEdit.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
</head>
<body>
    <h1>Edytuj post</h1>
    <form method="POST" action="">
        <label for="title">Tytuł:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br><br>
        
        <label for="content">Treść:</label><br>
        <textarea id="content" name="content" rows="5" cols="50" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>

        <label for="category_id">Kategoria:</label>
        <select id="category_id" name="category_id" required>
            <option value="1" <?= $post['category_id'] == 1 ? 'selected' : '' ?>>Drift</option>
            <option value="2" <?= $post['category_id'] == 2 ? 'selected' : '' ?>>Car Show</option>
            <option value="3" <?= $post['category_id'] == 3 ? 'selected' : '' ?>>Tuning</option>
        </select><br><br>

        <div class="button-container">
            <input type="submit" value="SAVE CHANGES">
            <input type="submit" name="delete" value="DELETE POST" onclick="return confirm('Czy na pewno chcesz usunąć ten post?');">
        </div>
    </form>

    <script>
        CKEDITOR.replace('content');
    </script>
</body>
</html>
