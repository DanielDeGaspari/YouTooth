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
