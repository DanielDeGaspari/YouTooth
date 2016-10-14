<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Licenzia personale",$username);
	navdiv($username,$privilegi);
if(isset($username) && ($privilegi=='seg'))
{

	$query="SELECT m.cod_fiscale, m.cognome, m.nome
			FROM Medico m
			WHERE m.data_licenziamento IS NULL";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

	$query2="SELECT s.cod_fiscale, s.cognome, s.nome
			FROM Segretaria s
			WHERE s.data_licenziamento IS NULL";

	$ris2=mysqli_query($conn,$query2) or die ("Query fallita".mysqli_query($conn));

echo<<<END
	<div id="section">
		<form id="formPersonale" action="Effettua_licenziamento_medico.php" method="post" class="styleForm">
			<div id="listaDati">
				<fieldset>
					<legend>Seleziona il medico da licenziare:</legend>
						<p>Medici:</p>
END;
					while($row=mysqli_fetch_row($ris)) {
							$medico = $row[0];
							echo "<input type='radio' name='medico' value='$medico'> ";
							for ($i=0, $n=count($row); $i<$n; $i++) {
								echo "$row[$i] ";
							}

						echo "<br />";
					}

echo<<<END
					<br />
					<input type="submit" name="licenzia" value="Licenzia medico" />
				</fieldset>
			</div>
	</form>
	<br />

		<form id="formPersonale" action="Effettua_licenziamento_segretaria.php" method="post" class="styleForm">
			<div id="listaDati">
				<fieldset>
					<legend>Seleziona la segretaria da licenziare:</legend>
						<p>Segretarie:</p>
END;
					while($row=mysqli_fetch_row($ris2)) {
							$segretaria = $row[0];
							echo "<input type='radio' name='segretaria' value='$segretaria'> ";
							for ($i=0, $n=count($row); $i<$n; $i++) {
								echo "$row[$i] ";
							}

						echo "<br />";
					}
echo<<<END
					<br />
					<input type="submit" name="licenzia" value="Licenzia segretaria" />
				</fieldset>
			</div>
	</form>
	<br />

	<p><a href="Altre_operazioni.php">Clicca qui</a> per tornare alla pagina precedente</p>
	<p><a href="Home.php">Clicca qui</a> per tornare alla Home Page</p>

	</div>
END;
}
else
	header('Location: Login.php');
	footerdiv();
?>
