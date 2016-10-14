/*PULIZIA====================================================================================*/

/* Rimuovo eventuali tabelle esistenti*/
DROP TABLE IF EXISTS Effettuazione;
DROP TABLE IF EXISTS SezioneVisita;
DROP TABLE IF EXISTS Visita;
DROP TABLE IF EXISTS Specializzazione;
DROP TABLE IF EXISTS TipoVisita;
DROP TABLE IF EXISTS Segretaria;
DROP TABLE IF EXISTS Medico;
DROP TABLE IF EXISTS Paziente;
DROP TABLE IF EXISTS Account;

/* Rimuovo eventuali viste esistenti*/
DROP VIEW IF EXISTS query1;
DROP VIEW IF EXISTS query1_aux;
DROP VIEW IF EXISTS query1_aux2;
DROP VIEW IF EXISTS query2;
DROP VIEW IF EXISTS query3;
DROP VIEW IF EXISTS query4;
DROP VIEW IF EXISTS query4_aux_clienti_non_recenti;
DROP VIEW IF EXISTS query5;
DROP VIEW IF EXISTS query5_aux_solo_giudiziosi;
DROP VIEW IF EXISTS query6;

/* Rimuovo eventuali trigger esistenti*/
DROP TRIGGER IF EXISTS check_data_ora_insert;
DROP TRIGGER IF EXISTS check_data_ora_update;
DROP TRIGGER IF EXISTS check_prezzo_positivo_upd;
DROP TRIGGER IF EXISTS check_prezzo_positivo_ins;
DROP TRIGGER IF EXISTS check_medico_libero_specializzato_insert;
DROP TRIGGER IF EXISTS check_medico_libero_specializzato_update;
DROP TRIGGER IF EXISTS segretaria_non_medico_insert;
DROP TRIGGER IF EXISTS segretaria_non_medico_update;
DROP TRIGGER IF EXISTS medico_non_segretaria_insert;
DROP TRIGGER IF EXISTS medico_non_segretaria_update;

/* Rimuovo eventuali function e procedure esistenti*/
DROP PROCEDURE IF EXISTS ri_assumi;
DROP PROCEDURE IF EXISTS assumi;
DROP PROCEDURE IF EXISTS disponibile;
DROP FUNCTION IF EXISTS is_operazione_disponibile;
DROP FUNCTION IF EXISTS prenotazioni_future;
DROP FUNCTION IF EXISTS totali;

/*DEFINIZIONE TABELLE====================================================================================*/

/* Creazione della tabella Account */
CREATE TABLE Account (
	username VARCHAR(64) PRIMARY KEY,
	pwd VARCHAR(64) NOT NULL,
    privilegi ENUM('paz', 'med', 'seg') DEFAULT 'paz' NOT NULL
);

/* Creazione della tabella Paziente */
CREATE TABLE Paziente (
	cod_fiscale CHAR(16) PRIMARY KEY,
	nome VARCHAR(64) NOT NULL,
	cognome VARCHAR(64) NOT NULL,
    data_nascita DATE,
	indirizzo VARCHAR(64),
	comune VARCHAR(64),
	prov CHAR(2),
	telefono VARCHAR(64),
	sesso ENUM('M', 'F'),
	e_mail VARCHAR(64) UNIQUE,	
	account VARCHAR(64),
	FOREIGN KEY (account)
				REFERENCES Account(username)
				ON DELETE NO ACTION
				ON UPDATE CASCADE
);

/* Creazione della tabella Medico */
CREATE TABLE Medico (
	cod_fiscale CHAR(16) PRIMARY KEY,
	nome VARCHAR(64) NOT NULL,
	cognome VARCHAR(64) NOT NULL,
    data_nascita DATE,
	indirizzo VARCHAR(64),
	comune VARCHAR(64),
	prov CHAR(2),
	telefono VARCHAR(64),
	sesso ENUM('M', 'F'),
	e_mail VARCHAR(64) UNIQUE,	
	data_assunzione DATE,
	data_licenziamento DATE DEFAULT NULL,
	account VARCHAR(64),
	FOREIGN KEY (account)
				REFERENCES Account(username)
				ON DELETE SET NULL
				ON UPDATE CASCADE
);

/* Creazione della tabella Segretaria */
CREATE TABLE Segretaria (
	cod_fiscale CHAR(16) PRIMARY KEY,
	nome VARCHAR(64) NOT NULL,
	cognome VARCHAR(64) NOT NULL,
    data_nascita DATE,
	indirizzo VARCHAR(64),
	comune VARCHAR(64),
	prov CHAR(2),
	telefono VARCHAR(64),
	sesso ENUM('M', 'F'),
	e_mail VARCHAR(64) UNIQUE,	
	data_assunzione DATE,
	data_licenziamento DATE DEFAULT NULL,
	account VARCHAR(64),
	FOREIGN KEY (account)
				REFERENCES Account(username)
				ON DELETE SET NULL
				ON UPDATE CASCADE
);

CREATE TABLE TipoVisita (
	descrizione VARCHAR(64) PRIMARY KEY,
	prezzo NUMERIC,
	note VARCHAR(256)
);

CREATE TABLE Specializzazione (
	medico CHAR(16),
	tipo_visita VARCHAR(64),
	PRIMARY KEY (medico, tipo_visita),
	FOREIGN KEY (medico) REFERENCES Medico (cod_fiscale) ON DELETE NO ACTION ON UPDATE CASCADE,
	FOREIGN KEY (tipo_visita) REFERENCES TipoVisita (descrizione) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE Visita (
	id_visita INT AUTO_INCREMENT PRIMARY KEY,
	paziente CHAR(16),
	data DATE,
	ora TIME,
	UNIQUE (paziente, data, ora),
	FOREIGN KEY (paziente) REFERENCES Paziente (cod_fiscale) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE SezioneVisita (
	tipo_visita VARCHAR(64),
	id_visita INT,
	FOREIGN KEY (tipo_visita) REFERENCES TipoVisita (descrizione) ON DELETE NO ACTION ON UPDATE CASCADE,
	FOREIGN KEY (id_visita) REFERENCES Visita (id_visita) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (tipo_visita, id_visita)
);

CREATE TABLE Effettuazione (
	medico CHAR(16),
	tipo_visita VARCHAR(64),
	id_visita INT,
	FOREIGN KEY (tipo_visita, id_visita) REFERENCES SezioneVisita (tipo_visita, id_visita) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (medico) REFERENCES Medico (cod_fiscale) ON DELETE NO ACTION ON UPDATE CASCADE,
	PRIMARY KEY (medico, tipo_visita, id_visita)
);



/*TRIGGER====================================================================================*/

/*
Una segretaria non può essere registrata anche come medico.
*/
DELIMITER |
	CREATE TRIGGER segretaria_non_medico_insert BEFORE INSERT ON Segretaria FOR EACH ROW
	IF (NEW.cod_fiscale IN (SELECT cod_fiscale FROM Medico WHERE cod_fiscale=NEW.cod_fiscale))
	THEN /*DELETE FROM Medico WHERE(NEW.cod_fiscale = Medico.cod_fiscale)*/
	INSERT INTO Medico
		SELECT * FROM Medico LIMIT 1;
	END IF;
|
DELIMITER ;

DELIMITER |
	CREATE TRIGGER segretaria_non_medico_update BEFORE UPDATE ON Segretaria FOR EACH ROW
	IF (NEW.cod_fiscale IN (SELECT cod_fiscale FROM Medico WHERE cod_fiscale=NEW.cod_fiscale))
	THEN INSERT INTO Medico
		SELECT * FROM Medico LIMIT 1;
	END IF;
|
DELIMITER ;



/*
Un medico non può essere registrato anche come segretaria.
*/
DELIMITER |
	CREATE TRIGGER medico_non_segretaria_insert BEFORE INSERT ON Medico FOR EACH ROW
	IF (NEW.cod_fiscale IN (SELECT cod_fiscale FROM Segretaria WHERE cod_fiscale=NEW.cod_fiscale))
	THEN INSERT INTO Medico
		SELECT * FROM Medico LIMIT 1;
	END IF;
|
DELIMITER ;

DELIMITER |
	CREATE TRIGGER medico_non_segretaria_update BEFORE UPDATE ON Medico FOR EACH ROW
	IF (NEW.cod_fiscale IN (SELECT cod_fiscale FROM Segretaria WHERE cod_fiscale=NEW.cod_fiscale))
	THEN INSERT INTO Medico
		SELECT * FROM Medico LIMIT 1;
	END IF;
|
DELIMITER ;



/*
Una tipologia di visita non puo' avere un prezzo negativo.
*/
DELIMITER |
CREATE TRIGGER check_prezzo_positivo_ins AFTER INSERT ON TipoVisita FOR EACH ROW
IF NEW.prezzo < 0 THEN
DELETE FROM TipoVisita
WHERE NEW.Prezzo = TipoVisita.prezzo;
END IF;
|
DELIMITER ;

DELIMITER |
CREATE TRIGGER check_prezzo_positivo_upd AFTER UPDATE ON TipoVisita FOR EACH ROW
IF NEW.prezzo < 0 THEN
DELETE FROM TipoVisita
WHERE NEW.Prezzo = TipoVisita.prezzo;
END IF;
|
DELIMITER ;



/*controllo che prima di licenziare un medico, questo non abbia visite future prenotate
*/
DELIMITER |
	CREATE TRIGGER check_medico_licenziato_libero_insert AFTER INSERT ON Medico
	FOR EACH ROW
	IF (prenotazioni_future(NEW.cod_fiscale, NEW.data_licenziamento)<>0)
	THEN
	UPDATE Medico
	SET Medico.data_licenziamento = NULL
	WHERE Medico.cod_fiscale = NEW.cod_fiscale;
	END IF;
|
DELIMITER ;

DELIMITER |
	CREATE TRIGGER check_medico_licenziato_libero_update AFTER UPDATE ON Medico
	FOR EACH ROW
	IF (prenotazioni_future(NEW.cod_fiscale, NEW.data_licenziamento)<>0)
	THEN
	UPDATE Medico
	SET Medico.data_licenziamento = NULL
	WHERE Medico.cod_fiscale = NEW.cod_fiscale;
	END IF;
|
DELIMITER ;



/*controllo che un medico non abbia due visite nello stesso momento e che sia specializzato in quella tipologia in inserimento*/
DELIMITER |
	CREATE TRIGGER check_medico_libero_specializzato_insert AFTER INSERT ON Effettuazione
	FOR EACH ROW
	IF NEW.medico NOT IN (
		SELECT Specializzazione.medico
		FROM Specializzazione
		WHERE Specializzazione.tipo_visita = NEW.tipo_visita
	) OR NEW.medico IN (
		SELECT e1.medico
		FROM (Effettuazione AS e1 JOIN Visita AS v1 ON (e1.id_visita = v1.id_visita))

				JOIN
			 (Effettuazione AS e2 JOIN Visita AS v2 ON (e2.id_visita = v2.id_visita))
				ON e1.medico=e2.medico
		WHERE v1.data=v2.data AND v1.ora=v2.ora AND v1.id_visita != v2.id_visita
	)
	THEN
	DELETE FROM Effettuazione
	WHERE NEW.medico=Effettuazione.medico AND NEW.tipo_visita=Effettuazione.tipo_visita AND NEW.id_visita=Effettuazione.id_visita;
	END IF;
|
DELIMITER ;

/*controllo che un medico non abbia due visite nello stesso momento e che sia specializzato in quella tipologia in modifica*/
DELIMITER |
	CREATE TRIGGER check_medico_libero_specializzato_update AFTER UPDATE ON Effettuazione
	FOR EACH ROW
	IF NEW.medico NOT IN (
		SELECT Specializzazione.medico
		FROM Specializzazione
		WHERE Specializzazione.tipo_visita = NEW.tipo_visita
	) OR NEW.medico IN (
		SELECT e1.medico
		FROM (Effettuazione AS e1 JOIN Visita AS v1 ON (e1.id_visita = v1.id_visita))

				JOIN
			 (Effettuazione AS e2 JOIN Visita AS v2 ON (e2.id_visita = v2.id_visita))
				ON e1.medico=e2.medico
		WHERE v1.data=v2.data AND v1.ora=v2.ora AND v1.id_visita != v2.id_visita
	)
	THEN
	DELETE FROM Effettuazione
	WHERE NEW.medico=Effettuazione.medico AND NEW.tipo_visita=Effettuazione.tipo_visita AND NEW.id_visita=Effettuazione.id_visita;
	END IF;
|
DELIMITER ;



/*FUNCTION========================================================================*/

/*riceve i dati di una operazione ossia tipologia, un giorno e un orario e ritorna true se è disponibile
(cioè se ci sono medici non licenziati disponibili in quella data ora specializzati in quella tipologia), false altrimenti */
DELIMITER |
CREATE FUNCTION is_operazione_disponibile(tipologia VARCHAR(64), giorno DATE, orario TIME)
RETURNS BOOLEAN
BEGIN
DECLARE cont INT(4);
 
SELECT COUNT(*) INTO cont
FROM Specializzazione as s
WHERE s.tipo_visita = tipologia AND s.medico NOT IN (
    SELECT e.medico
    FROM Effettuazione as e 
		JOIN Visita as v ON (e.id_visita = v.id_visita)
    WHERE v.data = giorno AND v.ora = orario
    UNION
    SELECT m.cod_fiscale
    FROM Medico as m
    WHERE m.data_licenziamento IS NOT NULL
);
 
IF cont > 0 THEN
    RETURN TRUE;
ELSE
    RETURN FALSE;
END IF;
 
END
|
DELIMITER ;
 
/*La funzione restituisce il numero di visite prenotate con il medico medico, in un periodo successivo
  alla data di licenziamento di medico*/
DELIMITER |
CREATE FUNCTION prenotazioni_future (medico CHAR(16), giorno_licenziamento DATE)
RETURNS INT
BEGIN
DECLARE conteggio INT;
SET conteggio = 0;
    SELECT count(*) INTO conteggio
    FROM Medico as m JOIN Effettuazione as e ON m.cod_fiscale=e.medico
                    JOIN SezioneVisita as sv ON (e.tipo_visita=sv.tipo_visita AND e.id_visita=sv.id_visita)
                    JOIN Visita as v ON v.id_visita=sv.id_visita
    WHERE v.data > giorno_licenziamento AND m.cod_fiscale=medico;
RETURN conteggio;
END
|
DELIMITER ;

/*FUNCTION dato in input un codice fiscale, trovare la somma totale di quanto hanno speso nelle visite */
DELIMITER |
CREATE FUNCTION totali(cf VARCHAR(64))
RETURNS NUMERIC
BEGIN
DECLARE tot NUMERIC;

SELECT SUM(tv.prezzo) INTO tot
FROM Paziente as p
	JOIN Visita as v ON v.paziente=p.cod_fiscale
	JOIN SezioneVisita as sv ON sv.id_visita=v.id_visita
	JOIN TipoVisita as tv ON tv.descrizione = sv.tipo_visita
WHERE p.cod_fiscale = cf;

RETURN tot;
END
|
DELIMITER ;

/*PROCEDURE========================================================================*/


/*La procedura ri_assumi effettua l'aggiunta di un nuovo account per un personale che ha già lavorato nello studio, oltre all'aggiornamento delle informazioni presenti su di esso nel DB.*/
DELIMITER |
CREATE PROCEDURE ri_assumi(IN newcod_fiscale CHAR(16), newnome VARCHAR(64), newcognome VARCHAR(64), newdata_nascita DATE, newindirizzo VARCHAR(64), newcomune VARCHAR(64), newprov CHAR(2), newtelefono VARCHAR(64), newsesso CHAR(1), newe_mail VARCHAR(64), newusername VARCHAR(64), newpassword VARCHAR(64), newprivilegi CHAR(3))
BEGIN
	DECLARE conta INT(4);
	
	SELECT COUNT(*) INTO conta
	FROM Account
	WHERE newusername = username;

	IF conta = 0 THEN
		INSERT INTO Account (username, pwd, privilegi) VALUES
				(newusername, newpassword, newprivilegi);

		IF newprivilegi='med' THEN
			UPDATE Medico
			SET cod_fiscale=newcod_fiscale, nome=newnome, cognome=newcognome, data_nascita=newdata_nascita, indirizzo=newindirizzo, comune=newcomune, prov=newprov, telefono=newtelefono, sesso=newsesso, e_mail=newe_mail, data_assunzione=CURRENT_DATE(), data_licenziamento=NULL, account= newusername
			WHERE cod_fiscale=newcod_fiscale;
		ELSEIF newprivilegi='seg' THEN
			UPDATE Segretaria
			SET cod_fiscale=newcod_fiscale, nome=newnome, cognome=newcognome, data_nascita=newdata_nascita, indirizzo=newindirizzo, comune=newcomune, prov=newprov, telefono=newtelefono, sesso=newsesso, e_mail=newe_mail, data_assunzione=CURRENT_DATE(), data_licenziamento=NULL, account= newusername
			WHERE cod_fiscale=newcod_fiscale;
		END IF;
	END IF;
END
|
DELIMITER ;



/*La procedura assumi effettua l'aggiunta di un nuovo account per un personale che non ha mai lavorato nello studio, oltre all'inserimento dei dati nella rispetta tabella "Segretaria" o "Medico", a seconda della tipologia che andiamo ad inserire.*/
DELIMITER |
CREATE PROCEDURE assumi(IN newcod_fiscale CHAR(16), newnome VARCHAR(64), newcognome VARCHAR(64), newdata_nascita DATE, newindirizzo VARCHAR(64), newcomune VARCHAR(64), newprov CHAR(2), newtelefono VARCHAR(64), newsesso CHAR(1), newe_mail VARCHAR(64), newusername VARCHAR(64), newpassword VARCHAR(64), newprivilegi CHAR(3))
BEGIN

		INSERT INTO Account (username, pwd, privilegi) VALUES
				(newusername, newpassword, newprivilegi);

		IF newprivilegi='med' THEN
			INSERT INTO Medico (cod_fiscale, nome, cognome, data_nascita, indirizzo, comune, prov, telefono, sesso, e_mail, data_assunzione, data_licenziamento, account) VALUES
			(newcod_fiscale,newnome,newcognome,newdata_nascita,newindirizzo,newcomune,newprov,newtelefono,newsesso, newe_mail, CURRENT_DATE(),NULL,newusername);
		ELSEIF newprivilegi='seg' THEN
			INSERT INTO Segretaria (cod_fiscale, nome, cognome, data_nascita, indirizzo, comune, prov, telefono, sesso, e_mail, data_assunzione, data_licenziamento, account) VALUES
			(newcod_fiscale,newnome,newcognome,newdata_nascita,newindirizzo,newcomune,newprov,newtelefono,newsesso, newe_mail, CURRENT_DATE(),NULL,newusername);
		END IF;

END
|
DELIMITER ;



/*La procedura disponibile invoca la funzione is_operazione_disponibile(tipologia, giorno, orario) e restituisce in disp l'output di tale funzione*/
DELIMITER |
CREATE PROCEDURE disponibile(IN tipologia VARCHAR(64), giorno DATE, orario TIME, OUT disp BOOL)
BEGIN
	SET disp = is_operazione_disponibile(tipologia, giorno, orario);
END
|
DELIMITER ;

/*INSERIMENTI======================================================================*/

INSERT INTO TipoVisita (descrizione, prezzo, note) VALUES
('Sbiancamento', '200', 'Sbiancamento a laser di tutti i denti'),
('Corona in ceramica', '200', 'Corona in ceramica priva di metallo'),
('Otturazione carie', '30', 'otturazione di una carie su un dente'),
('Studio caso odontoiatrico', '150', 'Studio caso odontoiatrico, odontoiatria conservativa'),
('Igiene', '70', 'pulizia dei denti'),
('Cementazione', '20', 'Cementazione'),
('Radiografia endorale', '15', 'Cementazione'),
('Cura canalare incisivi canini', '180', 'Detta anche devitalizzazione, consiste nella rimozione della polpa dentale e dei residui batterici all"interno del canale radicolare, disinfezione e allargamento dello stesso e successivo riempimento con materiale inerte'),
('Cura canalare premolari', '180', 'Detta anche devitalizzazione, consiste nella rimozione della polpa dentale e dei residui batterici all"interno del canale radicolare, disinfezione e allargamento dello stesso e successivo riempimento con materiale inerte'),
('Cura canalare molari', '250', 'Detta anche devitalizzazione, consiste nella rimozione della polpa dentale e dei residui batterici all"interno del canale radicolare, disinfezione e allargamento dello stesso e successivo riempimento con materiale inerte'),
('Estrazione deciduo', '15', 'Estrazione dei denti decidui, volgarmente detti denti da latte'),
('Estrazione dente del giudizio', '50', 'Estrazione dei denti del giudizio'),
('Sigillatura', '20', 'Procedura raccomandata per prevenire la formazione di carie'),
('Scheletrato', '2500', 'Soluzione ideale ai problemi di edentulia (mancanza di denti), parziale o totale');

/*correggere le password*/
INSERT INTO Account (username, pwd, privilegi) VALUES
('Daniel', '7b37259e149636e3330d530cbf408f2b8c1eda6a', 'med'),
('Giulia', 'dc942fe8fab74e62a9e05ce00d01c99f0514535e', 'seg'),
('Lazzaro.Omar', 'd53711bc955cc461dae5a262e37e5138eaff079f', 'paz'),
('mario.legionario', '52d2952bbe10bd2890057db2bc3fd0bdefa62f70', 'paz'),
('mariangela.angela', '263a0ba489768c6685fd69e0c9584a45811cb28c', 'med'),
('annabella.rosaria', '9134973aad83240b243b54758cd65e95f328af7e', 'paz'),
('lucia.bentivoglio', 'ebede235625373167f97b4cbe18ad4f03ccc1050', 'paz'),
('Daniel_paziente', 'e38ad214943daad1d64c102faec29de4afe9da3d', 'paz'),
('Camilla', '695e26ac527003167ec0fda478dc3a7283b7e10b', 'med'),
('Marco', 'bf38aa5369d719aa18b97d8b9c78ddc68193cff7', 'med'),
('mariangela_paziente', '263a0ba489768c6685fd69e0c9584a45811cb28c', 'paz'),
('alessio.fabiano', '32cc128588ebd3b2e4bcea75084245f91b842019', 'paz'),
('lucia.javorcekova', '1dce3d5072da07c1a6aa43dbf896689016f05cd5', 'paz'),
('martina.belvedere', '91ff4da3b5876dc3d1e5ee424726c6de54bfef9b', 'paz'),
('celeste.violetta', 'bb577aefe4e546b21abbeee38230de34d54d8848', 'paz');

/*inserimento di qualche paziente*/
INSERT INTO Paziente (cod_fiscale, nome, cognome, data_nascita, indirizzo, comune, prov, telefono, sesso, e_mail, account) VALUES
('DGSDNL94M27L736L', 'Daniel', 'De Gaspari', '1994-08-27', 'Via 8 Marzo 2', 'Camponogara', 'VE', '0111111111', 'M', 'daniel_DG@hotmail.it', 'Daniel_paziente'),
('LGNMRA79B05L736F', 'Mario', 'Legio', '1979-02-05', 'Via Venezia 25', 'Venezia', 'Ve', '041414141', 'M', 'mario.legionario@gmail.com', 'mario.legionario'),
('LZZMRO93R18D325H', 'Omar', 'Lazzaro', '1993-10-18', 'Via Garibaldi 25', 'Dolo', 'VE', '041424242', 'M', 'OmarLazzaro@email.com', 'Lazzaro.Omar'),
('BNTLCU01A01G224K', 'Lucia', 'Bentivoglio', '1983-10-18', 'Via Garibaldi 23', 'Dolo', 'VE', '041424142', 'F', 'Lucia@email.com', 'lucia.bentivoglio'),
('NNBRSR67A56D325R', 'Annabella', 'Rosaria', '1967-01-16', 'Via Venezia', 'Dolo', 'VE', '0411155443', 'F', 'annabella.rosario@gmail.com', 'annabella.rosaria'),
('JVRLCU90R58Z138E', 'Lucia', 'Javorcekova', '1990-10-18', 'Via della Bellezza 45', 'Dolo', 'VE', '041464942', 'F', 'Javorcekova@gmail.com', 'lucia.javorcekova'),
('BLVMTN95B68L736B', 'Martina', 'Belvedere', '1995-02-28', 'Via Milano 113', 'Sambruson', 'VE', '0414634992', 'F', 'martina95@gmail.com', 'martina.belvedere'),
('VLTCST96A68D325Q', 'Celeste', 'Violetta', '1996-01-28', 'Via Napoli 1', 'Mirano', 'VE', '0414634992', 'F', 'celestemulticolor@gmail.com', 'celeste.violetta'),
('LSSFBN80C18L840B', 'Alessio', 'Fabiano', '1980-3-18', 'Via Bologna 11', 'Vicenza', 'VI', '0436622112', 'M', 'Alessio.fabiano@email.com', 'alessio.fabiano'),
('NGLMNG95R59D325X', 'Mariangela', 'Angela', '1995-10-19', 'Via Roma 22', 'Padova', 'PD', '041434343', 'F', 'mariangela.angela@hotmail.com', 'mariangela_paziente');

/*inserimento di due medici licenziati e quindi con campo account a null e altri medici*/
INSERT INTO Medico (cod_fiscale, nome, cognome, data_nascita, indirizzo, comune, prov, telefono, sesso, e_mail, data_assunzione, data_licenziamento, account) VALUES
('DGSDNL94M27L736L', 'Daniel', 'De Gaspari', '1994-08-27', 'via 8 marzo 2', 'Camponogara', 'VE', '456', 'M', 'daniel@gmail.com', '1998-08-30', NULL, 'Daniel'),
('NGLMNG95R59D325X', 'Mariangela', 'Angela', '1995-10-19', 'Via Roma 22', 'Padova', 'PD', '041434343', 'F', 'mariangela.angela@hotmail.com', '2016-05-30', NULL, 'mariangela.angela'),
('MLLMRC85T12L736P', 'Marco', 'Muller', '1985-12-12', 'Via SanMarco 54', 'Venezia', 'Ve', '0311115554', 'M', 'mullerMarco@gmail.com','2010-09-11', NULL, 'Marco'),
('CMLGTT95M51L781P', 'Camilla', 'Gatto', '1995-11-08', 'Via Gattopardo 11', 'Verona', 'VR', '34198573421', 'F', 'GattoCamilla@gmail.com','2016-01-11', NULL, 'Camilla'),
('FRTFRC92T54L840P', 'Federica', 'Fortunata', '1992-12-14', 'Via della fortuna 1', 'Vicenza', 'Vi', '0414444444', 'F', 'fede@libero.it', '2016-05-30', '2016-05-30', NULL);

INSERT INTO Segretaria (cod_fiscale, nome, cognome, data_nascita, indirizzo, comune, prov, telefono, sesso, e_mail, data_assunzione, data_licenziamento, account) VALUES
/*inserimento di una segretaria licenziata e quindi con campo account a null*/
('AAAAAA00A00A000A', 'Anna', 'Bella', '1990-01-01', 'via dell Amore', 'Sarego', 'VI', '45', 'F', 'annabella@gmail.com', '2001-02-02', '2010-05-05', NULL),
/*inserimento di una segretaria attiva con il suo account*/
('PTNGLI95L65A459O', 'Giulia', 'Petenazzi', '1995-07-25', 'via San Marcello 24', 'Veronella', 'VR', '123', 'F', 'giulia@gmail.com', '1998-08-31', NULL, 'Giulia');


INSERT INTO Specializzazione (medico, tipo_visita) VALUES
('DGSDNL94M27L736L', 'Corona in ceramica'),
('DGSDNL94M27L736L', 'Studio caso odontoiatrico'),
('DGSDNL94M27L736L', 'Cementazione'),
('DGSDNL94M27L736L', 'Radiografia endorale'),
('DGSDNL94M27L736L', 'Cura canalare incisivi canini'),
('DGSDNL94M27L736L', 'Cura canalare premolari'),
('DGSDNL94M27L736L', 'Cura canalare molari'),
('DGSDNL94M27L736L', 'Scheletrato'),
('DGSDNL94M27L736L', 'Estrazione dente del giudizio'),
('DGSDNL94M27L736L', 'Estrazione deciduo'),
('FRTFRC92T54L840P', 'Estrazione deciduo'),
('NGLMNG95R59D325X', 'Estrazione deciduo'),
('DGSDNL94M27L736L', 'Sigillatura'),
('FRTFRC92T54L840P', 'Sigillatura'),
('NGLMNG95R59D325X', 'Sigillatura'),
('DGSDNL94M27L736L', 'Otturazione carie'),
('FRTFRC92T54L840P', 'Otturazione carie'),
('NGLMNG95R59D325X', 'Otturazione carie'),
('DGSDNL94M27L736L', 'Sbiancamento'),
('FRTFRC92T54L840P', 'Sbiancamento'),
('NGLMNG95R59D325X', 'Sbiancamento'),
('DGSDNL94M27L736L', 'Igiene'),
('FRTFRC92T54L840P', 'Igiene'),
('NGLMNG95R59D325X', 'Igiene');

INSERT INTO Visita (paziente, data, ora) VALUES
('DGSDNL94M27L736L', '2016-08-08', '10:00:00'),
('LGNMRA79B05L736F', '2016-08-09', '10:40:00'),
('LZZMRO93R18D325H', '2016-05-11', '10:00:00'),
('LGNMRA79B05L736F', '2016-05-10', '10:30:00'),
('LZZMRO93R18D325H', '2016-05-11', '11:00:00'),
('NNBRSR67A56D325R', '2015-08-10', '11:00:00'),
('NGLMNG95R59D325X', '2010-08-11', '15:00:00'),
('BNTLCU01A01G224K', '2015-04-10', '15:00:00'),
('DGSDNL94M27L736L', '2010-05-17', '16:00:00'),
('LGNMRA79B05L736F', '2010-05-18', '16:00:00'),
('LZZMRO93R18D325H', '2010-08-17', '17:00:00'),
('BNTLCU01A01G224K', '2010-08-18', '17:30:00'),
('JVRLCU90R58Z138E', '2010-08-18', '17:00:00'),
('BLVMTN95B68L736B', '2010-08-24', '10:00:00'),
('VLTCST96A68D325Q', '2010-08-25', '11:00:00'),
('LSSFBN80C18L840B', '2010-08-24', '09:00:00');

INSERT INTO SezioneVisita (tipo_visita, id_visita) VALUES
('Sbiancamento', '1'),
('Igiene', '1'),
('Otturazione carie', '2'),
('Sigillatura', '2'),
('Sbiancamento', '3'),
('Igiene', '3'),
('Scheletrato', '4'),
('Cura canalare molari', '5'),
('Estrazione deciduo', '6'),
('Estrazione dente del giudizio', '7'),
('Scheletrato', '7'),
('Estrazione dente del giudizio','8'),
('Estrazione dente del giudizio','9'),
('Sigillatura', '9'),
('Igiene', '10'),
('Sbiancamento', '10'),
('Igiene', '11'),
('Estrazione dente del giudizio','12'),
('Estrazione dente del giudizio','13'),
('Radiografia endorale','13'),
('Cura canalare molari', '13'),
('Scheletrato', '14'),
('Sbiancamento', '15'),
('Igiene', '15'),
('Sigillatura', '16');

INSERT INTO Effettuazione (medico, tipo_visita, id_visita) VALUES
('NGLMNG95R59D325X', 'Sbiancamento', '1'),
('NGLMNG95R59D325X', 'Igiene', '1'),
('NGLMNG95R59D325X', 'Otturazione carie', '2'),
('DGSDNL94M27L736L', 'Sigillatura', '2'),
('FRTFRC92T54L840P', 'Sbiancamento', '3'),
('FRTFRC92T54L840P', 'Igiene', '3'),
('DGSDNL94M27L736L', 'Scheletrato', '4'),
('DGSDNL94M27L736L', 'Cura canalare molari', '5'),
('DGSDNL94M27L736L', 'Estrazione deciduo', '6'),
('DGSDNL94M27L736L', 'Estrazione dente del giudizio', '7'),
('DGSDNL94M27L736L', 'Scheletrato', '7'),
('DGSDNL94M27L736L', 'Estrazione dente del giudizio', '8'),
('DGSDNL94M27L736L', 'Estrazione dente del giudizio', '9'),
('NGLMNG95R59D325X', 'Sigillatura', '9'),
('FRTFRC92T54L840P', 'Igiene', '10'),
('FRTFRC92T54L840P', 'Sbiancamento', '10'),
('FRTFRC92T54L840P', 'Igiene', '11'),
('DGSDNL94M27L736L', 'Estrazione dente del giudizio','12'),
('DGSDNL94M27L736L', 'Estrazione dente del giudizio','13'),
('DGSDNL94M27L736L', 'Radiografia endorale','13'),
('DGSDNL94M27L736L', 'Cura canalare molari', '13'),
('DGSDNL94M27L736L', 'Scheletrato', '14'),
('FRTFRC92T54L840P', 'Sbiancamento', '15'),
('FRTFRC92T54L840P', 'Igiene', '15'),
('NGLMNG95R59D325X', 'Sigillatura', '16');

/*TRIGGER seconda parte ========================================================================*/
/*
Controllo bonta' della data di prenotazione della visita:
	- no prenotazioni passate
	- no prenotazioni in momenti in cui lo studio e' chiuso
*/
DELIMITER |
	CREATE TRIGGER check_data_ora_insert AFTER INSERT ON Visita FOR EACH ROW
	IF (DAYOFWEEK(NEW.data)='6' OR DAYOFWEEK(NEW.data)='7' OR NEW.data < CURRENT_DATE() OR NEW.ora < '09:00:00' OR NEW.ora >'18:00:00')
	THEN DELETE FROM Visita
	WHERE(NEW.ora = Visita.ora AND NEW.data=Visita.data AND NEW.paziente = Visita.paziente AND 
		NEW.id_visita=Visita.id_visita);
	END IF;
|
DELIMITER ;

DELIMITER |
	CREATE TRIGGER check_data_ora_update AFTER UPDATE ON Visita FOR EACH ROW
	IF (DAYOFWEEK(NEW.data)='6' OR DAYOFWEEK(NEW.data)='7' OR NEW.data < CURRENT_DATE() OR NEW.ora < '09:00:00' OR NEW.ora >'18:00:00')
	THEN DELETE FROM Visita
	WHERE(NEW.ora = Visita.ora AND NEW.data=Visita.data AND NEW.paziente = Visita.paziente AND 
		NEW.id_visita=Visita.id_visita);
	END IF;
|
DELIMITER ;


/*QUERY========================================================================*/

/* QUERY 1 trovare le tipologie di visite che hanno avuto i due massimi numeri di richieste, mostrando anche il numero di volte in cui sono state richieste*/

CREATE VIEW query1_aux AS
SELECT COUNT(*) AS cont, tv.descrizione
FROM TipoVisita as tv JOIN SezioneVisita as sv on tv.descrizione=sv.tipo_visita
GROUP BY tv.descrizione;

CREATE VIEW query1_aux2 AS
SELECT cont
FROM query1_aux
WHERE cont NOT IN 
(
	SELECT MAX(query1_aux.cont)
  	FROM query1_aux
);

CREATE VIEW query1 AS
SELECT tv.descrizione, count(*) as conteggio
FROM TipoVisita as tv JOIN SezioneVisita as sv on tv.descrizione=sv.tipo_visita
GROUP BY tv.descrizione
HAVING conteggio IN
(
	SELECT MAX(query1_aux.cont)
  	FROM query1_aux
) OR conteggio IN
(
	SELECT MAX(query1_aux2.cont) 
	FROM query1_aux2
);

/* QUERY 2 mostra i clienti che hanno fatto almeno una visita la cui tipologia è di prezzo più costoso*/

CREATE VIEW query2 AS
SELECT DISTINCT p.cod_fiscale, p.cognome, p.nome
FROM Paziente as p
	JOIN Visita as v ON v.paziente=p.cod_fiscale
	JOIN SezioneVisita as sv ON sv.id_visita=v.id_visita
	JOIN TipoVisita as tv ON sv.tipo_visita=tv.descrizione
WHERE tv.descrizione IN
	(
	SELECT tv.descrizione
	FROM TipoVisita as tv
	WHERE tv.prezzo IN
			(
			SELECT MAX(tv.prezzo)
			FROM TipoVisita as tv
		    )
	);


/* QUERY 3 il cliente che ha fatto almeno una visita, il cui prezzo è il secondo più costoso*/

CREATE VIEW query3 AS
SELECT DISTINCT p.cod_fiscale, p.cognome, p.nome
FROM Paziente as p
	JOIN Visita as v ON v.paziente=p.cod_fiscale
	JOIN SezioneVisita as sv ON sv.id_visita=v.id_visita
	JOIN TipoVisita as tv ON sv.tipo_visita=tv.descrizione
WHERE tv.descrizione IN
(
		SELECT tv.descrizione
		FROM TipoVisita as tv
		WHERE tv.prezzo IN
		(
			SELECT MAX(tv.prezzo)
			FROM TipoVisita as tv
			WHERE tv.descrizione NOT IN
			(
				SELECT tv.descrizione
				FROM TipoVisita as tv
				WHERE tv.prezzo IN
				(
					SELECT MAX(tv.prezzo)
					FROM TipoVisita as tv
				)
			)
		)
);


/*QUERY 4 mostrare i medici e le segretarie che sono anche pazienti dello studio e che non si fanno visitare da più di 5 anni */


/*ausiliaria: clienti che non si fanno piu vedere da 5 anni numero*/
CREATE VIEW query4_aux_clienti_non_recenti AS
SELECT p.nome, p.cognome, p.cod_fiscale, max(v.data) as UltimaVisita
FROM Paziente as p JOIN Visita as v ON v.paziente=p.cod_fiscale
GROUP BY p.nome, p.cognome, p.cod_fiscale
HAVING UltimaVisita < DATE_SUB(CURRENT_DATE(), INTERVAL 4 YEAR);

CREATE VIEW query4 AS
SELECT DISTINCT m.cod_fiscale
FROM Medico AS m
WHERE m.cod_fiscale
IN (
	SELECT p.cod_fiscale
	FROM Paziente AS p
)
AND m.cod_fiscale
IN (
	SELECT query4_aux_clienti_non_recenti.cod_fiscale
	FROM query4_aux_clienti_non_recenti
)
UNION
SELECT DISTINCT s.cod_fiscale
FROM Segretaria AS s
WHERE s.cod_fiscale
IN (
	SELECT p.cod_fiscale
	FROM Paziente AS p
)
AND s.cod_fiscale
IN (
	SELECT query4_aux_clienti_non_recenti.cod_fiscale
	FROM query4_aux_clienti_non_recenti
);

/*QUERY 5 mostra i medici che hanno effettuato visite a pazienti che hanno fatto SOLO estrazioni di denti del giudizio*/

/*ausiliaria: persone che hanno SOLO tolto denti del giudizio*/
CREATE VIEW query5_aux_solo_giudiziosi AS
SELECT p.cod_fiscale
FROM Paziente as p
WHERE p.cod_fiscale NOT IN (
	SELECT p.cod_fiscale
	FROM Paziente as p JOIN Visita as v ON v.paziente=p.cod_fiscale
						JOIN SezioneVisita as sv ON sv.id_visita=v.id_visita
	WHERE sv.tipo_visita <> 'Estrazione dente del giudizio'
);

CREATE VIEW query5 AS
SELECT DISTINCT m.cod_fiscale, m.cognome, m.nome
FROM Medico AS m
	JOIN Effettuazione as e on m.cod_fiscale=e.medico
	JOIN Visita as v on v.id_visita=e.id_visita
	JOIN Paziente as p on p.cod_fiscale=v.paziente
WHERE p.cod_fiscale IN 
(
	SELECT query5_aux_solo_giudiziosi.cod_fiscale
	FROM query5_aux_solo_giudiziosi
);

/*QUERY 6 Mostra la spesa totale di ogni cliente,  mostrando cognome e nome del cliente, il codice
      fiscale e la sua spesa, ordinati per cognome e nome.*/
CREATE VIEW query6 AS
SELECT p.nome, p.cognome, p.cod_fiscale, totali(p.cod_fiscale)
FROM Paziente AS p
ORDER BY p.cognome, p.nome ASC;
