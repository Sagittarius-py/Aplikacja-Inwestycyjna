<?php
class Faktura
{
    private $conn;
    private $table_name = "faktury";

    public $faktura_id;
    public $umowa_id;
    public $nr_faktury;
    public $data_faktury;
    public $kwota_faktury;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET umowa_id=:umowa_id, nr_faktury=:nr_faktury, data_faktury=:data_faktury, kwota_faktury=:kwota_faktury";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":umowa_id", $this->umowa_id);
        $stmt->bindParam(":nr_faktury", $this->nr_faktury);
        $stmt->bindParam(":data_faktury", $this->data_faktury);
        $stmt->bindParam(":kwota_faktury", $this->kwota_faktury);

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
        $query = "UPDATE " . $this->table_name . " SET nr_faktury = :nr_faktury, data_faktury = :data_faktury, kwota_faktury = :kwota_faktury WHERE faktura_id = :faktura_id";

        $stmt = $this->conn->prepare($query);

        $this->nr_faktury = htmlspecialchars(strip_tags($this->nr_faktury));
        $this->data_faktury = htmlspecialchars(strip_tags($this->data_faktury));
        $this->kwota_faktury = htmlspecialchars(strip_tags($this->kwota_faktury));
        $this->faktura_id = htmlspecialchars(strip_tags($this->faktura_id));

        $stmt->bindParam(":nr_faktury", $this->nr_faktury);
        $stmt->bindParam(":data_faktury", $this->data_faktury);
        $stmt->bindParam(":kwota_faktury", $this->kwota_faktury);
        $stmt->bindParam(":faktura_id", $this->faktura_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE faktura_id = ?";

        $stmt = $this->conn->prepare($query);

        $this->faktura_id = htmlspecialchars(strip_tags($this->faktura_id));

        $stmt->bindParam(1, $this->faktura_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
