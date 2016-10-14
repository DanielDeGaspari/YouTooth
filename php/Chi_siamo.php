<?php
	session_start();
	require "Utility.php";
	page_start_where("YouTooth");
	headerdiv("YouTooth");
	$username=$_SESSION['username'];
	$privilegi=$_SESSION['privilegi'];
	pathdiv("HOME PAGE > Chi siamo",$username);
	navdiv($username,$privilegi);
echo<<<END
	<div id="section">
	<div id="img_storia">
			<img src="../immagini/loStudio.jpg" alt="Immagine storica"/>
		</div>

		<p id="testo_storia"> 
			<span id="titolo_storia">Chi siamo</span><br />
Lo studio Dentistico YouTooth nasce nel 1998, con la passione e l'impegno crescenti nella cura del cavo orale dalle patologie di competenza odontoiatrica. L'organizzazione e la metodologia di lavoro sono sempre state finalizzate al conseguimento di risultati clinici ottimali, avendo la massima attenzione per la persona. L'intero staff è impegnato a realizzare un ambiente ospitale che valorizzi la relazione medico-paziente.
<br /><br />
Lo studio si impegna in modo completo per il paziente dedicandogli il proprio lavoro, il proprio tempo e la propria professionalit&agrave;, e offrendo un ambiente ospitale e igienicamente impeccabile.
L'ambulatorio si occupa in particolare di odontoiatria, estetica dentale, implantologia e prevenzione dei disturbi dentali e gengivali.
<br />
Un'efficiente struttura organizzativa garantisce le migliori terapie cliniche in ambito odontoiatrico.
Professionalit&agrave; e rapporti umani di fiducia medico-paziente sono alla base della filosofia dell'ambulatorio.
<br /><br />
<strong><span class="span_testo_obiettivi">Il rapporto con i clienti:</span></strong><br />
Il dialogo con i clienti è alla base dell'attivit&agrave; dello studio che, attraverso precise spiegazioni (anche tramite illustrazioni) riguardanti le terapie che a giudizio del medico sono da eseguire, è in grado di ottenere il consenso a procedere rasserenando il paziente durante il trattamento.
<br /><br />
Il rapporto di fiducia interpersonale tra paziente e dentista è necessario in una situazione come quella che si propone in un ambulatorio odontoiatrico, dove la qualit&agrave; del lavoro non la si percepisce nell'immediato ma a distanza anche di qualche anno.
<br /><br /> Chi è gi&agrave; cliente abituale dello studio sa che queste poche e semplici regole sono fondamentali e vere; a chi invece non conosce ancora il modo di lavorare dell'ambulatorio, lo staff è desideroso di dimostrarlo e certo di riuscire nell'intento.
		</p>
	</div>
END;
	footerdiv();
?>
