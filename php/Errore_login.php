<?php
	session_start();
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Login Error",$username);
	navdiv($username,$privilegi);
echo<<<END
	<div id="section">
		<a name="contenutopagina"></a>
END;
	if(!isset($username))
	{
		echo<<<END
		<div id="fail"> <img src="../immagini/failLog.jpg" alt="login fallito" /></div>
		<div id="sectionLOG">
			<h1>Username o Password errati. Riprova.</h1>
			<form action="Accesso.php" method="post">
				<fieldset>
					<legend>Login</legend>
					<label for="user">Username</label>
					<input class="casella_input" name="username" id="user" value="user" maxlength="20" />
					<br /> <br />
					<label for="password">Password</label>
					<input class="casella_input" type="password" name="password" id="password" value="pwd" maxlength="20" />
					<input type="submit" value="Accedi"></input>
				</fieldset>
			</form>
		</div>
	</div>
END;
	}
	else {
		header('Location: Home.php');
	}
	footerdiv();
?>
