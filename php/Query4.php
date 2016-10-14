<?php
	session_start();
	require "Connect.php";
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Altre Operazioni > Query 4",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{

	$query="SELECT *
			FROM query4;";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));


echo<<<END
	<div id="section">
		<h1 class="titolo">mostrare i medici e le segretarie che sono anche pazienti dello studio e che non si fanno
      visitare da più di 5 anni</h1>
			<table id="tabellaOrari"  summary="mostrare i medici e le segretarie che sono anche pazienti dello studio e che non si fanno
      visitare da più di 5 anni">
				<thead>
				<tr class="tabHeaders">
					<th class="tabHeaders" scope="col">Codice fiscale</th>
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
