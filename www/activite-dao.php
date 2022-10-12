<?php

class ActiviteDao {

    private $database;
    private $conn;

    function __construct(MySql $database) {
        $this->database = $database;
        $this->conn = $this->database->conn;
    }

    function creerActivite($activite) {

        $req = "SELECT tri t FROM activite ORDER BY tri DESC LIMIT 1";
        $stmt = $this->conn->prepare($req);
        if(!$stmt->execute()) {
          error_log("erreur !", 0);
        }
        $next_tri = $stmt->fetch()['t'] + 1;
    
        $req = "INSERT INTO activite (libelle, tri)
                VALUES (:libelle, :tri)";
        $stmt = $this->conn->prepare($req);
    
        $stmt->bindParam(':libelle', $activite["libelle"], PDO::PARAM_STR);
        $stmt->bindParam(':tri', $next_tri, PDO::PARAM_INT);
    
        if(!$stmt->execute()) {
          echo "\nPDO::errorInfo():\n";
          print_r($this->conn->errorInfo());
          return false;
        }
        return true;
    }
    
    
    function supprimerActivite($id_activite) {
        $req = "DELETE FROM activite WHERE id=:id";
        $stmt = $this->conn->prepare($req);
        $stmt->bindParam(':id', $id_activite, PDO::PARAM_INT);
        
        if(!$stmt->execute()) {
            // si la suppression ne veut pas se faire : PB de contraintes !!
            return false;
        }
        return true;
    }
    
    function findActiviteById($id) {
    
        $req = "SELECT * FROM activite WHERE id = :id";
        $stmt = $this->conn->prepare($req);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if(!$stmt->execute()) {
            echo "\nPDO::errorInfo():\n";
            print_r($this->conn->errorInfo());
            error_log("erreur !", 0);
        }
        return $stmt->fetch();
    }

    function findAllActivites() {
        $req = "SELECT * FROM activite ORDER BY tri";
        return $this->database->allRecords($req);
    }

    function decrementerTriActivite($id_activite) {
        $cur_activite = $this->findActiviteById($id_activite);
        $cur_tri = $cur_activite["tri"];
  
        // récupere le précédent activite avec lequel switcher
        $req = "SELECT * FROM activite where tri < :tri ORDER BY tri DESC LIMIT 1";
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
  
        $switched_activite = $stmt->fetch();
       
        $cur_activite["tri"] = $switched_activite["tri"];
        $switched_activite["tri"] = $cur_tri;
  
        $this->saveActivite($switched_activite);
        $this->saveActivite($cur_activite);
    }
  
    function incrementerTriActivite($id_activite) {
      
      $cur_activite = $this->findActiviteById($id_activite);
      $cur_tri = $cur_activite["tri"];
  
      // récupere le précédent activite avec lequel switcher
      $req = "SELECT * FROM activite where tri > :tri ORDER BY tri ASC LIMIT 1";
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
  
      $switched_activite = $stmt->fetch();
     
      $cur_activite["tri"] = $switched_activite["tri"];
      $switched_activite["tri"] = $cur_tri;
  
      $this->saveActivite($switched_activite);
      $this->saveActivite($cur_activite);
    }
  
    function saveActivite($activite) {
     
      $req = "UPDATE activite 
              SET tri = :tri,
                  libelle = :libelle
              WHERE id = :id";
      $stmt = $this->conn->prepare($req);
  
      $stmt->bindParam(':id', $activite["id"], PDO::PARAM_INT);
      $stmt->bindParam(':tri', $activite["tri"], PDO::PARAM_INT);
      $stmt->bindParam(':libelle', $activite["libelle"], PDO::PARAM_STR);
      
      if(!$stmt->execute()) {
        echo "\nPDO::errorInfo():\n";
        print_r($this->conn->errorInfo());
        return false;
      }
      return true;
    }
  

}


?>