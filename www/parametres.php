<?php

include_once 'mysql.php';
include_once 'parametre-dao.php';
$mysql = new MySql();
$dao = new ParametreDao($mysql);

// TODO a simplifier : il ne faudrait pas nécessairement vérifier l'ID
//   juste savoir si on est en GET ou POST, et éditer le seul ID qui existe en base
// Mais ca peut être utile si un jour on veut prévoir de faire du multi-tableaux.
$mode = isset($_GET["id"]) ? "EDITION" : "CREATION";

if($mode == "EDITION") {
  $id_parametre = $_GET["id"];
  $parametre = $dao->findParametreById($id_parametre);
} else {
   // pas nécessaire ici
}

if(isset($_POST["form_action"])) {
  // si on a un POST on est en enregistrement
  $mode = "ENREGISTREMENT";
}

$erreurs = array();

if($mode == "ENREGISTREMENT") {
 
 // si l'id est fourni, alors, on modifie une fiche
 if(isset($_POST["id_parametre"]) && !empty($_POST["id_parametre"])) {
    $id_parametre = $_POST["id_parametre"];
    $parametre = $dao->findParametreById($id_parametre);
  } 

  if(isset($_POST["titre"]) && !empty($_POST["titre"])) {
    $parametre["titre"] = $_POST["titre"];
  } else {
    array_push($erreurs, "Le titre est obligatoire");
  }
  
  if(isset($_POST["sous_titre"]) && !empty($_POST["sous_titre"])) {
    $parametre["sous_titre"] = $_POST["sous_titre"];
  } else {
    array_push($erreurs, "Le sous_titre est obligatoire");
  }
  
        
  if(isset($_POST["email_obligatoire"]) ) {
    $parametre["email_obligatoire"] = $_POST["email_obligatoire"];
  } else {
    array_push($erreurs, "L'email_obligatoire est obligatoire");
  }

  if(isset($_POST["tel_obligatoire"])) {
    $parametre["tel_obligatoire"] = $_POST["tel_obligatoire"];
  } else {
    array_push($erreurs, "Le tel_obligatoire est obligatoire");
  }

  if(isset($_POST["mode_anonyme"])) {
    $parametre["mode_anonyme"] = $_POST["mode_anonyme"];
  } else {
    array_push($erreurs, "Le mode_anonyme est obligatoire");
  }


  if(empty($erreurs)) {
    
    $dao->saveParametre($parametre);
  
    header('Location: /parametres.php?id=1'); 
  }
  
}

?><!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Florent Dupont">
   
    <title>AEPG - Parametres généraux</title>
   
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
      <h2>Paramètres Généraux</h2>
	  <a class="btn btn-outline-secondary" href="admin.php" role="button">&larrhk; Retour à l'Admin</a>
    </div>
 
	
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

        <h4 class="mb-3">Paramètres Généraux</h4>
        
        <div class="mb-3">
          <label for="libelle">Titre du site</label>
          <input type="text" class="form-control" name="titre" value="<?php echo $parametre["titre"] ?>" placeholder="LUN., MAR., etc." required>
          
        </div>

        <div class="mb-3">
          <label for="numero">Sous-Titre du site</label>
          <input type="text" class="form-control" name="sous_titre" value="<?php echo $parametre["sous_titre"] ?>" placeholder="29, 30, etc." required>
        
        </div>

        <div class="mb-3">
          <label for="email_obligatoire">Adresse e-mail obligatoire</label>
          <input type="text" class="form-control" name="email_obligatoire" value="<?php echo $parametre["email_obligatoire"] ?>" placeholder="10h-11h, etc.">
        </div>

		<div class="mb-3">
          <label for="tel_obligatoire">Téléphone obligatoire</label>
          <input type="text" class="form-control" name="tel_obligatoire" value="<?php echo $parametre["tel_obligatoire"] ?>" placeholder="10h-11h, etc.">
        </div>
          
		<div class="mb-3">
          <label for="mode_anonyme">Mode Anonyme</label>
          <input type="text" class="form-control" name="mode_anonyme" value="<?php echo $parametre["mode_anonyme"] ?>" placeholder="10h-11h, etc.">
        </div>

        <hr class="mb-4">
        <div class="row">
          <div class="col-md-6">
            <a class="btn btn-secondary btn-lg btn-block" href="admin.php" role="button">Retour</a>
          </div>
          <div class="col-md-6">
            <button class="btn btn-primary btn-lg btn-block" type="submit"><?php echo $mode != "EDITION" ? "Enregistrer les paramètres" : "Enregistrer les paramètres" ?></button>
          </div>
        </div>
      </div>

      <div class="col-md-2"> </div>
    
    </div>

    <input type="hidden" id="form_action" name="form_action" value="<?php echo "edition" ?>" >
    <input type="hidden" id="id_parametre" name="id_parametre" value="<?php echo "" . $id_parametre ?>" >
  
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
