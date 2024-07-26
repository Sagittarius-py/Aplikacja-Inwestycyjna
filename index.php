<?php
require 'auth.php';
require 'Database.php';
require_once 'Umowy.php';
check_login();

$user_id = $_SESSION['user_id'];

$database = new Database();
$db = $database->getConnection();

$umowy = new Umowy($db);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $umowy->user_id = $user_id;
            $umowy->nazwa_zadania = $_POST['nazwa_zadania'];
            $umowy->klasyfikacja = $_POST['klasyfikacja'];
            $umowy->nr_umowy = $_POST['nr_umowy'];
            $umowy->data_zawarcia = $_POST['data_zawarcia'];
            $umowy->data_zakon = $_POST['data_zakon'];
            $umowy->tresc = $_POST['tresc'];


            $umowy->create();
            header('Location: index.php');
        } elseif ($_POST['action'] == 'edit') {
            $umowy->id = $_POST['id'];
            $umowy->nazwa_zadania = $_POST['nazwa_zadania'];
            $umowy->klasyfikacja = $_POST['klasyfikacja'];
            $umowy->nr_umowy = $_POST['nr_umowy'];
            $umowy->data_zawarcia = $_POST['data_zawarcia'];
            $umowy->data_zakon = $_POST['data_zakon'];
            $umowy->tresc = $_POST['tresc'];


            $umowy->update();
            header('Location: index.php');
        } elseif ($_POST['action'] == 'delete') {
            $umowy->id = $_POST['id'];

            $umowy->delete();
            header('Location: index.php');
        }
    }
}

// Pobieranie listy umów
$stmt = $umowy->read($user_id);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Umowy</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <nav>
        <a href="logout.php"><button>Wyloguj się</button></a>
    </nav>
    <h1>Umowy</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nazwa Zadania</th>
            <th>Klasyfikacja</th>
            <th>Nr Umowy</th>
            <th>Data Zawarcia</th>
            <th>Data Zakończenia</th>
            <th>Treść</th>
            <th colspan="2">Akcje</th>
            <th>Właściwości</th>
        </tr>
        <form action="index.php" method="post">
            <tr>
                <td></td>
                <input type="hidden" name="action" value="create">
                <td><input type="text" id="nazwa_zadania" name="nazwa_zadania" required></td>
                <td><input type="text" id="klasyfikacja" name="klasyfikacja" required></td>
                <td><input type="text" id="nr_u'mowy" name="nr_umowy" required></td>
                <td><input type="date" min="2000-01-01" max="2100-12-30" id="data_zawarcia" name="data_zawarcia" required></td>
                <td><input type="date" min="2000-01-01" max="2100-12-30" id="data_zakon" name="data_zakon" required></td>
                <td><textarea id="tresc" name="tresc" required></textarea></td>
                <td colspan="2"><input type="submit" value="Dodaj umowę"></td>
            </tr>
        </form>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <form action="index.php?id=<?php echo htmlspecialchars($row['id']); ?>" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="edit">
                    <td><input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><input type="text" name="nazwa_zadania" value="<?php echo htmlspecialchars($row['nazwa_zadania']); ?>"></td>
                    <td><input type="text" name="klasyfikacja" value="<?php echo htmlspecialchars($row['klasyfikacja']); ?>"></td>
                    <td><input type="text" name="nr_umowy" value="<?php echo htmlspecialchars($row['nr_umowy']); ?>"></td>
                    <td><input type="date" min="2000-01-01" max="2100-12-30" name="data_zawarcia" value="<?php echo htmlspecialchars($row['data_zawarcia']); ?>"></td>
                    <td><input type="date" min="2000-01-01" max="2100-12-30" name="data_zakon" value="<?php echo htmlspecialchars($row['data_zakon']); ?>"></td>
                    <td><input type="text" name="tresc" value="<?php echo htmlspecialchars($row['tresc']); ?>"></td>
                    <td>
                        <input type="submit" value="Edytuj">

                    </td>
                </form>
                <form action="index.php?id=<?php echo htmlspecialchars($row['id']); ?>" method="post" style="display:inline;">
                    <td>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="submit" value="Usuń" onclick="return confirm('Czy na pewno chcesz usunąć tę fakturę?');">
                    </td>
                </form>
                <td>
                    <a href="faktury.php?umowa_id=<?php echo $row['id']; ?>"><button>Faktury</button></a>
                    <a href="aneksy.php?umowa_id=<?php echo $row['id']; ?>"><button>Aneksy</button></a>
                    <a href="gwarancje.php?umowa_id=<?php echo $row['id']; ?>"><button>Gwarancje</button></a>
                    <a href="fundusze.php?umowa_id=<?php echo $row['id']; ?>"><button>Fundusze</button></a>
                    <a href="kontrahenci.php?umowa_id=<?php echo $row['id']; ?>"><button>Kontrahenci</button></a>

                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>