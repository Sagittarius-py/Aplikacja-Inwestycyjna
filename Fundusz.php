<?php
class Fundusz
{
    private $conn;
    private $table_name = "fundusze";

    public $fundusze_id;
    public $umowa_id;
    public $plan;
    public $rok;
    public $kwota_brutto;
    public $pozostaly_fundusz;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET umowa_id=:umowa_id, plan=:plan, rok=:rok, kwota_brutto=:kwota_brutto, pozostaly_fundusz=:pozostaly_fundusz";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":umowa_id", $this->umowa_id);
        $stmt->bindParam(":plan", $this->plan);
        $stmt->bindParam(":rok", $this->rok);
        $stmt->bindParam(":kwota_brutto", $this->kwota_brutto);
        $stmt->bindParam(":pozostaly_fundusz", $this->pozostaly_fundusz);

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
        $query = "UPDATE " . $this->table_name . " SET plan = :plan, rok = :rok, kwota_brutto = :kwota_brutto, pozostaly_fundusz = :pozostaly_fundusz WHERE fundusze_id = :fundusze_id";

        $stmt = $this->conn->prepare($query);

        $this->plan = htmlspecialchars(strip_tags($this->plan));
        $this->rok = htmlspecialchars(strip_tags($this->rok));
        $this->kwota_brutto = htmlspecialchars(strip_tags($this->kwota_brutto));


        $stmt->bindParam(":plan", $this->plan);
        $stmt->bindParam(":rok", $this->rok);
        $stmt->bindParam(":kwota_brutto", $this->kwota_brutto);
        $stmt->bindParam(":pozostaly_fundusz", $this->pozostaly_fundusz);
        $stmt->bindParam(":fundusze_id", $this->fundusze_id);


        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE fundusze_id=:fundusze_id";

        $stmt = $this->conn->prepare($query);

        $this->fundusze_id = htmlspecialchars(strip_tags($this->fundusze_id));
        echo ('<script>alert("cokolwiek")</script>');
        $stmt->bindParam(":fundusze_id", $this->fundusze_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
