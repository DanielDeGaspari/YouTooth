<?php
	session_start();
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Modifica Password",$username);
	navdiv($username,$privilegi);
if(isset($username))
{
	echo<<<END
	<div id="section">
		<h1>Cambia password</h1>
		<form id="formPersonale" action="Modifica.php" method="post" class="styleForm">
			<div id="listaDati">
				<fieldset>
					<legend>Modifica la tua password:</legend>
					<label for="vpassword">Vecchia password:</label>
					<span><input type="password" id="vpassword" name="vpassword" maxlength="16" /></span>
					<br />
					<label for="npassword1">Nuova password:</label>
					<span><input type="password" id="npassword1" name="npassword1" maxlength="16" /></span>
					<br />
					<label for="cpassword">Conferma password:</label>
					<span><input type="password" id="cpassword" name="cpassword" maxlength="16" /></span>
					<br />
					<input type="submit" value="Invia" />
				</fieldset>
			</div>
		 </form>
	</div>
END;
}
else
	header('Location: Login.php');
	footerdiv();
?>
