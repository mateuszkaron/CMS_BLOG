<?php
$uploadDir = 'public/uploads/';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileName = basename($_FILES['file']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
        echo "Plik został wgrany.";
    } else {
        echo "Wystąpił błąd podczas wgrywania.";
    }
}
?>
