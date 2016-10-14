<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Licenziamenti",$username);
	navdiv($username,$privilegi);
if(isset($username) && ($privilegi=='seg'))
{

	$medico=$_POST['medico'];
	$currentdate=date("Y/m/d");

	$query_controllo="SELECT count(*)
					  FROM Medico as m JOIN Effettuazione as e ON m.cod_fiscale=e.medico
					  JOIN SezioneVisita as sv ON (e.tipo_visita=sv.tipo_visita AND e.id_visita=sv.id_visita)
					  JOIN Visita as v ON v.id_visita=sv.id_visita
					  WHERE v.data > '$currentdate' AND m.cod_fiscale='$medico'";

	$risultato=mysqli_query($conn,$query_controllo) or die("Query fallita" . mysqli_error($conn));
	$row=mysqli_fetch_row($risultato);
	$conta=$row[0];

	if ($conta > 0) {

		$query="SELECT DISTINCT v.id_visita, v.data, v.ora, p.nome, p.cognome, p.telefono
			FROM ((Visita as v JOIN Effettuazione as e on (v.id_visita=e.id_visita))
					JOIN Paziente as p on (v.paziente=p.cod_fiscale)) 
					JOIN Medico as m on (m.cod_fiscale=e.medico)
			WHERE m.cod_fiscale='$medico' AND (v.data>CURDATE())
			ORDER BY v.data, v.ora DESC";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

	echo<<<END
	<div id="section">
		<p>Non &egrave; stato possibile portare a termine il licenziamento di $medico.</p>
		<p>Sono presenti visite prenotate con $medico. Si prega modificare tali visite e di riprovare. </p>
		<h1 class="titolo"> I prossimi appuntamenti di $medico: </h1>
			<table id="tabellaOrari"  summary="Elenca i prossimi appuntamenti del medico">
				<caption>Elenco appuntamenti:</caption>
				<thead>
				<tr class="tabHeaders">
					<th class="tabHeaders" scope="col">Data</th>
					<th class="tabHeaders" scope="col">Ora</th>
					<th class="tabHeaders" scope="col">Nome paziente</th>
					<th class="tabHeaders" scope="col">Cognome paziente</th>
					<th class="tabHeaders" scope="col">Telefono paziente</th>
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
						echo "</tr>";
					}
	echo<<<END
				</tbody>
			</table>
		</div>

END;
	}

	else
	
	{

			$query="SELECT m.account
					FROM Medico m
					WHERE m.cod_fiscale='$medico'";

			$risultato=mysqli_query($conn,$query) or die("Query fallita" . mysqli_error($conn));
			$row=mysqli_fetch_row($risultato);
			$account=$row[0];

		$update1="UPDATE Medico
				  SET data_licenziamento='$currentdate'
				  WHERE cod_fiscale='$medico'";

		/*Cancello l'account*/
		$delete1="DELETE FROM Account
				  WHERE username='$account'";

		$ris1=mysqli_query($conn,$update1) or die("Modica non riuscita".mysqli_error($conn));
		$ris2=mysqli_query($conn,$delete1) or die("Modica non riuscita".mysqli_error($conn));

		echo<<<END
			<div id="section">
				<p>"Licenziamento di $medico avvenuto con successo"</p>
				<p><a href="Licenzia_personale.php">Clicca qui </a> per tornare alla pagina precedente</p>
				<br /> <strong>Oppure</strong><br />
				<p><a href="Home.php">Clicca qui </a> per tornare alla Home Page</p>
			</div>
END;

	}
}
else
	header('Location: Login.php');
	footerdiv();
?>
