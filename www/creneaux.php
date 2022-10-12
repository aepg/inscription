<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Florent Dupont">
   
    <title>AEPG - Activités disponibles</title>
   
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
$erreurs = array();

if(isset($_POST["form_action"])) {

    $id_activite = $_POST["form_id_activite"];
    $id_horaire = $_POST["form_id_horaire"];
    $nombre = $_POST["form_nombre_participants"];
  
    if($_POST["form_action"] == "ajouter") {
      $creneauDao->creerCreneau($id_activite, $id_horaire);
    }
    if($_POST["form_action"] == "modifier") {
      $creneauDao->modifierCreneau($id_activite, $id_horaire, $nombre);
    }
    if($_POST["form_action"] == "supprimer") {

      // les créneaux ne sont pas liés à un participant... On vérifie donc qu'un participant n'existe pas
      // déjà sur le couple activité/horaire, avant de supprimer
      // sinon, on indique un message d'erreur
      if($participantDao->hasParticipant($id_activite, $id_horaire)) {
        array_push($erreurs, "impossible de supprimer ce créneau. Il doit être actuellement utilisé par un participant. Supprimer d'abord le participant."); 
      }
      else {
        if(!$creneauDao->supprimerCreneau($id_activite, $id_horaire)) {
          array_push($erreurs, "impossible de supprimer ce créneau. Problème technique."); 
        }
      }

     
    }
  } 



$activites = $activiteDao->findAllActivites();
$horaires = $horaireDao->findAllHoraires();
$creneaux = $creneauDao->findAllCreneaux();


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

  <div class="container-fluid">
  <form name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" id="form_action" name="form_action" value="ajout" >
    <input type="hidden" id="form_id_activite" name="form_id_activite" value="0" >
    <input type="hidden" id="form_id_horaire" name="form_id_horaire" value="0" >
    <input type="hidden" id="form_nombre_participants" name="form_nombre_participants" value="0" >
  </form>

 
  <div class="py-5 text-center">
    <img class="d-block mx-auto mb-4" src="logo_carre.jpg" alt="" width="72" height="72">
    <h2>Créneaux disponibles</h2>
    <p class="lead">Un Créneau = 1 activité sur un horaire donné et le nombre de participants. 
    <br/><i>par exemple : le "Bar de 10à11h peut avoir 4 participants" .</i></p>
    <a class="btn btn-outline-secondary" href="admin.php" role="button">&larrhk; Retour à l'Admin</a>
   
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
                        $nombre_participants =  $cur_cren["nombre_participants"];
                        
                        ?>

                        <td>
                           
                                <div class="input-group">
                                <?php
                                   $selectId = "select_" . $id_activite . "_" . $id_horaire;
                                ?>
                                <select class="custom-select" id="<?php echo $selectId ?>">
                                    <option disabled>Sélectionner</option>
                                    <?php 
                                         

                                         //echo "<option >3</option>";
                                         for( $i = 1; $i<=20; $i++ ) {
                                           if($nombre_participants == $i) {
                                                echo "<option selected=\"selected\" value=\"" . $i . "\">". $i ."</option>";
                                           } else {
                                            echo "<option value=\"" . $i . "\">". $i ."</option>";
                                           }
                                           
                                         }
                                    ?>
                                </select>
                                    <div class="input-group-append">
                                        <?php
                                        echo "<button onclick=\"modifier(" . $id_activite . ", " . $id_horaire . ", '".$selectId."')\" " ;
                                        echo "class=\"btn btn-sm btn-outline-success\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Sauver\">";
                                        echo "&#10004;</button>";
                                        ?>
                                    </div>
                                    <div class="input-group-append">
                                        <?php
                                         echo "<button onclick=\"supprimer(" . $id_activite . ", " . $id_horaire . ")\" class=\"btn btn-sm btn-outline-danger\"";
                                         echo "type=\"button\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Supprimer\">&#10008;</button>";
                                        ?>    
                                     </div>
                                </div>
                           
                        </td>


                        <?php
                      
					} else {
                        ?>
                        <td>
                           
                                <div class="input-group">
                                    <select class="custom-select" disabled>
                                        <option selected disabled></option>
                                    </select>
                                    <div class="input-group-append">
                                        <?php
                                        echo "<button onclick=\"ajouter(" . $id_activite . ", " . $id_horaire . ")\" class=\"btn btn-sm btn-outline-secondary\" type=\"button\" >Créer</button>";
                                        ?>
                                    </div>
                                </div>
                           
                        </td>
                        <?php

					}
							
					
				}
					
				?>
			</tr>  
			<?php } ?>		

		
		  </tbody>
		  </table>
	  </div>


  <footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; 2019 AEPG</p>
  </footer>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
       
<script>

function ajouter(id_activite, id_horaire) {
    document.getElementById("form_action").value = "ajouter";
    document.getElementById("form_id_activite").value = id_activite;
    document.getElementById("form_id_horaire").value = id_horaire;
    document.formulaire.submit();
  }

  function modifier(id_activite, id_horaire, selectId) {
    var e = document.getElementById(selectId);
    var strValue = e.options[e.selectedIndex].value;
    document.getElementById("form_action").value = "modifier";
    document.getElementById("form_id_activite").value = id_activite;
    document.getElementById("form_id_horaire").value = id_horaire;
    document.getElementById("form_nombre_participants").value = strValue;
    document.formulaire.submit();
  }

  function supprimer(id_activite, id_horaire) {
    document.getElementById("form_action").value = "supprimer";
    document.getElementById("form_id_activite").value = id_activite;
    document.getElementById("form_id_horaire").value = id_horaire;    
    document.formulaire.submit();
  }

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>

</body>
</html>
