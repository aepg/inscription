<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <meta name="description" content="">
    <meta name="author" content="Florent Dupont">
   
    <title>Inscription aux activités de la fête des écoles</title>
   
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

// j'indexe les participants dans un tableau a 2 dimensions [activite][horaire]
// je réduit ainsi le nombre de requetes.
$indexed_participants = array();
foreach($participants as $participant) {
	$part_act = $participant["id_activite"];
	$part_hor = $participant["id_horaire"];
	if(!isset($indexed_participants[$part_act]))
		$indexed_participants[$part_act] = array();
	
	if(!isset($indexed_participants[$part_act][$part_hor]))
		$indexed_participants[$part_act][$part_hor] = array();
	
	array_push($indexed_participants[$part_act][$part_hor], $participant);
}

// j'indexe les creneaux dans un tableau a 2 dimension [activite][horaire]
// je réduit ainsi le nombre de requetes.
$indexed_creneaux = array();
foreach($creneaux as $creneau) {
	$cren_act = $creneau["id_activite"];
	$cren_hor = $creneau["id_horaire"];
	if(!isset($indexed_creneaux[$cren_act]))
		$indexed_creneaux[$cren_act] = array();
	$indexed_creneaux[$cren_act][$cren_hor] = $creneau;
}
?>

<form name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" id="form_action" name="form_action" value="ajout" >
    <input type="hidden" id="form_id_activite" name="form_id_activite" value="0" >
  </form>

<div class="container-fluid">
     
	<div class="py-5 text-center">
	  <img src="logo.png" class="img-fluid mx-auto d-block" alt="Responsive image" width="200">
	  <h2>Inscription aux activités de la fête des écoles</h2>
	  <p class="lead">Aidez l'AEPG à participer aux activités et stands !</i></p>
	</div>
  
	<?php
	  $erreurs = array();
	?>
   
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
   
 
  
	  <div class="table-responsive-lg">
		  <table class="table table-striped table-bordered">
		  <thead class="thead">
			
		  <tr>
			<th></th>
			  <?php foreach($horaires as $horaire) { ?>
        		<th scope="col">
					<div class="row justify-content-center day"><?php echo $horaire["libelle_jour"]; ?></div>
					<div class="row justify-content-center daynb"><?php echo $horaire["numero_jour"]; ?></div>
					<div class="row justify-content-center timelap"><?php echo $horaire["libelle_intervalle"]; ?></div>
       			 </th>
     		 <?php } ?>
		
			  </tr>
		  </thead>
		  <tbody>

		  
		  <?php foreach($activites as $activite) { ?>
			<tr>
				<th scope="row" class="activite">
					<div class="activite"><?php echo $activite["libelle"]; ?></div>
				</th>

				<?php 
				$id_activite = $activite["id"];
				foreach($horaires as $horaire) {
					$id_horaire = $horaire["id"];

				// nombre de personnes max
					if(isset($indexed_creneaux[$id_activite]) && isset($indexed_creneaux[$id_activite][$id_horaire])) {
						$cur_cren = $indexed_creneaux[$id_activite][$id_horaire];
						$nombre_max =  $cur_cren["nombre_participants"];
					} else {
						$nombre_max = 0;
					}
							
					if(isset($indexed_participants[$id_activite]) && isset($indexed_participants[$id_activite][$id_horaire])) {
					
						$array_part = $indexed_participants[$id_activite][$id_horaire];

						if(count($array_part) >= $nombre_max) {
							echo "<td class=\"table-success\">";
						} else {
							echo "<td>";
						}				
						
						foreach($array_part as $cur_part) {
							echo "<div class=\"text-nowrap participant\">";
							echo $cur_part["prenom"] . " " . $cur_part["nom"] . "";
							echo "</div>";
						}
						if(count($array_part) < $nombre_max) {
							echo "<div class=\"text-nowrap participant\">";
							echo "<span class=\"badge badge-light\">+" . ( $nombre_max - count($array_part)) . " dispo </span>";
							echo "</div>";
						}
					
						if(count($array_part) < $nombre_max) {
							echo "<a href=\"nouveau.php?id_horaire=" . $horaire["id"] . "&id_activite=" . $activite["id"] . "\" role=\"button\" class=\"btn btn-outline-primary btn-sm mt-3\">S'inscrire</a>";
						}

						if(count($array_part) >= $nombre_max) {
							echo "<span class=\"badge badge-success mt-3\">complet !</span>";
						} 
						echo "</td>";
						
					} else {
						if($nombre_max > 0) {
							echo "<td>";
							echo "<div class=\"text-nowrap participant\">";
							echo "<span class=\"badge badge-light\">" . $nombre_max . " dispo </span>";
							echo "</div>";
						
							echo "<a href=\"nouveau.php?id_horaire=" . $horaire["id"] . "&id_activite=" . $activite["id"] . "\" role=\"button\" class=\"btn btn-outline-primary btn-sm mt-3\">S'inscrire</a>";
											
							echo "</td>";
						} else {
							// case vide
							echo "<td></td>";
							
						}
					
					}
				}
					
				?>
			</tr>  
			<?php } ?>		

		
		  </tbody>
		  </table>
	  </div>



	<footer class="my-5 pt-5 text-muted text-center text-small">
    	<p class="mb-1">&copy; 2019 AEPG - <a class="text-muted text-decoration-none" href="admin.php">admin</a></p>
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