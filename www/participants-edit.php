
<table>
	<thead>
        <tr>
			<th>Activités</th>
			<?php 
				foreach($horaires as $horaire) {
					echo "<th scope=\"col\">" .$horaire["libelle_intervalle"] . "</th>";
				}
			?>
        </tr>
    </thead>
	<tbody>
		<?php
			foreach($activites as $activite) {
				$id_activite = $activite["id"];
				echo "<tr>";
				echo "  <th scope=\"row\">" . $activite["libelle"] . "</th>";

				foreach($horaires as $horaire) {
					$id_horaire = $horaire["id"];
					// dans la liste des participants, recherche celui qui
					if(isset($indexed_participants[$id_activite]) && isset($indexed_participants[$id_activite][$id_horaire])) {
						$array_part = $indexed_participants[$id_activite][$id_horaire];
						echo "<td>";
						foreach($array_part as $cur_part) {
							echo $cur_part["nom"] . "<br/>";
						}
						echo "</td>";
					} else {
						echo "<td></td>";
					}
					
					/*if(isset($indexed_creneaux[$id_activite]) && isset($indexed_creneaux[$id_activite][$id_horaire])) {
						$cur_cren = $indexed_creneaux[$id_activite][$id_horaire];
						echo "<td>" . $cur_cren["nombre_participants"] . "</td>";
					} else {
						echo "<td></td>";
					}*/
				}
				echo "</tr>";
			}
		?>
     		
    </tbody>
</table>