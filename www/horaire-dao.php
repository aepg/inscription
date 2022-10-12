<?php

class HoraireDao {

    private $database;
    private $conn;

    function __construct(MySql $database) {
        $this->database = $database;
        $this->conn = $this->database->conn;
    }

    function findHoraireById($id) {
    
        $req = "SELECT * FROM horaire WHERE id = :id";
        $stmt = $this->conn->prepare($req);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        if(!$stmt->execute()) {
          echo "\nPDO::errorInfo():\n";
          print_r($this->conn->errorInfo());
          return false;
        }
    
        return $stmt->fetch();
      }
    
      function decrementerTriHoraire($id_horaire) {
          // l'horaire courant
          $cur_horaire = $this->findHoraireById($id_horaire);
          $cur_tri = $cur_horaire["tri"];
    
          // récupere le précédent horaire avec lequel switcher
          $req = "SELECT * FROM horaire where tri < :tri ORDER BY tri DESC LIMIT 1";
          $stmt = $this->conn->prepare($req);
          $stmt->bindParam(':tri', $cur_tri, PDO::PARAM_INT);
    
          if(!$stmt->execute()) {
            echo "\nPDO::errorInfo():\n";
            print_r($this->conn->errorInfo());
            error_log("erreur !", 0);
          }
    
          if($stmt->rowCount() == 0) {
            // pas de précédent, pas posibilité de switcher !
           return;
          } 
    
          $switched_horaire = $stmt->fetch();
         
          $cur_horaire["tri"] = $switched_horaire["tri"];
          $switched_horaire["tri"] = $cur_tri;
    
          $this->saveHoraire($switched_horaire);
          $this->saveHoraire($cur_horaire);
      }
    
      function incrementerTriHoraire($id_horaire) {
        // l'horaire courant
        $cur_horaire = $this->findHoraireById($id_horaire);
        $cur_tri = $cur_horaire["tri"];
    
        // récupere le précédent horaire avec lequel switcher
        $req = "SELECT * FROM horaire where tri > :tri ORDER BY tri ASC LIMIT 1";
        $stmt = $this->conn->prepare($req);
        $stmt->bindParam(':tri', $cur_tri, PDO::PARAM_INT);
    
        if(!$stmt->execute()) {
          echo "\nPDO::errorInfo():\n";
          print_r($this->conn->errorInfo());
          error_log("erreur !", 0);
        }
    
        if($stmt->rowCount() == 0) {
          // pas de précédent, pas posibilité de switcher !
         return;
        } 
    
        $switched_horaire = $stmt->fetch();
       
        $cur_horaire["tri"] = $switched_horaire["tri"];
        $switched_horaire["tri"] = $cur_tri;
    
        $this->saveHoraire($switched_horaire);
        $this->saveHoraire($cur_horaire);
      }
    
      function saveHoraire($horaire) {
       
        $req = "UPDATE horaire 
                SET tri = :tri,
                    libelle_jour = :libelle_jour,
                    libelle_intervalle = :libelle_intervalle,
                    numero_jour = :numero_jour
                WHERE id = :id";
        $stmt = $this->conn->prepare($req);
    
        $stmt->bindParam(':id', $horaire["id"], PDO::PARAM_INT);
        $stmt->bindParam(':tri', $horaire["tri"], PDO::PARAM_INT);
        $stmt->bindParam(':libelle_jour', $horaire["libelle_jour"], PDO::PARAM_STR);
        $stmt->bindParam(':libelle_intervalle', $horaire["libelle_intervalle"], PDO::PARAM_STR);
        $stmt->bindParam(':numero_jour', $horaire["numero_jour"], PDO::PARAM_STR);
    
        if(!$stmt->execute()) {
          echo "\nPDO::errorInfo():\n";
          print_r($this->conn->errorInfo());
          return false;
        }
        return true;
      }
    
      
      function creerHoraire($horaire) {
    
        $req = "SELECT tri t FROM horaire ORDER BY tri DESC LIMIT 1";
        $stmt = $this->conn->prepare($req);
        if(!$stmt->execute()) {
          echo "\nPDO::errorInfo():\n";
          print_r($this->conn->errorInfo());
          error_log("erreur !", 0);
        }
        $next_tri = $stmt->fetch()['t'] + 1;
    
        $req = "INSERT INTO horaire (libelle_intervalle, libelle_jour, numero_jour, tri)
                VALUES (:libelle_intervalle, :libelle_jour, :numero_jour, :tri)";
        $stmt = $this->conn->prepare($req);
    
        $stmt->bindParam(':libelle_intervalle', $horaire["libelle_intervalle"], PDO::PARAM_STR);
        $stmt->bindParam(':libelle_jour', $horaire["libelle_jour"], PDO::PARAM_STR);
        $stmt->bindParam(':numero_jour', $horaire["numero_jour"], PDO::PARAM_STR);
        $stmt->bindParam(':tri', $next_tri, PDO::PARAM_INT);
    
        if(!$stmt->execute()) {
          echo "\nPDO::errorInfo():\n";
          print_r($this->conn->errorInfo());
          return false;
        }
        return true;
      }
    
     function supprimerHoraire($id_horaire) {
      $req = "DELETE FROM horaire WHERE id=:id";
      $stmt = $this->conn->prepare($req);
      $stmt->bindParam(':id', $id_horaire, PDO::PARAM_INT);
     
      if(!$stmt->execute()) {
        // si la suppression ne veut pas se faire : PB de contraintes !!
        return false;
      }
      return true;
    }
    
    
    
      
    function countAllHoraires() {
        $req = "SELECT COUNT(*) c FROM horaire ORDER BY tri";
        $stmt = $this->conn->prepare($req);
        if(!$stmt->execute()) {
            echo "\nPDO::errorInfo():\n";
            print_r($this->conn->errorInfo());
            error_log("erreur !", 0);
        }
        return $stmt->fetch()['c'];
    }
    
    function findAllHoraires() {
        $req = "SELECT * FROM horaire ORDER BY tri";
        return $this->database->allRecords($req);
    }
    
    

}


?>