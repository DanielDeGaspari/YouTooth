<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE",$username);
	navdiv($username,$privilegi);

if(isset($username))
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
	$email=$_POST['e_mail'];

	if($cod_fiscale && $nome && $cognome && $data && $indirizzo && $comune && $provincia && $tel && $sesso && $email)
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

		$ris=mysqli_query($conn,$query) or die ("Modifica fallita".mysqli_query($conn));
		$row=mysqli_fetch_row($ris);
		$cod_fiscalev=$row[0];
		$nomev=$row[1];
		$cognomev=$row[2];
		$datav=$row[3];
		$data_array=explode('-', $data);
		$indirizzov=$row[4];
		$comunev=$row[5];
		$provinciav=$row[6];
		$telv=$row[7];
		$sessov=$row[8];
		$emailv=$row[9];

		/*Eventuale controllo per negare l'operazione se i dati nuovi sono uguali a quelli vecchi ...*/
if ($privilegi=='paz')
		$update1="UPDATE Paziente
				  SET cod_fiscale='$cod_fiscale', nome='$nome', cognome='$cognome', data_nascita='$data', indirizzo='$indirizzo',
				  comune='$comune', prov='$provincia', telefono='$tel', sesso='$sesso', e_mail='$email'
				  WHERE account='$username'";
elseif ($privilegi=='med')
		$update1="UPDATE Medico
				  SET cod_fiscale='$cod_fiscale', nome='$nome', cognome='$cognome', data_nascita='$data', indirizzo='$indirizzo',
				  comune='$comune', prov='$provincia', telefono='$tel', sesso='$sesso', e_mail='$email'
				  WHERE account='$username'";
elseif ($privilegi=='seg')
		$update1="UPDATE Segretaria
				  SET cod_fiscale='$cod_fiscale', nome='$nome', cognome='$cognome', data_nascita='$data', indirizzo='$indirizzo',
				  comune='$comune', prov='$provincia', telefono='$tel', sesso='$sesso', e_mail='$email'
				  WHERE account='$username'";

		$ris1=mysqli_query($conn,$update1) or die("Modica non riuscita".mysqli_error($conn));
		echo<<<END
			<div id="section">
				<p>I dati sono stati aggiornati correttamente.</p>
				<p><a href="Profilo.php">Clicca qui</a> per tornare al tuo profilo</p>
			</div>
END;
	}
	else
echo<<<END
			<div id="section">
				<p>"Compilare tutti i campi obbligatori"</p>
				<p><a href="ModificaProfilo.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
}
else
	header('Location: Login.php');
	footerdiv();
?>
