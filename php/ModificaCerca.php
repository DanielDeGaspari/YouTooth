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
	$paziente=$_POST['paz'];

	if(!ctype_alnum($paziente))
	{
			echo<<<END
			<div id="section">
				<p>"Non sono consentiti caratteri speciali. Effettuare una nuova ricerca"</p>
				<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
		
	}
	else
	{

	$query="SELECT DISTINCT v.id_visita, v.paziente, v.data, v.ora 
			FROM Visita as v JOIN Effettuazione as e on (v.id_visita=e.id_visita)
			WHERE (v.data>CURDATE() OR (v.data=CURDATE() AND v.ora>=CURTIME())) AND v.paziente='$paziente'
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

END;
	if(mysqli_num_rows($ris)==0)
	{
		echo "<p>'Non sono presenti prenotazioni future per il paziente '$paziente'. Effettuare una nuova ricerca.'</p>";
		echo "<p><a href='Modifica_visita.php'>Clicca qui </a> per visualizzare l'elenco completo delle visite prenotate</p>";
	}


echo<<<END

					<form action="ModificaVisita.php" method="post">
END;

					while($row=mysqli_fetch_row($ris)) {
						$visita_id=$row[0];
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
			<br />
			<p><a href='Modifica_visita.php'>Clicca qui </a> per visualizzare l'elenco completo delle visite prenotate</p>
		</div>

END;
}
}
else
	header('Location: Login.php');
	footerdiv();
?>
