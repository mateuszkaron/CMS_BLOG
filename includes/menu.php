<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/blog_cms/config.php';

    if (isset($_SESSION['id'])) {
        // echo "<p>Zalogowany użytkownik, ID: " . $_SESSION['id'] . "</p>";

        $userID = $_SESSION['id'];
        $query = "SELECT role, active FROM users WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result($role, $active);
        $stmt->fetch();
        $stmt->close();

        $_SESSION['role'] = $role;

        if ($role === 'admin') {
            $_SESSION['isAdmin'] = true;
        } else {
            $_SESSION['isAdmin'] = false;
        }

        $_SESSION['active'] = $active;
    }
?>

<div id="menu">
    <ul>
        <li><a href="/blog_cms/index.php">HOME</a></li>
        <li><a href="/blog_cms/public/about.php">ABOUT US</a></li>
        <li><a href="/blog_cms/public/contact.php">CONTACT US</a></li>
        <?php if (!isset($_SESSION['id'])): ?>
            <li><a href="/blog_cms/public/login_form.php">LOGIN</a></li>
        <?php endif; ?>
        <li><a href="/blog_cms/src/logout.php">LOGOUT</a></li>        
        <?php if (isset($_SESSION['id']) && $_SESSION['active'] == 1): ?>
            <li><a href="/blog_cms/public/postAdd.php">ADD POST</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
            <li><a href="/blog_cms/admin/admin.php">ADMIN PANEL</a></li>
        <?php endif; ?>
    </ul>
</div>