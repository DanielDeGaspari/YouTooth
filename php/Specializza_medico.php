<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Effettua prenotazione",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{
	$tipo_visita=$_POST['tipo_visita'];
	$medico=$_POST['medico'];

	$ok=TRUE;


	foreach($tipo_visita as $tipologiav) {
	/*Per ogni specializzazione che cerco di aggiungere, controllo se il medico $med è già specializzato in quella tipologia*/
		$controlla="SELECT *
					FROM Specializzazione as s
					WHERE s.medico='$medico' AND s.tipo_visita='$tipologiav'";

		$risultatocontrollo=mysqli_query($conn,$controlla) or die("Query fallita" . mysqli_error($conn));
		if (mysqli_num_rows($risultatocontrollo) !=0 ) {
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Il medico $medico &egrave; gi&agrave; abilitato alla tipologia $tipologiav. Deselezionare $tipologiav."</p>
				<p><a href="Specializza.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
		}
	}

	if($ok) {

		foreach($tipo_visita as $tipologiav) {
			$insert="INSERT INTO Specializzazione (medico, tipo_visita) VALUES
					('$medico','$tipologiav')";

			$risultato=mysqli_query($conn,$insert) or die("Query fallita" . mysqli_error($conn));
		}

		echo<<<END
			<div id="section">
				<p>"L'aggiunta delle specializzazioni &egrave; avvenuta con successo."</p>
				<p><a href="Specializza.php">Clicca qui </a> per tornare alla pagina precedente</p>
				<br /> <strong>Oppure</strong><br />
				<p><a href="Home.php">Clicca qui </a> per tornare alla Home Page</p>
			</div>
END;
	}
}
else
	header('Location: Login.php');
	footerdiv();
?>
