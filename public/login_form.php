<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['nickname']) && isset($_POST['password'])) {
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        

        $sql = "SELECT username, id, role, password_hash FROM users WHERE username = '$nickname' OR email = '$nickname'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Weryfikacja hasła
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['id'] = $row['id'];
                header('Location: ../index.php');
                exit();
            } else {
                echo "<script>alert('Incorrect password!');</script>";
            }
        } else {
            echo "<script>alert('User not found!');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>
<body>
    
    <form method="POST" action="login_form.php" class="login" onsubmit="return validateForm()">
        <h1>Login</h1>
        <!-- Wyświetlanie błędów po stronie serwera -->
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <input type="text" name="nickname" id="nickname" placeholder="Nickname or Email" required><br><br>
        <input type="password" name="password" id="password" placeholder="Password" required><br><br>

        <p>You still don't have an account? <a href="register_form.php">CLICK HERE</a></p>

        <input type="submit" value="Login">
    </form>

</body>
</html>