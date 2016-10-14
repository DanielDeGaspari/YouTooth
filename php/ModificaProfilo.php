<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Modifica Profilo",$username);
	navdiv($username,$privilegi);

if(isset($username))
{
if ($privilegi=='paz')
	$query="SELECT p.cod_fiscale, p.nome, p.cognome, p.data_nascita, p.indirizzo, p.comune, p.prov, p.telefono, p.sesso, p.e_mail
			FROM Paziente p
			WHERE p.account='$username'";
elseif ($privilegi=='med')
	$query="SELECT m.cod_fiscale, m.nome, m.cognome, m.data_nascita, m.indirizzo, m.comune, m.prov, m.telefono, m.sesso, m.e_mail
			FROM Medico m
			WHERE m.account='$username'";
elseif ($privilegi=='seg')
	$query="SELECT s.cod_fiscale, s.nome, s.cognome, s.data_nascita, s.indirizzo, s.comune, s.prov, s.telefono, s.sesso, s.e_mail
			FROM Segretaria s
			WHERE s.account='$username'";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));
	$row=mysqli_fetch_row($ris);
	$cod_fiscale=$row[0];
	$nome=$row[1];
	$cognome=$row[2];
	$data=$row[3];
	$data_array=explode('-', $data);
	$indirizzo=$row[4];
	$comune=$row[5];
	$provincia=$row[6];
	$tel=$row[7];
	$sesso=$row[8];
	$email=$row[9];
	echo<<<END
	<div id="section">
	<h1>Modifica i tuoi dati personali:</h1>
		<form id="formPersonale" action="ApportaModifiche.php" method="post" class="styleForm">
			<div id="listaDati">
				<fieldset>
					<legend>Modifica i tuoi dati personali qui:</legend>
					<label for="cod_fiscale">Codice Fiscale:</label>
					<input type="text" id="cod_fiscale" name="cod_fiscale" value="$cod_fiscale" /><br />
					<label for="nome">Nome:</label>
					<input type="text" id="nome" name="nome" value="$nome" /><br />
					<label for="cognome">Cognome:</label>
					<input type="text" id="cognome" name="cognome" value="$cognome" /><br />
					<label for="indirizzo">Indirizzo:</label>
					<input type="text" id="indirizzo" name="indirizzo" value="$indirizzo" /><br />
					<label for="comune">Comune:</label>
					<input type="text" id="comune" name="comune" value="$comune" /><br />
					<label for="provincia">Provincia:</label>
					<input type="text" id="provincia" name="provincia" value="$provincia" /><br />
					<label for="tel">Telefono:</label>
					<input type="text" id="tel" name="tel" value="$tel" /><br />
					<label for="e_mail">E-mail:</label>
					<input type="text" id="e_mail" name="e_mail" value="$email" /><br />
					<label for="sesso">Sesso:</label>
					<select id="sesso" name="sesso">
END;
				if($sesso=="M") {
					echo "<option value='M' selected>M</option> \n";
					echo "<option value='F'>F</option> \n";
				}
				else if($sesso=="F") {
					echo "<option value='M'>M</option> \n";
					echo "<option value='F' selected>F</option> \n";
				}

	echo<<<END
					</select><br />
					<fieldset>
					<legend>Data di Nascita:</legend>
					<label for="giorno">Giorno:</label>
					<select id="giorno" name="giorno">
END;
			for($i=1;$i<=31;$i++)
				echo '<option '.($data_array[2] == $i ? ' selected ' : '').' value="'.$i.'" >'.$i.'</option>'; 
			echo<<<END
					</select> <br />
					<label for="mese">Mese:</label>
					<select id="mese" name="mese">
END;
			for($i=1;$i<=12;$i++)
				echo '<option '.($data_array[1] == $i ? ' selected ' : '').' value="'.$i.'" >'.$i.'</option>'; 
			echo<<<END
					</select> <br />
					<label for="anno">Anno:</label>
					<select id="anno" name="anno">
END;
			for($i=date("Y");$i>=1900;$i--)
				echo '<option '.($data_array[0] == $i ? ' selected ' : '').' value="'.$i.'" >'.$i.'</option>'; 
			echo<<<END
					</select>
					</fieldset> <br />
					<input type="submit" name="modifica" value="Modifica" />
				</fieldset>
			</div>
	</form>
	<p>
	<a href="Profilo.php">Clicca qui</a> per tornare alla pagina precedente</p>
	</div>
END;
}
else
	header('Location: Login.php');
	footerdiv();
?>
