<?php
	session_start();
	require "Connect.php";
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Specializza medico",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{
echo<<<END
	<div id="section">
		<form id="formPersonale" action="Specializza_medico.php" method="post" class="styleForm">
			<div id="listaDati">
				<fieldset>
					<legend>Specializza un medico in una nuova tipologia di visita:</legend>
					<label for="medico">Medico:</label>
					<select id="medico" name="medico">
END;

					$query="SELECT m.cod_fiscale
							FROM Medico m
							WHERE m.data_licenziamento IS NULL";

					$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));

					while($row=mysqli_fetch_row($ris)) {
						echo '<option value="'.$row[0].'" >'.$row[0].'</option>';
					}

echo<<<END
					</select>
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
