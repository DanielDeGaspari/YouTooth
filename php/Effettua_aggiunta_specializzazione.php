<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Effettua aggiunta tipologia visita",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{
	$descrizione=$_POST['descrizione'];
	$prezzo=$_POST['prezzo'];
	$note=$_POST['note'];


if($descrizione && $prezzo && $note)
{
	$ok=TRUE;

	if ($prezzo<0) {
		$ok=FALSE;
		echo<<<END
		<div id="section">
			<p>"Il prezzo deve essere >= 0."</p>
			<p><a href="Aggiungi_specializzazione.php">Clicca qui </a> per tornare alla pagina precedente</p>
		</div>
END;
	}

	if(!lunghezza($descrizione,5) && $ok)
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Il campo descrizione deve contenere almeno 5 caratteri"</p>
				<p><a href="Aggiungi_specializzazione.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}

	if($ok)
	{
		$query1="SELECT *
				 FROM TipoVisita t
				 WHERE descrizione='$descrizione'";
		$ris1=mysqli_query($conn,$query1) or die("Query fallita" . mysqli_error($conn));
		if(mysqli_num_rows($ris1)>0)
		{
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Esiste gi&agrave; la tipologia di visita $descrizione."</p>
				<p><a href="Aggiungi_specializzazione.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

		}
	}


	if($ok) {

		$insert="INSERT INTO TipoVisita (descrizione, prezzo, note) VALUES
				('$descrizione','$prezzo', '$note')";

		$risultato=mysqli_query($conn,$insert) or die("Query fallita" . mysqli_error($conn));

		echo<<<END
			<div id="section">
				<p>"L'aggiunta della tipologia visita $descrizione &egrave; avvenuta con successo."</p>
				<p><a href="Aggiungi_specializzazione.php">Clicca qui </a> per tornare alla pagina precedente</p>
				<strong>Oppure</strong><br />
				<p><a href="Home.php">Clicca qui </a> per tornare alla Home Page</p>
			</div>
END;
	}

}
else {
	echo<<<END
			<div id="section">
				<p>"Compilare tutti i campi"</p>
				<p><a href="Aggiungi_specializzazione.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
}
}
else
	header('Location: Login.php');
	footerdiv();
?>
