<?php
	session_start();
	require "Connect.php";
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Altre Operazioni",$username);
	navdiv($username,$privilegi);
if(isset($username) && $privilegi=='seg')
{
echo<<<END
	<div id="section">
		<div class="linkAncore">
			<ul>
				<li>
					<a href="Aggiungi_specializzazione.php" name="aggiungi_specializzazione">Aggiungi tipologia specializzazione</a>
				</li>
				<li>
					<a href="Specializza.php" name="specializza_medico">Aggiungi specializzazione medico</a>
				</li>
				<li>
					<a href="Licenzia_personale.php" name="licenzia_personale">Licenzia personale</a>
				</li>
				<li>
					<a href="Query1.php" name="Query1">Query1</a>
				</li>
				<li>
					<a href="Query2.php" name="Query2">Query2</a>
				</li>
				<li>
					<a href="Query3.php" name="Query3">Query3</a>
				</li>
				<li>
					<a href="Query4.php" name="Query4">Query4</a>
				</li>
				<li>
					<a href="Query5.php" name="Query5">Query5</a>
				</li>
				<li>
					<a href="Query6.php" name="Query6">Query6</a>
				</li>
			</ul>
		</div>
	</div>
END;
}
else
	header('Location: Login.php');
	footerdiv();
?>
