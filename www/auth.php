<?php session_start();
    
    // https://www.julp.fr/articles/17-php-cookies-et-sessions.html#avant-de-poursuivre-un-mot-sur-les-fonctionnalites-obsoletes
      

    $erreurs = array();

    if( isset($_POST["login"]) && isset($_POST["password"])) {
      $login = $_POST["login"];
      $password = $_POST["password"];

      if($login == "admin" && $password == "0000") {
        $_SESSION['authenticated'] = true;
        header('Location: admin.php'); 
      } else {
          array_push($erreurs, "Erreur de connexion");        
      }
    } else {
        $login = "";
        $password = "";
    }
   

  ?><!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Florent Dupont">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <title>AEPG - Authentification</title>
   
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  </head>
  <body class="bg-light">

  

  <div class="container">
    
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="logo_carre.jpg" alt="" width="72" height="72">
      <h2>Authentification</h2>
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
    <form class="needs-validation" novalidate autocomplete="new-password" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    
    <div class="row">
      
      <div class="col"> </div>
      <div class="col-md-4">

        
        <div class="mb-3">
          <label for="libelle">Nom d'utilisateur</label>
          <input type="text" autocomplete="mlskdfop" class="form-control" name="login" value =""  placeholder="login" required>
          
        </div>

        <div class="mb-3">
          <label for="numero">Mot de passe</label>
          <input type="password" autocomplete="opsdfff"  class="form-control" name="password" value="" placeholder="" required>
        </div>

          
        <hr class="mb-4">

        <div class="row">
          <div class="col"></div>
          <div class="col-md-6">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Se connecter</button>
          </div>
          <div class="col"></div>
        </div>
      </div>

      <div class="col"> </div>
    
    </div>

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
