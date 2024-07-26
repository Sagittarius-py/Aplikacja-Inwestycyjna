<?php
class Aneks
{
    private $conn;
    private $table_name = "aneksy";

    public $aneks_id;
    public $umowa_id;
    public $aneks;
    public $data_zawarcia;
    public $tresc_aneksu;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET umowa_id=:umowa_id, aneks=:aneks, data_zawarcia=:data_zawarcia, tresc_aneksu=:tresc_aneksu";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":umowa_id", $this->umowa_id);
        $stmt->bindParam(":aneks", $this->aneks);
        $stmt->bindParam(":data_zawarcia", $this->data_zawarcia);
        $stmt->bindParam(":tresc_aneksu", $this->tresc_aneksu);

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
        $query = "UPDATE " . $this->table_name . " SET aneks = :aneks, data_zawarcia = :data_zawarcia, tresc_aneksu = :tresc_aneksu WHERE aneks_id = :aneks_id";

        $stmt = $this->conn->prepare($query);

        $this->aneks = htmlspecialchars(strip_tags($this->aneks));
        $this->data_zawarcia = htmlspecialchars(strip_tags($this->data_zawarcia));
        $this->tresc_aneksu = htmlspecialchars(strip_tags($this->tresc_aneksu));


        $stmt->bindParam(":aneks", $this->aneks);
        $stmt->bindParam(":data_zawarcia", $this->data_zawarcia);
        $stmt->bindParam(":tresc_aneksu", $this->tresc_aneksu);
        $stmt->bindParam(":aneks_id", $this->aneks_id);


        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE aneks_id = ?";

        $stmt = $this->conn->prepare($query);

        $this->aneks_id = htmlspecialchars(strip_tags($this->aneks_id));

        $stmt->bindParam(1, $this->aneks_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
