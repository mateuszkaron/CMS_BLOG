<?php
require_once '../config.php';
require_once '../src/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['activate_user_id'])) {
        activateUser($conn, $_POST['activate_user_id']);
    } elseif (isset($_POST['delete_user_id'])) {
        deleteUser($conn, $_POST['delete_user_id']);
    } elseif (isset($_POST['change_role_user_id'])) {
        changeUserRole($conn, $_POST['change_role_user_id'], $_POST['new_role']);
    } elseif (isset($_POST['delete_post_id'])) {
        deletePost($conn, $_POST['delete_post_id']);
    } elseif (isset($_POST['delete_message_id'])) {
        deleteMessage($conn, $_POST['delete_message_id']);
    }
}

$inactiveUsers = getInactiveUsers($conn);
$allUsers = getAllUsers($conn);
$allPosts = getAllPosts($conn);
$allMessages = getAllMessages($conn); // Fetch all messages
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Administartion Panel</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>
    <?php include '../includes/menu.php'; ?>
    <div id="showcase" style="height: 50vh; background-image: url('../img/cressida.jpg');">
        <div id="showcase-content">
            <h1 style="margin-bottom: -20vh;">Administartion Panel</h1>
        </div>
    </div>
    <div id="container">
        <h1>Not active users</h1>
        <?php if (count($inactiveUsers) > 0): ?>
            <ul class="user-list">
                <?php foreach ($inactiveUsers as $user): ?>
                    <li class="user-list-item">
                        <?= htmlspecialchars($user['username']) ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="activate_user_id" value="<?= $user['id'] ?>">
                            <button type="submit">Active</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>There are no not active users.</p>
        <?php endif; ?>

        <h1>Users</h1>
        <ul class="user-list">
            <?php foreach ($allUsers as $user): ?>
                <li class="user-list-item">
                    <?= htmlspecialchars($user['username']) ?> (<?= htmlspecialchars($user['role']) ?>)

                    <form method="post" style="display:inline;">
                        <input type="hidden" name="change_role_user_id" value="<?= $user['id'] ?>">
                        <select name="new_role">
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <button type="submit">Change Role</button>                   
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <h1>Posts</h1>
        <?php if (count($allPosts) > 0): ?>
            <ul class="post-list">
                <?php foreach ($allPosts as $post): ?>
                    <li class="post-list-item">
                        <?= htmlspecialchars($post['title']) ?> by <?= htmlspecialchars($post['author_name']) ?>
                        <div class="post-list-item-actions">
                            <a href="../public/postEdit.php?id=<?= $post['id'] ?>" class="button">Edit</a>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="delete_post_id" value="<?= $post['id'] ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>There are no posts.</p>
        <?php endif; ?>

        <h1>Messages</h1>
        <?php if (count($allMessages) > 0): ?>
            <ul class="message-list">
                <?php foreach ($allMessages as $message): ?>
                    <li class="message-list-item">
                        <p><strong>From:</strong> <?= htmlspecialchars($message['name']) ?> (<?= htmlspecialchars($message['email']) ?>)</p>
                        <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($message['message'])) ?></p>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="delete_message_id" value="<?= $message['id'] ?>">
                            <button type="submit">Mark as Read</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>There are no messages.</p>
        <?php endif; ?>
    </div>
</body>
</html>
