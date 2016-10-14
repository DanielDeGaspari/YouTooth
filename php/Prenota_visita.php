<?php
	session_start();
	require "Connect.php";
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Prenota una visita",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{
echo<<<END
	<div id="section">
		<form id="formPersonale" action="Effettua_prenotazione.php" method="post" class="styleForm">
			<div id="listaDati">
				<fieldset>
					<legend>Inserisci i dati relativi alla prenotazione</legend>
					<fieldset>
					<legend>Tipologia visita:</legend>
END;

	$query="SELECT t.descrizione, t.prezzo, t.note
			FROM TipoVisita t";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

	$i=0;
	while($row=mysqli_fetch_row($ris)) {
		echo '<input type="checkbox" name="tipo_visita['.$i.']" value="'.$row[0].'" />'.$row[0].'<br />';
		$i++;
	}
	echo<<<END
			<br />
					</fieldset>

					<fieldset>
					<legend>Seleziona i medici:</legend>
END;

	$query="SELECT m.cod_fiscale
			FROM Medico m
			WHERE m.data_licenziamento IS NULL";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

	$i=0;
	while($row=mysqli_fetch_row($ris)) {
		echo '<input type="checkbox" name="medico['.$i.']" value="'.$row[0].'" />'.$row[0].'<br />';
		$i++;
	}
	echo<<<END
			<br />
					</fieldset>

			<label for="paziente">Paziente</label>
			<select id="paziente" name="paziente">
END;

	$query="SELECT p.cod_fiscale
			FROM Paziente p
			ORDER BY p.cod_fiscale";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));


	while($row=mysqli_fetch_row($ris)) {
		echo '<option value="'.$row[0].'" >'.$row[0].'</option>';
	}

	$currentdate=date("Y/m/d");
	$currenttime=date("G:i");
	$data_array=explode('/', $currentdate);
	$time_array=explode(':', $currenttime);

	echo<<<END
			</select> <br />
			<fieldset>
			<legend>Giorno e ora:</legend>

			<label for="ora">Ora:</label>
			<select id="ora" name="ora">
END;
			for($i=0;$i<=23;$i++)
				echo '<option value="'.$i.'" '.($time_array[0] == $i ? ' selected="selected" ' : '').'>'.$i.'</option>'; 
			echo<<<END
					</select> <br />

			<label for="minuti">Minuti:</label>
			<select id="minuti" name="minuti">
END;
			for($i=0;$i<=59;$i++)
				echo '<option '.($time_array[1] == $i ? ' selected="selected" ' : '').' value="'.$i.'" >'.$i.'</option>'; 
			echo<<<END
					</select> <br />


			<label for="giorno">Giorno:</label>
			<select id="giorno" name="giorno">
END;
			for($i=1;$i<=31;$i++)
				echo '<option '.($data_array[2] == $i ? ' selected="selected" ' : '').' value="'.$i.'" >'.$i.'</option>'; 
			echo<<<END
					</select> <br />
					<label for="mese">Mese:</label>
					<select id="mese" name="mese">
END;
			for($i=1;$i<=12;$i++)
				echo '<option '.($data_array[1] == $i ? ' selected="selected" ' : '').' value="'.$i.'" >'.$i.'</option>'; 
			echo<<<END
					</select> <br />
					<label for="anno">Anno:</label>
					<select id="anno" name="anno">
END;
			for($i=date("Y");$i<=date("Y")+5;$i++)
				echo '<option '.($data_array[0] == $i ? ' selected="selected" ' : '').' value="'.$i.'" >'.$i.'</option>';
			echo<<<END
					</select>
					</fieldset> <br />

					<input type="submit" name="prenota" value="Prenota" />
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
