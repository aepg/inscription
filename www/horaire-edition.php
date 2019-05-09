<?php

include_once 'mysql.php';
include_once 'horaire-dao.php';
$mysql = new MySql();
$dao = new HoraireDao($mysql);

// mode CREATION par défaut, si aucun ID n'est fourni
$mode = isset($_GET["id"]) ? "EDITION" : "CREATION";

if($mode == "EDITION") {
  $id_horaire = $_GET["id"];
  $horaire = $dao->findHoraireById($id_horaire);
} else {
  $id_horaire = null;
  $horaire = array();
  $horaire["libelle_intervalle"] = null;
  $horaire["numero_jour"] = null;
  $horaire["libelle_jour"] = null;
}

if(isset($_POST["form_action"])) {
  // si on a un POST on est en enregistrement
  $mode = "ENREGISTREMENT";
}

$erreurs = array();

if($mode == "ENREGISTREMENT") {

  // si l'id est fourni, alors, on modifie une fichie
  if(isset($_POST["id_horaire"]) && !empty($_POST["id_horaire"])) {
    $id_horaire = $_POST["id_horaire"];
    $horaire = $dao->findHoraireById($id_horaire);
  } 

  if(isset($_POST["libelle"]) && !empty($_POST["libelle"])) {
    $horaire["libelle_jour"] = $_POST["libelle"];
  } else {
    array_push($erreurs, "Le libellé est obligatoire");
  }
  
  if(isset($_POST["numero"]) && !empty($_POST["numero"])) {
    $horaire["numero_jour"] = $_POST["numero"];
  } else {
    array_push($erreurs, "Le numéro est obligatoire");
  }
  
  if(isset($_POST["intervalle"]) && !empty($_POST["intervalle"])) {
    $horaire["libelle_intervalle"] = $_POST["intervalle"];
  } else {
    array_push($erreurs, "L'intervalle de temps est obligatoire");
  }

  if(empty($erreurs)) {
    if(isset($id_horaire)) {
      $dao->saveHoraire($horaire);
    } else {
      $dao->creerHoraire($horaire);
    }

    header('Location: /horaires.php'); 
  }
  
}

?><!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Florent Dupont">
   
    <title>AEPG - Horaires disponibles</title>
   
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    
  </head>
  <body class="bg-light">

  

  <div class="container">
    
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="logo_carre.jpg" alt="" width="72" height="72">
      <h2>Création d'un nouvel horaire</h2>
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

        <h4 class="mb-3">Nouvel horaire</h4>
        
        <div class="mb-3">
          <label for="libelle">Libellé du jour</label>
          <input type="text" class="form-control" name="libelle" value="<?php echo $horaire["libelle_jour"] ?>" placeholder="LUN., MAR., etc." required>
          
        </div>

        <div class="mb-3">
          <label for="numero">N° du jour</label>
          <input type="text" class="form-control" name="numero" value="<?php echo $horaire["numero_jour"] ?>" placeholder="29, 30, etc." required>
        
        </div>

        <div class="mb-3">
          <label for="intervalle">Intervalle de temps</label>
          <input type="text" class="form-control" name="intervalle" value="<?php echo $horaire["libelle_intervalle"] ?>" placeholder="10h-11h, etc.">
        </div>
          
        <hr class="mb-4">
        <div class="row">
          <div class="col-md-6">
            <a class="btn btn-secondary btn-lg btn-block" href="horaires.php" role="button">Retour</a>
          </div>
          <div class="col-md-6">
            <button class="btn btn-primary btn-lg btn-block" type="submit"><?php echo $mode != "EDITION" ? "Créer l'horaire" : "Modifier l'horaire" ?></button>
          </div>
        </div>
      </div>

      <div class="col-md-2"> </div>
    
    </div>

    <input type="hidden" id="form_action" name="form_action" value="<?php echo "edition" ?>" >
    <input type="hidden" id="id_horaire" name="id_horaire" value="<?php echo "" . $id_horaire ?>" >
  
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
