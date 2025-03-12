<?php
require_once '../config.php';
require_once '../src/comment.php';

// Pobieranie ID posta z URL-a
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid post ID.");
}

$postId = (int)$_GET['id'];

// Pobieranie danych posta
$sql = "SELECT posts.title, posts.content, posts.img_path, categories.name AS category_name, users.username AS author_name, posts.created_at
        FROM posts 
        JOIN categories ON posts.category_id = categories.id 
        JOIN users ON posts.author_id = users.id 
        WHERE posts.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Post not found.");
}

$post = $result->fetch_assoc();

// Obsługa dodawania komentarza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $authorName = isset($_POST['author_name']) ? $_POST['author_name'] : "Anonymous";
    $content = trim($_POST['content']);

    if (!empty($content)) {
        addComment($conn, $postId, $authorName, $content);
        header("Location: post.php?id=$postId"); // Odświeżenie strony
        exit();
    }
}

// Pobieranie komentarzy
$comments = getComments($conn, $postId);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../public/css/style.css">
        <link rel="stylesheet" href="../public/css/postEdit.css">
    <title><?= htmlspecialchars($post['title']) ?></title>
</head>
<body>
<div id="container">
        <div id="showcase" style="background-image: url('./<?= htmlspecialchars($post['img_path']) ?>'); height: 60vh;">

        <?php include '../includes/menu.php'; ?>

            <div id="showcase-content" style="margin-bottom: -10vh;">       
            <h1><?= htmlspecialchars($post['title']) ?></h1>
                <a href="#main" class="btn-start"> Read more </a>
            </div>
        </div>
    </div>
    <div id="main">           
        <div id="main-content">
            <div id="post">
                <div class="post-content">
                    <h1><?= htmlspecialchars($post['title']) ?></h1>
                    <h2><?= htmlspecialchars($post['created_at']) ?>  <?= htmlspecialchars($post['author_name']) ?></h2>
                    <p><?= nl2br(htmlspecialchars_decode(substr($post['content'], 0, 500))) ?>...</p>
                    <h3><?= htmlspecialchars($post['category_name']) ?></h3>
                </div>
            </div>
            <div class="comments-section">
                <h2>Komentarze</h2>

                <?php if (count($comments) > 0): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <p><strong><?= htmlspecialchars($comment['author_name']) ?>:</strong></p>
                            <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                            <p><small><?= htmlspecialchars($comment['created_at']) ?></small></p>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Brak komentarzy. Bądź pierwszy!</p>
                <?php endif; ?>

                <h3>Dodaj komentarz</h3>
                <form action="post.php?id=<?= $postId ?>" method="POST">
                    <input type="text" name="author_name" placeholder="Twoje imię (opcjonalne)">
                    <textarea name="content" rows="5" placeholder="Treść komentarza" required></textarea>
                    <button type="submit">Dodaj komentarz</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
