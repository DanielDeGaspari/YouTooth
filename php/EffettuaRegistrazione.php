<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Registrati",$username);
	navdiv($username,$privilegi);
if(!isset($username))
{
	$cod_fiscale=$_POST['cod_fiscale'];
	$nome=$_POST['nome'];
	$cognome=$_POST['cognome'];
	$giorno=$_POST['giorno'];
	$mese=$_POST['mese'];
	$anno=$_POST['anno'];
	$data=$anno."-".$mese."-".$giorno;
	$indirizzo=$_POST['indirizzo'];
	$comune=$_POST['comune'];
	$provincia=$_POST['provincia'];
	$tel=$_POST['tel'];
	$sesso=$_POST['sesso'];
	$username=$_POST['username'];
	$password=$_POST['password'];
	$password1=$_POST['cpassword'];
	$email=$_POST['email'];

if($cod_fiscale && $nome && $cognome && $data && $indirizzo && $comune && $provincia && $tel && $sesso && $username && $password && $password1 && $email)
{
	$ok=TRUE;
	if(!ctype_alnum($cod_fiscale))
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Il codice fiscale deve contenere solo caratteri alfanumerici!"</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
		
	}

	if($ok && !solocaratteri($nome) || !solocaratteri($cognome))
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Il nome e cognome devono essere formati da soli caratteri!"</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
		
	}

	if(!solocaratteri($comune) && $ok)
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Il campo comune deve essere formato da soli caratteri."</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}

	if(!solocaratteri($provincia) && $ok)
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Il campo provincia deve essere formato da soli caratteri."</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}

	if($ok)
	{
		$query1="SELECT *
				 FROM Paziente p
				 WHERE cod_fiscale='$cod_fiscale'";
		$ris1=mysqli_query($conn,$query1) or die("Query fallita" . mysqli_error($conn));
		if(mysqli_num_rows($ris1)>0)
		{
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Esiste gi&agrave; un paziente con lo stesso codice fiscale."</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

		}
	}

	if($ok)
	{
		$query1="SELECT *
				 FROM Account a
				 WHERE username='$username'";
		$ris1=mysqli_query($conn,$query1) or die("Query fallita" . mysqli_error($conn));
		if(mysqli_num_rows($ris1)>0)
		{
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Esiste gi&agrave; un account con il nome utente $username. Scegliere un altro nome utente."</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

		}
	}


	if(!lunghezza($username,5) && $ok)
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Il campo Username deve contenere almeno 5 caratteri"</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}

	if(!lunghezza($password,5) && $ok)
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"La password deve contenere almeno 5 caratteri"</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}

	if(!lunghezza_max($provincia,2) && $ok)
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Provincia deve contenere al massimo due caratteri"</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
	}

	if($password1!=$password && $ok)
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Le due password non corrispondono."</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}

	if($ok)
	{
		$passc=SHA1($password);
		$insert1="INSERT INTO Account (username, pwd, privilegi) VALUES
				('$username', '$passc', 'paz')";
		$risultato1=mysqli_query($conn,$insert1) or die("Query fallita" . mysqli_error($conn));



		$insert2="INSERT INTO Paziente (cod_fiscale, nome, cognome, data_nascita, indirizzo, comune, prov, telefono, sesso, e_mail, account) VALUES
				('$cod_fiscale', '$nome', '$cognome', '$data', '$indirizzo', '$comune', '$provincia', '$tel', '$sesso', '$email','$username')";

		$risultato2=mysqli_query($conn,$insert2) or die("Query fallita" . mysqli_error($conn));
		$id=mysqli_insert_id();
		echo<<<END
			<div id="section">
				<p>"Registrazione avvenuta con successo"</p>
				<p><a href="Login.php">Clicca qui </a> per effettuare l'accesso</p>
			</div>
END;
	}
}
else {
	echo<<<END
			<div id="section">
				<p>"Compilare tutti i campi"</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
}
}
else
	header('Location: Login.php');
	footerdiv();
?>
