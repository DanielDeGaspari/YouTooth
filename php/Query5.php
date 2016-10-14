<?php
	session_start();
	require "Connect.php";
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Altre Operazioni > Query 5",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{

	$query="SELECT *
			FROM query5;";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));


echo<<<END
	<div id="section">
		<h1 class="titolo">mostra i medici che hanno effettuato visite a pazienti che hanno fatto SOLO estrazioni di denti del giudizio</h1>
			<table id="tabellaOrari"  summary="mostra i medici che hanno effettuato visite a pazienti che hanno fatto SOLO estrazioni di denti del giudizio">
				<thead>
				<tr class="tabHeaders">
					<th class="tabHeaders" scope="col">Codice fiscale</th>
					<th class="tabHeaders" scope="col">Cognome</th>
					<th class="tabHeaders" scope="col">Nome</th>
				</tr>
				</thead>
				<tbody>
END;
					while($row=mysqli_fetch_row($ris)) {
						echo "<tr>";
						for ($i=0, $n=count($row); $i<$n; $i++) {
							echo "<td>'$row[$i]'</td>";
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
	header('Location: Login.php');
	footerdiv();
?>
