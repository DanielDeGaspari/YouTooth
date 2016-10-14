<?php
session_start();
require "Connect.php";
require "Utility.php";
page_start("YouTooth");
headerdiv("YouTooth");
$username=$_SESSION['username'];
$privilegi=$_SESSION['privilegi'];
pathdiv("HOME PAGE > I miei appuntamenti",$username);
navdiv($username,$privilegi);

if(isset($username) && $privilegi=='med')
{

	$query="SELECT DISTINCT v.id_visita, v.data, v.ora, p.nome, p.cognome
			FROM ((Visita as v JOIN Effettuazione as e on (v.id_visita=e.id_visita))
					JOIN Paziente as p on (v.paziente=p.cod_fiscale)) 
					JOIN Medico as m on (m.cod_fiscale=e.medico)
			WHERE m.account='$username' AND (v.data>CURDATE() OR (v.data=CURDATE() AND v.ora>=CURTIME()))
			ORDER BY v.data, v.ora DESC";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

	echo<<<END
	<div id="section">
		<h1 class="titolo"> I prossimi appuntamenti di $username: </h1>
			<table id="tabellaOrari"  summary="Elenca i prossimi appuntamenti del medico">
				<caption>Elenco appuntamenti:</caption>
				<thead>
				<tr class="tabHeaders">
					<th class="tabHeaders" scope="col">Data</th>
					<th class="tabHeaders" scope="col">Ora</th>
					<th class="tabHeaders" scope="col">Nome paziente</th>
					<th class="tabHeaders" scope="col">Cognome paziente</th>
					<th class="tabHeaders" scope="col">Operazioni della visita</th>
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
						echo "</td></tr>";
					}
	echo<<<END
				</tbody>
			</table>
		</div>

END;

	$query="SELECT DISTINCT v.id_visita, v.data, v.ora, p.nome, p.cognome
			FROM ((Visita as v JOIN Effettuazione as e on (v.id_visita=e.id_visita))
					JOIN Paziente as p on (v.paziente=p.cod_fiscale)) 
					JOIN Medico as m on (m.cod_fiscale=e.medico)
			WHERE m.account='$username' AND NOT(v.data>CURDATE() OR (v.data=CURDATE() AND v.ora>=CURTIME()))
			ORDER BY v.data, v.ora DESC";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

	echo<<<END
	<div id="section">
		<h1 class="titolo"> Appuntamenti passati di $username: </h1>
			<table id="tabellaOrari"  summary="Elenca gli appuntamenti passati del medico">
				<caption>Elenco appuntamenti passati:</caption>
				<thead>
				<tr class="tabHeaders">
					<th class="tabHeaders" scope="col">Data</th>
					<th class="tabHeaders" scope="col">Ora</th>
					<th class="tabHeaders" scope="col">Nome paziente</th>
					<th class="tabHeaders" scope="col">Cognome paziente</th>
					<th class="tabHeaders" scope="col">Operazioni della visita</th>
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
