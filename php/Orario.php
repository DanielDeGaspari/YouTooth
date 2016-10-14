<?php
	session_start();
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Orario",$username);
	navdiv($username,$privilegi);
echo<<<END
<div id="section">
	<h1 class="titolo"> Orario di apertura: </h1>
		<table id="tabellaOrari"  summary="Descrive gli orari di apertura dello studio dentistico">
			<caption>Orari di apertura</caption>
			<thead>
			<tr class="tabHeaders">
				<th class="tabHeaders" scope="col">Luned&igrave;</th>
				<th class="tabHeaders" scope="col">Marted&igrave;</th>
				<th class="tabHeaders" scope="col">Mercoled&igrave;</th>
				<th class="tabHeaders" scope="col">Gioved&igrave;</th>
				<th class="tabHeaders" scope="col">Venerd&igrave;</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>dalle 09:00<br />alle 18:00</td>
				<td>dalle 09:00<br />alle 18:00</td>
				<td>dalle 09:00<br />alle 18:00</td>
				<td>dalle 09:00<br />alle 18:00</td>
				<td>dalle 09:00<br />alle 18:00</td>
			</tr>
			</tbody>
		</table>
	</div>
END;
	footerdiv();
?>
