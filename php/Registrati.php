<?php
	session_start();
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Registrati",$username);
	navdiv($username,$privilegi);
if(!isset($username))
{
echo<<<END
	<div id="section">
		<form id="formPersonale" action="EffettuaRegistrazione.php" method="post" class="styleForm">
			<div id="listaDati">
				<fieldset>
					<legend>Inserisci qui i tuoi dati personali:</legend>
					<label for="cod_fiscale">Codice Fiscale:</label>
					<input type="text" id="cod_fiscale" name="cod_fiscale" /><br />
					<label for="nome">Nome:</label>
					<input type="text" id="nome" name="nome" /><br />
					<label for="cognome">Cognome:</label>
					<input type="text" id="cognome" name="cognome" /><br />
					<label for="indirizzo">Indirizzo:</label>
					<input type="text" id="indirizzo" name="indirizzo" /><br />
					<label for="comune">Comune:</label>
					<input type="text" id="comune" name="comune" /><br />
					<label for="provincia">Provincia:</label>
					<input type="text" id="provincia" name="provincia" /><br />
					<label for="tel">Telefono:</label>
					<input type="text" id="tel" name="tel" /><br />
					<label for="sesso">Sesso:</label>
					<select id="sesso" name="sesso">
						<option value='M' selected="selected">M</option>
						<option value='F'>F</option>
					</select><br />
					<fieldset>
					<legend>Data di Nascita:</legend>
					<label for="giorno">Giorno:</label>
					<select id="giorno" name="giorno">
END;
			for($i=1;$i<=31;$i++)
				echo '<option value="'.$i.'" >'.$i.'</option>';
			echo<<<END
					</select> <br />
					<label for="mese">Mese:</label>
					<select id="mese" name="mese">
END;
			for($i=1;$i<=12;$i++)
				echo '<option value="'.$i.'" >'.$i.'</option>'; 
			echo<<<END
					</select> <br />
					<label for="anno">Anno:</label>
					<select id="anno" name="anno">
END;
			for($i=date("Y");$i>=1900;$i--)
				echo '<option value="'.$i.'" >'.$i.'</option>'; 
			echo<<<END
					</select>
					</fieldset> <br />

					<label for="email">E-mail:</label>
					<input type="text" id="email" name="email" /><br />
					<label for="username">Username</label>
					<input type="text" id="username" name="username" /><br />
					<label for="password">Password</label>
					<input type="text" id="password" name="password" /><br />
					<label for="cpassword">Conferma Password:</label>
					<input type="text" id="cpassword" name="cpassword" /><br />

					<input type="submit" name="registrati" value="Registrati" />
				</fieldset>
			</div>
	</form>
	<br />
	<p><a href="Login.php">Clicca qui</a> se sei gi&agrave; registrato</p>
	<strong>Oppure</strong><br />
	<p><a href="Home.php">Clicca qui</a> per tornare alla Home Page</p>

	</div>
END;
}
else
	header('Location: Home.php');
	footerdiv();
?>
