<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Modifica i dati della visita",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{
	$id_visita=$_POST['appuntamento'];

	$query="SELECT sv.tipo_visita
			FROM SezioneVisita as sv 
			WHERE sv.id_visita='$id_visita'
			ORDER BY sv.tipo_visita";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

echo<<<END
	<div id="section">
		<form id="formPersonale" action="EffettuaModificaVisita.php" method="post" class="styleForm">
			<input type="hidden" name="id_visita" value='$id_visita' />
			<div id="listaDati">
				<fieldset>
					<legend>Modifica i dati relativi alla prenotazione</legend>
					<fieldset>
					<legend>Tipologia visita:</legend>
END;

	$query_tipologia="SELECT t.descrizione, t.prezzo, t.note
			FROM TipoVisita t
			ORDER BY t.descrizione";

	$ris_tipologia=mysqli_query($conn,$query_tipologia) or die ("Query fallita".mysqli_query($conn));
	$row_visita=mysqli_fetch_row($ris);

	$i=0;
	while($row=mysqli_fetch_row($ris_tipologia)) {
		$found=FALSE;
		foreach ($row_visita as $tipo) {
			if ($tipo==$row[0]) {
				$found=TRUE;
				$row_visita=mysqli_fetch_row($ris);
			}
		}
		if ($found)
			echo '<input type="checkbox" name="tipo_visita['.$i.']"  checked value="'.$row[0].'" />'.$row[0].'<br />';
		else
			echo '<input type="checkbox" name="tipo_visita['.$i.']" value="'.$row[0].'" />'.$row[0].'<br />';
		$i++;
	}
	echo<<<END
			<br />
					</fieldset>

					<fieldset>
					<legend>Seleziona i medici:</legend>
END;

	$query_visita="SELECT DISTINCT e.medico
			FROM Effettuazione as e 
			WHERE e.id_visita='$id_visita'
			ORDER BY e.medico";

	$ris_visita=mysqli_query($conn,$query_visita) or die ("Query fallita".mysqli_query($conn));

	$query="SELECT m.cod_fiscale
			FROM Medico m
			WHERE m.data_licenziamento IS NULL
			ORDER BY m.cod_fiscale";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));
	$row_visita=mysqli_fetch_row($ris_visita);

	$i=0;
	while($row=mysqli_fetch_row($ris)) {
		$found=FALSE;
		foreach ($row_visita as $medico) {
			if ($medico==$row[0]) {
				$found=TRUE;
				$row_visita=mysqli_fetch_row($ris_visita);
			}
		}
		if ($found)
			echo '<input type="checkbox" name="medico['.$i.']" checked value="'.$row[0].'" />'.$row[0].'<br />';
		else
			echo '<input type="checkbox" name="medico['.$i.']" value="'.$row[0].'" />'.$row[0].'<br />';
		$i++;
	}
	echo<<<END
			<br />
					</fieldset>

			<label for="paziente">Paziente</label>
			<select id="paziente" name="paziente">
END;

	$query_visita="SELECT v.paziente, v.data, v.ora
			FROM Visita as v 
			WHERE v.id_visita='$id_visita'
			ORDER BY v.paziente";

	$ris_visita=mysqli_query($conn,$query_visita) or die ("Query fallita".mysqli_query($conn));

	$query="SELECT p.cod_fiscale
			FROM Paziente p
			ORDER BY p.cod_fiscale";

	$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));
	$row_visita=mysqli_fetch_row($ris_visita);

	while($row=mysqli_fetch_row($ris)) {
		$found=FALSE;
		foreach ($row_visita as $paziente) {
			if ($paziente==$row[0]) {
				$found=TRUE;
			}
		}
		if ($found)
			echo '<option selected="selected" value="'.$row[0].'" >'.$row[0].'</option>';
		else
			echo '<option value="'.$row[0].'" >'.$row[0].'</option>';
	}

	$data_array=explode('-', $row_visita[1]);
	$time_array=explode(':', $row_visita[2]);

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

					<input type="submit" name="prenota" value="Modifica" />
				</fieldset>
			</div>
	</form>
	<br />
	<p><a href="Modifica_visita.php">Clicca qui</a> per tornare alla pagina precedente</p>

	</div>
END;
}
else
	header('Location: Login.php');
	footerdiv();
?>
