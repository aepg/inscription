<?php

class CreneauDao {

    private $database;
    private $conn;

    function __construct(MySql $database) {
        $this->database = $database;
        $this->conn = $this->database->conn;
    }

    function findAllCreneaux() {
        $req="SELECT * FROM creneau";
        return $this->database->allRecords($req);
    }

    function creerCreneau($id_activite, $id_horaire) {

        $req = "INSERT INTO creneau (id_activite, id_horaire, nombre_participants)
                VALUES (:id_activite, :id_horaire, 1)";
        $stmt = $this->conn->prepare($req);
    
        $stmt->bindParam(':id_activite', $id_activite, PDO::PARAM_INT);
        $stmt->bindParam(':id_horaire', $id_horaire, PDO::PARAM_INT);
        
        if(!$stmt->execute()) {
          echo "\nPDO::errorInfo():\n";
          print_r($this->conn->errorInfo());
          return false;
        }
        return true;
    }

    function supprimerCreneau($id_activite, $id_horaire) {

        $req = "DELETE FROM creneau
                WHERE id_activite = :id_activite AND id_horaire = :id_horaire";
        $stmt = $this->conn->prepare($req);
    
        $stmt->bindParam(':id_activite', $id_activite, PDO::PARAM_INT);
        $stmt->bindParam(':id_horaire', $id_horaire, PDO::PARAM_INT);
        
        if(!$stmt->execute()) {
          echo "\nPDO::errorInfo():\n";
          print_r($this->conn->errorInfo());
          return false;
        }
        return true;
    }

    function modifierCreneau($id_activite, $id_horaire, $nombre_participants) {

        $req = "UPDATE creneau 
        SET nombre_participants = :nombre_participants
        WHERE id_activite = :id_activite AND id_horaire = :id_horaire";
        $stmt = $this->conn->prepare($req);

        $stmt->bindParam(':id_activite', $id_activite, PDO::PARAM_INT);
        $stmt->bindParam(':id_horaire', $id_horaire, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_participants', $nombre_participants, PDO::PARAM_INT);

        if(!$stmt->execute()) {
            echo "\nPDO::errorInfo():\n";
            print_r($this->conn->errorInfo());
            return false;
        }
        return true;
    }

}


?>