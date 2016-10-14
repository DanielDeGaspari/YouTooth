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
	$vpassword=SHA1($_POST['vpassword']);
	$npassword=($_POST['npassword1']);
	$npassword1=($_POST['cpassword']);
	if($vpassword && $npassword && $npassword1)
	{
		$ok=TRUE;
		$query1="SELECT *
				 FROM Account acc
				 WHERE acc.username='$username' AND acc.pwd='$vpassword'";
		$ris1=mysqli_query($conn,$query1) or die("Modifica password non riuscita".mysqli_error($conn));

		if(!mysqli_num_rows($ris1))
		{
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Password errata"</p>
				<p><a href="CambiaPassword.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

		}
		if($ok && ($npassword!=$npassword1))
		{
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"Le password non corrispondono"</p>
				<p><a href="CambiaPassword.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

		}
		if($ok && !lunghezza($npassword,5))
		{
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"La password deve contenere almeno 5 caratteri"</p>
				<p><a href="CambiaPassword.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;

		}
		$cnpassword=SHA1($npassword);
		if($ok && ($vpassword==$cnpassword))
		{
			$ok=FALSE;
			echo<<<END
			<div id="section">
				<p>"La nuova password deve essere diversa dalla precedente"</p>
				<p><a href="CambiaPassword.php">Clicca qui </a> per tornare alla pagina precedente</p>
			</div>
END;
		}

		if($ok)
		{
			$update="UPDATE Account SET pwd='$cnpassword' WHERE username='$username'";
			$ris=mysqli_query($conn,$update) or die("Modifica della password fallita".mysqli_error($conn));
			echo<<<END
			<div id="section">
				<p>La modifica della password è avvenuta correttamente</p>
			</div>
END;
		}
	}
	else {
	echo<<<END
		<div id="section">
			<p>Alcuni campi non sono stati compilati</p>
			<p><a href="CambiaPassword.php">Clicca qui </a> per tornare alla pagina precedente</p>
		</div>
END;
	}
}
else
	header('Location: Login.php');
	footerdiv();
?>
