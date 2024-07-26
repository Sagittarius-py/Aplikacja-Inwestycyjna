<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    if ($stmt->execute([$username, $password])) {
        header('Location: login.php');
    } else {
        echo 'Rejestracja nie powiodła się.';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Rejestracja</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>


<body>
    <form method="POST">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" name="username" required>
        <label for="password">Hasło:</label>
        <input type="password" name="password" required>
        <button type="submit">Zarejestruj się</button>
    </form>
</body>

</html>