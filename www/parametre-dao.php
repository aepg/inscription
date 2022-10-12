<?php

class ParametreDao {

    private $database;
    private $conn;

    function __construct(MySql $database) {
        $this->database = $database;
        $this->conn = $this->database->conn;
    }

    function findParametreById($id) {
      $req="SELECT * FROM parametre WHERE id=".$id;
      return $this->database->record($req);
    }
    

    function saveParametre($parametre) {
       
      $req = "UPDATE parametre 
              SET titre = :titre,
                  sous_titre = :sous_titre,
                  email_obligatoire = :email_obligatoire,
                  tel_obligatoire = :tel_obligatoire,
                  mode_anonyme = :mode_anonyme
              WHERE id = :id";
      $stmt = $this->conn->prepare($req);
  
      $stmt->bindParam(':id', $parametre["id"], PDO::PARAM_INT);
      $stmt->bindParam(':titre', $parametre["titre"], PDO::PARAM_STR);
      $stmt->bindParam(':sous_titre', $parametre["sous_titre"], PDO::PARAM_STR);
      $stmt->bindParam(':email_obligatoire', $parametre["email_obligatoire"], PDO::PARAM_INT);
      $stmt->bindParam(':tel_obligatoire', $parametre["tel_obligatoire"], PDO::PARAM_INT);
      $stmt->bindParam(':mode_anonyme', $parametre["mode_anonyme"], PDO::PARAM_INT);
  
      if(!$stmt->execute()) {
        echo "\nPDO::errorInfo():\n";
        print_r($this->conn->errorInfo());
        return false;
      }
      return true;
    }
  

}


?>