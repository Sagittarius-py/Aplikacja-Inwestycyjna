<?php
class Kontrahent
{
    private $conn;
    private $table_name = "kontrahenci";
    private $relation_name = "kontrahenci_umowy";

    public $kontrahent_id;
    public $umowa_id;
    public $kontrahent;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function addKontrahentWithUmowa()
    {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " (kontrahent) VALUES (:kontrahent)";
            $stmt = $this->conn->prepare($query);

            $this->kontrahent = htmlspecialchars(strip_tags($this->kontrahent));
            $stmt->bindParam(':kontrahent', $this->kontrahent);

            if (!$stmt->execute()) {
                throw new Exception("Error inserting kontrahent");
            }

            $this->kontrahent_id = $this->conn->lastInsertId();

            $query = "INSERT INTO " . $this->relation_name . " (kontrahent_id, umowa_id) VALUES (:kontrahent_id, :umowa_id)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':kontrahent_id', $this->kontrahent_id);
            $stmt->bindParam(':umowa_id', $this->umowa_id);

            if (!$stmt->execute()) {
                throw new Exception("Error inserting into kontrahenci_umowy");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            echo "Failed: " . $e->getMessage();
            return false;
        }
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function readByUmowaId($umowa_id)
    {
        $query = "SELECT k.* FROM " . $this->table_name . " k 
                  LEFT JOIN kontrahenci_umowy ku ON k.kontrahent_id = ku.kontrahent_id 
                  WHERE ku.umowa_id = :umowa_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':umowa_id', $umowa_id);
        $stmt->execute();

        return $stmt;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET kontrahent = :kontrahent 
                  WHERE kontrahent_id = :kontrahent_id";

        $stmt = $this->conn->prepare($query);

        $this->kontrahent = htmlspecialchars(strip_tags($this->kontrahent));
        $this->kontrahent_id = htmlspecialchars(strip_tags($this->kontrahent_id));

        $stmt->bindParam(':kontrahent', $this->kontrahent);
        $stmt->bindParam(':kontrahent_id', $this->kontrahent_id);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE kontrahent_id = :kontrahent_id";

        $stmt = $this->conn->prepare($query);

        $this->kontrahent_id = htmlspecialchars(strip_tags($this->kontrahent_id));

        $stmt->bindParam(':kontrahent_id', $this->kontrahent_id);

        return $stmt->execute();
    }

    public function addExistingKontrahentToUmowa($kontrahent_id, $umowa_id)
    {
        $query = "INSERT INTO kontrahenci_umowy (umowa_id, kontrahent_id) 
                  VALUES (:umowa_id, :kontrahent_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':umowa_id', $umowa_id);
        $stmt->bindParam(':kontrahent_id', $kontrahent_id);

        return $stmt->execute();
    }

    public function removeFromCurrentUmowa($kontrahent_id, $umowa_id)
    {
        $query = "DELETE FROM kontrahenci_umowy 
                  WHERE kontrahent_id = :kontrahent_id 
                  AND umowa_id = :umowa_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':umowa_id', $umowa_id);
        $stmt->bindParam(':kontrahent_id', $kontrahent_id);

        return $stmt->execute();
    }

    public function isKontrahentLinkedToUmowa($kontrahent_id, $umowa_id)
    {
        $query = "SELECT * FROM kontrahenci_umowy 
                  WHERE kontrahent_id = :kontrahent_id 
                  AND umowa_id = :umowa_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':kontrahent_id', $kontrahent_id);
        $stmt->bindParam(':umowa_id', $umowa_id);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
