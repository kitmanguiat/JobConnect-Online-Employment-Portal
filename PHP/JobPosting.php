<?php
public function getEmployerInfo($employer_id) {
    try {
        $sql = "SELECT * FROM employers WHERE user_id = :employer_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':employer_id', $employer_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}
?>