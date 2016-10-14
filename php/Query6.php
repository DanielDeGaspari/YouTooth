<?php
	session_start();
	require "Connect.php";
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Altre Operazioni > Query 6",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{

	$query="SELECT *
			FROM query6;";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));


echo<<<END
	<div id="section">
		<h1 class="titolo">mostra la spesa totale di ogni cliente,  mostrando cognome e nome del cliente, il codice
      fiscale e la sua spesa, ordinati per cognome e nome</h1>
			<table id="tabellaOrari"  summary="mostra la spesa totale di ogni cliente,  mostrando cognome e nome del cliente, il codice
      fiscale e la sua spesa, ordinati per cognome e nome">
				<thead>
				<tr class="tabHeaders">
					<th class="tabHeaders" scope="col">Codice fiscale</th>
					<th class="tabHeaders" scope="col">Cognome</th>
					<th class="tabHeaders" scope="col">Nome</th>
					<th class="tabHeaders" scope="col">Totale(€)</th>
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
