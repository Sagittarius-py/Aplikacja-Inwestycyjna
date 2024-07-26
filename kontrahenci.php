<?php
session_start();

require_once 'Database.php';
require_once 'Kontrahent.php';
require_once 'Umowy.php';

$database = new Database();
$db = $database->getConnection();

$kontrahent = new Kontrahent($db);

$umowa_id = isset($_GET['umowa_id']) ? $_GET['umowa_id'] : die('ERROR: Umowa ID not found. cokolwiek');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $kontrahent->kontrahent = $_POST['kontrahent'];
            $kontrahent->umowa_id = $_POST['umowa_id'];

            $kontrahent->addKontrahentWithUmowa();
            header('Location: kontrahenci.php?umowa_id=' . $umowa_id);
            exit(); // Make sure to exit after redirect
        } elseif ($_POST['action'] == 'edit') {
            $kontrahent->kontrahent_id = $_POST['kontrahent_id'];
            $kontrahent->kontrahent = $_POST['kontrahent'];

            $kontrahent->update();
            header('Location: kontrahenci.php?umowa_id=' . $umowa_id);
            exit(); // Make sure to exit after redirect
        } elseif ($_POST['action'] == 'delete') {
            $kontrahent->kontrahent_id = $_POST['kontrahent_id'];

            $kontrahent->delete();
            header('Location: kontrahenci.php?umowa_id=' . $umowa_id);
            exit(); // Make sure to exit after redirect
        } elseif ($_POST['action'] == 'add_existing') {
            $kontrahent_id = $_POST['kontrahent_id'];

            if (!$kontrahent->isKontrahentLinkedToUmowa($kontrahent_id, $umowa_id)) {
                $kontrahent->addExistingKontrahentToUmowa($kontrahent_id, $umowa_id);
            }
            header('Location: kontrahenci.php?umowa_id=' . $umowa_id);
            exit(); // Make sure to exit after redirect
        } elseif ($_POST['action'] == 'remove_current') {
            $kontrahent_id = $_POST['kontrahent_id'];

            $kontrahent->removeFromCurrentUmowa($kontrahent_id, $umowa_id);
            header('Location: kontrahenci.php?umowa_id=' . $umowa_id);
            exit(); // Make sure to exit after redirect
        }
    }
}

// Pobieranie listy kontrahentów dla obecnej umowy
$stmt = $kontrahent->readByUmowaId($umowa_id);

// Pobieranie listy wszystkich dostępnych kontrahentów
$all_kontrahents_stmt = $kontrahent->read();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Kontrahenci</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <nav>
        <a href="logout.php"><button>Wyloguj się</button></a>
        <a href="index.php"><button>Umowy</button></a>
    </nav>
    <h1>Kontrahenci</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Kontrahent</th>
            <th colspan="2" class="actions">Akcje</th>
        </tr>
        <tr>
            <form action="kontrahenci.php?umowa_id=<?php echo htmlspecialchars($umowa_id); ?>" method="post">
                <input type="hidden" name="action" value="create">
                <input type="hidden" id="umowa_id" name="umowa_id" value="<?php echo htmlspecialchars($umowa_id); ?>" required>
                <td></td>
                <td><input type="text" id="kontrahent" name="kontrahent" required></td>
                <td><input type="submit" value="Dodaj kontrahenta"></td>
            </form>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <form action="kontrahenci.php?umowa_id=<?php echo htmlspecialchars($umowa_id); ?>&id=<?php echo htmlspecialchars($row['kontrahent_id']); ?>" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="umowa_id" value="<?php echo htmlspecialchars($row['umowa_id']); ?>">
                    <input type="hidden" name="kontrahent_id" value="<?php echo htmlspecialchars($row['kontrahent_id']); ?>">
                    <td><?php echo htmlspecialchars($row['kontrahent_id']); ?></td>
                    <td><input type="text" name="kontrahent" value="<?php echo htmlspecialchars($row['kontrahent']); ?>"></td>
                    <td><input type="submit" value="Edytuj"></td>
                </form>
                <form action="kontrahenci.php?umowa_id=<?php echo htmlspecialchars($umowa_id); ?>&id=<?php echo htmlspecialchars($row['kontrahent_id']); ?>" method="post" style="display:inline;">
                    <input type="hidden" name="kontrahent_id" value="<?php echo htmlspecialchars($row['kontrahent_id']); ?>">
                    <input type="hidden" name="umowa_id" value="<?php echo htmlspecialchars($row['umowa_id']); ?>">
                    <input type="hidden" name="action" value="remove_current">
                    <td><input type="submit" value="Usuń z tej umowy"></td>
                </form>

            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Dodaj istniejącego kontrahenta do umowy</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Kontrahent</th>
            <th colspan="2" class="actions">Akcja</th>
        </tr>
        <?php while ($row = $all_kontrahents_stmt->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <form action="kontrahenci.php?umowa_id=<?php echo htmlspecialchars($umowa_id); ?>" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="add_existing">
                    <input type="hidden" name="kontrahent_id" value="<?php echo htmlspecialchars($row['kontrahent_id']); ?>">
                    <td><?php echo htmlspecialchars($row['kontrahent_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['kontrahent']); ?></td>
                    <td><input type="submit" value="Dodaj do umowy"></td>
                </form>
                <form action="kontrahenci.php?umowa_id=<?php echo htmlspecialchars($umowa_id); ?>&id=<?php echo htmlspecialchars($row['kontrahent_id']); ?>" method="post" style="display:inline;">
                    <input type="hidden" name="kontrahent_id" value="<?php echo htmlspecialchars($row['kontrahent_id']); ?>">
                    <input type="hidden" name="action" value="delete">
                    <td><input type="submit" value="Usuń z bazy danych" onclick="return confirm('Czy na pewno chcesz usunąć tego kontrahenta?');"></td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>