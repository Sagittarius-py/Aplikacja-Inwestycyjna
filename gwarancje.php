<?php
session_start();

require_once 'Database.php';
require_once 'Gwarancja.php';
require_once 'Umowy.php';

$database = new Database();
$db = $database->getConnection();

$gwarancje = new Gwarancja($db);
$umowy = new Umowy($db);

// Pobieranie umowa_id z parametru URL
$umowa_id = isset($_GET['umowa_id']) ? $_GET['umowa_id'] : die('ERROR: Umowa ID not found.');

// Sprawdzanie, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $gwarancje->umowa_id = $umowa_id;
            $gwarancje->ilosc_miesiecy = $_POST['ilosc_miesiecy'];
            $gwarancje->termin_uplywu = $_POST['termin_uplywu'];

            $gwarancje->pierwszy_przeglad = $_POST['pierwszy_przeglad'];
            $gwarancje->przeglady = $_POST['przeglady'];

            $gwarancje->create();
            header('Location: gwarancje.php?umowa_id=' . $umowa_id);
        } elseif ($_POST['action'] == 'edit') {
            $gwarancje->gwarancja_id = $_POST['gwarancja_id'];
            $gwarancje->ilosc_miesiecy = $_POST['ilosc_miesiecy'];
            $gwarancje->termin_uplywu = $_POST['termin_uplywu'];
            $gwarancje->pierwszy_przeglad = $_POST['pierwszy_przeglad'];
            $gwarancje->przeglady = $_POST['przeglady'];

            $gwarancje->update();
            header('Location: gwarancje.php?umowa_id=' . $umowa_id);
        } elseif ($_POST['action'] == 'delete') {
            $gwarancje->gwarancja_id = $_POST['aneks_id'];

            $gwarancje->delete();
            header('Location: gwarancje.php?umowa_id=' . $umowa_id);
        }
    }
}

$stmt_gwarancje = $gwarancje->readByUmowaId($umowa_id);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Gwarancje</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <nav>
        <a href="index.php"><button>Umowy</button></a>
        <a href="logout.php"><button>Wyloguj się</button></a>
    </nav>
    <h1>Gwarancje dla umowy ID: <?php echo htmlspecialchars($umowa_id); ?></h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Ilosc Miesiecy obowiązywania Gwarancjii</th>
            <th>Data upływu gwarancjii</th>
            <th>Termin pierwszego przeglądu</th>
            <th>Co ile ma się odbywać przegląd</th>
            <th colspan="2">Akcje</th>
        </tr>
        <form action="gwarancje.php?umowa_id=<?php echo $umowa_id; ?>" method="post">
            <tr>
                <td></td>
                <td><input type="number" id="ilosc_miesiecy" name="ilosc_miesiecy" required min="1" max="240"></td>
                <td><input type="date" id="termin_uplywu" name="termin_uplywu" required min="2000-01-01" max="2100-12-30"></td>
                <td><input type="date" id="pierwszy_przeglad" name="pierwszy_przeglad" required min="2000-01-01" max="2100-12-30"></td>
                <td><input type="text" id="przeglady" name="przeglady" required></td>
                <td colspan="2">
                    <input type="hidden" name="action" value="create">
                    <input type="submit" value="Dodaj Gwarancje">
                </td>
            </tr>
        </form>
        <?php while ($row = $stmt_gwarancje->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <form action="gwarancje.php?umowa_id=<?php echo $umowa_id; ?>" method="post" style="display:inline;">
                    <td><input type="hidden" name="gwarancja_id" value="<?php echo htmlspecialchars($row['gwarancja_id']); ?>"><?php echo htmlspecialchars($row['gwarancja_id']); ?></td>
                    <td><input type="number" name="ilosc_miesiecy" value="<?php echo htmlspecialchars($row['ilosc_miesiecy']); ?>" required min="1" max="240"></td>
                    <td><input type="date" name="termin_uplywu" value="<?php echo htmlspecialchars($row['termin_uplywu']); ?>" required min="2000-01-01" max="2100-12-30"></td>
                    <td><input type="date" name="pierwszy_przeglad" value="<?php echo htmlspecialchars($row['pierwszy_przeglad']); ?>" required min="2000-01-01" max="2100-12-30"></td>
                    <td><input type="text" name="przeglady" value="<?php echo htmlspecialchars($row['przeglady']); ?>" required></td>
                    <td>
                        <input type="hidden" name="action" value="edit">
                        <input type="submit" value="Edytuj">

                    </td>
                </form>

                <form action="gwarancje.php?umowa_id=<?php echo $umowa_id; ?>" method="post" style="display:inline;">
                    <td>
                        <input type="hidden" name="gwarancja_id" value="<?php echo htmlspecialchars($row['gwarancja_id']); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="submit" value="Usuń" onclick="return confirm('Czy na pewno chcesz usunąć ten ilosc_miesiecy?');">
                    </td>
                </form>

            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>