<?php
class Umowy
{
    private $conn;
    private $table_name = "umowy";

    public $id;
    public $user_id;
    public $nazwa_zadania;
    public $klasyfikacja;
    public $nr_umowy;
    public $data_zawarcia;
    public $data_zakon;
    public $tresc;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id, nazwa_zadania=:nazwa_zadania, klasyfikacja=:klasyfikacja, nr_umowy=:nr_umowy, data_zawarcia=:data_zawarcia, data_zakon=:data_zakon, tresc=:tresc";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":nazwa_zadania", $this->nazwa_zadania);
        $stmt->bindParam(":klasyfikacja", $this->klasyfikacja);
        $stmt->bindParam(":nr_umowy", $this->nr_umowy);
        $stmt->bindParam(":data_zawarcia", $this->data_zawarcia);
        $stmt->bindParam(":data_zakon", $this->data_zakon);
        $stmt->bindParam(":tresc", $this->tresc);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    function read($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id =" . $id;
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt; // Fetching data as an associative array

        }
        return false;
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . " SET nazwa_zadania=:nazwa_zadania, klasyfikacja=:klasyfikacja, nr_umowy=:nr_umowy, data_zawarcia=:data_zawarcia, data_zakon=:data_zakon, tresc=:tresc WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nazwa_zadania", $this->nazwa_zadania);
        $stmt->bindParam(":klasyfikacja", $this->klasyfikacja);
        $stmt->bindParam(":nr_umowy", $this->nr_umowy);
        $stmt->bindParam(":data_zawarcia", $this->data_zawarcia);
        $stmt->bindParam(":data_zakon", $this->data_zakon);
        $stmt->bindParam(":tresc", $this->tresc);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
