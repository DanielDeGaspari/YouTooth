<?php
	session_start();
	require "Connect.php";
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Aggiungi tipologia visita",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{
echo<<<END
	<div id="section">
		<form id="formPersonale" action="Effettua_aggiunta_specializzazione.php" method="post" class="styleForm">
			<div id="listaDati">
				<fieldset>
					<legend>Inserisci qui i dati relativi alla nuova tipologia di visita:</legend>
					<label for="descrizione">Nome prestazione:</label>
					<input type="text" id="descrizione" name="descrizione"/><br />
					<label for="note">Descrizione:</label>
					<input type="text" id="note" name="note"/><br />
					<label for="prezzo">Prezzo (€)</label>
					<span>
						<input class="default-text" id="prezzo" name="prezzo" min="0" max="100000" type="number" step="any" value="0" />
					</span>
					 <br />

					<input type="submit" name="aggiungi" value="Aggiungi" />
				</fieldset>
			</div>
	</form>
	<br />
	<p><a href="Home.php">Clicca qui</a> per tornare alla Home Page</p>

	</div>
END;
}
else
	header('Location: Login.php');
	footerdiv();
?>
