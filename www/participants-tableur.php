<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Florent Dupont">
   
    <title>Information participants</title>
   
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
    <link rel="stylesheet" href="CSS/specific.css">
  
  </head>
  <body class="bg-light">



<?php session_start();

include_once 'mysql.php';
include_once 'activite-dao.php';
include_once 'horaire-dao.php';
include_once 'creneau-dao.php';
include_once 'participant-dao.php';

$mysql = new MySql();
$activiteDao = new ActiviteDao($mysql); 
$horaireDao = new HoraireDao($mysql);
$creneauDao = new CreneauDao($mysql);
$participantDao = new ParticipantDao($mysql);

$activites = $activiteDao->findAllActivites();
$horaires = $horaireDao->findAllHoraires();
$participants = $participantDao->findAllParticipants();
$creneaux = $creneauDao->findAllCreneaux();

$erreurs = array();

// met en cache les horaires
$indexed_horaires = array();
foreach($horaires as $horaire) {
	$indexed_horaires[$horaire["id"]] = $horaire;
}

$indexed_activites = array();
foreach($activites as $activite) {
	$indexed_activites[$activite["id"]] = $activite;
}

?>


<div class="container">
     
	<div class="py-5 text-center">
	  <img src="logo.png" class="img-fluid mx-auto d-block" alt="Responsive image" width="200">
	  <h2>Informations des participants (tableur)</h2>
	  <p class="lead">Depuis cette page, vous visualiser les participants</i></p>
    <a class="btn btn-outline-secondary" href="admin.php" role="button">&larrhk; Retour à l'Admin</a>
   
	</div>
  
   
	<table class="table table-striped table-bordered">

	<thead class="thead">
		<tr>
		   <th scope="col"><div class="row justify-content-center">Prénom</div></th>
		   <th scope="col"><div class="row justify-content-center">Nom</div></th>
		   <th scope="col"><div class="row justify-content-center">Téléphone</div></th>
		   <th scope="col"><div class="row justify-content-center">E-mail</div></th>
		   <th scope="col"><div class="row justify-content-center">Activité</div></th>
		   <th scope="col"><div class="row justify-content-center">Horaire</div></th>
		  
	</tr>
	</thead>


	<?php foreach($participants as $participant) { ?>
		<tr>
		<?php 
			$cur_act = $indexed_activites[$participant["id_activite"]];
			$lib_act = $cur_act["libelle"];

			$cur_hor = $indexed_horaires[$participant["id_horaire"]];
			$lib_hor = $cur_hor["libelle_jour"] . " " .  $cur_hor["numero_jour"] . " ". $cur_hor["libelle_intervalle"];
		?>
			
			<td><?php echo $participant["prenom"] ?></td>
			<td><?php echo $participant["nom"] ?></td>
			<td><?php echo $participant["telephone"] ?></td>
			<td><?php echo $participant["email"] ?></td>
			<td><?php echo $lib_act ?></td>
			<td><?php echo $lib_hor ?></td>
			
		</tr>
 	<?php } ?>
	</table>


	<footer class="my-5 pt-5 text-muted text-center text-small">
    	<p class="mb-1">&copy; 2019 AEPG</p>
  	</footer>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
       

</body>
</html>