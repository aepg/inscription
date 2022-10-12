<?php

class MySql {

   private $host = "db";
   private $port = "3306";
   private $db_name = "inscription";
   private $username = "root";
   private $password = "root";
   public $conn;

  function __construct() {
    try {
      $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";port=" . $this->port, $this->username, $this->password);
      $this->conn->exec("set names utf8");

      // pour avoir les messages d'erreur dès le prepare
      $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false); 
        
    }catch(PDOException $exception){
      echo "Connection error: " . $exception->getMessage();
      echo "Connection error: " . $exception->getTraceAsString();
      flush();
    }
  }

  


  /* CONVENIENT MeTHoDs*/
  
   function query($req) {
      $stmt = $this->conn->prepare($req);
    
      if (!$stmt) {
        echo "\nPDO::errorInfo():\n";
        print_r($this->conn->errorInfo());
        return false;
      }

      if(!$stmt->execute()) {
        echo "\nPDO::errorInfo():\n";
        print_r($this->conn->errorInfo());
        return false;
      }

      return false;
   }

   function record($query) {
     
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        echo "\nPDO::errorInfo() STATEMENT:\n";
        print_r($this->conn->errorInfo());
        return array();
    }
    
    if(!$stmt->execute()) {
      echo "\nPDO::errorInfo() EXECUTE:\n";
      error_log("Error executing : " . $query);
      print_r($this->conn->errorInfo());
      return null;
    }

    return $stmt->fetch(PDO::FETCH_ASSOC);
   }



  

   function allRecords($query) {
   
    $result = array();

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        echo "\nPDO::errorInfo():\n";
        print_r($this->conn->errorInfo());
        return array();
    }

     // execute query
     $stmt->execute();

     $num = $stmt->rowCount();

     // check if more than 0 record found
     if($num > 0){
      //  echo "EXECUTE : " . strval($num);

         // retrieve our table contents
         // fetch() is faster than fetchAll()
         // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($result, $row);
         }
      }
      return $result;
   }


/*** OBSOLETE FUNCTIONS *************************/
   // OBSOLETE
   function updateError($location) {
      $this->errorNumber = mysql_errno();
      $this->errorMessage = mysql_error();
      $this->errorLocation = $location;
      if($this->errorNumber && SHOW_ERROR_MESSAGES) {
         echo('<br /><b>'.$this->errorLocation.'</b><br />'.$this->errorMessage);
         flush();
      }
   }

   // OBSOLETE
   /*function query($queryString) {
      if(!$this->connect()) {
          return false;
      }
      if(!mysql_select_db($this->dbName, $this->linkId)) {
         $this->updateError('DB::connect()<br />mysql_select_db');
         return false;
      }
      $this->queryId = mysql_query($queryString, $this->linkId);
      //$this->updateError('DB::query('.$queryString.')<br />mysql_query');
      $this->updateError('DB::query(Erreur de la requete)<br />mysql_query');
      if(!$this->queryId) {
       return false;
      }
      $this->currentRow = 0;
      return true;
   }*/

 // retourne tous les enregistrements dans un tableau
 function queryAllRecords($queryString) {
    if(!$this->query($queryString))  {
        return false;
    }
    $ret = array();
    while($line = $this->nextRecord())
    {
     $ret[] = $line;
    }
    return $ret;
 }

 // retourne un enregistrement dans un tableau
 function queryOneRecord($queryString)
 {
  if(!$this->query($queryString) || $this->numRows() != 1)
  {
   return false;
  }
  return $this->nextRecord();
 }

 // retourne l'enregistrement suivant dans un tableau
 function nextRecord()
 {
  $this->record = mysql_fetch_array($this->queryId);
  $this->updateError('DB::nextRecord()<br />mysql_fetch_array');
  if(!$this->record || !is_array($this->record))
  {
   return false;
  }
  $this->currentRow++;
  return $this->record;
 }

 // retourne le nombre d'enregistrements remont�s par la derniere requete
 function numRows()
 {
  return mysql_num_rows($this->queryId);
 }


}
?>
