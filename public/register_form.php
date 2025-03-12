<?php
session_start();
require_once '../config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nickname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['captcha_value'])) {
        $nickname = $_POST['nickname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $captcha_value = $_POST['captcha_value'];

        // Sprawdzanie poprawności CAPTCHA
        if ($captcha_value != $_SESSION['captcha_correct']) {
            echo "<script>alert('Incorrect CAPTCHA.');</script>";
        } else {
            // Sprawdzanie, czy hasła się zgadzają
            if ($password !== $confirm_password) {
                echo "<script>alert('Passwords do not match.');</script>";
            } else {
                // Sprawdzanie, czy nickname lub email już istnieją
                $sql = "SELECT id FROM users WHERE username = '$nickname' OR email = '$email'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<script>alert('Username or email already exists.');</script>";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    // Dodawanie użytkownika do bazy danych
                    $sql = "INSERT INTO users (username, email, password_hash) VALUES ('$nickname', '$email', '$hashedPassword')";
                    
                    if ($conn->query($sql) === TRUE) {
                        // Rejestracja zakończona sukcesem, użytkownik może być przekierowany do logowania lub do głównej strony
                        echo "<script>alert('Registration successful! You can now log in.'); window.location.href = './login_form.php';</script>";
                    } else {
                        echo "<script>alert('Error: " . $conn->error . "');</script>";
                    }
                }
            }
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
    <link rel="stylesheet" href="css/register.css">
    <title>Register</title>
    <script src="js/validateRegister.js" defer></script>
</head>
<body>

    <form method="POST" action="register_form.php" class="register" onsubmit="return validateForm()">
        <h1>JOIN US!</h1>
        <!-- Wyświetlanie błędów po stronie serwera -->
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <input type="text" name="nickname" id="nickname" placeholder="Nickname" required><br><br>
        <input type="email" name="email" id="email" placeholder="Email" required><br><br>
        <input type="password" name="password" id="password" placeholder="Password" required><br><br>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required><br><br>
        <div id="captcha">
            <!-- CAPTCHA wstawiana dynamicznie -->
            <?php include('../src/captcha.php'); ?>
        </div>

        <p>You already have an account? <a href="login_form.php">CLICK HERE</a></p>

        <input type="submit" value="JOIN">
    </form>

</body>
</html>