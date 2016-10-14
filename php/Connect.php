<?php
$host="127.0.0.1";
$user="ddegaspa";
$pwd="F7EFOtxU";
$database="ddegaspa-PR";
/*Apertura connessione al server MySql*/
$conn = mysqli_connect($host, $user, $pwd, $database);
if (!$conn)
{
	echo ("errore nell'apertura del server MySQL");
	exit();
}
?>
