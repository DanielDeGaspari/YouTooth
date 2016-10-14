<?php
	session_start();
	require "Connect.php";
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > I nostri Medici",$username);
	navdiv($username,$privilegi);

	$query="SELECT m.cognome, m.nome, m.telefono, m.cod_fiscale
			FROM Medico m
			WHERE m.data_licenziamento IS NULL";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

echo<<<END
	<div id="section">
		<h1 class="titolo"> I nostri medici: </h1>
			<table id="tabellaOrari"  summary="Elenca i medici che puoi trovare nel nostro studio">
				<caption>Elenco medici:</caption>
				<thead>
				<tr class="tabHeaders">
					<th class="tabHeaders" scope="col">Cognome</th>
					<th class="tabHeaders" scope="col">Nome</th>
					<th class="tabHeaders" scope="col">Telefono</th>
					<th class="tabHeaders" scope="col">Specializzazioni</th>
				</tr>
				</thead>
				<tbody>
END;
					while($row=mysqli_fetch_row($ris)) {
						$med=$row[3];
						$query="SELECT s.tipo_visita
								FROM Specializzazione s
								WHERE s.medico='$med'";

						$risultato=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));
						echo "<tr>";
						for ($i=0, $n=count($row)-1; $i<$n; $i++) {
							echo "<td>'$row[$i]'</td>";
						}
						echo "<td><ul>";
						while($specializzazione=mysqli_fetch_row($risultato)) {
							$elem=$specializzazione[0];
							echo "<li>$elem</li>";
						}
						echo "</ul></td>";
						echo "</tr>";
					}
echo<<<END
				</tbody>
			</table>
		</div>

END;

	footerdiv();
?>
