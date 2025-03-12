<?php
require_once '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category_id = $_POST['category'];
    $content = $_POST['content'];
    $author_id = $_SESSION['id']; // Pobierz ID zalogowanego użytkownika
    $imagePath = null;

    // Obsługa zdjęcia
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = './uploads/';
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Przenieś plik do katalogu "uploads"
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $imagePath = 'uploads/' . $fileName; // Relatywna ścieżka do pliku
        }
    }

    // Dodaj post do bazy danych
    $sql = "INSERT INTO posts (category_id, title, content, author_id, img_path, created_at) 
            VALUES ('$category_id', '$title', '$content', '$author_id', '$imagePath', NOW())";
    if ($conn->query($sql) === TRUE) {
        header('Location: ../index.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/postEdit.css">
    <title>Dodaj post</title>
    <!-- Dodaj bibliotekę WYSIWYG -->
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
</head>
<body>
    <h1>Dodaj nowy post</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="title">Tytuł:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="category">Kategoria:</label>
        <select id="category" name="category" required>
            <?php
            $categories = $conn->query("SELECT id, name FROM categories");
            while ($row = $categories->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select><br><br>

        <label for="content">Treść:</label>
        <textarea id="content" name="content" rows="10" cols="50" required></textarea><br><br>

        <label for="image">Dodaj zdjęcie:</label>
        <input type="file" id="image" name="image" accept="image/*"><br><br>

        <div class="button-container">
            <a href="../index.php">Anuluj</a>
            <button type="submit">Dodaj post</button>
        </div>
    </form>

    <script>
        // Inicjalizacja edytora WYSIWYG
        CKEDITOR.replace('content');
    </script>
</body>
</html>
