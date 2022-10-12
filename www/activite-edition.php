<?php

include_once 'mysql.php';
include_once 'activite-dao.php';

$mysql = new MySql();
$dao = new ActiviteDao($mysql);

// mode CREATION par défaut, si aucun ID n'est fourni
$mode = isset($_GET["id"]) ? "EDITION" : "CREATION";

if($mode == "EDITION") {
  $id_activite = $_GET["id"];
  $activite = $dao->findActiviteById($id_activite);
} else {
  $id_activite = null;
  $activite = array();
  $activite["libelle"] = null;
}

if(isset($_POST["form_action"])) {
  // si on a un POST on est en enregistrement
  $mode = "ENREGISTREMENT";
}

$erreurs = array();

if($mode == "ENREGISTREMENT") {

  // si l'id est fourni, alors, on modifie une fichie
  if(isset($_POST["id_activite"]) && !empty($_POST["id_activite"])) {
    $id_activite = $_POST["id_activite"];
    $activite = $dao->findActiviteById($id_activite);
  } 

  if(isset($_POST["libelle"]) && !empty($_POST["libelle"])) {
    $activite["libelle"] = $_POST["libelle"];
  } else {
    array_push($erreurs, "Le libellé est obligatoire");
  }
  
  if(empty($erreurs)) {
    if(isset($id_activite)) {
      $dao->saveActivite($activite);
    } else {
      $dao->creerActivite($activite);
    }

    header('Location: activites.php'); 
  }
  
}

?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Florent Dupont">
   
    <title>AEPG - Activites disponibles</title>
   
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    
  </head>
  <body class="bg-light">

  <div class="container">
    
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="logo_carre.jpg" alt="" width="72" height="72">
      <h2>Création d'une nouvelle activité</h2>
    </div>
 
    <div class="row">
      <div class="col"></div>
      <div class="col-8">
        <?php foreach($erreurs as $erreur) { ?>
        <div class="alert alert-danger" role="alert">
          <strong><?php echo $erreur;?></strong>
        </div>
        <?php } ?>
      </div>
      <div class="col"></div>
    </div>
    <form class="needs-validation" novalidate method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    
    <div class="row">
      
      <div class="col-md-2"> </div>
      <div class="col-md-8">

        <h4 class="mb-3">Nouvelle activité</h4>
        
        <div class="mb-3">
          <label for="libelle">Libellé d' l'activité</label>
          <input type="text" class="form-control" name="libelle" value="<?php echo $activite["libelle"] ?>" placeholder="Poney, ..." required>
          
        </div>
      
          
        <hr class="mb-4">
        <div class="row">
          <div class="col-md-6">
            <a class="btn btn-secondary btn-lg btn-block" href="activites.php" role="button">Retour</a>
          </div>
          <div class="col-md-6">
            <button class="btn btn-primary btn-lg btn-block" type="submit"><?php echo $mode != "EDITION" ? "Créer l'activité" : "Modifier l'activité" ?></button>
          </div>
        </div>
      </div>

      <div class="col-md-2"> </div>
    
    </div>

    <input type="hidden" id="form_action" name="form_action" value="<?php echo "edition" ?>" >
    <input type="hidden" id="id_activite" name="id_activite" value="<?php echo "" . $id_activite ?>" >
  
  </form>

  <footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; 2019 AEPG</p>
  </footer>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
       
</body>
</html>
