<?php
session_start();

require_once 'Database.php';
require_once 'Aneks.php';
require_once 'Umowy.php';

$database = new Database();
$db = $database->getConnection();

$aneksy = new Aneks($db);
$umowy = new Umowy($db);

// Pobieranie umowa_id z parametru URL
$umowa_id = isset($_GET['umowa_id']) ? $_GET['umowa_id'] : die('ERROR: Umowa ID not found.');

// Sprawdzanie, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $aneksy->umowa_id = $umowa_id;
            $aneksy->aneks = $_POST['aneks'];
            $aneksy->data_zawarcia = $_POST['data_zawarcia'];
            $aneksy->tresc_aneksu = $_POST['tresc_aneksu'];

            $aneksy->create();
            header('Location: aneksy.php?umowa_id=' . $umowa_id);
        } elseif ($_POST['action'] == 'edit') {
            $aneksy->aneks_id = $_POST['aneks_id'];
            $aneksy->aneks = $_POST['aneks'];
            $aneksy->data_zawarcia = $_POST['data_zawarcia'];
            $aneksy->tresc_aneksu = $_POST['tresc_aneksu'];

            $aneksy->update();
            header('Location: aneksy.php?umowa_id=' . $umowa_id);
        } elseif ($_POST['action'] == 'delete') {
            $aneksy->aneks_id = $_POST['aneks_id'];

            $aneksy->delete();
            header('Location: aneksy.php?umowa_id=' . $umowa_id);
        }
    }
}

$stmt_aneksy = $aneksy->readByUmowaId($umowa_id);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Aneksy</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <nav>
        <a href="index.php"><button>Umowy</button></a>
        <a href="logout.php"><button>Wyloguj się</button></a>
    </nav>
    <h1>Aneksy dla umowy ID: <?php echo htmlspecialchars($umowa_id); ?></h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Aneks</th>
            <th>Data Zawarcia</th>
            <th>Tresc Aneksu</th>
            <th colspan="2">Akcje</th>
        </tr>
        <form action="aneksy.php?umowa_id=<?php echo $umowa_id; ?>" method="post">
            <tr>
                <td></td>
                <td><input type="text" id="aneks" name="aneks" required></td>
                <td><input type="date" id="data_zawarcia" name="data_zawarcia" required min="2000-01-01" max="2100-12-30"></td>
                <td><input type="text" id="tresc_aneksu" name="tresc_aneksu" required></td>
                <td colspan="2">
                    <input type="hidden" name="action" value="create">
                    <input type="submit" value="Dodaj aneks">
                </td>
            </tr>
        </form>
        <?php while ($row = $stmt_aneksy->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <form action="aneksy.php?umowa_id=<?php echo $umowa_id; ?>" method="post" style="display:inline;">
                    <td><input type="hidden" name="aneks_id" value="<?php echo htmlspecialchars($row['aneks_id']); ?>"></td>
                    <td><input type="text" name="aneks" value="<?php echo htmlspecialchars($row['aneks']); ?>" required></td>
                    <td><input type="date" name="data_zawarcia" value="<?php echo htmlspecialchars($row['data_zawarcia']); ?>" required min="2000-01-01" max="2100-12-30"></td>
                    <td><input type="text" name="tresc_aneksu" value="<?php echo htmlspecialchars($row['tresc_aneksu']); ?>" required></td>
                    <td>
                        <input type="hidden" name="action" value="edit">
                        <input type="submit" value="Edytuj">

                    </td>
                </form>

                <form action="aneksy.php?umowa_id=<?php echo $umowa_id; ?>" method="post" style="display:inline;">
                    <td>
                        <input type="hidden" name="aneks_id" value="<?php echo htmlspecialchars($row['aneks_id']); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="submit" value="Usuń" onclick="return confirm('Czy na pewno chcesz usunąć ten aneks?');">
                    </td>
                </form>

            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>