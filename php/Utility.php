<?php
function page_start($title)
{
echo<<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
END;
	echo "<title>$title</title>\n";
echo<<<END
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta name="description" content="YouTooth" />
	<meta name="keywords" content="YouTooth, denti, cura dei denti" />
	<meta name="language" content="italian it" />
	<meta name="author" content="Daniel De Gaspari" />
	<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen and (min-width: 650px)" />
	<link rel="stylesheet" type="text/css" href="../css/small-devices.css" media="screen and (max-width: 650px)" />
	<link rel="icon" href="../immagini/logo.jpg" type="image/jpg" />
</head>
<body>
END;
}

function headerdiv($title)
{
echo<<<END
	<div id="header">
		<span id="logo"></span>
		<h1>YouTooth</h1>
	</div>
END;
}

function pathdiv($path, $username)
{
	echo "<div id='path'>";
	if (isset($username))
	{
		echo "	<p id='benvenuto'>Benvenuto/a $username </p>";
		echo "	<p>Ti trovi in: <span xml:lang='en'>$path</span></p>";
	}
	else
	{
		echo "	<p>Ti trovi in: <span xml:lang='en'>$path</span></p>";
	}
	echo "</div>";
}

function navdiv($username,$privilegi)
{
echo<<<END
<!-- menu laterale sotto al logo, orientamento verticale -->
	<div id="menu">
		<ul>
			<li><a href="Home.php"><span xml:lang="en">Home</span></a></li>
			<li><a href="Chi_siamo.php">Chi siamo</a></li>
			<li><a href="Orario.php">Orari</a></li>
			<li><a href="Dove_siamo.php">Dove siamo</a></li>
			<li><a href="Servizi.php">I nostri servizi</a></li>
			<li><a href="Medici.php">I nostri medici</a></li>
END;
	if(!isset($username))
	{
		echo "\n<li><a href=\"Login.php\">Login</a></li>\n";
		echo "<li><a href=\"Registrati.php\">Registrati</a></li>\n";
	}
	else
	{
		echo "\n<li><a href=\"Profilo.php\">Il mio profilo</a></li>\n";
		if ($privilegi=='paz') {
			echo "<li><a href=\"Mie_visite.php\">Le mie visite</a></li>\n";		
			echo "<li><a href=\"Logout.php\">Logout</a></li>\n";
		}
		elseif ($privilegi=='med') {
			echo "<li><a href=\"Appuntamenti.php\">Appuntamenti</a></li>\n";
			echo "<li><a href=\"Registra_personale.php\">Registra nuovo Personale</a></li>\n";
			echo "<li><a href=\"Logout.php\">Logout</a></li>\n";
		}
		elseif ($privilegi=='seg') {
			echo "<li><a href=\"Registra_personale.php\">Registra nuovo Personale</a></li>\n";
			echo "<li><a href=\"Prenota_visita.php\">Prenota Visita</a></li>\n";
			echo "<li><a href=\"Modifica_visita.php\">Modifica visita</a></li>\n";
			echo "<li><a href=\"Altre_operazioni.php\">Altre Operazioni</a></li>\n";
			echo "<li><a href=\"Logout.php\">Logout</a></li>\n";
		}
	}
	echo "</ul>\n";
	echo "</div>\n";
}

function footerdiv()
{
echo<<<END
	<div id="footer">
		<img class="imgValidCode" src="../immagini/valid-xhtml10.png" alt="XHTML valido" /><img class="imgValidCode" 
		src="../immagini/vcss.gif" alt="CSS valido" />
	</div>
</body>
</html>
END;
}

/*page_start per la pagina "dove siamo"*/
function page_start_where($title)
{
echo<<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
END;
	echo "<title>$title</title>\n";
echo<<<END
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta name="description" content="YouTooth" />
	<meta name="keywords" content="YouTooth, denti, cura dei denti" />
	<meta name="language" content="italian it" />
	<meta name="author" content="Daniel De Gaspari" />
	<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen and (min-width: 650px)" />
	<link rel="stylesheet" type="text/css" href="../css/small-devices.css" media="screen and (max-width: 650px)" />
	<link rel="icon" href="../immagini/logo.jpg" type="image/jpg" />
	<script type="text/javascript" src="../script/script.js"></script>
</head>
<body onload="replaceMap()">
END;
}

function lunghezza($stringa,$n)
{
	return strlen($stringa)>=$n;
}

function lunghezza_max($stringa,$n)
{
	return strlen($stringa)<=$n;
}

function solocaratteri($stringa)
{
	return preg_match("/^[A-Za-z]+(\s[A-Za-z]+)*$/",$stringa);
}



?>
