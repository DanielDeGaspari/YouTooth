<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Registrazione nuovo personale",$username);
	navdiv($username,$privilegi);
if(isset($username) && ($privilegi=='med' || $privilegi=='seg'))
{
	$tipo=$_POST['tipo_personale'];
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

if($tipo && $cod_fiscale && $nome && $cognome && $data && $indirizzo && $comune && $provincia && $tel && $sesso && $username && $password && $password1 && $email)
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


	if ($ok) {
		/*Controllo se la persona che voglio assumere è già presente come segretaria/medico*/
		if ($tipo=='seg') {
			$query="SELECT m.cod_fiscale
			FROM Medico m
			WHERE m.cod_fiscale='$cod_fiscale'";
		}
		elseif ($tipo=='med') {
			$query="SELECT s.cod_fiscale
			FROM Segretaria s
			WHERE s.cod_fiscale='$cod_fiscale'";
		}
		$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));
		if (mysqli_num_rows($ris)!=0) {
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"$cod_fiscale &egrave; gi&agrave; registrato con un altra mansione."</p>
				<p><a href="Registrati.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
		}
	}

	if ($ok) {

		$currentdate=date("Y/m/d");

		/*Controllo se la persona che voglio assumere ha già lavorato nello studio con la stessa mansione*/
		if ($tipo=='med') {
			$query="SELECT m.cod_fiscale, m.data_licenziamento
			FROM Medico m
			WHERE m.cod_fiscale='$cod_fiscale'";
		}
		elseif ($tipo=='seg') {
			$query="SELECT s.cod_fiscale, s.data_licenziamento 
			FROM Segretaria s
			WHERE s.cod_fiscale='$cod_fiscale'";
		}

		$ris=mysqli_query($conn,$query) or die ("Query fallita".mysqli_query($conn));
		if (mysqli_num_rows($ris)!=0) {
			/*La persona che voglio assumere ha già lavorato nello studio con la stessa mansione*/
			$row=mysqli_fetch_row($ris);

			if($row[1]==NULL) {
			/*La persona che voglio assumere sta già lavorando nello studio con la mansione $tipo*/
				echo<<<END
			<div id="section">
				<p>"La persona che voglio assumere sta già lavorando nello studio con la mansione $tipo "</p>
				<p><a href="Registra_personale.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
			}
			else {
				/*La persona può essere ri-assunta*/
				$passc=SHA1($password);
				
				$insert="CALL ri_assumi('$cod_fiscale','$nome','$cognome','$data','$indirizzo','$comune','$provincia','$tel', '$sesso','$email','$username', '$passc', '$tipo');";

				$risultato=mysqli_query($conn,$insert) or die("Query fallita" . mysqli_error($conn));

			echo<<<END
				<div id="section">
					<p>"Registrazione avvenuta con successo."</p>
					<p><a href="Home.php">Clicca qui </a> per tornare alla Home Page</p>
				</div>
END;
			}
		}
		else {
/*La persona che voglio assumere non ha mai lavorato nello studio con la stessa mansione per la quale sta per essere assunta*/
			$passc=SHA1($password);

			$insert="CALL assumi('$cod_fiscale','$nome','$cognome','$data','$indirizzo','$comune','$provincia','$tel', '$sesso','$email','$username', '$passc', '$tipo');";

			$risultato=mysqli_query($conn,$insert) or die("Query fallita" . mysqli_error($conn));

			echo<<<END
				<div id="section">
					<p>"Registrazione avvenuta con successo"</p>
					<p><a href="Home.php">Clicca qui </a> per tornare alla Home Page</p>
				</div>
END;
		}
	}
}
else {
	echo<<<END
			<div id="section">
				<p>"Compilare tutti i campi"</p>
				<p><a href="Registra_personale.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
}
}
else
	header('Location: Login.php');
	footerdiv();
?>
