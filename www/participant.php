<?php


// http://localhost/participant.php?id=6

$erreurs = array();
$nom = '';
$prenom = '';
$horaire = '';
$activite = '';

$mail = '';
$telephone = '';
$remarque = '';
$contact = 0;

include_once './mysql.php';
include_once './activite-dao.php';
include_once './horaire-dao.php';
include_once './participant-dao.php';

$mysql = new MySql();

$horaireDao = new HoraireDao($mysql);
$activiteDao = new ActiviteDao($mysql);
$participantDao = new ParticipantDao($mysql);


$id = $_GET["id"];
$participant = $participantDao->findParticipantById($id);


$id_horaire = $participant["id_horaire"];
$id_activite = $participant["id_activite"];


$selected_horaire = $horaireDao->findHoraireById($id_horaire);
$selected_activite = $activiteDao->findActiviteById($id_activite);


$nom = $participant['nom'];
$prenom = $participant['prenom'];
$mail = $participant['email'];
$telephone = $participant['telephone'];


?><!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Florent Dupont">
   
    <title>Inscription aux activités de la fête des écoles</title>
   
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
    <link rel="stylesheet" href="CSS/specific.css">
  
  </head>
  <body class="bg-light">




<div class="container">
     
	<div class="py-5 text-center">
	  <img src="logo.png" class="img-fluid mx-auto d-block" alt="Responsive image" width="200">
	  <h2>Inscription à l'activité "<?php echo $selected_activite["libelle"] ?>"</h2>
	  <p class="lead">le 
        <?php echo $selected_horaire["libelle_jour"] . " " . 
                   $selected_horaire["numero_jour"] . " - <b>" . 
                   $selected_horaire["libelle_intervalle"] . "</b>"?></p>
     <a class="btn btn-outline-secondary" href="participants-read.php" role="button">&larrhk; Retour aux info participants</a>
       
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
      
      <div class="col-md-2"> </div>
      <div class="col-md-8">

        <h4 class="mb-3">Nouvelle inscription</h4>
       
       
        <div class="mb-3">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">&#9787; Nom *</span>
            </div>
            <input type="text" name="nom" class="form-control" placeholder="votre nom" value="<?php echo $nom; ?>">
          </div>
        </div>

        <div class="mb-3">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">&#9787; Prénom *</span>
            </div>
            <input type="text" name="prenom" class="form-control" placeholder="votre prénom" value="<?php echo $prenom; ?>">
          </div>
        </div>

        <div class="mb-3">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@ E-mail</span>
            </div>
            <input type="text" name="mail" class="form-control" placeholder="votre@adresse.com" value="<?php echo $mail; ?>">
          </div>
        </div>

        <div class="mb-3">
           <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">&#9990; Téléphone</span>
            </div>
            <input type="text" name="telephone" class="form-control" placeholder="06-12-34-56-78" value="<?php echo $telephone; ?>">
          </div>
        </div>
          
        <hr class="mb-4">
       
      </div>

      <div class="col-md-2"> </div>
    
    </div>







	<footer class="my-5 pt-5 text-muted text-center text-small">
    	<p class="mb-1">&copy; 2019 AEPG</p>
  	</footer>
</div>

    

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
       
<script>

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>

</body>
</html>