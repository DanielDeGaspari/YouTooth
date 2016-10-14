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
