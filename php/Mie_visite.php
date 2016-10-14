<?php
session_start();
require "Connect.php";
require "Utility.php";
page_start("YouTooth");
headerdiv("YouTooth");
$username=$_SESSION['username'];
$privilegi=$_SESSION['privilegi'];
pathdiv("HOME PAGE > Le mie visite",$username);
navdiv($username,$privilegi);

if(isset($username))
{

	$query="SELECT v.id_visita, v.data, v.ora
			FROM Visita as v JOIN Paziente p on (v.paziente=p.cod_fiscale)
			WHERE p.account='$username'
			ORDER BY v.data, v.ora DESC";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

	echo<<<END
	<div id="section">
		<h1 class="titolo"> Le visite di $username: </h1>
			<table id="tabellaOrari"  summary="Elenca le visite effettuate dal paziente">
				<caption>Elenco visite:</caption>
				<thead>
				<tr class="tabHeaders">
					<th class="tabHeaders" scope="col">Data</th>
					<th class="tabHeaders" scope="col">Ora</th>
					<th class="tabHeaders" scope="col">Elenco tipologia</th>
					<th class="tabHeaders" scope="col">Elenco medici</th>
					<th class="tabHeaders" scope="col">prezzo totale (€)</th>
				</tr>
				</thead>
				<tbody>
END;
					while($row=mysqli_fetch_row($ris)) {
						/*Data e ora*/
						echo "<tr>";
						for ($i=1, $n=count($row); $i<$n; $i++) {
							echo "<td>$row[$i]</td>";
						}
						echo "<td><ul>";

						$visita_id=$row[0];
						/*Tipologie coinvolte:*/
						$query="SELECT tipo_visita
								FROM SezioneVisita as sv
								WHERE sv.id_visita='$visita_id'";

						$tipologie=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

						while($tipo=mysqli_fetch_row($tipologie)) {
							$elem=$tipo[0];
							echo "<li>$elem</li>";
						}
						echo "</ul></td>";

						echo "<td><ul>";
						/*Elenco medici:*/
						$query="SELECT DISTINCT m.nome, m.cognome
								FROM Effettuazione as e JOIN Medico as m on (e.medico=m.cod_fiscale) 
								WHERE e.id_visita='$visita_id'";

						$medici=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

						while($medico=mysqli_fetch_row($medici)) {
							$nome=$medico[0];
							$cognome=$medico[1];
							echo "<li>$nome $cognome</li>";
						}
						echo "</ul></td><td>";

						$query="SELECT SUM(tv.prezzo) AS 'totale'
								FROM SezioneVisita as sv JOIN TipoVisita as tv on (sv.tipo_visita=tv.descrizione)
								WHERE sv.id_visita='$visita_id'";

						$ris_prezzo=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));
						$totale=mysqli_fetch_row($ris_prezzo);
						echo $totale[0];
						echo "</td></tr>";
					}
	echo<<<END
				</tbody>
			</table>
		</div>

END;

}
else
	header('Location: Login.php');
	footerdiv();
?>
