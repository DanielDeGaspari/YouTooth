<?php
session_start();
require "Connect.php";
require "Utility.php";
page_start("YouTooth");
headerdiv("YouTooth");
$username=$_SESSION['username'];
$privilegi=$_SESSION['privilegi'];
pathdiv("HOME PAGE > Modifica Visita",$username);
navdiv($username,$privilegi);

if(isset($username) && $privilegi=='seg')
{

	$query="SELECT DISTINCT v.id_visita, v.paziente, v.data, v.ora 
			FROM Visita as v JOIN Effettuazione as e on (v.id_visita=e.id_visita)
			WHERE (v.data>CURDATE() OR (v.data=CURDATE() AND v.ora>=CURTIME()))
			ORDER BY v.data, v.ora DESC";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

	echo<<<END
	<div id="section">
		<h1 class="titolo"> I prossimi appuntamenti (Codice fiscale paziente, Data, Ora)</h1>

			<form action="ModificaCerca.php" method="post">
				<fieldset>
					<legend>Cerca paziente (inserire il codice fiscale)</legend>
					<label for="paz">codice fiscale paziente:</label>
					<input class="casella_input" name="paz" id="paz" value="" maxlength="20" />
					<br /> <br />
					<input type="submit" value="Effettua ricerca"></input>
				</fieldset>
			</form>
			<br />


					<form action="ModificaVisita.php" method="post">
END;

	$cont=0;
					while($row=mysqli_fetch_row($ris)) {
						$cont=$cont+1;
						$visita_id=$row[0];
						if ($cont==1)
							echo "<input type='radio' name='appuntamento' value='$visita_id' checked='checked'> ";
						else
							echo "<input type='radio' name='appuntamento' value='$visita_id'> ";
						for ($i=1, $n=count($row); $i<$n; $i++) {
							echo "$row[$i] ";
						}
						echo "<br />";
					}
	echo<<<END
					<br />
					<input type="submit" name="modifica" value="Modifica la visita selezionata" />
					</form>
		</div>

END;

}
else
	header('Location: Login.php');
	footerdiv();
?>
