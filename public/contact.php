<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $nameCON = mysqli_real_escape_string($conn, htmlspecialchars($_POST['nameCON']));
    $emailCON = mysqli_real_escape_string($conn, htmlspecialchars($_POST['emailCON']));
    $messageCON = mysqli_real_escape_string($conn, htmlspecialchars($_POST['messageCON']));

    // Walidacja danych
    if (empty($nameCON) || empty($emailCON) || empty($messageCON)) {
        echo "<script>alert('All fields are required');</script>";
    } elseif (!filter_var($emailCON, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Type a valid email address.');</script>";
    } else {
        // Dodanie wiadomości do bazy danych za pomocą zwykłego zapytania SQL
        $query = "INSERT INTO messages (name, email, message) VALUES ('$nameCON', '$emailCON', '$messageCON')";
        
        // Wykonanie zapytania
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Congrats! Your message has been sent successfully.');</script>";
        } else {
            echo "<script>alert('We are sorry, but there was an error while sending your message.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Contact us!</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/postEdit.css">
</head>
<body>
    
    <?php include '../includes/menu.php'; ?>
    <div id="container">
        <div id="showcase" style="background-image: url(../img/200sx.jpg); height: 50vh;">
            <div id="showcase-content" style="margin-bottom: -10vh;">       
                <h1>CONTACT US</h1>
            </div>
        </div>

            <form method="POST" action="">
                <input type="text" id="nameCON" name="nameCON" placeholder="name" required><br>

                <input type="email" id="emailCON" name="emailCON" placeholder="e-mail" required><br>

                <textarea id="messageCON" name="messageCON" rows="5" placeholder="your message" required></textarea><br>

                <button type="submit" name="contact_submit">Send</button>
            </form>
    </div>

            <?php include '../includes/footer.php'; ?>
</body>
</html>

<style>
    .contact-form {
        margin-top: 50px;
        padding: 20px 0px 20px 0px;
        background-color: #f8f9fa;
        width: 100%;
        text-align: center;
        justify-content: center;
    }

    .contact-form input,
    .contact-form textarea {
        width: 60%;
        margin-bottom: 15px;
        padding: 10px;
        border:none;
        border-bottom: 1px solid #ced4da;
        border-radius: 4px;
    }

    .contact-form button {
        background-color: #007bff;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .contact-form button:hover {
        background-color: #0056b3;
    }

    .success-message {
        color: green;
        margin-bottom: 20px;
    }

    .error-message {
        color: red;
        margin-bottom: 20px;
    }
</style>