<?php
session_start();

require_once 'Database.php';
require_once 'Faktura.php';
require_once 'Umowy.php';

$database = new Database();
$db = $database->getConnection();

$faktury = new Faktura($db);
$umowy = new Umowy($db);

// Pobieranie umowa_id z parametru URL
$umowa_id = isset($_GET['umowa_id']) ? $_GET['umowa_id'] : die('ERROR: Umowa ID not found.');

// Sprawdzanie, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $faktury->umowa_id = $umowa_id;
            $faktury->nr_faktury = $_POST['nr_faktury'];
            $faktury->data_faktury = $_POST['data_faktury'];
            $faktury->kwota_faktury = $_POST['kwota_faktury'];

            $faktury->create();
            header('Location: faktury.php?umowa_id=' . $umowa_id);
        } elseif ($_POST['action'] == 'edit') {
            $faktury->faktura_id = $_POST['faktura_id'];
            $faktury->nr_faktury = $_POST['nr_faktury'];
            $faktury->data_faktury = $_POST['data_faktury'];
            $faktury->kwota_faktury = $_POST['kwota_faktury'];

            $faktury->update();
            header('Location: faktury.php?umowa_id=' . $umowa_id);
        } elseif ($_POST['action'] == 'delete') {
            $faktury->faktura_id = $_POST['faktura_id'];

            $faktury->delete();
            header('Location: faktury.php?umowa_id=' . $umowa_id);
        }
    }
}

// Pobieranie faktur tylko dla wybranej umowy
$stmt_faktury = $faktury->readByUmowaId($umowa_id);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Faktury</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <nav>
        <a href="index.php"><button>Umowy</button></a>
        <a href="logout.php"><button>Wyloguj się</button></a>
    </nav>
    <h1>Faktury dla umowy ID: <?php echo htmlspecialchars($umowa_id); ?></h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nr Faktury</th>
            <th>Data Faktury</th>
            <th>Kwota Faktury</th>
            <th colspan="2">Akcje</th>
        </tr>
        <form action="faktury.php?umowa_id=<?php echo $umowa_id; ?>" method="post">
            <tr>
                <td></td>
                <td><input type="text" id="nr_faktury" name="nr_faktury" required></td>
                <td><input type="date" id="data_faktury" name="data_faktury" required min="2000-01-01" max="2100-12-30"></td>
                <td><input type="number" id="kwota_faktury" name="kwota_faktury" required step='0.01' value='0.00' placeholder='0.00' min="0"></td>
                <td><input type="hidden" name="action" value="create"><input type="submit" value="Dodaj fakturę"></td>
            </tr>
        </form>
        <?php while ($row = $stmt_faktury->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <form action="faktury.php?umowa_id=<?php echo $umowa_id; ?>" method="post" style="display:inline;">
                    <td><input type="hidden" name="faktura_id" value="<?php echo htmlspecialchars($row['faktura_id']); ?>"><?php echo htmlspecialchars($row['faktura_id']); ?></td>
                    <td><input type="text" name="nr_faktury" value="<?php echo htmlspecialchars($row['nr_faktury']); ?>" required></td>
                    <td><input type="date" name="data_faktury" min="2000-01-01" max="2100-12-30" value="<?php echo htmlspecialchars($row['data_faktury']); ?>" required></td>
                    <td><input type="number" name="kwota_faktury" step='0.01' min="0" value="<?php echo htmlspecialchars($row['kwota_faktury']); ?>" required></td>
                    <td>
                        <input type="hidden" name="action" value="edit">
                        <input type="submit" value="Edytuj">
                    </td>
                </form>

                <form action="faktury.php?umowa_id=<?php echo $umowa_id; ?>" method="post" style="display:inline;">
                    <td>
                        <input type="hidden" name="faktura_id" value="<?php echo htmlspecialchars($row['faktura_id']); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="submit" value="Usuń" onclick="return confirm('Czy na pewno chcesz usunąć tę fakturę?');">
                    </td>
                </form>

            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>