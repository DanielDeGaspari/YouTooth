<?php
	session_start();
	require "Utility.php";
	require "Connect.php";

	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Effettua Modifica",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{
	$visita=$_POST['id_visita'];
	$tipo_visita=$_POST['tipo_visita'];
	$medico=$_POST['medico'];
	$paziente=$_POST['paziente'];
	$giorno=$_POST['giorno'];
	$mese=$_POST['mese'];
	$anno=$_POST['anno'];
	$ora=$_POST['ora'];
	$minuti=$_POST['minuti'];
	$data=$anno."/".$mese."/".$giorno;
	$orario=$ora.":".$minuti;

	$currentdate=date("Y/m/d");
	$day=date("N",mktime(0,0,0,$mese,$giorno,$anno));
	$currenttime=date("G:i");
	$ok=TRUE;
	if(strtotime($data)<strtotime($currentdate))
	{
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"La prenotazione deve essere relativa ad una data presente o futura."</p>
				<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
		
	}
	/*Controllo se la prenotazione è di Sabato o Domenica*/
	elseif ($day==6 || $day==7) {
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"La prenotazione non può avvenire di Sabato / Domenica."</p>
				<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}
	/*Se la prenotazione avviene in giornata, deve essere relativa ad un orario presente/futuro*/
	elseif (strtotime($data)==strtotime($currentdate) && strtotime($orario)<strtotime($currenttime)) {
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"La prenotazione non può avvenire ad un periodo passato. Modificare la data o l'orario."</p>
				<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}
	/*La prenotazione deve avvenire in un orario in cui lo studio è aperto*/
	elseif (strtotime($orario)<mktime(9,0) || strtotime($orario)>mktime(18,0)) {
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"La prenotazione non può avvenire nell'orario selezionato."</p>
				<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}
	/*Deve essere scelta almeno una tipologia di visita*/
	elseif (count($tipo_visita) == 0) {
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"La prenotazione deve prevedere almeno una tipologia di visita."</p>
				<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}

	/*Query per controllare che un medico non abbia due visite nello stesso momento*/
	elseif ($ok) {
		for ($i=0, $n = count($medico); $i < $n && $ok=TRUE ; $i++) {
			$query="SELECT *
					FROM Effettuazione AS e JOIN Visita AS v ON (e.id_visita = v.id_visita)
					WHERE medico='$medico[$i]' AND data='$data' AND ora='$orario' AND v.id_visita <> '$visita'";
			$risultatocontrollo=mysqli_query($conn,$query) or die("Query fallita" . mysqli_error($conn));
			if (mysqli_num_rows($risultatocontrollo) !=0 ) {
				$ok=FALSE;
				echo<<<END
				<div id="section">
					<p>"Un medico non può effettuare due visite nello stesso momento. Modificare l'orario."</p>
					<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
				</div>
END;
			}
		}
	}

	/*Deve essere presente almeno un medico per tipologia di visita*/
	if ($ok && count($medico) == 0) {
		$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"La prenotazione deve prevedere almeno un medico per tipologia di visita."</p>
				<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

	}

	/*Query per controllare se è presente almeno un medico per tipologia di visita*/
	if ($ok) {
		foreach($tipo_visita as $tipologiav) {
			$cont=0;
			foreach($medico as $med) {
				$query="SELECT *
						FROM Specializzazione s
						WHERE s.medico='$med' AND s.tipo_visita='$tipologiav'";

				$ris=mysqli_query($conn,$query) or die("Query fallita" . mysqli_error($conn));

				if (mysqli_num_rows($ris) !=0 ) {
					$cont=$cont+1;
				}
			}
			if ($cont==0) {
				$ok=FALSE;
				echo<<<END
				<div id="section">
					<p>"Non &egrave; presente nessun medico tra quelli specificati che sia specializzato in ''$tipologiav''."</p>
					<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
				</div>
END;

			}
		}
	}


	$controlla="Select v.id_visita
				FROM Visita as v
				WHERE v.paziente='$paziente' AND data='$data' AND ora='$orario' AND id_visita <> '$visita'";
	if ($ok) {
		/*Un paziente non può avere due visite lo stesso giorno alla stessa ora*/
		$risultatocontrollo=mysqli_query($conn,$controlla) or die("Query fallita" . mysqli_error($conn));
		if (mysqli_num_rows($risultatocontrollo) !=0 ) {
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Un paziente non può avere due visite lo stesso giorno alla stessa ora."</p>
				<p><a href="Home.php">Clicca qui </a> per tornare alla Home page</p>
			</div>
END;

		}
	}


	if($ok) {

		$update="UPDATE Visita 
				SET paziente='$paziente', data='$data', ora='$orario'
				WHERE id_visita='$visita'";

		$risultato=mysqli_query($conn,$update) or die("Query fallita" . mysqli_error($conn));


		$delete="DELETE FROM SezioneVisita
				WHERE id_visita='$visita'";
		$esegui_delete=mysqli_query($conn,$delete) or die("Query fallita" . mysqli_error($conn));

		$delete="DELETE FROM Effettuazione
				WHERE id_visita='$visita'";
		$esegui_delete=mysqli_query($conn,$delete) or die("Query fallita" . mysqli_error($conn));

		foreach($tipo_visita as $tipologiav) {
			$insert2="INSERT INTO SezioneVisita (tipo_visita, id_visita) VALUES
					('$tipologiav', '$visita')";

			$risultato3=mysqli_query($conn,$insert2) or die("Query fallita" . mysqli_error($conn));
			foreach($medico as $med) {
				$query="SELECT *
						FROM Specializzazione s
						WHERE s.medico='$med' AND s.tipo_visita='$tipologiav'";

				$ris=mysqli_query($conn,$query) or die("Query fallita" . mysqli_error($conn));

				if (mysqli_num_rows($ris) !=0 ) {
					$insert3="INSERT INTO Effettuazione (medico, tipo_visita, id_visita) VALUES
							('$med', '$tipologiav', '$visita')";
					$risultato4=mysqli_query($conn,$insert3) or die("Query fallita" . mysqli_error($conn));
				}
			}
		}

		echo<<<END
			<div id="section">
				<p>"Modifica della visita avvenuta con successo."</p>
				<p><a href="Modifica_visita.php">Clicca qui </a> per tornare alla pagina precedente</p>
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
