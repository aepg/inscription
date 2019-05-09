<?php

class ParticipantDao {

    private $database;
    private $conn;

    function __construct(MySql $database) {
        $this->database = $database;
        $this->conn = $this->database->conn;
    }

    function findAllParticipants() {
        $req="SELECT * FROM participant";
        return $this->database->allRecords($req);
      }

    function findParticipantById($id ) {
      $req="SELECT * FROM participant WHERE id=".$id;
      return $this->database->record($req);
    }


    function hasParticipant($id_activite, $id_horaire) {
      $req="SELECT count(*) as c FROM participant WHERE id_horaire=:id_horaire AND id_activite=:id_activite";
      
      $stmt = $this->conn->prepare($req);
      $stmt->bindParam(':id_horaire', $id_horaire, PDO::PARAM_INT);
      $stmt->bindParam(':id_activite', $id_activite, PDO::PARAM_INT);

      if(!$stmt->execute()) {
        echo "\nPDO::errorInfo():\n";
        print_r($this->conn->errorInfo());
        return false;
      }

      $nb_count = $stmt->fetch()['c'];

      error_log("NB PARTICIPANT: " . $nb_count, 0);
      return $nb_count > 0;
    }
    

    function supprimerParticipant($id_participant) {
      $req = "DELETE FROM participant WHERE id=:id";

      $stmt = $this->conn->prepare($req);
      $stmt->bindParam(':id', $id_participant, PDO::PARAM_INT);

      if(!$stmt->execute()) {
        echo "\nPDO::errorInfo():\n";
        print_r($this->conn->errorInfo());
        return false;
      }
      return true;
    }


    function createParticipant($id_horaire, $id_activite, $nom, $prenom, $mail, $telephone) {
      // formate le nom / prénom
      $nom = strtoupper($nom);
      $prenom = ucwords(strtolower($prenom));

      $req = "INSERT INTO participant (id_activite, id_horaire, nom, prenom, email, telephone) 
      VALUES(:id_activite, :id_horaire, :nom, :prenom, :email, :telephone)";

      $stmt = $this->conn->prepare($req);
      $stmt->bindParam(':id_activite', $id_activite, PDO::PARAM_INT);
      $stmt->bindParam(':id_horaire', $id_horaire, PDO::PARAM_INT);
      $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
      $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
      
      if (!$stmt) {
        echo "\nPDO::errorInfo():\n";
        print_r($this->conn->errorInfo());
        return array();
      }

      if(!$stmt->execute()) {
        echo "\nPDO::errorInfo():\n";
        print_r($this->conn->errorInfo());
        return false;
      }

      return true;
    }


}


?>