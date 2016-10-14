<?php
	session_start();
	require "Utility.php";
	page_start_where("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Dove siamo",$username);
	navdiv($username,$privilegi);
echo<<<END
	<div id="section">
		<a name="contenutopagina"></a>
		<h1 class="titolo">Dove siamo</h1>
			<div id="info">
				<p><strong>Indirizzo</strong>: Via Parolini, 11 30010 Camponogara VE</p>
				<p><strong>Telefono</strong>: 041 9999999 </p>
				<p><strong>E-mail</strong>: <a href="">YouTooth@YouTooth.it</a></p>
			</div>

			<div id="divMappa">
				<p>Consulta la seguente mappa:</p>
				<div id="visualizzaMappa">
					<a href="https://www.google.it/maps/place/Via+Parolini,+11,+30010+Camponogara+VE/@45.384382,12.075781,17z/data=!4m2!3m1!1s0x477ec7ace369e9db:0xfe2a4c9605f9ae2b"><img id="fotoMappa" src="../immagini/mappa.png" alt="Mappa della posizione dello studio dentistico" width="100%" height="100%"/></a>
				</div>
			</div>
	</div>
END;
	footerdiv();
?>
