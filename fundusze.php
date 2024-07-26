<?php
session_start();

require_once 'Database.php';
require_once 'Fundusz.php';
require_once 'Umowy.php';

$database = new Database();
$db = $database->getConnection();

$fundusze = new Fundusz($db);
$umowy = new Umowy($db);

// Pobieranie umowa_id z parametru URL
$umowa_id = isset($_GET['umowa_id']) ? $_GET['umowa_id'] : die('ERROR: Umowa ID not found.');

// Sprawdzanie, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $fundusze->umowa_id = $umowa_id;
            $fundusze->plan = $_POST['plan'];
            $fundusze->rok = $_POST['rok'];
            $fundusze->kwota_brutto = $_POST['kwota_brutto'];
            $fundusze->pozostaly_fundusz = $_POST['pozostaly_fundusz'];

            $fundusze->create();
            header('Location: fundusze.php?umowa_id=' . $umowa_id);
        } elseif ($_POST['action'] == 'edit') {
            $fundusze->fundusze_id = $_POST['fundusze_id'];
            $fundusze->plan = $_POST['plan'];
            $fundusze->rok = $_POST['rok'];
            $fundusze->kwota_brutto = $_POST['kwota_brutto'];
            $fundusze->pozostaly_fundusz = $_POST['pozostaly_fundusz'];
            $fundusze->update();
            header('Location: fundusze.php?umowa_id=' . $umowa_id);
        } elseif ($_POST['action'] == 'delete') {
            $fundusze->fundusze_id = $_POST['fundusze_id'];
            $fundusze->delete();
            header('Location: fundusze.php?umowa_id=' . $umowa_id);
        }
    }
}

$stmt_fundusze = $fundusze->readByUmowaId($umowa_id);
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
    <h1>Fundusze dla umowy ID: <?php echo htmlspecialchars($umowa_id); ?></h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Plan</th>
            <th>Rok Planowania</th>
            <th>Kwota funduszu (Brutto)</th>
            <th>Fundusze do wykożystania</th>
            <th colspan="2">Akcje</th>
        </tr>
        <form action="fundusze.php?umowa_id=<?php echo $umowa_id; ?>" method="post">
            <tr>
                <td></td>
                <td><input type="number" name="plan" id="plan" required step='0.01' placeholder='0.00' min="0"></td>
                <td><input type="number" id="rok" name="rok" required min="2000" max="2050"></td>
                <td><input type="number" id="kwota_brutto" name="kwota_brutto" required step='0.01' placeholder='0.00' min="0"></td>
                <td><input type="number" id="pozostaly_fundusz" name="pozostaly_fundusz" required step='0.01' placeholder='0.00' min="0"></td>
                <td colspan="2">
                    <input type="hidden" name="action" value="create">
                    <input type="submit" value="Dodaj Fundusz">
                </td>
            </tr>
        </form>
        <?php while ($row = $stmt_fundusze->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <form action="fundusze.php?umowa_id=<?php echo $umowa_id; ?>" method="post" style="display:inline;">
                    <td><input type="hidden" name="fundusze_id" value="<?php echo htmlspecialchars($row['fundusze_id']); ?>"><?php echo htmlspecialchars($row['fundusze_id']); ?></td>
                    <td><input type="number" name="plan" value="<?php echo htmlspecialchars($row['plan']); ?>" required step='0.01' placeholder='0.00' min="0"></td>
                    <td><input type="number" name="rok" value="<?php echo htmlspecialchars($row['rok']); ?>" required min="2000" max="2050"></td>
                    <td><input type="number" name="kwota_brutto" value="<?php echo htmlspecialchars($row['kwota_brutto']); ?>" required step='0.01' placeholder='0.00' min="0"></td>
                    <td><input type="number" name="pozostaly_fundusz" value="<?php echo htmlspecialchars($row['pozostaly_fundusz']); ?>" required step='0.01' placeholder='0.00' min="0"></td>
                    <td>
                        <input type="hidden" name="action" value="edit">
                        <input type="submit" value="Edytuj">

                    </td>
                </form>

                <form action="fundusze.php?umowa_id=<?php echo $umowa_id; ?>" method="post" style="display:inline;">
                    <td>
                        <input type="hidden" name="fundusze_id" value="<?php echo htmlspecialchars($row['fundusze_id']); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="submit" value="Usuń" onclick="return confirm('Czy na pewno chcesz usunąć ten fundusz?');">
                    </td>
                </form>

            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>