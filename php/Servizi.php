<?php
	session_start();
	require "Connect.php";
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > I nostri servizi",$username);
	navdiv($username,$privilegi);

	$query="SELECT DISTINCT t.descrizione, t.prezzo, t.note
			FROM (TipoVisita t JOIN Specializzazione as s on t.descrizione=s.tipo_visita) 
					JOIN Medico as m on s.medico=m.cod_fiscale
			WHERE data_licenziamento is NULL";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

echo<<<END
	<div id="section">
		<h1 class="titolo"> I nostri servizi: </h1>
			<table id="tabellaOrari"  summary="Elenca i vari servizi offerti dallo studio dentistico">
				<caption>Servizi offerti:</caption>
				<thead>
				<tr class="tabHeaders">
					<th class="tabHeaders" scope="col">Descrizione</th>
					<th class="tabHeaders" scope="col">Prezzo (€)</th>
					<th class="tabHeaders" scope="col">Note</th>
				</tr>
				</thead>
				<tbody>
END;
					while($row=mysqli_fetch_row($ris)) {
						echo "<tr>";
						 foreach ($row as $field) 
							 echo "<td>$field</td>";
						 echo "</tr>";
					}
echo<<<END
				</tbody>
			</table>
		</div>

END;

	footerdiv();
?>
