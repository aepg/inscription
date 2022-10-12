<?php session_start();
       
       // https://stackoverflow.com/questions/8028957/how-to-fix-headers-already-sent-error-in-php
        if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] == false) {
         
            header('Location: auth.php'); 
        } 
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
	<head>
		<title>AEPG - Administration</title>
		<meta charset="UTF-8">
		<meta name="description" content="Site d'inscription aux activités de l'école" />
	</head>

	<body>

        <h2>
          <a href="index.php">Accueil</a>
        </h2>
        <br/>
        <h2>
          <a href="participants-read.php">Info des Participants</a>
        </h2>
        <h2>
          <a href="participants-tableur.php">Info des Participants (Tableur)</a>
        </h2>
        <br/>

        <h2>
          <a href="parametres.php?id=1">Paramètres Généraux</a>
        </h2>
        <h2>
          <a href="activites.php">Gestion des Activités</a>
        </h2>
        <h2>
          <a href="horaires.php">Gestion des Horaires</a>
        </h2>
        <h2>
          <a href="creneaux.php">Gestion des Créneaux</a>
        </h2>
        <h2>
          <a href="participants.php">Gestion des Participants</a>
        </h2>

        <?php /*phpinfo()*/ ?>
    </body>
</html>