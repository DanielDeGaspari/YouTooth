<?php
session_start();
require "Connect.php";
require "Utility.php";
page_start("YouTooth");
headerdiv("YouTooth");
$username=$_SESSION['username'];
$privilegi=$_SESSION['privilegi'];
pathdiv("HOME PAGE > Il mio profilo",$username);
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
	$indirizzo=$row[4];
	$comune=$row[5];
	$provincia=$row[6];
	$tel=$row[7];
	$sesso=$row[8];
	$email=$row[9];
	echo<<<END
	<div id="section">
		<h1>Profilo di $username</h1>
		<table width="500" border="1">
			<tr>
			<td>Codice Fiscale:</td>
			<td>$cod_fiscale</td>
			</tr>
			<tr>
			<td>Cognome</td>
			<td>$cognome</td>
			</tr>
			<tr>
			<td>Nome</td>
			<td>$nome</td>
			</tr>
			<tr>
			<td>Data di nascita</td>
			<td>$data</td>
			</tr>
			<tr>
			<td>Indirizzo</td>
			<td>$indirizzo</td>
			</tr>
			<tr>
			<td>Comune di residenza</td>
			<td>$comune</td>
			</tr>
			<tr>
			<td>Provincia</td>
			<td>$provincia</td>
			</tr>
			<tr>
			<td>Sesso</td>
			<td>$sesso</td>
			</tr>
			<tr>
			<td>Telefono</td>
			<td>$tel</td>
			</tr>
			<tr>
			<td>E-email</td>
			<td>$email</td>
			</tr>
		</table>
		<br />
		<p>
		<a href="ModificaProfilo.php">Modifica le tue informazioni</a>
		<br />
		<a href="CambiaPassword.php">Modifica la tua password</a>
		</p>
	</div>
END;
}
else
	header('Location: Login.php');
	footerdiv();
?>
