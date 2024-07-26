<?php
class Gwarancja
{
    private $conn;
    private $table_name = "gwarancje";

    public $gwarancja_id;
    public $umowa_id;
    public $ilosc_miesiecy;
    public $pierwszy_przeglad;
    public $termin_uplywu;
    public $przeglady;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET umowa_id=:umowa_id, ilosc_miesiecy=:ilosc_miesiecy, termin_uplywu=:termin_uplywu, pierwszy_przeglad=:pierwszy_przeglad, przeglady=:przeglady";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":umowa_id", $this->umowa_id);
        $stmt->bindParam(":ilosc_miesiecy", $this->ilosc_miesiecy);
        $stmt->bindParam(":termin_uplywu", $this->termin_uplywu);
        $stmt->bindParam(":pierwszy_przeglad", $this->pierwszy_przeglad);
        $stmt->bindParam(":przeglady", $this->przeglady);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function read()
    {
        $query = "SELECT f.*, u.nr_umowy FROM " . $this->table_name . " f LEFT JOIN umowy u ON f.umowa_id = u.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function readByUmowaId($umowa_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE umowa_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $umowa_id);
        $stmt->execute();

        return $stmt;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET ilosc_miesiecy = :ilosc_miesiecy, termin_uplywu =:termin_uplywu, pierwszy_przeglad = :pierwszy_przeglad, przeglady = :przeglady WHERE gwarancja_id = :gwarancja_id";

        $stmt = $this->conn->prepare($query);

        $this->ilosc_miesiecy = htmlspecialchars(strip_tags($this->pierwszy_przeglad));
        $this->termin_uplywu = htmlspecialchars(strip_tags($this->termin_uplywu));
        $this->pierwszy_przeglad = htmlspecialchars(strip_tags($this->ilosc_miesiecy));
        $this->przeglady = htmlspecialchars(strip_tags($this->przeglady));

        $stmt->bindParam(":ilosc_miesiecy", $this->ilosc_miesiecy);
        $stmt->bindParam(":termin_uplywu", $this->termin_uplywu);
        $stmt->bindParam(":pierwszy_przeglad", $this->pierwszy_przeglad);
        $stmt->bindParam(":przeglady", $this->przeglady);
        $stmt->bindParam(":gwarancja_id", $this->gwarancja_id);


        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE aneks_id = ?";

        $stmt = $this->conn->prepare($query);

        $this->gwarancja_id = htmlspecialchars(strip_tags($this->gwarancja_id));

        $stmt->bindParam(1, $this->gwarancja_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
