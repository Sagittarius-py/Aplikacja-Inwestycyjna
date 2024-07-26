<?php
require 'Database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
    } else {
        echo 'Nieprawidłowa nazwa użytkownika lub hasło.';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Logowanie</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <div class="container">
        <form method="POST">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" name="username" required> <br />
            <label for="password">Hasło:</label> <br />
            <input type="password" name="password" required>
            <button type="submit">Zaloguj się</button>
        </form>
    </div>
</body>

</html>