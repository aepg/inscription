<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Florent Dupont">
   
    <title>AEPG - Horaires disponibles</title>
   
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
       
  </head>
  <body class="bg-light">


  <?php

include_once 'mysql.php';
include_once 'horaire-dao.php';
$mysql = new MySql();
$dao = new HoraireDao($mysql);

$erreurs = array();

if(isset($_POST["form_action"])) {

  $id_horaire = $_POST["form_id_horaire"];

  if($_POST["form_action"] == "descendre") {
    $dao->decrementerTriHoraire($id_horaire);
  }
  if($_POST["form_action"] == "monter") {
    $dao->incrementerTriHoraire($id_horaire);
  }
  if($_POST["form_action"] == "supprimer") {
    if(!$dao->supprimerHoraire($id_horaire)) {
      array_push($erreurs, "impossible de supprimer cet horaire. Il doit être actuellement utilisé par un créneau. Supprimer d'abord le creneau."); 
    }
  }
} 

$horaires = $dao->findAllHoraires();

?>



  <div class="container">
    
  <form name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" id="form_action" name="form_action" value="ajout" >
    <input type="hidden" id="form_id_horaire" name="form_id_horaire" value="0" >
  </form>

  <div class="py-5 text-center">
    <img class="d-block mx-auto mb-4" src="logo_carre.jpg" alt="" width="72" height="72">
    <h2>Horaires disponibles</h2>
    <p class="lead">Liste des horaires disponibles pour les participants.</p>
    <a class="btn btn-outline-secondary" href="admin.php" role="button">&larrhk; Retour à l'Admin</a>
    <a class="btn btn-primary" href="horaire-edition.php" role="button">&#9998; Ajouter un nouvel horaire</a>
  </div>
 

  <div class="row">
      <div class="col"></div>
      <div class="col-8">
        <?php foreach($erreurs as $erreur) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $erreur;?>
        </div>
        <?php } ?>
      </div>
      <div class="col"></div>
    </div>

  <div class="row">

  <table class="table">
  <thead>
    <tr>
      <th scope="col">Jour</th>
      <th scope="col">Numéro</th>
      <th scope="col">Intervalle</th>
      <th scope="col">Opérations</th>
    </tr>
  </thead>
  <tbody>

<?php foreach($horaires as $index => $horaire)  { ?>

    <tr>
      <td><?php echo $horaire['libelle_jour'] ?></td>
      <td><?php echo $horaire['numero_jour'] ?></td>
      <td><?php echo $horaire['libelle_intervalle'] ?></td>
      <td>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a class="btn btn-primary btn-sm" href="horaire-edition.php?id=<?php echo $horaire['id']; ?>" role="button">Editer</a>
            <button type="button" class="btn btn-secondary btn-sm" onclick="javascript:supprimer(<?php echo $horaire['id']; ?>)">Supprimer</button>
            <?php $disabled = $index == 0; ?>
            <button type="button" class="btn btn-success btn-sm" <?php echo $disabled ? 'disabled' : ''; ?> onclick="javascript:descendre(<?php echo $horaire['id']; ?>)"><span aria-hidden="true">&uarr;</span></button>
            <?php $disabled = $index == count($horaires)-1; ?>
            <button type="button" class="btn btn-success btn-sm" <?php echo $disabled ? 'disabled' : ''; ?> onclick="javascript:monter(<?php echo $horaire['id']; ?>)"><span aria-hidden="true">&darr;</span></button>
        </div>
      </td>
    </tr>

<?php } ?>
   
  </tbody>
</table>

  </div>


  <footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; 2019 AEPG</p>
  </footer>
</div>

<script>

  function edit(id_horaire) {
    document.getElementById("form_action").value = "modifier";
    document.getElementById("form_id_horaire").value = id_horaire;
    document.formulaire.submit();
  }

  function supprimer(id_horaire) {
    document.getElementById("form_action").value = "supprimer";
    document.getElementById("form_id_horaire").value = id_horaire;
    document.formulaire.submit();
  }

  function monter(id_horaire) {
    document.getElementById("form_action").value = "monter";
    document.getElementById("form_id_horaire").value = id_horaire;
    document.formulaire.submit();
  }

  function descendre(id_horaire) {
    document.getElementById("form_action").value = "descendre";
    document.getElementById("form_id_horaire").value = id_horaire;
    document.formulaire.submit();
  }
</script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
       
</body>
</html>
