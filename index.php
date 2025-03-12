<?php
require_once './src/post.php';
$posts = getAllPosts($conn);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="./public/css/style.css">
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <div id="container">
        <div id="showcase">

        <?php include './includes/menu.php'; ?>

            <div id="showcase-content">       
                <h1>Japanese</br>Car Culture</h1>
                <a href="#main" class="btn-start"> Read more </a>
            </div>
        </div>

        <div id="main">           
            <div id="main-content-sort">
                <h4 id="content">Japanese Car Culture</h4>
                <h4> Select category:</h4>
                <select id="category-select">
                    <option value="all">ALL</option>
                    <option value="tuning">TUNING</option>
                    <option value="carshow">CARSHOW </option>
                    <option value="drift">DRIFT</option>
                </select>
            </div>
        <div id="main-content">
            <div id="posts">
            <?php
            $sql = "SELECT posts.id AS post_id, categories.name AS category_name, posts.title, posts.content, posts.author_id, users.username AS author_name, posts.created_at, posts.img_path  
                    FROM posts 
                    JOIN categories ON posts.category_id = categories.id 
                    JOIN users ON posts.author_id = users.id 
                    ORDER BY posts.created_at DESC";
                    
            $result = mysqli_query($conn, $sql);
            $posts = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $posts[] = $row;
                }
            }
            ?>
            <?php foreach ($posts as $post): ?>
                <div class="post" data-category="<?= htmlspecialchars($post['category_name']) ?>">
                        <div class="post-img" style="background-image: url('./public/<?= htmlspecialchars($post['img_path']) ?>')"></div>
                    <div class="post-content">
                        <h1><?= htmlspecialchars($post['title']) ?></h1>
                        <h2><?= htmlspecialchars($post['created_at']) ?>  <?= htmlspecialchars($post['author_name']) ?></h2>
                        <p><?= nl2br(htmlspecialchars_decode(substr($post['content'], 0, 100))) ?>...</p>
                        <h3><?= htmlspecialchars($post['category_name']) ?></h3>
                        <a href="./public/post.php?id=<?= $post['post_id'] ?>">Czytaj dalej</a>
                        <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $post['author_id']): ?>
                            <a href="./public/postEdit.php?id=<?= $post['post_id'] ?>">Edit</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            </div>

        </div>
        
        <?php include './includes/footer.php'; ?>

    <script> 

        document.addEventListener('DOMContentLoaded', () => {
            const categorySelect = document.getElementById('category-select');
            const posts = document.querySelectorAll('.post');

            console.log(categorySelect); 
            console.log(posts);  

            categorySelect.addEventListener('change', () => {
                const selectedCategory = categorySelect.value;

                posts.forEach(post => {
                    if (selectedCategory === 'all' || post.dataset.category === selectedCategory) {
                        post.classList.add('visible');
                    } else {
                        post.classList.remove('visible');
                    }
                });
            });
            
            categorySelect.dispatchEvent(new Event('change'));
        });

    </script>

</body>
</html>
