<?php
	session_start();
	require "Utility.php";
	require "Connect.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Licenziati",$username);
	navdiv($username,$privilegi);
if(isset($username) && ($privilegi=='seg'))
{
	$segretaria=$_POST['segretaria'];
	$currentdate=date("Y/m/d");

		$query="SELECT s.account
				FROM Segretaria s
				WHERE s.cod_fiscale='$segretaria'";

		$risultato=mysqli_query($conn,$query) or die("Query fallita" . mysqli_error($conn));
		$row=mysqli_fetch_row($risultato);
		$account=$row[0];

	$update1="UPDATE Segretaria
			  SET data_licenziamento='$currentdate'
			  WHERE cod_fiscale='$segretaria'";

	/*Cancello l'account*/
	$delete1="DELETE FROM Account
			  WHERE username='$account'";

	$ris1=mysqli_query($conn,$update1) or die("Modica non riuscita".mysqli_error($conn));
	$ris2=mysqli_query($conn,$delete1) or die("Modica non riuscita".mysqli_error($conn));
		echo<<<END
			<div id="section">
				<p>"Licenziamento di $segretaria avvenuto con successo"</p>
				<p><a href="Licenzia_personale.php">Clicca qui </a> per tornare alla pagina precedente</p>
				<br /> <strong>Oppure</strong><br />
				<p><a href="Home.php">Clicca qui </a> per tornare alla Home Page</p>
			</div>
END;
}
else
	header('Location: Login.php');
	footerdiv();
?>
