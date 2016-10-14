<?php
	session_start();
	require "Utility.php";
	page_start("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE",$username);
	navdiv($username,$privilegi);
echo<<<END
<div id="section">
		<a name="contenutopagina"></a>
		<h1 class="titolo">Studio medico dentistico: YouTooth</h1>
		<div id="news">
			<h2>Ultime novit&agrave; </h2>
				<ul>
					<li>Luned&igrave; 16 Maggio 2016 lo studio rimarr&agrave; chiuso a causa di lavori di ristrutturazione</li>
					<li class="alternate">Prevenzione ed educazione alla salute orale [ <a href="../Documenti/identikit.pdf">PDF 2MB</a> ]</li>
					<li>Manuale per la salute della bocca [ <a href="../Documenti/manuale.pdf">PDF 1.5MB</a> ]</li>
					<li class="alternate">Dentosofia - la salute passa anche dai denti [ <a href="../Documenti/denti-e-salute.pdf">PDF 200KB</a> ]</li>
				</ul>
		</div>
	</div>
END;
	footerdiv();
?>
